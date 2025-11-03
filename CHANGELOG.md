# Changelog

All notable changes to Seed Framework will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.2] - 2025-11-02

### Added
- **Pagination System** - Complete pagination support
  - `Paginator` class for paginated data
  - `paginate()` helper for array pagination
  - `db_paginate()` helper for database queries
  - Pagination links with customizable templates
  - Methods: `items()`, `links()`, `info()`, `total()`, `currentPage()`, `lastPage()`
  - Previous/Next navigation
  - Page number generation with ellipsis
- **File Download & Streaming** - Enhanced Response class
  - `response()->download($file, $name)` - Download as attachment
  - `response()->file($file)` - Display inline (PDFs, images)
  - `response()->stream($file)` - Memory-efficient streaming
  - Range request support for video seeking
  - MIME type detection for 15+ file types
  - Custom filename support
- **Database Session Driver** - Production-ready sessions
  - `DatabaseSessionDriver` class implementing SessionHandlerInterface
  - SQL file for sessions table (`app/sql/sessions.sql`)
  - Configurable via `SESSION_DRIVER=database`
  - Automatic garbage collection
  - Stores user_id, IP address, user agent
  - Indexed for performance
- **Framework Event Hooks** - Lifecycle events
  - `seed.booting` - Before framework initialization
  - `seed.booted` - After framework ready
  - `request.received` - When request arrives
  - `view.rendering` - Before view renders (can modify data)
  - `view.rendered` - After view rendered
  - `query.executing` - Before database query
  - `query.executed` - After query completes
  - Extensibility for logging, debugging, monitoring
- **String Helpers** - Common string operations
  - `str_limit()` - Truncate with ellipsis
  - `str_slug()` - Generate URL-friendly slug
  - `str_random()` - Generate random string
  - `str_contains()`, `str_starts_with()`, `str_ends_with()` - PHP 8 polyfills
  - `str_has()`, `str_starts()`, `str_ends()` - Convenient aliases
  - `str_title()`, `str_camel()`, `str_studly()` - Case conversion
- **Response Helper** - `response()` function for easy access to Response instance

### Changed
- Updated version to 1.0.2 in composer.json
- Enhanced `Session` class to support multiple drivers (file, database)
- Enhanced `Response` class with download/streaming methods

### Documentation
- Created v1.0.2 development plan in dev-docs

---

## [1.0.1] - 2025-11-02

### Added
- **Storage Module** - File and directory operations
  - `storage()` helper for file operations
  - Support for public, private, uploads, cache, logs disks
  - Methods: put, get, delete, exists, append, copy, move
  - Directory operations: makeDirectory, deleteDirectory, files listing
  - URL generation for public files
- **Configuration System** - Config file management
  - `config()` helper for accessing configuration
  - Load PHP config files from `app/config/`
  - Dot notation support (e.g., `config('app.name')`)
  - Example `app/config/app.php` included
- **Event System** - Simple observer pattern
  - `event()` and `listen()` helpers
  - Event dispatching and listening
  - Built-in framework events support
- **URL Helpers** - URL generation and manipulation
  - `url()` - Generate full URLs
  - `asset()` - Asset URLs
  - `current_url()` - Get current URL
  - `url_is()` - Check if current URL matches pattern
- **Cookie Helpers** - Cookie management
  - `cookie()`, `cookie_set()`, `has_cookie()`, `cookie_forget()`
  - Simple, intuitive API
- **Array Helpers** - Array manipulation utilities
  - `array_get()`, `array_set()`, `array_has()` with dot notation
  - `array_pluck()`, `array_only()`, `array_except()`
  - Clean data manipulation

### Changed
- Updated version to 1.0.1 in composer.json
- Added `app/storage/public/` directory for public file storage

### Documentation
- Created v1.0.1 development plan in dev-docs

---

## [1.0.0] - 2025-11-01

### Initial Release 🎉

Seed Framework v1.0.0 - A minimal PHP framework that helps you grow without taking over.

### Added

#### Core Framework (Phase 1)
- **Router** with full HTTP method support (GET, POST, PUT, DELETE, PATCH)
- **Middleware system** with route-specific and grouped middleware
- **Route parameters** support (e.g., `/user/{id}`)
- **Route groups** with prefixes and middleware
- **Request & Response** objects with clean API
- **MVC structure** with base Controller, Model, and View classes
- **View rendering** with fallback support (app views override system views)
- **Beautiful URLs** via `.htaccess` (Apache) and nginx configuration
- **PSR-4 autoloading** via Composer

#### Security & Error Handling (Phase 2)
- **Error Handler** with development and production modes
- **Logger** with file-based logging (info, error, debug levels)
- **CSRF protection** with token generation and validation
- **XSS prevention** with output escaping helpers
- **Rate limiting** middleware (60 requests/minute default)
- **Session management** with file-based driver
- **Flash messages** for one-time user notifications
- **Custom error pages** (404, 403, 500, debug mode)

#### Essential Modules (Phase 3)
- **Database module** with MySQL and PostgreSQL drivers
  - PDO-based connections
  - Prepared statements (query, insert, update, delete)
  - Transaction support
  - Bulk operations
  - SQL file loading
  - Connection pooling via DatabaseManager
- **Authentication module** with session-based auth
  - Password hashing (bcrypt)
  - Login/logout functionality
  - Auth middleware
- **Validation system** with 10+ built-in rules
  - required, email, numeric, min/max, alpha, alphanumeric, url, etc.
  - Custom error messages
  - Session-based error storage
  - Form repopulation helpers

#### Extended Features (Phase 4)
- **HTTP Client** for external API calls
  - cURL-based with full HTTP method support
  - Authentication (API-Key, Basic Auth, Bearer Token)
  - JSON and form data support
  - Response wrapper with helper methods
- **Email module** for sending emails
  - HTML email support
  - Attachments
  - Fluent API
- **CLI support** with command-line interface
  - Built-in commands: serve, routes, clear:cache, clear:sessions, clear:logs
  - Custom command registration
  - Color output support
  - User prompts (ask, confirm)

#### Helper Functions
**General:**
- `env()` - Get environment variables
- `view()` - Render views
- `json()` - JSON responses
- `redirect()` - Redirects
- `dd()` - Dump and die
- `output()` - Debug output

**Security:**
- `esc()` - Escape output (HTML, JS, URL)
- `csrf_token()` - Generate CSRF token
- `csrf_field()` - Output CSRF field
- `verify_csrf()` - Verify CSRF token

**Forms:**
- `input_value()` - Repopulate form fields
- `form_error()` - Get validation error
- `has_error()` - Check for errors
- `show_error()` - Display formatted error

**Session:**
- `session()` - Session data management
- `flash()` - Flash messages
- `has_flash()` - Check flash existence
- `show_flash()` - Display flash messages
- `destroy_session()` - Destroy session

**Database:**
- `db()` - Get database instance

**Authentication:**
- `auth()` - Get current user
- `is_logged_in()` - Check authentication
- `user_id()` - Get user ID

**Validation:**
- `validate()` - Simple validation
- `validator()` - Get validator instance

**HTTP:**
- `http()` - Create HTTP client

**Email:**
- `email()` - Create email instance

**Logging:**
- `log_info()` - Log info message
- `log_error()` - Log error message
- `log_debug()` - Log debug message

#### Middleware
- `CsrfMiddleware` - CSRF token validation
- `RateLimitMiddleware` - Rate limiting
- `AuthMiddleware` - Authentication requirement

#### Documentation
- Comprehensive framework description
- Coding conventions guide
- Architectural decisions document
- Framework comparison (vs Laravel, Symfony, CI3, Slim)
- Installation and quick start guide
- Example application with working routes

#### Development Tools
- Executable CLI runner (`./seed`)
- Test suite (`test.php`) with 23 automated tests
- Example controllers, models, and views
- `.env.example` with all configuration options

### Philosophy

Seed Framework follows these core principles:
- **Minimal but capable** - Only what you need, nothing you don't
- **Easy to learn** - Simple concepts, clear patterns
- **Developer freedom** - Opinionated where it helps, flexible where it matters
- **Modern yet accessible** - Works with PHP 7.0+
- **AI-friendly** - Clean, well-documented code perfect for AI-assisted development

### Requirements

- PHP 7.0 or higher
- Composer
- Apache (with mod_rewrite) or Nginx
- PDO extension (for database features)
- cURL extension (for HTTP client)

### Notes

This is the first stable release of Seed Framework. All core features are implemented, tested, and ready for production use.

---

## [Unreleased]

### Planned for v1.1+
- SMTP email support (PHPMailer integration)
- Event system
- Storage abstraction
- MongoDB driver
- Additional validation rules
- More CLI commands
- Community-driven features

---

[1.0.0]: https://github.com/iQ-Global/seed/releases/tag/v1.0.0

