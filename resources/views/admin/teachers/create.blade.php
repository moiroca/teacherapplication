@extends('layouts.blank')

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>Register Teacher<small> List of all teachers.</small></h2>
                <a href="#" class='btn btn-primary btn-sm pull-right'><i class='fa fa-plus'></i> Create Teacher</a>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form method="post" action="{{ route('admin.teachers.save') }}" id="update-user-information" class="form-horizontal form-label-left">
		    		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		    		<input type="hidden" name="role" value="1" />
		    		
		    		{!! BootForm::text('name', 'Name', old('name'), ['placeholder' => 'Full Name']) !!}

		            {!! BootForm::text('username', 'Username', old('username'), ['placeholder' => 'Username']) !!}

		            <div class="ln_solid"></div>
		    		<div class="form-group">
		    			<div class="col-md-6 col-sm-6 col-xs-12">
		    				<button type="submit" class="btn btn-success"><i class='fa fa-save'></i> Save Information</button>
		    			</div>
		    		</div>
		    	</form>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection