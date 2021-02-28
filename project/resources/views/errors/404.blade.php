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
            <a href="javascript:;" itemprop="item">
              <span itemprop="name">{{ $langg->lang427 }}</span>
            </a>
            <meta itemprop="position" content="2">
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Area End -->

<section class="fourzerofour">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="content">
            <img src="{{ $gs->error_banner ? asset('assets/images/'.$gs->error_banner):asset('assets/images/noimage.png') }}" alt="">
            <h1 class="heading">{{ $langg->lang428 }}</h1>
            <p class="text">
              {{ $langg->lang429 }}
            </p>
            <a class="mybtn1" href="{{ route('front.index') }}">{{ $langg->lang430 }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>


@endsection