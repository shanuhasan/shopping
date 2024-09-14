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
              <li class="breadcrumb-item"><a href="{{url('admin/add_service_provide')}}">Add</a></li>
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
                  <th>Booking Id</th>
                  <th>Customer Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Total Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  
                  <?php foreach ($service_order_list as $key => $value) { ?>
                   
                <tr>
                  <td>OGB000<?=$value->id?></td>
                  <td><?=$value->customer_name?></td>
                  <td>
                   <?=$value->email?>
                  </td>
                  <td> <?=$value->phone?></td>
                  <td> <?=$value->total_amount?></td>
                  <td>
                    <?php if($value->service_status){ ?>
                      <a class="btn btn-sm"><?=$value->service_status?></a>
                    <?php } ?>
                    <select class="change_status_booking" data-id="<?=$value->id?>">
                      <option <?php if($value->service_status=='pending'){echo "selected";}?> value="pending">Pending</option>
                      <option <?php if($value->service_status=='accept'){echo "selected";}?> value="accept">Accept</option>
                      <option <?php if($value->service_status=='reject'){echo "selected";}?> value="reject">Reject</option>
                      <option <?php if($value->service_status=='started'){echo "selected";}?> value="started">Started</option>
                      <option <?php if($value->service_status=='reached'){echo "selected";}?> value="reached">Reached</option>
                      <option <?php if($value->service_status=='complete'){echo "selected";}?> value="complete">Complete</option>
                    </select>
                  </td>
                  <td> <a href="{{url('admin/view_booking')}}/<?=$value->id?>" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="{{url('admin/edit_booking')}}/<?=$value->id?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  <a onclick="return confirm('Are you sure delete this record ?..')" href="{{url('admin/delete_booking')}}/<?=$value->id?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
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