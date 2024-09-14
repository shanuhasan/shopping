  @extends('admin.layout')
@section('content')
  <style type="text/css">
    .service_details table tr {
    border-bottom: solid 1px #d4d2d2;
}
.service_details table tr th {
    padding: 4px;
}
.service_details table tr td {
    padding: 4px;
}
  </style>
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
              <li class="breadcrumb-item"><a href="{{url('admin/service_order_list')}}">List</a></li>
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
      {{Form::open(array('url'=>'admin/insert_service_to_vendor','method'=>'post','enctype'=>'multipart/form-data'))}}
         
        <div class="card">
        <div class="row" style="padding:2%">
          <div class="col-lg-6 col-6">
           <label>Customer name</label> <span style="color:red">*</span>
            <input type="text" name="customer_name" value="<?=@$booking_details->customer_name?>" placeholder="Customer Name" class="form-control">
          </div>
          <div class="col-lg-6 col-6">
           <label>Mobile Number</label> <span style="color:red">*</span>
            <input type="text" name="phone" value="<?=@$booking_details->phone?>" placeholder="Mobile Number" class="form-control">
          </div>
          <div class="col-lg-6 col-6">
           <label>Email</label> <span style="color:red">*</span>
            <input type="text" name="email" value="<?=@$booking_details->email?>" placeholder="Email" class="form-control">
          </div>
          <div class="col-lg-6 col-6">
           <label>Organization Name</label> <span style="color:red">*</span>
            <input type="text" name="organization_name" value="<?=@$booking_details->organization_name?>" placeholder="Organization Name" class="form-control">
          </div>
          <div class="col-lg-6 col-6">
           <label>Organization Address</label> <span style="color:red">*</span>
            <input type="text" name="organization_address" value="<?=@$booking_details->organization_address?>" placeholder="Organization address" class="form-control">
          </div>
          <div class="col-lg-6 col-6">
           <label>Near By Address</label> <span style="color:red">*</span>
            <input type="text" name="near_by_address" value="<?=@$booking_details->near_by_address?>" placeholder="Near By Address" class="form-control">
          </div>
          
         <!--  <div class="col-lg-3  col-6">
            <label> Category</label> <span style="color:red">*</span>
           <select name="parent_category" class="form-control parent_category">
            <option value="">Select Category</option>
            <?php foreach ($category as $key => $value) {
            if($value->parent_id=='0' || $value->parent_id==''){ ?>
            	<option value="<?=$value->id?>"><?=$value->category_name?></option>
            <?php } }?>
            </select>
         </div>
         <div class="col-lg-3 col-6">
            <label>Subcategory</label> <span style="color:red">*</span>
           <select name="subcategory" class="form-control subcategory">
            <option value="">Select SubCategory</option>
           
            </select>
         </div>
         <div class="col-lg-3 col-6">
            <label>Select Vendor</label> <span style="color:red">*</span>
           <select name="vendor" class="form-control vendors">
            <option value="">Select Vendors</option>          
            </select>
         </div>
         <div class="col-lg-3 col-6">
            <label>Select Service</label> <span style="color:red">*</span>
           <select name="serives" class="form-control serives">
            <option value="">Select Services</option>
           
            </select>
           
         </div>
          -->
        <div class="col-lg-6 col-6">
           <h3>Vendor Details</h3>
            <div class="vendor_details">
              <b>Name</b>: <?=$booking_details->first_name?> <?=$booking_details->last_name?> <br>
              <b>Phone</b>: <?=$booking_details->number?> <br>
              <b>Address</b>: <?=$booking_details->address?> <br>
            </div>
        </div> 
        <div class="col-lg-6 col-6">
           <h3>Service List</h3>
            <div class="service_details">
              <table width="100%">
                <thead>
                   <tr>
                     <th>Service Name</th>
                     <th>Service Price</th>
                   </tr>
                </thead>
                
                <tbody class="service_list">
                  <?php
                  $total=array();
                   foreach ($booking_item_details as $key => $value) {
                    $total[]=$value->service_price;
                   ?>
                   
                 <tr>
                   <td><?=$value->service_name?></td>
                   <td><?=$value->service_price?></td>
                  
                 </tr>
               <?php } ?>
               <tr>
               <th>Total</th>
               <th><?=array_sum($total)?></th>
                </tr>
                </tbody>
              </table>
            </div>
        </div>
         <!--  <div class="col-lg-3 col-6">
            <label>Subcategory</label> <span style="color:red">*</span>
           <select name="subcategory" class="form-control subcategory">
            <option value="">Select SubCategory</option>
           
            </select>
         </div> -->
         <!--   <div class="col-lg-3 col-6">
           <label>Service Name</label> <span style="color:red">*</span>
            <input type="text" name="service_name" value="<?=@$_POST['service_name']?>" placeholder="Service Name" class="form-control"></div>
            <div class="col-lg-3 col-6">
           <label>Service Price</label> <span style="color:red">*</span>
            <input type="text" name="service_price" value="<?=@$_POST['service_price']?>" placeholder="Service Price" class="form-control"></div> -->
            <div class="col-lg-12">
          <h4>Change Status</h4>
        </div>
          <div class="col-lg-3">
            <select class="change_status_booking form-control" data-id="<?=$booking_details->id?>">
                      <option <?php if($booking_details->service_status=='pending'){echo "selected";}?> value="pending">Pending</option>
                      <option <?php if($booking_details->service_status=='accept'){echo "selected";}?> value="accept">Accept</option>
                      <option <?php if($booking_details->service_status=='reject'){echo "selected";}?> value="reject">Reject</option>
                      <option <?php if($booking_details->service_status=='started'){echo "selected";}?> value="started">Started</option>
                      <option <?php if($booking_details->service_status=='reached'){echo "selected";}?> value="reached">Reached</option>
                      <option <?php if($booking_details->service_status=='complete'){echo "selected";}?> value="complete">Complete</option>
                    </select>
          </div>
          
         <!--  <div class="col-lg-12" style="margin-top: 2%">
            <input type="submit" value="submit" class="btn btn-primary" style="float: right;">
          </div> -->
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