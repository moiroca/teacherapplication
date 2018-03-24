<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsAttendanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('students_attendance', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('attendance_id');
			$table->unsignedInteger('student_id');

			$table->foreign('attendance_id')
					->references('id')
					->on('attendance');

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
		Schema::table('students_attendance', function (Blueprint $table) {
			$table->dropForeign('students_attendance_attendance_id_foreign');
			$table->dropForeign('students_attendance_student_id_foreign');
		});

		Schema::drop('students_attendance');
	}

}
