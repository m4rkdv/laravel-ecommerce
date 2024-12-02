@extends('layouts.admin')
@section('content')
        <style>
            .table-transaction>tbody>tr:nth-of-type(odd) {
                --bs-table-accent-bg: #fff !important;
            }
        </style>
        <div class="main-content-inner">
            <div class="main-content-wrap">
                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                    <h3>Detalles del Pedido</h3>
                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                        <li>
                            <a href="{{ route('admin.index') }}">
                                <div class="text-tiny">Dashboard</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <div class="text-tiny">Artículos del Pedido</div>
                        </li>
                    </ul>
                </div>

                <div class="wg-box">
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <div class="wg-filter flex-grow">
                            <h5>Datos de la orden</h5>
                        </div>
                        <a class="tf-button style-1 w208" href="{{ route('admin.orders') }}">Atrás</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Orden Nro</th>
                                    <td>{{ $order->id }}</td>
                                    <th>Celular</th>
                                    <td>{{ $order->phone }}</td>
                                    <th>Código Postal</th>
                                    <td>{{ $order->zip }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha del Pedido</th>
                                    <td>{{ $order->created_at }}</td>
                                    <th>Fecha de Entrega</th>
                                    <td>{{ $order->delivered_date }}</td>
                                    <th>Fecha de Cancelación</th>
                                    <td>{{ $order->canceled_date }}</td>
                                </tr>
                                <tr>
                                    <th>Estado de la Orden</th>
                                    <td colspan="5">
                                        @if ($order->status == 'delivered')
                                            <span class="badge bg-success">Entregado</span>
                                        @elseif ($order->status == 'ordered')
                                            <span class="badge bg-warning">Ordenado</span>
                                        @else
                                            <span class="badge bg-danger">Cancelado</span>
                                        @endif
                                    </td>
                                </tr>
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
                                    <th class="text-center">Opciones</th>
                                    <th class="text-center">Estado de Devolución</th>
                                    <th class="text-center">Acción</th>
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
                                        <td class="text-center">{{ $item->options }}</td>
                                        <td class="text-center">{{ $item->rstatus == 0 ? "No":"Si" }}</td>
                                        <td class="text-center">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </div>
                                        </td>
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
                        <tbody>
                            <tr>
                                <th>Subtotal</th>
                                <td>${{ $order->subtotal }}</td>
                                <th>Descuento</th>
                                <td>${{ $order->discount }}</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>${{ $order->total }}</td>
                                <th>Método de Pago</th>
                                <td>{{ $transaction->mode }}</td>
                                <th>Estado</th>
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
                            </tr>
                            <tr>
                                <th>Fecha del Pedido</th>
                                <td>2024-07-11 00:54:14</td>
                                <th>Fecha de Entrega</th>
                                <td></td>
                                <th>Fecha de Cancelación</th>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection