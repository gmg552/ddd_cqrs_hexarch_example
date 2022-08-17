<?php

namespace Qalis\Shared\Domain\AuditDecisionActions;

use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class AuditDecisionActionType extends StringValueObject
{
    public const ISSUE_CERTIFICATE = 'issue';
    public const NOT_TO_ISSUE_CERTIFICATE = 'not_to_issue';
    public const KEEP_CERTIFICATE = 'keep';
    public const SUSPEND_CERTIFICATE = 'suspend';
    public const KEEP_SUSPENSION = 'keep_suspension';
    public const REMOVE_SUSPENSION = 'remove_suspension';
    public const CANCEL_CERTIFICATE = 'cancel';
    public const DOWNGRADE = 'downgrade';

    public static function passingTypes(bool $pass) : array {
        return $pass ? [
                self::ISSUE_CERTIFICATE,
                self::KEEP_CERTIFICATE,
                self::REMOVE_SUSPENSION
            ] : [
                self::NOT_TO_ISSUE_CERTIFICATE,
                self::SUSPEND_CERTIFICATE,
                self::KEEP_SUSPENSION,
                self::CANCEL_CERTIFICATE,
                self::DOWNGRADE
            ];
    }

    public static function actionFlow(string $previousAction = null) : array {

        $actionFlow = [
            self::ISSUE_CERTIFICATE => [
                self::ISSUE_CERTIFICATE,
                self::KEEP_CERTIFICATE,
                self::SUSPEND_CERTIFICATE,
                self::CANCEL_CERTIFICATE,
                self::DOWNGRADE
            ],
            self::NOT_TO_ISSUE_CERTIFICATE => [
                    self::ISSUE_CERTIFICATE,
                    self::NOT_TO_ISSUE_CERTIFICATE
            ],
            self::KEEP_CERTIFICATE => [
                self::ISSUE_CERTIFICATE,
                self::KEEP_CERTIFICATE,
                self::SUSPEND_CERTIFICATE,
                self::CANCEL_CERTIFICATE,
                self::DOWNGRADE
            ],
            self::SUSPEND_CERTIFICATE => [
                self::KEEP_SUSPENSION,
                self::REMOVE_SUSPENSION,
                self::CANCEL_CERTIFICATE,
                self::DOWNGRADE
            ],
            self::KEEP_SUSPENSION => [
                self::KEEP_SUSPENSION,
                self::REMOVE_SUSPENSION,
                self::CANCEL_CERTIFICATE,
                self::DOWNGRADE
            ],
            self::REMOVE_SUSPENSION => [
                self::ISSUE_CERTIFICATE,
                self::KEEP_CERTIFICATE,
                self::CANCEL_CERTIFICATE
            ],
            self::CANCEL_CERTIFICATE => [
                self::ISSUE_CERTIFICATE
            ],
            self::DOWNGRADE => [
                self::ISSUE_CERTIFICATE
            ],
            null => [
                self::ISSUE_CERTIFICATE,
                self::NOT_TO_ISSUE_CERTIFICATE
            ],
        ];

        if (isset($actionFlow[$previousAction]))
            return $actionFlow[$previousAction];

        throw new \InvalidArgumentException("No se encuentra la acción <$previousAction> en el flujo de acciones de decisión");

    }

}
