      <div class="row">

        @foreach($blogs as $blogg)
        <div class="col-md-6 col-lg-4">
              <div class="blog-box">
                <div class="blog-images">
                    <div class="img">
<<<<<<< HEAD
                    <img src="{{ $blogg->photo ? asset('assets/images/blogs/'.$blogg->photo):asset('assets/images/noimage.png') }}" class="img-fluid" alt="">
=======
                    <img src="{{ $blogg->photo ? asset('assets/images/blogs/'.$blogg->photo):asset('assets/images/noimage.png') }}" class="img-fluid" alt="{{ $blogg->title }}" title="{{ $blogg->title }}">
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
                    <div class="date d-flex justify-content-center">
                      <div class="box align-self-center">
                        <p>{{date('d', strtotime($blogg->created_at))}}</p>
                        <p>{{date('M', strtotime($blogg->created_at))}}</p>
                      </div>
                    </div>
                    </div>
                </div>
                <div class="details">
<<<<<<< HEAD
                    <a href='{{route('front.blogshow',$blogg->id)}}'>
=======
                    <a href='{{route('front.blogshow', [$blogg->id, $blogg->slug_title])}}'>
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
                      <h4 class="blog-title">
                        {{mb_strlen($blogg->title,'utf-8') > 50 ? mb_substr($blogg->title,0,50,'utf-8')."...":$blogg->title}}
                      </h4>
                    </a>
                  <p class="blog-text">
                    {{substr(strip_tags($blogg->details),0,120)}}
                  </p>
<<<<<<< HEAD
                  <a class="read-more-btn" href="{{route('front.blogshow',$blogg->id)}}">{{ $langg->lang38 }}</a>
=======
                  <a class="read-more-btn" href="{{route('front.blogshow', [$blogg->id, $blogg->slug_title])}}">{{ $langg->lang38 }}</a>
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
                </div>
            </div>
        </div>


        @endforeach

      </div>

        <div class="page-center">
          {!! $blogs->links() !!}               
        </div>