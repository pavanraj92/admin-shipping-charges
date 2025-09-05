<?php

namespace admin\shipping_charges\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishShippingChargesModuleCommand extends Command
{
    protected $signature = 'shipping_charges:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Shipping Charges module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Shipping Charges module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/ShippingCharges');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'shipping_charge',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Shipping Charges module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/shipping_charges/src

        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/ShippingMethodManagerController.php' => base_path('Modules/ShippingCharges/app/Http/Controllers/Admin/ShippingMethodManagerController.php'),
            $basePath . '/Controllers/ShippingRateManagerController.php' => base_path('Modules/ShippingCharges/app/Http/Controllers/Admin/ShippingRateManagerController.php'),

            // Models
            $basePath . '/Models/ShippingMethod.php' => base_path('Modules/ShippingCharges/app/Models/ShippingMethod.php'),
            $basePath . '/Models/ShippingRate.php' => base_path('Modules/ShippingCharges/app/Models/ShippingRate.php'),
            $basePath . '/Models/ShippingZone.php' => base_path('Modules/ShippingCharges/app/Models/ShippingZone.php'),

            // Requests
            $basePath . '/Requests/ShippingMethodCreateRequest.php' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingMethodCreateRequest.php'),
            $basePath . '/Requests/ShippingMethodUpdateRequest.php' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingMethodUpdateRequest.php'),
            $basePath . '/Requests/ShippingRateCreateRequest.php' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingRateCreateRequest.php'),
            $basePath . '/Requests/ShippingRateUpdateRequest.php' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingRateUpdateRequest.php'),

            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/ShippingCharges/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\shipping_charges\\Controllers;' => 'namespace Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\shipping_charges\\Models;' => 'namespace Modules\\ShippingCharges\\app\\Models;',
            'namespace admin\\shipping_charges\\Requests;' => 'namespace Modules\\ShippingCharges\\app\\Http\\Requests;',

            // Use statements transformations
            'use admin\\shipping_charges\\Controllers\\' => 'use Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin\\',
            'use admin\\shipping_charges\\Models\\' => 'use Modules\\ShippingCharges\\app\\Models\\',
            'use admin\\shipping_charges\\Requests\\' => 'use Modules\\ShippingCharges\\app\\Http\\Requests\\',

            // Class references in routes
            'admin\\shipping_charges\\Controllers\\ShippingMethodManagerController' => 'Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin\\ShippingMethodManagerController',
            'admin\\shipping_charges\\Controllers\\ShippingRateManagerController' => 'Modules\\ShippingCharges\\app\\Http\\Controllers\\Admin\\ShippingRateManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace('use admin\\shipping_charges\\Models\\ShippingMethod;', 'use Modules\\ShippingCharges\\app\\Models\\ShippingMethod;', $content);
            $content = str_replace('use admin\\shipping_charges\\Models\\ShippingRate;', 'use Modules\\ShippingCharges\\app\\Models\\ShippingRate;', $content);
            $content = str_replace('use admin\\shipping_charges\\Models\\ShippingZone;', 'use Modules\\ShippingCharges\\app\\Models\\ShippingZone;', $content);
            $content = str_replace('use admin\\shipping_charges\\Requests\\ShippingMethodCreateRequest;', 'use Modules\\ShippingCharges\\app\\Http\\Requests\\ShippingMethodCreateRequest;', $content);
            $content = str_replace('use admin\\shipping_charges\\Requests\\ShippingMethodUpdateRequest;', 'use Modules\\ShippingCharges\\app\\Http\\Requests\\ShippingMethodUpdateRequest;', $content);
            $content = str_replace('use admin\\shipping_charges\\Requests\\ShippingRateCreateRequest;', 'use Modules\\ShippingCharges\\app\\Http\\Requests\\ShippingRateCreateRequest;', $content);
            $content = str_replace('use admin\\shipping_charges\\Requests\\ShippingRateUpdateRequest;', 'use Modules\\ShippingCharges\\app\\Http\\Requests\\ShippingRateUpdateRequest;', $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\ShippingCharges\\'])) {
            $composer['autoload']['psr-4']['Modules\\ShippingCharges\\'] = 'Modules/ShippingCharges/app/';

            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
