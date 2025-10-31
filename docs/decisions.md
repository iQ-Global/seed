# Seed Framework - Key Decisions & Next Steps

**Date**: October 31, 2025  
**Status**: Planning & Design Phase

---

## Core Decisions Made

### 1. Middleware over Core Controller ✅
- **Decision**: Use middleware pattern (like Gin) instead of a single Core Controller
- **Rationale**: More flexible, composable, and granular control over route handling
- **Impact**: Routes can have specific middleware chains; better separation of concerns

### 2. Hybrid Programming Paradigm: OOP Core + Procedural Helpers ✅
- **Decision**: Object-oriented core with procedural helper functions
- **Rationale**: Best of both worlds - structure where needed, convenience where wanted
- **Implementation**:
  - Controllers and Models are classes (easy to extend and organize)
  - Helpers are procedural functions (clean, convenient syntax)
  - Modules are classes but accessible via helpers too
  - Developer can choose their preferred style
- **Example**: Both `$this->view->render()` and `view()` work
- **Inspiration**: CodeIgniter 3's approach

### 3. File Naming: mixedCase (camelCase) ✅
- **Decision**: Use `mixedCase` for controllers and models with suffixes
- **Examples**: `userController.php`, `userModel.php`
- **Rationale**: Prevents naming conflicts, makes purpose clear
- **Note**: Recommended but not strictly required
- **Views**: Flexible naming - use what makes sense

### 4. Single Routes File ✅
- **Decision**: All routes in `app/routes.php` (single file)
- **Rationale**: Simpler to maintain, easier to see all routes at once
- **Alternative considered**: Separate files for web/api/cli routes (rejected - adds complexity)

### 5. User Helpers Location ✅
- **Decision**: `app/helpers/` for custom helper functions
- **Rationale**: Clean organization, clear separation from framework helpers

### 6. View Override System ✅
- **Decision**: App views override system views (fallback pattern)
- **Rationale**: Allows customizing framework views (like error pages) without modifying core
- **Implementation**: Check `app/views/` first, fall back to `system/views/`

### 7. Minimal Database Layer ✅
- **Decision**: Connection management + prepared query helpers, NO ORM or forced migrations
- **Rationale**: Databases are powerful; abstractions often degrade their usefulness
- **Approach**: 
  - Query helpers for common operations (insert, bulk insert, replace)
  - Raw queries with explicit opt-in
  - SQL file loading for table creation and seeding
  - Developer controls database structure

### 8. Native PHP Templates ✅
- **Decision**: No template engine; use native PHP + HTMX
- **Included**: Partial views (header/footer includes), view helpers
- **Rationale**: Keeps it simple, works great with HTMX

### 9. File-Based Sessions (Default) ✅
- **Decision**: File-based by default, extensible to database/Redis via modules
- **Rationale**: No database requirement for basic usage

### 10. Security Built-In ✅
- **CSRF**: Built-in but optional (middleware-based)
- **XSS**: `esc()` helper function for escaping
- **SQL Injection**: Prepared statement helpers
- **Password Hashing**: Part of Authentication module
- **Rate Limiting**: Core middleware

### 11. Simple Input Validation ✅
- **Decision**: Include validation with clean error handling
- **Approach**: Simple, modern validation that plays well with HTMX
- **Functions**: `validate()` for simple cases, `validator()` for more control
- **View helpers**: `input_value()`, `form_error()`, `has_error()`, `show_error()`

### 12. CLI Support ✅
- **Decision**: CodeIgniter 3 style CLI
- **Uses**: Run controllers from CLI, custom commands, cron jobs

### 13. MIT License ✅
- **Decision**: MIT License for maximum freedom and adoption

### 14. Assets Directory ✅
- **Decision**: `/assets/` instead of `/public/`
- **Rationale**: Root is the main public folder; assets are just one part of it

### 15. Upgrade Strategy ✅
- **Decision**: System folder is completely replaceable
- **User Code**: Lives in `app/` and is never touched by upgrades
- **Versioning**: Semantic versioning with clear breaking change documentation

### 16. JSON Response Helper ✅
- **Decision**: Include `json()` helper function in core
- **Rationale**: Essential for API development, used frequently
- **Example**: `return json(['status' => 'ok'], 200);`

### 17. Route Syntax: `controller/function` ✅
- **Decision**: Use `controller/function` format (NOT `controller@function`)
- **Examples**: `'userController/show'`, `'apiController/users'`
- **Rationale**: 
  - Matches the default URL pattern (controller/function)
  - Consistent with CodeIgniter 3 style  
  - Simpler than Laravel's `@` syntax
  - More intuitive - URLs and routes use the same format
- **Alternative considered**: `controller@function` like Laravel (rejected - inconsistent with URL pattern)

### 18. Modern & Logical Helper Names ✅
- **Decision**: Use descriptive, self-documenting helper function names
- **Philosophy**: Not stuck in CI3, but avoid Laravel-isms. Be modern and logical.
- **Examples**:
  - `input_value()` instead of `old()` (more descriptive)
  - `form_error()` instead of `error()` (more specific)
  - `has_error()`, `show_error()` (self-documenting)
  - `has_flash()`, `show_flash()` (clear intent)
  - `validate()` and `validator()` (simple and clear)
  - `redirect()` and `redirect_back()` (no method chaining)
- **Keep simple**: `view()`, `json()`, `dd()`, `esc()`, `csrf_field()` (universally clear)
- **Rationale**:
  - Function names should describe what they do
  - Avoid ambiguous names (`old()` - old what?)
  - Avoid overly generic names (`error()` - error for what?)
  - Self-documenting code is easier to learn and maintain
  - Modern without unnecessary complexity
- **Rejected patterns**:
  - Method chaining on redirects (Laravel-ism, less readable)
  - `old()` (vague, Laravel-specific)
  - `$request->all()` (Laravel-specific)

---

## Framework Structure

### Core System (v1.0)
Must-have features in the initial release:
1. Router with middleware
2. Request/Response objects
3. MVC structure
4. Error handling & logging
5. Security features (CSRF, XSS, rate limiting)
6. Helper functions
7. CLI support
8. Session management
9. View rendering with partials

### Core Modules (v1.1)
Included but loaded on-demand:
1. Database (MySQL, PostgreSQL)
2. Authentication
3. .env support
4. Email (SMTP)
5. Storage/filesystem

### Extended Modules (v1.2+)
Future development:
1. Input validation (might move to core)
2. Event system (might move to core)
3. HTTP client (might move to core)
4. Session drivers (database, Redis)

### Advanced Modules (v2.0+)
Long-term goals:
1. MongoDB
2. AI interface (OpenAI, Claude)
3. Accounts/multi-tenancy
4. Messaging (Slack)
5. Queue/background jobs
6. Caching layer
7. S3 storage
8. WebSockets

---

## What's NOT Included

### Explicitly Decided Against (v1.0)
- ❌ **Code generation** - controllers/models should be simple to create manually
- ❌ **Auto-documentation** - minimal but informative docs instead
- ❌ **Extensive query builder** - degrades database usefulness
- ❌ **Forced migrations** - developer controls database structure
- ❌ **Complex template engine** - native PHP + HTMX is sufficient
- ❌ **Build system requirements** - keep it simple
- ❌ **ORM or Active Record** - raw queries preferred

### Not Included Initially (Maybe Later)
- 🤔 **Cookie helpers** - PHP native functions are sufficient for now
- 🤔 **URL helpers** - PHP native functions work for v1.0 (could add `base_url()`, `current_url()` later)
- 🤔 **Redirect with flash** - not in v1.0 (flash messages exist, combine manually)
- 🤔 **Route groups** - OK to add but not a priority (middleware makes it more useful)
- 🤔 **Pagination** - nice to have later
- 🤔 **Localization (i18n)** - future consideration

---

## Technical Specifications

### Requirements
- **PHP**: 7.0+ (7.4+ recommended)
- **Web Server**: Apache (with mod_rewrite) or Nginx
- **Composer**: For dependency management
- **Optional**: Database (MySQL, PostgreSQL, MongoDB)

### Directory Layout
```
/
├── index.php              # Entry point
├── app/                   # User code (never touched by upgrades)
├── system/                # Framework code (replaceable)
├── assets/                # Public files (CSS, JS, images)
├── docs/                  # Documentation
└── vendor/                # Composer packages
```

### Inspiration Sources
- **CodeIgniter 3**: Simplicity, ease of use
- **Gin (Go)**: Middleware pattern
- **Modern PHP**: Best practices without bloat

---

## Open Questions & Considerations

### Nice to Have (Later Priority)
1. **Pagination**: Useful but not critical for v1.0
2. **Flash Messages**: Session-based messages - might include in core
3. **Form Helpers**: csrf_field(), old(), error() - already decided to include
4. **Localization**: Multi-language support - future consideration
5. **Cookie Management**: Beyond PHP natives - evaluate need

### To Investigate
1. **Event System**: Core or module? Leaning toward core for extensibility
2. **HTTP Client**: Core or module? API Client could wrap it - leaning toward core
3. **Validation**: Core or module? Leaning toward core due to importance
4. **WebSockets**: Module or future? Interesting but complex

### Development Strategy
1. Build core framework first (Router, MVC, security, CLI)
2. Add essential modules (Database, Auth, .env, Email)
3. Test with real applications
4. Iterate based on actual usage
5. Release when stable and well-documented

---

## Next Steps

### Immediate (Start Development)
1. ✅ Finalize description document
2. Set up basic project structure
3. Implement Router with middleware support
4. Build Request/Response objects
5. Create base Controller and Model classes
6. Implement error handling
7. Build helper functions

### Short-term (v1.0)
1. Complete core framework features
2. Write comprehensive documentation
3. Create example applications
4. Set up testing framework
5. Write tests for core features

### Medium-term (v1.1)
1. Develop core modules
2. Create module documentation
3. Build more examples
4. Beta testing with real projects

### Long-term (v2.0+)
1. Community feedback integration
2. Advanced module development
3. Performance optimization
4. Ecosystem growth (community modules)

---

## Success Criteria

### For v1.0 Release
- [ ] Build a real application with it
- [ ] Comprehensive documentation
- [ ] All core features working
- [ ] Clean, readable code
- [ ] Fast and lightweight
- [ ] Easy to install (under 5 minutes)
- [ ] Easy to learn (tutorial completable in 30 minutes)

### For Open Source Release
- [ ] Stable and well-tested
- [ ] Complete documentation
- [ ] Example projects
- [ ] Contributing guidelines
- [ ] Issue templates
- [ ] CI/CD pipeline
- [ ] Version 1.0 or higher

---

## Notes

- Keep it simple, always
- Optimize for developer happiness
- Don't try to do everything
- Focus on the 80% use case
- Make the right thing easy, not impossible to do the wrong thing
- Documentation is as important as code
- Every feature should earn its place in the framework

---

**Remember**: A framework is successful when developers enjoy using it and can build real applications quickly. Seed should feel like a helpful tool, not a restrictive system.

---

## Quick Reference: All Decisions

### Architectural Decisions
1. ✅ Middleware pattern (not Core Controller)
2. ✅ Hybrid OOP + Procedural
3. ✅ mixedCase file naming with suffixes
4. ✅ Single routes file (`app/routes.php`)
5. ✅ Custom helpers in `app/helpers/`
6. ✅ View override system (app overrides system)
7. ✅ Minimal database layer (no ORM)
8. ✅ Native PHP templates (no template engine)
9. ✅ File-based sessions (default)

### Security & Features
10. ✅ Security built-in (CSRF, XSS, rate limiting)
11. ✅ Simple input validation
12. ✅ CLI support (CI3 style)
13. ✅ MIT License
14. ✅ Assets directory (`/assets/`)
15. ✅ Replaceable system folder

### Syntax & Conventions
16. ✅ JSON response helper
17. ✅ Route syntax: `controller/function` (not `@`)
18. ✅ Modern & logical helper names (descriptive, self-documenting)

### Core Philosophy
- **Modern and logical**: Not stuck in CI3, not copying Laravel
- **Descriptive over terse**: `input_value()` not `old()`
- **Specific over generic**: `form_error()` not `error()`
- **Simple, not simplistic**: No method chaining, clean function calls
- **Developer-friendly**: Easy to learn, remember, and use

