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
                    <h3 style="margin-bottom:30px">Basic Project Information</h3>
                    <div class="row p-4 text-left">
                        <div class="col-md-4">
                            <b>Project Name</b>
                            <p class="product_name" id="field_1_1">{{@$project->name}}</p>
                                          
                        </div>
                        <div class="col-md-4">
                            <b>Project Location</b>
                            <p class="product_name" id="field_1_1">{{@$project->location}}</p>
                                          
                        </div>
                        <div class="col-md-4">
                            <b>Type</b>
                            <p class="product_name" id="field_1_1">{{@$project->type ? @$project->type : '-'}}</p>      
                        </div>
                        <div class="col-md-4">
                            <b>Description</b>
                            <p class="product_name" id="field_1_1">{{@$project->description ? @$project->description : '-'}}</p>
                                          
                        </div>   
                        <div class="col-md-4">
                            <b>Slug</b>
                            <p class="product_name" id="field_1_1">{{@$project->slug ? @$project->slug : '-'}}</p>
                                          
                        </div>   
                    </div>
                    <div class="row p-4 text-left">
                    <b>Project Images</b>
                        @if(@$project_image)
                        <div class="flex gap-2 mb-4" style="display:flex">
                        @foreach($project_image as $key=>$val)
                            <span class="input-group-addon file-input-img-span" style="overflow: hidden;">
                                <img src="{{ @$val->image ? asset('storage/uploads/projects/'.@$val->image) : asset('images/default.png') }}" height="100">
                            </span>
                        @endforeach
                    </div>
                    @endif
                    </div>
                
            </div>
        </div>
    </div>
@stop