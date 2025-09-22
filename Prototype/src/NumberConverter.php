<?php
// numberconvert.php
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
        // Equivalent built-in: intval($this->number)
    }

    public function toBinary(): string {
        if ($this->number === 0) return "0";

        $num = $this->number;
        $bits = [];

        while ($num > 0) {
            $bits[] = (string)($num % 2);
            $num = intdiv($num, 2); // built-in: intdiv()
        }

        return implode('', array_reverse($bits));
        // Equivalent built-in: decbin($this->number)
    }

    public function toHexa(): string {
        if ($this->number === 0) return "0";

        $num = $this->number;
        $digits = "0123456789ABCDEF";
        $hex = [];

        while ($num > 0) {
            $remainder = $num % 16;
            $hex[] = $digits[$remainder];
            $num = intdiv($num, 16); // built-in: intdiv()
        }

        return implode('', array_reverse($hex));
        // Equivalent built-in: dechex($this->number)
    }

    // public function toHexa(): string {
    //     return strtoupper(dechex($this->number));
    // }

    public function andOp(int $value): int {
        return $this->number & $value;
        // Equivalent built-in: ($this->number & $value) is already native bitwise AND
    }

    public function orOp(int $value): int {
        return $this->number | $value;
        // Equivalent built-in: ($this->number | $value) is already native bitwise OR
    }

    public function xorOp(int $value): int {
        return $this->number ^ $value;
        // Equivalent built-in: ($this->number ^ $value) is already native bitwise XOR
    }

    public function notOp(): int {
        return ~$this->number;
        // Equivalent built-in: (~$this->number) is already native bitwise NOT
    }

    public function shiftLeft(int $bits): int {
        return $this->number << $bits;
        // Equivalent built-in: ($this->number << $bits) is already native shift left
    }

    public function shiftRight(int $bits): int {
        return $this->number >> $bits;
        // Equivalent built-in: ($this->number >> $bits) is already native shift right
    }
}
