<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('general_id');
            $table->string('title');
            $table->string('address1');
            $table->string('address2');
            $table->string('footer');
            $table->longText('tawkto')->nullable();
            $table->longText('disqus')->nullable();
            $table->longText('gverification')->nullable();
            $table->longText('sharethis')->nullable();
            $table->string('keyword');
            $table->string('meta_desc');
            $table->string('local');
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
        Schema::dropIfExists('general_translations');
    }
}
