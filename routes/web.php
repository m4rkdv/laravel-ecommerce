<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop',[ShopController::class,'index'])->name('shop.index');
Route::get('/shop/{product_slug}',[ShopController::class,'product_details'])->name('shop.products.details');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/add',[CartController::class,'add_to_cart'])->name('cart.add');
Route::put('cart/increase-qty/{rowId}',[CartController::class,'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('cart/decrease-qty/{rowId}',[CartController::class,'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('cart/remove/{rowId}',[CartController::class,'remove_item'])->name('cart.remove');
Route::delete('cart/clear',[CartController::class,'empty_cart'])->name('cart.destroy');

Route::post('cart/apply-coupon',[CartController::class,'apply_coupon_code'])->name('cart.coupon.apply');
Route::delete('cart/remove-coupon',[CartController::class,'remove_coupon'])->name('cart.coupon.remove');

Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/add', [AdminController::class, 'add_brands'])->name('admin.brand.add');
    Route::post('/admin/brands/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'edit_brand'])->name('admin.brand.edit');
    Route::put('/admin/brands/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brands/{id}/delete', [AdminController::class, 'delete_brand'])->name('admin.brand.delete');

    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categories/add', [AdminController::class, 'add_category'])->name('admin.categories.add');
    Route::post('/admin/categories/store', [AdminController::class, 'category_store'])->name('admin.categories.store');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'edit_category'])->name('admin.categories.edit');
    Route::put('/admin/categories/update', [AdminController::class, 'category_update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{id}/delete', [AdminController::class, 'category_delete'])->name('admin.categories.delete');

    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/add', [AdminController::class, 'add_product'])->name('admin.products.add');
    Route::post('/admin/products/store', [AdminController::class, 'product_store'])->name('admin.products.store');
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.products.edit');
    Route::put('/admin/products/update', [AdminController::class, 'product_update'])->name('admin.products.update');
    Route::delete('/admin/products/{id}/delete', [AdminController::class, 'product_delete'])->name('admin.products.delete');

    Route::get('/admin/coupons',[AdminController::class,'coupons'])->name('admin.coupons');
    Route::get('/admin/coupons/add',[AdminController::class,'add_coupon'])->name('admin.coupons.add');
    Route::post('/admin/coupons/store',[AdminController::class,'store_coupon'])->name('admin.coupons.store');
    Route::get('/admin/coupons/{id}/edit',[AdminController::class,'edit_coupon'])->name('admin.coupons.edit');
    Route::put('/admin/coupons/update',[AdminController::class,'update_coupon'])->name('admin.coupons.update');
    Route::delete('/admin/coupons/{id}/delete',[AdminController::class,'delete_coupon'])->name('admin.coupons.delete');
});
