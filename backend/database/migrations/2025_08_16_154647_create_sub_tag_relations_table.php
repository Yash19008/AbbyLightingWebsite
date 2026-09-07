<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_tag_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sub_tag_id');
            $table->unsignedInteger('linked_sub_tag_id');
            $table->enum('is_active', ['yes','no'])->default('yes');
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('sub_tag_id')
                ->references('id')->on('sub_tags')
                ->onDelete('cascade');

            $table->foreign('linked_sub_tag_id')
                ->references('id')->on('sub_tags')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_tag_relations');
    }
};
