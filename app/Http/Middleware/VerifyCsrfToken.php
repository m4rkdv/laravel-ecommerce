<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Las URIs que deben excluirse de la verificación CSRF.
     *
     * @var array
     */
    protected $except = [
        'mercadopago/webhook', // Excluye esta ruta de la protección CSRF
    ];
}