<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'name')) {
                $table->string('name')->after('uuid');
            }
            if (!Schema::hasColumn('items', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('items', 'code')) {
                $table->string('code')->unique()->after('description');
            }
            if (!Schema::hasColumn('items', 'status')) {
                $table->enum('status', ['active','inactive'])->default('active')->after('code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['name','description','code','status']);
        });
    }
};
