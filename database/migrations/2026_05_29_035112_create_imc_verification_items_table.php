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
        Schema::create('imc_verif_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imc_verif_id')->constrained('imc_verifs')->onDelete('cascade');
            $table->foreignId('shipment_item_id')->constrained('shipment_items')->onDelete('cascade');
            $table->decimal('quantity_actual', 15, 2);
            $table->string('condition', 50);
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imc_verif_items');
    }
};
