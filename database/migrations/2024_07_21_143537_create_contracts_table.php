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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Invoicing::class, 'fakturisanje_id');
            $table->foreignIdFor(\App\Models\Company::class, 'firma_id')->nullable();
            $table->foreignIdFor(\App\Models\ImplementationStatus::class, 'status_id');
            $table->foreignIdFor(\App\Models\Package::class, 'paket_id');
            $table->integer('iznos_fakture');
            $table->string('status_implementiranja')->nullable();
            $table->string('broj_ugovora');
            $table->string('broj_aneksa')->nullable();
            $table->year('godina_ugovora');
            $table->date('datum_implementacije')->nullable();
            $table->integer('broj_preostalih_instalacija')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
