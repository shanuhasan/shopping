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
                      <li class="breadcrumb-item"><a href="">Home</a></li>
                      <li class="breadcrumb-item"><a href="{{url('admin/order')}}">Order</a></li>
                      <li class="breadcrumb-item active"><?=@$page_title?></li>
                    <?php $slug=Sentinel::getUser()->roles()->first()->slug;

                    if($slug=='super_admin'){ ?>
                        <li class="breadcrumb-item"> <a target="_blank" href="{{url('admin/print_invoice/')}}/<?=$order->id?>">Print invoice</a></li>
                        <li class="breadcrumb-item">  <a target="_blank" href="{{url('admin/print_invoice_pdf/')}}/<?=$order->id?>">Print invoice in PDF</a></li>
                    <?php } ?>
                        <li> <a href="#" class="print">Print</a></li>
                  </ol>
                  
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-4 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <small class="float-right">Date: {!!date("d/m/Y",strtotime($order->date))!!}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From
                  <address>
                    <strong>{{isset($settings['site_name'])?$settings['site_name']:NULL}}</strong><br>
                    {!!isset($settings['address'])?$settings['address']:NULL!!}<br>
                    Phone: {{isset($settings['phone'])?$settings['phone']:NULL}}<br>
                    Email: {{isset($settings['email'])?$settings['email']:NULL}}
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To
                  <address>
                    <strong>{{$order->user_name}}</strong><br>
                    {!!$order->address!!}
                    Phone: {{$order->phone}}<br>
                    Email: {{$order->email}}<br>
                    Name: {{$order->name}}<br>
                    Address: {!! $order->address !!}<br>
                    Address2: {{$order->address2}}
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Invoice {{$order->order_id}}</b><br>
                  <br>
                  <b>Order ID:</b> {{$order->order_id}}<br>
                  <b>Order Status:</b> {{ucfirst($order->status)}}<br>
                  <b>Payment Status:</b> {{ucfirst($order->payment_status)}}<br>
                  <b>Payment Method:</b> {{ucfirst($order->payment_method)}}<br>
                  <b>Date time:</b> {{ucfirst($order->created_at)}} <br>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
                <?php
                        $total_weight_offer=array();
                        $total_pcs_offer=array();
                ?>
             
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Sr no</th>
                      <th>Product</th>
                      <th>Price</th>
                      <th>Quatity</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                      @php
                          $i=1;
                          $subtotal=0;
                          $total_weight=array();
                          $total_pcs=array();
                      @endphp
                      @forelse ($items as $item)
                      @php
                          $subtotal+=$item->total;
                      @endphp
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$item->service_name}}</td>
                        <td>INR {{ $item->price }}</td>
                        <td>{{ $item->quantity }} </td>
                     
                        <td>INR {{ $item->total }}</td>
                      </tr>
                      @php
                          $i++;
                      @endphp
                      @empty
                          
                      @endforelse
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">

                <div class="col-6">

                  <div class="table-responsive">
                    <table class="table">

                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <th>INR {{ $subtotal }}</th>
                      </tr>
 
                      <tr>
                        <th> Discount:</th>
                        <th>{{ $order->order_discount?$order->order_discount:0.00 }}</th>
                      </tr>
                      <tr>
                        <th>Shipping:</th>
                        <th>INR {{ $order->shipping }}</th>
                      </tr>
                      
                      <tr>
                        <th>Total:</th>
                        <th><?php $total= $subtotal+$order->shipping-$order->order_discount; 
                        echo number_format($order->grand_total,2); ?></th>
                      </tr>
                    </table>
                  </div>
                  
                </div>
                
                
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <!--div class="row no-print">
                <div class="col-12">
                  <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                  <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                    Payment
                  </button>
                  <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button>
                </div-->
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@stop