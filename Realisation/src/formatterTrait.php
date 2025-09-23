<?php

namespace App;

trait FormatterTrait {
    public function format(string $label, string|int $value, int $labelPad = 15): string {
        return str_pad($label, $labelPad, " ", STR_PAD_RIGHT) . ": " . $value . PHP_EOL;
    }

    public function formatTableHeader(array $headers, int $labelPad = 15): string {
        $headerLine = "";
        foreach ($headers as $header) {
            $headerLine .= str_pad($header, $labelPad, " ", STR_PAD_RIGHT);
        }
        return $headerLine . PHP_EOL . str_repeat("-", strlen($headerLine)) . PHP_EOL;
    }

    public function formatTableRow(array $values, int $labelPad = 15): string {
        $rowLine = "";
        foreach ($values as $value) {
            $rowLine .= str_pad($value, $labelPad, " ", STR_PAD_RIGHT);
        }
        return $rowLine . PHP_EOL;
    }
}
