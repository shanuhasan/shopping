@extends('admin.layouts.app')
@section('title', 'Coupons')
@section('coupon', 'active')

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

                                <div class="row">

                                    <div class="col-md-12 text-right">

                                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-info">Add
                                            Coupon</a>

                                    </div>

                                </div>

                                <br>

                                <table id="example1" class="table table-bordered table-striped">

                                    <thead>

                                        <tr>

                                            <th>Title</th>
                                            <th>Coupon Code</th>
                                            <th>Value</th>
                                            <th>Per User</th>
                                            <th>Expiry Date</th>
                                            <th>Type</th>
                                            <th>Status</th>



                                            <th>Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @forelse ($list as $item)
                                            <tr>

                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->coupon_code }}</td>

                                                <td>{{ $item->value }}</td>
                                                <td>{{ $item->per_user }}</td>
                                                <td>{{ $item->expiry_date }}</td>

                                                <td>

                                                    <?= $item->type ?>

                                                </td>

                                                <td>

                                                    <a href="{{ route('admin.coupons.status', $item->id) }}"
                                                        class="btn-{!! $item->status == 1 ? 'success' : 'danger' !!} btn btn-sm">{!! $item->status == 1 ? 'Active' : 'Deactive' !!}</a>

                                                </td>



                                                <td>

                                                    <a href="{{ route('admin.coupons.edit', $item->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o"
                                                            aria-hidden="true"></i></a> <a
                                                        onclick="return confirm('Are you sure delete this record ?..')"
                                                        href="{{ route('admin.coupons.destroy', $item->id) }}"
                                                        class="btn btn-danger btn-sm"><i class="fa fa-trash"
                                                            aria-hidden="true"></i>

                                                </td>

                                            </tr>

                                        @empty
                                        @endforelse



                                    </tbody>

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

@stop
