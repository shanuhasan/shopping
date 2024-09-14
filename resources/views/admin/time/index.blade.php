  @extends('admin.layout')
@section('content')
  
  <div class="content-wrapper">

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?=@$page_title?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{url('/admin/add/time')}}">Add Time Schedule</a></li>
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

              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Start Time</th>
                  <th>EndTime</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    
                    @if($timeSchedule)
                    @foreach($timeSchedule as $time)
                    <tr>
                        <td> {{ $time->start_time }} </td>
                        <td> {{ $time->end_time }} </td>
                        <td> 
                            <a href="{{url('admin/edit/time',$time->id)}}"> Edit</a>
                            <a href="{{url('admin/delete/time',$time->id)}}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                    <@endif
                
                
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