# Framework Comparison

How Seed compares to other PHP frameworks.

---

## The Landscape

### Laravel
**Philosophy**: Full-featured, batteries-included framework

**Pros**:
- Comprehensive ecosystem
- Excellent documentation
- Large community
- Many built-in features
- Modern PHP practices

**Cons**:
- Heavy and complex (200+ files just to start)
- Takes over your project structure
- Steep learning curve
- Lots of "magic" that can be confusing
- Slow for simple applications
- Requires extensive configuration

**Seed's Approach**: We take the good (modern practices, clean API) and leave the bloat. No magic, just clear code.

---

### Symfony
**Philosophy**: Enterprise-grade, component-based framework

**Pros**:
- Very robust and mature
- Excellent for large applications
- Reusable components
- Strong architecture

**Cons**:
- Extremely complex
- Massive learning curve
- Overkill for most projects
- Heavy abstraction
- Configuration overload

**Seed's Approach**: We're not trying to solve enterprise problems. We solve the 80% use case with 20% of the complexity.

---

### CodeIgniter 3
**Philosophy**: Simple, lightweight, no-nonsense framework

**Pros**:
- ✅ Extremely easy to learn
- ✅ Fast and lightweight
- ✅ Clear documentation
- ✅ Minimal configuration
- ✅ Doesn't take over your project
- ✅ Great for rapid development

**Cons**:
- No longer actively developed
- Outdated patterns (PHP 5 era)
- No middleware support
- Limited modern features
- Aging ecosystem

**Seed's Approach**: This is our spiritual ancestor. We take CI3's simplicity and ease of use, but modernize it with middleware, better security, and current PHP practices.

---

### CodeIgniter 4
**Philosophy**: Modernized version of CodeIgniter

**Pros**:
- Modern PHP (7.4+)
- Better architecture
- Active development

**Cons**:
- Lost much of CI3's simplicity
- More complex than necessary
- Trying to compete with Laravel (unnecessary)
- Breaking changes from CI3 make migration hard

**Seed's Approach**: We're not trying to be Laravel. We're trying to be the spiritual successor to CI3 - simple, fast, clear.

---

### Slim
**Philosophy**: Micro-framework for APIs and simple applications

**Pros**:
- Very lightweight
- Great for APIs
- PSR-7 compliant
- Minimal overhead

**Cons**:
- Too minimal (you have to build everything)
- Not opinionated enough
- No built-in MVC
- Requires lots of additional packages

**Seed's Approach**: We're more opinionated than Slim. We give you MVC, routing, views, security - the essentials. But we're still lightweight.

---

### Lumen
**Philosophy**: Laravel for microservices

**Pros**:
- Faster than Laravel
- Familiar API for Laravel developers
- Good for APIs

**Cons**:
- Still relatively heavy
- Tied to Laravel ecosystem
- Limited compared to Laravel (confusing choice)
- Being deprecated in favor of Laravel

**Seed's Approach**: We're not a "lite" version of something else. We're designed from the ground up to be minimal and fast.

---

## Where Seed Fits

### Best For:
- ✅ Developers who loved CodeIgniter 3
- ✅ Projects that don't need enterprise features
- ✅ Rapid prototyping and MVPs
- ✅ Small to medium applications
- ✅ APIs and web applications
- ✅ Developers who want control
- ✅ Projects where you need to understand all the code
- ✅ Applications that need to be fast and lightweight

### Not Ideal For:
- ❌ Massive enterprise applications with hundreds of developers
- ❌ Teams that need extensive scaffolding and code generation
- ❌ Projects that require a massive ecosystem of packages
- ❌ Developers who prefer "magic" over explicit code
- ❌ Complex ORM requirements (though you can use Eloquent separately)

---

## Feature Comparison

| Feature | Laravel | Symfony | CI3 | CI4 | Slim | Seed |
|---------|---------|---------|-----|-----|------|------|
| **Learning Curve** | Steep | Very Steep | Easy | Moderate | Easy | Easy |
| **File Size (Core)** | ~50MB | ~40MB | ~2MB | ~8MB | ~500KB | ~2MB (target) |
| **Setup Time** | 15-30 min | 30-60 min | 2-5 min | 10-15 min | 5-10 min | 2-5 min |
| **Middleware** | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ |
| **MVC Built-in** | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ |
| **ORM Included** | ✅ | ✅ | ❌ | ✅ | ❌ | ❌* |
| **Template Engine** | Blade | Twig | PHP | PHP | None | PHP |
| **CLI Tools** | ✅ | ✅ | Basic | ✅ | ❌ | ✅ |
| **Dependency Injection** | ✅ | ✅ | ❌ | ✅ | ✅ | Optional** |
| **Built-in Auth** | ✅ | ✅ | ❌ | ✅ | ❌ | ✅ (Module) |
| **API Support** | ✅ | ✅ | Manual | ✅ | ✅ | ✅ |
| **Database Migrations** | ✅ | ✅ | ❌ | ✅ | ❌ | ❌* |
| **Code Generation** | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |
| **Active Development** | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ (Starting) |

\* *By design - use raw queries and SQL files*  
\*\* *Not required, but can be used if desired*

---

## Philosophy Comparison

### Laravel/Symfony Philosophy
> "We'll give you everything you might need, pre-configured and ready to go."

**Result**: Heavy, complex, but comprehensive.

### CodeIgniter 3 Philosophy  
> "Simple tools that get out of your way."

**Result**: Light, fast, but aging.

### Slim Philosophy
> "Just routing and middleware. You build the rest."

**Result**: Minimal, but requires too much work.

### **Seed Philosophy**
> "Essential features, clearly implemented, stay out of your way."

**Result**: Light, modern, and complete enough to be productive immediately.

---

## Code Comparison

### Creating a Simple Route

**Laravel:**
```php
// routes/web.php (1 of many route files)
Route::get('/users/{id}', [UserController::class, 'show'])
    ->middleware(['auth', 'verified']);

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

class UserController extends Controller {
    public function show($id) {
        return view('users.show', ['user' => User::findOrFail($id)]);
    }
}
```

**CodeIgniter 3:**
```php
// config/routes.php
$route['users/(:num)'] = 'users/show/$1';

// controllers/Users.php
class Users extends CI_Controller {
    public function show($id) {
        $this->load->model('user_model');
        $data['user'] = $this->user_model->get($id);
        $this->load->view('users/show', $data);
    }
}
```

**Seed:**
```php
// app/routes.php
$router->get('/users/{id}', 'userController/show')
    ->middleware(['auth']);

// app/controllers/userController.php
class userController extends Controller {
    public function show($id) {
        $user = $this->db->query("SELECT * FROM users WHERE id = ?", [$id]);
        return view('users/show', ['user' => $user]);
    }
}
```

**Analysis**: Seed is as simple as CI3, but with modern features like middleware. Cleaner than Laravel, more modern than CI3.

---

## Performance (Estimated)

| Framework | Hello World (req/sec) | Real App (req/sec) | Memory Usage |
|-----------|----------------------|-------------------|--------------|
| Laravel | ~600 | ~100-300 | ~10MB |
| Symfony | ~800 | ~200-400 | ~8MB |
| CI3 | ~3000 | ~800-1200 | ~1MB |
| CI4 | ~2000 | ~500-800 | ~3MB |
| Slim | ~3500 | ~1000-1500 | ~500KB |
| **Seed (Target)** | **~2500-3000** | **~700-1000** | **~1-2MB** |

*Note: These are rough estimates. Actual performance depends on many factors.*

---

## When to Choose Each

### Choose Laravel if:
- You need a comprehensive ecosystem
- You're building a large, complex application
- You want lots of built-in features
- Your team already knows Laravel
- You need extensive third-party packages

### Choose Symfony if:
- You're building enterprise-scale applications
- You need maximum flexibility and reusability
- You have a large development team
- You need components you can use separately

### Choose CodeIgniter 4 if:
- You need something modern but familiar to CI3
- You want active development and support
- You're okay with more complexity than CI3

### Choose Slim if:
- You're building a simple API
- You want maximum control
- You're experienced and know what you need
- You want to pick your own components

### **Choose Seed if:**
- ✅ **You loved CodeIgniter 3's simplicity**
- ✅ **You want modern features without bloat**
- ✅ **You're building small to medium applications**
- ✅ **You want to understand all your framework code**
- ✅ **You need to be productive fast**
- ✅ **You prefer explicit over magic**
- ✅ **You want control over your database**
- ✅ **You value clarity and simplicity**

---

## The Sweet Spot

```
More Features                          Seed's Sweet Spot ⭐
       ▲                                      ▲
       │                                      │
       │   Laravel                            │
       │   Symfony                            │
       │                                      │
       │                                      │
       │                  CI4                 │
       │                                      │
       │                         Seed ⭐      │
       │                                      │
       │                  CI3                 │
       │                                      │
       │                         Slim         │
       │                                      │
       └────────────────────────────────────► More Simplicity
```

Seed aims for the sweet spot: modern features and best practices, but with CI3-level simplicity.

---

## Summary

**Seed is for developers who:**
- Want the simplicity of CodeIgniter 3
- Need modern features (middleware, security, CLI)
- Don't want framework bloat
- Prefer to write SQL over fighting an ORM
- Want to understand their whole stack
- Value getting things done over endless configuration

**Seed is NOT for developers who:**
- Want a massive ecosystem of pre-built packages
- Need extensive scaffolding and code generation
- Prefer "convention over configuration" to the extreme
- Want an ORM that handles everything
- Are building massive enterprise applications

---

**The Bottom Line**: Seed is CodeIgniter 3's spiritual successor, modernized for 2025 and beyond. Simple, fast, clear, and powerful enough for real applications.

