<!DOCTYPE html>
<html lang="en">
<head>
	<title>Acceso al sistema</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
	<style type="text/css">
		.container-login100{
			background-image: url('images/bg.jpg');
			background-size:cover;
		}
	</style>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">
				<form class="login100-form validate-form flex-sb flex-w" action="{{ url('login/verificar') }}" method="post">
					<span class="login100-form-title p-b-32">Zona de usuarios</span>

					<span class="txt1 p-b-11">Usuario</span>
					<div class="wrap-input100 validate-input m-b-36" data-validate = "El usuario es requerido">
						<input class="input100" type="text" name="user" placeholder="Ingresa tu usuario">
						<span class="focus-input100"></span>
					</div>
					
					<span class="txt1 p-b-11">Contraseña</span>
					<div class="wrap-input100 validate-input m-b-12" data-validate = "La clave es requerida">
						<span class="btn-show-pass">
							<i class="fa fa-eye"></i>
						</span>
						<input class="input100" type="password" name="password" placeholder="Ingresa tu clave">
						<span class="focus-input100"></span>
					</div>
					
					<div class="flex-sb-m w-full p-b-48">
						<div class="contact100-form-checkbox">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
						<div>
							<a href="#" class="txt3">Olvidé mi clave</a>
						</div>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">Ingresar al sistema</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
	<script src="{{ asset('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/login.js') }}"></script>

</body>
</html>