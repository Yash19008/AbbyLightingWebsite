<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('catalog_downloads');
        Schema::dropIfExists('subscriptions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('catalog_downloads')) {
            Schema::create('catalog_downloads', function (Blueprint $table) {
                $table->id();
                $table->string('catalogue_name')->nullable();
                $table->unsignedBigInteger('catalogue_id')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('mobile')->nullable();
                $table->string('city')->nullable();
                $table->string('company')->nullable();
                $table->string('role')->nullable();
                $table->text('message')->nullable();
                $table->string('is_active', 10)->default('yes');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->timestamp('verified_at')->nullable();
                $table->string('verification_code')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }
};
