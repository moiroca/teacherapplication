<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsSubjectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_subjects', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('subject_id');
			$table->unsignedInteger('student_id');

			$table->foreign('subject_id')
					->references('id')
					->on('subjects');

			$table->foreign('student_id')
					->references('id')
					->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('student_subjects', function (Blueprint $table) {
			$table->dropForeign('student_subjects_subject_id_foreign');
			$table->dropForeign('student_subjects_student_id_foreign');
		});

		Schema::drop('student_subjects');
	}

}
