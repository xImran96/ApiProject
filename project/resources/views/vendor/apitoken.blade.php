@extends('layouts.vendor')
@section('content')


<div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading">API Token</h4>
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
                                                <a href="">API Token</a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>

<section class="col-lg-8 mx-auto mt-2">
    <div class="container">
      <div class="row">

        <div style="background-color: #FFF; padding: 20px; width: 100%">
            <textarea class="form-control" id="token" rows='7'>{{ auth()->user()->token }}</textarea>  
            <br>
            <button class="btn btn-success btn-sm" onclick="copy()">Copy Token</button>      
        </div>
      </div>
    </div>
  </section>
</div>  

@endsection



@section('scripts')


<script type="text/javascript">
    
function copy() {
  let textarea = document.getElementById("token");
  textarea.select();
  document.execCommand("copy");
}

</script>
@endsection