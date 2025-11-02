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

**Seed Framework v1.0.0** • MIT License • https://github.com/iQ-Global/seed

