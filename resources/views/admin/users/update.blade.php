@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
    	<form method="post" action="{{ route('user.update.save') }}" id="update-user-information" class="form-horizontal form-label-left">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="user_id" value="{{ $user->id }}" />
            <div class='form-group'>
                <h3>Update Information</h3>
            </div>
    		{!! BootForm::text('name', 'Name', $user->name, ['placeholder' => 'Full Name']) !!}

            {!! BootForm::text('username', 'Username', $user->username, ['placeholder' => 'Username']) !!}

            {!! BootForm::select('is_confirmed', 'Confirmation', [1 => 'Confirm', 0 => 'Un Confirm'], $user->is_confirmed) !!}

            <div class="ln_solid"></div>
    		<div class="form-group">
    			<div class="col-md-6 col-sm-6 col-xs-12">
    				<button type="submit" class="btn btn-success"><i class='fa fa-save'></i> Save Information</button>
    			</div>
    		</div>
    	</form>
    </div>
    <!-- /page content -->
@endsection

@push('scripts')
    <script type="text/javascript">
        $('#change-password').on('click', function () {
            var isHidden = $('input[name="change-password"]').val();
            // alert(isHidden.length);
            if (isHidden == 0){
                $('#change-password-section').show();
                isHidden = 1;
            } else {
                $('#change-password-section').hide();
                isHidden = 0;
            }

            $('input[name="change-password"]').val(isHidden);
        });
    </script>
@endpush