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
            Schema::create('room_inventories', function (Blueprint $table) {
        $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
        $table->date('inventory_date');
        $table->integer('available_count');
        $table->primary(['room_type_id', 'inventory_date']); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_inventories');
    }
};
