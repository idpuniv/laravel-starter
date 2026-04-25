<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('iso2', 2);
            $table->string('iso3', 3)->nullable();
            $table->string('phone_code')->nullable();
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->string('timezones')->nullable();
            $table->timestamps();
        });

        //DB::statement('ALTER TABLE countries ENGINE = InnoDB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
