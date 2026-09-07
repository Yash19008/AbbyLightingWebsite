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
            <form class="form-horizontal" id="{{$frn_id}}" action="{{@$action}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card-body">
                    <!-- ./form sub header-->
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Product Name<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Product Name" value="{{ old('title',@$product->title) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Slug</label>
                        <div class="col-sm-6">
                            <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug" value="{{ old('slug',@$product->slug) }}">
                            @if($errors->has('slug'))
                            <div class="text-danger">{{ $errors->first('slug') }}</div>
                            @endif
                        </div>

                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Thumbnail Image<i class="text-danger">*</i></label>
                        <div class="col-sm-5 ">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input type="hidden" name="photo" id="photo" value="">
                                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="file-input">

                                </div>
                                <input type="text" class="form-control" disabled placeholder="Upload Image" value="{{@$product->featured_image}}">
                                <input type="hidden" name="oldPhoto" value="{{@$product->featured_image}}">
                                <div class="input-group-append">
                                    <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                </div>
                            </div>
                            <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                        </div>
                    </div>


                    <!--div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Banner Images</label>
                        <div class="col-sm-5 ">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input type="hidden" name="photo" id="photo" value="">
                                    <input type="file" name="gallary[]" multiple id="imagefilegallary" accept="image/*" class="file-input">

                                </div>
                                <input type="text" class="form-control" disabled placeholder="Upload Image" value="">
                                <input type="hidden" name="oldPhoto" value="">
                                <div class="input-group-append">
                                    <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                </div>

                            </div>
                            <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                        </div>

                    </div-->
                    <div class="form-group row">
                        <label for="ingredients" class="col-sm-2 control-label">Category<i class="text-danger">*</i></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <select id="category_id" name="category_id" class="form-control" placeholder="Category">
                                    @foreach($category as $cat)
                                    <option value="{{ $cat->id }}" {{($cat->id == @$product->category_id) ? "selected" : ""}}> {{ $cat->title }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="collection_ids" class="col-sm-2 control-label">Collection</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <select id="collection_ids" name="collection_ids[]" class="form-control select2" multiple="multiple" data-placeholder="Select Collections" style="width: 100%;">
                                    @foreach($collections as $col)
                                    <option value="{{ $col->id }}" {{ in_array($col->id, $selected_collections) ? "selected" : "" }}> {{ $col->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ingredients" class="col-sm-2 control-label">Icons<i class="text-danger">*</i></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <?php $icons_selected = explode(",", @$product->icons); ?>
                                <select id="icons" name="icons[]" multiple="multiple" class="form-control select2" placeholder="Icons" required>

                                    @foreach($icons as $icon)
                                    <option value="{{ $icon->id }}" {{ (in_array($icon->id, $icons_selected)) ? 'selected' : '' }}> {{ $icon->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ingredients" class="col-sm-2 control-label">Optional Icons</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <?php $optional_icons_selected = explode(",", @$product->optional_icons); ?>
                                <select id="optional_icons" name="optional_icons[]" multiple="multiple" class="form-control select2" placeholder="Optional Icons">

                                    @foreach($icons as $icon)
                                    <option value="{{ $icon->id }}" {{ (in_array($icon->id, $optional_icons_selected)) ? 'selected' : '' }}> {{ $icon->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ingredients" class="col-sm-2 control-label">Sub Tags<i class="text-danger">*</i></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <?php $selected = explode(",", @$product->sub_tag_ids); ?>
                                <select id="sub_tag_ids" name="sub_tag_ids[]" multiple="multiple" class="form-control select2" placeholder="Tags" required>

                                    @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ (in_array($tag->id, $selected)) ? 'selected' : '' }}> {{ $tag->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Number of Variants</label>
                        <div class="col-sm-6">
                            <input type="text" disabled id="number_of_variants" name="number_of_variants" class="form-control" placeholder="Number of Variants" value="{{@$variant_no ? @$variant_no : 0}}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label">Banner Images</label>
                        <div class="col-sm-10">
                            <label id="business-productImage-label" style="font-size: medium;" for="" class="col-form-label hide">Banner Images</label>
                            <input id="productImage-{{count($productImages)}}" class="businessProduct" type="file" name="productImages[{{count($productImages)}}][item]" accept="image/*" style="background-color: transparent !important;">
                            <table id="productImages-table" class="table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th style="width: 30px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($productImages != null && count($productImages) > 0)
                                    @foreach($productImages as $key => $productImage)
                                    <tr id="productImage-table-{{$key}}">
                                        <td>{{$productImage->image}}</td>
                                        <td style="width: 30px;"><i class="fa fa-trash" onclick="removeProduct({{$key}})" style="cursor: pointer; font-size: 22px;"></i></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr id="noProductImagesRow">
                                        <td colspan="2">
                                            <p style="text-align: center;">No Banner Images</p>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            @if ($productImages != null && count($productImages) > 0)
                            @foreach($productImages as $key => $productImage)
                            <input id="existingProduct-{{$key}}" type="text" name="existingProductImages[{{$key}}]" value="{{$productImage->image}}" class="hide">
                            @endforeach
                            @endif
                        </div>
                    </div>

                    @if(@$method != 'Edit')
                    @php $attr_group_id = null; @endphp
                    @foreach($attr as $key =>$val)
                    <div class="form-group row">
                        <p class="header-line-through attribute-group-header {{ $attr_group_id == $val->group->id ? 'd-none' : '' }}">{{ucfirst(@$val->group->title)}}</p>
                        <label for="inputName" class="col-sm-2 control-label">{{ucfirst(@$val->attribute_name)}}</label>
                        <div class="col-sm-6">
                            @if($val->values == null || trim($val->values) == '')
                            <input type="text" id="{{@$val->attribute_name}}" name="attribute_id_{{@$key}}[]" data-id="{{@$val->id}}" class="form-control attr-name" placeholder="" value="{{@$prod_att_val->value}}">
                            @else
                            @php $values_options = array_map('trim', explode(',', trim($val->values))); @endphp
                            <select id="{{@$val->attribute_name}}" class="form-control select2" name="attribute_id_{{@$key}}[]" data-id="{{@$val->id}}" placeholder="{{@$val->attribute_name}}" multiple="multiple">
                                <option value="">Select</option>
                                @foreach($values_options as $values_option)
                                <option value="{{$values_option}}">{{$values_option}}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                    </div>
                    @php $attr_group_id = $val->group->id; @endphp
                    @endforeach
                    @else
                    @php $attr_group_name = null; @endphp

                    @foreach($outerjoin as $key=>$value)
                    <div class="form-group row">
                        <p class="header-line-through attribute-group-header {{ $attr_group_name == $value->title ? 'd-none' : '' }}">{{ucfirst(@$value->title)}}</p>
                        <label for="inputName" class="col-sm-2 control-label">{{ucfirst(@$value->attribute_name)}}</label>
                        <div class="col-sm-6">
                            @if($value->values == null || trim($value->values) == '')
                            <input type="text" id="{{@$value->attribute_id}}" name="attribute_id_{{@$value->attribute_id}}[]" data-id="{{@$value->attribute_id}}" class="form-control attr-name" placeholder="" value="{{@$value->value}}">
                            @else
                            @php $values_options = array_map('trim', explode(',', trim($value->values))); @endphp
                            @php $selected_options = array_map('trim', explode(',', trim($value->value))); @endphp
                            <select id="{{@$value->attribute_name}}" class="form-control select2" name="attribute_id_{{@$value->attribute_id}}[]" data-id="{{@$value->id}}" placeholder="{{@$value->attribute_name}}" multiple="multiple">
                                <option value="">Select</option>
                                @foreach($values_options as $values_option)
                                <option value="{{$values_option}}" {{ (in_array($values_option, $selected_options)) ? 'selected' : '' }}>{{$values_option}}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                    </div>
                    @php $attr_group_name = $value->title; @endphp
                    @endforeach
                    @endif

                    <div class="form-group row" style="margin-top:10px">
                        <div class="offset-3 col-sm-8">
                            <button type="submit
                                " data-id="{{@$product->id}}" class="btn btn-dark update-submit">Submit</button>
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
    let noOfProductImages = {{count($productImages)}};
    $(document).ready(function() {

        $('body').on("change", '.businessProduct', function() {

            if ($(`#noProductImagesRow`).length) {
                $(`#noProductImagesRow`).remove();
            }

            var fileName = $(this).val();
            let prevElem = $("#productImage-" + noOfProductImages);
            $(prevElem).addClass(`hide`);

            fileName = getFile(fileName);
            $("#productImages-table tbody").append(`
                <tr id="productImage-table-${noOfProductImages}">
                    <td> ${fileName}</td>
                    <td><i class="fa fa-trash" onclick="removeProduct(${noOfProductImages})" style="cursor: pointer; font-size: 22px;"></i></td>
                </tr>`);

            noOfProductImages++;
            $("#business-productImage-label").after(`<input id="productImage-${noOfProductImages}" class="businessProduct" type="file" name="productImages[${noOfProductImages}][item]"  accept="image/*" style="background-color: transparent !important;">`);

        });

    });


    function removeProduct(ProductNo) {
        $(`#productImage-table-${ProductNo}`).remove();
        if ($(`#productImage-${ProductNo}`).length) {
            $(`#productImage-${ProductNo}`).remove();
        }
        if ($(`#existingProduct-${ProductNo}`).length) {
            $(`#existingProduct-${ProductNo}`).remove();
        }
    }

    function getFile(filePath) {
        return filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[0];
    }

    $(document).ready(function() {
        $('input[name="title"]').on('input', function() {
            let variantName = $(this).val();
            let formattedSlug = variantName.toLowerCase().replace(/\s+/g, '-');

            $('input[name="slug"]').val(formattedSlug);
        });
    });
    // $(document).on('click','.update-submit',function(){
    //     var form = $('#frm_product')[0];
    //     var formData = new FormData(form);
    //     var eduarray = [];
    //     $('.attr-name').each(function(index, el) {
    //         var att_id = $(this).attr('data-id');
    //         eduarray.push( $(this).val() );
    //     });
    //     var id = $(this).attr('data-id');
    //     console.log(id);
    //     formData.append('id',id);
    //     if($('#frm_product').valid()){
    //         $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    //         $.ajax({
    //             dataType: 'json',
    //             cache: false,
    //             contentType: false,
    //             processData: false,
    //             data: formData,
    //             type: 'post',
    //             url: siteUrl + '/product/update/'+id,
    //             success: function(obj)
    //             {
    //                 if (obj.code == 1)
    //                 {
    //                    location.reload();
    //                 }
    //             },
    //             error: function(obj) {
    //                 errormsg(csrf_error);
    //             },
    //         });
    //     }
    // })
</script>
@stop
