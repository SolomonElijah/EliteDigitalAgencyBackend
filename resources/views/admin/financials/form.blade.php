@extends('admin.layout')
@section('title', isset($financial) && $financial->exists ? 'Edit Financial Record' : 'Record Deal')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<form method="POST" action="{{ isset($financial) && $financial->exists ? route('admin.financials.update', $financial) : route('admin.financials.store') }}">
    @csrf
    @if(isset($financial) && $financial->exists) @method('PUT') @endif

    <div class="card mb-3">
        <div class="card-header">Project & Company</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-500">Company / Client *</label>
                    <select name="company_id" class="form-select" required>
                        <option value="">Select company...</option>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}" {{ old('company_id', $financial->company_id ?? '') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="mt-1">
                        <a href="{{ route('admin.companies.create') }}" target="_blank" class="small text-primary">+ Add new company</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Link to Portfolio Project</label>
                    <select name="project_id" class="form-select">
                        <option value="">None (internal only)</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ old('project_id', $financial->project_id ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-500">Project / Deal Name *</label>
                    <input type="text" name="project_name" class="form-control"
                        value="{{ old('project_name', $financial->project_name ?? '') }}" required
                        placeholder="e.g. E-commerce Website for Acme Corp">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Financials</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-500">Project Cost (₦) *</label>
                    <input type="number" name="project_cost" class="form-control" step="0.01" min="0"
                        value="{{ old('project_cost', $financial->project_cost ?? 0) }}" required>
                    <div class="form-text">What the client pays you</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500">Total Expenses (₦) *</label>
                    <input type="number" name="expenses" class="form-control" step="0.01" min="0" id="totalExpenses"
                        value="{{ old('expenses', $financial->expenses ?? 0) }}" required>
                    <div class="form-text">Your costs to deliver</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500">Estimated Profit</label>
                    <input type="text" class="form-control bg-light" id="profitPreview" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="unpaid" {{ old('payment_status', $financial->payment_status ?? '') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ old('payment_status', $financial->payment_status ?? '') === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ old('payment_status', $financial->payment_status ?? '') === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500">Amount Paid (₦)</label>
                    <input type="number" name="amount_paid" class="form-control" step="0.01" min="0"
                        value="{{ old('amount_paid', $financial->amount_paid ?? 0) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <span>Expense Line Items</span>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addExpense">
                <i class="bi bi-plus"></i> Add Item
            </button>
        </div>
        <div class="card-body">
            <div id="expenseItems">
                @php $items = $financial->expenseItems ?? collect(); @endphp
                @foreach($items as $i => $item)
                <div class="row g-2 mb-2 expense-row">
                    <div class="col-md-4">
                        <input type="text" name="expense_descriptions[]" class="form-control form-control-sm"
                            placeholder="Description" value="{{ $item->description }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="expense_amounts[]" class="form-control form-control-sm expense-amount"
                            placeholder="₦ Amount" step="0.01" value="{{ $item->amount }}">
                    </div>
                    <div class="col-md-3">
                        <select name="expense_categories[]" class="form-select form-select-sm">
                            <option value="">Category</option>
                            @foreach(['hosting','tools','freelancer','software','design','marketing','other'] as $cat)
                                <option {{ $item->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="expense_dates[]" class="form-control form-control-sm"
                            value="{{ $item->date?->toDateString() }}">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-expense"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-muted small mt-2">
                <i class="bi bi-info-circle me-1"></i>
                Adding items will auto-calculate total expenses.
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Timeline & Notes</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-500">Start Date</label>
                    <input type="date" name="start_date" class="form-control"
                        value="{{ old('start_date', isset($financial->start_date) ? $financial->start_date->toDateString() : '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">End Date</label>
                    <input type="date" name="end_date" class="form-control"
                        value="{{ old('end_date', isset($financial->end_date) ? $financial->end_date->toDateString() : '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-500">Notes</label>
                    <textarea name="notes" rows="3" class="form-control"
                        placeholder="Any additional notes...">{{ old('notes', $financial->notes ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>
            {{ isset($financial) && $financial->exists ? 'Update Record' : 'Save Record' }}
        </button>
        <a href="{{ route('admin.financials.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
const expenseRowTemplate = `
<div class="row g-2 mb-2 expense-row">
    <div class="col-md-4">
        <input type="text" name="expense_descriptions[]" class="form-control form-control-sm" placeholder="Description">
    </div>
    <div class="col-md-2">
        <input type="number" name="expense_amounts[]" class="form-control form-control-sm expense-amount" placeholder="₦ Amount" step="0.01">
    </div>
    <div class="col-md-3">
        <select name="expense_categories[]" class="form-select form-select-sm">
            <option value="">Category</option>
            <option>hosting</option><option>tools</option><option>freelancer</option>
            <option>software</option><option>design</option><option>marketing</option><option>other</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="expense_dates[]" class="form-control form-control-sm">
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-outline-danger remove-expense"><i class="bi bi-x"></i></button>
    </div>
</div>`;

document.getElementById('addExpense').addEventListener('click', () => {
    document.getElementById('expenseItems').insertAdjacentHTML('beforeend', expenseRowTemplate);
    bindExpenseListeners();
    calcExpenses();
});

function bindExpenseListeners() {
    document.querySelectorAll('.remove-expense').forEach(btn => {
        btn.onclick = function() { this.closest('.expense-row').remove(); calcExpenses(); };
    });
    document.querySelectorAll('.expense-amount').forEach(inp => {
        inp.oninput = calcExpenses;
    });
}

function calcExpenses() {
    let total = 0;
    document.querySelectorAll('.expense-amount').forEach(inp => {
        total += parseFloat(inp.value || 0);
    });
    if (total > 0) document.getElementById('totalExpenses').value = total.toFixed(2);
    updateProfit();
}

function updateProfit() {
    const cost = parseFloat(document.querySelector('[name=project_cost]').value || 0);
    const exp  = parseFloat(document.getElementById('totalExpenses').value || 0);
    const profit = cost - exp;
    const el = document.getElementById('profitPreview');
    el.value = '₦' + profit.toLocaleString('en', {minimumFractionDigits: 2});
    el.style.color = profit >= 0 ? '#065f46' : '#dc2626';
}

document.querySelector('[name=project_cost]').addEventListener('input', updateProfit);
document.getElementById('totalExpenses').addEventListener('input', updateProfit);
bindExpenseListeners();
updateProfit();
</script>
@endpush
