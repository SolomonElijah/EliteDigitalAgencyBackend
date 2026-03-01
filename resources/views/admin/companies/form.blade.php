@extends('admin.layout')
@section('title', $company->exists ? 'Edit Company' : 'Add Company')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<form method="POST" action="{{ $company->exists ? route('admin.companies.update', $company) : route('admin.companies.store') }}">
    @csrf
    @if($company->exists) @method('PUT') @endif

    <div class="card mb-3">
        <div class="card-header">Company Information</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-500">Company Name *</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $company->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Email *</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $company->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Phone</label>
                    <input type="text" name="phone" class="form-control"
                        value="{{ old('phone', $company->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Website</label>
                    <input type="url" name="website" class="form-control"
                        value="{{ old('website', $company->website) }}" placeholder="https://">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control"
                        value="{{ old('contact_person', $company->contact_person) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Status</label>
                    <select name="status" class="form-select">
                        <option value="lead" {{ old('status', $company->status) === 'lead' ? 'selected' : '' }}>Lead</option>
                        <option value="active" {{ old('status', $company->status) === 'active' ? 'selected' : '' }}>Active Client</option>
                        <option value="inactive" {{ old('status', $company->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-500">Address</label>
                    <textarea name="address" rows="2" class="form-control">{{ old('address', $company->address) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-500">Internal Notes</label>
                    <textarea name="notes" rows="3" class="form-control"
                        placeholder="Private notes about this company...">{{ old('notes', $company->notes) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            {{ $company->exists ? 'Update Company' : 'Add Company' }}
        </button>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
</div>
</div>
@endsection
