@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <h6>{{ $subject->name }} Attendances</h6> 
        <br/>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studenStubjectAttendances as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->date }}</td>
                        <td>
                            @if( $attendance->is_present )
                                <span class="label label-success">
                                    <i class="fa fa-smile-o"></i> Present
                                </span>
                            @else
                                <span class="label label-danger">
                                    <i class="fa fa-frown-o"></i> Absent
                                </span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="submit" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Not Attendance Found!</strong> Ask your teacher if this is correct.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection