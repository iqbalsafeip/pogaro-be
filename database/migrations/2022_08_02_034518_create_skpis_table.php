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
        Schema::create('skpis', function (Blueprint $table) {
            $table->id();
            $table->string('file_skpi')->nullable();
            $table->string('file_pendukung')->nullable();
            $table->string('title_skpi')->nullable();
            $table->string('title_pendukung')->nullable();
            $table->string('status');
            $table->foreignId('user_id');
            $table->foreignId('approved_by')->nullable();
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
        Schema::dropIfExists('skpis');
    }
};
