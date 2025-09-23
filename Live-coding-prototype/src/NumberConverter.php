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


    public function toHexa(): string {
        return strtoupper(dechex($this->number));
    }


    public function bitwiseAnd(int $value): int {
        return $this->number & $value;
    }

    public function bitwiseOr(int $value): int {
        return $this->number | $value;
    }

    public function bitwiseXor(int $value): int {
        return $this->number ^ $value;
    }

    public function bitwiseNot(): int {
        return ~$this->number;
    }

    public function shiftLeft(int $bits): int {
        return $this->number << $bits;
    }

    public function shiftRight(int $bits): int {
        return $this->number >> $bits;
    }
}
