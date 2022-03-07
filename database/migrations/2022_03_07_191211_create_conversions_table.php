<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->morphs('typeInfo');
            $table->string('source_disk');
            $table->string('source_format')->default(null)->nullable();
            $table->string('source_codec')->default(null)->nullable();
            $table->integer('source_duration')->default(0);
            $table->string('guid')->unique();
            $table->string('filename');
            $table->boolean('keep_resolution');
            $table->string('requested_size');
            $table->boolean('failed')->default(false);
            $table->boolean('downloaded')->default(false);
            $table->ipAddress('ip');
            $table->string('converter_remaining')->default(null)->nullable();
            $table->string('converter_rate')->default(null)->nullable();
            $table->string('converter_error')->default(null)->nullable();
            $table->integer('converter_progress')->default(0);
            $table->integer('probe_score')->default(0);
            $table->string('probe_error')->default(null)->nullable();
            $table->integer('result_bitrate')->default(0);
            $table->integer('result_height')->default(0);
            $table->integer('result_width')->default(0);
            $table->integer('result_start')->default(0);
            $table->integer('result_duration')->default(0);
            $table->integer('result_audio')->default(0);
            $table->integer('result_size')->default(0);
            $table->string('result_profile')->default(null)->nullable();
            $table->string('result_disk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversions');
    }
};
