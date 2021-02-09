@foreach($prods as $prod)
	<div class="docname">
		<a href="{{ route('front.product', $prod->slug) }}">
			<img src="{{ asset('assets/images/thumbnails/'.$prod->thumbnail) }}" alt="">
			<div class="search-content">
				@if(Session::get('language')==2)	<p>{!! mb_strlen($prod->id,'utf-8') > 66 ? str_replace($slug,'<b>'.$slug.'</b>',mb_substr($prod->name_ar,0,66,'utf-8')).'...' : str_replace($slug,'<b>'.$slug.'</b>',$prod->name_ar)  !!} </p>
				@else 
				<p>{!! mb_strlen($prod->id,'utf-8') > 66 ? str_replace($slug,'<b>'.$slug.'</b>',mb_substr($prod->name_en,0,66,'utf-8')).'...' : str_replace($slug,'<b>'.$slug.'</b>',$prod->name_en)  !!} </p>
				@endif
				<span style="font-size: 14px; font-weight:600; display:block;">{{ $prod->showPrice() }}</span>
			</div>
		</a>
	</div> 
@endforeach