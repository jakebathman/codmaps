<?php

namespace App\Models;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Weapon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expected_attachment_counts' => AsArrayObject::class,
    ];

    public function attachments(): BelongsToMany
    {
        return $this->belongsToMany(Attachment::class)->withPivot('order')->orderBy('order');
    }
}
