<?php

namespace App\Support;

use App\Models\Attachment;
use App\Models\AttachmentID;
use App\Models\Weapon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class BuildCode
{
    private ?int $base10 = null;

    public const ALPHABET = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';

    public ?Weapon $weapon = null;
    public ?Collection $attachments = null;
    public string $weaponCode = '';
    public string $versionCode = '';
    public string $attachmentsCode = '';
    public array $attachmentIds = [];

    public bool $isValid = false;

    public function __construct(public string $base34)
    {
        $this->base34 = strtoupper(trim($base34));

        $this->isValid = false;

        // Parse out the weapon, version, and attachments
        if (preg_match('/^(?:(?<weapon>\w\d\d)-)?(?<attachments>[\-1-9A-NP-Z]+)(?<version>1)$/', $base34, $matches)) {
            $this->weaponCode = str_replace('-', '', $matches['weapon'] ?? '');
            $this->attachmentsCode = str_replace('-', '', $matches['attachments'] ?? '');
            $this->versionCode = $matches['version'] ?? '';

            $this->weapon = Weapon::where('code_prefix', $this->weaponCode)->first();

            $this->parseAttachmentIds();
        }
    }

    public function parseAttachmentIds(): void
    {
        // Validate attachment codes by attempting to convert to base10
        $base10 = $this->convertBase34ToBase10($this->attachmentsCode);
        if ($base10 !== null) {
            $this->base10 = $base10;
            $carry = (string) $base10;
            $lastN = null;

            $lengthFunc = \DB::connection()->getDriverName() === 'sqlite' ? 'LENGTH' : 'CHAR_LENGTH';
            foreach (AttachmentID::orderByRaw("{$lengthFunc}(base_10) DESC, base_10 DESC")->get() as $attachment) {
                // If we've already found an attachment ID for this $n, skip to the next

                if ($attachment->n == $lastN) {
                    continue;
                }
                // bcomp(a, b) is:
                //   -1 if a < b
                //    0 if a == b
                //    1 if a > b
                if (bccomp($attachment->base_10, $carry) === 0) {
                    // This is the final attachment ID, so store it and we're done
                    $this->attachmentIds[] = $attachment->base_10;
                    $carry = 0;
                    break;
                }

                if (bccomp($attachment->base_10, $carry) === -1) {
                    // This attachment ID is lower than the running ID, so subtract and store it
                    $this->attachmentIds[] = $attachment->base_10;
                    $carry = bcsub($carry, $attachment->base_10);
                    $lastN = $attachment->n;

                    // We don't want any more attachment IDs from this $n loop iteration
                }
            }
            $this->attachments = Attachment::whereIn('code_base10', $this->attachmentIds)->whereHas('weapons', function ($query) {
                $query->where('weapons.id', $this->weapon?->id);
            })->get();

            if ($carry == 0 && $this->attachments?->count() > 0) {
                $this->isValid = true;
            }

        }
    }

    public function __get(string $name)
    {
        if ($name === 'base10') {
            if (isset($this->base10)) {
                return $this->base10;
            }
            return $this->convertBase34ToBase10($this->base34);
        }

        throw new \Exception("Property '{$name}' does not exist.");
    }

    private function convertBase34ToBase10(string $base34): int | string | null
    {
        try {
            $base34 = strtoupper(trim($base34));
            $base = strlen(self::ALPHABET);

            $map = [];
            for ($i = 0; $i < $base; $i++) {
                $map[self::ALPHABET[$i]] = $i;
            }

            $number = '0';

            for ($i = 0; $i < strlen($base34); $i++) {
                $ch = $base34[$i];
                if (! isset($map[$ch])) {
                    throw new InvalidArgumentException("Invalid character '$ch' in code");
                }

                $value = $map[$ch];

                $number = bcadd(bcmul($number, (string) $base, 0), (string) $value, 0);
            }

            if ($number === '0') {
                return null;
            }

            if (bccomp($number, (string) PHP_INT_MAX, 0) <= 0) {
                return (int) $number;
            }

            return $number;
        } catch (\Throwable $e) {
            return null;
        }
    }

}
