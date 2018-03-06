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
                    <th>Status</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjectStudents as $index => $subjectStudent)
                    <tr>
                        <td>
                            <input 
                                disabled="disabled" 
                                {{ in_array($subjectStudent->student->id, $studentAttendance) ? 'checked' : '' }}
                                type="checkbox" 
                                name="{{ 'present['. $subjectStudent->student->id .']' }}" />
                        </td>
                        <td>{{ $subjectStudent->student->name }}</td>
                        <td>{{ $subjectStudent->student->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection