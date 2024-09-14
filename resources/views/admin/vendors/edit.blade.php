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
          <h1 class="m-0 text-dark"><?=@$page_title?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/vendors')}}">vendors</a></li>
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
        <div class="row" id="vendor_div">
          {{Form::open(array('url'=>'admin/vendors/edit','method'=>'post','enctype'=>'multipart/form-data','id'=>'customer_edit'))}}
          <input type="hidden" name="id" value="{{$user->id}}" id="id">
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
              <input type="password" name="password" value="" id="password" class="form-control"
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
              <label>Image</label>
              <input type="file" name="image" id="view_img" class="form-control">
              <div class="viewimg" style="{{$user->profile_image?'':'display: none'}}"><img id="v_img" src="" alt="your image"
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
          <div class="col-md-4">
            <div class="form-group">
              <label>Commission (%)</label>
              <input type="number" name="commission" value="{{$user->commission}}" placeholder="commission" value="0" class="form-control" min='0'>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Country</label> <span style="color:red">*</span>
              {{Form::select('country', $countries, $user->country,['class'=>'form-control countries','id'=>'countries','required'=>'required'])}}
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