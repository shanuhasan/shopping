@extends('admin.layout')
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
                    <h1 class="m-0 text-dark"><?=@$page_title?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('admin/order')}}">Order</a></li>
                        <li class="breadcrumb-item active"><?=@$page_title?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row" id="customer_div">
                    {{Form::open(array('url'=>'admin/customers/create','method'=>'post','enctype'=>'multipart/form-data','id'=>'customer_form'))}}
                    
                    <input type="hidden" name="user_type" value="5">
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>First name</label> <span style="color:red">*</span>
                            <input type="text" name="first_name" value="" required id="first_name" class="form-control"
                                data-placeholder="First name">
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>Last name</label>
                            <input type="text" name="last_name" value="" id="last_name" class="form-control"
                                placeholder="Last name">
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>Email</label> <span style="color:red">*</span>
                            <input type="email" name="email" required value="" id="email" class="form-control"
                                placeholder="Email">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" value="user@12345" id="phone" class="form-control"
                                placeholder="Password">
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone" value="" id="phone" class="form-control" placeholder="Phone">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" id="view_img" class="form-control">
                            <div class="viewimg" style="display: none"><img id="v_img" src="" alt="your image"
                                    style="width: 22%; padding: 1%;"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address1</label> <span style="color:red">*</span>
                            <input type="text" name="line_1" required value="" placeholder="Address"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address2</label>
                            <input type="text" name="line_2" value="" placeholder="Address 2 (optional)"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Zip Code</label>
                            <input type="text" name="zip_code" value="" placeholder="Zip code (optional)"
                                class="form-control">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Country</label> <span style="color:red">*</span>
                            {{Form::select('country', $countries, '',['class'=>'form-control countries','id'=>'countries','required'=>'required'])}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State</label>
                            {{Form::select('state', $states, '',['class'=>'form-control state','id'=>'states'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City</label>
                            {{Form::select('city', $cities,'',['class'=>'form-control city','id'=>'cities'])}}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-sm btn-info">Save</button>
                    </div>
                    <div class="clearfix"></div>
                    <input type="hidden" name="from" value="order">
                    {{Form::close()}}
                </div>
                
                <div class="row">
                    {{Form::open(array('url'=>'admin/order/create','method'=>'post','enctype'=>'multipart/form-data','id'=>'order_form'))}}
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>Customer</label>
                            <div class="input-group">
                                <input type="text" name="customer" required
                                    value="{!!Session::has('user_id')?Session::get('user_id'):''!!}" id="customer"
                                    class="form-control" data-placeholder="Select Customer sss">
                                    
                                <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                    <a href="#" id="customer_action" class="external">
                                        <i class="fa fa-plus" id="addIcon" style="font-size: 1.2em;"></i> 
                                    </a>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Search product</label>
                            <input type="text" name="product_name" id="search_product" class="form-control"
                                data-placeholder="Search product">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
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
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <input type="number" name="order_discount" required
                                    value="" id="order_discount"
                                    class="form-control" data-placeholder="Order Discount">                            
                            </div>
    
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Shipping</label>
                            <div class="input-group">
                                <input type="number" name="shipping" required
                                    value="" id="shipping"
                                    class="form-control" data-placeholder="Shipping">                            
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            {{Form::select('status', $status, 'pending',['class'=>'form-control status','id'=>'status'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Payment status</label>
                            {{Form::select('payment_status', $payment_status, 'pending',['class'=>'form-control payment_status','id'=>'payment_status'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Note</label>
                        <textarea class="form-control textarea" name="note"><?=@$_POST['note']?></textarea>
                      </div>
                    </div>
                  </div>
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-info" id="place_order">Place Order</button>
                </div>
                <div class="clearfix"></div>
                {{Form::close()}}
            </div>
        </div>
    </section>

</div>

@stop
@push('js')
<script src="{{asset('assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="<?php echo asset('/assets/order.js');?>"></script>
<script src="<?php echo asset('/assets/user.js');?>"></script>
@endpush