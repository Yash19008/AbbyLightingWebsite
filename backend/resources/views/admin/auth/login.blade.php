@extends('admin.master')

@section('title', $title)

@section('body')
    <!--Login Page Starts-->
    <section id="login" class="auth-height">
        <div class="row full-height-vh m-0">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body auth-img">
                            <div class="row m-0">
                                <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center auth-img-bg p-3">
                                    <img src="{{asset('adminlte/img/gallery/login.png')}}" alt="" class="img-fluid" width="300" height="230">
                                </div>
                                <div class="col-lg-6 col-12 px-4 py-3">
                                    <h4 class="mb-2 card-title">Login to Abby Lighting</h4>
                                    @include('admin.include.notification')
                                    <form action="{{ url('admin/login') }}" method="post" id="admin_login_form">
                                    @csrf
                                        <p>Welcome back, please login to your account.</p>
                                        <div class="form-group mb-3">
                                            <input type="text"  name="username" id="username" class="form-control" placeholder="Enter User name here" value="{{old('username')}}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="password"  name="password" id="password" class="form-control" placeholder="Password">
                                        </div>
                                        <!-- <div class="d-sm-flex justify-content-between mb-3 font-small-2">
                                            <a href="{{route('forgotpassword_admin.reset')}}">Forgot Password?</a>
                                        </div> -->
                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </div>
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Login Page Ends-->
@stop
