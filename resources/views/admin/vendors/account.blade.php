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
              <!--<li class="breadcrumb-value"><a href="{{url('/vendor/add/stock')}}">Add Stock</a></li>-->
              <li class="breadcrumb-value active"><?=@$page_title?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <?php
        $salePrice = 0;
        $commissionPrice = 0;
        if($commissions){
        foreach($commissions as $com){
            $salePrice += $com->sale_price;
            $commissionPrice += $com->commission_price;
        }        
        }
    ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          
        <div class="row">
         <div class="col-lg-3 col-6">
            <div class="small-box bg-default">
              <div class="inner">
                <h3>₹{{$salePrice}}</h3>
                <p><b>Total sale amount</b></p>
              </div>
              <div class="icon"></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-default">
              <div class="inner">
                <h3>₹{{$commissionPrice}}</h3>
                <p><b>Total commission amount</b></p>
              </div>
              <div class="icon"></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-default">
              <div class="inner">
                <h3>₹{{$salePrice-$commissionPrice}}</h3>
                <p><b>Total balance amount</b></p>
              </div>
              <div class="icon"></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-default">
              <div class="inner">
                <h3> <i class="fa fa-money" aria-hidden="true"></i></h3>
                <p><b>Withdrawal Request</b></p>
              </div>
              <div class="icon"></div>
            </div>
        </div>
        </div>
          
            <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>Sr no.</th>
                        <th>Sale Price</th>
                        <th>Commission</th>
                        <th>Created Date</th>
                    </tr>
                    
                @if($commissions)  
                @foreach($commissions as $index => $commission)  
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $commission->sale_price }}</td>
                        <td>{{ $commission->commission_price }}</td>
                        <td>{{ $commission->created_at }}</td>
                    </tr>
                @endforeach
                @endif
                </table>          
            </div>
            </div>
            
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @stop
  

 

