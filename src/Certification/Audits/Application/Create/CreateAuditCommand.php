<?php

namespace Qalis\Certification\Audits\Application\Create;

use Qalis\Shared\Domain\Bus\Command\Command;

class CreateAuditCommand extends Command {

    private $id;
    private $operatorId;
    private $auditSchemeId;
    private $realChiefAuditorId;

    public function __construct($id, $operatorId, $auditSchemeId, $realChiefAuditorId = null) {
        $this->id = $id;
        $this->operatorId = $operatorId;
        $this->auditSchemeId = $auditSchemeId;
        $this->realChiefAuditorId = $realChiefAuditorId;
    }

    public function id() {
        return $this->id;
    }

    public function operatorId() {
        return $this->operatorId;
    }

    public function auditSchemeId() {
        return $this->auditSchemeId;
    }

    public function realChiefAuditorId() {
        return $this->realChiefAuditorId;
    }

}
