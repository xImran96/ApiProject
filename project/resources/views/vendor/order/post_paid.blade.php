@extends('layouts.vendor')
     


@section('content')
    <div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading">{{ Lang::get('custom.order_confirm_post_paid') }} <a class="add-btn" href="{{route('vendor-order-show',$order->order_number)}}"><i class="fas fa-arrow-left"></i> {{ $langg->lang550 }}</a></h4>
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
                                            <li>
                                                <a href="javascript:;">{{ Lang::get('custom.order_confirm_post_paid') }}</a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>

                        <div class="order-table-wrap">
                            @include('includes.admin.form-both')
                        </div>

                        
                        <div class="row">
                            <div class="col-lg-12 order-details-table">
                                <div class="mr-table">
                                    <h4 class="title">{{ $langg->lang566 }}</h4>
                                    <div class="table-responsiv">
                                            <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                        <tr>
                            <th>{{ $langg->lang567 }}</th>
                            <th>{{ $langg->lang568 }}</th>
                            <th>{{ $langg->lang569 }}</th>
                            <th>{{ $langg->lang570 }}</th>
                            <th>{{ $langg->lang539 }}</th>
                            <th>{{ $langg->lang574 }}</th>
                            <th>{{Lang::get('custom.is_approved_product') }}</th>
                        </tr>
                                                    </tr>
                                                </thead>
                                                <tbody>
                        @foreach($cart->items as $key => $product)

                        @if($product['item']['user_id'] != 0)
                            @if($product['item']['user_id'] == $user->id)
                            <tr>
                                
                                    <td><input type="hidden" value="{{$key}}">{{ $product['item']['id'] }}</td>

                                    <td>
                                        @if($product['item']['user_id'] != 0)
                                        @php
                                        $user = App\Models\User::find($product['item']['user_id']);
                                        @endphp
                                        @if(isset($user))
                                        <a target="_blank" href="{{route('admin-vendor-show',$user->id)}}">{{$user->shop_name}}</a>
                                        @else
                                        {{ $langg->lang575 }}
                                        @endif
                                        @endif

                                    </td>
                                    <td>
                                        @if($product['item']['user_id'] != 0)
                                        @php
                                        $user = App\Models\VendorOrder::where('order_id','=',$order->id)->where('user_id','=',$product['item']['user_id'])->first();
                                        @endphp

                                            @if($order->dp == 1 && $order->payment_status == 'Completed')

                                           <span class="badge badge-success">{{ $langg->lang542 }}</span>

                                            @else

                                                @if($user->status == 'pending')
                                                <span class="badge badge-warning">{{ucwords($user->status)}}</span>
                                                @elseif($user->status == 'processing')
                                                <span class="badge badge-info">{{ucwords($user->status)}}</span>
                                               @elseif($user->status == 'on delivery')
                                                <span class="badge badge-primary">{{ucwords($user->status)}}</span>
                                               @elseif($user->status == 'completed')
                                                <span class="badge badge-success">{{ucwords($user->status)}}</span>
                                               @elseif($user->status == 'declined')
                                                <span class="badge badge-danger">{{ucwords($user->status)}}</span>
                                               @endif

                                            @endif

                                    @endif
                                    </td>



                                    <td>
                                        <input type="hidden" value="{{ $product['license'] }}">

                                        @if($product['item']['user_id'] != 0)
                                        @php
                                        $user = App\Models\User::find($product['item']['user_id']);
                                        @endphp
                                        @if(isset($user))
<<<<<<< HEAD
                                      <a target="_blank" href="{{ route('front.product', $product['item']['slug']) }}">{{mb_strlen($product['item']['name'],'utf-8') > 30 ? mb_substr($product['item']['name'],0,30,'utf-8').'...' : $product['item']['name']}}</a>
=======
                                        <?php $productt = App\Models\Product::find($product['item']['id']) ?>
                                        <a target="_blank" href="{{ route('front.product', [$productt->id, $productt->slug_name]) }}">{{mb_strlen($product['item']['name'],'utf-8') > 30 ? mb_substr($product['item']['name'],0,30,'utf-8').'...' : $product['item']['name']}}</a>
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
                                        @else
                                        <a href="javascript:;">{{mb_strlen($product['item']['name'],'utf-8') > 30 ? mb_substr($product['item']['name'],0,30,'utf-8').'...' : $product['item']['name']}}</a>
                                        @endif
                                        @endif


                                        @if($product['license'] != '')
                      <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete" class="btn btn-info product-btn" id="license" style="padding: 5px 12px;"><i class="fa fa-eye"></i> View License</a>
                                        @endif

                                    </td>
                                    <td>
                                        @if($product['size'])
                                       <p>
                                            <strong>{{ $langg->lang312 }} :</strong> {{str_replace('-',' ',$product['size'])}}
                                       </p>
                                       @endif
                                       @if($product['color'])
                                        <p>
                                                <strong>{{ $langg->lang313 }} :</strong> <span
                                                style="width: 40px; height: 20px; display: block; background: #{{$product['color']}};"></span>
                                        </p>
                                        @endif
                                        <p>
                                                <strong>{{ $langg->lang754 }} :</strong> {{$order->currency_sign}}{{ round($product['item_price'] * $order->currency_value , 2) }}
                                        </p>
                                       <p>
                                            <strong>{{ $langg->lang311 }} :</strong> {{$product['qty']}} {{ $product['item']['measure'] }}
                                       </p>
                                            @if(!empty($product['keys']))

                                            @foreach( array_combine(explode(',', $product['keys']), explode(',', $product['values']))  as $key => $value)
                                            <p>

                                                <b>{{ ucwords(str_replace('_', ' ', $key))  }} : </b> {{ $value }} 

                                            </p>
                                            @endforeach

                                            @endif

                                    </td>

                                     <td>{{$order->currency_sign}}{{ round($product['price'] * $order->currency_value , 2) }}</td>
                
                                     <td> <input type="checkbox" id="is_approved" data-id="{{$product['item']['id']}}"   name="is_approved[]"></td>
                            </tr>

            @endif

        @endif
                        @endforeach
                                                </tbody>
                                            </table>
                                            <div class="row">
                                                <div class="col-lg-12 order-details-table text-center">
                                                    <div class="mr-table">
                                                        <h4 class="title">{{ Lang::get('custom.installments') }}</h4>
                                                        <input type="number" class="input-field" id="num_installment" name="num_installment" placeholder="{{Lang::get('custom.num_installment') }} *" value="" required="">
                                                        <div id="post_paid" class="col-lg-12" >
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-right">
                                                    <a href="javascript:;" class="btn btn-success" id="send" data-order="{{$order->order_number}}">
                                                            <i class="fa fa-send"></i> {{ Lang::get('custom.confirm') }}
                                                    </a>
                                                </div>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                         
                        </div>
    </div>

    @endsection

    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js" integrity="sha512-MqEDqB7me8klOYxXXQlB4LaNf9V9S0+sG1i8LtPOYmHqICuEZ9ZLbyV3qIfADg2UJcLyCm4fawNiFvnYbcBJ1w==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" integrity="sha512-f8gN/IhfI+0E9Fc/LKtjVq4ywfhYAVeMGKsECzDUHcFJ5teVwvKTqizm+5a84FINhfrgdvjX8hEJbem2io1iTA==" crossorigin="anonymous" />
<script>
    function search( myArray){
        for (var i=0; i < myArray.length; i++) {
            if (myArray[i].is_selected==true) {
                return true;
            }
        }
        return false;
    }

    function validate( myArray){
        for (var i=0; i < myArray.length; i++) {
            if (myArray[i]=='') {
                return false;
            }
        }
        return true;
    }

   
    $('#send').on('click',function(){

        var product_selected=[];
        var order_id=$(this).data('order');
   
        var values = $("input[name='is_approved[]']")
        .map(function(){
              product_selected.push({ 
                "product_id" :$(this).data('id'),
                "is_selected"  : $(this).prop('checked'),
            });
             return product_selected;
        
        }).get();
        var installment_date = $("input[name='installment_date[]']")
        .map(function(){
          
             return $(this).val();
        
        }).get();

        var instalment_values = $("input[name='values[]']")
        .map(function(){
             return $(this).val();
        }).get();
        var total = 0;
        for (var i = 0; i < instalment_values.length; i++) {
            total += instalment_values[i]<< 0;
        }
  
        var resultObject = search(values);
        var resutlInstallmentcheck=validate(installment_date);
        var resultInstallmentvalue=validate(instalment_values);
      
        var num_installment=$('#num_installment').val();

        if(!resultObject)
        {
            swal("warning", "please select one product at least", "error");
        } else
        if(num_installment==''){
            swal("warning", "please enter installment number", "error");
        }
        else
        if(!resutlInstallmentcheck)
        {
            swal("warning", "please enter all date", "error");
        } else if(!resultInstallmentvalue)
        {
            swal("warning", "please enter all values", "error");
        } else 
        {
            $.ajax({
                type: "post",
                url: "{{ route('check_product_price') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    order:order_id,
                    items: values,
                    values:instalment_values
                }, success: function(data){
                    if(data.message == 1) 
                    {
                        swal("warning", "all installment value less than selected product "+data.total+"and your calculation installment value is "+total, "error");

                    } else if(data.message == 2)
                    {
                        swal("warning", "all installment value garter than selected product "+data.total+"and your calculation installment value is "+total, "error");
                    } else {
                        $.ajax({
                            type: "post",
                            url: "{{ route('save_installment_product') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                order:order_id,
                                items: values,
                                values:instalment_values,
                                dates:installment_date,
                            }, success: function(data)
                            {
                                if(data.message == 1) 
                                {
                                    setTimeout(function() {
                                    swal({
                                        title: "Good job!!",
                                        text: "order installment Succssfully Saved!",
                                        type: "success"
                                    }, function() {
                                        window.location = '{{ route('vendor-order-index')}}';
                                    });
                                }, 3000);
                                   // swal("warning", "all installment value less than selected product "+data.total+"and your calculation installment value is "+total, "error");
                                  // swal("Good job!", "!", "success");
                                   //window.location = '{{ route('vendor-order-index')}}'
                                }
                            }
                        });
                    }
                    ; // "Success"
                }
            });
        }
        // validate values of selected product 
      
     });
    $('input[name=num_installment]').change(function() { 
        $("#post_paid").empty();
        var num_installment=$('#num_installment').val();
      
        var html = '<table id="installment_table">';
      
        for(var i =1; i<= num_installment;i++)
        {
            html += '<tr><td><input class="input-field installment_date"  type="date" id="installment_date_' + i + '" name="installment_date[]" ></td><td>'+
                
           '<input type="number" name="values[]" class="input-field" placeholder="{{Lang::get('custom.value_installment') }} *" id ="installment_vale_'+i+'"</td></tr>';
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
</script>






    @endsection