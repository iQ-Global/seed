# Seed Framework

**By iQ** — A minimal PHP framework that helps you grow without taking over.

---

## Overview

Seed is a lightweight, minimal PHP framework designed for simplicity and flexibility. It provides just enough structure to build robust applications without imposing unnecessary complexity.

### Core Philosophy
- **Minimal but capable**: Only what you need, nothing you don't
- **Easy to learn**: Simple concepts, clear patterns
- **Developer freedom**: Opinionated where it helps, flexible where it matters
- **Modern yet accessible**: Works with PHP 7+ and modern development practices

---

## Requirements

- PHP 7.0 or higher
- Composer (for dependency management)
- Apache (with mod_rewrite) or Nginx

---

## Architecture

### MVC Pattern

The framework follows the Model-View-Controller pattern with clear separation of concerns:

- **Controllers**: Handle HTTP requests and business logic
- **Models**: Manage data and database interactions
- **Views**: Render HTML and user interfaces

All three support subfolder organization for better code structure.

### Entry Point & Routing

- **Single Entry Point**: `index.php` handles all requests
- **Single Routes File**: All routes defined in one place
- **Beautiful URLs**: Configured via `.htaccess` (Apache) or nginx configuration (both provided)
- **Default Route Pattern**: `controller/function`
- **Custom Routes**: Easily define custom URL patterns
- **HTTP Methods**: Full support for GET, POST, PUT, DELETE, PATCH
- **Route Parameters**: Dynamic segments (e.g., `/user/{id}`)
- **Route Middleware**: Attach middleware to individual routes or groups

### Middleware

Inspired by frameworks like Gin (Go), Seed uses middleware functions that run before controllers. This provides:
- **Granular control**: Apply specific middleware to specific routes or route groups
- **Composable**: Stack multiple middleware (auth → logging → rate limiting)
- **Reusable**: Same middleware across different routes
- **Flexible**: Define execution order per route

Common middleware uses:
- Authentication and authorization
- Logging and request tracking
- Rate limiting
- CORS handling
- Loading globally-needed resources

### Model Loading

- Models must be explicitly loaded within controllers to be used
- Supports lazy loading for better performance
- Can be loaded in middleware for global availability when needed

### Request & Response

Clean, object-oriented handling of HTTP:

**Request Object:**
- Access GET, POST, JSON body data
- File upload handling
- Headers and cookies
- Input validation helpers

**Response Object:**
- Send HTML, JSON, or other content types
- Set status codes and headers
- Redirect helpers
- Download/file streaming

### Views & Templates

Simple and flexible view rendering using native PHP:
- **Native PHP**: No complex template syntax to learn
- **Partial Views**: Include headers, footers, and reusable components
- **View Helpers**: Simple functions for common tasks (escaping, input values, validation errors)
- **HTMX-Friendly**: Designed to work seamlessly with HTMX for dynamic interactions
- **Layout Support**: Master page pattern for consistent structure

Example:
```php
// Controller
return view('users/profile', ['user' => $user]);

// View with partials
<?php include 'partials/header.php'; ?>
<h1><?= esc($user->name) ?></h1>
<?php include 'partials/footer.php'; ?>
```

**Note**: Route definitions use `controller/function` syntax (e.g., `'userController/show'`) to match URL patterns and stay consistent with CodeIgniter 3 style.

### Error Handling & Logging

**Error Handling:**
- Custom error handlers for different error types
- Simple, clean error page templates (customizable)
- Development vs. production modes
- JSON error responses for API endpoints

**Logging:**
- File-based logging (primary)
- Simple log functions: `log_info()`, `log_error()`, `log_debug()`
- Optional database logging via module (not priority)
- PSR-3 compatible structure for future extensibility

### CLI Support

Run framework operations from the command line, similar to CodeIgniter 3:
- Custom command creation
- Built-in commands for common tasks
- Run controllers from CLI for cron jobs/background tasks
- Framework maintenance commands

---

## Security

### CSRF Protection
- Built-in but optional (via middleware)
- Token generation helper: `csrf_field()`
- Automatic validation on POST/PUT/DELETE
- Easy to exclude API routes

### XSS Prevention
Simple escaping helper for views:
```php
<?= esc($user_input) ?>           // HTML escape
<?= esc($user_input, 'js') ?>     // JavaScript escape  
<?= esc($user_input, 'url') ?>    // URL encode
```

### SQL Injection Prevention
- Prepared statement helpers
- Parameter escaping built into query functions
- No raw query execution without explicit opt-in

### Password Hashing
Built into Authentication module:
- Wrapper around PHP's `password_hash()` and `password_verify()`
- Secure defaults
- Simple API

### Rate Limiting
Core middleware for:
- API endpoint protection
- Login attempt throttling
- Customizable per route/group

### Input Validation
Clean validation with helpful error handling:
```php
// Simple validation
$rules = [
    'email' => 'required|email',
    'age' => 'required|numeric|min:18'
];

if (!validate($request->post(), $rules)) {
    // Validation failed - errors automatically available in view
    return view('form');
}

// Or get validator object for more control
$validator = validator($data, $rules);
if ($validator->failed()) {
    return view('form');
}
```

View helpers: `input_value()`, `form_error()`, `has_error()`, `show_error()`

---

## Core System Features

These features are part of the framework core (system folder):

### HTTP Client
A general-purpose HTTP client for making external requests:
- GET, POST, PUT, DELETE, PATCH support
- Send PHP parameters, JSON body, or form data
- Authentication support:
  - API-Key header
  - Basic authentication
  - Bearer token
- Built on cURL with clean API
- Response object with easy access to status, headers, body

**API Client** wraps the HTTP Client with convenience methods for common API patterns.

### API Server Utilities
Lightweight functions to build REST-like APIs:
- Works with the existing router, middleware, and controllers
- Not a full REST implementation, just essential tools
- Parse URL parameters and JSON request bodies
- JSON response helpers
- API-Key authentication middleware
- Proper HTTP status code handling

### Session Management
- File-based sessions by default (no database required)
- Extensible to other drivers via modules (database, Redis)
- Flash message support
- Helper functions: `session()`, `flash()`, `has_flash()`, `show_flash()`
- Secure configuration options

### Helper Functions
Common utilities to make development easier:

**General Helpers:**
- **`output($data)`**: Intelligently print variables, objects, or arrays for debugging
- **`dd($data)`**: Dump and die for debugging
- **`esc($string, $context)`**: Escape output for HTML, JS, URL contexts

**View & Response:**
- **`view($template, $data)`**: Render a view
- **`json($data, $status)`**: Return JSON response
- **`redirect($url)`**: Create redirect response
- **`redirect_back()`**: Redirect to previous page

**Form & Validation:**
- **`input_value($key, $default)`**: Get input value for form repopulation
- **`form_error($key)`**: Get validation error message for a field
- **`has_error($key)`**: Check if a field has a validation error
- **`show_error($key)`**: Display formatted error message if exists
- **`csrf_field()`**: Output CSRF hidden field

**Session & Flash:**
- **`session($key, $value)`**: Get or set session data
- **`flash($key, $value)`**: Set flash message
- **`has_flash($key)`**: Check if flash message exists
- **`show_flash($key)`**: Display formatted flash message if exists

**Validation:**
- **`validate($data, $rules)`**: Simple validation (returns boolean)
- **`validator($data, $rules)`**: Get validator object for more control

**Logging:**
- **`log_info($message, $context)`**: Log info message
- **`log_error($message, $context)`**: Log error message
- **`log_debug($message, $context)`**: Log debug message

### Storage & File System
Simple abstraction for file operations:
```php
Storage::put('uploads/file.jpg', $contents);
Storage::get('uploads/file.jpg');
Storage::delete('uploads/file.jpg');
Storage::exists('uploads/file.jpg');
Storage::url('uploads/file.jpg');  // Get public URL
```

- Core: Local filesystem
- S3 support via optional module (same interface)
- File upload utilities
- Secure file handling

### Event System
Simple event dispatching for extensibility:
```php
// Dispatch an event
Event::dispatch('user.created', $user);

// Listen for events
Event::listen('user.created', function($user) {
    // Send welcome email, log activity, etc.
});
```

Useful for:
- Hooking into framework actions
- Building modular features
- Keeping code decoupled
- Module integration points

---

## Modules & Libraries

Modules extend the framework with optional functionality. They're included but only loaded when needed.

### Core Modules

These modules are included with the framework (in `system/modules/`) and can be loaded on-demand:

#### Database
Connection management and query utilities (no ORM, no migrations):
- **MySQL**: PDO-based connection and prepared query helpers
- **PostgreSQL**: PDO-based connection and prepared query helpers
- Query execution with parameter escaping
- Raw query support (with explicit opt-in)
- Support for loading SQL files to create tables and seed data
- **No forced migrations**: Database structure is up to you
- Connection pooling and management

Example:
```php
// Load the database module
$db = $this->load->database('mysql');

// Prepared query
$users = $db->query("SELECT * FROM users WHERE status = ?", ['active']);

// Insert helper
$db->insert('users', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// Load SQL file
$db->loadSQL('app/sql/create_users_table.sql');
```

#### Authentication
Simple user authentication (can be used standalone or with Accounts module):
- Session-based authentication
- Password hashing utilities
- Login/logout functionality
- Remember me functionality
- Password reset utilities
- Protection against brute force

#### Environment Configuration (.env)
- Load and parse `.env` files
- Access environment variables: `env('DB_HOST', 'localhost')`
- Type casting (boolean, integer, etc.)
- Validation of required variables

#### Email
Send emails with SMTP as primary method:
- Simple API: `Email::send($to, $subject, $body)`
- HTML and plain text support
- Attachments
- CC, BCC support
- Template support (use view files)
- Extensible: Add custom drivers (API-based services, etc.)

### Extended Modules

These modules may be developed separately or as part of later releases:

#### MongoDB
NoSQL database support with similar API to SQL database module

#### AI Interface
Common interface for multiple AI providers:
- OpenAI Provider
- Claude (Anthropic) Provider
- Extensible for additional providers
- Streaming support
- Token management

#### Accounts & Multi-tenancy
Multi-tenant user management:
- One Account, Many Users model
- Account creation with initial user
- User invitation system
- Role-based permissions
- Works with Authentication module

#### Messaging
Communication integrations:
- **Slack**: Send messages, notifications
- Extensible for other platforms (Discord, Teams, etc.)

#### Caching
Optional caching layer:
- File-based caching
- Redis support (via separate driver)
- Memcached support (via separate driver)
- Cache tags and expiration

#### Queue & Background Jobs
Async task processing:
- Database-backed queue
- Redis queue (via driver)
- Job scheduling
- Retry logic and failure handling

#### S3 Storage
Amazon S3 integration using the Storage interface

#### WebSockets
Real-time communication support (specific implementation TBD)

### Custom Modules

Build your own modules or add third-party ones:
- Place in `app/modules/` directory
- Can be standalone PHP libraries (no Composer required)
- Can extend framework functionality
- Can override or extend system modules
- Easy to share between projects

### Composer Integration

Full native support for Composer-installed packages. Use any package from Packagist alongside Seed modules.

---

## Frontend Recommendations

The framework is designed to work particularly well with modern, lightweight frontend tools (though not required):

- **HTMX**: For dynamic HTML interactions
- **Alpine.js**: For lightweight JavaScript reactivity
- **Tailwind CSS**: For utility-first styling

These are suggestions, not requirements. Use any frontend stack you prefer.

---

## Directory Structure

```
/
├── index.php                    # Entry point for all requests
├── .env                         # Environment configuration (git-ignored)
├── .env.example                 # Example environment config
├── .htaccess                    # Apache rewrite rules (if using Apache)
├── composer.json                # Composer dependencies
├── composer.lock                # Locked dependency versions
├── app/                         # Primary location for user code
│   ├── config/                  # Configuration files (PHP-based)
│   │   └── app.php             # Main application config
│   ├── controllers/             # Application controllers (supports subfolders)
│   │   └── homeController.php  # Example controller
│   ├── middleware/              # Custom middleware
│   │   └── customMiddleware.php # Example middleware
│   ├── models/                  # Data models (supports subfolders)
│   │   └── userModel.php       # Example model
│   ├── modules/                 # Custom modules and libraries
│   ├── views/                   # View templates (supports subfolders)
│   │   ├── partials/           # Reusable view components
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   ├── errors/             # Custom error pages (override system errors)
│   │   │   └── 404.php         # Custom 404 (optional)
│   │   └── home.php            # Example view
│   ├── helpers/                 # Custom helper functions
│   │   └── myHelpers.php       # Example helper file
│   ├── routes.php               # All route definitions in one file
│   ├── sql/                     # SQL files for table creation/seeding
│   │   └── create_users.sql    # Example SQL file
│   └── storage/                 # Application storage (git-ignored)
│       ├── logs/               # Log files
│       ├── sessions/           # Session files (if file-based)
│       ├── cache/              # Cache files (if file-based)
│       └── uploads/            # User uploads
├── assets/                      # Publicly accessible assets
│   ├── css/                    # Stylesheets
│   ├── js/                     # JavaScript files
│   └── images/                 # Images
├── system/                      # Framework core (replaceable on upgrade)
│   ├── core/                   # Core framework classes
│   │   ├── Router.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Controller.php
│   │   ├── Model.php
│   │   ├── Middleware.php
│   │   ├── View.php
│   │   └── Seed.php           # Main framework class
│   ├── modules/                # System modules (included with framework)
│   │   ├── database/          # Database module
│   │   ├── auth/              # Authentication module
│   │   ├── email/             # Email module
│   │   └── env/               # .env support module
│   ├── helpers/                # Core helper functions
│   │   ├── helpers.php         # General helpers (view, redirect, etc.)
│   │   ├── security.php        # Security helpers (esc, csrf_field, etc.)
│   │   └── http.php            # HTTP helpers (json, etc.)
│   ├── views/                   # Default error pages and system views
│   │   └── errors/
│   │       ├── 404.php         # Default 404 page
│   │       ├── 500.php         # Default 500 page
│   │       └── 403.php         # Default 403 page
│   └── config/                  # Default framework configuration
│       └── defaults.php
├── docs/                        # Documentation
│   ├── framework/              # Framework documentation
│   └── project/                # Project-specific docs (user-created)
└── vendor/                      # Composer dependencies (auto-generated)
```

### Directory Notes

- **`app/`**: Your application code lives here. Safe from framework upgrades.
- **`system/`**: Framework code. Can be completely replaced when upgrading.
- **`assets/`**: Publicly accessible files. Configure your web server to serve these.
- **`app/storage/`**: Writable directory for logs, sessions, cache, uploads.
- **`app/sql/`**: Optional location for SQL files to create tables and seed data.
- **Root directory**: The web server document root points here, but only `index.php` and `assets/` are publicly accessible.

---

## Installation

Super simple installation and setup:

1. **Download or clone the framework**
   ```bash
   git clone https://github.com/iQ/seed.git myproject
   cd myproject
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your settings (database, etc.)
   ```

4. **Set up web server**
   - Apache: `.htaccess` file is included and ready to use
   - Nginx: See `docs/framework/nginx.conf` for configuration example
   - Point document root to the project root directory
   - Ensure `assets/` directory is publicly accessible
   - Ensure `app/storage/` is writable

5. **Set permissions** (Linux/Mac)
   ```bash
   chmod -R 755 app/storage
   chmod -R 755 assets
   ```

6. **Start building!**
   - Create your first controller in `app/controllers/`
   - Define routes in `app/routes.php`
   - Build views in `app/views/`

That's it! No complex build processes or configuration files to manage.

---

## Upgrade Strategy

Seed is designed to make upgrades simple and safe:

### How Upgrades Work

1. **System folder is replaceable**: The entire `system/` directory can be replaced with a new version
2. **User code is protected**: Everything in `app/` remains untouched
3. **Module compatibility**: System modules maintain backward compatibility when possible

### Upgrade Process

**Manual Upgrade:**
```bash
# Backup current system folder
mv system system.backup

# Download new version
# Replace system folder with new version

# Check for breaking changes
php seed version:check

# Test your application
```

**CLI Upgrade (future feature):**
```bash
php seed upgrade
# Automatically backs up, downloads, and installs new version
```

### Breaking Changes

- Major version updates (2.x to 3.x) may include breaking changes
- Minor updates (2.1 to 2.2) maintain backward compatibility
- Patch updates (2.1.1 to 2.1.2) are always safe
- Breaking changes are documented in `CHANGELOG.md`

### Best Practices

- Always backup before upgrading
- Test upgrades in a development environment first
- Review the changelog for breaking changes
- Keep `app/` and `system/` strictly separated in your code

---

## Development Philosophy

### What Seed IS

- **Minimal**: Only essential features in core
- **Flexible**: Extend with modules as needed
- **Transparent**: Plain PHP, no magic
- **Modern**: Works with current best practices
- **Developer-friendly**: Clear patterns, minimal abstraction

### What Seed IS NOT

- **Not an ORM**: We don't abstract the database away
- **Not a build system**: No asset compilation required
- **Not opinionated about frontend**: Use any tools you want
- **Not trying to do everything**: Focus on the essentials
- **Not forcing patterns**: Use as much or as little as you need

### Inspired By

- **CodeIgniter 3**: Simplicity and ease of use
- **Gin (Go)**: Middleware pattern and minimal approach
- **Modern PHP**: Best practices without the bloat

---

## CLI Usage

Run Seed commands from the terminal:

```bash
# Run built-in commands
php seed version              # Show framework version
php seed routes               # List all defined routes
php seed help                 # Show available commands

# Run a controller method from CLI (for cron jobs, etc.)
php seed run controller/method

# Create custom commands in app/commands/
# Then run them:
php seed custom:command
```

---

## License

Seed is open-source software licensed under the **MIT License**.

This means you can:
- ✅ Use it for commercial projects
- ✅ Modify it as needed
- ✅ Distribute it
- ✅ Sublicense it

The only requirement is to include the original license and copyright notice.

---

## Contributing

When Seed becomes open source, contributions will be welcome! More details will be provided in `CONTRIBUTING.md`.

---

## Why "Seed"?

A seed is small but contains everything needed to grow into something substantial. That's the philosophy of this framework:

- **Start small**: Minimal core, clean structure
- **Grow naturally**: Add modules and features as you need them
- **Stay healthy**: Good practices built in, but not forced
- **Bear fruit**: Build real applications without fighting the framework

Just like a seed, Seed helps you grow without taking over.

---

## Roadmap

### Phase 1: Core Framework (v1.0)
- ✅ Router with middleware support
- ✅ Request/Response objects
- ✅ MVC structure
- ✅ Error handling and logging
- ✅ Basic security (CSRF, XSS prevention)
- ✅ Core helpers
- ✅ CLI support

### Phase 2: Essential Modules (v1.1)
- Database module (MySQL, PostgreSQL)
- Authentication module
- .env support
- Email module
- Storage/filesystem

### Phase 3: Extended Features (v1.2+)
- Input validation
- Event system
- HTTP client
- Session drivers (database, Redis)
- Rate limiting

### Phase 4: Advanced Modules (v2.0)
- MongoDB support
- AI interface
- Accounts/multi-tenancy
- Queue system
- Caching layer
- S3 storage

### Future Considerations
- WebSockets support
- Additional integrations
- Community modules
- Performance optimizations

---

## Support & Documentation

- **Documentation**: `docs/framework/` (comprehensive guides and API reference)
- **Examples**: `docs/framework/examples/` (common use cases)
- **GitHub Issues**: For bug reports and feature requests (when open source)
- **Community**: Details TBD when project goes public

---

**Developed by iQ** • Building tools that help you grow