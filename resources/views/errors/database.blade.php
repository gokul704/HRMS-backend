@extends('layouts.app')
@section('title', 'Database Error - HRMS')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <h1 class="display-1 text-warning">⚠️</h1>
                <h3>Database Connection Error</h3>
                <p class="text-muted">Unable to connect to the database. Please check your configuration.</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Try Again</a>
            </div>
        </div>
    </div>
</div>
@endsection
