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
        Schema::create('email_messages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('sent_by');
            $table->string('sent_to');
            $table->string('subject');
            $table->string('recipient');
            $table->text('message');
            $table->string('summary');
            $table->tinyInteger('starred')->default(0);
            $table->tinyInteger('important')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
