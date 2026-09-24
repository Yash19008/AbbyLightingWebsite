@extends('admin.page')

@section('title', $title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header">{{@$title}}</div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <div class="card card-primary">
            <form class="form-horizontal" id="frm_composition_category" novalidate action="{{@$action}}" method="post">
                {{ csrf_field() }}
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Category Name<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="name" name="name" class="form-control" placeholder="E.g. Living Room" value="{{ old('name', @$result->name) }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="offset-3 col-sm-8">
                            <button type="submit" class="btn btn-dark">Save</button>
                            <a href="{{ route('composition_categories_admin') }}" class="btn btn-default ml-2">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
