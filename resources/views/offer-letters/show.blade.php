@extends('layouts.app')
@section('title', 'Offer Letter Details - HRMS')
@section('page-title', 'Offer Letter Details')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Offer Letter for {{ $offerLetter->candidate_name }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Candidate Information</h6>
                <p><strong>Name:</strong> {{ $offerLetter->candidate_name }}</p>
                <p><strong>Email:</strong> {{ $offerLetter->candidate_email }}</p>
                <p><strong>Position:</strong> {{ $offerLetter->position }}</p>
                <p><strong>Department:</strong> {{ $offerLetter->department->name ?? 'N/A' }}</p>
                <p><strong>Salary:</strong> ${{ number_format($offerLetter->salary, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($offerLetter->status) }}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h6>Offer Letter Content</h6>
                <div class="border p-3 bg-light">
                    {!! nl2br(e($offerLetter->content)) !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('web.offer-letters.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('web.offer-letters.edit', $offerLetter) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection
