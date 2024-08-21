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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Services\Models\Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Services\Models\Domain::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Cloud\Models\Server::class)->constrained()->cascadeOnDelete();
            $table->boolean("initialized");
            $table->string("sub_domain")->nullable();
            $table->string("template");
            $table->string("name");
            $table->text("ssh_credentials_path");
            $table->text("host_ssh_credentials_path");
            $table->text("git_repository");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
