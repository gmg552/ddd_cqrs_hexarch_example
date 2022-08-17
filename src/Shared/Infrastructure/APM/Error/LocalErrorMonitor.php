<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Error;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;
use Qalis\Shared\Domain\ValueObjects\Uuid;

final class LocalErrorMonitor implements ErrorMonitor {

    public function __invoke(string $type, array $trace): void
    {
        try {
            if (config('app.APM_ENABLED')) {
                Session::push('APM',
                    [
                        "insert into error_monitor_records(uuid, user_id, type, trace, created_at) VALUES (?,?,?,?,?)",
                        [hex2bin(Uuid::generateString()), Auth::check() ? Auth::user()->id : null, $type, empty($trace) ? '[]' : json_encode($trace), now()->format('Y-m-d H:i:s.u')]
                    ]);
            }
        } catch (Exception $e) {

        }
    }

}
