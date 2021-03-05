@extends('layouts.front')
@section('content')

<head>
  <style>
    @media (min-width:961px)  {   
      #overflowTest {
      padding: 15px;
      width: 100%;
      height: 250px;
      overflow: scroll;
      border: 1px solid #ccc;
    }
   }

   @media (min-width:320px)  {   
    #overflowTest {
      width: 100%;
      height: 250px;
      overflow: scroll;
      border: 1px solid #ccc;
  }
  </style>
  </head>
<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
          <ul class="pages" itemscope itemtype="http://schema.org/BreadcrumbList">
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
              <a href="{{ route('front.blogshow', [$blog->id, $blog->slug_title]) }}" itemprop="item">
                <span itemprop="name">{{ $langg->lang39 }}</span>
              </a>
              <meta itemprop="position" content="3">
            </li>
          </ul>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Area End -->



  <!-- Blog Details Area Start -->
  <section class="blog-details" id="blog-details" itemprop='blogPost' itemscope='itemscope' itemtype='http://schema.org/BlogPosting'>
    <meta content='{{ route('front.blogshow', [$blog->id, $blog->slug_title]) }}' itemprop='url'/>
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="blog-content">
            <div class="feature-image">
              <img class="img-fluid" src="{{ asset('assets/images/blogs/'.$blog->photo) }}" alt="{{ $blog->title }}" title="{{ $blog->title }}" itemprop="image">
            </div>
            <div class="content">
                <h1 class="title" itemprop='name'>{{ $blog->title }}</h1>
                  <ul class="post-meta">
                    <li>
                      <a href="javascript:;">
                        <i class="icofont-calendar"></i>
                        <span itemprop='datePublished'>{{ date('d M, Y',strtotime($blog->created_at)) }}</span>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:;">
                          <i class="icofont-eye"></i>
                        {{ $blog->views }} {{ $langg->lang40 }}
                      </a>
                    </li>
                    <li>
                      <a href="javascript:;">
                        <i class="icofont-speech-comments"></i>
                        {{ $langg->lang41 }} : {{ $blog->source }}
                      </a>
                    </li>
                  </ul>
                  <div id="overflowTest" itemprop='description articleBody'> {!! $blog->details !!}</div>
                 

                  <div class="tag-social-link">
                    <div class="tag">
                      <h6 class="title">Tag : </h6>
                      @foreach(explode(',', $blog->tags) as $key => $tag)
                        <a href="{{ route('front.blogtags',$tag) }}">
                        {{ $tag }}{{ $key != count(explode(',', $blog->tags)) - 1  ? ',':''}}
                        </a>
                      @endforeach
                    </div>

                    <div class="social-sharing a2a_kit a2a_kit_size_32">
                    <ul class="social-links">
                      <li>
                        <a class="facebook a2a_button_facebook" href="">
                          <i class="fab fa-facebook-f"></i>
                        </a>
                      </li>
                        <li>
                            <a class="twitter a2a_button_twitter" href="">
                              <i class="fab fa-twitter"></i>
                            </a>
                          
                        </li>
                        <li>
                            <a class="linkedin a2a_button_linkedin" href="">
                              <i class="fab fa-linkedin-in"></i>
                            </a>

                        </li>
                        <li>
                          
                        <a class="a2a_dd plus" href="https://www.addtoany.com/share">
                            <i class="fas fa-plus"></i>
                          </a>
                        </li>
                      
                    </ul>
                    </div>
                    <script async src="https://static.addtoany.com/menu/page.js"></script>
                  </div>
            </div>
          </div>


    {{-- DISQUS START --}}   
    @if($gs->is_disqus == 1)
      <div class="comments">
           {!! $gs->disqus !!}
      </div>
    @endif
    {{-- DISQUS ENDS --}}

      </div>

        <div class="col-lg-4">
          <div class="blog-aside">
            <div class="serch-form">
              <form action="{{ route('front.blogsearch') }}">
                <input type="text" name="search" placeholder="{{ $langg->lang46 }}" required="">
                <button type="submit"><i class="icofont-search"></i></button>
              </form>
            </div>
            <div class="categori">
              <h4 class="title">{{ $langg->lang42 }}</h4>
              <span class="separator"></span>
              <ul class="categori-list">
                @foreach($bcats as $cat)
                <li>
                  <a href="{{ route('front.blogcategory',$cat->slug) }}"  {!! $cat->id == $blog->category_id ? 'class="active"':'' !!}>
                    <span>{{ $cat->name }}</span>
                    <span>({{ $cat->blogs()->count() }})</span>
                  </a>
                </li>
                @endforeach

              </ul>
            </div>
            <div class="recent-post-widget">
              <h4 class="title">{{ $langg->lang43 }}</h4>
              <span class="separator"></span>
              <ul class="post-list">

                @foreach (App\Models\Blog::orderBy('created_at', 'desc')->limit(4)->get() as $blog)
                <li>
                  <div class="post">
                    <div class="post-img">
                      <img style="width: 73px; height: 59px;" src="{{ asset('assets/images/blogs/'.$blog->photo) }}" alt="{{ $blog->title }}" title="{{ $blog->title }}">
                    </div>
                    <div class="post-details">
                      <a href="{{ route('front.blogshow', [$blog->id, $blog->slug_title]) }}">
                          <h4 class="post-title">
                              {{mb_strlen($blog->title,'utf-8') > 45 ? mb_substr($blog->title,0,45,'utf-8')." .." : $blog->title}}
                          </h4>
                      </a>
                      <p class="date">
                          {{ date('M d - Y',(strtotime($blog->created_at))) }}
                      </p>
                    </div>
                  </div>
                </li>
                @endforeach


              </ul>
            </div>
            <div class="archives">
              <h4 class="title">{{ $langg->lang44 }}</h4>
              <span class="separator"></span>
              <ul class="archives-list">
                @foreach($archives as $key => $archive)
                <li>
                  <a href="{{ route('front.blogarchive',$key) }}">
                    <span>{{ $key }}</span>
                    <span>({{ count($archive) }})</span>
                  </a>
                </li>
                @endforeach
              </ul>
            </div>
            <div class="tags">
              <h4 class="title">{{ $langg->lang45 }}</h4>
              <span class="separator"></span>
              <ul class="tags-list">
                @foreach($tags as $tag)
                  @if(!empty($tag))
                  <li>
                    <a href="{{ route('front.blogtags',$tag) }}">{{ $tag }} </a>
                  </li>
                  @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Blog Details Area End-->


@endsection
