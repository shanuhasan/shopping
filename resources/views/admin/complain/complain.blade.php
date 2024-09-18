@extends('admin.layouts.app')
@section('title', 'Compalin')
@section('compalin', 'active')
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
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($complains as $key => $value) { ?>

                                        <tr>
                                            <td><?= $value->subject ?></td>
                                            <td>
                                                <?php if($value->complain_image){ ?>
                                                <img src="<?php echo asset('/'); ?>uploads/complain/<?= $value->complain_image ?>"
                                                    width="60px">
                                                <?php } ?>
                                            </td>

                                            <!--<td><a href="{{ url('admin/complaint_edit') }}/<?= $value->id ?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
                                            <!--<a onclick="return confirm('Are you sure delete this record ?..')" href="{{ url('admin/complaint_delete') }}/<?= $value->id ?>" class="btn btn-danger btn-sm">-->
                                            <!--    <i class="fa fa-trash" aria-hidden="true"></i></a></td>-->
                                        </tr>
                                        <?php  }?>
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
