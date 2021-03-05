<option value="">Select Child Category</option>
@foreach($subcat->childs as $child)
<<<<<<< HEAD
<option value="{{ $child->id }}"> @if(Session::get('language')==2) {{ $child->name_ar }} @else {{$child->name_en}} @endif </option>
=======
<option value="{{ $child->id }}"> @if(Session::get('language')==2) {{ $child->name_en }} @else {{$child->name_en}} @endif </option>
>>>>>>> b97def46f19189690be84a036e8aa6c8f17e4aa6
@endforeach