<?php

namespace Qalis\Shared\Domain;

interface ConfigProvider
{
    public function globalgapAuditOnlineApiToken();
    public function auditOnlineHubBaseUrl(): string;
    public function enableErrorMonitor(bool $state) : void;
}
