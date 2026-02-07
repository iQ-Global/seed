# Seed Framework

Your project is built on **Seed Framework** — a minimal PHP framework that helps you grow.

---

## Quick Reference

| Command | Description |
|---------|-------------|
| `php seed serve` | Start development server |
| `php seed version` | Check framework version |
| `php seed update` | Update framework to latest version |
| `php seed check:case` | Check PSR-4 case compliance |
| `php seed help` | Show all commands |

---

## Project Structure

| Directory | Purpose |
|-----------|---------|
| `app/Controllers/` | Route handlers (namespaced classes) |
| `app/Models/` | Database models (namespaced classes) |
| `app/Modules/` | Business logic & API clients (namespaced classes) |
| `app/views/` | PHP templates |
| `app/config/` | Configuration files |
| `app/helpers/` | Custom helper functions |
| `app/middleware/` | Custom middleware |
| `app/routes.php` | Route definitions |
| `system/` | Framework core — don't edit, updated via `php seed update` |

---

## PSR-4 Case Sensitivity — Important!

If you develop on **macOS** (case-insensitive) and deploy to **Linux** (case-sensitive),
directory names **must exactly match** namespace case:

| Namespace | Directory |
|-----------|-----------|
| `App\Controllers\Admin` | `app/Controllers/Admin/` ✅ |
| `App\Controllers\Admin` | `app/controllers/admin/` ❌ |

**Rule of thumb:**
- Directories with namespaced PHP classes → **PascalCase** (`Controllers/`, `Models/`)
- Directories without namespaces → **lowercase** (`views/`, `config/`, `helpers/`)

**Before deploying, always run:**

```bash
php seed check:case
```

This catches mismatches. Use `php seed check:case --fix` to auto-fix them.

---

## Documentation & Resources

- **Full README** — https://github.com/iQ-Global/seed/blob/master/README.md
- **Quick Reference** — https://github.com/iQ-Global/seed/blob/master/docs/context/quick-reference.md
- **Complete Guide** — https://github.com/iQ-Global/seed/blob/master/docs/text/seed-framework-complete-guide.txt
- **HTML Documentation** — https://github.com/iQ-Global/seed/blob/master/docs/html/index.html
- **Changelog** — https://github.com/iQ-Global/seed/blob/master/CHANGELOG.md
- **License (MIT)** — https://github.com/iQ-Global/seed/blob/master/LICENSE

---

## Updating

```bash
php seed update
```

Updates `system/` and `seed` CLI. Never touches your `app/` directory.

---

**Seed Framework** • MIT License • https://github.com/iQ-Global/seed
