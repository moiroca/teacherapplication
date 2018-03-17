@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <a  class="btn btn-primary btn-sm" href="{{ route('subject.students.attendance.index', ['subject_id' => $subject_id]) }}"> <i class='fa fa-list'></i> View Subject Attendance</a>
        <br/>
        <br/>
        <form method="post" action="{{ route('subject.students.attendance.save', ['subject_id' => $subject_id]) }}" id="demo-form2" data-parsley-validate class="form-label-left">

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="date">
                    Date <span class="required">*</span>
                </label>
                <div class="col-md-11 col-sm-11 col-xs-12">
                    <input name="date" type="date" id="date" required="required" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjectStudents as $index => $subjectStudent)
                            <tr>
                                <td>
                                    <input 
                                        checked 
                                        type="checkbox" 
                                        name="{{ 'present['. $subjectStudent->student->id .']' }}" />
                                </td>
                                <td>{{ $subjectStudent->student->name }}</td>
                                <td>{{ $subjectStudent->student->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                                        <button type="submit" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Not Student Found!</strong> You can enroll student when choosing subject.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" class="btn btn-success"><i class='fa fa-save'></i> Save Attendance </button>
                </div>
            </div>  
        </form>
    </div>
    <!-- /page content -->
@endsection