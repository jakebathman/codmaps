<?php

namespace App\Models;

use App\Models\AttachmentID;
use App\Models\Weapon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'prestige' => 'boolean',
        ];
    }

    public function formatCode(?Weapon $weapon = null): string
    {
        if (! $this->code_base34) {
            return '';
        }

        $code = $this->code_base34 . '1'; // Add version suffix

        if ($weapon) {
            // Add weapon code prefix
            return rtrim($weapon->code_prefix . '-' . chunk_split($code, 5, '-'), '-');
        }

        // Only format the attachment code part
        return rtrim(chunk_split($code, 5, '-'), '-');

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
