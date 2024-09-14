  @extends('layouts.app')
  @section('content')
      <section class="breadcrumb-area">
          <div class="container">
              <div class="breadcrumb-content">
                  <ul>
                      <li><a href="{{ url('/') }}">Home</a></li>
                      @if (@$search_value)
                          <li>{{ @$search_value }}</li>
                      @endif
                  </ul>
              </div>
          </div>
      </section>

      <section class="pt-3 pb-4 pb-md-5">
          <div class="container">
              <div class="row">


                  <div class="col-lg-12">



                      <!-- End Sorting -->
                      <div class="products">
                          <div class="row gutters-2">
                              @if (count($all_products) > 0)
                                  @foreach ($all_products as $c_product)
                                      <div class="col-md-6 col-lg-3 mb-4">
                                          <!-- Product -->
                                          <div class="product-block text-center h-100">
                                              <div class="position-relative">

                                                  @if (!empty($c_product['image']))
                                                      <img class="product-thumb"
                                                          src="<?php echo asset('/uploads/service'); ?>/{{ $c_product['image'] }}">
                                                  @else
                                                      <img class="product-thumb img-fluid" src="<?php echo asset('/uploads/default-store.jpg'); ?>"
                                                          alt="{{ $item['name'] }}">
                                                  @endif
                                                  <!--<div class="offer"><span class="badge badge-primary">HOT</span></div>-->
                                                  <div class="hover-content">
                                                      <button type="button" data-product_id="{{ $c_product['id'] }}"
                                                          class="btn btn-outline-primary rounded-circle add-to-wishlist"
                                                          data-toggle="tooltip" data-placement="top" title=""
                                                          data-original-title="Save for wishlist">
                                                          <span class="fa fa-heart"></span>
                                                      </button>
                                                      <!--<button type="button" class="btn btn-outline-primary rounded-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to cart">-->
                                                      <!--<span class="fa fa-shopping-cart"></span>-->
                                                      <!--</button>-->
                                                  </div>
                                              </div>
                                              <div class="product-body pt-3 px-3 pb-0">
                                                  <div class="mb-2">
                                                      <a href="{{ url('/product_details', $c_product['slug']) }}"
                                                          class="product-title">
                                                          {{ Str::words($c_product['service_name'], '5') }} </a>
                                                      <div class="my-1 small">
                                                          <small class="fa fa-star text-warning"></small>
                                                          <small class="fa fa-star text-warning"></small>
                                                          <small class="fa fa-star text-warning"></small>
                                                          <small class="fa fa-star text-warning"></small>
                                                          <small class="fa fa-star text-muted"></small>
                                                      </div>
                                                      <div class="d-block">
                                                          <span class="fw-500 text-primary">₹
                                                              {{ @$c_product['item_price'] }}</span><span
                                                              class="text-secondary ml-2"><del>₹
                                                                  {{ @$c_product['item_mrp_price'] }}</del></span>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <!-- End Product -->
                                      </div>
                                  @endforeach
                              @else
                                  <h5> No record available... </h5>
                              @endif
                          </div>
                      </div>



                  </div>
              </div>
          </div>
      </section>

  @stop
