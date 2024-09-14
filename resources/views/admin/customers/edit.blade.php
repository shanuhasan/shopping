@extends('admin.layout')
@section('content')
<style>
  form#customer_edit,
  #user_form {
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
            @if(@$user_details->id)
                <h1 class="m-0 text-dark">Delivery Boy</h1>
            @else
                <h1 class="m-0 text-dark"><?=@$page_title?></h1>
            @endif
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="">Home</a></li>
             @if(@$user_details->id)
                <li class="breadcrumb-item"><a href="{{url('admin/customers/deliveryboy')}}">Add delivery boy </a></li>
                @else
                <li class="breadcrumb-item"><a href="{{url('admin/customers')}}">Add Customers</a></li>
             @endif
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
          {{Form::open(array('url'=>'admin/customers/delivery_boy_update','method'=>'post','enctype'=>'multipart/form-data','id'=>'customer_edit'))}}
          <input type="hidden" name="id" value="{{$user->id}}" id="id">
          <input type="hidden" name="profile_id" value="{{@$user_details->id}}">
          
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>First name</label> <span style="color:red">*</span>
              <input type="text" name="first_name" value="{{$user->first_name}}" required id="first_name" class="form-control"
                data-placeholder="First name">
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Last name</label>
              <input type="text" name="last_name" value="{{$user->last_name}}" id="last_name" class="form-control" placeholder="Last name">
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Email</label> <span style="color:red">*</span>
              <input type="email" name="email" required value="{{$user->email}}" id="email" class="form-control" placeholder="Email">
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
              <input type="tel" name="phone" value="{{$user->phone}}" id="phone" class="form-control" placeholder="Phone">
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="form-group">
              <input type="hidden" name="old_image" value="{{$user->profile_image}}" class="form-control">
              
              <label>Top to bottom photo</label>
              <input type="file" name="image" id="view_img" class="form-control">
              <div class="viewimg"><img id="v_img" src="<?php echo asset('/');?>uploads/users/<?=$user->profile_image?>" alt="your image"
                  style="width: 22%; padding: 1%;"></div>
            </div>
          </div>
          
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Address1</label> <span style="color:red">*</span>
              <input type="text" name="line_1" required value="{{$user->line_1}}" placeholder="Address" class="form-control">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Address2</label>
              <input type="text" name="line_2" value="{{$user->line_2}}" placeholder="Address 2 (optional)" class="form-control">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Zip Code</label>
              <input type="text" name="zip_code" value="{{$user->zip_code}}" placeholder="Zip code (optional)" class="form-control">
            </div>
          </div>
          
          <!--<div class="col-md-4">-->
          <!--  <div class="form-group">-->
          <!--    <label>Latitude</label>-->
          <!--    <input type="text" name="lat" value="{{$user->lat}}" placeholder="Latitude" class="form-control">-->
          <!--  </div>-->
          <!--</div>-->
          <!--<div class="col-md-4">-->
          <!--  <div class="form-group">-->
          <!--    <label>Longitude</label>-->
          <!--    <input type="text" name="long" value="{{$user->long}}" placeholder="Longitude" class="form-control">-->
          <!--  </div>-->
          <!--</div>-->
          
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Country</label> <span style="color:red">*</span>
              {{Form::select('country', $countries, $user->country,['class'=>'form-control countries','id'=>'countries_b','required'=>'required'])}}
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>State</label>
              {{Form::select('state', $states, $user->state,['class'=>'form-control state','id'=>'states'])}}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>City</label>
              {{Form::select('city', $cities,$user->city,['class'=>'form-control city','id'=>'cities'])}}
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="form-group">
              <label>Change User type</label>
             <select class="form-control" name="user_type" id="change_div">
                 <option <?php if($roles->role_id=='5'){echo "selected";}?> value="5">Customer</option>
                 <!--<option <?php if($roles->role_id=='6'){echo "selected";}?> value="6">Buyer</option>-->
                 <option <?php if($roles->role_id=='7'){echo "selected";}?> value="7">Delivery Boy</option>
             </select>
            </div>
          </div>
          
         @if(@$user_details->id)
         <div id="div_show">
          
          <div class="clearfix"></div>
         <hr>
          
          <div class="row">
              <div class="col-sm-3">
                   <div class="form-group">
                    <label>Father name</label>
                    <input type="text" name="father_name" value="{{ @$user_details->father_name }}" class="form-control">
                    </div>
              </div>
              
              <div class="col-sm-3">
                   <div class="form-group">
                    <label> Married / unmarried  </label>
                       <select name="single" class="form-control">
                           <option value="">  --select-- </option>
                           <option {{ @$user_details->single=='Married'?'selected':'' }}> Married </option>
                           <option {{ @$user_details->single=='unmarried'?'selected':'' }}> unmarried </option>
                       </select>
                    </div>
              </div>
              
              <div class="col-sm-3">
                   <div class="form-group">
                    <label>Adhar number</label>
                    <input type="text" name="adhar_no" value="{{ @$user_details->adhar_no }}" class="form-control">
                    </div>
              </div>
              <div class="col-sm-3">
                   <div class="form-group">
                    <label>driving licance number </label>
                    <input type="text" name="driving_licance_no" value="{{ @$user_details->driving_licance_no }}" class="form-control">
                    </div>
              </div>
              <div class="col-sm-3">
                   <div class="form-group">
                    <label>RC insurance number </label>
                    <input type="text" name="rc_insurance_no" value="{{ @$user_details->rc_insurance_no }}" class="form-control">
                    </div>
              </div>
              <div class="col-sm-3">
                   <div class="form-group">
                    <label> Wheeler for option </label>
                   <select name="wheeler" class="form-control">
                       <option value="2" {{ @$user_details->wheeler=='2'?'selected':'' }}> 2 </option>
                       <option value="3" {{ @$user_details->wheeler=='3'?'selected':'' }}> 3 </option>
                       <option value="4" {{ @$user_details->wheeler=='4'?'selected':'' }}> 4 </option>
                   </select>
                    </div>
              </div>
              
              <div class="col-sm-3">
                   <div class="form-group">
                    <label> Refrance full name </label>
                    <input type="text" name="refrance_name" value="{{ @$user_details->refrance_name }}" class="form-control">
                    </div>
              </div>
              
              <div class="col-sm-3">
                   <div class="form-group">
                    <label> Refrance contact number </label>
                    <input type="number" name="refrance_no" value="{{ @$user_details->refrance_no }}" class="form-control">
                    </div>
              </div>
              
          </div>
          
          
          <div class="clearfix"></div>
          <h4> Account Details </h4><hr>
          <div class="row">
          
              <div class="col-md-4">
                <div class="form-group">
                  <label>Account holder name</label>
                  <input type="text" name="holder_name" value="{{ @$user_details->holder_name }}" class="form-control">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <label>Bank Name</label>
                  <input type="text" name="bank_name" value="{{ @$user_details->bank_name }}" class="form-control">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <label>Branch Name</label>
                  <input type="text" name="branch_name"value="{{ @$user_details->branch_name }}" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Account no.</label>
                  <input type="number" name="account_no" value="{{ @$user_details->account_no }}" class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>IFSC Code</label>
                  <input type="text" name="ifsc_code" value="{{ @$user_details->ifsc_code }}" class="form-control">
                </div>
              </div>
              
          </div>
          
          <div class="clearfix"></div>
          <h4> KYC Details </h4><hr>
          
          <div class="row">
              
              <div class="col-md-6">
                <div class="form-group">
                  <input type="hidden" name="old_passbook" value="{{ @$user_details->passbook }}">    
                  <label>Passbook  UPLOAD </label>
                  <input type="file" name="passbook" class="form-control"><br>
                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->passbook)}}" width="100">
                </div>
              </div>
              
               <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_cencel_check" value="{{ @$user_details->cencel_check }}">
                  <label>Cancel cheque UPLOAD </label>
                  <input type="file" name="cencel_check" class="form-control"><br>
                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->cencel_check)}}" width="100">
                </div>
              </div>
              
               <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_addhar_card" value="{{ @$user_details->addhar_card }}">
                  <label>Addhar Card UPLOAD</label>
                  <input type="file" name="addhar_card" class="form-control"><br>
                 <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->addhar_card)}}" width="100">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_pan_card" value="{{ @$user_details->pan_card }}">
                  <label>Pan Card UPLOAD</label>
                  <input type="file" name="pan_card" class="form-control"><br>
                 <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->pan_card)}}" width="100">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_rc_document" value="{{ @$user_details->rc_document }}">
                  <label>RC document upload</label>
                  <input type="file" name="rc_document" class="form-control"><br>
                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->rc_document)}}" width="100">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_driving_licence" value="{{ @$user_details->driving_licence }}">
                  <label>Driving licence document upload</label>
                  <input type="file" name="driving_licence" class="form-control"><br>
                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->driving_licence)}}" width="100">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_bike_image" value="{{ @$user_details->bike_image }}">
                  <label> Bike Image UPLOAD </label>
                  <input type="file" name="bike_image" class="form-control"><br>
                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->bike_image)}}" width="100">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_police_verification_document" value="{{ @$user_details->police_verification_document }}">
                  <label> police verification documents upload</label>
                  <input type="file" name="police_verification_document" class="form-control"><br>
                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->police_verification_document)}}" width="100">
                </div>
              </div>
              
               <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_house_electric_document" value="{{ @$user_details->house_electric_document }}">
                  <label> house electric bill documents upload</label>
                  <input type="file" name="house_electric_document" class="form-control"><br>
                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->house_electric_document)}}" width="100">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="old_house_photo" value="{{ @$user_details->house_photo }}">
                  <label> photo of house upload</label>
                  <input type="file" name="house_photo" class="form-control"><br>
                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_details->house_photo)}}" width="100">
                </div>
              </div>
              
         </div>
         </div> 
          @endif
          
          
          <div class="clearfix"></div>
          <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-sm btn-info">Update</button>
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