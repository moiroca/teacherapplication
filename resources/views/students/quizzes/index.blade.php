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
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subject->quizzes as $index => $quiz)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $quiz->title }}</td>
                        <td>
                            <a href="{{ route('students.quizzes.take', ['quiz_id' => $quiz->id]) }}"><i class='fa fa-bomb'></i> Take Quiz</a> | 
                            <a href="{{ route('students.quizzes.take', ['quiz_id' => $quiz->id]) }}"><i class='fa fa-bar-chart'></i> View Score</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection