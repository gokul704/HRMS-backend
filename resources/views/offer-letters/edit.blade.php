@extends('layouts.app')
@section('title', 'Edit Offer Letter - HRMS')
@section('page-title', 'Edit Offer Letter')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Offer Letter</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('web.offer-letters.update', $offerLetter) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_name" class="form-label">Candidate Name</label>
                        <input type="text" name="candidate_name" value="{{ $offerLetter->candidate_name }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_email" class="form-label">Candidate Email</label>
                        <input type="email" name="candidate_email" value="{{ $offerLetter->candidate_email }}" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" value="{{ $offerLetter->position }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" class="form-control">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $offerLetter->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" name="salary" value="{{ $offerLetter->salary }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Offer Letter Content</label>
                <textarea name="content" class="form-control" rows="10">{{ $offerLetter->content }}</textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('web.offer-letters.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update Offer Letter</button>
            </div>
        </form>
    </div>
</div>
@endsection
