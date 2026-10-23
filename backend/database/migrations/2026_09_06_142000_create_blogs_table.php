<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('dek')->nullable();
            $table->string('author')->default('Abby Studio');
            $table->date('published_at')->nullable();
            $table->string('read_time')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_caption')->nullable();
            $table->string('secondary_image')->nullable();
            $table->string('secondary_image_caption')->nullable();
            $table->longText('content')->nullable();
            $table->json('table_of_contents')->nullable();
            $table->text('pull_quote')->nullable();
            $table->string('quote_author')->nullable();
            $table->string('status')->default('published');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};