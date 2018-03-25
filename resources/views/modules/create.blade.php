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

            <div class="form-group {{ ($errors->has('module')) ? 'has-error' : '' }}">
                <label for="module" class="control-label">Name</label>
                <div>
                    <input type='text' class="form-control" id="module" name="module" /">
                    @if($errors->has('module'))
                        <span class="help-block">{{ $errors->first('module') }}</span>
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
                <div><button class="btn btn-success" type="submit"><i class='fa fa-save'></i> Save Module</button></div></div>
    	</form>
    </div>
    <!-- /page content -->
@endsection