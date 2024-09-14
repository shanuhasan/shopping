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
              <li class="breadcrumb-item"><a href="{{url('admin/vendor_list')}}">Vendor List</a></li>
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
      {{Form::open(array('url'=>'admin/update_vendor','method'=>'post','enctype'=>'multipart/form-data'))}}
         <input type="hidden" value="<?=$edit_data->id?>" name="update_id">
          <div class="card">
        <div class="row" style="padding:2%">
          <div class="col-lg-4 col-6">
            <label>Choose type</label> <span style="color:red">*</span>
           <select name="parent_category[]" multiple class="form-control">
            <option value="">Select Type</option>
            <?php foreach ($category as $key => $value) {
            if($value->parent_id=='0'){ ?>
            	<option <?php if(in_array($value->id, explode(',', $edit_data->parent_category))){echo "selected";}?> value="<?=$value->id?>"><?=$value->category_name?></option>
            <?php } }?>
            </select>
         </div>
           <div class="col-lg-4 col-6">
           <label>First Name</label> <span style="color:red">*</span>
            <input type="text" name="first_name" value="<?=@$edit_data->first_name?>" placeholder="Vendor  Name" class="form-control"></div>
            <div class="col-lg-4 col-6">
           <label>Last Name</label>
            <input type="text" name="last_name" value="<?=@$edit_data->last_name?>" placeholder="Vendor  Name" class="form-control"></div>
          <div class="col-lg-6 col-6">
           <label>Email</label> <span style="color:red">*</span>
            <input type="email" name="email" value="<?=@$edit_data->email?>" placeholder="Email" class="form-control"></div>
          <div class="col-lg-6 col-6">
           <label>Phone</label> <span style="color:red">*</span>
            <input type="number" name="phone" value="<?=@$edit_data->phone?>" placeholder="Phone" class="form-control"></div>
            
          <div class="col-lg-12 col-6">
           <label>Permanent Address</label> 
            <input type="text" name="permanent_address" value="<?=$edit_data->permanent_address?>" placeholder="Permanent Address" class="form-control"></div>
             <div class="col-lg-12 col-6">
           <label>Present Address</label> 
            <input type="text" name="present_address" value="<?=$edit_data->present_address?>" placeholder="Present Address" class="form-control"></div>
          <div class="col-lg-12 col-6">
           <label>Current Address</label> <span style="color:red">*</span>
            <input type="text" name="address" value="<?=@$edit_data->address?>" placeholder="Current Address" class="form-control"></div>
            <div class="col-lg-12 col-6">
           <label>Country</label> 
            <input type="text" name="country" value="<?=@$edit_data->country?>" placeholder="Country" class="form-control"></div>
              <div class="col-lg-12 col-6">
           <label>State</label>
            <select name="state" class="form-control state_view">
            <option value="">Select State</option>
            <?php foreach ($states as $key => $value) {
            ?>
              <option <?php if($value->id==$edit_data->state){echo "selected";}?> value="<?=$value->id?>"><?=$value->name?></option>
            <?php } ?>
            </select>
            </div>
              <div class="col-lg-12 col-6">
           <label>City</label>
          <select name="city" class="form-control city_view">
            <option value="">Select City</option>
             <?php foreach ($cities as $key => $value) {
            ?>
              <option <?php if($value->id==$edit_data->city){echo "selected";}?> value="<?=$value->id?>"><?=$value->name?></option>
            <?php } ?>
          </select>
        </div>
             <div class="col-lg-12 col-6">
           <label>Commission</label> 
            <input type="number" name="commission" value="<?=@$edit_data->commission?>" placeholder="Commission" class="form-control"></div>
            <div class="col-lg-12">
             <label>Profile pic</label>
             <input type="hidden" name="pp" value="<?=$edit_data->profile?>">
            <input type="file" name="profile" id="view_img" class="form-control">
             <?php if($edit_data->profile){ ?>
                      <img src="<?php echo asset('/');?>uploads/profile/<?=$edit_data->profile?>" width="60px">
                    <?php } ?>
            <div class="viewimg" style="display: none"><img id="v_img" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
            <div class="col-lg-12">
            <h3>Account Details</h3>
          </div>
             <div class="col-lg-6 col-6">
           <label>Holder Name</label> 
            <input type="text" name="holder_name" value="<?=@$edit_data->holder_name?>" placeholder="Holder Name" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Account Number</label> 
            <input type="text" name="account_number" value="<?=@$edit_data->account_number?>" placeholder="Account Number" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>IFSC Code</label> 
            <input type="text" name="ifsc_code" value="<?=@$edit_data->ifsc_code?>" placeholder="IFSC Code" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Branch Name</label> 
            <input type="text" name="branch_name" value="<?=@$edit_data->branch_name?>" placeholder="Branch Name" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Bank Name</label> 
            <input type="text" name="bank_name" value="<?=@$edit_data->bank_name?>" placeholder="Bank Name" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Cancel Check</label> 
            <input type="hidden" name="cc" value="<?=$edit_data->cancel_check?>">
            <input type="file" name="cancel_check" class="form-control">
             <?php if($edit_data->cancel_check){ ?>
                      <img src="<?php echo asset('/');?>uploads/cancel_check/<?=$edit_data->cancel_check?>" width="60px">
                    <?php } ?>
          </div>
            <div class="col-lg-6 col-6">
           <label>Passbook</label> 
            <input type="hidden" name="pass" value="<?=$edit_data->passbook?>">
            <input type="file" name="passbook" class="form-control">
             <?php if($edit_data->passbook){ ?>
                      <img src="<?php echo asset('/');?>uploads/passbook/<?=$edit_data->passbook?>" width="60px">
                    <?php } ?>
          </div>
            <div class="col-lg-12">
            <h3>Kyc Details</h3>
          </div>
         
          <div class="col-lg-6">
             <label>Adhar card</label>
              <input type="hidden" name="ad" value="<?=$edit_data->adhar_card?>">
            <input type="file" name="adhar_card" id="view_img2" class="form-control">
            <?php if($edit_data->adhar_card){ ?>
                      <img src="<?php echo asset('/');?>uploads/adharcard/<?=$edit_data->adhar_card?>" width="60px">
                    <?php } ?>
            <div class="viewimg2" style="display: none"><img id="v_img2" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
           <div class="col-lg-6">
             <label>Another image 1 </label>
              <input type="hidden" name="img1" value="<?=$edit_data->image1?>">
            <input type="file" name="image1" id="view_img1" class="form-control">
            <?php if($edit_data->image1){ ?>
                      <img src="<?php echo asset('/');?>uploads/adharcard/<?=$edit_data->image1?>" width="60px">
                    <?php } ?>
            <div class="viewimg1" style="display: none"><img id="v_img1" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
           <div class="col-lg-6">
             <label>Another Image 2 </label>
              <input type="hidden" name="img2" value="<?=$edit_data->image2?>">
            <input type="file" name="image2" id="view_img1" class="form-control">
            <?php if($edit_data->image2){ ?>
                      <img src="<?php echo asset('/');?>uploads/adharcard/<?=$edit_data->image2?>" width="60px">
                    <?php } ?>
            <div class="viewimg1" style="display: none"><img id="v_img1" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
           <div class="col-lg-6">
             <label>Another image 3 </label>
              <input type="hidden" name="img3" value="<?=$edit_data->image3?>">
            <input type="file" name="image3" id="view_img1" class="form-control">
            <?php if($edit_data->image3){ ?>
                      <img src="<?php echo asset('/');?>uploads/adharcard/<?=$edit_data->image3?>" width="60px">
                    <?php } ?>
            <div class="viewimg1" style="display: none"><img id="v_img1" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
           <div class="col-lg-12">
            <h3>Vendor Status</h3>
          </div>
           <div class="col-lg-6">
             <label>Status</label>
                <select class="form-control" name="status">
                  <option <?php if($edit_data->status=='0'){echo "selected";}?> value="0">Deactive</option>
                  <option <?php if($edit_data->status=='1'){echo "selected";}?> value="1">Active</option>
                </select>
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