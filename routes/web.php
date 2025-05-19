<?php

use App\Models\Transaction;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use MercadoPago\Client\Payment\PaymentClient;

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
Route::get('/checkout/success', function (Request $request) {
    $orderId = $request->input('external_reference'); // ID del pedido
    $status = $request->input('status');             // Estado del pago (enviado en el back_url)
    $paymentId = $request->input('payment_id');      // ID del pago de Mercado Pago

    if (!$paymentId || !$orderId) {
        return redirect()->route('cart.index')->with('error', 'Faltan datos para procesar el pago.');
    }

    // Verificar el estado del pago en la API de Mercado Pago
    if (!$status) {
        $paymentClient = new PaymentClient();
        try {
            $payment = $paymentClient->get($paymentId); // Consultar detalles del pago en la API
            $status = $payment->status; // Obtener el estado del pago desde la API
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Error al verificar el estado del pago.');
        }
    }

     // Actualizar el estado de la transacción
     $transaction = Transaction::where('order_id', $orderId)->first();
     if ($transaction) {
         $transaction->status = match ($status) {
             'approved' => 'approved',
             'rejected' => 'declined',
             'refunded' => 'refunded',
             default => 'pending',
         };
         $transaction->save();
     }

        // Limpieza del carrito y datos de sesión
        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('order_id', $orderId);

        return redirect()->route('cart.confirmation');
})->name('mercadopago.success');

Route::get('/checkout/failed', function () {
    return view('mercadopago.failed'); // Renderiza la vista payment_failed.blade.php
})->name('mercadopago.failed');
Route::get('/contacto',[HomeController::class,'contact'])->name('home.contact');

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

    Route::get('/admin/pedidos',[AdminController::class,'orders'])->name('admin.orders');
    Route::get('/admin/pedidos/{orderId}/detalles',[AdminController::class,'order_details'])->name('admin.orders.details');
    Route::put('/admin/pedidos/actulizar-estado',[AdminController::class,'update_order_status'])->name('admin.orders.status.update');

    Route::get('/admin/cupones',[AdminController::class,'coupons'])->name('admin.coupons');
    Route::get('/admin/cupones/add',[AdminController::class,'add_coupon'])->name('admin.coupons.add');
    Route::post('/admin/cupones/store',[AdminController::class,'store_coupon'])->name('admin.coupons.store');
    Route::get('/admin/cupones/{id}/edit',[AdminController::class,'edit_coupon'])->name('admin.coupons.edit');
    Route::put('/admin/cupones/update',[AdminController::class,'update_coupon'])->name('admin.coupons.update');
    Route::delete('/admin/cupones/{id}/delete',[AdminController::class,'delete_coupon'])->name('admin.coupons.delete');

    Route::get('/admin/slider',[AdminController::class,'slides'])->name('admin.slides');
    Route::get('/admin/slider/add',[AdminController::class,'slide_add'])->name('admin.slides.add');
    Route::post('/admin/slider/store',[AdminController::class,'slide_store'])->name('admin.slides.store');
    Route::get('/admin/slider/{id}/edit',[AdminController::class,'slide_edit'])->name('admin.slides.edit');
    Route::put('/admin/slider/update',[AdminController::class,'slide_update'])->name('admin.slides.update');
    Route::delete('/admin/slider/{id}/delete',[AdminController::class,'slide_delete'])->name('admin.slides.delete');

});
