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
            $table->foreignIdFor(\Modules\Teams\Models\Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, "creator_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\Modules\Services\Models\Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Services\Models\Domain::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Cloud\Models\Server::class)->constrained()->cascadeOnDelete();
            $table->string("public_id");
            $table->boolean("initialized");
            $table->string("sub_domain")->nullable();
            $table->string("template");
            $table->string("name");
            $table->string("ssh_user");
            $table->text("ssh_credentials_path");
            $table->text("host_ssh_credentials_path");
            $table->text("git_repository");
            $table->text("settings");
            $table->text("environments");
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
