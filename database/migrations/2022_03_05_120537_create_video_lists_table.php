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
        Schema::create('video_lists', function (Blueprint $table) {
            $table->string('guid')->primary();
            $table->enum('type', ['Upload', 'Youtube', 'Download']);
            $table->boolean('failed')->default(0);
            $table->ipAddress('uploaderIP');
            $table->boolean('downloaded')->default(0);
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
        Schema::dropIfExists('video_lists');
    }
};
