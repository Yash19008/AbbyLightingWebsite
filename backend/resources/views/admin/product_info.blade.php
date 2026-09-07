@extends('admin.page')

@section('title',$title)

@section('content_header')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">

    </div>
</div>
@stop
@section('content')@if($errors->any())
{!! implode('', $errors->all('<div>:message</div>')) !!}
@endif
<div class="row">
    <!-- About Me Box Start-->
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-body">
                <div class="row">
                    <h3 style="margin-bottom:30px">Basic Product Information</h3>
                    <div class="row p-4 text-left">
                        <div class="col-md-4">
                            <b>Product Name</b>
                            <p class="product_name" id="field_1_1">{{@$product->title}}</p>

                        </div>
                        <div class="col-md-4">
                            <b>Slug</b>
                            <p class="product_name" id="field_1_1">{{@$product->slug}}</p>

                        </div>
                        <div class="col-md-4">
                            <b>Tag</b>
                            <p class="product_name" id="field_1_1">{{@$tags ? @$tags : '-'}}</p>

                        </div>
                        <div class="col-md-4">
                            <b>Category</b>
                            <p class="product_name" id="field_1_1">{{@$product->category->title}}</p>

                        </div>
                        <div class="col-md-4">
                            <b>Number of Variant</b>
                            <p class="product_name" id="field_1_1">{{@$variant_no ? @$variant_no : 0}}</p>

                        </div>
                        @if(@$product->featured_image)
                        <div class="col-md-4">
                            <b>Thumbnail Image</b>
                            <p class="product_name" id="field_1_1">
                                <img src="{{ @$product->featured_image ? asset('storage/uploads/products/'.@$product->featured_image) : asset('images/default.png') }}" height="100">
                            </p>
                        </div>
                        @endif
                    </div>
                    <div class="row p-4 text-left">
                        <b>Banner Images</b>
                        @if(@$product_image)
                        <div class="flex gap-2 mb-4" style="display:flex">
                            @foreach($product_image as $key=>$val)
                            <span class="input-group-addon file-input-img-span" style="overflow: hidden;">
                                <img src="{{ @$val->image ? asset('storage/uploads/products/'.@$val->image) : asset('images/default.png') }}" height="100">
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="row p-4 text-left">
                        <b>Icons</b>
                        @if(@$icons)
                        <div class="flex gap-2 mb-4" style="display:flex">
                            @foreach($icons as $icon)
                            <span class="input-group-addon file-input-img-span" style="overflow: hidden;">
                                <img src="{{ @$icon ? asset('storage/uploads/icons/'.@$icon) : asset('images/default.png') }}" height="100">
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="row p-4 text-left">
                        <b>Optional Icons</b>
                        @if(@$optional_icons)
                        <div class="flex gap-2 mb-4" style="display:flex">
                            @foreach($optional_icons as $icon)
                            <span class="input-group-addon file-input-img-span" style="overflow: hidden;">
                                <img src="{{ @$icon ? asset('storage/uploads/icons/'.@$icon) : asset('images/default.png') }}" height="100">
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @if(count($product_att) > 0)
                    <div class="row p-4 text-left">
                        @php $attr_group_id = $product_att[0]->group_id ; @endphp
                        <p class="header-line-through attribute-group-header">{{ucfirst(@$product_att[0]->title)}}</p>
                        @foreach($product_att as $att)
                        @if(@$attr_group_id != $att->group_id)
                        <div class="col-12">
                        </div>
                        <p class="header-line-through attribute-group-header">{{ucfirst(@$att->title)}}</p>
                        @endif
                        <div class="col-md-4">
                            <b>{{@$att->attribute->attribute_name}}</b>
                            <p class="product_name" id="field_1_1">{{@$att->value ? @$att->value : '-'}}</p>
                        </div>
                        @php $attr_group_id = $att->group_id ; @endphp
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @foreach($variant as $key=>$row)
        <div class="card my-2 text-center variant-wrapper">
            <form method="post" action="javascript:;" id="add_new_variant_form" enctype="multipart/form-data">
                @csrf
                <div class="row p-4 text-left">
                    {{-- <div class="col-4 mb-2">
                        <label>Product</label>
                        <p class="varint_name" id="field_1_1">{{@$row->product->title}}</p>
                </div> --}}

                <div class="col-12 mb-2">
                    <label>Variant ID</label>
                    <p class="varint_name" id="field_1_1">{{@$row->id}}</p>
                </div>


                <div class="col-4 mb-2">
                    <label>Variant Name</label>
                    <p class="varint_name" id="field_1_1">{{@$row->variant_name}}</p>
                </div>

                <div class="col-12 mb-2">
                    <label>Slug</label>
                    <p class="varint_name" id="field_1_1">{{@$row->slug}}</p>
                </div>

                <div class="col-4 mb-2">
                    <label>LED Fitted</label>
                    <p class="varint_name" id="field_1_1">{{@$row->led_fitted}}</p>
                </div>

                <div class="col-4 mb-2">
                    <label>Co-related Colour Temprature</label>
                    <div class="row p-0 m-0">
                        <div class="col-6 p-0 m-0 pr-1">
                            <p class="varint_name" id="field_1_1">{{@$row->co_related_color}}</p>
                        </div>
                        <div class="col-6 p-0 m-0 pl-1">
                            <p class="varint_name" id="field_1_1">{{@$row->co_related_color_code}}</p>
                        </div>
                    </div>
                </div>

                <div class="col-4 mb-2">
                    <label>Delivered Lumens</label>
                    <p class="varint_name" id="field_1_1">{{@$row->lumens}}</p>
                </div>

                <div class="col-4 mb-2">
                    <label>Efficacy (System - lm/watt)</label>
                    <p class="varint_name" id="field_1_1">{{@$row->efficacy}}</p>
                </div>

                <div class="col-4 mb-2">
                    <label>Beam Angle (2xFWHM)°</label>
                    <div class="row p-0 m-0">
                        <div class="col-6 p-0 m-0 pr-1">
                            <p class="varint_name" id="field_1_1">{{@$row->beam_angle}}</p>
                        </div>
                        <div class="col-6 p-0 m-0 pl-1">
                            <p class="varint_name" id="field_1_1">{{@$row->beam_angle_code}}</p>
                        </div>
                    </div>
                </div>

                <div class="col-4 mb-2">
                    <label>LED Power watts</label>
                    <p class="varint_name" id="field_1_1">{{@$row->led_power_watts}}</p>
                </div>

                <div class="col-4 mb-2">
                    <label>System Power watts</label>
                    <p class="varint_name" id="field_1_1">{{@$row->system_power_watts}}</p>
                </div>

                <div class="col-4 mb-2">
                    <label>Operating Voltage Vin</label>
                    <p class="varint_name" id="field_1_1">{{@$row->operating_voltage}}</p>
                </div>

                <div class="col-4 mb-2">
                    <label>Power factor p.f.</label>
                    <p class="varint_name" id="field_1_1">{{@$row->power_factor}}</p>
                </div>

                <div class="col-4 mb-2">
                    <label>Line Diagram</label>
                    <p class="varint_name" id="field_1_1"><img src="{{asset('storage/uploads/products/'.@$row->line_diagram)}}" width="100px" height="100px"></p>
                </div>

                <div class="col-4 mb-2">
                    <label>Custom Specsheet</label>
                    <p class="varint_name" id="field_1_1"><a href="{{asset('storage/uploads/products/'.@$row->custom_specsheet)}}" target="_blank">{{$row->custom_specsheet}}</a></p>
                </div>

                {{-- <div class="col-4 mb-2">
                        <label>Photometry File</label>
                        <p class="varint_name" id="field_1_1">
                            @if(@$row->photometry_file)
                            <a href="{{asset('storage/uploads/products/'.@$row->photometry_file)}}"
                target="_blank">Click Here</a>
                @else
                NA
                @endif
                </p>
        </div> --}}

        @if(count($row->vectorImages) > 0)
        <div class="col-12 mb-2">
            <label>Photometry Files</label>
            <div class="flex gap-2 mb-4" style="display:flex">
                @foreach($row->vectorImages as $key=>$val)
                <span class="input-group-addon file-input-img-span" style="overflow: hidden;">
                    <img src="{{ @$val->file ? asset('storage/uploads/product_variant_images/'.@$val->file) : asset('images/default.png') }}" height="100">
                </span>
                @endforeach
            </div>
        </div>
        @endif

        @if(count($row->iesFiles) > 0)
        <div class="row p-4 text-left">
            <div class="col-12">
                <label>IES Images</label>
                <table id="iesFiles-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-left">File Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($row->iesFiles as $val)
                        <tr id="noIesFilesRow">
                            <td><a href="{{asset('storage/uploads/product_variant_ies_files/'.@$val->file)}}" target="_blank">{{$val->file_name}}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="col-md-12 text-right mt-4">
            <input type="hidden" name="product_id" value="{{@$product->id}}">
            <a class="remove_variant btn btn-danger pull-left delete-variant" data-variant_id="{{@$row->id}}" data-product-id="{{@$row->product_id}}">Delete</a>
            <a class="btn btn-primary btn edit-variant" data-variant_id="{{@$row->id}}" data-product-id="{{@$row->product_id}}">Edit</a>
        </div>
    </div>
    </form>
</div>
@endforeach
<div class="card my-2">
    <h3 style="margin-top:10px" class=" text-center">Add New Variant</h3>
    <small class="form-text text-danger pl-4">Note : Add multiple attributes with comma seprated Eg. Black,Red,Blue</small>
    <form method="post" action="{{route('product_admin.variant_insert')}}" id="add_new_variant_form" enctype="multipart/form-data">
        @csrf
        <div class="row p-4 text-left">
            <div class="col-4 mb-2 hide">
                <label>Product</label>
                <input type="text" name="product_name" class="form-control add_variant" value="{{@$product->title}}">
            </div>

            <div class="col-4 mb-2">
                <label>Variant Name</label>
                <input type="text" name="variant_name" class="form-control add_variant">
            </div>

            <div class="col-4 mb-2">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control add_variant">
                @if($errors->has('slug'))
                <div class="text-danger">{{ $errors->first('slug') }}</div>
                @endif
            </div>


            <div class="col-4 mb-2">
                <label>LED Fitted</label>
                <input type="text" name="led_fitted" class="form-control add_variant">
            </div>

            <div class="col-4 mb-2">
                <label>Co-related Colour Temprature</label>
                <div class="row p-0 m-0">
                    <div class="col-6 p-0 m-0 pr-1">
                        <input type="text" name="co_related_color" class="form-control add_variant" placeholder="Values">
                    </div>
                    <div class="col-6 p-0 m-0 pl-1">
                        <input type="text" name="co_related_color_code" class="form-control add_variant" placeholder="Code">
                    </div>
                    <small class="form-text text-muted pl-0">Add values & codes in same order</small>
                </div>
            </div>

            <div class="col-4 mb-2">
                <label>Delivered Lumens</label>
                <input type="text" name="lumens" class="form-control add_variant">
            </div>

            <div class="col-4 mb-2">
                <label>Efficacy (System - lm/watt)</label>
                <input type="text" name="efficacy" class="form-control add_variant">
            </div>

            <div class="col-4 mb-2">
                <label>Beam Angle (2xFWHM)°</label>
                <div class="row p-0 m-0">
                    <div class="col-6 p-0 m-0 pr-1">
                        <input type="text" name="beam_angle" class="form-control add_variant" placeholder="Values">
                    </div>
                    <div class="col-6 p-0 m-0 pl-1">
                        <input type="text" name="beam_angle_code" class="form-control add_variant" placeholder="Code">
                    </div>
                    <small class="form-text text-muted pl-0">Add values & codes in same order</small>
                </div>
            </div>

            <div class="col-4 mb-2">
                <label>LED Power watts</label>
                <input type="text" name="led_power_watts" class="form-control add_variant">
            </div>

            <div class="col-4 mb-2">
                <label>System Power watts</label>
                <input type="text" name="system_power_watts" class="form-control add_variant">
            </div>

            <div class="col-4 mb-2">
                <label>Operating Voltage Vin</label>
                <input type="text" name="operating_voltage" class="form-control add_variant">
            </div>

            <div class="col-4 mb-2">
                <label>Power factor p.f.</label>
                <input type="text" name="power_factor" class="form-control add_variant">
            </div>

            <div class="col-4 mb-2">
                <label>Line Diagram</label>
                <input type="file" name="line_diagram" class="form-control">
            </div>

            <div class="col-4 mb-2">
                <label>Custom Specsheet</label>
                <input type="file" name="custom_specsheet" class="form-control">
            </div>

            {{-- <div class="col-4 mb-2">
                        <label>Photometry File</label>
                        <input type="file" id="photometry_file" name="photometry_file" class="form-control">
                    </div> --}}
        </div>

        <div class="row p-4 text-left">
            <div class="col-12">
                <label id="business-varientImage-label" class="col-form-label mr-4">Photometry Files <span class="font-small-1">(Upto 4 files)</span></label>
                <input id="varientImage-0" class="businessVarientImage" type="file" name="varientImages[0][item]" accept="image/*" style="background-color: transparent !important;">
                <table id="varientImages-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-left">File Name</th>
                            <th style="width: 30px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="noVarientImagesRow">
                            <td colspan="2">
                                <p style="text-align: center;">No Photometry Files</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row p-4 text-left">
            <div class="col-12">
                <label id="business-iesFile-label" class="col-form-label mr-4">IES Files</label>
                <input id="iesFile-0" class="businessIesFile" type="file" name="iesFiles[0][item]" accept="*/*" style="background-color: transparent !important;">
                <table id="iesFiles-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-left">File Name</th>
                            <th style="width: 30px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="noIesFilesRow">
                            <td colspan="2">
                                <p style="text-align: center;">No IES Files</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row p-4 ">
            <div class="col-12 p-3">
                <input type="hidden" name="product_id" value="{{@$product->id}}">
                <button class="btn btn-primary pull-right" type="submit" style="display: inline-block;">Save
                    Variant</button>
            </div>
        </div>
    </form>
</div>
</div>
<input type="hidden" id="hdn" value="{{$tbl}}">
@stop

@section('extra_js')

<script>
    let noOfVarientImages = 0;
    let noOfIesFiles = 0;
    $(document).ready(function() {
        $('body').on("change", '.businessVarientImage', function() {
            if ($(`#noVarientImagesRow`).length) {
                $(`#noVarientImagesRow`).remove();
            }

            var fileName = $(this).val();
            let prevElem = $("#varientImage-" + noOfVarientImages);
            $(prevElem).addClass(`hide`);

            fileName = getFile(fileName);
            $("#varientImages-table tbody").append(`
                    <tr id="varientImage-table-${noOfVarientImages}">
                        <td> ${fileName}</td>
                        <td><i class="fa fa-trash" onclick="removeVarientImage(${noOfVarientImages})" style="cursor: pointer; font-size: 22px;"></i></td>
                    </tr>`);

            noOfVarientImages++;
            if (noOfVarientImages <= 3) {
                $("#business-varientImage-label").after(`<input id="varientImage-${noOfVarientImages}" class="businessVarientImage" type="file" name="varientImages[${noOfVarientImages}][item]" accept="image/*" style="background-color: transparent !important;">`);
            }
        });

        $('body').on("change", '.businessIesFile', function() {
            if ($(`#noIesFilesRow`).length) {
                $(`#noIesFilesRow`).remove();
            }

            var fileName = $(this).val();
            let prevElem = $("#iesFile-" + noOfIesFiles);
            $(prevElem).addClass(`hide`);

            fileName = getFile(fileName);
            $("#iesFiles-table tbody").append(`
                    <tr id="iesFile-table-${noOfIesFiles}">
                        <td> ${fileName}</td>
                        <td><i class="fa fa-trash" onclick="removeIesFile(${noOfIesFiles})" style="cursor: pointer; font-size: 22px;"></i></td>
                    </tr>`);

            noOfIesFiles++;
            $("#business-iesFile-label").after(`<input id="iesFile-${noOfIesFiles}" class="businessIesFile" type="file" name="iesFiles[${noOfIesFiles}][item]" accept="*/*" style="background-color: transparent !important;">`);

        });

        var productNameSlug = "{{ @$product->title }}".toLowerCase().replace(/\s+/g, '-');
        $('input[name="slug"]').val(productNameSlug);
        $('input[name="variant_name"]').on('input', function() {
            let variantName = $(this).val();
            let formattedSlug = productNameSlug +"-"+variantName.toLowerCase().replace(/\s+/g, '-');

            $('input[name="slug"]').val(formattedSlug);
        });

    });

    function removeVarientImage(varientImageNo) {
        $(`#varientImage-table-${varientImageNo}`).remove();
        $(`#varientImage-${varientImageNo}`).remove();
        noOfVarientImages--;
        if (noOfVarientImages == 3) {
            $("#business-varientImage-label").after(`<input id="varientImage-${noOfVarientImages}" class="businessVarientImage" type="file" name="varientImages[${noOfVarientImages}][item]" accept="image/*" style="background-color: transparent !important;">`);
        }
    }

    function removeIesFile(iesFileNo) {
        $(`#iesFile-table-${iesFileNo}`).remove();
        $(`#iesFile-${iesFileNo}`).remove();
    }

    function getFile(filePath) {
        return filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[0];
    }
</script>
@endsection
