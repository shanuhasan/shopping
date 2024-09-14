  @extends('admin.layouts.app')
  @section('title', 'Add Category')
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
                              <li class="breadcrumb-item"><a href="">Home</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('admin.category') }}">Category List</a></li>
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
                  <form action="{{ route('admin.category.store') }}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                          <div class="row" style="padding:2%">
                              <div class="col-lg-4 col-6">
                                  <label>Category Name</label> <span style="color:red">*</span>
                                  <input type="text" name="category_name" value="<?= @$_POST['category_name'] ?>"
                                      placeholder="Category Name" class="form-control">
                                  @error('category_name')
                                      <div style="color: red;">
                                          {{ $message }}
                                      </div>
                                  @enderror
                              </div>
                              <div class="col-lg-4 col-6">
                                  <label>Meta Title</label>
                                  <input type="text" value="<?= @$_POST['meta_title'] ?>" name="meta_title"
                                      placeholder="Meta Title" class="form-control">
                              </div>
                              <div class="col-lg-4 col-6">
                                  <label>Meta Keyword</label>
                                  <input type="text" name="meta_keyword" value="<?= @$_POST['meta_keyword'] ?>"
                                      placeholder="Meta Keyword" class="form-control">
                              </div>
                              <!-- ./col -->
                              <div class="col-lg-12 col-12">
                                  <label>Meta Description</label>
                                  <input type="text" name="meta_discription" value="<?= @$_POST['meta_discription'] ?>"
                                      placeholder="Meta Description" class="form-control">
                              </div>

                              <div class="col-lg-12">
                                  <label>Description</label>
                                  <textarea class="form-control" name="description"><?= @$_POST['description'] ?></textarea>
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
