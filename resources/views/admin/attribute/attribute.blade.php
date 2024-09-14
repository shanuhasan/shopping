@extends('admin.layouts.app')
@section('title', 'Attributes')
@section('attributes', 'active')
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
                <div class="card" style="padding: 0px;">
                    <h4 class="px-4">Attributes</h4>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <th>Terms</th>
                                <th>Action</th>
                            </tr>

                            @foreach ($attributes as $key => $attribute)
                                <tr>
                                    <td> {{ $attribute->attributes_name }} </td>
                                    <td>
                                        @foreach ($attribute->attribute_varition as $ak => $avalue)
                                            {{ $avalue->sub_attributes_name }}&nbsp;|
                                        @endforeach
                                    </td>
                                    <td>
                                        <!-- Edit | Delete |  -->
                                        <a href="{{ url('admin/attribute_varition_configer', $attribute->id) }}"> Configure
                                            terms </a>
                                    </td>
                                </tr>
                            @endforeach

                        </table>

                    </div>
                </div>
            </div>

        </section>



    </div>



@stop

@push('js')
    <script src="{{ asset('assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

    <script src="<?php echo asset('/assets/order.js'); ?>"></script>
@endpush
