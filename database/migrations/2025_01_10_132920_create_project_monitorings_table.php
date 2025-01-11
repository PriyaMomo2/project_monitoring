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
        Schema::create('project_monitorings', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('project_name');
            $table->string('client');
            $table->string('project_leader_image');
            $table->string('project_leader_name');
            $table->string('project_leader_email');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_monitorings');
    }
};
