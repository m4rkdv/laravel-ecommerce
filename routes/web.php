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

Route::get('/carrito',[CartController::class,'index'])->name('cart.index');
Route::post('/carrito/add',[CartController::class,'add_to_cart'])->name('cart.add');
Route::put('/carrito/increase-qty/{rowId}',[CartController::class,'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('/carrito/decrease-qty/{rowId}',[CartController::class,'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('/carrito/remove/{rowId}',[CartController::class,'remove_item'])->name('cart.remove');
Route::delete('/carrito/clear',[CartController::class,'empty_cart'])->name('cart.destroy');

Route::post('/carrito/aplicar-coupon',[CartController::class,'apply_coupon_code'])->name('cart.coupon.apply');
Route::delete('/carrito/quitar-coupon',[CartController::class,'remove_coupon'])->name('cart.coupon.remove');

Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');
Route::post('/realizar-pedido',[CartController::class,'place_an_order'])->name('cart.place.order');
Route::get('/pedido-confirmado',[CartController::class,'order_confirmation'])->name('cart.confirmation');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/account-pedidos', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/account-pedidos/{order_id}/detalles', [UserController::class, 'orders_details'])->name('user.orders.details');

});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/marcas', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/marcas/add', [AdminController::class, 'add_brands'])->name('admin.brand.add');
    Route::post('/admin/marcas/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/marcas/edit/{id}', [AdminController::class, 'edit_brand'])->name('admin.brand.edit');
    Route::put('/admin/marcas/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/marcas/{id}/delete', [AdminController::class, 'delete_brand'])->name('admin.brand.delete');

    Route::get('/admin/categorias', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categorias/add', [AdminController::class, 'add_category'])->name('admin.categories.add');
    Route::post('/admin/categorias/store', [AdminController::class, 'category_store'])->name('admin.categories.store');
    Route::get('/admin/categorias/edit/{id}', [AdminController::class, 'edit_category'])->name('admin.categories.edit');
    Route::put('/admin/categorias/update', [AdminController::class, 'category_update'])->name('admin.categories.update');
    Route::delete('/admin/categorias/{id}/delete', [AdminController::class, 'category_delete'])->name('admin.categories.delete');

    Route::get('/admin/productos', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/productos/add', [AdminController::class, 'add_product'])->name('admin.products.add');
    Route::post('/admin/productos/store', [AdminController::class, 'product_store'])->name('admin.products.store');
    Route::get('/admin/productos/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.products.edit');
    Route::put('/admin/productos/update', [AdminController::class, 'product_update'])->name('admin.products.update');
    Route::delete('/admin/productos/{id}/delete', [AdminController::class, 'product_delete'])->name('admin.products.delete');

    Route::get('/admin/ordenes',[AdminController::class,'orders'])->name('admin.orders');
    Route::get('/admin/ordenes/{orderId}/details',[AdminController::class,'order_details'])->name('admin.orders.details');

    Route::get('/admin/cupones',[AdminController::class,'coupons'])->name('admin.coupons');
    Route::get('/admin/cupones/add',[AdminController::class,'add_coupon'])->name('admin.coupons.add');
    Route::post('/admin/cupones/store',[AdminController::class,'store_coupon'])->name('admin.coupons.store');
    Route::get('/admin/cupones/{id}/edit',[AdminController::class,'edit_coupon'])->name('admin.coupons.edit');
    Route::put('/admin/cupones/update',[AdminController::class,'update_coupon'])->name('admin.coupons.update');
    Route::delete('/admin/cupones/{id}/delete',[AdminController::class,'delete_coupon'])->name('admin.coupons.delete');
});
