@extends('layouts.app')
@section('title', 'Home')
@section('content')

    <style>
        .h-100 {
            height: 86% !important;
        }
    </style>

    <section class="banner-wrapper">
        <div class="container">
            <div class="header-slider owl-theme">
                @if (!empty($slides))
                    @foreach ($slides as $slider)
                        <div class="item">
                            <div class="row align-items-center justify-content-around">
                                <div class="col-md-6 col-lg-4">
                                    <h1> {{ $slider['heading'] }} </h1>
                                    <p>{{ $slider['sub_heading'] }}</p>
                                </div>
                                <div class="col-md-6 col-lg-5">
                                    <img src="{{ asset($slider['image']) }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <section class="offer-wrapper pb-4 pb-md-5">
        <div class="container">
            <div class="offer-slider owl-theme_b">
                @foreach ($offerBanner as $k => $offer_b)
                    <div class="item">
                        <a href="{{ url('/shop', @$category_slug[$k]) }}"><img
                                src="<?php echo asset('/uploads/banner'); ?>/{{ $offer_b->image }}"></a>
                        <p style="">
                            {{ $offer_b->sub_heading }}
                        </p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>


    <section class="py-4 ">
        <div class="container">
            <div class="mt-1">

                <div class="mb-4 df border-bottom">
                    <h4 class="text-uppercase">Bumper Sale </h4>

                    <div id="demo" style="color: #4e4e4e;margin-left: 28px;margin-top: 7px;"> </div>

                    <?php
                    $curt_date = date('Y-m-d');
                    $f = explode('T', @$comdowns['comdown_start']);
                    $s = explode('T', @$comdowns['comdown_end']);
                    ?>
                    @if ($f[0] <= $curt_date && $s[0] <= $curt_date)
                        <div class="fr mt-4">
                            <a href="#"> See More </a>
                        </div>
                    @endif

                </div>

                <div class="mb-4">
                    <div class="products">
                        <div class="product-slider owl-theme">

                            @if (count($comdowns) > 0 && isset($comdowns['item']))
                                @foreach ($comdowns['item'] as $comdown)
                                    <div class="item">
                                        <!-- Product -->
                                        <div class="product-block text-center">
                                            <div class="position-relative">

                                                @if (!empty($comdown['image']))
                                                    <a href="{{ url('/product_details', $comdown['slug']) }}">
                                                        <img class="resizeImg  w-100"
                                                            src="<?php echo asset('/'); ?>{{ $comdown['image'] }}"
                                                            alt="{{ $comdown['name'] }}">
                                                    </a>
                                                @else
                                                    <img class="product-thumb img-fluid" src="<?php echo asset('/uploads/default-store.jpg'); ?>"
                                                        alt="{{ $comdown['name'] }}">
                                                @endif

                                                <div class="hover-content">
                                                    <button type="button" data-product_id="{{ $comdown['id'] }}"
                                                        class="btn btn-outline-primary rounded-circle add-to-wishlist"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Save for wishlist">
                                                        <span class="fa fa-heart"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product-body pt-3 px-3 pb-0">
                                                <div class="mb-2">

                                                    <a href="{{ url('product_details', $comdown['slug']) }}"
                                                        class="product-title"> {{ Str::words($comdown['name'], '3') }}

                                                        <div class="my-1 small">
                                                            <small class="fa fa-star text-warning"></small>
                                                            <small class="fa fa-star text-warning"></small>
                                                            <small class="fa fa-star text-warning"></small>
                                                            <small class="fa fa-star text-muted"></small>
                                                        </div>
                                                        <p>
                                                            <?php
                                                            \App\Helper\Helper::discount($comdown['price'], $comdown['sale_price']);
                                                            ?>
                                                        </p>
                                                        <div class="d-block">
                                                            <span class="fw-500 text-primary"> ₹
                                                                {{ $comdown['sale_price'] }} </span>
                                                            <span class="text-secondary ml-2"><del> ₹
                                                                    {{ $comdown['price'] }} </del></span>
                                                        </div>

                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Product -->
                                    </div>
                                @endforeach
                            @endif

                        </div><!--row-->
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-3 py-md-3">
        <div class="container">
            <div class="row gutters-2">

                <div class="col-md-6">

                    <h4 class="text-muted mb-0">Recently Viewed</h4>

                    <?php if(isset($_COOKIE['recent_view'])) {?>

                    <div class="products">
                        <div class="best-seller-slider owl-theme">


                            @if (!empty($recent_product))
                                @foreach ($recent_product as $recentProduct)
                                    @if (count($recent_product) == 1)
                                        <div class="item">
                                            <div class="product-block text-center h-100">
                                                <div class="position-relative">

                                                    @if (!empty($recentProduct['image']))
                                                        <img class="product-thumb img-fluid"
                                                            src="{{ asset($recentProduct['image']) }}"
                                                            alt="{{ $recentProduct['name'] }}">
                                                    @else
                                                        <img class="product-thumb img-fluid" src="<?php echo asset('/uploads/default-store.jpg'); ?>"
                                                            alt="{{ $recentProduct['name'] }}">
                                                    @endif

                                                    <div class="hover-content">
                                                        <button type="button" data-product_id="{{ $recentProduct['id'] }}"
                                                            class="btn btn-outline-primary rounded-circle add-to-wishlist"
                                                            data-toggle="tooltip" data-placement="top" title=""
                                                            data-original-title="Save for wishlist">
                                                            <span class="fa fa-heart"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="product-body pt-1 px-1 pb-0">
                                                    <div class="mb-2">
                                                        <a href="{{ url('product_details', $recentProduct['slug']) }}"
                                                            class="product-title">
                                                            {{ Str::words($recentProduct['name'], '3') }}
                                                            <div class="my-1 small">
                                                                <small class="fa fa-star text-warning"></small>
                                                                <small class="fa fa-star text-warning"></small>
                                                                <small class="fa fa-star text-warning"></small>
                                                                <small class="fa fa-star text-warning"></small>
                                                                <small class="fa fa-star text-muted"></small>
                                                            </div>

                                                            <?php
                                                            \App\Helper\Helper::discount($recentProduct['price'], $recentProduct['sale_price']);
                                                            ?>
                                                            <div class="d-block">
                                                                <span class="fw-500 text-primary"> ₹
                                                                    {{ $recentProduct['sale_price'] }} </span>
                                                                <span class="text-secondary ml-2"><del> ₹
                                                                        {{ $recentProduct['price'] }} </del></span>
                                                            </div>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="item">
                                        <div class="product-block text-center h-100">
                                            <div class="position-relative">

                                                @if (!empty($recentProduct['image']))
                                                    <img class="product-thumb img-fluid"
                                                        src="{{ asset($recentProduct['image']) }}"
                                                        alt="{{ $recentProduct['name'] }}">
                                                @else
                                                    <img class="product-thumb img-fluid" src="<?php echo asset('/uploads/default-store.jpg'); ?>"
                                                        alt="{{ $recentProduct['name'] }}">
                                                @endif

                                                <div class="hover-content">
                                                    <button type="button" data-product_id="{{ $recentProduct['id'] }}"
                                                        class="btn btn-outline-primary rounded-circle add-to-wishlist"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Save for wishlist">
                                                        <span class="fa fa-heart"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product-body pt-1 px-1 pb-0">
                                                <div class="mb-2">
                                                    <a href="{{ url('product_details', $recentProduct['slug']) }}"
                                                        class="product-title">
                                                        {{ Str::words($recentProduct['name'], '3') }}
                                                        <div class="my-1 small">
                                                            <small class="fa fa-star text-warning"></small>
                                                            <small class="fa fa-star text-warning"></small>
                                                            <small class="fa fa-star text-warning"></small>
                                                            <small class="fa fa-star text-warning"></small>
                                                            <small class="fa fa-star text-muted"></small>
                                                        </div>

                                                        <?php
                                                        \App\Helper\Helper::discount($recentProduct['price'], $recentProduct['sale_price']);
                                                        ?>
                                                        <div class="d-block">
                                                            <span class="fw-500 text-primary"> ₹
                                                                {{ $recentProduct['sale_price'] }} </span>
                                                            <span class="text-secondary ml-2"><del> ₹
                                                                    {{ $recentProduct['price'] }} </del></span>
                                                        </div>

                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>

                    <?php }else{?>

                    <img class="w-100" src="<?php echo asset('/front/images/recntly-viewed.png'); ?>">

                    <?php }?>

                </div>


                <div class="col-md-6">
                    <h4 class="text-muted mb-0">Promotions and Deals</h4>
                    <?php $currentDate = date('Y-m-d'); ?>

                    <div class="products">
                        <div class="best-seller-slider owl-theme">

                            @foreach ($coupons as $coupon)
                                <div class="item">
                                    <div class="product-block text-center h-100">
                                        <div class="position-relative">
                                            <a href="{{ url('/promocode') }}"> <img
                                                    src="<?php echo asset('/'); ?>front/images/promotion.png"
                                                    class="product-thumb img-fluid"> </a>
                                        </div>
                                        <div class="product-body pt-1 px-1 pb-0">
                                            <div class="mb-2">
                                                <p> Validaty {{ @$coupon->expiry_date }} </p>
                                                <div> {{ @$coupon->short_descriptoin }} </div>
                                                <h6> code : {{ @$coupon->coupon_code }}
                                                    {{ @$coupon->expiry_date < $currentDate ? '| validity expired' : '' }}
                                                </h6>
                                                <p> {{ @$coupon->description }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>



    <section class="py-4 py-md-5">
        <div class="container">
            <div class="mt-2">

                @if (!empty($type_by_product))
                    @foreach ($type_by_product as $type)
                        @if (!empty($type->items))
                            <div class="mb-4 border-bottom">
                                <h4 class="text-uppercase"> {{ $type->name }} </h4>
                            </div>

                            <div class="mb-4">
                                <div class="products">
                                    <div class="product-slider owl-theme">
                                        @foreach ($type->items as $item)
                                            <div class="item">
                                                <!-- Product -->
                                                <div class="product-block text-center">
                                                    <div class="position-relative">
                                                        @if (!empty($item['image']))
                                                            <img class="product-thumb img-fluid"
                                                                src="{{ asset($item['image']) }}"
                                                                alt="{{ $item['name'] }}">
                                                        @else
                                                            <img class="product-thumb img-fluid"
                                                                src="<?php echo asset('/uploads/default-store.jpg'); ?>" alt="{{ $item['name'] }}">
                                                        @endif
                                                        <!--<div class="offer"><span class="badge badge-primary">HOT</span></div>-->
                                                        <div class="hover-content">
                                                            <button type="button"
                                                                class="btn btn-outline-primary rounded-circle add-to-wishlist"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                data-original-title="Save for wishlist"
                                                                data-product_id="{{ $item['id'] }}">
                                                                <span class="fa fa-heart"></span>
                                                            </button>
                                                            <!--<button type="button" class="btn btn-outline-primary rounded-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to cart">-->
                                                            <!--<span class="fa fa-shopping-cart"></span>-->
                                                            <!--</button>-->
                                                        </div>
                                                    </div>
                                                    <div class="product-body pt-3 px-3 pb-0">
                                                        <div class="mb-2">
                                                            <a href="{{ url('product_details', $item['slug']) }}"
                                                                class="product-title">
                                                                {{ Str::words($item['name'], '3') }}

                                                                <div class="my-1 small">
                                                                    <small class="fa fa-star text-warning"></small>
                                                                    <small class="fa fa-star text-warning"></small>
                                                                    <small class="fa fa-star text-warning"></small>
                                                                    <small class="fa fa-star text-warning"></small>
                                                                    <small class="fa fa-star text-muted"></small>
                                                                </div>
                                                                <?php
                                                                \App\Helper\Helper::discount($item['price'], $item['sale_price']);
                                                                ?>
                                                                <div class="d-block">
                                                                    <span class="fw-500 text-primary"> ₹
                                                                        {{ $item['sale_price'] }} </span>
                                                                    <span class="text-secondary ml-2"><del> ₹
                                                                            {{ $item['price'] }} </del></span>
                                                                </div>

                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Product -->
                                            </div>
                                        @endforeach
                                    </div><!--row-->
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

            </div>
        </div>
    </section>



    <section class="py-1 py-md-1">
        <div class="container">


            <div class="mt-5">

                @if (!empty($category_by_product))
                    @foreach ($category_by_product as $type)
                        @if (!empty($type['items']))
                            <div class="mb-4 border-bottom">
                                <h4 class="text-uppercase"> {{ $type['name'] }} </h4>
                                <div align="right" style="margin-top: -32px;font-size: 17px;"><a
                                        href="{{ url('/shop', @$type['slug']) }}"> See More </a></div>
                            </div>

                            <div class="mb-4">
                                <div class="products">
                                    <div class="product-slider owl-theme">
                                        @foreach ($type['items'] as $item)
                                            <div class="item">
                                                <!-- Product -->
                                                <div class="product-block text-center">
                                                    <div class="position-relative">
                                                        <!--default-store.jpg-->
                                                        @if (!empty($item['image']))
                                                            <img class="product-thumb img-fluid"
                                                                src="<?php echo asset('/'); ?>{{ $item['image'] }}"
                                                                alt="{{ $item['name'] }}">
                                                        @else
                                                            <img class="product-thumb img-fluid"
                                                                src="<?php echo asset('/uploads/default-store.jpg'); ?>" alt="{{ $item['name'] }}">
                                                        @endif
                                                        <!--<div class="offer"><span class="badge badge-primary">HOT</span></div>-->
                                                        <div class="hover-content">
                                                            <button type="button"
                                                                class="btn btn-outline-primary rounded-circle add-to-wishlist"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                data-original-title="Save for wishlist"
                                                                data-product_id="{{ $item['id'] }}">
                                                                <span class="fa fa-heart"></span>
                                                            </button>
                                                            <!--<button type="button" class="btn btn-outline-primary rounded-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to cart">-->
                                                            <!--<span class="fa fa-shopping-cart"></span>-->
                                                            <!--</button>-->
                                                        </div>
                                                    </div>
                                                    <div class="product-body pt-3 px-3 pb-0">
                                                        <div class="mb-2">
                                                            <a href="{{ url('product_details', $item['slug']) }}"
                                                                class="product-title">
                                                                {{ Str::words($item['name'], '3') }}
                                                                <div class="my-1 small">
                                                                    <small class="fa fa-star text-warning"></small>
                                                                    <small class="fa fa-star text-warning"></small>
                                                                    <small class="fa fa-star text-warning"></small>
                                                                    <small class="fa fa-star text-warning"></small>
                                                                    <small class="fa fa-star text-muted"></small>
                                                                </div>
                                                                <?php
                                                                \App\Helper\Helper::discount($item['price'], $item['sale_price']);
                                                                ?>
                                                                <div class="d-block">
                                                                    <span class="fw-500 text-primary"> ₹
                                                                        {{ $item['sale_price'] }} </span>
                                                                    <span class="text-secondary ml-2"><del> ₹
                                                                            {{ $item['price'] }} </del></span>
                                                                </div>

                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Product -->
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endif
                    @endforeach
                @endif


            </div>
        </div>
    </section>
    <hr>



    <section class="banner-wrapper bg-blue py-0">
        <div class="container">
            <div class="row align-items-center justify-content-around">
                <div class="col-md-6 col-lg-4">
                    <h1 class="fw-500">iPhone 6 Plus</h1>
                    <p>Performance and design. Taken right to the edge.</p>
                    <a href="#" class="more mt-lg-3">More</a>
                </div>
                <div class="col-md-6">
                    <img src="<?php echo asset('/'); ?>front/images/6-plus.png" style="margin-top: -70px;">
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-4 col-md-6">
                    <img class="mb-4" src="<?php echo asset('/'); ?>front/images/free-shipping.svg" alt="">
                    <h4 class="fw-500">FREE SHIPPING</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor minim veniam, quis
                        nostrud reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <img class="mb-4" src="<?php echo asset('/'); ?>front/images/refund.svg" alt="">
                    <h4 class="fw-500">100% REFUND</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor minim veniam, quis
                        nostrud reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <img class="mb-4" src="<?php echo asset('/'); ?>front/images/support.svg" alt="">
                    <h4 class="fw-500">SUPPORT 24/7</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor minim veniam, quis
                        nostrud reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="comdown_start" value="{{ @$comdowns['comdown_start'] }}">
    <input type="hidden" id="comdown_end" value="{{ @$comdowns['comdown_end'] }}">

@stop


@section('extra_script')

    <script>
        var comdown_start = document.getElementById("comdown_start").value;
        var comdown_end = document.getElementById("comdown_end").value;

        var countDownDate = new Date(comdown_end).getTime();

        var x = setInterval(function() {

            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("demo").innerHTML = days + "day - " + hours + "h - " +
                minutes + "m - " + seconds + "s ";

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demo").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>

@stop
