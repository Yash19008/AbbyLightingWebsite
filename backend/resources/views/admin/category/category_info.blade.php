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
                    <h3 style="margin-bottom:30px">Basic Category Information</h3>
                    <div class="row p-4 text-left">
                        <div class="col-md-4">
                            <b>Category Title</b>
                            <p class="product_name" id="field_1_1">{{@$category->title}}</p>
                                          
                        </div>
                        <div class="col-md-4">
                            <b>URI</b>
                            <p class="product_name" id="field_1_1">{{@$category->uri ? @$category->uri : '-'}}</p>      
                        </div>
                        <div class="col-md-4">
                            <b>In Menu</b>
                            <p class="product_name" id="field_1_1">{{@$category->in_menu ? @$category->in_menu : '-'}}</p>
                                          
                        </div>
                        <div class="col-md-4">
                            <b>Sheet Value</b>
                            <p class="product_name" id="field_1_1">{{@$category->sheet_title ? @$category->sheet_title : '-'}}</p>
                                          
                        </div>
                        @if(@$category->featured_image)
                        <div class="col-md-4">
                            <b>Featured Image</b>
                            <p class="product_name" id="field_1_1">
                            <img src="{{ @$category->featured_image ? asset('storage/uploads/categories/'.@$category->featured_image) : asset('images/default.png') }}" height="100">
                            </p>  
                        </div>  
                        @endif
                        @if(@$category->display_icon)
                        <div class="col-md-4">
                            <b>Display Icon</b>
                            <p class="product_name" id="field_1_1">
                            <img src="{{ @$category->display_icon ? asset('storage/uploads/categories/'.@$category->display_icon) : asset('images/default.png') }}" height="100">
                            </p>  
                        </div>  
                        @endif
                       
                    </div>
                   
                
                </div>
            
            </div>
        
        </div>
    </div>
</div>
@if(count(@$category_image) > 0)
<div class="row">
    <!-- About Me Box Start-->
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-body">
                <div class="row">
                    <h3 style="margin-bottom:30px">Category Images</h3>
                    <div class="row p-4 text-left">
                        <div class="flex gap-2 mb-4" style="display:flex">
                        @foreach($category_image as $key=>$val)
                            <span class="input-group-addon file-input-img-span" style="overflow: hidden;">
                                <img src="{{ @$val->image ? asset('storage/uploads/categories/'.@$val->image) : asset('images/default.png') }}" height="100">
                            </span>
                        @endforeach
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@stop