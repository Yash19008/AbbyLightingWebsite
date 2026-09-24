@extends('admin.page')

@section('title', $title)

@section('content_header')
@stop
@section('content')
<div class="row">
        <div class="col-12">
            <div class="content-header">{{@$title}}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
            <div class="card card-primary">
                <form class="form-horizontal" id="{{$frn_id}}" novalidate action="{{@$action}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <!-- ./form sub header-->
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Title<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="title" name="title" class="form-control" placeholder="Composition Title" value="{{@$result->title}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Kicker / Description</label>
                            <div class="col-sm-6">
                                <input type="text" id="kicker" name="kicker" class="form-control" placeholder="Short description" value="{{@$result->kicker}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Category</label>
                            <div class="col-sm-6">
                                <select name="category_id" id="category_id" class="form-control select2">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (@$result->category_id == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="products" class="col-sm-3 control-label">Tagged Products</label>
                            <div class="col-sm-6">
                                @php
                                    $selectedProducts = [];
                                    if(isset($result) && $result->products) {
                                        $selectedProducts = $result->products->pluck('id')->toArray();
                                    }
                                @endphp
                                <select name="product_ids[]" id="product_ids" class="form-control select2" multiple="multiple" style="width: 100%;" data-placeholder="Select products to tag...">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>
                                            {{ $product->name }} ({{ $product->slug }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Showcase in Inspiration Page?</label>
                            <div class="col-sm-6">
                                <div class="custom-control custom-switch mt-1">
                                    <input type="checkbox" name="is_showcase" class="custom-control-input" id="isShowcase" {{ @$result->is_showcase ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="isShowcase">Yes</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Thumbnail Image<i class="text-danger">*</i></label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="file" id="imagefile"  accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" disabled placeholder="Upload Image" value="{{@$result->image}}">
                                    <input type="hidden" name="oldPhoto" value="{{@$result->image}}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>
                                @if(@$result->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/uploads/compositions/' . $result->image) }}" style="max-height: 150px; border: 1px solid #ccc;" />
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="offset-3 col-sm-8">
                                <button type="submit" class="btn btn-dark ">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
