  @extends('admin.layout')

@section('content')

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

              <li class="breadcrumb-item active"><?=@$page_title?></li>

            </ol>

          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

    </div>

    <!-- Main content -->

    <section class="content">

        <div class="container-fluid">



        <div class="card">

            

            <div class="card-body">

            	<div class="row">
                {{Form::open(array('url'=>'admin/filter_report','method'=>'post','enctype'=>'multipart/form-data'))}}
                
            		<div class="col-md-12">

                     <select name="vendor" id="vendor">

	                      <option value="">Select Vendor</option>

	                      <?php foreach($vendor as $v){ ?>

	                        <option <?php if(@$_POST['vendor']==$v->id){echo "selected";}?> value="<?=$v->id?>"><?=$v->first_name?> <?=$v->last_name?></option>

	                      <?php } ?>

	                  </select>

                    <select name="status" id="status">

                      <option value="">Select Status</option>

                      <option <?php if(@$_POST['status']=="pending"){echo "selected";}?> value="pending">Pending</option>

                      <option <?php if(@$_POST['status']=="ordered"){echo "selected";}?> value="ordered">Ordered</option>

                      <option <?php if(@$_POST['status']=="processing"){echo "selected";}?> value="processing">Processing</option>

                      <option <?php if(@$_POST['status']=="delivered"){echo "selected";}?> value="delivered">Delivered</option>

                      <option <?php if(@$_POST['status']=="return_complete"){echo "selected";}?> value="return_complete">Returned</option>

                      <option <?php if(@$_POST['status']=="return_pending"){echo "selected";}?> value="return_pending">Return Pending</option>

                      <option <?php if(@$_POST['status']=="cancelled"){echo "selected";}?> value="cancelled">Cancelled</option>

                      <option <?php if(@$_POST['status']=="shipped"){echo "selected";}?> value="shipped">Shipped</option>

                    </select>

                    <select name="customer" id="customer">

                      <option value="">Select Customer</option>

                      <?php foreach($customer as $cust){ ?>

                        <option <?php if(@$_POST['customer']==$cust->id){echo "selected";}?> value="<?=$cust->id?>"><?=$cust->first_name?></option>

                      <?php } ?>

                    </select>


                    <select name="deliveryboy" id="deliveryboy">

                      <option value="">Select Delivery Boy</option>

                      <?php foreach($deliveryboy as $boys){ ?>

                        <option <?php if(@$_POST['deliveryboy']==$boys->id){echo "selected";}?> value="<?=$boys->id?>"><?=$boys->first_name?></option>

                      <?php } ?>

                    </select>

                    <select name="city">

                      <option value="">Select City</option>

                      <?php foreach($cities as $city){ ?>

                        <option <?php if(@$_POST['city']==$city->id){echo "selected";}?> value="<?=$city->id?>"><?=$city->city?></option>

                      <?php } ?>

                    </select>

                  </div>
                  <div class="col-md-12">

                    <select name="area">

                      <option value="">Select Area</option>

                      <?php foreach($area as $areas){ ?>

                        <option <?php if(@$_POST['area']==$areas->id){echo "selected";}?> value="<?=$areas->id?>"><?=$areas->area?></option>

                      <?php } ?>

                    </select>

                    <select name="payment_method">

                      <option value="">Select Payment Method</option>

                      <option <?php if(@$_POST['payment_method']=="cod"){echo "selected";}?> value="cod">cod</option>

                      <option <?php if(@$_POST['payment_method']=="online"){echo "selected";}?> value="online">Online</option>

                    </select>

                    <input type="date" value="<?=@$_POST['filterDate']?>" name="filterDate">

                    <input type="Submit" name="filter" value="Filter" class="btn btn-info">
	              
                  </form>
                    <a href="{{ url('admin/report') }}">
                      <input type="button" name="reset" value="Reset" class="btn btn-secondary">
                    </a>                
                  
                    <a href="#" class="btn btn-sm btn-info export_report_vendor">Export CSV</a>
                  
              </div>
            </div>
            

              <style>

                  #example2_wrapper{overflow: scroll !important;}

              </style>

              

            <table id="example2" class="table table-bordered table-striped" >

                <thead>

                <tr>
                	<th> <div class="icheck-primary d-inline">

                        <input type="checkbox" id="ckbCheckAll">

                        <label for="ckbCheckAll"> All

                        </label>

                      </div>

                  </th>
                  
                  <th>Product Name</th>

                  <th>Order Id</th>

                  <th>Email</th>

                  <th>Price</th>

                  <th>Vendor Name</th>

                  <th>Order Date</th>

                  <th>Status</th>

                  <th>Delivery Boy</th>

                  <th>City</th>


                </tr>

                </thead>

                <tbody>

                    
					<?php 
					$i=1;
					foreach ($product_list as $key => $value) { ?>
                    

                   <tr> 

                   	<td>

                        {{$i}} 

                        <div class="icheck-primary d-inline">

                        	<input type="checkbox" name="checkname" value="<?=$value->order_id?>" id="checkboxPrimary<?=$i?>" class="checkboxall">

                        	<label for="checkboxPrimary<?=$i?>"></label>

                      	</div>

                      </td>

                      <td><?=$value->service_name?></td>

                      <td><?=$value->order_id?></td>

                      <td><?=$value->email?></td>

                      <td><?=$value->price?></td>

                      <td><?=$value->first_name?> <?=$value->last_name?></td>

                      <td><?=$value->date?></td>

                      <td><?=$value->status?></td>

                      <td><?=$value->user_id?></td>

                      <td><?=$value->city?></td>

                   </tr>

                  <?php $i++; } ?>

                </tbody>  

              </table>

              

            </div><!-- /.card-body -->

            

            

        </div>

        </div><!-- /.container-fluid -->

    </section>


   </div>



  @stop