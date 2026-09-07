@extends('admin.page')

@section('title', $title)

@section('content_header')
@stop
@section('content')
<div class="row">
        <div class="col-12">
            <div class="content-header">Add Product to Canallazo R</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
            <div class="card card-primary">
                <form class="form-horizontal" id="{{$frn_id}}" novalidate action="javascript:;" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <!-- ./form sub header-->
                        <div class="form-group row">
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox1" >
                                <label for="checkbox1"><span>Helix 50</span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox2">
                                <label for="checkbox2"><span>Heron</span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox3">
                                <label for="checkbox3"><span>iNline P 60</span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox4">
                                <label for="checkbox4"><span>iNline P 120</span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox2">
                                <label for="checkbox2"><span>iNline P 180</span></label>
                            </div>
                        </div>
                     
                     
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-dark ">Add Products</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
