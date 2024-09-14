<?php



namespace App\Http\Controllers;



use Sentinel;

use App\Models\Order;

use App\Models\OrderItems;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Category_model as Category_model;

class OrderController extends Controller

{

    public function __construct() {}

    public function cart_remove(Request $request, $remove_id)
    {

        $cart = Session::has('carts') ? Session::get('carts') : null;
        unset($cart[$remove_id]);
        Session::put('carts', $cart);
        return back();
    }

    public function qty_update(Request $request)
    {

        $cart = Session::has('carts') ? Session::get('carts') : null;
        $cart[$request->item_id]['quantity'] = $request->qty;
        Session::put('carts', $cart);
        return $cart;
    }



    function order_place_buynow(Request $request)
    {

        // dd($request->all());

        if ($request->new_address == 'old_address') {

            $request->validate([
                'delivery_address' => 'required'
            ]);
        } else {

            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'pincode' => 'required',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required',
            ]);
        }

        $userid = Auth::user()->id;

        $payment_method = $request->payment_method;
        $grand_total = $request->grand_total;
        $gst_price = $request->gst_price;
        $base_amount_value = $request->base_amount_value;

        $product_id = $request->product_id;
        $item_id = $request->item_id;

        $order_data = array(
            'user_id' => $userid,
            'order_from' => 'website',
            "coupon_amount" => '00.00',
            "base_amount" => @$base_amount_value,
            "gst_amount" => @$gst_price,
            'reference_no' => '',
            'tax' => $gst_price,
            'order_discount' => '00.00',
            'shipping' => '50',
            'grand_total' => $base_amount_value,
            'paid' => $grand_total,
            'payment_status' => 'pending',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'payment_method' => $payment_method,
        );


        if ($request->new_address == 'old_address') {

            $userAddressData = DB::table('tbl_address')->where('id', $request->delivery_address)->first();

            $order_data['user_address'] = $request->delivery_address;
            $order_data['name'] = @$userAddressData->name;
            $order_data['user_name'] = @$userAddressData->name;
            $order_data['email'] = @$userAddressData->email;
            $order_data['phone'] = @$userAddressData->phone;
            //  $order_data['alternet_phone'] = @$userAddressData->alternet_phone;
            $order_data['address'] = @$userAddressData->address;
            //  $order_data['address2'] = @$userAddressData->address2;
            $order_data['area'] = @$userAddressData->zipcode;
        } else {

            $stateName = DB::table('states')->where('id', $request->state)->first();
            $cityName = DB::table('cities')->where('id', $request->city)->first();

            $userAddress = array(
                'user_id' => $userid,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'alternate_phone' => @$request->phone2,
                'address' => $request->address,
                'address_second' => @$request->address2,
                'zipcode' => @$request->pincode,
                'country' => @$request->country,
                'state' => @$stateName->name,
                'city' => @$cityName->name
            );

            $lastId = DB::table('tbl_address')->insertGetId($userAddress);

            $order_data['user_address'] = $lastId;

            $order_data['name'] = $request->name;
            $order_data['user_name'] = $request->name;
            $order_data['email'] = $request->email;
            $order_data['phone'] = $request->phone;
            $order_data['alternet_phone'] = @$request->phone2;
            $order_data['address'] = $request->address;
            $order_data['address2'] = @$request->address2;
            $order_data['area'] = @$request->pincode;
        }


        $insert_id = DB::table('orders')->insertGetId($order_data);

        // $message=' Hi '.$name.'
        // https://anb.com/ 
        // Your order has been received, Order No. ORD000'.$insert_id.' 
        // ';
        // $message1=' Hi Admin
        // https://and.com/ 
        // An Order has been received, Order No. ORD000'.$insert_id.' Please Start with Processing
        // ';
        // $this->sent_sms($request->phone,$message);


        $total_array = array();

        $this->qty_manage('1', $item_id);

        $product_details = DB::table('services')->where('id', $product_id)->first();
        $first_itmes = DB::table('product_items')->where('id', $item_id)->first();
        $subtotal = $first_itmes->item_price * 1;
        $total_array[] = $subtotal;

        //dd($first_itmes);

        $items = array(

            'order_id' =>          $insert_id,
            'product_id' =>        $product_id,
            'item_id' =>           $item_id,
            'price' =>             @$first_itmes->item_price,
            'quantity' =>          '1',
            'total' =>             @$subtotal,
            'vendor_id' =>         @$product_details->add_by,
            'tax' =>               '0.00',
            'status' =>            'pending',
            'created_at' =>        date('Y-m-d H:i:s'),
            'updated_at' =>        date('Y-m-d H:i:s'),
        );

        //DB::table('order_items')->insertGetId($items);

        $itemidlast_insert = DB::table('order_items')->insertGetId($items);
        DB::table('order_items')->where('id', $itemidlast_insert)->update(['sku' => 'ORDITM00' . $itemidlast_insert]);

        $this->get_all_address($insert_id);

        $order_id_array = array('order_id' => 'ORD000' . $insert_id, 'grand_total' => array_sum($total_array));
        DB::table('orders')->where('id', $insert_id)->update($order_id_array);


        if ($request->payment_method == 'cod') {

            Session::forget('carts');
            Session::forget('shoping_charge_cart');
            Session::flash('success', 'Order Create Successfully.......');
            return Redirect('/my-order');
        } else if ($request->payment_method == 'online') {

            $uuSer = Auth::user();

            $payment_info = [
                "price" => $grand_total,
                "name" => @$uuSer->name,
                "email" => @$uuSer->email,
                "phone" => $uuSer->phone,
                "order_id" => $insert_id,
                "api_key" => "rzp_live_1d6hhDgaQZgj47"
            ];

            Session::forget('carts');
            Session::forget('shoping_charge_cart');

            Session::put('payment_info', $payment_info);
            return Redirect('/payment/razorpay');
        }
    }


    function order_place(Request $request)
    {

        // $card = Session::get('carts');
        //dd($request->all());

        if ($request->new_address == 'old_address') {

            $request->validate([
                'delivery_address' => 'required'
            ]);
        } else {

            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'pincode' => 'required',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required',
            ]);
        }


        $shoping_charge = Session::get('shoping_charge_cart');

        $userid = Auth::user()->id;

        $payment_method = $request->payment_method;
        $grand_total = $request->grand_total;
        $gst_price = round($request->gst_price);
        $deleveryCharge = $request->delevery_charge;

        $defaultShippingCharge = $shoping_charge['shipping'];

        $base_amount_value = $request->base_amount_value;

        $order_data = array(
            'user_id' => $userid,
            'coupon_id' => $shoping_charge['coupon_id'],
            'order_from' => 'website',
            "coupon_amount" => round($shoping_charge['coupon_discount']),
            "base_amount" => @$base_amount_value,
            "gst_amount" => @$gst_price,
            'reference_no' => '',
            'tax' => $gst_price,
            'order_discount' => round($shoping_charge['coupon_discount']),
            'shipping' => $deleveryCharge,
            'grand_total' => $grand_total,
            'paid' => $grand_total,
            'payment_status' => 'pending',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'payment_method' => $payment_method,
        );


        if ($request->new_address == 'old_address') {

            $userAddressData = DB::table('tbl_address')->where('id', $request->delivery_address)->first();

            $order_data['user_address'] = $request->delivery_address;
            $order_data['name'] = @$userAddressData->name;
            $order_data['user_name'] = @$userAddressData->name;
            $order_data['email'] = @$userAddressData->email;
            $order_data['phone'] = @$userAddressData->phone;
            //  $order_data['alternet_phone'] = @$userAddressData->alternet_phone;
            $order_data['address'] = @$userAddressData->address;
            //  $order_data['address2'] = @$userAddressData->address2;
            $order_data['area'] = @$userAddressData->zipcode;
        } else {

            $stateName = DB::table('states')->where('id', @$request->state)->first();
            $cityName = DB::table('cities')->where('id', $request->city)->first();

            $userAddress = array(
                'user_id' => $userid,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'alternate_phone' => @$request->phone2,
                'address' => $request->address,
                'zipcode' => @$request->pincode,
                'country' => @$request->country,
                'state' => @$stateName->name,
                'city' => @$cityName->name
            );

            $lastId = DB::table('tbl_address')->insertGetId($userAddress);

            $order_data['user_address'] = $lastId;

            $order_data['name'] = $request->name;
            $order_data['user_name'] = $request->name;
            $order_data['email'] = $request->email;
            $order_data['phone'] = $request->phone;
            //  $order_data['alternet_phone'] = @$request->phone2;
            $order_data['address'] = $request->address;
            //  $order_data['address2'] = @$request->address2;
            $order_data['area'] = @$request->pincode;
        }


        $insert_id = DB::table('orders')->insertGetId($order_data);

        // $message=' Hi '.$name.'
        // https://anb.com/ 
        // Your order has been received, Order No. ORD000'.$insert_id.' 
        // ';
        // $message1=' Hi Admin
        // https://and.com/ 
        // An Order has been received, Order No. ORD000'.$insert_id.' Please Start with Processing
        // ';
        // $this->sent_sms($request->phone,$message);

        $card = Session::get('carts');

        $total_array = array();

        foreach ($card as $key => $value) {

            $this->qty_manage($value['quantity'], $value['item_id']);

            $product_details = DB::table('tbl_services')->where('id', $value['id'])->first();
            $first_itmes = DB::table('product_items')->where('id', $value['item_id'])->first();
            $subtotal = $first_itmes->item_price * $value['quantity'];
            $total_array[] = $subtotal;

            $vendorID = @$product_details->add_by;
            $randNum = mt_rand(1000, 9999);

            $genrate_sub_orderId = $randNum . '_' . @$vendorID;

            $hasWrap = DB::table('apply_wrap')->where('id', $value['item_id'])->where('user_id', $userid)->first();
            if (!empty($hasWrap)) {

                $wrapPrice = $hasWrap->price;
            } else {
                $wrapPrice = 0;
            }

            $items = [

                'order_id' => $insert_id,
                'sub_order_id' => $genrate_sub_orderId,
                'product_id' => $value['id'],
                'item_id' => $value['item_id'],
                'price' => $value['sale_price'],
                'quantity' => $value['quantity'],
                'total' => $subtotal,
                'vendor_id' => @$vendorID,
                'tax' => '0.00',
                'wrap_price' => $wrapPrice,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $itemidlast_insert = DB::table('order_items')->insertGetId($items);
            DB::table('order_items')->where('id', $itemidlast_insert)->update(['sku' => 'ORDITM00' . $itemidlast_insert]);

            // commission set accouding to vendor

            $getCommission = DB::table('users')->select('id', 'commission')->where('id', $vendorID)->first();
            if ($getCommission->commission > 0) {

                $this->commissionStore($vendorID, $value['id'], $value['item_id'], $itemidlast_insert, $value['sale_price'], $getCommission->commission, $shoping_charge['coupon_id']);
            } else {
                $defaultCommission = DB::table('settings')->select('id', 'commission')->first();
                $this->commissionStore($vendorID, $value['id'], $value['item_id'], $itemidlast_insert, $value['sale_price'], $defaultCommission->commission, $shoping_charge['coupon_id']);
            }
        }


        $this->get_all_address($insert_id);

        $order_id_array = array('order_id' => 'ORD000' . $insert_id, 'grand_total' => array_sum($total_array));
        DB::table('orders')->where('id', $insert_id)->update($order_id_array);

        if ($shoping_charge['coupon_id'] > 0) {

            DB::table('apply_coupon')->insert([

                "user_id" => @$userid,
                "order_id" => @$insert_id,
                "total_amount" => @$grand_total,
                "base_amount" => @$shoping_charge['base_amount'],
                "coupon_id" => @$shoping_charge['coupon_id'],
                "coupon_value" => @$shoping_charge['coupon_value'],
                "coupon_discount" => @$shoping_charge['coupon_discount'],
                "coupon_code" => @$shoping_charge['coupon_code'],
                "gst_amount" => @$gst_price

            ]);
        }

        DB::table('apply_wrap')->where('user_id', $userid)->delete();

        if ($request->payment_method == 'cod') {

            Session::forget('carts');
            Session::forget('shoping_charge_cart');
            Session::flash('success', 'Order Create Successfully.......');
            return Redirect('/my-order');
        } else if ($request->payment_method == 'online') {

            $uuSer = Sentinel::getUser();

            $payment_info = [
                "price" => $grand_total,
                "name" => @$uuSer->name,
                "email" => @$uuSer->email,
                "phone" => @$uuSer->phone,
                "order_id" => $insert_id,
                // "api_key" => "rzp_live_1d6hhDgaQZgj47"
                "api_key" => "rzp_test_4XmsfvzPHi2OUO"
            ];

            Session::forget('carts');
            Session::forget('shoping_charge_cart');
            Session::put('payment_info', $payment_info);
            return Redirect('/payment/razorpay');
        }
    }

    public function commissionStore($vendorId, $productId, $itemId, $orderItemId, $salePrice, $commissionValue, $coupon)
    {

        $adminCommission = $salePrice * $commissionValue / 100;

        DB::table('commission_set')->insert([
            "vendor_id" => $vendorId,
            "product_id" => $productId,
            "item_id" => $itemId,
            "order_item_id" => $orderItemId,
            "sale_price" => $salePrice,
            "commission_price" => round($adminCommission),
            "coupon" => $coupon
        ]);

        return true;
    }


    function qty_manage($qty, $id)
    {

        $data = DB::table('product_items')->where('id', $id)->first();

        $upated_qty = floatval($data->stock - $qty);

        $data_array = array(
            'stock' => $upated_qty
        );
        DB::table('product_items')->where('id', $id)->update($data_array);
    }


    public function payment_razorpay()
    {

        $payment_info = Session::get('payment_info');
        //dd($payment_info);
        return view('frontend.razorpay', compact('payment_info'));
    }

    public function success_payment(Request $request)
    {

        $order_id = $_POST['hidden'];
        $txnid = $_POST['razorpay_payment_id'];
        $status = 'Success';
        $data = array('payment_status' => $status, 'reference_no' => $txnid);
        DB::table('orders')->where('id', $order_id)->update($data);

        Session::forget('payment_info');
        Session::flash('success', 'Order Create Successfully.');
        return back();
    }


    public function returnOrder(Request $request)
    {
        Order::where("id", $request->id)->update(['return_reason' => $request->reason, 'status' => 'returned', 'return_date' => date('Y-m-d H:i:s')]);
        Session::flash('success', 'Order returned Successfully.......');
        return Redirect('/account/orders/order-details/' . $request->id);
    }
    function sentsms()
    {
        $user_list = DB::table('users')->get();
        $msg = 'Dear Customer,
        Please click the below link to update your Himveg App and enjoy the home delivery of fresh fruits and vegetables at your doorstep. <br>
        https://play.google.com/store/apps/details?id=com.himvegfru&hl=en
        ';
        foreach ($user_list as $value) {
            $this->sent_sms($value->phone, $msg);
        }
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
    function getDistance_bylatlong($lat1, $lng1, $lat2, $lng2, $unit = '')
    {

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
        if ($unit == "K") {
            return round($miles * 1.609344, 2);
        } elseif ($unit == "M") {
            return round($miles * 1609.344, 2) . ' meters';
        } else {
            return round($miles, 2) . ' miles';
        }
    }
    function get_all_address1($orderid)
    {
        $data = DB::table('users')
            ->select('users.*')
            // ->select('users.*', 'role_users.role_id')
            // ->where('role_users.role_id', '7')
            ->orderBy('users.id', 'DESC')
            // ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
            ->get();
        $res = array();
        $lat_long_array = DB::table('settings')->where('type', 'site_settings')->first();
        $lat_long_array = json_decode($lat_long_array->value);
        /// print_r($lat_long_array->site_name); die;
        $lat = $lat_long_array->lat;
        $long = $lat_long_array->long;
        foreach ($data as $key => $value) {
            // $dis = $this->getDistance_bylatlong($lat, $long, $value->lat, $value->long, 'K');
            $dis = '12.5';
            $res[] = array(
                'distance' => $dis,
                'address' => $value->address_1,
                'name' => $value->name,
                'phone' => $value->phone,
                'id' => $value->id,
            );
        }


        if ($res) {
            sort($res);
            $insert_data = array(
                'order_id' => $orderid,
                'user_id' => $res[0]['id'],
                'status' => 'pending',
                'is_closed' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            DB::table('deliveries')->insert($insert_data);
            return 1;
        }
    }

    function get_all_address($orderid)
    {
        $data = DB::table('users')
            ->select('users.*')
            // ->select('users.*', 'role_users.role_id')
            // ->where('role_users.role_id', '7')
            ->orderBy('users.id', 'DESC')
            // ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
            ->get();
        $res = array();
        $lat_long_array = DB::table('settings')->where('type', 'site_settings')->first();
        $lat_long_array = json_decode($lat_long_array->value);
        /// print_r($lat_long_array->site_name); die;
        $lat = $lat_long_array->lat;
        $long = $lat_long_array->long;
        foreach ($data as $key => $value) {
            // $dis = $this->getDistance_bylatlong($lat, $long, $value->lat, $value->long, 'K');
            $dis = '12.5';
            $res[] = array(
                'distance' => $dis,
                'address' => $value->address_1,
                'name' => $value->name,
                'phone' => $value->phone,
                'id' => $value->id,
            );
        }

        $ides = '';
        if ($res) {
            sort($res);

            foreach ($res as $val) {
                $user_delivery_list = DB::table('deliveries')->where('user_id', $val['id'])->whereNotIn('status', ['reject', 'complete', 'cancel'])->get();

                if (count($user_delivery_list) >= 5) {
                    continue;
                } else if (count($user_delivery_list) < 5) {
                    $ides = $val['id'];
                    $insert_data = array(
                        'order_id' => $orderid,
                        'user_id' => $ides,
                        'status' => 'pending',
                        'is_closed' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    DB::table('deliveries')->insert($insert_data);
                    return 1;
                } else {
                    return 1;
                }
            }
        }
    }
    function shipping()
    {
        return view('shipping');
    }
    function thankyou()
    {
        return view('thankyou');
    }
}
