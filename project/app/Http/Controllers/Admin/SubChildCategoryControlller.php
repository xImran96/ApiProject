<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subchildcategory;
use Validator;

class SubChildCategoryControlller extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
         $datas = Subchildcategory::orderBy('id','desc')->get();
      
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('category', function(Subchildcategory $data) {
                                return $data->childcategory->subcategory->category->name_en;
                            })
                            ->editColumn('name', function(Subchildcategory $data) {
                                return $data->name_en;
                            })
                            
                            ->addColumn('subcategory', function(Subchildcategory $data) {
                                return $data->childcategory->subcategory->name_en;
                            })
                            ->addColumn('childcategory', function(Subchildcategory $data) {
                                return $data->childcategory->name_en;
                            })
                            ->addColumn('status', function(Subchildcategory $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-childcat-status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin-childcat-status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>/select></div>';
                            })
                            ->addColumn('attributes', function(Subchildcategory $data) {
                                $buttons = '<div class="action-list"><a data-href="' . route('admin-attr-createForChildcategory', $data->id) . '" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>';
                                if ($data->attributes()->count() > 0) {
                                  $buttons .= '<a href="' . route('admin-attr-manage', $data->id) .'?type=childcategory' . '" class="edit"> <i class="fas fa-edit"></i>Manage</a>';
                                }
                                $buttons .= '</div>';

                                return $buttons;
                            })
                            ->addColumn('action', function(Subchildcategory $data) {
                                return '<div class="action-list"><a data-href="' . route('admin-subchildcat-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-childcat-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })
                            ->rawColumns(['status', 'attributes','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }



    //*** GET Request
    public function index()
    {
        return view('admin.subchildcategory.index');
    }

    //*** GET Request
    public function create()
    {
      	$cats = Category::all();
        return view('admin.subchildcategory.create',compact('cats'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'slug' => 'unique:childcategories|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Subchildcategory();
        $input = $request->all();
         
        $data->fill($input)->save();
        //--- Logic Section Ends
        cache()->forget('categories');
        //--- Redirect Section
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
    	$cats = Category::all();
        $subcats = Subcategory::all();
        $childcats = Childcategory::all();
        $data = Subchildcategory::with('childcategory')->findOrFail($id);
        
        return view('admin.subchildcategory.edit',compact('data','cats','subcats','childcats'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'slug' => 'unique:childcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Subchildcategory::findOrFail($id);
        $input = $request->all();
    
        $data->update($input);
        //--- Logic Section Ends
        cache()->forget('categories');
        //--- Redirect Section
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

      //*** GET Request Status
      public function status($id1,$id2)
        {
            $data = Subchildcategory::findOrFail($id1);
            $data->status = $id2;
            $data->update();
            cache()->forget('categories');
        }

    //*** GET Request
    public function load($id)
    {
        $subcat = Subcategory::findOrFail($id);
        return view('load.childcategory',compact('subcat'));
    }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Subchildcategory::findOrFail($id);

        if($data->attributes->count()>0)
        {
        //--- Redirect Section
        $msg = 'Remove the Attributes first !';
        return response()->json($msg);
        //--- Redirect Section Ends
        }

        if($data->products->count()>0)
        {
        //--- Redirect Section
        $msg = 'Remove the products first !';
        return response()->json($msg);
        //--- Redirect Section Ends
        }

        $data->delete();
        cache()->forget('categories');
        //--- Redirect Section
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
