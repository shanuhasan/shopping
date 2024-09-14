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
              <li class="breadcrumb-item"><a href="{{url('admin/add_subadmin')}}">Add Subadmin</a></li>
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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>phone</th>
                  <th>Profile</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  
                  <?php foreach ($subadmin_list as $key => $value) { ?>
                   
                <tr>
                  <td>OG000<?=$value->id?></td>
                  <td><?=$value->first_name?> <?=$value->last_name?></td>
                  <td><?=$value->email?></td>
                  <td><?=$value->phone?></td>
                  <td>
                    <?php if($value->profile){ ?>
                      <img src="<?php echo asset('/');?>uploads/profile/<?=$value->profile?>" width="60px">
                    <?php } ?>
                  </td>
                 
                  <td>
                    <?php if($value->status=='1'){ ?>
                      <a href="{{url('admin/subadmin_active')}}/<?=$value->id?>" class="btn-success btn btn-sm">Active</a>
                    <?php }else{ ?>
                       <a href="{{url('admin/subadmin_deactive')}}/<?=$value->id?>" class="btn-danger btn btn-sm">Deactive</a>
                    <?php } ?>
                  </td>
                  <td><a href="{{url('admin/edit_subadmin')}}/<?=$value->id?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a onclick="return confirm('Are you sure delete this record ?..')" href="{{url('admin/subadmin_delete')}}/<?=$value->id?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></td>
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