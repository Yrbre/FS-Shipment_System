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
        Schema::table('shipment_history_items', function (Blueprint $table) {
            $table->string('rf')->nullable()->after('item_id');
            $table->string('hscode')->nullable()->after('rf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_history_items', function (Blueprint $table) {
            $table->dropColumn(['rf', 'hscode']);
        });
    }
};
