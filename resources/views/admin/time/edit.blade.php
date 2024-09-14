  @extends('admin.layout')
@section('content')
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
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{url('admin/time_index')}}">List Time Schedule</a></li>
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
        <div class="row">
          <div class="col-md-12">
            <div class="card">
            
            <!-- /.card-header -->
            <div class="card-body">

            <h3> Add time Schedule</h3>
            
            <form action="{{url('admin/time/update')}}" method="post">
                
                @csrf
                
                <input type="hidden" name="update_id" value="{{ @$edit->id}}">
                <div class="form-group">
                    <lebel> Start Time</lebel>
                    <input type="time" name="start_time" class="form-control" value="{{ @$edit->start_time}}">
                </div>
                
                <div class="form-group">
                    <lebel> End Time</lebel>
                    <input type="time" name="end_time" class="form-control" value="{{ @$edit->end_time}}">
                </div>
                
                <div class="form-group">
                
                    <input type="submit" value="Update time">
                </div>
                
                
            </form>
            
            
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