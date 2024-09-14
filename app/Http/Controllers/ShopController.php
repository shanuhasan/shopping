<?php

namespace App\Http\Controllers;

use Cookie;
use Session;
use Sentinel;
use App\Models\ServiceModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Category as Category;
use App\Models\ProductItem;
use App\Models\Service;

class ShopController extends Controller
{
    public function addToWishlist(Request $request)
    {


        if (Sentinel::check()) {



            $user = Sentinel::getUser();



            DB::table('wishlists')->insert([



                "product_id" => $request->productid,

                "app_id" => $user->id



            ]);



            return response()->json([

                "status" => "errro",

                "message" => "Item add wishlist successfully."

            ]);
        } else {



            return response()->json([

                "status" => "error",

                "message" => "Please First login your account."

            ]);
        }
    }



    public function coupon_apply_to_web(Request $request)
    {



        $user = Sentinel::getUser();



        $cart = Session::get('carts');

        $amount = [];

        foreach ($cart as $c) {



            $amount[] = $c['sale_price'];
        }



        $coupon_code = $request->coupon_code;



        $shoping_charge_cart = Session::get('shoping_charge_cart');



        // dd($shoping_charge_cart['gst_per']);



        $date = date('Y-m-d');

        $result = DB::table('coupons')->where('coupon_code', $coupon_code)->where('expiry_date', '>=', $date)->first();

        if ($result) {



            $couponCount = DB::table('orders')

                ->where('user_id', $user->id)

                ->where('coupon_id', $result->id)

                ->count();



            if ($couponCount > $result->per_user) {



                echo json_encode(array('status' => 'success', 'Message' => 'coupon already use multtime..'));
            } else {



                $totalSum = array_sum($amount);



                $gstPer = 100 + $shoping_charge_cart['gst_per'];

                $basamount = ($totalSum * 100) / $gstPer;



                $val = $result->value;

                $coupon_discount_peice = $basamount - ($basamount * $val / 100);

                $coupon_discount = $basamount - $coupon_discount_peice;



                $shoping_charge_cart['coupon_value'] = $result->value;

                $shoping_charge_cart['coupon_discount'] = $coupon_discount;

                $shoping_charge_cart['coupon_id'] = $result->id;

                $shoping_charge_cart['coupon_code'] = $result->coupon_code;

                $shoping_charge_cart['base_amount'] = $basamount;



                Session::put('shoping_charge_cart', $shoping_charge_cart);



                echo json_encode(array('status' => 'success', 'Message' => 'coupon apply successfully..', "data" => $shoping_charge_cart));



                //   Session::flash('success', 'coupon apply successfully..'); 

                //   return back();

            }
        } else {



            echo json_encode(array('status' => 'success', 'Message' => 'Coupon is expiry'));
        }
    }





    public function delivery_product_to_pincode(Request $request)
    {


        $chkAvi = DB::table('product_area_pincode')
            ->where('product_id', base64_decode($request->product_id))
            ->where('pincode', $request->pincode)
            ->get();

        if ($request->pincode) {
            $setPincode = $request->pincode;
        } else {
            $setPincode = 0;
        }

        setcookie("setPincode", $setPincode, strtotime('+30 days'), '/');

        if (count($chkAvi) > 0) {

            return response()->json(["status" => "success", "msg" => "Available on this pincode"]);
        } else {

            return response()->json(["status" => "error", "msg" => "Not available on this pincode"]);
        }
    }



    public function ajax_item_price(Request $request)
    {



        $first_itmes = ProductItem::where('id', $request->itemid)->whereNotIn('type', ['bulk'])->first();

        return response()->json([

            "status" => "success",

            "data" => $first_itmes



        ]);
    }

    function shop($id = '')
    {
        $data['category'] = Category::all();

        if ($id) {
            $all_products = Service::where('status', '1')->where('parent_category', $id)->get();
        } else {
            $all_products = Service::where('status', '1')->get();
        }

        $product_array = array();

        foreach ($all_products as $products) {

            $itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->get();
            $first_itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();

            $product_array[] = array(
                'id' => $products->id,
                'image' => $products->image,
                'service_name' => $products->service_name,
                'seller_price' => $products->seller_price,
                'sale_price' => @$first_itmes->item_price,
                'item_id' => @$first_itmes->id,
                'service_price' => @$first_itmes->item_mrp_price,
                'unit_value' => @$first_itmes->item_unit_value,
                'unit' => @$first_itmes->item_unit,
                'items' => $itmes,
            );
        }

        $data['all_products'] = $product_array;

        //dd($data['all_products']);

        return view('frontend.shop-1', $data);
    }

    function product_list($slug = '', $sluge2 = '', $sluge3 = '')
    {
        $main_category = Category::select('id', 'slug', 'category_name as name')->where('parent_id', 0)->where('status', 1)->get();
        $check = $sluge3 !== '' ? $sluge3 : $sluge2;
        $check = $check !== '' ? $check : $slug;
        $slug_url = Category::where('slug', $check)->first();

        if ($slug_url) {

            $m_service = Service::where('services.parent_category', $slug_url->id)
                ->orWhere('services.subcategory', $slug_url->id)
                ->orWhere('services.child_id', $slug_url->id);

            $short = request()->has('short');
            $max_count = request()->has('max_count');

            if ($short) {
                $value = request('short');
                if ($value == 'priceLowHigh') {
                    $m_service = $m_service->join('product_items', 'product_items.product_id', '=', 'services.id')
                        ->select('services.*', 'product_items.product_id as prod_id', 'product_items.item_price as product_item_price', 'product_items.id as product_item_id')
                        ->groupBy('prod_id', 'product_item_price', 'product_item_id')
                        ->orderBy('product_items.item_price', 'ASC');
                } elseif ($value == 'priceHighLow') {

                    $m_service = $m_service->join('product_items', 'services.id', '=', 'product_items.product_id')
                        ->select('services.*', 'product_items.product_id', 'product_items.item_price', 'product_items.id as product_item_id')
                        ->groupBy('services.id', 'product_items.item_price', 'product_items.product_id', 'product_item_id')
                        ->orderByDesc('product_items.item_price');
                } elseif ($value == 'newest') {
                    $m_service = $m_service->orderBy('services.id', 'DESC');
                }
            }

            if ($max_count) {
                $max_count_value = request('max_count');
                $m_service = $m_service->take($max_count_value);
            }

            $finalData = $m_service->get()->map(function ($finalData) {
                $finalData->items = ProductItem::where('product_id', $finalData->id)->first();
                return $finalData;
            });

            $data['category_by_product'] = $finalData;
            $data['category_banner'] = $slug_url;
            $data['main_category'] = $main_category;
            $data['page_title'] = 'Product List';
            return view('frontend.product_list', $data);
        } else {
            return redirect('/');
        }
    }


    public function get_product_by_slug($product_data)
    {

        $product = array();

        $itmes = ProductItem::where('product_id', $product_data->id)->whereNotIn('type', ['bulk'])->get();

        $first_itmes = ProductItem::where('product_id', $product_data->id)->whereNotIn('type', ['bulk'])->first();

        $category = Category::where('id', $product_data->parent_category)->first();

        $slider_mage = DB::table('product_images')->where('product_id', $product_data->id)->get();



        return array(

            'id' => $product_data->id,

            'slug' => $product_data->slug,

            'image' => @$product_data->image,

            'category' => @$category->category_name,
            'category_slug' => @$category->slug,

            'name' => $product_data->name,

            'description' => @$product_data->description,

            'seller_price' => @$product_data->seller_price,

            'sale_price' => @$first_itmes->item_price,

            'item_id' => @$first_itmes->id,

            'price' => @$first_itmes->item_mrp_price,

            'unit_value' => @$first_itmes->item_unit_value,

            'unit' => @$first_itmes->item_unit,

            'stock' => @$first_itmes->stock > 0 ? 'In stock' : 'out of stock',

            'color' => @$first_itmes->color != null ? 'yes' : 'no',

            'size' => @$first_itmes->size  != null ? 'yes' : 'no',

            'product_slider' => @$slider_mage,

            'items' => $itmes

        );



        //return $product;

    }



    public function best_saller_product()
    {



        //COALESCE(((((service_price-sale_price))/service_price)*100),0) as discount

        $best_seller_product = Service::where('type_id', 1)

            ->select(DB::raw("

        		            id,

        		            slug,

        		            service_name as name,

        		            if(image IS NOT NUll,concat('/uploads/service/',image),'') as image,

        		            service_price as price,

        		            sale_price as sale_price,

        		            seller_price

        		            "))

            ->get();



        foreach ($best_seller_product as $product_data) {



            $first_itmes = ProductItem::where('product_id', $product_data->id)->whereNotIn('type', ['bulk'])->first();

            $product[] = array(

                'id' => $product_data->id,
                'image' => $product_data->image,
                'name' => $product_data->name,
                'slug' => $product_data->slug,
                'seller_price' => $product_data->seller_price,
                'sale_price' => @$first_itmes->item_price,
                'item_id' => @$first_itmes->id,
                'price' => @$first_itmes->item_mrp_price,

            );
        }

        return $product;
    }





    public function related_product($param)
    {



        $product = [];



        if (!empty($param)) {



            $best_seller_product = Service::whereIn('id', $param)

                ->select(DB::raw("

        		            id,

        		            slug,

        		            service_name as name,

        		            if(image IS NOT NUll,concat('/uploads/service/',image),'') as image,

        		            service_price as price,

        		            sale_price as sale_price,

        		            seller_price

        		            "))

                ->get();



            //dd($best_seller_product);



            foreach ($best_seller_product as $product_data) {



                $first_itmes = ProductItem::where('product_id', $product_data->id)->whereNotIn('type', ['bulk'])->first();

                $product[] = array(

                    'id' => $product_data->id,

                    'image' => $product_data->image,

                    'name' => $product_data->name,
                    'slug' => $product_data->slug,

                    'seller_price' => $product_data->seller_price,

                    'sale_price' => @$first_itmes->item_price,

                    'item_id' => @$first_itmes->id,

                    'price' => @$first_itmes->item_mrp_price,

                );
            }





            return $product;
        } else {

            return $product = [];
        }
    }



    public function setCookie($data)
    {



        if (isset($_COOKIE["recent_view"])) {



            $da = json_decode($_COOKIE["recent_view"]);

            if (in_array($data, $da)) {

                return;
            } else {



                $da[] = $data;

                $jsonencode = json_encode($da);

                setcookie("recent_view", $jsonencode, strtotime('+30 days'), '/');
            }
        } else {



            $hasValue[] = $data;

            $jsonencode = json_encode($hasValue);

            setcookie("recent_view", $jsonencode, strtotime('+30 days'), '/');
        }
    }



    function productDetails($slug = '')
    {
        $this->setCookie($slug);
        // unset($_COOKIE['recent_view']); 
        // setcookie('recent_view', null, -1);
        //dd(json_decode($_COOKIE["recent_view"]));
        $product = Service::where('slug', $slug)
            ->select(DB::raw("id,slug,parent_category,service_name as name,if(image IS NOT NUll,concat('/uploads/service/',image),'') as image,
        		            description,service_price as price,sale_price as sale_price,seller_price,unit,unit_value,stock,related_product,
        		            COALESCE(((((service_price-sale_price))/service_price)*100),0) as discount"))->first();

        $data['product_details'] = $this->get_product_by_slug($product);
        $data['best_seller_product'] = $this->best_saller_product();
        $related_product = json_decode($product->related_product);
        $data['related_product'] = $this->related_product($related_product);
        // dd($data);
        $data['page_title'] = 'Product Details';
        return view('frontend.product_details', $data);
    }













    public function addToCart(Request $request)
    {



        // $items = null;

        // $totapQty = 0;

        // $totalPrice = 0;



        // $oldCart = Session::has('cart') ? Session::get('cart') : null



        // $items = $oldCart->items;

        // $totapQty = $oldCart->totapQty;

        // $totalPrice = $oldCart->totalPrice;



        // $product_id = $request->product_id;

        // $item_id = $request->item_id;





        // $storeItem = ['qty'=>0, 'price'=>0, item=>$items];



        // if($items){

        //     if(array_key_exists($product_id, $items)){



        //         $storeItem = $items[$product_id];

        //     }

        // }



        // $storeItem['qty']++; 

        // $storeItem['price'] = 200 * $storeItem['qty'];



        // $items[$product_id] = $storeItem;

        // $totapQty++;

        // $totalPrice += 200;





        // die;



        $cart = [];



        if (Session::get('cart')) {



            $cart = Session::get('cart');



            return $cart[$request->product_id];



            die;



            if ($cart[$request->product_id] == $request->product_id) {



                return "yes";



                $Qty =  $cart[$request->product_id]['qty'] += 1;



                $cartStore = [



                    "product_id" => $cart[$request->product_id]['product_id'],

                    "item_id" => $cart[$request->product_id]['item_id'],

                    "qty" => $Qty,

                    "price" => 1

                ];



                $cart[$request->product_id] = $cartStore;

                $cart['totalQty'] += 1;

                $cart['totalPrice'] += 200;



                Session::put('cart', $cart);



                $cart = Session::get('cart');

                return $cart;
            } else {



                return "no";



                $cartStore = [



                    "product_id" => $request->product_id,

                    "item_id" => $request->item_id,

                    "qty" => 1,

                    "price" => 1

                ];



                $cart[$request->product_id] = $cartStore;

                $cart['totalQty'] = 1;

                $cart['totalPrice'] = 200;



                Session::put('cart', $cart);

                return $cart;
            }
        } else {





            $cartStore = [



                "product_id" => $request->product_id,

                "item_id" => $request->item_id,

                "qty" => 1,

                "price" => 1

            ];



            $cart[$request->product_id] = $cartStore;

            $cart['totalQty'] = 1;

            $cart['totalPrice'] = 200;



            Session::put('cart', $cart);



            return $cart;
        }
    }







    function vendor_product($id = '')
    {

        $data['category'] = Category_model::all();



        $all_products = DB::table('services')->where('status', '1')->where('add_by', $id)->get();





        $product_array = array();

        foreach ($all_products as $products) {

            $itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->get();
            $first_itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();

            $product_array[] = array(

                'id' => $products->id,

                'image' => $products->image,

                'service_name' => $products->service_name,

                'seller_price' => $products->seller_price,

                'sale_price' => @$first_itmes->item_price,

                'item_id' => @$first_itmes->id,

                'service_price' => @$first_itmes->item_mrp_price,

                'unit_value' => @$first_itmes->item_unit_value,

                'unit' => @$first_itmes->item_unit,

                'items' => $itmes,

            );
        }

        $data['all_products'] = $product_array;

        //dd($data['all_products']);

        return view('shop-1', $data);
    }
    function bulk_order()
    {

        if (Sentinel::check()) {

            if (Sentinel::getUser()->roles()->first()->slug != 'seller') {

                Session::flash('error', 'You are not our premium member');

                return redirect('shop');
            }
        } else {

            Session::flash('error', 'You are not our premium member');

            return redirect('shop');
        }

        $data['category'] = Category_model::all();

        $all_products = DB::table('services')->where('status', '1')->get();

        $product_array = array();

        foreach ($all_products as $products) {

            $itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['normal'])->get();
            $first_itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['normal'])->first();

            $product_array[] = array(

                'id' => $products->id,

                'image' => $products->image,

                'service_name' => $products->service_name,

                'seller_price' => $products->seller_price,

                'sale_price' => @$first_itmes->item_price,

                'item_id' => @$first_itmes->id,

                'service_price' => @$first_itmes->item_mrp_price,

                'unit_value' => @$first_itmes->item_unit_value,

                'unit' => @$first_itmes->item_unit,

                'items' => $itmes,

            );
        }

        $data['all_products'] = $product_array;

        //dd($data['all_products']);

        return view('bulk_purchase', $data);
    }



    function search_product(Request $request)
    {

        $product_array = array();
        $gerUrl = request('q');
        $seach_data = $gerUrl;

        $data['category'] = Category_model::all();

        if (request()->has('q')) {
            if (request('q') != null) {

                $all_products = DB::table('services')->where('service_name', 'like', '%' . $seach_data . '%')->where('status', '1')->get();

                foreach ($all_products as $products) {

                    $itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->get();
                    $first_itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();

                    $product_array[] = array(
                        'id' => $products->id,
                        'image' => $products->image,
                        'slug' => $products->slug,
                        'service_name' => $products->service_name,
                        'item_price' => @$first_itmes->item_price,
                        'item_mrp_price' => @$first_itmes->item_mrp_price,
                    );
                }
            } else {
                $product_array = $product_array;
            }
        } else {
            $product_array = $product_array;
        }

        $data['all_products'] = $product_array;
        $data['search_value'] = $seach_data;

        return view('shop-1', $data);
    }



    function product_details1($id)
    {

        $data['category'] = Category_model::all();


        $data['product_details'] = DB::table('services')->where('id', $id)->first();

        if (Sentinel::check()) {

            if (Sentinel::getUser()->roles()->first()->slug != 'seller') {

                $data['items'] = ProductItem::where('product_id', $id)->whereNotIn('type', ['bulk'])->get();

                $data['first_items'] = ProductItem::where('product_id', $id)->whereNotIn('type', ['bulk'])->first();
            } else {

                $data['items'] = ProductItem::where('product_id', $id)->whereNotIn('type', ['normal'])->get();

                $data['first_items'] = ProductItem::where('product_id', $id)->whereNotIn('type', ['normal'])->first();
            }
        } else {

            $data['items'] = ProductItem::where('product_id', $id)->whereNotIn('type', ['bulk'])->get();

            $data['first_items'] = ProductItem::where('product_id', $id)->whereNotIn('type', ['bulk'])->first();
        }

        $related_product = array();

        if (json_decode($data['product_details']->related_product)) {

            $related_product1 = DB::table('services')->whereIn('id', json_decode($data['product_details']->related_product))->get();

            $product_array = array();

            foreach ($related_product1 as $products) {

                if (Sentinel::check()) {

                    if (Sentinel::getUser()->roles()->first()->slug != 'seller') {

                        $itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->get();
                        $first_itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();
                    } else {

                        $itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['normal'])->get();
                        $first_itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['normal'])->first();
                    }
                } else {

                    $itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->get();
                    $first_itmes = ProductItem::where('product_id', $products->id)->whereNotIn('type', ['bulk'])->first();
                }

                $related_product[] = array(

                    'id' => $products->id,

                    'image' => $products->image,

                    'service_name' => $products->service_name,

                    'seller_price' => $products->seller_price,

                    'sale_price' => @$first_itmes->item_price,

                    'item_id' => @$first_itmes->id,

                    'service_price' => @$first_itmes->item_mrp_price,

                    'unit_value' => @$first_itmes->item_unit_value,

                    'unit' => @$first_itmes->item_unit,

                    'items' => $itmes,

                );
            }
        }



        $data['category_details'] = Category::where('id', $data['product_details']->parent_category)->first();

        $data['related_product'] = $related_product;

        $data['product_images'] = DB::table('product_images')->where('product_id', $id)->get();

        //dd($data['all_products']);

        return view('product_details', $data);
    }

    function get_category_by_product(Request $request)
    {

        $cat_id = $request->val;

        $cat_data = '';

        $cat_data = DB::table('services')->where('status', '1')->where('parent_category', $cat_id)->get();

        if ($cat_data) {
        } else {

            $cat_data = DB::table('services')->where('status', '1')->where('subcategory', $cat_id)->get();
        }

        foreach ($cat_data as $value) {

            $itmes = ProductItem::where('product_id', $value->id)->whereNotIn('type', ['bulk'])->get();
            $first_itmes = ProductItem::where('product_id', $value->id)->whereNotIn('type', ['bulk'])->first();

            $option = '';

            foreach ($itmes as $dropdown_items) {

                $option .= '<option value="' . $dropdown_items->item_unit_value . ' ' . $dropdown_items->item_unit . ' ' . $dropdown_items->item_price . ' ' . $dropdown_items->item_mrp_price . ' ' . $dropdown_items->id . '"> ' . $dropdown_items->item_unit_value . ' ' . $dropdown_items->item_unit . ' ' . $dropdown_items->item_price . '</option>';
            }

            echo '

             <div class="col-lg-3 col-md-4 col-sm-6"> 

                                   <div class="product-box">

                                   <a href="/product_details/' . $value->id . '">

                                            <div class="product-media"> 

                                                 <img class="prod-img" alt="" src="' . asset('/') . 'uploads/service/' . $value->image . '" />     

                                               

                                            </div>                                   </div>        

                                            <div class="product-caption"> 

                                                <h3 class="product-title">

                                                    <a href="/product_details/' . $value->id . '">   <strong>' . $value->service_name . '</strong></a>

                                                </h3>

                                                <div class="price"> 

                                                    <strong class="clr-txt"><i class="fa fa-inr" aria-hidden="true"></i> 

                                                    

                                                   <span class="sale_price' . $value->id . '">' . $first_itmes->item_price . '</span>

                                                  

                                                    </strong> <del class="light-font"><i class="fa fa-inr" aria-hidden="true"></i> <span class="mrp_price' . $value->id . '">' . $first_itmes->item_mrp_price . '<span> </del></br>

                                                  Qty: <span class="unit_value' . $value->id . '">' . $first_itmes->item_unit_value . '</span> <span class="unit' . $value->id . '">' . $first_itmes->item_unit . '</span>

                                                  

                                                </div>

                                                <span class="item_id' . $value->id . '" style="display:none">' . $first_itmes->id . '</span>

                                                <select class="dropdown_items" data-id="' . $value->id . '">

                                                    ' . $option . '

                                                </select>

                                               <div class="add-cart pt-15">

                                                <a href="javaScript:void(0)" class="theme-btn btn add_tocart" data-id="' . $value->id . '"> <strong> ADD TO CART </strong> </a>

                                            </div>

                                            </div>

                                        </div>

                                </div>

           ';
        }
    }
}
