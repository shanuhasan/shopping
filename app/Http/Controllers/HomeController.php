<?php

namespace App\Http\Controllers;

use Sentinel;
use App\Models\City;
use App\Models\Type;
use App\Models\User;
use App\Models\Order;
use App\Models\State;
use App\Models\Banner;
use App\Models\Country;
use App\Models\Service;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Category_model as Category_model;

class HomeController extends Controller
{


    public function notifyme_product(Request $request)
    {

        if (!Sentinel::check()) {

            return response()->json([
                "status" => "error",
                "msg" => "you have login"
            ]);
        } else {

            $userId = Sentinel::getUser()->id;

            $product_id = base64_decode($request->product_id);
            $item_id = base64_decode($request->item_id);

            $hasUserNotify =  DB::table('send_notify')
                ->where('product_id', $product_id)
                ->where('item_id', $item_id)
                ->where('user_id', $userId)
                ->first();

            if (empty($hasUserNotify)) {

                $hasUserNotify =  DB::table('send_notify')->insert([

                    "product_id" => $product_id,
                    "item_id" => $item_id,
                    "user_id" => $userId
                ]);

                return response()->json([
                    "status" => "success",
                    "msg" => "we will notify you"
                ]);
            } else {
                return response()->json([
                    "status" => "success",
                    "msg" => "we have already get message. we will notify you"
                ]);
            }
        }
    }

    public function promocode()
    {

        $data['coupons'] = DB::table('coupons')->get();
        //dd($data);
        return view('promocode', $data);
    }

    function index()
    {
        $offerBanner = Banner::where('type', '!=', 'Default')->get();
        $category = [];

        if (!empty($offerBanner)) {
            foreach ($offerBanner as $cat) {
                $category[] = Category::findById($cat['category_id'])->slug;
            }
        }

        $data['offerBanner'] = $offerBanner;
        $data['category_slug'] = $category;

        $banners = Banner::getDefaultBanners();

        $slides = array();
        if (!empty($banners)) {
            $slides = $banners->toArray();
        }

        $data['slides'] = $slides;

        $settings = $this->get_settings('site_settings');
        $category_id = json_decode($settings->category_id);
        $type_id = json_decode($settings->type_id);

        // category by product fetch
        $category_with_product = Category::whereIn('id', $category_id)
            ->select(DB::raw("id,category_name as name, slug,if(image IS NOT NUll,concat('/uploads/category/',image),'') as image"))
            ->get();

        $category_product = [];
        if (!empty($category_with_product)) {
            $cat_by_data = $category_with_product->toArray();
            foreach ($cat_by_data as $value) {
                $value['items'] = $this->getCategoryProduct($value['id']);
                $category_product[] = $value;
            }
        }
        $data['category_by_product'] = $category_product;

        //  getRecentProduct

        // type by product fetch
        $type_with_product = DB::table('types')->whereIn('id', $type_id)
            ->select(DB::raw("id,name"))
            ->get();

        if (!empty($type_with_product)) {

            foreach ($type_with_product as $value) {

                $value->items = $this->getProduct_by_type($value->id);
                $type_product[] = $value;
            }
        }

        $data['type_by_product'] = $type_product;

        $data['comdowns'] = $this->getComeDownProduct($type_id = 10);

        // recent view product
        $recent_product = [];
        if (isset($_COOKIE["recent_view"])) {

            $recentArrayData = json_decode($_COOKIE["recent_view"]);
            $recent_product =  $this->getRecentProduct($recentArrayData);
        }

        $data['recent_product'] = $recent_product;

        $data['coupons'] = DB::table('coupons')->get();

        // dd( $data['recent_product']);

        return view('frontend.index', $data);
    }


    public function getRecentProduct($slug)
    {
        $results = Service::whereIn("slug", $slug)
            ->select(DB::raw("id,service_name as name, slug, if(image IS NOT NUll,concat('/uploads/service/',image),'') as image,description,service_price as price,sale_price as sale_price,seller_price,unit,unit_value,stock,COALESCE(((((service_price-sale_price))/service_price)*100),0) as discount"))
            ->take(10)->get();
        $product = array();
        foreach ($results as $products) {

            $itmes = DB::table('product_items')->where('product_id', $products->id)->whereNotIn('type', ['bulk'])->get();
            $first_itmes = DB::table('product_items')->where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();

            $product[] = array(
                'id' => $products->id,
                'image' => $products->image,
                'name' => $products->name,
                'slug' => $products->slug,
                'seller_price' => $products->seller_price,
                'sale_price' => @$first_itmes->item_price,
                'item_id' => @$first_itmes->id,
                'price' => @$first_itmes->item_mrp_price,
                'unit_value' => @$first_itmes->item_unit_value,
                'unit' => @$first_itmes->item_unit,
                'items' => $itmes,
            );
        }
        return $product;
    }

    public function getComeDownProduct($type_id)
    {
        $results = Service::where("type_id", $type_id)
            ->select(DB::raw("id,
		service_name as name, 
		slug, 
		comdown_start,
		comdown_end,
		if(image IS NOT NUll,concat('/uploads/service/',image),'') as image,
		description,
		service_price as price,
		sale_price as sale_price,
		seller_price,
		unit,
		unit_value,
		stock,
		COALESCE(((((service_price-sale_price))/service_price)*100),0) as discount
		"))
            ->take(4)->get();
        $product = array();
        foreach ($results as $products) {
            $first_itmes = DB::table('product_items')->where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();
            $product['item'][] = array(
                'id' => $products->id,
                'image' => $products->image,
                'name' => $products->name,
                'comdown_start' => $products->comdown_start,
                'comdown_end' => $products->comdown_end,
                'slug' => $products->slug,
                'seller_price' => $products->seller_price,
                'sale_price' => @$first_itmes->item_price,
                'item_id' => @$first_itmes->id,
                'price' => @$first_itmes->item_mrp_price,
                'unit_value' => @$first_itmes->item_unit_value,
                'unit' => @$first_itmes->item_unit,
            );
        }

        $product['comdown_start'] = @$results[0]['comdown_start'];
        $product['comdown_end'] = @$results[0]['comdown_end'];
        return $product;
    }



    function get_settings($type)
    {
        return DB::table('settings')->where('type', $type)->first();
    }

    function update_all_products()
    {
        $all_products = Service::all();
        foreach ($all_products as $value) {
            $item_array = array(
                'item_mrp_price' => $value->service_price,
                'item_price' => $value->sale_price,
                'item_unit' => $value->unit,
                'item_unit_value' => $value->unit_value,
                'product_id' => $value->id,
            );
            DB::table('product_items')->insert($item_array);
        }
    }

    public function getCategoryProduct($category_id)
    {
        $results = Service::whereRaw("((parent_category={$category_id} OR subcategory={$category_id}) AND status=1)")
            ->select(DB::raw("id,service_name as name, slug, if(image IS NOT NUll,concat('/uploads/service/',image),'') as image,description,service_price as price,sale_price as sale_price,seller_price,unit,unit_value,stock,COALESCE(((((service_price-sale_price))/service_price)*100),0) as discount"))
            ->take(8)->get();
        $product = array();
        foreach ($results as $products) {
            $itmes = DB::table('product_items')->where('product_id', $products->id)->whereNotIn('type', ['bulk'])->get();
            $first_itmes = DB::table('product_items')->where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();

            $product[] = array(
                'id' => $products->id,
                'image' => $products->image,
                'name' => $products->name,
                'slug' => $products->slug,
                'seller_price' => $products->seller_price,
                'sale_price' => @$first_itmes->item_price,
                'item_id' => @$first_itmes->id,
                'price' => @$first_itmes->item_mrp_price,
                'unit_value' => @$first_itmes->item_unit_value,
                'unit' => @$first_itmes->item_unit,
                'items' => $itmes,
            );
        }
        return $product;
    }




    public function getProduct_by_type($type_id)
    {
        $results = Service::whereRaw("((type_id={$type_id}) AND status=1)")
            ->select(DB::raw("
		            id,
		            service_name as name,
		            if(image IS NOT NUll,concat('/uploads/service/',image),'') as image,
		            description,service_price as price,
		            sale_price as sale_price,
		            seller_price,
		            unit,
		            unit_value,
		            stock,
		            slug,
		            COALESCE(((((service_price-sale_price))/service_price)*100),0) as discount
		            "))
            ->take(8)
            ->get();
        $product = array();
        foreach ($results as $products) {
            $itmes = DB::table('product_items')->where('product_id', $products->id)->whereNotIn('type', ['bulk'])->get();
            $first_itmes = DB::table('product_items')->where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();
            $product[] = array(
                'id' => $products->id,
                'image' => $products->image,
                'name' => $products->name,
                'slug' => $products->slug,
                'seller_price' => $products->seller_price,
                'sale_price' => @$first_itmes->item_price,
                'item_id' => @$first_itmes->id,
                'price' => @$first_itmes->item_mrp_price,
                'unit_value' => @$first_itmes->item_unit_value,
                'unit' => @$first_itmes->item_unit,
                'items' => $itmes,
            );
        }
        return $product;
    }


    function login()
    {

        if (Sentinel::check()) {

            if ($redirect = Session::get('redirect')) {
                return redirect($redirect);
            } else {
                return redirect('/')->with('success', 'You have logged in successfully');
            }
        }

        Session::put('redirect', url()->previous());

        $data['category'] = Category_model::all();
        $id = Session::get('user_id');

        $checkcurrentUrl = Session::get('redirect');

        if ($checkcurrentUrl == "https://anbshopping.com/login") {

            Session::put('redirect', "https://anbshopping.com");
        }

        //dd(Session::get('redirect'));

        if (!$id) {
            return view('login');
        } else {
            Session::reflash('redirect');
            return redirect('/');
        }
    }


    public function get_order_item($id)
    {
        $data = OrderItems::where('order_id', $id)->get()->toArray();
        return $data;
    }

    public function orders()
    {
        if (!Auth::check()) {
            Session::flash('error', 'Please Login your account');
            return redirect('/login');
        }

        $user = Auth::user();

        $data_lsit = array();
        $data_lsit = Order::where("user_id", $user->id)->orderBy('id', 'DESC')->whereIn('payment_method', ['online', 'cod'])->get()->toArray();

        $data['orders'] = $data_lsit;
        $data['title'] = "Orders";

        return view('frontend.orders', $data);
    }

    function sent_mail($email = '', $subject = '', $msg = '')
    {

        $settings = $this->get_settings('site_settings');
        $setting_email = json_decode($settings->value)->other_email;

        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail('support@anbshopping.com', $subject, $msg, $headers);
    }

    function success_payment()
    {

        $order_id = $_POST['hidden'];
        $txnid = $_POST['razorpay_payment_id'];
        $status = 'Success';
        $data = array('payment_status' => $status, 'reference_no' => $txnid);
        DB::table('orders')->where('id', $order_id)->update($data);

        Session::flash('success', 'Order Create Successfully.......');
        return back();
    }

    public function order_return_by_user(Request $request)
    {

        if (!Sentinel::check()) {
            Session::flash('error', 'Please Login your account');
            return redirect('/login');
        }

        $user = Sentinel::getUser();

        if ($user) {

            $comment = @$request->comment;
            $item_id = @$request->item_id;
            $order_id = @$request->order_id;


            $checkEvereyItemCancel = DB::table('order_items')
                ->where('order_id', $order_id)
                ->where('status', 'delivered')
                ->get();

            //dd($checkEvereyItemCancel);  

            if (count($checkEvereyItemCancel) > 0) {


                $ddd = DB::table('order_items')->where('id', $item_id)->update([
                    "status" => "return_pending"
                ]);

                //dd($ddd);                            

                $ch = DB::table('order_items')
                    ->where('order_id', $order_id)
                    ->whereNotIn('status', ['return_pending'])
                    ->get();
                //dd($ch);

                if (count($ch) <= 0) {

                    DB::table('orders')->where('id', $order_id)->update(["status" => "return"]);
                }


                DB::table('return_cancel')->insert([

                    "user_id" => $user->id,
                    "order_id" => $order_id,
                    "item_id" => $item_id,
                    "comment" => $comment
                ]);

                $order_data =   DB::table('orders')->where('id', $order_id)->first();

                $msg = '
                <div style="text-align:left">
                Dear ' . @$user->first_name . ',
                <br>
                You have requested for order return ' . @$user->email . ' in Anbshopping.<br>
                
                Your order ID  is -' . @$order_data->order_id . ' <br>
                
                </div>
                ';

                $subject = 'Order Return order id ' . @$order_data->order_id;

                $this->sent_mail($user->email, $subject, $msg);

                return back()->with('success', 'Order return Successfully Changed');
            } else {


                return back()->with('success', 'something went wrong');
            }
        } else {
            return back()->with('error', 'Invalid Authentication key');
        }
    }



    public function order_cancelled_by_user(Request $request)
    {

        if (!Sentinel::check()) {
            Session::flash('error', 'Please Login your account');
            return redirect('/login');
        }

        $res = Sentinel::getUser();

        if ($res) {

            $status = 'cancelled';
            $note = 'order cancelled';

            $comment = @$request->comment;
            $item_id = @$request->item_id;
            $order_id = @$request->order_id;

            $order_item_data = array('status' => $status, 'note' => $note);

            $checkEvereyItemCancel = DB::table('order_items')
                ->where('order_id', $order_id)
                ->where('status', '!=', 'cancelled')
                ->get();

            //dd($checkEvereyItemCancel); 

            if (count($checkEvereyItemCancel) > 0) {

                DB::table('order_items')->where('id', $item_id)->update($order_item_data);

                $chkSecond = DB::table('order_items')->where('order_id', $order_id)->where('status', '!=', 'cancelled')->get();
                if (count($chkSecond) <= 0) {

                    DB::table('orders')->where('id', $order_id)->update(["status" => "cancelled"]);
                }

                $userCheck = DB::table('users')->where('id', $res->id)->first();

                $order_data =   DB::table('orders')->where('id', $order_id)->first();

                DB::table('return_cancel')->insert([

                    "user_id" => $res->id,
                    "order_id" => $order_id,
                    "item_id" => $item_id,
                    "comment" => $comment
                ]);

                $msg = '
                <div style="text-align:left">
                Dear ' . @$res->first_name . ',
                <br>
                You have requested for order cancel ' . @$res->email . ' in Anbshopping.<br>
                
                Your order ID  is -' . @$order_data->order_id . ' <br>
                
                </div>
                ';

                $subject = 'Order Cancelled order id ' . @$order_data->order_id;

                $this->sent_mail($res->email, $subject, $msg);

                return back()->with('success', 'Order Cancelled Successfully Changed');
            } else {

                return back()->with('error', 'Something went wrong plz try again..');
            }
        } else {

            return back()->with('error', 'Invalid Authentication key');
        }
    }



    public function orderDetails($id)
    {

        if (!Auth::check()) {
            Session::flash('error', 'Please Login your account');
            return redirect('/login');
        }

        $user = Auth::getUser();

        $data = Order::where(["user_id" => $user->id, 'id' => $id])->first();

        $data['user_address'] = DB::table('user_address')->where('id', $data->user_address)->first();

        $orderitem = OrderItem::where('order_id', $id)->get();
        $order_item = [];

        foreach ($orderitem as $k => $prod) {

            $productValue = DB::table('services')
                ->select('id', 'service_name', 'image', 'add_by', 'slug')
                ->where('id', $prod->product_id)
                ->first();

            $product_item_value = DB::table('product_items')
                ->where('id', $prod->item_id)
                ->first();

            $order_item[] = [

                "order_item_id" => $prod->id,
                "order_id" => $prod->order_id,
                "product_id" => $prod->product_id,
                "product_image" => @$productValue->image,
                "product_name" => @$productValue->service_name,
                "product_slug" => @$productValue->slug,
                "product_vendor" => @$productValue->add_by,
                "product_item_id" => @$prod->item_id,
                "product_item_price" => @$product_item_value->item_price,
                "product_item_mrp_price" => @$product_item_value->item_mrp_price,
                "product_item_unit" => @$product_item_value->item_unit,
                "product_item_unit_value" => @$product_item_value->item_unit_value,
                "product_item_type" => @$product_item_value->type,
                "product_item_stock" => @$product_item_value->stock,
                "product_item_color" => @$product_item_value->color,
                "product_item_size" => @$product_item_value->size,
                "product_item_image" => @$product_item_value->image,
                "price" => $prod->price,
                "quantity" => $prod->quantity,
                "order_item_tax" => $prod->tax,
                "order_item_total" => $prod->total,
                "order_item_status" => $prod->status,
                "order_item_review" => $prod->review,
                "order_item_rating" => $prod->rating,
                "order_item_note" => $prod->note,
                "order_item_created_at" => $prod->created_at,
                "order_item_updated_at" => $prod->updated_at,

            ];
        }

        $data['order_item'] = $order_item;

        //dd($data);
        return view('frontend.order_details', compact('data'));
    }


    function wishlist()
    {
        if (!Auth::check()) {
            Session::flash('error', 'Please Login your account');
            return redirect('/login');
        }

        $user = Auth::getUser();
        $wishData = DB::table('wishlists')->where('app_id', $user->id)->get();
        $wish_use_id = [];

        foreach ($wishData as $wish) {
            $wish_use_id[] = $wish->product_id;
        }

        $services = DB::table('services')->whereIn('id', $wish_use_id)
            ->get()
            ->map(function ($services) {
                $services->items = DB::table('product_items')->where('product_id', $services->id)->first();
                return $services;
            });

        $data['wishlist'] = $services;
        return view('frontend.wishlist', $data);
    }


    function profile()
    {

        if (!Auth::check()) {
            Session::flash('error', 'Please Login your account');
            return redirect('/login');
        }
        $user = Auth::getUser();
        return view('frontend.profile', compact('user'));
    }



    public function profile_update(Request $request)
    {

        if (!Auth::check()) {
            Session::flash('error', 'Please Login your account');
            return redirect('/login');
        }
        $user = Auth::getUser();

        $model = User::findById($user->id);
        $model->name = $request->name;
        $model->address_1 = $request->address_1;
        $model->address_2 = $request->address_2;
        $model->pincode = $request->pincode;
        $model->phone = $request->phone;


        if ($request->new_password !== null || $request->confirm_password !== null) {

            if ($request->new_password == $request->confirm_password) {
                $pass = Hash::make($request->new_password);
                $model->password = $pass;
            } else {
                return redirect()->back()->with('errors', 'New password or confirm password not match..');
            }
        }

        $name = '';

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/profile/');
            $image->move($destinationPath, $name);

            $model->image = $name;
        }

        $model->save();

        return redirect()->back()->with('success', 'Profile update successfully..');
    }

    function address_list()
    {
        return view('address_list');
    }

    function contact()
    {
        $data['category'] = Category_model::all();
        return view('contact');
    }


    function comingsoon()
    {
        $data['category'] = Category_model::all();
        return view('coming-soon');
    }

    function register_user(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'phone' => 'required|digits_between:10,11',
            'zipcode' => 'required|digits_between:6,6',
            'password' => 'required',
        ]);


        $name = $request->name;
        $email = $request->email;
        $address = $request->address;
        $phone = $request->phone;
        $zip_code = $request->zipcode;

        if ($name && $email && $address && $phone) {

            $check_duplicate = DB::table('users')->where('email', $email)->first();
            $check_duplicate_phone = DB::table('users')->where('phone', $phone)->first();

            if (!$check_duplicate || !$check_duplicate_phone) {

                $data = array(
                    'first_name' => $name,
                    'email' => $email,
                    'line_1' => $address,
                    'phone' => $phone,
                    'password' => $request->password,
                );

                $userdata = Sentinel::registerAndActivate($data);


                $role = Sentinel::findRoleBySlug('customer');
                $res = $role->users()->attach($userdata);

                $auth_key = 'Bearer ' . bin2hex(random_bytes(300));

                DB::table('users')->where('id', $userdata->id)->update(["zip_code" => $zip_code, 'auth_key' => $auth_key]);
                Session::flash('success', 'Register Successfully....');

                return redirect('/login');
            } else {
                Session::flash('error', 'This user is allready exist...');
                return back();
            }
        } else {
            Session::flash('error', 'All Fields are required...');
            return back();
        }
    }


    function customer_login(Request $request)
    {

        $user = Sentinel::authenticate(['email' => $request->email, 'password' => $request->password]);
        $data = Sentinel::getUser();

        $user = Sentinel::authenticate($request->all());
        $users = Sentinel::check();

        if ($users) {
            $check_venodr = DB::table('role_users')->where('user_id', $users->id)->first();

            if ($check_venodr->role_id == 5 || $check_venodr->role_id == 6) {

                $getUrl = Session::get('redirect');
                echo "success";
            } else {
                Sentinel::logout();

                echo "error";
            }
        } else {
            DB::table('throttle')->where('type', 'global')->delete();
            DB::table('throttle')->where('type', 'ip')->delete();
            DB::table('throttle')->where('type', 'user')->delete();
            echo "error";
        }
    }

    function otp_sent(Request $request)
    {
        $phone = $request->phone;

        if ($phone) {

            $data = DB::table('users')->where('phone', $phone)->where('status', '1')->first();
            if ($data) {

                $check_venodr = DB::table('role_users')->where('user_id', $data->id)->first();
                if ($check_venodr->role_id == 5 || $check_venodr->role_id == 6) {
                    $code = mt_rand(1000, 9999);

                    DB::table('users')->where('id', $data->id)->update(['otp' => $code]);
                    $msg = 'Your OTP Code for HIMVEG Verification' . $code;
                    $this->sent_sms($phone, $msg);
                    echo "success";
                } else {
                    Sentinel::logout();

                    echo "error";
                }
            } else {
                echo "not exist";
            }
        } else {

            echo "error";
        }
    }

    public function sent_sms($number, $message)
    {

        $apiKey = urlencode('SYnYjh4fZ58-is7IBxkmj78JUvS1z7jWlIeQxveth3');
        $numbers = array($number);
        $sender = urlencode('TXTLCL');
        $message = rawurlencode($message);
        $numbers = implode(',', $numbers);

        $data = array('apikey' => $apiKey, 'numbers' => $numbers, 'sender' => $sender, 'message' => $message);
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function check_coupon_val(Request $request)
    {

        $val = $request->val;
        $date = date('Y-m-d');

        $data = DB::table('coupons')->where('coupon_code', $val)->where('expiry_date', '>=', $date)->first();
        if ($data) {
            Session::put('coupons', $data);
            Session::flash('success', 'Coupon Add Successfully');
        } else {
            echo "error";
        }
    }
    function remove_coupon(Request $request)
    {

        Session::forget('coupons');
        Session::flash('success', 'Coupon Remove Successfully');
    }


    function cart()
    {

        $settings = DB::table('settings')->first();
        $endcode_settings = json_decode($settings->value);

        $cart = Session::get('carts');
        $shoping_charge_cart = Session::get('shoping_charge_cart');

        if (empty($shoping_charge_cart)) {

            $shoping_charge = [
                "gst_price" => 0,
                "gst_per" => $endcode_settings->cgst + $endcode_settings->sgst,
                "cgst" => $endcode_settings->cgst,
                "sgst" => $endcode_settings->sgst,
                "coupon_value" => 0,
                "coupon_discount" => 0,
                "coupon_id" => 0,
                "shipping" => $endcode_settings->shipping_charge
            ];

            Session::put('shoping_charge_cart', $shoping_charge);
        }

        $data['category'] = Category::all();
        return view('frontend.cart');
    }


    public function logout(Request $request)
    {

        $cookie_id = Session::get('cookie_id');
        DB::table('attempt_login')->where('cookie_id', $cookie_id)->delete();
        Sentinel::logout();
        Session::flash('success', 'Logout Successfully....');
        return redirect('/login');
    }

    public function addTocart(Request $request)
    {

        $productId = base64_decode(request('product_id'));
        $itemId = base64_decode(request('item_id'));

        $product = DB::table('services')
            ->where("services.id", $productId)
            ->where("product_items.id", $itemId)
            ->select("services.id", "product_items.id as item_id", "services.service_name", "product_items.item_mrp_price as service_price", "product_items.item_unit as unit", "product_items.item_unit_value as unit_value", "product_items.item_price as sale_price", "product_items.image")
            ->leftJoin('product_items', 'services.id', '=', 'product_items.product_id')
            ->first();

        $cart = array();
        //dd($product);
        if (!empty($product)) {
            $products = array();
            //$product=$product->toArray();
            foreach ($product as $p_key => $pvalue) {
                $products[$p_key] = $pvalue;
            }
            $product = $products;
            $product_id = $product['id'] . $product['item_id'];

            $cart = Session::get('carts');
            if (Session::get('carts')) {
                $cart = Session::get('carts');

                if (isset($cart[$product_id])) {
                    $product = $cart[$product_id];

                    $product['quantity'] = isset($request->quantity) ? $request->quantity : $product['quantity'] + 1;
                } else {

                    $product['quantity'] = 1;
                }
                $cart[$product_id] = $product;
                Session::put('carts', $cart);
            } else {
                $product['quantity'] = 1;
                //dd($product); 
                $cart[$product['id'] . $product['item_id']] = $product;
                Session::put('carts', $cart);
            }
        }
        $cart = Session::get('carts');
        $status = false;
        if (count($cart)) {
            $status = true;
        }
        return response()->json(['cart' => $cart, 'status' => $status]);
    }


    public function  removeTocart(Request $request)
    {

        $product_id = $request->product_id;
        $item_id = $request->item_id;

        DB::table('wishlists')->where('product_id', $product_id)->delete();
        return response()->json(['cart' => 'data', 'status' => true]);
    }

    public function removeCart(Request $request)
    {
        if (count(array_values(Session::get('cart'))) > 0) {
            $product_id = $request->product_id . $request->item_id;
            $cart = Session::get('cart');
            if (isset($cart[$product_id])) {
                unset($cart[$product_id]);
            }
            Session::put('cart', $cart);
        }
        $cart = Session::get('cart');
        $status = false;
        if (count(array_values($cart))) {
            $status = true;
        }
        return response()->json(['cart' => $cart, 'status' => $status]);
    }

    function add_tocart(Request $request)
    {
        $cartcookie = $request->cartcookie;
        $product_id = $request->product_id;
        $item_id = $request->item_id;
        $info = array();
        $product_details = DB::table('services')->where('id', $product_id)->first();
        $item_details = DB::table('product_items')->where('product_id', $product_id)->where('id', $item_id)->first();

        $data = array(
            'cookie_cart' => $cartcookie,
            'product_id' => $product_id,
            'item_id' => $item_id,
            'qty' => '1',
            'name' => $product_details->service_name,
            'image' => $product_details->image,
            'price' => $item_details->item_price,
            'mrp_price' => $item_details->item_mrp_price,
            'subtotal' => $item_details->item_price,
            'info' => json_encode($info),
            'user_id' => '0'
        );

        $check_cart = DB::table('carts')->where('cookie_cart', $cartcookie)->where('product_id', $product_id)->where('item_id', $item_id)->first();

        if ($check_cart) {
            if (isset($request->quantity) && !empty($request->quantity)) {
                $subtotal =
                    $qty = $request->quantity;
                $subtotal = $request->quantity * $item_details->item_price;
            } else {
                $qty = $check_cart->qty + 1;
                $subtotal = $qty * $item_details->item_price;
            }

            $update_cart = array(
                'qty' => $qty,
                'subtotal' => $subtotal,
                'mrp_price' => $item_details->item_mrp_price,
            );
            DB::table('carts')->where('id', $check_cart->id)->update($update_cart);
        } else {
            DB::table('carts')->insert($data);
        }
        $carts = DB::table('carts')->where('cookie_cart', $cartcookie)->get();
        $totalamount = DB::table('carts')
            ->where('cookie_cart', $cartcookie)->get()->sum('subtotal');
        $count = count($carts);
        Session::put('carts', $carts);
        Session::put('cart_total', $totalamount);
        Session::put('cart_cookie', $cartcookie);
        echo json_encode(array('cartcount' => $count, 'totalamount' => '<i class="fa fa-inr" aria-hidden="true"></i> ' . $totalamount));
    }

    function update_tocart(Request $request)
    {
        $cartcookie = $request->cartcookie;
        $product_id = $request->product_id;
        $item_id = $request->item_id;
        $quantity = $request->quantity;
        $info = array();
        $product_details = DB::table('services')->where('id', $product_id)->first();
        $item_details = DB::table('product_items')->where('product_id', $product_id)->where('id', $item_id)->first();



        $check_cart = DB::table('carts')->where('cookie_cart', $cartcookie)->where('product_id', $product_id)->where('item_id', $item_id)->first();
        if ($check_cart) {
            if (isset($request->quantity) && !empty($request->quantity)) {
                $subtotal =
                    $qty = $request->quantity;
                $subtotal = $request->quantity * $item_details->item_price;
            } else {
                $qty = $check_cart->qty + 1;
                $subtotal = $check_cart->subtotal + $item_details->item_price;
            }

            $update_cart = array(
                'qty' => $qty,
                'subtotal' => $subtotal,
                'mrp_price' => $item_details->item_mrp_price,
            );
            DB::table('carts')->where('id', $check_cart->id)->update($update_cart);
        }
        $carts = DB::table('carts')->where('cookie_cart', $cartcookie)->get();
        $totalamount = DB::table('carts')
            ->where('cookie_cart', $cartcookie)->get()->sum('subtotal');
        $count = count($carts);
        Session::put('carts', $carts);
        Session::put('cart_total', $totalamount);
        Session::put('cart_cookie', $cartcookie);
        echo json_encode(array('cartcount' => $count, 'totalamount' => '<i class="fa fa-inr" aria-hidden="true"></i> ' . $totalamount));
    }

    function removeCart1(Request $request)
    {
        $product_id = $request->product_id;
        $item_id = $request->item_id;
        $cartcookie = $request->cartcookie;
        //echo $item_id; die;
        $check_cart = DB::table('carts')->where('cookie_cart', $cartcookie)->where('product_id', $product_id)->where('item_id', $item_id)->delete();
        $carts = DB::table('carts')->where('cookie_cart', $cartcookie)->get();
        $totalamount = DB::table('carts')
            ->where('cookie_cart', $cartcookie)->get()->sum('subtotal');
        $count = count($carts);
        Session::put('carts', $carts);
        Session::put('cart_total', $totalamount);
        Session::put('cart_cookie', $cartcookie);
        echo json_encode(array('cartcount' => $count, 'totalamount' => '<i class="fa fa-inr" aria-hidden="true"></i> ' . $totalamount));
    }

    function aboutus()
    {
        $data['page'] = DB::table('tbl_pages')->where("id", 1)->first();
        return view('page', $data);
    }



    function privacy()
    {
        $data['page'] = DB::table('tbl_pages')->where("id", 3)->first();
        return view('page', $data);
    }

    function return()
    {
        $data['page'] = DB::table('tbl_pages')->where("id", 4)->first();
        return view('page', $data);
    }

    function terms()
    {
        $data['page'] = DB::table('tbl_pages')->where("id", 2)->first();
        return view('page', $data);
    }
    function auto_search(Request $request)
    {
        $seach_data = $request->val;
        if (Sentinel::check()) {
            if (Sentinel::getUser()->roles()->first()->slug == 'seller') {
                $all_products = DB::table('services')->where('status', '1')->select('id', 'service_name', 'service_price', 'seller_price as sale_price', 'image')
                    ->where('service_name', 'like', '%' . $seach_data . '%')
                    ->get();
            } else {
                $all_products = DB::table('services')->where('status', '1')->where('service_name', 'like', '%' . $seach_data . '%')->get();
            }
        } else {

            $all_products = DB::table('services')->where('status', '1')->where('service_name', 'like', '%' . $seach_data . '%')->get();
        }
        foreach ($all_products as $val) {
            echo '<li><a href="/product_details/' . $val->id . '">' . $val->service_name . '</a></li>';
        }
    }
}
