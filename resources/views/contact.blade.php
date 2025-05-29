@extends('layouts.app')
@section('content')
    <style>
        .text-danger{
            color:#e72010 !important;
        }
        .suc-notif{
            color: black !important;
            background-color: gray !important;
            width: fit-content !important;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
        <div class="mw-930">
            <h2 class="page-title">CONTACT US</h2>
        </div>
        </section>

        <hr class="mt-2 text-secondary " />
        <div class="mb-4 pb-4"></div>

        <section class="contact-us container">
        <div class="mw-930">
            <div class="contact-us__form">
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show suc-notif" role="alert">
                        {{ Session::get('success') }}
                    </div>
                @endif
            <form name="contact-us-form" class="needs-validation" novalidate="" action="{{ route('home.contact.store') }}" method="POST">
                @csrf
                <h3 class="mb-5">Contacto</h3>
                <div class="form-floating my-4">
                <input type="text" class="form-control" name="name" placeholder="Nombre *" value="{{ old('name') }}" required="">
                <label for="contact_us_name">Nombre *</label>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                <span class="text-danger"></span>
                </div>
                <div class="form-floating my-4">
                <input type="text" class="form-control" name="phone" placeholder="Número de Teléfono *" value="{{ old('phone') }}" required="">
                <label for="contact_us_name">Telefono *</label>
                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-floating my-4">
                <input type="email" class="form-control" name="email" placeholder="Email *" value="{{ old('email') }}" required="">
                <label for="contact_us_name">Email *</label>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="my-4">
                <textarea class="form-control form-control_gray" name="comment" placeholder="Su Mensaje" value="{{ old('comment') }}" cols="30"
                    rows="8" required=""></textarea>
                @error('comment') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="my-4">
                <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
            </div>
        </div>
        </section>
    </main>
@endsection