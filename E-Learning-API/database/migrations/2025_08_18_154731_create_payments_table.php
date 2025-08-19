<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->foreignId('course_id')->nullable()->constrained()->cascadeOnDelete();
      $table->foreignId('subscription_id')->nullable()->constrained()->cascadeOnDelete();
      $table->unsignedInteger('amount_cents');
      $table->string('currency', 3)->default('INR');
      $table->string('gateway');
      $table->string('status'); // created, succeeded, failed, refunded
      $table->string('external_id')->nullable();
      $table->json('meta')->nullable();
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('payments'); }
};
