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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // e.g., Futsal, Badminton, Basketball
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->string('image_url')->nullable();
            $table->string('open_time')->nullable();
            $table->string('close_time')->nullable();
            $table->text('facilities')->nullable(); // JSON encoded
            $table->boolean('promo')->default(false);
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
        Schema::dropIfExists('fields');
    }
};
