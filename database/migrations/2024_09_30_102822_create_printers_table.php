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
            $table->string('model');
            $table->unsignedMediumInteger('number');
            $table->string('location');
            $table->string('IP')->unique();
            $table->string('status');
            $table->string('comment')->nullable();
            $table->boolean('attention')->default(false);
            $table->string('logo')->nullable();
            $table->timestamps();
            $table->index('location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};

