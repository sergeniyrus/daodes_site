<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('mail_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('mail_templates')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('recipients')->onDelete('cascade');
            $table->enum('status', ['pending', 'sent', 'delivered', 'read'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('mail_logs'); }
};
