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
            <div class="card-body">

                <div class="row">
                    {{Form::open(array('url'=>'admin/order/edit','method'=>'post','enctype'=>'multipart/form-data','id'=>'order_form'))}}
                    
                    <input type="hidden" name="id" value="{{$order->id}}" id="order_id">
                    
                    <!--<div class="col-lg-4 col-6">-->
                    <!--    <div class="form-group">-->
                    <!--        <label>Customer</label>-->
                    <!--        <div class="input-group">-->
                    <!--            <input type="text" name="customer" required value="{{$order->user_id}}" id="customer"-->
                    <!--            class="form-control" data-placeholder="Select Customer">-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>

                <!--<div class="row">-->
                <!--    <div class="col-6">-->
                <!--        <div class="form-group">-->
                <!--            <label>Search product</label>-->
                <!--            <input type="text" name="" value="" id="search_product" class="form-control" data-placeholder="Search product">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <div class="row">

                    <div class="col-12">

                        <div class="form-group">

                            <label>Cart Items </label>

                            <table id="cart_table" class="table table-bordered table-striped" data-grand_total="0">

                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Vendor</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <!--<th>Action </th>-->
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
                                        <td> </td>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <input type="number" name="order_discount" required value="{{number_format((float)$order->order_discount, 2, '.', '')}}" id="order_discount" class="form-control" data-placeholder="Order Discount" readonly>                            
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Shipping</label>
                            <div class="input-group">
                                <input type="number" name="shipping" required value="{{number_format((float)$order->shipping, 2, '.', '')}}" id="shipping" class="form-control" data-placeholder="Shipping" readonly>                            
                            </div>
                        </div>
                    </div>

                    <!--<div class="col-md-3">-->
                    <!--    <div class="form-group">-->
                    <!--        <label>Status</label>-->
                    <!--        {{Form::select('status', $status, $order->status,['class'=>'form-control status','id'=>'status'])}}-->
                    <!--    </div>-->
                    <!--</div>-->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Payment status</label>
                            {{Form::select('payment_status', $payment_status, $order->payment_status,['class'=>'form-control payment_status','id'=>'payment_status'])}}
                        </div>
                    </div>
                </div>

                <!--<div class="row">-->
                <!--    <div class="col-md-12">-->
                <!--      <div class="form-group">-->
                <!--        <label>Note</label>-->
                <!--        <textarea class="form-control" name="note"><?=$order->note?></textarea>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="col-md-12 text-right">-->
                <!--    <button type="button" class="btn btn-sm btn-info" id="place_order">Save Order</button>-->
                <!--</div>-->
                
                <div class="clearfix"></div>

                {{Form::close()}}

            </div>
        </div>
        </div>

    </section>



</div>



@stop

@push('js')

<script src="{{asset('assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

<script src="<?php echo asset('/assets/order.js');?>"></script>

@endpush