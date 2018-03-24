<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignInStudentAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_quiz_answers', function (Blueprint $table) {
            $table->foreign('student_quiz_id')
                    ->references('id')
                    ->on('student_quizzes');

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
        Schema::table('student_quiz_answers', function (Blueprint $table) {
            $table->dropForeign('student_quiz_answers_student_quiz_id_foreign');
            $table->dropForeign('student_quiz_answers_quiz_item_id_foreign');
        });
    }
}
