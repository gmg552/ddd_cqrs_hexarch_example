<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Config;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Domain\ConfigProvider;

class LaravelConfigProvider implements ConfigProvider
{

    public function globalgapAuditOnlineApiToken(): string
    {
        return (config('app.AOH_GG_API_ENV') == 'demo') ? strval(config('app.DEMO_AOH_GG_API_TOKEN')) : strval(config('app.PRO_AOH_GG_API_TOKEN'));
    }

    public function auditOnlineHubBaseUrl(): string
    {
        return (config('app.AOH_GG_API_ENV') == 'demo') ? strval(config('app.DEMO_AOH_GG_BASE_URL')) : strval(config('app.PRO_AOH_GG_BASE_URL'));
    }

    public function enableErrorMonitor(bool $state): void
    {
        config(['app.APM_ENABLED' => $state]);
    }

    public function systemEmailHost() : string {
        return DB::table('system_config')->first()->system_email_host;
    }

    public function systemEmailPassword() : string {
        return DB::table('system_config')->first()->system_email_password;
    }

    public function systemEmailPort() : int {
        return DB::table('system_config')->first()->system_email_port;
    }

    public function systemEmailSecurity() : string {
        return DB::table('system_config')->first()->system_email_security;
    }

    public function systemEmailSender() : string {
        return DB::table('system_config')->first()->system_email_sender;
    }

    public function systemEmailSenderName() : string {
        return DB::table('system_config')->first()->system_email_sender_name;
    }

}
