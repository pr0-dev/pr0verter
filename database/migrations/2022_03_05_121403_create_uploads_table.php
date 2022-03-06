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
        Schema::create('uploads', function (Blueprint $table) {
            $table->string('guid');
            $table->string('input_folder');
            $table->string('result_folder');
            $table->string('mime_type');
            $table->string('extension');
            $table->string('filename');
            $table->integer('convert_progress')->default(0);
            $table->string('convert_remaining')->default(null)->nullable();
            $table->string('convert_rate')->default(null)->nullable();
            $table->string('convert_error')->default(null)->nullable();
            $table->integer('probe_score')->default(0);
            $table->string('probe_error')->default(null)->nullable();
            $table->integer('original_duration')->default(0);
            $table->string('original_format')->default(null)->nullable();
            $table->string('original_codec')->default(null)->nullable();
            $table->integer('result_bitrate')->default(0);
            $table->integer('result_height')->default(0);
            $table->integer('result_width')->default(0);
            $table->integer('result_start')->default(0);
            $table->integer('result_end')->default(0);
            $table->integer('result_audio')->default(0);
            $table->foreign('guid')->references('guid')->on('video_lists')->onDelete('cascade');
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
        Schema::dropIfExists('uploads');
    }
};
