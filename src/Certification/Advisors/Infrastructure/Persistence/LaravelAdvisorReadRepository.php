<?php

namespace Qalis\Certification\Advisors\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Qalis\Certification\Advisors\Application\Search\SearchAdvisorResponse;
use Qalis\Certification\Advisors\Application\Search\SearchAdvisorsResponse;
use Qalis\Certification\Advisors\Domain\AdvisorReadRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use stdClass;
use function Lambdish\Phunctional\map;

final class LaravelAdvisorReadRepository implements AdvisorReadRepository {

    public function search(): SearchAdvisorsResponse
    {
        $advisors = QueryBuilderUtils::notDeleted(DB::table('advisors')
            ->join('subjects', 'advisors.id', '=', 'subjects.id'))
            ->selectRaw('lower(hex(advisors.uuid)) as id, subjects.name')
            ->get();

        return new SearchAdvisorsResponse(...map($this->toAdvisorResponse(), $advisors));

    }

    private function toAdvisorResponse(): callable
    {
        return static fn(stdClass $advisor) => new SearchAdvisorResponse(
            $advisor->id,
            $advisor->name
        );
    }

}
