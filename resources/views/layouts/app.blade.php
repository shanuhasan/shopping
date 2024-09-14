<!DOCTYPE HTML>
<html>

<head>
    <title> @yield('title') | SHOPPING</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('uploads/apple-icon-57x57.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="{{ asset('front/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
</head>

<body>
    <div id="page">
        <div class="top-bar" id="top_bar">
            <div class="container">
                <div class="d-flex align-items-center">
                    <ul class="list-inline ml-auto mb-0">
                        <li class="list-inline-item">
                            @if (!Auth::check())
                                <a href="{{ url('/login') }}" class="nav-link"><i class="fa fa-user"></i> <span
                                        class="d-none d-md-inline-block">Login/Register</span></a>
                            @else
                                <div class="d-flex" style="align-items: baseline;">
                                    <div class="d-none_b d-md-inline-block" style="margin: 0px 6px;">
                                        @if (Auth::user()->image != '')
                                            <img src="{{ url('/public/uploads/profile', Auth::user()->image) }}"
                                                style="width: 28px; border-radius: 16px;box-shadow: 0px 0px 1px 0px black;">
                                            {{ Auth::user()->name }}
                                        @else
                                            <i class="fa fa-user"></i>
                                            {{ Auth::user()->name }}
                                        @endif
                                    </div>|&nbsp;&nbsp;
                                    <div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-responsive-nav-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-responsive-nav-link>
                                        </form>
                                    </div>&nbsp;&nbsp;|
                                </div>
                            @endif
                        </li>

                        <!-- Shopping Cart -->

                        <?php
                        
                        $total = [];
                        
                        $totalQTY = 0;
                        
                        if ($carts = Session::get('carts')) {
                            $item = count($carts);
                        
                            foreach ($carts as $items) {
                                $total[] = $items['sale_price'] * $items['quantity'];
                                $totalQTY += $items['quantity'];
                            }
                        }
                        
                        ?>

                        <li class="list-inline-item dropdown mini-cart">
                            <a class="nav-link dropdown-toggle_b" href="{{ url('/cart') }}" id="navbardrop_b"
                                data-toggle="dropdown_b">
                                <i class="fa fa-shopping-basket"></i>
                                &nbsp;
                                <?php
                                
                                if (Session::get('carts')) {
                                    echo $item;
                                }
                                ?> &nbsp;Items
                                &nbsp;
                                <span class="text-muted d-none d-md-inline-block"> ₹
                                    <?= $total > 0 ? array_sum($total) : 0 ?> </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <header class="header" id="header">
            <div class="container">
                <nav class="navbar navbar-expand-lg justify-content-between">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img alt="{{ isset($settings['site_name']) ? $settings['site_name'] : null }}"
                            src="{{ isset($settings['header_logo']) ? asset($settings['header_logo']) : null }}">
                    </a>

                    <div class="collapse navbar-collapse primary-menu">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="nav-item d-none d-xl-flex">
                                <form action="{{ url('search') }}">
                                    <div class="input-group" style="margin-top:8px;">
                                        <input type="text" name="q" class="form-control" placeholder="search"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2"
                                            style="height: 30px;">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit"
                                                style="line-height: 0px;">search</button>
                                        </div>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>

        <div class="sub-header">
            <div class="container">
                <div class="d-flex align-items-center">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-map-marker"></i>
                                <span id="location_div"></span>
                            </a>
                        </li>
                    </ul>
                    <!-- End Language -->
                    <ul class="list-inline ml-auto mb-0 d-none d-lg-block">
                        <li class="list-inline-item">
                            <a href="{{ url('/wishlist') }}" class="nav-link"><i class="la la-percentage"></i> Wish
                                List</a>
                        </li>
                        @if (Auth::check())
                            <li class="list-inline-item">
                                <a href="{{ url('/my-order') }}" class="nav-link">My order</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ url('/profile') }}" class="nav-link">My Account</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div id="content">
            @yield('content')
        </div>

        @include('layouts.partials.footer')

    </div>

    <div class="gototop js-top">
        <a href="#" class="js-gotop"><i class="fa fa-angle-up"></i></a>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <!-- popper -->
    <script src="{{ asset('front/js/popper.min.js') }}"></script>
    <!-- bootstrap 4.1 -->
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <!-- jQuery easing -->
    <script src="{{ asset('front/js/jquery.easing.1.3.js') }}"></script>
    <!-- Waypoints -->
    <script src="{{ asset('front/js/jquery.waypoints.min.js') }}"></script>
    <!-- Owl carousel -->
    <script src="{{ asset('front/js/owl.carousel.min.js') }}"></script>

    <!-- Fancybox Popup -->
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!-- Main -->
    <script src="{{ asset('front/js/main.js') }}"></script>
    <script src="{{ asset('admin-assets/bacend/plugins/toastr/toastr.min.js') }}"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

    @yield('extra_script')

    @if ($message = Session::get('error'))
        <script type="text/javascript">
            var msg = '{{ $message }}';
            toastr.error(msg);
        </script>
    @endif
    @if ($message = Session::get('success'))
        <script type="text/javascript">
            var msg = '{{ $message }}';
            toastr.success(msg);
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#country').change(function() {
                var markup = '<option> select state </option>';
                var id = $(this).val();
                $.ajax({
                    url: "{{ url('/get_state_by_country') }}",
                    type: 'get',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        data.forEach(function(item) {
                            markup +=
                                `<option value='${item.id}'> ${item.name} </option>`;
                        });
                        $('.show_state').html(markup);
                    }
                });
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            $('#state').change(function() {
                var markup = '';

                var id = $(this).val();

                $.ajax({

                    url: '{{ url('/get_city_by_state') }}',

                    type: 'get',

                    data: {
                        id: id
                    },

                    success: function(data) {

                        data.forEach(function(item) {
                            markup +=
                                `<option value='${item.id}'> ${item.name} </option>`;
                        });
                        $('.show_city').html(markup);
                    }
                });
            });
        });
    </script>



    <script>
        var show_msg = document.getElementById("show_msg");
        var product_id = document.getElementById("product_id");
        var button = document.getElementById("check_area");

        const debounce = (func, delay) => {

            let debounceTimer
            return function() {

                const context = this
                const args = arguments
                clearTimeout(debounceTimer)
                debounceTimer = setTimeout(() => func.apply(context, args), delay)
            }
        }



        button.addEventListener('keyup', debounce(function() {

            //alert(product_id.value);
            $.ajax({

                url: '{{ url('/delivery_product_to_pincode') }}',
                type: 'get',
                data: {
                    product_id: product_id.value,
                    pincode: this.value
                },
                success: function(data) {

                    show_msg.classList.remove("text-danger");
                    show_msg.classList.remove("text-success");
                    if (data.status == 'success') {

                        show_msg.innerText = data.msg;
                        show_msg.classList.add("text-success");
                        toastr.success(data.msg);

                    } else {

                        show_msg.innerText = data.msg;
                        show_msg.classList.add("text-danger");
                        toastr.error(data.msg);
                    }
                }

            });



        }, 1000));
    </script>



    <script>
        $(document).ready(function() {



            $("#apply_coupon_code").click(function() {



                var coupon_code = $("#coupon_code_value").val();



                $.ajax({

                    url: '{{ url('/coupon_apply_to_web') }}',

                    type: 'get',

                    data: {
                        coupon_code: coupon_code
                    },

                    success: function(data) {



                        console.log(data);



                        $('body').load(" ");

                        toastr.success('copupon apply successfully..');



                    }

                });



            });



        });
    </script>





    <script>
        $(document).ready(function() {



            $(".updateQTY").change(function() {



                var qty = $(this).val();

                var item_id = $(this).attr('data-item_id');

                $.ajax({

                    url: '{{ url('/qty_update') }}',

                    type: 'get',

                    data: {
                        qty: qty,
                        item_id: item_id
                    },

                    success: function(data) {



                        console.log(data);



                        $('body').load(" ");



                        toastr.success('item updated Successfully...');

                        setTimeout(function() {

                            location.reload();

                        }, 1000);



                    }

                });



            });



        });
    </script>



    <script>
        $('#login-form').submit(function(e) {

            e.preventDefault();



            var formdata = new FormData(this);

            // var id=create_UUID();        

            //setCookie('user_cookie',id,30);

            //var usercookie=getCookie("user_cookie");

            //formdata.append('usercookie', usercookie);

            $.ajax({

                url: '{{ url('customer_login') }}',

                type: 'post',

                data: formdata,

                contentType: false,

                cache: false,

                processData: false,

                success: function(data) {

                    if (data == 'error') {

                        toastr.error('Invalid Email and Password');

                    } else if (data == 'success') {

                        toastr.success('Login Successfully...');

                        setTimeout(function() {

                            location.reload();

                        }, 1000);

                    }

                }

            });

        });
    </script>



    <script>
        $('#addToCartBtn1').click(function(e) {

            e.preventDefault();
            var product_id = $('#product_id').val();
            var item_id = $('#item_id').val();

            $.ajax({

                url: '{{ url('add-to-cart') }}',
                type: 'get',
                data: {
                    product_id: product_id,
                    item_id: item_id
                },
                success: function(data) {

                    console.log("cc " + data);
                    // $('.top-bar').load(" #top_bar");
                    $("#top_bar").load(" ");
                    //   toastr.success('item add Successfully...');
                    //   setTimeout(function(){
                    //     location.reload();
                    //   },2000);
                }
            });
        });

        $('body').on('click', '#addToCartBtn', function(e) {

            e.preventDefault();
            var product_id = $('#product_id').val();
            var item_id = $('#item_id').val();
            //alert(product_id);
            $.ajax({

                url: '{{ url('addTocart') }}',
                type: 'get',
                data: {
                    'product_id': product_id,
                    'item_id': item_id
                },
                success: function(data) {
                    //   console.log(data);
                    $("#top_bar").load(" #top_bar");
                    toastr.success('item add Successfully...');
                }
            });

        });

        function addTocartProduct(productId, itemId) {

            $.ajax({
                url: '{{ url('addTocart') }}',
                type: 'get',
                data: {
                    'product_id': productId,
                    'item_id': itemId
                },
                success: function(data) {
                    //   console.log(data);
                    $("#top_bar").load(" #top_bar");
                    toastr.success('item add Successfully...');
                }
            });
        }

        function removeTocartProduct(productId, itemId) {

            $.ajax({
                url: '{{ url('removeTocart') }}',
                type: 'get',
                data: {
                    'product_id': productId,
                    'item_id': itemId
                },
                success: function(data) {
                    $("#top_bar").load(" #top_bar");
                    toastr.success('item remove Successfully...');
                    window.location.reload(true);
                }
            });
        }


        $('body').on('click', '#buyNow', function(e) {



            e.preventDefault();

            var product_id = $('#product_id').val();

            var item_id = $('#item_id').val();

            $.ajax({

                url: '{{ url('addTocart') }}',

                type: 'get',

                data: {
                    'product_id': product_id,
                    'item_id': item_id
                },

                success: function(data) {



                    //  console.log(data);

                    $("#top_bar").load(" #top_bar");

                    toastr.success('item add Successfully...');

                }

            });



        });



        /*$('body').on('click','.by_now',function(e){



            e.preventDefault();

             var product_id=$(this).data('id');

            var item_id=$(this).parent('.add-cart').prev().prev('.item_id'+product_id).text();

           // alert(product_id);

            var cart_cookie=getCookie("cart_cookie");

            var cartcookie='';

            if(cart_cookie){

                cartcookie=cart_cookie;

            }else{

            var id=create_UUID();        

             setCookie('cart_cookie',id,30);

             var cart_cookie_new=getCookie("cart_cookie");

             cartcookie=cart_cookie_new;

            }

            $.ajax({

                  url:'{{ url('add_tocart') }}',

                  type:'get',

                  data:{'cartcookie':cartcookie,'product_id':product_id,'item_id':item_id},

                  success:function(data){

                    

                  window.location.href = "{{ url('checkout/checkout') }}";

                  }

             });

            

        });*/
    </script>



    <script>
        var changePrice = document.querySelectorAll(".changePrice");

        var item_id = document.querySelector("#item_id");

        var priceset = document.querySelector("#priceset");



        var changePrice = document.querySelectorAll(".changePrice");



        function removeClass() {

            changePrice.forEach((btn, index) => {

                btn.classList.remove("active_product");

            });

        }





        function updatePrice(itemId) {



            const data = {

                "_token": "{{ csrf_token() }}",

                "itemid": itemId

            };

            const response = fetch("{{ url('/ajax_item_price') }}", {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json'

                        //'Content-Type': 'application/x-www-form-urlencoded'

                    },

                    body: JSON.stringify(data)

                })

                .then(res => {
                    return res.json()
                });



            response.then((responseData) => {



                //console.log(responseData.data);

                var itemData = responseData.data;

                var markup = `<span class="fw-500 text-primary"> ₹ ${itemData.item_price} </span>

        				<span class="text-secondary ml-2"><del> ₹ ${itemData.item_mrp_price} </del></span>

        				<small class="text-primary ml-4">Save 3%</small>

        				`;

                priceset.innerHTML = markup;





            }).catch((err) => {

                console.log('res :' + err);

            });



        }



        changePrice.forEach((btn, index) => {



            btn.addEventListener("click", function() {

                removeClass();

                var itemId = btn.dataset.id;

                item_id.value = btoa(itemId);

                btn.classList.add('active_product');

                updatePrice(itemId);



            });

        });
    </script>



    <script>
        $(document).ready(function() {



            $('.add-to-wishlist').click(function() {



                var productid = $(this).attr('data-product_id');



                $.ajax({

                    url: '{{ url('/addToWishlist') }}',

                    type: 'get',

                    data: {
                        'productid': productid
                    },

                    success: function(data) {



                        if (data.status == 'error') {

                            $("#top_bar").load(" #top_bar");

                            toastr.error(data.message);

                        } else {

                            $("#top_bar").load(" #top_bar");

                            toastr.success(data.message);

                        }

                    }

                });



            });



        });
    </script>



    <script>
        $(window).on('load', function() {

            $portfolio_filter = $('.grid');

            $portfolio_filter.isotope({

                itemSelector: '.element-item',

                layoutMode: 'masonry'

            });

        });
    </script>







    <script>
        // var uu = "https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&location_type=ROOFTOP&result_type=street_address&key=AIzaSyCjT2-PVCaU_hHi0UZmFDJGAXCeEQSjplI";



        var geocoder;

        var map;



        $(document).ready(function() {

            if (navigator.geolocation)

            {

                navigator.geolocation.getCurrentPosition(successFunction, errorFunction);

            } else

            {

                alert(
                    'It seems like Geolocation, which is required for this page, is not enabled in your browser.'
                );

            }

        });



        function successFunction(position)

        {

            var lat = position.coords.latitude;

            var long = position.coords.longitude;



            var latlon = lat + ',' + long;







            var Newuu = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + latlon +
                "&key=AIzaSyD_Wq5KWsd6xPZ1jrJgUNTeR5Xx5hoSiFQ";



            fetch(Newuu).then(response => response.json())

                .then(doc => {

                    document.getElementById("location_div").innerHTML = doc.results[0].formatted_address;

                });



        }


        function errorFunction(position)

        {

            console.log(position);

            document.getElementById("location_div").innerHTML = "Address not allow";

        }
    </script>


    <script>
        $(document).ready(function() {
            $('.gift_wrap').change(function() {

                var pid = $(this).attr("data-productid");
                var itmid = $(this).attr("data-itemid");
                var type = '';

                if ($(this).prop("checked") == true) {
                    type = "add";
                } else if ($(this).prop("checked") == false) {
                    type = "remove";
                }

                $.ajax({
                    url: '{{ url('giftwrap_add') }}',
                    type: 'get',
                    data: {
                        'product_id': pid,
                        'item_id': itmid,
                        'type': type
                    },
                    success: function(data) {

                        if (data == 'success') {
                            toastr.success('wrap add Successfully...');
                        } else {
                            toastr.success('wrap remove Successfully...');
                        }
                        window.location.reload(true);
                    }
                });

            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".input").keyup(function() {
                var currentValue = $(this).val();
                var newValue = currentValue.replace(/[^a-z\s]/gi, '');
                var checkValue = /^[a-zA-Z ]*$/;

                if (checkValue.test(currentValue) == false) {
                    $(this).siblings().html("Can't contains spacil characters.");
                    $(this).siblings().css({
                        "color": "red"
                    });
                }
                $(this).val(newValue);
            });


            $(".email").keyup(function() {
                var currentValue = $(this).val();
                var newValue = currentValue.replace(/[^a-z0-9,@._]/gi, '');
                var checkValue = /^[a-z0-9,@._]*$/;

                if (checkValue.test(currentValue) == false) {
                    $(this).siblings().html("Cant contains spacel characters.");
                    $(this).siblings().css({
                        "color": "red"
                    });
                }
                $(this).val(newValue);
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $("#notify_me").click(function() {

                var it_id = $("#product_id").val();
                var p_id = $("#item_id").val();

                $.ajax({
                    url: '{{ url('notifyme_product') }}',
                    type: 'get',
                    data: {
                        'product_id': p_id,
                        'item_id': it_id
                    },
                    success: function(data) {

                        console.log(data.status);

                        if (data.status == 'error') {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                        }
                    }
                });

            });
        });
    </script>







</body>

</html>
