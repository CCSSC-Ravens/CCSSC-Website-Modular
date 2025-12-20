# Codebase Index

## Project Overview

**CCSSC Project - Modular Laravel Application**

A modular Laravel 12 application for the Gordon College - College of Computer Studies Student Council website. The project follows a feature-first, modular architecture where each module is self-contained with its own routes, controllers, views, and resources.

---

## Technology Stack

### Backend
- **Framework**: Laravel 12.0
- **PHP**: ^8.2
- **Testing**: Pest PHP ^3.8
- **Code Quality**: Laravel Pint ^1.24

### Frontend
- **Build Tool**: Vite ^7.0.7
- **CSS Framework**: Tailwind CSS ^4.0.0
- **JavaScript**: Vanilla JS (via `resources/js/app.js`)
- **Package Manager**: npm

### Development Tools
- **Package Manager**: Composer
- **Process Manager**: Concurrently (for running dev server, queue, and Vite)
- **Logging**: Laravel Pail ^1.2.2

---

## Project Structure

```
CCSSC_Project-Modular-/
├── app/
│   ├── Http/
│   │   └── Controllers/          # Shared controllers (if any)
│   ├── Models/
│   │   └── User.php              # User authentication model
│   ├── Modules/                  # ⭐ MODULAR ARCHITECTURE ⭐
│   │   ├── Home/
│   │   │   ├── Http/
│   │   │   │   └── Controllers/
│   │   │   │       └── HomeController.php
│   │   │   ├── Resources/
│   │   │   │   └── views/
│   │   │   │       └── welcome.blade.php
│   │   │   └── routes/
│   │   │       └── web.php
│   │   └── News/
│   │       ├── Http/
│   │       │   └── Controllers/
│   │       │       └── NewsController.php
│   │       ├── Resources/
│   │       │   └── views/
│   │       │       └── index.blade.php
│   │       └── routes/
│   │           └── web.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── ModulesServiceProvider.php  # ⭐ Auto-loads modules
├── bootstrap/
│   ├── app.php
│   └── providers.php             # Service provider registration
├── config/                       # Laravel configuration files
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/                       # Public assets
├── resources/
│   ├── css/
│   │   └── app.css              # Tailwind CSS entry point
│   ├── js/
│   │   ├── app.js               # JavaScript entry point
│   │   └── bootstrap.js
│   └── views/
│       └── components/
│           └── navbar.blade.php  # Shared Blade component
├── routes/
│   ├── web.php                   # Minimal (modules register own routes)
│   └── console.php
├── storage/                      # Laravel storage
├── tests/                        # Pest PHP tests
├── vendor/                       # Composer dependencies
├── composer.json                 # PHP dependencies
├── package.json                  # Node dependencies
├── vite.config.js               # Vite configuration
└── README.md                     # Project documentation
```

---

## Module Architecture

### How Modules Work

The application uses a **modular architecture** where each feature is a self-contained module under `app/Modules/`. Each module can have:

- **Routes**: `routes/web.php` and/or `routes/api.php`
- **Controllers**: `Http/Controllers/`
- **Views**: `Resources/views/` (namespaced by module name)
- **Migrations**: `database/migrations/` (optional)
- **Translations**: `Resources/lang/` (optional)
- **Config**: `Config/config.php` (optional)

### Module Auto-Loading

The `ModulesServiceProvider` automatically discovers and loads modules:

1. **Scans** `app/Modules/*` directory
2. **Loads routes** with appropriate middleware (`web` or `api`)
3. **Registers views** under kebab-case namespace (e.g., `Home` → `home::`)
4. **Loads migrations** if present
5. **Loads translations** if present
6. **Merges config** if present

### View Namespacing

Module views are accessed via namespaced syntax:
```php
view('home::welcome')    // Home module → welcome.blade.php
view('news::index')      // News module → index.blade.php
```

---

## Current Modules

### 1. Home Module
**Location**: `app/Modules/Home/`

**Purpose**: Landing page for the CCSSC website

**Routes**:
- `GET /` → `HomeController@index` (named: `home.index`)

**Views**:
- `welcome.blade.php` - Main landing page with hero section

**Features**:
- Hero section with "Soaring beyond limits" tagline
- Navigation bar with logo, links (About Us, Committees, News), and search
- Orange/red color scheme (#B13407)
- Responsive design with Tailwind CSS

**Controller**: `HomeController.php`
```php
namespace App\Modules\Home\Http\Controllers;
// Returns: view('home::welcome')
```

---

### 2. News Module
**Location**: `app/Modules/News/`

**Purpose**: News page for CCSSC announcements

**Routes**:
- `GET /newsPage` → `NewsController@index` (named: `news.index`)

**Views**:
- `index.blade.php` - Basic news page template

**Features**:
- Simple welcome page structure
- Ready for news content implementation

**Controller**: `NewsController.php`
```php
namespace App\Modules\News\Http\Controllers;
// Returns: view('news::index')
```

---

## Key Files

### Service Providers

#### `app/Providers/ModulesServiceProvider.php`
**Purpose**: Auto-discovers and loads all modules

**Functionality**:
- Scans `app/Modules/*` directories
- Loads web routes with `web` middleware
- Loads API routes with `api` middleware (if exists)
- Registers views with kebab-case namespace
- Loads migrations, translations, and config (if present)

**Registration**: Registered in `bootstrap/providers.php`

---

### Configuration Files

#### `composer.json`
- PSR-4 autoloading for `App\Modules\`
- Laravel 12.0 framework
- Development dependencies: Pest, Pint, Sail

#### `package.json`
- Vite build tool
- Tailwind CSS v4
- Laravel Vite plugin
- Concurrently for dev scripts

#### `vite.config.js`
- Laravel Vite plugin configuration
- Tailwind CSS plugin
- Entry points: `resources/css/app.css`, `resources/js/app.js`

---

### Routes

#### `routes/web.php`
Minimal file - modules register their own routes via `ModulesServiceProvider`.

---

## Shared Resources

### Components
- `resources/views/components/navbar.blade.php` - Shared navigation component

### Models
- `app/Models/User.php` - Laravel authentication model with HasFactory and Notifiable traits

---

## Development Workflow

### Adding a New Module

1. **Create module structure**:
   ```
   app/Modules/YourModule/
     ├── Http/Controllers/
     ├── Resources/views/
     └── routes/
   ```

2. **Create Controller** (`Http/Controllers/YourModuleController.php`):
   ```php
   <?php
   namespace App\Modules\YourModule\Http\Controllers;
   use Illuminate\Routing\Controller;
   
   class YourModuleController extends Controller {
       public function index() {
           return view('your-module::index');
       }
   }
   ```

3. **Create Routes** (`routes/web.php`):
   ```php
   <?php
   use App\Modules\YourModule\Http\Controllers\YourModuleController;
   use Illuminate\Support\Facades\Route;
   
   Route::get('/your-route', [YourModuleController::class, 'index'])
        ->name('your-module.index');
   ```

4. **Create View** (`Resources/views/index.blade.php`):
   ```blade
   <!DOCTYPE html>
   <html>
   <head>
       <title>Your Module</title>
   </head>
   <body>
       <h1>Your Module Content</h1>
   </body>
   </html>
   ```

5. **Clear caches**:
   ```bash
   composer dump-autoload
   php artisan config:clear route:clear view:clear
   ```

---

## Available Commands

### Development
```bash
# Start development server (server + queue + vite)
composer dev

# Setup project (install dependencies, generate key, migrate)
composer setup

# Run tests
composer test
```

### Cache Management
```bash
# Clear all caches after module changes
composer dump-autoload && php artisan config:clear && php artisan route:clear && php artisan view:clear
```

### Build
```bash
# Build assets for production
npm run build

# Watch assets for development
npm run dev
```

---

## Design System

### Colors
- **Primary**: Orange/Red (#B13407)
- **Background**: Orange (#B13407)
- **Text**: White (on colored backgrounds)
- **Accent**: Orange-800

### Typography
- **Font**: Instrument Sans (from Bunny Fonts)
- **Headings**: Bold, large sizes (text-7xl for hero)

### UI Components
- Rounded corners (rounded-2xl)
- Border styling
- Flexbox layouts
- Responsive design with Tailwind breakpoints

---

## Testing

- **Framework**: Pest PHP ^3.8
- **Test Location**: `tests/`
- **Structure**:
  - `Feature/` - Feature tests
  - `Unit/` - Unit tests
  - `Pest.php` - Pest configuration

---

## Database

### Migrations
- Standard Laravel migrations in `database/migrations/`
- Module-specific migrations can be placed in `app/Modules/{Module}/database/migrations/`

### Models
- `User` model in `app/Models/User.php`
- Module-specific models should be placed in `app/Modules/{Module}/Models/`

---

## Security & Authentication

- Laravel's built-in authentication system
- User model with password hashing
- CSRF protection via Laravel middleware
- Session management configured

---

## Notes

- Module view namespace is kebab-case of folder name: `PaymentsGateway` → `payments-gateway::`
- Shared layouts/partials can remain under `resources/views` or use a `Core` module
- Each module is self-contained and can be easily extracted or shared
- Follows Laravel best practices and PSR-4 autoloading standards

---

## Future Considerations

- Consider adding a `Core` module for shared layouts/components
- API routes can be added per module (`routes/api.php`)
- Module-specific migrations, translations, and configs are supported
- Consider implementing module service providers for module-specific boot logic

---

*Last Updated: Generated from codebase analysis*
*Laravel Version: 12.0*
*PHP Version: ^8.2*

