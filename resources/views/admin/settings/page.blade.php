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
                        <li class="breadcrumb-item active"><?=@$page_title?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <section class="content">
        <div class="container-fluid">  
            {{Form::open(array('url'=>'admin/update-page','method'=>'post','enctype'=>'multipart/form-data'))}} 
        <input type="hidden" name="id" value="{{$page->id}}">         
            <div class="card">
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <textarea class="form-control textarea" name="description">{!!$page->description!!}</textarea>
                      </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-sm btn-info">Save</button>
                    </div>
                  </div>
            </div>
             {{Form::close()}}
        </div>
    </section>
</div>
@endsection
