@extends('layouts.app')

@section('title', 'My Leaves')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Leaves</li>
                    </ol>
                </div>
                <h4 class="page-title">My Leave Requests</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Leave History</h5>
                        <a href="{{ route('employees.request-leave') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Request Leave
                        </a>
                    </div>

                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total Days</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $leave)
                                        <tr>
                                            <td>
                                                <span class="badge bg-info">{{ $leave->leave_type_label }}</span>
                                            </td>
                                            <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                            <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                            <td>{{ $leave->total_days }} day(s)</td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                      title="{{ $leave->reason }}">
                                                    {{ $leave->reason }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $leave->status_badge }}">
                                                    {{ $leave->status_label }}
                                                </span>
                                            </td>
                                            <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#leaveModal{{ $leave->id }}">
                                                    <i class="mdi mdi-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $leaves->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-calendar-remove font-size-48 text-muted"></i>
                            <h5 class="text-muted mt-2">No Leave Requests</h5>
                            <p class="text-muted">You haven't submitted any leave requests yet.</p>
                            <a href="{{ route('employees.request-leave') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Request Your First Leave
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Detail Modals -->
@foreach($leaves as $leave)
<div class="modal fade" id="leaveModal{{ $leave->id }}" tabindex="-1" aria-labelledby="leaveModalLabel{{ $leave->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveModalLabel{{ $leave->id }}">
                    Leave Request Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Leave Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Leave Type:</strong></td>
                                <td>{{ $leave->leave_type_label }}</td>
                            </tr>
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td>{{ $leave->start_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>End Date:</strong></td>
                                <td>{{ $leave->end_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Days:</strong></td>
                                <td>{{ $leave->total_days }} day(s)</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $leave->status_badge }}">
                                        {{ $leave->status_label }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Request Details</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Applied By:</strong></td>
                                <td>{{ $leave->appliedBy->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Applied Date:</strong></td>
                                <td>{{ $leave->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @if($leave->approvedBy)
                            <tr>
                                <td><strong>Approved By:</strong></td>
                                <td>{{ $leave->approvedBy->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Approved Date:</strong></td>
                                <td>{{ $leave->approved_at ? $leave->approved_at->format('M d, Y H:i') : 'N/A' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="font-weight-bold">Reason</h6>
                        <p class="border rounded p-3 bg-light">{{ $leave->reason }}</p>
                    </div>
                </div>

                @if($leave->manager_remarks || $leave->hr_remarks)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="font-weight-bold">Approval Remarks</h6>
                        @if($leave->manager_remarks)
                        <div class="alert alert-info">
                            <strong>Manager Remarks:</strong> {{ $leave->manager_remarks }}
                        </div>
                        @endif
                        @if($leave->hr_remarks)
                        <div class="alert alert-info">
                            <strong>HR Remarks:</strong> {{ $leave->hr_remarks }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
