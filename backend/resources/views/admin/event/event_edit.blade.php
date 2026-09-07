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
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Name<i class="text-danger">*</i></label>
                        <div class="col-sm-5">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Name"
                                value="{{ old('name',@$event->name) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSource" class="col-sm-3 control-label">Source / Publication</label>
                        <div class="col-sm-5">
                            <input type="text" id="source" name="source" class="form-control" placeholder="e.g., ARCHITECTURAL DIGEST, ELLE DECOR INDIA"
                                value="{{ old('source',@$event->source) }}">
                            <small class="form-text text-muted">Publication or source name for the news article</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSourceLink" class="col-sm-3 control-label">Source Link</label>
                        <div class="col-sm-9">
                            <input type="url" id="source_link" name="source_link" class="form-control" placeholder="https://..."
                                value="{{ old('source_link',@$event->source_link) }}">
                            <small class="form-text text-muted">External URL to the original article</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <div class="text-muted">
                                Default font size : 22.4px or 1.4rem
                            </div>
                            <form method="post">
                                <textarea id="description" name="description">{{@$event->description}}</textarea>
                              </form>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Slug<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug"
                                value="{{ old('slug',@$event->slug) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Location<i
                                class="text-danger">*</i></label>
                        <div class="col-sm-5">
                            <input type="text" id="location" name="location" class="form-control" placeholder="Location"
                                value="{{ old('location',@$event->location) }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Event Images</label>
                        <div class="col-sm-9">
                            <label id="business-eventImage-label" style="font-size: medium;" for=""
                                class="col-form-label hide">Event Images</label>
                            <input id="filesInput" class="businessEvent" type="file" name="eventImages[]"
                                accept="image/*" style="background-color: transparent !important;" multiple>
                            <table id="eventImages-table" class="table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th style="width: 30px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($eventImages != null && count($eventImages) > 0)
                                    @foreach($eventImages as $key => $eventImage)
                                    <tr id="eventImage-table-{{$key}}">
                                        <td>{{$eventImage->image}}</td>
                                        <td style="width: 30px;">
                                            <i class="fa fa-trash" onclick="removeEvent({{$key}})"
                                                style="cursor: pointer; font-size: 22px;"></i>
                                            <input class="hide" type="text" name="fileName[]"
                                                value="{{$eventImage->image}}">
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr id="noEventImagesRow">
                                        <td colspan="2">
                                            <p style="text-align: center;">No Event Images</p>
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

    let noOfEventImages = {{count($eventImages)}};
    $(document).ready(function() {

        $('body').on("change", '.businessEvent', function() {

            uploadFile(event.target.files, 'uploads/events').subscribe({
                next : (res) => {
                    if ($(`#noEventImagesRow`).length) {
                        $(`#noEventImagesRow`).remove();
                    }
                    const fileNames = res?.fileNames || [];
                    if (fileNames) {
                        fileNames.forEach(fileName => {
                            $("#eventImages-table tbody").append(`
                                <tr id="eventImage-table-${noOfEventImages}">
                                    <td> ${fileName}</td>
                                    <td>
                                        <i class="fa fa-trash" onclick="removeEvent(${noOfEventImages})" style="cursor: pointer; font-size: 22px;"></i>
                                        <input class="hide" type="text" name="fileName[]" value="${fileName}">
                                    </td>
                                </tr>`);
                            noOfEventImages++;
                        });
                    }
                    document.getElementById('filesInput').value = "";
                },
                error: err => console.error('Error occurred:', err)
            });
        });

    });

    function removeEvent(EventNo) {
        $(`#eventImage-table-${EventNo}`).remove();
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
