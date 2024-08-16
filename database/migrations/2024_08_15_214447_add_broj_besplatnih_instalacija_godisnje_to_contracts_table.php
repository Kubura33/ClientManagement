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
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('broj_besplatnih_instalacija_godisnje')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

            Schema::dropColumns('contracts',['broj_besplatnih_instalacija_godisnje']);

    }
};
