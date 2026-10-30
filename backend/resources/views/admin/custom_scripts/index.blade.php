@extends('admin.page')

@section('title', 'Custom Header & Footer Scripts')

@section('extra_css')
<!-- CodeMirror CSS for syntax highlighting -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
<style>
    .CodeMirror {
        height: 320px;
        border-radius: 6px;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 13.5px;
        line-height: 1.5;
        border: 1px solid #ced4da;
    }
    .script-card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .script-badge {
        font-family: monospace;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
        background: #e9ecef;
        color: #495057;
    }
    .guide-card {
        background: #fff;
        border-left: 4px solid #000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        padding: 16px;
        border-radius: 0 6px 6px 0;
        margin-bottom: 16px;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0 font-weight-bold">Custom Header &amp; Footer Scripts</h3>
                <small class="text-muted">Inject custom tracking codes, meta tags, Google Tag Manager, analytics, pixels, or chat widgets across the entire website.</small>
            </div>
            <div>
                <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-dark btn-sm">
                    <i class="ft-external-link mr-1"></i> View Live Site
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ft-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ft-alert-triangle mr-2"></i> <strong>Please check the errors below:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<form action="{{ route('admin.custom_scripts.update') }}" method="POST" id="custom-scripts-form">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8 col-12">
            <!-- Global Enable/Disable Toggle -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 font-weight-bold">Enable Custom Scripts</h5>
                        <small class="text-muted">Toggle whether custom header and footer scripts should be actively injected on live pages.</small>
                    </div>
                    <div class="custom-control custom-switch" style="transform: scale(1.3); margin-right: 1.5rem;">
                        <input type="checkbox" class="custom-control-input" id="enabledSwitch" name="enabled" value="1" {{ $enabled ? 'checked' : '' }}>
                        <label class="custom-control-label" for="enabledSwitch"></label>
                    </div>
                </div>
            </div>

            <!-- Header Scripts (<head>) -->
            <div class="card mb-4 shadow-sm">
                <div class="script-card-header">
                    <div>
                        <strong class="text-dark"><i class="ft-code mr-1"></i> Header Scripts</strong>
                        <span class="ml-2 text-muted small">(HTML, &lt;script&gt;, &lt;style&gt;, &lt;meta&gt;)</span>
                    </div>
                    <span class="script-badge">&lt;head&gt; ... &lt;/head&gt;</span>
                </div>
                <div class="card-body p-0">
                    <textarea id="header_scripts" name="header_scripts" class="form-control">{{ $headerScripts }}</textarea>
                </div>
                <div class="card-footer bg-light py-2">
                    <small class="text-muted">
                        Injected inside the <code>&lt;head&gt;</code> tag on both Next.js frontend pages and Laravel backend pages. Ideal for Google Tag Manager (head part), Google Analytics, Facebook Pixel base code, site verification meta tags, or custom CSS.
                    </small>
                </div>
            </div>

            <!-- Footer Scripts (<body>) -->
            <div class="card mb-4 shadow-sm">
                <div class="script-card-header">
                    <div>
                        <strong class="text-dark"><i class="ft-terminal mr-1"></i> Footer Scripts</strong>
                        <span class="ml-2 text-muted small">(HTML, &lt;script&gt;, widgets)</span>
                    </div>
                    <span class="script-badge">Before &lt;/body&gt;</span>
                </div>
                <div class="card-body p-0">
                    <textarea id="footer_scripts" name="footer_scripts" class="form-control">{{ $footerScripts }}</textarea>
                </div>
                <div class="card-footer bg-light py-2">
                    <small class="text-muted">
                        Injected right before the closing <code>&lt;/body&gt;</code> tag across the entire website. Ideal for live chat widgets (Tawk.to, WhatsApp, Intercom), Google Tag Manager (noscript body part), conversion trackers, and non-critical deferred JavaScript.
                    </small>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="ft-eye mr-1"></i> Preview Live Site
                </a>
                <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold">
                    <i class="ft-save mr-1"></i> Save Custom Scripts
                </button>
            </div>
        </div>

        <!-- Documentation & Examples Sidebar -->
        <div class="col-lg-4 col-12">
            <div class="guide-card">
                <h6 class="font-weight-bold mb-2"><i class="ft-info mr-1 text-primary"></i> How It Works</h6>
                <p class="small text-muted mb-0">
                    Any valid HTML tags placed here will be safely injected into both the <strong>Next.js frontend</strong> (via <code>layout.tsx</code>) and all <strong>Laravel web pages</strong> (via master layouts).
                </p>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold"><i class="ft-book-open mr-1"></i> Common Snippets</h6>
                </div>
                <div class="card-body p-3 small">
                    <div class="mb-3">
                        <strong class="d-block text-dark">Google Tag Manager (GTM):</strong>
                        <span class="text-muted">Place the primary <code>&lt;script&gt;</code> block in <strong>Header Scripts</strong>, and the <code>&lt;noscript&gt;</code> iframe block in <strong>Footer Scripts</strong>.</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block text-dark">Meta / Facebook Pixel:</strong>
                        <span class="text-muted">Place the Facebook Pixel base code script inside <strong>Header Scripts</strong>.</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block text-dark">Live Chat / Support Widgets:</strong>
                        <span class="text-muted">Place WhatsApp floating button, Crisp, or Tawk.to embed scripts inside <strong>Footer Scripts</strong> so page loading stays fast.</span>
                    </div>
                    <div>
                        <strong class="d-block text-dark">Custom CSS Overrides:</strong>
                        <span class="text-muted">Wrap any custom styling rules in <code>&lt;style&gt; ... &lt;/style&gt;</code> and place them in <strong>Header Scripts</strong>.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('extra_js')
<!-- CodeMirror Syntax Editor JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize CodeMirror on Header Scripts
        var headerEditor = CodeMirror.fromTextArea(document.getElementById('header_scripts'), {
            mode: 'htmlmixed',
            theme: 'dracula',
            lineNumbers: true,
            lineWrapping: true,
            indentUnit: 4,
            tabSize: 4,
            autoCloseTags: true,
            matchBrackets: true
        });

        // Initialize CodeMirror on Footer Scripts
        var footerEditor = CodeMirror.fromTextArea(document.getElementById('footer_scripts'), {
            mode: 'htmlmixed',
            theme: 'dracula',
            lineNumbers: true,
            lineWrapping: true,
            indentUnit: 4,
            tabSize: 4,
            autoCloseTags: true,
            matchBrackets: true
        });

        // Sync CodeMirror back to textarea on form submit
        $('#custom-scripts-form').on('submit', function() {
            headerEditor.save();
            footerEditor.save();
        });
    });
</script>
@stop

