@extends('layouts.app')
@section('title', 'View Not Found - HRMS')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 text-warning">📄</h1>
                <h3>View Not Found</h3>
                <p class="text-muted">The view "{{ $view ?? 'Unknown' }}" could not be found.</p>
                <div class="alert alert-info">
                    <strong>Debug Information:</strong><br>
                    <small>View: {{ $view ?? 'Unknown' }}</small><br>
                    <small>Data Keys: {{ isset($data) ? implode(', ', array_keys($data)) : 'None' }}</small>
                </div>
                <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
            </div>
        </div>
    </div>
</div>
@endsection
