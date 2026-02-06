<?php

namespace App\Livewire;

use App\Models\Attachment;
use App\Models\AttachmentID;
use App\Models\Weapon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Codes extends Component
{
    public const ALPHABET = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';

    public $attachmentId;

    public $codeInput = '';

    public $notesInput = null;

    public ?string $currentType = null;

    public array $typesWithCounts = [];

    public array $skippedIds = [];

    public string $attachmentSearchInput = '';

    public array $attachedWeaponIds = [];

    public array $types = [
        'barrel',
        'comb',
        'fire mods',
        'laser',
        'magazine',
        'muzzle',
        'optic',
        'rear grip',
        'stock',
        'underbarrel',
    ];

    public function mount()
    {
        $this->nextAttachment();
    }

    public function calculateTypesWithCounts()
    {
        $this->typesWithCounts = Attachment::all()
            ->groupBy('type')
            ->map(function ($group) {
                $empty = $group->whereNull('code_base34')->count();
                $filled = $group->whereNotNull('code_base34')->count();
                $total = $group->count();
                return [
                    'empty' => $empty,
                    'filled' => $filled,
                    'total' => $total,
                    'percent_complete' => $total > 0 ? round(($filled / $total) * 100, 2) : 0,
                ];
            })
            ->toArray();

        // Select the first type with <100% complete
        if (! $this->currentType) {
            foreach ($this->typesWithCounts as $type => $counts) {
                if ($counts['percent_complete'] < 100) {
                    $this->currentType = $type;
                    break;
                }
            }
        }

        return $this->typesWithCounts;
    }

    public function setType($type)
    {
        $this->currentType = $type;
        $this->nextAttachment();
    }

    public function setAttachment($attachmentId)
    {
        $this->attachmentId = $attachmentId;

        unset($this->attachment);

        $this->codeInput = $this->attachment?->code_base34 ?? '';
        $this->notesInput = $this->attachment?->notes;

        unset($this->decoded);
        unset($this->attachmentsCode);

        $this->attachedWeaponIds = $this->attachment?->weapons->pluck('id')->toArray() ?? [];
    }

    #[Computed]
    public function allAttachments()
    {
        return Attachment::where('type', $this->currentType)
            ->where(function ($query) {
                $query->where('label', 'like', '%' . $this->attachmentSearchInput . '%')
                    ->orWhere('name', 'like', '%' . $this->attachmentSearchInput . '%');
            })
            ->orderBy('name')->orderBy('label')->get();
    }

    #[Computed]
    public function allWeapons()
    {
        return Weapon::with('attachments')->orderBy('type')->orderBy('name')->get();
    }

    #[Computed]
    public function attachedWeapons()
    {
        return $this->attachment?->weapons;
    }

    private function nextAttachment()
    {
        $this->calculateTypesWithCounts();

        // Get an attachment from the database that has no code
        $this->attachmentId = Attachment::whereNull('code_base34')
            ->where('type', $this->currentType)
            ->whereNotIn('id', $this->skippedIds)
            ->get()
            ->sortBy(function ($attachment) {
                return $attachment->weaponUnlock->type . $attachment->weaponUnlock->name;
            })
            ->first()
        ?->id;

        $this->setAttachment($this->attachmentId);
    }

    #[Computed]
    public function attachment()
    {
        return Attachment::find($this->attachmentId);
    }

    public function skip()
    {
        $this->skippedIds[] = $this->attachmentId;
        $this->nextAttachment();
    }

    public function saveAndNext()
    {
        $attachment = $this->attachment;

        if (! $attachment) {
            return;
        }

        if (empty($this->attachmentsCode()) || empty($this->decoded)) {
            // Sync attached weapons and notes all the time
            $attachment->update([
                'notes' => $this->notesInput,
            ]);
            $attachment->weapons()->sync($this->attachedWeaponIds);

            // If we don't mark this as skipped, it'll just keep showing up as the next attachment since it has no code
            $this->skippedIds[] = $attachment->id;
        } else {
            $attachment->code_base34 = $this->attachmentsCode();
            $attachment->code_base10 = $this->base34ToBase10($this->attachmentsCode, self::ALPHABET);
            $attachment->notes = $this->notesInput;
            $attachment->save();

            // Sync attached weapons
            $attachment->weapons()->sync($this->attachedWeaponIds);
        }

        // Get next attachment
        $this->nextAttachment();
    }

    #[Computed]
    public function decoded()
    {
        return $this->base34ToBase10($this->attachmentsCode, self::ALPHABET);
    }

    public function render()
    {
        return view('livewire.codes',
            [
                'attachments' => $this->allAttachments,
            ]);
    }

    #[Computed]
    public function attachmentsCode()
    {
        // Code without weapon prefix and hyphens, and removing the final 1
        $code = strtoupper($this->codeInput);
        if (preg_match('/^(?:(\w\d\d)-)?([\-1-9A-NP-Z]+)1$/', $code, $matches)) {
            return trim(str_replace('-', '', $matches[2]));
        }

        return null;
    }

    #[Computed]
    public function attachmentsCodeIdExists()
    {
        if (! $this->attachmentsCode()) {
            return null;
        }

        return AttachmentID::where('base_34', $this->attachmentsCode())->exists();
    }

    #[Computed]
    public function isValid(): bool
    {
        return $this->decoded > 0 && strlen($this->attachmentsCode) > 0;
    }

    #[Computed]
    public function isDuplicate(): bool
    {
        if (trim($this->attachmentsCode() ?? '') == '') {
            return false;
        }
        return Attachment::where('code_base34', $this->attachmentsCode())
            ->exists();
    }

    public function cloneAttachment()
    {
        if (! $this->attachment()) {
            return;
        }

        $cloned = $this->attachment()->replicate();
        $cloned->code_base34 = null;
        $cloned->code_base10 = null;
        $cloned->notes = null;
        $cloned->save();

        $this->setAttachment($cloned->id);
    }

    public function base34ToBase10($encoded): string
    {
        // Cast to string first, before any operations
        $encoded = (string) trim(strtoupper($encoded ?? ''));

        $alphabet = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $base = '34';

        $result = '0';
        $length = strlen($encoded);

        for ($i = 0; $i < $length; $i++) {
            $char = $encoded[$i];
            $value = strpos($alphabet, $char);

            if ($value === false) {
                throw new InvalidArgumentException("Invalid character in base34 string: {$char}");
            }

            // result = result * base + value
            $result = bcadd(bcmul($result, $base, 0), (string) $value, 0);
        }

        return $result;
    }
}
