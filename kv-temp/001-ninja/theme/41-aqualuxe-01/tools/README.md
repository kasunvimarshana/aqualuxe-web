# AquaLuxe Theme Development Tools

This directory contains utility scripts to help with theme development and maintenance.

## Available Tools

### 1. Duplicate Function Checker

**File:** `check-duplicate-functions.php`

**Description:** This script scans all PHP files in the theme and identifies duplicate function declarations that could cause PHP parse errors.

**Usage:**
```bash
php check-duplicate-functions.php [directory]
```

**Example:**
```bash
# Check the entire theme
php check-duplicate-functions.php ../

# Check a specific directory
php check-duplicate-functions.php ../inc/
```

**Output:** The script will list all functions that are declared in multiple files, showing the file paths for each declaration.

### 2. PHP Syntax Checker

**File:** `check-php-syntax.php`

**Description:** This script checks all PHP files in the theme for syntax errors using PHP's built-in linter.

**Usage:**
```bash
php check-php-syntax.php [directory]
```

**Example:**
```bash
# Check the entire theme
php check-php-syntax.php ../

# Check a specific directory
php check-php-syntax.php ../inc/
```

**Output:** The script will list any files that contain PHP syntax errors, along with the error messages.

## Best Practices

1. **Run Before Committing:** Run these tools before committing changes to ensure code quality.

2. **Include in CI/CD:** Consider including these checks in your continuous integration pipeline.

3. **Regular Maintenance:** Run these tools periodically as part of regular theme maintenance.

4. **Fix Issues Promptly:** Address any issues found by these tools promptly to prevent errors in production.

## Adding New Tools

When adding new tools to this directory:

1. Follow the same pattern of accepting a directory parameter.
2. Include clear documentation at the top of the file.
3. Update this README with information about the new tool.
4. Make sure the tool is executable and properly handles errors.