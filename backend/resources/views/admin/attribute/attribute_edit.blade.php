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
@include('admin.include.notification')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <div class="card card-primary">
            <form class="form-horizontal" id="{{$frn_id}}" novalidate action="{{$action}}" method="post"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card-body">
                    <!-- ./form sub header-->
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Attribute Name<i
                                class="text-danger">*</i></label>
                        <div class="col-sm-5">
                            <input type='text' name="attribute_name" class="form-control"
                                value="{{ old('attribute_name',@$attribute->attribute_name) }}"
                                placeholder="Attribute Name" required>

                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="ingredients" class="col-sm-2 control-label">Group Name<i
                                class="text-danger">*</i></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <select id="group_id" name="group_id" class="form-control select2"
                                    placeholder="Select service">
                                    @foreach($group as $g)
                                    <option value="{{ $g->id }}" {{($g->id == @$attribute->group_id) ? "selected" : ""}}
                                        > {{ $g->title }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Values <span></span></label>
                        <div class="col-sm-5">
                            <input type='text' name="values" class="form-control"
                                value="{{ old('values',@$attribute->values) }}" placeholder="Values">
                            <small class="form-text text-muted">Add multiple attributes with comma seprated Eg.
                                Black,Red,Blue</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Codes <span></span></label>
                        <div class="col-sm-5">
                            <input type='text' name="codes" class="form-control"
                                value="{{ old('codes',@$attribute->codes) }}" placeholder="Values">
                            <small class="form-text text-muted">Add codes in same order of values</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Is visible on website ? <span></span></label>
                        <div class="col-sm-5">
                            <input type="radio" id="option1" name="is_visible" value="yes" {{
                                (@$attribute->is_visible==null || @$attribute->is_visible=="yes")? "checked" : "" }}
                            >&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="radio" id="option2" name="is_visible" value="no" {{
                                (@$attribute->is_visible=="no")? "checked" : "" }} >&nbsp;&nbsp;No</label>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Sheet Title</label>
                        <div class="col-sm-5">
                            <input type='text' name="sheet_title" class="form-control"
                                value="{{ old('sheet_title',@$attribute->sheet_title) }}" placeholder="Sheet Title">

                        </div>
                    </div> --}}


                    <div class="form-group row">
                        <div class="offset-2 col-sm-10">
                            <button type="submit" class="btn btn-dark ">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop