<?php

namespace App\Models;

use App\Models\Weapon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attachment extends Model
{
    protected $guarded = [];

    public function weapons(): BelongsToMany
    {
        return $this->belongsToMany(Weapon::class)->withPivot('order')->orderBy('order');
    }
}
