<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Calculator;
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
    echo "  --txtin             Read input from input.txt\n";
    echo "  --jsonout           Output to output.json\n";
    exit(0);
}

try {
    $isTxtIn = in_array("--txtin", $argv, true);
    $isJsonOut = in_array("--jsonout", $argv, true);

    $number1 = null;
    $operator = null;
    $number2 = null;

// Removes the flags --jsonin and --jsonout from $argv.
// Leaves only the script name and the actual numbers/operators
    $args = array_values(array_filter($argv, function($arg) {
        return !in_array($arg, ["--txtin", "--jsonout"]);
    }));

    if ($isTxtIn) {
        $inputFile = 'samples/input.txt';
        if (!file_exists($inputFile)) {
            throw new Exception("Input file samples/input.txt not found");
        }
        $number1 = (int) file_get_contents($inputFile);
        if ($number1 < 0) {
            throw new Exception("Input file 'number' must be a positive integer in samples/input.txt.");
        }
        echo "Input successfully read from samples/input.txt" . PHP_EOL;
        // Remove the script name and --txtin from args for further parsing
        array_shift($args); // remove script name (the first index)
    } else {
        // Parse number1
        if (!isset($args[1]) || !is_numeric($args[1])) {
            throw new Exception("Invalid number provided. Please provide a numeric value for number1.");
        }
        $number1 = (int) $args[1];
        if ($number1 < 0) {
            throw new Exception("Number1 must be a positive integer.");
        }
        array_shift($args); // remove script name
        array_shift($args); // remove number1
        
        // Parse operator and number2
        if (count($args) > 0) {
            // If there's only one argument left, it could be an operator or a second number
            if (count($args) === 1) {
                // Check if it's a valid operator
                if (in_array($args[0], ['&', '|', '^', '~', '<<', '>>'])) {
                    $operator = $args[0];
                    array_shift($args); // remove operator
                } else if (is_numeric($args[0])) {
                    // If it's a number, assume it's number2 and no operator was specified
                    $number2 = (int) $args[0];
                    if ($number2 < 0) {
                        throw new Exception("Number2 must be a positive integer.");
                    }
                    array_shift($args); // remove number2
                } else {
                    throw new Exception("Invalid argument: " . $args[0]);
                }
            } else if (count($args) >= 2) {
                // If there are two or more arguments, assume operator and number2
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
        }
    }

    if ($number1 === null) {
        throw new Exception("A primary number (number1) is required.");
    }

    $calculator = new Calculator($number1, $number2);
    $numberConverter1 = new NumberConverter($number1);

    $results = [
        "Decimal" => $numberConverter1->toDecimal(),
        "Binary"  => $numberConverter1->toBinary(),
        "Hexadecimal"    => $numberConverter1->toHexa(),
    ];

    // Perform operations
    if ($operator) {
        switch ($operator) {
            case '&':
                if ($number2 === null) throw new Exception("Operator '&' requires a second number.");
                $results["AND"] = $calculator->bitwiseAnd();
                break;
            case '|':
                if ($number2 === null) throw new Exception("Operator '|' requires a second number.");
                $results["OR"] = $calculator->bitwiseOr();
                break;
            case '^':
                if ($number2 === null) throw new Exception("Operator '^' requires a second number.");
                $results["XOR"] = $calculator->bitwiseXor();
                break;
            case '~':
                $results["NOT"] = $calculator->bitwiseNot();
                break;
            case '<<':
                if ($number2 === null) throw new Exception("Operator '<<' requires a second number (shift bits).");
                $results["Shift Left"] = $numberConverter1->shiftLeft($number2);
                break;
            case '>>':
                if ($number2 === null) throw new Exception("Operator '>>' requires a second number (shift bits).");
                $results["Shift Right"] = $numberConverter1->shiftRight($number2);
                break;
            default:
                throw new Exception("Unknown operator: " . $operator);
        }
    } else if ($number2 !== null) {
        // If no operator is specified but two numbers are provided, perform all binary operations
        $results["AND"] = $calculator->bitwiseAnd();
        $results["OR"] = $calculator->bitwiseOr();
        $results["XOR"] = $calculator->bitwiseXor();
        $results["NOT"] = $calculator->bitwiseNot(); // NOT always applies to number1
    } else {
        // If only one number is provided without an operator, perform NOT
        $results["NOT"] = $calculator->bitwiseNot();
    }

    if ($isJsonOut) {
        $outputFile = 'samples/output.json';
        $jsonOutput = json_encode($results, JSON_PRETTY_PRINT);
        if (file_put_contents($outputFile, $jsonOutput) === false) {
            throw new Exception("Failed to write to samples/output.json");
        }
        echo "Output successfully written to samples/output.json" . PHP_EOL;
    } else {
        // Output for Number 1
        echo $calculator->formatTableHeader(["Entrée A", $number1]);
        echo $calculator->formatTableRow(["Decimal", $numberConverter1->toDecimal()]);
        echo $calculator->formatTableRow(["Binary", $numberConverter1->toBinary()]);
        echo $calculator->formatTableRow(["Hexadecimal", $numberConverter1->toHexa()]);
        echo PHP_EOL;

        // Output for Number 2 if available
        if ($number2 !== null) {
            $numberConverter2 = new NumberConverter($number2);
            echo $calculator->formatTableHeader(["Entrée B", $number2]);
            echo $calculator->formatTableRow(["Decimal", $numberConverter2->toDecimal()]);
            echo $calculator->formatTableRow(["Binary", $numberConverter2->toBinary()]);
            echo $calculator->formatTableRow(["Hexadecimal", $numberConverter2->toHexa()]);
            echo PHP_EOL;
        }

        // Output for Bitwise Operations
        $operationHeaders = ["Operation", "Result", "Binary"];
        $operationRows = [];

        if (isset($results["AND"])) {
            $operationRows[] = ["A ET B", $results["AND"], decbin($results["AND"])];
        }
        if (isset($results["OR"])) {
            $operationRows[] = ["A OU B", $results["OR"], decbin($results["OR"])];
        }
        if (isset($results["XOR"])) {
            $operationRows[] = ["A XOR B", $results["XOR"], decbin($results["XOR"])];
        }
        if (isset($results["NOT"])) {
            $operationRows[] = ["NON A", $results["NOT"], decbin($results["NOT"])];
        }
        if ($operator === '<<' && isset($results["Shift Left"])) {
            $operationRows[] = ["A SHL B", $results["Shift Left"], decbin($results["Shift Left"])];
        }
        if ($operator === '>>' && isset($results["Shift Right"])) {
            $operationRows[] = ["A SHR B", $results["Shift Right"], decbin($results["Shift Right"])];
        }

        if (!empty($operationRows)) {
            echo $calculator->formatTableHeader($operationHeaders);
            foreach ($operationRows as $row) {
                echo $calculator->formatTableRow($row);
            }
            echo PHP_EOL;
        }
    }
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
?>