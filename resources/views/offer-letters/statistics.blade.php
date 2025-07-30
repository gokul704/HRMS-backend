@extends('layouts.app')
@section('title', 'Offer Letter Statistics - StaffIQ')
@section('page-title', 'Offer Letter Statistics')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Offer Letter Statistics</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Offers</h5>
                        <h3>{{ $data['total_offers'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Accepted</h5>
                        <h3>{{ $data['accepted_offers'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Sent</h5>
                        <h3>{{ $data['sent_offers'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5>Rejected</h5>
                        <h3>{{ $data['rejected_offers'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
