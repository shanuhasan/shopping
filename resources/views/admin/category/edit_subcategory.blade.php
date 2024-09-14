  @extends('admin.layouts.app')
  @section('title', 'Edit Sub Category')
  @section('category_open', 'menu-open')
  @section('subcategory_active', 'active')
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
                              <li class="breadcrumb-item"><a href="">Home</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('admin.subcategory') }}">Sub Category
                                      List</a></li>
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
                  <!-- Small boxes (Stat box) -->
                  <form action="{{ route('admin.subcategory.update') }}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                          <div class="row" style="padding:2%">
                              <div class="col-lg-3 col-6">
                                  <input type="hidden" value="<?= $edit_data->id ?>" name="update_id">
                                  <label>Main Category</label>
                                  <select name="parent_category" required class="form-control">
                                      <option value="">Select Category</option>
                                      @foreach ($category as $item)
                                          <option {{ $item->id == $edit_data->parent_id ? 'selected' : '' }}
                                              value="<?= $item->id ?>"><?= $item->category_name ?>
                                          </option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="col-lg-3 col-6">
                                  <label>Category Name</label> <span style="color:red">*</span>
                                  <input type="text" name="category_name" value="<?= @$edit_data->category_name ?>"
                                      placeholder="Category  Name" class="form-control">
                                  @error('category_name')
                                      <div style="color: red;">
                                          {{ $message }}
                                      </div>
                                  @enderror
                              </div>
                              <div class="col-lg-3 col-6">
                                  <label>Meta Title</label>
                                  <input type="text" value="<?= @$edit_data->meta_title ?>" name="meta_title"
                                      placeholder="Meta Title" class="form-control">
                              </div>

                              <div class="col-lg-3 col-6">
                                  <label>Meta Keyword</label>
                                  <input type="text" name="meta_keyword" value="<?= @$edit_data->meta_keyword ?>"
                                      placeholder="Meta Keyword" class="form-control">
                              </div>
                              <!-- ./col -->
                              <div class="col-lg-12 col-12">
                                  <label>Meta Description</label>
                                  <input type="text" name="meta_discription" value="<?= @$edit_data->meta_discription ?>"
                                      placeholder="Meta Description" class="form-control">
                              </div>

                              <div class="col-lg-12">
                                  <label>Description</label>
                                  <textarea class="form-control" name="description"><?= @$edit_data->description ?></textarea>
                              </div>

                              <div class="col-md-6">
                                  <input type="hidden" name="image_id" id="image_id" value="">
                                  <div class="mb-3">
                                      <label for="image">Image</label>
                                      <div id="image" class="dropzone dz-clickable">
                                          <div class="dz-message needsclick">
                                              <br>Drop files here or click to upload.<br><br>
                                          </div>
                                      </div>
                                  </div>
                                  @if (!empty($edit_data->media_id))
                                      <div>
                                          <img width="200"
                                              src="{{ asset('uploads/category/thumb/' . $edit_data->media_id) }}"
                                              alt="">
                                      </div>
                                  @endif

                              </div>
                              <div class="col-lg-12" style="margin-top: 2%">
                                  <input type="submit" value="submit" class="btn btn-primary" style="float: right;">
                              </div>
                              <!-- ./col -->
                          </div>
                      </div>
                  </form>
              </div><!-- /.container-fluid -->
          </section>
          <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

  @stop

  @section('script')
      <script>
          Dropzone.autoDiscover = false;
          const dropzone = $("#image").dropzone({
              init: function() {
                  this.on('addedfile', function(file) {
                      if (this.files.length > 1) {
                          this.removeFile(this.files[0]);
                      }
                  });
              },
              url: "{{ route('admin.media.create') }}",
              maxFiles: 1,
              paramName: 'image',
              addRemoveLinks: true,
              acceptedFiles: "image/jpeg,image/png,image/gif",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(file, response) {
                  $("#image_id").val(response.image_id);
                  //console.log(response)
              }
          });
      </script>
  @endsection
