@extends('layouts.vendor')
     
@section('styles')


@endsection

@section('content')
    <div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading">{{ $langg->lang549 }} <a class="add-btn" href="{{ route('vendor-order-index') }}"><i class="fas fa-arrow-left"></i> {{ $langg->lang550 }}</a></h4>
                                        <ul class="links">
                                            <li>
                                                <a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">{{ $langg->lang443 }}</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">{{ $langg->lang549 }}</a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>

                        <div class="order-table-wrap">
                            @include('includes.admin.form-both')
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="special-box">
                                        <div class="heading-area">
                                            <h4 class="title">
                                            {{ $langg->lang549 }}
                                            </h4>
                                        </div>
                                        <div class="table-responsive-sm">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <th class="45%" width="45%">{{ $langg->lang551 }}</th>
                                                    <td width="10%">:</td>
                                                    <td class="45%" width="45%">{{$order->order_number}}</td>
                                                </tr>
                                                <tr>
                                                    <th width="45%">{{ $langg->lang552 }}</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">{{$order->dealerOrder()->where('dealer_id','=',$user->id)->sum('qty')}}</td>
                                                </tr>

                                                @if(Auth::user()->id == $order->vendor_shipping_id)
                                                @if($order->shipping_cost != 0)
                                                @php 
                                                $price = round(($order->shipping_cost / $order->currency_value),2);
                                                @endphp
                                                @if(DB::table('shippings')->where('price','=',$price)->count() > 0)
                                                <tr>
                                                    <th width="45%">{{ DB::table('shippings')->where('price','=',$price)->first()->title }}</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">{{ $order->currency_sign }}{{ round($order->shipping_cost, 2) }}</td>
                                                </tr>
                                                @endif
                                                @endif
                                                @endif
                                                
                                                @if(Auth::user()->id == $order->vendor_packing_id)
                                                @if($order->packing_cost != 0)
                                                @php 
                                                $pprice = round(($order->packing_cost / $order->currency_value),2);
                                                @endphp
                                                @if(DB::table('packages')->where('price','=',$pprice)->count() > 0)
                                                <tr>
                                                    <th width="45%">{{ DB::table('packages')->where('price','=',$pprice)->first()->title }}</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">{{ $order->currency_sign }}{{ round($order->packing_cost, 2) }}</td>
                                                </tr>
                                                @endif
                                                @endif
                                                @endif

                                                <tr>
                                                    <th width="45%">{{ $langg->lang553 }}</th>
                                                    <td width="10%">:</td>

                                                        @php 

                                                        $price = round($order->dealerOrder()->where('dealer_id','=',$user->id)->sum('price'),2);


                                                        if($user->shipping_cost != 0){
                                                            $price = $price  + round($user->shipping_cost * $order->currency_value , 2);
                                                            }

                                                        if($order->tax != 0){
                                                            $tax = ($price / 100) * $order->tax;
                                                            $price  += $tax;
                                                            }   

                                                        @endphp

                                                    <td width="45%">{{$order->currency_sign}}{{ round($price * $order->currency_value , 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th width="45%">{{ $langg->lang554 }}</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">{{date('d-M-Y H:i:s a',strtotime($order->created_at))}}</td>
                                                </tr>


                                                <tr>
                                                    <th width="45%">{{ $langg->lang795 }}</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">{{$order->method}}</td>
                                                </tr>
                
                                                @if($order->method != "Cash On Delivery")
                                                @if($order->method=="Stripe")
                                                <tr>
                                                    <th width="45%">{{$order->method}} {{ $langg->lang796 }}</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">{{$order->charge_id}}</td>
                                                </tr>                        
                                                @endif
                                                <tr>
                                                    <th width="45%">{{$order->method}} {{ $langg->lang797 }}</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">{{$order->txnid}}</td>
                                                </tr>                         
                                                @endif

                                                <tr>
                                                    <th width="45%">{{ $langg->lang798 }}</th>
                                                    <th width="10%">:</th>
                                                    <td width="45%">{!! $order->payment_status == 'Pending' ? "<span class='badge badge-danger'>". $langg->lang799 ."</span>":"<span class='badge badge-success'>". $langg->lang800 ."</span>" !!}</td>
                                                </tr>   
                                                @if(!empty($order->order_note))
                                                <tr>
                                                    <th width="45%">{{ $langg->lang801 }}</th>
                                                    <th width="10%">:</th>
                                                    <td width="45%">{{$order->order_note}}</td>
                                                </tr> 
                                                @endif
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="footer-area">
                                            <a href="{{ route('vendor-order-invoice',$order->order_number) }}" class="mybtn1"><i class="fas fa-eye"></i> {{ $langg->lang555 }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="special-box">
                                        <div class="heading-area">
                                            <h4 class="title">
                                            {{ $langg->lang556 }}
                                            </h4>
                                        </div>
                                        <div class="table-responsive-sm">
                                            <table class="table">
                                                <tbody>
                                                        <tr>
                                                            <th width="45%">{{ $langg->lang557 }}</th>
                                                            <th width="10%">:</th>
                                                            <td width="45%">{{$order->customer_name}} @if($platinum) <span style="background-color: green"> approved by hareer </span>  
                                                             @elseif($gold)
                                                             <span style="background-color: #ffd700	"> Gold</span> 
                                                             @elseif($sliver)
                                                             <span style="background-color:#808080"> sliver</span> 
                                                             @elseif($bronze)
                                                             <span style="background-color:#6f4848"> bronze</span> 
                                                             @else 
                                                             <span style="background-color:rgb(48, 76, 142)"> new</span> 
                                                               @endif
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th width="45%">{{ $langg->lang558 }}</th>
                                                            <th width="10%">:</th>
                                                            <td width="45%">{{$order->customer_email}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th width="45%">{{ $langg->lang559 }}</th>
                                                            <th width="10%">:</th>
                                                            <td width="45%">{{$order->customer_phone}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th width="45%">{{ $langg->lang560 }}</th>
                                                            <th width="10%">:</th>
                                                            <td width="45%">{{$order->customer_address}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th width="45%">{{ $langg->lang561 }}</th>
                                                            <th width="10%">:</th>
                                                            <td width="45%">{{$order->customer_country}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th width="45%">{{ $langg->lang562 }}</th>
                                                            <th width="10%">:</th>
                                                            <td width="45%">{{$order->customer_city}}</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th width="45%">{{ $langg->lang563 }}</th>
                                                            <th width="10%">:</th>
                                                            <td width="45%">{{$order->customer_zip}}</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                @if($order->dp == 0)
                                <div class="col-lg-6">
                                    <div class="special-box">
                                        <div class="heading-area">
                                            <h4 class="title">
                                            {{ $langg->lang564 }}
                                            </h4>
                                        </div>
                                        <div class="table-responsive-sm">
                                            <table class="table">
                                                <tbody>
                            @if($order->shipping == "pickup")
                        <tr>
                                    <th width="45%"><strong>{{ $langg->lang565 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->pickup_location}}</td>
                                </tr>
                            @else
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang557 }}:</strong></th>
                                    <th width="10%">:</th>
                <td>{{$order->shipping_name == null ? $order->customer_name : $order->shipping_name}}</td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang558 }}:</strong></th>
                                    <th width="10%">:</th>
                <td width="45%">{{$order->shipping_email == null ? $order->customer_email : $order->shipping_email}}</td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang559 }}:</strong></th>
                                    <th width="10%">:</th>
                <td width="45%">{{$order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone}}</td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang560 }}:</strong></th>
                                    <th width="10%">:</th>
                <td width="45%">{{$order->shipping_address == null ? $order->customer_address : $order->shipping_address}}</td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang561 }}:</strong></th>
                                    <th width="10%">:</th>
                <td width="45%">{{$order->shipping_country == null ? $order->customer_country : $order->shipping_country}}</td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang562 }}:</strong></th>
                                    <th width="10%">:</th>
                <td width="45%">{{$order->shipping_city == null ? $order->customer_city : $order->shipping_city}}</td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang563 }}:</strong></th>
                                    <th width="10%">:</th>
                <td width="45%">{{$order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip}}</td>
                                </tr>
                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                    <div class="col-lg-12 order-details-table">
                                        <div class="mr-table">
                                            <h4 class="title">{{ $langg->lang566 }}</h4>
                                            <div class="table-responsiv">
                                                    <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                
                                    <th>{{ $langg->lang567 }}</th>
                                    <!-- <th>{{ $langg->lang568 }}</th> -->
                                    <th>{{ $langg->lang569 }}</th>
                                    <th>{{ $langg->lang570 }}</th>
                                    <th>{{ $langg->lang539 }}</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>{{ $langg->lang574 }}</th>
                                

                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($cart as $item)




                                                                <tr>
                                                                        <td>{{ $item['item_id'] }}</td>
                                                                        
                                                                        @php


                                                                            $product = auth()->user()->myProducts()->where('product_id', $item['item_id'])->first();
 
                                                                            
                                                                        @endphp

                                                                        <td>{{ $product->status ?? 'not available' }}</td>
                                                                        <td>
                                                                            {{ $product->name_en ?? 'not available' }}
                                                                        </td>

                                                                        <td></td>
                                                                        <td>{{ $item['qty'] }}</td>
                                                                        <td>
                                                                            {{ $product->price ?? 'not available' }}
                                                                        </td>

                                                                        <td>

                                                                            {{ $product->price*$item['qty'] }}


                                                            
                                                                        </td>



                                                                </tr>


                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-lg-12 ">
                                        <div class="text-left">
                                        <a class="btn sendEmail send" href="javascript:;" class="send" data-email="{{ $order->customer_email }}" data-toggle="modal" data-target="#vendorform">
                                                <i class="fa fa-send"></i> {{ $langg->lang576 }}
                                        </a>
                                     <button type="button" class="btn send sendEmail float-right" data-toggle="modal" data-target="#exampleModal">
                                      Processed Order
                                    </button>
                                        </div>



                                        @if($order->method == "post_paid" && $order->post_paid_confirm==0)
                                        <div class="text-right">
                                            <a href="{{route('vendor-confirm-order',$order->order_number) }}" class="btn btn-success" class="send" >
                                                    <i class="fa fa-send"></i> {{ Lang::get('custom.confirm') }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                        </div>
                    </div>
                    <!-- Main Content Area End -->
                </div>
            </div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mt-3 mx-auto" id="exampleModalLabel">Hareer Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <hr>
      <div class="modal-body">
            <form method="POST" action="{{ route('vendor-hareer-order') }}">
                @csrf
                <input type="hidden" name="order_number" value="{{ $order->order_number }}">
                <div class="row">
                <div class="form-group col-md-6 text-center">
                    <label>Cash On Delivery</label>
                    <input type="radio" class="form-control" name="method" value="cashOnDelivery" required="required">
                    
                </div>
                <div class="form-group col-md-6 text-center">
                    <label>My Fatoora</label>
                    <input type="radio" class="form-control" name="method" value="My fatoora" required="required">
                    
                </div>
                </div>
                <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Go</button>
            </div>
            </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


</div>


    {{-- Confrim Post Paid Payment Modal --}}

    <div class="modal fade" id="confirm-order" style="width: 100%" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

    <div class="modal-header d-block text-center">
        <h4 class="modal-title d-inline-block">{{ Lang::get('custom.installments') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
    </div>
                <div id="post_paid" class="modal-body">
                    <input type="number" class="input-field" id="num_installment" name="num_installment" placeholder="{{Lang::get('custom.num_installment') }} *" value="" required="">
                 
                </div>
                <div class="modal-footer justify-content-left">
                    <button type="button" class="btn btn-success" data-dismiss="modal">{{ Lang::get('custom.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

{{-- LICENSE MODAL --}}

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

    <div class="modal-header d-block text-center">
        <h4 class="modal-title d-inline-block">{{ $langg->lang577 }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
    </div>

                <div class="modal-body">
                    <p class="text-center">{{ $langg->lang578 }} :  <span id="key"></span> <a href="javascript:;" id="license-edit">{{ $langg->lang577 }}</a><a href="javascript:;" id="license-cancel" class="showbox">{{ $langg->lang584 }}</a></p>
                    <form method="POST" action="{{route('vendor-order-license',$order->order_number)}}" id="edit-license" style="display: none;">
                        {{csrf_field()}}
                        <input type="hidden" name="license_key" id="license-key" value="">
                        <div class="form-group text-center">
                    <input type="text" name="{{ $langg->lang585 }}" placeholder="{{ $langg->lang579 }}" style="width: 40%; border: none;" required=""><input type="submit" name="submit" value="Save License" class="btn btn-primary" style="border-radius: 0; padding: 2px; margin-bottom: 2px;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $langg->lang580 }}</button>
                </div>
            </div>
        </div>
    </div>


{{-- LICENSE MODAL ENDS --}}

{{-- MESSAGE MODAL --}}
<div class="sub-categori">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel">{{ $langg->lang576 }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <div class="modal-body">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact-form">
                                <form id="emailreply">
                                    {{csrf_field()}}
                                    <ul>
                                        <li>
                                            <input type="email" class="input-field eml-val" id="eml" name="to" placeholder="{{ $langg->lang583 }} *" value="" required="">
                                        </li>
                                        <li>
                                            <input type="text" class="input-field" id="subj" name="subject" placeholder="{{ $langg->lang581 }} *" required="">
                                        </li>
                                        <li>
                                            <textarea class="input-field textarea" name="message" id="msg" placeholder="{{ $langg->lang582 }} *" required=""></textarea>
                                        </li>
                                    </ul>
                                    <button class="submit-btn" id="emlsub" type="submit">{{ $langg->lang576 }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

{{-- MESSAGE MODAL ENDS --}}




@endsection


@section('scripts')


<script type="text/javascript">
$('#example2').dataTable( {
  "ordering": false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
      'responsive'  : true
} );
</script>

    <script type="text/javascript">
      
        $('input[name=num_installment]').change(function() { 

            var num_installment=$('#num_installment').val();
          
            var html = '<table id="installment_table">';
          
            for(var i =1; i<= num_installment;i++)
            {
                html += '<tr><td><input class="input-field installment_date"  type="date" id="installment_date_' + i + '" name="installment_date[]" ></td><td>'+
                    
               '<input type="number" name="value[]" class="input-field" placeholder="{{Lang::get('custom.value_installment') }} *" id ="installment_vale_'+i+'"</td></tr>';
                $( ".installment_date").datepicker();

                $("#post_paid .installment_date").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "yy-mm-dd"
                 }).focus();
            }

            html += '</table>';
          
            $("#post_paid").append(html);
         });
        $(document).on('click','#license' , function(e){
            var id = $(this).parent().find('input[type=hidden]').val();
            var key = $(this).parent().parent().find('input[type=hidden]').val();
            $('#key').html(id);  
            $('#license-key').val(key);    
    });
        $(document).on('click','#license-edit' , function(e){
            $(this).hide();
            $('#edit-license').show();
            $('#license-cancel').show();
        });
        $(document).on('click','#license-cancel' , function(e){
            $(this).hide();
            $('#edit-license').hide();
            $('#license-edit').show();
        });

        $(document).on('submit','#edit-license' , function(e){
            e.preventDefault();
          $('button#license-btn').prop('disabled',true);
              $.ajax({
               method:"POST",
               url:$(this).prop('action'),
               data:new FormData(this),
               dataType:'JSON',
               contentType: false,
               cache: false,
               processData: false,
               success:function(data)
               {
                  if ((data.errors)) {
                    for(var error in data.errors)
                    {
                        $.notify('<li>'+ data.errors[error] +'</li>','error');
                    }
                  }
                  else
                  {
                    $.notify(data,'success');
                    $('button#license-btn').prop('disabled',false);
                    $('#confirm-delete').modal('toggle');

                   }
               }
                });
        });
    </script>

@endsection