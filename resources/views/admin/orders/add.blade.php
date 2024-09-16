@extends('admin.layouts.app')
@section('title', 'Add Order')
@section('order_open', 'menu-open')
@section('order_create_active', 'active')
@section('content')
    <style>
        form#customer_form,
        #order_form {
            width: 100%;
        }

        .item_quantity {
            width: 45px;
            padding: 0px 0px 0px 5px;
        }
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?= @$page_title ?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}">Order</a></li>
                            <li class="breadcrumb-item active"><?= @$page_title ?></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div id="customer_div">
                    <form action="{{ route('admin.customer.store') }}" method="post" enctype="multipart/form-data"
                        id="customer_form">
                        @csrf
                        <input type="hidden" name="user_type" value="2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Name</label> <span style="color:red">*</span>
                                            <input type="text" name="name" required id="name"
                                                class="form-control" data-placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email</label> <span style="color:red">*</span>
                                            <input type="email" name="email" required id="email"
                                                class="form-control" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Password</label>
                                            <input type="password" name="password" value="user@12345" class="form-control"
                                                placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Phone</label>
                                            <input type="tel" name="phone" value="" id="phone"
                                                class="form-control" placeholder="Phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Image</label>
                                            <input type="file" name="image" id="view_img" class="form-control">
                                            <div class="viewimg" style="display: none"><img id="v_img" src=""
                                                    alt="your image" style="width: 22%; padding: 1%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Address1</label> <span style="color:red">*</span>
                                            <input type="text" name="address_1" required value=""
                                                placeholder="Address" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Address2</label>
                                            <input type="text" name="address_2" value=""
                                                placeholder="Address 2 (optional)" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Zip Code</label>
                                            <input type="text" name="pincode" value=""
                                                placeholder="Zip code (optional)" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Country</label> <span style="color:red">*</span>
                                            <select class="form-control countries" name="country" id="countries">
                                                <option value=""> --select--</option>
                                                @foreach ($countries as $key => $item)
                                                    <option value="{{ $key }}"> {{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>State</label>
                                            <select class="form-control state" name="states" id="states">
                                                <option value=""> --select--</option>
                                                @foreach ($states as $key => $item)
                                                    <option value="{{ $key }}"> {{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>City</label>
                                            <select class="form-control city" name="city" id="cities">
                                                <option value=""> --select--</option>
                                                @foreach ($cities as $key => $item)
                                                    <option value="{{ $key }}"> {{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="pb-5 pt-3">
                                    <button type="submit" class="btn btn-sm btn-info">Save</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="from" value="order">
                    </form>
                </div>
                <form action="{{ route('admin.order.store') }}" method="post" enctype="multipart/form-data"
                    id="order_form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Customer</label>
                                        <input type="text" name="customer" required value="{!! Session::has('user_id') ? Session::get('user_id') : '' !!}"
                                            id="customer" class="form-control" data-placeholder="Select Customer">

                                        <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                            <a href="javascript:void(0);" id="customer_action" class="external">
                                                <i class="fa fa-plus" id="addIcon" style="font-size: 1.2em;"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Search product</label>
                                        <input type="text" name="product_name" id="search_product"
                                            class="form-control" data-placeholder="Search product">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <label>Cart Items</label>
                            <table id="cart_table" class="table table-bordered table-striped" data-grand_total="0">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center">No Items Selected</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr id="shipping_tr">
                                        <td colspan="3"></td>
                                        <td>Shipping</td>
                                        <td id="shipping_val"></td>
                                        <td></td>
                                    </tr>
                                    <tr id="orderdiscount_tr">
                                        <td colspan="3"></td>
                                        <td>Discount</td>
                                        <td id="discount_val"></td>
                                        <td></td>
                                    </tr>
                                    <tr id="grand_total_tr">
                                        <td colspan="3"></td>
                                        <td>Grand total</td>
                                        <td id="grand_total_val"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Discount</label>
                                        <input type="number" name="order_discount" required value=""
                                            id="order_discount" class="form-control" data-placeholder="Order Discount">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Shipping</label>
                                        <input type="number" name="shipping" required value="" id="shipping"
                                            class="form-control" data-placeholder="Shipping">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select class="form-control status" name="status" id="status">
                                            <option value=""> --select--</option>
                                            @foreach ($status as $key => $item)
                                                <option value="{{ $key }}"> {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Payment Status</label>
                                        <select class="form-control payment_status" name="payment_status"
                                            id="payment_status">
                                            <option value=""> --select--</option>
                                            @foreach ($payment_status as $key => $item)
                                                <option value="{{ $key }}"> {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Note</label>
                                        <textarea class="form-control textarea" name="note"><?= @$_POST['note'] ?></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="button" class="btn btn-sm btn-info" id="place_order">Place Order</button>
                    </div>
                </form>
        </section>
    </div>
@stop
@push('js')
    <script src="{{ asset('admin-assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="<?php echo asset('/admin-assets/order.js'); ?>"></script>
    <script src="<?php echo asset('/admin-assets/user.js'); ?>"></script>
@endpush
