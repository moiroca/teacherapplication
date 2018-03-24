<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStudentQuizzesTableQuizIdColumnToAttemptId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_quizzes', function (Blueprint $table) {
            $table->dropColumn('quiz_id');
            $table->integer('attempt_id')->unsigned();
            $table->foreign('attempt_id')
                    ->references('id')
                    ->on('attempts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_quizzes', function (Blueprint $table) {
            $table->dropForeign('student_quizzes_attempt_id_foreign');
            $table->dropColumn('attempt_id');
            $table->integer('quiz_id');
        });
    }
}
