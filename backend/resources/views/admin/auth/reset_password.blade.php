@extends('admin.master')

@section('title', $title)

@section('body')
     <!--Forgot Password Starts-->
     <section id="reset-password" class="auth-height">
        <div class="row full-height-vh m-0 d-flex align-items-center justify-content-center">
            <div class="col-md-7 col-12">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body auth-img">
                            <div class="row m-0">
                                <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center text-center auth-img-bg py-2">
                                    <img src="{{asset('adminlte/img/gallery/forgot.png')}}" alt="" class="img-fluid" width="260" height="230">
                                </div>
                                <div class="col-lg-6 col-md-12 px-4 py-3">
                                    <h4 class="mb-2 card-title">Reset Password</h4>
                                    @include('admin.include.notification')
                                    <form action="{{@$action}}" method="post" id="admin_reset_form">
                                    {{ csrf_field() }}
                                    <p class="card-text mb-3">Please enter your email address apassword to change for your account.</p>
                                    <div class="form-group mb-3">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                                    </div>
                                    <div class="d-flex flex-sm-row flex-column justify-content-between">
                                        <a href="{{route('login_admin')}}" class="btn bg-light-primary mb-2 mb-sm-0">Back To Login</a>
                                        <button type="submit" class="btn btn-primary ml-sm-1">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Forgot Password Ends-->
@stop