<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyInQuizOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_options', function (Blueprint $table) {
            $table->foreign('quiz_item_id')
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
        Schema::table('quiz_options', function (Blueprint $table) {
            $table->dropForeign('quiz_options_quiz_item_id_foreign');
        });
    }
}
