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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamp('timestamp')->useCurrent();
            $table->string('ip_address');
            $table->string('action', 255);
            $table->string('module', 255);
            $table->string('message',255)->nullable();
            $table->text('old_data')->nullable();
            $table->text('new_data')->nullable();
            $table->text('other_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};
