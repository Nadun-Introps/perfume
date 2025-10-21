<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\OnepageController;
use Webkul\Shop\Http\Controllers\StripeController;

/**
 * Cart routes.
 */
Route::controller(CartController::class)->prefix('checkout/cart')->group(function () {
    Route::get('', 'index')->name('shop.checkout.cart.index');
});

Route::controller(OnepageController::class)->prefix('checkout/onepage')->group(function () {
    Route::get('', 'index')->name('shop.checkout.onepage.index');

    Route::get('success', 'success')->name('shop.checkout.onepage.success');
});

// Stripe routes
Route::controller(StripeController::class)->prefix('stripe')->group(function () {
    Route::post('process', 'process')->name('shop.stripe.process');
    Route::post('confirm', 'confirm')->name('shop.stripe.confirm');
    Route::get('return', 'handleReturn')->name('shop.stripe.return');
    Route::post('webhook', 'webhook')->name('shop.stripe.webhook');
});
