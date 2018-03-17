@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="title_left">
          <h3> {{ $subject->name }} Exams <small> List of Exams </small></h3>
        </div>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Items</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $index => $exam)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>
                            <a href="{{ route('quiz.items.create', $exam->id) }}">{{ $exam->title }}</a>
                        </td>
                        <td>{{ $exam->exam_item_count }}</td>
                        <td>
                            <a href="{{ route('exams.subjects.exam_list.result', ['subject_id' => $exam->subject_id, 'exam_id' => $exam->id]) }}"><i class='fa fa-bullhorn'></i> View Result</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="submit" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Exams Found!</strong> You can create quiz by visiting Exam Management.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection