<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Vendor| Log in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" rel="stylesheet" />
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
		<!-- Bootstrap  -->
		<link rel="stylesheet" href="<?php echo asset('/');?>front/css/bootstrap.min.css">

		<!-- Theme style  -->
		<link rel="stylesheet" href="<?php echo asset('/');?>front/css/style.css">
		 <link rel="stylesheet" href="<?php echo asset('/');?>assets/bacend/plugins/toastr/toastr.min.css">
  
  
</head>
<body>
    
    
    <header class="header" id="header">
		    <div class="container">
		        <nav class="navbar navbar-expand-lg justify-content-between">
					<a class="navbar-brand" href="<?php echo asset('/');?>">
						<img alt="Anbshopping" src="<?php echo asset('/');?>uploads/logo/1599827425.png">
					</a><a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle colorlib-nav-white"><i></i></a>
					<div class="collapse navbar-collapse primary-menu">
						<ul class="navbar-nav">

							<li class="nav-item">
								<a class="nav-link" href="{{url('vendor/login')}}">Login as vendor</a>
							</li>
					
						</ul>
					</div>
				</nav>
		    </div>
		</header>

  
    <div class="container"><br>
    
               @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                	<button type="button" class="close" data-dismiss="alert">×</button>	
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                
                
                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                	<button type="button" class="close" data-dismiss="alert">×</button>	
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                        
          {{Form::open(array('url'=>'vendor/vendor_store','method'=>'post','enctype'=>'multipart/form-data','id'=>'customer_form'))}}
          
          <div class="row" id="vendor_div_b">
              
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>First name </label> <span style="color:red">*</span>
              <input type="text" name="first_name" value="" required id="first_name" class="form-control" placeholder="Contact person Name ">
            </div>
          </div>
          
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Last name</label>
              <input type="text" name="last_name" value="" id="last_name" class="form-control" placeholder="Contact person Name">
            </div>
          </div>
          
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Name of firm</label>
              <input type="text" name="name_firm" value="" class="form-control" placeholder="Name of firm">
            </div>
          </div>
          
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Owner name</label>
              <input type="text" name="owner_name" value="" class="form-control" placeholder="Owner name">
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
              <input type="password" name="password" id="password" class="form-control"
                placeholder="Password">
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Phone</label>
              <input type="tel" name="phone" value="" id="phone" class="form-control" placeholder="Phone">
            </div>
          </div>
          
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Address 1</label> <span style="color:red">*</span>
              <input type="text" name="line_1" required value="" placeholder="Address" class="form-control">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Address 2</label>
              <input type="text" name="line_2" value="" placeholder="Address 2 (optional)" class="form-control">
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
              <label>Zip Code</label>
              <input type="text" name="zip_code" value="" placeholder="Zip code (optional)" class="form-control">
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="form-group">
              <label>GST No</label>
              <input type="text" name="gst_no" placeholder="gst no"  class="form-control">
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="form-group">
              <label>Profile Upload</label>
              <input type="file" name="image" id="view_img" class="form-control">
              <div class="viewimg" style="display: none"><img id="v_img" src="" alt="your image"
                  style="width: 22%; padding: 1%;"></div>
            </div>
          </div>
          
          <?php if(Sentinel::check()){ ?>
          
          <div class="col-md-4">
            <div class="form-group">
              <label>Commission</label>
              <input type="number" name="commission" id="commission" class="form-control">
            </div>
          </div>
          
          <?php }?>
         
           </div>
         
           <div class="clearfix"></div>
           <h4> Account Details </h4><hr>
           <div class="row">
          
              <!--<div class="col-md-4">-->
              <!--  <div class="form-group">-->
              <!--    <label>Account holder name</label>-->
              <!--    <input type="text" name="holder_name" placeholder=""  class="form-control">-->
              <!--  </div>-->
              <!--</div>-->
              
              <div class="col-md-3">
                <div class="form-group">
                  <label>Bank Name</label>
                  <input type="text" name="bank_name" placeholder=""  class="form-control">
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                  <label>Branch Name</label>
                  <input type="text" name="branch_name" placeholder=""  class="form-control">
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                  <label>Account no.</label>
                  <input type="number" name="account_no" placeholder=""  class="form-control">
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                  <label>IFSC Code</label>
                  <input type="text" name="ifsc_code" placeholder=""  class="form-control">
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                  <label>Cancel cheque UPLOAD </label>
                  <input type="file" name="cencel_check" placeholder=""  class="form-control">
                </div>
              </div>
              
          </div><!--inner row-->
          
          <div class="clearfix"></div>
           <h4> KYC Details </h4><hr>
           <div class="row">
               
               <div class="col-md-6">
                <div class="form-group">
                  <label>Addhar Card UPLOAD</label>
                  <input type="file" name="addhar_card" placeholder=""  class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pan Card UPLOAD</label>
                  <input type="file" name="pan_card" placeholder=""  class="form-control">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Trade licenses document upload</label>
                  <input type="file" name="trade_license_document" placeholder=""  class="form-control">
                </div>
              </div>
              
              <!--<div class="col-md-6">-->
              <!--  <div class="form-group">-->
              <!--    <label>Vendor policy agreement document upload</label>-->
              <!--    <input type="file" name="vendor_policy_document" placeholder=""  class="form-control">-->
              <!--  </div>-->
              <!--</div>-->
              
              <div class="col-md-3">
                <div class="form-group">
                  <label>Gst no document UPLOAD </label>
                  <input type="file" name="gst_document" placeholder=""  class="form-control">
                </div>
              </div>
            
         </div><!--inner row--> 
         
         <div class="">
             
             <input type="checkbox" name="term_and_condition" value="1" required>
             <lebel> <a href="{{url('/terms-condition')}}" target="_blank">terms and conditions </a></lebel>
         </div>
      
          
          <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-sm btn-info">Save</button>
          </div>
          <div class="clearfix"></div>
         
        
        {{Form::close()}}


</div><br><br>


<!-- jQuery -->
<script src="<?php echo asset('/');?>assets/bacend/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo asset('/');?>assets/bacend/plugins/toastr/toastr.min.js"></script>

@if ($message = Session::get('error'))

<script type="text/javascript">
  var msg='{{ $message }}';
  toastr.error(msg);
</script>
@endif


@if($message = Session::get('success'))
<script type="text/javascript">
  var msg='{{ $message }}';
  toastr.success(msg);
</script>
@endif

<script type="text/javascript">

   $('.toastrDefaultSuccess').click(function() {
      toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultError').click(function() {
      toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    
</script>

<script type="text/javascript">

    $('#countries').on("change",function(){
      $("#states > option").remove();
      var country_id=$(this).val();
      
      $.ajax({
        url:'{{url("get_states")}}',
        type:'get',
        data:{'country_id':country_id},
        success:function(states){ 
            
          var opt = $('<option />');
              opt.val("");
              opt.text("Select State");
              $('#states').append(opt);
          $.each(states, function (id, state) {
              var opt = $('<option />');
              opt.val(state.id);
              opt.text(state.name);
              $('#states').append(opt);
          });
          if ($('#states').attr("data-val")) {
            $('#states').val($('#states').attr("data-val")).trigger("change");
          }
         // 
        }
      });
   });

   $('#states').on("change",function(){
      $("#cities > option").remove();
      var state_id=$(this).val();
      $.ajax({
        url:'{{url("get_cities")}}',
        type:'get',
        data:{'state_id':state_id},
        success:function(states){ 
          var opt = $('<option />');
          opt.val('');
          opt.text("Select City");
          $('#cities').append(opt);
          $.each(states, function (id, state) {
              var opt = $('<option />');
              opt.val(state.id);
              opt.text(state.name);
              $('#cities').append(opt);
          });

          if ($('#cities').attr("data-val")) {
            $('#cities').val($('#cities').attr("data-val"));
          }
        }
      });
   });
 </script> 


</body>
</html>

 
