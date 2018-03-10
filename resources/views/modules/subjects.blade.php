@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <br/>
        <br/>
    	<table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $index => $subject)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td><a href="{{ route('subject.students', $subject->id) }}">{{ $subject->name }}</a></td>
                        <td>
                            <a href="{{ route('modules.subject.index', ['subject_id' => $subject->id]) }}"> 
                                <i class='fa fa-bullhorn'></i>
                            </a> | 
                            <a href="#"><i class='fa fa-edit'></i></a> | 
                            <a href="#"><i class='fa fa-trash'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /page content -->
@endsection