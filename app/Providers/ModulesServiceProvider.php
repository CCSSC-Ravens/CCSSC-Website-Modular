<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ModulesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $modulesPath = base_path('app/Modules');
        if (! is_dir($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $dir) {
            $module = basename($dir);
            $namespace = Str::kebab($module);

            $config = $dir . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php';
            if (is_file($config)) {
                $this->mergeConfigFrom($config, $namespace);
            }
        }

        foreach (File::directories($modulesPath) as $dir) {
            $module = basename($dir);
            $namespace = Str::kebab($module);

            $web = $dir . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php';
            if (is_file($web)) {
                Route::middleware('web')->group($web);
            }

            $api = $dir . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'api.php';
            if (is_file($api)) {
                Route::prefix('api')->middleware('api')->group($api);
            }

            $views = $dir . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'views';
            if (is_dir($views)) {
                $this->loadViewsFrom($views, $namespace);
            }

            $langs = $dir . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'lang';
            if (is_dir($langs)) {
                $this->loadTranslationsFrom($langs, $namespace);
            }

            $migrations = $dir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
            if (is_dir($migrations)) {
                $this->loadMigrationsFrom($migrations);
            }
        }
    }
}
