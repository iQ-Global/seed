# Seed Framework

**By iQ** — A minimal PHP framework that helps you grow without taking over.

---

## What is Seed?

Seed is a lightweight, modern PHP framework designed for simplicity and developer happiness. Inspired by CodeIgniter 3's ease of use and Gin's middleware pattern, Seed provides just enough structure to build robust applications without imposing unnecessary complexity.

### Core Philosophy

- ✨ **Minimal but capable** - Only what you need, nothing you don't
- 🚀 **Easy to learn** - Simple concepts, clear patterns
- 🎯 **Developer freedom** - Opinionated where it helps, flexible where it matters
- 🔧 **Modern yet accessible** - Works with PHP 7+ and modern development practices

---

## Quick Example

```php
// app/routes.php
$router->get('/users/{id}', 'userController/show');

// app/controllers/userController.php
class userController extends Controller {
    public function show($id) {
        $user = db()->query("SELECT * FROM users WHERE id = ?", [$id]);
        return view('users/show', ['user' => $user]);
    }
}

// app/views/users/show.php
<h1><?= esc($user->name) ?></h1>
<p><?= esc($user->email) ?></p>
```

Simple, clear, and powerful.

---

## Key Features

- **Middleware Support** - Route-specific middleware chains (inspired by Gin)
- **Hybrid OOP + Procedural** - Use classes or functions as you prefer
- **Native PHP Templates** - No complex syntax to learn, works great with HTMX
- **Modern & Logical Helpers** - Descriptive names: `input_value()`, `form_error()`, `has_error()`
- **Built-in Security** - CSRF protection, XSS prevention, rate limiting
- **Simple Validation** - Clean API: `validate()` for simple cases, `validator()` for control
- **No ORM** - Use raw SQL with prepared statements (database is powerful as-is)
- **CLI Support** - Run controllers from command line, custom commands
- **MIT License** - Maximum freedom for commercial and open-source projects

---

## Installation

Seed is feature-complete and ready to use! Follow these steps to get started:

```bash
# Future installation (once released)
git clone https://github.com/iQ-Global/seed.git myproject
cd myproject
composer install
cp .env.example .env
# Configure .env and start building!
```

---

## Helper Function Examples

Seed uses descriptive, self-documenting helper names:

```php
// Form helpers
input_value('email')      // Repopulate form field
form_error('email')       // Get validation error
has_error('email')        // Check if error exists
show_error('email')       // Display formatted error

// Flash messages
flash('success', 'Saved!')
show_flash('success')

// Validation
validate($data, $rules)   // Simple boolean
validator($data, $rules)  // Object for more control

// View & response
view('template', $data)
json(['status' => 'ok'])
redirect('/home')
redirect_back()

// Session
session('user_id', 123)   // Set
session('user_id')        // Get
```

**Why these names?**
- **Descriptive**: `input_value()` is clearer than `old()`
- **Specific**: `form_error()` is more precise than `error()`
- **Self-documenting**: Code reads like plain English

---

## Design Principles

### Modern and Logical
- Not stuck in the past (CI3 inspired but improved)
- Not copying trends (avoid Laravel-isms)
- Clarity over brevity
- Self-documenting code

### What We Believe
1. **Descriptive names** - Functions should describe what they do
2. **Specific over generic** - `form_error()` beats `error()`
3. **Simple, not simplistic** - No method chaining, clean function calls
4. **Developer-friendly** - Easy to learn, remember, and use
5. **Database respect** - Raw SQL is powerful, don't hide it behind abstractions

---

## Comparison with Other Frameworks

| Framework | Learning Curve | Size | Setup Time | Philosophy |
|-----------|---------------|------|------------|------------|
| **Seed** | Easy | ~2MB | 2-5 min | Simple, clear, powerful enough |
| Laravel | Steep | ~50MB | 15-30 min | Batteries-included, comprehensive |
| CodeIgniter 3 | Easy | ~2MB | 2-5 min | Simple (but aging, no longer developed) |
| Slim | Easy | ~500KB | 5-10 min | Micro-framework (too minimal, you build everything) |

**Seed's sweet spot:** As simple as CI3, modern features like middleware, but without the bloat of Laravel.

---

## Current Status

**Phase:** 🎉 **v1.0.0 RELEASED!**  
**Status:** ✅ Production Ready

### What's Included
- Complete framework core (~3,500 lines of code)
- 27 core classes fully implemented and tested
- 40+ helper functions
- 23 automated tests (all passing)
- Complete documentation (text + HTML)
- Example application
- CLI tools
- All security features working

### Roadmap (v1.1+)

**v1.0 - Core Framework** ✅ **RELEASED!**
- ✅ Router with middleware
- ✅ Request/Response objects
- ✅ MVC structure
- ✅ Security features (CSRF, XSS, rate limiting)
- ✅ Database (MySQL & PostgreSQL)
- ✅ Authentication & Validation
- ✅ HTTP Client & Email
- ✅ Helper functions (40+)
- ✅ CLI support

**v1.1 - Enhancements** (Future)
- Database (MySQL, PostgreSQL)
- Authentication
- .env support
- Email (SMTP)
- Storage/filesystem

**v2.0+ - Advanced Features**
- MongoDB support
- AI interface (OpenAI, Claude)
- Queue system
- Caching layer
- And more...

---

## Documentation

📚 **Complete documentation for using Seed Framework:**

### User Documentation
- **[📖 HTML Documentation](docs/html/index.html)** - Beautiful, searchable docs (open in browser)
- **[📄 Complete Guide](docs/text/seed-framework-complete-guide.txt)** - Text guide for AI context
- **[⚡ Quick Reference](docs/context/quick-reference.md)** - Essential commands & code snippets
- **[💡 Examples](docs/examples/)** - Configuration files (nginx.conf, .htaccess)
- **[📚 Documentation Guide](docs/README.md)** - Which documentation to use when

### Development Documentation
For framework development and architectural decisions:
- **[Conventions](dev-docs/conventions.md)** - Coding standards and design philosophy
- **[Decisions](dev-docs/decisions.md)** - All 18 architectural decisions with rationale
- **[Framework Comparison](dev-docs/framework-comparison.md)** - vs Laravel, Symfony, CI3, etc.
- **[Complete TODO](dev-docs/todo.md)** - Full implementation plan and progress

---

## Why "Seed"?

A seed is small but contains everything needed to grow into something substantial. That's our philosophy:

- **Start small** - Minimal core, clean structure
- **Grow naturally** - Add modules and features as you need them
- **Stay healthy** - Good practices built in, but not forced
- **Bear fruit** - Build real applications without fighting the framework

Just like a seed, Seed helps you grow without taking over.

---

## Contributing

Seed will be open source once v1.0 is released. Contribution guidelines will be provided at that time.

---

## License

Seed is open-source software licensed under the **[MIT License](LICENSE)**.

You can:
- ✅ Use it for commercial projects
- ✅ Modify it as needed
- ✅ Distribute it
- ✅ Sublicense it

---

## Credits

**Developed by iQ** • Building tools that help you grow

**Inspired by:**
- CodeIgniter 3 - For simplicity and ease of use
- Gin (Go) - For the middleware pattern
- Modern PHP - For best practices without bloat

---

**Status:** 🎉 v1.0.0 Released!  
**Version:** 1.0.0  
**Date:** November 1, 2025
