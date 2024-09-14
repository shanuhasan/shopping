<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Validator;
use Sentinel;
use Session;
use Activation;
use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Str;

class Logincontroller extends Controller
{
    
	public function index(){
        return view('admin/login');
	}
	
	function login_user(Request $request){
	    
	    //dd($request->all());
	    
		if($request->email && $request->password){
		    
		 $user=Sentinel::authenticate($request->all());
         $users=Sentinel::check();
         
        //dd($users);
        
        if($user){
            
             $check_venodr=DB::table('role_users')->where('user_id',$users->id)->first();
              if($check_venodr->role_id==4 || $check_venodr->role_id==2 || $check_venodr->role_id==3){
            	return redirect('admin/dashboard')->with('success','Login Successfully...');
            }else{
                
                Sentinel::logout();
                return back()->with('error','Wrong Email id and password...');
                
                // return redirect('admin')->with('error','Wrong Email id and password...');
            }
        }else{
            DB::table('throttle')->where('type','global')->delete();
            DB::table('throttle')->where('type','ip')->delete();
            DB::table('throttle')->where('type','user')->delete();
        	return back()->with('error','Wrong Email id and password...');
        	
        // 	return redirect('admin')->with('error','Wrong Email id and password...');
        }
        
    }else{
        return back()->with('error','Please Enter Email id and password...');
        
        //	return redirect('admin')->with('error','Please Enter Email id and password...');
    }

	}
	
	
	public function logout()
    {
        $slug = Sentinel::getUser()->roles()->first()->slug;
        
        if( $slug == 'vendor'){
            Sentinel::logout();
            return redirect('vendor/login')->with('success','Logout Successfully...');
        }
        
        Sentinel::logout();
        return redirect('admin')->with('success','Logout Successfully...');
        
    }
    
    
    public function vendor_login(){
        
        return view('admin/vendors/login');
        
    }
    
    public function vendor_register(){
        
        $state=array(''=>"Select state");
        $city=array(''=>"Select city");
        $countries=array(''=>"Select country");
        $country=Country::all();
        foreach ($country as $cun) {
            $countries[$cun->id]=$cun->name;
        }
        $data['cities']=$city;
        $data['states']=$state;
        $data['countries']=$countries;
        $data['page_title']='Add Vendor';
        
        return view('admin/vendors/register', $data);
        
    }
    
    function base_url(){
        $base_url= $_SERVER["DOCUMENT_ROOT"]; 
        return $base_url;
    }
    
    public function vendor_store(Request $request){
        
          $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password'=>'required',
            'first_name'=>'required',
        ]);        
    
         //dd($request->all());
    
        if ($validator->fails()){
            return back()->with('error','Some Field are missing...');
        }       

        $pin=mt_rand(100000, 999999);
    
        $image_name='';
        if ($request->hasFile('image')) {
        
            $image = $request->file('image');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/';
            $image->move($destinationPath, $image_name);
        }
      
            $user = Sentinel::registerAndActivate([
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                    "email" => $request->email,
                    "password" => $request->password
            ]);
            
            $commission = isset($request->commission) ? $request->commission : 0;
            
            $data = array(
                  "profile_image" => $image_name,
                  "phone"=>$request->phone,
                  "commission"=>$commission,
                  "line_1"=>$request->line_1,
                  "line_2"=>$request->line_2,
                  "country"=>$request->country,
                  "state"=>$request->state,
                  "city"=>$request->city,
                  "zip_code"=>$request->zip_code,
                  "pin" => md5($pin),
                  "term_and_condition" => 1,
                  "status" => 0,
                ); 
            
            $role = Sentinel::findRoleBySlug('vendor');
            User::where("id",$user->id)->update($data);
            $res = $role->users()->attach($user);
        
        $cencel_check='';
        if ($request->hasFile('cencel_check')) {
            $image = $request->file('cencel_check');
            $cencel_check = time().'.cc'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $cencel_check);
        }
    
         $addhar_card='';
        if ($request->hasFile('addhar_card')) {
            $image = $request->file('addhar_card');
            $addhar_card = time().'.ac'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $addhar_card);
        }
        
        $pan_card='';
        if ($request->hasFile('pan_card')) {
        
            $image = $request->file('pan_card');
            $pan_card = time().'.pc'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $pan_card);
        }
        
        $trade_license_document='';
        if ($request->hasFile('trade_license_document')) {
            $image = $request->file('trade_license_document');
            $trade_license_document = time().'.pb'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $trade_license_document);
        }
        
        $vendor_policy_document='';
        if ($request->hasFile('vendor_policy_document')) {
            $image = $request->file('vendor_policy_document');
            $vendor_policy_document = time().'.pb'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $vendor_policy_document);
        }
        
         $gst_document='';
        if ($request->hasFile('gst_document')) {
            $image = $request->file('gst_document');
            $gst_document = time().'.pb'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $gst_document);
        }
        
        
        DB::table('vendor_details')->insert([
            
                  "vendor_id" => $user->id,
                  "name_firm" => $request->name_firm,
                  "owner_name" => $request->owner_name,
                  "gst_no" => $request->gst_no,
                  "account_no" => $request->account_no,
                  "ifsc_code" => $request->ifsc_code,
                  "bank_name" => $request->bank_name,
                  "branch_name" => $request->branch_name,
                  "cencel_check" => $cencel_check,
                  "addhar_card" => $addhar_card,
                  "pan_card" => $pan_card,
                  "trade_license_document" => $trade_license_document,
                  "vendor_policy_document" => $vendor_policy_document,
                  "gst_document" => $gst_document
            ]);
        
         return back()->with('success','Vendor created successfully!');
        
      }
      
      
      public function vendor_update(Request $request){
          
         //dd($request->all());
         
        $image_name = $request->old_image;
        if ($request->hasFile('image')) {
        
            $image = $request->file('image');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/';
            $image->move($destinationPath, $image_name);
            
        }
      
      $commission = isset($request->commission) ? $request->commission : 0;
      
            $data=array(
                  "first_name" => $request->first_name,
                  "last_name" => $request->last_name,
                  "commission"=>$commission,
                  //"email" => $request->email,
                  "profile_image" => $image_name,
                  "phone"=>$request->phone,
                  "line_1"=>$request->line_1,
                  "line_2"=>$request->line_2,
                  "country"=>$request->country,
                  "state"=>$request->state,
                  "city"=>$request->city,
                  "zip_code"=>$request->zip_code
                ); 
            
            //$role = Sentinel::findRoleBySlug('vendor');
            User::where("id",$request->update_id)->update($data);
            
            // $res = $role->users()->attach($user);
        
        $cencel_check= $request->old_cencel_check;
        if ($request->hasFile('cencel_check')) {
            $image = $request->file('cencel_check');
            $cencel_check = time().'.cc'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $cencel_check);
        }
        
         $addhar_card= $request->old_addhar_card;
        if ($request->hasFile('addhar_card')) {
            $image = $request->file('addhar_card');
            $addhar_card = time().'.ac'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $addhar_card);
        }
        
         $pan_card= $request->old_pan_card;
        if ($request->hasFile('pan_card')) {
        
            $image = $request->file('pan_card');
            $pan_card = time().'.pc'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $pan_card);
        }
        
        $trade_license_document= $request->trade_license_document;
        if ($request->hasFile('trade_license_document')) {
            $image = $request->file('trade_license_document');
            $trade_license_document = time().'.pb'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $trade_license_document);
        }
        
        $vendor_policy_document= $request->vendor_policy_document;
        if ($request->hasFile('vendor_policy_document')) {
            $image = $request->file('vendor_policy_document');
            $vendor_policy_document = time().'.pb'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $vendor_policy_document);
        }
        
         $gst_document= $request->gst_document;
        if ($request->hasFile('gst_document')) {
            $image = $request->file('gst_document');
            $gst_document = time().'.pb'.$image->getClientOriginalExtension();
            $destinationPath = $this->base_url().'/uploads/users/document/';
            $image->move($destinationPath, $gst_document);
        }
        
        $proData = [
                  "vendor_id" =>$request->update_id,    
                  "gst_no" => $request->gst_no,
                  "name_firm" => $request->name_firm,
                  "owner_name" => $request->owner_name,
                  "account_no" => $request->account_no,
                  "ifsc_code" => $request->ifsc_code,
                  "bank_name" => $request->bank_name,
                  "branch_name" => $request->branch_name,
                  "cencel_check" => $cencel_check,
                  "addhar_card" => $addhar_card,
                  "pan_card" => $pan_card,
                  "trade_license_document" => $trade_license_document,
                  "vendor_policy_document" => $vendor_policy_document,
                  "gst_document" => $gst_document
            ];
        
        if($request->profile_id ==''){
            DB::table('vendor_details')->insert($proData);
        }else{
           DB::table('vendor_details')->where('id', $request->profile_id)->update($proData); 
        }
        
         return back()->with('success','Vendor updated successfully!');
        
      }
      
      
      
      public function vendor_profile($id=''){
          
         //dd($id);
          $user = Sentinel::getUser();
          
          if($id==''){
              $id = Sentinel::getUser()->id;
             
          }else{
              $id = $id;
              $user = DB::table('users')->where('id', $id)->first();
          }
           
           //dd($user);
           
           $user_detail = DB::table('vendor_details')->where('vendor_id', $id)->first();
          
            $state= array(''=>"Select sate");
            $city= array(''=>"Select city");
            $countries= array(''=>"Select country");
            
            $country = Country::all();
            foreach ($country as $cun) {
                $countries[$cun->id]=$cun->name;
            }
            
            $state_arr = State::where('country_id', $user->country)->get();
            
            foreach ($state_arr as $cun) {
                $state[$cun->id]=$cun->name;
            }
            
            $city_arr = City::where('state_id', $user->state)->get();
            
            foreach ($city_arr as $cun) {
                $city[$cun->id]=$cun->name;
            }
            
            $data['country'] = Country::where('id', $user->country)->first();
            $data['state'] = State::where('id', $user->state)->first();
            
            $data['city'] = City::where('id', $user->city)->first();
            
            
            $data['cities'] = $city;
            $data['states'] = $state;
            $data['countries'] = $countries;
            
            $data['page_title']='Vendor Profile';
          
            $data['user'] = $user;
            $data['user_detail']= $user_detail;
          
          //dd($data);
          
          return view('admin/vendors/vendor_profile', $data);
      }
      
      
      
    //   function get_settings($type){
    //     return DB::table('settings')->where('type',$type)->first();
    // }
    
    //   function sent_mail($email='',$subject='',$msg=''){
         
    //      $settings=$this->get_settings('site_settings');
    //      $setting_email=json_decode($settings->value)->other_email;
           
    //       $headers = "From: ".$setting_email."\r\n";
    //       $headers .= "Reply-To: ".$setting_email."\r\n";
    //       $headers .= "MIME-Version: 1.0\r\n";
    //       $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    //       mail($email, $subject, $msg, $headers);
     
    // }
    
      
      public function updatePawword(Request $request){
          //dd($request);
          $user=Sentinel::getUser();
    
          $credentials = [
              'password' => $request->new_password,
          ];
    
          $user = Sentinel::update($user, $credentials);
          Session::flash('success', 'Password updated successfully...');
          
          return back()->with('success','Password updated successfully...');
          
            //   return Redirect('account'); 
        }
        
        public function forgotPassword(Request $request){
          
        //   dd($request);
          
          return view('admin/vendors/forgot_password');
          
          //return back()->with('success','Password updated successfully...');
          
        }
      
      
      public function resetPawword(Request $request){
          
          $user = User::whereEmail($request->user_email)->first();
          $email= $request->user_email;
          
          $token = Str::random(32);
          
          if(!empty($user)){
              
             $password_table = DB::table('forgot_password')->where('email', $email)->first();
             if(!empty($password_table)){
                 
                 DB::table('forgot_password')->where('email', $email)->update(["token" => $token]);
             }else{
                 
                 DB::table('forgot_password')->insert(["email" => $email, "token" => $token]);
             }
             
              $link=url('/vendor/password-reset/'.$user->email.'/'.$token);
        
              $data = array('link'=>$link);
        
              $headers = "From: test@marketingchord.com\r\n";
              $headers .= "Reply-To: test@marketingchord.com\r\n";
              $headers .= "MIME-Version: 1.0\r\n";
              $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
              mail($user->email, 'Reset Your Password', $link, $headers);
              
        
              Session::flash('success', 'Reset Link sent to your mail...'); 
              return back()->with('success','Reset Link sent to your mail...');
                
              
          }else{
              
              
              return back()->with('error','Your email id invalid');
              
          }
        
          
        }
    
    
    public function password_reset($email,$resetCode)
        {
          $user=User::byEmail($email);
        
          
          if (empty($user)) {
                Session::flash('error', 'Invalid Reset request...'); 
                return redirect('vendor/login');
          }
    
            $password_table = DB::table('forgot_password')->where('email', $email)->first();
            
            
          if (!empty($password_table)) {
              
              if ($resetCode==$password_table->token) {
                $data['title']='Reset Password';
                $data['email']=$email;
                $data['token']=$resetCode;
                
                //dd($data);
                 return view('admin/vendors/password_reset', $data);
                 
              }else{
                Session::flash('error', 'Invalid Reset request...'); 
                  return redirect('vendor/login');
              }
          }else{
              Session::flash('error', 'Invalid Reset request...'); 
              return redirect('vendor/login');
          }
            
        }
    
        public function updatePassword(Request $request, $email,$resetCode)
        {
            $user=User::byEmail($email);
            
            
            if($request->password != $request->new_password) {
             
              return back()->with('error','Password don\'t match ');
            }
            
             if($request->password =='null' && $request->new_password =='null') {
             
              return back()->with('error','Your new password or  password required ');
            }
            
            
            // dd($user->id);
            
            if (empty($user)) {
              Session::flash('error', 'Invalid Reset request...'); 
              return redirect('vendor/login');
            }
     
            $password_table = DB::table('forgot_password')->where('email', $email)->first();
            
            if (!empty($password_table)) {
                if ($resetCode==$password_table->token) {
                    
    
                      $credentials = [
                          'password' => Hash::make($request->new_password),
                      ];
                
                      User::where('id', $user->id)->update($credentials);
                      DB::table('forgot_password')->where('email', $email)->delete();
                    
                      Session::flash('success', 'Password change Successfully...'); 
                      return redirect('vendor/login');
                    
                }else{
                  Session::flash('error', 'Invalid Reset request...'); 
                  return redirect('vendor/login');
                }
            }else{
                
                Session::flash('error', 'Invalid Reset request...'); 
                  return redirect('vendor/login');
                
            }
        }
    
    
    
}
