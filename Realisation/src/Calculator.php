<?php

namespace App;

use App\FormatterTrait;
// use App\NumberConverter;

class Calculator {
    use FormatterTrait;

    private int $number1;
    private ?int $number2; //integer or null

    public function __construct(int $number1, ?int $number2 = null) {
        $this->number1 = $number1;
        $this->number2 = $number2;
    }

    public function getNumber1(): int {
        return $this->number1;
    }

    public function getNumber2(): ?int {
        return $this->number2;
    }

    public function bitwiseAnd(): int {
        if ($this->number2 === null) {
            throw new \InvalidArgumentException("Bitwise AND requires a second number.");
        }
        return $this->number1 & $this->number2;
    }

    public function bitwiseOr(): int {
        if ($this->number2 === null) {
            throw new \InvalidArgumentException("Bitwise OR requires a second number.");
        }
        return $this->number1 | $this->number2;
    }

    public function bitwiseXor(): int {
        if ($this->number2 === null) {
            throw new \InvalidArgumentException("Bitwise XOR requires a second number.");
        }
        return $this->number1 ^ $this->number2;
    }

    public function bitwiseNot(): int {
        return ~$this->number1;
    }

    // public function getConversions(int $number): array {
    //     $converter = new NumberConverter($number);
    //     return [
    //         "Decimal" => $converter->toDecimal(),
    //         "Binary" => $converter->toBinary(),
    //         "Hexadecimal" => $converter->toHexa(),
    //     ];
    // }
}