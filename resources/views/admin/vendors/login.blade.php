<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Vendor| Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo asset('/');?>assets/bacend/dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
</head>
<body class="hold-transition login-page">
    

<div class="login-box">
  <div class="login-logo">
    	<a class="navbar-brand" href="<?php echo asset('/');?>">
			<img alt="Anbshopping" src="<?php echo asset('/');?>uploads/logo/1599827425.png" width="70">
		</a>
  </div>
  
  @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                	<button type="button" class="close" data-dismiss="alert">×</button>	
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

     {{Form::open(array('url'=>'admin/login_user','method'=>'post'))}}
        
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        

        <div class="row">
          <div class="col-8"></div>
    
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block form-control">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

   <!--<div class="social-auth-links text-center mb-3">-->
   <!--     <p>- OR -</p>-->
   <!--     <a href="#" class="btn btn-block btn-primary">-->
   <!--       <i class="fab fa-facebook mr-2"></i> Sign in using Facebook-->
   <!--     </a>-->
   <!--     <a href="#" class="btn btn-block btn-danger">-->
   <!--       <i class="fab fa-google-plus mr-2"></i> Sign in using Google+-->
   <!--     </a>-->
   <!-- </div> -->
   

     <p class="mb-1">
        <a href="{{url('vendor/forgot-password')}}">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="{{url('vendor/register')}}" class="text-center">Register a new membership</a>
      </p>
      
    </div>
  
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo asset('/');?>assets/bacend/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo asset('/');?>assets/bacend/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('/');?>assets/bacend/plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo asset('/');?>assets/bacend/dist/js/adminlte.min.js"></script>
@if ($message = Session::get('error'))

<script type="text/javascript">
  var msg='{{ $message }}';
  toastr.error(msg);
</script>
@endif


@if ($message = Session::get('success'))

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
    $('#loginform').submit(function(e){
      e.preventDefault();
     var formdata=new FormData(this);
     $.ajax({
          url:'admin/auth/login',
          type:'post',
          data:formdata,
          contentType: false,
          cache: false,
          processData:false,
          success:function(data){
            if(data=='error'){
              toastr.error('Required Email and Password...');
            }else if(data=='success'){
              toastr.success('Login Successfully');
              setTimeout(function(){
                location.reload();
              },2000);
            }else{
              toastr.error('Wrong Email and Password...');
            }
          }
     });
    });
</script>

<!-- <script type="text/javascript">
  var msg='';
  toastr.success(msg);
</script> -->

</body>
</html>
