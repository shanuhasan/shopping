<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | SHOPPING</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
        href="{{ asset('admin-assets/bacend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('admin-assets/bacend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="{{ asset('admin-assets/bacend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/dropzone/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/select2/css/select2.css') }}">
    <!-- Google Font: Source Sans Pro -->

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

        body {
            font-family: 'Roboto', sans-serif !important;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            background-color: #fff;
        }

        .card {
            padding: 0px;
        }

        p.error {
            color: #ff0000;
        }

        .profile-user-img {
            border: 2px solid #adb5bd;
            margin: 0 auto;
            padding: 2px !important;
            width: 80px !important;
            height: 80px !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <?php 
                    $role = Auth::user()->role_id;
                    if($role == App\Models\User::VENDOR):
                ?>
                <li class="nav-item d-none d-sm-inline-block">

                    <a href="{{ url('vendor/account') }}" class="nav-link"> <i class='fas fa-wallet'></i> wallet</a>
                </li>

                <?php endif; ?>

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('admin/pos') }}" class="nav-link">Pos</a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('admin.layouts.sidebar')


        <div id="content">
            @yield('content')
        </div>


        <footer class="main-footer">
            <strong>Copyright &copy; 2019 <a href="#">Admin</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.0.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('admin-assets/bacend/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('admin-assets/bacend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin-assets/bacend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


    <script src="{{ asset('admin-assets/bacend/plugins/select2/js/select2.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('admin-assets/bacend/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('admin-assets/bacend/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('admin-assets/bacend/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bacend/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('admin-assets/bacend/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('admin-assets/bacend/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bacend/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('admin-assets/bacend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
    </script>
    <!-- Summernote -->
    <script src="{{ asset('admin-assets/bacend/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('admin-assets/bacend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bacend/plugins/toastr/toastr.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin-assets/bacend/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('admin-assets/bacend/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('admin-assets/bacend/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bacend/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('admin-assets/bacend/dist/js/pages/dashboard.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('admin-assets/bacend/dist/js/demo.js') }}"></script>
    <!-- <script src="{{ asset('admin-assets/ck/editor.js') }}"></script> -->
    <script src="{{ asset('admin-assets/custom.js') }}"></script>
    <div class="modal" id="checkdeliveryboymodal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Order Details</h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Date time</th>
                            <th>Status</th>
                        </tr>
                        <tbody class="showdeliveryboydetails">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('admin-assets/bacend/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <style>
        .toast-message {
            font-size: 15px;
        }
    </style>
    <script>
        site_url = "{{ url('/') }}/";
        asset = "{{ asset('/') }}/";
        //$('select, .select').select2({ minimumResultsForSearch: 7 });
    </script>

    @yield('script')

    @if ($message = Session::get('error'))
        <script type="text/javascript">
            var msg = '{{ $message }}';
            toastr.error(msg);
        </script>
    @endif
    @if ($message = Session::get('success'))
        <script type="text/javascript">
            var msg = '{{ $message }}';
            toastr.success(msg);
        </script>
    @endif

    <script>
        $(function() {
            // Summernote
            $('.textarea').summernote()
        });
    </script>
    <script>
        $(document).ready(function() {

            $("#ckbCheckAll").click(function() {
                $(".checkboxall").prop("checked", $(this).prop("checked"));
            });
        });

        $('.parent_category').change(function() {
            var cat = $(this).val();
            $.ajax({
                url: "{{ route('admin.product.get_subcategory') }}",
                type: 'get',
                data: {
                    'cat': cat
                },
                success: function(data) {
                    $('.subcategory').html(data);
                }
            });
        });
        $('.check_deliveryboy').click(function() {
            var order_id = $(this).data('id');
            $.ajax({
                url: '{{ url('admin/get_order_deliveryboy') }}',
                type: 'get',
                data: {
                    'order_id': order_id
                },
                success: function(data) {
                    //alert(data);
                    $('.showdeliveryboydetails').html(data);
                }
            });
        });


        $('.parent_category').change(function() {
            $("#sub_category > option").remove();
            var cat = $(this).val();


            $.ajax({
                url: "{{ route('admin.get-subcategory') }}",
                type: 'get',
                data: {
                    'cat': cat
                },
                success: function(categories) {
                    var opt = $('<option />');
                    opt.val("");
                    opt.text("Select Subcategory");
                    $('#sub_category').append(opt);
                    $.each(categories, function(id, category) {
                        var opt = $('<option />');
                        opt.val(category.id);
                        opt.text(category.category_name);
                        $('#sub_category').append(opt);
                    });

                }
            });
        });
        /* $('.near_by_address').keyup(function(){
          //alert('asdf');
          var address=$(this).val();
          $.ajax({
            url:'{{ url('admin/get_all_address') }}',
            type:'get',
            data:{'address':address},
            success:function(data){ 
              $('.vendors').html(data);
            }
          });
         });*/
        $('.near_by_address').keyup(function() {
            //alert('asdf');
            var address = $(this).val();

            $.ajax({
                url: '{{ url('admin/get_address_by_address') }}',
                type: 'get',
                data: {
                    'address': address
                },
                success: function(data) {
                    $('.view_address').html(data);
                }
            });
        });
        $('body').on('click', '.append_address', function() {
            var address = $(this).text();
            var parent_category = $('.parent_category').val();
            $('.near_by_address').val(address);
            $('.view_address').html('');
            $.ajax({
                url: '{{ url('admin/get_all_address') }}',
                type: 'get',
                data: {
                    'address': address,
                    'parent_category': parent_category
                },
                success: function(data) {
                    $('.vendors').html(data);
                }
            });
        });
    </script>
    <script>
        $('.parent_category').change(function() {
            var cat = $(this).val();
            var near_by_address = $('.near_by_address').val();
            $.ajax({
                url: '{{ url('admin/get_vendor_bycategory') }}',
                type: 'get',
                data: {
                    'cat': cat,
                    'near_by_address': near_by_address
                },
                success: function(data) {
                    $('.vendors').html(data);
                }
            });
        });
    </script>
    <script>
        $('.subcategory').change(function() {
            var cat = $(this).val();
            $.ajax({
                url: '{{ url('admin/get_service_bysubcategory') }}',
                type: 'get',
                data: {
                    'cat': cat
                },
                success: function(data) {
                    $('.services').html(data);
                }
            });
        });
        $('.state_view').change(function() {
            var state_id = $(this).val();
            $.ajax({
                url: '{{ url('admin/get_city_by_state') }}',
                type: 'get',
                data: {
                    'state_id': state_id
                },
                success: function(data) {
                    $('.city_view').html(data);
                }
            });
        });


        // country profile edit 

        $('#countries_b').change(function() {

            $("#states > option").remove();
            var country_id = $(this).val();

            $.ajax({
                url: '{{ url('get-states') }}',
                type: 'get',
                data: {
                    'country_id': country_id
                },
                success: function(states) {

                    var opt = $('<option />');
                    opt.val("");
                    opt.text("Select State");

                    $('#states').append(opt);
                    $.each(states, function(id, state) {
                        var opt = $('<option />');
                        opt.val(state.id);
                        opt.text(state.name);
                        $('#states').append(opt);
                    });
                    if ($('#states').attr("data-val")) {
                        $('#states').val($('#states').attr("data-val")).trigger("change");
                    }
                    // 
                }
            });
        });



        $('#countries').change(function() {

            $("#states > option").remove();
            var country_id = $(this).val();

            $.ajax({
                url: '{{ url('get-states') }}',
                type: 'get',
                data: {
                    'country_id': country_id
                },
                success: function(states) {

                    var opt = $('<option />');
                    opt.val("");
                    opt.text("Select State");

                    $('#states').append(opt);
                    $.each(states, function(id, state) {
                        var opt = $('<option />');
                        opt.val(state.id);
                        opt.text(state.name);
                        $('#states').append(opt);
                    });
                    if ($('#states').attr("data-val")) {
                        $('#states').val($('#states').attr("data-val")).trigger("change");
                    }
                    // 
                }
            });
        });

        $('body').on("change", "#states", function() {
            $("#cities > option").remove();
            var state_id = $(this).val();
            $.ajax({
                url: '{{ url('get-cities') }}',
                type: 'get',
                data: {
                    'state_id': state_id
                },
                success: function(states) {
                    var opt = $('<option />');
                    opt.val('');
                    opt.text("Select City");
                    $('#cities').append(opt);
                    $.each(states, function(id, state) {
                        var opt = $('<option />');
                        opt.val(state.id);
                        opt.text(state.name);
                        $('#cities').append(opt);
                    });

                    if ($('#cities').attr("data-val")) {
                        $('#cities').val($('#cities').attr("data-val"));
                    }
                }
            });
        });
    </script>

    <script>
        $('body').on('click', '.vendor_check', function() {
            var vendor_id = $(this).val();
            $.ajax({
                url: '{{ url('admin/get_vendor_details') }}',
                type: 'get',
                data: {
                    'vendor_id': vendor_id
                },
                success: function(data) {
                    $('.vendor_details').html(data);
                }
            });
        });
    </script>
    <script>
        $('body').on('click', '.remove_rervice_item1', function() {
            $(this).parent('td').parent().remove();
        });
        $('body').on('click', '.remove_rervice_item', function() {
            $(this).parent('td').parent().remove();
            var service_id = $(this).data('id');
            var vendor_id = '';
            var v = $('input[type=radio].vendor_check:checked');
            $(v).each(function(i) {
                vendor_id = $(this).val();
            });

            $.ajax({
                url: '{{ url('admin/cart_item_detele') }}',
                type: 'get',
                data: {
                    'vendor_id': vendor_id,
                    'service_id': service_id
                },
                success: function(data) {
                    $('.service_list').html(data);
                }
            });
        });
        $('body').on('change', '.change_status_booking', function() {
            var status = $(this).val();
            var booking_id = $(this).data('id');
            //alert(item_id);
            $.ajax({
                url: '{{ url('admin/change_status_booking') }}',
                type: 'get',
                data: {
                    'booking_id': booking_id,
                    'status': status
                },
                success: function(data) {
                    toastr.success('Status Update Successfully');
                }
            });
        });
    </script>
    <script>
        $('body').on('click', '.add_service', function() {
            var service_id = $(this).data('id');
            var vendors = '';
            var v = $('input[type=radio].vendor_check:checked');
            $(v).each(function(i) {
                vendors = $(this).val();
            });

            if (vendors) {
                $.ajax({
                    url: '{{ url('admin/get_service_details') }}',
                    type: 'get',
                    data: {
                        'service_id': service_id,
                        'vendor_id': vendors
                    },
                    success: function(data) {
                        if (data == 'exist') {
                            toastr.error('This service is allready added');
                        } else {
                            $('.service_list').html(data);
                            toastr.success('Service Add Successfully');
                        }

                    }
                });
            } else {
                toastr.error('Please select any Vendor...');
            }
        });
    </script>
    <script>
        // $.ajax({
        //     url: "{{ url('admin/get_data_graph') }}",
        //     type: 'get',
        //     data: {},
        //     success: function(data) {
        //         //alert(data);
        //         graph(data)
        //     }
        // });

        // function graph(val) {
        //     var obj = JSON.parse(val);
        //     var areaChartData = {
        //         labels: obj.month,
        //         datasets: [{
        //                 label: 'Orders',
        //                 backgroundColor: 'rgba(60,141,188,0.9)',
        //                 borderColor: 'rgba(60,141,188,0.8)',
        //                 pointRadius: false,
        //                 pointColor: '#3b8bba',
        //                 pointStrokeColor: 'rgba(60,141,188,1)',
        //                 pointHighlightFill: '#fff',
        //                 pointHighlightStroke: 'rgba(60,141,188,1)',
        //                 data: obj.complete_booking_data
        //             },
        //             {
        //                 label: '',
        //                 backgroundColor: 'white',
        //                 borderColor: 'white',
        //                 pointRadius: false,
        //                 pointColor: '',
        //                 pointStrokeColor: '',
        //                 pointHighlightFill: '',
        //                 pointHighlightStroke: '',
        //                 data: []
        //             },
        //         ]
        //     }

        //     var areaChartOptions = {
        //         maintainAspectRatio: false,
        //         responsive: true,
        //         legend: {
        //             display: false
        //         },
        //         scales: {
        //             xAxes: [{
        //                 gridLines: {
        //                     display: false,
        //                 }
        //             }],
        //             yAxes: [{
        //                 gridLines: {
        //                     display: false,
        //                 }
        //             }]
        //         }
        //     }


        //     var barChartCanvas = $('#barChart').get(0).getContext('2d')
        //     var barChartData = jQuery.extend(true, {}, areaChartData)
        //     var temp0 = areaChartData.datasets[0]
        //     var temp1 = areaChartData.datasets[1]
        //     barChartData.datasets[0] = temp1
        //     barChartData.datasets[1] = temp0

        //     var barChartOptions = {
        //         responsive: true,
        //         maintainAspectRatio: false,
        //         datasetFill: false
        //     }

        //     var barChart = new Chart(barChartCanvas, {
        //         type: 'bar',
        //         data: barChartData,
        //         options: barChartOptions
        //     })


        // }
    </script>
    <script>
        $('.deletegallery_image').click(function() {
            var id = $(this).data('id');
            $(this).parent('div').remove();
            $.ajax({
                url: '{{ url('admin/remove_product_images') }}',
                type: 'get',
                data: {
                    'id': id
                },
                success: function(data) {

                }
            });
        });
    </script>


    <script>
        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
            });
            $('#example3').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
            });
            $('#example4').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
    <script>
        $('.assignorder').click(function() {

            //alert('jkjs');

            var deliveryboy = $('#deliveryboy').val();

            var favorite = [];
            $.each($("input[name='checkname']:checked"), function() {
                favorite.push($(this).val());
            });
            if (favorite.length > 0) {
                $.ajax({
                    url: '{{ url('admin/assign_delivery_boy') }}',
                    type: 'get',
                    data: {
                        'arrays': favorite,
                        'deliveryboy': deliveryboy
                    },
                    success: function(data) {
                        if (data == 'success') {
                            var msg = 'Order Assign Successfully..';
                            toastr.success(msg);
                        }

                    }
                });

            } else {
                var msg = 'Please Select any record';
                toastr.error(msg);
            }
        });
        $('.export_report_vendor').click(function(e) {
            var type = $(this).data('id');
            var favorite = [];
            $.each($("input[name='checkname']:checked"), function() {
                favorite.push($(this).val());
            });
            if (favorite.length > 0) {

                var string_val = favorite.join('-');

                window.location.href = "{{ url('/') }}/admin/export_order_report/" + string_val;
            } else {
                var msg = 'Please Select any record';
                toastr.error(msg);
            }

        });
        $('.bulk_print_order').click(function() {
            var type = $(this).data('id');
            var favorite = [];
            $.each($("input[name='checkname']:checked"), function() {
                favorite.push($(this).val());
            });
            if (favorite.length > 0) {

                var string_val = favorite.join('-');

                window.location.href = "{{ url('/') }}/admin/print_invoice_multiple/" + string_val;
            } else {
                var msg = 'Please Select any record';
                toastr.error(msg);
            }
        });

        $('.bulk_print_order_item').click(function() {
            var type = $(this).data('id');
            var favorite = [];
            $.each($("input[name='checkname']:checked"), function() {
                favorite.push($(this).val());
            });
            if (favorite.length > 0) {

                var string_val = favorite.join('-');

                window.location.href = "{{ url('/') }}/admin/print_invoice_items/" + string_val;
            } else {
                var msg = 'Please Select any record';
                toastr.error(msg);
            }
        });

        $('.bulk_export_order_item').click(function() {
            var type = $(this).data('id');
            var favorite = [];
            $.each($("input[name='checkname']:checked"), function() {
                favorite.push($(this).val());
            });
            if (favorite.length > 0) {

                var string_val = favorite.join('-');

                window.location.href = "{{ url('/') }}/admin/export_order/" + string_val;
            } else {
                var msg = 'Please Select any record';
                toastr.error(msg);
            }
        });

        $('.filterDate').datepicker({
            format: "yyyy/mm/dd",
            autoclose: true,
            todayHighlight: true,
            clearBtn: true
        });

        $('.print').click(function() {

            window.print();
        });


        $('.seach_customer').keyup(function() {
            var val = $(this).val();
            $.ajax({
                url: '{{ url('admin/search_customer') }}',
                type: 'get',
                data: {
                    'value': val
                },
                success: function(data) {
                    $('.view_customer').html(data);

                }
            });
        });
    </script>
    <script>
        $('.filters').change(function() {
            var val = $(this).val();
            if (val == 'Filter By Status') {
                $('.status').show();
            } else {
                $('.status').hide();
            }
            if (val == 'Filter By Date') {
                $('.date_html').show();
            } else {
                $('.date_html').hide();
            }
            if (val == 'Filter By Datetime') {
                $('.datetime_html').show();
            } else {
                $('.datetime_html').hide();
            }
            if (val == 'Filter By Area') {
                $('.area').show();
            } else {
                $('.area').hide();
            }
            if (val == 'Filter By Order') {
                $('.order').show();
            } else {
                $('.order').hide();
            }
            if (val == 'Filter By Customer') {
                $('.customers').show();
            } else {
                $('.customers').hide();
            }
        });
        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                        'month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function(start, end) {
                // $('.view_date').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'))
                $('.date1').val(start.format('YYYY-MM-DD'));
                $('.date2').val(end.format('YYYY-MM-DD'));
            }
        )
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'YYYY-MM-DD hh:mm A'
            }
        })
    </script>
    <script src="<?php echo asset('/admin-assets/common.js'); ?>"></script>
    <script>
        $('.add_more').click(function() {
            var html = $('.get_html').html();
            $('.append_html').append(html);
        });

        $('.add_more_items').click(function() {
            $.ajax({
                url: "{{ route('admin.product.add_itemsinproduct') }}",
                type: 'get',
                data: {
                    'id': $(this).data('id')
                },
                success: function(data) {
                    $('.append_html').append(data);
                }
            });

        });

        $('body').on('click', '.remove_items', function() {
            $(this).parent('div').parent('.row').remove();
            if ($(this).data('id')) {
                $.ajax({
                    url: "{{ route('admin.product.deleteProductitems') }}",
                    type: 'get',
                    data: {
                        'id': $(this).data('id')
                    },
                    success: function(data) {
                        /// alert(data);
                    }
                });
            }
        });

        $('body').on('change', '.change_status_delivery', function() {
            var id = $(this).data('id');
            var user_id = $(this).data('userid');
            var status = $(this).val();
            $.ajax({
                url: '{{ url('admin/updatedeliveryStatus') }}',
                type: 'get',
                data: {
                    'id': id,
                    'user_id': user_id,
                    'status': status
                },
                success: function(data) {
                    var msg = 'Status Changed Successfully';
                    toastr.success(msg);

                }
            });

        });
    </script>
    <script type="text/javascript">
        $('.variantimageupload11').change(function(e) {
            e.preventDefault;
            var variant_id = $(e.target).data('id');
            var file_data = this.files[0]; //$('#variant_image'+variant_id).prop('files')[0];

            var new_form = new FormData();
            new_form.append('images', file_data);
            new_form.append('variant_id', variant_id);
            $.ajax({
                url: "{{ route('admin.product.uploadvariantimages') }}",
                type: 'get',
                data: new_form,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    alert(data);

                    //$('#product_variantimages'+variant_id).html(data);
                }

            });

        });
    </script>


    <script>
        $(document).ready(function() {
            $('.variantimageupload').change(function(e) {

                e.preventDefault;
                var variant_id = $(e.target).data('id');

                let formData = new FormData($('#form_data')[0]);

                let file = $(this)[0].files[0];

                // alert(variant_id); 

                formData.append('file', file, file.name);

                formData.append('variant_id', variant_id);

                $.ajax({
                    url: "{{ route('admin.product.uploadvariantimages') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    success: function(data) {
                        var img = '<img src="<?php echo asset('/'); ?>uploads/items/' + data +
                            '" style="width:100px">';
                        $('.viewimage' + variant_id).html(img);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#sub_category').change(function(e) {
                e.preventDefault;
                var value = $(this).val();

                $.ajax({
                    url: "{{ route('admin.product.get_child_category_by_ajax') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    data: {
                        value: value
                    },
                    success: function(data) {

                        $('#show_child_category').html(data);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });


        $(document).ready(function() {
            $('#select_category').change(function(e) {

                e.preventDefault;
                var value = $(this).val();

                $.ajax({
                    url: '{{ url('admin/get_subcategory_by_ajax') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    data: {
                        value: value
                    },
                    success: function(data) {

                        $('#show_subCategory').html(data);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.postal_code').select2({
                placeholder: 'postal code'

            });
        });

        $(document).ready(function() {
            $('#select_custome_city').change(function(e) {
                e.preventDefault;
                var value = $(this).val();

                console.log(value);

                $.ajax({
                    url: '{{ url('admin/select_custome_city') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    data: {
                        value: value
                    },
                    success: function(data) {

                        $('#postal_code').html(data);
                        $('#postal_code').select2({
                            placeholder: '--select postal code--'

                        });

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });


        $(document).ready(function() {
            $('#change_div').change(function() {
                var value = $(this).val();
                if (value == 5) {
                    $("#div_show").css('display', 'none')
                } else {
                    $("#div_show").css('display', 'block')
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#type_id').change(function() {
                var value = $(this).val();
                if (value == 10) {
                    $('#comdown_show').css('display', 'block');
                } else {
                    $('#comdown_show').css('display', 'none');
                }

            });
        });
    </script>

    <script>
        $(function() {
            $("#expiry_date").datepicker();
            $("#mfg_date").datepicker();
            $("#from").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#to").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>


</body>

</html>
