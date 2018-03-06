@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <a  class="btn btn-primary btn-sm" href="{{ route('quiz.create') }}"><i class='fa fa-plus'></i> Create Quiz</a>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $index => $quizz)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td><a href="{{ route('quiz.items.create', $quizz->id) }}">{{ $quizz->title }}</a></td>
                        <td>{{ $quizz->subject->name }}</td>
                        <td>
                            <a href="#"><i class='fa fa-edit'></i></a> | 
                            <a href="#"><i class='fa fa-trash'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection