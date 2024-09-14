@extends('admin.layouts.app')
@section('title', 'Orders')
@section('order_open', 'menu-open')
@section('order_active', 'active')

@section('content')

    <!-- Content Wrapper. Contains page content -->

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

                            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>

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

                <div class="row">

                    <div class="col-md-12">

                        <div class="card">



                            <!-- /.card-header -->

                            <div class="card-body">

                                <?php $slug=Sentinel::getUser()->roles()->first()->slug;



        if($slug=='super_admin'){ ?>

                                <div class="row">

                                    {{ Form::open(['url' => 'admin/filter_orders', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

                                    <div class="col-md-12 text-right">



                                        <select name="filters" class="filters" style="float:left">

                                            <option value="">Filter Orders</option>

                                            <option>Filter By Status</option>

                                            <!-- <option>Filter By Datetime</option>-->

                                            <option>Filter By Date</option>

                                            <option>Filter By Area</option>

                                            <option>Filter By Order</option>

                                            <option>Filter By Customer</option>



                                        </select>

                                        <div class="row status" style="display:none">

                                            <div class="col-md-6">

                                                <select name="status">

                                                    <option value="pending">Pending</option>

                                                    <option value="ordered">Ordered</option>

                                                    <option value="processing">Processing</option>

                                                    <option value="delivered">Delivered</option>

                                                    <option value="returned">Returned</option>

                                                    <option value="cancelled">Cancelled</option>

                                                    <option value="shipped">Shipped</option>

                                                </select>

                                            </div>

                                            <div class="col-md-6">

                                                <input type="Submit" value="Filter" class="btn btn-info">

                                            </div>

                                        </div>

                                        <div class="row area" style="display:none">

                                            <div class="col-md-6">

                                                <select name="area">

                                                    <option value="">Select Area</option>

                                                    <?php foreach($area as $areas){ ?>

                                                    <option value="<?= $areas->id ?>"><?= $areas->area ?></option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <div class="col-md-6">

                                                <input type="Submit" value="Filter" class="btn btn-info">

                                            </div>

                                        </div>

                                        <div class="row customers" style="display:none">

                                            <!--<div class="col-md-5">

                            <input type="text" name="customer_search" placeholder="Name Or Number" class="form-control seach_customer">

                            </div>-->

                                            <div class="col-md-5">

                                                <select name="customers" class="form-control view_customer">

                                                    <option value="">Select Customer</option>

                                                    <?php foreach($customers as $user_val){ ?>

                                                    <option value="<?= $user_val->id ?>"><?= $user_val->phone ?>
                                                        <?= $user_val->first_name ?></option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <div class="col-md-2">

                                                <input type="Submit" value="Filter" class="btn btn-info">

                                            </div>

                                        </div>



                                        <div class="row order" style="display:none">

                                            <div class="col-md-5">

                                                <input type="text" name="from_order" placeholder="From"
                                                    class="form-control">

                                            </div>

                                            <div class="col-md-5">

                                                <input type="text" name="to_order" placeholder="To" class="form-control">

                                            </div>

                                            <div class="col-md-2">

                                                <input type="Submit" value="Filter" class="btn btn-info">

                                            </div>

                                        </div>



                                        <div class="row date_html" style="display:none">

                                            <div class="col-md-6">

                                                <div class="input-group">

                                                    <button type="button" class="btn btn-default float-right"
                                                        id="daterange-btn">

                                                        <i class="far fa-calendar-alt"></i> Date range picker

                                                        <i class="fas fa-caret-down"></i>

                                                    </button>



                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <input type="hidden" name="date1" class="date1">

                                                <input type="hidden" name="date2" class="date2">

                                                <input type="Submit" value="Filter" class="btn btn-info">

                                            </div>

                                        </div>

                                        <div class="row datetime_html" style="display:none">

                                            <div class="col-md-6">

                                                <div class="input-group">

                                                    <div class="input-group-prepend">



                                                        <!--<input type="text" class="form-control float-right" name="datetime" id="reservationtime" style="width:306px">-->

                                                        <input type="date" name="firstdate"><input type="time"
                                                            name="firsttime"> <input type="date"
                                                            name="seconddate"><input type="time" name="secondtime">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <input type="Submit" value="Filter" class="btn btn-info">

                                            </div>

                                        </div>



                                        </form>

                                    </div>

                                    <hr>

                                    <div class="col-md-12 text-right">

                                        <a href="{{ url('admin/order/create') }}" class="btn btn-sm btn-info">Add
                                            Order</a>

                                        <select name="deliveryboy" id="deliveryboy" style="float:left">

                                            <option value="">Select Delivery Boy</option>

                                            <?php foreach($deliveryboy as $boys){ ?>

                                            <option value="<?= $boys->id ?>"><?= $boys->first_name ?></option>

                                            <?php } ?>

                                        </select>

                                        <a href="#" class="btn btn-sm btn-info assignorder"
                                            style="float:left">Assign Orders</a>

                                        <a href="#" class="btn btn-sm btn-info bulk_print_order"
                                            style="float:left">Multiple print</a>







                                        <a href="#" class="btn btn-sm btn-info bulk_print_order_item"
                                            style="float:left">Print Order Items</a>

                                    </div>

                                </div>

                                <?php } ?>

                                <br>

                                <style>
                                    #example2_wrapper {
                                        overflow: scroll !important;
                                    }
                                </style>

                                <table id="example2" class="table table-bordered table-striped">

                                    <thead>

                                        <tr>

                                            <th>
                                                <div class="icheck-primary d-inline">

                                                    <input type="checkbox" id="ckbCheckAll">

                                                    <label for="ckbCheckAll"> All

                                                    </label>

                                                </div>
                                            </th>

                                            <th>Order date</th>

                                            <th>Order Id</th>

                                            <th>Customer Name</th>

                                            <th>Email</th>

                                            <th>Phone</th>

                                            <th>Total Amount</th>

                                            <th>Status</th>

                                            <th>Payment status</th>

                                            <th>Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php $i=1;

                    $total=array();

                    foreach ($list as $key => $item) {

                        if(count($item->get_items)>0){

                          $total[]=$item['grand_total'];  

                    ?>

                                        <tr>

                                            <td>{{ $i }} <div class="icheck-primary d-inline">

                                                    <input type="checkbox" name="checkname" value="<?= $item->id ?>"
                                                        id="checkboxPrimary<?= $i ?>" class="checkboxall">

                                                    <label for="checkboxPrimary<?= $i ?>">

                                                    </label>

                                                </div>
                                            </td>

                                            <td>{!! date('d/m/Y H:i:s', strtotime($item['date'])) !!}</td>

                                            <td>{{ isset($item['order_id']) ? $item['order_id'] : 'ORD000' . $item['id'] }}
                                            </td>

                                            <td>{{ $item['name'] }}</td>

                                            <td>{{ $item['email'] }}</td>

                                            <td>{{ $item['phone'] }}</td>

                                            <td>{{ number_format((float) $item['grand_total'], 2, '.', '') }}</td>

                                            <td>{!! ucfirst($item['status']) !!}</td>

                                            <td>{!! ucfirst($item['payment_status']) !!}</td>

                                            <td>

                                                <div class="btn-group text-left">

                                                    <p class="dot-container" aria-expanded="false"
                                                        data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></p>

                                                    <ul class="dropdown-menu animated flipInY pull-right" role="menu">

                                                        <li><a href="{{ url('admin/order/view') }}/<?= $item['id'] ?>"
                                                                class=""><i class="fa fa-eye"></i> Order Details</a>

                                                        </li>

                                                        @if ($slug == 'super_admin')
                                                            <li><a
                                                                    href="{{ url('admin/order/edit') }}/<?= $item['id'] ?>"><i
                                                                        class="fa fa-edit"></i> Edit Order</a></li>

                                                            <li><a onclick="return confirm('Are you sure delete this record ?..')"
                                                                    href="{{ url('admin/order/delete') }}/<?= $item['id'] ?>"><i
                                                                        class="fa fa-trash"></i> Delete Order</a></li>
                                                        @endif





                                                    </ul>

                                                </div>

                                                @if ($slug == 'super_admin')
                                                    <a target="_blank"
                                                        href="{{ url('admin/print_invoice_list/') }}/<?= $item['id'] ?>"
                                                        class="btn btn-primary btn-sm">Print invoice</a>

                                                    <a style="cursor: pointer; color:white;" data-toggle="modal"
                                                        data-target="#checkdeliveryboymodal" data-id='<?= $item['id'] ?>'
                                                        class="btn btn-primary btn-sm check_deliveryboy">Check Delivery
                                                        boy</a>
                                                @endif

                                            </td>

                                        </tr>



                                        <?php $i++; } } ?>



                                    </tbody>

                                    <tr>



                                        <td colspan="6" style="text-align:right"> Total Amount: </td>

                                        <td><strong><?= number_format((float) array_sum($total), 2, '.', '') ?></strong>
                                        </td>

                                    </tr>

                                </table>

                            </div>

                            <!-- /.card-body -->

                        </div>

                    </div>

                </div>

            </div><!-- /.container-fluid -->

        </section>

        <!-- /.content -->

    </div>

    <!-- /.content-wrapper -->



    <div class="modal fade" id="myModal" role="dialog">

        <div class="modal-dialog">



            <!-- Modal content-->

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">Modal Header</h4>

                </div>

                <div class="modal-body">

                    <p>Some text in the modal.</p>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>

            </div>



        </div>

    </div>

@stop
