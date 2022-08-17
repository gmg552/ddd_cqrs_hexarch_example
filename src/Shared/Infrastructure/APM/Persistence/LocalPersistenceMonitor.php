<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Persistence;

use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Session;
use Qalis\Shared\Domain\ValueObjects\Uuid;

final class LocalPersistenceMonitor implements PersistenceMonitor {

    public function __invoke(string $statement, array $bindings, float $time): void
    {
        try {
            if (config('app.APM_ENABLED')) {
                $bindings = $this->convertHexToBinBindings($bindings);
                Session::push('APM',
                    [
                        "insert into persistence_monitor_records(uuid, user_id, sentence, bindings, time, created_at) VALUES (?,?,?,?,?,?)",
                        [hex2bin(Uuid::generateString()), Auth::check() ? Auth::user()->id : null, addslashes($statement), empty($bindings) ? '[]' : json_encode($bindings), $time, now()->format('Y-m-d H:i:s.u')]
                    ]);
            }
        } catch (Exception $e) {

        }

    }

    private function convertHexToBinBindings($bindings) {
        foreach ($bindings as $key => $binding) {
            if ($this->isBinary($binding)) $bindings[$key] = bin2hex($binding);
        }
        return $bindings;
    }

    private function isBinary($value): bool
    {
        return false === mb_detect_encoding((string)$value, null, true);
    }

}
