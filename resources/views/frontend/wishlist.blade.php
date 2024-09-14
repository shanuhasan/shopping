@extends('layouts.app')
@section('title', 'Wishlist')
@section('content')
    <section class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Wishlist</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="user-dasboard">
        <div class="side-bar">
            <h3 class="title">My Wishlist</h3>
            <ul class="side-nav">
                <li><a href="{{ url('/profile') }}"><i class="la la-user"></i>My Profile</a></li>
                <li><a href="{{ url('/my-order') }}"><i class="la la-box"></i>My Orders</a></li>
                <li><a href="{{ url('/wishlist') }}" class="active"><i class="la la-heart-o"></i>My Wishlist</a></li>
                <!--<li><a href="{{ url('/') }}" class="log-out"><i class="la la-sign-out"></i>Log Out</a></li>-->
            </ul>
        </div>
        <div class="dasboard-wrapper">
            <div class="products list">

                @foreach ($wishlist as $wish)
                    <div class="product-block-list">
                        <div class="row gutters-2">
                            <div class="col-md-4 col-xl-3">
                                <div class="product-block h-100">
                                    <div class="position-relative">
                                        <img class="product-thumb" src="<?php echo asset('/uploads/service'); ?>/{{ $wish->image }}">
                                        <!--<div class="offer"><span class="badge badge-primary">HOT</span></div>-->
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="product_id" id="product_id_b" class="product_id"
                                value="{{ base64_encode($wish->id) }}">
                            <input type="hidden" name="item_id" id="item_id_b" class="item_id"
                                value="{{ base64_encode($wish->items->id) }}">

                            <div class="col-md-8 col-xl-9">
                                <div class="product-body">
                                    <div class="mb-2">
                                        <a href="{{ url('/shop', $wish->slug) }}" class="product-title h4">
                                            {{ $wish->service_name }} </a>
                                        <div class="d-flex align-items-center small mb-2">
                                            <div class="text-warning mr-2">
                                                <small class="fa fa-star"></small>
                                                <small class="fa fa-star"></small>
                                                <small class="fa fa-star"></small>
                                                <small class="fa fa-star"></small>
                                                <small class="fa fa-star text-muted"></small>
                                            </div>
                                            <span>0 Reviews</span>
                                            <!--<a class="js-go-to ml-2" href="#reviewSection" data-target="/product_details#reviewSection">Submit a review</a>-->
                                        </div>
                                        <div class="d-block h4">
                                            <span class="fw-500 text-primary"> ₹ {{ @$wish->items->item_price }}</span>
                                            <span class="text-secondary ml-2"><del>₹
                                                    {{ @$wish->items->item_mrp_price }}</del></span>
                                        </div>
                                        <!--<p class="small mb-2">Nunc facilisis sagittis ullamcorper. Proin lectus ipsum, gravida et mattis vulputate, tristique ut lectus. Sed et lectus lorem nunc leifend laorevtr istique et congue. Vivamus adipiscin vulputate g nisl ut dolor ...</p>-->
                                        <button type="button" id="addToCartBtn_bbb"
                                            onclick="addTocartProduct('{{ base64_encode($wish->id) }}','{{ base64_encode($wish->items->id) }}')"
                                            class="btn btn-air-primary"><i class="fa fa-shopping-cart mr-2"></i> Add to
                                            cart</button>
                                        <button type="button"
                                            onclick="removeTocartProduct('{{ $wish->id }}','{{ $wish->items->id }}')"
                                            class="btn btn-air-primary ml-2"><i class="fa fa-heart"></i> Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection
