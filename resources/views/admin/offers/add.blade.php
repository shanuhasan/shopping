@extends('admin.layouts.app')
@section('title', 'Add Offer')
@section('offer', 'active')

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

                            <li class="breadcrumb-item"><a href="{{ url('admin/offers') }}">Offers</a></li>

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

                <form action="{{ route('admin.offers.store') }}" method="post" enctype="multipart/form-data">
                    @csrf


                    <div class="card">

                        <div class="row">

                            <div class="col-lg-3 col-6">

                                <div class="form-group">

                                    <label>Title</label> <span style="color:red">*</span>

                                    <input type="text" name="title" value="<?= @$_POST['title'] ?>" placeholder="title"
                                        class="form-control">

                                </div>

                            </div>
                            <div class="col-lg-3 col-6">

                                <div class="form-group">

                                    <label>Value</label> <span style="color:red">*</span>

                                    <input type="text" name="value" value="<?= @$_POST['value'] ?>" placeholder="Value"
                                        class="form-control">

                                </div>

                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="form-group">

                                    <label>Select Type</label>

                                    <select class="form-control" name="type">
                                        <option value="default">Default</option>
                                        <option value="firsttime">Only for first time</option>


                                    </select>


                                </div>

                            </div>
                            <div class="col-lg-3 col-6">

                                <div class="form-group">

                                    <label>Select Product</label> <span style="color:red">*</span>

                                    <select class="form-control" name="product">
                                        <option value="">Select Product</option>

                                        <?php foreach($list as $value){ ?>
                                        <option value="<?= $value->id ?>"><?= $value->service_name ?></option>
                                        <?php } ?>
                                    </select>


                                </div>

                            </div>
                            <div class="col-lg-12 col-6">

                                <div class="form-group">

                                    <label>Description
                                    </label>

                                    <textarea class="form-control" name="description"><?= @$_POST['description'] ?></textarea>

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
