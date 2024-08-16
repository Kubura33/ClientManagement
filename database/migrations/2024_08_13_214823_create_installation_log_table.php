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
        Schema::create('installation_log', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Contract::class, 'ugovor_id');
            $table->date("datum_instalacije")->nullable();
            $table->string("instalaciju_izvrsio")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installation_log');
    }
};
