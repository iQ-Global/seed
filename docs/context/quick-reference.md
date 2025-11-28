# Seed Framework - Quick Reference

Essential commands and code snippets for daily development.

---

## CLI Commands

```bash
./seed serve              # Start development server
./seed serve 3000        # Start on specific port
./seed help              # Show all commands
./seed routes            # List routes
./seed clear:cache       # Clear cache
./seed clear:sessions    # Clear sessions
./seed clear:logs        # Clear logs
```

---

## Routing

```php
// Basic routes (app/routes.php)
$router->get('/', 'homeController/index');
$router->post('/users', 'userController/store');
$router->put('/users/{id}', 'userController/update');
$router->delete('/users/{id}', 'userController/destroy');

// With middleware
$router->get('/dashboard', 'dashboardController/index')
    ->middleware('auth');

// Route groups
$router->group(['middleware' => 'auth'], function($r) {
    $r->get('/profile', 'userController/profile');
    $r->post('/profile', 'userController/updateProfile');
});

// With prefix
$router->group(['prefix' => 'api'], function($r) {
    $r->get('/users', 'apiController/users');
});
```

---

## Multi-Domain Routing

```php
// Domain-specific routes
$router->domain('example.com', function($router) {
    $router->setDefault('exampleController/index');  // Default for this domain
    $router->get('/about', 'exampleController/about');
});

$router->domain('app.example.com', function($router) {
    $router->setDefault('dashboardController/index');
    $router->get('/settings', 'dashboardController/settings');
});

// Subdomain with parameter extraction
$router->domain('{tenant}.app.example.com', function($router) {
    $router->setDefault('tenantController/index');
    $router->get('/dashboard', 'tenantController/dashboard');
});

// Wildcard subdomains
$router->domain('*.example.com', function($router) {
    $router->get('/', 'subdomainController/index');
});

// Global default (fallback for all domains)
$router->setDefault('homeController/index');

// Shared routes (work on all domains)
$router->get('/api/health', 'apiController/health');
```

**Access Domain Parameters in Controllers:**

```php
// For {tenant}.app.example.com
$tenant = domain_param('tenant');          // e.g., 'acme'
$all = domain_param();                     // ['tenant' => 'acme']

// For *.example.com wildcard
$subdomain = domain_param('subdomain');    // e.g., 'blog'

// Get current domain (normalized)
$domain = current_domain();                // e.g., 'example.com'
```

**Notes:**
- `www.` is stripped automatically
- Ports are ignored (`:8000`)
- Domain matching is case-insensitive
- Domain-specific routes take priority over shared routes

---

## Controllers

```php
<?php
namespace App\Controllers;
use Seed\Core\Controller;

class userController extends Controller {
    public function show($id) {
        $user = db()->queryOne("SELECT * FROM users WHERE id = ?", [$id]);
        view('users/show', ['user' => $user]);
    }
    
    public function store() {
        // Validate
        if (!validate($this->request->post(), [
            'email' => 'required|email'
        ])) {
            return redirect_back();
        }
        
        // Save
        $id = db()->insert('users', $this->request->post());
        
        flash('success', 'User created!');
        redirect('/users/' . $id);
    }
}
```

---

## Database

```php
// Query
$users = db()->query("SELECT * FROM users WHERE status = ?", ['active']);

// Query one
$user = db()->queryOne("SELECT * FROM users WHERE id = ?", [1]);

// Insert
$id = db()->insert('users', [
    'name' => 'John',
    'email' => 'john@example.com'
]);

// Update
db()->update('users', ['status' => 'active'], ['id' => 5]);

// Delete
db()->delete('users', ['id' => 5]);

// Transactions
db()->beginTransaction();
try {
    db()->insert('users', $data);
    db()->commit();
} catch (Exception $e) {
    db()->rollback();
}
```

---

## Validation

```php
// Simple
$rules = [
    'name' => 'required|min:3',
    'email' => 'required|email',
    'age' => 'numeric|min:18'
];

if (!validate($data, $rules)) {
    // Errors automatically available in views
    return redirect_back();
}

// With object
$validator = validator($data, $rules);
if ($validator->failed()) {
    $errors = $validator->errors();
}
```

**Available Rules:**
`required`, `email`, `numeric`, `alpha`, `alpha_num`, `url`, `min:X`, `max:X`

---

## Views

```php
// Render view
view('users/profile', ['user' => $user]);

// In view (app/views/users/profile.php)
<h1><?= esc($user->name) ?></h1>

// Form with validation
<form method="POST">
    <?= csrf_field() ?>
    
    <input type="text" name="email" value="<?= input_value('email') ?>">
    <?= show_error('email') ?>
    
    <button>Submit</button>
</form>

<?= show_flash('success') ?>
```

---

## Authentication

```php
// Hash password
$hash = \Seed\Modules\Auth\Auth::hashPassword($password);

// Verify password
if (\Seed\Modules\Auth\Auth::verifyPassword($password, $hash)) {
    // Correct
}

// Login
\Seed\Modules\Auth\Auth::login($userId);

// Logout
\Seed\Modules\Auth\Auth::logout();

// Check if logged in
if (is_logged_in()) {
    $userId = user_id();
}

// Protect routes
$router->get('/dashboard', 'dashboardController/index')
    ->middleware('auth');
```

---

## Sessions & Flash

```php
// Session
session('key', 'value');     // Set
$value = session('key');      // Get
session_remove('key');        // Remove
destroy_session();            // Destroy

// Flash messages
flash('success', 'User created!');
flash('error', 'Something went wrong');

// In views
<?= show_flash('success') ?>
<?= show_flash('error') ?>

// Check
if (has_flash('success')) {
    $message = get_flash('success');
}
```

---

## HTTP Client

```php
// GET request
$response = http('https://api.example.com')->get('/users');

// POST with JSON
$response = http('https://api.example.com')
    ->post('/users', ['name' => 'John']);

// With authentication
$response = http('https://api.example.com')
    ->bearerToken('token')
    ->get('/data');

// Check response
if ($response->ok()) {
    $data = $response->json();
}
```

---

## Email

```php
email()
    ->to('user@example.com')
    ->subject('Welcome!')
    ->body('Thank you for signing up')
    ->send();

// HTML email
email()
    ->to('user@example.com')
    ->subject('Newsletter')
    ->html('<h1>Hello!</h1><p>Content here</p>')
    ->attach('/path/to/file.pdf')
    ->send();
```

---

## Helper Functions

```php
// General
view('template', $data);
json(['status' => 'ok'], 200);
redirect('/path');
redirect_back();
dd($variable);              // Dump and die
output($variable);          // Debug

// Security
esc($string);               // HTML escape
esc($string, 'js');        // JS escape
esc($string, 'url');       // URL encode
csrf_token();
csrf_field();

// Database
db();

// Auth
auth();
is_logged_in();
user_id();

// HTTP & Email
http($baseUrl);
email();

// Logging
log_info($message, $context);
log_error($message, $context);
log_debug($message, $context);
```

---

## Environment Variables

```bash
# .env file
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=seed
DB_USERNAME=root
DB_PASSWORD=

SESSION_LIFETIME=120
CSRF_ENABLED=true
```

```php
// Access in code
$debug = env('APP_DEBUG', false);
```

---

## Storage & Files

```php
// Store file
storage()->put('documents/report.pdf', $pdfData);

// Get file
$contents = storage()->get('documents/report.pdf');

// Public storage with URL
storage('public')->put('images/logo.png', $imageData);
$url = storage('public')->url('images/logo.png');

// Delete file
storage()->delete('temp/file.txt');

// Directory operations
storage()->makeDirectory('uploads/2025');
$files = storage()->files('uploads');
```

---

## Configuration

```php
// app/config/app.php
return [
    'name' => env('APP_NAME', 'My App'),
    'features' => ['api' => true]
];

// Access config
$appName = config('app.name');
$apiEnabled = config('app.features.api');

// Set runtime config
config_set('app.mode', 'maintenance');

// Check if exists
if (config_has('app.features.api')) {
    // ...
}
```

---

## Events

```php
// Register listener
listen('user.created', function($data) {
    // Send welcome email
    email()->to($data['email'])->subject('Welcome!')->send();
});

// Dispatch event
event('user.created', ['email' => $user->email]);

// Multiple listeners execute in order
listen('order.placed', function($order) { /* ... */ });
listen('order.placed', function($order) { /* ... */ });
event('order.placed', $orderData);
```

---

## URLs & Assets

```php
// Generate URL
$url = url('/users/123');  // http://yoursite.com/users/123

// Asset URL
$cssUrl = asset('css/app.css');  // http://yoursite.com/assets/css/app.css

// Current URL
$current = current_url();

// Check URL pattern
if (url_is('/admin/*')) {
    // Current URL starts with /admin/
}
```

---

## Cookies

```php
// Set cookie (60 minutes default)
cookie_set('user_preference', 'dark_mode');
cookie_set('remember_token', $token, 1440);  // 24 hours

// Get cookie
$preference = cookie('user_preference', 'light_mode');

// Check if exists
if (has_cookie('remember_token')) {
    // Cookie exists
}

// Delete cookie
cookie_forget('user_preference');
```

---

## Array Helpers

```php
// Dot notation access
$data = ['user' => ['profile' => ['name' => 'John']]];
$name = array_get($data, 'user.profile.name');  // 'John'

// Set with dot notation
array_set($data, 'user.profile.age', 30);

// Pluck values
$users = [
    ['id' => 1, 'name' => 'John'],
    ['id' => 2, 'name' => 'Jane']
];
$names = array_pluck($users, 'name');  // ['John', 'Jane']

// Filter keys
$filtered = array_only($data, ['name', 'email']);
$without = array_except($data, ['password']);
```

---

## Common Patterns

### CRUD Controller

```php
public function index() {
    $items = db()->query("SELECT * FROM items");
    view('items/index', ['items' => $items]);
}

public function create() {
    view('items/create');
}

public function store() {
    if (!validate($this->request->post(), $rules)) {
        return redirect_back();
    }
    
    $id = db()->insert('items', $this->request->post());
    flash('success', 'Item created!');
    redirect('/items/' . $id);
}

public function show($id) {
    $item = db()->queryOne("SELECT * FROM items WHERE id = ?", [$id]);
    view('items/show', ['item' => $item]);
}

public function edit($id) {
    $item = db()->queryOne("SELECT * FROM items WHERE id = ?", [$id]);
    view('items/edit', ['item' => $item]);
}

public function update($id) {
    if (!validate($this->request->post(), $rules)) {
        return redirect_back();
    }
    
    db()->update('items', $this->request->post(), ['id' => $id]);
    flash('success', 'Item updated!');
    redirect('/items/' . $id);
}

public function destroy($id) {
    db()->delete('items', ['id' => $id]);
    flash('success', 'Item deleted!');
    redirect('/items');
}
```

### API Endpoint

```php
public function api() {
    try {
        $users = db()->query("SELECT id, name FROM users");
        
        json([
            'status' => 'success',
            'data' => $users
        ], 200);
        
    } catch (Exception $e) {
        log_error('API error', ['exception' => $e->getMessage()]);
        json(['status' => 'error', 'message' => 'Internal error'], 500);
    }
}
```

### Login/Logout

```php
public function login() {
    $email = $this->request->post('email');
    $password = $this->request->post('password');
    
    $user = db()->queryOne("SELECT * FROM users WHERE email = ?", [$email]);
    
    if ($user && Auth::verifyPassword($password, $user->password)) {
        Auth::login($user->id);
        flash('success', 'Logged in!');
        redirect('/dashboard');
    }
    
    flash('error', 'Invalid credentials');
    redirect_back();
}

public function logout() {
    Auth::logout();
    flash('success', 'Logged out');
    redirect('/');
}
```

---

## File Structure

```
app/
├── controllers/    # Your controllers
├── models/        # Your models
├── views/         # Your views
├── middleware/    # Custom middleware
├── helpers/       # Custom helpers
├── routes.php     # Route definitions
└── commands.php   # CLI commands

system/            # Framework core (don't modify)
assets/           # CSS, JS, images
.env              # Configuration
```

---

## Troubleshooting

```bash
# Clear everything
./seed clear:cache
./seed clear:sessions
./seed clear:logs

# Regenerate autoload
composer dump-autoload

# Check permissions
chmod -R 755 app/storage

# View logs
tail -f app/storage/logs/seed-*.log

# Test routes
./seed routes
```

---

## MongoDB

```php
// Get MongoDB instance
$mongo = db('mongodb');

// Query documents
$users = $mongo->query('users', ['status' => 'active']);

// Query one
$user = $mongo->queryOne('users', ['_id' => $objectId]);

// Insert
$id = $mongo->insert('users', [
    'name' => 'John',
    'email' => 'john@example.com',
    'created_at' => new \MongoDB\BSON\UTCDateTime()
]);

// Update
$mongo->update('users', ['status' => 'inactive'], ['status' => 'active']);

// Update one
$mongo->updateOne('users', ['_id' => $id], ['name' => 'Jane']);

// Delete
$mongo->delete('users', ['status' => 'deleted']);

// Count
$count = $mongo->count('users', ['status' => 'active']);

// Aggregation
$results = $mongo->aggregate('users', [
    ['$match' => ['status' => 'active']],
    ['$group' => ['_id' => '$country', 'count' => ['$sum' => 1]]]
]);

// Create index
$mongo->createIndex('users', ['email' => 1], ['unique' => true]);

// Transactions (MongoDB 4.0+)
$session = $mongo->beginTransaction();
try {
    $mongo->insert('users', $data);
    $mongo->commit($session);
} catch (\Exception $e) {
    $mongo->rollback($session);
}
```

---

## Enhanced Authentication

```php
// Password Reset
send_password_reset('user@example.com');

// In controller (password reset page)
if (verify_reset_token($_GET['token'])) {
    reset_password($_GET['token'], $newPassword);
    flash('success', 'Password reset!');
}

// Email Verification
send_verification_email($user);

// In controller (verification page)
if (verify_email($_GET['token'])) {
    flash('success', 'Email verified!');
}

// Check if email verified
if (!is_email_verified()) {
    redirect('/verify-email');
}

// Account Lockout
// In login controller
if (is_account_locked($email)) {
    return view('auth/locked');
}

// Record login attempt
record_login_attempt($email, $success = true);

// Unlock account manually
unlock_account($email);

// Remember Me
// In login controller
Auth::login($userId, $remember = true);

// In bootstrap (check remember token)
if (!is_logged_in()) {
    check_remember_token();
}

// Forget remember token
forget_remember_token();
```

---

## AI Interface

```php
// Quick chat
$response = ai()->chat('What is PHP?');
echo $response->content();

// With options
$response = ai()->chat('Explain quantum computing', [
    'model' => 'gpt-4o',
    'temperature' => 0.7,
    'max_tokens' => 500
]);

// Conversation history
$response = ai()
    ->addMessage('user', 'Hello!')
    ->addMessage('assistant', 'Hi there!')
    ->addMessage('user', 'What is 2+2?')
    ->send();

// System prompt
$response = ai()
    ->system('You are a helpful coding assistant')
    ->chat('How do I sort an array in PHP?');

// Specific provider
$response = ai('openai')->chat('Hello');
$response = ai('claude')->chat('Hello');

// Model switching
$response = ai()
    ->model('gpt-5')
    ->chat('Complex task');

// Get token usage
echo $response->tokens()->prompt();
echo $response->tokens()->completion();
echo $response->tokens()->total();

// Streaming (callback)
ai()->chat('Write a long story')->stream(function($chunk) {
    echo $chunk;
    flush();
});

// OpenAI-specific params
$response = ai('openai')->chat('Generate JSON', [
    'response_format' => ['type' => 'json_object'],
    'frequency_penalty' => 0.5
]);

// Claude-specific params
$response = ai('claude')->chat('Write code', [
    'top_k' => 40,
    'stop_sequences' => ['\n\n']
]);

// Error handling
try {
    $response = ai()->chat('Hello');
} catch (\Seed\Modules\AI\Exceptions\RateLimitException $e) {
    // Rate limited
} catch (\Seed\Modules\AI\Exceptions\AuthenticationException $e) {
    // Invalid API key
}
```

---

## Enhanced Email

```php
// Basic email (now with PHPMailer)
email()
    ->to('user@example.com')
    ->subject('Welcome!')
    ->html('<h1>Welcome to our site!</h1>')
    ->send();

// CC and BCC
email()
    ->to('user@example.com')
    ->cc('manager@example.com')
    ->bcc('admin@example.com')
    ->subject('Report')
    ->send();

// Reply-To
email()
    ->to('user@example.com')
    ->replyTo('support@example.com', 'Support Team')
    ->send();

// Alternative plain-text body
email()
    ->to('user@example.com')
    ->html('<p>Rich HTML content</p>')
    ->altBody('Plain text version')
    ->send();

// Error handling
$mail = email()->to('user@example.com')->subject('Test');
if (!$mail->send()) {
    log_error('Email failed: ' . $mail->getError());
}
```

---

## Additional Validation Rules

```php
// Password confirmation
validate($data, [
    'password' => 'required|min:8',
    'password_confirmation' => 'required|confirmed'  // Checks password_confirmation field
]);

// Field matching
validate($data, [
    'password' => 'required',
    'password_verify' => 'required|matches:password'
]);

// Different fields
validate($data, [
    'new_password' => 'required|different:old_password'
]);

// Date validation
validate($data, [
    'birthdate' => 'required|date',
    'start_date' => 'required|date_format:Y-m-d',
    'end_date' => 'required|after:start_date',
    'event_date' => 'required|before:2025-12-31'
]);

// Between range
validate($data, [
    'age' => 'required|between:18,65',           // Numeric
    'username' => 'required|between:3,20'        // String length
]);

// Whitelist/Blacklist
validate($data, [
    'status' => 'required|in:active,pending,inactive',
    'username' => 'required|not_in:admin,root,system'
]);

// Regex
validate($data, [
    'phone' => 'required|regex:/^\+?[1-9]\d{1,14}$/'
]);

// Database validation
validate($data, [
    'email' => 'required|email|unique:users,email',           // Must be unique
    'category_id' => 'required|exists:categories,id',         // Must exist
    'email' => 'required|unique:users,email,' . $userId       // Unique except current user
]);

// Type validation
validate($data, [
    'age' => 'required|integer',
    'terms' => 'required|boolean'
]);
```

---

**Seed Framework v1.5.0** • MIT License • https://github.com/iQ-Global/seed

