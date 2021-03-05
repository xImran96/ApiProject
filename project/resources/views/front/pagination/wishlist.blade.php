			<div class="row wish-list-area">

				@foreach($wishlists as $wishlist)

				@if(!empty($sort))


				<div class="col-lg-6">
					<div class="single-wish">
						<span class="remove wishlist-remove" data-href="{{ route('user-wishlist-remove', App\Models\Wishlist::where('user_id','=',$user->id)->where('product_id','=',$wishlist->id)->first()->id ) }}"><i class="fas fa-times"></i></span>
						<div class="left">
							<img src="{{ $wishlist->photo ? asset('assets/images/products/'.$wishlist->photo):asset('assets/images/noimage.png') }}" alt="">
						</div>
						<div class="right">
							<h4 class="title">
<<<<<<< HEAD
								<a href="{{ route('front.product', $wishlist->slug) }}">
=======
								<a href="{{ route('front.product', [$wishlist->id, $wishlist->slug_name]) }}">
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
								{{ $wishlist->name }}
								</a>
							</h4>
							<ul class="stars">
                                <div class="ratings">
                                    <div class="empty-stars"></div>
                                   	<div class="full-stars" style="width:{{App\Models\Rating::ratings($wishlist->id)}}%"></div>
                                </div>
							</ul>
							<div class="price">
								{{ $wishlist->showPrice() }}<small><del>{{ $wishlist->showPreviousPrice() }}</del></small>
							</div>
						</div>
					</div>
				</div>

				@else

				<div class="col-lg-6">
					<div class="single-wish">
						<span class="remove wishlist-remove" data-href="{{ route('user-wishlist-remove',$wishlist->id) }}"><i class="fas fa-times"></i></span>
						<div class="left">
							<img src="{{ $wishlist->product->photo ? asset('assets/images/products/'.$wishlist->product->photo):asset('assets/images/noimage.png') }}" alt="">
						</div>
						<div class="right">
							<h4 class="title">
<<<<<<< HEAD
						<a href="{{ route('front.product', $wishlist->product->slug) }}">
=======
						<a href="{{ route('front.product', [$wishlist->product->id, $wishlist->product->slug_name]) }}">
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
							{{ $wishlist->product->name }}
						</a>
							</h4>
							<ul class="stars">
                                <div class="ratings">
                                    <div class="empty-stars"></div>
                                   	<div class="full-stars" style="width:{{App\Models\Rating::ratings($wishlist->product->id)}}%"></div>
                                </div>
							</ul>
							<div class="price">
								{{ $wishlist->product->showPrice() }}<small><del>{{ $wishlist->product->showPreviousPrice() }}</del></small>
							</div>
						</div>
					</div>
				</div>

				@endif
				@endforeach
				
			</div>

			@if(isset($sort))

				<div class="page-center category">
					{!! $wishlists->appends(['sort' => $sort])->links() !!}          
				</div>

			 @else 

				<div class="page-center category">
					{!! $wishlists->links() !!}               
				</div>

			@endif
<script type="text/javascript">
        $("#sortby").on('change',function () {
        var sort = $("#sortby").val();
        window.location = "{{url('/user/wishlists')}}?sort="+sort;
    	});
</script>
