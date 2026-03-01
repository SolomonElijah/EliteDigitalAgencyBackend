<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('financials')
            ->withSum('financials', 'project_cost')
            ->latest()->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.companies.form', ['company' => new Company()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:200',
            'email'          => 'required|email|unique:companies,email',
            'phone'          => 'nullable|string|max:20',
            'website'        => 'nullable|url',
            'contact_person' => 'nullable|string|max:150',
            'address'        => 'nullable|string',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:lead,active,inactive',
        ]);

        Company::create($data);
        return redirect()->route('admin.companies.index')->with('success', 'Company added!');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.form', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:200',
            'email'          => 'required|email|unique:companies,email,' . $company->id,
            'phone'          => 'nullable|string|max:20',
            'website'        => 'nullable|url',
            'contact_person' => 'nullable|string|max:150',
            'address'        => 'nullable|string',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:lead,active,inactive',
        ]);

        $company->update($data);
        return redirect()->route('admin.companies.index')->with('success', 'Company updated!');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted.');
    }
}
