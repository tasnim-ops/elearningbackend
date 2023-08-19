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
        Schema::create('essais', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('essai_name');
            $table->string('essai_desc');
            $table->string('essai_result');
            $table->string('photo');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('essais');
    }
};
