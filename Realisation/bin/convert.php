<?php

require __DIR__ . '/../vendor/autoload.php';

use App\NumberConverter;


if ($argc < 2 || in_array('--help', $argv, true)) {
    echo "Usage: php bin/convert.php <number1> [operator] [<number2>] [options]\n";
    echo "Operators:\n";
    echo "  & (AND)             Bitwise AND with number2\n";
    echo "  | (OR)              Bitwise OR with number2\n";
    echo "  ^ (XOR)             Bitwise XOR with number2\n";
    echo "  ~ (NOT)             Bitwise NOT (unary, applies to number1)\n";
    echo "  << (SHL)            Shift left by number2 bits\n";
    echo "  >> (SHR)            Shift right by number2 bits\n";
    echo "Options:\n";
    echo "  --jsonin            Read input from input.json\n";
    echo "  --jsonout           Output to output.json\n";
    exit(0);
}

try {
    $isJsonIn = in_array("--jsonin", $argv, true);
    $isJsonOut = in_array("--jsonout", $argv, true);

    $number1 = null;
    $operator = null;
    $number2 = null;

    $args = array_values(array_filter($argv, function($arg) {
        return !in_array($arg, ["--jsonin", "--jsonout"]);
    }));

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
        $number1 = $inputData['number'];
        if ($number1 < 0) {
            throw new Exception("Input JSON 'number' must be a positive integer.");
        }
        echo "Input successfully read from input.json" . PHP_EOL;
        // Remove the script name and --jsonin from args for further parsing
        array_shift($args); // remove script name
    } else {
        if (!isset($args[1]) || !is_numeric($args[1])) {
            throw new Exception("Invalid number provided. Please provide a numeric value for number1.");
        }
        $number1 = (int) $args[1];
        if ($number1 < 0) {
            throw new Exception("Number1 must be a positive integer.");
        }
        array_shift($args); // remove script name
        array_shift($args); // remove number1
    }

    if (count($args) > 0) {
        $operator = $args[0];
        array_shift($args); // remove operator

        if ($operator !== '~') {
            if (!isset($args[0]) || !is_numeric($args[0])) {
                throw new Exception("Invalid number provided for number2. Please provide a numeric value.");
            }
            $number2 = (int) $args[0];
            if ($number2 < 0) {
                throw new Exception("Number2 must be a positive integer.");
            }
            array_shift($args); // remove number2
        }
    }

    if ($number1 === null) {
        throw new Exception("A primary number (number1) is required.");
    }

    $converter = new NumberConverter($number1);

    $results = [
        "Decimal" => $converter->toDecimal(),
        "Binary"  => $converter->toBinary(),
        "Hexadecimal"    => $converter->toHexa(),
    ];

    if ($operator) {
        switch ($operator) {
            case '&':
                if ($number2 === null) throw new Exception("Operator '&' requires a second number.");
                $results["AND"] = $converter->bitwiseAnd($number2);
                break;
            case '|':
                if ($number2 === null) throw new Exception("Operator '|' requires a second number.");
                $results["OR"] = $converter->bitwiseOr($number2);
                break;
            case '^':
                if ($number2 === null) throw new Exception("Operator '^' requires a second number.");
                $results["XOR"] = $converter->bitwiseXor($number2);
                break;
            case '~':
                $results["NOT"] = $converter->bitwiseNot();
                break;
            case '<<':
                if ($number2 === null) throw new Exception("Operator '<<' requires a second number (shift bits).");
                $results["Shift Left"] = $converter->shiftLeft($number2);
                break;
            case '>>':
                if ($number2 === null) throw new Exception("Operator '>>' requires a second number (shift bits).");
                $results["Shift Right"] = $converter->shiftRight($number2);
                break;
            default:
                throw new Exception("Unknown operator: " . $operator);
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
        // Prepare for table output
        $maxLabelLength = 0;
        foreach ($results as $label => $value) {
            $maxLabelLength = max($maxLabelLength, strlen($label));
        }

        echo str_repeat("-", $maxLabelLength + 15) . PHP_EOL;
        $headers = ["Type", "Value"];
        $rows = [];

        foreach ($results as $label => $value) {
            $rows[] = [ucfirst(str_replace("_", " ", $label)), (string)$value];
        }

        $maxLabelLength = 0;
        foreach ($rows as $row) {
            $maxLabelLength = max($maxLabelLength, strlen($row[0]));
        }
        $maxLabelLength = max($maxLabelLength, strlen($headers[0]));

        echo $converter->formatTableHeader($headers, $maxLabelLength + 5);
        foreach ($rows as $row) {
            echo $converter->formatTableRow($row, $maxLabelLength + 5);
        }
        echo str_repeat("-", ($maxLabelLength + 5) * count($headers)) . PHP_EOL;
    }
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
?>