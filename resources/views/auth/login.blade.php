<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title> {{ config('app.name') }} </title>
    
    <!-- Bootstrap -->
    <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("css/font-awesome.min.css") }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset("css/gentelella.min.css") }}" rel="stylesheet">

</head>

<body class="login">
<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
				{!! BootForm::open(['url' => url('/login'), 'method' => 'post']) !!}
                    
				<h4>{{ config('app.name') }} Login</h4>
			
				{!! BootForm::email('email', 'Email', old('email'), ['placeholder' => 'Email', 'afterInput' => '<span>test</span>'] ) !!}
			
				{!! BootForm::password('password', 'Password', ['placeholder' => 'Password']) !!}
				
                <div class="form-group">
                	<button class="btn btn-default col-md-12 col-offset-0" type="submit"><i class='fa fa-lock'></i> Login</button>
                </div>
				<div class="clearfix"></div>
                    
				<div class="separator">
					<p class="change_link">New to {{ config('app.name') }}?
						<a href="{{ url('/register') }}" class="to_register"> Create Account </a>
					</p>
                        
					<div class="clearfix"></div>
				</div>
				
				{!! BootForm::close() !!}
            </section>
        </div>
    </div>
</div>
</body>
</html>