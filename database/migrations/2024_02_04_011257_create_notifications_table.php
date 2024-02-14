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
        Schema::disableForeignKeyConstraints();

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ["reaction","comment","follow","message","follow_request"]);
            $table->unsignedBigInteger('related_id');
            $table->boolean('read')->default(false);
            $table->timestamp('notification_date')->useCurrent();
            $table->timestamps();

            // Agregar índices para optimizar las consultas
            $table->index('user_id');
            $table->index('read');
            $table->index('notification_date');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
