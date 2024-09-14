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
        <div class="row">
          <div class="col-md-12">
            <div class="card">
            
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Product Name</th>
                  <th>Action</th>
                </tr>
                </thead>
                
                <tbody>
                @foreach($review as $key => $rev)    
                    <tr>
                        <td> {{$key + 1 }} </td>
                        <td> {{ $rev->service_name}}</td>
                        <?php
                        $slugUser = Sentinel::getUser()->roles()->first()->slug;  
                        if($slugUser=='admin' || $slugUser="super_admin"){
                        ?>
                        <td>
                            <a href="{{url('admin/review',$rev->id)}}" class="btn btn-primary"> Reviews </a>
                        </td>
                        <?php }else{?>
                        <td>
                            <a href="{{url('vendor/review',$rev->id)}}" class="btn btn-primary"> Reviews </a>
                        </td>
                        <?php }?>
                    </tr>
                  @endforeach  
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