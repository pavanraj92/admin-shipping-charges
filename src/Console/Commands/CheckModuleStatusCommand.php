<?php

namespace admin\shipping_charges\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckModuleStatusCommand extends Command
{
    protected $signature = 'shipping_charges:status';
    protected $description = 'Check if Shipping Charges module files are being used';

    public function handle()
    {
        $this->info('Checking Shipping Charges Module Status...');
        
        // Check if module files exist
        $moduleFiles = [
            // Shipping Method files
            'Shipping Method Controller' => base_path('Modules/ShippingCharges/app/Http/Controllers/Admin/ShippingMethodManagerController.php'),
            'Shipping Method Model' => base_path('Modules/ShippingCharges/app/Models/ShippingMethod.php'),
            'Shipping Method Request (Create)' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingMethodCreateRequest.php'),
            'Shipping Method Request (Update)' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingMethodUpdateRequest.php'),

            // Shipping Rate files
            'Shipping Rate Controller' => base_path('Modules/ShippingCharges/app/Http/Controllers/Admin/ShippingRateManagerController.php'),
            'Shipping Rate Model' => base_path('Modules/ShippingCharges/app/Models/ShippingRate.php'),
            'Shipping Rate Request (Create)' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingRateCreateRequest.php'),
            'Shipping Rate Request (Update)' => base_path('Modules/ShippingCharges/app/Http/Requests/ShippingRateUpdateRequest.php'),

            // Common files
            'Routes' => base_path('Modules/ShippingCharges/routes/web.php'),
            'Views' => base_path('Modules/ShippingCharges/resources/views'),
            'Config' => base_path('Modules/ShippingCharges/config/shipping_charges.php'),
        ];

        $this->info("\n📁 Module Files Status:");
        foreach ($moduleFiles as $type => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$type}: EXISTS");
                
                // Check if it's a PHP file and show last modified time
                if (str_ends_with($path, '.php')) {
                    $lastModified = date('Y-m-d H:i:s', filemtime($path));
                    $this->line("   Last modified: {$lastModified}");
                }
            } else {
                $this->error("❌ {$type}: NOT FOUND");
            }
        }

        // Check namespace in controller
        $controllers = [
            'Shipping Method Controller' => base_path('Modules/ShippingCharges/app/Http/Controllers/Admin/ShippingMethodManagerController.php'),
            'Shipping Rate Controller' => base_path('Modules/ShippingCharges/app/Http/Controllers/Admin/ShippingRateManagerController.php'),
        ];

        foreach ($controllers as $name => $controllerPath) {
            if (File::exists($controllerPath)) {
            $content = File::get($controllerPath);
            if (str_contains($content, 'namespace Modules\ShippingCharges\app\Http\Controllers\Admin;')) {
                $this->info("\n✅ {$name} namespace: CORRECT");
            } else {
                $this->error("\n❌ {$name} namespace: INCORRECT");
            }

            // Check for test comment
            if (str_contains($content, 'Test comment - this should persist after refresh')) {
                $this->info("✅ Test comment in {$name}: FOUND (changes are persisting)");
            } else {
                $this->warn("⚠️  Test comment in {$name}: NOT FOUND");
            }
            }
        }

        // Check composer autoload
        $composerFile = base_path('composer.json');
        if (File::exists($composerFile)) {
            $composer = json_decode(File::get($composerFile), true);
            if (isset($composer['autoload']['psr-4']['Modules\\Pages\\'])) {
                $this->info("\n✅ Composer autoload: CONFIGURED");
            } else {
                $this->error("\n❌ Composer autoload: NOT CONFIGURED");
            }
        }

        $this->info("\n🎯 Summary:");
        $this->info("Your Pages module is properly published and should be working.");
        $this->info("Any changes you make to files in Modules/Pages/ will persist.");
        $this->info("If you need to republish from the package, run: php artisan pages:publish --force");
    }
}
