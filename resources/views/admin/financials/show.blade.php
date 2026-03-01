@extends('admin.layout')
@section('title', $financial->project_name)

@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Project: {{ $financial->project_name }}</span>
                <span class="badge badge-{{ $financial->payment_status === 'paid' ? 'success' : ($financial->payment_status === 'partial' ? 'warning' : 'danger') }}">
                    {{ ucfirst($financial->payment_status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="text-muted small">Company</div>
                        <div class="fw-500">{{ $financial->company->name }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Timeline</div>
                        <div class="small">{{ $financial->start_date?->format('M Y') }} — {{ $financial->end_date?->format('M Y') ?? 'Ongoing' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Portfolio Project</div>
                        <div class="small">{{ $financial->project->title ?? 'Not linked' }}</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <div style="background:#f0fdf4;border-radius:10px;padding:1rem;text-align:center">
                            <div class="text-muted small">Project Cost</div>
                            <div class="fs-4 fw-700 text-success">₦{{ number_format($financial->project_cost) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#fff1f2;border-radius:10px;padding:1rem;text-align:center">
                            <div class="text-muted small">Expenses</div>
                            <div class="fs-4 fw-700 text-danger">₦{{ number_format($financial->expenses) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#eff6ff;border-radius:10px;padding:1rem;text-align:center">
                            <div class="text-muted small">Net Profit</div>
                            <div class="fs-4 fw-700 {{ $financial->profit >= 0 ? 'text-primary' : 'text-danger' }}">
                                ₦{{ number_format($financial->profit) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#fffbeb;border-radius:10px;padding:1rem;text-align:center">
                            <div class="text-muted small">Balance Due</div>
                            <div class="fs-4 fw-700 text-warning">₦{{ number_format($financial->balance_due) }}</div>
                        </div>
                    </div>
                </div>

                @if($financial->notes)
                <div class="mt-3">
                    <div class="text-muted small mb-1">Notes</div>
                    <p class="mb-0 small">{{ $financial->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Expense Items -->
        @if($financial->expenseItems->count())
        <div class="card">
            <div class="card-header">Expense Breakdown</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr>
                        <th class="ps-3">Description</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th class="text-end pe-3">Amount</th>
                    </tr></thead>
                    <tbody>
                    @foreach($financial->expenseItems as $item)
                    <tr>
                        <td class="ps-3 small">{{ $item->description }}</td>
                        <td class="small text-muted">{{ $item->category ?? '—' }}</td>
                        <td class="small text-muted">{{ $item->date->format('M d, Y') }}</td>
                        <td class="text-end pe-3 small fw-500 text-danger">₦{{ number_format($item->amount) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="ps-3 fw-600 small">Total Expenses</td>
                            <td class="text-end pe-3 fw-700 text-danger">₦{{ number_format($financial->expenseItems->sum('amount')) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Actions</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.financials.edit', $financial) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit Record
                </a>
                <a href="{{ route('admin.companies.edit', $financial->company) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-building me-1"></i> View Company
                </a>
                <a href="{{ route('admin.financials.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
