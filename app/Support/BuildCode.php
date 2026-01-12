<?php

namespace App\Support;

use InvalidArgumentException;

class BuildCode
{
    private ?int $base10 = null;

    public const ALPHABET = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';

    public function __construct(public string $base34)
    {}

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

    private function convertBase34ToBase10(string $base34): ?int
    {
        try {
            $base34 = strtoupper(trim($base34));
            $base = strlen(self::ALPHABET);

            $map = [];
            for ($i = 0; $i < $base; $i++) {
                $map[self::ALPHABET[$i]] = $i;
            }

            $number = 0;

            for ($i = 0; $i < strlen($base34); $i++) {
                $ch = $base34[$i];
                if (! isset($map[$ch])) {
                    throw new InvalidArgumentException("Invalid character '$ch' in code");
                }

                $value = $map[$ch];

                $number = ($number * $base) + $value;
            }

            if ($number === 0) {
                return null;
            }

            return $number;
        } catch (\Throwable $e) {
            return null;
        }
    }

}
