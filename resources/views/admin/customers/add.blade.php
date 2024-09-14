@extends('admin.layout')
@section('content')
<style>
  form#customer_form,
  #order_form {
    width: 100%;
  }

  .item_quantity {
    width: 45px;
    padding: 0px 0px 0px 5px;
  }
</style>
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
            <li class="breadcrumb-item"><a href="{{url('admin/customers')}}">Customers</a></li>
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
      <div class="card">
        <div class="row" id="customer_div">
          {{Form::open(array('url'=>'admin/customers/create','method'=>'post','enctype'=>'multipart/form-data','id'=>'customer_form'))}}
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>First name</label> <span style="color:red">*</span>
              <input type="text" name="first_name" value="" required id="first_name" class="form-control"
                data-placeholder="First name">
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Last name</label>
              <input type="text" name="last_name" value="" id="last_name" class="form-control" placeholder="Last name">
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Email</label> <span style="color:red">*</span>
              <input type="email" name="email" required value="" id="email" class="form-control" placeholder="Email">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Password</label>
              <input type="password" name="password"   class="form-control"
                placeholder="Password">
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Phone</label>
              <input type="tel" name="phone" value="" id="phone" class="form-control" placeholder="Phone">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Image</label>
              <input type="file" name="image" id="view_img" class="form-control">
              <div class="viewimg" style="display: none"><img id="v_img" src="" alt="your image"
                  style="width: 22%; padding: 1%;"></div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Address1</label> <span style="color:red">*</span>
              <input type="text" name="line_1" required value="" placeholder="Address" class="form-control">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Address2</label>
              <input type="text" name="line_2" value="" placeholder="Address 2 (optional)" class="form-control">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Zip Code</label>
              <input type="text" name="zip_code" value="" placeholder="Zip code (optional)" class="form-control">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Country</label> <span style="color:red">*</span>
              {{Form::select('country', $countries, '',['class'=>'form-control countries','id'=>'countries','required'=>'required'])}}
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>State</label>
              {{Form::select('state', $states, '',['class'=>'form-control state','id'=>'states'])}}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>City</label>
              {{Form::select('city', $cities,'',['class'=>'form-control city','id'=>'cities'])}}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Change User type</label>
             <select class="form-control" name="user_type" id="change_div">
                 <option  value="5">Customer</option>
                <!-- <option  value="6">Buyer</option>-->
                 <option  value="7">Delivery Boy</option>
             </select>
            </div>
          </div>
          
         <div id="div_show" style="display:none"> 
         
           <div class="clearfix"></div>
         <hr>
          
          <div class="row">
              <div class="col-sm-3">
                   <div class="form-group">
                    <label>Father name</label>
                    <input type="text" name="father_name" class="form-control">
                    </div>
              </div>
              
              <div class="col-sm-3">
                   <div class="form-group">
                    <label> Married / unmarried  </label>
                       <select name="single" class="form-control">
                           <option value="">  --select-- </option>
                           <option> Married </option>
                           <option> unmarried </option>
                       </select>
                    </div>
              </div>
              
              <div class="col-sm-3">
                   <div class="form-group">
                    <label>Adhar number</label>
                    <input type="text" name="adhar_no" class="form-control">
                    </div>
              </div>
              <div class="col-sm-3">
                   <div class="form-group">
                    <label>driving licance number </label>
                    <input type="text" name="driving_licance_no" class="form-control">
                    </div>
              </div>
              <div class="col-sm-3">
                   <div class="form-group">
                    <label>RC insurance number </label>
                    <input type="text" name="rc_insurance_no" class="form-control">
                    </div>
              </div>
              <div class="col-sm-3">
                   <div class="form-group">
                    <label> Wheeler for option </label>
                   <select name="wheeler" class="form-control">
                       <option value="2"> 2 </option>
                       <option value="3"> 3 </option>
                       <option value="4"> 4 </option>
                   </select>
                    </div>
              </div>
              
              <div class="col-sm-3">
                   <div class="form-group">
                    <label> Refrance full name </label>
                    <input type="text" name="refrance_name" class="form-control">
                    </div>
              </div>
              
              <div class="col-sm-3">
                   <div class="form-group">
                    <label> Refrance contact number </label>
                    <input type="number" name="refrance_no" class="form-control">
                    </div>
              </div>
              
          </div>
         
          
          <div class="clearfix"></div>
          <h4> Account Details </h4><hr>
          <div class="row">
          
              <div class="col-md-4">
                <div class="form-group">
                  <label>Account holder name</label>
                  <input type="text" name="holder_name" class="form-control">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <label>Bank Name</label>
                  <input type="text" name="bank_name" placeholder="" class="form-control">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <label>Branch Name</label>
                  <input type="text" name="branch_name" placeholder="" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Account no.</label>
                  <input type="number" name="account_no" placeholder="" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>IFSC Code</label>
                  <input type="text" name="ifsc_code" placeholder="" class="form-control">
                </div>
              </div>
              
          </div>
          
          <div class="clearfix"></div>
          <h4> KYC Details </h4><hr>
          
          <div class="row">
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Passbook  UPLOAD </label>
                  <input type="file" name="passbook" class="form-control">
                </div>
              </div>
              
               <div class="col-md-6">
                <div class="form-group">
                  <label>Cancel cheque UPLOAD </label>
                  <input type="file" name="cencel_check" class="form-control">
                </div>
              </div>
              
               <div class="col-md-6">
                <div class="form-group">
                  <label>Addhar Card UPLOAD</label>
                  <input type="file" name="addhar_card" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pan Card UPLOAD</label>
                  <input type="file" name="pan_card" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>RC document upload</label>
                  <input type="file" name="rc_document" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Driving licence document upload</label>
                  <input type="file" name="driving_licence" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label> Bike Image UPLOAD </label>
                  <input type="file" name="bike_image" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label> police verification documents upload</label>
                  <input type="file" name="police_verification_document" class="form-control"><br>
                </div>
              </div>
              
               <div class="col-md-6">
                <div class="form-group">
                  <label> house electric bill documents upload</label>
                  <input type="file" name="house_electric_document" class="form-control"><br>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label> photo of house upload</label>
                  <input type="file" name="house_photo" class="form-control"><br>
                </div>
              </div>
            
         </div>
          
        </div>
    
          
          <div class="clearfix"></div>
          <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-sm btn-info">Save</button>
          </div>
          <div class="clearfix"></div>
          {{Form::close()}}
        </div>

      </div>
  </section>

</div>

@stop
@push('js')
<script src="{{asset('assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="<?php echo asset('/assets/user.js');?>"></script>
@endpush