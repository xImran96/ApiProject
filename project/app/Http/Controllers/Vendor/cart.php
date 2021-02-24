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
                                                $user = App\Models\DealerOrderDetail::where('order_id','=',$order->id)->where('dealer_id','=',$product['item']['user_id'])->first();
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
                                              <a target="_blank" href="{{ route('front.product', $product['item']['slug']) }}">{{mb_strlen($product['item']['name'],'utf-8') > 30 ? mb_substr($product['item']['name'],0,30,'utf-8').'...' : $product['item']['name']}}</a>
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

                                            <td></td>

                                    </tr>

                    @endif

                @endif
                                @endforeach