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
        Schema::create('identity_cards', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_nik');
            $table->string('family_registration_number');
            $table->string('name');
            $table->string('date_of_birth');
            $table->string('place_of_birth');
            $table->string('gender');
            $table->text('address');
            $table->string('religion');
            $table->string('marital_status');
            $table->string('work');
            $table->string('citizenship');
            $table->string('expired_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_cards');
    }
};
