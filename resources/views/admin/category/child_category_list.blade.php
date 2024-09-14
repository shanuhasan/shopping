@extends('admin.layouts.app')
@section('title', 'Child Category')
@section('category_open', 'menu-open')
@section('childcategory_active', 'active')
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.childcategory.create') }}">Add Child
                                    Category</a></li>
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
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th> Category Name</th>
                                            <th>Sub Category</th>
                                            <th>Main Category</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($category as $key => $value) { 
                    if($value->parent_id>0){?>

                                        <tr>
                                            <td><?= $value->category_name ?></td>
                                            <td><?= $value->subcategory_name ?></td>
                                            <td><?= $value->main_category_name ?></td>
                                            <td>
                                                <?php if($value->media_id){ ?>
                                                <img src="<?php echo asset('/'); ?>uploads/category/thumb/<?= $value->media_id ?>"
                                                    width="60px">
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if($value->status=='1'){ ?>
                                                <a href="{{ route('admin.childcategory.active', $value->id) }}"
                                                    class="btn-success btn btn-sm">Active</a>
                                                <?php }else{ ?>
                                                <a href="{{ route('admin.childcategory.deactive', $value->id) }}"
                                                    class="btn-danger btn btn-sm">Deactive</a>
                                                <?php } ?>
                                            </td>
                                            <td><a href="{{ route('admin.childcategory.edit', $value->id) }}"
                                                    class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o"
                                                        aria-hidden="true"></i></a>
                                                <a onclick="return confirm('Are you sure delete this record ?..')"
                                                    href="{{ route('admin.childcategory.delete', $value->id) }}"
                                                    class="btn btn-danger btn-sm"><i class="fa fa-trash"
                                                        aria-hidden="true"></i>
                                            </td>
                                        </tr>
                                        <?php } }?>
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
