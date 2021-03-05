@extends('layouts.front')
@section('content')


  <!-- Breadcrumb Area Start -->
  <div class="breadcrumb-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <ul class="pages" itemscope itemtype="http://schema.org/BreadcrumbList">

          {{-- Category Breadcumbs --}}

          @if(isset($bcat))
                
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.index') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang17 }}</span>
                </a>
                <meta itemprop="position" content="1">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.blog') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang18 }}</span>
                </a>
                <meta itemprop="position" content="2">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.blogcategory',$bcat->slug) }}" itemprop="item">
                  <span itemprop="name">{{ $bcat->name }}</span>
                </a>
                <meta itemprop="position" content="3">
              </li>

          @elseif(isset($slug))

              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.index') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang17 }}</span>
                </a>
                <meta itemprop="position" content="1">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.blog') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang18 }}</span>
                </a>
                <meta itemprop="position" content="2">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.blogtags',$slug) }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang35 }}: {{ $slug }}</span>
                </a>
                <meta itemprop="position" content="3">
              </li>

          @elseif(isset($search))
                
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.index') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang17 }}</span>
                </a>
                <meta itemprop="position" content="1">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.blog') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang18 }}</span>
                </a>
                <meta itemprop="position" content="2">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="Javascript:;" itemprop="item">
                  <span itemprop="name">{{ $langg->lang36 }}</span>
                </a>
                <meta itemprop="position" content="3">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="Javascript:;" itemprop="item">
                  <span itemprop="name">{{ $search }}</span>
                </a>
                <meta itemprop="position" content="4">
              </li>

          @elseif(isset($date))
                
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.index') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang17 }}</span>
                </a>
                <meta itemprop="position" content="1">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.blog') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang18 }}</span>
                </a>
                <meta itemprop="position" content="2">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="Javascript:;" itemprop="item">
                  <span itemprop="name">{{ $langg->lang37 }}: {{ date('F Y',strtotime($date)) }}</span>
                </a>
                <meta itemprop="position" content="3">
              </li>

          @else
                
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.index') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang17 }}</span>
                </a>
                <meta itemprop="position" content="1">
              </li>
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ route('front.blog') }}" itemprop="item">
                  <span itemprop="name">{{ $langg->lang18 }}</span>
                </a>
                <meta itemprop="position" content="2">
              </li>
          @endif

          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumb Area End -->

  <!-- Blog Page Area Start -->
  <section class="blogpagearea">
    <div class="container">
      <h1 style="text-align:center;font-size:30px">{{ $langg->lang18 }}</h1>
      <div id="ajaxContent">

      <div class="row">

        @foreach($blogs as $blogg)
        <div class="col-md-6 col-lg-4">
              <div class="blog-box">
                <div class="blog-images">
                    <div class="img">
                    <img src="{{ $blogg->photo ? asset('assets/images/blogs/'.$blogg->photo):asset('assets/images/noimage.png') }}" class="img-fluid" alt="{{ $blogg->title }}" title="{{ $blogg->title }}">
                    <div class="date d-flex justify-content-center">
                      <div class="box align-self-center">
                        <p>{{date('d', strtotime($blogg->created_at))}}</p>
                        <p>{{date('M', strtotime($blogg->created_at))}}</p>
                      </div>
                    </div>
                    </div>
                </div>
                <div class="details">
                    <a href='{{route('front.blogshow', [$blogg->id, $blogg->slug_title])}}'>
                      <h4 class="blog-title">
                        {{mb_strlen($blogg->title,'utf-8') > 50 ? mb_substr($blogg->title,0,50,'utf-8')."...":$blogg->title}}
                      </h4>
                    </a>
                  <p class="blog-text">
                    {{substr(strip_tags($blogg->details),0,120)}}
                  </p>
                  <a class="read-more-btn" href="{{route('front.blogshow', [$blogg->id, $blogg->slug_title])}}">{{ $langg->lang38 }}</a>
                </div>
            </div>
        </div>


        @endforeach

      </div>

        <div class="page-center">
          {!! $blogs->links() !!}               
        </div>
</div>

    </div>
  </section>
  <!-- Blog Page Area Start -->




@endsection


@section('scripts')

<script type="text/javascript">
  

    // Pagination Starts

    $(document).on('click', '.pagination li', function (event) {
      event.preventDefault();
      if ($(this).find('a').attr('href') != '#' && $(this).find('a').attr('href')) {
        $('#preloader').show();
        $('#ajaxContent').load($(this).find('a').attr('href'), function (response, status, xhr) {
          if (status == "success") {
            $("html,body").animate({
              scrollTop: 0
            }, 1);
            $('#preloader').fadeOut();


          }

        });
      }
    });

    // Pagination Ends

</script>


@endsection