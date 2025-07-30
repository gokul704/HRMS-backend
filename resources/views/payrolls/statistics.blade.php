@extends('layouts.app')
@section('title', 'Payroll Statistics - StaffIQ')
@section('page-title', 'Payroll Statistics')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Payroll Statistics</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Payrolls</h5>
                        <h3>{{ $data['total_payrolls'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Paid</h5>
                        <h3>{{ $data['paid_payrolls'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5>Pending</h5>
                        <h3>{{ $data['pending_payrolls'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5>Failed</h5>
                        <h3>{{ $data['failed_payrolls'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
