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
         Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('acquaintance_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('alias_name', 100)->nullable();
            $table->boolean('is_invited')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'acquaintance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
