<?php

    use App\Models\Contract;
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
        Schema::create('contract_functionality', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Functionalities::class, 'funkcionalnost_id');
            $table->foreignIdFor(Contract::class, 'ugovor_id');
            $table->boolean("uradjeno")->default(false);
            $table->boolean("izabrano")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_functionality');
    }
};
