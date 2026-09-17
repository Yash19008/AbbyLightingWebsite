@extends('admin.page')
@section('title', 'Edit Spec Attribute')
@php $main_module = 'Decorative Product'; @endphp
@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Edit Spec Attribute</h3>
        </div>
        <div class="content-header-right text-md-right col-md-6 col-12">
            <a href="{{ route('decorative_spec_attributes_admin') }}" class="btn btn-secondary">
                <i class="ft-arrow-left"></i> Back to Attributes
            </a>
        </div>
    </div>
    
    <div class="content-body">
        <section id="basic-form-layouts">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <form class="form" action="{{ route('decorative_spec_attributes_admin.update', $attribute->id) }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Attribute Name <span class="text-danger">*</span></label>
                                                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $attribute->name) }}" required>
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ft-check"></i> Update Attribute
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
