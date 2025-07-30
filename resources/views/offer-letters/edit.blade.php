@extends('layouts.app')
@section('title', 'Edit Offer Letter - StaffIQ')
@section('page-title', 'Edit Offer Letter')

@section('styles')
<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/kcenergm4kg3lo1rv1ktr7pih8ledc05b8rkb9ad5tw377r8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<style>
    .tox-tinymce {
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .rich-text-section {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .rich-text-section h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Offer Letter</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('web.offer-letters.update', $offerLetter) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_name" class="form-label">Candidate Name</label>
                        <input type="text" name="candidate_name" value="{{ $offerLetter->candidate_name }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_email" class="form-label">Candidate Email</label>
                        <input type="email" name="candidate_email" value="{{ $offerLetter->candidate_email }}" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_phone" class="form-label">Candidate Phone</label>
                        <input type="tel" name="candidate_phone" value="{{ $offerLetter->candidate_phone }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" value="{{ $offerLetter->position }}" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" class="form-control">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $offerLetter->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="offered_salary" class="form-label">Offered Salary (₹)</label>
                        <input type="number" name="offered_salary" value="{{ $offerLetter->offered_salary }}" class="form-control" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="offer_date" class="form-label">Offer Date</label>
                        <input type="date" name="offer_date" value="{{ $offerLetter->offer_date ? $offerLetter->offer_date->format('Y-m-d') : date('Y-m-d') }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="joining_date" class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" value="{{ $offerLetter->joining_date ? $offerLetter->joining_date->format('Y-m-d') : '' }}" class="form-control" required>
                    </div>
                </div>
            </div>

            <!-- Rich Text Editor Sections -->
            <div class="rich-text-section">
                <h6><i class="fas fa-briefcase me-2"></i>Job Description</h6>
                <textarea name="job_description" id="job_description" class="form-control">{{ $offerLetter->job_description }}</textarea>
            </div>

            <div class="rich-text-section">
                <h6><i class="fas fa-gift me-2"></i>Benefits & Perks</h6>
                <textarea name="benefits" id="benefits" class="form-control">{{ $offerLetter->benefits }}</textarea>
            </div>

            <div class="rich-text-section">
                <h6><i class="fas fa-file-contract me-2"></i>Terms & Conditions</h6>
                <textarea name="terms_and_conditions" id="terms_and_conditions" class="form-control">{{ $offerLetter->terms_and_conditions }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('web.offer-letters.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Offer Letter
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
tinymce.init({
    selector: '#job_description',
    height: 300,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
    branding: false,
    menubar: false
});

tinymce.init({
    selector: '#benefits',
    height: 250,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
    branding: false,
    menubar: false
});

tinymce.init({
    selector: '#terms_and_conditions',
    height: 300,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
    branding: false,
    menubar: false
});
</script>
@endsection
