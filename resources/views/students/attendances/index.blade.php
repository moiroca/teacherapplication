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
                @foreach($studenStubjectAttendances as $index => $attendance)
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
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection