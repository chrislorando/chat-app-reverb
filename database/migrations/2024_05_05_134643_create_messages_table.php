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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->text('content')->nullable();
            $table->timestamp('timestamp');
            $table->enum('delivery_status', ['Pending', 'Delivered'])->default('Pending');
            $table->enum('read_status', ['Unread', 'Read'])->default('Unread');
            $table->enum('message_type', ['Text', 'Image', 'Document'])->default('Text');
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_url')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // Reference to the parent message being replied to
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('messages'); // Foreign key constraint for reply_to column

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
