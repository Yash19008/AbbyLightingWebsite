@extends('admin.page')

@section('title', $title)

@section('content_header')
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="content-header">{{ @$title }}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
            <div class="card card-primary">
                <form class="product-form form-horizontal" id="{{ $frn_id }}" novalidate action="{{ @$action }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <!-- ./form sub header-->
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Display Name<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="display_name" name="display_name" class="form-control" placeholder="Display Name" value="{{ @$tag->display_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Name<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="{{ @$tag->name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Slug<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug" value="{{ @$tag->slug }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ingredients" class="col-sm-2 control-label">Tag<i class="text-danger">*</i></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <?php $selected_tags = $subTagsMapping; ?>
                                    <select id="tags" name="tags[]" multiple="multiple" class="form-control select2" placeholder="Tag" required>
    
                                        @foreach($tags as $ptag)
                                        <option value="{{ $ptag->id }}" {{ (in_array($ptag->id, $selected_tags)) ? 'selected' : '' }}> {{ $ptag->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Thumbnail Image<i class="text-danger">*</i></label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="file" id="imagefile" accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" id="disabled_file_image" disabled placeholder="Upload Image" value="{{ @$tag->image }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$tag->image }}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                    <a href="javascript:;" data-toggle="tooltip" title="delete" style="width: unset; height: 21px; margin-top: 8px; margin-left: 10px; visibility: hidden;"><i class="ft-trash font-medium-3"></i></a>
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>
                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$tag->id }}" data-img-type="image" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Hover Thumbnail Image</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="hover_file" id="imagefile" accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" disabled id="disabled_file_hover_image" placeholder="Upload Hover Image" value="{{ @$tag->hover_image }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$tag->hover_image }}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                    <a href="javascript:;" data-toggle="tooltip" title="delete" style="width: unset; height: 21px; margin-top: 8px; margin-left: 10px; visibility: hidden;"><i class="ft-trash font-medium-3"></i></a>
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$tag->id }}" data-img-type="hover_image" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Banner Image 1<i class="text-danger">*</i></label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="banner" id="imagefile" accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" disabled id="disabled_file_banner_image" placeholder="Upload Image" value="{{ @$tag->banner_image }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$tag->banner_image }}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$tag->id }}" data-img-type="banner_image" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Banner Image 2</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="banner_2" id="imagefile" accept="image/*" class="file-input banner_selector_input banner_selector_input_g">
                                        <input type="text" name="banner_2_remove" class="hide remove_input">

                                    </div>
                                    <input type="text" class="form-control banner_selector_input_g" disabled id="disabled_file_banner_image_2" placeholder="Upload Image" value="{{ @$tag->banner_image_2 }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$tag->banner_image_2 }}" class="banner_selector_input_g">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                    {{-- <a href="javascript:;" data-toggle="tooltip" title="delete" style="width: unset; height: 21px; margin-top: 8px; margin-left: 10px; visibility: {{ @$tag->banner_image_2 ? 'visible' : 'hidden' }};"><i class="ft-trash font-medium-3 banner_remove_button"></i></a> --}}
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$tag->id }}" data-img-type="banner_image_2" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Banner Image 3</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="banner_3" id="imagefile" accept="image/*" class="file-input banner_selector_input banner_selector_input_g">
                                        <input type="text" name="banner_3_remove" class="hide remove_input">

                                    </div>
                                    <input type="text" class="form-control banner_selector_input_g" disabled id="disabled_file_banner_image_3" placeholder="Upload Image" value="{{ @$tag->banner_image_3 }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$tag->banner_image_3 }}" class="banner_selector_input_g">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                    {{-- <a href="javascript:;" data-toggle="tooltip" title="delete" style="width: unset; height: 21px; margin-top: 8px; margin-left: 10px; visibility: {{ @$tag->banner_image_3 ? 'visible' : 'hidden' }};"><i class="ft-trash font-medium-3 banner_remove_button"></i></a> --}}
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$tag->id }}" data-img-type="banner_image_3" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Banner Image 4</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="banner_4" id="imagefile" accept="image/*" class="file-input banner_selector_input banner_selector_input_g">
                                        <input type="text" name="banner_4_remove" class="hide remove_input">

                                    </div>
                                    <input type="text" class="form-control banner_selector_input_g" disabled id="disabled_file_banner_image_4" placeholder="Upload Image" value="{{ @$tag->banner_image_4 }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$tag->banner_image_4 }}" class="banner_selector_input_g">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                    {{-- <a href="javascript:;" data-toggle="tooltip" title="delete" style="width: unset; height: 21px; margin-top: 8px; margin-left: 10px; visibility: {{ @$tag->banner_image_4 ? 'visible' : 'hidden' }};"><i class="ft-trash font-medium-3 banner_remove_button"></i></a> --}}
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$tag->id }}" data-img-type="banner_image_4" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Banner Image 5</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="banner_5" id="imagefile" accept="image/*" class="file-input banner_selector_input banner_selector_input_g">
                                        <input type="text" name="banner_5_remove" class="hide remove_input">

                                    </div>
                                    <input type="text" class="form-control banner_selector_input_g" disabled id="disabled_file_banner_image_5" placeholder="Upload Image" value="{{ @$tag->banner_image_5 }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$tag->banner_image_5 }}" class="banner_selector_input_g">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                    {{-- <a href="javascript:;" data-toggle="tooltip" title="delete" style="width: unset; height: 21px; margin-top: 8px; margin-left: 10px; visibility: {{ @$tag->banner_image_5 ? 'visible' : 'hidden' }};"><i class="ft-trash font-medium-3 banner_remove_button"></i></a> --}}
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$tag->id }}" data-img-type="banner_image_5" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>

                        <input type="hidden" name="remove_image" value="0">
                        <input type="hidden" name="remove_hover_image" value="0">
                        <input type="hidden" name="remove_banner_image" value="0">
                        <input type="hidden" name="remove_banner_image_2" value="0">
                        <input type="hidden" name="remove_banner_image_3" value="0">
                        <input type="hidden" name="remove_banner_image_4" value="0">
                        <input type="hidden" name="remove_banner_image_5" value="0">

                        <div class="form-group row">
                            <label for="youtube_url" class="col-sm-2 control-label">YouTube URL Link 1</label>
                            <div class="col-sm-6">
                                <input 
                                    type="text" 
                                    id="youtube_url" 
                                    name="youtube_url" 
                                    class="form-control" 
                                    placeholder="YouTube URL Example: https://www.youtube.com/watch?v=-UYVYYl4vn0" 
                                    value="{{ @$tag->youtube_url }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="youtube_url_link_2" class="col-sm-2 control-label">YouTube URL Link 2</label>
                            <div class="col-sm-6">
                                <input 
                                    type="text" 
                                    id="youtube_url_link_2" 
                                    name="youtube_url_link_2" 
                                    class="form-control" 
                                    placeholder="YouTube URL Example: https://www.youtube.com/watch?v=-UYVYYl4vn0" 
                                    value="{{ @$tag->youtube_url_link_2 }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="youtube_url_link_3" class="col-sm-2 control-label">YouTube URL Link 3</label>
                            <div class="col-sm-6">
                                <input 
                                    type="text" 
                                    id="youtube_url_link_3" 
                                    name="youtube_url_link_3" 
                                    class="form-control" 
                                    placeholder="YouTube URL Example: https://www.youtube.com/watch?v=-UYVYYl4vn0" 
                                    value="{{ @$tag->youtube_url_link_3 }}">
                            </div>
                        </div>

                        <div class="form-group row"> 
                            <label for="product_catalog" class="col-sm-2 control-label">Product Catalog (PDF)</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="file" 
                                            name="product_catalog" 
                                            id="product_catalog_file" 
                                            accept="application/pdf" 
                                            class="file-input catalog_selector_input">
                                    </div>
                                    
                                    {{-- Disabled input showing current PDF file name --}}
                                    <input type="text" 
                                        class="form-control catalog_selector_input" 
                                        disabled 
                                        id="disabled_file_product_catalog" 
                                        placeholder="Upload PDF" 
                                        value="{{ @$tag->product_catalog }}">
                                    
                                    {{-- Hidden field to keep old value --}}
                                    <input type="hidden" 
                                        name="oldProductCatalog" 
                                        value="{{ @$tag->product_catalog }}" 
                                        class="catalog_selector_input">

                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button">
                                            <i class="glyphicon glyphicon-search"></i> Browse
                                        </button>

                                        @if(!empty($tag->product_catalog))
                                            <a href="{{ asset('storage/uploads/product_catalogs/'.$tag->product_catalog) }}" 
                                            target="_blank" 
                                            class="btn btn-info">
                                            <i class="fa fa-eye"></i> View
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>
                            </div>

                            <div class="col-sm-1">
                                <span title="Remove PDF" class="btn btn-dark remove-tag-file">
                                    <i class="fa fa-times"></i>
                                </span>
                            </div>
                        </div>
                        {{-- hidden removal flag --}}
                        <input type="hidden" name="remove_product_catalog" value="0">

                        <div class="form-group row">
                            <label for="linked_sub_tags" class="col-sm-2 control-label">Linked Sub Tags</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <select id="linked_sub_tags" 
                                            name="linked_sub_tags[]" 
                                            multiple 
                                            class="form-control select2"
                                            placeholder="Select Sub Tags" 
                                            data-maximum-selection-length="10">

                                        @foreach($subtags as $st)
                                            {{-- Show all on Add, exclude itself on Edit --}}
                                            @if(!$tag || $st->id != $tag->id)
                                                <option value="{{ $st->id }}"
                                                    {{ in_array($st->id, $linkedSubTags ?? []) ? 'selected' : '' }}>
                                                    {{ $st->display_name ?? $st->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>




                        <input type="text" id="project_images" name="project_images" class="form-control hide" placeholder="Project Images" value="">
                        <div class="form-group row">
                            <label for="ingredients" class="col-sm-2 control-label">Project</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <select id="project_id" name="project_id" class="form-control" placeholder="Project">
                                        <option value=""> Select </option>
                                        @foreach ($project as $proj)
                                            <option value="{{ $proj->id }}" data-slug="{{ $proj->slug }}"> {{ $proj->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label for="ingredients" class="col-sm-2 control-label">Project Image</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <select id="project_image_id" name="project_image_id" class="form-control" placeholder="Project Image">
                                        <option value=""> Select </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-dark" onclick="addProjectImage()">Add</button>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <table id="projectImages-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Image Name</th>
                                            <th style="width: 30px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="noProductImagesRow">
                                            <td colspan="2">
                                                <p style="text-align: center;">No Project Images</p>
                                            </td>
                                        </tr>
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
    <script>
        let projectImages = @php echo($projectImages) @endphp;
        $(document).ready(function() {
            $('.banner_selector_input').on("change", function() {
                if ($(this).val()) {
                    $(this).closest('.input-group').find(".banner_remove_button").css('visibility', 'visible');
                } else {
                    $(this).closest('.input-group').find(".banner_remove_button").css('visibility', 'hidden');
                }
            });
            $('.banner_remove_button').on("click", function() {
                $(this).closest('.input-group').find('.banner_selector_input_g').val("");
                $(this).closest('.input-group').find('.remove_input').val("true");
                $(this).css('visibility', 'hidden');
            });


            $('#project_id').on('change', function() {
                const id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'get',
                    url: siteUrl + '/project-images/' + id,
                    success: function(obj) {
                        let options = "<option value=''> Select </option>"
                        if (obj?.project_images) {
                            obj.project_images.forEach(image => {
                                options += "<option value='" + image.id + "''> " + image.image + " </option>";
                            });
                        }
                        $("#project_image_id").html(options);
                    },
                    error: function(obj) {
                        errormsg(csrf_error);
                    },
                });
            });

            $(".product-form").on("submit", function(e) {
                e.preventDefault();

                $("#project_images").val(JSON.stringify(projectImages));
                var form = this;
                form.submit(); // submit bypassing the jQuery bound event
            });

            updateProjectTable();
        });

        function addProjectImage() {
            const project_id = $("#project_id").val();
            const project_image_id = $("#project_image_id").val();
            const project_name = $("#project_id option:selected").text();
            const project_slug = $("#project_id option:selected").attr('data-slug');
            const project_image_name = $("#project_image_id option:selected").text();
            if (project_id && project_image_id) {
                projectImages.push({
                    project_name,
                    project_slug,
                    project_id,
                    project_image_id,
                    project_image_name
                })
                updateProjectTable();
                resetProjectImageDropdowns();
            } else {
                alert('Select project image');
            }
        }

        function updateProjectTable() {
            let tableRows = `<tr>
                            <td colspan="2">
                                <p style="text-align: center;">No Project Images</p>
                            </td>
                        </tr>`;
            if (projectImages?.length) {
                tableRows = '';
                projectImages.forEach((projectImage, index) => {
                    tableRows += `<tr>
                        <td> ${projectImage.project_name}</td>
                        <td> ${projectImage.project_image_name}</td>
                        <td><i class="fa fa-trash" onclick="removeProjectImage(${index})" style="cursor: pointer; font-size: 22px;"></i></td>
                    </tr>`
                });
            }
            $("#projectImages-table tbody").html(tableRows);
        }

        function removeProjectImage(index) {
            projectImages.splice(index, 1);
            updateProjectTable();
        }

        function resetProjectImageDropdowns() {
            $('#project_id').val('');
            $('#project_image_id').val('');
        }

        // Delete Image
        $(".remove-tag-img").click(function(e) {
            e.preventDefault();

            var model_id = $(this).attr("data-id");
            var column = $(this).attr("data-img-type");

            $('[name="remove_' + column + '"]').val(1);
            $('#disabled_file_'+column).val("");
        });

        $(document).ready(function() {
            $('input[name="name"]').on('input', function() {
                let variantName = $(this).val();
                let formattedSlug = variantName.toLowerCase().replace(/\s+/g, '-');

                $('input[name="slug"]').val(formattedSlug);
            });
        });
    </script>
    <script>
    // Update text field when file is selected
    $(document).on('change', '#product_catalog_file', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#disabled_file_product_catalog').val(fileName);
        $("input[name='remove_product_catalog']").val("0"); // reset remove flag
    });

    // Handle Remove PDF (X button)
    $(document).on('click', '.remove-tag-file', function() {
        $('#disabled_file_product_catalog').val('');
        $('#product_catalog_file').val(''); // clear file input
        $("input[name='remove_product_catalog']").val("1"); // mark for deletion
        // ❌ Do NOT hide the button — keep it visible
    });
</script>



@stop
