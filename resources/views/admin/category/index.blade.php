  @extends('admin.layouts.app')
  @section('title', 'Categories')
  @section('category_open', 'menu-open')
  @section('category_active', 'active')
  @section('content')
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <div class="content-header">
              <div class="container-fluid">
                  <div class="row mb-2">
                      <div class="col-sm-6">
                          <h1 class="m-0 text-dark"><?= @$page_title ?></h1>
                      </div><!-- /.col -->
                      <div class="col-sm-6">
                          <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('admin.category.create') }}">Add Category</a>
                              </li>
                              <li class="breadcrumb-item active"><?= @$page_title ?></li>
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
                                              <th>Category Name</th>
                                              <th>Image</th>
                                              <th>Status</th>
                                              <th>Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>

                                          @foreach ($category as $item)
                                              @if ($item->parent_id == '0')
                                                  <tr>
                                                      <td><?= $item->category_name ?></td>
                                                      <td>
                                                          <?php if($item->media_id){ ?>
                                                          <img src="<?php echo asset('/'); ?>uploads/category/thumb/<?= $item->media_id ?>"
                                                              width="60px">
                                                          <?php } ?>
                                                      </td>

                                                      <td>
                                                          <?php if($item->status=='1'){ ?>
                                                          <a href="{{ route('admin.category.active', $item->id) }}"
                                                              class="btn-success btn btn-sm">Active</a>
                                                          <?php }else{ ?>
                                                          <a href="{{ route('admin.category.deactive', $item->id) }}"
                                                              class="btn-danger btn btn-sm">Deactive</a>
                                                          <?php } ?>
                                                      </td>
                                                      <td>
                                                          <a href="{{ route('admin.category.edit', $item->id) }}"
                                                              class="btn btn-primary btn-sm"><i
                                                                  class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                          </a>
                                                          <a onclick="return confirm('Are you sure delete this record ?..')"
                                                              href="{{ route('admin.category.delete', $item->id) }}"
                                                              class="btn btn-danger btn-sm"><i class="fa fa-trash"
                                                                  aria-hidden="true"></i></a>
                                                      </td>
                                                  </tr>
                                              @endif
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
  @endsection
