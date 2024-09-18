@extends('admin.layouts.app')
@section('title', 'Orders')
@section('order_open', 'menu-open')
@section('order_active', 'active')

@section('content')

    <div class="content-wrapper">
        <div class="content-header my-2">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">All Orders</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 text-right">
                        {{-- <a href="{{ route('admin.dashboard') }}">Home</a> --}}
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <div class="card">
                    {{-- <form action="" method="get"> --}}
                    <form action="{{ route('admin.order.filter_orders') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <select name="filters" class="form-control filters">
                                            <option value="">Filter Orders</option>
                                            <option>Filter By Status</option>
                                            <option>Filter By Date</option>
                                            <option>Filter By Customer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 status" style="display:none">
                                    <div class="mb-3">
                                        <select name="status" class="form-control">
                                            <option value="pending">Pending</option>
                                            <option value="ordered">Ordered</option>
                                            <option value="processing">Processing</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="returned">Returned</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="shipped">Shipped</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 customers" style="display:none">
                                    <div class="mb-3">
                                        <select name="customers" class="form-control view_customer">
                                            <option value="">Select Customer</option>
                                            <?php foreach($customers as $user_val){ ?>
                                            <option value="<?= $user_val->id ?>"><?= $user_val->phone ?>
                                                <?= $user_val->name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 date_html" style="display:none">
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-default" id="daterange-btn">
                                            <i class="far fa-calendar-alt"></i> Date range picker
                                            <i class="fas fa-caret-down"></i>
                                        </button>
                                        <input type="hidden" name="date1" class="date1">
                                        <input type="hidden" name="date2" class="date2">
                                    </div>
                                </div>

                            </div>
                            <button type="submit" class="btn btn-success">Filter</button>
                            <a href="{{ route('admin.order.index') }}" class="btn btn-danger">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="mb-3">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-info bulk_print_order">Multiple
                                        print</a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-info bulk_print_order_item">Print
                                        Order Items</a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-info bulk_export_order_item">Export
                                        CSV</a>
                                    <a href="{{ url('admin/order/create') }}" class="btn btn-sm btn-info">Add Order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <style>
                        #example2_wrapper {
                            overflow: scroll !important;
                        }
                    </style>
                    <div class="card-body">
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
                                <?php 
                                $i=1;
                                $total = array();
                                foreach ($list as $key => $item) {
                                    if(count($item->get_items)>0){
                                        $total[] = $item->grand_total;
                                    ?>
                                <tr>
                                    <td>
                                        {{ $i }}
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" name="checkname" value="<?= $item->id ?>"
                                                id="checkboxPrimary<?= $i ?>" class="checkboxall">
                                            <label for="checkboxPrimary<?= $i ?>"></label>
                                        </div>
                                    </td>
                                    <td>{!! date('d/m/Y H:i:s', strtotime($item->date)) !!}</td>
                                    <td>{{ isset($item->order_id) ? $item->order_id : 'ORD000' . $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ number_format((float) $item->grand_total, 2, '.', '') }}</td>
                                    <td>{!! ucfirst($item->status) !!}</td>
                                    <td>{!! ucfirst($item->payment_status) !!}</td>
                                    <td>
                                        <div class="btn-group text-left">
                                            <p class="dot-container btn btn-warning btn-sm" aria-expanded="false"
                                                data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></p>
                                            <ul class="dropdown-menu animated flipInY pull-right" role="menu">
                                                <li><a href="{{ url('admin/order/view') }}/<?= $item->id ?>"
                                                        class=""><i class="fa fa-eye"></i> Order Details</a>
                                                </li>
                                                <li><a href="{{ url('admin/order/edit') }}/<?= $item->id ?>"><i
                                                            class="fa fa-edit"></i> Edit Order</a></li>
                                                <li><a onclick="return confirm('Are you sure delete this record ?..')"
                                                        href="{{ url('admin/order/delete') }}/<?= $item->id ?>"><i
                                                            class="fa fa-trash"></i> Delete Order</a></li>
                                            </ul>
                                        </div> &nbsp;&nbsp;
                                        <a target="_blank" href="{{ url('admin/print_invoice_list/') }}/<?= $item->id ?>"
                                            class="btn btn-primary btn-sm">Print invoice</a>
                                        <a style="cursor: pointer; color:white;" data-toggle="modal"
                                            data-target="#checkdeliveryboymodal" data-id='<?= $item->id ?>'
                                            class="btn btn-danger btn-sm check_deliveryboy">Check Delivery boy</a>
                                    </td>
                                </tr>
                                <?php $i++; } } ?>
                            </tbody>
                            <tr>
                                <td colspan="6" style="text-align:right"> Total Amount: </td>
                                <td><strong><?= number_format((float) array_sum($total), 2, '.', '') ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div><!-- /.card-body -->
            </div>
        </section>
    </div>

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

@endsection
