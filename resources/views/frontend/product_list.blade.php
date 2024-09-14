  @extends('layouts.app')
  @section('content')
      <section class="breadcrumb-area">
          <div class="container">
              <div class="breadcrumb-content">
                  <ul>
                      <li><a href="{{ url('/') }}">Home</a></li>
                      <li>{{ @$category_banner->category_name }}</li>

                  </ul>
              </div>
          </div>
      </section>

      <section class="pt-3 pb-4 pb-md-5">
          <div class="container">
              <div class="row">
                  <div class="col-lg-3">
                      <form>
                          <div class="widget">
                              <!--<h2 class="widget-title">Accessories</h2>-->
                              <ul class="list-inline mb-0">
                                  @if ($main_category)
                                      @foreach ($main_category as $maincategory)
                                          <li>
                                              <a href="{{ url('/shop', $maincategory->slug) }}"
                                                  class="d-flex align-items-center justify-content-between">
                                                  {{ $maincategory->name }}
                                                  <span>{{ DB::table('services')->where('parent_category', $maincategory->id)->count() }}
                                                  </span>
                                              </a>
                                          </li>
                                      @endforeach
                                  @endif
                              </ul>
                          </div>

                          <!--<div class="widget">-->
                          <!--	<h2 class="widget-title">Price</h2>-->
                          <!--	<div class="d-flex align-items-center justify-content-between">-->
                          <!--		<span>Ranger:</span>-->
                          <!--		<span>$13.99 - $25.99</span>-->
                          <!--	</div>-->
                          <!--</div>-->

                          <!--<div class="widget">-->
                          <!--	<h2 class="widget-title">Color</h2>-->
                          <!--	<div class="radio-color mt-3">-->
                          <!--                             <div class="radio">-->
                          <!--                                 <label>-->
                          <!--                                     <input type="radio" class="form-check-input" name="color" checked="">-->
                          <!--                                     <i class="form-icon blue"></i>-->
                          <!--                                 </label>-->
                          <!--                             </div>-->
                          <!--                             <div class="radio">-->
                          <!--                                 <label>-->
                          <!--                                     <input type="radio" class="form-check-input" name="color">-->
                          <!--                                     <i class="form-icon red"></i>-->
                          <!--                                 </label>-->
                          <!--                             </div>-->
                          <!--                             <div class="radio">-->
                          <!--                                 <label>-->
                          <!--                                     <input type="radio" class="form-check-input" name="color">-->
                          <!--                                     <i class="form-icon black"></i>-->
                          <!--                                 </label>-->
                          <!--                             </div>-->
                          <!--                             <div class="radio">-->
                          <!--                                 <label>-->
                          <!--                                     <input type="radio" class="form-check-input" name="color">-->
                          <!--                                     <i class="form-icon yellow"></i>-->
                          <!--                                 </label>-->
                          <!--                             </div>-->
                          <!--                             <div class="radio">-->
                          <!--                                 <label>-->
                          <!--                                     <input type="radio" class="form-check-input" name="color">-->
                          <!--                                     <i class="form-icon pink"></i>-->
                          <!--                                 </label>-->
                          <!--                             </div>-->
                          <!--                             <div class="radio">-->
                          <!--                                 <label>-->
                          <!--                                     <input type="radio" class="form-check-input" name="color">-->
                          <!--                                     <i class="form-icon gray"></i>-->
                          <!--                                 </label>-->
                          <!--                             </div>-->
                          <!--                         </div>-->
                          <!--</div>-->

                          <!--<div class="widget">-->
                          <!--	<h2 class="widget-title">Brand</h2>-->
                          <!--	<ul class="list-inline mb-0">-->
                          <!--		<li>-->
                          <!--			<a href="#" class="d-flex align-items-center justify-content-between">-->
                          <!--				Apple <span>20</span>-->
                          <!--			</a>-->
                          <!--		</li>-->
                          <!--		<li>-->
                          <!--			<a href="#" class="d-flex align-items-center justify-content-between">-->
                          <!--				LG <span>48</span>-->
                          <!--			</a>-->
                          <!--		</li>-->
                          <!--		<li>-->
                          <!--			<a href="#" class="d-flex align-items-center justify-content-between">-->
                          <!--				Samsung <span>14</span>-->
                          <!--			</a>-->
                          <!--		</li>-->
                          <!--		<li>-->
                          <!--			<a href="#" class="d-flex align-items-center justify-content-between">-->
                          <!--				Simens <span>15</span>-->
                          <!--			</a>-->
                          <!--		</li>-->
                          <!--	</ul>-->
                          <!--</div>-->

                          <!--<div class="widget">-->
                          <!--	<a href="#" class="d-block text-center text-uppercase">More</a>-->
                          <!--</div>-->
                      </form>
                  </div>

                  <div class="col-lg-9">
                      <!--<div class="mb-3">-->
                      <!--	<a href="#"><img src="<?php echo asset('/uploads/category/'); ?>/{{ $category_banner->image }}"></a>-->
                      <!--</div>-->
                      <!-- Sorting -->

                      <div class="px-3 py-2 mb-4" style="background-color: #F9F9F9;">
                          <div class="row align-items-center">
                              <div class="col-lg-8 mb-3 mb-lg-0 d-flex align-items-center">
                                  <span class="text-muted mr-3"> {{ count($category_by_product) }} Items </span>
                                  <form action="">
                                      <ul class="list-inline mb-0">
                                          <li class="list-inline-item">
                                              <!-- Select -->

                                              <div class="dropdown">
                                                  <select class="custom-select" name="short" required>
                                                      <option value="">Sort by</option>
                                                      <option value="newest"
                                                          {{ request()->input('short') == 'newest' ? 'selected' : '' }}>
                                                          Newest
                                                          first</option>
                                                      <option value="priceHighLow"
                                                          {{ request()->input('short') == 'priceHighLow' ? 'selected' : '' }}>
                                                          Price (high - low)</option>
                                                      <option value="priceLowHigh"
                                                          {{ request()->input('short') == 'priceLowHigh' ? 'selected' : '' }}>
                                                          Price (low - high)</option>
                                                      <!--<option value="topSellers">Top sellers</option>-->
                                                  </select>
                                              </div>

                                              <!-- End Select -->
                                          </li>
                                          <li class="list-inline-item">
                                              <!-- Select -->
                                              <div class="dropdown d-flex align-items-center">
                                                  <span class="mr-2">Show</span>
                                                  <select class="custom-select" name="max_count">
                                                      <option value="10"
                                                          {{ request()->input('max_count') == '10' ? 'selected' : '' }}>10
                                                      </option>
                                                      <option value="20"
                                                          {{ request()->input('max_count') == '20' ? 'selected' : '' }}>20
                                                      </option>
                                                      <option value="50"
                                                          {{ request()->input('max_count') == '50' ? 'selected' : '' }}>50
                                                      </option>
                                                      <option value="100"
                                                          {{ request()->input('max_count') == '100' ? 'selected' : '' }}>
                                                          100
                                                      </option>
                                                  </select>
                                              </div>
                                              <!-- End Select -->
                                          </li>
                                          <li class="list-inline-item">
                                              <input type="submit" value="Go Filter">
                                          </li>
                                      </ul>
                                  </form>
                              </div>

                          </div>
                      </div>

                      <!-- End Sorting -->
                      <div class="products">
                          <div class="row gutters-2">
                              @if (count($category_by_product) > 0)
                                  @foreach ($category_by_product as $c_product)
                                      <div class="col-md-6 col-lg-4 mb-4">
                                          <!-- Product -->
                                          <div class="product-block text-center h-100">
                                              <div class="position-relative">

                                                  @if (!empty($c_product->image))
                                                      <img class="product-thumb"
                                                          src="<?php echo asset('/uploads/service'); ?>/{{ $c_product->image }}">
                                                  @else
                                                      <img class="product-thumb img-fluid" src="<?php echo asset('/uploads/default-store.jpg'); ?>">
                                                  @endif

                                                  <!--<div class="offer"><span class="badge badge-primary">HOT</span></div>-->
                                                  <div class="hover-content">
                                                      <button type="button"
                                                          class="btn btn-outline-primary rounded-circle add-to-wishlist"
                                                          data-toggle="tooltip" data-placement="top"
                                                          data-product_id="{{ $c_product->id }}"
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
                                                      <a href="{{ url('/product_details', $c_product->slug) }}"
                                                          class="product-title">
                                                          {{ Str::words($c_product->service_name, '5') }}
                                                          <div class="my-1 small">
                                                              <small class="fa fa-star text-warning"></small>
                                                              <small class="fa fa-star text-warning"></small>
                                                              <small class="fa fa-star text-warning"></small>
                                                              <small class="fa fa-star text-warning"></small>
                                                              <small class="fa fa-star text-muted"></small>
                                                          </div>
                                                          <?php
                                                          \App\Helper\Helper::discount(@$c_product->items->item_mrp_price, @$c_product->items->item_price);
                                                          ?>

                                                          <div class="d-block">
                                                              <span class="fw-500 text-primary">₹
                                                                  {{ @$c_product->items->item_price }}</span>
                                                              <span class="text-secondary ml-2"><del>₹
                                                                      {{ @$c_product->items->item_mrp_price }}</del></span>
                                                          </div>

                                                      </a>
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

                      <!--<div class="products list">-->
                      <!--	<div class="product-block-list">-->
                      <!--		<div class="row gutters-2">-->
                      <!--			<div class="col-md-4">-->
                      <!--				<div class="product-block h-100">-->
                      <!--					<div class="position-relative">-->
                      <!--						<img class="product-thumb" src="<?php echo asset('/'); ?>front/images/product-thumb.png" alt="Image Description">-->
                      <!--						<div class="offer">-->
                      <!--							<span class="badge badge-primary">HOT</span>-->
                      <!--						</div>-->
                      <!--					</div>-->
                      <!--				</div>-->
                      <!--			</div>-->
                      <!--			<div class="col-md-8">-->
                      <!--				<div class="product-body">-->
                      <!--					<div class="mb-2">-->
                      <!--						<a href="single-product.html" class="product-title h4">Apple Macbook Pro</a>-->
                      <!--						<div class="d-flex align-items-center small mb-2">-->
                      <!--		                    <div class="text-warning mr-2">-->
                      <!--		                        <small class="fa fa-star"></small>-->
                      <!--		                        <small class="fa fa-star"></small>-->
                      <!--		                        <small class="fa fa-star"></small>-->
                      <!--		                        <small class="fa fa-star"></small>-->
                      <!--		                        <small class="fa fa-star text-muted"></small>-->
                      <!--		                    </div>-->
                      <!--		                    <span>0 Reviews</span>-->
                      <!--		                    <a class="js-go-to ml-2" href="#reviewSection" data-target="/product_details#reviewSection">Submit a review</a>-->
                      <!--		                </div>-->
                      <!--						<div class="d-block h4">-->
                      <!--							<span class="fw-500 text-primary">$499</span>-->
                      <!--							<span class="text-secondary ml-2"><del>$599</del></span>-->
                      <!--						</div>-->
                      <!--						<p class="small mb-2">Nunc facilisis sagittis ullamcorper. Proin lectus ipsum, gravida et mattis vulputate, tristique ut lectus. Sed et lectus lorem nunc leifend laorevtr istique et congue. Vivamus adipiscin vulputate g nisl ut dolor ...</p>-->
                      <!--						<button type="button" class="btn btn-air-primary"><i class="fa fa-shopping-cart mr-2"></i> Add to cart</button>-->
                      <!--						<button type="button" class="btn btn-air-primary ml-2"><i class="fa fa-heart-o"></i></button>-->
                      <!--					</div>-->
                      <!--				</div>-->
                      <!--			</div>-->
                      <!--		</div>-->
                      <!--	</div>-->
                      <!--	<div class="product-block-list">-->
                      <!--		<div class="row gutters-2">-->
                      <!--			<div class="col-md-4">-->
                      <!--				<div class="product-block h-100">-->
                      <!--					<div class="position-relative">-->
                      <!--						<img class="product-thumb" src="<?php echo asset('/'); ?>front/images/product-thumb.png" alt="Image Description">-->
                      <!--						<div class="offer">-->
                      <!--							<span class="badge badge-primary">HOT</span>-->
                      <!--						</div>-->
                      <!--					</div>-->
                      <!--				</div>-->
                      <!--			</div>-->
                      <!--			<div class="col-md-8">-->
                      <!--				<div class="product-body">-->
                      <!--					<div class="mb-2">-->
                      <!--						<a href="single-product.html" class="product-title h4">Apple Macbook Pro</a>-->
                      <!--						<div class="d-flex align-items-center small mb-2">-->
                      <!--		                    <div class="text-warning mr-2">-->
                      <!--		                        <small class="fa fa-star"></small>-->
                      <!--		                        <small class="fa fa-star"></small>-->
                      <!--		                        <small class="fa fa-star"></small>-->
                      <!--		                        <small class="fa fa-star"></small>-->
                      <!--		                        <small class="fa fa-star text-muted"></small>-->
                      <!--		                    </div>-->
                      <!--		                    <span>0 Reviews</span>-->
                      <!--		                    <a class="js-go-to ml-2" href="#reviewSection" data-target="/product_details#reviewSection">Submit a review</a>-->
                      <!--		                </div>-->
                      <!--						<div class="d-block h4">-->
                      <!--							<span class="fw-500 text-primary">$499</span>-->
                      <!--							<span class="text-secondary ml-2"><del>$599</del></span>-->
                      <!--						</div>-->
                      <!--						<p class="small mb-2">Nunc facilisis sagittis ullamcorper. Proin lectus ipsum, gravida et mattis vulputate, tristique ut lectus. Sed et lectus lorem nunc leifend laorevtr istique et congue. Vivamus adipiscin vulputate g nisl ut dolor ...</p>-->
                      <!--						<button type="button" class="btn btn-air-primary"><i class="fa fa-shopping-cart mr-2"></i> Add to cart</button>-->
                      <!--						<button type="button" class="btn btn-air-primary ml-2"><i class="fa fa-heart-o"></i></button>-->
                      <!--					</div>-->
                      <!--				</div>-->
                      <!--			</div>-->
                      <!--		</div>-->
                      <!--	</div>-->
                      <!--</div>-->

                      <!--<div class="pagination-block">-->
                      <!--	<ul class="pagination pagination-lg">-->
                      <!--		<li class="page-item"><a class="page-link" href="#">1</a></li>-->
                      <!--	</ul>-->
                      <!--</div>-->

                  </div>
              </div>
          </div>
      </section>

  @stop
