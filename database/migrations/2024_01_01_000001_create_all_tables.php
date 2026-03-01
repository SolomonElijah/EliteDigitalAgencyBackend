<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users / Admins
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'manager'])->default('admin');
            $table->rememberToken();
            $table->timestamps();
        });

        // Projects (Portfolio)
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable();          // multiple images
            $table->json('tech_stack')->nullable();       // ["Laravel","Vue","MySQL"]
            $table->string('demo_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('client_name')->nullable();
            $table->enum('category', ['web', 'mobile', 'design', 'ecommerce', 'saas', 'other'])->default('web');
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Companies / Clients
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['lead', 'active', 'inactive'])->default('lead');
            $table->timestamps();
        });

        // Project-Company (financial tracking)
        Schema::create('project_financials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('project_name');
            $table->decimal('project_cost', 12, 2)->default(0);   // what client pays
            $table->decimal('expenses', 12, 2)->default(0);        // our costs
            $table->decimal('profit', 12, 2)->storedAs('project_cost - expenses');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Expense line items per financial record
        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_financial_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('category')->nullable(); // hosting, tools, freelancer, etc.
            $table->date('date');
            $table->timestamps();
        });

        // Contact form submissions
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // Email campaigns / bulk mailer
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->longText('body');            // HTML email body
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->enum('status', ['draft', 'sending', 'sent'])->default('draft');
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // Campaign recipients (from manual add or Excel import)
        Schema::create('campaign_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_campaign_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('company')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->string('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // API tokens for frontend
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('campaign_recipients');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('expense_items');
        Schema::dropIfExists('project_financials');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
    }
};
