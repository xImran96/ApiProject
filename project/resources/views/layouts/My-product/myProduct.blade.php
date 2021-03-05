@extends('layouts.vendor') 
@section('styles')  
<style>
	#import{
		display: flex;
		justify-content: space-around;
	}
	#import a{
		font-size: 24px;
	}
	#import a .fa-edit{
     color:#9b59b6;
	}
	#import a .fa-trash{
		color: #e74c3c;
	}
	/*#import:hover{
		cursor: pointer;
	}*/
	.importBtn{
		border: none;
		outline: none;
		color: #e74c3c;
		font-size: 20px;

	}
	.importBtn:hover{
        cursor: pointer;
	}
</style>
<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=602ba54ff71937001207d4cc&product=inline-share-buttons" async="async"></script>
@endsection
@section('content')  

					<input type="hidden" id="headerdata" value="PRODUCT">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">{{ $langg->lang444 }}</h4>
										<ul class="links">
											<li>
												<a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
											</li>
											
											<li>
												<a href="{{ route('vendor-prod-index') }}">My Products</a>
											</li>
										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">

                        @include('includes.vendor.form-success')  

										<div class="table-responsiv">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Image</th>
									                        <th>{{ $langg->lang608 }}</th>
									                        <th>{{ $langg->lang609 }}</th>
									                        <th>Stock</th>
									                        <th>Hareer Price</th>
									                        <th>Profit Percentage</th>
									                        <th>New Price</th>
									                        <th>{{ $langg->lang612 }}</th>
														</tr>
													</thead>
													<tbody>
														@foreach($myProducts as $myProduct)
														<tr>
															<td><img src="{{asset('assets/images/products/'.$myProduct->photo)}}"></td>
															<td>{{$myProduct->slug}}</td>
															<td>{{$myProduct->type}}</td>
															<td>{{$myProduct->stock}}</td>
															<td>{{$myProduct->price}}</td>
															<td>{{$myProduct->profit_percentage}}</td>
															<td>{{$myProduct->new_price}}</td>
															<td id="import">
																
												
												
																<a href="{{url('/deletemyproduct',$myProduct->id)}}"  onclick="return confirm('Are you Sure?')"><i class="fas fa-trash">
																</i></a>

																<a href="{{url('editmyproduct',$myProduct->id)}}"><i class="fas fa-edit"></i></a>
																
																<div class="sharethis-inline-share-buttons"></div>
															</td>
														</tr>
														@endforeach
													</tbody>
												</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								{{ $myProducts->links() }}
							</div>
						</div>
					</div>




@endsection    

@section('scripts')


@endsection   