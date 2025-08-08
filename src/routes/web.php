<?php

use Illuminate\Support\Facades\Route;
use admin\shipping_charges\Controllers\ShippingMethodManagerController;
use admin\shipping_charges\Controllers\ShippingRateManagerController;
use admin\shipping_charges\Controllers\ShippingZoneManagerController;


Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
        Route::resource('shipping_methods', ShippingMethodManagerController::class);
        Route::post('shipping_methods/updateStatus', [ShippingMethodManagerController::class, 'updateStatus'])->name('shipping_methods.updateStatus');

        Route::resource('shipping_rates', ShippingRateManagerController::class);
});