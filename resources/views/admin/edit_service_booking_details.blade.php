  @extends('admin.layout')
@section('content')
 <style type="text/css">
    .service_details table tr {
    border-bottom: solid 1px #d4d2d2;
}
ul.view_address {
    padding: 0;
    position: absolute;
    background: white;
    z-index: 999;
    border: solid 1px #cccccc;
    width: 93%;
    border-radius: 3px;
}
ul.view_address li {
    list-style: none;
    padding: 2%;
}
tbody.vendors tr {
    border-bottom: solid 1px grey;
}
tbody.vendors tr td {
    padding: 2% 1% 2% 1%;
}
.service_details table tr th {
    padding: 4px;
}
.service_details table tr td {
    padding: 4px;
}
ul.vendors li {
    list-style: none;
    padding: 9px 0px 4px 0px;
    border-bottom: solid 1px #d2cbcb;
}
.vendors{padding:2px;
    overflow-y: scroll;
    max-height: 179px;}
.services .add_service{
    text-align: center !important;
    padding: 1%;
    border: solid 1px #cecdcd;
}.services .add_service span{
    font-size: 10px;
}.services .add_service h6{
    font-size: 12px;
    margin-top: 2px;
    margin-bottom: 0px;
}
.services .add_service img{
    width: 100%;
    padding:2%;
}
.add_service{cursor: pointer;}
.services{padding:2px;overflow-y: scroll;
    max-height: 200px;}
  </style>>
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
      {{Form::open(array('url'=>'admin/update_service_to_vendor','method'=>'post','enctype'=>'multipart/form-data'))}}
         <input type="hidden" name="update_id" value="<?=$booking_details->id?>">
        <div class="card">
        <div class="row" style="padding:2%">
           <div class="col-lg-6  col-6">
            <label> Category</label> <span style="color:red">*</span>
           <select name="parent_category" class="form-control parent_category">
            <option value="">Select Category</option>
            <?php foreach ($category as $key => $value) {
            if($value->parent_id=='0'){ ?>
              <option <?php if($booking_details->category==$value->id){echo "selected";}?> value="<?=$value->id?>"><?=$value->category_name?></option>
            <?php } }?>
            </select>
         </div>
         <div class="col-lg-6 col-6">
            <label>Subcategory</label> <span style="color:red">*</span>
           <select name="subcategory" class="form-control subcategory">
            <option value="">Select SubCategory</option>
           
            </select>
         </div>
         <div class="col-lg-4 col-6">
           <label>Select Customer</label> 
            <select name="customer_id" class="form-control">
            <option value="">Select Customer</option>
            <?php foreach ($customers as $key => $value) {
           ?>
              <option <?php if($value->id==$booking_details->customer_id){echo "selected";} ?> value="<?=$value->id?>"><?=$value->customer_name?> (<?=$value->email?>)</option>
            <?php } ?>
            </select>
          </div>
           <div class="col-lg-4 col-6">
           <label>Customer name</label> <span style="color:red">*</span>
            <input type="text" name="customer_name" value="<?=@$booking_details->customer_name?>" placeholder="Customer Name" class="form-control">
          </div>
          <div class="col-lg-4 col-6">
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
            <input type="text" name="near_by_address" value="<?=@$booking_details->near_by_address?>" placeholder="Near By Address" class="form-control near_by_address">
            <ul class="view_address">
              
            </ul>
          </div>
          <div class="col-lg-12 col-6">
           <label>Note</label>
            <input type="text" name="note" value="<?=@$booking_details->note?>" placeholder="Note" class="form-control">
          </div>
         
          <div class="col-lg-12 col-6">
            <hr>
          </div>
         <div class="col-lg-7 col-6" >
            <label>Choose Vendor</label>
            <div style="    overflow-y: scroll;
    max-height: 250px;">
             <table width="100%">
              <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Distance</th>
              </tr>
              <tbody class="vendors">
                  <?php foreach ($vendor as $key => $value) { ?>
                <tr>
                <td><input type='radio' <?php if($booking_details->vendor==$value->id){echo "checked";} ?> name='vendor' class='vendor_check' value='<?=$value->id?>'></td>
                <td><?=$value->first_name ?> <?=$value->last_name?></td>
                <td><?=$value->phone?></td>
                <td><?=$value->address?></td>
                <td>0 Km</td>
              </tr>
            <?php } ?>
              </tbody>
               
            </table>
          </div>
        <div class="col-lg-12 col-6">
           <h5>Vendor Details</h5>
            <div class="vendor_details" style="font-size: 13px;">
               <b>Name</b>: <?=$booking_details->first_name?> <?=$booking_details->last_name?>, 
              <b>Phone</b>: <?=$booking_details->number?>, 
              <b>Address</b>: <?=$booking_details->address?>
            </div>
           <h4>Service List</h4>
            <div class="service_details">
              <table width="100%">
                <thead>
                   <tr>
                     <th>Service Name</th>
                     <th>Service Price</th>

                     <th>Action</th>
                   </tr>
                </thead>
                
                <tbody class="service_list">
                  <?php
                  $total=array();
                   foreach ($booking_item_details as $key => $value) {
                    $total[]=$value->service_price;
                   ?>
                   
                 <tr>
                    <td><input type="hidden" value="<?=$value->id?>" name="service_id[]">
       <input type="hidden" value="<?=$value->service_name?>" name="service_name[]">
       <?=$value->service_name?> </td>
       <td><input type="hidden" value="<?=$value->service_price?>" name="service_price[]">
       <?=$value->service_price?></td>
                  <td><a href="javaScript:void(0)" class="btn btn-danger btn-sm remove_rervice_item1"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                 </tr>
               <?php } ?>
               <tr>
               <th>Total</th>
               <th><input type="hidden" name="total_amount" value="<?=array_sum($total)?>"><?=array_sum($total)?></th>
                </tr>
                </tbody>
              </table>
            </div>
        </div>
         </div>
         <div class="col-lg-5 col-6">
            <label>Choose Service</label>
            <div class="row services">
             <?php foreach ($services as $key => $value) { ?>
              <div class="col-lg-5 add_service" data-id="<?=$value->id?>">
                 <?php if($value->image){ ?>
                <img src="<?php echo asset('/');?>uploads/service/<?=$value->image?>">
              <?php } ?>
                <h6>Rs. <?=$value->service_price?></h6>
                <span><?=$value->service_name?></span>
              </div>
               <?php } ?>
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