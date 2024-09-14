<?php

namespace App\Http\Controllers\shiproket;

use Illuminate\Http\Request;
use App\Models\Category_model as Category_model;
use DB;
use Sentinel;
use Session;
use App\Models\Order;
use App\Models\OrderItems;

class ShiproketController
{

  public function generateToken(){

      date_default_timezone_set('Asia/Kolkata');
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/auth/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\n    \"email\": \"alokdadariya@gmail.com\",\n    \"password\": \"anbshopping@12345\"\n}",
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/json"
        ),
      ));
      $response   = curl_exec($curl);
      curl_close($curl);
      $newData    =  json_decode($response);

      $token      = $newData->token;
      $auth_id    = $newData->id;
      $first_name = $newData->first_name;
      $last_name  = $newData->last_name;
      $email      = $newData->email;
      $company_id = $newData->company_id;

      DB::table('shiprocket_token')->where('id',1)->update([
        'token'=>$token, 
        'auth_id'=>$auth_id, 
        'first_name'=>$first_name, 
        'last_name'=>$last_name, 
        'email'=>$email, 
        'company_id'=>$company_id, 
        'added_on'=>date('Y-m-d h:i:s')
      ]);

      return $token;
  }

  public function newToken(){

    date_default_timezone_set('Asia/Kolkata');
      $shiprocket_token   = DB::table('shiprocket_token')->first();
      $added_on           = strtotime($shiprocket_token->added_on);
      $current_time       = strtotime(date('Y-m-d h:i:s'));
      $diff_time          = $current_time-$added_on;
      $token              = '';
      if($diff_time > 86400){
        $token = $this->generateToken();
      }else{
        $token = $shiprocket_token->token;
      }

      return $token;
  }
  
  public function validTokenGenrateShiproket($item_id){

      date_default_timezone_set('Asia/Kolkata');
      $shiprocket_token   = DB::table('shiprocket_token')->first();
      $added_on           = strtotime($shiprocket_token->added_on);
      $current_time       = strtotime(date('Y-m-d h:i:s'));
      $diff_time          = $current_time-$added_on;
      $token              = '';
      if($diff_time > 86400){
        $token = $this->generateToken();
      }else{
        $token = $shiprocket_token->token;
      }

      $newValue = $this->order_place_shiproket($token, $item_id);
      return $newValue;
  }


  public function order_place_shiproket($token,$order_item_id){

      $orderItems = DB::table('order_items')->where('id',$order_item_id)->first();
      if(!empty($orderItems)){
          $data = explode('_', $orderItems->sub_order_id);
          $orderShiped = DB::table('order_shiped')->where('sub_order_id',$orderItems->sub_order_id)->first();
          
          if(empty($orderShiped)){
             $productName = DB::table('tbl_services')->select('id','service_name')
                            ->where('id',$orderItems->product_id)->first()->service_name;
              DB::table('order_shiped')->insert([
                "order_id" => @$orderItems->order_id,
                "sub_order_id" => @$orderItems->sub_order_id,
                "user_id" => 000,
                "vendor_id" => @$data[1],
                "order_item_id" => @$order_item_id,
                "product_item_id" => @$orderItems->item_id,
                "product_id" => @$orderItems->product_id,
                "product_name" => @$productName,
                "status" => 'shipped'
              ]);

              $subOrderId     = $orderItems->sub_order_id;
              $orderId         = $orderItems->order_id;
              $productId       = $orderItems->product_id;
              $productItemId  = $orderItems->item_id;

              $orderGenerateReturn = $this->orderGenrateOnShiproket($token,$subOrderId,$orderId,$orderItems,$productId,$productItemId);
              return $orderGenerateReturn;
          }else{
            return 'already order_shiped';
          }
      }else{

        return 'no order_items';
      }
  }


  public function orderGenrateOnShiproket($token,$subOrderId,$orderId,$orderItems,$productId,$productItemId){

      $orders        = DB::table('orders')->where('id',$orderId)->first();
      $product       = DB::table('tbl_services')->where('id',$productId)->first();
      $product_item  = DB::table('product_items')->where('id',$productItemId)->first();
      $userAddress   = DB::table('tbl_address')->where('id', @$orders->user_address)->first();

      $order_date_str         = strtotime($orders->created_at);
      $order_date             = date('Y-m-d h:i',$order_date_str);

      if($orders->payment_method =='cod'){
        $payment_method = 'COD';
      }else{
        $payment_method = 'Prepaid';
      }

      $order_id               = @$orderItems->sub_order_id;
      $order_date             = $order_date;
      $pickup_location        = "aaaaa";
      $channel_id             = "";
      $comment                = "";
      $billing_customer_name  = @$userAddress->name;
      $billing_last_name      = "";
      $billing_address        = @$userAddress->address;
      $billing_address_2      = "";
      $billing_city           = @$userAddress->city;
      $billing_pincode        = @$userAddress->zipcode;
      $billing_state          = isset($userAddress->state)?$userAddress->state:'delhi';
      $billing_country        = @$userAddress->country;
      $billing_email          = @$userAddress->email;
      $billing_phone          = @$userAddress->phone;

      $name                   = @$product->service_name;
      $sku                    = @$orderItems->sku;
      $units                  = @$orderItems->quantity;
      $selling_price          = @$orderItems->price;
      $discount               = "";
      $tax                    = "";
      $hsn                    = "";

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/create/adhoc",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS =>'{
                "order_id": "'.$order_id.'",
                "order_date": "'.$order_date.'",
                "pickup_location": "'.$pickup_location.'",
                "channel_id": "'.$channel_id.'",
                "comment": "'.$comment.'",
                "billing_customer_name": "'.$billing_customer_name.'",
                "billing_last_name": "'.$billing_last_name.'",
                "billing_address": "'.$billing_address.'",
                "billing_address_2": "'.$billing_address_2.'",
                "billing_city": "'.$billing_city.'",
                "billing_pincode": "'.$billing_pincode.'",
                "billing_state": "'.$billing_state.'",
                "billing_country": "'.$billing_country.'",
                "billing_email": "'.$billing_email.'",
                "billing_phone": "'.$billing_phone.'",
                "shipping_is_billing": true,
                "shipping_customer_name": "",
                "shipping_last_name": "",
                "shipping_address": "",
                "shipping_address_2": "",
                "shipping_city": "",
                "shipping_pincode": "",
                "shipping_country": "",
                "shipping_state": "",
                "shipping_email": "",
                "shipping_phone": "",
                "order_items": [
                      {
                        "name": "'.$name.'",
                        "sku": "'.$sku.'",
                        "units": "'.$units.'",
                        "selling_price": "'.$selling_price.'",
                        "discount": "'.$discount.'",
                        "tax": "'.$tax.'",
                        "hsn": "'.$hsn.'"
                      }
                  ],
                  "payment_method": "'.$payment_method.'",
                  "shipping_charges": 0,
                  "giftwrap_charges": 0,
                  "transaction_charges": 0,
                  "total_discount": 0,
                  "sub_total": "'.$selling_price.'",
                  "length": 15,
                  "breadth": 20,
                  "height": 10,
                  "weight": 2.5
                }',
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/json",
          "Authorization: Bearer ".$token
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $responseData    =  json_decode($response);
      DB::table('order_track')->insert([
        "shiproket_order_id"       => $responseData->order_id,
        "order_item_id"            => @$orderItems->id,
        "sub_order_id"             => @$orderItems->sub_order_id,
        "shipment_id"              => $responseData->shipment_id,
        "courier_name"             => $responseData->courier_name,
        "courier_company_id"       => $responseData->courier_company_id,
        "awb_code"                 => $responseData->awb_code,
        "status_code"              => $responseData->status_code,
        "status"                   => $responseData->status,
        "onboarding_completed_now" => $responseData->onboarding_completed_now
      ]);

      return "success";

  }

  
}