@extends('layouts.vendor')

@section('content')
<div class="content-area">
                    <div class="mr-breadcrumb">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="heading">{{ $langg->lang586 }} <a class="add-btn" href="{{ route('vendor-order-show',$order->order_number) }}"><i class="fas fa-arrow-left"></i> {{ $langg->lang550 }}</a></h4>
                                <ul class="links">
                                    <li>
                                        <a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">{{ $langg->lang443 }}</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">{{ $langg->lang586 }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
    <div class="order-table-wrap">
        <div class="invoice-wrap">
            <div class="invoice__title">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice__logo text-left">
                           <img src="{{ asset('assets/images/'.$gs->invoice_logo) }}" alt="woo commerce logo">
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <a class="btn  add-newProduct-btn print" href="{{route('vendor-order-print',$order->order_number)}}"
                        target="_blank"><i class="fa fa-print"></i> {{ $langg->lang607 }}</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row invoice__metaInfo mb-4">
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">
                        
                        <p><strong>{{ $langg->lang601 }} </strong></p>
                        <span><strong>{{ $langg->lang588 }} :</strong> {{ sprintf("%'.08d", $order->id) }}</span><br>
                        <span><strong>{{ $langg->lang589 }} :</strong> {{ date('d-M-Y',strtotime($order->created_at)) }}</span><br>
                        <span><strong>{{  $langg->lang590 }} :</strong> {{ $order->order_number }}</span><br>
                        @if($order->dp == 0)
                        <span> <strong>{{ $langg->lang602 }} :</strong>
                            @if($order->shipping == "pickup")
                            {{ $langg->lang603 }}
                            @else
                            {{ $langg->lang604 }}
                            @endif
                        </span><br>
                        @endif
                        <span> <strong>{{ $langg->lang605 }} :</strong> {{$order->method}}</span>
                    </div>
                </div>
            </div>
            <div class="row invoice__metaInfo">
           @if($order->dp == 0)
                <div class="col-lg-6">
                        <div class="invoice__shipping">
                            <p><strong>{{ $langg->lang606 }}</strong></p>
                           <span><strong>{{ $langg->lang557 }}</strong>: {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name}}</span><br>
                           <span><strong>{{ $langg->lang560 }}</strong>: {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}</span><br>
                           <span><strong>{{ $langg->lang562 }}</strong>: {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}</span><br>
                           <span><strong>{{ $langg->lang561 }}</strong>: {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}</span>

                        </div>
                </div>

            @endif

                <div class="col-lg-6" style="width:50%;">
                        <div class="invoice__orderDetails" style="margin-top:5px;">
                            <p><strong>{{ $langg->lang587 }}</strong></p>
                            <span><strong>{{ $langg->lang557 }}</strong>: {{ $order->customer_name}}</span><br>
                            <span><strong>{{ $langg->lang560 }}</strong>: {{ $order->customer_address }}</span><br>
                            <span><strong>{{ $langg->lang562 }}</strong>: {{ $order->customer_city }}</span><br>
                            <span><strong>{{ $langg->lang561 }}</strong>: {{ $order->customer_country }}</span>
                        </div>
                </div>
                <div class="col-lg-6" style="width:50%;">
                        <div class="invoice__orderDetails" style="margin-top:5px;">
                            <p><strong>
                                <!-- {{ $langg->lang587 }} --> Shipping Address
                            </strong></p>
                            <span><strong>
                                <!-- {{ $langg->lang557 }} --> Shipping Name
                            </strong>: {{ $order->shipping_name}}</span><br>
                            <span><strong>
                                {{ $langg->lang560 }}
                            </strong>: {{ $order->shipping_address }}</span><br>
                            <span><strong>
                                {{ $langg->lang562 }}
                            </strong>: {{ $order->shipping_city }}</span><br>
                            <span><strong>{{ $langg->lang561 }}</strong>: {{ $order->shipping_country }}</span>
                        </div>
                </div>
            
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="invoice_table">
                        <div class="mr-table">
                            <div class="table-responsive">
                                <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%" >
                                    <thead>
                                        <tr>
                                            
                                            <th>{{ $langg->lang591 }}</th>
                                            <th>{{ $langg->lang595 }}</th>
                                            <th>Item Price</th>
                                            <th>{{ $langg->lang539 }}</th>
                                            <th>{{ $langg->lang600 }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $subtotal = 0;
                                        $data = 0;
                                        $tax = 0;
                                        $subs = auth()->user()->subscribes()->orderBy('id', 'desc')->first();
                                        @endphp

                                        @foreach($cart->items as $product)
                                        <tr>
                                            <td> <a target="_blank"
                                                    href="{{ route('vendor.product', $product['item']['slug']) }}">
                                                {{ $product['item']['name_en']}}
                                            </a>
                                                </td>
                                            <td>{{ $product['qty'] }}</td>
                                            <td>{{ $product['item_price'] }}</td>
                                            <td>
                                                @if($product['size_key'] != null)
                                                <ul>
                                                    <li>Size : {{ $product['size_key'] }}</li>
                                                    <li>Size Qty: {{ $product['size_qty'] }}</li>
                                                    <li>Size Price: {{ $product['size_price'] }}</li>
                                                </ul>
                                                 @endif

                                         </td>
                                            <td>{{ $product['price'] }}</td>

                                        </tr>
                                            <!-- {{ $subtotal += $product['price'] }} -->
                                        @endforeach
                                       
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="4">{{ $langg->lang597 }}</td>
                                            <td>{{$order->currency_sign}}{{ round($subtotal, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td colspan="3"></td>
                                            <td>
                                                <!-- {{ $langg->lang600 }} -->
                                                Per Delivery Charges
                                            </td>
                                            <td>{{$order->currency_sign}}{{ round(($subtotal += $subs->per_delivery_charges), 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="3"></td>
                                            <td>
                                                <!-- {{ $langg->lang600 }} -->
                                                Per Order Charges
                                            </td>
                                            <td>{{$order->currency_sign}}{{ round(($subtotal += $subs->per_order_charges), 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="3"></td>
                                            <td>
                                                <!-- {{ $langg->lang600 }} -->
                                                Preparation Cost
                                            </td>
                                            <td>{{$order->currency_sign}} {{ round(($subtotal += $subs->preparation_cost), 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="3"></td>
                                            <td>{{ $langg->lang600 }}</td>
                                            <td>{{$order->currency_sign}}{{ round(($subtotal + $data), 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="3"></td>
                                            <td>{{ $langg->lang600 }}</td>
                                            <td>{{$order->currency_sign}}{{ round(($subtotal + $data), 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Content Area End -->
</div>
</div>
</div>

@endsection