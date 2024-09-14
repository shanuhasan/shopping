@extends('layouts.app')
@section('title', 'My Orders')
@section('content')
    <section class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/my-order') }}">My Orders</a></li>
                    <li>Order Details</li>
                </ul>
            </div>
        </div>
    </section>


    <?php
    // dd($data->get_items);
    ?>


    <section class="user-dasboard">
        <div class="side-bar">
            <h3 class="title">My Orders </h3>
            <ul class="side-nav">
                <li><a href="{{ url('/profile') }}"><i class="la la-user"></i>My Profile</a></li>
                <li><a href="{{ url('/my-order') }}" class="active"><i class="la la-box"></i>My Orders</a></li>
                <li><a href="{{ url('/wishlist') }}"><i class="la la-heart-o"></i>My Wishlist</a></li>
                <!--<li><a href="{{ url('/address-list') }}"><i class="la la-map-marker"></i>My Addresses</a></li>-->
                <li><a href="{{ url('/logout') }}" class="log-out"><i class="la la-sign-out"></i>Log Out</a></li>
            </ul>
        </div>

        <div class="dasboard-wrapper">

            <div class="row">
                <div class="col-md-8" style="font-size:12px;">
                    <div class="fw-500">Delivery Address</div>

                    @if (!empty($data->user_address))
                        <div class="lead">{{ @$data->user_address->name }}</div>
                        <div>{{ @$data->user_address->address }}</div>
                        <div>
                            <span class="fw-100">Phone number</span>
                            <span class="ml-2">{{ @$data->user_address->phone }}</span>
                        </div>
                    @else
                        <div class="lead">{{ @$data->name }} </div>
                        <div>{{ @$data->address }}</div>
                        <div>
                            <span class="fw-100">Phone number</span>
                            <span class="ml-2">{{ @$data->phone }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-4" align="right">
                    <h5> Order ID : {{ $data->order_id }} </h5>
                    <div style="font-size:12px !important;">
                        Sub total amount :&nbsp; ₹ {{ @$data->grand_total }} <br>
                        Delivery charge :&nbsp; ₹ {{ @$data->shipping }} <br>
                        Discount :&nbsp; ₹ {{ @$data->order_discount }} <br>
                        Total amount :&nbsp; ₹ {{ @$data->paid }} <br>
                    </div>
                </div>
            </div>
            <hr>

            <div class="products list">
                <div class="product-block-list">

                    @foreach ($data->order_item as $key => $item_product)
                        <div class="row gutters-2">
                            <div class="col-md-3 col-xl-2">
                                <div class="product-block h-100">
                                    <div class="position-relative">
                                        <img class="product-thumb"
                                            src="{{ url('/uploads/items/', $item_product['product_item_image']) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 col-xl-10 border-bottom">
                                <div class="product-body">
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between">
                                            <p class="text-size">{{ $item_product['product_name'] }}</p>

                                            @if (
                                                $item_product['order_item_status'] == 'pending' ||
                                                    $item_product['order_item_status'] == 'ordered' ||
                                                    $item_product['order_item_status'] == 'processing')
                                                <p align="right">

                                                    <a href=""
                                                        style="border: 1px solid;padding: 6px;display: inline-table;"
                                                        data-toggle="modal"
                                                        data-target="#myOrder_cancel_{{ $key }}">
                                                        Order cancel</a>
                                                </p>
                                                <div id="myOrder_cancel_{{ $key }}" class="modal fade"
                                                    role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title" align="left">Oreder Cancel</h4>
                                                            </div>
                                                            <form action="{{ url('order_cancelled_by_user') }}">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="order_id"
                                                                        value="{{ $item_product['order_id'] }}">
                                                                    <input type="hidden" name="item_id"
                                                                        value="{{ $item_product['order_item_id'] }}">
                                                                    <lebel>Type here comment</lebel>
                                                                    <textarea name='comment' class="form-control" required></textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="submit" class="btn btn-default btn-sm"
                                                                        value="submit">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($item_product['order_item_status'] == 'delivered')
                                                <div align="right">
                                                    <a href="" style="float:right;padding:6px;display:contents;"
                                                        data-toggle="modal"
                                                        data-target="#myOrder_return_{{ $key }}">Return Order</a>
                                                    <div style="font-size: 11px;margin-top: 10px;"> Return item with in a 7
                                                        Days</div>
                                                </div>

                                                <div id="myOrder_return_{{ $key }}" class="modal fade"
                                                    role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title" align="left">Oreder return</h4>
                                                            </div>
                                                            <form action="{{ url('order_return_by_user') }}">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="order_id"
                                                                        value="{{ $item_product['order_id'] }}">
                                                                    <input type="hidden" name="item_id"
                                                                        value="{{ $item_product['order_item_id'] }}">
                                                                    <lebel>Type here comment</lebel>
                                                                    <textarea name='comment' class="form-control" required></textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="submit" class="btn btn-default btn-sm"
                                                                        value="submit">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="d-flex align-items-center small mb-2">
                                            <div class="text-warning mr-2">
                                                <small class="fa fa-star"></small>
                                                <small class="fa fa-star"></small>
                                                <small class="fa fa-star"></small>
                                                <small class="fa fa-star"></small>
                                                <small class="fa fa-star text-muted"></small>
                                            </div>
                                            <span>126 Reviews</span>
                                            <!--<a class="js-go-to ml-2" href="#reviewSection" data-target="#reviewSection">Submit a review</a>-->
                                        </div>
                                        <div class="d-block">
                                            <span class="fw-500 text-primary">₹
                                                {{ $item_product['price'] * $item_product['quantity'] }}</span>
                                            <span class="text-secondary ml-2"><del>₹
                                                    {{ $item_product['product_item_mrp_price'] }}</del></span>
                                        </div>
                                        <div class="d-block">QTY : {{ $item_product['quantity'] }} </div>
                                    </div>
                                </div>

                                @if ($item_product['order_item_status'] == 'pending')
                                    @include('step/pending')
                                @endif

                                @if ($item_product['order_item_status'] == 'ordered')
                                    @include('step/ordered')
                                @endif

                                @if ($item_product['order_item_status'] == 'processing')
                                    @include('step/processing')
                                @endif

                                @if ($item_product['order_item_status'] == 'shipped')
                                    @include('step/shipped')
                                @endif

                                @if ($item_product['order_item_status'] == 'delivered')
                                    @include('step/delivered')
                                @endif

                                @if ($item_product['order_item_status'] == 'cancelled')
                                    @include('step/cancelled')
                                @endif

                                @if ($item_product['order_item_status'] == 'return_pending')
                                    @include('step/return_pending')
                                @endif

                                @if ($item_product['order_item_status'] == 'return_processing')
                                    @include('step/return_processing')
                                @endif

                                @if ($item_product['order_item_status'] == 'return_complete')
                                    @include('step/return_complete')
                                @endif


                            </div>
                        </div><br>
                    @endforeach


                    <!--<div class="media align-items-center">-->
                    <!--	<div class="media-body">-->
                    <!--		<div class="d-flex align-items-center">-->
                    <!--			<span class="badge badge-secondary p-2 rounded mr-2">-->
                    <!--				<i class="la la-file-alt la-2x"></i>-->
                    <!--			</span>-->
                    <!--			Download Invoice-->
                    <!--		</div>-->
                    <!--	</div>-->
                    <!--	<a href="#" class="btn btn-primary btn-wide">Download</a>-->
                    <!--</div>-->

                </div>
            </div>
        </div>
    </section>
@endsection
