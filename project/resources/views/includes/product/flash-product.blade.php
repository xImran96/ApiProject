
@if(strpos($prod->discount_date, '-') !== false || strpos($prod->discount_date, '/') !== false)

@if(Carbon\Carbon::now()->format('Y-m-d') < Carbon\Carbon::parse($prod->discount_date)->format('Y-m-d'))


<<<<<<< HEAD
	<a href="{{ route('front.product', $prod->slug) }}" class="item">
=======
	<a href="{{ route('front.product', [$prod->id, $prod->slug_name]) }}" class="item">
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
		<div class="item-img">
			@if(!empty($prod->features))
				<div class="sell-area">
				@foreach($prod->features as $key => $data1)
					<span class="sale" style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
					@endforeach 
				</div>
			@endif
				<div class="extra-list">
					<ul>
						<li>
							@if(Auth::guard('web')->check())

							<span class="add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}" data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}" data-placement="right"><i class="icofont-heart-alt" ></i>
							</span>

							@else 

							<span rel-toggle="tooltip" title="{{ $langg->lang54 }}" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg" data-placement="right">
								<i class="icofont-heart-alt"></i>
							</span>

							@endif
						</li>
						<li>
						<span class="quick-view" rel-toggle="tooltip" title="{{ $langg->lang55 }}" href="javascript:;" data-href="{{ route('product.quick',$prod->id) }}" data-toggle="modal" data-target="#quickview" data-placement="right"> <i class="icofont-eye"></i>
						</span>
						</li>
						<li>
							<span class="add-to-compare" data-href="{{ route('product.compare.add',$prod->id) }}"  data-toggle="tooltip" data-placement="right" title="{{ $langg->lang57 }}" data-placement="right">
								<i class="icofont-exchange"></i>
							</span>
						</li>
					</ul>
				</div>
<<<<<<< HEAD
			<img class="img-fluid" src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="">
=======
			<img class="img-fluid" src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="{{ Session::get('language') != 1 ? $prod->name_ar : $prod->name_en }}" title="{{ Session::get('language') != 1 ? $prod->name_ar : $prod->name_en }}">
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
		</div>
		<div class="info">
			<div class="stars">
																<div class="ratings">
																		<div class="empty-stars"></div>
																		<div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
																</div>
			</div>
			@if(Auth::guard('web')->check())
			<h4 class="price">{{ $prod->showPrice() }} <del><small>{{ $prod->showPreviousPrice() }}</small></del></h4>
			@else 
			<h4 class="price"><span class="add-to-cart-quick add-to-cart-btn login-to-show-price"
				data-href="{{ route('user.login') }}">
				<i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}

			</span></h4>
			@endif
					<h5 class="name">{{ $prod->showName() }}</h5>
					<div class="item-cart-area">
												@if($prod->product_type == "affiliate")
													<span class="add-to-cart-btn affilate-btn" data-href="{{ route('affiliate.product', $prod->slug) }}"><i class="icofont-cart"></i> {{ $langg->lang251 }}
													</span>
												@else
													<span class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',$prod->id) }}">
														<i class="icofont-cart"></i> {{ $langg->lang56 }}
													</span>
													<span class="add-to-cart-quick add-to-cart-btn" data-href="{{ route('product.cart.quickadd',$prod->id) }}">
														<i class="icofont-cart"></i> {{ $langg->lang251 }}
													</span>
												@endif
					</div>
		</div>
		
		<div class="deal-counter">
		<div data-countdown="{{ $prod->discount_date }}"></div>
		</div>
	</a>


@endif
			

@endif