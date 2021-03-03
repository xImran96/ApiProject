@extends('layouts.vendor')
@section('styles')
<style>
	.box{
		height: 70vh;
        box-shadow:20px 20px 50px 15px grey;  
        border-top:5px solid #3498db;
        margin-top: 20px;
		/*background: blue;*/

	}
	.mylist{
		display: flex;
		justify-content: space-around;
		list-style: none;
		padding-top: 10px;
	}
	.content{
		text-align: center;
		padding-top: 10px;
	}
	.content h3{
       font-weight: bold;
	}
	.content p{
		font-weight: bold;
	}
	.myBtn{
		border: none;
		outline: none;
		background: #3498db;
		padding: 10px;
		width: 100px;
		border-radius: 30px;
		color: white;
		transition: 300ms;
		font-weight: bold;
		font-size: 16px;
		margin-left: 50px;
		margin-top: 20px;
	}
	.myBtn:hover{
		background: #f1c40f;
		cursor: pointer;
	}
</style>
@endsection
 
@section('content')
<section class="container">
	<div class="content-area">
		<div class="mr-breadcrumb">
			<div class="row">
				<div class="col-lg-12">
						<h4 class="heading">Accounts</h4>
						<ul class="links">
							<li>
								<a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
							</li>
							
							<li>
								<a href="{{ route('vendor-prod-index') }}">Accounts</a>
							</li>
							<li>
								<a href="{{ route('vendor-prod-index') }}">Profit</a>
							</li>
						</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="box">
		<div class="content">
			<h3>Set your profit</h3>
			<p>You can change anytime and will update daily in your store</p>
			@if(session('message'))
			<p class="alert alert-success alert-dismissible fade show"  role="alert">{{session('message')}}
		   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
			</p>
			
			@endif
			<form method="POST" action="{{url('profitApply')}}">
				@csrf
			<ul class="mylist">
				<li>Product cost</li>
				<li>x</li>
				<li><input type="number" name="percentage"></li>
				<li>=</li>
				<li>Product price in your website</li>
			</ul>
			<button type="submit" class="myBtn">Save</button>
			</form>
		</div>
	</div>
</section>
@endsection

@section('scripts')
	
@endsection