  @extends('admin.layout')@section('content')  <!-- Content Wrapper. Contains page content -->  <div class="content-wrapper">    <!-- Content Header (Page header) -->    <div class="content-header">      <div class="container-fluid">        <div class="row mb-2">          <div class="col-sm-6">            <h1 class="m-0 text-dark"><?=@$page_title?></h1>          </div><!-- /.col -->          <div class="col-sm-6">            <ol class="breadcrumb float-sm-right">              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>              <li class="breadcrumb-item active"><?=@$page_title?></li>            </ol>          </div><!-- /.col -->        </div><!-- /.row -->      </div><!-- /.container-fluid -->    </div>    <!-- /.content-header -->    <!-- Main content -->    <section class="content">      <div class="container-fluid">        <div class="row">          <div class="col-md-12">            <div class="card">                        <!-- /.card-header -->            <div class="card-body">              <div class="row">                <div class="col-md-12 text-right">                  <a href="{{url('admin/offers/create')}}" class="btn btn-sm btn-info">Add Offer Product</a>                </div>              </div>              <br>              <table id="example1" class="table table-bordered table-striped">                <thead>                <tr>                  <th>Product Name</th>                  <th>Value</th>                  <th>Status</th>                  <th>Action</th>                </tr>                </thead>                <tbody>                    @forelse ($list as $item)                    <tr>                      <td>{{$item->service_name}}</td>                      <td>{{$item->value}}</td>                                           <td>                        <a href="{{url('admin/offers/status')}}/<?=$item->id?>" class="btn-{!!$item->status==1?'success':'danger'!!} btn btn-sm">{!!$item->status==1?'Active':'Deactive'!!}</a>                                              </td>                                           <td>                        <!--a href="{{url('admin/banners/view')}}/<?=$item->id?>" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a--> <a href="{{url('admin/offers/edit')}}/<?=$item->id?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a onclick="return confirm('Are you sure delete this record ?..')" href="{{url('admin/offers/delete')}}/<?=$item->id?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i>                      </td>                    </tr>                    @empty                                            @endforelse                                </tbody>                             </table>            </div>            <!-- /.card-body -->          </div>        </div>      </div>      </div><!-- /.container-fluid -->    </section>    <!-- /.content -->  </div>  <!-- /.content-wrapper -->  @stop