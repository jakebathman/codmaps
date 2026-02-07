<?php

namespace App\Models;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Weapon extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expected_attachment_counts' => AsArrayObject::class,
    ];

    public function expectedAttachmentTotal(): int
    {
        return array_sum(
            array_filter(
                $this->expected_attachment_counts->toArray(),
                fn($key) => $key !== 'total' && $key !== 'complete',
                ARRAY_FILTER_USE_KEY
            )
        );
    }

    public function attachmentProgress(): float
    {
        if (! isset($this->expected_attachment_counts['total']) || $this->expected_attachment_counts['total'] == 0) {
            return 0.0;
        }

        return ($this->attachments->count() / ($this->expectedAttachmentTotal() ?? 0)) * 100.0;
    }

    public function attachments(): BelongsToMany
    {
        return $this->belongsToMany(Attachment::class)->withPivot('order')->orderBy('order');
    }
}
