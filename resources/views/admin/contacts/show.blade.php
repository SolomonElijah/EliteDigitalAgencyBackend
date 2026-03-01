@extends('admin.layout')
@section('title', 'Contact from ' . $contact->name)

@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Message Details</span>
                <span class="badge badge-{{ $contact->status === 'new' ? 'danger' : ($contact->status === 'replied' ? 'success' : 'warning') }}">
                    {{ ucfirst($contact->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Name</div>
                        <div class="fw-500">{{ $contact->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Email</div>
                        <div><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></div>
                    </div>
                    @if($contact->phone)
                    <div class="col-md-6">
                        <div class="text-muted small">Phone</div>
                        <div>{{ $contact->phone }}</div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="text-muted small">Received</div>
                        <div class="small">{{ $contact->created_at->format('M d, Y \a\t H:i') }}</div>
                    </div>
                </div>

                @if($contact->subject)
                <div class="mb-3">
                    <div class="text-muted small mb-1">Subject</div>
                    <div class="fw-500">{{ $contact->subject }}</div>
                </div>
                @endif

                <div>
                    <div class="text-muted small mb-1">Message</div>
                    <div style="background:#f9f9f9;padding:1rem;border-radius:8px;border-left:4px solid #302b63;line-height:1.7">
                        {{ $contact->message }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Reply -->
        <div class="card">
            <div class="card-header"><i class="bi bi-reply-fill me-1"></i> Send Reply</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.contacts.reply', $contact) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-500">Reply to: <strong>{{ $contact->email }}</strong></label>
                        <textarea name="reply_message" rows="6" class="form-control"
                            required placeholder="Type your reply here...">{{ old('reply_message', $contact->admin_notes) }}</textarea>
                    </div>
                    <button class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Send Reply
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Actions</div>
            <div class="card-body d-grid gap-2">
                <form method="POST" action="{{ route('admin.contacts.status', $contact) }}">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select form-select-sm mb-2" onchange="this.form.submit()">
                        <option value="new" {{ $contact->status === 'new' ? 'selected' : '' }}>New</option>
                        <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>Read</option>
                        <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>Replied</option>
                    </select>
                </form>
                <a href="mailto:{{ $contact->email }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-envelope me-1"></i> Open in Mail App
                </a>
                <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i> Delete</button>
                </form>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
