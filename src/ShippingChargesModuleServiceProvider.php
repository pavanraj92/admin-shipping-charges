<?php

namespace admin\shipping_charges;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ShippingChargesModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/ShippingCharges/resources/views'), // Published module views first
            resource_path('views/admin/shipping_charges'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'shipping_charges');

        $this->mergeConfigFrom(__DIR__.'/../config/shipping_charges.php', 'shipping_charges.constants');

        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/ShippingCharges/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/ShippingCharges/resources/views'), 'shipping-charges-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/ShippingCharges/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/ShippingCharges/database/migrations'));
        }

        // Also merge config from published module if it exists
        if (file_exists(base_path('Modules/ShippingCharges/config/shipping_charges.php'))) {
            $this->mergeConfigFrom(base_path('Modules/ShippingCharges/config/shipping_charges.php'), 'shipping_charges.constants');
        }
        
        // Only publish automatically during package installation, not on every request
        // Use 'php artisan categories:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();
        
        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../config/' => base_path('Modules/ShippingCharges/config/'),
            __DIR__ . '/../database/' => base_path('Modules/ShippingCharges/database/'),
            __DIR__ . '/../resources/views' => base_path('Modules/ShippingCharges/resources/views/'),
        ], 'shipping_charge');

        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();
            
        $slug = $admin->website_slug ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                // Load routes from published module first, then fallback to package
                if (file_exists(base_path('Modules/ShippingCharges/routes/web.php'))) {
                    $this->loadRoutesFrom(base_path('Modules/ShippingCharges/routes/web.php'));
                } else {
                    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                }
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\shipping_charges\Console\Commands\PublishShippingChargesModuleCommand::class,
                \admin\shipping_charges\Console\Commands\CheckModuleStatusCommand::class,
                \admin\shipping_charges\Console\Commands\DebugShippingChargesCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/ShippingRateManagerController.php' => base_path('Modules/ShippingCharges/app/Http/Controllers/Admin/ShippingRateManagerController.php'),
            __DIR__ . '/../src/Controllers/ShippingMethodManagerController.php' => base_path('Modules/ShippingCharges/app/Http/Controllers/Admin/ShippingMethodManagerController.php'),
            // Models
            __DIR__ . '/../src/Models/ShippingRate.php' => base_path('Modules/ShippingCharges/app/Models/ShippingRate.php'),
            __DIR__ . '/../src/Models/ShippingMethod.php' => base_path('Modules/ShippingCharges/app/Models/ShippingMethod.php'),
            __DIR__ . '/../src/Models/ShippingZone.php' => base_path('Modules/ShippingCharges/app/Models/ShippingZone.php'),
            // Requests
            __DIR__ . '/../src/Requests/ShippingRateCreateRequest.php' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingRateCreateRequest.php'),
            __DIR__ . '/../src/Requests/ShippingRateUpdateRequest.php' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingRateUpdateRequest.php'),
            __DIR__ . '/../src/Requests/ShippingMethodCreateRequest.php' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingMethodCreateRequest.php'),
            __DIR__ . '/../src/Requests/ShippingMethodUpdateRequest.php' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingMethodUpdateRequest.php'),
            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/ShippingCharges/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));
                
                // Read the source file
                $content = File::get($source);
                
                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);
                
                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\shipping\\Controllers;' => 'namespace Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\shipping\\Models;' => 'namespace Modules\\ShippingCharges\\app\\Models;',
            'namespace admin\\shipping\\Requests;' => 'namespace Modules\\ShippingCharges\\app\\Http\\Requests;',

            // Use statements transformations
            'use admin\\shipping\\Controllers\\' => 'use Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin\\',
            'use admin\\shipping\\Models\\' => 'use Modules\\ShippingCharges\\app\\Models\\',
            'use admin\\shipping\\Requests\\' => 'use Modules\\ShippingCharges\\app\\Http\\Requests\\',

            // Class references in routes
            'admin\\shipping\\Controllers\\ShippingMethodController' => 'Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin\\ShippingMethodController',
            'admin\\shipping\\Controllers\\ShippingRateManagerController' => 'Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin\\ShippingRateManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\shipping\\Models\\ShippingMethod;',
            'use Modules\\ShippingCharges\\app\\Models\\ShippingMethod;',
            $content
        );
        $content = str_replace(
            'use admin\\shipping\\Models\\ShippingRate;',
            'use Modules\\ShippingCharges\\app\\Models\\ShippingRate;',
            $content
        );
        $content = str_replace(
            'use admin\\shipping\\Requests\\ShippingMethodCreateRequest;',
            'use Modules\\ShippingCharges\\app\\Http\\Requests\\ShippingMethodCreateRequest;',
            $content
        );
        $content = str_replace(
            'use admin\\shipping\\Requests\\ShippingMethodUpdateRequest;',
            'use Modules\\ShippingCharges\\app\\Http\\Requests\\ShippingMethodUpdateRequest;',
            $content
        );
        $content = str_replace(
            'use admin\\shipping\\Requests\\ShippingRateCreateRequest;',
            'use Modules\\ShippingCharges\\app\\Http\\Requests\\ShippingRateCreateRequest;',
            $content
        );
        $content = str_replace(
            'use admin\\shipping\\Requests\\ShippingRateUpdateRequest;',
            'use Modules\\ShippingCharges\\app\\Http\\Requests\\ShippingRateUpdateRequest;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\shipping\\Controllers\\ShippingMethodController',
            'Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin\\ShippingMethodController',
            $content
        );
        $content = str_replace(
            'admin\\shipping\\Controllers\\ShippingRateManagerController',
            'Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin\\ShippingRateManagerController',
            $content
        );

        return $content;
    }
}
