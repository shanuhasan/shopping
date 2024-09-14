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
              <li class="breadcrumb-value"><a href="{{url('/admin')}}">Home</a></li>
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
            <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
    
              <form action="">
                <div class="row">
                    
                    <!--<input type="hidden" name="start" id="start">-->
                    <!--<input type="hidden" name="end" id="end">-->
                    
                <div class="col-sm-3">           
                  <div class="form-group">
                  <lebel>Select Product </lebel><br>
                  <select name="p" class="form-control" required>
                      <option value="">select product</option>
                      <option value="0">All Product</option>
                      @foreach($products as $product)
                        <option value="{{ $product->id }}"> {{ $product->service_name }} </option>
                      @endforeach
                  </select>
                  </div>
                  </div> 
                 
                  
                  <div class="col-sm-3">
                  <div class="form-group">
                  <lebel> Select Type </lebel><br>
                  <select name="select_type" class="form-control">
                      <!--<option value="">select type</option>-->
                      <option value="sale">Sale</option>
                  </select>
                  </div>
                  </div>
                  
                  <div class="col-sm-3">
                  <div class="form-group">
                  <lebel> Date Range </lebel><br>
                  <input type="text" name="daterange" class="form-control" required autocomplete="off">
                  <!--<select name="date_range">-->
                      <!--<option value="">select date range</option>-->
                  <!--    <option value="sale">Sale</option>-->
                  <!--</select>-->
                  </div>
                  </div>
        
                      
                <div class="col-sm-3">           
                  <div class="form-group"><br>
                    <input type="submit">
                  </div>
                  </div>   
                      
                </div>  
              </form>
              
              
              @if(count($filter_data)> 0)
              
              <table class="table table-striped">
                  <tr>
                      <th>Sr no.</th>
                      <th>Product Name</th>
                      <th>Total Cost</th>
                  </tr>
                  
                 @foreach($filter_data as $key => $filter) 
                   <tr>
                      <td> {{$key+1 }} </td>
                      <td> {{ $filter['name'] }}  </td>
                      <td> {{ $filter['total_price'] }} </td>
                  </tr>
                 @endforeach 
                  
              </table>
              @else
              
              <h4> Record Not Found </h4>
              
              @endif
              
                        
            </div>
            <!-- /.card-body -->
      </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @stop
  
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
 
 <script>
 
 $(function() {

  $('input[name="daterange"]').daterangepicker({
      autoUpdateInput: false,
      timePicker: true,
      locale: {
          cancelLabel: 'Clear',
          format: 'YYYY-MM-DD hh:mm:ss'
      }
  });

  $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD hh:mm:ss') + ' | ' + picker.endDate.format('YYYY-MM-DD hh:mm:ss'));
  });

  $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

});
 
 
// $(function() {
//   $('input[name="daterange"]').daterangepicker({
//     opens: 'left',
//     "autoApply": true,
//     "showDropdowns": true,
//     timePicker: true,
//     timePicker24Hour: true,
//     timePickerIncrement: 30,
//     locale: {
//           format: 'YYYY-MM-DD hh:mm:ss'
//         },
//   }, function(start, end, label) {
//       $('#start').val(start.format('YYYY-MM-DD hh:mm:ss'));
//       $('#end').val(end.format('YYYY-MM-DD hh:mm:ss'));
//     console.log("A new date selection was made: " + start.format('YYYY-MM-DD hh:mm:ss') + ' to ' + end.format('YYYY-MM-DD hh:mm:ss'));
//   });
// });
</script>
