<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Sentinel;
use Session;
use App\Models\Category_model as Category_model;
use App\Models\VendorModel as VendorModel;
class SubadminController extends Controller
{
     function base_url(){
        $base_url= $_SERVER["DOCUMENT_ROOT"]; 
        return $base_url;
    }
    function add_subadmin(){
    	$data['category']=Category_model::all();
    	$data['page_title']='Add Subadmin';
    	return view('admin/add_subadmin',$data);
    }function subadmin_list(){
      $data['subadmin_list']=DB::table('users')
     ->select('users.*','role_users.role_id')
     ->where('role_users.role_id','4')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->get();
      $data['page_title']='Subadmin List';
      return view('admin/subadmin_list',$data);
    }
    
    function insert_subadmin(Request $request){
    	if ($request->hasFile('profile')) {
        $profile = $request->file('profile');
        $profile_pic = time().'profile.'.$profile->getClientOriginalExtension();
        
        $destinationPathprofile = $this->base_url().'/uploads/profile/';
        $profile->move($destinationPathprofile, $profile_pic);
       
        
      }
     
      $first_name=$request->first_name;
      $last_name=$request->last_name;
      $phone=$request->phone;
      $address=$request->address;
      $email=$request->email;
      $password=$request->password;
      $data=array(
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'phone'=>$phone,
        'email'=>$email,
        'password'=>$password,
        'address'=>$address,
        'profile'=>@$profile_pic,
        'status'=>'0',
        'created_at'=>date('Y-m-d H:i:s'),
        'updated_at'=>date('Y-m-d H:i:s'),
        'add_by'=>Sentinel::getUser()->id,
      );
      if($first_name && $email && $phone && $address && $password){
        $check_user=VendorModel::whereEmail($email)->first();
        //print_r($check_user); die;
        if(!$check_user){
         $userdata=Sentinel::registerAndActivate($data);
          $role=Sentinel::findRoleBySlug('subadmin');
       $res= $role->users()->attach($userdata);
        Session::flash('success', 'insert Successfully....');
        return Redirect('admin/add_subadmin');
    }else{
        Session::flash('error', 'This Email id is used...');
        return $this->add_subadmin();
    }
      }else{
        Session::flash('error', 'All Star Fields are required...');
        return $this->add_vendor();
      }    
    }
    function update_subadmin(Request $request){
       $id=$request->update_id;
      $edit_data=VendorModel::find($id);
      if ($request->hasFile('profile')) {
        $profile = $request->file('profile');
        $profile_pic = time().'profile.'.$profile->getClientOriginalExtension();
        $destinationPathprofile = $this->base_url().'/uploads/profile/';
        $profile->move($destinationPathprofile, $profile_pic);   
        $image_profile=$this->base_url().'uploads/profile/'.$edit_data->profile;
        if($edit_data->profile){
        if(file_exists(@$image_profile)){
          unlink($image_profile);
        } }
      }
     
     
      $first_name=$request->first_name;
      $last_name=$request->last_name;
      $phone=$request->phone;
      $address=$request->address;
      if(@$profile_pic){
      $data=array(
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'phone'=>$phone,
        'address'=>$address,
        'profile'=>@$profile_pic,
         'updated_at'=>date('Y-m-d H:i:s'),
        'add_by'=>Sentinel::getUser()->id,
      );
    }else {
       $data=array(
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'phone'=>$phone,
        'address'=>$address,
         'updated_at'=>date('Y-m-d H:i:s'),
        'add_by'=>Sentinel::getUser()->id,
      );
     }
      if($first_name && $phone){
        DB::table('users')->where('id',$id)->update($data);
        Session::flash('success', 'Update Successfully....');
        return Redirect('admin/edit_subadmin/'.$id);
      }else{
        Session::flash('error', 'All Star Fields are required...');
        return $this->edit_subadmin($id);
      }    
    }
       function subadmin_active($id){
    $data=array('status'=>'0');
     DB::table('users')->where('id', $id)->update($data );
    Session::flash('success', 'Status Update successfully...');
      return Redirect('admin/subadmin_list');
   }
   function subadmin_deactive($id){
    $data=array('status'=>'1');
     DB::table('users')->where('id', $id)->update($data);
    Session::flash('success', 'Status Update successfully...');
      return Redirect('admin/subadmin_list');
   }
   function subadmin_delete($id){
    $edit_data=VendorModel::find($id);
    $image_profile=$this->base_url().'uploads/profile/'.$edit_data->profile;
    if($edit_data->profile){
    if(file_exists($image_profile)){
      unlink($image_profile);
    }
  }
     if($edit_data->driving_licence){
    $image_licence=$this->base_url().'uploads/licence/'.$edit_data->driving_licence;
    if(file_exists($image_licence)){
      unlink($image_licence);
    } }
    if($edit_data->adhar_card){
    $image_adharcard=$this->base_url().'uploads/adharcard/'.$edit_data->adhar_card;
    if(file_exists($image_adharcard)){
      unlink($image_adharcard);
    } }
     DB::table('users')->where('id', $id)->delete();
     DB::table('role_users')->where('user_id', $id)->delete();
     DB::table('activations')->where('user_id', $id)->delete();
    Session::flash('success', 'Delete successfully...');
      return Redirect('admin/subadmin_list');
   }
   function edit_subadmin($id){

    $data['edit_data']=VendorModel::find($id);
    $data['category']=Category_model::all();
    $data['page_title']='Edit Subadmin';
    return view('admin/edit_subadmin',$data);

   }
    

}
?>