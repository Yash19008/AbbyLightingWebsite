@extends('admin.page')

@section('title', $title)

@section('content_header')
@stop
@section('content')
@if($errors->any())
{!! implode('', $errors->all('<div>:message</div>')) !!}
@endif
<div class="row">
    <div class="col-12">
        <div class="content-header">{{@$title}}</div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <div class="card card-primary">
            <form class="form-horizontal" id="{{$frn_id}}" novalidate action="{{@$action}}" method="post"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card-body">
                    <!-- ./form sub header-->

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Project Name<i
                                class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Project Name"
                                value="{{ old('name',@$project->name) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Project Location<i
                                class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="location" name="location" class="form-control"
                                placeholder="Project Location" value="{{ old('location',@$project->location) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Project Type</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="type">
                                <option value="Residential" {{@$project->type == 'Residential' ? 'selected' :
                                    ''}}>Residential</option>
                                <option value="Hospitality" {{@$project->type == 'Hospitality' ? 'selected' :
                                    ''}}>Hospitality</option>
                                <option value="Retail" {{@$project->type == 'Retail' ? 'selected' : ''}}>Retail</option>
                                <option value="Education" {{@$project->type == 'Education' ? 'selected' : ''}}>Education
                                </option>
                                <option value="Office Spaces" {{@$project->type == 'Office Spaces' ? 'selected' :
                                    ''}}>Office Spaces</option>
                                <option value="Public Spaces" {{@$project->type == 'Public Spaces' ?
                                    'selected' : ''}}>Public Spaces</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ingredients" class="col-sm-3 control-label">Products</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <select id="sub_tag_id" name="sub_tag_id[]" multiple class="form-control select2"
                                    placeholder="Products" data-maximum-selection-length="10">
                                    @foreach($subtags as $subtag)
                                    <option value="{{ $subtag->id }}" {{(in_array($subtag->id, @$arr)) ? 'selected' :
                                        ''}}> {{ $subtag->display_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            
                            <div class="text-muted">
                                Default font size : 22.4px or 1.4rem
                            </div>
                            <form method="post">
                                <textarea id="description" name="description">{{@$project->description}}</textarea>
                              </form>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Slug</label>
                        <div class="col-sm-6">
                            <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug"
                                value="{{ old('slug',@$project->slug) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Sequence</label>
                        <div class="col-sm-6">
                            <input type="number" id="sequence" name="sequence" class="form-control" placeholder="Sequence"
                                value="{{ old('sequence',@$project->sequence) ?? 1 }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Block Column</label>
                        <div class="col-sm-6">
                            <select name="block_column" class="form-control form-control-sm">
                                <option value="1" {{ @$project->block_column == 1 ? 'selected' : '' }}>1</option>
                                <option value="2" {{ @$project->block_column == 2 ? 'selected' : '' }}>2</option>
                                <option value="3" {{ @$project->block_column == 3 ? 'selected' : '' }}>3</option>
                            </select>
                            <div class="text-muted">
                                Only for Desktop Layout. Recommended, For Wide Images, use Larger number, for Tall
                                Images use smaller.
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Gallary</label>
                        <div class="col-sm-5 ">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input type="hidden" name="photo" id="photo" value="">
                                    <input type="file" name="gallary[]" multiple id="imagefilegallary" accept="image/*"
                                        class="file-input">

                                </div>
                                <input type="text" class="form-control" disabled placeholder="Upload Image" value="">
                                <input type="hidden" name="oldPhoto" value="">
                                <div class="input-group-append">
                                    <button class="file-input-browse btn btn-dark" type="button"><i
                                            class="glyphicon glyphicon-search"></i> Browse</button>
                                </div>

                            </div>
                            <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                        </div>

                    </div> --}}




                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Project Images</label>
                        <div class="col-sm-9">
                            <label id="business-projectImage-label" style="font-size: medium;" for=""
                                class="col-form-label hide">Project Images</label>
                            <input id="filesInput" class="businessEvent" type="file" name="projectImages[]"
                                accept="image/*" style="background-color: transparent !important;" multiple>
                            <table id="projectImages-table" class="table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th style="width: 30px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($projectImages != null && count($projectImages) > 0)
                                    @foreach($projectImages as $key => $projectImage)
                                    <tr id="projectImage-table-{{$key}}">
                                        <td>{{$projectImage->image}}</td>
                                        <td style="width: 30px;">
                                            <i class="fa fa-trash" onclick="removeProject({{$key}})"
                                                style="cursor: pointer; font-size: 22px;"></i>
                                            <input class="hide" type="text" name="fileName[]" value="{{$projectImage->image}}">
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr id="noProjectImagesRow">
                                        <td colspan="2">
                                            <p style="text-align: center;">No Project Images</p>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
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
@section('extra_js')
<script src="https://cdn.tiny.cloud/1/papuga16njammqnfbaea3th0zmvlmdfd9wrmi5qfofkba6ho/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>    
    tinymce.init({
        selector: 'textarea#description',
        plugins: [],
        toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | ' +
        'align numlist bullist |  forecolor backcolor removeformat | lineheight outdent indent',
        font_size_formats: "0.6rem 0.8rem 1rem 1.2rem 1.4rem 1.6rem 1.8rem 2rem 2.2rem 2.4rem 2.6rem 2.8rem 3rem",
        menubar: false,
        content_style: `
            body {
                font-size: 1.4rem;
                letter-spacing: .05625rem;
                font-family: sans-serif;
            }
        `
    });

    let noOfProductImages = {{count($projectImages)}};
    $(document).ready(function() {
        $('body').on("change", '.businessEvent', function(event) {
            uploadFile(event.target.files, 'uploads/projects').subscribe({
                next : (res) => {
                    if ($(`#noProjectImagesRow`).length) {
                        $(`#noProjectImagesRow`).remove();
                    }
                    const fileNames = res?.fileNames || [];
                    if (fileNames) {
                        fileNames.forEach(fileName => {
                            $("#projectImages-table tbody").append(`
                                <tr id="projectImage-table-${noOfProductImages}">
                                    <td> ${fileName}</td>
                                    <td>
                                        <i class="fa fa-trash" onclick="removeProject(${noOfProductImages})" style="cursor: pointer; font-size: 22px;"></i>
                                        <input class="hide" type="text" name="fileName[]" value="${fileName}">
                                    </td>
                                </tr>`);
                            noOfProductImages++;
                        });
                    }
                    document.getElementById('filesInput').value = "";
                },
                error: err => console.error('Error occurred:', err)
            });
        });
    });

    function removeProject(ProjectNo) {
        $(`#projectImage-table-${ProjectNo}`).remove();
    }

    $(document).ready(function() {
        $('input[name="name"]').on('input', function() {
            let variantName = $(this).val();
            let formattedSlug = variantName.toLowerCase().replace(/\s+/g, '-');

            $('input[name="slug"]').val(formattedSlug);
        });
    });
</script>
@stop
