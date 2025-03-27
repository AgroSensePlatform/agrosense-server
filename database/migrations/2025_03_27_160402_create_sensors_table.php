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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Associated user
            $table->foreignId('farm_id')->nullable()->constrained()->onDelete('set null'); // Associated farm
            $table->string('code')->unique(); // Unique sensor code
            $table->decimal('lat', 10, 8)->nullable(); // Latitude
            $table->decimal('lon', 11, 8)->nullable(); // Longitude
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
