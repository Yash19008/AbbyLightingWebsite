@extends('layout.print', ['theme' => 'light'])

@push('css')
@vite(['resources/scss/project-internal.scss'])
@endpush

@section('page-content')
<section class="pdf-section">
    <section class="header-section float-start w-100 mt-0 pb-2">
        <div class="float-start" style="width: 305px">
            <img src="{{ public_path('img/black-logo-1.png')}} " alt="Abby Lighting" />
        </div>
        <div class="float-end" style="width: calc(100% - 305px)">
            @if(isset($icons))
            <div class="float-end w-100 icon-parent">
                @php $icons = $icons->reverse(); @endphp
                @foreach($icons as $icon)
                <img src="{{asset('storage/uploads/icons/'.@$icon->icon)}}" alt=""
                    class="img-fluid float-end icons px-2">
                @endforeach
            </div>
            @endif
            @if(isset($optional_icons))
            <div id="optional-icons" class="float-start w-100 mt-3 icon-parent">
                <div id="right-bracket-open" class="float-end">
                    <div></div>
                </div>
                @php $optional_icons = $optional_icons->reverse(); @endphp
                @foreach($optional_icons as $optional_icon)
                <img src="{{asset('storage/uploads/icons/'.@$optional_icon->icon)}}" alt=""
                    class="img-fluid float-end icons px-2 py-1 {{$loop->last ? 'last-icon' : ''}}">
                @endforeach
                <div id="left-bracket-open" class="float-end">
                    <span>Optional</span>
                    <div></div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <section class="details-section">
        <div>
            <div class="row">
                <div class="col-md-5 col-sm-12 details mt-4">
                    <div class="product-title section-title bolder pt-0">
                        {{$product->title}} <span class="text-uppercase">| {{$variant?->variant_name ?? ''}}</span>
                    </div>
                    @if(count($product_att) > 0)
                    @php $attr_group_id = $product_att[0]->group_id ; @endphp
                    <p class="section-text line-ht-1-8  fs-1 bolder mb-0 pb-0">{{ucfirst(@$product_att[0]->title)}}</p>
                    @foreach($product_att as $product_key => $att)
                    @if(@$att->group_id != 6 && @$att->group_id != 7)
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
                    @endif
                    @endforeach
                    @endif
                </div>
                <div class="col-md-7 col-sm-12 images mt-3">

                    <div class="float-start w-100">
                        <div class="float-start" style="max-width: 65%;">
                            <img src="{{asset('storage/uploads/products/'.@$product->featured_image)}}" alt=""
                                class="img-fluid" style="height: 350px; width: 350px;">
                        </div>
                        <div class="float-start" style="max-width: 55%;">
                            <img src="{{asset('storage/uploads/products/'.@$variant->line_diagram)}}" alt=""
                                class="img-fluid">
                        </div>
                    </div>

                    <div class="row float-start w-100">
                        <p class="section-text line-ht-1-8 fs-1-1 bolder mb-3 pb-0 mt-3 ms-2">Light Distribution</p>
                        @foreach($variant->variantFiles as $variantFile)
                        @if(@$variantFile->file_type === 'image')
                        <div class="col-sm-3 text-center">
                            <img src="{{asset('storage/uploads/product_variant_images/'.@$variantFile->file)}}" alt=""
                                class="img-fluid">
                        </div>
                        @endif
                        @endforeach
                    </div>


                    @if(count($product_att) > 0)
                    <section class="float-start mt-5">
                        @foreach($product_att as $product_key => $att)
                        @if(@$att->group_id == 6 || @$att->group_id == 7)
                        @if(@$attr_group_id != $att->group_id)
                        <div class="col-12">
                        </div>
                        <p class="section-text line-ht-1-8  fs-1 bolder mb-0 header pb-0 pe-3">{{ucfirst(@$att->title)}}
                        </p>
                        @endif
                        @if(@$att->attribute->is_visible == 'yes')
                        <p
                            class="section-text line-ht-1-8  fs-1 p-0 m-0  pe-3 {{@$attr_group_id == $att->group_id && $product_key != 0 ? 'border-top-top' : ''}} ">
                            <span class="primary-color">{{@$att->attribute->attribute_name}}</span>
                            {{@$att->value ? @$att->value : '-'}}
                        </p>
                        @endif
                        @php $attr_group_id = $att->group_id ; @endphp
                        @endif
                        @endforeach
                        @endif
                    </section>
                </div>
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
                                        <td class="section-text line-ht-1-8  fs-1 bolder">{{$variant?->variant_name ??
                                            ''}}
                                        </td>
                                        <td class="section-text line-ht-1-8  fs-1 bolder">{{$attr_table['CCT_CODE'][0]}}
                                        </td>
                                        <td class="section-text line-ht-1-8  fs-1 bolder">{{$attr_table['CRI'][0]}}</td>
                                        <td class="section-text line-ht-1-8  fs-1 bolder">{{$attr_table['ANGLE'][0]}}</td>
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
                <p class="section-subtext mb-0">Select one from each column with highlighted values to create the
                    ordering
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
                                    <th
                                        class="section-text line-ht-1-8  fs-1 p-0 fainted border-right-none text-center">
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
                                    <th colspan="2" class="section-text line-ht-1-8  fs-1 text-center">
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
                                    <td class="section-text line-ht-1-8  fs-1 bolder">{{$variant?->variant_name ?? ''}}
                                    </td>
                                    <td class="section-text line-ht-1-8  fs-1 fainted border-right-none">
                                        {{$attr_table['LUMENS'][0]}}</td>
                                    <td class="section-text line-ht-1-8  fs-1 fainted">{{$attr_table['EFFICACY'][0]}}
                                    </td>
                                    <td class="section-text line-ht-1-8  fs-1 border-right-none">
                                        {{$attr_table['CCT'][0]}}
                                    </td>
                                    <td class="section-text line-ht-1-8  fs-1"><span
                                            class="bolder">{{$attr_table['CCT_CODE'][0]}}</span></td>
                                    <td class="section-text line-ht-1-8  fs-1"><span
                                            class="bolder">{{$attr_table['CRI'][0]}}</span></td>
                                    <td class="section-text line-ht-1-8  fs-1 border-right-none">
                                        {{$attr_table['ANGLE'][0]}}
                                    </td>
                                    <td class="section-text line-ht-1-8  fs-1"><span
                                            class="bolder">{{$attr_table['ANGLE_CODE'][0]}}</span></td>
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
                                    <td class="section-text line-ht-1-8  fs-1 fainted">{{$attr_table['EFFICACY'][$i]}}
                                    </td>
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
                                    <td class="section-text line-ht-1-8  fs-1 fainted border-right-none">
                                        {{$attr_table['ANGLE'][$i]}}</td>
                                    <td class="section-text line-ht-1-8  fs-1">
                                        @if(isset($attr_table['ANGLE_CODE'][$i]))<span
                                            class="bolder">{{$attr_table['ANGLE_CODE'][$i]}}</span>@endif</td>
                                    @else
                                    <td class="section-text line-ht-1-8  fs-1 border-right-none"></td>
                                    <td class="section-text line-ht-1-8  fs-1"></td>
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
                                        <td class="section-text line-ht-1-8  fs-1 bolder text-center">
                                            {{$product->title}}
                                        </td>
                                        <td class="section-text line-ht-1-8  fs-1 bolder text-center">
                                            {{$variant?->variant_name ?? ''}}</td>
                                        <td class="section-text line-ht-1-8  fs-1 fainted border-right-none"></td>
                                        <td class="section-text line-ht-1-8  fs-1 fainted"></td>
                                        <td colspan="2" class="section-text line-ht-1-8  fs-1 text-center"><span
                                                class="bolder w-100">{{$attr_table['CCT_CODE'][0]}}</span></td>
                                        <td class="section-text line-ht-1-8  fs-1 text-center"><span
                                                class="bolder w-100">{{$attr_table['CRI'][0]}}</span></td>
                                        <td colspan="2" class="section-text line-ht-1-8  fs-1 text-center"><span
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
    <section class="footer mt-0 pt-2 d-flex" style="position:static">
        <div class="flex-fill">
            <p class="mb-2"><b>Abby Lighting & Switchgear Limited</b> 802 A, Fortune Terraces, New Link Road, Opp City
                Mall, Andheri West,
                Mumbai - 400053, India.</p>
            <p class="mb-0">Abby reserves the right to discontinue any product from its collection at any time
                whatsover and without
                prior notice</p>
        </div>
        <div class="">
            <p class="float-end w-100 text-end mb-2">frontdesk@abbylighting.com, +91 9833645212</p>
            <p class="float-end w-100 text-end mb-0">www.abbylighting.com</p>
        </div>
    </section>
</section>
@endsection
