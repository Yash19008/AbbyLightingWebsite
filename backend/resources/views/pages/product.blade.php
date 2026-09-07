@extends('layout.web', ['theme' => 'light'])

@push('css')
@vite(['resources/scss/project-internal.scss'])
@endpush
@section("title", "$product->title - $variant?->variant_name | Abby Lighting")
@section('page-content')
<section class="slider primary-bg-color position-relative" style="margin-top: 0px !important">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($product->productImages as $productImage)
            <div class="carousel-item active">
                <img src="{{asset('storage/uploads/products/'.@$productImage->image)}}" alt="" class="img-fluid">
            </div>
            @endforeach
        </div>
        @if(count($product->productImages) > 1)
            <button class="carousel-control-prev mx-3" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="prev">
                <svg fill="#fff" width="16px" height="25px" viewBox="0 0 16.971 25.459" class="">
                    <path id="arrow_4L" d="M0,12.024H0L12.02,0l3.536,3.536L7.069,12.023l8.487,8.487L12.02,24.044Z"
                        transform="translate(0.707 0.707)"></path>
                </svg>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next mx-3" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="next">
                <svg fill="#fff" width="16px" height="25px" viewBox="0 0 16.973 25.459" class="">
                    <path id="arrow_4R" d="M0,20.509l8.488-8.487L0,3.536,3.536,0,15.557,12.021l0,0h0L3.535,24.044Z"
                        transform="translate(0.708 0.707)"></path>
                </svg>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>
    {{-- <p class="go-back-link position-absolute" onclick="goBack()">View more {{$subtag_display_name != '' ? 'from ' . $subtag_display_name . ' family' : '' }}</p> --}}
</section>
<section class="stupid-padding-mid-half details-section mt-4">
    <div>
        <div class="row">
            <div class="col-md-5 col-sm-12 details">
                <div class="d-md-none" style="margin-bottom: 1.5rem;">
                    <div class="w-100 button-wrapper">
                        <a href="{{route('product.download-pdf', $variant->id)}}" target="_blank" class="button donwload-btns w-100 m-0">
                            <p>Download Data Sheet</p>
                        </a>
                    </div>
                    <div class="w-100 button-wrapper mt-3">
                        <a href="#" class="button donwload-btns w-100 m-0">
                            <p onclick="goBack()">Back {{$subtag_display_name != '' ? 'to ' . $subtag_display_name . ' family' : '' }}</p>
                        </a>
                    </div>
                </div>
                <h1 class="product-title section-title bolder pt-0 mb-0">
                    {{$product->title}} <span class="text-uppercase">| {{$variant?->variant_name ?? ''}}</span>
                </h1>
                @if(count($product_att) > 0)
                @php $attr_group_id = $product_att[0]->group_id ; @endphp
                <p class="section-text line-ht-1-8  fs-1 bolder mb-0 pb-0">{{ucfirst(@$product_att[0]->title)}}</p>
                @foreach($product_att as $product_key => $att)
                @if(@$attr_group_id != $att->group_id)
                <div class="col-12">
                </div>
                <p class="section-text line-ht-1-8  fs-1 bolder mb-0 header pb-0">{{ucfirst(@$att->title)}}</p>
                @endif
                @if(@$att->attribute->is_visible == 'yes')
                <p
                    class="section-text line-ht-1-8  fs-1 p-0 m-0  {{@$attr_group_id == $att->group_id && $product_key != 0 ? 'border-top-top' : ''}} ">
                    <span class="primary-color">{{@$att->attribute->attribute_name}}</span>
                    {{@$att->value ? @$att->value : '-'}}
                </p>
                @endif
                @php $attr_group_id = $att->group_id ; @endphp
                @endforeach
                @endif
            </div>
            <div class="col-md-7 col-sm-12 images">
                <div class="row d-none d-md-flex">
                    <div class="col-12 col-sm-12">
                        <div class="w-100 button-wrapper">
                            <a href="{{route('product.download-pdf', $variant->id)}}" target="_blank" class="button donwload-btns">
                                <p>Download Data Sheet</p>
                            </a>
                            <a href="#" class="button donwload-btns">
                                <p onclick="goBack()">Back {{$subtag_display_name != '' ? 'to ' . $subtag_display_name . ' family' : '' }}</p>
                            </a>
                        </div>
                    </div>
                </div>
                @if(isset($icons))
                <div class="float-start w-100 icon-parent" style="margin-top:3rem">
                    @foreach($icons as $icon)
                    <img src="{{asset('storage/uploads/icons/'.@$icon->icon)}}" alt=""
                        class="img-fluid float-start icons px-2 mt-3">
                    @endforeach
                </div>
                @endif
                @if(isset($optional_icons))
                <div id="optional-icons" class="float-start w-100 mt-3 icon-parent">
                    <div id="left-bracket-open">
                        <span>Optional</span>
                        <div></div>
                    </div>
                    @foreach($optional_icons as $optional_icon)
                    <img src="{{asset('storage/uploads/icons/'.@$optional_icon->icon)}}" alt=""
                        class="img-fluid float-start icons px-2 py-1 {{$loop->last ? 'last-icon' : ''}}">
                    @endforeach
                    <div id="right-bracket-open">
                        <div></div>
                    </div>
                </div>
                @endif
                <div class="p-4">
                    <img src="{{asset('storage/uploads/products/'.@$variant->line_diagram)}}" alt=""
                        class="ms-0 img-fluid" style="height:400px; width:400px; object-fit: contain;">
                </div>

                <div class="row p-4 ms-0 text-center">
                    <p class="section-text line-ht-1-8 fs-1-1 bolder mb-3 pb-0 text-start">Light Distribution</p>
                    @foreach($variant->variantFiles as $variantFile)
                    @if(@$variantFile->file_type === 'image')
                    <div class="col-md-6 col-lg-3 col-sm-12 text-center">
                        <img src="{{asset('storage/uploads/product_variant_images/'.@$variantFile->file)}}" alt=""
                            class="img-fluid">
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @include('partials.download-ies')
        </div>

        <div id="scrolling-tables">
            <div class="row first-table-div">
                <div class="col-12">
                    <p class="section-text line-ht-1-8  fs-1 bolder mb-0 pb-0">Ordering Details</p>
                    <div class="float-start me-3">
                        <p class="section-text line-ht-1-8  fs-1 p-0 m-0  float-start">Code Example:</p>
                    </div>
                    <div class="float-start">
                        <table class="table float-start ms-0 mb-0">
                            <thead>
                                <tr>
                                    <th class="section-text line-ht-1-8  fs-1">Name</th>
                                    <th class="section-text line-ht-1-8  fs-1">Watt</th>
                                    <th class="section-text line-ht-1-8  fs-1">CCT</th>
                                    <th class="section-text line-ht-1-8  fs-1">CRI</th>
                                    <th class="section-text line-ht-1-8  fs-1">Angle</th>
                                    <th class="section-text line-ht-1-8  fs-1">Finish</th>
                                    <th class="section-text line-ht-1-8  fs-1">Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="section-text line-ht-1-8  fs-1 bolder">{{$product->title}}</td>
                                    <td class="section-text line-ht-1-8  fs-1 bolder">{{$variant?->variant_name ?? ''}}
                                    </td>
                                    <td class="section-text line-ht-1-8  fs-1 bolder">{{$attr_table['CCT_CODE'][0]}}
                                    </td>
                                    <td class="section-text line-ht-1-8  fs-1 bolder">{{$attr_table['CRI'][0]}}</td>
                                    <td class="section-text line-ht-1-8  fs-1 bolder">{{$attr_table['ANGLE'][0]}}
                                    </td>
                                    <td class="section-text line-ht-1-8  fs-1 bolder">
                                        {{$attr_table['BODY_FINISH_CODE'][0]}}</td>
                                    <td class="section-text line-ht-1-8  fs-1 bolder">
                                        {{$attr_table['DIMMING_OPTION_CODE'][0]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <p class="section-subtext mb-0">Select one from each column with highlighted values to create the ordering
                code</p>


            <div class="row">
                <div class="col-12">
                    <table class="table float-start extra-table ms-0 text-center">
                        <thead>
                            <tr class="up-down-borders">
                                <th class="section-text line-ht-1-8  fs-1 text-center">
                                    NAME
                                </th>
                                <th class="section-text line-ht-1-8  fs-1 text-center">
                                    WATT
                                </th>
                                <th class="section-text line-ht-1-8  fs-1 p-0 fainted border-right-none text-center">
                                    LUMEN
                                </th>
                                <th class="section-text line-ht-1-8  fs-1 p-0 fainted text-center">
                                    EFFICACY
                                </th>
                                <th colspan="2" class="section-text line-ht-1-8  fs-1 text-center">
                                    CCT
                                </th>
                                <th class="section-text line-ht-1-8  fs-1 text-center">
                                    CRI
                                </th>
                                {{-- <th colspan="2" class="section-text line-ht-1-8  fs-1 text-center"> --}}
                                <th class="section-text line-ht-1-8  fs-1 text-center">
                                    ANGLE
                                </th>
                                <th colspan="2" class="section-text line-ht-1-8  fs-1 text-center">
                                    FINISH
                                </th>
                                <th colspan="2" class="section-text line-ht-1-8  fs-1 text-center">
                                    CONTROL
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="section-text line-ht-1-8  fs-1 bolder">{{$product->title}}</td>
                                <td class="section-text line-ht-1-8  fs-1 bolder">{{$variant?->variant_name ?? ''}}</td>
                                <td class="section-text line-ht-1-8  fs-1 fainted border-right-none">
                                    {{$attr_table['LUMENS'][0]}}</td>
                                <td class="section-text line-ht-1-8  fs-1 fainted">{{$attr_table['EFFICACY'][0]}}</td>
                                <td class="section-text line-ht-1-8  fs-1 border-right-none">{{$attr_table['CCT'][0]}}
                                </td>
                                <td class="section-text line-ht-1-8  fs-1"><span
                                        class="bolder">{{$attr_table['CCT_CODE'][0]}}</span></td>
                                <td class="section-text line-ht-1-8  fs-1"><span
                                        class="bolder">{{$attr_table['CRI'][0]}}</span></td>
                                <td class="section-text line-ht-1-8  fs-1">{{$attr_table['ANGLE'][0]}}
                                </td>
                                {{-- <td class="section-text line-ht-1-8  fs-1"><span
                                        class="bolder">{{$attr_table['ANGLE_CODE'][0]}}</span></td> --}}
                                <td class="section-text line-ht-1-8  fs-1 border-right-none">
                                    {{$attr_table['BODY_FINISH'][0]}} </td>
                                <td class="section-text line-ht-1-8  fs-1"><span
                                        class="bolder">{{$attr_table['BODY_FINISH_CODE'][0]}}</span></td>
                                <td class="section-text line-ht-1-8  fs-1 border-right-none">
                                    {{$attr_table['DIMMING_OPTION'][0]}}</td>
                                <td class="section-text line-ht-1-8  fs-1"><span
                                        class="bolder">{{$attr_table['DIMMING_OPTION_CODE'][0]}}</span></td>
                            </tr>

                            @for ($i = 1; $i < $maxRows; $i++) <tr>
                                <td class="section-text line-ht-1-8  fs-1"></td>
                                <td class="section-text line-ht-1-8  fs-1"></td>
                                @if(isset($attr_table['LUMENS'][$i]))
                                <td class="section-text line-ht-1-8  fs-1 fainted border-right-none">
                                    {{$attr_table['LUMENS'][$i]}}</td>
                                @else
                                <td class="section-text line-ht-1-8  fs-1 border-right-none"></td>
                                @endif
                                @if(isset($attr_table['EFFICACY'][$i]))
                                <td class="section-text line-ht-1-8  fs-1 fainted">{{$attr_table['EFFICACY'][$i]}}</td>
                                @else
                                <td class="section-text line-ht-1-8  fs-1"></td>
                                @endif

                                @if(isset($attr_table['CCT'][$i]))
                                <td class="section-text line-ht-1-8  fs-1 fainted border-right-none">
                                    {{$attr_table['CCT'][$i]}}</td>
                                <td class="section-text line-ht-1-8  fs-1">
                                    @if(isset($attr_table['CCT_CODE'][$i]))<span
                                        class="bolder">{{$attr_table['CCT_CODE'][$i]}}</span>@endif</td>
                                @else
                                <td class="section-text line-ht-1-8  fs-1 fainted border-right-none"></td>
                                <td class="section-text line-ht-1-8  fs-1"></td>
                                @endif
                                @if(isset($attr_table['CRI'][$i]))
                                <td class="section-text line-ht-1-8  fs-1">{{$attr_table['CRI'][$i]}}</td>
                                @else
                                <td class="section-text line-ht-1-8  fs-1"></td>
                                @endif
                                @if(isset($attr_table['ANGLE'][$i]))
                                <td class="section-text line-ht-1-8  fs-1 fainted">
                                    {{$attr_table['ANGLE'][$i]}}</td>
                                {{-- <td class="section-text line-ht-1-8  fs-1">
                                    @if(isset($attr_table['ANGLE_CODE'][$i]))<span
                                        class="bolder">{{$attr_table['ANGLE_CODE'][$i]}}</span>@endif</td> --}}
                                @else
                                <td class="section-text line-ht-1-8  fs-1"></td>
                                {{-- <td class="section-text line-ht-1-8  fs-1"></td> --}}
                                @endif
                                @if(isset($attr_table['BODY_FINISH'][$i]))
                                <td class="section-text line-ht-1-8  fs-1 border-right-none">
                                    {{$attr_table['BODY_FINISH'][$i]}}</td>
                                <td class="section-text line-ht-1-8  fs-1">
                                    @if(isset($attr_table['BODY_FINISH_CODE'][$i]))<span
                                        class="bolder">{{$attr_table['BODY_FINISH_CODE'][$i]}}</span>@endif
                                </td>
                                @else
                                <td class="section-text line-ht-1-8  fs-1 border-right-none"></td>
                                <td class="section-text line-ht-1-8  fs-1"></td>
                                @endif
                                @if(isset($attr_table['DIMMING_OPTION'][$i]))
                                <td class="section-text line-ht-1-8  fs-1 border-right-none">
                                    {{$attr_table['DIMMING_OPTION'][$i]}}</td>
                                <td class="section-text line-ht-1-8  fs-1">
                                    @if(isset($attr_table['DIMMING_OPTION_CODE'][$i]))<span
                                        class="bolder">{{$attr_table['DIMMING_OPTION_CODE'][$i]}}</span>@endif
                                </td>
                                @else
                                <td class="section-text line-ht-1-8  fs-1 border-right-none"></td>
                                <td class="section-text line-ht-1-8  fs-1"></td>
                                @endif
                                </tr>
                                @endfor
                                <tr class="up-down-borders">
                                    <td class="section-text line-ht-1-8  fs-1 bolder text-center">{{$product->title}}
                                    </td>
                                    <td class="section-text line-ht-1-8  fs-1 bolder text-center">
                                        {{$variant?->variant_name ?? ''}}</td>
                                    <td class="section-text line-ht-1-8  fs-1 fainted border-right-none"></td>
                                    <td class="section-text line-ht-1-8  fs-1 fainted"></td>
                                    <td colspan="2" class="section-text line-ht-1-8  fs-1 text-center"><span
                                            class="bolder w-100">{{$attr_table['CCT_CODE'][0]}}</span></td>
                                    <td class="section-text line-ht-1-8  fs-1 text-center"><span
                                            class="bolder w-100">{{$attr_table['CRI'][0]}}</span></td>
                                    <td class="section-text line-ht-1-8  fs-1 text-center"><span
                                            class="bolder w-100">{{$attr_table['ANGLE'][0]}}</span></td>
                                    <td colspan="2" class="section-text line-ht-1-8  fs-1 text-center"><span
                                            class="bolder w-100">{{$attr_table['BODY_FINISH_CODE'][0]}}</span>
                                    </td>
                                    <td colspan="2" class="section-text line-ht-1-8  fs-1 text-center"><span
                                            class="bolder w-100">{{$attr_table['DIMMING_OPTION_CODE'][0]}}</span>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</section>
@endsection

<script type="text/javascript">
    function goBack() {
        window.location.href = "{{ $subtag_slug ? route('products', $subtag_slug) : route('sub-tags')}}";
    }
</script>
