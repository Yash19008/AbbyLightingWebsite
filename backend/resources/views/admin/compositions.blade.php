@extends('admin.page')

@section('title',$title)

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
                    <a href="{{route('composition_admin.add')}}" class="buttons" style="color:white;"><span>Create Composition</span></a>
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
                                        <th style="width: 50px" class="text-left">ID</th>
                                        <th width="15%" class="text-center">Image</th>
                                        <th width="20%" class="text-center">Title</th>
                                        <th width="20%" class="text-center">Category</th>
                                        <th width="15%" class="text-center">Showcase in Inspiration</th>
                                        <th width="15%" class="text-center"><a>Action<i class="ml-1"></i></a></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($results->total()==0)
                                    <tr class="data">
                                        <td colspan="11" align="center">No Records Found</td>
                                    </tr>
                                    @else
                                        @php
                                            $i = $results->firstItem() - 1;
                                        @endphp
                                        @foreach($results as $key=>$row)
                                        <tr class="data module-list" id="data-{{ $row->id }}">
                                            <td>{{@$row->id}}</td>
                                            <td class="img-td">
                                                <div class="text-center">
                                                    <img style="text-align:center;width:80px;height:60px;object-fit:contain;" src="{{ $row->image ?  asset('storage/uploads/compositions/'.@$row->image) : asset('images/default.png') }}" class="list-image-prof">
                                                </div>
                                            </td>
                                            <td class="text-center">{{@$row->title}}</td>
                                            <td class="text-center">{{@$row->category}}</td>
                                            <td>
                                                <div class="custom-control custom-switch text-center">
                                                    <input type="checkbox" class="custom-control-input knob switch" data-col="{{Common_function::encrypt('is_showcase')}}" id="customSwitch2{{$row->id}}" {{ $row->is_showcase ? 'Checked' : '' }}>
                                                    <label class="custom-control-label" for="customSwitch2{{$row->id}}"></label>
                                                </div>
                                            </td>
                                            <td class="text-center list-action actBtn-td">
                                                <a href="{{route('composition_admin.edit',@$row->id)}}" class="" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-1"></i></a>
                                                <a href="javascript:;" class="delete" data-module="{{@$main_module}}" data-toggle="tooltip" title="delete"><i class="ft-trash font-medium-3"></i></a>     
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <input type="hidden" id="hdn" value="{{$tbl}}">
   
@stop
