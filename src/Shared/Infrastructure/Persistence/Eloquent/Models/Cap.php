<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cap extends Model
{
    protected $table = "caps";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function correctiveActions() {
        return $this->hasMany(CorrectiveAction::class);
    }

}
