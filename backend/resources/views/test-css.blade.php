<!DOCTYPE html>
<html>
<head>
    <title>CSS Test Page</title>
</head>
<body>
    <h1>URL Generation Test</h1>
    
    <h2>Current Request Info:</h2>
    <ul>
        <li><strong>Request URL:</strong> {{ request()->url() }}</li>
        <li><strong>Request Full URL:</strong> {{ request()->fullUrl() }}</li>
        <li><strong>Host:</strong> {{ request()->getHost() }}</li>
        <li><strong>Port:</strong> {{ request()->getPort() }}</li>
        <li><strong>X-Forwarded-Host:</strong> {{ request()->header('X-Forwarded-Host', 'NOT SET') }}</li>
        <li><strong>X-Forwarded-Port:</strong> {{ request()->header('X-Forwarded-Port', 'NOT SET') }}</li>
        <li><strong>X-Forwarded-Proto:</strong> {{ request()->header('X-Forwarded-Proto', 'NOT SET') }}</li>
    </ul>

    <h2>URL Generation Test:</h2>
    <ul>
        <li><strong>APP_URL:</strong> {{ config('app.url') }}</li>
        <li><strong>asset('css/test.css'):</strong> {{ asset('css/test.css') }}</li>
        <li><strong>url('css/test.css'):</strong> {{ url('css/test.css') }}</li>
        <li><strong>URL::to('css/test.css'):</strong> {{ URL::to('css/test.css') }}</li>
    </ul>

    <h2>Expected URLs:</h2>
    <ul>
        <li>✅ Good: <code>http://localhost/css/test.css</code></li>
        <li>❌ Bad: <code>http://localhost:8000/css/test.css</code></li>
    </ul>
</body>
</html>
