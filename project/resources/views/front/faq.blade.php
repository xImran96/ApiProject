@extends('layouts.front')
@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
<<<<<<< HEAD
        <ul class="pages">
          <li>
            <a href="{{ route('front.index') }}">
              {{ $langg->lang17 }}
            </a>
          </li>
          <li>
            <a href="{{ route('front.faq') }}">
              {{ $langg->lang19 }}
            </a>
=======
        <ul class="pages" itemscope itemtype="http://schema.org/BreadcrumbList">
          <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="{{ route('front.index') }}" itemprop="item">
              <span itemprop="name">{{ $langg->lang17 }}</span>
            </a>
            <meta itemprop="position" content="1">
          </li>
          <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="{{ route('front.faq') }}" itemprop="item">
              <span itemprop="name">{{ $langg->lang19 }}</span>
            </a>
            <meta itemprop="position" content="2">
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Area End -->



  <!-- faq Area Start -->
  <section class="faq-section">
    <div class="container">
<<<<<<< HEAD
=======
      <h1 style="text-align:center;font-size:30px">{{ $langg->lang19 }}</h1>
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div id="accordion">

            @foreach($faqs as $fq)
            <h3 class="heading">{{ $fq->title }}</h3>
            <div class="content">
                <p>{!! $fq->details !!}</p>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- faq Area End-->

@endsection