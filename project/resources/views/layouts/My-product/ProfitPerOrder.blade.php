@extends('layouts.vendor') 

@section('content')  
                    <div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading">{{ $langg->lang443 }}</h4>
                                        <ul class="links">
                                            <li>
                                                <a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">{{ $langg->lang442 }}</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('vendor-order-index') }}">{{ $langg->lang443 }}</a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>
                        <div class="product-area">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mr-table allproduct">
                                        @include('includes.form-success') 

                                        <div class="table-responsiv">
                                        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                                                <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                                    <thead>
                                                       
                                                        <tr>
                                                            <th>Product Name</th>
                                                            <th>Slug</th>
                                                            <th>Hareer Price</th>
                                                            <th>Profit Percentage</th>
                                                            <th>New Price</th>
                                                            
                                                          
                                                        </tr>
                                                       
                                                    </thead>


                                              <tbody>
                                                @foreach($dealerOrders as $dealerOrder)
                                                <tr>
                                                    <td>{{$dealerOrder->importProduct->name_en}}</td>
                                                    <td>{{$dealerOrder->importProduct->slug}}</td>
                                                    <td>{{$dealerOrder->importProduct->price}}</td>
                                                    <td>{{$dealerOrder->importProduct->profit_percentage}}</td>
                                                    <td>{{$dealerOrder->importProduct->new_price}}</td>
                                                </tr>
                                                @endforeach
                                                  
                                                  </tbody>
                                                    
                                                </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

{{-- ORDER MODAL --}}

<div class="modal fade" id="confirm-delete2" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="submit-loader">
        <img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
    </div>
    <div class="modal-header d-block text-center">
        <h4 class="modal-title d-inline-block">{{ $langg->lang544 }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

      <!-- Modal body -->
      <div class="modal-body">
        <p class="text-center">{{ $langg->lang545 }}</p>
        <p class="text-center">{{ $langg->lang546 }}</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ $langg->lang547 }}</button>
            <a class="btn btn-success btn-ok order-btn">{{ $langg->lang548 }}</a>
      </div>

    </div>
  </div>
</div>

{{-- ORDER MODAL ENDS --}}


@endsection    

@section('scripts')

{{-- DATA TABLE --}}

    <script type="text/javascript">


$('.vendor-btn').on('change',function(){
          $('#confirm-delete2').modal('show');
          $('#confirm-delete2').find('.btn-ok').attr('href', $(this).val());

});

        var table = $('#geniustable').DataTable({
               ordering: false
           });
                                                                
    </script>

{{-- DATA TABLE --}}
    
@endsection   