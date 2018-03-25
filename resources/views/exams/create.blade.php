@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
    	<form method="post" action="{{ route('exams.save') }}" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="type" value="{{ \App\Models\Quiz::EXAM }}" />
            <div class='form-group'>
                <h3>Create Exam</h3>
            </div>
    		<div class="form-group">
    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
    				Title <span class="required">*</span>
    			</label>
    			<div class="col-md-6 col-sm-6 col-xs-12">
    				<input name="title" type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12">
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="subject" class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">
    				Subject <span class="required">*</span>
    			</label>
    			<div class="col-md-6 col-sm-6 col-xs-12">
    				<select name='subject_id' id="heard" class="form-control col-md-7 col-xs-12" required>
    					<option value="">Choose Subject..</option>
    					@foreach($subjects as $subject)
    						<option value="{{ $subject->id }}">{{ $subject->name }}</option>
    					@endforeach
    				</select>
    			</div>
    		</div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                    Exam Attempts <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input placeholder="Minimum of 1 Attempt" name="attempts" type="number" min=1 max=10 id="attempts" required="required" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                    Duration <span class="required">* (In Hours)</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input placeholder="Duration in Hours" name="duration" type="number" min=1 max=10 id="duration" required="required" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                    Expiration <span class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input name="expiration" type="date" id="expiration" required="required" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
    		<div class="ln_solid"></div>
    		<div class="form-group">
    			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
    				<button type="submit" class="btn btn-success"><i class='fa fa-save'></i> Save Exam</button>
    			</div>
    		</div>
    	</form>
    </div>
    <!-- /page content -->
@endsection