<?php

namespace Qalis\Certification\Shared\Infrastructure\Persistence\SchemeEntityFields;

use function Lambdish\Phunctional\map;

use Illuminate\Support\Facades\DB;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldsResponse;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;
use Qalis\Certification\Shared\Domain\Entities\EntityCode;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldReadRepository;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldResponse;

use Qalis\Shared\Domain\ValueObjects\DateValueObject;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Entity;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Scheme;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\SchemeEntityField as SchemeEntityFieldEloquent;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use stdClass;

class LaravelSchemeEntityFieldReadRepository implements SchemeEntityFieldReadRepository {

    
    public function find(SchemeEntityFieldId ...$entityFieldIds): SchemeEntityFieldsResponse
    {
        $entityFieldIds = map(static fn(SchemeEntityFieldId $entityFieldId) => $entityFieldId->binValue(), $entityFieldIds);

        $schemeEntityFieldRecords = QueryBuilderUtils::notDeleted(DB::table('entity_fields')
        ->join('entities', 'entities.id', 'entity_fields.entity_id'))
        ->whereIn('entity_fields.uuid', $entityFieldIds)
        ->select(
            DB::raw('lower(hex(entity_fields.uuid)) uuid'),
            'entities.name as entity_name',
            'entity_fields.name as name',
            'entity_fields.label as label',
            'entity_fields.type as type',
            'entity_fields.string_values',
            'entity_fields.length',
        )
        ->get();

        return new SchemeEntityFieldsResponse(...map($this->toResponse(), $schemeEntityFieldRecords));
    }

    public function search(EntityCode $entityCode, DateValueObject $date, SchemeId ...$schemeIds): SchemeEntityFieldsResponse
    {
        $schemeIds = map(static fn(SchemeId $schemeId) => $schemeId->value(), $schemeIds);
        $schemeRealIds = Scheme::whereUuid($schemeIds)->pluck('id')->toArray();

        $entity = Entity::where('code', $entityCode->value())->first();

        if (!$entity) 
            return new SchemeEntityFieldsResponse(...map($this->toResponse(), []));

        $entityFieldIds = $entity->fields()->pluck('id')->toArray();

        $schemeEntityFieldRecords = QueryBuilderUtils::notDeleted(DB::table('entity_fields')
            ->join('scheme_entity_fields', 'scheme_entity_fields.entity_field_id', 'entity_fields.id')
            ->join('entities', 'entities.id', 'entity_fields.entity_id'))
            ->whereDate('scheme_entity_fields.start_date', '<=', $date->__toString())
            ->whereIn('scheme_id', $schemeRealIds)
            ->where(function ($query) use($date) {
                $query->whereDate('scheme_entity_fields.end_date', '>', $date->__toString())
                    ->orWhereNull('scheme_entity_fields.end_date');
            })
            ->whereIn('entity_field_id', $entityFieldIds)
            ->select(
                DB::raw('lower(hex(entity_fields.uuid)) uuid'),
                'entities.name as entity_name',
                'entity_fields.name as name',
                'entity_fields.label as label',
                'entity_fields.type as type',
                'entity_fields.string_values',
                'entity_fields.length',
            )
            ->distinct()
            ->get();

        return new SchemeEntityFieldsResponse(...map($this->toResponse(), $schemeEntityFieldRecords));
    }

    private function toResponse(): callable
    {
        return static fn(stdClass $entityField) => new SchemeEntityFieldResponse(
            $entityField->uuid,
            $entityField->entity_name,
            $entityField->name,
            $entityField->label,
            $entityField->type,
            $entityField->string_values ? json_decode($entityField->string_values) : null,
            $entityField->length,
            null,
            null
        );
    }

}
