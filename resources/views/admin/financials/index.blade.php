@extends('admin.layout')
@section('title', 'Financials')

@section('content')
<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="text-muted small mb-1">Total Revenue</div>
            <div class="fs-4 fw-700 text-primary">₦{{ number_format($summary['total_revenue']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="text-muted small mb-1">Total Expenses</div>
            <div class="fs-4 fw-700 text-danger">₦{{ number_format($summary['total_expenses']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="text-muted small mb-1">Net Profit</div>
            <div class="fs-4 fw-700 {{ $summary['total_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                ₦{{ number_format($summary['total_profit']) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="text-muted small mb-1">Outstanding</div>
            <div class="fs-4 fw-700 text-warning">₦{{ number_format($summary['unpaid']) }}</div>
        </div>
    </div>
</div>

<!-- Filter + Add -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2">
        <select name="company_id" class="form-select form-select-sm" style="width:200px">
            <option value="">All Companies</option>
            @foreach($companies as $c)
                <option value="{{ $c->id }}" {{ request('company_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
        <select name="payment_status" class="form-select form-select-sm" style="width:150px">
            <option value="">All Statuses</option>
            <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
        <button class="btn btn-sm btn-outline-secondary">Filter</button>
    </form>
    <a href="{{ route('admin.financials.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Record Deal
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead><tr>
                <th class="ps-3">Project</th>
                <th>Company</th>
                <th>Cost</th>
                <th>Expenses</th>
                <th>Profit</th>
                <th>Paid</th>
                <th>Payment</th>
                <th>Period</th>
                <th>Actions</th>
            </tr></thead>
            <tbody>
            @forelse($financials as $f)
            <tr>
                <td class="ps-3 fw-500 small">{{ $f->project_name }}</td>
                <td class="small text-muted">{{ $f->company->name ?? '—' }}</td>
                <td class="small fw-500">₦{{ number_format($f->project_cost) }}</td>
                <td class="small text-danger">₦{{ number_format($f->expenses) }}</td>
                <td class="small fw-600 {{ $f->profit >= 0 ? 'text-success' : 'text-danger' }}">
                    ₦{{ number_format($f->profit) }}
                </td>
                <td class="small">₦{{ number_format($f->amount_paid) }}</td>
                <td>
                    <span class="badge rounded-pill badge-{{ $f->payment_status === 'paid' ? 'success' : ($f->payment_status === 'partial' ? 'warning' : 'danger') }}">
                        {{ ucfirst($f->payment_status) }}
                    </span>
                </td>
                <td class="small text-muted">
                    {{ $f->start_date?->format('M Y') }} — {{ $f->end_date?->format('M Y') }}
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.financials.show', $f) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.financials.edit', $f) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.financials.destroy', $f) }}" onsubmit="return confirm('Delete this record?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No financial records yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($financials->hasPages())
    <div class="card-footer">{{ $financials->links() }}</div>
    @endif
</div>
@endsection
