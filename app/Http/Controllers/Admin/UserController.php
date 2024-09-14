<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\Helper;
use DB;
use Validator;
use Sentinel;
use Session;
use Activation;
use App\User;
use App\Models\Country;
use App\Models\Delivery;
use App\Models\State;
use App\Models\City;
use Carbon\Carbon;
use Crypt;


class UserController extends Controller
{
  function index()
  {

    $role = Sentinel::findRoleBySlug('customer');

    $users = $role->users()->get();
    $data['customers'] = $users;
    $data['page_title'] = 'Customer List';
    return view('admin/customers/index', $data);
  }

  function sent_sms($number, $message)
  {
    // Account details
    $apiKey = urlencode('SYnYjh4fZ58-is7IBxkmj78JUvS1z7jWlIeQxveth3');
    // Message details
    $numbers = array($number);
    $sender = urlencode('TXTLCL');
    $message = rawurlencode($message);

    $numbers = implode(',', $numbers);

    // Prepare data for POST request
    $data = array('apikey' => $apiKey, 'numbers' => $numbers, 'sender' => $sender, 'message' => $message);
    // Send the POST request with cURL
    $ch = curl_init('https://api.textlocal.in/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    // Process your response here
    return $response;
    //print_r($response);
  }
  function sellers()
  {
    $role = Sentinel::findRoleBySlug('seller');

    $users = $role->users()->get();
    $data['customers'] = $users;
    $data['page_title'] = 'Buyer List';
    return view('admin/customers/index', $data);
  }

  function deliveryboy()
  {

    $role = Sentinel::findRoleBySlug('deliveryboy');

    $users = $role->users()->get();
    $data['customers'] = $users;
    $data['page_title'] = 'Delivery Boy List';
    return view('admin/customers/index', $data);
  }

  public function deliveryboy_active($id)
  {


    $chkUser = DB::table('users')->where('id', $id)->first();

    if (!empty($chkUser)) {

      $phone = $chkUser->phone;

      $passwordSend = DB::table('password_save')->where('user_id', $id)->first();


      if ($chkUser->status == 0) {

        DB::table('users')->where('id', $id)->update([
          "status" => 1
        ]);

        Session::flash('success', 'Delivery Boy activate Successfully...');
      } else {

        DB::table('users')->where('id', $id)->update([
          "status" => 0
        ]);

        //   $apk = DB::table('apk_table')->first();

        //   $download_url = '{{url("uploads/apk_file")}}/'.$apk->apk_file; 

        //   $link ='<a href="{{url("uploads/apk_file")}}/'.$apk->apk_file.'">Download apk</a>';
        //   $message='Hi '.$chkUser->first_name.' Congrats! Now you are our delivery partner, you can download our delivery partner app.<br>
        //   App link: '.$link.' <br>
        //   Email id: '.$chkUser->email.'<br>
        //   Password: '.$passwordSend->password.' <br>
        //   <b>Regard: anbshopping</b>
        //   ';
        //   $message1='Hi '.$chkUser->first_name.' Congrats! Now you are our delivery partner, you can download our delivery partner app.
        //   App link: '.$download_url.'
        //   Email id: '.$chkUser->email.'
        //   Password: '.$passwordSend->password.' 
        //   Regard: anbshopping
        //   ';
        //   $subject='anbshopping Deliveryboy Status';
        //   $this->sent_mail($chkUser->email,$subject,$message);
        //   $this->sent_sms($phone,$message1);

        Session::flash('success', 'Delivery Boy deactivate Successfully...');
      }

      return back();
    }
  }

  public function create()
  {

    $state = array('' => "Select country");
    $city = array('' => "Select state");
    $countries = array('' => "Select country");
    $country = Country::all();
    foreach ($country as $cun) {
      $countries[$cun->id] = $cun->name;
    }
    $data['cities'] = $city;
    $data['states'] = $state;
    $data['countries'] = $countries;
    $data['page_title'] = 'Add Customer';


    return view('admin/customers/add', $data);
  }

  public function edit($id)
  {

    $user = User::where("id", $id)->first();
    $state = array();
    $city = array();
    $states = State::where("country_id", $user->country)->select("id", "name")->get();
    $cities = City::where("state_id", $user->state)->select("id", "name")->get();
    foreach ($states as $st) {
      $state[$st->id] = $st->name;
    }

    foreach ($cities as $ct) {
      $city[$ct->id] = $ct->name;
    }
    $countries = array('' => "Select country");
    $country = Country::all();
    foreach ($country as $cun) {
      $countries[$cun->id] = $cun->name;
    }

    $data['user'] = $user;
    $data['cities'] = $city;
    $data['states'] = $state;
    $data['countries'] = $countries;
    $data['roles'] = DB::table('role_users')->where('user_id', $id)->first();
    $data['user_details'] = DB::table('vendor_details')->where('vendor_id', $id)->first();

    // dd($data);

    $data['page_title'] = 'Edit Customer';
    return view('admin/customers/edit', $data);
  }

  function base_url()
  {
    $base_url = $_SERVER["DOCUMENT_ROOT"];
    return $base_url;
  }


  public function add_delivery_boy()
  {

    $country = Country::all();
    $data['countries'] = $country;

    return view('add_delivery_boy', $data);
  }


  function get_state_by_country(Request $request)
  {

    $states = State::where('country_id', $request->id)->get();

    return $states;
  }

  function get_city_by_state(Request $request)
  {

    $city = City::where('state_id', $request->id)->get();

    return $city;
  }




  public function store(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email' => 'required|unique:users|email',
      'password' => 'required',
      'first_name' => 'required'
    ]);

    if ($validator->fails()):
      return redirect()->with('error', 'Some Field are missing...')->back();
    endif;

    $pin = mt_rand(100000, 999999);
    $auth_key = 'Bearer ' . bin2hex(random_bytes(300));

    $image_name = '';
    if ($request->hasFile('image')) {

      $image = $request->file('image');
      $image_name = time() . '.' . $image->getClientOriginalExtension();
      $destinationPath = $this->base_url() . '/uploads/users/';
      $image->move($destinationPath, $image_name);
    }

    $from = array();
    if (isset($request->from) && !empty($request->from)) {
      $from = $request->from;
      $request->except(['from']);
    }

    // $user = Sentinel::registerAndActivate($request->all()); 
    $user = Sentinel::registerAndActivate([
      "first_name" => $request->first_name,
      "last_name" => $request->last_name,
      "email" => $request->email,
      "password" => $request->password
    ]);

    //$commission = isset($request->commission) ? $request->commission : 0;

    $data = array(
      "first_name" => $request->first_name,
      "last_name" => $request->last_name,
      "email" => $request->email,
      "phone" => $request->phone,
      "auth_key" => $auth_key,
      "line_1" => $request->line_1,
      "line_2" => $request->line_2,
      "country" => $request->country,
      "state" => $request->state,
      "city" => $request->city,
      "zip_code" => $request->zip_code,
      "lat" => $request->lat,
      "long" => $request->long,
      "term_and_condition" => 1,
      "status" => 0,
      "pin" => md5($pin),
    );


    User::where("id", $user->id)->update($data);

    DB::table('password_save')->insert([

      "email" => $request->email,
      "password" => $request->password,
      "user_id" => $user->id

    ]);

    $role = Sentinel::findRoleById($request->user_type);
    $users_check = $role->users()->attach($user);

    $change_usertypedata = array('role_id' => $request->user_type);
    $res = DB::table('role_users')->where('user_id', $request->id)->update($change_usertypedata);

    if ($request->user_type == '7') {

      $cencel_check = '';
      if ($request->hasFile('cencel_check')) {
        $image = $request->file('cencel_check');
        $cencel_check = time() . '.cc' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $cencel_check);
      }

      $addhar_card = '';
      if ($request->hasFile('addhar_card')) {
        $image = $request->file('addhar_card');
        $addhar_card = time() . '.ac' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $addhar_card);
      }

      $pan_card = '';
      if ($request->hasFile('pan_card')) {

        $image = $request->file('pan_card');
        $pan_card = time() . '.pc' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $pan_card);
      }

      $passbook = '';
      if ($request->hasFile('passbook')) {
        $image = $request->file('passbook');
        $passbook = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $passbook);
      }

      $rc_document = '';
      if ($request->hasFile('rc_document')) {
        $image = $request->file('rc_document');
        $rc_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $rc_document);
      }

      $driving_licence = '';
      if ($request->hasFile('driving_licence')) {
        $image = $request->file('driving_licence');
        $driving_licence = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $driving_licence);
      }

      $bike_image = '';
      if ($request->hasFile('bike_image')) {
        $image = $request->file('bike_image');
        $bike_image = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $bike_image);
      }

      $police_verification_document = '';
      if ($request->hasFile('police_verification_document')) {
        $image = $request->file('police_verification_document');
        $police_verification_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $police_verification_document);
      }

      $house_electric_document = '';
      if ($request->hasFile('house_electric_document')) {
        $image = $request->file('house_electric_document');
        $house_electric_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $house_electric_document);
      }

      $house_photo = '';
      if ($request->hasFile('house_photo')) {
        $image = $request->file('house_photo');
        $house_photo = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $house_photo);
      }


      DB::table('vendor_details')->insert([

        "vendor_id" => $user->id,
        "holder_name" => $request->holder_name,
        "account_no" => $request->account_no,
        "ifsc_code" => $request->ifsc_code,
        "bank_name" => $request->bank_name,
        "branch_name" => $request->branch_name,
        "father_name" => $request->father_name,
        "single" => $request->single,
        "adhar_no" => $request->adhar_no,
        "driving_licance_no" => $request->driving_licance_no,
        "rc_insurance_no" => $request->rc_insurance_no,
        "wheeler" => $request->wheeler,
        "refrance_name" => $request->refrance_name,
        "refrance_no" => $request->refrance_no,
        "police_verification_document" => $police_verification_document,
        "house_electric_document" => $house_electric_document,
        "house_photo" => $house_photo,
        "cencel_check" => $cencel_check,
        "addhar_card" => $addhar_card,
        "pan_card" => $pan_card,
        "passbook" => $passbook,
        "rc_document" => $rc_document,
        "driving_licence" => $driving_licence,
        "bike_image" => $bike_image
      ]);
    }



    if ($request->user_type == '6' || $request->user_type == '7') {

      $phone = $request->phone;
      $message = '';
      if ($request->user_type == '6') {
        $message = ' Bulk Order Request Status: ' . $request->first_name . '  Congrats! You are now a premium member!, Now you can purchase bulk order. Regards:ANB';
        $this->sent_sms($phone, $message);
      }

      if ($request->user_type == '77') {

        $link = '<a href="https://anbshopping.com/app-debug.apk">Download apk</a>';
        $message = 'Hi ' . $request->first_name . ' Congrats! Now you are our delivery partner, you can download our delivery partner app.<br>
          App link: ' . $link . ' <br>
          Email id: ' . $request->email . '<br>
          Password: ' . $request->password . ' <br>
          <b>Regard: anbshopping</b>
          ';
        $message1 = 'Hi ' . $request->first_name . ' Congrats! Now you are our delivery partner, you can download our delivery partner app.
          App link: https://anbshopping.com/app-debug.apk
          Email id: ' . $request->email . '
          Password: ' . $request->password . ' 
          Regard: anbshopping
          ';
        $subject = 'anbshopping Deliveryboy Status';
        $this->sent_mail($request->email, $subject, $message);
        $this->sent_sms($phone, $message1);

        Session::flash('success', 'Delivery Boy Created Successfully...');
        return Redirect('admin/customers/deliveryboy');
      }
    }

    if (!empty($from)) {
      Session::flash('user_id', $user->id);
      if ($from == 'front') {
        Session::flash('success', 'Account Created Successfully...');
      }
      return redirect()->back();
    } else {
      Session::flash('success', 'Customer Created Successfully...');
      return Redirect('admin/customers');
    }
  }

  public function store_fornt(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email' => 'required|unique:users|email',
      'password' => 'required',
      'first_name' => 'required'
    ]);

    if ($validator->fails()):
      return redirect()->with('error', 'Some Field are missing...')->back();
    endif;

    $pin = mt_rand(100000, 999999);
    $auth_key = 'Bearer ' . bin2hex(random_bytes(300));

    $image_name = '';
    if ($request->hasFile('image')) {

      $image = $request->file('image');
      $image_name = time() . '.' . $image->getClientOriginalExtension();
      $destinationPath = $this->base_url() . '/uploads/users/';
      $image->move($destinationPath, $image_name);
    }

    $from = array();
    if (isset($request->from) && !empty($request->from)) {
      $from = $request->from;
      $request->except(['from']);
    }

    // $user = Sentinel::registerAndActivate($request->all()); 
    $user = Sentinel::registerAndActivate([
      "first_name" => $request->first_name,
      "last_name" => $request->last_name,
      "email" => $request->email,
      "password" => $request->password
    ]);

    //$commission = isset($request->commission) ? $request->commission : 0;

    $data = array(
      "first_name" => $request->first_name,
      "last_name" => $request->last_name,
      "email" => $request->email,
      "phone" => $request->phone,
      "auth_key" => $auth_key,
      "line_1" => $request->line_1,
      "line_2" => $request->line_2,
      "country" => $request->country,
      "state" => $request->state,
      "city" => $request->city,
      "zip_code" => $request->zip_code,
      "lat" => $request->lat,
      "long" => $request->long,
      "term_and_condition" => 1,
      "pin" => md5($pin),
      "status" => 0,
    );


    User::where("id", $user->id)->update($data);

    $role = Sentinel::findRoleById($request->user_type);
    $users_check = $role->users()->attach($user);

    $change_usertypedata = array('role_id' => $request->user_type);
    $res = DB::table('role_users')->where('user_id', $request->id)->update($change_usertypedata);


    if ($request->user_type == '7') {

      $cencel_check = '';
      if ($request->hasFile('cencel_check')) {
        $image = $request->file('cencel_check');
        $cencel_check = time() . '.cc' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $cencel_check);
      }

      $addhar_card = '';
      if ($request->hasFile('addhar_card')) {
        $image = $request->file('addhar_card');
        $addhar_card = time() . '.ac' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $addhar_card);
      }

      $pan_card = '';
      if ($request->hasFile('pan_card')) {

        $image = $request->file('pan_card');
        $pan_card = time() . '.pc' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $pan_card);
      }

      $passbook = '';
      if ($request->hasFile('passbook')) {
        $image = $request->file('passbook');
        $passbook = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $passbook);
      }

      $rc_document = '';
      if ($request->hasFile('rc_document')) {
        $image = $request->file('rc_document');
        $rc_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $rc_document);
      }

      $driving_licence = '';
      if ($request->hasFile('driving_licence')) {
        $image = $request->file('driving_licence');
        $driving_licence = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $driving_licence);
      }

      $bike_image = '';
      if ($request->hasFile('bike_image')) {
        $image = $request->file('bike_image');
        $bike_image = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $bike_image);
      }

      $police_verification_document = '';
      if ($request->hasFile('police_verification_document')) {
        $image = $request->file('police_verification_document');
        $police_verification_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $police_verification_document);
      }

      $house_electric_document = '';
      if ($request->hasFile('house_electric_document')) {
        $image = $request->file('house_electric_document');
        $house_electric_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $house_electric_document);
      }

      $house_photo = '';
      if ($request->hasFile('house_photo')) {
        $image = $request->file('house_photo');
        $house_photo = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $house_photo);
      }


      DB::table('vendor_details')->insert([

        "vendor_id" => $user->id,
        "holder_name" => $request->holder_name,
        "account_no" => $request->account_no,
        "ifsc_code" => $request->ifsc_code,
        "bank_name" => $request->bank_name,
        "branch_name" => $request->branch_name,
        "father_name" => $request->father_name,
        "single" => $request->single,
        "adhar_no" => $request->adhar_no,
        "driving_licance_no" => $request->driving_licance_no,
        "rc_insurance_no" => $request->rc_insurance_no,
        "wheeler" => $request->wheeler,
        "refrance_name" => $request->refrance_name,
        "refrance_no" => $request->refrance_no,
        "police_verification_document" => $police_verification_document,
        "house_electric_document" => $house_electric_document,
        "house_photo" => $house_photo,
        "cencel_check" => $cencel_check,
        "addhar_card" => $addhar_card,
        "pan_card" => $pan_card,
        "passbook" => $passbook,
        "rc_document" => $rc_document,
        "driving_licence" => $driving_licence,
        "bike_image" => $bike_image
      ]);

      DB::table('password_save')->insert([

        "email" => $request->email,
        "password" => $request->password,
        "user_id" => $user->id
      ]);
    }



    if ($request->user_type == '6' || $request->user_type == '7') {

      $phone = $request->phone;
      $message = '';
      if ($request->user_type == '6') {
        $message = ' Bulk Order Request Status: ' . $request->first_name . '  Congrats! You are now a premium member!, Now you can purchase bulk order. Regards:ANB';
        $this->sent_sms($phone, $message);
      }

      if ($request->user_type == '77') {

        $link = '<a href="https://anbshopping.com/app-debug.apk">Download apk</a>';
        $message = 'Hi ' . $request->first_name . ' Congrats! Now you are our delivery partner, you can download our delivery partner app.<br>
          App link: ' . $link . ' <br>
          Email id: ' . $request->email . '<br>
          Password: ' . $request->password . ' <br>
          <b>Regard: anbshopping</b>
          ';
        $message1 = 'Hi ' . $request->first_name . ' Congrats! Now you are our delivery partner, you can download our delivery partner app.
          App link: https://anbshopping.com/app-debug.apk
          Email id: ' . $request->email . '
          Password: ' . $request->password . ' 
          Regard: anbshopping
          ';
        $subject = 'anbshopping Deliveryboy Status';
        $this->sent_mail($request->email, $subject, $message);
        $this->sent_sms($phone, $message1);

        Session::flash('success', 'Delivery Boy Created Successfully...');
        return redirect()->back();
      }

      Session::flash('success', 'You account created Successfully. ');
      return redirect()->back();
    }

    if (!empty($from)) {
      Session::flash('user_id', $user->id);
      if ($from == 'front') {
        Session::flash('success', 'Account Created Successfully...');
      }
      return redirect()->back();
    } else {
      Session::flash('success', 'Customer Created Successfully...');
      return Redirect('admin/customers');
    }
  }



  public function delivery_boy_update(Request $request)
  {


    //dd($request->all());

    $image_name = $request->old_image;
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $image_name = time() . '.' . $image->getClientOriginalExtension();
      $destinationPath = $this->base_url() . '/uploads/users/';
      $image->move($destinationPath, $image_name);
    }

    //$commission = isset($request->commission) ? $request->commission : 0;

    $data = array(
      "first_name" => $request->first_name,
      "last_name" => $request->last_name,
      //"commission"=>$commission,
      //"email" => $request->email,
      "profile_image" => $image_name,
      "phone" => $request->phone,
      "line_1" => $request->line_1,
      "line_2" => $request->line_2,
      "country" => $request->country,
      "state" => $request->state,
      "city" => $request->city,
      "zip_code" => $request->zip_code
    );

    //$role = Sentinel::findRoleBySlug('vendor');
    User::where("id", $request->id)->update($data);
    // $res = $role->users()->attach($user);

    $change_usertypedata = array('role_id' => $request->user_type);
    $res = DB::table('role_users')->where('user_id', $request->id)->update($change_usertypedata);

    if ($request->user_type == '7') {

      $cencel_check = $request->old_cencel_check;
      if ($request->hasFile('cencel_check')) {
        $image = $request->file('cencel_check');
        $cencel_check = time() . '.cc' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $cencel_check);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_cencel_check;
        unlink($unlink);
      }

      $addhar_card = $request->old_addhar_card;
      if ($request->hasFile('addhar_card')) {
        $image = $request->file('addhar_card');
        $addhar_card = time() . '.ac' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $addhar_card);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_addhar_card;
        unlink($unlink);
      }

      $pan_card = $request->old_pan_card;
      if ($request->hasFile('pan_card')) {

        $image = $request->file('pan_card');
        $pan_card = time() . '.pc' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $pan_card);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_pan_card;
        unlink($unlink);
      }

      $passbook = $request->old_passbook;
      if ($request->hasFile('passbook')) {
        $image = $request->file('passbook');
        $passbook = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $passbook);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_passbook;
        unlink($unlink);
      }

      $rc_document = $request->old_rc_document;
      if ($request->hasFile('rc_document')) {
        $image = $request->file('rc_document');
        $rc_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $rc_document);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_rc_document;
        unlink($unlink);
      }

      $driving_licence = $request->old_driving_licence;
      if ($request->hasFile('driving_licence')) {
        $image = $request->file('driving_licence');
        $driving_licence = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $driving_licence);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_driving_licence;
        unlink($unlink);
      }

      $bike_image = $request->old_bike_image;
      if ($request->hasFile('bike_image')) {
        $image = $request->file('bike_image');
        $bike_image = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $bike_image);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_bike_image;
        unlink($unlink);
      }

      $police_verification_document = $request->old_police_verification_document;
      if ($request->hasFile('police_verification_document')) {
        $image = $request->file('police_verification_document');
        $police_verification_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $police_verification_document);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_police_verification_document;
        unlink($unlink);
      }

      $house_electric_document = $request->old_house_electric_document;
      if ($request->hasFile('house_electric_document')) {
        $image = $request->file('house_electric_document');
        $house_electric_document = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $house_electric_document);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_house_electric_document;
        unlink($unlink);
      }

      $house_photo = $request->old_house_photo;
      if ($request->hasFile('house_photo')) {
        $image = $request->file('house_photo');
        $house_photo = time() . '.pb' . $image->getClientOriginalExtension();
        $destinationPath = $this->base_url() . '/uploads/users/document/';
        $image->move($destinationPath, $house_photo);

        $unlink = $this->base_url() . '/uploads/users/document/' . $request->old_house_photo;
        unlink($unlink);
      }


      $proData = [
        "vendor_id" => $request->id,
        "holder_name" => $request->holder_name,
        "account_no" => $request->account_no,
        "ifsc_code" => $request->ifsc_code,
        "bank_name" => $request->bank_name,
        "branch_name" => $request->branch_name,
        "cencel_check" => $cencel_check,
        "addhar_card" => $addhar_card,
        "pan_card" => $pan_card,
        "passbook" => $passbook,
        "father_name" => $request->father_name,
        "single" => $request->single,
        "adhar_no" => $request->adhar_no,
        "driving_licance_no" => $request->driving_licance_no,
        "rc_insurance_no" => $request->rc_insurance_no,
        "wheeler" => $request->wheeler,
        "refrance_name" => $request->refrance_name,
        "refrance_no" => $request->refrance_no,
        "rc_document" => $rc_document,
        "driving_licence" => $driving_licence,
        "bike_image" => $bike_image,
        "police_verification_document" => $police_verification_document,
        "house_electric_document" => $house_electric_document,
        "house_photo" => $house_photo
      ];

      if ($request->profile_id == '') {
        DB::table('vendor_details')->insert($proData);
      } else {
        DB::table('vendor_details')->where('id', $request->profile_id)->update($proData);
      }
    }

    Session::flash('success', ' updated Successfully...');
    return back();
  }



  public function update(Request $request)
  {
    if ($request->user_type == '6') {
      $ms = 'Premium Member';
      $data = array(
        "first_name" => $request->first_name,
        "last_name" => $request->last_name,
        "email" => $request->email,
        "phone" => $request->phone,
        "line_1" => $request->line_1,
        "line_2" => $request->line_2,
        "country" => $request->country,
        "state" => $request->state,
        "city" => $request->city,
        "messge_for_seller" => $ms,
        "lat" => $request->lat,
        "long" => $request->long,
        "zip_code" => $request->zip_code,
      );
    } else {

      $data = array(
        "first_name" => $request->first_name,
        "last_name" => $request->last_name,
        "email" => $request->email,
        "phone" => $request->phone,
        "line_1" => $request->line_1,
        "line_2" => $request->line_2,
        "country" => $request->country,
        "state" => $request->state,
        "city" => $request->city,
        "lat" => $request->lat,
        "long" => $request->long,
        "zip_code" => $request->zip_code,
      );
    }

    $change_usertypedata = array('role_id' => $request->user_type);

    $res = DB::table('role_users')->where('user_id', $request->id)->update($change_usertypedata);

    $image_name = '';
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $image_name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = $this->base_url() . '/uploads/users/';
      $image->move($destinationPath, $image_name);
      $data['profile_image'] = $image_name;
    }

    if (!empty($request->password)) {
      $user = Sentinel::findById($request->id);

      $credentials = [
        'password' => $request->password,
      ];

      Sentinel::update($user, $credentials);
    }


    $user = User::where("id", $request->id)->update($data);
    if ($request->user_type == '6' || $request->user_type == '7') {
      $phone = $request->phone;
      $message = '';
      if ($request->user_type == '6') {
        $message = ' Bulk Order Request Status: ' . $request->first_name . '  Congrats! You are now a premium member!, Now you can purchase bulk order. Regards:anbshopping';
        $this->sent_sms($phone, $message);
      }
      if ($request->user_type == '7') {
        $link = '<a href="https://anbshopping.com/app-debug.apk">Download apk</a>';
        $message = 'Hi ' . $request->first_name . ' Congrats! Now you are our delivery partner, you can download our delivery partner app.<br>
      App link: ' . $link . ' <br>
      Email id: ' . $request->email . '<br>
      Password: ' . $request->password . ' <br>
      <b>Regard: anbshopping</b>
      ';
        $message1 = 'Hi ' . $request->first_name . ' Congrats! Now you are our delivery partner, you can download our delivery partner app.
      App link: https://anbshopping.com/app-debug.apk
      Email id: ' . $request->email . '
      Password: ' . $request->password . ' 
      Regard: anbshopping
      ';
        $subject = 'anbshopping Deliveryboy Status';
        $this->sent_mail($request->email, $subject, $message);
        $this->sent_sms($phone, $message1);
      }
    }
    Session::flash('success', 'Customer updated Successfully...');
    return Redirect('admin/customers/edit/' . $request->id);
  }


  public function getCustomers(Request $request)
  {

    if (isset($request->term)) {
      $term = $request->term;
      $page = $request->page - 1;

      $rows['results'] = User::where("first_name", "LIKE", "%{$term}%")->orWhere("last_name", "LIKE", "%{$term}%")->orWhere("email", "LIKE", "%{$term}%")->orWhere("phone", "LIKE", "%{$term}%")->offset($page)->limit($request->limit)->select(DB::raw("id,concat(first_name,' ',last_name) as text"))->get();

      $count = count(User::where("first_name", "LIKE", "%{$term}%")->orWhere("last_name", "LIKE", "%{$term}%")->orWhere("email", "LIKE", "%{$term}%")->orWhere("phone", "LIKE", "%{$term}%")->get());
      $rows['total_count'] = $count;
      $rows['incomplete_results'] = $count > 0 ? true : false;
    } else {
      // dd( $request);
      $rows = User::where("id", $request->customer_id)->select(DB::raw("id,concat(first_name,' ',last_name) as text"))->get();
    }

    return response()->json($rows);
  }


  public function updatePawword(Request $request)
  {
    //dd($request);
    $user = Sentinel::getUser();;

    $credentials = [
      'password' => $request->password,
    ];

    $user = Sentinel::update($user, $credentials);
    Session::flash('success', 'Password updated successfully...');
    return Redirect('account');
  }


  public function resetPawword(Request $request)
  {
    $user = User::whereEmail($request->user_email)->first();
    $email = $request->user_email;
    $reminder = Reminder::exists($user) ?: Reminder::create($user);
    $link = url('/password-reset/' . $user->email . '/' . $reminder->code);

    $data = array('link' => $link);

    $headers = "From: test@marketingchord.com\r\n";
    $headers .= "Reply-To: test@marketingchord.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    mail($user->email, 'Reset Your Password', $link, $headers);


    Session::flash('success', 'Reset Link sent to your mail...');
    return Redirect('login');
  }

  public function password_reset($email, $resetCode)
  {
    $user = User::byEmail($email);
    if (empty($user)) {
      Session::flash('error', 'Invalid Reset request...');
      return redirect('login');
    }

    if ($reminder = Reminder::exists($user)) {
      if ($resetCode == $reminder->code) {
        $data['title'] = 'Reset Password';
        $data['email'] = $email;
        $data['resetCode'] = $resetCode;
        return view($this->theme('reset-password'), $data);
      } else {
        Session::flash('error', 'Invalid Reset request...');
        return redirect('login');
      }
    } else {
      Session::flash('error', 'Invalid Reset request...');
      return redirect('login');
    }
  }

  public function updatePassword(Request $request, $email, $resetCode)
  {
    $user = User::byEmail($email);
    //  dd($user);
    if (empty($user)) {
      Session::flash('error', 'Invalid Reset request...');
      return redirect('login');
    }

    if ($reminder = Reminder::exists($user)) {
      if ($resetCode == $reminder->code) {
        if (Reminder::complete($user, $resetCode, $request->password)) {
          Session::flash('success', 'Password change Successfully...');
          return redirect('login');
        } else {
          Session::flash('error', 'Error in Password change...');
          return redirect('login');
        }
      } else {
        Session::flash('error', 'Invalid Reset request...');
        return redirect('login');
      }
    }
  }
  public function destroy($id)
  {
    DB::table('users')->where('id', $id)->delete();
    DB::table('carts')->where('user_id', $id)->delete();
    Session::flash('success', 'Delete Successfully...');
    return redirect('admin/customers');
  }


  function assign_delivery_boy(Request $request)
  {

    $arrays = $request->arrays;
    $deliveryboy = $request->deliveryboy;

    foreach ($arrays as $orderid) {

      $check = DB::table('deliveries')->where('order_id', $orderid)->first();
      $check_order = DB::table('deliveries')->where('order_id', $orderid)->whereIn('status', ['cancel', 'reject'])->first();
      /*if($check){
                   $insert_data=array(
              'order_id'=>$orderid,
              'user_id'=>$deliveryboy,
              'is_closed'=>'1',
              'updated_at'=>date('Y-m-d H:i:s'),
              );
                   DB::table('deliveries')->where('user_id',$deliveryboy)->where('order_id',$orderid)->update($insert_data);
              }*/
      if ($check) {
        if ($check->status == 'cancel' || $check->status == 'reject') {

          $insert_data = array(
            'order_id' => $orderid,
            'user_id' => $deliveryboy,
            'status' => 'pending',
            'is_closed' => '1',
            'created_at' =>    date('Y-m-d H:i:s'),
            'updated_at' =>    date('Y-m-d H:i:s'),
          );
          DB::table('deliveries')->insert($insert_data);
        } else if ($check->status == 'pending') {
          $insert_data = array(
            'order_id' => $orderid,
            'user_id' => $deliveryboy,
            'status' => 'pending',
            'is_closed' => '1',
            'updated_at' => date('Y-m-d H:i:s'),
          );
          DB::table('deliveries')->where('order_id', $orderid)->update($insert_data);
        }
      } else {
        $insert_data = array(
          'order_id' => $orderid,
          'user_id' => $deliveryboy,
          'status' => 'pending',
          'is_closed' => '1',
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s'),
        );
        DB::table('deliveries')->insert($insert_data);
      }
    }


    $userCheck = DB::table('users')->where('id', $deliveryboy)->first();

    if ($userCheck) {
      $fcmToken = $userCheck->fcmToken;
    } else {
      $fcmToken = "cjLQ_mopQ6Gb6P0Mq501SI:APA91bFcQdSjtHitdbr6IH0lTTz9V1Rc557Zo5F_nJK5sdQ7ZEA9gMRx5kKte0ydwzJDwyFbGDio3lg9pdriBCvZkN5klTXExdFrDMu4mt_2v81llzyupYeVdiTV54RJlBKhTnLESU8U";
    }

    $userid = $deliveryboy;
    $user_name = 'Anb shopping';
    $title = 'assign order';
    $message = 'Assign you order..';
    $type = 'assign_order';

    $check = Helper::notification($userid, $user_name, $title, $message, $type, $fcmToken);

    // dd($check);
    echo "success";
  }


  function sent_mail($email = '', $subject = '', $msg = '')
  {
    $settings = $this->get_settings('site_settings');
    $setting_email = json_decode($settings->value)->other_email;

    $headers = "From: " . $setting_email . "\r\n";
    $headers .= "Reply-To: " . $setting_email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    mail($email, $subject, $msg, $headers);
  }


  function get_order_deliveryboy(Request $request)
  {

    $order_id = $request->order_id;
    $get_data = DB::table('deliveries')
      ->select('deliveries.*', 'users.first_name', 'users.last_name', 'users.id as user_id')
      ->where('deliveries.order_id', $order_id)
      ->leftJoin('users', 'deliveries.user_id', '=', 'users.id')
      ->get();
    //echo "<pr>";
    //print_r($get_data); die;
    foreach ($get_data as $value) {
      if ($value->status == 'pending') {
        $pending = 'selected';
      } else {
        $pending = '';
      }
      if ($value->status == 'accept') {
        $Accept = 'selected';
      } else {
        $Accept = '';
      }
      if ($value->status == 'started') {
        $Started = 'selected';
      } else {
        $Started = '';
      }
      if ($value->status == 'reached') {
        $Reached = 'selected';
      } else {
        $Reached = '';
      }
      if ($value->status == 'reject') {
        $Reject = 'selected';
      } else {
        $Reject = '';
      }
      if ($value->status == 'complete') {
        $Complete = 'selected';
      } else {
        $Complete = '';
      }
      if ($value->status == 'cancel') {
        $Cancel = 'selected';
      } else {
        $Cancel = '';
      }
      $option = '
           <select class="change_status_delivery" data-id="' . $value->id . '" data-userid="' . $value->user_id . '">
            <option ' . @$pending . ' value="pending">Pending</option>
            <option ' . @$Accept . ' value="accept">Accept</option>
            <option ' . @$Started . ' value="started">Started</option>
            <option ' . @$Reached . ' value="reached">Reached</option>
            <option ' . @$Reject . ' value="reject">Reject</option>
            <option ' . @$Complete . ' value="complete">Complete</option>
            <option ' . @$Cancel . ' value="cancel">Cancel</option>
           </select>
           ';
      echo "<tr>
           <td>" . $value->first_name . " " . $value->last_name . "</td>
           <td>" . $value->updated_at . "</td>
           <td>" . $option . "</td>
           </tr>
           ";
    }
  }
  function get_settings($type)
  {
    return DB::table('settings')->where('type', $type)->first();
  }
  public function updatedeliveryStatus(Request $request)
  {
    $delivery_id = $request->id;
    $status = $request->status;
    $user_id = $request->user_id;

    $results = DB::table('deliveries')->where('user_id', $user_id)->where('id', $delivery_id)->first();
    if (!empty($results)) {

      Delivery::where("id", $delivery_id)->update(['updated_at' => date("Y-m-d H:i:s"), 'status' => $status]);
      if ($status == 'accept') {
        DB::table('orders')->where('id', $results->order_id)->update(['status' => 'processing']);
      }
      if ($status == 'started') {
        DB::table('orders')->where('id', $results->order_id)->update(['status' => 'ordered']);
      }
      if ($status == 'reached') {
        DB::table('orders')->where('id', $results->order_id)->update(['status' => 'shipped']);
      }
      if ($status == 'reject') {
        DB::table('orders')->where('id', $results->order_id)->update(['status' => 'pending']);
      }
      if ($status == 'complete') {
        DB::table('orders')->where('id', $results->order_id)->update(['status' => 'delivered', 'payment_status' => 'success']);
      }
      if ($status == 'cancel') {
        DB::table('orders')->where('id', $results->order_id)->update(['status' => 'pending']);
      }

      /*$order_details = DB::table('orders')->where('id',$results->order_id)->first();
           if($order_details){
             $user_details= DB::table('users')->where('id',$order_details->user_id)->first();
             $phone=$user_details->phone;
             $message='Himveg: Order:'.$order_details->order_id.', Your Order has been '.$data["status"].'';
             $this->sent_sms($phone,$message);
            }*/
      echo "success";
    }
  }
}
