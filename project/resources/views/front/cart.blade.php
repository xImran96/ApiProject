@extends('layouts.front')
@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="pages">
          <li>
            <a href="{{ route('front.index') }}">
              {{ $langg->lang17 }}
            </a>
          </li>
          <li>
            <a href="{{ route('front.cart') }}">
              {{ $langg->lang121 }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Area End -->

<!-- Cart Area Start -->
<section class="cartpage">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="left-area">
          <div class="cart-table">
            <table class="table">
              @include('includes.form-success')
                <thead>
                    <tr>
                      <th>{{ $langg->lang122 }}</th>
                      <th width="30%">{{ $langg->lang539 }}</th>
                      <th>{{ $langg->lang125 }}</th>
                      <th>{{ $langg->lang126 }}</th>
                      <th><i class="icofont-close-squared-alt"></i></th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(Session::has('cart'))

                    @foreach($products as $product)
                    <?php $productt = App\Models\Product::find($product['item']['id']) ?>

                    <tr class="cremove{{ $product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values']) }}">
                      <td class="product-img">
                        <div class="item">
                          <img src="{{ $product['item']['photo'] ? asset('assets/images/products/'.$product['item']['photo']):asset('assets/images/noimage.png') }}" alt="">
                          <p class="name"><a href="{{ route('front.product', [$productt->id, $productt->slug_name]) }}">{{mb_strlen($product['item']['name'],'utf-8') > 35 ? mb_substr($product['item']['name'],0,35,'utf-8').'...' : $product['item']['name']}}</a></p>
                        </div>
                      </td>
                                            <td>
                                                @if(!empty($product['size']))
                                                <b>{{ $langg->lang312 }}</b>: {{ $product['item']['measure'] }}{{str_replace('-',' ',$product['size'])}} <br>
                                                @endif
                                                @if(!empty($product['color']))
                                                <div class="d-flex mt-2">
                                                <b>{{ $langg->lang313 }}</b>:  <span id="color-bar" style="border: 10px solid #{{$product['color'] == "" ? "white" : $product['color']}};"></span>
                                                </div>
                                                @endif

                                                    @if(!empty($product['keys']))

                                                    @foreach( array_combine(explode(',', $product['keys']), explode(',', $product['values']))  as $key => $value)

                                                        <b>{{ ucwords(str_replace('_', ' ', $key))  }} : </b> {{ $value }} <br>
                                                    @endforeach

                                                    @endif

                                                  </td>




                      <td class="unit-price quantity">
                        <p class="product-unit-price">
                          @if(Auth::guard('web')->check())
                          {{ App\Models\Product::convertPrice($product['item_price']) }}  
                          @else 
                          <a class="price"
                          href="{{ route('user.login') }}">
                          <i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}
              
                        </a>	
                          @endif
                                                
                        </p>
          @if($product['item']['type'] == 'Physical')

          @php 
         $product_test=App\Models\Product::find($product['item']['id']);
          @endphp
      
                          <div class="qty">
                              <ul>
              <input type="hidden" class="prodid" value="{{$product['item']['id']}}">  
              <input type="hidden" class="itemid" value="{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}">     
              <input type="hidden" class="size_qty" value="{{$product['size_qty']}}">   
              <input type="hidden" class="min_order_qty" value="{{$product_test->min_order_qty}}">    
              <input type="hidden" class="size_price" value="{{$product['size_price']}}">   
                                <li>
                                  <span class="qtminus1 reducing">
                                    <i class="icofont-minus"></i>
                                  </span>
                                </li>
                                <li>
                                  <span class="qttotal1" id="qty{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}">{{ $product['qty'] }}</span>
                                </li>
                                <li>
                                  <span class="qtplus1 adding">
                                    <i class="icofont-plus"></i>
                                  </span>
                                </li>
                              </ul>
                          </div>
        @endif


                      </td>

                            @if($product['size_qty'])
                            <input type="hidden" id="stock{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}" value="{{$product['size_qty']}}">
                            @elseif($product['item']['type'] != 'Physical') 
                            <input type="hidden" id="stock{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}" value="1">
                            @else
                            <input type="hidden" id="stock{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}" value="{{$product['stock']}}">
                            @endif

                      <td class="total-price">
                        <p id="prc{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}">
                          @if(Auth::guard('web')->check())
                          {{ App\Models\Product::convertPrice($product['price']) }}                 
                          @else 
                          <a class="price"
                          href="{{ route('user.login') }}">
                          <i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}
              
                        </a>	
                        @endif
                        </p>
                      </td>
                      <td>
                        <span class="removecart cart-remove" data-class="cremove{{ $product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values']) }}" data-href="{{ route('product.cart.remove',$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])) }}"><i class="icofont-ui-delete"></i> </span>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
            </table>
          </div>
        </div>
      </div>
      @if(Session::has('cart'))
      <div class="col-lg-4">
        <div class="right-area">
          <div class="order-box">
            <h4 class="title">{{ $langg->lang127 }}</h4>
            <ul class="order-list">
              <li>
                <p>
                  {{ $langg->lang128 }}
                </p>
                <P>
                  <b class="cart-total">
                    @if(Auth::guard('web')->check())
                    {{ Session::has('cart') ? App\Models\Product::convertPrice($totalPrice) : '0.00' }}
                    @else 
                    <a class="price"
                    href="{{ route('user.login') }}">
                    <i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}
        
                  </a>
                    @endif
                  
                  </b>
                </P>
              </li>
              <li>
                <p>
                  {{ $langg->lang129 }}
                </p>
                <P>
                  <b class="discount">
                    @if(Auth::guard('web')->check())
                    {{ App\Models\Product::convertPrice(0)}}
                    @else 
                    <a class="price"
                    href="{{ route('user.login') }}">
                    <i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}
                  </a>
                    @endif
                  
                  </b>

                  <input type="hidden" id="d-val" value="{{ App\Models\Product::convertPrice(0)}}">
                </P>
              </li>
              <li>
                <p>
                  {{ $langg->lang130 }}
                </p>
                <P>
                  <b>{{$tx}}%</b>
                </P>
              </li>
            </ul>
            <div class="total-price">
              <p>
                  {{ $langg->lang131 }}
              </p>
              <p>
                  <span class="main-total">
                    @if(Auth::guard('web')->check())
                    {{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}
                    @else 
                    <a class="price"
                    href="{{ route('user.login') }}">
                    <i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}
                  </a>
                    @endif
                  </span>
              </p>
            </div>
            <div class="cupon-box">
              <div id="coupon-link">
                  {{ $langg->lang132 }}
              </div>
              <form id="coupon-form" class="coupon">
                <input type="text" placeholder="{{ $langg->lang133 }}" id="code" required="" autocomplete="off">
                <input type="hidden" class="coupon-total" id="grandtotal" value="{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}">
                <button type="submit">{{ $langg->lang134 }}</button>
              </form>
            </div>
            <a href="{{ route('front.checkout') }}" class="order-btn">
              {{ $langg->lang135 }}
            </a>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</section>
<!-- Cart Area End -->
@endsection 
@section('scripts')
<script>


  $(document).on("click", ".reducing" , function(){
        
    $('.xloader').removeClass('d-none');
    var pid =  $(this).parent().parent().find('.prodid').val();
    var itemid =  $(this).parent().parent().find('.itemid').val();
    var min_order_qty =  $(this).parent().parent().find('.min_order_qty').val();
    var size_qty = $(this).parent().parent().find('.size_qty').val();
    var size_price = $(this).parent().parent().find('.size_price').val();
    var stck = $("#stock"+itemid).val();
    var qty = $("#qty"+itemid).html();
    qty--;
  
    if(qty < 1)
     {
     $("#qty"+itemid).html("1");
     }
if(qty<min_order_qty)
{
  $("#qty"+itemid).html(min_order_qty);
}
     else{
     $("#qty"+itemid).html(qty);
        $.ajax({
                type: "GET",
                url:mainurl+"/reducebyone",
                data:{id:pid,itemid:itemid,size_qty:size_qty,size_price:size_price},
                success:function(data){
              if(data[0]!=-1)
                {
                    $(".discount").html($("#d-val").val());
                    $(".cart-total").html(data[0]);
                    $(".main-total").html(data[3]);
                    $(".coupon-total").val(data[3]);
                    $("#prc"+itemid).html(data[2]);
                    $("#prct"+itemid).html(data[4]);
                    $("#cqt"+itemid).html(data[1]);
                    $("#qty"+itemid).html(data[1]);
                  } 
                }
          });
     }
    //  $('.xloader').addClass('d-none');
   });
</script>
@endsection