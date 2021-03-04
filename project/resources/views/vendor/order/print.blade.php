<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="{{$seo->meta_keys}}">
        <meta name="author" content="GeniusOcean">

        <title>{{$gs->title}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('assets/print/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/print/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('assets/print/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/print/css/style.css')}}">
  <link href="{{asset('assets/print/css/print.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="icon" type="image/png" href="{{asset('assets/images/'.$gs->favicon)}}"> 
  <style type="text/css">
@page { size: auto;  margin: 0mm; }
@page {
  size: A4;
  margin: 0;
}
@media print {
  html, body {
    width: 210mm;
    height: 287mm;
  }

html {

}
::-webkit-scrollbar {
    width: 0px;  /* remove scrollbar space */
    background: transparent;  /* optional: just make scrollbar invisible */
}
  </style>
</head>
<body onload="window.print();">
    <div class="invoice-wrap">
            <div class="invoice__title">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice__logo text-left">
                           <img src="{{ asset('assets/images/'.$gs->invoice_logo) }}" alt="woo commerce logo">
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="invoice__metaInfo">
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

            <div class="invoice__metaInfo" style="margin-top:0px;">
                @if($order->dp == 0)
                <div class="col-lg-6">
                        <div class="invoice__orderDetails" style="margin-top:5px;">
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
                <br>
                <div class="col-lg-12">
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
                                            <td> 
                                                <a target="_blank"
                                                    href="{{ route('vendor.product', $product['item']['slug']) }}">

                                                    {{ $product['item']['name_en'] }}
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
<!-- ./wrapper -->

<script type="text/javascript">
setTimeout(function () {
        window.close();
      }, 500);
</script>

</body>
</html>
