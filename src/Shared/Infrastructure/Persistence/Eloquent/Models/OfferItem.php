<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferItem extends Model
{
    protected $table = "offer_items";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
