<?php

namespace App\Http\Controllers\admin;

use Sentinel;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    function base_url()
    {
        $base_url = $_SERVER["DOCUMENT_ROOT"];
        return $base_url;
    }
    public function index()
    {
        $data['list'] = Banner::get();

        //dd($data['list']);  
        $data['page_title'] = 'All Banners';
        return view('admin/banners/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['page_title'] = 'Add Banner';
        $data['categorylist'] = DB::table('tbl_categories')->where('status', '1')->get();
        return view('admin/banners/add', $data);
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
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back();
        }
        $image_name = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = $this->base_url() . '/uploads/banner/';
            $image->move($destinationPath, $image_name);
        }
        $data = array(
            'heading' => $request->heading,
            'type' => $request->type,
            'category_id' => $request->category_id,
            'sub_heading' => $request->sub_heading,
            'image' => @$image_name,
            'created_by' => Sentinel::getUser()->id,
        );

        Banner::create($data);
        Session::flash('success', 'Banner insert Successfully....');
        return Redirect('admin/banners');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        $data['edit_data'] = Banner::find($banner);
        $data['page_title'] = 'Edit Banner';
        return view('admin/banners/view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit($banner)
    {
        $banner = Banner::where('id', $banner)->first();
        $data['banner'] = $banner;
        $data['categorylist'] = DB::table('categories')->where('status', '1')->get();
        $data['page_title'] = 'Edit Banner';
        return view('admin/banners/edit', $data);
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
        $validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            Session::flash('success', 'Please Fill required feild....');
            return redirect()->back();
        }


        $data = array(
            'heading' => $request->heading,
            'type' => $request->type,
            'category_id' => $request->category_id,
            'sub_heading' => $request->sub_heading,
            'updated_by' => Auth::user()->id,
        );

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $data['image'] = $image_name;

            $destinationPath = $this->base_url() . '/uploads/banner/';
            $image->move($destinationPath, $image_name);
        }
        Banner::where('id', $request->id)->update($data);
        Session::flash('success', 'Banner updated Successfully....');
        return Redirect('admin/banners');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy($banner)
    {
        Banner::where('id', $banner)->delete();
        Session::flash('success', 'Banner deleted Successfully....');
        return Redirect('admin/banners');
    }

    public function updateStatus($banner)
    {
        $data = Banner::where('id', $banner)->select('status')->first();
        $status = $data->status == 1 ? 0 : 1;
        Banner::where('id', $banner)->update(['status' => $status]);
        Session::flash('success', 'Banner updated Successfully....');
        return Redirect('admin/banners');
    }
}
