@extends('layouts.app')
@section('content')

    <section class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('shop', $product_details['category_slug']) }}"> {{ $product_details['category'] }}
                        </a></li>
                    <li> {{ $product_details['name'] }} </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="pt-3 pb-4 pb-md-5">
        <div class="container">
            <div class="row product-detail">
                <div class="col-lg-9">
                    <div class="row gutters-2">
                        <!-- First column -->
                        <div class="col-md-5">
                            <div class="product-detail-slider text-center">
                                <div class="item">
                                    <img src="{{ url('/') }}/{{ $product_details['image'] }}" class="img-fluid" />
                                </div>

                                @if (!empty($product_details['product_slider']))
                                    @foreach ($product_details['product_slider'] as $product_image)
                                        <div class="item">
                                            <img src="{{ url('/uploads/service') }}/{{ $product_image->image }}" />
                                        </div>
                                    @endforeach
                                @endif
                                <!--<img src="{{ url('/') }}/{{ $product_details['image'] }}" class="img-fluid" /> -->
                            </div>
                        </div>
                        <div class="col-md-7">
                            <h2 class="h4"> {{ $product_details['name'] }} </h2>
                            <div class="d-flex align-items-center justify-content-between small mb-2">
                                <div class="text-warning mr-2">
                                    <small class="fa fa-star"></small>
                                    <small class="fa fa-star"></small>
                                    <small class="fa fa-star"></small>
                                    <small class="fa fa-star"></small>
                                    <small class="fa fa-star text-muted"></small>&nbsp;
                                    <!-- <span>0 Reviews</span> -->
                                </div>

                                <!--<a class="js-go-to ml-2" href="#reviewSection" data-target="#reviewSection">Submit a review</a>-->

                                <?php
                                if (isset($_COOKIE['setPincode'])) {
                                    if ($_COOKIE['setPincode'] > 0) {
                                        $hasPincode = $_COOKIE['setPincode'];
                                    } else {
                                        $hasPincode = 0;
                                    }
                                } else {
                                    $hasPincode = 0;
                                }
                                ?>

                                <div align="right">
                                    <input type="number" id="check_area" value="<?php echo $hasPincode > 0 ? $hasPincode : ''; ?>"
                                        style="display:block; border:0px; border-bottom: 1px solid #d0ab3d; outline: none;"
                                        placeholder="Enter delivery pincode">
                                    <span id="show_msg" class="k"></span>
                                </div>
                            </div>

                            <form action="{{ url('/buy-now') }}">

                                <input type="hidden" name="p" value="{{ $product_details['slug'] }}">
                                <input type="hidden" name="product_id" id="product_id" class="product_id"
                                    value="{{ base64_encode($product_details['id']) }}">
                                <input type="hidden" name="item_id" id="item_id" class="item_id"
                                    value="{{ base64_encode($product_details['item_id']) }}">
                                <hr>

                                <div class="d-block h4" id="priceset">
                                    <span class="fw-500 text-primary"> ₹ {{ $product_details['sale_price'] }} </span>
                                    <span class="text-secondary ml-2"><del> ₹ {{ $product_details['price'] }} </del></span>
                                    <small class="text-primary ml-4">
                                        Save
                                        {{ round(100 - ($product_details['sale_price'] * 100) / $product_details['price'], 2) }}
                                        %
                                    </small>
                                </div>
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Availability</td>
                                            <td> {{ $product_details['stock'] }} </td>
                                        </tr>
                                        <!-- <tr>
             <td>Delivery</td>
             <td>in 7-9 days </td>
            </tr> -->
                                    </tbody>
                                </table>

                                <div class="row">
                                    @foreach ($product_details['items'] as $item)
                                        <div class="col-sm-3">
                                            <div class="product_item changePrice {{ $product_details['item_id'] == $item->id ? 'active_product' : '' }}"
                                                id="addclass" data-id="{{ $item->id }}">

                                                @if ($item->image)
                                                    <img src="{{ url('/uploads/items') }}/{{ $item->image }}" />
                                                @else
                                                    <img src="{{ url('/') }}/{{ $product_details['image'] }}"
                                                        class="img-fluid" />
                                                @endif

                                                <p> {{ $item->color }} | {{ $item->size }} |
                                                    {{ $item->item_unit_value }} {{ $item->item_unit }} </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                                <!--<table class="table table-borderless table-sm mb-0">-->
                                <!--	<tbody>-->
                                <!--	   @if ($product_details['color'] == 'yes') -->
                                <!--		<tr>-->
                                <!--			<th>Select Color:</th>-->
                                <!--		    @foreach ($product_details['items'] as $color)
    -->
                                <!--			<td>-->
                                <!--				<div class="radio-color">-->
                                <!--                                      <div class="radio">-->
                                <!--                                          <label>-->
                                <!--                                              <input type="radio" class="form-check-input" name="color" checked="" value="{{ $color->id }}">-->
                                <!--                                              <i class="form-icon {{ $color->color }}"></i>-->
                                <!--                                          </label>-->
                                <!--                                      </div>-->
                                <!--                                  </div>-->
                                <!--			</td>-->
                                <!--
    @endforeach	-->
                                <!--		</tr>-->
                                <!--		@endif-->

                                <!--		@if ($product_details['size'] == 'yes') -->
                                <!--		<tr>-->
                                <!--			<th>Size:</th>-->
                                <!--			<td>-->
                                <!--				<div class="d-flex align-items-center justify-content-between">-->
                                <!--					 Select -->
                                <!--					<div class="dropdown w-100">-->
                                <!--						<select class="custom-select">-->
                                <!--						@foreach ($product_details['items'] as $size)
    -->
                                <!--							<option value="{{ $size->size }}" selected=""> {{ $size->size }} </option>-->
                                <!--
    @endforeach	-->
                                <!--						</select>-->
                                <!--					</div>-->
                                <!--					<button type="button" class="btn btn-air-primary btn-sm ml-4"><i class="fa fa-heart-o px-1"></i></button>-->
                                <!--				</div>-->
                                <!--			</td>-->
                                <!--		</tr>-->
                                <!--		@endif-->
                                <!--	</tbody>-->
                                <!--</table>-->


                                <div class="mt-3">
                                    <div class="row gutters-1">

                                        @if ($product_details['stock'] == 'out of stock')
                                            <div class="col-xl-9 mt-2 mt-xl-0">
                                                <a href="javascript:void(0)" class="btn btn-air-primary btn-sm btn-block"
                                                    id="notify_me"> Notify me </a>
                                            </div>
                                        @else
                                            <div class="col-xl-9 mt-2 mt-xl-0">
                                                <div class="row gutters-1">
                                                    <div class="col-6">
                                                        <button type="submit" id="buyNow_b"
                                                            class="btn btn-air-primary btn-block"><i
                                                                class="fa fa-shopping-cart mr-2"></i> Buy Now </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="button" id="addToCartBtn"
                                                            class="btn btn-air-primary btn-block"><i
                                                                class="fa fa-shopping-cart mr-2"></i> Add to cart</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                            </form>
                            <hr>


                            <div class="row gutters-1">
                                <div class="col-sm-6">

                                    <button type="button" class="btn btn-facebook btn-block"><i
                                            class="fa fa-facebook mr-2"></i> <a
                                            href="https://www.facebook.com/sharer/sharer.php?u=http://marketingchord.com/anb/product_details/{{ $product_details['slug'] }}"
                                            target="_blank">Share on Facebook</a></button>
                                </div>
                                <div class="col-sm-6 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-twitter btn-block"><i
                                            class="fa fa-twitter mr-2"></i> Share on Twitter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <ul class="product-tab-list nav tab-border mt-5">

                            <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                    href="#product_info">Product Information</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reviews">Reviews <span
                                        class="text-muted">(0)</span></a></li>
                            <!--<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#another"> Another tab </a></li>-->

                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content mt-3">

                            <div class="tab-pane container active" id="product_info">{!! $product_details['description'] !!}</div>
                            <div class="tab-pane container active" id="reviews"></div>
                            <!--<div class="tab-pane container active" id="another"></div>-->

                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mt-4 mt-lg-0">
                    <p class="text-uppercase text-muted lead fw-400">Best Seller</p>
                    <div class="products">
                        <div class="best-seller-slider owl-dot-box">
                            @foreach ($best_seller_product as $best_product)
                                <div class="item">
                                    <div class="product-block text-center h-100">
                                        <div class="position-relative">

                                            <img class="product-thumb"
                                                src="<?php echo asset('/'); ?>{{ $best_product['image'] }}"
                                                alt="Image Description">
                                            <!--<div class="offer"><span class="badge badge-primary">HOT</span></div>-->

                                            <div class="hover-content">
                                                <button type="button" class="btn btn-outline-primary rounded-circle"
                                                    data-toggle="tooltip" data-placement="top" title=""
                                                    data-original-title="Save for later">
                                                    <span class="fa fa-heart"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="product-body pt-3 px-3 pb-0">
                                            <div class="mb-2">

                                                <a href="{{ url('/product_details', @$best_product['slug']) }}"
                                                    class="product-title"> {{ $best_product['name'] }} </a>
                                                <div class="my-1 small">
                                                    <small class="fa fa-star text-warning"></small>
                                                    <small class="fa fa-star text-warning"></small>
                                                    <small class="fa fa-star text-warning"></small>
                                                    <small class="fa fa-star text-warning"></small>
                                                    <small class="fa fa-star text-muted"></small>
                                                </div>
                                                <div class="d-block">
                                                    <span class="fw-500 text-primary"> ₹ {{ $best_product['sale_price'] }}
                                                    </span>
                                                    <span class="text-secondary ml-2"><del> ₹ {{ $best_product['price'] }}
                                                        </del></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#"><img src="<?php echo asset('/'); ?>front/images/sidebar-banner.png"
                                alt="Image Description"></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="text-center">
                <h3 class="text-uppercase">Related Products</h3>
            </div>
            <div class="products">
                <div class="row gutters-2">
                    @foreach ($related_product as $best_product)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="item">
                                <div class="product-block text-center h-100">
                                    <div class="position-relative">

                                        <img class="product-thumb" src="<?php echo asset('/'); ?>{{ $best_product['image'] }}"
                                            alt="Image Description">
                                        <!--<div class="offer"><span class="badge badge-primary">HOT</span></div>-->

                                        <div class="hover-content">
                                            <button type="button" class="btn btn-outline-primary rounded-circle"
                                                data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="Save for later">
                                                <span class="fa fa-heart"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-body pt-3 px-3 pb-0">
                                        <div class="mb-2">
                                            <a href="{{ url('/product_details', @$best_product['slug']) }}"
                                                class="product-title"> {{ $best_product['name'] }} </a>
                                            <div class="my-1 small">
                                                <small class="fa fa-star text-warning"></small>
                                                <small class="fa fa-star text-warning"></small>
                                                <small class="fa fa-star text-warning"></small>
                                                <small class="fa fa-star text-warning"></small>
                                                <small class="fa fa-star text-muted"></small>
                                            </div>
                                            <div class="d-block">
                                                <span class="fw-500 text-primary"> ₹ {{ $best_product['sale_price'] }}
                                                </span>
                                                <span class="text-secondary ml-2"><del> ₹ {{ $best_product['price'] }}
                                                    </del></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
@stop
