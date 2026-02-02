<?php

namespace Database\Seeders;

use App\Models\AttachmentID;
use Illuminate\Database\Seeder;

class AttachmentIDSeeder extends Seeder
{
    public function run(): void
    {
        for ($n = 1; $n <= 60; $n++) {
            foreach ([1, 3, 5, 7] as $k) {
                // 17 * k * 2^n
                $base10 = bcmul(bcmul('17', (string) $k), bcpow('2', (string) $n));
                if (AttachmentID::where('base_10', $base10)->exists()) {
                    continue;
                }
                try {
                    $base34 = $this->base10ToBase34($base10);
                } catch (\Throwable $e) {
                    dd($e->getMessage(), $n, $k, $base10);
                }
                AttachmentID::create([
                    'base_10' => $base10,
                    'base_34' => $base34,
                    'binary' => $this->largeDecToBin($base10),
                    'k' => $k,
                    'n' => $n,
                ]);
            }
        }
    }

    public function base10ToBase34(int | string $number): string
    {
        $alphabet = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';

        if (! is_int($number)) {
            $number = (string) $number; // handle big ints
        }

        if ($number === 0 || $number === '0') {
            return $alphabet[0];
        }

        $base = strlen($alphabet);
        $chars = [];

        while ($number > 0) {
            $remainder = $number % $base;
            $chars[] = $alphabet[$remainder];
            $number = bcdiv((string) $number, (string) $base, 0);
        }

        return implode('', array_reverse($chars));
    }

    public function base34ToBase10($encoded): string
    {
        // Cast to string first, before any operations
        $encoded = (string) $encoded;

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

    public function largeDecToBin(int | string $number): string
    {
        $number = (string) $number;
        if ($number === '0') {
            return '0';
        }

        $binary = '';

        while (bccomp($number, '0') > 0) {
            $binary = bcmod($number, '2') . $binary;
            $number = bcdiv($number, '2', 0);
        }

        return $binary;
    }
}
