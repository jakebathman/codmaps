<?php

namespace App\Livewire;

use Livewire\Component;

class Radix extends Component
{
    public const RADIX_ALPHABET = '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'; // 35 chars, excludes O

    public $inputCode = '';
    public $codes = '';

    public function getDecodedProperty()
    {
        if (empty($this->inputCode)) {
            return null;
        }

        try {
            return $this->decode($this->inputCode, self::RADIX_ALPHABET);
        } catch (\InvalidArgumentException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function getEncodedProperty()
    {
        if (empty($this->inputCode)) {
            return null;
        }

        try {
            return $this->encode($this->decoded, self::RADIX_ALPHABET);
        } catch (\InvalidArgumentException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function getLettersProperty()
    {
        // given a string, including whitespace and line breaks, return only unique letters and digits
        $input = $this->codes;
        $letters = [];
        $input = strtoupper($input);
        for ($i = 0; $i < strlen($input); $i++) {
            $ch = $input[$i];
            if (ctype_alnum($ch) && ! in_array($ch, $letters)) {
                $letters[] = $ch;
            }
        }
        // sort letters
        sort($letters);
        return implode('', $letters);
    }

    /**
     * Encode a non-negative integer into a custom-base string.
     *
     * @param int|string $number  Arbitrary-size integer
     * @param string $alphabet    Characters used for digits
     */
    public function encode(int | string $number, string $alphabet = self::RADIX_ALPHABET): string
    {
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
            $number = intdiv($number, $base);
        }

        return implode('', array_reverse($chars));
    }

    /**
     * Decode a custom-base string back into an integer.
     *
     * @param string $code
     * @param string $alphabet
     * @return int|string  (string if the number grows large)
     */
    public function decode(string $code, string $alphabet = self::RADIX_ALPHABET): int | string
    {
        $code = strtoupper($code);
        $base = strlen($alphabet);

        // Code without weapon prefix and hyphens
        $code = preg_replace('/^(\w\d\d-)?([\S\s]+)$/', '$2', $code);
        $code = trim(str_replace('-', '', $code));

        // Build a reverse lookup map: char → value
        $map = [];
        for ($i = 0; $i < $base; $i++) {
            $map[$alphabet[$i]] = $i;
        }

        $number = 0;

        for ($i = 0; $i < strlen($code); $i++) {
            $ch = $code[$i];
            if (! isset($map[$ch])) {
                throw new InvalidArgumentException("Invalid character '$ch' in code");
            }

            $value = $map[$ch];
            $number = $number * $base + $value;
        }

        return $number;
    }

    public function render()
    {
        return view('livewire.radix');
    }
}
