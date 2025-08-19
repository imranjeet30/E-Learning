<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('courses', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('slug')->unique()->nullable();
      $table->string('instructor_id')->unique()->nullable();
      $table->text('description')->nullable();
      $table->unsignedInteger('price')->default(0);
      $table->string('currency', 3)->default('INR');
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('courses'); }
};
