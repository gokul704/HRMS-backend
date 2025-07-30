@extends('layouts.app')
@section('title', 'Offer Letter Details - StaffIQ')
@section('page-title', 'Offer Letter Details')

@section('styles')
<style>
    .offer-section {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .offer-section h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1rem;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
    }

    .rich-content {
        background: white;
        border-radius: 0.375rem;
        padding: 1rem;
        border: 1px solid #dee2e6;
    }

    .rich-content p {
        margin-bottom: 0.75rem;
    }

    .rich-content ul, .rich-content ol {
        margin-bottom: 0.75rem;
        padding-left: 1.5rem;
    }

    .status-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-file-contract me-2"></i>
            Offer Letter for {{ $offerLetter->candidate_name }}
        </h5>
    </div>
    <div class="card-body">
        <!-- Candidate Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="offer-section">
                    <h6><i class="fas fa-user me-2"></i>Candidate Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $offerLetter->candidate_name }}</p>
                            <p><strong>Email:</strong> {{ $offerLetter->candidate_email }}</p>
                            @if($offerLetter->candidate_phone)
                                <p><strong>Phone:</strong> {{ $offerLetter->candidate_phone }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Position:</strong> {{ $offerLetter->position }}</p>
                            <p><strong>Department:</strong> {{ $offerLetter->department->name ?? 'N/A' }}</p>
                            <p><strong>Salary:</strong> ₹{{ number_format($offerLetter->offered_salary, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="offer-section">
                    <h6><i class="fas fa-calendar me-2"></i>Offer Details</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Offer Date:</strong> {{ $offerLetter->offer_date ? $offerLetter->offer_date->format('M d, Y') : 'N/A' }}</p>
                            <p><strong>Joining Date:</strong> {{ $offerLetter->joining_date ? $offerLetter->joining_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                <span class="badge status-badge bg-{{ $offerLetter->status === 'accepted' ? 'success' : ($offerLetter->status === 'rejected' ? 'danger' : ($offerLetter->status === 'sent' ? 'info' : 'secondary')) }}">
                                    {{ ucfirst($offerLetter->status) }}
                                </span>
                            </p>
                            <p><strong>Created:</strong> {{ $offerLetter->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Description -->
        @if($offerLetter->job_description)
        <div class="offer-section">
            <h6><i class="fas fa-briefcase me-2"></i>Job Description</h6>
            <div class="rich-content">
                {!! $offerLetter->job_description !!}
            </div>
        </div>
        @endif

        <!-- Benefits & Perks -->
        @if($offerLetter->benefits)
        <div class="offer-section">
            <h6><i class="fas fa-gift me-2"></i>Benefits & Perks</h6>
            <div class="rich-content">
                {!! $offerLetter->benefits !!}
            </div>
        </div>
        @endif

        <!-- Terms & Conditions -->
        @if($offerLetter->terms_and_conditions)
        <div class="offer-section">
            <h6><i class="fas fa-file-contract me-2"></i>Terms & Conditions</h6>
            <div class="rich-content">
                {!! $offerLetter->terms_and_conditions !!}
            </div>
        </div>
        @endif

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('web.offer-letters.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <div>
                <a href="{{ route('web.offer-letters.edit', $offerLetter) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                @if($offerLetter->status === 'draft')
                    <form method="POST" action="{{ route('web.offer-letters.send', $offerLetter) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-info ms-2">
                            <i class="fas fa-paper-plane me-2"></i>Send Offer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
