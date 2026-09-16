@extends('admin.page')

@section('title', $title ?? 'Compositions')

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Compositions List</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('composition_admin.add') }}" class="buttons" style="color:white;"><span>Create Composition</span></a>
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
                        <table class="table data-table table-striped table-bordered" data-order='[[ 0, "desc" ]]' id="compositions-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 50px" class="text-left">ID</th>
                                    <th style="width: 100px" class="text-center">IMAGE</th>
                                    <th>TITLE</th>
                                    <th style="width: 150px" class="text-center">CATEGORY / ROOM</th>
                                    <th style="width: 160px" class="text-center">SHOWCASE IN INSPIRATION</th>
                                    <th style="width: 100px" class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $key => $row)
                                <tr class="data module-list" id="data-{{ $row->id }}">
                                    <td class="text-left align-middle">{{ $row->id }}</td>
                                    <td class="img-td text-center align-middle">
                                        @if($row->image)
                                            <img style="width: 70px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;" src="{{ asset('storage/uploads/compositions/' . $row->image) }}" class="list-image-prof">
                                        @else
                                            <img style="width: 70px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;" src="{{ asset('images/default.png') }}" class="list-image-prof">
                                        @endif
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        {{ $row->title ?: 'Untitled' }}
                                        @if($row->kicker)
                                            <br><small class="text-muted font-weight-normal">{{ $row->kicker }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-secondary text-uppercase" style="font-size: 11px; padding: 5px 8px;">
                                            {{ $row->category ?: 'General' }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ Common_function::encrypt('is_showcase') }}" id="customSwitch2{{ $row->id }}" {{ $row->is_showcase ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitch2{{ $row->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center list-action actBtn-td align-middle">
                                        <a href="{{ route('composition_admin.edit', $row->id) }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2"></i></a>
                                        <a href="javascript:;" class="delete btn btn-sm btn-outline-danger ml-1" data-module="{{ $main_module }}" data-toggle="tooltip" title="Delete"><i class="ft-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="hdn" value="{{ $tbl }}">
@stop
