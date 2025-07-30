@extends('layouts.app')
@section('title', 'Server Error - StaffIQ')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <h1 class="display-1 text-danger">500</h1>
                <h3>Server Error</h3>
                <p class="text-muted">Something went wrong. Please try again later.</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
            </div>
        </div>
    </div>
</div>
@endsection
