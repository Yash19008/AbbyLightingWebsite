<?php 
return [
    'Project' => env('APP_NAME'),
    'APP_URL' => env('APP_URL'),
    'FORCE_HTTPS' => env('FORCE_HTTPS', false),
    'css_js_version' => '0.0.4',

    'maxAttempts' => 10,
    'decayMinutes' => 2,

    'api_key' => '',
    'fcm_google_api_push_key' => '',

    'api_success_res' => 200,
    'api_forcefully_logout_res' => 203,
    'api_error_res' => 202,
    
    'token_expiration_time_mins' => 30,

    'PUBLIC_PATH' => '',
    'record_listing_limit' => 5,

     /************PERMISION OF ADMIN PENAL MODULE WISE***************/
    //  'admin_module_permission' => [
    //     'dashboard' => [
    //         'index' => ['super_admin','sub_admin'],
    //     ],
    //     'profile' => [
    //         'index' => ['super_admin','sub_admin'],
    //         'update' => ['super_admin','sub_admin'],
    //     ],
    //     'delete' => [
    //         'index' => ['super_admin','sub_admin'],
    //     ],
    //     'status' => [
    //         'index' => ['super_admin','sub_admin'],
    //     ],
    //     'admins' => [
    //         'index' => ['super_admin'],
    //         'add' => ['super_admin'],
    //         'insert' => ['super_admin'],
    //         'edit' => ['super_admin'],
    //         'update' => ['super_admin'],
    //         'information' => ['super_admin'],
    //     ],
    //     'users' => [
    //         'index' => ['super_admin','sub_admin'],
    //         'add' => ['super_admin','sub_admin'],
    //         'insert' => ['super_admin','sub_admin'],
    //         'edit' => ['super_admin','sub_admin'],
    //         'update' => ['super_admin','sub_admin'],
    //         'information' => ['super_admin','sub_admin'],
    //     ],
    //     'mail-settings' => [
    //         'index' => ['super_admin','sub_admin'],
    //         'add' => ['super_admin','sub_admin'],
    //         'insert' => ['super_admin','sub_admin'],
    //         'edit' => ['super_admin','sub_admin'],
    //         'update' => ['super_admin','sub_admin'],
    //         'information' => ['super_admin','sub_admin'],
    //     ],
    //     'web-pages' => [
    //         'index' => ['super_admin','sub_admin'],
    //         'add' => ['super_admin','sub_admin'],
    //         'insert' => ['super_admin','sub_admin'],
    //         'edit' => ['super_admin','sub_admin'],
    //         'update' => ['super_admin','sub_admin'],
    //         'information' => ['super_admin','sub_admin'],
    //     ],
    //     'settings' => [
    //         'index' => ['super_admin','sub_admin'],
    //         'add' => ['super_admin','sub_admin'],
    //         'insert' => ['super_admin','sub_admin'],
    //         'edit' => ['super_admin','sub_admin'],
    //         'update' => ['super_admin','sub_admin'],
    //         'information' => ['super_admin','sub_admin'],
    //     ],
    // ],

        
   

];