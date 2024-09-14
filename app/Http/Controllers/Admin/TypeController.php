<?php

namespace App\Http\Controllers\admin;

use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;
use Session;
use Validator;
use DB;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['list']=Type::get();  
        //dd($data['list']);  
        $data['page_title']='Product Types';
        return view('admin/types/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$data['page_title']='Add Type';
    	return view('admin/types/add',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'name'=>"required|unique:types,name", 
        ]); 

        if ($validator->fails()){
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back();
        }  

        $data=array(
            'name'=>$request->name,
        );

        Type::create($data);
        Session::flash('success', 'Type insert Successfully....');
        return Redirect('admin/types');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        $data['edit_data']=Type::find($type);
        $data['page_title']='Edit Type';
        return view('admin/types/view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit($type)
    {
       //dd($type);
       $type=Type::where('id',$type)->first();
       
        $data['type']=$type;
       
        $data['page_title']='Edit Type';
        return view('admin/types/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        $validator = Validator::make($request->all(), [
            'name'=>"required|unique:types,name,{$type->id}", 
        ]); 

        if ($validator->fails()){
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back();
        }        
       
        $data=array(
            'name'=>$request->name,
        );
        Type::where('id',$request->id)->update($data);
        Session::flash('success', 'Type updated Successfully....');
        return Redirect('admin/types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy($type)
    {
        Type::where('id',$type)->delete();
        Session::flash('success', 'Type deleted Successfully....');
        return Redirect('admin/types');
    }

    public function updateStatus($type)
    {
        $data=Type::where('id',$type)->select('status')->first();
        $status=$data->status==1?0:1;
        Type::where('id',$type)->update(['status'=>$status]);
        Session::flash('success', 'Type updated Successfully....');
        return Redirect('admin/types');
    }

    public function findTypes(Request $request){
        $term=$request->term;
        $page=$request->page-1;
        
        $rows['results']= Type::where("name","LIKE","%{$term}%")->orWhere("email","LIKE","%{$term}%")->orWhere("phone","LIKE","%{$term}%")->orWhere("meta_title","LIKE","%{$term}%")->orWhere("slug","LIKE","%{$term}%")->orWhere("meta_keyword","LIKE","%{$term}%")->offset($page)->limit($request->limit)->select(DB::raw("id,name as text"))->where("status",1)->get();
    
        $count= count(Type::where("name","LIKE","%{$term}%")->orWhere("email","LIKE","%{$term}%")->orWhere("phone","LIKE","%{$term}%")->orWhere("meta_title","LIKE","%{$term}%")->orWhere("slug","LIKE","%{$term}%")->orWhere("meta_keyword","LIKE","%{$term}%")->where("status",1)->get());
        $rows['total_count']=$count;
        $rows['incomplete_results'] =$count>0?true:false;
        return response()->json($rows);
    }
}
