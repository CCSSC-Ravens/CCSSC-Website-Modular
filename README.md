# Architecture Overview

This codebase uses a **Modular Laravel Architecture**. Each module is a self-contained feature package with its own controllers, views, routes, and configuration.

## Module Structure

```
app/Modules/{ModuleName}/
├── Config/
│   └── config.php          # Module-specific configuration
├── Http/
│   └── Controllers/        # Module controllers
├── Resources/
│   └── views/              # Module Blade templates
├── routes/
│   └── web.php             # Module routes
└── (optional) database/migrations/
```

## Current Modules

| Module    | Purpose                                             |
| --------- | --------------------------------------------------- |
| **Admin** | Admin panel (auth, dashboard, CRUD for posts/users) |
| **Home**  | Public homepage                                     |
| **News**  | News/articles display                               |

## How It Works

The `ModulesServiceProvider` automatically discovers and registers each module by:

1. **Loading configurations** - Merges module `Config/config.php` into Laravel's config
2. **Registering routes** - Auto-loads `routes/web.php` and `routes/api.php`
3. **Loading views** - Registers views with a namespace (e.g., `admin::dashboard`)
4. **Loading translations** - If `Resources/lang/` exists
5. **Loading migrations** - If `database/migrations/` exists

## Shared Resources

Outside of modules, shared components live in:

| Location                      | Purpose                                           |
| ----------------------------- | ------------------------------------------------- |
| `app/Models/`                 | Eloquent models (shared across modules)           |
| `app/Policies/`               | Authorization policies                            |
| `app/Services/`               | Business logic services (JWT, Session)            |
| `resources/views/components/` | Reusable Blade components (navbar, footer, cards) |

## Benefits of This Approach

-   **Separation of concerns** - Each feature is self-contained
-   **Scalability** - Add new modules without touching existing code
-   **Maintainability** - Easy to find and modify feature-specific code
-   **Reusability** - Modules can potentially be extracted to packages

---

## Adding a New Module

Module views use namespaced lookups:

```php
return view('home::welcome');   // from Home module
return view('news::index');     // from News module
return view('admin::dashboard'); // from Admin module
```

1. Create folders:

```
app/Modules/Blog/
  Http/Controllers/
  Resources/views/
  routes/
```

2. Controller `app/Modules/Blog/Http/Controllers/BlogController.php`:

```php
<?php
namespace App\Modules\Blog\Http\Controllers;
use Illuminate\Routing\Controller;

class BlogController extends Controller {
    public function index() { return view('blog::index'); }
}
```

3. Routes `app/Modules/Blog/routes/web.php`:

```php
<?php
use Illuminate\Support\Facades\Route;
use App\Modules\Blog\Http\Controllers\BlogController;

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
```

4. View `app/Modules/Blog/Resources/views/index.blade.php`:

```blade
<h1>Blog</h1>
```

5. Refresh and verify:

```
composer dump-autoload
php artisan config:clear route:clear view:clear
php artisan route:list --path=blog
```

### Useful Commands

Regenerate autoload and clear caches after adding/moving modules:

```bash
composer dump-autoload && php artisan config:clear && php artisan route:clear && php artisan view:clear
```

---

## Notes

-   Shared layouts/partials remain under `resources/views/components/`
-   Module view namespace is kebab-case of the folder name: `PaymentsGateway` → `payments-gateway::...`
-   This is a custom lightweight modular implementation (not using external packages)
