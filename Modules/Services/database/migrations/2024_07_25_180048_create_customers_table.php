<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignIdFor(\Modules\Teams\Models\Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, "creator_id")->nullable()->constrained()->nullOnDelete();
            $table->string("company_name");
            $table->string("name");
            $table->string("email");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
