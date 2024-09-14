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
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{url('admin/vendors')}}">Add Vendor</a></li>
              <li class="breadcrumb-item active"><?=@$page_title?></li>
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
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Vendor Name</th>
                  <th>Category Name</th>
                  <th>Sub Category Name</th>
                  <th>Product Name</th>
                 <!-- <th>Mrp. Price</th>
                  <th>Sale Price</th>
                  
                
                  <th>Unit</th>
                  <th>Unit value</th>-->
                  <th>Image</th>
                  <th>Status</th>
                  <!--<th>Stock</th>-->
                  <th style="width:140px !important">Action</th>
                </tr>
                </thead>
                <tbody>
                  
                  <?php foreach ($product_list as $key => $value) { ?>
                   
                <tr>
                  <td><?=$value->first_name?> <?=$value->last_name?> </td>
                  <td><?=$value->category_name?> </td>
                  <td><?=$value->subcategory_name?> </td>
                  <td><?=$value->service_name?> </td>
                  
                  <td>
                    <?php if($value->image){ ?>
                      <img src="<?php echo asset('/');?>uploads/service/<?=$value->image?>" width="60px">
                    <?php } ?>
                  </td>
                 
                  <td>
                    <?php if($value->status=='1'){ ?>
                      <a href="{{url('/activeAccount',['tbl_services', $value->id])}}" class="btn-success btn btn-sm">Active</a>
                    <?php }else{ ?>
                       <a href="{{url('/activeAccount',['tbl_services', $value->id])}}" class="btn-danger btn btn-sm">Deactive</a>
                    <?php } ?>
                  </td>
                  
                  <!--<td> {{ $value->stock }}</td>-->
                  <td><a href="{{url('admin/view_product')}}/<?=$value->id?>" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="{{url('admin/edit_product')}}/<?=$value->id?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a onclick="return confirm('Are you sure delete this record ?..')" href="{{url('admin/product_delete')}}/<?=$value->id?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></td>
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