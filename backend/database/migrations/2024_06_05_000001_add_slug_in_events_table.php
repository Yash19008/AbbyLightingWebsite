<?php

use App\Models\Event;
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
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable()->unique();
        });

        Event::all()->each(function ($event) {
            $slug = Str::slug($event->name);
            $slug = Event::where('slug', $slug)->exists() ? $slug . '-' . $event->id : $slug;
            $event->slug = $slug;
            $event->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
