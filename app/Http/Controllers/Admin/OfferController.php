<?php

namespace App\Http\Controllers\admin;

use Sentinel;
use App\Models\Banner;
use App\Models\OfferBanner;
use App\Models\OfferProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    function base_url()
    {
        $base_url = $_SERVER["DOCUMENT_ROOT"];
        return $base_url;
    }
    public function index()
    {
        $data['list'] = OfferProduct::select('offer_products.*', 'services.service_name')
            ->leftJoin('services', 'offer_products.product_id', '=', 'services.id')
            ->get();
        //dd($data['list']);  
        $data['page_title'] = 'All Offer Products';
        return view('admin.offers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['list'] = DB::table('services')->get();
        //print_r($data['list']); die;
        $data['page_title'] = 'Add Offer Products';
        return view('admin.offers.add', $data);
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
            'title' => 'required',
            'value' => 'required',
            'product' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $model = new OfferProduct();
        $model->title = $request->title;
        $model->value = $request->value;
        $model->type = $request->type;
        $model->status = 1;
        $model->product_id = $request->product;
        $model->description = $request->description;
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');
        $model->save();

        Session::flash('success', 'Offer Product insert Successfully....');
        return redirect()->route('admin.offers');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        $data['edit_data'] = OfferProduct::find($banner);
        $data['page_title'] = 'Edit Offer Product';
        return view('admin/offers/view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit($banner)
    {
        $banner = OfferProduct::where('id', $banner)->first();
        $data['list'] = DB::table('services')->get();
        $data['edit'] = $banner;
        $data['page_title'] = 'Edit Offer Products';
        return view('admin/offers/edit', $data);
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
            'title' => 'required',
            'value' => 'required',
            'product' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $model = OfferProduct::findById($request->id);
        $model->title = $request->title;
        $model->value = $request->value;
        $model->type = $request->type;
        $model->status = 1;
        $model->product_id = $request->product;
        $model->description = $request->description;
        $model->updated_at = date('Y-m-d H:i:s');
        $model->save();

        Session::flash('success', 'Offers updated Successfully....');
        return redirect()->route('admin.offers.edit', $request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy($banner)
    {
        OfferProduct::where('id', $banner)->delete();
        Session::flash('success', 'Offers deleted Successfully....');
        return redirect()->route('admin.offers');
    }

    public function updateStatus($banner)
    {
        $data = OfferProduct::where('id', $banner)->select('status')->first();
        $status = $data->status == 1 ? 0 : 1;
        OfferProduct::where('id', $banner)->update(['status' => $status]);
        Session::flash('success', 'Offers updated Successfully....');
        return redirect()->route('admin.offers');
    }
}
