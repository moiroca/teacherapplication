@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
    	<div class="col-md-12">
            <p>Welcome <strong>{{ Auth()->user()->name }}!</strong></p>
    	</div>
    </div>
    <!-- /page content -->
@endsection