<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('title'); // Post title
            $table->text('content'); // Post content
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void {
        Schema::dropIfExists('posts');
    }
};