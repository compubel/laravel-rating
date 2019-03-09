<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('rating.table_name', 'ratings'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('rater_type')->nullable();
            $table->integer('rater_id')->nullable();
            $table->string('rateable_type');
            $table->string('rateable_id');
            $table->float('rating', 9, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(config('rating.table_name', 'ratings'));
    }
}
