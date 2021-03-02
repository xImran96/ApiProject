@extends('layouts.vendor') 
@section('styles')  
<style>
	/*#import{
		color: orange;
	}
	#import:hover{
		cursor: pointer;
	}*/
	.importBtn{
		border: none;
		outline: none;
		color: orange;
		font-size: 20px;
	}
	.importBtn:hover{
        cursor: pointer;
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
		
	}
	.myBtn:hover{
		background: #f1c40f;
		cursor: pointer;
	}
</style>
@endsection
@section('content')  
  @php
 
   $products = App\Models\Product::paginate(10);
 

  $categories = App\Models\Category::all();

  @endphp

					<input type="hidden" id="headerdata" value="PRODUCT">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">{{ $langg->lang444 }}</h4>
										<ul class="links">
											<li>
												<a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
											</li>
											<li>
												<a href="javascript:;">{{ $langg->lang444 }} </a>
											</li>
											<li>
												<a href="{{ route('vendor-prod-index') }}">{{ $langg->lang446 }}</a>
											</li>
										</ul>
								</div>
							</div>
						</div>

						<div class="product-area">
							<div class="row">
								<div class="col-lg-12">
									<h3 class="text-center pt-5 pb-5">SELECT CATEGORY</h3>
								</div>
							</div>

							<form action="{{url('/searchproduct')}}"  method="get">
                            <div class="row">
                         <!--   	<h4>Select Category</h4> -->
                            	<div class="col-lg-3">
                            		
                            		<label>Categories</label>
                            		<select onchange="myftn()" id="category" name="category">
                            			@foreach($categories as $category)
                            			<option value="{{$category->id}}">{{$category->name_en}}</option>
                            		    @endforeach
                            		</select>
                            	</div>
                            	<div class="col-lg-3">
                            		<label>Sub Categories</label>
                          <select id="subcategory" onchange="myftn2()" name="subcategory"></select>
                            	</div>
                            	<div class="col-lg-3">
                            		<label>Child Categories</label>
                           		<select id="childcategory" onchange="myftn3()" name="childcategory"></select>
                            	</div>
                            	<div class="col-lg-3">
                            		<label>Sub Child Categories</label>
                            <select id="subchildcategory" name="subchildcategory"></select>
                            	</div>
                            </div>
                            <div class="row pt-5 pb-5">
								<div class="col-lg-12 text-center">
									<button type="submit"  class="myBtn">Search</button>
									
								</div>

							</div>
							</form>
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">
						

                        @include('includes.vendor.form-success')  

										<div class="table-responsiv">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Image</th>
									                        <th>{{ $langg->lang608 }}</th>
									                        <th>{{ $langg->lang609 }}</th>
									                        <th>Stock</th>
									                        <th>{{ $langg->lang610 }}</th>
									                        <th>{{ $langg->lang611 }}</th>
									                        <th>{{ $langg->lang612 }}</th>
														</tr>
													</thead>
													<tbody>
														@if(!empty($categoriesWiseProducts))
														@foreach($categoriesWiseProducts as $product)
														<tr>
															<td><img src="{{$product->photo}}"></td>
															<td>{{$product->name_en}}</td>
															<td>{{$product->type}}</td>
															<td>{{$product->stock}}</td>
															<td>{{$product->price}}</td>
															<td>{{$product->status}}</td>
															<td id="import">
																
													<!-- 			<i class="fas fa-cloud-upload-alt">
											<input type="hidden" value="{{$product->id}}">
																</i> -->
												<form action="{{url('/import_Product')}}" method="POST">
													@csrf
					<input type="hidden" name="product_id" value="{{$product->id}}">
													<button type="submit" class="importBtn">
																	<i class="fas fa-cloud-upload-alt">
																</i>
																</button>
																</form>
																
																
															</td>
														</tr>
														@endforeach
														@else
														@foreach($products as $product)
														<tr>
															<td><img src="{{$product->photo}}"></td>
															<td>{{$product->name_en}}</td>
															<td>{{$product->type}}</td>
															<td>{{$product->stock}}</td>
															<td>{{$product->price}}</td>
															<td>{{$product->status}}</td>
															<td id="import">
																
													<!-- 			<i class="fas fa-cloud-upload-alt">
											<input type="hidden" value="{{$product->id}}">
																</i> -->
												<form action="{{url('/import_Product')}}" method="POST">
													@csrf
					<input type="hidden" name="product_id" value="{{$product->id}}">
													<button type="submit" class="importBtn">
																	<i class="fas fa-cloud-upload-alt">
																</i>
																</button>
																</form>
																
																
															</td>
														</tr>
														@endforeach
														@endif
														
													</tbody>
												</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								{{ $products->links() }}
							</div>
						</div>
					</div>


{{-- HIGHLIGHT MODAL --}}
										<div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modal2" aria-hidden="true">
										
										
										<div class="modal-dialog highlight" role="document">
										<div class="modal-content">
												<div class="submit-loader">
														<img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
												</div>
											<div class="modal-header">
											<h5 class="modal-title"></h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											</div>
											<div class="modal-body">

											</div>
											<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $langg->lang613 }}</button>
											</div>
										</div>
										</div>
</div>

{{-- HIGHLIGHT ENDS --}}

{{-- DELETE MODAL --}}

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

	<div class="modal-header d-block text-center">
		<h4 class="modal-title d-inline-block">{{ $langg->lang614 }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
	</div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-center">{{ $langg->lang615 }}</p>
            <p class="text-center">{{ $langg->lang616 }}</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ $langg->lang617 }}</button>
            <a class="btn btn-danger btn-ok">{{ $langg->lang618 }}</a>
      </div>

    </div>
  </div>
</div>

{{-- DELETE MODAL ENDS --}}

{{-- GALLERY MODAL --}}

		<div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalCenterTitle">{{ $langg->lang619 }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="top-area">
						<div class="row">
							<div class="col-sm-6 text-right">
								<div class="upload-img-btn">
									<form  method="POST" enctype="multipart/form-data" id="form-gallery">
										{{ csrf_field() }}
									<input type="hidden" id="pid" name="product_id" value="">
									<input type="file" name="gallery[]" class="hidden" id="uploadgallery" accept="image/*" multiple>
											<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i>{{ $langg->lang620 }}</label>
									</form>
								</div>
							</div>
							<div class="col-sm-6">
								<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i> {{ $langg->lang621 }}</a>
							</div>
							<div class="col-sm-12 text-center">( <small>{{ $langg->lang622 }}</small> )</div>
						</div>
					</div>
					<div class="gallery-images">
						<div class="selected-image">
							<div class="row">


							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>

{{-- GALLERY MODAL ENDS --}}

@endsection    

@section('scripts')
<!-- my script -->
<script>
	function myftn(){
		
		let id = document.querySelector('#category').value;
		
		
		$.ajax({
               type:'GET',
                url:'{{ url("/subcategory") }}',
                 data:{
                 id :  id
               },
               success:function(response) {     
               console.log(response);   
                $("#subcategory").empty();     
               $.each(response,function(key,value){

          $("#subcategory").append('<option value="'+value.id+'">'+value.name_en+'</option>');
        });
            },
            error:function(error){
           console.log(error);
            }
              });
	}
	function myftn2(){
		
		let id = document.querySelector('#subcategory').value;
		

		$.ajax({
               type:'GET',
                url:'{{ url("/childcategory") }}',
                 data:{
                 id :  id
               },
               success:function(response) {     
               console.log(response);   
                $("#childcategory").empty();     
               $.each(response,function(key,value){

          $("#childcategory").append('<option value="'+value.id+'">'+value.name_en+'</option>');
        });
            },
            error:function(error){
           console.log(error);
            }
              });
	}
	function myftn3(){
		
		let id = document.querySelector('#childcategory').value;
		
		
		$.ajax({
               type:'GET',
                url:'{{ url("/subchildcategory") }}',
                 data:{
                 id :  id
               },
               success:function(response) {     
               console.log(response);   
                $("#subchildcategory").empty();     
               $.each(response,function(key,value){

          $("#subchildcategory").append('<option value="'+value.id+'">'+value.name_en+'</option>');
        });
            },
            error:function(error){
           console.log(error);
            }
              });
	}
	
	// realted to jquery
	// let imprt = document.querySelectorAll('#import');
	
	// const importProdcut = (event)=>{
   
 //   let productId = event.target.querySelector('input').value;

   // end jquery


   // console.log(productId);
   // try{
   // 	const response = await fetch('http://localhost:8081/hareer/ApiProject/import_Product',productId).then(response =>{
   // 		console.log(response);
   // 	});
    
  
   // }catch(error){
   // console.log(error);
   // }
  

  // realted to jquery
 //   $.ajax({
 //               type:'GET',
 //               url:'{{ url("import_Product") }}',
 //               data:{
 //                product_id :  productId
 //               },
 //               success:function(response) {             
 //                  console.log(response);       
 //            },
 //            error:function(error){
 //           console.log(error);
 //            }
 //              });
	// }

	// //end jquery

	// imprt.forEach((el,index,imprt)=>{
 //    el.addEventListener('click',importProdcut);
	// });

	
	
</script>
<!-- my script  end-->
<!-- {{-- DATA TABLE --}} -->

  <!--   <script type="text/javascript">

		var table = $('#geniustable').DataTable({
			   ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('vendor-prod-datatables') }}',
               columns: [
                        { data: 'name', name: 'name' },
                        { data: 'type', name: 'type' },
                        { data: 'price', name: 'price' },
                        { data: 'status', searchable: false, orderable: false},
            			{ data: 'action', searchable: false, orderable: false }

                     ],
                language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                },
				drawCallback : function( settings ) {
	    				$('.select').niceSelect();	
				}
            });

      	$(function() {
        $(".btn-area").append('<div class="col-sm-4 table-contents">'+
        	'<a class="add-btn" href="{{route('vendor-prod-types')}}">'+
          '<i class="fas fa-plus"></i> <span class="remove-mobile">{{ $langg->lang623 }}<span>'+
          '</a>'+
          '</div>');
      });	//										
									


{{-- DATA TABLE ENDS--}}

</script> -->

<script type="text/javascript">
	
// Gallery Section Update

    $(document).on("click", ".set-gallery" , function(){
        var pid = $(this).find('input[type=hidden]').val();
        $('#pid').val(pid);
        $('.selected-image .row').html('');
            $.ajax({
                    type: "GET",
                    url:"{{ route('vendor-gallery-show') }}",
                    data:{id:pid},
                    success:function(data){
                      if(data[0] == 0)
                      {
	                    $('.selected-image .row').addClass('justify-content-center');
	      				$('.selected-image .row').html('<h3>{{ $langg->lang624 }}</h3>');
     				  }
                      else {
	                    $('.selected-image .row').removeClass('justify-content-center');
	      				$('.selected-image .row h3').remove();      
                          var arr = $.map(data[1], function(el) {
                          return el });

                          for(var k in arr)
                          {
        				$('.selected-image .row').append('<div class="col-sm-6">'+
                                        '<div class="img gallery-img">'+
                                            '<span class="remove-img"><i class="fas fa-times"></i>'+
                                            '<input type="hidden" value="'+arr[k]['id']+'">'+
                                            '</span>'+
                                            '<a href="'+'{{asset('assets/images/galleries').'/'}}'+arr[k]['photo']+'" target="_blank">'+
                                            '<img src="'+'{{asset('assets/images/galleries').'/'}}'+arr[k]['photo']+'" alt="gallery image">'+
                                            '</a>'+
                                        '</div>'+
                                  	'</div>');
                          }                         
                       }
 
                    }
                  });
      });

  $(document).on('click', '.remove-img' ,function() {
    var id = $(this).find('input[type=hidden]').val();
    $(this).parent().parent().remove();
	    $.ajax({
	        type: "GET",
	        url:"{{ route('vendor-gallery-delete') }}",
	        data:{id:id}
	    });
  });

  $(document).on('click', '#prod_gallery' ,function() {
    $('#uploadgallery').click();
  });
                                        
                                
  $("#uploadgallery").change(function(){
    $("#form-gallery").submit();  
  });

  $(document).on('submit', '#form-gallery' ,function() {
		  $.ajax({
		   url:"{{ route('vendor-gallery-store') }}",
		   method:"POST",
		   data:new FormData(this),
		   dataType:'JSON',
		   contentType: false,
		   cache: false,
		   processData: false,
		   success:function(data)
		   {
		    if(data != 0)
		    {
	                    $('.selected-image .row').removeClass('justify-content-center');
	      				$('.selected-image .row h3').remove();   
		        var arr = $.map(data, function(el) {
		        return el });
		        for(var k in arr)
		           {
        				$('.selected-image .row').append('<div class="col-sm-6">'+
                                        '<div class="img gallery-img">'+
                                            '<span class="remove-img"><i class="fas fa-times"></i>'+
                                            '<input type="hidden" value="'+arr[k]['id']+'">'+
                                            '</span>'+
                                            '<a href="'+'{{asset('assets/images/galleries').'/'}}'+arr[k]['photo']+'" target="_blank">'+
                                            '<img src="'+'{{asset('assets/images/galleries').'/'}}'+arr[k]['photo']+'" alt="gallery image">'+
                                            '</a>'+
                                        '</div>'+
                                  	'</div>');
		            }          
		    }
		                     
		                       }

		  });
		  return false;
 }); 

// Gallery Section Update Ends	

</script>

@endsection   