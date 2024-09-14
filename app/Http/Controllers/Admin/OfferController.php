<?php

namespace App\Http\Controllers\admin;

use App\Models\Offer_product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;
use Session;
use Validator;
use DB;

class OfferController extends Controller
{
    function base_url(){
        $base_url= $_SERVER["DOCUMENT_ROOT"]; 
        return $base_url;
    }
    public function index()
    {
        $data['list']=Offer_product::select('offer_products.*','tbl_services.service_name')
        ->leftJoin('tbl_services', 'offer_products.product_id', '=', 'tbl_services.id')
        ->get();  
        //dd($data['list']);  
        $data['page_title']='All Offer Products';
        return view('admin/offers/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['list']=DB::table('tbl_services')->get();
        //print_r($data['list']); die;
    	$data['page_title']='Add Offer Products';
    	return view('admin/offers/add',$data);
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
            'title'=>'required',
            'value'=>'required',
            'product'=>'required',
            'description'=>'required',
        ]); 

        if ($validator->fails()){
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back();
        } 
       
        $data=array(
            'title'=>$request->title,
            'value'=>$request->value,
            'type'=>$request->type,
            'status'=>'1',
            'product_id'=>$request->product,
            'description'=>$request->description,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        );
    //print_r($data); die;
        Offer_product::create($data);
        Session::flash('success', 'Offer Product insert Successfully....');
        return Redirect('admin/offers');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        $data['edit_data']=Offer_product::find($banner);
        $data['page_title']='Edit Offer Product';
        return view('admin/offers/view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit($banner)
    {
         $banner=Offer_product::where('id',$banner)->first();
          $data['list']=DB::table('tbl_services')->get();
        $data['edit']=$banner;
        $data['page_title']='Edit Offer Products';
        return view('admin/offers/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'value'=>'required',
            'product'=>'required',
            'description'=>'required',
        ]); 

        if ($validator->fails()){
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back();
        } 
       
        $data=array(
            'title'=>$request->title,
            'value'=>$request->value,
            'type'=>$request->type,
            'status'=>'1',
            'product_id'=>$request->product,
            'description'=>$request->description,
            'updated_at'=>date('Y-m-d H:i:s'),
        );
        
        Offer_product::where('id',$request->id)->update($data);
        Session::flash('success', 'Offers updated Successfully....');
        return Redirect('admin/offers/edit/'.$request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy($banner)
    {
        Offer_product::where('id',$banner)->delete();
        Session::flash('success', 'Offers deleted Successfully....');
        return Redirect('admin/offers');
    }

    public function updateStatus($banner)
    {
        $data=Offer_product::where('id',$banner)->select('status')->first();
        $status=$data->status==1?0:1;
        Offer_product::where('id',$banner)->update(['status'=>$status]);
        Session::flash('success', 'Offers updated Successfully....');
        return Redirect('admin/offers');
    }

}
