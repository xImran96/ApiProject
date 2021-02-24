@extends('layouts.vendor') 

@section('content')
                    <div class="content-area">

                            @if($user->checkWarning())

                                <div class="alert alert-danger validation text-center">
                                        <h3>{{ $user->displayWarning() }} </h3> <a href="{{ route('vendor-warning',$user->verifies()->where('admin_warning','=','1')->orderBy('id','desc')->first()->id) }}"> {{$langg->lang803}} </a>
                                </div>

                            @endif

                        
                        @include('includes.form-success')
                        <div class="row row-cards-one">

                            @foreach($categories as $category)

                                <div class="col-md-12 col-lg-6 col-xl-4">
                                    <div class="mycard bg1">
                                        <div class="left">
                                            <h5 class="title">
                                                <!-- {{ $langg->lang465 }}  -->
                                                 {{ $category->name }}
                                            </h5>
                                            <span class="number"></span>
                                            <a href="{{route('vendor-order-index')}}" class="link">{{ $langg->lang471 }}</a>
                                        </div>
                                        <div class="right d-flex align-self-center">
                                            <div class="icon">
                                                <i class="icofont-dollar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                         

                            </div>
                    </div>

@endsection
