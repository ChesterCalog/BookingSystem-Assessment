<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['standard', 'deluxe', 'vip', 'party'])->default('standard');
            $table->enum('size', ['small', 'medium', 'large', 'xlarge'])->default('medium');
            $table->integer('capacity');           // max number of guests
            $table->decimal('price_per_hour', 8, 2);
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();  // ["Mic", "TV", "Sofa", ...]
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
