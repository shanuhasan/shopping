@extends('layouts.app')
@section('title', 'My Orders')
@section('content')
    <section class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>My Orders</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="user-dasboard">
        <div class="side-bar">
            <h3 class="title">My Orders</h3>
            <ul class="side-nav">
                <li><a href="{{ url('/profile') }}"><i class="la la-user"></i>My Profile</a></li>
                <li><a href="{{ url('/my-order') }}" class="active"><i class="la la-box"></i>My Orders</a></li>
                <!--<li><a href="{{ url('/address-list') }}"><i class="la la-map-marker"></i>My Addresses</a></li>-->
                <li><a href="{{ url('/wishlist') }}"><i class="la la-heart-o"></i>My Wishlist</a></li>
                <!--<li><a href="{{ url('/') }}" class="log-out"><i class="la la-sign-out"></i>Log Out</a></li>-->
            </ul>
        </div>

        <div class="dasboard-wrapper">
            <div class="products list">

                <table class="table table-striped">
                    <tr>
                        <th>Sr no.</th>
                        <!--<th>Name</th>-->
                        <th>Order id</th>
                        <th>order discount</th>
                        <th>shipping</th>
                        <th>Sub Total amount</th>
                        <th>Total amount</th>
                        <th>payment status</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>

                    @foreach ($orders as $k => $order)
                        <tr>
                            <td> {{ $k + 1 }} </td>
                            <!--<td> {{ $order['name'] }} </td>-->
                            <td> {{ $order['order_id'] }} </td>
                            <td> {{ $order['order_discount'] }} </td>
                            <td> {{ $order['shipping'] }} </td>
                            <td> {{ $order['grand_total'] }} </td>
                            <td> {{ $order['paid'] }} </td>
                            <td> <span
                                    class="badge {{ $order['payment_status'] == 'success' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $order['payment_status'] }} </span></td>
                            <td> <span
                                    class="badge {{ $order['payment_status'] == 'delivered' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $order['status'] }} </span> </td>
                            <td> {{ $order['created_at'] }} </td>
                            <td>
                                <a href="{{ url('/orderdetails', $order['id']) }}" class="btn text-primary btn-wide">Track
                                    Order</a>
                                <!--<a href="{{ url('/') }}" class="btn btn-air-primary btn-wide ml-2">Buy Again</a>-->
                            </td>
                        </tr>
                    @endforeach


                </table>

                <!--<div class="product-block-list border p-3 rounded">-->
                <!--	<div class="row gutters-2">-->
                <!--		<div class="col-md-3 col-xl-2">-->
                <!--			<div class="product-block h-100">-->
                <!--				<div class="position-relative">-->
                <!--					<img class="product-thumb" src="<?php echo asset('/'); ?>front/images/product-thumb.png" alt="Image Description">-->
                <!--				</div>-->
                <!--			</div>-->
                <!--		</div>-->
                <!--		<div class="col-md-8 col-xl-10">-->
                <!--			<div class="product-body text-center text-md-left">-->
                <!--				<div class="mb-2">-->
                <!--					<a href="{{ url('/product_details') }}" class="product-title h5">Apple Macbook Pro</a>-->
                <!--					<div class="d-flex align-items-center justify-content-center justify-content-md-start small mb-2">-->
                <!--	                    <div class="text-warning mr-2">-->
                <!--	                        <small class="fa fa-star"></small>-->
                <!--	                        <small class="fa fa-star"></small>-->
                <!--	                        <small class="fa fa-star"></small>-->
                <!--	                        <small class="fa fa-star"></small>-->
                <!--	                        <small class="fa fa-star text-muted"></small>-->
                <!--	                    </div>-->
                <!--	                    <span>126 Reviews</span>-->
                <!--	                    <a class="js-go-to ml-2" href="#reviewSection" data-target="#reviewSection">Submit a review</a>-->
                <!--	                </div>-->
                <!--					<div class="d-block mb-2">-->
                <!--						<span class="fw-500 text-primary">$499</span>-->
                <!--						<span class="text-secondary ml-2"><del>$599</del></span>-->
                <!--					</div>-->
                <!--					<a href="{{ url('/orderdetails') }}" class="btn btn-air-primary btn-wide">Track Order</a>-->
                <!--					<a href="{{ url('/') }}" class="btn btn-air-primary btn-wide ml-2">Buy Again</a>-->
                <!--				</div>-->
                <!--			</div>-->
                <!--		</div>-->
                <!--	</div>-->
                <!--</div>-->

            </div>
        </div>
    </section>
@endsection
