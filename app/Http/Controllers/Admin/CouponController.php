<?php

namespace App\Http\Controllers\admin;

use DB;
use Sentinel;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index()
    {
        $data['list'] = Coupon::get();
        //dd($data['list']);  
        $data['page_title'] = 'All Coupons';
        return view('admin.coupons.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['page_title'] = 'Add Coupon';
        return view('admin.coupons.add', $data);
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
            'coupon_code' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', 'Please Fill required feild....');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $model = new Coupon();
        $model->title = $request->title;
        $model->coupon_code = $request->coupon_code;
        $model->value = $request->value;
        $model->type = $request->type;
        $model->status = 1;
        $model->per_user = $request->per_user;
        $model->short_descriptoin = $request->short_descriptoin;
        $model->description = $request->long_description;
        $model->expiry_date = $request->expiry_date;
        $model->created_by = Auth::user()->id;
        $model->updated_at = date('Y-m-d H:i:s');
        $model->save();

        Session::flash('success', 'Coupons insert Successfully....');
        return redirect()->route('admin.coupons');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $Coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $Coupon)
    {
        $data['edit_data'] = Coupon::find($Coupon);
        $data['page_title'] = 'Edit Coupons';
        return view('admin/Coupons/view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $Coupon
     * @return \Illuminate\Http\Response
     */
    public function edit($Coupon)
    {
        $Coupon = Coupon::where('id', $Coupon)->first();
        $data['Coupon'] = $Coupon;
        $data['page_title'] = 'Edit Coupon';
        return view('admin/coupons/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $Coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back();
        }


        $data = array(
            'coupon_code' => $request->coupon_code,
            'value' => $request->value,
            'type' => @$request->type,
            'per_user' => @$request->per_user,
            'expiry_date' => @$request->expiry_date,
            'title' => @$request->title,
            'short_descriptoin' => @$request->short_descriptoin,
            'description' => @$request->long_description,

        );


        Coupon::where('id', $request->id)->update($data);
        Session::flash('success', 'Coupon updated Successfully....');
        return Redirect('admin/coupons');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $Coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy($Coupon)
    {
        Coupon::where('id', $Coupon)->delete();
        Session::flash('success', 'Coupon deleted Successfully....');
        return Redirect('admin/coupons');
    }

    public function updateStatus($Coupon)
    {
        $data = Coupon::where('id', $Coupon)->select('status')->first();
        $status = $data->status == 1 ? 0 : 1;
        Coupon::where('id', $Coupon)->update(['status' => $status]);
        Session::flash('success', 'Coupon updated Successfully....');
        return Redirect('admin/coupons');
    }
}
