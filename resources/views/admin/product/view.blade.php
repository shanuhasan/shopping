@extends('admin.layouts.app')
@section('title', 'View Product')
@section('product', 'active')
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
                            <li class="breadcrumb-item"><a href="">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.product') }}">Product List</a></li>
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
                <form action="{{ route('admin.product.update') }}" method="post" enctype="multipart/form-data"
                    id="form_data">
                    @csrf
                    <input type="hidden" name="update_id" value="<?= $edit_data->id ?>">
                    <div class="card">
                        <div class="row" style="padding:2%">
                            <div class="col-lg-3 col-6">
                                <label>Category</label>
                                <select name="parent_category" disabled class="form-control parent_category">
                                    <option value="">Select Category</option>
                                    <?php foreach ($category as $key => $value) {
                                    if($value->parent_id=='0' || $value->parent_id==''){ ?>
                                    <option <?php if ($edit_data->parent_category == $value->id) {
                                        echo 'selected';
                                    } ?> value="<?= $value->id ?>"><?= $value->category_name ?>
                                    </option>
                                    <?php } }?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-6">
                                <label>Subcategory</label>
                                <select name="subcategory" disabled class="form-control subcategory">
                                    <option value="">Select SubCategory</option>
                                    <?php foreach ($category as $key => $value) {
                                      if($value->parent_id){ ?>
                                    <option <?php if ($edit_data->subcategory == $value->id) {
                                        echo 'selected';
                                    } ?> value="<?= $value->id ?>"><?= $value->category_name ?>
                                    </option>
                                    <?php } }?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-6">
                                <label>Product Name</label>
                                <input type="text" disabled name="service_name" value="<?= @$edit_data->service_name ?>"
                                    placeholder="Product Name" class="form-control">
                            </div>
                            <div class="col-lg-3 col-6">
                                <label>Product Price</label>
                                <input type="text" disabled name="service_price"
                                    value="<?= @$edit_data->service_price ?>" placeholder="Product Price"
                                    class="form-control">
                            </div>

                            <div class="col-lg-11" style="padding:10px; border:1px solid; margin:15px;">
                                <label>Description</label>
                                {!! $edit_data->description !!}
                            </div>
                            <div class="col-lg-12">
                                <!--<label>Image</label>-->
                                <!--<input type="file" disabled name="image" id="view_img" class="form-control">-->
                                <?php if($edit_data->image){ ?>
                                <img src="<?php echo asset('/'); ?>uploads/service/<?= $edit_data->image ?>" width="500px">
                                <?php } ?>
                                <div class="viewimg" style="display: none"><img id="v_img" src="#"
                                        alt="your image" style="width: 22%; padding: 1%;"></div>
                            </div>

                            <!-- ./col -->
                        </div>
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@stop
