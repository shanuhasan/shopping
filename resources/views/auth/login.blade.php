@extends('layouts.app')
@section('title', 'Login')
@section('content')

    <section class="sec-space">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-5"></div>
                <div class="col-md-4 col-sm-5">
                    <div class="login-wrap">
                        <h3 class="fsz-25 ptb-15"><span class="light-font">Login </span></h3>
                        <form class="login-form row pt-50" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group col-sm-12">
                                <input type="email" class="form-control" placeholder="Email" name="email"
                                    value="{{ old('email') }}" />
                            </div>

                            <div class="form-group col-sm-12">
                                <input type="password" class="form-control" placeholder="Password" name="password" />
                            </div>

                            <div class="form-group col-sm-12 pt-15">
                                <button type="submit" class="theme-btn btn submit-btn"> <b> LOGIN </b> </button>
                            </div>
                        </form>

                        <p>
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </p>

                    </div>
                </div>
                <div class="col-md-4 col-sm-5"></div>
            </div>
        </div>
    </section>
@endsection
