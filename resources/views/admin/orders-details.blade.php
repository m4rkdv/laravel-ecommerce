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
                        @if (Session::has('status'))
                            <p class="alert alert-success">{{ Session::get('status') }}</p>
                        @endif
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
                                            <span class="badge bg-warning">Solicitado</span>
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
                                    <th class="text-center">Opciones</th>
                                    <th class="text-center">Estado de Devolución</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $item)
                                    <tr>
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
                                    </tr>
                                @endforeach
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

                <div class="wg-box mt-5">
                    <h5>Actualizar Estado del pedido</h5>
                    <form action="{{ route('admin.orders.status.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="select">
                                    <select name="order_status" id="order_status">
                                        <option value="ordered" {{ $order->status == 'ordered' ? "selected":"" }}>Encargado</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? "selected":"" }}>Enviado</option>
                                        <option value="canceled" {{ $order->status == 'canceled' ? "selected":"" }}>Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary tf-button w208">Actulizar Estado</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
@endsection