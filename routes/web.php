<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\admin\UserController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/shop/{slug}', [ShopController::class, 'shop'])->name('shop');
Route::get('/shop/{main_category?}/{slug}', [ShopController::class, 'product_list'])->name('shop2');
Route::get('/shop/{main_category?}/{sub_category?}/{slug}', [ShopController::class, 'product_list'])->name('shop3');
Route::get('/product_details/{slug}', [ShopController::class, 'productDetails'])->name('product_details');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/add-to-cart', [ShopController::class, 'addToCart'])->name('addToCart');
    Route::post('/ajax_item_price', [ShopController::class, 'ajax_item_price'])->name('ajax_item_price');
    Route::get('/addToWishlist', [ShopController::class, 'addToWishlist'])->name('addToWishlist');

    //cart 
    Route::get('/cart_remode/{remove_id}', [OrderController::class, 'cart_remove']);
    Route::get('/qty_update', [OrderController::class, 'qty_update']);

    Route::get('/cart', [HomeController::class, 'cart']);
    Route::get('/addTocart', [HomeController::class, 'addToCart']);
    Route::get('/removeTocart', [HomeController::class, 'removeTocart']);

    // my order
    Route::get('/my-order', [HomeController::class, 'orders']);
    Route::get('/orderdetails/{id}', [HomeController::class, 'orderdetails']);
    Route::get('/wishlist', [HomeController::class, 'wishlist']);
    Route::get('/profile', [HomeController::class, 'profile']);
    Route::post('/profile/update', [HomeController::class, 'profile_update']);


    Route::get('/buy-now', [CheckoutController::class, 'buy_now_checkout'])->name('buy_now_checkout');
    Route::post('/order_place_buynow', [OrderController::class, 'order_place_buynow'])->name('order_place_buynow');
    Route::post('/success_payment', [OrderController::class, 'success_payment'])->name('success_payment');
    Route::get('/payment/razorpay', [OrderController::class, 'payment_razorpay'])->name('payment_razorpay');

    Route::get('/get_city_by_state', [UserController::class, 'get_city_by_state'])->name('get_city_by_state');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/adminauth.php';
