@extends('admin.page')

@section('title',$title)

@section('content_header')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">

    </div>
</div>
@stop
@section('content')
<div class="row">
    <!-- About Me Box Start-->
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-body">
                <div class="row">
                    <h3 style="margin-bottom:30px">Job</h3>
                    <div class="row p-4 text-left">
                        <div class="col-md-6">
                            <b>Title</b>
                            <p class="product_name" id="field_1_1">{{@$job->title}}</p>

                        </div>
                        <div class="col-md-6">
                            <b>Location</b>
                            <p class="product_name" id="field_1_1">{{@$job->location}}</p>
                        </div>
                    </div>
                    <div class="row p-4 text-left">
                        <div class="col-md-6">
                            <b>Short Description</b>
                            <p class="product_name" id="field_1_1">{{@$job->short_description}}
                            </p>

                        </div>
                        <div class="col-md-6">
                            <b>Description</b>
                            <p class="product_name" id="field_1_1">{!!@$job->description!!}</p>

                        </div>

                    </div>


                </div>

            </div>

        </div>
    </div>
</div>
@stop