@extends('layouts.front')
@section('content')

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
            <a href="{{ route('front.page',$page->slug) }}" itemprop="item">
              <span itemprop="name">{{ $page->title }}</span>
            </a>
            <meta itemprop="position" content="2">
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Area End -->



<section class="about" itemscope itemtype="http://schema.org/AboutPage">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="about-info">
            <h1 class="title" itemprop="name">{{ $page->title }}</h1>
            <p itemprop="text">
              {!! $page->details !!}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection