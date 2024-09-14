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
                
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                	<button type="button" class="close" data-dismiss="alert">×</button>	
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Change password</p>

     
     <form action="{{ url('vendor/password-reset', [$email,$token]) }}" method="post">
         @csrf
        
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        
        <div class="input-group mb-3">
          <input type="password" name="new_password" class="form-control" placeholder="New Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- /.col -->
          <div class="col-6"></div>
          <div class="col-6" align="right">
            <button type="submit" class="btn btn-primary btn-block form-control"> {{ $title}} </button>
          </div>
          <!-- /.col -->
        </div>
      </form>

   

     <p class="mb-1">
        <a href="{{url('vendor/login')}}">Login</a>
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
