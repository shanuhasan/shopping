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
                        <li class="breadcrumb-item"><a href="{{url('admin/settings/princode/create')}}">Create Pincode</a></li>
                        <li class="breadcrumb-item active"><?=@$page_title?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <section class="content">
    <div class="container-fluid">            
        <div class="card">
                
                <table id="example2" class="table table-bordered table-striped" width="100%">
                <thead>
                    <tr>
                        <th>Sr no.</th>
                        <th>Pincode</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if(!empty($pincode_item))
                    @foreach($pincode_item as $key => $item)
                    <tr>
                        <td> {{ $key+1 }} </td>
                        <td> {{ $item->pincode }} </td>
                        <td> 
                            @if($item->status > 0)
                                <a href="{{url('admin/settings/princode/active',$item->id)}}" class="btn btn-sm btn-{{ $item->status==1 ? 'success':''}}"> Active </a> 
                            @else
                                <a href="{{url('admin/settings/princode/active',$item->id)}}" class="btn btn-sm btn-warning"> Inactive </a> 
                            @endif
                            </td>
                        <td>
                            <a href="{{url('admin/settings/princode/edit', $item->id)}}" class="btn btn-sm btn-primary"> Edit </a>  
                            <a href="{{url('admin/settings/princode/delete', $item->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? delete this item.');"> Delete </a>  
                        </td>
                    </tr>
                    @endforeach
                    @else
                        <h2> No Record Found.</h2>
                    @endif
                </tbody>               
              </table>

        </div>
    </div>        
    </section>
    
</div>


@stop

@push('js')
<script src="{{asset('assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="<?php echo asset('/assets/settings.js');?>"></script>
@endpush