@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Total Projects</div>
                    <div class="fs-3 fw-700">{{ $stats['total_projects'] }}</div>
                    <div class="text-success small mt-1">{{ $stats['published'] }} published</div>
                </div>
                <div class="icon" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-collection-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Total Revenue</div>
                    <div class="fs-3 fw-700">₦{{ number_format($stats['total_revenue']) }}</div>
                    <div class="text-danger small mt-1">₦{{ number_format($stats['unpaid_amount']) }} outstanding</div>
                </div>
                <div class="icon" style="background:#d1fae5;color:#065f46"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Net Profit</div>
                    <div class="fs-3 fw-700 {{ $stats['total_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                        ₦{{ number_format($stats['total_profit']) }}
                    </div>
                    <div class="text-muted small mt-1">Expenses: ₦{{ number_format($stats['total_expenses']) }}</div>
                </div>
                <div class="icon" style="background:#fef3c7;color:#d97706"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">New Contacts</div>
                    <div class="fs-3 fw-700">{{ $stats['new_contacts'] }}</div>
                    <div class="text-muted small mt-1">{{ $stats['active_companies'] }} active clients</div>
                </div>
                <div class="icon" style="background:#fee2e2;color:#dc2626"><i class="bi bi-envelope-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Revenue Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Revenue (Last 6 Months)</span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Contacts -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Contacts</span>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($recent_contacts as $c)
                <div class="d-flex align-items-center p-3 border-bottom">
                    <div style="width:36px;height:36px;background:#ede9fe;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-person" style="color:#7c3aed"></i>
                    </div>
                    <div class="ms-2 flex-grow-1 overflow-hidden">
                        <div class="fw-500 small text-truncate">{{ $c->name }}</div>
                        <div class="text-muted" style="font-size:0.75rem">{{ $c->created_at->diffForHumans() }}</div>
                    </div>
                    @if($c->status === 'new')
                        <span class="badge rounded-pill" style="background:#fee2e2;color:#dc2626;font-size:0.65rem">New</span>
                    @endif
                </div>
                @empty
                <div class="p-3 text-muted text-center small">No contacts yet</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Financial Records</span>
                <a href="{{ route('admin.financials.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th class="ps-3">Project</th>
                        <th>Company</th>
                        <th>Cost</th>
                        <th>Profit</th>
                        <th>Status</th>
                    </tr></thead>
                    <tbody>
                    @forelse($recent_financials as $f)
                    <tr>
                        <td class="ps-3 fw-500 small">{{ $f->project_name }}</td>
                        <td class="small text-muted">{{ $f->company->name ?? '—' }}</td>
                        <td class="small">₦{{ number_format($f->project_cost) }}</td>
                        <td class="small {{ $f->profit >= 0 ? 'text-success' : 'text-danger' }}">₦{{ number_format($f->profit) }}</td>
                        <td>
                            <span class="badge rounded-pill badge-{{ $f->payment_status === 'paid' ? 'success' : ($f->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                {{ ucfirst($f->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-muted text-center small py-3">No records yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Quick Actions</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add Project
                </a>
                <a href="{{ route('admin.companies.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-building me-1"></i> Add Company
                </a>
                <a href="{{ route('admin.financials.create') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-cash me-1"></i> Record Project Deal
                </a>
                <a href="{{ route('admin.campaigns.create') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-send me-1"></i> Create Email Campaign
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const monthlyData = @json($monthly_revenue);
const labels = monthlyData.map(d => {
    const months = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return months[d.month] + ' ' + d.year;
});
const data = monthlyData.map(d => parseFloat(d.revenue));

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels.length ? labels : ['No data'],
        datasets: [{
            label: 'Revenue (₦)',
            data: data.length ? data : [0],
            backgroundColor: 'rgba(102, 126, 234, 0.7)',
            borderColor: '#667eea',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '₦' + v.toLocaleString() } }
        }
    }
});
</script>
@endpush
