@extends('admin.layout')
@section('title', 'Email Campaigns')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> New Campaign
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead><tr>
                <th class="ps-3">Campaign Name</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Recipients</th>
                <th>Sent</th>
                <th>Success Rate</th>
                <th>Sent At</th>
                <th>Actions</th>
            </tr></thead>
            <tbody>
            @forelse($campaigns as $c)
            <tr>
                <td class="ps-3 fw-500">{{ $c->name }}</td>
                <td class="text-muted small" style="max-width:200px">{{ Str::limit($c->subject, 40) }}</td>
                <td>
                    <span class="badge rounded-pill badge-{{ $c->status === 'sent' ? 'success' : ($c->status === 'sending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($c->status) }}
                    </span>
                </td>
                <td class="small">{{ $c->total_recipients }}</td>
                <td class="small text-success">{{ $c->sent_count }}
                    @if($c->failed_count) <span class="text-danger">({{ $c->failed_count }} failed)</span> @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress" style="width:60px;height:6px;background:#e9ecef;border-radius:3px">
                            <div class="progress-bar bg-success" style="width:{{ $c->success_rate }}%"></div>
                        </div>
                        <small>{{ $c->success_rate }}%</small>
                    </div>
                </td>
                <td class="text-muted small">{{ $c->sent_at?->format('M d, Y H:i') ?? '—' }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.campaigns.recipients', $c) }}" class="btn btn-sm btn-outline-secondary" title="Recipients">
                            <i class="bi bi-people"></i>
                        </a>
                        @if($c->status !== 'sent')
                        <form method="POST" action="{{ route('admin.campaigns.send', $c) }}"
                            onsubmit="return confirm('Send this campaign to {{ $c->total_recipients }} recipients?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-success" title="Send Now">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.campaigns.destroy', $c) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No campaigns yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($campaigns->hasPages())
    <div class="card-footer">{{ $campaigns->links() }}</div>
    @endif
</div>
@endsection
