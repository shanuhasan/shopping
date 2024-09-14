@extends('admin.layout')
@section('content')

<?php $slugCheck = Sentinel::getUser()->roles()->first()->slug; ?>
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
              <li class="breadcrumb-value"><a href="{{url('/admin')}}">Home</a></li>
                <?php if($slugCheck =='vendor'){?>
                    <li class="breadcrumb-value"><a href="{{url('/vendor/review')}}">Reviews</a></li>
                <?php }else{?>
                    <li class="breadcrumb-value"><a href="{{url('/admin/review')}}">Reviews</a></li>
                <?php }?>
              <li class="breadcrumb-value active"><?=@$page_title?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
            
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr no.</th>
                  <th>Review </th>
                  <th>Rating </th>
                  <th> User </th>
                </tr>
                </thead>
                
                <tbody>
                @foreach($review as $key => $rev)    
                    <tr>
                        <td> {{$key + 1 }} </td>
                        <td> {{ @$rev['review'] }}</td>
                        <td> {{ @$rev['rating'] }}</td>
                        <td> {{ @$rev['username'] }} </td>
                    </tr>
                  @endforeach  
                </tbody>
                         
              </table>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @stop