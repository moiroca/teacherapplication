@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <h6>Add document for {{ $subject->name }} </h6>
    	<form
            method="post" 
            action="{{ route('announcements.save', ['subject_id' => $subject->id]) }}" 
            data-parsley-validate 
            class="form-horizontal form-label-left">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
                <label for="content" class="control-label">Content</label>
                <div>
                    <textarea required type='content' class="form-control" id="content" name="content" /"></textarea>
                    @if ($errors->has('content'))
                        <span class="help-block">{{ $errors->first('content') }}</span>
                    @endif
                </div>
            </div>
    		<div class="ln_solid"></div>
            <div class="form-group">
                <div><button class="btn btn-success" type="submit"><i class='fa fa-save'></i> Save Announcement</button></div></div>
    	</form>
    </div>
    <!-- /page content -->
@endsection