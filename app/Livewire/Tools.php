<?php

namespace App\Livewire;

use Livewire\Component;

class Tools extends Component
{
    public string $inputA = '';
    public string $inputB = '';
    public string $operation = 'subtract';

    public function render()
    {
        return view('livewire.tools',
            [
                'resultBase10' => $this->aMinusB(),
                'resultBase34' => $this->base10ToBase34($this->aMinusB()),
            ]);
    }

    public function aMinusB(): string
    {
        if ($this->inputA === '' || $this->inputB === '') {
            return '';
        }

        $a = trim(strtoupper($this->inputA));
        $b = trim(strtoupper($this->inputB));

        try {
            if ($this->operation == 'subtract') {
                $result = bcsub($this->base34ToBase10($a), $this->base34ToBase10($b));
            } else {
                $result = bcadd($this->base34ToBase10($a), $this->base34ToBase10($b));
            }
        } catch (\Throwable $e) {
            return 'Error';
        }

        return $result;
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

}
