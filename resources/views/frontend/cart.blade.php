 @extends('layouts.app')
 @section('content')

     <section class="breadcrumb-area">
         <div class="container">
             <div class="breadcrumb-content">
                 <ul>
                     <li><a href="{{ url('/') }}">Home</a></li>
                     <li>Cart</li>
                 </ul>
             </div>
         </div>
     </section>

     @if (Session::get('carts'))


         <section class="py-4 py-md-5">
             <div class="container">
                 <div class="row justify-content-center">
                     <div class="col-lg-8">
                         <div class="track">
                             <div class="step active">
                                 <span class="icon"></span>
                                 <span class="text">Cart</span>
                             </div>
                             <div class="step">
                                 <span class="icon"></span>
                                 <span class="text">Shipping Address</span>
                             </div>
                             <div class="step">
                                 <span class="icon"></span>
                                 <span class="text">Checkout</span>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </section>
         <section class="py-4 py-md-5">
             <div class="container">
                 <div class="table-responsiveness mb-4 mb-lg-5">
                     <table class="table table-borderless table-cart" cellspacing="0">
                         <thead>
                             <tr>
                                 <th class="product-remove">&nbsp;</th>
                                 <th class="product-name">Product</th>
                                 <th class="product-price">Price</th>
                                 <th class="product-quantity">Quantity</th>
                                 <th class="product-subtotal">Total</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                    $total=array();
                    $shoping_charge = Session::get('shoping_charge_cart');
                    if($carts=Session::get('carts')){

                       //dd($carts);    

                    foreach($carts as $key => $items){
                        $total[]=$items['sale_price']*$items['quantity'];
                        
                        $actualPrice        =   "";
                        $total_amount       =   array_sum($total);
                        $gst                =   $shoping_charge['gst_per'];
                        $calculateTax       =   100+$gst;
                        $calculateAmount    =   $total_amount*100;
                        $actualPrice        =   $calculateAmount/$calculateTax;
                    ?>
                             <tr class="cart_item">
                                 <td class="product-remove">
                                     <a href="{{ url('/cart_remode', $key) }}" aria-label="Remove this item"
                                         onclick="return confirm('Are you sure? remove this item');">×</a>
                                 </td>
                                 <td class="product">
                                     <div class="d-flex justify-content-around">
                                         <div>
                                             <img src="<?php echo asset('/'); ?>uploads/items/<?= $items['image'] ?>"
                                                 style="max-width:70px;">
                                         </div>
                                         <div style="font-size:13px;">
                                             <?= $items['service_name'] ?>
                                             <div>
                                                 <?php
                                                 $product_item = DB::table('product_items')
                                                     ->where('id', $items['item_id'])
                                                     ->first();
                                                 ?>
                                                 <?= isset($product_item->color) ? $product_item->color . '&nbsp;|' : '' ?>
                                                 <?= $product_item->size ?>
                                                 <?= @$product_item->item_unit_value ?> <?= @$product_item->item_unit ?>
                                             </div>
                                             <div>
                                                 <del> ₹ <?= $items['service_price'] ?> </del> &nbsp;&nbsp; ₹
                                                 <?= $items['sale_price'] ?>
                                             </div>
                                         </div>
                                     </div>

                                 </td>
                                 <td class="product-price" data-title="Price">
                                     <span class="amount">₹<?= $items['sale_price'] ?></span>
                                     <div style="font-size: 10px;">
                                         @if ($items['id'] > 0)
                                             <?php
                                             $totalItemAmount = $items['sale_price'] * $items['quantity'];
                                             $productTable = DB::table('services')
                                                 ->select('id', 'product_tax', 'tax_include')
                                                 ->where('id', $items['id'])
                                                 ->first();
                                             if (!empty($productTable)) {
                                                 if ($productTable->tax_include == 1) {
                                                     $tax_pay = \App\Helper\Helper::tax_included($items['id']);
                                                     echo '(tax included ' . $tax_pay . '%)';
                                                 } else {
                                                     $tax_excluded = \App\Helper\Helper::tax_excluded($items['id'], $totalItemAmount);
                                                     echo '(tax excluded ' . $tax_excluded['value'] . '%)';
                                                     $totalItemAmount += $tax_excluded['amount'];
                                                     $total[] = $tax_excluded['amount'];
                                                 }
                                             }
                                             ?>
                                         @endif
                                     </div>
                                 </td>
                                 <td class="product-quantity" data-title="Quantity">
                                     <div class="quantity">
                                         <form action="#" class="d-flex align-items-center justify-content-around">
                                             <!--<div class="qtyminus btn">-</div>-->
                                             <input type="text" name="quantity" value="<?= $items['quantity'] ?>"
                                                 class="form-control h-auto py-2 updateQTY"
                                                 data-item_id="{{ $key }}">
                                             <!--<div class="qtyplus btn">+</div>-->
                                         </form>
                                     </div>
                                 </td>
                                 <td class="product-price" data-title="Total">
                                     <!--<span class="amount">₹<?= $items['sale_price'] * $items['quantity'] ?></span>-->
                                     <span class="amount">₹<?= $totalItemAmount ?></span>
                                 </td>
                             </tr>
                             <?php } }?>
                         </tbody>
                     </table>
                 </div>
                 <div class="row justify-content-lg-between">
                     <div class="col-xl-4 col-lg-5 col-md-6">
                         <!--<form class="form">-->
                         <!--    <div class="input-group">-->
                         <!--        <input type="text" class="form-control" placeholder="Voucher code">-->
                         <!--        <div class="input-group-append">-->
                         <!--            <button type="button" class="btn btn-primary">Applay coupon</button>-->
                         <!--        </div>-->
                         <!--    </div>-->
                         <!--</form>-->
                     </div>
                     <div class="col-lg-4 col-md-6 mt-4 mt-md-0">
                         <table class="table table-borderless cart-table">
                             <tbody>
                                 <tr>
                                     <td>Sub total</td>
                                     <td class="text-right"><span>₹ <?= array_sum($total) ?> </span></td>
                                 </tr>
                                 <!--<tr>-->
                                 <!--    <td>Base Amount</td>-->
                                 <!--    <td class="text-right">-->
                                 <?php //echo round($actualPrice,2);
                                 ?>
                                 <!--    </td>-->
                                 <!--</tr>-->

                                 <tr>
                                     <td>GST({{ $shoping_charge['gst_per'] }}%) </td>
                                     <td class="text-right"><span>₹
                                             <?php echo round($total_amount - $actualPrice, 2); ?>
                                         </span></td>
                                 </tr>
                                 <!--  <tr>
                                        <td>Delivery Charges</td>
                                        <td class="text-right"><span>₹ {{ $shoping_charge['shipping'] }} </span></td>
                                    </tr> -->

                                 <!--<tr>-->
                                 <!--    <td>Coupon</td>-->
                                 <!--    <td class="text-right"><span>No</span></td>-->
                                 <!--</tr>-->
                             </tbody>
                             <tbody class="total">
                                 <tr>
                                     <th class="text-uppercase">Total</th>
                                     <th class="text-right"><span>₹<?= array_sum($total) ?></span></th>
                                 </tr>
                             </tbody>
                         </table>
                         <a href="{{ url('/checkout') }}" class="btn btn-block btn-primary">Checkout</a>
                         <!--<button type="button" class="btn btn-block btn-primary">Checkout</button>-->
                     </div>
                 </div>
             </div>
         </section>
     @else
         <section class="py-4 py-md-5">
             <div class="container" align="center">
                 <img src="{{ url('/uploads/empty_cart.png') }}">
                 <p> Look like you have no item in you shoping cart click <a href="{{ url('/') }}"> here </a> to
                     continue shoping.</p>
             </div>
         </section>

     @endif


 @stop
