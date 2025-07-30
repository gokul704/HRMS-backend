@extends('layouts.app')

@section('title', 'Offer Letters - StaffIQ')

@section('page-title', 'Offer Letters')

@section('page-actions')
@if(auth()->user()->isHr())
<a href="{{ route('web.offer-letters.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>
    Create Offer Letter
</a>
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-file-contract me-2"></i>
            All Offer Letters
        </h5>
    </div>
    <div class="card-body">
        @if(isset($offerLetters) && count($offerLetters) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Salary</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offerLetters as $offerLetter)
                        <tr>
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $offerLetter->candidate_name }}</h6>
                                    <small class="text-muted">{{ $offerLetter->candidate_email }}</small>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $offerLetter->position }}</strong>
                            </td>
                            <td>
                                {{ $offerLetter->department->name ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="text-success">₹{{ number_format($offerLetter->salary, 2) }}</span>
                            </td>
                            <td>
                                @if($offerLetter->status === 'draft')
                                    <span class="badge bg-secondary rounded-pill">Draft</span>
                                @elseif($offerLetter->status === 'sent')
                                    <span class="badge bg-info rounded-pill">Sent</span>
                                @elseif($offerLetter->status === 'accepted')
                                    <span class="badge bg-success rounded-pill">Accepted</span>
                                @elseif($offerLetter->status === 'rejected')
                                    <span class="badge bg-danger rounded-pill">Rejected</span>
                                @elseif($offerLetter->status === 'expired')
                                    <span class="badge bg-warning rounded-pill">Expired</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($offerLetter->status) }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $offerLetter->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('web.offer-letters.show', $offerLetter) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->isHr())
                                    <a href="{{ route('web.offer-letters.edit', $offerLetter) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($offerLetter->status === 'draft')
                                        <form method="POST" action="{{ route('web.offer-letters.send', $offerLetter) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-info"
                                                    title="Send Offer">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('web.offer-letters.destroy', $offerLetter) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this offer letter?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if(auth()->user()->isManager() && $offerLetter->status === 'sent')
                                        <form method="POST" action="{{ route('web.offer-letters.approve', $offerLetter) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                    title="Mark as Accepted">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(isset($offerLetters) && method_exists($offerLetters, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $offerLetters->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Offer Letters Found</h5>
                <p class="text-muted">
                    @if(auth()->user()->isHr())
                        Get started by creating your first offer letter.
                    @else
                        No offer letters are available at the moment.
                    @endif
                </p>
                @if(auth()->user()->isHr())
                <a href="{{ route('web.offer-letters.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Create Offer Letter
                </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
