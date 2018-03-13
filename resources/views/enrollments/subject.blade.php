@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <h6>Subject: {{ $subject->name }}</h6>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            @if($student->is_enrolled)
                                <form method="POST" action="{{ route('enrollment.subject.delete', ['subject_id' => $subject->id]) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <button type='submit' class='btn btn-warning btn-sm' href="#"><i class='fa fa-mortar-board'></i> Un-Enroll</button>
                               </form>
                                
                            @else
                               <form method="POST" action="{{ route('enrollment.subject.save', ['subject_id' => $subject->id]) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <button type='submit' class='btn btn-primary btn-sm' href="#"><i class='fa fa-save'></i> Enroll</button>
                               </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="submit" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Student Registered!</strong> Ask your teacher to let studenst enroll in {{ config('app.name') }}.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection