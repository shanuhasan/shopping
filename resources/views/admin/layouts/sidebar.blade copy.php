<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        @php
            $role = Auth::user()->role_id;
        @endphp

        @if ($role == App\Models\User::VENDOR)
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ url('/uploads/users/', Auth::user()->image) }}" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        {{ Auth::user()->name }}
                    </a>
                </div>
            </div>
        @else
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('uploads/logo/1599827425.png') }}" class="img-circle elevation-2"
                        alt="User Image" style="width: 3.2rem;">
                </div>
                <div class="info">
                    <span class="text-white">SHOPING</span>
                </div>
            </div>
        @endif

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if ($role == App\Models\User::VENDOR)
                    <li class="nav-item">
                        <a href="{{ url('vendor/dasboard') }}" class="nav-link">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link @yield('dashboard')">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endif

                @if ($role == App\Models\User::ADMIN)
                    <li class="nav-item">
                        <a href="{{ url('admin/attribute') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Product Attribute
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('report/index') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Report
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Category
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/category_list') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Main Category</p>
                                </a>
                                <a href="{{ url('admin/subcategory_list') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sub Category</p>
                                </a>
                                <a href="{{ url('admin/childcategory_list') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Child Category</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {!! Route::getFacadeRoot()->current()->uri() == 'admin/product_list' ? 'active' : '' !!}">
                        <a href="{{ url('admin/product_list') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>Products</p>
                        </a>
                    </li>


                    <li class="nav-item {!! Route::getFacadeRoot()->current()->uri() == 'admin/offers' ? 'active' : '' !!}">
                        <a href="{{ url('admin/offers') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Offer Product</p>
                        </a>
                    </li>
                    <li class="nav-item {!! Route::getFacadeRoot()->current()->uri() == 'admin/coupons' ? 'active' : '' !!}">
                        <a href="{{ url('admin/coupons') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Coupons</p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview" style="display: none;">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Vendors
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('admin/vendor_list') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Vendor List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview" style="display: none;">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Sub Admins
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('admin/subadmin_list') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Subadmin List</p>
                                </a>
                                <a href="{{ url('admin/add_subadmin') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Subadmin</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview @yield('order_open')">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Orders
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/order') }}" class="nav-link @yield('order_active')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Order List</p>
                                </a>
                                <a href="{{ url('admin/order/create') }}" class="nav-link @yield('order_create_active')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New Order</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('vendor/complaint') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Complaint
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/review') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Review
                        </a>
                    </li>

                    <li class="nav-item has-treeview {!! Request::segments()[1] == 'customers' ? 'menu-open' : '' !!}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('admin/customers') }}" class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/customers' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Users</p>
                                </a>
                                <a href="{{ url('admin/customers/create') }}"
                                    class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/customers/create' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add User</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/customers/deliveryboy') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Delivery Boy</p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview {!! Request::segments()[1] == 'vendors' ? 'menu-open' : '' !!}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Vendors
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('admin/vendors') }}" class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/vendors' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All vendors</p>
                                </a>
                                <a href="{{ url('admin/vendors/create') }}" class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/vendors/create' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add vendor</p>
                                </a>

                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview @yield('setting_open')">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                General Setting
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">

                                <a href="{{ url('admin/settings/site-settings') }}"
                                    class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/settings/site-settings' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Site Settings</p>
                                </a>

                                <a href="{{ url('admin/tax_in') }}" class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/tax_in' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tax</p>
                                </a>

                                <a href="{{ url('admin/time_index') }}" class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/tax_in' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Time Schedule</p>
                                </a>

                                <a href="{{ url('admin/settings/city') }}" class="nav-link {!! Request::segments()[1] == 'city' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>City</p>
                                </a>

                                <a href="{{ url('admin/settings/area') }}" class="nav-link {!! Request::segments()[1] == 'area' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Area</p>
                                </a>

                                <a href="{{ url('admin/settings/pincode') }}"
                                    class="nav-link {!! Request::segments()[1] == 'pincode' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pincode</p>
                                </a>

                                <a href="{{ route('admin.banner') }}" class="nav-link @yield('banner_active')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Banners</p>
                                </a>
                                <a href="{{ url('admin/types') }}" class="nav-link {!! Request::segments()[1] == 'types' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Product Types</p>
                                </a>
                                <a href="{{ url('admin/settings/about') }}"
                                    class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/settings/about' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>About Us</p>
                                </a>

                                <a href="{{ url('admin/settings/terms') }}"
                                    class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/settings/terms' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Terms & condition</p>
                                </a>

                                <a href="{{ url('admin/settings/privacy') }}"
                                    class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/settings/privacy' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Privacy & Policy</p>
                                </a>

                                <a href="{{ url('admin/settings/return') }}"
                                    class="nav-link {!! Route::getFacadeRoot()->current()->uri() == 'admin/settings/return' ? 'active' : '' !!}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Return Policy</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/report') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Export Report
                        </a>
                    </li>
                @endif

                @if ($role == App\Models\User::VENDOR)
                    <li class="nav-item">
                        <a href="{{ url('vendor/account') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Account
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('vendor/review') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Review
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('vendor/report') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Report
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('vendor/profile') }}" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            Profile Setting
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
