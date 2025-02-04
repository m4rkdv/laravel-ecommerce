@extends('layouts.app')

@section('content')
<style>
  .pt-90{
      background-color: black;
  }
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
      <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore active" id="register-tab" data-bs-toggle="tab"
            href="#tab-item-register" role="tab" aria-controls="tab-item-register" aria-selected="true">Register</a>
        </li>
      </ul>
      <div class="tab-content pt-2" id="login_register_tab_content">
        <div class="tab-pane fade show active" id="tab-item-register" role="tabpanel" aria-labelledby="register-tab">
          <div class="register-form">
            <form method="POST" action="{{ route('register') }}" name="register-form" class="needs-validation" novalidate="">
                @csrf
              <div class="form-floating mb-3">
                <input class="form-control form-control_gray @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required="" autocomplete="name"
                  autofocus="">
                <label for="name">Nombre</label>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="pb-3"></div>
              <div class="form-floating mb-3">
                <input id="surname" type="text" class="form-control form-control_gray @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname">
                <label for="surname">Apellido</label>
                @error('surname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

              <div class="pb-3"></div>
              <div class="form-floating mb-3">
                <input id="email" type="email" class="form-control form-control_gray @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required=""
                  autocomplete="email">
                <label for="email">Email *</label>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              
              <div class="pb-3"></div>
              <div class="form-floating mb-3">
                <input id="mobile" type="mobile" class="form-control form-control_gray @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required=""
                  autocomplete="mobile">
                <label for="mobile">Teléfono</label>
                @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="pb-3"></div>

              <div class="form-floating mb-3">
                <input id="password" type="password" class="form-control form-control_gray @error('password') is-invalid @enderror" name="password" required=""
                  autocomplete="new-password">
                <label for="password">Password *</label>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="form-floating mb-3">
                <input id="password-confirm" type="password" class="form-control form-control_gray @error('password') is-invalid @enderror"
                  name="password_confirmation" required="" autocomplete="new-password">
                <label for="password">Confirmar Password *</label>
              </div>

              <div class="d-flex align-items-center mb-3 pb-2">
                <p class="m-0">Tus datos personales se utilizarán para mejorar tu experiencia en este sitio, gestionar el acceso a tu cuenta y
                  otros fines detallados en nuestra <a href="/privacy-policy" target="_blank" class="text-primary">política de privacidad</a>.</p>
              </div>

              <button class="btn btn-primary w-100 text-uppercase" type="submit">Registrar</button>

              <div class="customer-option mt-4 text-center">
                <span class="text-secondary">¿Ya tienes una cuenta?</span>
                <a href="{{ route('login') }}" class="btn-text js-show-register">Inicia sesión aquí</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>


@endsection
