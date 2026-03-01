@extends('admin.layout')
@section('title', 'Companies')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Company
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead><tr>
                <th class="ps-3">Company</th>
                <th>Email</th>
                <th>Contact Person</th>
                <th>Status</th>
                <th>Projects</th>
                <th>Total Revenue</th>
                <th>Actions</th>
            </tr></thead>
            <tbody>
            @forelse($companies as $c)
            <tr>
                <td class="ps-3">
                    <div class="fw-500">{{ $c->name }}</div>
                    @if($c->website)
                        <a href="{{ $c->website }}" target="_blank" class="small text-muted">{{ $c->website }}</a>
                    @endif
                </td>
                <td class="small"><a href="mailto:{{ $c->email }}">{{ $c->email }}</a></td>
                <td class="small text-muted">{{ $c->contact_person ?? '—' }}</td>
                <td>
                    <span class="badge rounded-pill badge-{{ $c->status === 'active' ? 'success' : ($c->status === 'lead' ? 'warning' : 'danger') }}">
                        {{ ucfirst($c->status) }}
                    </span>
                </td>
                <td class="small">{{ $c->financials_count }}</td>
                <td class="small fw-500">₦{{ number_format($c->financials_sum_project_cost ?? 0) }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.companies.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.companies.destroy', $c) }}" onsubmit="return confirm('Delete company?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No companies yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($companies->hasPages())
    <div class="card-footer">{{ $companies->links() }}</div>
    @endif
</div>
@endsection
