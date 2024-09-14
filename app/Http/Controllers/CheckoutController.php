<?php



namespace App\Http\Controllers;

use Session;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Category as Category;
use App\Http\Controllers\shiproket\ShiproketController;

class CheckoutController extends Controller
{

    protected $shiproket;
    public function __construct(ShiproketController $shiproketController)
    {

        $this->shiproket = $shiproketController;
    }


    public function giftwrap_add(Request $request)
    {

        $userId = Sentinel::getUser()->id;

        $settings = DB::table('settings')->select('id', 'gift_wrap')->first();

        if ($request->type == 'add') {
            DB::table('apply_wrap')->insert([

                "ptoduct_id" => $request->product_id,
                "item_id" => $request->item_id,
                "price" => $settings->gift_wrap,
                "user_id" => $userId
            ]);

            return "success";
        } else {

            DB::table('apply_wrap')->where('ptoduct_id', $request->product_id)
                ->where('item_id', $request->item_id)
                ->where('user_id', $userId)
                ->delete();

            return "remove";
        }
    }



    public function remove_coupon_to_web()
    {


        $settings = DB::table('settings')->first();

        $endcode_settings = json_decode($settings->value);



        Session::forget('shoping_charge_cart');



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



        return back()->with('success', 'coupon remove successfully..');
    }



    function checkout()
    {

        $cart = Session::get('carts');

        //dd($cart);

        //dd(Sentinel::getUser()->zip_code);

        if (!Sentinel::check()) {
            return Redirect('/login');
        }

        if (empty($cart)) {
            return Redirect('/login');
        }

        if (Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
        } else {
            $user_id = 0;
        }

        if (isset($_COOKIE['setPincode'])) {
            if ($_COOKIE['setPincode'] > 0) {
                $hasPincode = $_COOKIE['setPincode'];
            } else {

                $usaerAddress = Db::table('tbl_address')->where('user_id', $user_id)->first();
                if (!empty($usaerAddress)) {
                    $hasPincode = $usaerAddress->zipcode;
                } else {
                    $hasPincode = Sentinel::getUser()->zip_code;
                }
            }
        } else {
            $hasPincode = 110016;
        }

        $tokenHas = $this->shiproket->newToken();

        $allPincode = [];
        $productID = [];
        $totalShipping = 0;
        foreach ($cart as $key => $c) {

            $productID[$c['id']] = $c['id'];
        }

        foreach ($productID as $k => $value) {

            $productCheckWhichVandor = DB::table('services')
                ->select('id', 'add_by')
                ->where('id', $value)->first();
            $pickupPin = DB::table('users')
                ->select('id', 'zip_code')
                ->where('id', $productCheckWhichVandor->add_by)
                ->first();

            //dd($pickupPin);                

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/courier/serviceability?pickup_postcode=" . $pickupPin->zip_code . "&delivery_postcode=" . $hasPincode . "&cod=1&weight=0.5",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer " . $tokenHas
                ),
            ));

            $response = curl_exec($curl);
            $newdata = json_decode($response);
            curl_close($curl);

            $ship_charge = $newdata->data->available_courier_companies[0]->rate;
            $totalShipping += $ship_charge;
        }

        $data["shipping_charge"] = $totalShipping;

        $data['user_address'] = DB::table('tbl_address')->where('user_id', $user_id)->get();
        $data['states'] = DB::table('states')->where('country_id', 101)->get();

        return view('checkout', $data);
    }





    public function addTocart_by_buynow($productId, $itemId)
    {



        $product = DB::table('services')

            ->where("services.id", $productId)

            ->where("product_items.id", $itemId)

            ->select("services.id", "product_items.id as item_id", "services.service_name", "product_items.item_mrp_price as service_price", "product_items.item_unit as unit", "product_items.item_unit_value as unit_value", "product_items.item_price as sale_price", "product_items.image")

            ->leftJoin('product_items', 'services.id', '=', 'product_items.product_id')

            ->first();



        $cart = array();



        if (!empty($product)) {

            $products = array();



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

                $cart[$product['id'] . $product['item_id']] = $product;

                Session::put('carts', $cart);
            }
        }

        $cart = Session::get('carts');

        $status = false;

        if (count($cart)) {

            $status = true;
        }

        return true;
    }




    public function buy_now_checkout()
    {

        // dd(url()->previous());

        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $data['user_address'] = DB::table('tbl_address')->where('user_id', $user_id)->get();
        }

        $product_id = '';
        $item_id = '';

        if (request()->has('product_id')) {

            $product_id = base64_decode(request('product_id'));
        }

        if (request()->has('item_id')) {

            $item_id = base64_decode(request('item_id'));
        }

        $this->addTocart_by_buynow($product_id, $item_id);

        $data['product'] = DB::table('services')->where('id', $product_id)->first();
        $data['item'] = DB::table('product_items')->where('id', $item_id)->first();
        $data['states'] = DB::table('states')->where('country_id', 101)->get();

        //dd($data);

        return view('frontend.buy_now', $data);
    }
}
