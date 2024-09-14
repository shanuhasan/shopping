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
              <li class="breadcrumb-item"><a href="{{url('admin/request_money_list')}}">Request Money List</a></li>
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
      {{Form::open(array('url'=>'admin/update_request_money','method'=>'post','enctype'=>'multipart/form-data'))}}
         <input type="hidden" value="<?=$edit_data->id?>" name="update_id">
        <div class="card">
        <div class="row" style="padding:2%">
          <div class="col-lg-4 col-6">
            <label>Select Vendor</label> <span style="color:red">*</span>
           <select name="vendor" required class="form-control">
            <option value="">Select Vendor</option>
            <?php foreach ($vendors as $key => $value) { ?>
              <option <?php if($edit_data->vendor_id==$value->id){echo "selected";} ?> value="<?=$value->id?>"><?=$value->first_name?> <?=$value->last_name?></option>
            <?php }?>
            </select>
         </div>
          <div class="col-lg-4 col-6">
            <label>Money</label> <span style="color:red">*</span>
          <input type="number" value="<?=$edit_data->money?>" required name="money" class="form-control">
         </div> 
         <div class="col-lg-4 col-6">
            <label>Select Status</label> <span style="color:red">*</span>
           <select name="status" class="form-control">
            <option <?php if($edit_data->request_status=='pending'){echo "selected";} ?> value="pending">Pending</option>
            <option <?php if($edit_data->request_status=='approved'){echo "selected";} ?> value="approved">Approved</option>
            </select>
         </div> 
         <div class="col-lg-4 col-6">
            <label>Select Payment Status</label> <span style="color:red">*</span>
           <select name="payment_status" class="form-control">
            <option <?php if($edit_data->payment_status=='pending'){echo "selected";} ?> value="pending">Pending</option>
            <option <?php if($edit_data->payment_status=='approved'){echo "selected";} ?> value="approved">Approved</option>
            </select>
         </div>
        <div class="col-lg-4 col-6">
            <label>Payment Date</label> <span style="color:red">*</span>
          <input type="date" value="<?=$edit_data->payment_date?>" name="payment_date" class="form-control">
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