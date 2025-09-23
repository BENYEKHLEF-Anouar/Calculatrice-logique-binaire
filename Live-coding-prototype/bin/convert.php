<?php

require __DIR__ . '/../vendor/autoload.php';

use App\NumberConverter;


if ($argc < 2) {
    echo "Usage: php bin/convert.php <number> [options]\n";
    echo "Options:\n";
    echo "  --jsonin                 Read input from input.json\n";
    echo "  --jsonout                Output to output.json\n";
    echo "  --and <number>           Bitwise AND with n\n";
    echo "  --or <number>            Bitwise OR with n\n";
    echo "  --xor <number>           Bitwise XOR with n\n";
    echo "  --not                    Bitwise NOT\n";
    echo "  --shl <number>           Shift left by n bits\n";
    echo "  --shr <number>           Shift right by n bits\n";
    exit(1);
}

if (in_array('--help', $argv, true)) {
    echo "Usage: php bin/convert.php <number> [options]\n";
    echo "Options:\n";
    echo "  --jsonin                 Read input from input.json\n";
    echo "  --jsonout                Output to output.json\n";
    echo "  --and <number>           Bitwise AND with n\n";
    echo "  --or <number>            Bitwise OR with n\n";
    echo "  --xor <number>           Bitwise XOR with n\n";
    echo "  --not                    Bitwise NOT\n";
    echo "  --shl <number>           Shift left by n bits\n";
    echo "  --shr <number>           Shift right by n bits\n";
    exit(0);
}

try {
    $isJsonIn = in_array("--jsonin", $argv, true);
    $isJsonOut = in_array("--jsonout", $argv, true);

    if ($isJsonIn) {
        $inputFile = 'input.json';
        if (!file_exists($inputFile)) {
            throw new Exception("Input file input.json not found");
        }
        $jsonInput = file_get_contents($inputFile);
        $inputData = json_decode($jsonInput, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in input.json");
        }
        if (!isset($inputData['number']) || !is_int($inputData['number'])) {
            throw new Exception("Input JSON must contain a valid 'number' integer");
        }
        $number = $inputData['number'];
        echo "Input successfully read from input.json" . PHP_EOL;
    } else {
        if (!isset($argv[1]) || !is_numeric($argv[1])) {
            throw new Exception("Invalid number provided. Please provide a numeric value.");
        }
        $number = (int) $argv[1];
    }

    $converter = new NumberConverter($number);

    $results = [
        "Decimal" => $converter->toDecimal(),
        "Binary"  => $converter->toBinary(),
        "Hexadecimal"    => $converter->toHexa(),
    ];

    foreach ($argv as $i => $arg) {
        switch ($arg) {
            case "--and":
                $results["and"] = $converter->bitwiseAnd((int) $argv[$i+1]);
                break;
            case "--or":
                $results["or"] = $converter->bitwiseOr((int) $argv[$i+1]);
                break;
            case "--xor":
                $results["xor"] = $converter->bitwiseXor((int) $argv[$i+1]);
                break;
            case "--not":
                $results["not"] = $converter->bitwiseNot();
                break;
            case "--shl":
                $results["shift_left"] = $converter->shiftLeft((int) $argv[$i+1]);
                break;
            case "--shr":
                $results["shift_right"] = $converter->shiftRight((int) $argv[$i+1]);
                break;
        }
    }

    if ($isJsonOut) {
        $outputFile = 'output.json';
        $jsonOutput = json_encode($results, JSON_PRETTY_PRINT);
        if (file_put_contents($outputFile, $jsonOutput) === false) {
            throw new Exception("Failed to write to output.json");
        }
        echo "Output successfully written to output.json" . PHP_EOL;
    } else {
        foreach ($results as $label => $value) {
            echo $converter->format(ucfirst(str_replace("_", " ", $label)), (string)$value);
        }
    }
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
?>