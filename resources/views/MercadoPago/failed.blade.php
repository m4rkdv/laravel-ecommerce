@extends('layouts.app')

@section('content')
    <meta http-equiv="refresh" content="5;url={{ route('cart.index') }}">

    <div class="d-flex flex-column align-items-center justify-content-center" style="height: 80vh;">
        <div class="text-center p-4 rounded" style="border: 1px solid #ccc;">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#ff4d4f" class="mb-3" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>

            <h1 style="color: #ff4d4f;">¡El pago falló!</h1>
            <p style="font-size: 1.2rem; color: #6c757d;">Hubo un problema con tu pago. Serás redirigido al carrito en <span id="countdown">5</span> segundos.</p>
            <p class="small text-muted">(Si no eres redirigido automáticamente, haz clic en el botón)</p>
            <a href="{{ route('cart.index') }}" class="btn btn-warning mt-3" style="padding: 10px 20px; font-size: 1.1rem;">Volver al carrito</a>
        </div>
    </div>

    <script>
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');

        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(interval);
            }
        }, 1000);
    </script>
@endsection


