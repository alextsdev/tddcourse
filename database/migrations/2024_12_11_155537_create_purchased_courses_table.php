<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('purchased_courses', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(User::class);
        $table->foreignIdFor(model: Course::class);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('purchased_courses');
  }
};
