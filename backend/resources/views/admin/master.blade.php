<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>Abby Lighting | @yield('title', '@$title')</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('adminlte/img/logo-dark.png')}}">
        <link rel="shortcut icon" type="image/png" href="{{asset('adminlte/img/logo-dark.png')}}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900%7CMontserrat:300,400,500,600,700,800,900" rel="stylesheet">
        <!-- BEGIN VENDOR CSS-->
        <!-- font icons-->
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/fonts/feather/style.min.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/fonts/simple-line-icons/style.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/fonts/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/vendors/css/perfect-scrollbar.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/vendors/css/prism.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/vendors/css/pickadate/pickadate.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/vendors/css/switchery.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/vendors/css/select2.min.css')}}">
        <!-- Datatable css -->
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/vendors/css/datatables/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/vendors/css/chartist.min.css')}}">
        <!-- END VENDOR CSS-->
        <!-- BEGIN APEX CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/bootstrap.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/bootstrap-extended.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/colors.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/components.css')}}">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/themes/layout-dark.css')}}">
        <link rel="stylesheet" href="{{asset('adminlte/css/plugins/switchery.css')}}">
        <!-- END APEX CSS-->
        <!-- BEGIN Page Level CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/pages/dashboard1.css')}}">
        <!-- END Page Level CSS-->
        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/custom.css')}}">

        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/style.css')}}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" />
        <!-- END: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/premium-admin.css')}}">
        
        <!-- Fix for double scrollbars -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/admin-scrollbar-fix.css')}}">
        <style>
            .main-content, .content-wrapper {
                padding-bottom: 30px !important;
            }
            .custom-switch {
                padding-left: 3rem;
            }
        </style>
        
        @yield('extra_css')
    </head>
    <body class="vertical-layout vertical-menu 2-columns navbar-sticky menu-expanded page-scrolled pace-done" data-menu="vertical-menu" data-col="2-columns">

    @yield('body' )

    <!-- REQUIRED SCRIPTS -->


    @include("admin.include.js_message")
    <script src="{{asset('adminlte/vendors/js/vendors.min.js')}}"></script>
    <!-- <script src="http://code.jquery.com/jquery-1.8.3.min.js" type="text/javascript"></script> -->
    <script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/rxjs/5.4.0/Rx.js"></script>
    <script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js" type="text/javascript"></script>
     <script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
     <!-- BEGIN VENDOR JS-->
    <script src="{{asset('adminlte/vendors/js/switchery.min.js')}}"></script>
    <!-- BEGIN VENDOR JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <!-- BEGIN APEX JS-->
    <script src="{{asset('adminlte/js/core/app-menu.js')}}"></script>
    <script src="{{asset('adminlte/js/notification-sidebar.js')}}"></script>

    <script src="{{asset('adminlte/js/scroll-top.js')}}"></script>
    <script src="{{asset('adminlte/vendors/js/select2.full.min.js')}}"></script>

    <!-- Datatable js -->
        <script src="{{asset('js/admin/datatables.min.js')}}"></script>
        <script src="{{asset('js/admin/dataTables.bootstrap4.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js">
       </script>
    <!-- Datatable js -->
    <script src="{{ asset('js/admin/validate_init.js').'?v='.config('custom_config.css_js_version')  }}"></script>
    <script src="{{  asset('js/admin/custom.js').'?v='.config('custom_config.css_js_version')  }}"></script>
    <!-- END APEX JS-->
    <!-- BEGIN PAGE LEVEL JS-->

    <!-- END PAGE LEVEL JS-->
    <!-- BEGIN: Custom CSS-->
    <script src="{{asset('adminlte/js/scripts.js')}}"></script>
    <!-- END: Custom CSS-->
    <!-- Submenu toggle fix: prevent theme from auto-expanding all submenus -->
    <script>
    $(document).ready(function() {
        // Collapse all submenus that are NOT marked open by the server
        $('.navigation-main li.has-sub').not('.open').each(function() {
            $(this).children('ul.menu-content').hide();
        });

        // Remove any duplicate click handlers and bind our own clean one
        $('.navigation-main').off('click.app.menu', 'li');
        $(document).off('click', '.has-sub > a');

        // Bind clean toggle on the anchor inside has-sub
        $(document).on('click', '.navigation-main li.has-sub > a', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $li = $(this).parent();
            if ($li.hasClass('open')) {
                $li.removeClass('open');
                $li.children('ul.menu-content').slideUp(300);
            } else {
                // Close siblings first
                $li.siblings('.open').removeClass('open').children('ul.menu-content').slideUp(300);
                $li.addClass('open');
                $li.children('ul.menu-content').slideDown(300);
            }
        });
    });
    </script>
    @yield('extra_js')
    <script type="text/javascript">
        var siteUrl= "{{url('/admin')}}";
        var baseUrl= "{{url('/')}}";
    </script>
    </body>
</html>
