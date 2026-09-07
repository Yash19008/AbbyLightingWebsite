@extends('admin.master')
@section('classes_body', 'hold-transition sidebar-mini')
@section('body')
<nav class="navbar navbar-expand-lg navbar-light header-navbar navbar-fixed">
    <div class="container-fluid navbar-wrapper">
        <div class="navbar-header d-flex">
            <div class="navbar-toggle menu-toggle d-xl-none d-block float-left align-items-center justify-content-center" data-toggle="collapse"><i class="ft-menu font-medium-3"></i></div>
        </div>
        <div class="navbar-container">
            <div class="collapse navbar-collapse d-block" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <?php $user = Auth::guard('admin')->user();
                    ?>
                    <li class="dropdown nav-item mr-1">
                        <a class="nav-link dropdown-toggle user-dropdown d-flex align-items-end" id="dropdownBasic2" href="javascript:;" data-toggle="dropdown" aria-expanded="true">
                            <div class="user d-md-flex d-none mr-2"><span class="text-right">Profile</span></div>
                        </a>
                        <div class="dropdown-menu text-left dropdown-menu-right m-0 pb-0" aria-labelledby="dropdownBasic2">
                            <a class="dropdown-item" href="{{route('change_pass')}}">
                                <div class="d-flex align-items-center"><i class="ft-edit mr-2"></i><span>Change
                                        Password</span></div>
                            </a>
                            <a class="dropdown-item" href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div class="d-flex align-items-center"><i class="ft-power mr-2"></i><span>Logout</span>
                                </div>
                            </a>
                            <form id="logout-form" action="{{ route('logoutadmin') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="wrapper">
    <div class="app-sidebar menu-fixed overflow-auto" data-background-color="white" data-image="" data-scroll-to-active="true">
        <!-- main menu header-->
        <!-- Sidebar Header starts-->
        <div class="sidebar-header">
            <div class="logo clearfix"><a class="logo-text float-left" href="{{route('contact_form_admin')}}">
                    <div class="logo-img"><img src="{{asset('images/abbydashboardlogo.png')}}" alt="" style="width:175px;" /></div>
                    <!-- <span class="text">APEX</span> -->
                </a><a class="nav-toggle d-none d-lg-none d-xl-block" id="sidebarToggle" href="javascript:;"><i class="toggle-icon ft-toggle-right" data-toggle="expanded"></i></a><a class="nav-close d-block d-lg-block d-xl-none" id="sidebarClose" href="javascript:;"><i class="ft-x"></i></a></div>
        </div>
        <!-- Sidebar Header Ends-->
        <!-- / main menu header-->
        <!-- main menu content-->
        <div class="sidebar-content main-menu-content">
            <div class="nav-container">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                @php
                    $user = Auth::guard('admin')->user();
                @endphp

                @if($user && $user->id == 3)
                    {{-- Specgen user → ONLY Products menu --}}
                    <li class="nav-item {{(@$main_module == 'Product') ? 'active' : ''}}">
                        <a href="{{route('product_admin')}}">
                            <i class="ft-package"></i>
                            <span class="menu-title" data-i18n="Products">Products</span>
                        </a>
                    </li>
                    <li class="nav-item has-sub {{ (@$main_module == 'Decorative Product') ? 'open' : '' }}">
                        <a href="#">
                            <i class="ft-package"></i>
                            <span class="menu-title" data-i18n="Decorative">Decorative Products</span>
                        </a>
                        <ul class="menu-content">
                            <li class="{{(Route::currentRouteName() == 'decorative_product_admin') ? 'active' : ''}}"><a class="menu-item" href="{{route('decorative_product_admin')}}">All Decorative</a></li>
                            <li class="{{(Route::currentRouteName() == 'decorative_product_admin.add') ? 'active' : ''}}"><a class="menu-item" href="{{route('decorative_product_admin.add')}}">Add Decorative</a></li>
                            <li class="{{(Route::currentRouteName() == 'decorative_attribute_admin' || Route::currentRouteName() == 'decorative_attribute_admin.add' || Route::currentRouteName() == 'decorative_attribute_admin.edit') ? 'active' : ''}}"><a class="menu-item" href="{{route('decorative_attribute_admin')}}">Attributes</a></li>
                            <li class="{{(Route::currentRouteName() == 'decorative_category_admin' || Route::currentRouteName() == 'decorative_category_admin.add' || Route::currentRouteName() == 'decorative_category_admin.edit') ? 'active' : ''}}"><a class="menu-item" href="{{route('decorative_category_admin')}}">Categories</a></li>
                        </ul>
                    </li>
                @else
                    {{-- Normal admin → full menu --}}
                    <li class="nav-item {{(@$main_module == 'Category') ? 'active' : ''}}"><a href="{{route('category_admin')}}"><i class="fa fa-th-list"></i><span class="menu-title" data-i18n="Category">Categories</span></a></li>
                    <!-- <li class="nav-item {{(@$main_module == 'Families') ? 'active' : ''}}"><a href="{{route('family_admin')}}"><i class="icon-users"></i><span class="menu-title" data-i18n="Families">Families</span></a></li> -->
                    <li class="nav-item {{(@$main_module == 'Tags') ? 'active' : ''}}"><a href="{{route('tag_admin')}}"><i class="icon-tag"></i><span class="menu-title" data-i18n="Tags">Tags</span></a></li>
                    <li class="nav-item {{(@$main_module == 'Sub Tags') ? 'active' : ''}}"><a href="{{route('sub_tag_admin')}}"><i class="icon-tag"></i><span class="menu-title" data-i18n="Tags">Sub Tags</span></a></li>
                    <li class="nav-item {{(@$main_module == 'Product') ? 'active' : ''}}"><a href="{{route('product_admin')}}"><i class="ft-package"></i><span class="menu-title" data-i18n="Products">Products</span></a></li>
                    <li class="nav-item {{(@$main_module == 'Icons') ? 'active' : ''}}"><a href="{{route('icon_admin')}}"><i class="ft-package"></i><span class="menu-title" data-i18n="Icons">Icons</span></a></li>
                    <li class="nav-item {{(@$main_module == 'Attributes') ? 'active' : ''}}"><a href="{{route('attribute_admin')}}"><i class="fa fa-list-alt"></i><span class="menu-title" data-i18n="Attributes">Attributes</span></a></li>
                    
                    <li class="nav-item has-sub {{ (@$main_module == 'Decorative Product') ? 'open' : '' }}">
                        <a href="#"><i class="ft-package"></i><span class="menu-title" data-i18n="Decorative">Decorative Products</span></a>
                        <ul class="menu-content">
                            <li class="{{(Route::currentRouteName() == 'decorative_product_admin') ? 'active' : ''}}"><a class="menu-item" href="{{route('decorative_product_admin')}}">All Decorative</a></li>
                            <li class="{{(Route::currentRouteName() == 'decorative_product_admin.add') ? 'active' : ''}}"><a class="menu-item" href="{{route('decorative_product_admin.add')}}">Add Decorative</a></li>
                            <li class="{{(Route::currentRouteName() == 'decorative_attribute_admin' || Route::currentRouteName() == 'decorative_attribute_admin.add' || Route::currentRouteName() == 'decorative_attribute_admin.edit') ? 'active' : ''}}"><a class="menu-item" href="{{route('decorative_attribute_admin')}}">Attributes</a></li>
                            <li class="{{(Route::currentRouteName() == 'decorative_category_admin' || Route::currentRouteName() == 'decorative_category_admin.add' || Route::currentRouteName() == 'decorative_category_admin.edit') ? 'active' : ''}}"><a class="menu-item" href="{{route('decorative_category_admin')}}">Categories</a></li>
                        </ul>
                    </li>
                    
                    {{-- PORTFOLIO SECTION --}}
                    <li class="nav-item has-sub {{ (in_array(@$main_module, ['Project', 'Clients'])) ? 'open' : '' }}">
                        <a href="#"><i class="ft-briefcase"></i><span class="menu-title" data-i18n="Portfolio">Portfolio</span></a>
                        <ul class="menu-content">
                            <li class="{{(@$main_module == 'Project') ? 'active' : ''}}"><a class="menu-item" href="{{route('project_admin')}}">Projects</a></li>
                            <li class="{{(@$main_module == 'Clients') ? 'active' : ''}}"><a class="menu-item" href="{{route('client_admin')}}">Clients</a></li>
                        </ul>
                    </li>
                    
                    {{-- MEDIA & UPDATES SECTION --}}
                    <li class="nav-item has-sub {{ (in_array(@$main_module, ['News', 'Events', 'Jobs', 'Blog Categories', 'Blogs', 'Watch & Shop']) || str_starts_with(Route::currentRouteName() ?? '', 'admin.blog-categories') || str_starts_with(Route::currentRouteName() ?? '', 'admin.blogs') || str_starts_with(Route::currentRouteName() ?? '', 'admin.watch_and_shops')) ? 'open' : '' }}">
                        <a href="#"><i class="ft-globe"></i><span class="menu-title" data-i18n="Media">Media & Updates</span></a>
                        <ul class="menu-content">
                            <li class="{{(@$main_module == 'News') ? 'active' : ''}}"><a class="menu-item" href="{{route('admin.news-items.index')}}">News</a></li>
                            <li class="{{(@$main_module == 'Events') ? 'active' : ''}}"><a class="menu-item" href="{{route('event_admin')}}">Events</a></li>
                            <li class="{{(@$main_module == 'Jobs') ? 'active' : ''}}"><a class="menu-item" href="{{route('job_admin')}}">Jobs</a></li>
                            <li class="{{(@$main_module == 'Blog Categories' || str_starts_with(Route::currentRouteName() ?? '', 'admin.blog-categories')) ? 'active' : ''}}"><a class="menu-item" href="{{route('admin.blog-categories.index')}}">Blog Categories</a></li>
                            <li class="{{(@$main_module == 'Blogs' || str_starts_with(Route::currentRouteName() ?? '', 'admin.blogs')) ? 'active' : ''}}"><a class="menu-item" href="{{route('admin.blogs.index')}}">Blogs / Articles</a></li>
                            <li class="{{(@$main_module == 'Watch & Shop' || str_starts_with(Route::currentRouteName() ?? '', 'admin.watch_and_shops')) ? 'active' : ''}}"><a class="menu-item" href="{{route('admin.watch_and_shops.index')}}">Watch &amp; Shop (Reels)</a></li>
                        </ul>
                    </li>
                    
                    {{-- INQUIRIES SECTION --}}
                    <li class="nav-item has-sub {{ (in_array(@$main_module, ['Contact Form', 'Catalog', 'Subscriptions'])) ? 'open' : '' }}">
                        <a href="#"><i class="ft-mail"></i><span class="menu-title" data-i18n="Inquiries">Inquiries</span></a>
                        <ul class="menu-content">
                            <li class="{{(@$main_module == 'Contact Form') ? 'active' : ''}}"><a class="menu-item" href="{{route('contact_form_admin')}}">Contact Forms</a></li>
                            <li class="{{(@$main_module == 'Catalog') ? 'active' : ''}}"><a class="menu-item" href="{{route('catalog_admin')}}">Catalog Downloads</a></li>
                            <li class="{{(@$main_module == 'Subscriptions') ? 'active' : ''}}"><a class="menu-item" href="{{route('subscriptions_admin')}}">Subscriptions</a></li>
                        </ul>
                    </li>
                    
                    {{-- HOMEPAGE SETTINGS SECTION --}}
                    <li class="nav-item has-sub {{ (Route::currentRouteName() && (str_starts_with(Route::currentRouteName(), 'homeslider_admin') || str_starts_with(Route::currentRouteName(), 'light_worlds_admin') || Route::currentRouteName() == 'admin.manufacturing.edit')) ? 'open' : '' }}">
                        <a href="#"><i class="ft-home"></i><span class="menu-title" data-i18n="Homepage Setting">Homepage Setting</span></a>
                        <ul class="menu-content">
                            <li class="{{(Route::currentRouteName() == 'homeslider_admin' || Route::currentRouteName() == 'homeslider_admin.add' || Route::currentRouteName() == 'homeslider_admin.edit') ? 'active' : ''}}"><a class="menu-item" href="{{route('homeslider_admin')}}">Home Sliders</a></li>
                            <li class="{{(str_starts_with(Route::currentRouteName() ?? '', 'light_worlds_admin')) ? 'active' : ''}}"><a class="menu-item" href="{{route('light_worlds_admin')}}">Worlds of Light</a></li>
                            <li class="{{(Route::currentRouteName() == 'admin.manufacturing.edit') ? 'active' : ''}}"><a class="menu-item" href="{{route('admin.manufacturing.edit', 1)}}">Manufacturing Section</a></li>
                        </ul>
                    </li>

                    <li class="nav-item has-sub {{ (@$main_module == 'Settings' || @$main_module == 'Color Masters') ? 'open' : '' }}">
                        <a href="#"><i class="ft-settings"></i><span class="menu-title" data-i18n="Settings">Settings</span></a>
                        <ul class="menu-content">
                            <li class="{{(@$main_module == 'Color Masters' || str_starts_with(Route::currentRouteName() ?? '', 'color_master_admin')) ? 'active' : ''}}"><a class="menu-item" href="{{route('color_master_admin')}}">Color Masters</a></li>
                        </ul>
                    </li>

                    {{-- COLLECTIONS SECTION --}}
                    <li class="nav-item has-sub {{ (@$main_module == 'Collections') ? 'open' : '' }}">
                        <a href="#"><i class="ft-layers"></i><span class="menu-title" data-i18n="Collections">Collections</span></a>
                        <ul class="menu-content">
                            <li class="{{(Route::currentRouteName() == 'admin.collections.index') ? 'active' : ''}}"><a class="menu-item" href="{{route('admin.collections.index')}}">All Collections</a></li>
                            <li class="{{(Route::currentRouteName() == 'admin.collections.create') ? 'active' : ''}}"><a class="menu-item" href="{{route('admin.collections.create')}}">Add New Collection</a></li>
                        </ul>
                    </li>

                    <!-- <li class="nav-item {{(@$main_module == 'Upload CSV') ? 'active' : ''}}"><a href="{{route('upload_csv_admin')}}"><i class="icon-cloud-upload"></i><span class="menu-title" data-i18n="Upload CSV">Upload CSV</span></a></li> -->
                @endif
                </ul>
            </div>
        </div>
        <!-- main menu content-->
        <div class="sidebar-background"></div>
        <!-- main menu footer-->
        <!-- include includes/menu-footer-->
        <!-- main menu footer-->
        <!-- / main menu-->
    </div>

    <div class="main-panel">
        <div class="main-content">
            <div class="content-wrapper">
                <div class="content-overlay"></div>
                @yield('content_header')
                @yield('content')
            </div>
        </div>
    </div>
    <footer class="footer undefined undefined">
        <p class="clearfix text-muted m-0"><span>Copyright &copy; 2023 &nbsp;</span><a href="#" id="pixinventLink" target="_blank">Abby Lighting</a><span class="d-none d-sm-inline-block">, All rights reserved.</span>
        </p>
    </footer>
</div>
<div class="modal fade" id="delete_popup">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="icon fa fa-warning"></i> Confirmation</h3>
                <button type="button" class="close closes" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left closes" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-dark confirm">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_variant_popup">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="icon fa fa-warning"></i> Confirmation</h3>
                <button type="button" class="close closes" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left closes" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-dark confirm">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_variant_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Variant</h3>
                <button type="button" class="close closes" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <small class="form-text text-danger mb-2" style="padding-left: 15px !important;">Note : Add multiple attributes with comma seprated Eg. Black,Red,Blue</small>
                <form method="post" action="javascript:;" id="edit_variant">
                    <div class="col-12 mb-2">
                        <label>VARIANT NAME</label>
                        <input type="text" name="variant_name" id="variant_name" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label>SLUG</label>
                        <input type="text" name="slug" id="slug" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label>LED FITTED</label>
                        <input type="text" name="led_fitted" id="led_fitted" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label>CO-RELATED COLOUR TEMPRATURE</label>
                        <div class="row p-0 m-0">
                            <div class="col-6 p-0 m-0 pr-1">
                                <input type="text" name="co_related_color" id="co_related_color" class="form-control add_variant" value="" placeholder="Values">
                            </div>
                            <div class="col-6 p-0 m-0 pl-1">
                                <input type="text" name="co_related_color_code" id="co_related_color_code" class="form-control add_variant" value="" placeholder="Codes">
                            </div>
                            <small class="form-text text-muted pl-0">Add values & codes in same order</small>
                        </div>
                    </div>
                    <div class="col-12 mb-2">
                        <label>DELIVERED LUMENS</label>
                        <input type="text" name="lumens" id="lumens" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label>EFFICACY (SYSTEM - LM/WATT)</label>
                        <input type="text" name="efficacy" id="efficacy" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label>BEAM ANGLE (2XFWHM)°</label>
                        <div class="row p-0 m-0">
                            <div class="col-6 p-0 m-0 pr-1">
                                <input type="text" name="beam_angle" id="beam_angle" class="form-control add_variant" value="" placeholder="Values">
                            </div>
                            <div class="col-6 p-0 m-0 pl-1">
                                <input type="text" name="beam_angle_code" id="beam_angle_code" class="form-control add_variant" value="" placeholder="Codes">
                            </div>
                            <small class="form-text text-muted pl-0">Add values & codes in same order</small>
                        </div>
                    </div>
                    <div class="col-12 mb-2">
                        <label>LED POWER WATTS</label>
                        <input type="text" name="led_power_watts" id="led_power_watts" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label>SYSTEM POWER WATTS</label>
                        <input type="text" name="system_power_watts" id="system_power_watts" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label>OPERATING VOLTAGE VIN</label>
                        <input type="text" name="operating_voltage" id="operating_voltage" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label>POWER FACTOR P.F.</label>
                        <input type="text" name="power_factor" id="power_factor" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label for="inputName" class="control-label">Line Diagram</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <input type="hidden" name="photo" id="photo" value="">
                                <input type="file" name="line_diagram" id="imagefile" accept="image/*" class="file-input">

                            </div>
                            <input type="text" class="form-control line_diagram" disabled placeholder="Upload Image" value="">
                            <input type="hidden" name="oldPhoto" id="oldPhoto" value="">
                            <div class="input-group-append">
                                <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                            </div>
                        </div>
                        <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                    </div>
                    <div class="col-12 mb-2">
                        <label for="inputName" class="control-label">Custom Specsheet</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <input type="hidden" name="photo" id="photo" value="">
                                <input type="file" name="custom_specsheet" id="imagefile" accept="*/*" class="file-input">

                            </div>
                            <input type="text" class="form-control custom_specsheet" disabled placeholder="Upload Image" value="">
                            <input type="hidden" name="oldPhoto" id="oldPhoto" value="">
                            <div class="input-group-append">
                                <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                            </div>
                        </div>
                        <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                    </div>
                    {{-- <div class="col-12 mb-2">
                        <label for="inputName" class="control-label">PHOTOMETRY FILE</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <input type="hidden" name="photo" id="photo" value="">
                                <input type="file" name="photometry_file" id="imagefile" accept="image/*"
                                    class="file-input">

                            </div>
                            <input type="text" class="form-control photometry_file" disabled placeholder="Upload Image"
                                value="">
                            <input type="hidden" name="oldPhoto" value="">
                            <div class="input-group-append">
                                <button class="file-input-browse btn btn-dark" type="button"><i
                                        class="glyphicon glyphicon-search"></i> Browse</button>
                            </div>
                        </div>
                        <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                    </div> --}}


                    <div class="col-12 mb-2 hide">
                        <label>deleted Photometry</label>
                        <input type="text" name="deleted_vectorImages" id="deleted_vectorImages" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label id="edit-business-varientImage-label" for="inputName" class="control-label">Photometry
                            Files <span class="font-small-1">(Upto 4 files)</span></label>
                        <table id="edit-varientImages-table" class="table mt-2">
                            <thead>
                                <tr>
                                    <th class="text-left">File Name</th>
                                    <th class="text-center" style="width: 20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="edit-noVarientImagesRow">
                                    <td colspan="2">
                                        <p style="text-align: center;">No Photometry Files</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mb-2 hide">
                        <label>deleted ies</label>
                        <input type="text" name="deleted_ies" id="deleted_ies" class="form-control add_variant" value="">
                    </div>
                    <div class="col-12 mb-2">
                        <label id="edit-business-iesFile-label" for="inputName" class="control-label">IES Files</label>
                        <table id="edit-iesFiles-table" class="table mt-2">
                            <thead>
                                <tr>
                                    <th class="text-left">File Name</th>
                                    <th class="text-center" style="width: 20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="edit-noIesFilesRow">
                                    <td colspan="2">
                                        <p style="text-align: center;">No IES Files</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left closes" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark update-variant">Edit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@stop