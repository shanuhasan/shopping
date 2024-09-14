<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Sentinel;
use Session;
use App\Models\Category_model as Category_model;
use App\Models\ServiceModel as ServiceModel;
use App\Models\ServiceprovideModel as ServiceprovideModel;
use App\Models\VendorModel as VendorModel;
class ServiceprovideController extends Controller
{
    function add_service_provide(){
      $data['customers']=DB::table('customers')->get();
      $data['category']=Category_model::all();
      $data['services']=ServiceModel::all();
      $data['vendor']=DB::table('users')
     ->select('users.*','role_users.role_id')
     ->where('role_users.role_id','3')
     ->orderBy('users.id','DESC')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->get();
      $data['page_title']='Service Provide';
      return view('admin/add_provide_service',$data);
      
    }
    function get_service_details(Request $request){
      $id= $request->service_id;
      $vendor_id= $request->vendor_id;
      $add_by=Sentinel::getUser()->id;
      $data_res= ServiceModel::find($id);
      $data=array(
        'add_by'=>$add_by,
        'service_id'=>@$data_res->id,
        'service_price'=>$data_res->service_price,
        'service_name'=>$data_res->service_name,
        'vendor_id'=>$vendor_id,
        'item_count'=>'1',
        'total_amount'=>$data_res->service_price
        );
      $check_data1= DB::table('tbl_cart_service')
        ->where('add_by',$add_by)
        ->where('vendor_id',$vendor_id)
        ->where('service_id',$id)
            ->get();
    $check_data = DB::select(DB::raw("select * from tbl_cart_service where add_by = ".$add_by." and vendor_id = ".$vendor_id." and service_id = ".$id.""));
            //print_r($check_data); die;
        if(!$check_data){
      DB::table('tbl_cart_service')->insert($data);
     }else{
      echo "exist";
     }
      $get_data= DB::table('tbl_cart_service')
        ->where('add_by',$add_by)
        ->where('vendor_id',$vendor_id)
      ->get();
      return $this->get_cart_list($get_data);
    }
    function get_cart_list($data){
      $price=array();
      foreach ($data as $key => $value) {
          
          $price[]=$value->service_price;
      echo '
       <tr>
       <td><input type="hidden" value="'.$value->service_id.'" name="service_id[]">
       <input type="hidden" value="'.$value->service_name.'" name="service_name[]">
       '.$value->service_name.' </td>
       <td><input type="hidden" value="'.$value->service_price.'" name="service_price[]">
       '.$value->service_price.'</td>
       <td><a href="javaScript:void(0)" class="btn btn-danger btn-sm remove_rervice_item" data-id="'.$value->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
       </tr>
       ';
     }
     echo '<tr>
       <th>Total Amount</th>
       <th><input type="hidden" name="total_amount" value="'.array_sum($price).'">'.array_sum($price).'</th>
       <th></th>
       </tr>';
    }
    function cart_item_detele(Request $request){
      $service_id= $request->service_id;
      $vendor_id= $request->vendor_id;
      $add_by=Sentinel::getUser()->id;
      DB::table('tbl_cart_service')->where('id', $service_id)->delete();
      $get_data= DB::table('tbl_cart_service')
        ->where('add_by',$add_by)
        ->where('vendor_id',$vendor_id)
      ->get();
      return $this->get_cart_list($get_data);
    }
    function insert_service_to_vendor(Request $request){
      $customer_name=$request->customer_name;
      $customer_id=$request->customer_id;
      $phone=$request->phone;
      $email=$request->email;
      $organization_name=$request->organization_name;
      $organization_address=$request->organization_address;
      $near_by_address=$request->near_by_address;
      $vendor=$request->vendor;
      $note=$request->note;

      $service_id=$request->service_id;

      $category=$request->parent_category;
      $subcategory=$request->subcategory;
      $service_name=$request->service_name;
      $service_price=$request->service_price;
      $total_amount=$request->total_amount;

      if($customer_name && $phone && $email && $organization_address && $organization_name && $near_by_address && $vendor && $service_id){
        if($customer_id){
          $cus_id=$customer_id;
        }else{
        $customer_data=array(
          'customer_name'=>$customer_name,
          'phone'=>$phone,
          'email'=>$email,
          'organization_name'=>$organization_name,
          'organization_address'=>$organization_address,
          'near_by_address'=>$near_by_address,
          'created_date'=>date('Y-m-d H:i:s'),
          'updated_date'=>date('Y-m-d H:i:s'),
          'status'=>'0',
          'add_by'=>$users=Sentinel::getUser()->id,
        );

        $cus_id= DB::table('customers')->insertGetId($customer_data);
      }
        $data=array(
          'customer_name'=>$customer_name,
          'customer_id'=>$cus_id,
          'phone'=>$phone,
          'email'=>$email,
          'note'=>$note,
          'organization_name'=>$organization_name,
          'organization_address'=>$organization_address,
          'near_by_address'=>$near_by_address,
          'vendor'=>$vendor,
          'category'=>$category,
          'total_amount'=>$total_amount,
          'created_date'=>date('Y-m-d H:i:s'),
          'updated_date'=>date('Y-m-d H:i:s'),
          'date'=>date('Y-m-d'),
          'service_status'=>'pending',
          'add_by'=>$users=Sentinel::getUser()->id,
        );
       $insert_id= DB::table('orders')->insertGetId($data);
       foreach ($service_id as $key => $value) {
        $service_list=array(
          'order_id'=>$insert_id,
          'service_id'=>$value,
          'service_name'=>$service_name[$key],
          'service_price'=>$service_price[$key],
        );
        DB::table('service_order_items')->insert($service_list);
       }
     $vendor= $request->vendor;
      $add_by=Sentinel::getUser()->id;
      DB::table('tbl_cart_service')
      ->where('vendor_id', $vendor)
      ->where('add_by', $add_by)
      ->delete();
      Session::flash('success', 'Add Successfully....');
      return Redirect('admin/add_service_provide');
      }else{

        Session::flash('error', 'All Star fields are required...');
      return $this->add_service_provide();
      }
    }
     function service_order_list(){
      $slug=Sentinel::getUser()->roles()->first()->slug;
      if($slug=='super_admin'){
      $data['service_order_list']=ServiceprovideModel::Orderby('id','DESC')->get();
      }else{
         $data['service_order_list']=ServiceprovideModel::where('add_by',Sentinel::getUser()->id)->get();
      }
      $data['page_title']='All Services Order';
      return view('admin/service_order_list',$data);
    } 
    function change_status_booking(Request $request){
      $id=$request->booking_id;
      $status=$request->status;
       $data=array('service_status'=>$status);
     DB::table('orders')->where('id', $id)->update($data);
    }function delete_booking($id){
      DB::table('orders')->where('id', $id)->delete();
     DB::table('service_order_items')->where('order_id', $id)->delete();
     Session::flash('success', 'Delete Successfully....');
      return Redirect('admin/service_order_list');
    }function view_booking($id){
      $data['category']=Category_model::all();
     $data['booking_details']= DB::table('orders')
     ->where('orders.id',$id)
     ->select('orders.*','users.first_name','users.last_name','users.address','users.phone as number')
     ->leftJoin('users', 'orders.vendor', '=', 'users.id')
     ->first();
     $data['booking_item_details']=DB::table('service_order_items')->where('order_id', $id)->get();
      return view('admin/edit_booking_details',$data);
    }
    function edit_booking($id){
      $data['category']=Category_model::all();
      $data['services']=ServiceModel::all();
      $data['customers']=DB::table('customers')->get();
      $data['vendor']=DB::table('users')
     ->select('users.*','role_users.role_id')
     ->where('role_users.role_id','3')
     ->orderBy('users.id','DESC')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->get();
     $data['booking_details']= DB::table('orders')
     ->where('orders.id',$id)
     ->select('orders.*','users.first_name','users.last_name','users.address','users.phone as number','users.id as vendorid')
     ->leftJoin('users', 'orders.vendor', '=', 'users.id')
     ->first();
     $data['booking_item_details']=DB::table('service_order_items')->where('order_id', $id)->get();
      return view('admin/edit_service_booking_details',$data);
    }
     function update_service_to_vendor(Request $request){
      $id=$request->update_id;
      $customer_name=$request->customer_name;
      $phone=$request->phone;
      $email=$request->email;
      $organization_name=$request->organization_name;
      $organization_address=$request->organization_address;
      $near_by_address=$request->near_by_address;
      $vendor=$request->vendor;
      $note=$request->note;
      $service_id=$request->service_id;
      $category=$request->parent_category;
      $subcategory=$request->subcategory;
      $service_name=$request->service_name;
      $service_price=$request->service_price;
      $total_amount=$request->total_amount;
      $customer_id=$request->customer_id;

      if($customer_name && $phone && $email && $organization_address && $organization_name && $near_by_address && $vendor && $service_id){
       
        $data=array(
          'customer_name'=>$customer_name,
          'customer_id'=>$customer_id,
          'phone'=>$phone,
          'email'=>$email,
          'note'=>$note,
          'organization_name'=>$organization_name,
          'organization_address'=>$organization_address,
          'near_by_address'=>$near_by_address,
          'vendor'=>$vendor,
          'category'=>$category,
          'total_amount'=>$total_amount,
          'created_date'=>date('Y-m-d H:i:s'),
          'updated_date'=>date('Y-m-d H:i:s'),
          'date'=>date('Y-m-d'),
          'service_status'=>'pending',
          'add_by'=>$users=Sentinel::getUser()->id,
        );
       DB::table('orders')->where('id',$id)->update($data);
       DB::table('service_order_items')->where('order_id',$id)->delete();
       foreach ($service_id as $key => $value) {
        $service_list=array(
          'order_id'=>$id,
          'service_id'=>$value,
          'service_name'=>$service_name[$key],
          'service_price'=>$service_price[$key],
        );
        DB::table('service_order_items')->insert($service_list);
       }
     $vendor= $request->vendor;
      $add_by=Sentinel::getUser()->id;
      DB::table('tbl_cart_service')
      ->where('vendor_id', $vendor)
      ->where('add_by', $add_by)
      ->delete();
      Session::flash('success', 'Add Successfully....');
      return Redirect('admin/edit_booking/'.$id);
      }else{

        Session::flash('error', 'All Star fields are required...');
      return Redirect('admin/edit_booking/'.$id);
      }
    }
    function getDistance($addressFrom, $addressTo, $unit = ''){
    // Google API key
    $apiKey = 'AIzaSyCtwxSB6-GcpLvkoKEVEUY3_BY9ct30iqE';
    
    // Change address format
    $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
    $formattedAddrTo     = str_replace(' ', '+', $addressTo);
    
    // Geocoding API request with start address
    $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
    $outputFrom = json_decode($geocodeFrom);
    if(!empty($outputFrom->error_message)){
        return $outputFrom->error_message;
    }
    
    // Geocoding API request with end address
    $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
    $outputTo = json_decode($geocodeTo);
    if(!empty($outputTo->error_message)){
        return $outputTo->error_message;
    }
    
    // Get latitude and longitude from the geodata
    $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
    $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
    $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
    $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
    
    // Calculate distance between latitude and longitude
    $theta    = $longitudeFrom - $longitudeTo;
    $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist    = acos($dist);
    $dist    = rad2deg($dist);
    $miles    = $dist * 60 * 1.1515;
    
    // Convert unit and return distance
    $unit = strtoupper($unit);
    if($unit == "K"){
        return round($miles * 1.609344, 2).' km';
    }elseif($unit == "M"){
        return round($miles * 1609.344, 2).' meters';
    }else{
        return round($miles, 2).' miles';
    }
}
 function get_lat_long($address){
$apiKey = 'AIzaSyCtwxSB6-GcpLvkoKEVEUY3_BY9ct30iqE'; // Google maps now requires an API key.
// Get JSON results from this request
$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey);
$geo = json_decode($geo, true); // Convert the JSON to an array

if (isset($geo['status']) && ($geo['status'] == 'OK')) {
  $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
  $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
  return array('lat'=>$latitude,'long'=>$longitude);
}
 }
  function get_all_address(Request $request){
     $data=DB::table('users')
     ->select('users.*','role_users.role_id')
     ->where('role_users.role_id','3')
     ->orderBy('users.id','DESC')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->get();
     $res=array();
     $parent_id=$request->parent_category;
     $lat_long_array=$this->get_lat_long($request->address);
     $lat=$lat_long_array['lat'];
     $long=$lat_long_array['long'];
     foreach ($data as $key => $value) {
       $dis=$this->getDistance_bylatlong($lat,$long,$value->l_at,$value->l_ong,'K');
       $res[]=array(
        'distance'=>$dis,
        'address'=>$value->address,   
        'name'=>$value->first_name .' '.$value->last_name,   
        'phone'=>$value->phone,   
        'parent_category'=>$value->parent_category,   
        'id'=>$value->id,   
      );
     }
     sort($res);
       foreach ($res as $key => $value) {
        if($parent_id){
        $array=explode(',', $value['parent_category']);
        if(in_array($parent_id, $array)){
        echo "
          <tr><td><input type='radio' name='vendor' class='vendor_check' value='".$value['id']."'></td>
                <td>".$value['name']."</td>
                <td>".$value['phone']."</td>
                <td>".$value['address']."</td>
                <td>".$value['distance']." Km</td>
              </tr>
        ";
        }
      }else{
         echo "
          <tr><td><input type='radio' name='vendor' class='vendor_check' value='".$value['id']."'></td>
                <td>".$value['name']."</td>
                <td>".$value['phone']."</td>
                <td>".$value['address']."</td>
                <td>".$value['distance']." Km</td>
              </tr>
        ";
      }
      }    
  }
  function get_all_vendor_bycategory_address($address,$parent_id){
     $data=DB::table('users')
     ->select('users.*','role_users.role_id')
     ->where('role_users.role_id','3')
     ->orderBy('users.id','DESC')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->get();
     $res=array();
     
     $lat_long_array=$this->get_lat_long($address);
     $lat=$lat_long_array['lat'];
     $long=$lat_long_array['long'];
     foreach ($data as $key => $value) {
       $dis=$this->getDistance_bylatlong($lat,$long,$value->l_at,$value->l_ong,'K');
       $res[]=array(
        'distance'=>$dis,
        'address'=>$value->address,   
        'name'=>$value->first_name .' '.$value->last_name,   
        'phone'=>$value->phone,   
        'parent_category'=>$value->parent_category,   
        'id'=>$value->id,   
      );
     }
     sort($res);
       foreach ($res as $key => $value) {
        if($parent_id){
        $array=explode(',', $value['parent_category']);
        if(in_array($parent_id, $array)){
        echo "
          <tr><td><input type='radio' name='vendor' class='vendor_check' value='".$value['id']."'></td>
                <td>".$value['name']."</td>
                <td>".$value['phone']."</td>
                <td>".$value['address']."</td>
                <td>".$value['distance']." Km</td>
              </tr>
        ";
        }
      }else{
         echo "
          <tr><td><input type='radio' name='vendor' class='vendor_check' value='".$value['id']."'></td>
                <td>".$value['name']."</td>
                <td>".$value['phone']."</td>
                <td>".$value['address']."</td>
                <td>".$value['distance']." Km</td>
              </tr>
        ";
      }
      }    
  }
  function get_vendor_bycategory(Request $request){
      $parent_id= $request->cat;
      $address= $request->near_by_address;
      if($address){
        $this->get_all_vendor_bycategory_address($address,$parent_id);
      }else{
      $vendors=DB::table('users')
     ->select('users.*','role_users.role_id')
     ->where('role_users.role_id','3')
     ->orderBy('users.id','DESC')
     ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
     ->get();

      $data=VendorModel::where('parent_category',$parent_id)->get();

      foreach ($vendors as $key => $value) {
        if($parent_id){
        $array=explode(',', $value->parent_category);
        if(in_array($parent_id, $array)){
        echo "
          <tr>
                <td><input type='radio' name='vendor' class='vendor_check' value='".$value->id."'></td>
                <td>".$value->first_name." ".$value->last_name."</td>
                <td>".$value->phone."</td>
                <td>".$value->address."</td>
                <td>0 Km</td>
              </tr>
        ";
      }
    }else{
      echo "
          <tr>
                <td><input type='radio' name='vendor' class='vendor_check' value='".$value->id."'></td>
                <td>".$value->first_name." ".$value->last_name."</td>
                <td>".$value->phone."</td>
                <td>".$value->address."</td>
                <td>0 Km</td>
              </tr>
        ";
    }
    }
    }
    }

  function getDistance_bylatlong($lat1,$lng1,$lat2,$lng2,$unit = ''){
    
    // Get latitude and longitude from the geodata
    $latitudeFrom    = $lat1;
    $longitudeFrom    = $lng1;
    $latitudeTo        = $lat2;
    $longitudeTo    = $lng2;
    
    // Calculate distance between latitude and longitude
    $theta    = floatval($longitudeFrom) - floatval($longitudeTo);
    $dist    = sin(deg2rad(floatval($latitudeFrom))) * sin(deg2rad(floatval($latitudeTo))) +  cos(deg2rad(floatval($latitudeFrom))) * cos(deg2rad(floatval($latitudeTo))) * cos(deg2rad(floatval($theta)));
    $dist    = acos($dist);
    $dist    = rad2deg($dist);
    $miles    = $dist * 60 * 1.1515;
    
    // Convert unit and return distance
    $unit = strtoupper($unit);
    if($unit == "K"){
        return round($miles * 1.609344, 2);
    }elseif($unit == "M"){
        return round($miles * 1609.344, 2).' meters';
    }else{
        return round($miles, 2).' miles';
    }
  }
   function get_address_by_address(Request $request){
 $address=   $request->address;
$apiKey = 'AIzaSyCtwxSB6-GcpLvkoKEVEUY3_BY9ct30iqE'; // Google maps now requires an API key.
// Get JSON results from this request
$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey);
$geo = json_decode($geo, true); // Convert the JSON to an array

if (isset($geo['status']) && ($geo['status'] == 'OK')) {
  $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
  $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
  /*echo $latitude; echo "<br>";
  echo $longitude; */
  $address= $geo['results'][0]['formatted_address'];
  echo "<li> <a href='javaScript:void(0)' class='append_address'>".$address."</a></li>";
  
}
   }
}
