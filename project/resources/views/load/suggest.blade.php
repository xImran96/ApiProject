@foreach($prods as $prod)
	<div class="docname">
<<<<<<< HEAD
		<a href="{{ route('front.product', $prod->slug) }}">
			<img src="{{ asset('assets/images/thumbnails/'.$prod->thumbnail) }}" alt="">
			<div class="search-content">
				<p>{!! mb_strlen($prod->id,'utf-8') > 66 ? str_replace($slug,'<b>'.$slug.'</b>',mb_substr($prod->name,0,66,'utf-8')).'...' : str_replace($slug,'<b>'.$slug.'</b>',$prod->name)  !!} </p>
				<span style="font-size: 14px; font-weight:600; display:block;">{{ $prod->showPrice() }}</span>
=======
		<a href="{{ route('front.product', [$prod->id, $prod->slug_name]) }}">
			<img src="{{ asset('assets/images/thumbnails/'.$prod->thumbnail) }}" alt="">
			<div class="search-content">
				@if(Session::get('language')==2)	<p>{!! mb_strlen($prod->id,'utf-8') > 66 ? str_replace($slug,'<b>'.$slug.'</b>',mb_substr($prod->name_ar,0,66,'utf-8')).'...' : str_replace($slug,'<b>'.$slug.'</b>',$prod->name_ar)  !!} </p>
				@else 
				<p>{!! mb_strlen($prod->id,'utf-8') > 66 ? str_replace($slug,'<b>'.$slug.'</b>',mb_substr($prod->name_en,0,66,'utf-8')).'...' : str_replace($slug,'<b>'.$slug.'</b>',$prod->name_en)  !!} </p>
				@endif
				@if(Auth::guard('web')->check())
				<span style="font-size: 14px; font-weight:600; display:block;">{{ $prod->showPrice() }}</span>
			@else 
			<h4 class="price"><span class="add-to-cart-quick add-to-cart-btn login-to-show-price"
				data-href="{{ route('user.login') }}">
				<i class="icofont-eye"></i> {{ __('custom.login_to_show_price') }}

			</span></h4>
			@endif
				
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
			</div>
		</a>
	</div> 
@endforeach