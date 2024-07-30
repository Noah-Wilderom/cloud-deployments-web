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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Services\Models\Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Services\Models\ServicePlan::class)->constrained()->cascadeOnDelete();
            $table->nullableUuidMorphs("service");
            $table->string("name")->nullable();
            $table->integer("price");
            $table->timestamp("canceled_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
