@extends('admin.layout')
@section('title', 'New Email Campaign')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.campaigns.store') }}">
            @csrf

            <div class="card mb-3">
                <div class="card-header">Campaign Details</div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-500">Campaign Name *</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control"
                                   value="{{ old('name') }}" 
                                   required 
                                   placeholder="e.g. November Newsletter">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-500">Email Subject *</label>
                            <input type="text" 
                                   name="subject" 
                                   class="form-control"
                                   value="{{ old('subject') }}" 
                                   required 
                                   placeholder="Subject line recipients see">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">From Name</label>
                            <input type="text" 
                                   name="from_name" 
                                   class="form-control"
                                   value="{{ old('from_name', config('mail.from.name')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">From Email</label>
                            <input type="email" 
                                   name="from_email" 
                                   class="form-control"
                                   value="{{ old('from_email', config('mail.from.address')) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-500">Email Body (HTML) *</label>

                            <div class="form-text mb-2">
                                Use <code>@{{name}}</code> and <code>@{{company}}</code> as merge tags.
                            </div>

                            <textarea name="body" 
                                      rows="12" 
                                      class="form-control" 
                                      required
                                      placeholder="<h2>Hello @{{name}},</h2><p>Your message here...</p>">{{ old('body') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-arrow-right me-1"></i> Next: Add Recipients
                </button>

                <a href="{{ route('admin.campaigns.index') }}" 
                   class="btn btn-outline-secondary">
                   Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection