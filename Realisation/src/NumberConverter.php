<?php

namespace App;

use App\ConverterInterface;
use App\FormatterTrait;

class NumberConverter implements ConverterInterface {
    use FormatterTrait;

    private int $number;

    public function __construct(int $number) {
        $this->number = $number;
    }

    public function toDecimal(): int {
        return $this->number;
    }

    public function toBinary(): string {
        return decbin($this->number);
    }

    // public function toBinary(): string {
    //     if ($this->number === 0) return "0";

    //     $num = $this->number;
    //     $bits = [];

    //     while ($num > 0) {
    //         $bits[] = (string)($num % 2);
    //         $num = intdiv($num, 2);
    //     }

    //     return implode('', array_reverse($bits));
    // }

    public function toHexa(): string {
        return strtoupper(dechex($this->number));
    }

    // public function toHexa(): string {
    //     if ($this->number === 0) return "0";

    //     $num = $this->number;
    //     $digits = "0123456789ABCDEF";
    //     $hex = [];

    //     while ($num > 0) {
    //         $remainder = $num % 16;
    //         $hex[] = $digits[$remainder];
    //         $num = intdiv($num, 16);
    //     }

    //     return implode('', array_reverse($hex));
    // }


    public function shiftLeft(int $bits): int {
        return $this->number << $bits;
    }

    public function shiftRight(int $bits): int {
        return $this->number >> $bits;
    }
}
