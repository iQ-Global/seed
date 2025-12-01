# Changelog

All notable changes to Seed Framework will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.6.0] - 2025-12-01 🌍 First Public Release

**First Public Release** - Seed Framework is now open source!

### Added

#### CLI Command: `check:case`
- **`php seed check:case`** - Scan for PSR-4 namespace/directory case mismatches
- **`php seed check:case --fix`** - Auto-fix case mismatches
- Catches issues before deploying to case-sensitive Linux servers
- Scans both `app/` and `system/` directories

### Fixed

#### PSR-4 Case Compliance (Complete)
- **All framework directories** now match PSR-4 namespace conventions
- **System directories**: `system/Core/`, `system/Modules/`, `system/Modules/AI/`, etc.
- **App directories**: `app/Controllers/`, `app/Models/`, `app/Modules/`
- Prevents "Class not found" errors when deploying from macOS to Linux

### Notes

**PSR-4 Naming Convention:**
- Directories containing namespaced PHP classes use **PascalCase** (e.g., `Controllers/`, `Models/`)
- Directories without namespaces use **lowercase** (e.g., `views/`, `config/`, `helpers/`)
- Run `php seed check:case` before deploying to Linux to catch any issues

---

## [1.5.1] - 2025-11-28

### Added

#### Multi-Domain Routing
- **Domain Groups** - Define domain-specific routes
  - `$router->domain('example.com', function($router) { ... })`
  - Routes inside domain groups only match for that domain
  - Shared routes (outside domain groups) work on all domains
- **Domain Defaults** - Set default route per domain
  - `$router->setDefault('controller/method')` - Global default
  - Inside domain group: Sets default for that domain
- **Subdomain Parameters** - Extract values from subdomains
  - Pattern: `{tenant}.app.example.com`
  - Access via: `domain_param('tenant')`
- **Wildcard Subdomains** - Match any subdomain
  - Pattern: `*.example.com`
  - Access via: `domain_param('subdomain')`
- **Automatic Normalization**
  - Strips `www.` prefix automatically
  - Ignores port numbers (`:8000`)
  - Case-insensitive matching

#### New Helper Functions
- `domain_param($key, $default)` - Get extracted domain parameters
- `current_domain()` - Get normalized current domain

#### Request Class Enhancements
- `host()` - Get request host (with port)
- `serverName()` - Get server name (without port)
- `port()` - Get server port
- `isSecure()` - Check if HTTPS
- `fullUrl()` - Get complete request URL

### Fixed
- **Auth.php** - Corrected reversed `update()` parameters in `resetPassword()` and `verifyEmail()` methods (critical bug)
- **Case-sensitivity** - Renamed directories to match PSR-4 namespace conventions (fixes deployment to case-sensitive Linux filesystems):
  - `system/core` → `system/Core`
  - `system/modules` → `system/Modules`
  - `system/modules/ai` → `system/Modules/AI`
  - `system/modules/auth` → `system/Modules/Auth`
  - `system/modules/database` → `system/Modules/Database`
  - `system/modules/email` → `system/Modules/Email`
  - `system/modules/http` → `system/Modules/Http`
  - `system/modules/session` → `system/Modules/Session`
  - `system/modules/storage` → `system/Modules/Storage`

### Notes
Multi-domain routing is fully backward compatible. Existing single-domain apps work without any changes.

---

## [1.5.0] - 2025-11-03 🚀 "SaaS Ready"

**Major Feature Release** - Everything you need to build modern SaaS applications.

### Added

#### MongoDB Driver
- **MongoDBDatabase** class with full MongoDB support
- Same clean interface as MySQL/PostgreSQL drivers
- CRUD operations: `query()`, `queryOne()`, `insert()`, `bulkInsert()`, `update()`, `delete()`
- `aggregate()` for aggregation pipelines
- `createIndex()` for index management
- Transaction support (MongoDB 4.0+)
- Connection management via DatabaseManager
- Event hooks for query tracking
- Configurable via `.env` (MONGODB_HOST, MONGODB_PORT, etc.)
- Helper: `db('mongodb')` to access MongoDB driver

#### Enhanced Authentication
- **Password Reset Flow**
  - `sendPasswordReset($email)` - Generate token and send email
  - `resetPassword($token, $newPassword)` - Reset password with token
  - `verifyResetToken($token)` - Validate reset token
  - Secure token hashing and expiration
  - Email templates included
  - Configurable expiration (AUTH_PASSWORD_RESET_EXPIRATION)
- **Email Verification**
  - `sendVerificationEmail($user)` - Send verification email
  - `verifyEmail($token)` - Verify email with token
  - `isEmailVerified($userId)` - Check verification status
  - Optional email verification middleware
  - Configurable via AUTH_EMAIL_VERIFICATION
- **Account Lockout**
  - `recordLoginAttempt($email, $success)` - Track login attempts
  - `isLocked($email)` - Check if account is locked
  - `unlock($email)` - Manually unlock account
  - Automatic lockout after N failed attempts
  - Configurable attempts and duration (AUTH_LOCKOUT_ATTEMPTS, AUTH_LOCKOUT_DURATION)
- **Remember Me**
  - `login($userId, $remember = false)` - Login with remember me option
  - `checkRememberToken()` - Auto-login from cookie
  - `forgetRememberToken($userId)` - Revoke remember token
  - Secure token management
  - Configurable duration (AUTH_REMEMBER_DURATION)
- **Helper Functions**: `send_password_reset()`, `reset_password()`, `send_verification_email()`, `verify_email()`, `is_email_verified()`, `is_account_locked()`, `unlock_account()`, `check_remember_token()`, `forget_remember_token()`
- **Database Tables**: `password_resets`, `email_verifications`, `login_attempts`, `remember_tokens`

#### AI Interface Module
- **Unified AI Provider Interface**
  - Support for OpenAI and Claude (Anthropic)
  - Same API regardless of provider
  - Easy provider switching
  - Model-aware with automatic defaults
- **OpenAI Provider**
  - GPT-4o support (`gpt-4o`, `gpt-4o-mini`)
  - GPT-5 support (`gpt-5`, `gpt-5-preview`, `gpt-5-turbo`)
  - Full chat completion API
  - Streaming support
  - OpenAI-specific parameters (frequency_penalty, presence_penalty, response_format, seed)
- **Claude Provider**
  - Claude Sonnet 4 (`claude-sonnet-4-20250514`)
  - Claude Sonnet 4.5 (`claude-sonnet-4.5-20250514`)
  - Latest alias (`claude-sonnet-4-latest`)
  - Full messages API
  - Streaming support
  - Claude-specific parameters (top_k, stop_sequences, metadata)
- **Features**
  - Conversation history management
  - System prompts
  - Model switching
  - Token usage tracking
  - Unified response format
  - Comprehensive error handling
- **Helper Functions**: `ai($provider)`, `ai_chat($prompt, $options)`, `ai_openai()`, `ai_claude()`
- **Configuration**: `AI_DEFAULT_PROVIDER`, `OPENAI_API_KEY`, `OPENAI_DEFAULT_MODEL`, `CLAUDE_API_KEY`, `CLAUDE_DEFAULT_MODEL`

#### Enhanced Email with PHPMailer
- **Refactored Email class** using PHPMailer library
- More reliable SMTP support
- Better error handling and debugging
- **New Features**:
  - CC and BCC support: `cc($email, $name)`, `bcc($email, $name)`
  - Reply-To: `replyTo($email, $name)`
  - Alternative plain-text body: `altBody($body)`
  - Better attachment handling
  - Error reporting: `getError()`
- **Backwards Compatible** - All existing methods work the same
- **Configuration**: Enhanced .env options (MAIL_ENCRYPTION, better defaults)

#### Additional Validation Rules
- `confirmed` - Password confirmation (checks {field}_confirmation)
- `matches:field` - Field must match another field
- `different:field` - Field must be different from another field
- `date` - Valid date format
- `date_format:format` - Specific date format (Y-m-d, etc.)
- `after:date` - Date after specified date
- `before:date` - Date before specified date
- `between:min,max` - Numeric/string between range
- `in:list` - Value in whitelist
- `not_in:list` - Value not in blacklist
- `regex:pattern` - Custom regex pattern
- `unique:table,column,except` - Database unique check
- `exists:table,column` - Database existence check
- `integer` - Must be integer
- `boolean` - Must be boolean

### Changed
- **Version**: Updated to 1.5.0
- **composer.json**: Added PHPMailer dependency, MongoDB suggestions, AI helper autoload
- **DatabaseManager**: Added MongoDB driver support
- **Email Module**: Completely refactored with PHPMailer (backwards compatible)

### Dependencies
- **Added**: PHPMailer (^6.9) - Required for email functionality
- **Suggested**: mongodb/mongodb (^1.17) - Optional for MongoDB support

### Database Schema
Four new authentication tables (SQL files included):
- `password_resets` - Password reset tokens
- `email_verifications` - Email verification tokens
- `login_attempts` - Failed login tracking
- `remember_tokens` - Remember me tokens

### Configuration
New `.env` variables for all features:
- MongoDB connection settings
- Authentication behavior settings
- AI provider API keys and models
- Enhanced email configuration

### Notes
This is a **major feature release** with no breaking changes. All existing v1.0.x applications will continue to work. The new features are opt-in and can be used independently.

---

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
  - SQL file for sessions table (`system/modules/session/sessions.sql`)
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

