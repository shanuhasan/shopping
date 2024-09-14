@extends('admin.layout')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?=@$page_title?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-value"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-value"><a href="{{url('/vendor/account')}}">Account</a></li>
              <li class="breadcrumb-value active"><?=@$page_title?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
            <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
             
            <form action="{{url('vendor/add_stock')}}" method="post">
                @csrf
                
                <div class="col-sm-4">
                <div class="form-group">
                    <lebel> Category </lebel>
                    <select name="category" required class="form-control">
                        <option value=""> select category </option>
                    </select>
                </div>
                </div>
                
                <div class="col-sm-4">
                <div class="form-group">
                    <lebel> Product </lebel>
                    <select name="product" required class="form-control">
                        <option value=""> select product </option>
                    </select>
                </div>
                </div>
                
                <div class="col-sm-4">
                <div class="form-group">
                    <lebel> Price </lebel>
                    <input type="text" name="price" class="form-control">
                </div>
                </div>
                
                <div class="col-sm-4">
                <div class="form-group">
                    <lebel> Stock </lebel>
                    <input type="text" name="price" class="form-control">
                </div>
                </div>
                
                 <div class="col-sm-4">
                <div class="form-group">
                    <input type="submit" class="form-control btn btn-warning" >
                </div>
                </div>
                
                
            </form>
              
                        
            </div>
            <!-- /.card-body -->
      </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @stop
  

 

