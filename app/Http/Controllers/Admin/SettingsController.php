<?php

namespace App\Http\Controllers\admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Sentinel;
use DB;
use Session;

class SettingsController extends Controller
{
   
   //area

    public function area_index(){
       
        $data['area'] = DB::table('tbl_area')->get();
        $data['page_title'] = "Area List";
        return view('admin/area/index',$data);
        
    }

    public function area_create(){
       
       $data['city'] = DB::table('tbl_custom_city')->get();
       $data['edit_item'] = '';
       $data['area_id'] = 0;
       $data['page_title'] = "Create Area";
       return view('admin/area/create', $data);    }

    public function area_store(Request $request){
       
        $this->validate($request,[
             'area'=>'required'
          ]);

        $data = [ "area" => $request->area, "city_id" => $request->city ];
        DB::table('tbl_area')->insert($data);
        Session::flash('success', 'Area carete Successfully....');
        return redirect()->back();       
    }

    public function area_edit($id){
       
       $data['city'] = DB::table('tbl_custom_city')->get();
       $data['edit_item'] = DB::table('tbl_area')->where('id',$id)->first();
       $data['area_id'] = $id;
       $data['page_title'] = "Edit Area";
       return view('admin/area/create', $data);
    }

    public function area_update(Request $request){

        $data = [ "area" => $request->area, "city_id" => $request->city ];
        DB::table('tbl_area')->where('id', $request->area_id)->update($data);
        Session::flash('success', 'Area update Successfully....');
        return redirect()->back();       
    }

    public function area_delete($id){
       
        DB::table('tbl_area')->where('id',$id)->delete();
        Session::flash('success', 'Area Delete Successfully....');
        return redirect()->back();
       
    }
   
   public function time_index(){
       
       $data['timeSchedule'] = DB::table('time_schedule')->get();
       return view('admin/time/index',$data);
       
   }
   
   public function add_time_schedule(){
     
       
       return view('admin/time/add');
   }
   
   public function add_time_store(Request $request){
       
        DB::table('time_schedule')->insert([
           
           "start_time"=>$request->start_time,
           "end_time"=>$request->end_time
           
           ]);
           
        Session::flash('success', 'carete Successfully....');
        return redirect()->back();
       
   }
   
   public function add_time_edit($id){
       
       $data['edit'] = DB::table('time_schedule')->where('id',$id)->first();
       return view('admin/time/edit',$data);
   }
   
   public function add_time_delete($id){
       
       DB::table('time_schedule')->where('id',$id)->delete();
        Session::flash('success', 'delete Successfully....');
        return redirect()->back();
   }
   
   
   public function add_time_update(Request $request){
       
        DB::table('time_schedule')->where('id',$request->update_id)->update([
           
           "start_time"=>$request->start_time,
           "end_time"=>$request->end_time
           
           ]);
           
        Session::flash('success', 'update Successfully....');
        return redirect()->back();
       
   }
   
   
    public function tax_index(){
      
      $data['taxs'] = DB::table('tax_pay')->get();
      return view('admin/tax/index', $data);
    } 
    
    public function tax_create(){
       
       return view('admin/tax/add'); 
    }
    
    public function tax_store(Request $request){
        
        //dd($request->all()); 
        
        DB::table('tax_pay')->insert([
            "title" => $request->title, 
            "type" => $request->type,
            "value" => $request->value,
            "status" => $request->status
            ]);
            
        Session::flash('success', 'carete Successfully....');
        return redirect()->back();
    
    }
    
    public function tax_sattus($id){
       
      $tax_pay = DB::table('tax_pay')->where('id', $id)->first();
      
      if(!empty($tax_pay)){
          
          if($tax_pay->status==1){
              DB::table('tax_pay')->where('id', $id)->update(['status'=>0]);
              Session::flash('success', 'deactive successfully');
          }else{
              Session::flash('success', 'active successfully');
              DB::table('tax_pay')->where('id', $id)->update(['status'=>1]);
          }
          
          return redirect()->back();
          
      }else{
        Session::flash('error', 'you are not authorize..');
        return redirect()->back();
      }
    }
    
    public function tax_edit($id){
     
        $data['tax'] = DB::table('tax_pay')->where('id', $id)->first();
        return view('admin/tax/edit',$data);
    }
    
    public function tax_update(Request $request){
        
        DB::table('tax_pay')->where('id',$request->update_id)->update([
            "title" => $request->title,
            "type" => $request->type,
            "value" => $request->value,
            "status" => $request->status
            ]);
            
        Session::flash('success', 'updated Successfully....');
        return redirect()->back();
    }
    
    
   
   public function pincode_active($id){
       
       $active = DB::table('tbl_pincode')->where('id', $id)->first();
       
       if($active->status > 0){
           Session::flash('success', 'Pincode Inactive Successfully....');
           DB::table('tbl_pincode')->where('id', $id)->update(['status'=>0]);
           
       }else{
            DB::table('tbl_pincode')->where('id', $id)->update(['status'=>1]);
            Session::flash('success', 'Pincode active Successfully....');  
       }
       
        return redirect()->back();
   }
   
   public function pincode_index(){
       
        // DB::table('tbl_custom_city')->where('id', $item->city_id)->first();
        $data['pincode_item']= DB::table('tbl_pincode')->get();
       $data['page_title'] = "Pincode List";
       return view('admin/pincode/index', $data);
   }
   
   
   public function pincode_create(){
       
       $data['city'] = DB::table('tbl_custom_city')->get();
       $data['edit_item'] = '';
       $data['pincode_id'] = 0;
       $data['page_title'] = "create Pincode";
       return view('admin/pincode/create', $data);
       
   }
   
    public function pincode_edit($id){
       
       $data['city'] = DB::table('tbl_custom_city')->get();
       $data['edit_item'] = DB::table('tbl_pincode')->where('id',$id)->first();
       $data['pincode_id'] = $id;
       $data['page_title'] = "Edit Pincode";
       return view('admin/pincode/create', $data);
       
   }
   
   public function pincode_store(Request $request){
       
        $this->validate($request,[
             'pincode'=>'required|numeric|min:6|unique:tbl_pincode'
          ]);

        $data = [ "pincode" => $request->pincode, "city_id" => $request->city ];
        DB::table('tbl_pincode')->insert($data);
        Session::flash('success', 'Pincode carete Successfully....');
        return redirect()->back();
       
   }
   
   public function pincode_update(Request $request){
       
        // $this->validate($request,[
        //      'pincode'=>'required|numeric|min:6|unique:tbl_pincode'
        //   ]);

        $data = [ "pincode" => $request->pincode, "city_id" => $request->city ];
        DB::table('tbl_pincode')->where('id', $request->pincode_id)->update($data);
        Session::flash('success', 'Pincode update Successfully....');
        return redirect()->back();
       
   }
   
   public function princode_delete($id){
       
        DB::table('tbl_pincode')->where('id',$id)->delete();
        Session::flash('success', 'Pincode Delete Successfully....');
        return redirect()->back();
       
   }
   
   
   //city
   
   public function city_active($id){
       
       $active = DB::table('tbl_custom_city')->where('id', $id)->first();
       
       if($active->status > 0){
           Session::flash('success', 'City Inactive Successfully....');
           DB::table('tbl_custom_city')->where('id', $id)->update(['status'=>0]);
           
       }else{
            DB::table('tbl_custom_city')->where('id', $id)->update(['status'=>1]);
            Session::flash('success', 'City active Successfully....');  
       }
       
        return redirect()->back();
   }
   
   public function city_index(){
       
       $data['city_item'] = DB::table('tbl_custom_city')->get();
       $data['page_title'] = "City List";
       return view('admin/city/index', $data);
   }
   
   
   public function city_create(){
       
       $data['edit_item'] = '';
       $data['city_id'] = 0;
       $data['page_title'] = "Create City";
       return view('admin/city/create', $data);
       
   }
   
    public function city_edit($id){
      
       $data['edit_item'] = DB::table('tbl_custom_city')->where('id',$id)->first();
       $data['city_id'] = $id;
       $data['page_title'] = "Edit City";
       return view('admin/city/create', $data);
       
   }
   
   public function city_store(Request $request){
       
        $this->validate($request,[
             'city'=>'required|unique:tbl_custom_city'
          ]);

        $data = [ "city" => $request->city];
        DB::table('tbl_custom_city')->insert($data);
        Session::flash('success', 'City carete Successfully....');
        return redirect()->back();
       
   }
   
   public function citye_update(Request $request){
       
        $this->validate($request,[
             'city'=>'required|unique:tbl_custom_city'
          ]);

        $data = [ "city" => $request->city];
        
        DB::table('tbl_custom_city')->where('id',$request->city_id)->update($data);
        Session::flash('success', 'City update Successfully....');
        return redirect()->back();
       
   }
   
    public function city_delete($id){
       
        DB::table('tbl_custom_city')->where('id',$id)->delete();
        Session::flash('success', 'City Delete Successfully....');
        return redirect()->back();
       
   }
   
   
    public function index()
    {
       
        $state=array(''=>"Select country");
        $city=array(''=>"Select state");
        $countries=array(''=>"Select country");
        $country=Country::all();
        foreach ($country as $cun) {
            $countries[$cun->id]=$cun->name;
        }
        $data['cities']=$city;
        $data['states']=$state;
        $data['countries']=$countries;
        $data['types']=DB::table('types')->get();
        $data['category']=DB::table('tbl_categories')->where('parent_id',0)->get();
        
        //dd($data);
        
        $data['page_title']='Settigns';        
        return view('admin/settings/index',$data);
    }

   
    public function create()
    {
        //
    }

 
      function base_url(){
        $base_url= $_SERVER["DOCUMENT_ROOT"]; 
        return $base_url;
    }
    
    public function store(Request $request)
    {
        $type_data=json_encode($request->type_data);
        $cat_data=json_encode($request->cat_data);
        //print_r($cat_data); die;
        
       $data= array('type'=>$request->settings_for,'category_id'=>$cat_data,'type_id'=>$type_data,'name'=>$request->settings_for,'slogon'=>$request->slogan,'commission'=>$request->commission,'gift_wrap'=>$request->gift_wrap,'value'=>$request[$request->settings_for]);
       $settings_data= Setting::where(['type'=>$request->settings_for,'name'=>$request->settings_for])->select('value')->first();
       
       $settings=(object)array();
       if (!empty($settings_data)) {
            $settings=json_decode($settings_data->value);
       }
       if ($request->hasFile('header_logo')) {
            $image = $request->file('header_logo');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = $this->base_url().'/uploads/logo/';
            $image->move($destinationPath, $image_name);
            $data['value']['header_logo']='/uploads/logo/'.$image_name;
        
        }else{
            $data['value']['header_logo']=$settings->header_logo?$settings->header_logo:NULL;
        }
        if ($request->hasFile('footer_logo')) {
            $image = $request->file('footer_logo');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = $this->base_url().'/uploads/logo/';
            $image->move($destinationPath, $image_name);
            $data['value']['footer_logo']='/uploads/logo/'.$image_name;
        
        }else{
            $data['value']['footer_logo']=$settings->footer_logo?$settings->footer_logo:NULL;
        }

        $data['value']=json_encode($data['value']);
       
        if (!empty($settings_data)) {
                Setting::where(['type'=>$request->settings_for,'name'=>$request->settings_for])->update($data);
        }else{
                Setting::create($data);
        }
        Session::flash('success', 'Settings updated Successfully....');
        return redirect()->back();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function about()
    {
        $data['page_title']='About Us';     
        $data['page']=DB::table('tbl_pages')->where("id",1)->first();
        return view('admin/settings/page',$data);
    }

    public function terms()
    {
        $data['page_title']='Terms & condition';     
        $data['page']=DB::table('tbl_pages')->where("id",2)->first();
        return view('admin/settings/page',$data);
    }

    public function privacy()
    {
        $data['page_title']='Privacy & Policy';     
        $data['page']=DB::table('tbl_pages')->where("id",3)->first();
        return view('admin/settings/page',$data);
    }

    public function return()
    {
        $data['page_title']='Return Policy';     
        $data['page']=DB::table('tbl_pages')->where("id",4)->first();
        return view('admin/settings/page',$data);
    }

    public function updatePage(Request $request)
    {
        $data['page']=DB::table('tbl_pages')->where("id",$request->id)->update(['description'=>$request->description]);
        Session::flash('success', 'Page updated Successfully....');
        return redirect()->back();
    }
}
