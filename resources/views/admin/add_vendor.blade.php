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
      {{Form::open(array('url'=>'admin/insert_vendor','method'=>'post','enctype'=>'multipart/form-data'))}}
         
      <div class="card">
        <div class="row" style="padding:2%">
          <div class="col-lg-4 col-6">
            <label>Choose type</label> <span style="color:red">*</span>
           <select name="parent_category[]" multiple class="form-control">
            <option value="">Select Type</option>
            <?php foreach ($category as $key => $value) {
            if($value->parent_id=='0'){ ?>
            	<option value="<?=$value->id?>"><?=$value->category_name?></option>
            <?php } }?>
            </select>
         </div>
           <div class="col-lg-4 col-6">
           <label>First Name</label> <span style="color:red">*</span>
            <input type="text" name="first_name" value="<?=@$_POST['first_name']?>" placeholder="Vendor  Name" class="form-control">
          </div>
            <div class="col-lg-4 col-6">
           <label>Last Name</label>
            <input type="text" name="last_name" value="<?=@$_POST['last_name']?>" placeholder="Vendor  Name" class="form-control">
          </div>
          <div class="col-lg-6 col-6">
           <label>Email</label> <span style="color:red">*</span>
            <input type="email" name="email" value="<?=@$_POST['email']?>" placeholder="Email" class="form-control">
          </div>
          <div class="col-lg-6 col-6">
           <label>Phone</label> <span style="color:red">*</span>
            <input type="number" name="phone" value="<?=@$_POST['phone']?>" placeholder="Phone" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Password</label> <span style="color:red">*</span>
            <input type="password" name="password"  placeholder="Password" class="form-control"></div>
          <div class="col-lg-12 col-6">
           <label>Permanent Address</label> 
            <input type="text" name="permanent_address" value="<?=@$_POST['permanent_address']?>" placeholder="Permanent Address" class="form-control"></div>
             <div class="col-lg-12 col-6">
           <label>Present Address</label> 
            <input type="text" name="present_address" value="<?=@$_POST['present_address']?>" placeholder="Present Address" class="form-control"></div>
             <div class="col-lg-12 col-6">
           <label>Current Address</label> <span style="color:red">*</span>
            <input type="text" name="address" value="<?=@$_POST['address']?>" placeholder="Current Address" class="form-control"></div>
            <div class="col-lg-12 col-6">
           <label>Country</label>
            <input type="text" name="country" value="India" placeholder="Country" class="form-control">
           </div>
              <div class="col-lg-12 col-6">
           <label>State</label>
            <select name="state" class="form-control state_view">
            <option value="">Select State</option>
            <?php foreach ($states as $key => $value) {
            ?>
              <option value="<?=$value->id?>"><?=$value->name?></option>
            <?php } ?>
            </select>
            </div>
              <div class="col-lg-12 col-6">
           <label>City</label>
          <select name="city" class="form-control city_view">
            <option value="">Select City</option>
          </select>
        </div>
             <div class="col-lg-12 col-6">
           <label>Commission</label> 
            <input type="number" name="commission" value="<?=@$_POST['commission']?>" placeholder="Commission" class="form-control"></div>
            <div class="col-lg-12">
             <label>Profile pic</label> 
            <input type="file" name="profile" id="view_img" class="form-control">
            <div class="viewimg" style="display: none"><img id="v_img" src="#" alt="your image" style="width: 22%; padding: 1%;"></div>
          </div>
            <div class="col-lg-12">
            <h3>Account Details</h3>
          </div>
             <div class="col-lg-6 col-6">
           <label>Holder Name</label> 
            <input type="text" name="holder_name" value="<?=@$_POST['holder_name']?>" placeholder="Holder Name" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Account Number</label> 
            <input type="text" name="account_number" value="<?=@$_POST['account_number']?>" placeholder="Account Number" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>IFSC Code</label> 
            <input type="text" name="ifsc_code" value="<?=@$_POST['ifsc_code']?>" placeholder="IFSC Code" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Branch Name</label> 
            <input type="text" name="branch_name" value="<?=@$_POST['branch_name']?>" placeholder="Branch Name" class="form-control"></div>
            <div class="col-lg-6 col-6">
           <label>Bank Name</label> 
            <input type="text" name="bank_name" value="<?=@$_POST['bank_name']?>" placeholder="Bank Name" class="form-control"></div>
             <div class="col-lg-6 col-6">
           <label>Cancel Check</label> 
           <input type="file" name="cancel_check" class="form-control">
            </div>
          <div class="col-lg-6 col-6">
           <label>Passbook</label> 
            <input type="file" name="passbook" class="form-control">
           </div>
            <div class="col-lg-12">
            <h3>Kyc Details</h3>
          </div>
        <div class="col-lg-6">
             <label>Adhar card</label>
               <input type="file" name="adhar_card" id="view_img2" class="form-control">
           </div>
           <div class="col-lg-6">
             <label>Another image 1 </label>
              <input type="file" name="image1" id="view_img1" class="form-control">
           
             </div>
           <div class="col-lg-6">
             <label>Another Image 2 </label>
               <input type="file" name="image2" id="view_img1" class="form-control">
            </div>
           <div class="col-lg-6">
             <label>Another image 3 </label>
            <input type="file" name="image3" id="view_img1" class="form-control">
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