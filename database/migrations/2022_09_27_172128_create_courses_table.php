<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('price')->default(0);
            $table->integer('sell')->default(0);
            $table->text('detail');
            $table->string('thumbnail')->nullable();
            $table->string('mentor');
            $table->boolean('status')->default(0);
            $table->uuid('fk_category');
            $table->timestamps();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('fk_category', 'fk_category_courses')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
