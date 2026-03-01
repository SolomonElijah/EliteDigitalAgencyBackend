@extends('admin.layout')
@section('title', 'Contact Submissions')

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead><tr>
                <th class="ps-3">Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr></thead>
            <tbody>
            @forelse($contacts as $c)
            <tr class="{{ $c->status === 'new' ? 'fw-500' : '' }}">
                <td class="ps-3">
                    {{ $c->status === 'new' ? '🔴 ' : '' }}{{ $c->name }}
                </td>
                <td class="small"><a href="mailto:{{ $c->email }}">{{ $c->email }}</a></td>
                <td class="small text-muted">{{ Str::limit($c->subject, 30) ?? '—' }}</td>
                <td class="small text-muted">{{ Str::limit($c->message, 50) }}</td>
                <td>
                    <span class="badge rounded-pill badge-{{ $c->status === 'new' ? 'danger' : ($c->status === 'replied' ? 'success' : 'warning') }}">
                        {{ ucfirst($c->status) }}
                    </span>
                </td>
                <td class="small text-muted">{{ $c->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.contacts.show', $c) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.contacts.destroy', $c) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No contacts yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($contacts->hasPages())
    <div class="card-footer">{{ $contacts->links() }}</div>
    @endif
</div>
@endsection
