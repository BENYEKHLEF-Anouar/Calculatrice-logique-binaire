<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Calculator;
use App\NumberConverter;

<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Calculator;
use App\NumberConverter;

// Display help message if no arguments or --help flag is present
if ($argc < 2 || in_array('--help', $argv, true)) {
    echo "Usage: php bin/calc.php <number1> [operator] [<number2>] [options]\n";
    echo "Operators:\n";
    echo "  & (AND)             Bitwise AND with number2\n";
    echo "  | (OR)              Bitwise OR with number2\n";
    echo "  ^ (XOR)             Bitwise XOR with number2\n";
    echo "  ~ (NOT)             Bitwise NOT (unary, applies to number1)\n";
    echo "  << (SHL)            Shift left by number2 bits\n";
    echo "  >> (SHR)            Shift right by number2 bits\n";
    echo "Options:\n";
    echo "  --txtin             Read input from samples/input.txt (legacy)\n";
    echo "  --jsonout           Output to samples/output.json (legacy)\n";
    echo "  --input=FILE        JSON or plain text input file\n";
    echo "  --output=FILE       JSON output file\n";
    echo "  --mode=batch        Run in batch mode (non-interactive)\n";
    exit(0);
}

try {
    // --- Argument Parsing ---
    // Initialize flags for legacy and new-style input/output
    $isTxtIn   = in_array("--txtin", $argv, true);
    $isJsonOut = in_array("--jsonout", $argv, true);

    $inputFileArg  = null;
    $outputFileArg = null;
    $modeArg       = null;

    // Parse command-line arguments for new-style flags
    foreach ($argv as $arg) {
        if (str_starts_with($arg, "--input=")) {
            $inputFileArg = substr($arg, 8);
        } elseif (str_starts_with($arg, "--output=")) {
            $outputFileArg = substr($arg, 9);
        } elseif (str_starts_with($arg, "--mode=")) {
            $modeArg = substr($arg, 7);
        }
    }

    $number1 = null;
    $operator = null;
    $number2 = null;

    // Filter out flags to get positional arguments (numbers and operator)
    $args = array_values(array_filter($argv, function($arg) {
        return !str_starts_with($arg, "--");
    }));

    // --- Input Handling ---
    // Helper function to read input from a file (JSON or plain text)
    $readInputFile = function (string $filePath): array {
        if (!file_exists($filePath)) {
            throw new Exception("Input file $filePath not found");
        }
        $fileContent = trim(file_get_contents($filePath));

        // Attempt to decode as JSON first
        $inputData = json_decode($fileContent, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($inputData)) {
            return $inputData;
        }

        // If not JSON, treat as a plain number
        if (is_numeric($fileContent)) {
            return ["number" => (int)$fileContent];
        }

        throw new Exception("Invalid input format in $filePath. Must be JSON or a single integer.");
    };

    // Determine input source based on flags and arguments
    if ($inputFileArg) {
        // Read from specified input file (--input=FILE)
        $inputData = $readInputFile($inputFileArg);

        if (!isset($inputData['number']) || !is_numeric($inputData['number'])) {
            throw new Exception("Missing or invalid 'number' in $inputFileArg.");
        }
        $number1 = (int) $inputData['number'];
        if ($number1 < 0) {
            throw new Exception("'number' must be a positive integer in $inputFileArg.");
        }

        $operator = $inputData['operator'] ?? null;
        $number2  = $inputData['number2'] ?? null;
        if ($number2 !== null) {
            if (!is_numeric($number2)) {
                throw new Exception("Invalid 'number2' in $inputFileArg. Must be numeric.");
            }
            $number2 = (int) $number2;
            if ($number2 < 0) {
                throw new Exception("'number2' must be a positive integer in $inputFileArg.");
            }
        }
        echo "Input successfully read from $inputFileArg" . PHP_EOL;

    } elseif ($isTxtIn) {
        // Read from legacy input file (--txtin)
        $legacyFile = 'samples/input.txt';
        $inputData = $readInputFile($legacyFile);

        if (!isset($inputData['number']) || !is_numeric($inputData['number'])) {
            throw new Exception("Missing or invalid 'number' in $legacyFile.");
        }
        $number1 = (int) $inputData['number'];
        if ($number1 < 0) {
            throw new Exception("'number' must be a positive integer in $legacyFile.");
        }

        $operator = $inputData['operator'] ?? null;
        $number2  = $inputData['number2'] ?? null;
        if ($number2 !== null) {
            if (!is_numeric($number2)) {
                throw new Exception("Invalid 'number2' in $legacyFile. Must be numeric.");
            }
            $number2 = (int) $number2;
            if ($number2 < 0) {
                throw new Exception("'number2' must be a positive integer in $legacyFile.");
            }
        }
        echo "Input successfully read from $legacyFile" . PHP_EOL;

    } else {
        // Read from command-line arguments (default)
        if (!isset($args[1]) || !is_numeric($args[1])) {
            throw new Exception("Invalid number provided. Please provide a numeric value for number1.");
        }
        $number1 = (int) $args[1];
        if ($number1 < 0) {
            throw new Exception("Number1 must be a positive integer.");
        }
        array_shift($args); // remove script name
        array_shift($args); // remove number1

        if (count($args) > 0) {
            if (count($args) === 1) {
                // If only one argument left, it could be an operator or number2
                if (in_array($args[0], ['&', '|', '^', '~', '<<', '>>'])) {
                    $operator = $args[0];
                    array_shift($args);
                } elseif (is_numeric($args[0])) {
                    $number2 = (int) $args[0];
                    if ($number2 < 0) {
                        throw new Exception("Number2 must be a positive integer.");
                    }
                    array_shift($args);
                } else {
                    throw new Exception("Invalid argument: " . $args[0]);
                }
            } elseif (count($args) >= 2) {
                // If two or more arguments, assume operator then number2
                $operator = $args[0];
                array_shift($args);

                if ($operator !== '~') { // Unary operator doesn't need number2
                    if (!isset($args[0]) || !is_numeric($args[0])) {
                        throw new Exception("Invalid number provided for number2.");
                    }
                    $number2 = (int) $args[0];
                    if ($number2 < 0) {
                        throw new Exception("Number2 must be a positive integer.");
                    }
                    array_shift($args);
                }
            }
        }
    }

    // Ensure number1 is set
    if ($number1 === null) {
        throw new Exception("A primary number (number1) is required.");
    }

    // Initialize Calculator and NumberConverter
    $calculator = new Calculator($number1, $number2);
    $numberConverter1 = new NumberConverter($number1);

    // Prepare initial results (conversions for number1)
    $results = [
        "Decimal"      => $numberConverter1->toDecimal(),
        "Binary"       => $numberConverter1->toBinary(),
        "Hexadecimal"  => $numberConverter1->toHexa(),
    ];

    // --- Operations ---
    // Perform bitwise operations based on the detected operator or default behavior
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
                if ($number2 === null) throw new Exception("Operator '<<' requires a second number.");
                $results["Shift Left"] = $numberConverter1->shiftLeft($number2);
                break;
            case '>>':
                if ($number2 === null) throw new Exception("Operator '>>' requires a second number.");
                $results["Shift Right"] = $numberConverter1->shiftRight($number2);
                break;
            default:
                throw new Exception("Unknown operator: " . $operator);
        }
    } elseif ($number2 !== null) {
        // If two numbers are provided without an explicit operator, perform default operations
        $results["AND"] = $calculator->bitwiseAnd();
        $results["OR"]  = $calculator->bitwiseOr();
        $results["XOR"] = $calculator->bitwiseXor();
        $results["NOT"] = $calculator->bitwiseNot();
    } else {
        // If only one number, perform NOT operation by default
        $results["NOT"] = $calculator->bitwiseNot();
    }

    // --- Output Handling ---
    // Determine output destination based on flags
    if ($outputFileArg) {
        // Write to specified output file (--output=FILE)
        $jsonOutput = json_encode($results, JSON_PRETTY_PRINT);
        if (file_put_contents($outputFileArg, $jsonOutput) === false) {
            throw new Exception("Failed to write to $outputFileArg");
        }
        echo "Output successfully written to $outputFileArg" . PHP_EOL;

    } elseif ($isJsonOut) {
        // Write to legacy output file (--jsonout)
        $legacyOut = 'samples/output.json';
        $jsonOutput = json_encode($results, JSON_PRETTY_PRINT);
        if (file_put_contents($legacyOut, $jsonOutput) === false) {
            throw new Exception("Failed to write to $legacyOut");
        }
        echo "Output successfully written to $legacyOut" . PHP_EOL;

    } elseif ($modeArg === 'batch') {
        // In batch mode, suppress console output if no file output is specified
        // (This block intentionally does nothing if no file output is set, as per batch mode's non-interactive nature)
        // If you want to ensure *some* output in batch mode, you might add a default file output here.
        // For now, it's silent if no --output is given in batch mode.
    } else {
        // Pretty print results to console (default)
        echo $calculator->formatTableHeader(["Entrée A", $number1]);
        echo $calculator->formatTableRow(["Decimal", $numberConverter1->toDecimal()]);
        echo $calculator->formatTableRow(["Binary", $numberConverter1->toBinary()]);
        echo $calculator->formatTableRow(["Hexadecimal", $numberConverter1->toHexa()]);
        echo PHP_EOL;

        if ($number2 !== null) {
            $numberConverter2 = new NumberConverter($number2);
            echo $calculator->formatTableHeader(["Entrée B", $number2]);
            echo $calculator->formatTableRow(["Decimal", $numberConverter2->toDecimal()]);
            echo $calculator->formatTableRow(["Binary", $numberConverter2->toBinary()]);
            echo $calculator->formatTableRow(["Hexadecimal", $numberConverter2->toHexa()]);
            echo PHP_EOL;
        }

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
