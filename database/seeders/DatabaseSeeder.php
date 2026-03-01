<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Project;
use App\Models\Company;
use App\Models\ProjectFinancial;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@eliteagency.com')],
            [
                'name'     => 'Elite Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@123')),
                'role'     => 'admin',
            ]
        );

        // Sample projects
        $projects = [
            ['title' => 'E-Commerce Platform for RetailMax', 'category' => 'ecommerce', 'tech_stack' => ['Laravel', 'Vue.js', 'MySQL', 'Stripe'], 'demo_url' => 'https://retailmax.demo', 'client_name' => 'RetailMax Ltd', 'featured' => true, 'description' => 'A full-featured e-commerce solution with inventory management, payment processing, and analytics dashboard.'],
            ['title' => 'Healthcare SaaS Dashboard', 'category' => 'saas', 'tech_stack' => ['React', 'Node.js', 'PostgreSQL', 'AWS'], 'client_name' => 'MediCore Inc', 'featured' => true, 'description' => 'A real-time healthcare monitoring SaaS platform for hospital chains.'],
            ['title' => 'Mobile Banking App', 'category' => 'mobile', 'tech_stack' => ['Flutter', 'Firebase', 'Dart'], 'client_name' => 'FinEdge Bank', 'description' => 'Cross-platform mobile banking application with biometric authentication.'],
            ['title' => 'Real Estate Marketplace', 'category' => 'web', 'tech_stack' => ['Laravel', 'React', 'ElasticSearch', 'MySQL'], 'client_name' => 'PropertyHub NG', 'description' => 'Property listing and management platform for Nigerian real estate market.'],
        ];

        foreach ($projects as $p) {
            Project::firstOrCreate(
                ['title' => $p['title']],
                array_merge($p, ['status' => 'published'])
            );
        }

        // Sample companies
        $companies = [
            ['name' => 'RetailMax Ltd', 'email' => 'info@retailmax.com', 'status' => 'active', 'contact_person' => 'John Okafor'],
            ['name' => 'MediCore Inc', 'email' => 'contact@medicore.com', 'status' => 'active', 'contact_person' => 'Dr. Sarah Eze'],
            ['name' => 'FinEdge Bank', 'email' => 'it@finedge.com.ng', 'status' => 'active', 'contact_person' => 'Emeka Nwosu'],
            ['name' => 'PropertyHub NG', 'email' => 'dev@propertyhub.ng', 'status' => 'inactive'],
            ['name' => 'StartupXY', 'email' => 'hello@startupxy.com', 'status' => 'lead'],
        ];

        foreach ($companies as $c) {
            Company::firstOrCreate(['email' => $c['email']], $c);
        }

        // Sample financial records
        if (ProjectFinancial::count() === 0) {
            $company1 = Company::where('email', 'info@retailmax.com')->first();
            $company2 = Company::where('email', 'contact@medicore.com')->first();
            $project1 = Project::where('title', 'E-Commerce Platform for RetailMax')->first();

            if ($company1 && $project1) {
                ProjectFinancial::create([
                    'company_id'     => $company1->id,
                    'project_id'     => $project1->id,
                    'project_name'   => 'E-Commerce Platform for RetailMax',
                    'project_cost'   => 2500000,
                    'expenses'       => 850000,
                    'payment_status' => 'paid',
                    'amount_paid'    => 2500000,
                    'start_date'     => '2024-01-15',
                    'end_date'       => '2024-04-30',
                ]);
            }

            if ($company2) {
                ProjectFinancial::create([
                    'company_id'     => $company2->id,
                    'project_name'   => 'Healthcare SaaS Dashboard',
                    'project_cost'   => 4200000,
                    'expenses'       => 1100000,
                    'payment_status' => 'partial',
                    'amount_paid'    => 2000000,
                    'start_date'     => '2024-03-01',
                    'end_date'       => '2024-09-30',
                ]);
            }
        }
    }
}
