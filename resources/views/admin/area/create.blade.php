@extends('admin.layout')

@section('content')

<style>

    .card {

        padding: 20px;

    }



   

</style>

<div class="content-wrapper">

    

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0 text-dark"><?=@$page_title?></h1>

                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="{{url('admin/settings/area/')}}">Home</a></li>

                        @if($area_id > 0)

                        <li class="breadcrumb-item"><a href="{{url('admin/settings/area/create')}}">Create Area</a></li>

                        @endif

                        <li class="breadcrumb-item"><a href="{{url('admin/settings/area/')}}">List Area</a></li>

                        <li class="breadcrumb-item active"><?=@$page_title?></li>

                    </ol>

                </div>

            </div>

        </div>

    </div>

    

    <section class="content">

        <div class="container-fluid">            

            <div class="card">

                

                <!--{{Form::open(array('url'=>'admin/settings/princode/store','method'=>'post','enctype'=>'multipart/form-data','id'=>'settings_form'))}}-->

                

                <form action="{{url( $area_id > 0 ? 'admin/settings/area/update' : 'admin/settings/area/store' )}}" method="POST" enctype="multipart/form-data">

                    @csrf

                

                @if($area_id > 0)

                 <input type="hidden" name="area_id" value="{{$area_id}}">

                @endif 

                

                <div class="row">

                    

                    <div class="col-lg-6 col-6">

                        <div class="form-group">

                          <label> Cities </label> <span style="color:red">*</span>

                            <select name="city" class="form-control" required>

                                <option value=""> --Select City--</option>

                                @foreach($city as $ct)

                                    <option value="{{ $ct->id }}" {{ $ct->id == @$edit_item->city_id ? 'selected':'' }}> {{ $ct->city }}</option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    

                    <div class="col-lg-6 col-6">

                        <div class="form-group">

                          <label> Area</label> <span style="color:red">*</span>

                            <input type="text" name="area" required placeholder="Type here.." class="form-control" value="{{ @$edit_item->area }}">

                             @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                             @endforeach

                        </div>

                        

                        <div class="text-right">

                            <button type="submit" class="btn btn-sm btn-info"> {{ $area_id > 0 ? 'Update Area' : 'Create Area'}}  </button>

                        </div>

                        

                      </div>



                

                {{Form::close()}}

            </div>

        </div>

    </section>

    

</div>





@stop



@push('js')

<script src="{{asset('assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

<script src="<?php echo asset('/assets/settings.js');?>"></script>

@endpush