@extends('admin.layouts.app')
@section('title', 'Edit Coupon')
@section('coupon', 'active')


@section('content')

    <style>
        .card {

            padding: 20px;

        }



        .timig .select2-container.form-control.timing {

            width: 46%;

            float: left;

            margin-right: 2%;

        }
    </style>





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

                            <li class="breadcrumb-item"><a href="">Home</a></li>

                            <li class="breadcrumb-item"><a href="{{ route('admin.coupons') }}">Coupons</a></li>

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

                <!-- Small boxes (Stat box) -->

                <form action="{{ route('admin.coupons.update') }}" method="post" enctype="multipart/form-data">
                    @csrf


                    <div class="card">

                        <div class="row">

                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Coupon Code</label> <span style="color:red">*</span>

                                    <input type="text" name="coupon_code" value="<?= $Coupon->coupon_code ?>"
                                        placeholder="Coupon Code" class="form-control">
                                    <input type="hidden" name="id" value="<?= $Coupon->id ?>"
                                        placeholder="Coupon Code" class="form-control">

                                </div>

                            </div>
                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Title</label> <span style="color:red">*</span>

                                    <input type="text" name="title" value="<?= $Coupon->title ?>"
                                        placeholder="Coupon Title" class="form-control">


                                </div>

                            </div>

                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Value</label> <span style="color:red">*</span>

                                    <input type="text" name="value" value="<?= $Coupon->value ?>" placeholder="Value"
                                        class="form-control">

                                </div>

                            </div>
                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Type</label> <span style="color:red">*</span>

                                    <select class="form-control" name="type">
                                        <option <?php if ($Coupon->type == 'Persentage') {
                                            echo 'selected';
                                        } ?>>Persentage</option>
                                        <option <?php if ($Coupon->type == 'Flat') {
                                            echo 'selected';
                                        } ?>>Flat</option>
                                    </select>

                                </div>

                            </div>

                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Per user</label> <span style="color:red">*</span>

                                    <input type="number" value="<?= $Coupon->per_user ?>" name="per_user"
                                        class="form-control">

                                </div>

                            </div>
                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Expiry Date</label> <span style="color:red">*</span>

                                    <input type="date" value="<?= $Coupon->expiry_date ?>" name="expiry_date"
                                        class="form-control">

                                </div>

                            </div>
                            <div class="col-lg-12 col-6">

                                <div class="form-group">

                                    <label>Short Description</label>

                                    <input type="text" value="<?= $Coupon->short_descriptoin ?>" name="short_descriptoin"
                                        class="form-control">

                                </div>

                            </div>
                            <div class="col-lg-12 col-6">

                                <div class="form-group">

                                    <label>Long Description</label>

                                    <textarea class="form-control" name="long_description"><?= $Coupon->description ?></textarea>

                                </div>

                            </div>
                        </div>



                        <div class="row">

                            <div class="col-md-12 text-right">

                                <button type="submit" class="btn btn-sm btn-info">Save</button>

                            </div>

                        </div>

                </form>

            </div><!-- /.container-fluid -->

        </section>

        <!-- /.content -->

    </div>

    <!-- /.content-wrapper -->



@stop
