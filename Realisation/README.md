# Calculatrice Logique Binaire CLI

Une interface en ligne de commande (CLI) PHP pour convertir des nombres entre les formats décimal, binaire et hexadécimal, et effectuer diverses opérations logiques binaires.

## Fonctionnalités

- Convertit un entier en binaire/hexadécimal.
- Accepte deux entiers et applique les opérateurs logiques : ET (`&`), OU (`|`), XOR (`^`), NON (`~`), Décalage à gauche (`<<`), Décalage à droite (`>>`).
- Affiche un tableau clair des résultats.
- Valide les entrées (entiers positifs) et gère les erreurs.
- Lecture de l'entrée depuis les arguments de la ligne de commande, un fichier spécifié (`--input=FILE`), ou le fichier hérité `samples/input.txt`.
- Sortie des résultats vers la console, un fichier spécifié (`--output=FILE`), ou le fichier hérité `samples/output.json`.

## Prérequis

- PHP >= 8.2

## Installation

1. Cloner le dépôt :
   ```bash
   git clone https://github.com/BENYEKHLEF-Anouar/Calculatrice-logique-binaire.git
   cd Calculatrice-logique-binaire/Realisation
   ```
2. Installer les dépendances Composer :
   ```bash
   composer install
   ```

## Utilisation

Le script principal est `bin/calc.php`. Vous pouvez l'exécuter en utilisant `php bin/calc.php`.

### Options de la ligne de commande

```
Usage: php bin/calc.php <number1> [operator] [<number2>] [options]
```

**Opérateurs disponibles :**
- `&` (ET) : Opération ET binaire avec `nombre2`
- `|` (OU) : Opération OU binaire avec `nombre2`
- `^` (XOR) : Opération XOR binaire avec `nombre2`
- `~` (NON) : Opération NON binaire (unaire, s'applique à `nombre1`)
- `<<` (SHL) : Décalage à gauche de `nombre1` par `nombre2` bits
- `>>` (SHR) : Décalage à droite de `nombre1` par `nombre2` bits

**Options :**
- `--txtin` : Lit l'entrée depuis `samples/input.txt` (hérité)
- `--jsonout` : Écrit la sortie vers `samples/output.json` (hérité)
- `--input=FILE` : Fichier d'entrée JSON ou texte brut
- `--output=FILE` : Fichier de sortie JSON
- `--mode=batch` : Exécute en mode batch (non-interactif)

**Note sur les opérateurs spéciaux :**
Certains opérateurs comme `&`, `|`, `^`, `<<`, `>>` peuvent avoir une signification spéciale dans votre shell (par exemple, `&` pour exécuter en arrière-plan). Pour vous assurer qu'ils sont passés correctement au script PHP, il est recommandé de les entourer de guillemets (simples ou doubles) ou de les échapper.

### Exemples d'utilisation

**Opérateurs disponibles :**
- `&` (ET) : Opération ET binaire avec `nombre2`
- `|` (OU) : Opération OU binaire avec `nombre2`
- `^` (XOR) : Opération XOR binaire avec `nombre2`
- `~` (NON) : Opération NON binaire (unaire, s'applique à `nombre1`)
- `<<` (SHL) : Décalage à gauche de `nombre1` par `nombre2` bits
- `>>` (SHR) : Décalage à droite de `nombre1` par `nombre2` bits

**Note sur les opérateurs spéciaux :**
Certains opérateurs comme `&`, `|`, `^`, `<<`, `>>` peuvent avoir une signification spéciale dans votre shell (par exemple, `&` pour exécuter en arrière-plan). Pour vous assurer qu'ils sont passés correctement au script PHP, il est recommandé de les entourer de guillemets (simples ou doubles) ou de les échapper.

Exemple :
```bash
php bin/calc.php 5 "&" 3

```

**1. Opérations binaires multiples (sans opérateur explicite) :**
Si deux nombres sont fournis sans opérateur, les opérations ET, OU, XOR et NON sont effectuées par défaut.

```bash
php bin/calc.php 5 3
```

Sortie :
```
+-------------+-------+
| Entrée A    | 5     |
+-------------+-------+
| Decimal     | 5     |
| Binary      | 101   |
| Hexadecimal | 5     |
+-------------+-------+

+-------------+-------+
| Entrée B    | 3     |
+-------------+-------+
| Decimal     | 3     |
| Binary      | 11    |
| Hexadecimal | 3     |
+-------------+-------+

+-----------+--------+--------+
| Operation | Result | Binary |
+-----------+--------+--------+
| A ET B    | 1      | 1      |
| A OU B    | 7      | 111    |
| A XOR B   | 6      | 110    |
| NON A     | -6     | 1..1010|
+-----------+--------+--------+
```
*Note: La représentation binaire de NON A peut varier en fonction de l'architecture système (généralement 32 ou 64 bits).*

**2. Opération spécifique avec opérateur :**

```bash
php bin/calc.php 5 "&" 3
```

Sortie :
```
+-------------+-------+
| Entrée A    | 5     |
+-------------+-------+
| Decimal     | 5     |
| Binary      | 101   |
| Hexadecimal | 5     |
+-------------+-------+

+-------------+-------+
| Entrée B    | 3     |
+-------------+-------+
| Decimal     | 3     |
| Binary      | 11    |
| Hexadecimal | 3     |
+-------------+-------+

+-----------+--------+--------+
| Operation | Result | Binary |
+-----------+--------+--------+
| A ET B    | 1      | 1      |
+-----------+--------+--------+
```

**3. Opération OU :**

```bash
php bin/calc.php 5 "|" 3
```

Sortie :
```
+-------------+-------+
| Entrée A    | 5     |
+-------------+-------+
| Decimal     | 5     |
| Binary      | 101   |
| Hexadecimal | 5     |
+-------------+-------+

+-------------+-------+
| Entrée B    | 3     |
+-------------+-------+
| Decimal     | 3     |
| Binary      | 11    |
| Hexadecimal | 3     |
+-------------+-------+

+-----------+--------+--------+
| Operation | Result | Binary |
+-----------+--------+--------+
| A OU B    | 7      | 111    |
+-----------+--------+--------+
```

**4. Opération XOR :**

```bash
php bin/calc.php 5 "^" 3
```

Sortie :
```
+-------------+-------+
| Entrée A    | 5     |
+-------------+-------+
| Decimal     | 5     |
| Binary      | 101   |
| Hexadecimal | 5     |
+-------------+-------+

+-------------+-------+
| Entrée B    | 3     |
+-------------+-------+
| Decimal     | 3     |
| Binary      | 11    |
| Hexadecimal | 3     |
+-------------+-------+

+-----------+--------+--------+
| Operation | Result | Binary |
+-----------+--------+--------+
| A XOR B   | 6      | 110    |
+-----------+--------+--------+
```

**5. Opération Décalage à gauche (SHL) :**

```bash
php bin/calc.php 5 "<<" 1
```

Sortie :
```
+-------------+-------+
| Entrée A    | 5     |
+-------------+-------+
| Decimal     | 5     |
| Binary      | 101   |
| Hexadecimal | 5     |
+-------------+-------+

+-------------+-------+
| Entrée B    | 1     |
+-------------+-------+
| Decimal     | 1     |
| Binary      | 1     |
| Hexadecimal | 1     |
+-------------+-------+

+-----------+--------+--------+
| Operation | Result | Binary |
+-----------+--------+--------+
| A SHL B   | 10     | 1010   |
+-----------+--------+--------+
```

**6. Opération Décalage à droite (SHR) :**

```bash
php bin/calc.php 5 ">>" 1
```

Sortie :
```
+-------------+-------+
| Entrée A    | 5     |
+-------------+-------+
| Decimal     | 5     |
| Binary      | 101   |
| Hexadecimal | 5     |
+-------------+-------+

+-------------+-------+
| Entrée B    | 1     |
+-------------+-------+
| Decimal     | 1     |
| Binary      | 1     |
| Hexadecimal | 1     |
+-------------+-------+

+-----------+--------+--------+
| Operation | Result | Binary |
+-----------+--------+--------+
| A SHR B   | 2      | 10     |
+-----------+--------+--------+
```

**7. Opération NON (unaire) :**

```bash
php bin/calc.php 5 ~
```

Sortie :
```
+-------------+-------+
| Entrée A    | 5     |
+-------------+-------+
| Decimal     | 5     |
| Binary      | 101   |
| Hexadecimal | 5     |
+-------------+-------+

+-----------+--------+--------+
| Operation | Result | Binary |
+-----------+--------+--------+
| NON A     | -6     | 1..1010|
+-----------+--------+--------+
```
*Note: La représentation binaire de NON A peut varier en fonction de l'architecture système (généralement 32 ou 64 bits).*

### Gestion des entrées/sorties

Le script prend en charge plusieurs méthodes pour fournir des entrées et gérer les sorties.

#### Entrée
Vous pouvez fournir l'entrée de trois manières :

1.  **Arguments de la ligne de commande (par défaut) :**
    Fournissez les nombres et l'opérateur directement comme arguments.
    ```bash
    php bin/calc.php 5 "&" 3
    ```

2.  **Fichier d'entrée (JSON ou texte brut) :**
    Utilisez l'option `--input=FILE` pour spécifier un fichier d'entrée. Le fichier peut être au format JSON ou un simple fichier texte contenant un entier.

    **Format JSON (recommandé pour les opérations binaires) :**
    Créez un fichier (par exemple, `input.json`) avec le contenu suivant :
    ```json
    {
      "number": 5,
      "operator": "&",
      "number2": 3
    }
    ```
    Exécutez :
    ```bash
    php bin/calc.php --input=samples/input.json
    ```

    **Format texte brut (pour un seul nombre) :**
    Créez un fichier (par exemple, `input.txt`) avec un seul entier :
    ```
    5
    ```
    Exécutez :
    ```bash
    php bin/calc.php --input=samples/input.txt
    ```

3.  **Option `--txtin` (héritée) :**
    Cette option lit l'entrée depuis `samples/input.txt`. Elle est maintenue pour la compatibilité mais `--input=samples/input.txt` est la méthode préférée.
    ```bash
    php bin/calc.php --txtin
    ```

#### Sortie
Les résultats peuvent être affichés sur la console ou enregistrés dans un fichier JSON.

1.  **Console (par défaut) :**
    Les résultats sont affichés directement dans le terminal.
    ```bash
    php bin/calc.php 5 "&" 3
    ```

2.  **Fichier de sortie JSON :**
    Utilisez l'option `--output=FILE` pour enregistrer les résultats dans un fichier JSON.
    ```bash
    php bin/calc.php 5 "&" 3 --output=samples/output.json
    ```
    Un exemple de fichier de sortie JSON est disponible à [`samples/output.json`](Realisation/samples/output.json).

3.  **Option `--jsonout` (héritée) :**
    Cette option écrit la sortie vers `samples/output.json`. Elle est maintenue pour la compatibilité mais `--output=samples/output.json` est la méthode préférée.
    ```bash
    php bin/calc.php 5 "&" 3 --jsonout
    ```

Vous pouvez combiner les options d'entrée et de sortie :
```bash
php bin/calc.php --input=samples/input.json --output=results.json
php bin/calc.php --txtin --jsonout
```

### Mode Batch

Le mode batch (`--mode=batch`) est conçu pour les exécutions non-interactives, où le script doit traiter des opérations sans intervention de l'utilisateur. Dans ce mode, le script ne produira pas de sortie formatée sur la console, mais se concentrera sur la lecture des entrées et l'écriture des sorties vers les fichiers spécifiés.

Ce mode est particulièrement utile pour l'intégration dans des scripts plus larges ou des systèmes automatisés.

Exemple d'utilisation en mode batch avec entrée et sortie fichier :
```bash
php bin/calc.php --input=samples/input.json --output=results.json --mode=batch
```

### Scripts Composer

Les scripts Composer suivants sont disponibles pour faciliter le développement :

- `composer build`: Affiche "Build process complete."
- `composer validate`: Exécute les vérifications de lint PHP sur tous les fichiers PHP pertinents dans `bin/` et `src/`.
- `composer save`: Exécute le script `validate` puis affiche "Saving current state...".
- `composer calc`: Exécute le script `bin/calc.php`.
- `composer clean`: Efface le contenu du fichier `samples/output.json`.
- `composer help`: Affiche l'aide du script `bin/calc.php`.

## Structure du projet

- `bin/calc.php`: Le script exécutable principal.
- `src/Calculator.php`: Gère les opérations logiques binaires.
- `src/ConverterInterface.php`: Interface pour la conversion de nombres.
- `src/NumberConverter.php`: Implémente `ConverterInterface` et fournit la logique de conversion.
- `src/formatterTrait.php`: Un trait pour formater les chaînes de sortie.
- `composer.json`: Dépendances et scripts du projet.
- `samples/input.txt`: Fichier par défaut pour l'entrée texte.
- `samples/output.json`: Fichier par défaut pour la sortie JSON.

## Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE.md](LICENSE.md) pour plus de détails.