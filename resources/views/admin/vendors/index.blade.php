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
              <div class="row">
                <div class="col-md-12 text-right">
                  <a href="{{url('admin/vendors/create')}}" class="btn btn-sm btn-info">Add vendor</a>
                </div>
              </div>
              <br>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Profile image</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  
                  <?php foreach ($vendors as $key => $value) { ?>
                   
                <tr>
                  <td>VND00<?=$value->id?></td>
                  <td><?=$value->first_name." ".$value->last_name?></td>
                  
                  <td><?=$value->email?></td>
                  <td><?=$value->phone?></td>
                  <td>
                    <?php if($value->profile_image){ ?>
                      <img src="<?php echo asset('/');?>uploads/users/<?=$value->profile_image?>" width="60px">
                    <?php } ?>
                </td>
                  <td>
                    <?php if($value->status=='1'){ ?>
                      <a href="{{url('/activeAccount',['users', $value->id])}}" class="btn-success btn btn-sm">Active</a>
                    <?php }else{ ?>
                       <a href="{{url('/activeAccount',['users', $value->id])}}" class="btn-danger btn btn-sm">Deactive</a>
                    <?php } ?>
                  </td>
                  <td> 
                    <div class="btn-group text-left">
                      <p class="dot-container" aria-expanded="false" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></p>
                      <ul class="dropdown-menu animated flipInY pull-right" role="menu">
                          <!--<li><a href="{{url('admin/vendors/view')}}/<?=$value->id?>" class=""><i class="fa fa-eye"></i> Order Details</a></li>-->
                          
                          <!--<li><a href="{{url('admin/vendors/edit')}}/<?=$value->id?>"><i class="fa fa-edit"></i> Edit vendor</a></li>-->
                          <li><a href="{{url('vendor/profile')}}/<?=$value->id?>"><i class="fa fa-eye"></i> View vendor</a></li> 
                          <li><a onclick="return confirm('Are you sure delete this record ?..')" href="{{url('admin/order/delete')}}/<?=$value->id?>"><i class="fa fa-trash"></i> Delete Order</a></li>
                      </ul>
                      
                      <a href="{{url('/admin/vendor/product')}}/<?=$value->id?>" class="btn btn-info"> Product </a>
                  </div>
                  </td>
                </tr>
                <?php } ?>
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