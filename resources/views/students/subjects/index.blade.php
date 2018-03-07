@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <h6>Enrolled Subjects</h6>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student->subjects as $index => $subject)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $subject->name }}</td>
                        <td>
                            <a href="{{ route('students.attendances') }}"><i class='fa fa-bar-chart'></i> View Attendance</a> | 
                            <a href="{{ route('students.quizzes', ['subject_id' => $subject->id]) }}"><i class='fa fa-file-text'></i> View Quizzes</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection