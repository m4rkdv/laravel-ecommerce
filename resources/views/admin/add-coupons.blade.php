@extends('layouts.admin')
@section('content')

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Información del Cupón</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Panel de Control</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.coupons') }}">
                            <div class="text-tiny">Cupón</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Nuevo Cupón</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.coupons.store') }}">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Código del Cupón <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Coupon Code" name="code"
                            tabindex="0" value="{{ old('code') }}" aria-required="true" required="">
                    </fieldset>
                    @error('code')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="category">
                        <div class="body-title">Tipo de Cupón</div>
                        <div class="select flex-grow">
                            <select class="" name="type">
                                <option value="">Seleccionar</option>
                                <option value="fixed">Fijo</option>
                                <option value="percent">Porcentual</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('type')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Valor <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Coupon Value" name="value"
                            tabindex="0" value="{{ old('value') }}" aria-required="true" required="">
                    </fieldset>
                    @error('value')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Valor del Carrito(min) <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Cart Value"
                            name="cart_value" tabindex="0" value="{{ old('cart_value') }}" aria-required="true"
                            required="">
                    </fieldset>
                    @error('cart_value')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Fecha de Expiración <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="date" placeholder="Expiry Date"
                            name="expiry_date" tabindex="0" value="{{ old('expiry_date') }}" aria-required="true"
                            required="">
                    </fieldset>
                    @error('expiry_date')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    
                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection