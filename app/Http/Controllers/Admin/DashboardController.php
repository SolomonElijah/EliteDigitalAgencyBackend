<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Company;
use App\Models\ProjectFinancial;
use App\Models\Contact;
use App\Models\EmailCampaign;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_projects'   => Project::count(),
            'published'        => Project::published()->count(),
            'total_companies'  => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'new_contacts'     => Contact::where('status', 'new')->count(),
            'total_revenue'    => ProjectFinancial::sum('project_cost'),
            'total_expenses'   => ProjectFinancial::sum('expenses'),
            'total_profit'     => ProjectFinancial::selectRaw('SUM(project_cost - expenses) as p')->value('p') ?? 0,
            'unpaid_amount'    => ProjectFinancial::where('payment_status', '!=', 'paid')
                                    ->selectRaw('SUM(project_cost - amount_paid) as d')->value('d') ?? 0,
        ];

        $recent_contacts   = Contact::latest()->take(5)->get();
        $recent_financials = ProjectFinancial::with('company')->latest()->take(5)->get();
        $campaigns         = EmailCampaign::latest()->take(3)->get();

        // Monthly revenue for chart (last 6 months)
        $monthly_revenue = ProjectFinancial::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(project_cost) as revenue')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderBy('year')->orderBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_contacts', 'recent_financials', 'campaigns', 'monthly_revenue'));
    }
}
