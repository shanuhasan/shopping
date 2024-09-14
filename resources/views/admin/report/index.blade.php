@extends('admin.layouts.app')
@section('title', 'Report')
@section('report', 'active')
@section('content')

    <div class="content-wrapper">
        <!-- Main content -->

        <section class="content">
            <div class="container-fluid">
                <h3 class="m-2 text-dark">Report Filter</h3>
                <hr>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label> Filter accouding to date</label><br>
                            <select class="form-control">
                                <option> daily </option>
                                <option> weakly </option>
                                <option> monthly </option>
                                <option> particular date </option>
                                <option> particular date range </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label> Filter by order status</label><br>
                            <select name="itme_status" class="form-control item_status_change">
                                <option value="pending">Pending </option>
                                <option value="processing">Processing</option>
                                <option value="ordered">Ordered</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="returncomplete"> Return </option>
                            </select>
                        </div>
                    </div>

                </div><!--close row-->

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">

                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>

                                </table>

                            </div>

                            <!-- /.card-body -->

                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


    </div><!-- /.content-wrapper -->

@stop
