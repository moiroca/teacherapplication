@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <h6>Subjects Quizzes</h6>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $index => $quiz)
                    <tr>
                        <td>{{ $quiz->title }}</td>
                        <td>
                            <a href="{{ route('students.quizzes.take', ['quiz_id' => $quiz->id]) }}"><i class='fa fa-bomb'></i> Take Exam/Quiz</a> | 
                            <a href="{{ route('students.quizzes.take', ['quiz_id' => $quiz->id]) }}"><i class='fa fa-bar-chart'></i> View Score</a>
                        </td>
                    </tr>
                 @empty
                    <tr>
                        <td colspan="2">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="submit" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Not Quiz Found!</strong> Ask your teacher if this is correct.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection