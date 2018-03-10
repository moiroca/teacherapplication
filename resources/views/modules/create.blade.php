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
            enctype="multipart/form-data"
            method="post" 
            action="{{ route('modules.subject.save', ['subject_id' => $subject->id]) }}" 
            id="demo-form2" 
            data-parsley-validate 
            class="form-horizontal form-label-left">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <div class="form-group {{ ($errors->has('announcement')) ? 'has-error' : '' }}">
                <label for="announcement" class="control-label">Announcement</label>
                <div>
                    <textarea class="form-control" id="announcement" name="announcement" cols="50" rows="10">gasdasd</textarea>
                    @if($errors->has('announcement'))
                        <span class="help-block">{{ $errors->first('announcement') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group {{ ($errors->has('document')) ? 'has-error' : '' }}">
                <label for="document" class="control-label">Attachments (Only accepts pdf and image file formats)</label>
                <div>
                    <input class="" data-buttonbefore="true" id="document" name="document" type="file">
                    @if($errors->has('document'))
                        <span class="help-block">{{ $errors->first('document') }}</span>
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