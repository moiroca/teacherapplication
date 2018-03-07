@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>In Attendance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $index => $attendance)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>
                            <a 
                            href="{{ route('attendance.index', [
                                    'attendance_id' => $attendance->id
                                ]) }}">
                                {{ $attendance->date }}
                            </a>
                        </td>
                        <td>{{ $attendance->inattendance->count() }}</td>
                    </tr>
                 @empty
                    <tr>
                        <td colspan="3">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="submit" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Not Attendance Found!</strong> You can create attendance when you choose a subject.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection