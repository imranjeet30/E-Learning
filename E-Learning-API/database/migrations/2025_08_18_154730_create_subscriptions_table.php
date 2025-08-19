<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('subscriptions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->foreignId('course_id')->constrained()->cascadeOnDelete();
      $table->string('status')->default('pending'); // active, cancelled, expired
      $table->dateTime('starts_at')->nullable();
      $table->dateTime('ends_at')->nullable();
      $table->string('gateway')->nullable();
      $table->string('external_reference')->nullable(); // payment intent/order id
      $table->timestamps();
      $table->unique(['user_id', 'course_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('subscriptions'); }
};
