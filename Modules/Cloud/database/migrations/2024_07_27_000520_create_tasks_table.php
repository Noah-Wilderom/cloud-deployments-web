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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignIdFor(\Modules\Cloud\Models\Server::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Cloud\Models\Project::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string("status");
            $table->string("name");
            $table->string("type");
            $table->text("meta")->nullable();
            $table->timestamp("started_at")->nullable();
            $table->timestamp("stopped_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
