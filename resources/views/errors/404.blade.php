@extends('layouts.app')
@section('title', 'Page Not Found - HRMS')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <h1 class="display-1 text-muted">404</h1>
                <h3>Page Not Found</h3>
                <p class="text-muted">The page you are looking for does not exist.</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
            </div>
        </div>
    </div>
</div>
@endsection
