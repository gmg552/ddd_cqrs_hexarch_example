<?php

namespace Qalis\Certification\Shared\Domain\SchemeEntityFields\Search;

use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldResponse;
use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class SchemeEntityFieldsResponse extends CollectionResponse
{

   public function __construct(SchemeEntityFieldResponse ...$items)
   {
      $this->items = $items;
   }

   protected function type(): string
   {
      return SchemeEntityFieldResponse::class;
   }
}
