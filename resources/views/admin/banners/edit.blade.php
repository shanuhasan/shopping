@extends('admin.layouts.app')
@section('title', 'Banners')
@section('setting_open', 'menu-open')
@section('banner_active', 'active')

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

                            <li class="breadcrumb-item"><a href="{{ url('admin/banners') }}">Banners</a></li>

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

                <form action="{{ route('admin.banner.update') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $banner->id }}">

                    <div class="card">



                        <div class="row">

                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Heading</label> <span style="color:red">*</span>

                                    <input type="text" name="heading" value="{{ $banner->heading }}"
                                        placeholder="Heading" class="form-control">

                                </div>

                            </div>

                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Sub heading</label> <span style="color:red">*</span>

                                    <input type="text" name="sub_heading" value="{{ $banner->sub_heading }}"
                                        placeholder="Sub heading" class="form-control">

                                </div>

                            </div>
                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Type</label>
                                    <select class="form-control" name="type">
                                        <option value="">Select Type</option>
                                        <option <?php if ($banner->type == 'Offer Banner 1') {
                                            echo 'selected';
                                        } ?>>Offer Banner 1</option>
                                        <option <?php if ($banner->type == 'Offer Banner 2') {
                                            echo 'selected';
                                        } ?>>Offer Banner 2</option>
                                        <option <?php if ($banner->type == 'Offer Banner 3') {
                                            echo 'selected';
                                        } ?>>Offer Banner 3</option>
                                        <option <?php if ($banner->type == 'Default') {
                                            echo 'selected';
                                        } ?>>Default</option>
                                    </select>


                                </div>

                            </div>
                            <div class="col-lg-4 col-6">

                                <div class="form-group">

                                    <label>Category List</label>
                                    <select class="form-control" name="category_id">
                                        <option value="">Select Category</option>
                                        <?php foreach($categorylist as $value){ ?>
                                        <option <?php if ($value->id == $banner->category_id) {
                                            echo 'selected';
                                        } ?> value="<?= $value->id ?>"><?= $value->category_name ?>
                                        </option>
                                        <?php }?>
                                    </select>


                                </div>

                            </div>



                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>Image</label>

                                    <input type="file" name="image" id="view_img" class="form-control">

                                    <div class="viewimg" style="<?php if (empty($banner->image)) {
                                        echo 'display: none';
                                    } ?>"><img id="v_img"
                                            src="<?php echo asset('/'); ?>uploads/banner/<?= $banner->image ?>" alt="your image"
                                            style="width: 22%; padding: 1%;"></div>

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
