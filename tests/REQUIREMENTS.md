# Test Suite Requirements

## Minimal Requirements

The Seed Framework test suite is designed to be **non-intrusive** and requires minimal setup.

### Required

✅ **PHP 7.0 or higher**
```bash
php --version
```

✅ **Composer dependencies installed**
```bash
composer install
```

That's it! No database, no .env file, no API keys needed.

---

## What Tests DO

✅ **Verify class existence** - Classes are properly loaded
✅ **Verify method existence** - Public API is available
✅ **Test basic functionality** - Simple logic works (password hashing, validation rules, etc.)
✅ **Check helper functions** - All helpers are defined

## What Tests DON'T Do

❌ **Don't connect to databases** - No MySQL/PostgreSQL/MongoDB connections
❌ **Don't require .env file** - No environment configuration needed
❌ **Don't make API calls** - No OpenAI/Claude/SMTP connections
❌ **Don't write files** - No filesystem operations (except loading classes)
❌ **Don't require web server** - Pure CLI execution

---

## Running Tests

### Quick Start

```bash
# 1. Install dependencies (one time)
composer install

# 2. Run tests
php tests/TestRunner.php

# OR use the shell script
chmod +x run-tests.sh
./run-tests.sh
```

### Expected Output

```
================================================================================
SEED FRAMEWORK TEST SUITE
================================================================================

ROUTER
-------
✓ Router class exists
✓ Router can register GET route
✓ Router can register POST route
...

TEST SUMMARY
================================================================================

Total Tests:  120
Passed:       120 (100.0%)
Failed:       0

✓ ALL TESTS PASSED!
```

---

## Common Issues

### Issue: "Class not found"

**Solution**: Run `composer install` or `composer dump-autoload`

```bash
composer dump-autoload
```

### Issue: "PHP version too old"

**Solution**: Upgrade to PHP 7.0 or higher

```bash
php --version  # Check current version
```

### Issue: "Tests fail with database errors"

**This shouldn't happen!** Our tests don't connect to databases. If you see this:
- Make sure you're running the NEW test suite (`tests/TestRunner.php`)
- Not the OLD dev tests (`dev-docs/testing/*.php`)

---

## Optional: Integration Tests

If you want to test actual database/API connections, you would need:

```env
# .env file (for integration tests only)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=test_db
DB_USERNAME=root
DB_PASSWORD=

# Not needed for basic tests!
```

**Note**: We don't include integration tests in the main suite to keep it fast and dependency-free.

---

## CI/CD Setup

### GitHub Actions (Recommended)

Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    strategy:
      matrix:
        php-version: ['7.0', '7.4', '8.0', '8.1', '8.2']
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
      
      - name: Install dependencies
        run: composer install --prefer-dist --no-progress
      
      - name: Run tests
        run: php tests/TestRunner.php
```

No secrets, no services, no database needed!

---

## Development vs Production

### Development
```bash
# Clone repo
git clone https://github.com/iQ-Global/seed.git
cd seed

# Install dependencies
composer install

# Run tests
php tests/TestRunner.php
```

### Production
```bash
# Install via Composer
composer require iq/seed

# Tests are excluded automatically (via .gitattributes)
# Framework is ready to use immediately
```

---

## Philosophy

**Why minimal requirements?**

1. ✅ **Fast** - No database setup, no configuration
2. ✅ **Reliable** - No external dependencies
3. ✅ **Portable** - Runs anywhere PHP runs
4. ✅ **CI-friendly** - Easy to integrate
5. ✅ **Contributor-friendly** - Low barrier to entry

**What if I need more comprehensive tests?**

For integration testing (real database, real APIs):
- Create separate integration test suite
- Use PHPUnit or similar
- Keep in separate directory (`tests/Integration/`)
- Require opt-in via environment variables

---

## Summary

**To run Seed Framework tests:**

```bash
composer install
php tests/TestRunner.php
```

**That's it!** No .env, no database, no config needed.

---

## Questions?

See the main [README.md](../README.md) or [test README](README.md) for more information.

