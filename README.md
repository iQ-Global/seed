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

### Requirements

- PHP 7.4+ (PHP 8.x recommended)
- Composer
- MySQL 5.7+, PostgreSQL 10+, or MongoDB 4.4+

### Option 1: One-Liner (Recommended)

```bash
# Full install (includes docs/, LICENSE, README.md)
curl -sL https://raw.githubusercontent.com/iQ-Global/seed/master/install.sh | bash -s myproject

# Clean install (minimal — just framework + SEED.md with links to docs on GitHub)
curl -sL https://raw.githubusercontent.com/iQ-Global/seed/master/install.sh | bash -s myproject --clean
```

This downloads Seed, installs dependencies, and initializes a fresh git repo — ready to push to your own repository.

### Option 2: GitHub Template

1. Click **"Use this template"** → **"Create a new repository"** on GitHub
2. Clone your new repository
3. Run `composer install`
4. Copy `.env.example` to `.env`

### Option 3: Manual Download

```bash
# Download and extract
curl -L https://github.com/iQ-Global/seed/archive/master.zip -o seed.zip
unzip seed.zip && mv seed-master myproject && cd myproject

# Install and configure
composer install
cp .env.example .env

# Initialize your own git repo
git init && git add . && git commit -m "Initial commit"
```

### Start Building

```bash
cd myproject
php seed serve
```

Visit **http://localhost:8000** — you're running Seed! 🌱

### Web Server Configuration

<details>
<summary><strong>Apache</strong> (click to expand)</summary>

The included `.htaccess` handles everything. Just enable mod_rewrite:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

</details>

<details>
<summary><strong>Nginx</strong> (click to expand)</summary>

```nginx
server {
    listen 80;
    server_name myapp.com;
    root /var/www/myproject;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(env|git) { deny all; }
    location ~ ^/(app|system|vendor)/ { deny all; }
}
```

</details>

---

## Updating Seed

Keep your framework up to date while preserving your code:

```bash
# Check current version
php seed version

# Check for updates
php seed update --check

# Update to latest version
php seed update
```

The updater:
- ✅ Updates `system/` (framework core)
- ✅ Updates `docs/` (documentation)
- ✅ Updates `seed` CLI
- ✅ Creates automatic backups
- ❌ Never touches your `app/` directory

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

**Phase:** 🛠️ **v1.6.1 Installation & Update Tooling**  
**Status:** ✅ Production Ready & Open Source

### What's Included
- Complete framework core (~7,000+ lines of code)
- 43 core classes fully implemented
- 95+ helper functions
- Complete documentation (text + HTML)
- Example application
- CLI tools
- All security features working
- MongoDB support
- Enhanced authentication (password reset, email verification, lockout, remember me)
- AI Interface (OpenAI GPT-4o/GPT-5, Claude Sonnet 4/4.5)
- Professional email with PHPMailer
- 23 validation rules

### Roadmap

**v1.0 - Core Framework** ✅ **RELEASED!**
- ✅ Router with middleware
- ✅ Request/Response objects
- ✅ MVC structure
- ✅ Security features (CSRF, XSS, rate limiting)
- ✅ Database (MySQL & PostgreSQL)
- ✅ Basic Authentication & Validation
- ✅ HTTP Client & Email
- ✅ Storage, Config, Events, Pagination
- ✅ CLI support

**v1.5 - SaaS Ready** ✅ **RELEASED!**
- ✅ MongoDB driver
- ✅ Enhanced authentication (password reset, email verification, lockout, remember me)
- ✅ AI Interface (OpenAI, Claude)
- ✅ Enhanced email with PHPMailer
- ✅ 15 additional validation rules

**v1.6 - First Public Release** ✅ **RELEASED!**
- ✅ Multi-domain routing with subdomain parameters
- ✅ PSR-4 case compliance (Linux-ready)
- ✅ `check:case` CLI command for deployment validation
- ✅ Open source!

**v1.7+ - Future Enhancements**
- Multi-tenancy helpers
- Stripe integration
- Role/Permission system
- Notification system
- Data export helpers

**v2.0+ - Advanced Features**
- Queue system
- Caching layer
- WebSockets support
- And more...

---

## Documentation

📚 **Complete documentation for using Seed Framework:**

- **[📖 HTML Documentation](docs/html/index.html)** - Beautiful, searchable docs (open in browser)
- **[📄 Complete Guide](docs/text/seed-framework-complete-guide.txt)** - Text guide for AI context
- **[⚡ Quick Reference](docs/context/quick-reference.md)** - Essential commands & code snippets
- **[💡 Examples](docs/examples/)** - Configuration files (nginx.conf, .htaccess)
- **[📚 Documentation Guide](docs/README.md)** - Which documentation to use when

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

Seed is open source! We welcome contributions.

**Before contributing:**
1. Check existing issues and discussions
2. For bugs, open an issue with reproduction steps
3. For features, discuss in an issue first
4. Follow the existing code style
5. Run `php seed check:case` before submitting PRs

**Pull requests welcome for:**
- Bug fixes
- Documentation improvements
- Test coverage
- New features (discuss first)

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

**Status:** 🛠️ v1.6.1 Installation & Update Tooling  
**Version:** 1.6.1  
**Date:** December 1, 2025
