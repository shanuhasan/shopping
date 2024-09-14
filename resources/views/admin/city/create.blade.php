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
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        @if($city_id > 0)
                        <li class="breadcrumb-item"><a href="{{url('admin/settings/city/create')}}">City Create</a></li>
                        @endif 
                        <li class="breadcrumb-item"><a href="{{url('admin/settings/city')}}">City List</a></li>
                        <li class="breadcrumb-item active"><?=@$page_title?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <section class="content">
        <div class="container-fluid">            
            <div class="card">
                @if($city_id > 0)
                    
                    
                @endif 
                
                <!--{{Form::open(array('url'=>'admin/settings/city/store','method'=>'post','enctype'=>'multipart/form-data'))}}-->
                
                <form action="{{url( $city_id > 0 ? 'admin/settings/city/update' : 'admin/settings/city/store' )}}" method="POST" enctype="multipart/form-data">
                    @csrf
                
                @if($city_id > 0)
                 <input type="hidden" name="city_id" value="{{$city_id}}">
                @endif 
                
                <div class="row">
                    
                    <div class="col-lg-4 col-6 offset-4">
                        <div class="form-group">
                          <label> City </label> <span style="color:red">*</span> 
                            <input type="text" name="city" required placeholder="Type here.." class="form-control" value="{{ @$edit_item->city }}">
                             @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                             @endforeach
                        </div>
                        
                        <div class="text-right">
                        <button type="submit" class="btn btn-sm btn-info"> {{ $city_id > 0 ? 'Update City' : 'Create City'}}  </button>
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