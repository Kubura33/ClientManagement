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
        Schema::create('functionalities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Functionalities::class, 'trziste_id');
            $table->string('funkcionalnost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('functionalities');
    }
};
