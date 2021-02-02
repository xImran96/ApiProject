@if(Auth::guard('admin')->check())

<option data-href="" value="">Select Sub Category</option>
@foreach($cat->subs as $sub)
<option data-href="{{ route('admin-childcat-load',$sub->id) }}" value="{{ $sub->id }}">   @if(Session::get('language')==2) {{ $sub->name_ar }} @else {{$sub->name_en}} @endif</option>
@endforeach

@else 

<option data-href="" value="">Select Sub Category</option>
@foreach($cat->subs as $sub)
<option data-href="{{ route('vendor-childcat-load',$sub->id) }}" value="{{ $sub->id }}">   @if(Session::get('language')==2) {{ $sub->name_ar }} @else {{$sub->name_en}} @endif</option>
@endforeach
@endif