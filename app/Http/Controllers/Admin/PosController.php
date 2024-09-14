<?php

namespace App\Http\Controllers\admin;

use App\Models\Banner;
use App\Models\Category_model;
use App\Models\ServiceModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;
use Session;
use Validator;
use DB;
use App\User;

class PosController extends Controller
{
    function base_url(){
        $base_url= $_SERVER["DOCUMENT_ROOT"]; 
        return $base_url;
    }
    
    public function index()
    {
       $data['category']=Category_model::all();
		$all_products=ServiceModel::where('status','1')->get();
		$product_array=array();
		foreach($all_products as $products){
		    $itmes=DB::table('product_items')->where('product_id',$products->id)->whereNotIn('type', ['bulk'])->get();  
		    $first_itmes=DB::table('product_items')->where('product_id',$products->id)->whereNotIn('type', ['bulk'])->first();
		   $product_array[]=array(
		   'id'=>$products->id,
		    'image'=>$products->image,
		    'service_name'=>$products->service_name,
		    'seller_price'=>$products->seller_price,
		    'sale_price'=>@$first_itmes->item_price,
		    'item_id'=>@$first_itmes->id,
		    'service_price'=>@$first_itmes->item_mrp_price,
		    'unit_value'=>@$first_itmes->item_unit_value,
		    'unit'=>@$first_itmes->item_unit,
		    'color'=>@$first_itmes->color,
		    'size'=>@$first_itmes->size,
		    'items'=>$itmes,
		    );
		}
		

        $role_customer=Sentinel::findRoleBySlug('customer');
        $customer = $role_customer->users()->orderBy('id', 'DESC')->get(); 
        $data['customer']=$customer;
		$data['all_products']=$product_array;
        $data['page_title']='Pos';
        return view('admin/pos/pos',$data);
    }
    
    public function pos_create(Request $request){
        
        //dd($request->all());
        
           $validator = Validator::make($request->all(), [
                'email' => 'required|unique:users|email',
                'first_name'=>'required'
            ]);        
        
            if ($validator->fails()):        
                return redirect()->with('error','Some Field are missing...')->back();
            endif;
        
        $user = Sentinel::registerAndActivate([
                        "first_name" => $request->first_name,
                        "last_name" => $request->last_name,
                        "email" => $request->email,
                        "password" => "Secret@12345"
                ]);
                
        
        $data = array(
                  "phone"=>$request->phone,
                  "line_1"=>$request->line_1,
                  "zip_code"=>$request->zip_code
                );
        
        User::where("id",$user->id)->update($data);
        
        $role = Sentinel::findRoleById('5');
        $users_check = $role->users()->attach($user); 
        
        Session::flash('message', 'Customer Created Successfully...'); 
        return back();
        
    }
    
    

    function createorders(Request $request){
        $user_id=$request->customers;
        $user_details=DB::table('users')->where('id',$user_id)->first();
        $order_data=array(
            'user_id'=>$user_id,
            'email'=>$user_details->email,
            'phone'=>$user_details->phone,
            'address'=>$user_details->line_1,
            'order_discount'=>isset($request->discount)?$request->discount:0.00,
            'shipping'=>isset($request->shipping)?$request->shipping:0.00,
            'grand_total'=>$request->total_payble,
            'tax'=>0.00,
            'paid'=>0.00,
            'payment_status'=>'success',
            'status'=>'complete',
            'payment_method'=>'cod',
            'order_from'=>'pos',
            'date'=>date('Y-m-d H:i:s'),
            'estimated_delivery'=>date('Y-m-d H:i:s'),
            'name'=>$user_details->first_name .' '.$user_details->last_name,
            );
            $order_id=DB::table('orders')->insertGetId($order_data);
            $product_id=$request->product_id;
            $item_id=$request->item_id;
            $price=$request->price;
            $qty=$request->qty;
            $subtotal=$request->subtotal;
            foreach($item_id as $key => $item ){
            $item_data=array(
                'order_id'=>$order_id,
                'product_id'=>$product_id[$key],
                'item_id'=>$item,
                'price'=>$price[$key],
                'tax'=>0.00,
                'quantity'=>$qty[$key],
                'total'=>$subtotal[$key],
                'created_at'=>date('Y-m-d H:i:s'),
                );
                DB::table('order_items')->insert($item_data);
            }
            $orderid='ORD000'.$order_id;
            DB::table('orders')->where('id',$order_id)->update(['order_id'=>$orderid]);
            echo $order_id;
            
    }
    function filter_product_bycategory(Request $request){
        $cat_id=$request->id;
        //echo $cat_id; die;
        if($cat_id=='all'){
        	$all_products=ServiceModel::where('status','1')->get();
        	
        }else{
            $all_products=ServiceModel::where('status','1')->where('parent_category',$cat_id)->get();
        }
		$product_array=array();
		foreach($all_products as $products){
		    $itmes=DB::table('product_items')->where('product_id',$products->id)->whereNotIn('type', ['bulk'])->get();  
		    $first_itmes=DB::table('product_items')->where('product_id',$products->id)->whereNotIn('type', ['bulk'])->first();
		   $product_array[]=array(
		    'id'=>$products->id,
		    'image'=>$products->image,
		    'service_name'=>$products->service_name,
		    'seller_price'=>$products->seller_price,
		    'sale_price'=>@$first_itmes->item_price,
		    'item_id'=>@$first_itmes->id,
		    'service_price'=>@$first_itmes->item_mrp_price,
		    'unit_value'=>@$first_itmes->item_unit_value,
		    'unit'=>@$first_itmes->item_unit,
		    'color'=>@$first_itmes->color,
		    'size'=>@$first_itmes->size,
		    'items'=>$itmes,
		    );
		}
		//print_r($all_products); die;
		if($product_array){
		    foreach($product_array as $value){
		        $items='';
		        $items.='<div class="more_items'.$value['id'].'" style="display:none">';
                     foreach($value['items'] as $item){
                    $items.=' <div class="col-md-6 moretimes_item moreitems'.$value['id'].'"
                    id="item_'.$item->id.'" data-name="'.$value['service_name'].'" data-id="'.$value['id'].'" data-item_id="'.$item->id.'" data-units="'.$item->item_unit.'/'.$item->item_unit_value.'" data-price="'.$item->item_price.'" data-color="'.$item->color.'" data-size="'.$item->size.'" >
                   <img src="'.asset('/').'uploads/items/'.$item->image.'" width="100%">
                   <p class="item_price">Rs '.$item->item_price.'</p>
                   <p class="item_price">'.$item->item_unit.'/'.$item->item_unit_value.'</p>
                    <p class="item_name">'.$value['service_name'].'</p>
                     </div>';
                    } 
                                                   
                 $items.='</div>';
		    echo '
		     <div class="single_item fix" id="item_'.$value['item_id'].'" data-name="'.$value['service_name'].'" data-id="'.$value['id'].'" data-item_id="'.$value['item_id'].'" data-units="'.$value['unit'].'/'.$value['unit_value'].'" data-price="'.$value['sale_price'].'" data-color="'.$value['color'].'" data-size="'.$value['size'].'" >
                        <p class="item_price">Rs '.$value['sale_price'].'</p><img src="'.asset('/').'uploads/service/'.$value['image'].'" width="100%">
                <p class="item_name">'.$value['service_name'].'</p>
           
             '.$items.'
            </div>
		    ';
		    }
		}else{
		    echo "No Data found...";
		}
    }
    
    
function filter_product_byname(Request $request){
        $value=$request->value;
        //echo $cat_id; die;
        
            $all_products=ServiceModel::orWhere('service_name', 'like', '%' . $value . '%')->where('status','1')->get();
        
		$product_array=array();
		foreach($all_products as $products){
		    $itmes=DB::table('product_items')->where('product_id',$products->id)->whereNotIn('type', ['bulk'])->get();  
		    $first_itmes=DB::table('product_items')->where('product_id',$products->id)->whereNotIn('type', ['bulk'])->first();
		   $product_array[]=array(
		   'id'=>$products->id,
		    'image'=>$products->image,
		    'service_name'=>$products->service_name,
		    'seller_price'=>$products->seller_price,
		    'sale_price'=>@$first_itmes->item_price,
		    'item_id'=>@$first_itmes->id,
		    'service_price'=>@$first_itmes->item_mrp_price,
		    'unit_value'=>@$first_itmes->item_unit_value,
		    'unit'=>@$first_itmes->item_unit,
		    'color'=>@$first_itmes->color,
		    'size'=>@$first_itmes->size,
		    'items'=>$itmes,
		    );
		}
	
		if($product_array){
		    foreach($product_array as $value){
		        $items='';
		        $items.='<div class="more_items'.$value['id'].'" style="display:none">';
                     foreach($value['items'] as $item){
                    $items.=' <div class="col-md-6 moretimes_item moreitems'.$value['id'].'"
                    id="item_'.$item->id.'" data-name="'.$value['service_name'].'" data-id="'.$value['id'].'" data-item_id="'.$item->id.'" data-units="'.$item->item_unit.'/'.$item->item_unit_value.'" data-price="'.$item->item_price.'" data-color="'.$item->color.'" data-size="'.$item->size.'" >
                   <img src="'.asset('/').'uploads/items/'.$item->image.'" width="100%">
                   <p class="item_price">Rs '.$item->item_price.'</p>
                   <p class="item_price">'.$item->item_unit.'/'.$item->item_unit_value.'</p>
                    <p class="item_name">'.$value['service_name'].'</p>
                     </div>';
                    } 
                                                   
                 $items.='</div>';
		    echo '
		     <div class="single_item fix" id="item_'.$value['item_id'].'" data-name="'.$value['service_name'].'" data-id="'.$value['id'].'" data-item_id="'.$value['item_id'].'" data-units="'.$value['unit'].'/'.$value['unit_value'].'" data-price="'.$value['sale_price'].'" data-color="'.$value['color'].'" data-size="'.$value['size'].'" >
                        <p class="item_price">Rs '.$value['sale_price'].'</p><img src="'.asset('/').'uploads/service/'.$value['image'].'" width="100%">
                <p class="item_name">'.$value['service_name'].'</p>
                '.$items.'
            </div>
		    ';
		    }
		}else{
		    echo "No Data found...";
		}
    }

}
