@extends('layouts.vendor')
@section('styles')

@endsection
 
@section('content')

<div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading">Profit</h4>
                                        <ul class="links">
                                            <li>
                                                <a href="">
                                                    <!-- {{ $langg->lang441 }} --> Dashboard
                                                     </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">Account</a>
                                            </li>
                                            <li>
                                                <a href="">Profit Percentage</a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>

<section class="col-lg-8 mx-auto mt-2">

@if(session()->get('message'))

<div class="alert alert-success alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Success!</strong>      
     {{ session()->get('message') }}
</div>

@endif
    <div class="container">
      <div class="row">

        <div style="background-color: #FFF; padding: 20px; width: 100%">
		<form method="POST" action="{{url('profitApply')}}">
				@csrf
				<div class="form-group">
					<label>Default Profit</label>
					<input class="form-control" type="number" name="percentage" value="{{ auth()->user()->profit }}">
				</div>
				<div class="form-group col-md-12 text-center">
				<button type="submit" class="btn btn-primary text-center">Save</button>
				</div>
			</form> 
        </div>
      </div>
    </div>
  </section>
</div>  

@endsection


@section('scripts')
	
@endsection