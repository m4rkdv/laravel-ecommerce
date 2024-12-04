@extends('layouts.app')
@section('content')
    <style>
        .pt-90 {
        padding-top: 90px !important;
        }

        .pr-6px {
        padding-right: 6px;
        text-transform: uppercase;
        }

        .my-account .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 40px;
        border-bottom: 1px solid;
        padding-bottom: 13px;
        }

        .my-account .wg-box {
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        padding: 24px;
        flex-direction: column;
        gap: 24px;
        border-radius: 12px;
        background: var(--White);
        box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
        }

        .bg-success {
        background-color: #40c710 !important;
        }

        .bg-danger {
        background-color: #f44032 !important;
        }

        .bg-warning {
        background-color: #f5d700 !important;
        color: #000;
        }

        .table-transaction>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;

        }

        .table-transaction th,
        .table-transaction td {
        padding: 0.625rem 1.5rem .25rem !important;
        color: #000 !important;
        }

        .table> :not(caption)>tr>th {
        padding: 0.625rem 1.5rem .25rem !important;
        background-color: #6a6e51 !important;
        }

        .table-bordered>:not(caption)>*>* {
        border-width: inherit;
        line-height: 32px;
        font-size: 14px;
        border: 1px solid #e1e1e1;
        vertical-align: middle;
        }

        .table-striped .image {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        flex-shrink: 0;
        border-radius: 10px;
        overflow: hidden;
        }

        .table-striped td:nth-child(1) {
        min-width: 250px;
        padding-bottom: 7px;
        }

        .pname {
        display: flex;
        gap: 13px;
        }

        .table-bordered> :not(caption)>tr>th,
        .table-bordered> :not(caption)>tr>td {
        border-width: 1px 1px;
        border-color: #6a6e51;
        }
    </style>
    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Detalles del Pedido</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">
                    <div class="wg-box mt-5 mb-5">
                        <div class="flex items-center justify-between gap10 flex-wrap">
                            <div class="row">
                                <div class="col-6">
                                    <h5>Detalles del Pedido</h5>
                                </div>
                                <div class="col-6 text-right">
                                    <a class="btn btn-sm btn-danger" href="{{ route('user.orders') }}">Regresar</a>
                                </div>
                            </div>
                        </div>
                        <div class="wg-box">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                        <tr>
                                            <th>Orden Nro</th>
                                            <th>Celular</th>
                                            <th>Código Postal</th>
                                            <th>Fecha del Pedido</th>
                                            <th>Fecha de Entrega</th>
                                            <th>Fecha de Cancelación</th>
                                            <th>Estado de la Orden</th>
                                        </tr>
                                        <tbody>
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->phone }}</td>
                                                <td>{{ $order->zip }}</td>
                                                <td>{{ $order->created_at }}</td>
                                                <td>{{ $order->delivered_date }}</td>
                                                <td>{{ $order->canceled_date }}</td>
                                                <td>
                                                    @if ($order->status == 'delivered')
                                                    <span class="badge bg-success">Entregado</span>
                                                    @elseif ($order->status == 'ordered')
                                                    <span class="badge bg-warning">Ordenado</span>
                                                    @else
                                                    <span class="badge bg-danger">Cancelado</span>
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                        </tbody>
                                </table>
                            </div>
                        </div>
        
                        <div class="wg-box mt-5">
                            <div class="flex items-center justify-between gap10 flex-wrap">
                                <div class="wg-filter flex-grow">
                                    <h5>Artículos Ordenados</h5>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th class="text-center">Precio</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">SKU</th>
                                            <th class="text-center">Categoría</th>
                                            <th class="text-center">Marca</th>
                                            <th class="text-center">Estado de Devolución</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach ($orderItems as $item)
                                                <td class="pname">
                                                    <div class="image">
                                                        <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}" class="image">
                                                    </div>
                                                    <div class="name">
                                                        <a href="{{ route('shop.products.details',['product_slug'=>$item->product->slug]) }}" target="_blank"
                                                            class="body-title-2">{{ $item->product->name }}</a>
                                                    </div>
                                                </td>
                                                <td class="text-center">${{ $item->price }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-center">{{ $item->product->SKU }}</td>
                                                <td class="text-center">{{ $item->product->category->name }}</td>
                                                <td class="text-center">{{ $item->product->brand->name }}</td>
                                                <td class="text-center">{{ $item->rstatus == 0 ? "No":"Si" }}</td>
                                                
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="divider"></div>
                            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                {{ $orderItems->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
        
                        <div class="wg-box mt-5">
                            <h5>Dirección de Envío</h5>
                            <div class="my-account__address-item col-md-6">
                                <div class="my-account__address-item__detail">
                                    <p>{{ $order->name }}</p>
                                    <p>{{ $order->address }}</p>
                                    <p>{{ $order->locality }}</p>
                                    <p>{{ $order->city }}</p>
                                    <p>{{ $order->landmark }}</p>
                                    <p>{{ $order->zip }}</p>
                                    <br>
                                    <p>Celular : {{ $order->phone }}</p>
                                </div>
                            </div>
                        </div>
        
                        <div class="wg-box mt-5">
                            <h5>Transacciones</h5>
                            <table class="table table-striped table-bordered table-transaction">
                                <thead>
                                    <tr>
                                        <th>Subtotal</th>
                                       
                                        <th>Descuento</th>
                                       
                                        <th>Total</th>
                                        
                                        <th>Método de Pago</th>
                                        
                                        <th>Estado</th>
                                        
                                        <th>Fecha del Pedido</th>
                                       
                                        <th>Fecha de Entrega</th>
                                        
                                        <th>Fecha de Cancelación</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${{ $order->subtotal }}</td>
                                        <td>${{ $order->discount }}</td>
                                        <td>${{ $order->total }}</td>
                                        <td>{{ $transaction->mode }}</td>
                                        <td>
                                            @if ($transaction->status == 'approved')
                                                <span class="badge bg-success">Aprobado</span>                                        
                                            @elseif ($transaction->status == 'declined')
                                                <span class="badge bg-danger">Rechazado</span>
                                            @elseif ($transaction->status == 'refunded')
                                                <span class="badge bg-secondary">Reintegrado</span>
                                            @else 
                                                <span class="badge bg-warning">Pendiente</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>{{ $order->delivered_date }}</td>
                                        <td>{{ $order->canceled_date }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    <div class="wg-box mt-5 text-right">
                        <form action="http://localhost:8000/account-order/cancel-order" method="POST">
                            <input type="hidden" name="_token" value="3v611ELheIo6fqsgspMOk0eiSZjncEeubOwUa6YT"
                                autocomplete="off">
                            <input type="hidden" name="_method" value="PUT"> <input type="hidden" name="order_id"
                                value="1">
                            <button type="submit" class="btn btn-danger">Cancel Order</button>
                        </form>
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
