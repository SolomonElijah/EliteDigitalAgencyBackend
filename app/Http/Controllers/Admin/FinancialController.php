<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectFinancial;
use App\Models\ExpenseItem;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectFinancial::with(['company', 'project'])->latest();

        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $financials = $query->paginate(15);

        $summary = [
            'total_revenue' => ProjectFinancial::sum('project_cost'),
            'total_expenses' => ProjectFinancial::sum('expenses'),
            'total_profit'  => ProjectFinancial::selectRaw('SUM(project_cost - expenses) as p')->value('p') ?? 0,
            'unpaid'        => ProjectFinancial::where('payment_status', 'unpaid')->sum('project_cost'),
        ];

        $companies = Company::all();

        return view('admin.financials.index', compact('financials', 'summary', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $projects  = Project::orderBy('title')->get();
        return view('admin.financials.form', compact('companies', 'projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id'     => 'required|exists:companies,id',
            'project_id'     => 'nullable|exists:projects,id',
            'project_name'   => 'required|string|max:200',
            'project_cost'   => 'required|numeric|min:0',
            'expenses'       => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'amount_paid'    => 'nullable|numeric|min:0',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $financial = ProjectFinancial::create($data);

        // Handle expense line items
        if ($request->has('expense_descriptions')) {
            foreach ($request->expense_descriptions as $i => $desc) {
                if (!empty($desc)) {
                    ExpenseItem::create([
                        'project_financial_id' => $financial->id,
                        'description'          => $desc,
                        'amount'               => $request->expense_amounts[$i] ?? 0,
                        'category'             => $request->expense_categories[$i] ?? null,
                        'date'                 => $request->expense_dates[$i] ?? now()->toDateString(),
                    ]);
                }
            }

            // Recalculate total expenses from items
            $total = $financial->expenseItems()->sum('amount');
            $financial->update(['expenses' => $total]);
        }

        return redirect()->route('admin.financials.index')
            ->with('success', 'Project financial record created!');
    }

    public function show(ProjectFinancial $financial)
    {
        $financial->load(['company', 'project', 'expenseItems']);
        return view('admin.financials.show', compact('financial'));
    }

    public function edit(ProjectFinancial $financial)
    {
        $financial->load('expenseItems');
        $companies = Company::orderBy('name')->get();
        $projects  = Project::orderBy('title')->get();
        return view('admin.financials.form', compact('financial', 'companies', 'projects'));
    }

    public function update(Request $request, ProjectFinancial $financial)
    {
        $data = $request->validate([
            'company_id'     => 'required|exists:companies,id',
            'project_id'     => 'nullable|exists:projects,id',
            'project_name'   => 'required|string|max:200',
            'project_cost'   => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'amount_paid'    => 'nullable|numeric|min:0',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $financial->update($data);

        return redirect()->route('admin.financials.index')
            ->with('success', 'Financial record updated!');
    }

    public function destroy(ProjectFinancial $financial)
    {
        $financial->delete();
        return redirect()->route('admin.financials.index')->with('success', 'Record deleted.');
    }
}
