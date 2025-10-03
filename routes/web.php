<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features as FortifyFeatures;
use Livewire\Volt\Volt;
use App\Livewire\Store\CartPage;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Public Storefront
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $products = Product::query()
        ->where('is_active', true)
        ->latest()
        ->take(12)
        ->get();

    return view('store.index', compact('products'));
})->name('store.index');

Route::get('/products/{product:slug}', function (Product $product) {
    abort_unless($product->is_active, 404);
    return view('store.show', compact('product'));
})->name('store.show');

/*
|--------------------------------------------------------------------------
| Authenticated Pages
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', CartPage::class)->name('cart.show');
    Route::get('/checkout', fn () => view('checkout.index'))->name('checkout.show');

    // Settings (Volt)
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                FortifyFeatures::canManageTwoFactorAuthentication()
                && FortifyFeatures::optionEnabled(FortifyFeatures::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

/*
|--------------------------------------------------------------------------
| Jetstream Dashboard (with sanctum + verification)
|--------------------------------------------------------------------------
*/
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $orders = \App\Models\Order::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('orders'));
    })->name('dashboard');
});



/*
|--------------------------------------------------------------------------
| Auth scaffolding
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
