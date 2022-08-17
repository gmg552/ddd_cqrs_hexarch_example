<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;
use Qalis\Shared\Domain\ValueObjects\Uuid;

final class LocalLogger implements Logger {


    public function __invoke(string $level, string $message, array $tags = null, array $bindings = null): void
    {
        try {
            if (config('app.APM_ENABLED')) {
                Session::push('APM',
                    [
                        "insert into log_records(uuid, user_id, level, message, bindings, tags, created_at) VALUES (?,?,?,?,?,?,?)",
                        [hex2bin(Uuid::generateString()), Auth::check() ? Auth::user()->id : null, $level, $message, empty($bindings) ? '[]' : json_encode($bindings), empty($tags) ? '[]' : json_encode($tags), now()->format('Y-m-d H:i:s.u')]
                    ]);
            }
        } catch (Exception $e) {

        }
    }
}
