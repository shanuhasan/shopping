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
              <li class="breadcrumb-item"><a href="">Home</a></li>
              <li class="breadcrumb-item"><a href="{{url('admin/subadmin_list')}}">SubAdmin list</a></li>
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
        <!-- Small boxes (Stat box) -->
      {{Form::open(array('url'=>'admin/update_subadmin','method'=>'post','enctype'=>'multipart/form-data'))}}
         
          <div class="card">
        <div class="row" style="padding:2%">
          
           <div class="col-lg-6 col-6">
             <input type="hidden" name="update_id" value="<?=@$edit_data->id?>">
           <label>First Name</label> <span style="color:red">*</span>
            <input type="text" name="first_name" value="<?=@$edit_data->first_name?>" placeholder="Vendor  Name" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Last Name</label>
            <input type="text" name="last_name" value="<?=@$edit_data->last_name?>" placeholder="Vendor  Name" class="form-control"></div>
        
          <div class="col-lg-6 col-6">
           <label>Phone</label> <span style="color:red">*</span>
            <input type="number" name="phone" value="<?=@$edit_data->phone?>" placeholder="Phone" class="form-control"></div>
           
          <div class="col-lg-12 col-6">
           <label>Address</label>
            <input type="text" name="address" value="<?=@$edit_data->address?>" placeholder="Address" class="form-control"></div>
          
        <div class="col-lg-12 col-6">
           <label>Profile Pic</label>
            <input type="file" name="profile" class="form-control">
             <?php if($edit_data->profile){ ?>
                      <img src="<?php echo asset('/');?>uploads/profile/<?=$edit_data->profile?>" width="60px">
                    <?php } ?>
          </div>
          
       
          
          <div class="col-lg-12" style="margin-top: 2%">
            <input type="submit" value="submit" class="btn btn-primary" style="float: right;">
          </div>
          <!-- ./col -->
        </div>
        </div>
       </form>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
 @stop