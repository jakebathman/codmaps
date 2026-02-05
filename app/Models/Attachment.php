<?php

namespace App\Models;

use App\Models\AttachmentID;
use App\Models\Weapon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attachment extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'prestige' => 'boolean',
        ];
    }

    public function validBase34(): ?bool
    {
        if (is_null($this->code_base34)) {
            return null;
        }

        return AttachmentID::where('base_34', $this->code_base34)->count() > 0;
    }

    public function weapons(): BelongsToMany
    {
        return $this->belongsToMany(Weapon::class)->withPivot('order')->orderBy('order');
    }

    public function weaponUnlock(): HasOne
    {
        return $this->hasOne(Weapon::class, 'name', 'weapon_unlock');
    }
}
