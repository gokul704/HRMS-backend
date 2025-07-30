@extends('layouts.app')
@section('title', 'Department Offer Letters - StaffIQ')
@section('page-title', 'Offer Letters for {{ $department->name }}')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Offer Letters for {{ $department->name }}</h5>
    </div>
    <div class="card-body">
        @if(count($offerLetters) > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Position</th>
                            <th>Salary</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offerLetters as $offerLetter)
                        <tr>
                            <td>{{ $offerLetter->candidate_name }}</td>
                            <td>{{ $offerLetter->position }}</td>
                            <td>₹{{ number_format($offerLetter->salary, 2) }}</td>
                            <td>{{ ucfirst($offerLetter->status) }}</td>
                            <td>
                                <a href="{{ route('web.offer-letters.show', $offerLetter) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No offer letters found for this department.</p>
        @endif
    </div>
</div>
@endsection
