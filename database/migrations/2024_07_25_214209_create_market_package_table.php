<?php

    use App\Models\Package;
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
        Schema::create('market_package', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Market::class, 'trziste_id');
            $table->foreignIdFor(Package::class, 'paket_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_package');
    }
};
