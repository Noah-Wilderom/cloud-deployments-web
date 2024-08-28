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
        Schema::create('deployments', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignIdFor(\Modules\Cloud\Models\Project::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->nullable();
            $table->string("type");
            $table->string("status");
            $table->string("environment");
            $table->string("commit_hash");
            $table->text("logs")->nullable();
            $table->timestamp("finished_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
