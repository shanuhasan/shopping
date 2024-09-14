<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Sentinel;
use Session;
use App\Models\Category_model as Category_model;
use App\Models\VendorModel as VendorModel;
class VendorController1 extends Controller
{
    function add_vendor(){
      $data['category']=Category_model::all();
      $data['states']=DB::table('sma_states')->orderBy('name','ASC')->get();
      $data['page_title']='Add Vendor';
      return view('admin/add_vendor',$data);
    }
    function get_city_by_state(Request $request){
      $state_id= $request->state_id;
      $data=DB::table('sma_cities')->where('state_id',$state_id)->orderBy('name','ASC')->get();
       echo "<option value=''>Select City</option>";
      foreach ($data as $key => $value) {
        echo "<option value='".$value->id."'>".$value->name."</option>";
      }
    }
    function vendor_list(){
      $data['user_list']=DB::table('users')
     ->select('users.*','role_users.role_id','sma_cities.name as city_name')
     ->where('role_users.role_id','3')
     ->orderBy('users.id','DESC')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->leftJoin('sma_cities', 'users.city', '=', 'sma_cities.id')
     ->get();
      $data['page_title']='Vendor List';
      return view('admin/vendor_list',$data);
    }
    function request_money_list(){
     $data['money']=DB::table('money_requests')
     ->select('money_requests.*','users.first_name','users.last_name')
     ->leftJoin('users', 'money_requests.vendor_id', '=', 'users.id')
     ->orderBy('money_requests.id', 'desc')
     ->get();
      $data['page_title']='Request Money List';
      return view('admin/request_money_list',$data);
    }
    
    function add_request_money(){
      $data['vendors']=DB::table('users')
     ->select('users.*','role_users.role_id')
     ->where('role_users.role_id','3')
     ->orderBy('users.id','DESC')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->get();
      $data['page_title']='Add Money Request';
      return view('admin/add_money_request',$data);
    }
    function edit_request_money($id){
      $data['vendors']=DB::table('users')
     ->select('users.*','role_users.role_id')
     ->where('role_users.role_id','3')
     ->orderBy('users.id','DESC')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->get();
     $data['edit_data']=DB::table('money_requests')->where('id',$id)->first();
     $data['page_title']='Edit Money Request';
      return view('admin/edit_money_request',$data);
    }
    function insert_request_money(Request $request){
      $vendor=$request->vendor;
      $money=$request->money;
      $payment_status=$request->payment_status;
      $payment_date=$request->payment_date;
      $status=$request->status;
      $data=array(
          'vendor_id'=>$vendor,
          'money'=>$money,
          'request_status'=>$status,
          'payment_status'=>$payment_status,
          'payment_date'=>$payment_date,
          'created_date'=>date('Y-m-d H:i:s'),
          'updated_by'=>Sentinel::getUser()->id
        );
        
          DB::table('money_requests')->insert($data);
           Session::flash('success', 'Insert successfully...');
      return Redirect('admin/add_request_money');
    } function update_request_money(Request $request){
      $id=$request->update_id;
      $vendor=$request->vendor;
      $payment_status=$request->payment_status;
      $payment_date=$request->payment_date;
      $money=$request->money;
      $status=$request->status;
      $data=array(
          'vendor_id'=>$vendor,
          'money'=>$money,
          'request_status'=>$status,
          'payment_status'=>$payment_status,
          'payment_date'=>$payment_date,
          'updated_date'=>date('Y-m-d H:i:s'),
          'updated_by'=>Sentinel::getUser()->id
        );
        
          DB::table('money_requests')->where('id',$id)->update($data);
           Session::flash('success', 'Update successfully...');
      return Redirect('admin/edit_request_money/'.$id);
    }
    function insert_vendor(Request $request){
      if ($request->hasFile('profile')) {
        $profile = $request->file('profile');
        $profile_pic = time().'profile.'.$profile->getClientOriginalExtension();
        
        $destinationPathprofile = public_path('/uploads/profile/');
        $profile->move($destinationPathprofile, $profile_pic);
       
        
      }
      if ($request->hasFile('adhar_card')) {
        $adhar_card = $request->file('adhar_card');
        $adhar_card_name = time().'adharcard.'.$adhar_card->getClientOriginalExtension();
        $destinationPathadhar_card_name = public_path('/uploads/adharcard/');
        $adhar_card->move($destinationPathadhar_card_name, $adhar_card_name);
        
      }
      if ($request->hasFile('passbook')) {
        $passbook = $request->file('passbook');
        $passbook_name = time().'passbook.'.$passbook->getClientOriginalExtension();
        $destinationPathadhar_passbook_name_name = public_path('/uploads/passbook/');
        $passbook->move($destinationPathadhar_passbook_name_name, $passbook_name);
       
      }
      if ($request->hasFile('cancel_check')) {
        $cancel_check = $request->file('cancel_check');
        $cancel_check_name = time().'cancel_check.'.$cancel_check->getClientOriginalExtension();
        $destinationPathadhar_cancel_check_name = public_path('/uploads/cancel_check/');
        $cancel_check->move($destinationPathadhar_cancel_check_name, $cancel_check_name);
        
      }
       if ($request->hasFile('image1')) {
        $image1 = $request->file('image1');
        $image1_name = time().'image1.'.$image1->getClientOriginalExtension();
        $destinationPathadhar_image1_name = public_path('/uploads/adharcard/');
        $image1->move($destinationPathadhar_image1_name, $image1_name);
        
      } 
      if ($request->hasFile('image2')) {
        $image2 = $request->file('image2');
        $image2_name = time().'image2.'.$image2->getClientOriginalExtension();
        $destinationPathadhar_image2_name = public_path('/uploads/adharcard/');
        $image2->move($destinationPathadhar_image2_name, $image2_name);
        
      } 
      if ($request->hasFile('image3')) {
        $image3 = $request->file('image3');
        $image3_name = time().'image3.'.$image3->getClientOriginalExtension();
        $destinationPathadhar_image3_name = public_path('/uploads/adharcard/');
        $image3->move($destinationPathadhar_image3_name, $image3_name);
        
      }
      $parent_category=implode(',', $request->parent_category);
      $first_name=$request->first_name;
      $last_name=$request->last_name;
      $phone=$request->phone;
      $address=$request->address;
      $permanent_address=$request->permanent_address;
      $present_address=$request->present_address;
      $holder_name=$request->holder_name;
      $email=$request->email;
      $password=$request->password;
      $account_number=$request->account_number;
      $ifsc_code=$request->ifsc_code;
      $branch_name=$request->branch_name;
      $bank_name=$request->bank_name;
      $country=$request->country;
      $state=$request->state;
      $city=$request->city;
     
      $commission=$request->commission;
    
      $data=array(
        'parent_category'=>$parent_category,
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'phone'=>$phone,
        'email'=>$email,
        'password'=>$password,
        'address'=>$address,
        'permanent_address'=>$permanent_address,
        'present_address'=>$present_address,
        'country'=>$country,
        'state'=>$state,
        'city'=>$city,
        'holder_name'=>$holder_name,
        'account_number'=>$account_number,
        'ifsc_code'=>$ifsc_code,
        'branch_name'=>$branch_name,
        'bank_name'=>$bank_name,
        'commission'=>$commission,
        'profile'=>@$profile_pic,
        'status'=>'0',
        'adhar_card'=>@$adhar_card_name,
        'cancel_check'=>@$cancel_check_name,
        'passbook'=>@$passbook_name,
        'image3'=>@$image3_name,
        'image2'=>@$image2_name,
        'image1'=>@$image1_name,
        'created_at'=>date('Y-m-d H:i:s'),
        'updated_at'=>date('Y-m-d H:i:s'),
        'add_by'=>Sentinel::getUser()->id,
      );
      if($parent_category && $first_name && $email && $phone && $address && $password){
        $check_user=VendorModel::whereEmail($email)->first();
        //print_r($check_user); die;
        if(!$check_user){
         $userdata=Sentinel::registerAndActivate($data);
          $role=Sentinel::findRoleBySlug('vendor');
       $res= $role->users()->attach($userdata);

    $sub='oyegattu';
     $msg='<b>Hi '.$first_name.'</b> you are registered successfully, but your account has been under approval.';
     $this->sent_mail($email,$sub,$msg);
        Session::flash('success', 'Vendor insert Successfully....');
        return Redirect('admin/add_vendor');
    }else{
        Session::flash('error', 'This Email id is used...');
        return $this->add_vendor();
    }
      }else{
        Session::flash('error', 'All Star Fields are required...');
        return $this->add_vendor();
      }    
    }
       function user_active($id){
    $data=array('status'=>'0');
     DB::table('users')->where('id', $id)->update($data );
     $get_data=DB::table('users')->where('id', $id)->first();
     $sub='oyegattu';
     $msg='<b>Hi '.$get_data->first_name.'</b> Your account has been Deactivated';
     $this->sent_mail($get_data->email,$sub,$msg);
    Session::flash('success', 'Status Update successfully...');
      return Redirect('admin/vendor_list');
   }
   function user_deactive($id){
    $data=array('status'=>'1');
     DB::table('users')->where('id', $id)->update($data);
     $get_data=DB::table('users')->where('id', $id)->first();
     $sub='oyegattu';
     $msg='<b>Hi '.$get_data->first_name.'</b> Your account has been activated';
     $this->sent_mail($get_data->email,$sub,$msg);
    Session::flash('success', 'Status Update successfully...');
      return Redirect('admin/vendor_list');
   }
   
   function sent_mail($email='',$subject='',$msg=''){
   
      $headers = "From: test@marketingchord.com\r\n";
      $headers .= "Reply-To: test@marketingchord.com\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
      mail($email, $subject, $msg, $headers);
     
    }
   function request_money_active($id){
    $data=array('request_status'=>'pending');
     DB::table('money_requests')->where('id', $id)->update($data );
    Session::flash('success', 'Status Update successfully...');
      return Redirect('admin/request_money_list');
   }
   function request_money_deactive($id){
    $data=array('request_status'=>'approved');
     DB::table('money_requests')->where('id', $id)->update($data);
    Session::flash('success', 'Status Update successfully...');
      return Redirect('admin/request_money_list');
   }function request_money_delete($id){
     DB::table('money_requests')->where('id', $id)->delete();
    Session::flash('success', 'Status Delete successfully...');
      return Redirect('admin/request_money_list');
   }
   function user_delete($id){
    $edit_data=VendorModel::find($id);
    $image_profile=public_path('uploads/profile/'.$edit_data->profile);
    if($edit_data->profile){
    if(file_exists($image_profile)){
      unlink($image_profile);
    }
  }
     if($edit_data->driving_licence){
    $image_licence=public_path('uploads/licence/'.$edit_data->driving_licence);
    if(file_exists($image_licence)){
      unlink($image_licence);
    } }
    if($edit_data->adhar_card){
    $image_adharcard=public_path('uploads/adharcard/'.$edit_data->adhar_card);
    if(file_exists($image_adharcard)){
      unlink($image_adharcard);
    } }
     DB::table('users')->where('id', $id)->delete();
     DB::table('role_users')->where('user_id', $id)->delete();
     DB::table('activations')->where('user_id', $id)->delete();
    Session::flash('success', 'Delete successfully...');
      return Redirect('admin/vendor_list');
   }
   function edit_user($id){

    $data['edit_data']=VendorModel::find($id);
    $data['category']=Category_model::all();
     $data['states']=DB::table('sma_states')->get();
     $data['cities']=DB::table('sma_cities')->where('state_id',$data['edit_data']->state)->get();
    $data['page_title']='Edit Vendor';
    return view('admin/edit_vendor',$data);

   }
    function update_vendor(Request $request){
      $update_id=$request->update_id;
      $edit_data=VendorModel::find($update_id);
      if ($request->hasFile('profile')) {
        $profile = $request->file('profile');
        $profile_pic = time().'profile.'.$profile->getClientOriginalExtension();
        
        $destinationPathprofile = public_path('/uploads/profile/');
        $profile->move($destinationPathprofile, $profile_pic);
        $image_profile=public_path('uploads/profile/'.$edit_data->profile);
        if($edit_data->profile){
        if(file_exists(@$image_profile)){
          unlink($image_profile);
        } }
        
      }
      if ($request->hasFile('adhar_card')) {
        $adhar_card = $request->file('adhar_card');
        $adhar_card_name = time().'adharcard.'.$adhar_card->getClientOriginalExtension();
        $destinationPathadhar_card_name = public_path('/uploads/adharcard/');
        $adhar_card->move($destinationPathadhar_card_name, $adhar_card_name);
        $image_adharcard=public_path('uploads/adharcard/'.$edit_data->adhar_card);
        if($edit_data->adhar_card){
        if(file_exists($image_adharcard)){
          unlink($image_adharcard);
        } }
      }
      if ($request->hasFile('passbook')) {
        $passbook = $request->file('passbook');
        $passbook_name = time().'passbook.'.$passbook->getClientOriginalExtension();
        $destinationPathadhar_passbook_name_name = public_path('/uploads/passbook/');
        $passbook->move($destinationPathadhar_passbook_name_name, $passbook_name);
        $image_passbook=public_path('uploads/passbook/'.$edit_data->passbook);
        if($edit_data->passbook){
        if(file_exists($image_passbook)){
          unlink($image_passbook);
        } }
      }
      if ($request->hasFile('cancel_check')) {
        $cancel_check = $request->file('cancel_check');
        $cancel_check_name = time().'cancel_check.'.$cancel_check->getClientOriginalExtension();
        $destinationPathadhar_cancel_check_name = public_path('/uploads/cancel_check/');
        $cancel_check->move($destinationPathadhar_cancel_check_name, $cancel_check_name);
        $image_cancel_check_name=public_path('uploads/cancel_check/'.$edit_data->cancel_check);
        if($edit_data->cancel_check){
        if(file_exists($image_cancel_check_name)){
          unlink($image_cancel_check_name);
        } }
      }
       if ($request->hasFile('image1')) {
        $image1 = $request->file('image1');
        $image1_name = time().'image1.'.$image1->getClientOriginalExtension();
        $destinationPathadhar_image1_name = public_path('/uploads/adharcard/');
        $image1->move($destinationPathadhar_image1_name, $image1_name);
        $image_image1=public_path('uploads/adharcard/'.$edit_data->image1);
        if($edit_data->image1){
        if(file_exists($image_image1)){
          unlink($image_image1);
        } }
      } 
      if ($request->hasFile('image2')) {
        $image2 = $request->file('image2');
        $image2_name = time().'image2.'.$image2->getClientOriginalExtension();
        $destinationPathadhar_image2_name = public_path('/uploads/adharcard/');
        $image2->move($destinationPathadhar_image2_name, $image2_name);
        $image_image2=public_path('uploads/adharcard/'.$edit_data->image2);
        if($edit_data->image2){
        if(file_exists($image_image2)){
          unlink($image_image2);
        } }
      } 
      if ($request->hasFile('image3')) {
        $image3 = $request->file('image3');
        $image3_name = time().'image3.'.$image3->getClientOriginalExtension();
        $destinationPathadhar_image3_name = public_path('/uploads/adharcard/');
        $image3->move($destinationPathadhar_image3_name, $image3_name);
        $image_image3=public_path('uploads/adharcard/'.$edit_data->image3);
        if($edit_data->image3){
        if(file_exists($image_image3)){
          unlink($image_image3);
        } }
      }
      $parent_category=implode(',', $request->parent_category);
      $first_name=$request->first_name;
      $last_name=$request->last_name;
      $phone=$request->phone;
      $address=$request->address;
      $permanent_address=$request->permanent_address;
      $present_address=$request->present_address;
      $holder_name=$request->holder_name;
      $account_number=$request->account_number;
      $ifsc_code=$request->ifsc_code;
      $branch_name=$request->branch_name;
      $bank_name=$request->bank_name;
      $country=$request->country;
      $state=$request->state;
      $city=$request->city;
       $status=$request->status;
      $commission=$request->commission;
      $pp=$request->pp;
      $ad=$request->ad;
      $cc=$request->cc;
      $pass=$request->pass;
      $img1=$request->img1;
      $img2=$request->img2;
      $img3=$request->img3;
      if(@$profile_pic){
        $pp=$profile_pic;
      }
      if(@$adhar_card_name){
        $ad=$adhar_card_name;
      }
      if(@$passbook_name){
        $pass=$passbook_name;
      }
      if(@$cancel_check_name){
        $cc=$cancel_check_name;
      } 
      if(@$image3_name){
        $img3=$image3_name;
      }
      if(@$image2_name){
        $img2=$image2_name;
      }
       if(@$image1_name){
        $img1=$image1_name;
      }
      $data=array(
        'parent_category'=>$parent_category,
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'phone'=>$phone,
        'address'=>$address,
        'permanent_address'=>$permanent_address,
        'present_address'=>$present_address,
        'country'=>$country,
        'state'=>$state,
        'city'=>$city,
        'holder_name'=>$holder_name,
        'account_number'=>$account_number,
        'ifsc_code'=>$ifsc_code,
        'branch_name'=>$branch_name,
        'bank_name'=>$bank_name,
        'commission'=>$commission,
        'profile'=>@$pp,
        'adhar_card'=>@$ad,
        'cancel_check'=>@$cc,
        'passbook'=>@$pass,
        'image3'=>@$img3,
        'image2'=>@$img2,
        'image1'=>@$img1,
        'status'=>$status,
        'updated_at'=>date('Y-m-d H:i:s'),
        'add_by'=>Sentinel::getUser()->id,
      );
      //print_r($data); die;
      if($parent_category && $first_name && $phone && $address){
        //$check_user=VendorModel::whereEmail($email)->first();
        //print_r($check_user); die;
        //if(!$check_user){
         DB::table('users')->where('id',$update_id)->update($data);
      Session::flash('success', 'Update Successfully....');
      return Redirect('admin/edit_user/'.$update_id);
        //return Redirect('admin/add_vendor');
       
      }else{
        Session::flash('error', 'All Star Fields are required...');
        return Redirect('admin/edit_user/'.$update_id);
      }    
    }

}
?>