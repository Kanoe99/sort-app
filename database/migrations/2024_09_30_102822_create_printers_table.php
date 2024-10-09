<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();

            // New
            $table->string('type');

            $table->string('model');

            // Number became unique
            $table->unsignedMediumInteger('number')->unique();

            $table->string('location');

            // Update
            // Should have a checkbox
            $table->string('IP')->unique()->nullable();

            $table->string('status');
            $table->string('comment')->nullable();
            $table->boolean('attention')->default(false);
            $table->string('logo')->nullable();

            // New
            $table->string('counter'); // Assuming this is still a string
            $table->dateTime('counterDate'); // Change to datetime
            $table->dateTime('fixDate')->nullable(); // Change to datetime

            $table->timestamps();
            $table->index('location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};


