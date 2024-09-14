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
                            <li class="breadcrumb-item"><a href="{{ url('admin/attribute') }}"> Attribute</a></li>

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

                    <div class="d-flex">
                        <!-- <div>
                    <h4 class="px-4">Attributes varition</h4>
                 </div> -->
                        @if ($varition > 0)
                            <div class="px-4 py-3">
                                <a href="{{ url('admin/attribute_varition_configer', $attributes->id) }}"> Go back </a>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-4">

                                <div class="shadow px-4 py-3 mx-4">
                                    <h4>
                                        @if ($varition > 0)
                                            Update
                                        @else
                                            Add new
                                        @endif
                                        {{ @$attributes->attributes_name }}
                                    </h4>

                                    <form action="{{ url('admin/add_varition') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="attributes_id" value="{{ @$attributes->id }}">

                                        @if ($varition > 0)
                                            <input type="hidden" name="attribute_varition_id"
                                                value="{{ @$edit_varition->id }}">
                                        @else
                                            <input type="hidden" name="attribute_varition_id" value="0">
                                        @endif

                                        <div class="form-group">
                                            <lebel> Name </lebel>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ @$edit_varition->sub_attributes_name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <lebel> Slug </lebel>
                                            <input type="text" name="slug" class="form-control"
                                                value="{{ @$edit_varition->slug }}">
                                        </div>
                                        <input type="submit" value="{{ $varition > 0 ? 'update' : 'add' }}"
                                            class="btn btn-primary">
                                    </form>
                                </div>



                            </div>


                            <div class="col-sm-8">

                                <table class="table table-striped">
                                    <tr>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Action</th>
                                    </tr>

                                    @foreach ($attributes_varition as $key => $attribute_vari)
                                        <tr>
                                            <td> {{ $attribute_vari->sub_attributes_name }} </td>
                                            <td> {{ $attribute_vari->slug }} </td>
                                            <td>
                                                <a
                                                    href="{{ url('admin/attribute_varition_configer', [$attributes->id, $attribute_vari->id]) }}">
                                                    Edit </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>

                            </div>
                        </div><!--end row-->


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
