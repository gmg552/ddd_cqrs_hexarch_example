<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\PrivacyLevels;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\PrivacyLevels\SearchByScheme\SearchPrivacyLevelResponse;
use Qalis\Shared\Application\PrivacyLevels\SearchByScheme\SearchPrivacyLevelsResponse;
use Qalis\Shared\Domain\PrivacyLevels\PrivacyLevelReadRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use function Lambdish\Phunctional\map;
use stdClass;

class LaravelPrivacyLevelReadRepository implements PrivacyLevelReadRepository
{

    public function searchIdByCodeAndScheme(string $privacyLevelCode, string $baseSchemeId): ?string
    {
        return QueryBuilderUtils::notDeleted(DB::table('privacy_levels')
            ->join('services', 'privacy_levels.service_id', '=', 'services.id'))
            ->where('code', $privacyLevelCode)
            ->whereRaw('lower(hex(services.uuid)) = "'.$baseSchemeId.'"')
            ->selectRaw('lower(hex(privacy_levels.uuid)) as id')
            ->pluck('id')
            ->first();
    }

    public function searchByScheme(string $schemeId): SearchPrivacyLevelsResponse
    {
        $privacyLevels = QueryBuilderUtils::notDeleted(
            DB::table('privacy_levels')
                ->join('services', 'privacy_levels.service_id', '=', 'services.id'))
            ->whereRaw('lower(hex(services.uuid)) = "'.$schemeId.'"')
            ->selectRaw('lower(hex(privacy_levels.uuid)) as id, lower(hex(services.uuid)) as service_id, label, description')->get();


        return new SearchPrivacyLevelsResponse(...map($this->toResponse(), $privacyLevels));
    }

    private function toResponse(): callable
    {
        return static fn(stdClass $privacyLevel) => new SearchPrivacyLevelResponse(
            $privacyLevel->id,
            $privacyLevel->service_id,
            $privacyLevel->label,
            $privacyLevel->description
        );
    }

}
