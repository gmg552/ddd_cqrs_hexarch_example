<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Command;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;
use Qalis\Shared\Domain\ValueObjects\Uuid;

final class LocalCommandMonitor implements CommandMonitor {

    public function __invoke(string $command, array $params): void
    {
        try {
            if (config('app.APM_ENABLED')) {
                Session::push('APM',
                    [
                        "insert into command_monitor_records(uuid, user_id, command, params, created_at) VALUES (?,?,?,?,?)",
                        [hex2bin(Uuid::generateString()), Auth::check() ? Auth::user()->id : null, $command, empty($params) ? '[]' : json_encode($params), now()->format('Y-m-d H:i:s.u')]
                    ]);
            }
        } catch (Exception $e) {

        }
    }

}
