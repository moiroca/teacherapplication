@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Create Subject <small></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" action="{{ route('subject.save') }}" id="demo-form2" data-parsley-validate class="form-label-left">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <label class="control-label col-md-12 3 col-sm-12 col-xs-12" for="school_year">
                                School Year <span class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <select name="school_year" id="school_year" required="required" class="form-control col-md-7 col-xs-12">
                                    @foreach($schoolYears as $schoolYear)
                                        <option value="{{ $schoolYear->id }}"> {{ $schoolYear->from }}-{{ $schoolYear->to }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-12 3 col-sm-12 col-xs-12" for="semester">
                                Semester <span class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <select name="semester" id="semester" required="required" class="form-control col-md-7 col-xs-12">
                                    @foreach(config('app.semesters') as $index => $semester)
                                        <option value="{{ $index }}"> {{ $semester }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-12 3 col-sm-12 col-xs-12" for="first-name">
                                Period <span class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <select name="period" id="period" required="required" class="form-control col-md-7 col-xs-12">
                                    @foreach(config('app.periods') as $index => $period)
                                        <option value="{{ $index }}"> {{ $period }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-12 3 col-sm-12 col-xs-12" for="first-name">
                                Title <span class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input name="name" type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <br/>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection