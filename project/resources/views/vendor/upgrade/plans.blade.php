@extends('layouts.vendor') 

@section('content')

<div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading">Upgrade Plan</h4>
                                        <ul class="links">
                                            <li>
                                                <a href="">{{ $langg->lang441 }} </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">Subscription Plans</a>
                                            </li>
                                            <li>
                                                <a href="">Choose</a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>
      
<section class="col-lg-12 mx auto mt2">
    <div class="container">
      <div class="row">
        
<div class="col-lg-12">
<div class="user-profile-details">
                        


                        <div class="row">
                            @foreach($subs as $sub)
                                <div class="col-lg-6 mx-auto">
                                    <div class="elegant-pricing-tables style-2 text-center">
                                        <div class="pricing-head">
                                            <h3>{{ $sub->title }}</h3>
                                            @if($sub->price  == 0)
                                            <span class="price">
                                            <span class="price-digit">{{ $langg->lang402 }}</span>
                                            </span>
                                            @else
                                            <span class="price">
                                                <sup>{{ $sub->currency }}</sup>
                                                <span class="price-digit">{{ $sub->price }}</span><br>
                                                <span class="price-month">{{ $sub->days }} {{ $langg->lang403 }}</span>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="pricing-detail">
                                            <p>Details {!! $sub->details !!}</p>
                                            <p>Per Delivary Charges / {!! $sub->per_delivery_charges !!} $</p>
                                            <p>Per Order Charges / {!! $sub->per_order_charges !!} SAR</p>
                                            <p>Preparation Cost / {!! $sub->preparation_cost !!} SAR</p>
        
                                        </div>
                                    @if(!empty($package))
                                        @if($package->subscription_id == $sub->id)
                                            <a href="javascript:;" class="btn btn-default">{{ $langg->lang404 }}</a>
                                            <br>
                                            @if(Carbon\Carbon::now()->format('Y-m-d') > $user->date)
                                            <small class="hover-white">{{ $langg->lang405 }} {{ date('d/m/Y',strtotime($user->date)) }}</small>
                                            @else
                                            <small class="hover-white">{{ $langg->lang406 }} {{ date('d/m/Y',strtotime($user->date)) }}</small>
                                            @endif
                                             <a href="{{route('user-vendor-request',$sub->id)}}" class="hover-white"><u>{{ $langg->lang407 }}</u></a>
                                        @else
                                            <a href="{{route('vendor-upgrade',$sub->id)}}" class="btn btn-default">
                                                <!-- {{ $langg->lang408 }} -->
                                            Upgrade Plan</a>
                                            <br><small>&nbsp;</small>
                                        @endif
                                    @else
                                        <a href="{{route('user-vendor-request',$sub->id)}}" class="btn btn-default">{{ $langg->lang408 }}</a>
                                        <br><small>&nbsp;</small>
                                    @endif


{{--                                         <a href="#" class="btn btn-default">Get Started Now</a>   --}}                
                                    </div>
                                </div>

                                @endforeach



                        </div>
                    </div>
                </div>
      </div>
    </div>
  </section>
</div>


@endsection
