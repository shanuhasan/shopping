@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
    <section class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>My Profile</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="user-dasboard">
        <div class="side-bar">
            <h3 class="title">My Profile</h3>
            <ul class="side-nav">
                <li><a href="{{ url('/profile') }}" class="active"><i class="la la-user"></i>My Profile</a></li>
                <li><a href="{{ url('/my-order') }}"><i class="la la-box"></i>My Orders</a></li>
                <li><a href="{{ url('/wishlist') }}"><i class="la la-heart-o"></i>My Wishlist</a></li>
                <!--<li><a href="{{ url('/') }}" class="log-out"><i class="la la-sign-out"></i>Log Out</a></li>-->
            </ul>
        </div>
        <div class="dasboard-wrapper">
            @if (Session::has('errors'))
                <div class="alert alert-danger">
                    {!! Session::get('errors') !!}
                </div>
            @endif


            <form class="form" action="{{ url('/profile/update') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                    </div>

                    <div class="form-group col-sm-6">
                        <label>Phone</label>
                        <input type="number" name="phone" class="form-control" value="{{ $user->phone }}">
                    </div>

                    <div class="form-group col-sm-6">
                        <label>Address 1</label>
                        <input type="text" name="address_1" class="form-control" value="{{ $user->address_1 }}">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Address 2</label>
                        <input type="text" name="address_2" class="form-control" value="{{ $user->address_2 }}">
                    </div>

                    <div class="form-group col-sm-6">
                        <label>Zip code</label>
                        <input type="text" name="pincode" class="form-control" value="{{ $user->pincode }}">
                    </div>
                    <div class="form-group col-sm-6">
                        <input type="hidden" name="old_profile" value="{{ $user->image }}" <label>Profile
                        image</label>
                        <input type="file" name="profile_image" class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                        <img src="{{ url('/uploads/profile', $user->image) }}" width="100">
                    </div>
                </div>

                <div>
                    <hr>
                    <span class="text-muted">Change account password. (optional)</span>
                </div>
                <div class="row mt-4">
                    <!--<div class="form-group col-lg-4 col-xl-4">-->
                    <!--     <label>Current Password</label>-->
                    <!--     <input type="password" name="current_password" class="form-control" placeholder="••••••••••">-->
                    <!-- </div>-->
                    <div class="form-group col-md-6 col-lg-6 col-xl-6">
                        <label>New Pasword</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••••">
                    </div>
                    <div class="form-group col-md-6 col-lg-6 col-xl-6">
                        <label>Confirm New Pasword</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••••">
                    </div>
                    <div class="form-group col-12" align="right">
                        <button value="button" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>

            </form>

            <!--<div>-->
            <!--  <hr>-->
            <!--  <h4 class="fw-500 mb-0">Delete Account</h4>-->
            <!--  <span class="text-muted">We do our very best to give you a great experience. - We’ll be sad to see you leave.<a class="fw-500 ml-2" href="#">Delete Account</a></span>-->
            <!--</div>-->

        </div>
    </section>
@endsection
