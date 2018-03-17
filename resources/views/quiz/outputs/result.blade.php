@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <?php $examItemCount = $exam->items->count(); ?>
        <div class="title_left">
            <h3> {{ $exam->title }} Result <small> {{ $examItemCount }} Items</small></h3>
        </div>
        <div class="clearfix"></div>
        <br/>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studentExamResult as $index => $studentExam)
                    <tr>
                        <td scope="row">{{ $index + 1 }}</td>
                        <td>{{ $studentExam->student_name }}</td>
                        <td>
                            @if(is_null($studentExam->score))
                                <span class="label label-info">N/A</span>
                            @elseif($studentExam->score == $examItemCount)
                                <span class="label label-success">{{ $studentExam->score }} out of {{ $examItemCount }}</span>
                            @else 
                                <span class="label label-default">{{ $studentExam->score }} out of {{ $examItemCount }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Subject Enrolled!</strong> Ask your teacher to enroll you in a subject.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection