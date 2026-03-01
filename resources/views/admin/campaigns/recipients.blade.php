@extends('admin.layout')
@section('title', 'Campaign Recipients — ' . $campaign->name)

@section('content')
<div class="row g-3">
    <!-- Add recipients -->
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-upload me-1"></i> Upload Excel / CSV
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.campaigns.recipients.add', $campaign) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-500">Excel / CSV File</label>
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv">
                        <div class="form-text">
                            Columns: <strong>name</strong>, <strong>email</strong>, <strong>company</strong><br>
                            First row must be the header row.
                        </div>
                    </div>
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import Excel
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-1"></i> Add Manually
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.campaigns.recipients.add', $campaign) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-500">Recipients (one per line)</label>
                        <textarea name="recipients" rows="8" class="form-control" style="font-family:monospace;font-size:0.82rem"
                            placeholder="John Doe, john@example.com, Acme Corp&#10;jane@example.com&#10;Bob Smith, bob@co.com"></textarea>
                        <div class="form-text">Format: <code>name, email, company</code> or just <code>email</code></div>
                    </div>
                    <button class="btn btn-outline-primary w-100">
                        <i class="bi bi-person-plus me-1"></i> Add Recipients
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Recipients list + send -->
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-people me-1"></i>
                    Recipients <span class="badge bg-secondary ms-1">{{ $campaign->total_recipients }}</span>
                </span>
                @if($campaign->status !== 'sent' && $campaign->total_recipients > 0)
                <form method="POST" action="{{ route('admin.campaigns.send', $campaign) }}"
                    onsubmit="return confirm('Send to {{ $campaign->total_recipients }} recipients now?')">
                    @csrf
                    <button class="btn btn-success btn-sm">
                        <i class="bi bi-send-fill me-1"></i> Send Campaign
                    </button>
                </form>
                @elseif($campaign->status === 'sent')
                <span class="badge badge-success">Sent {{ $campaign->sent_at?->format('M d Y') }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-sm mb-0">
                    <thead><tr>
                        <th class="ps-3">Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Status</th>
                    </tr></thead>
                    <tbody>
                    @forelse($recipients as $r)
                    <tr>
                        <td class="ps-3 small">{{ $r->name ?? '—' }}</td>
                        <td class="small">{{ $r->email }}</td>
                        <td class="small text-muted">{{ $r->company ?? '—' }}</td>
                        <td>
                            <span class="badge rounded-pill badge-{{ $r->status === 'sent' ? 'success' : ($r->status === 'failed' ? 'danger' : 'warning') }}" style="font-size:0.65rem">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3 small">No recipients yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($recipients->hasPages())
            <div class="card-footer">{{ $recipients->links() }}</div>
            @endif
        </div>

        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Campaigns
        </a>
    </div>
</div>

<!-- Excel Template Download hint -->
<div class="card mt-3 border-info" style="background:#f0f9ff">
    <div class="card-body py-2 d-flex align-items-center gap-2">
        <i class="bi bi-info-circle-fill text-info"></i>
        <small>
            <strong>Excel format:</strong> Create a spreadsheet with columns
            <code>name</code>, <code>email</code>, <code>company</code>.
            Save as .xlsx or .csv before uploading.
        </small>
    </div>
</div>
@endsection
