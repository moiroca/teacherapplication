@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <a  class="btn btn-info btn-sm" href="{{ route('modules.subject', ['subject_id' => $subject->id]) }}">
            <i class='fa fa-bullhorn'></i> Create Announcements
        </a>
        <div class="clearfix"></div>
        <br/>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Document Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subject->modules as $index => $module)
                    <tr>
                        <td> {{ $index + 1 }} </td>
                        <td> {{ $module->name }} </td>
                        <td> <a href="{{ \Storage::url($module->path) }}"><i class='fa fa-download'></i>  Download</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>No Module in this Subject Enrolled!</strong> Ask your teacher to enroll you in a subject.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection