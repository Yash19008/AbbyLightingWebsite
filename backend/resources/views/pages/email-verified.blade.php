@extends('layout.web', ['theme' => 'light'])
@push('css')
@vite(['resources/scss/policy.scss'])
@endpush
@section("title", "Email Verified | Abby Lighting") 
@section('page-content')
<div class="container">
    <div class="sub-header mt-3 text-center">
        <strong>Your email has been verified</strong>
    </div>
</div>
@endsection