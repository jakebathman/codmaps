<?php

namespace App\Console\Commands;

use App\Models\Attachment;
use Illuminate\Console\Command;

class FixBase10AttachmentsTable extends Command
{
    protected $signature = 'app:fix-base10-attachments-table';

    protected $description = 'Fix improper base 10 values in attachments table';

    public function handle()
    {
        Attachment::chunk(100, function ($attachments) {
            foreach ($attachments as $attachment) {
                $base34 = $attachment->code_base34;
                if ($base34) {
                    $base10 = $this->convertBase34ToBase10($base34);
                    if ($attachment->code_base10 != $base10) {
                        $this->info("Updating attachment ID {$attachment->id}: base34={$base34}, old_base10={$attachment->code_base10}, new_base10={$base10}");
                        $attachment->code_base10 = $base10;
                        $attachment->save();
                    }
                }
            }
        });
    }

    public function convertBase34ToBase10(string $base34): int | string | null
    {
        $alphabet = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        try {
            $base34 = strtoupper(trim($base34));
            $base = strlen($alphabet);

            $map = [];
            for ($i = 0; $i < $base; $i++) {
                $map[$alphabet[$i]] = $i;
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
