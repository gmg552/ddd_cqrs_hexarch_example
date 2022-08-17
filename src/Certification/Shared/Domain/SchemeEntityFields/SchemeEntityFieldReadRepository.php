<?php

namespace Qalis\Certification\Shared\Domain\SchemeEntityFields;

use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldsResponse;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;
use Qalis\Certification\Shared\Domain\Entities\EntityCode;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;

interface SchemeEntityFieldReadRepository {
    function search(EntityCode $entityCode, DateValueObject $date, SchemeId ...$schemeIds) : SchemeEntityFieldsResponse;
    function find(SchemeEntityFieldId ...$schemeEntityFieldIds) : SchemeEntityFieldsResponse;
}
