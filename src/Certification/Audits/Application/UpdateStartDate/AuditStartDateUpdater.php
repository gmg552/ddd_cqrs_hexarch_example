<?php

namespace Qalis\Certification\Audits\Application\UpdateStartDate;

use InvalidArgumentException;
use Qalis\Certification\AuditedSchemes\Domain\AuditedSchemeRepository;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\OperatorSchemes\Domain\CheckIfAuditableSchemeIsCancelled\AuditableSchemeIsCancelledChecker;
use Qalis\Certification\CropIterations\Domain\CropIterationReadRepository;
use Qalis\Certification\CroppedAreaIterations\Domain\CroppedAreaIterationReadRepository;
use Qalis\Certification\HandlingUnitIterations\Domain\HandlingUnitIterationReadRepository;
use Qalis\Certification\OperatorIterations\Domain\OperatorIterationReadRepository;
use Qalis\Certification\SchemeOrders\Domain\SchemeOrder;
use Qalis\Certification\SchemeOrders\Domain\SchemeOrderRepository;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Shared\Domain\Criteria\Criteria;
use Qalis\Shared\Domain\Criteria\Filter;
use Qalis\Shared\Domain\Criteria\FilterExpression;
use Qalis\Shared\Domain\Criteria\FilterField;
use Qalis\Shared\Domain\Criteria\FilterOperator;
use Qalis\Shared\Domain\Criteria\FilterValue;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;
use RuntimeException;

class AuditStartDateUpdater {

    private AuditRepository $auditRepository;
    private SchemeOrderRepository $schemeOrderRepository;
    private Audit $audit;
    private SchemeOrder $schemeOrder;
    private AuditedSchemeRepository $auditedSchemeRepository;
    private CroppedAreaIterationReadRepository $croppedAreaIterationReadRepository;
    private OperatorIterationReadRepository $operatorIterationReadRepository;
    private CropIterationReadRepository $cropIterationReadRepository;
    private HandlingUnitIterationReadRepository $handlingUnitIterationReadRepository;
    private AuditableSchemeIsCancelledChecker $auditableSchemeIsCancelledChecker;

    public function __construct(
        AuditRepository $auditRepository,
        SchemeOrderRepository $schemeOrderRepository,
        AuditedSchemeRepository $auditedSchemeRepository,
        CroppedAreaIterationReadRepository $croppedAreaIterationReadRepository,
        OperatorIterationReadRepository $operatorIterationReadRepository,
        CropIterationReadRepository $cropIterationReadRepository,
        HandlingUnitIterationReadRepository $handlingUnitIterationReadRepository,
        AuditableSchemeIsCancelledChecker $auditableSchemeIsCancelledChecker
    )
    {
        $this->auditRepository = $auditRepository;
        $this->schemeOrderRepository = $schemeOrderRepository;
        $this->auditedSchemeRepository = $auditedSchemeRepository;
        $this->croppedAreaIterationReadRepository = $croppedAreaIterationReadRepository;
        $this->operatorIterationReadRepository = $operatorIterationReadRepository;
        $this->cropIterationReadRepository = $cropIterationReadRepository;
        $this->handlingUnitIterationReadRepository = $handlingUnitIterationReadRepository;
        $this->auditableSchemeIsCancelledChecker = $auditableSchemeIsCancelledChecker;
    }

    public function __invoke(UpdateStartDateCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->auditRepository->find(new AuditId($command->auditId()));

        $this->ensureSchemeIsNotCancelled($this->audit->operatorId()->value(), $this->audit->baseSchemeId()->value(), $this->audit->startDate());
        $this->ensureNewDateNoChangeIterations($command->startDate(), $this->audit);

        //hacemos el set
        $this->audit->updateStartDate(new DateValueObject($command->startDate()));

        //rescatamos el schemeOrder en base a la fecha
        $this->schemeOrder = $this->schemeOrderRepository->findBeforeAudit(new AuditId($command->auditId()), $this->audit->startDate());

        //actualizamos el schemeOrder
        $this->audit->updateSchemeOrderId($this->schemeOrder->id());

        //guardamos
        $this->auditRepository->update($this->audit);
    }

    private function ensureSchemeIsNotCancelled($operatorId, $auditSchemeId, $currentStartDate) {
        if (!$currentStartDate) {
            if ($this->auditableSchemeIsCancelledChecker->__invoke($operatorId, $auditSchemeId)) {
                throw new RuntimeException("No se puede asignar fecha a una auditoría si el operador ha dado de baja el esquema");
            }
        }
    }

    private function ensureNewDateNoChangeIterations($startDate, $audit) {

        $auditedSchemes = $this->auditedSchemeRepository->searchByCriteria(
            new Criteria(
                new FilterExpression(
                    new Filter(
                        new FilterField('Audit','id'),
                        new FilterOperator(FilterOperator::EQUAL),
                        new FilterValue($audit->id())
                    )
                )
            )
        );

        //limpiamos formato fecha
        $startDate = date("Y-m-d", strtotime($startDate));

        foreach($auditedSchemes as $auditedScheme) {

            if ($auditedScheme->croppedAreaIterationId()) {
                $croppedAreaIterationId = $this->croppedAreaIterationReadRepository->searchIterationIdForAuditedScheme($startDate, $auditedScheme->orderedSchemeId());
                if ($auditedScheme->croppedAreaIterationId() != $croppedAreaIterationId) {
                    throw new InvalidArgumentException("No se puede cambiar la fecha de inicio a <$startDate> ya que cambiaría la iteración de superficies de cultivo asociada para alguno de los esquemas");
                }
            }

            if ($auditedScheme->operatorIterationId()) {
                $operatorIterationId = $this->operatorIterationReadRepository->searchIterationIdForAuditedScheme($startDate, $auditedScheme->orderedSchemeId());
                if ($auditedScheme->operatorIterationId() != $operatorIterationId) {
                    throw new InvalidArgumentException("No se puede cambiar la fecha de inicio a <$startDate> ya que cambiaría la iteración de productores usada para alguno de los esquemas");
                }
            }

            if ($auditedScheme->cropIterationId()) {
                $cropIterationId = $this->cropIterationReadRepository->searchIterationIdForAuditedScheme($startDate, $auditedScheme->orderedSchemeId());
                if ($auditedScheme->cropIterationId() != $cropIterationId) {
                    throw new InvalidArgumentException("No se puede cambiar la fecha de inicio a <$startDate> ya que cambiaria la iteración de datos de cultivo usada para alguno de los esquemas");
                }
            }

            if ($auditedScheme->handlingUnitIterationId()) {
                $handlingIterationId = $this->handlingUnitIterationReadRepository->searchIterationIdForAuditedScheme($startDate, $auditedScheme->orderedSchemeId());
                if ($auditedScheme->handlingUnitIterationId() != $handlingIterationId) {
                    throw new InvalidArgumentException("No se puede cambiar la fecha de inicio a <$startDate> ya que cambiaria la iteración de unidades de manipulación usada para alguno de los esquemas");
                }
            }

        }

    }

}
