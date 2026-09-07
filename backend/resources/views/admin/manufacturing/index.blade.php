@extends('admin.page')

@section('title', 'Manufacturing Section')

@section('content_header')
<div class="row">
    <div class="col-6 col-md-6">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Manufacturing Excellence Section</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-edit mr-1"></i>
                    <a href="{{route('admin.manufacturing.edit')}}" class="buttons"><span>Edit Section</span></a>
                </span>
            </button>
        </div>
    </div>
</div>
@stop

@section('content')
@include('admin.include.notification')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="20%" class="text-left">Title</th>
                                        <th width="15%" class="text-center">Title Highlight</th>
                                        <th width="30%" class="text-center">Description</th>
                                        <th width="10%" class="text-center">Button Text</th>
                                        <th width="10%" class="text-center">Background Image</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="5%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($section)
                                    <tr class="data">
                                        <td>{{@$section->title}}</td>
                                        <td class="text-center">{{@$section->title_highlight ?? '-'}}</td>
                                        <td>{{Str::limit(@$section->description, 100) ?? '-'}}</td>
                                        <td class="text-center">{{@$section->button_text ?? '-'}}</td>
                                        <td class="text-center">
                                            @if($section->background_image)
                                            <img style="width:60px;height:40px;object-fit:cover;" src="/storage/{{ $section->background_image }}" alt="Background">
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($section->is_active)
                                            <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center list-action actBtn-td">
                                            <a href="{{route('admin.manufacturing.edit')}}" class="" data-toggle="tooltip" title="Edit">
                                                <i class="ft-edit-2 font-medium-3 mr-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @else
                                    <tr class="data">
                                        <td colspan="7" align="center">No Section Found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
