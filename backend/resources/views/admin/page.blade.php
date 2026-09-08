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
                @php $user = Auth::guard('admin')->user(); @endphp

                @if($user && $user->id == 3)
                    {{-- Specgen user → ONLY Products & Decorative --}}
                    <li class="nav-item {{ (@$main_module == 'Product') ? 'active' : '' }}">
                        <a href="{{ route('product_admin') }}">
                            <i class="ft-package"></i>
                            <span class="menu-title">Products</span>
                        </a>
                    </li>
                    <li class="nav-item has-sub {{ (@$main_module == 'Decorative Product') ? 'open' : '' }}">
                        <a href="#"><i class="ft-package"></i><span class="menu-title">Decorative Products</span></a>
                        <ul class="menu-content">
                            <li class="{{ (Route::currentRouteName() == 'decorative_product_admin') ? 'active' : '' }}"><a class="menu-item" href="{{ route('decorative_product_admin') }}">All Decorative</a></li>
                            <li class="{{ (Route::currentRouteName() == 'decorative_product_admin.add') ? 'active' : '' }}"><a class="menu-item" href="{{ route('decorative_product_admin.add') }}">Add Decorative</a></li>
                            <li class="{{ (Route::currentRouteName() == 'decorative_attribute_admin' || Route::currentRouteName() == 'decorative_attribute_admin.add' || Route::currentRouteName() == 'decorative_attribute_admin.edit') ? 'active' : '' }}"><a class="menu-item" href="{{ route('decorative_attribute_admin') }}">Attributes</a></li>
                            <li class="{{ (Route::currentRouteName() == 'decorative_category_admin' || Route::currentRouteName() == 'decorative_category_admin.add' || Route::currentRouteName() == 'decorative_category_admin.edit') ? 'active' : '' }}"><a class="menu-item" href="{{ route('decorative_category_admin') }}">Categories</a></li>
                        </ul>
                    </li>
                @else
                    {{-- Normal admin → full categorized menu --}}

                    {{-- ══════════════════════════════════════
                         SECTION: PAGES
                         Manage content per frontend page
                         ══════════════════════════════════════ --}}
                    <li class="nav-item has-sub {{ (Route::currentRouteName() && (str_starts_with(Route::currentRouteName(), 'homeslider_admin') || str_starts_with(Route::currentRouteName(), 'light_worlds_admin') || Route::currentRouteName() == 'admin.manufacturing.edit' || str_starts_with(Route::currentRouteName() ?? '', 'admin.news-items') || str_starts_with(Route::currentRouteName() ?? '', 'admin.watch_and_shops'))) ? 'open' : '' }}">
                        <a href="#"><i class="ft-layout"></i><span class="menu-title">Pages</span></a>
                        <ul class="menu-content">
                            {{-- HOME PAGE --}}
                            <li class="menu-item-heading"><small class="text-uppercase text-muted" style="font-size:9px;letter-spacing:1px;padding-left:10px;">Home</small></li>
                            <li class="{{ (Route::currentRouteName() == 'homeslider_admin' || Route::currentRouteName() == 'homeslider_admin.add' || Route::currentRouteName() == 'homeslider_admin.edit') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('homeslider_admin') }}"><i class="ft-image" style="font-size:11px;margin-right:4px;"></i> Home Sliders</a>
                            </li>
                            <li class="{{ str_starts_with(Route::currentRouteName() ?? '', 'light_worlds_admin') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('light_worlds_admin') }}"><i class="ft-sun" style="font-size:11px;margin-right:4px;"></i> Worlds of Light</a>
                            </li>
                            <li class="{{ (Route::currentRouteName() == 'admin.manufacturing.edit') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('admin.manufacturing.edit', 1) }}"><i class="ft-settings" style="font-size:11px;margin-right:4px;"></i> Manufacturing Section</a>
                            </li>
                            <li class="{{ (str_starts_with(Route::currentRouteName() ?? '', 'admin.news-items')) ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('admin.news-items.index') }}"><i class="ft-rss" style="font-size:11px;margin-right:4px;"></i> News Items</a>
                            </li>
                            {{-- INSPIRATION PAGE --}}
                            <li class="menu-item-heading mt-1"><small class="text-uppercase text-muted" style="font-size:9px;letter-spacing:1px;padding-left:10px;">Inspiration</small></li>
                            <li class="{{ (str_starts_with(Route::currentRouteName() ?? '', 'admin.watch_and_shops')) ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('admin.watch_and_shops.index') }}"><i class="ft-film" style="font-size:11px;margin-right:4px;"></i> Watch &amp; Shop (Reels)</a>
                            </li>
                        </ul>
                    </li>

                    {{-- ══════════════════════════════════════
                         SECTION: ARCHITECTURAL PRODUCTS
                         ══════════════════════════════════════ --}}
                    <li class="nav-item has-sub {{ (in_array(@$main_module, ['Product', 'Category', 'Tags', 'Sub Tags', 'Attributes', 'Icons', 'Collections'])) ? 'open' : '' }}">
                        <a href="#"><i class="ft-zap"></i><span class="menu-title">Architectural</span></a>
                        <ul class="menu-content">
                            <li class="{{ (@$main_module == 'Product') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('product_admin') }}"><i class="ft-package" style="font-size:11px;margin-right:4px;"></i> Products</a>
                            </li>
                            <li class="{{ (@$main_module == 'Category') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('category_admin') }}"><i class="ft-grid" style="font-size:11px;margin-right:4px;"></i> Categories</a>
                            </li>
                            <li class="{{ (@$main_module == 'Tags') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('tag_admin') }}"><i class="ft-tag" style="font-size:11px;margin-right:4px;"></i> Tags</a>
                            </li>
                            <li class="{{ (@$main_module == 'Sub Tags') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('sub_tag_admin') }}"><i class="ft-tag" style="font-size:11px;margin-right:4px;"></i> Sub Tags</a>
                            </li>
                            <li class="{{ (@$main_module == 'Attributes') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('attribute_admin') }}"><i class="ft-sliders" style="font-size:11px;margin-right:4px;"></i> Attributes</a>
                            </li>
                            <li class="{{ (@$main_module == 'Icons') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('icon_admin') }}"><i class="ft-star" style="font-size:11px;margin-right:4px;"></i> Icons</a>
                            </li>
                            <li class="{{ (@$main_module == 'Collections') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('admin.collections.index') }}"><i class="ft-layers" style="font-size:11px;margin-right:4px;"></i> Collections</a>
                            </li>
                        </ul>
                    </li>

                    {{-- ══════════════════════════════════════
                         SECTION: DECORATIVE PRODUCTS
                         ══════════════════════════════════════ --}}
                    <li class="nav-item has-sub {{ (@$main_module == 'Decorative Product') ? 'open' : '' }}">
                        <a href="#"><i class="ft-aperture"></i><span class="menu-title">Decorative</span></a>
                        <ul class="menu-content">
                            <li class="{{ (Route::currentRouteName() == 'decorative_product_admin') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('decorative_product_admin') }}"><i class="ft-package" style="font-size:11px;margin-right:4px;"></i> Products</a>
                            </li>
                            <li class="{{ (Route::currentRouteName() == 'decorative_attribute_admin' || Route::currentRouteName() == 'decorative_attribute_admin.add' || Route::currentRouteName() == 'decorative_attribute_admin.edit') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('decorative_attribute_admin') }}"><i class="ft-sliders" style="font-size:11px;margin-right:4px;"></i> Attributes</a>
                            </li>
                            <li class="{{ (Route::currentRouteName() == 'decorative_category_admin' || Route::currentRouteName() == 'decorative_category_admin.add' || Route::currentRouteName() == 'decorative_category_admin.edit') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('decorative_category_admin') }}"><i class="ft-grid" style="font-size:11px;margin-right:4px;"></i> Categories</a>
                            </li>
                        </ul>
                    </li>

                    {{-- ══════════════════════════════════════
                         SECTION: BLOGS & ARTICLES
                         ══════════════════════════════════════ --}}
                    <li class="nav-item has-sub {{ (in_array(@$main_module, ['Blogs', 'Blog Categories']) || str_starts_with(Route::currentRouteName() ?? '', 'admin.blog')) ? 'open' : '' }}">
                        <a href="#"><i class="ft-edit"></i><span class="menu-title">Blogs &amp; Articles</span></a>
                        <ul class="menu-content">
                            <li class="{{ (@$main_module == 'Blogs' || str_starts_with(Route::currentRouteName() ?? '', 'admin.blogs')) ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('admin.blogs.index') }}"><i class="ft-file-text" style="font-size:11px;margin-right:4px;"></i> All Articles</a>
                            </li>
                            <li class="{{ (Route::currentRouteName() == 'admin.blogs.add') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('admin.blogs.add') }}"><i class="ft-plus-circle" style="font-size:11px;margin-right:4px;"></i> New Article</a>
                            </li>
                            <li class="{{ (@$main_module == 'Blog Categories' || str_starts_with(Route::currentRouteName() ?? '', 'admin.blog-categories')) ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('admin.blog-categories.index') }}"><i class="ft-folder" style="font-size:11px;margin-right:4px;"></i> Categories</a>
                            </li>
                        </ul>
                    </li>

                    {{-- ══════════════════════════════════════
                         SECTION: OUR WORK (PORTFOLIO)
                         ══════════════════════════════════════ --}}
                    <li class="nav-item has-sub {{ (in_array(@$main_module, ['Project', 'Clients'])) ? 'open' : '' }}">
                        <a href="#"><i class="ft-briefcase"></i><span class="menu-title">Our Work</span></a>
                        <ul class="menu-content">
                            <li class="{{ (@$main_module == 'Project') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('project_admin') }}"><i class="ft-folder" style="font-size:11px;margin-right:4px;"></i> Projects</a>
                            </li>
                            <li class="{{ (@$main_module == 'Clients') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('client_admin') }}"><i class="ft-users" style="font-size:11px;margin-right:4px;"></i> Clients</a>
                            </li>
                        </ul>
                    </li>

                    {{-- ══════════════════════════════════════
                         SECTION: INQUIRIES
                         ══════════════════════════════════════ --}}
                    <li class="nav-item has-sub {{ (in_array(@$main_module, ['Contact Form', 'Catalog', 'Subscriptions'])) ? 'open' : '' }}">
                        <a href="#"><i class="ft-mail"></i><span class="menu-title">Inquiries</span></a>
                        <ul class="menu-content">
                            <li class="{{ (@$main_module == 'Contact Form') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('contact_form_admin') }}"><i class="ft-message-circle" style="font-size:11px;margin-right:4px;"></i> Contact Forms</a>
                            </li>
                            <li class="{{ (@$main_module == 'Catalog') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('catalog_admin') }}"><i class="ft-download" style="font-size:11px;margin-right:4px;"></i> Catalog Downloads</a>
                            </li>
                            <li class="{{ (@$main_module == 'Subscriptions') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('subscriptions_admin') }}"><i class="ft-bell" style="font-size:11px;margin-right:4px;"></i> Subscriptions</a>
                            </li>
                        </ul>
                    </li>

                    {{-- ══════════════════════════════════════
                         SECTION: SETTINGS
                         ══════════════════════════════════════ --}}
                    <li class="nav-item has-sub {{ (in_array(@$main_module, ['Color Masters', 'Events', 'Jobs', 'Settings'])) ? 'open' : '' }}">
                        <a href="#"><i class="ft-settings"></i><span class="menu-title">Settings</span></a>
                        <ul class="menu-content">
                            <li class="{{ (@$main_module == 'Color Masters' || str_starts_with(Route::currentRouteName() ?? '', 'color_master_admin')) ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('color_master_admin') }}"><i class="ft-droplet" style="font-size:11px;margin-right:4px;"></i> Color Masters</a>
                            </li>
                            <li class="{{ (@$main_module == 'Events') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('event_admin') }}"><i class="ft-calendar" style="font-size:11px;margin-right:4px;"></i> Events</a>
                            </li>
                            <li class="{{ (@$main_module == 'Jobs') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('job_admin') }}"><i class="ft-clipboard" style="font-size:11px;margin-right:4px;"></i> Jobs / Careers</a>
                            </li>
                        </ul>
                    </li>

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