<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Customers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Qalis\Shared\Domain\Customers\CustomerRepository;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;

class LaravelCustomerRepository implements CustomerRepository
{

    public function makeCustomer(string $subjectId): void
    {
        $subjectPK = Uuid2Id::resolve('subjects', $subjectId);

        DB::table('customers')
            ->updateOrInsert([
                'id' => $subjectPK,
                'uuid' => hex2bin($subjectId)
            ], [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

    }

}
