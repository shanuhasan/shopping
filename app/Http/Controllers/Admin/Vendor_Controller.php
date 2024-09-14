<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Sentinel;
use Session;
use Activation;
use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItems;
use Auth;

class Vendor_Controller extends Controller
{
    
   
    public function __construct()
    {
        if(!Sentinel::check()){ 
         return redirect('vendor/login');   
        }
    }
    
    public function complaint(){
        
        $slugUser = Sentinel::getUser()->roles()->first()->slug; 
        if($slugUser=='super_admin' || $slugUser=='admin'){
            $complain = DB::table('complain')->get();
        }else{
            $user = Sentinel::getUser();
            $complain = DB::table('complain')->where('user_id', $user->id)->get();
        }
        $data['page_title']='complain';
        $data['complains'] = $complain;
        return view('admin/complain/complain',$data);
    }
    
    public function vendor_add_stock(){
        
        $data['page_title']='Add stock';
        //dd($data);
        return view('admin/vendors/add_stock',$data);
        
    }
    
    public function vendor_add_stock_store(Request $request){
        
        $user = Sentinel::getUser();
        dd($request->all());
    }
    
    public function  vendor_account(){
        
        //commission_set
        //admin_pay
        $user = Sentinel::getUser();
        
        $data['commissions'] = DB::table('commission_set')->where('vendor_id',$user->id)->where('status','delivered')->get(['id','sale_price','commission_price','created_at']);
        
        $data['vendors']=$user;
        $data['page_title']='Account';
        
        //dd($data);
        
        return view('admin/vendors/account',$data);
        
    }
    
    public function vendor_report(){
        $user = Sentinel::getUser();
        
        $product = DB::table('tbl_services')->select('id','service_name','add_by')->where('add_by',$user->id)->get();
        
        $query = DB::table('tbl_services')->where('add_by', $user->id);
        
        $get_product = isset($_GET['p']) ? $_GET['p'] : NULL;
        $get_select_type = isset($_GET['select_type']) ? $_GET['select_type'] : NULL;
        $get_daterange = isset($_GET['daterange']) ? $_GET['daterange'] : NULL;
        
        $prduct_data = [];
        
        if($get_product !='' && $get_product !=null){
            
            $dateExplode = explode('|', $get_daterange);
            $trFirst = trim($dateExplode[0]);
            $trSecond = trim($dateExplode[1]);
            
            if($get_product != 0){
                $query->where('id', $get_product);
                $result = $query->get();
                foreach($result as $res){
                    $total_price = 0;
                    $itemData = DB::table('order_items')->where('product_id', $res->id)
                                        ->whereBetween('created_at', array($trFirst, $trSecond))
                                        ->get();
                    $item_id = [];
                    if(count($itemData) > 0){
                        foreach($itemData as $q){
                            
                           $item_id = ["item_id"=> $q->id];
                            $total_price += $q->total;
                        }
                        $prduct_data[] = [
                        
                        "product_id" => $res->id,
                        "name" => $res->service_name,
                        "total_price" => $total_price,
                        "item_id" => $item_id
                        ];
                    }
                }
            }else{
            
                $result = $query->get();
                foreach($result as $res){
                    $total_price = 0;
                    $itemData = DB::table('order_items')->where('product_id', $res->id)
                                        ->whereBetween('created_at', array($trFirst, $trSecond))
                                        ->get();
                    $item_id = [];
                    if(count($itemData) > 0){
                        foreach($itemData as $q){
                            
                           $item_id = ["item_id"=> $q->id];
                            $total_price += $q->total;
                        }
                        $prduct_data[] = [
                        
                        "product_id" => $res->id,
                        "name" => $res->service_name,
                        "total_price" => $total_price,
                        "item_id" => $item_id
                        ];
                    }
                }
            }
        }
        
        //   dd($prduct_data);
    
       
        $data['filter_data'] = $prduct_data;
        $data['products'] = $product;
        $data['vendors']=$user;
        $data['page_title']='Report';
        
        //dd($data);
        return view('admin/vendors/report',$data);
    }
    
    public function vendor_dasboard(){
        
        if(!Sentinel::check()){ 
         return redirect('/');   
        }
        
        $user = Sentinel::getUser();
        $totalProduct = DB::table('tbl_services')->where('add_by', $user->id)->count();
        
       $orders=array();
       $orders = Order::select(DB::raw("
                    orders.id as id,
                    orders.order_id as order_id,
                    sum(order_items.total) as grand_total,
                    orders.user_name,
                    orders.email,
                    orders.phone,
                    orders.status,
                    orders.payment_status,
                    date"))
       ->whereRaw("tbl_services.add_by={$user->id}")
       ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
       ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
       ->groupBy("orders.id")
       ->count();
       
        //   foreach($orders as $key=> $value){
        //         $orders[$key]->get_items=$this->get_order_item($value->id);
        //     }
       
       $data['orderCount']= $orders;
       $data['productCount']= $totalProduct;
       $data['page_title'] = 'vendor | Dashboard';
    
        //dd($data);
        
        return view('admin/vendors/vendor_dashboard', $data);
        
    }
    
    function get_order_item($id){
        $data =  OrderItems::where('order_items.order_id',$id)
             ->select(DB::raw("order_items.*,product_items.item_unit as unit, product_items.item_unit_value as unit_value,tbl_services.service_name"))
            ->leftJoin('product_items', 'order_items.item_id', '=', 'product_items.id')
            ->leftJoin('tbl_services', 'order_items.product_id', '=', 'tbl_services.id')
            ->get();   
        return $data; 
    }
    
    public function review_list(){
        
        if(!Sentinel::check()){ 
         return redirect('/');   
        }
        
        $slugUser = Sentinel::getUser()->roles()->first()->slug;  
        $user = Sentinel::getUser();
        
        
        if($slugUser == 'admin' || $slugUser=='super_admin'){
            
            $productsReview = DB::table('tbl_services')
                ->select(DB::raw(
                    'tbl_services.id AS id, 
                    tbl_services.service_name, 
                    tbl_services.add_by
                    '
                    ))
                ->leftJoin('order_items', 'tbl_services.id', '=', 'order_items.product_id')
                ->whereNotNull('order_items.review')
                ->get();
            
        }else{
            
        $productsReview = DB::table('tbl_services')
                ->select(DB::raw(
                    'tbl_services.id AS id, 
                    tbl_services.service_name, 
                    tbl_services.add_by
                    '
                    ))
                ->where('add_by', $user->id)
                ->leftJoin('order_items', 'tbl_services.id', '=', 'order_items.product_id')
                ->whereNotNull('order_items.review')
                ->get();
        }        
        
        $data['review']=$productsReview;
        $data['vendors']=$user;
        $data['page_title']='Review List';
        
        //dd($data);
        return view('admin/vendors/review',$data);
    }
    
    public function review_list_detail($id){
       
        if(!Sentinel::check()){ 
         return redirect('/');   
        }
        
       $productsReview = DB::table('order_items')->where('product_id', $id)->whereNotNull('order_items.review')->get();
       foreach($productsReview  as $review){
           
           $order = DB::table('orders')->where('id', $review->order_id)->first();
           $userName = DB::table('users')->where('id', $order->user_id)->first();
           $rev[] = [
                   "id" => $review->id,
                   "order_id" => $review->order_id,
                   "product_id" => $review->product_id,
                   "review" => $review->review,
                   "rating" => $review->rating,
                   "username" => $userName->first_name . ' ' . $userName->last_name,
               ];
       }
     
        $data['review']=$rev;
        // $data['vendors']=$user;
        $data['page_title']='Review List';
        
        //dd($data);
        return view('admin/vendors/review_detail',$data); 
        
    }
    
    public function activeAccount($table,$id){
        
        if(!Sentinel::check()){ 
         return redirect('/');   
        }
        
        if($table !=''){
            
           $getData = DB::table($table)->where('id',$id)->first();
           
           if($getData->status==1){
               $msg = "status deactivate successfully";
               DB::table($table)->where('id',$id)->update(["status"=>0]);
           }else{
                $msg = "status activate successfully";
                DB::table($table)->where('id',$id)->update(["status"=>1]);
           }
            
            return back()->with('success', $msg);
        }else{
            
            return back()->with('error', 'unauthorized request...');
        }
        
    }
    
    function index(){
        
        $role=Sentinel::findRoleBySlug('vendor');
    
        $users = $role->users()->get(); 
        $data['vendors']=$users;
        $data['page_title']='Vendor List';
        return view('admin/vendors/index',$data);
      }
    
      public function create()
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
        $data['page_title']='Add Vendor';
        return view('admin/vendors/add',$data);
      }
      
      public function product($id)
      {
        //$user = User::where("id",$id)->first();
        $users = $id;
       
              $data['product_list'] = DB::table('tbl_services')
             ->select('tbl_services.*','tbl_categories.category_name','sb.category_name as subcategory_name','users.first_name', 'users.last_name')
             ->where('tbl_services.add_by',$users)
             ->leftJoin('tbl_categories', 'tbl_services.parent_category', '=', 'tbl_categories.id')
             ->leftJoin('tbl_categories as sb', 'tbl_services.subcategory', '=', 'sb.id')
             ->leftJoin('users', 'tbl_services.add_by', '=', 'users.id')
             ->orderBy('tbl_services.id', 'desc')
             ->get();
        
            $data['page_title']='Vendor Products';
            
            return view('admin/vendors/product',$data);
        
      }
    
      public function edit($id)
      {
        $user=User::where("id",$id)->first();
        $state=array();
        $city=array();
        $states=State::where("country_id",$user->country)->select("id","name")->get();
        $cities=City::where("state_id",$user->state)->select("id","name")->get();
        foreach ($states as $st) {
            $state[$st->id]=$st->name;
        }
    
        foreach ($cities as $ct) {
            $city[$ct->id]=$ct->name;
        }
        $countries=array(''=>"Select country");
        $country=Country::all();
        foreach ($country as $cun) {
            $countries[$cun->id]=$cun->name;
        }
        $data['user']=$user;
        $data['cities']=$city;
        $data['states']=$state;
        $data['countries']=$countries;
        $data['category']=DB::table('tbl_categories')->where('parent_id',0)
       ->get();
        $data['page_title']='Add Vendor';
        return view('admin/vendors/edit',$data);
      }
    
      public function store(Request $request){
          $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password'=>'required',
            'first_name'=>'required'
        ]);        
    
        if ($validator->fails()):        
            return redirect()->with('error','Some Field are missing...')->back();
        endif;
        $pin=mt_rand(100000, 999999);
        $data=array(
          "first_name"=>$request->first_name,
          "last_name"=>$request->last_name,
          "email"=>$request->email,
          "phone"=>$request->phone,
          "commission"=>$request->commission,
          "line_1"=>$request->line_1,
          "line_2"=>$request->line_2,
          "country"=>$request->country,
          "state"=>$request->state,
          "city"=>$request->city,
          "zip_code"=>$request->zip_code,
          "pin" => md5($pin),
        );   
        $image_name='';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = $this->base_url().'/uploads/users/';
            $image->move($destinationPath, $image_name);
            $data['profile_image']=$image_name;
        }
       
        $from=array();
        if(isset($request->from) && !empty($request->from)){
          $from=$request->from;
          $request->except(['from']);
        }
        
        
        $user=Sentinel::registerAndActivate($request->all());  
        User::where("id",$user->id)->update($data);
        $role=Sentinel::findRoleBySlug('vendor');
        $role->users()->attach($user); 
        
        $msg='
                <div style="text-align:left">
                Dear '.$request->first_name.',
                <br>
                You have Username for login at ' .$request->email. '<br>
                Or Password '.$request->password.' <br>
            
                This is a system generated mail. Please do not reply to this mail.
                Thanks
                
                </div>
                ';
                $subject = ' Login your application';
                
        $this->sent_mail($request->email,$subject,$msg);            

        Session::flash('success', 'Vendor Created Successfully...'); 
        return Redirect('admin/vendors');
        
      }
      
    function sent_mail($email='',$subject='',$msg=''){
         
         $settings=$this->get_settings('site_settings');
         $setting_email=json_decode($settings->value)->other_email;
           
          $headers = "From: ".$setting_email."\r\n";
          $headers .= "Reply-To: ".$setting_email."\r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
          mail($email, $subject, $msg, $headers);
     
    }
    
    function get_settings($type){
        return DB::table('settings')->where('type',$type)->first();
    }
      
      public function update(Request $request){
       
        $data=array(
          "first_name"=>$request->first_name,
          "last_name"=>$request->last_name,
          "email"=>$request->email,
          "phone"=>$request->phone,
          "commission"=>$request->commission,
          "line_1"=>$request->line_1,
          "line_2"=>$request->line_2,
          "country"=>$request->country,
          "state"=>$request->state,
          "city"=>$request->city,
          "zip_code"=>$request->zip_code,
        );
      $image_name='';
      if ($request->hasFile('image')) {
          $image = $request->file('image');
          $image_name = time().'.'.$image->getClientOriginalExtension();
          
          $destinationPath = $this->base_url().'/uploads/users/';
          $image->move($destinationPath, $image_name);
          $data['profile_image']=$image_name;
      }
     if(!empty($request->password)){
        $user=Sentinel::findById($request->id);
      
        $credentials = [
            'password' => $request->password,
        ];

        Sentinel::update($user, $credentials);
     }
      
      
      $user=User::where("id",$request->id)->update($data);  
    
      Session::flash('success', 'Vendor updated Successfully...'); 
        return Redirect('admin/vendors');  
     
    }
      function base_url(){
        $base_url= $_SERVER["DOCUMENT_ROOT"]; 
        return $base_url;
    }
    
      public function getVendors(Request $request){
          
          if(isset($request->term)){
            $term=$request->term;
            $page=$request->page-1;
    
            $rows['results']= User::where("first_name","LIKE","%{$term}%")->orWhere("last_name","LIKE","%{$term}%")->orWhere("email","LIKE","%{$term}%")->orWhere("phone","LIKE","%{$term}%")->offset($page)->limit($request->limit)->select(DB::raw("id,concat(first_name,' ',last_name) as text"))->get();
    
            $count= count(User::where("first_name","LIKE","%{$term}%")->orWhere("last_name","LIKE","%{$term}%")->orWhere("email","LIKE","%{$term}%")->orWhere("phone","LIKE","%{$term}%")->get());
            $rows['total_count']=$count;
            $rows['incomplete_results'] =$count>0?true:false;
          }else{          
            $rows= User::where("id",$request->vendor_id)->select(DB::raw("id,concat(first_name,' ',last_name) as text"))->get();
          }
          
          return response()->json($rows);
          
        }
      
        
        public function updatePawword(Request $request){
          //dd($request);
          $user=Sentinel::getUser();
    
          $credentials = [
              'password' => $request->password,
          ];
    
          $user = Sentinel::update($user, $credentials);
          Session::flash('success', 'Password updated successfully...'); 
          return Redirect('account'); 
        }
    
        
        public function resetPawword(Request $request){
          $user=User::whereEmail($request->user_email)->first();
          $email= $request->user_email;
          $reminder=Reminder::exists($user)?:Reminder::create($user);
          $link=url('/password-reset/'.$user->email.'/'.$reminder->code);
        
          $data = array('link'=>$link);
    
          $headers = "From: test@marketingchord.com\r\n";
          $headers .= "Reply-To: test@marketingchord.com\r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
          mail($user->email, 'Reset Your Password', $link, $headers);
          
    
          Session::flash('success', 'Reset Link sent to your mail...'); 
          return Redirect('login'); 
        }
    
        public function password_reset($email,$resetCode)
        {
           $user=User::byEmail($email);
           if (empty($user)) {
                Session::flash('error', 'Invalid Reset request...'); 
                return redirect('login');
           }
    
           if ($reminder=Reminder::exists($user)) {
               if ($resetCode==$reminder->code) {
                $data['title']='Reset Password';
                $data['email']=$email;
                $data['resetCode']=$resetCode;
                 return view($this->theme('reset-password'),$data);
               }else{
                Session::flash('error', 'Invalid Reset request...'); 
                  return redirect('login');
               }
           }else{
              Session::flash('error', 'Invalid Reset request...'); 
              return redirect('login');
           }
            
        }
    
        public function updatePassword(Request $request, $email,$resetCode)
        {
            $user=User::byEmail($email);
          //  dd($user);
            if (empty($user)) {
              Session::flash('error', 'Invalid Reset request...'); 
              return redirect('login');
            }
     
            if ($reminder=Reminder::exists($user)) {
                if ($resetCode==$reminder->code) {
                    if (Reminder::complete($user,$resetCode,$request->password)) {
                      Session::flash('success', 'Password change Successfully...'); 
                      return redirect('login');
                    }else{
                      Session::flash('error', 'Error in Password change...'); 
                      return redirect('login');
                    }
                }else{
                  Session::flash('error', 'Invalid Reset request...'); 
                  return redirect('login');
                }
            }
        }
}
