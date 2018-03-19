@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
      <div class="title_left">
          <h3> Subjects With Exams <small> List of subjects with exams </small></h3>
      </div>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $index => $subject)
                    <tr>
                        <td scope="row">{{ $index + 1 }}</td>
                        <td><a href="{{ route('quizzes.subjects.exam_list', ['subject_id' => $subject->id]) }}">{{ $subject->name }}</a></td>
                        <td>
                            <a href="{{ route('quizzes.subjects.exam_list', ['subject_id' => $subject->id])  }}"> 
                                <i class='fa fa-bullhorn'></i> View Quizzes
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                         <td colspan="4">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Exams in this Subject!</strong> Create one by visiting Exam Management Page.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection

@push('scripts')
@endpush