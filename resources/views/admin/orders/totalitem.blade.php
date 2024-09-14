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
                  <div>
                    <address>
                    <strong>{{isset($settings['site_name'])?$settings['site_name']:NULL}}</strong><br>
                    {!!isset($settings['address'])?$settings['address']:NULL!!}
                    Phone: {{isset($settings['phone'])?$settings['phone']:NULL}}<br>
                    Email: {{isset($settings['email'])?$settings['email']:NULL}}
                  </address>
                   
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              
                <div class="row">
                    
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Sr no</th>
                      <th>Product name</th>
                      <th>Title</th>
                      <th>Description</th>
                      
                     
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_weight_offer=array();
                        $total_pcs_offer=array();
                        $i=1; foreach($offer_item as $values){ ?>
                     <tr>
                         <td><?=$i?></td>
                         <td><?=$values->service_name?>
                         
                         <?php if($values->unit=='gm' || $values->unit=='kg'){ ?>
                       <?php if($values->unit=='gm'){ ?>
                       <?php
                       $total_weight_offer[]= number_format($values->unit_value)/1000;
                        ?>
                       <?php }else{ ?>
                       <?php
                       $total_weight_offer[]= number_format($values->unit_value);
                        ?>
                       <?php } ?>
                     
                       <?php }else{ ?>
                       <?php 
                       $total_pcs_offer[]=number_format($values->unit_value);
                       ?>
                      
                       <?php } ?>
                         </td>
                         <td><?=$values->title?></td>
                         <td><?=$values->description?></td>
                         
                     </tr>
                     <?php $i++; } ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
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
                      <th>Total weight</th>
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
                      @forelse ($order_item as $item)
                      @php
                          $subtotal+=$item->total;
                      @endphp
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$item->service_name}}</td>
                        <td>INR {{number_format((float)$item->price, 2, '.', '')}}</td>
                      <td>{{number_format((float)$item->quantity, 2, '.', '')}}  x {{$item->unit_value}}{{$item->unit}}</td>
                       <td>
                       <?php if($item->unit=='gm' || $item->unit=='kg'){ ?>
                       <?php if($item->unit=='gm'){ ?>
                       <?php
                       $total_weight[]=number_format($item->quantity)* number_format($item->unit_value)/1000;
                       echo (number_format($item->quantity)* number_format($item->unit_value)/1000 .' kg');  ?>
                       <?php }else{ ?>
                       <?php
                       $total_weight[]=number_format($item->quantity)* number_format($item->unit_value);
                       echo (number_format($item->quantity)* number_format($item->unit_value).' '. $item->unit);  ?>
                       <?php } ?>
                     
                       <?php }else{ ?>
                       <?php 
                       $total_pcs[]=number_format($item->quantity);
                       ?>
                       {{number_format((float)$item->quantity, 2, '.', '')}} {{$item->unit}}
                       <?php } ?>
                       </td>
                        <td>INR {{number_format((float)$item->total, 2, '.', '')}}</td>
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
                <!-- accepted payments column -->
                <div class="col-6">
                 
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <!--p class="lead">Amount Due 2/22/2014</p-->

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Total Weight:</th>
                        <th><?=array_sum($total_weight) + array_sum($total_weight_offer)?> Kg</th>
                      </tr>
                      <tr>
                        <th style="width:50%">Total PCS:</th>
                        <th><?=array_sum($total_pcs) + array_sum($total_pcs_offer)?> PCS</th>
                      </tr>
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <th>INR {{number_format((float)$subtotal, 2, '.', '')}}</th>
                      </tr>
                      <!--tr>
                        <th>Tax (9.3%)</th>
                        <td>0</td>
                      </tr-->
                     
                    </table>
                  </div>
                   
                 
                </div>
                
              
              </div>
            
              </div>
           
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
      
    </section>
   
    <!-- /.content -->
  </div>
 
@stop
