# Seed Framework Tests

## Overview

Comprehensive test suite for Seed Framework covering all core features, modules, and helpers.

## Requirements

**Minimal setup - no database, no .env file, no API keys needed!**

- ✅ PHP 7.0 or higher
- ✅ Composer dependencies installed (`composer install`)

That's it! See [REQUIREMENTS.md](REQUIREMENTS.md) for details.

## Running Tests

### Quick Start

```bash
# Install dependencies (one time)
composer install

# Run all tests
php tests/TestRunner.php

# OR use shell script
./run-tests.sh
```

### Test Organization

```
tests/
├── TestRunner.php          # Main test runner
├── Core/                   # Core framework tests
│   ├── RouterTest.php
│   ├── RequestTest.php
│   ├── ResponseTest.php
│   ├── ValidatorTest.php
│   └── SessionTest.php
├── Modules/                # Module tests
│   ├── DatabaseTest.php
│   ├── AuthTest.php
│   ├── EmailTest.php
│   ├── StorageTest.php
│   └── AITest.php
└── Helpers/                # Helper function tests
    └── HelpersTest.php
```

## Requirements

- PHP 7.0+
- Composer packages installed (`composer install`)
- No additional dependencies (uses simple assertions)

## Test Coverage

**Total Tests**: ~120+

**Coverage by Category:**
- **Core**: ~40 tests (Router, Request, Response, Validator, Session)
- **Modules**: ~45 tests (Database, Auth, Email, Storage, AI)
- **Helpers**: ~60 tests (All helper functions)

## Writing Tests

Tests use a simple assertion-based approach without external dependencies:

```php
TestRunner::suite('MY FEATURE');

TestRunner::test("Feature works correctly", function() {
    $result = myFeature();
    return $result === 'expected';
});
```

### Test Structure

1. **Suite** - Group related tests
2. **Test** - Individual test case
3. **Callback** - Return `true` for pass, `false` for fail
4. **Exceptions** - Caught and reported as failures

## CI/CD Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      - run: composer install
      - run: php tests/TestRunner.php
```

## Production Builds

Tests are excluded from production via `.gitattributes`:

```
/tests export-ignore
```

This means:
- Development: Tests included
- Production: Tests excluded from archive/deploy

## Philosophy

**Why Simple Tests?**
- No heavy dependencies (PHPUnit, etc.)
- Fast to run
- Easy to understand
- Works anywhere PHP runs
- Perfect for lightweight framework

**What We Test:**
- Class existence
- Method availability
- Basic functionality
- Integration points
- Helper functions

**What We Don't Test:**
- Complex scenarios requiring database
- External API calls
- File system operations (unless mocked)

## Adding Tests

When adding new features to Seed:

1. Create test file in appropriate category
2. Add to `TestRunner.php` require list
3. Write tests for public API
4. Run full test suite
5. Ensure all tests pass

## Version History

- **v1.0.0**: 23 tests (Core functionality)
- **v1.0.1**: +18 tests (Storage, Config, Events)
- **v1.0.2**: +22 tests (Pagination, Streaming, Sessions)
- **v1.5.0**: +51 tests (MongoDB, Auth, AI, Email, Validation)

---

**Happy Testing!** 🧪

For questions or contributions, see the main [README.md](../README.md).

