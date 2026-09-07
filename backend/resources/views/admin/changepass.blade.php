@extends('admin.page')

@section('title',$title)

@section('content')
<div class="col-md-12">
        <div class="card card-primary">
            <div class="card-body">
                <div class="row">
                <div class="col-6">
                     <h3 style="margin-bottom:30px">Change Password</h3>
                     @include('admin.include.notification')
                    <form action="{{@$action}}" method="post" id="admin_reset_form">
                    {{ csrf_field() }}
                    <p class="card-text mb-3">Please enter password to change for your account.</p>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Old Password">
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password">
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm Password">
                    </div>
                    <div class="d-flex flex-sm-row flex-column justify-content-between">
                        <button type="submit" class="btn btn-primary ml-sm-1">Reset</button>
                    </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
 
@stop