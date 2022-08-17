<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Query;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;
use Qalis\Shared\Domain\ValueObjects\Uuid;

final class LocalQueryMonitor implements QueryMonitor {

    public function __invoke(string $query, array $params): void
    {
        try {
            if (config('app.APM_ENABLED')) {
                Session::push('APM',
                    [
                        "insert into query_monitor_records(uuid, user_id, query, params, created_at) VALUES (?,?,?,?,?)",
                        [hex2bin(Uuid::generateString()), Auth::check() ? Auth::user()->id : null, $query, empty($params) ? '[]' : json_encode($params), now()->format('Y-m-d H:i:s.u')]
                    ]);
            }
        } catch (Exception $e) {

        }
    }

}
