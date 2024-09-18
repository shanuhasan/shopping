@extends('admin.layouts.app')
@section('title', 'Edit Order')
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

                            <li class="breadcrumb-item"><a href="{{ url('admin/order') }}">Order</a></li>

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
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.order.update') }}" method="post" enctype="multipart/form-data"
                            id="order_form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $order->id }}" id="order_id">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Cart Items </label>
                                        <table id="cart_table" class="table table-bordered table-striped"
                                            data-grand_total="0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Name</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $i = 1;
                                                    $subtotal = 0;
                                                    $total_weight = [];
                                                    $total_pcs = [];
                                                @endphp
                                                @forelse ($items as $item)
                                                    @php
                                                        $subtotal += $item->total;
                                                    @endphp
                                                    <tr data-id={{ $item->id }}>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $item->service_name }}</td>
                                                        <td>INR {{ $item->price }}</td>
                                                        <td>{{ $item->quantity }} </td>
                                                        <td>INR {{ $item->total }}</td>
                                                        <td>
                                                            <select class="form-control status" name="status">
                                                                <option value="">--select--</option>
                                                                @foreach ($status as $key => $val)
                                                                    <option {{ $key == $item->status ? 'selected' : '' }}
                                                                        value="{{ $key }}">
                                                                        {{ $val }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @empty
                                                @endforelse
                                            </tbody>

                                            <tfoot>
                                                <tr id="shipping_tr">
                                                    <td colspan="3"></td>
                                                    <td>Shipping</td>
                                                    <td> INR {{ $order->shipping }}</td>
                                                    <td id="shipping_val"></td>
                                                </tr>

                                                <tr id="orderdiscount_tr">
                                                    <td colspan="3"></td>
                                                    <td>Discount</td>
                                                    <td>{{ $order->order_discount ? $order->order_discount : 0.0 }}</td>
                                                    <td id="discount_val"></td>
                                                </tr>

                                                <tr id="grand_total_tr">
                                                    <td colspan="3"></td>
                                                    <td>Grand total</td>
                                                    <th><?php $total = $subtotal + $order->shipping - $order->order_discount;
                                                    echo number_format($order->grand_total, 2); ?></th>
                                                    <td id="grand_total_val"></td>
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
                                            <input type="number" name="order_discount" required
                                                value="{{ number_format((float) $order->order_discount, 2, '.', '') }}"
                                                id="order_discount" class="form-control" data-placeholder="Order Discount"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping</label>
                                        <div class="input-group">
                                            <input type="number" name="shipping" required
                                                value="{{ number_format((float) $order->shipping, 2, '.', '') }}"
                                                id="shipping" class="form-control" data-placeholder="Shipping" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment status</label>
                                        <select class="form-control payment_status" name="payment_status"
                                            id="payment_status">
                                            <option value=""> --select--</option>
                                            @foreach ($payment_status as $key => $item)
                                                <option {{ $key == $order->payment_status ? 'selected' : '' }}
                                                    value="{{ $key }}">
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-5 pt-3">
                                <button type="submit" class="btn btn-sm btn-info">Update</button>
                            </div>
                            </from>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop

@push('js')
    <script>
        $("body").on("change", ".status", function(params) {
            if (confirm('Are you sure you want to change status of this item?')) {
                val = $(this).val();
                item_id = $(this).closest("tr").attr("data-id");
                $.ajax({
                    type: "post",
                    async: false,
                    url: site_url + "admin/order/updateOrderStatus",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "item_id": item_id,
                        "status": val,
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data.status == true) {
                            window.location.reload(status.url);
                        }
                    }
                });
            }

        })
    </script>
@endpush
