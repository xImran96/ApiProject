@extends('layouts.vendor')
@section('styles')
<style>
</style>
@endsection

@section('content')

<section class="container">
   
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">Accounts</h4>
										<ul class="links">
											<li>
												<a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
											</li>
											
											<li>
												<a href="{{ route('vendor-prod-index') }}">Accounts</a>
											</li>
                                            <li>
												<a href="{{ route('finance') }}">Finance</a>
											</li>
										</ul>
								</div>
							</div>
						</div>
                    </div>
                    <div class="content-area">
                        <div class="row row-cards-one">
                    
                            <div class="col-md-12 col-lg-6 col-xl-6">
                                <div class="mycard bg1">
                                    <div class="left">
                                        <h5 class="title">Profit Per Order</h5>
                                        <span class="number">{{ $sum }}</span>
                                        <a href="{{route('profitPerOrder')}}" class="link">{{ $langg->lang471 }}</a>
                                    </div>
                                    <div class="right d-flex align-self-center">
                                        <div class="icon">
                                            <i class="icofont-dollar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-6">
                                <div class="mycard bg6">
                                    <div class="left">
                                        <h5 class="title">Total Revenue</h5>
                                        <span class="number">{{ $sum }}</span>
                                    </div>
                                    <div class="right d-flex align-self-center">
                                        <div class="icon">
                                           <i class="icofont-dollar-true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-6">
                                <div class="mycard bg3">
                                    <div class="left">
                                        <h5 class="title">Active Balance</h5>
                                        <span class="number">{{$blc}}</span>
                                       
                                    </div>
                                    <div class="right d-flex align-self-center">
                                        <div class="icon">
                                            <i class="icofont-dollar-true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col-md-12 col-lg-6 col-xl-6">
                                <div class="mycard bg2">
                                    <div class="left">
                                        <h5 class="title">Request Transfer</h5>
                                        <span class="number"></span>
                                        <a href="{{route('vendor-order-index')}}" class="link">{{ $langg->lang471 }}</a>
                                    </div>
                                    <div class="right d-flex align-self-center">
                                        <div class="icon">
                                            <i class="icofont-truck-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            
                    
                      
                    
                    
                        </div>
                    </div>
                    </div>
	
</section>

@endsection

@section('scripts')
	
@endsection