<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\Run;

use Qalis\Certification\IteratedCroppedAreas\Domain\MassCreate\MassIteratedCroppedAreaCreatorCommandConnector;
use Qalis\Certification\IteratedOperators\Domain\MassCreate\MassIteratedOperatorCreatorCommandConnector;
use Qalis\Certification\SpreadsheetFormats\Domain\Load\SpreadsheetFormatDataLoader;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttempt;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptId;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptRepository;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptState;
use Qalis\Shared\Domain\BatchCommandErrors\BatchCommandError;
use Qalis\Shared\Domain\BatchCommandErrors\BatchCommandErrorRepository;
use Qalis\Shared\Domain\BatchCommandErrors\BatchCommandErrorsResponse;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\Create\SpreadsheetBatchCommandAttemptCreator;
use Qalis\Shared\Domain\SpreadsheetBatchCommands\SpreadsheetBatchCommandReadRepository;
use Qalis\Shared\Domain\Users\FindAuthenticatedUser\AuthenticatedUserFinder;
use Qalis\Shared\Domain\Users\UserReadRepository;
use Qalis\Shared\Domain\ValueObjects\Uuid;

final class SpreadsheetBatchCommandRunner {

    public const MASS_ITERATED_OPERATOR_CREATOR_CODE = 'MassIteratedOperatorCreator';
    public const MASS_ITERATED_CROPPED_AREA_CREATOR_CODE  = 'MassIteratedCroppedAreaCreator';

    private SpreadsheetBatchCommandReadRepository $spreadsheetBatchCommandReadRepository;
    private SpreadsheetFormatDataLoader $spreadsheetFormatDataLoader;
    private BatchCommandAttemptRepository $batchCommandAttemptRepository;
    private MassIteratedOperatorCreatorCommandConnector $massIteratedOperatorCreatorCommandConnector;
    private MassIteratedCroppedAreaCreatorCommandConnector $massIteratedCroppedAreaCreatorCommandConnector;
    private BatchCommandErrorRepository $batchCommandErrorRepository;
    private AuthenticatedUserFinder $authenticatedUserFinder;
    private SpreadsheetBatchCommandAttemptCreator $spreadsheetBatchCommandAttemptCreator;

    public function __construct(
        SpreadsheetBatchCommandReadRepository $spreadsheetBatchCommandReadRepository,
        SpreadsheetFormatDataLoader $spreadsheetFormatDataLoader,
        BatchCommandAttemptRepository $batchCommandAttemptRepository,
        MassIteratedOperatorCreatorCommandConnector $massIteratedOperatorCreatorCommandConnector,
        BatchCommandErrorRepository $batchCommandErrorRepository,
        AuthenticatedUserFinder $authenticatedUserFinder,
        SpreadsheetBatchCommandAttemptCreator $spreadsheetBatchCommandAttemptCreator,
        MassIteratedCroppedAreaCreatorCommandConnector $massIteratedCroppedAreaCreatorCommandConnector
    )
    {
        $this->spreadsheetBatchCommandReadRepository = $spreadsheetBatchCommandReadRepository;
        $this->spreadsheetFormatDataLoader = $spreadsheetFormatDataLoader;
        $this->batchCommandAttemptRepository = $batchCommandAttemptRepository;
        $this->massIteratedOperatorCreatorCommandConnector = $massIteratedOperatorCreatorCommandConnector;
        $this->batchCommandErrorRepository = $batchCommandErrorRepository;
        $this->authenticatedUserFinder = $authenticatedUserFinder;
        $this->spreadsheetBatchCommandAttemptCreator = $spreadsheetBatchCommandAttemptCreator;
        $this->massIteratedCroppedAreaCreatorCommandConnector = $massIteratedCroppedAreaCreatorCommandConnector;
    }

    public function __invoke(string $spreadsheetFormatId, string $fileContent, string $originalFileName, array $contextParams): void
    {

        $spreadsheetBatchCommandResponse = $this->spreadsheetBatchCommandReadRepository->findToRun($spreadsheetFormatId);
        $commandParams = $this->spreadsheetFormatDataLoader->__invoke($fileContent, $spreadsheetBatchCommandResponse->sheetName(), $spreadsheetBatchCommandResponse->firstRow(), $spreadsheetBatchCommandResponse->columnIndexes(), $spreadsheetBatchCommandResponse->protocol());

        if ((strtolower($spreadsheetBatchCommandResponse->code())) === (strtolower(self::MASS_ITERATED_OPERATOR_CREATOR_CODE))) {

            $this->massIteratedOperatorCreatorCommandConnector->__invoke($contextParams, $commandParams, $spreadsheetBatchCommandResponse->paramMatching());
            $errors = $this->massIteratedOperatorCreatorCommandConnector->errors();

        } elseif ((strtolower($spreadsheetBatchCommandResponse->code())) === (strtolower(self::MASS_ITERATED_CROPPED_AREA_CREATOR_CODE))) {

            $this->massIteratedCroppedAreaCreatorCommandConnector->__invoke($contextParams, $commandParams, $spreadsheetBatchCommandResponse->paramMatching(), $spreadsheetBatchCommandResponse->customFieldParamMatching());
            $errors = $this->massIteratedCroppedAreaCreatorCommandConnector->errors();

        } else {
            throw new \InvalidArgumentException("No se reconoce el código de comando por lotes.");
        }

        $this->applyOffsetToErrorIndex($spreadsheetBatchCommandResponse->firstRow(), $errors);
        $batchCommandAttemptId = $this->createSpreadsheetBatchCommandAttempt($spreadsheetBatchCommandResponse->batchCommandId(), $spreadsheetBatchCommandResponse->code(), $fileContent, $originalFileName, $contextParams);
        $this->saveErrors($errors, $batchCommandAttemptId);
        $this->closeBatchCommandAttempt($batchCommandAttemptId, $errors);

    }

    private function applyOffsetToErrorIndex(int $firstRow, &$errors) {
        foreach($errors as $error) {
            if ($error->index() !== null) {
                $error->updateIndex($error->index()+($firstRow-1));
            }
        }
    }

    private function closeBatchCommandAttempt(BatchCommandAttemptId $batchCommandAttemptId, BatchCommandErrorsResponse $errors) : void {
        $batchCommandAttempt = $this->batchCommandAttemptRepository->find($batchCommandAttemptId);
        $batchCommandAttempt->updateState(new BatchCommandAttemptState($errors->count()  ? BatchCommandAttemptState::FAILED : BatchCommandAttemptState::SUCCESSFUL));
        $this->batchCommandAttemptRepository->save($batchCommandAttempt);
    }

    private function saveErrors(BatchCommandErrorsResponse $errors, BatchCommandAttemptId $batchCommandAttemptId) : void {
        foreach($errors as $error) {
            $batchCommandError = BatchCommandError::fromPrimitives(
                Uuid::generateString(),
                $batchCommandAttemptId,
                $error->message(),
                $error->index(),
                $error->howToFix()
            );
            $this->batchCommandErrorRepository->save($batchCommandError);
        }
    }

    private function createSpreadsheetBatchCommandAttempt(string $batchCommandId, string $commandCode, string $fileContent, string $originalFileName, array $contextParams): BatchCommandAttemptId {

        $batchCommandAttemptId = Uuid::generateString();
        $authenticatedUser = $this->authenticatedUserFinder->__invoke();

        $this->spreadsheetBatchCommandAttemptCreator->__invoke($batchCommandId, $batchCommandAttemptId, $commandCode, $authenticatedUser->id(), $fileContent, $originalFileName, $contextParams);

        return new BatchCommandAttemptId($batchCommandAttemptId);

    }

}
