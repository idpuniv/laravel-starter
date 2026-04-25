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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->nullableMorphs('personable');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique()->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phone_code', 7)->nullable();
            $table->string('phone')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['phone', 'phone_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
