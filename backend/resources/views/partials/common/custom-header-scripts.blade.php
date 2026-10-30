@php
    $headerScripts = \App\Models\Setting::getValue('CUSTOM_HEADER_SCRIPTS');
    $scriptsEnabled = \App\Models\Setting::getValue('CUSTOM_SCRIPTS_ENABLED', '1') === '1';
@endphp
@if($scriptsEnabled && !empty($headerScripts))
    {!! $headerScripts !!}
@endif

