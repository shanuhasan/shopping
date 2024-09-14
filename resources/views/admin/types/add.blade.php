@extends('admin.layout')
@section('content')
<style>
  .card {
    padding: 20px;
  }

  .timig .select2-container.form-control.timing {
    width: 46%;
    float: left;
    margin-right: 2%;
  }
</style>

<?php 
$discounts=array(''=>'Select Discount');
$hours=array();
$minutes=array();

for($i = 0; $i <= 100; $i += 5){
  $discounts[$i]=$i."%";
}
  

for($i = 0; $i <= 23; $i ++){
  $j=$i;
  if($i<10){
    $j="0".$i;
  }
  $hours[$i]=$j;
}
  

for($i = 0; $i <= 59; $i ++){
  $j=$i;
  if($i<10){
    $j="0".$i;
  }
  $minutes[$i]=$j;
}
  

$week_days=array("monday"=>"Monday","tuesday"=>"Tuesday","wednesday"=>"Wednesday","thursday"=>"Thursday","friday"=>"Friday","saturday"=>"Saturday","sunday"=>"Sunday");

?>
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
            <li class="breadcrumb-item"><a href="{{url('admin/types')}}">Types</a></li>
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
      {{Form::open(array('url'=>'admin/types/create','method'=>'post','enctype'=>'multipart/form-data'))}}

      <div class="card">
        <div class="row">
         
          <div class="col-lg-4 col-6">
            <div class="form-group">
              <label>Name</label> <span style="color:red">*</span>
              <input type="text" name="name" value="<?=@$_POST['name']?>" placeholder="Name" class="form-control">
            </div>
          </div>
        </div>
       
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-sm btn-info">Save</button>
            </div>
            </div>
        {{Form::close()}}
      </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@stop