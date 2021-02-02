<option value="">Select Child Category</option>
@foreach($subcat->childs as $child)
<option value="{{ $child->id }}"> @if(Session::get('language')==2) {{ $child->name_ar }} @else {{$child->name_en}} @endif </option>
@endforeach