	<div class="col-lg-3 col-md-4 col-6 remove-padding">

<<<<<<< HEAD
		<a class="item" href="{{ route('front.product', $prod->slug) }}">
=======
		<a class="item" href="{{ route('front.product', [$prod->id, $prod->slug_name]) }}">
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

								<span href="javascript:;" class="add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}" data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}" data-placement="right"><i class="icofont-heart-alt" ></i>
								</span>

								@else 

								<span href="javascript:;" rel-toggle="tooltip" title="{{ $langg->lang54 }}" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg" data-placement="right">
									<i class="icofont-heart-alt"></i>
								</span>

								@endif
							</li>
							<li>
							<span class="quick-view" rel-toggle="tooltip" title="{{ $langg->lang55 }}" href="javascript:;" data-href="{{ route('product.quick',$prod->id) }}" data-toggle="modal" data-target="#quickview" data-placement="right"> <i class="icofont-eye"></i>
							</span>
							</li>
							<li>
								<span href="javascript:;" class="add-to-compare" data-href="{{ route('product.compare.add',$prod->id) }}"  data-toggle="tooltip" data-placement="right" title="{{ $langg->lang57 }}" data-placement="right">
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
			
				
				@endif
				<h5 class="name">{{ $prod->showName() }}</h5>
				<div class="item-cart-area">
				@if(Auth::guard('web')->check())
					@if($prod->product_type == "affiliate")
						<span class="add-to-cart-btn affilate-btn"
							data-href="{{ route('affiliate.product', $prod->slug) }}"><i class="icofont-cart"></i>
							{{ $langg->lang251 }}
						</span>
					@else
						@if($prod->emptyStock())
						<span class="add-to-cart-btn cart-out-of-stock">
							<i class="icofont-close-circled"></i> {{ $langg->lang78 }}
						</span>													
						@else
						<span class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',$prod->id) }}">
							<i class="icofont-cart"></i> {{ $langg->lang56 }}
						</span>
						<span class="add-to-cart-quick add-to-cart-btn"
							data-href="{{ route('product.cart.quickadd',$prod->id) }}">
							<i class="icofont-cart"></i> {{ $langg->lang251 }}
						</span>
						@endif
					@endif
					@else 
					 
					<span class="add-to-cart-quick add-to-cart-btn login-to-show-price"
						data-href="{{ route('user.login') }}">
						<i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}

					</span>		
                     @endif
				</div>
			
			</div>
		</a>
</div>