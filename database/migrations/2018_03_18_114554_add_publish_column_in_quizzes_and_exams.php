<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishColumnInQuizzesAndExams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->smallInteger('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
