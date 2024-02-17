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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->date('birth_date')->nullable();
            $table->timestamp('register_date')->useCurrent();
            $table->text('bio')->nullable();
            $table->boolean('is_private')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('available_pines')->default(3);
            $table->text('profile_picture')->nullable();
            $table->integer('accumulated_points')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->string('github_id')->nullable();
            $table->string('github_token')->nullable();
            $table->string('github_refresh_token')->nullable();
            $table->string('google2fa_secret')->nullable();
            $table->boolean('is_2fa_enabled')->default(false);
            $table->string('google_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
