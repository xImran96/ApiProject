

		<li>
			<div class="single-box">
				<div class="left-area">
					<img src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="">
				</div>
				<div class="right-area">
						<div class="stars">
							<div class="ratings">
								<div class="empty-stars"></div>
								<div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
							</div>
							</div>
							@if(Auth::guard('web')->check())
			<h4 class="price">{{ $prod->showPrice() }} <del><small>{{ $prod->showPreviousPrice() }}</small></del></h4>
			@else 
			<h4 class="price"><a class=""
				href="{{ route('user.login') }}">
				<i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}

			</a></h4>
			@endif
							<p class="text"><a href="{{ route('front.product',$prod->slug) }}">{{ mb_strlen($prod->name,'utf-8') > 35 ? mb_substr($prod->name,0,35,'utf-8').'...' : $prod->name }}</a></p>
				</div>
			</div>
		</li>




