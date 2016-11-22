<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->text('text');
            $table->text('formatted_text');
            $table->integer('order');
            $table->integer('project_id')->nullable()->unsigned()->index();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('project_id')
              ->references('id')
              ->on('projects')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('text_blocks');
    }
}
