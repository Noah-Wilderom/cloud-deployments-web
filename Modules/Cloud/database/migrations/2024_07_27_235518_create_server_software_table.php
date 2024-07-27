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
        Schema::create('server_software', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignIdFor(\Modules\Cloud\Models\Server::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Cloud\Models\Task::class)->constrained()->cascadeOnDelete();
            $table->string("software");
            $table->string("active_version")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_software');
    }
};
