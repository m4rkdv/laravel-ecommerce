@extends('layouts.app')
@section('content')
    <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Envio y Confirmación</h2>
      <div class="checkout-steps">
        <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Carrito de Compras</span>
            <em>Administra tu lista de compras</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Envío y Pago</span>
            <em>Revisa los detalles de tu compra</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Confirmación</span>
            <em>Revisa y confirma tu pedido<</em>
          </span>
        </a>
      </div>
      <form name="checkout-form" action="{{ route('cart.place.order') }}" method="POST">
        @csrf
        <div class="checkout-form">
          <div class="billing-info__wrapper">
            <div class="row">
              <div class="col-6">
                <h4>DETALLES DE ENVÍO</h4>
              </div>
              <div class="col-6">
              </div>
            </div>
            @if($address)
                <div class="row">
                    <div class="col-md-12">
                        <div class="my-account__address-list">
                            <div class="my-account__address-list-item">
                                <div class="my-account__address-list-item__detail">
                                    <p>{{ $address->name }}</p>
                                    <p>{{ $address->address }}</p>
                                    <p>{{ $address->landmark }}</p>
                                    <p>{{ $address->city }}, {{ $address->state }}, {{ $address->country }}</p>
                                    <p>{{ $address->zip }}</p>
                                    <br/>
                                    <p>{{ $address->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            <div class="row mt-5">
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="name" required="" value="{{ old('name') }}">
                  <label for="name">Nombre Completo *</label>
                  @error('name')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="phone" required="" value="{{ old('phone') }}">
                  <label for="phone">Número de teléfono *</label>
                  @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="zip" required="" value="{{ old('zip') }}">
                  <label for="zip">Código Postal *</label>
                  @error('zip')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mt-3 mb-3">
                  <input type="text" class="form-control" name="state" required="" value="{{ old('state') }}">
                  <label for="state">Provincia *</label>
                  @error('state')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="city" required="" value="{{ old('city') }}">
                  <label for="city">Ciudad / Localidad *</label>
                  @error('city')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="address" required="" value="{{ old('address') }}">
                  <label for="address">Número y Calle *</label>
                  @error('address')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="locality" required="" value="{{ old('locality') }}">
                  <label for="locality">Barrio / Zona *</label>
                  @error('locality')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="landmark" required="" value="{{ old('landmark') }}">
                  <label for="landmark">Referencia *</label>
                  @error('landmark')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
            @endif
          </div>
          <div class="checkout__totals-wrapper">
            <div class="sticky-content">
              <div class="checkout__totals">
                <h3>Tu Pedido</h3>
                <table class="checkout-cart-items">
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th >SUBTOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach (Cart::instance('cart') as $item)
                    <tr>
                      <td>
                        {{ $item->name }} x {{ $item->qty }}
                      </td>
                      <td >
                        ${{ $item->subtotal() }}
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                @if (Session::has('discounts'))
                <table class="checkout-totals">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <td class="text-right">${{ Cart::instance('cart')->subtotal() }}</td>
                        </tr>
                        <tr>
                            <th>Descuento {{ Session::get('coupon')['code'] }}</th>
                            <td class="text-right">${{ Session::get('discounts')['discount'] }}</td>
                        </tr>
                        <tr>
                            <th>Subtotal con Descuento {{ Session::get('coupon')['code'] }}</th>
                            <td class="text-right">${{ Session::get('discounts')['subtotal'] }}</td>
                        </tr>
                        <tr>
                            <th>Shipping</th>
                            <td class="text-right">
                            Free
                            </td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td class="text-right">${{ Session::get('discounts')['total'] }}</td>
                        </tr>
                        </tbody>
                  </table>
                @else
                    <table class="checkout-totals">
                    <tbody>
                        <tr>
                        <th>SUBTOTAL</th>
                        <td class="text-right">${{ Cart::instance('cart')->subtotal() }}</td>
                        </tr>
                        <tr>
                        <th>SHIPPING</th>
                        <td class="text-right">Free shipping</td>
                        </tr>
                        <tr>
                        <th>TOTAL</th>
                        <td class="text-right">${{ Cart::instance('cart')->total() }}</td>
                        </tr>
                    </tbody>
                    </table>
                @endif
              </div>
              <div class="checkout__payment-methods p-4" style="background-color: #222; border-radius: 10px; padding: 20px; color: #ffffff;">
                <h3 class="mb-4" style="font-weight: bold; color: #ffffff; text-align: center;">Método de Pago</h3>
            
                <div class="form-check mb-4 d-flex align-items-center" style="padding: 10px; background-color: #333; border-radius: 8px; border: 1px solid #444;">
                    <input class="form-check-input me-3" type="radio" name="mode" id="mode1" value="mercadopago" checked style="width: 20px; height: 20px;">
                    <label class="form-check-label d-flex align-items-center" for="mode1" style="font-size: 1.1rem; color: #ffffff;">
                      <img src="{{ asset('images/mp/MP_RGB_HANDSHAKE_pluma-relleno-blanco_hori-izq.svg') }}" 
                      alt="Mercado Pago" 
                      style="width: 120px; height: auto; margin-right: 15px;">
                        Mercado Pago
                    </label>
                </div>
                <p class="option-detail text-muted" style="font-size: 0.9rem; margin-left: 40px; color: #cccccc;">
                    Paga de forma segura utilizando tu cuenta de Mercado Pago. Aceptamos tarjetas de crédito y otros medios de pago.
                </p>
            
                <div class="form-check mb-3 d-flex align-items-center" style="padding: 10px; background-color: #333; border-radius: 8px; border: 1px solid #444;">
                    <input class="form-check-input me-3" type="radio" name="mode" id="mode2" value="card" style="width: 20px; height: 20px;">
                    <label class="form-check-label d-flex align-items-center" for="mode2" style="font-size: 1.1rem; color: #ffffff;">
                      <img width="45" height="45" src="https://img.icons8.com/ios/50/FFFFFF/credit-card-front.png" alt="credit-card-front" style="margin-right: 10px;">
                        Transferencia Bancaria Directa
                    </label>
                </div>
                <p class="option-detail text-muted" style="font-size: 0.9rem; margin-left: 40px; color: #cccccc;">
                    Realizá el pago directamente en nuestra cuenta bancaria. Usá tu ID de Pedido como referencia de pago. El pedido no será enviado hasta que los fondos se acrediten en nuestra cuenta.
                </p>
            
                <div class="policy-text mt-4" style="font-size: 0.9rem; color: #cccccc;">
                    <strong>Nota:</strong> Tus datos personales serán utilizados para procesar tu pedido, mejorar tu experiencia en esta tienda y otros propósitos descritos en nuestra <a href="terms.html" target="_blank" style="color: #ffcc00;">política de privacidad</a>.
                </div>
            </div>
            
              <button class="btn btn-primary btn-checkout">CONFIRMAR PEDIDO</button>
            </div>
          </div>
        </div>
      </form>
    </section>
  </main>
@endsection