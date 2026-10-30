@php
    $footerScripts = \App\Models\Setting::getValue('CUSTOM_FOOTER_SCRIPTS');
    $scriptsEnabled = \App\Models\Setting::getValue('CUSTOM_SCRIPTS_ENABLED', '1') === '1';
@endphp
@if($scriptsEnabled && !empty($footerScripts))
    {!! $footerScripts !!}
@endif

