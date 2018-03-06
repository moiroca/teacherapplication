@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">

    	<form method="post" action="{{ route('subject.save') }}" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
    		<div class="form-group">
    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
    				Title <span class="required">*</span>
    			</label>
    			<div class="col-md-6 col-sm-6 col-xs-12">
    				<input name="name" type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12">
    			</div>
    		</div>
    		<div class="ln_solid"></div>
    		<div class="form-group">
    			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
    				<button type="submit" class="btn btn-success">Submit</button>
    			</div>
    		</div>
    	</form>
    </div>
    <!-- /page content -->
@endsection