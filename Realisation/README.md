# Calculatrice Logique Binaire CLI

Une interface en ligne de commande (CLI) PHP pour convertir des nombres entre les formats décimal, binaire et hexadécimal, et effectuer diverses opérations logiques binaires.

## Fonctionnalités

- Convertit un entier en binaire/hexadécimal.
- Accepte deux entiers et applique les opérateurs logiques : ET (`&`), OU (`|`), XOR (`^`), NON (`~`), Décalage à gauche (`<<`), Décalage à droite (`>>`).
- Affiche un tableau clair des résultats.
- Valide les entrées (entiers positifs) et gère les erreurs.
- Lecture de l'entrée depuis les arguments de la ligne de commande ou un fichier `input.json`.
- Sortie des résultats vers la console ou un fichier `output.json`.

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

### Opérations de base

Pour effectuer des opérations logiques binaires :

```bash
php bin/calc.php <nombre1> [opérateur] [<nombre2>]
```

**Opérateurs disponibles :**
- `&` (ET) : Opération ET binaire avec `nombre2`
- `|` (OU) : Opération OU binaire avec `nombre2`
- `^` (XOR) : Opération XOR binaire avec `nombre2`
- `~` (NON) : Opération NON binaire (unaire, s'applique à `nombre1`)
- `<<` (SHL) : Décalage à gauche de `nombre1` par `nombre2` bits
- `>>` (SHR) : Décalage à droite de `nombre1` par `nombre2` bits

**Exemples :**

**1. Opérations binaires multiples (sans opérateur explicite) :**
Si deux nombres sont fournis sans opérateur, les opérations ET, OU, XOR et NON sont effectuées par défaut.

```bash
php bin/calc.php 5 3
```

Sortie :
```
Entrée A : 5 (101)
Entrée B : 3 (011)

A ET B : 1 (001)
A OU B : 7 (111)
A XOR B: 6 (110)
NON A : -6 (11111111111111111111111111111010)
```

**2. Opération spécifique avec opérateur :**

```bash
php bin/calc.php 5 & 3
```

Sortie :
```
Entrée A : 5 (101)
Entrée B : 3 (011)

A ET B : 1 (001)
```

**3. Opération NON (unaire) :**

```bash
php bin/calc.php 5 ~
```

Sortie :
```
Entrée A : 5 (101)

NON A : -6 (11111111111111111111111111111010)
```

### Entrée/Sortie JSON

#### Lecture depuis `input.json`

Un exemple de fichier d'entrée JSON est disponible à [`samples/input.json`](Realisation/samples/input.json).
Pour l'utiliser, exécutez le script avec l'option `--jsonin` :

```bash
php bin/calc.php --jsonin
```

#### Écriture vers `output.json`

Pour enregistrer les résultats dans un fichier `output.json`, utilisez l'option `--jsonout` :

```bash
php bin/calc.php 5 & 3 --jsonout
```

Un exemple de fichier de sortie JSON est disponible à [`samples/output.json`](Realisation/samples/output.json).

Vous pouvez également combiner l'entrée et la sortie JSON :

```bash
php bin/calc.php --jsonin --jsonout
```

### Scripts Composer

Les scripts Composer suivants sont disponibles pour faciliter le développement :

- `composer build`: Affiche "Build process complete."
- `composer validate`: Exécute les vérifications de lint PHP sur tous les fichiers PHP pertinents dans `bin/` et `src/`.
- `composer save`: Exécute le script `validate` puis affiche "Saving current state...".
- `composer calc`: Exécute le script `bin/calc.php`.
- `composer clean`: Efface le contenu du fichier `output.json`.
- `composer help`: Affiche l'aide du script `bin/calc.php`.

## Structure du projet

- `bin/calc.php`: Le script exécutable principal.
- `src/Calculator.php`: Gère les opérations logiques binaires.
- `src/ConverterInterface.php`: Interface pour la conversion de nombres.
- `src/NumberConverter.php`: Implémente `ConverterInterface` et fournit la logique de conversion.
- `src/formatterTrait.php`: Un trait pour formater les chaînes de sortie.
- `composer.json`: Dépendances et scripts du projet.
- `input.json`: Fichier par défaut pour l'entrée JSON (peut être utilisé directement).
- `output.json`: Fichier par défaut pour la sortie JSON (peut être utilisé directement).
- `samples/input.json`: Exemple de fichier pour l'entrée JSON.
- `samples/output.json`: Exemple de fichier pour la sortie JSON.

## Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE.md](LICENSE.md) pour plus de détails.