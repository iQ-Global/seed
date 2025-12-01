# Installing Seed Framework

Get up and running with Seed in under 5 minutes.

---

## Requirements

- **PHP 7.4+** (PHP 8.x recommended)
- **Composer** (for dependency management)
- **Web Server** (Apache with mod_rewrite, or Nginx)
- **Database** (MySQL 5.7+, PostgreSQL 10+, or MongoDB 4.4+)

### Required PHP Extensions

```
php-json     (usually included)
php-mbstring (string handling)
php-pdo      (database)
php-curl     (HTTP client, optional)
```

---

## Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/iQ-Global/seed.git myproject
cd myproject
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp .env.example .env
```

Edit `.env` with your settings:

```env
# Application
APP_NAME="My App"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myapp
DB_USERNAME=root
DB_PASSWORD=secret
```

### 4. Start Development Server

```bash
php seed serve
```

Visit **http://localhost:8000** — you should see the Seed welcome page!

---

## Alternative: Download ZIP

If you prefer not to use Git:

1. Download from [GitHub Releases](https://github.com/iQ-Global/seed/releases)
2. Extract to your project folder
3. Run `composer install`
4. Continue from step 3 above

---

## Web Server Configuration

### Apache (with .htaccess)

The included `.htaccess` file handles everything. Just ensure:

```apache
# In your Apache config or vhost
<Directory /var/www/myproject>
    AllowOverride All
    Require all granted
</Directory>
```

Enable mod_rewrite:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Nginx

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

    # Deny access to sensitive files
    location ~ /\.(env|git|htaccess) {
        deny all;
    }
    
    location ~ ^/(app|system|vendor)/ {
        deny all;
    }
}
```

---

## Directory Structure

```
myproject/
├── app/                    # YOUR CODE LIVES HERE
│   ├── Controllers/        # Route handlers
│   ├── Models/             # Database models
│   ├── Modules/            # Business logic & services
│   ├── views/              # PHP templates
│   ├── config/             # App configuration
│   ├── helpers/            # Custom helper functions
│   ├── middleware/         # Custom middleware
│   ├── storage/            # Uploads, cache, logs, sessions
│   ├── routes.php          # Route definitions
│   └── commands.php        # Custom CLI commands
│
├── system/                 # FRAMEWORK (don't edit)
│   ├── Core/               # Framework core classes
│   ├── Modules/            # Framework modules
│   └── helpers/            # Framework helper functions
│
├── docs/                   # Documentation
├── .env                    # Environment config (create from .env.example)
├── .env.example            # Example environment file
├── composer.json           # PHP dependencies
├── index.php               # Entry point
└── seed                    # CLI tool
```

**Golden Rule:** Put your code in `app/`, never edit `system/`.

---

## Your First Route

### 1. Define a Route

Edit `app/routes.php`:

```php
$router->get('/hello', 'helloController/index');
$router->get('/hello/{name}', 'helloController/greet');
```

### 2. Create a Controller

Create `app/Controllers/helloController.php`:

```php
<?php
namespace App\Controllers;

use Seed\Core\Controller;

class helloController extends Controller {
    
    public function index() {
        view('hello/index', [
            'message' => 'Welcome to Seed!'
        ]);
    }
    
    public function greet($name) {
        view('hello/greet', [
            'name' => $name
        ]);
    }
}
```

### 3. Create Views

Create `app/views/hello/index.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Hello</title>
</head>
<body>
    <h1><?= esc($message) ?></h1>
    <p>Try visiting <a href="/hello/World">/hello/World</a></p>
</body>
</html>
```

Create `app/views/hello/greet.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Hello <?= esc($name) ?></title>
</head>
<body>
    <h1>Hello, <?= esc($name) ?>!</h1>
    <p><a href="/hello">← Back</a></p>
</body>
</html>
```

### 4. Test It

```bash
php seed serve
```

Visit:
- http://localhost:8000/hello
- http://localhost:8000/hello/YourName

---

## CLI Commands

```bash
# Start development server
php seed serve

# Start on custom port
php seed serve 3000

# List all routes
php seed routes

# Check PSR-4 case compliance (before deploying to Linux)
php seed check:case

# Auto-fix case issues
php seed check:case --fix

# Clear cache
php seed clear:cache

# Clear sessions
php seed clear:sessions

# Clear logs
php seed clear:logs

# Show help
php seed help
```

---

## Updating Seed

To update the framework while keeping your code safe:

```bash
# Check current version
php seed version

# Update to latest version
php seed update
```

This updates `system/` and `seed` CLI without touching your `app/` directory.

---

## Next Steps

1. **Read the Documentation**
   - [Quick Reference](docs/context/quick-reference.md) — Essential commands & snippets
   - [Complete Guide](docs/text/seed-framework-complete-guide.txt) — Full documentation

2. **Explore Features**
   - Database queries with `db()`
   - Form validation with `validate()`
   - Authentication with `auth()`
   - Email with `email()`

3. **Build Something!**
   - Seed is production-ready
   - Check the example controller in `app/Controllers/homeController.php`

---

## Getting Help

- **Documentation:** [docs/](docs/)
- **Issues:** [GitHub Issues](https://github.com/iQ-Global/seed/issues)
- **Discussions:** [GitHub Discussions](https://github.com/iQ-Global/seed/discussions)

---

**Happy coding!** 🌱

