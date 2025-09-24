# Number Converter CLI

A PHP command-line interface (CLI) tool for converting numbers between decimal, binary, and hexadecimal formats, and performing various bitwise operations.

## Features

- Convert decimal numbers to binary and hexadecimal.
- Perform bitwise AND, OR, XOR, NOT operations.
- Perform bitwise left and right shifts.
- Read input from command-line arguments or a `input.json` file.
- Output results to the console or a `output.json` file.

## Requirements

- PHP >= 8.2

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/BENYEKHLEF-Anouar/Calculatrice-logique-binaire.git
   cd number-converter-cli
   ```
2. Install Composer dependencies:
   ```bash
   composer install
   ```

## Usage

The main script is `bin/convert.php`. You can run it using `php bin/convert.php`.

### Basic Conversion

To convert a number and see its decimal, binary, and hexadecimal representations:

```bash
php bin/convert.php 42
```

Output:
```
Decimal     : 42
Binary      : 101010
Hexa        : 2A
```

### Bitwise Operations

You can perform bitwise operations using the following options:

- `--and <n>`: Bitwise AND with `n`
- `--or <n>`: Bitwise OR with `n`
- `--xor <n>`: Bitwise XOR with `n`
- `--not`: Bitwise NOT
- `--shl <n>`: Shift left by `n` bits
- `--shr <n>`: Shift right by `n` bits

Example with bitwise AND and NOT:

```bash
php bin/convert.php 42 --and 15 --not
```

Example with shift left:

```bash
php bin/convert.php 42 --shl 2
```

Example with shift right:

```bash
php bin/convert.php 42 --shr 2
```

### JSON Input/Output

#### Reading from `input.json`

Create an `input.json` file in the project root with the following format:

```json
{
  "number": 42
}
```

Then run the converter with the `--jsonin` option:

```bash
php bin/convert.php --jsonin
```

#### Writing to `output.json`

To save the results to an `output.json` file, use the `--jsonout` option:

```bash
php bin/convert.php 42 --jsonout
```

The `output.json` file will contain:

```json
{
    "decimal": 42,
    "binary": "101010",
    "hexa": "2A"
}
```

You can also combine JSON input and output:

```bash
php bin/convert.php --jsonin --jsonout --and 15
```

### Cleaning Output

To clear the `output.json` file:

```bash
composer clean
```

## Project Structure

- `bin/convert.php`: The main executable script.
- `src/ConverterInterface.php`: Interface for number conversion.
- `src/NumberConverter.php`: Implements `ConverterInterface` and provides conversion and bitwise logic.
- `src/formatterTrait.php`: A trait for formatting output strings.
- `composer.json`: Project dependencies and scripts.
- `input.json`: Example file for JSON input.
- `output.json`: File for JSON output.

## License

This project is licensed under the MIT License - see the [LICENECE.md](LICENECE.md) file for details.