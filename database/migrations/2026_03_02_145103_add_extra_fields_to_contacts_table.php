<?php
// database/migrations/xxxx_xx_xx_add_extra_fields_to_contacts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('company')->nullable()->after('phone');
            $table->string('service')->nullable()->after('company');
            $table->string('budget')->nullable()->after('service');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['company', 'service', 'budget']);
        });
    }
};