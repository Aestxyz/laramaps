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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('thumbnail');
            $table->string('url_maps')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
         * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};