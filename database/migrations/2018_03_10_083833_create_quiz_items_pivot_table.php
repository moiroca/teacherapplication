<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizItemsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_items_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('quiz_id');
            $table->unsignedinteger('item_id');

            $table->foreign('quiz_id')
                    ->references('id')
                    ->on('quizzes');

            $table->foreign('item_id')
                    ->references('id')
                    ->on('quiz_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_items_pivot', function (Blueprint $table) {
            $table->dropForeign('quiz_items_pivot_quiz_id_foreign');
            $table->dropForeign('quiz_items_pivot_item_id_foreign');
        });
        Schema::drop('quiz_items_pivot');
    }
}
