# Seed Framework - Coding Conventions & Standards

**Version**: 1.0  
**Last Updated**: October 31, 2025

---

## Design Philosophy

Seed's coding conventions follow these core principles:

### Modern and Logical
- **Not stuck in the past**: We take good ideas from CodeIgniter 3 but make improvements
- **Not copying trends**: We avoid Laravel-isms just because they're popular
- **Clarity over brevity**: `input_value()` is better than `old()` - it's clear what it does
- **Self-documenting**: Function names describe their purpose

### Key Principles
1. **Descriptive names**: Functions and helpers should describe what they do
2. **Specific over generic**: `form_error()` is better than `error()` - it's specific
3. **Simple, not simplistic**: Keep it clean but don't sacrifice clarity
4. **Developer-friendly**: Easy to learn, easy to remember, easy to use
5. **Hybrid approach**: OOP for structure, procedural helpers for convenience

### What This Means in Practice
- Helper functions have clear, descriptive names
- No method chaining - simple, separate function calls
- Flexible - use OOP or procedural style as you prefer
- Convention over configuration, but conventions aren't enforced

---

## File Naming Conventions

### Controllers
- **Format**: `mixedCase` (camelCase) with `Controller` suffix
- **Examples**: `userController.php`, `blogPostController.php`, `apiController.php`
- **Rationale**: Prevents naming conflicts with models, clear purpose
- **Note**: Not strictly required, but strongly recommended

### Models
- **Format**: `mixedCase` (camelCase) with `Model` suffix
- **Examples**: `userModel.php`, `blogPostModel.php`, `productModel.php`
- **Rationale**: Prevents naming conflicts with controllers, clear purpose
- **Note**: Not strictly required, but strongly recommended

### Views
- **Format**: Flexible - use what makes sense
- **Examples**: 
  - `profile.php` (simple)
  - `user-profile.php` (kebab-case)
  - `user/profile.php` (subfolder organization)
- **Rationale**: Views are templates, naming should be intuitive
- **Recommendation**: Use subfolders for organization

### Middleware
- **Format**: `mixedCase` with descriptive name
- **Examples**: `authMiddleware.php`, `corsMiddleware.php`, `rateLimitMiddleware.php`
- **Location**: `app/middleware/`

### Helpers
- **Format**: `snake_case` or descriptive names
- **Examples**: `helpers.php`, `string_helpers.php`, `custom_helpers.php`
- **Location**: `app/helpers/`

---

## Directory Structure

### Routes
**Decision**: Single routes file for simplicity

```
app/
  routes.php          # All routes in one file
```

### Route Syntax
**Format**: `controller/function` (not `controller@function`)

**Examples**:
```php
$router->get('/users', 'userController/index');
$router->post('/users', 'userController/store');
$router->get('/users/{id}', 'userController/show');
```

**Rationale**:
- Matches the default URL pattern (controller/function)
- Consistent with CodeIgniter 3 style
- Simpler than Laravel's `@` syntax
- More intuitive - URLs and routes use the same format

**Rationale**: 
- Easier to maintain
- Can see all routes at a glance
- Can organize with comments if needed

**Example**:
```php
<?php
// app/routes.php

// ============================================
// Web Routes
// ============================================

$router->get('/', 'homeController/index');
$router->get('/about', 'homeController/about');

// ============================================
// User Routes
// ============================================

$router->get('/users/{id}', 'userController/show');
$router->post('/users', 'userController/store')
    ->middleware(['auth', 'csrf']);

// ============================================
// API Routes
// ============================================

$router->group(['prefix' => 'api'], function($r) {
    $r->get('/users', 'apiController/users');
    $r->post('/users', 'apiController/createUser')
        ->middleware(['api-key']);
});
```

### Helpers
**Decision**: Dedicated helpers directory

```
app/
  helpers/
    myHelpers.php     # Custom helper functions
    utilities.php     # Utility functions
```

**Loading**: Autoload from config or load manually as needed

---

## Programming Paradigm

### **Hybrid Approach: OOP Core + Procedural Helpers**

Seed uses object-oriented programming for structure and procedural functions for convenience.

### OOP Components

**Controllers** (classes):
```php
<?php
// app/controllers/userController.php

class userController extends Controller {
    
    public function index() {
        $users = $this->db->query("SELECT * FROM users");
        return view('users/index', ['users' => $users]);
    }
    
    public function show($id) {
        $user = $this->db->query("SELECT * FROM users WHERE id = ?", [$id]);
        
        if (!$user) {
            return error_404();
        }
        
        return view('users/show', ['user' => $user]);
    }
}
```

**Models** (classes):
```php
<?php
// app/models/userModel.php

class userModel extends Model {
    
    protected $table = 'users';
    
    public function findActive() {
        return $this->db->query("SELECT * FROM {$this->table} WHERE status = 'active'");
    }
    
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }
}
```

**Middleware** (classes):
```php
<?php
// app/middleware/authMiddleware.php

class authMiddleware extends Middleware {
    
    public function handle($request, $next) {
        if (!session('user_id')) {
            return redirect('/login');
        }
        
        return $next($request);
    }
}
```

### Procedural Helpers

**Helper functions** (procedural):
```php
<?php
// system/helpers/helpers.php

function view($template, $data = []) {
    return View::render($template, $data);
}

function redirect($url) {
    return Response::redirect($url);
}

function json($data, $status = 200) {
    return Response::json($data, $status);
}

function session($key = null, $value = null) {
    return Session::get($key, $value);
}

function db() {
    return Database::getInstance();
}
```

### Flexible Usage

Developers can choose their preferred style:

```php
// OOP style
class userController extends Controller {
    public function show($id) {
        $user = $this->db->query("SELECT * FROM users WHERE id = ?", [$id]);
        return $this->view->render('users/show', ['user' => $user]);
    }
}

// Procedural style
class userController extends Controller {
    public function show($id) {
        $user = db()->query("SELECT * FROM users WHERE id = ?", [$id]);
        return view('users/show', ['user' => $user]);
    }
}

// Both are valid and do the same thing!
```

**Recommendation**: Use procedural helpers for cleaner code, but OOP is always available when needed.

---

## Code Style

### PHP Standards

- **PSR-12** compliant where practical
- **PHP 7.0+** compatible
- **Type hints** encouraged but not required (for PHP 7.0 compatibility)

### Naming

- **Classes**: `PascalCase` - `UserController`, `DatabaseModule`
- **Methods**: `camelCase` - `getUser()`, `createPost()`
- **Functions**: `snake_case` - `get_user()`, `create_post()`
- **Variables**: `camelCase` or `snake_case` (be consistent)
- **Constants**: `UPPER_SNAKE_CASE` - `MAX_UPLOAD_SIZE`, `DB_HOST`

### Example

```php
<?php

class userController extends Controller {
    
    const MAX_RESULTS = 100;
    
    public function index() {
        $maxResults = self::MAX_RESULTS;
        $users = $this->getAllUsers($maxResults);
        return view('users/index', ['users' => $users]);
    }
    
    private function getAllUsers($limit) {
        return db()->query("SELECT * FROM users LIMIT ?", [$limit]);
    }
}
```

---

## View Conventions

### View Files

- **Extension**: `.php`
- **Location**: `app/views/`
- **Organization**: Use subfolders

```
app/views/
  partials/
    header.php
    footer.php
    nav.php
  users/
    index.php
    show.php
    edit.php
  errors/
    404.php
    500.php
```

### View Override System

**System views can be overridden by app views:**

1. Framework looks for view in `app/views/` first
2. If not found, falls back to `system/views/`
3. This allows customizing error pages, etc.

**Example**:
```
system/views/errors/404.php     # Default 404 page
app/views/errors/404.php        # Custom 404 page (if exists, this is used)
```

### View Syntax

**Recommended**: Clean, minimal PHP

```php
<?php include 'partials/header.php'; ?>

<div class="container">
    <h1><?= esc($pageTitle) ?></h1>
    
    <?= show_flash('success') ?>
    <?= show_flash('error') ?>
    
    <form method="POST" action="/users">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= input_value('name') ?>" />
            <?= show_error('name') ?>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= input_value('email') ?>" />
            <?php if (has_error('email')): ?>
                <span class="error"><?= form_error('email') ?></span>
            <?php endif; ?>
        </div>
        
        <button type="submit">Submit</button>
    </form>
</div>

<?php include 'partials/footer.php'; ?>
```

---

## Helper Function Conventions

### Form Helpers

Seed provides descriptive, self-documenting form helpers:

**Repopulate Form Fields:**
```php
<!-- Get input value for repopulation (after validation failure) -->
<input name="email" value="<?= input_value('email') ?>" />
<input name="age" value="<?= input_value('age', 18) ?>" />  <!-- With default -->
```

**Display Validation Errors:**
```php
<!-- Check if field has error -->
<?php if (has_error('email')): ?>
    <span class="error"><?= form_error('email') ?></span>
<?php endif; ?>

<!-- Or use convenience helper -->
<?= show_error('email') ?>  <!-- Automatically shows formatted error if exists -->
```

**CSRF Protection:**
```php
<form method="POST">
    <?= csrf_field() ?>  <!-- Outputs hidden CSRF token field -->
    ...
</form>
```

### Flash Messages

Simple flash message pattern:

**In Controller:**
```php
// Set flash message
flash('success', 'User created successfully!');
flash('error', 'Something went wrong.');

// Then redirect
redirect('/users');
```

**In View:**
```php
<!-- Check and display -->
<?php if (has_flash('success')): ?>
    <div class="alert success"><?= flash('success') ?></div>
<?php endif; ?>

<!-- Or use convenience helper -->
<?= show_flash('success') ?>  <!-- Automatically shows formatted message -->
<?= show_flash('error', 'alert-danger') ?>  <!-- With custom CSS class -->
```

### Validation

**Simple validation function:**
```php
// In controller
$rules = [
    'email' => 'required|email',
    'password' => 'required|min:8',
    'age' => 'required|numeric|min:18'
];

// Simple boolean check
if (!validate($request->post(), $rules)) {
    // Validation failed - errors automatically available in view
    flash('error', 'Please fix the errors below.');
    return view('signup_form');
}

// Validation passed - continue processing
```

**Validator object (for more control):**
```php
$validator = validator($data, $rules);

if ($validator->failed()) {
    // Get all errors
    $errors = $validator->errors();
    
    // Get specific error
    $emailError = $validator->error('email');
    
    return view('form');
}

// Custom error messages
$validator->messages([
    'email.required' => 'We need your email address!',
    'email.email' => 'That doesn\'t look like an email address.'
]);
```

### Session Helpers

**Setting and getting session data:**
```php
// Set session data
session('user_id', 123);
session('username', 'john');

// Get session data
$userId = session('user_id');
$username = session('username', 'Guest');  // With default

// Check if exists
if (session('user_id')) {
    // User is logged in
}

// Remove session data
session_remove('user_id');

// Destroy entire session
session_destroy();
```

### Redirect Helpers

**Simple redirects:**
```php
// Basic redirect
redirect('/home');

// Redirect back to previous page
redirect_back();

// Redirect with flash message
flash('success', 'Settings saved!');
redirect('/dashboard');

// Or combined (optional convenience)
redirect('/dashboard', ['success' => 'Settings saved!']);
```

**Note:** Seed uses simple, separate function calls instead of method chaining. This is more readable and easier to understand.

---

## Database Conventions

### Connection

- **No ORM**: Use direct SQL queries
- **Prepared statements**: Always use for user input
- **Raw queries**: Available but require explicit opt-in

### Query Style

**Recommended**:
```php
// Prepared statements (safe)
$users = db()->query("SELECT * FROM users WHERE status = ?", ['active']);

// Insert helper
$userId = db()->insert('users', [
    'name' => $name,
    'email' => $email
]);

// Update helper
db()->update('users', ['status' => 'inactive'], ['id' => $userId]);

// Raw query (explicit opt-in for safety)
db()->raw("DROP TABLE IF EXISTS temp_users");
```

**Avoid**:
```php
// String concatenation (unsafe!)
$users = db()->query("SELECT * FROM users WHERE status = '$status'");
```

### SQL Files

For table creation and seeding:
```
app/sql/
  create_users.sql
  create_posts.sql
  seed_data.sql
```

**Loading**:
```php
db()->loadSQL('app/sql/create_users.sql');
```

---

## Module Conventions

### System Modules
- **Location**: `system/modules/`
- **Naming**: Lowercase folder names - `database/`, `auth/`, `email/`
- **Entry point**: `Module.php` in each folder

### User Modules
- **Location**: `app/modules/`
- **Naming**: Flexible
- **Structure**: Can override system modules

### Loading Modules

```php
// In controller
$this->load->module('database', 'mysql');

// Via helper
$email = module('email');
$email->send(...);
```

---

## Security Conventions

### Input Handling

**Always escape output**:
```php
<?= esc($userInput) ?>           // HTML context
<?= esc($userInput, 'js') ?>     // JavaScript context
<?= esc($userInput, 'url') ?>    // URL context
```

**Always use prepared statements**:
```php
// Good
db()->query("SELECT * FROM users WHERE id = ?", [$id]);

// Bad
db()->query("SELECT * FROM users WHERE id = $id");
```

### Password Handling

```php
// Hash passwords
$hash = auth()->hashPassword($password);

// Verify passwords
if (auth()->verifyPassword($password, $hash)) {
    // Login successful
}
```

### CSRF Protection

```php
// In forms
<?= csrf_field() ?>

// In routes
$router->post('/users', 'userController/store')
    ->middleware(['csrf']);
```

---

## Error Handling

### Throwing Errors

```php
// In controllers
if (!$user) {
    throw new NotFoundException("User not found");
}

// Or use helpers
if (!$user) {
    return error_404("User not found");
}
```

### Logging

```php
log_info("User logged in", ['user_id' => $userId]);
log_error("Database connection failed", ['error' => $e->getMessage()]);
log_debug("Query executed", ['query' => $sql]);
```

---

## Testing Conventions

(To be defined when testing framework is implemented)

---

## Documentation Conventions

### Code Comments

**DocBlocks** for classes and public methods:
```php
/**
 * Retrieve a user by ID
 * 
 * @param int $id User ID
 * @return array|null User data or null if not found
 */
public function getUser($id) {
    return db()->query("SELECT * FROM users WHERE id = ?", [$id]);
}
```

**Inline comments** for complex logic:
```php
// Check if user has permission to access this resource
if (!auth()->can('edit', $post)) {
    return error_403();
}
```

**No excessive documentation**:
- Code should be self-documenting where possible
- Comments explain "why", not "what"
- Minimal but informative

---

## Git Conventions

### Branch Naming
- `main` - Stable releases
- `develop` - Development branch
- `feature/feature-name` - New features
- `fix/bug-name` - Bug fixes

### Commit Messages
- Clear and descriptive
- Present tense: "Add feature" not "Added feature"
- Reference issues when applicable

**Example**:
```
Add middleware support to router

- Implement middleware chain execution
- Add middleware registration methods
- Update router to handle middleware

Fixes #12
```

---

## Summary

**Key Principles**:
1. ✅ **mixedCase** for controllers and models (recommended, not required)
2. ✅ **Single routes file** for simplicity (`app/routes.php`)
3. ✅ **Helpers in `app/helpers/`** for custom functions
4. ✅ **Hybrid OOP + Procedural** for flexibility
5. ✅ **View override system** (app views override system views)
6. ✅ **Security first** - always escape, always use prepared statements
7. ✅ **Simple and clear** - code should be readable

**Remember**: These are conventions, not strict requirements. The framework is flexible, but following these conventions will make your code more maintainable and consistent with the Seed community.

