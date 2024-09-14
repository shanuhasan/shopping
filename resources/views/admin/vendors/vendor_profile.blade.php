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
            <li class="breadcrumb-item active"><?=@$page_title?></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  
  
  <section class="container-fluid card">
    <div class="container">

      <div class="row">
        <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="{{url('/uploads/users/',@$user->profile_image)}}" alt="User profile picture">
    
                  <h3 class="profile-username text-center"> {{ @$user->first_name }} {{ @$user->last_name }} </h3><br>
    
                  <ul class="list-group list-group-unbordered">
                      <li class="list-group-item">
                      <b>Commission</b> <a class="pull-right">{{@$user->commission}}% </a>
                    </li>
                    
                      <li class="list-group-item">
                      <b>Name of firm</b> <a class="pull-right">{{ @$user_detail->name_firm }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Owner name</b> <a class="pull-right">{{ @$user_detail->owner_name }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Email</b> <a class="pull-right">{{ @$user->email }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Number</b> <a class="pull-right">{{ @$user->phone }}</a>
                    </li>
                     <li class="list-group-item">
                        <b>country</b> <a class="pull-right">{{ @$country->name }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>state</b> <a class="pull-right">{{ @$state->name }}</a>
                    </li>
                  </ul>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
        </div>
        
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Profile</a></li>
              <li><a href="#settings" data-toggle="tab">Profile Edit</a></li>
              <li><a href="#change_password" data-toggle="tab">Change Password</a></li>
            </ul>
            <div class="tab-content">
                
              <div class="active tab-pane" id="activity">
                <!-- Post -->
                <div class="item mt-3">
                 <div class="row">
                        
                    <div class="col-sm-6">
                        <li class="list-group-item">
                          <b>Address 1</b> <a class="pull-right">{{ @$user->line_1 }}</a>
                        </li>
                        <li class="list-group-item">
                          <b>Address 2</b> <a class="pull-right">{{ @$user->line_2 }}</a>
                        </li>
                        <li class="list-group-item">
                          <b>city</b> <a class="pull-right">{{ @$city->name }}</a>
                        </li>
                        <li class="list-group-item">
                          <b>zip_code</b> <a class="pull-right">{{ @$user->zip_code }}</a>
                        </li>
                        <li class="list-group-item">
                          <b>GST no </b> <a class="pull-right">{{ @$user_detail->gst_no }}</a>
                        </li>
                
                    </div>  
                    
                    <div class="col-sm-6">
                        <h4> Account Details </h4>
                        <li class="list-group-item">
                          <b>Bank Name</b> <a class="pull-right">{{ @$user_detail->bank_name }}</a>
                        </li>
                        <li class="list-group-item">
                          <b>Branch Name</b> <a class="pull-right">{{ @$user_detail->branch_name }}</a>
                        </li>
                        <li class="list-group-item">
                          <b>Account No </b> <a class="pull-right">{{ @$user_detail->account_no }}</a>
                        </li>
                        <li class="list-group-item">
                          <b>IFSC code</b> <a class="pull-right">{{ @$user_detail->ifsc_code }}</a>
                        </li>
                    </div><!-- col-sm-6--> 
                 </div><!-- row-->
                 
                 <hr><h4> Document Details </h4><hr>
                 <div class-="row" align="center">
                     <div class="col-sm-2"> <p> cencel check document</p><img class="profile-user-img img-responsive" src="{{url('/uploads/users/document/',@$user_detail->cencel_check)}}"></div>
                     <div class="col-sm-2"><p> addhar card document</p><img class="profile-user-img img-responsive" src="{{url('/uploads/users/document/',@$user_detail->addhar_card)}}"></div>
                     <div class="col-sm-2"><p> pan card document</p><img class="profile-user-img img-responsive" src="{{url('/uploads/users/document/',@$user_detail->pan_card)}}"></div>
                     <div class="col-sm-2"><p> trade license document </p><img class="profile-user-img img-responsive" src="{{url('/uploads/users/document/',@$user_detail->trade_license_document)}}"></div>
                     <div class="col-sm-2"><p> vendor policy document </p><img class="profile-user-img img-responsive" src="{{url('/uploads/users/document/',@$user_detail->vendor_policy_document)}}"></div>
                     <div class="col-sm-2"><p> gst document</p><img class="profile-user-img img-responsive" src="{{url('/uploads/users/document/',@$user_detail->gst_document)}}"></div>
                 </div>
                
                </div>
              </div>
              <!-- /.tab-pane -->
             

              <div class="tab-pane" id="settings">
            
                   {{Form::open(array('url'=>'vendor/vendor_update','method'=>'post','enctype'=>'multipart/form-data','id'=>'customer_form_b'))}}
                   
                      <input type="hidden" name="update_id" value="{{ @$user->id}}">
                      <input type="hidden" name="profile_id" value="{{ @$user_detail->id}}">
          
                      <div class="row">
                          
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <label>First name </label> <span style="color:red">*</span>
                          <input type="text" name="first_name" value="{{ @$user->first_name}}" required id="first_name" class="form-control" data-placeholder="First name">
                        </div>
                      </div>
                      
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <label>Last name</label>
                          <input type="text" name="last_name" value="{{ @$user->last_name}}" id="last_name" class="form-control" placeholder="Last name">
                        </div>
                      </div>
                      
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <label>Name of firm</label>
                          <input type="text" name="name_firm" value="{{ @$user_detail->name_firm }}" class="form-control" placeholder="Name of firm">
                        </div>
                      </div>
                      
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <label>Owner name</label>
                          <input type="text" name="owner_name" value="{{ @$user_detail->owner_name }}" class="form-control" placeholder="Owner name">
                        </div>
                      </div>
                      
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <label>Email</label> <span style="color:red">*</span>
                          <input type="email" name="email" required value="{{ @$user->email}}" id="email" class="form-control" placeholder="Email">
                        </div>
                      </div>
                      <div class="clearfix"></div>

                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <label>Phone</label>
                          <input type="tel" name="phone" value="{{ @$user->phone}}" id="phone" class="form-control" placeholder="Phone">
                        </div>
                      </div>
                      
                      <div class="clearfix"></div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Address1</label> <span style="color:red">*</span>
                          <input type="text" name="line_1" required value="{{ @$user->line_1}}" placeholder="Address" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Address2</label>
                          <input type="text" name="line_2" value="{{ @$user->line_2}}" placeholder="Address 2 (optional)" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Zip Code</label>
                          <input type="text" name="zip_code" value="{{ @$user->zip_code}}" placeholder="Zip code (optional)" class="form-control">
                        </div>
                      </div>
                
                      <div class="clearfix"></div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Country</label> <span style="color:red">*</span>
                          {{Form::select('country', $countries,  @$user->country ,['class'=>'form-control countries','id'=>'countries_b','required'=>'required'])}}
                          
                          
                        </div>
                        <!--selected="selected"-->
                      </div> 
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>State </label>
                          {{Form::select('state', $states, @$user->state, ['class'=>'form-control state','id'=>'states'])}}
                          
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>City </label>
                          {{Form::select('city', $cities, @$user->city, ['class'=>'form-control city','id'=>'cities'])}}
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>GST No</label>
                          <input type="type" name="gst_no" value="{{@$user_detail->gst_no}}" placeholder="gst no"  class="form-control">
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Commission</label>
                          <input type="number" name="commission" id="commission" class="form-control" value="{{@$user->commission}}">
                        </div>
                      </div>
                      
                       </div>
         
                       <div class="clearfix"></div>
                       <h4> Account Details </h4><hr>
                       <div class="row">
                           
                           <div class="col-md-3">
                            <div class="form-group">
                              <label>Bank Name</label>
                              <input type="text" name="bank_name" value="{{@$user_detail->bank_name}}" class="form-control">
                            </div>
                          </div>
                          
                          <div class="col-md-3">
                            <div class="form-group">
                              <label>Branch Name</label>
                              <input type="text" name="branch_name" value="{{@$user_detail->branch_name}}"  class="form-control">
                            </div>
                          </div>
                     
                          <div class="col-md-3">
                            <div class="form-group">
                              <label>Account Number</label>
                              <input type="number" name="account_no" value="{{@$user_detail->account_no}}"  class="form-control">
                            </div>
                          </div>
                          
                          <div class="col-md-3">
                            <div class="form-group">
                              <label>IFSC Code</label>
                              <input type="text" name="ifsc_code" value="{{@$user_detail->ifsc_code}}" class="form-control">
                            </div>
                          </div>
    
                      
                      </div><!--inner row-->
          
                      <div class="clearfix"></div>
                       <h4> Document Details </h4><hr>
                       <div class="row">
                           
                             <div class="col-md-4">
                                <div class="form-group">
                              <label>Profile</label>
                              <input type="hidden" name="old_image" value="{{ @$user->profile_image}}">
                              <input type="file" name="image" id="view_img" class="form-control">
                              <img class="img-responsive" src="{{url('/uploads/users/',@$user->profile_image)}}" width="100">
                            </div>
                            </div>
                            
                            <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" name="old_cencel_check" value="{{@$user_detail->cencel_check}}"  class="form-control">
                              <label>Cancel cheque UPLOAD </label>
                              <input type="file" name="cencel_check" placeholder=""  class="form-control">
                              <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_detail->cencel_check)}}" width="100">
                            </div>
                          </div>
                            
                           <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" name="old_addhar_card" value="{{@$user_detail->addhar_card}}"  class="form-control">
                              <label>Addhar Card UPLOAD</label>
                              <input type="file" name="addhar_card" placeholder=""  class="form-control">
                              <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_detail->addhar_card)}}" width="100">
                            </div>
                          </div>
                          
                          <div class="col-md-4">
                              <input type="hidden" name="old_pan_card" value="{{@$user_detail->pan_card}}"  class="form-control">
                            <div class="form-group">
                              <label>Pan Card UPLOAD</label>
                              <input type="file" name="pan_card" placeholder=""  class="form-control">
                              <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_detail->pan_card)}}" width="100">
                            </div>
                          </div>
                          
                          <div class="col-md-4">
                                <input type="hidden" name="old_trade_license_document" value="{{@$user_detail->trade_license_document}}"  class="form-control">
                                <div class="form-group">
                                  <label>Trade licenses document upload</label>
                                  <input type="file" name="trade_license_document" placeholder=""  class="form-control">
                                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_detail->trade_license_document)}}" width="100">
                                </div>
                          </div>
                          
                          <div class="col-md-4">
                                <input type="hidden" name="old_vendor_policy_document" value="{{@$user_detail->vendor_policy_document}}"  class="form-control">
                                <div class="form-group">
                                  <label>Vendor policy agreement document upload</label>
                                  <input type="file" name="vendor_policy_document" placeholder=""  class="form-control">
                                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_detail->vendor_policy_document)}}" width="100">
                                </div>
                          </div>
                          
                          <div class="col-md-4">
                                <input type="hidden" name="old_gst_document" value="{{@$user_detail->gst_document}}"  class="form-control">
                                <div class="form-group">
                                  <label>Gst no document UPLOAD </label>
                                  <input type="file" name="gst_document" placeholder=""  class="form-control">
                                  <img class="img-responsive" src="{{url('/uploads/users/document/',@$user_detail->gst_document)}}" width="100">
                                </div>
                          </div>
                        
                     </div><!--inner row-->  
          
                  <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-sm btn-info"> update Save</button>
                  </div>
                  <div class="clearfix"></div>
         
        {{Form::close()}}

                  
               
              </div>
              <!-- /.tab-pane -->
              
              
            <div class="tab-pane" id="change_password">
                
                <div class="box-body"><br><br>
                <div class="">
                    <form action="{{url('vendor/change_pasword')}}" method="post">
                        @csrf
                    <div class="form-group">
                        <lebel> Current Password </lebel>
                        <input type="password" name="current_password" class="form-control">
                    </div>  
                    
                    <div class="form-group">
                        <lebel> New Password </lebel>
                        <input type="password" name="new_password" class="form-control">
                    </div>  
                    <div class="form-group">
                        <input type="submit" value="change password" class="btn btn-info">
                    </div>  
                    </form>
                </div>
                </div>
                  
            </div>
              
              
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </div>  
    </section>



  

</div>

@stop
@push('js')
<script src="{{asset('assets/bacend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="<?php echo asset('/assets/user.js');?>"></script>
@endpush