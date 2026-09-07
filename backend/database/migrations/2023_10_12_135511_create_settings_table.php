<?php

use App\Models\Setting;
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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value');
            $table->string('type')->comment('text, textarea, image')->default('text');
            $table->timestamps();
        });

        Setting::create([
            "key" => "SUBTAG_BANNER_IMAGE",
            "value" => "",
            "type" => "image"
        ]);
        Setting::create([
            "key" => "CAREER_BANNER_IMAGE",
            "value" => "",
            "type" => "image"
        ]);
        Setting::create([
            "key" => "CONTACT_SIDE_IMAGE",
            "value" => "",
            "type" => "image"
        ]);
        Setting::create([
            "key" => "CLIENT_BANNER_IMAGE",
            "value" => "",
            "type" => "image"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
