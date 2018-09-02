<!DOCTYPE html>
<html>
<head>
	<title>Configuración inicial</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/font-awesome-4.7.0/css/font-awesome.min.css') }}">
</head>
<body>
	<div class="container">
		<div class="row mt-3">
			<form class="col">
				<div class="form-group">
					<label for="tpdocumento">Tipo de documento</label>
					<select class="form-control" id="tpdocumento" name="tpdocumento">
						<option value="DNI">Documento Nacional de Identidad - DNI</option>
						<option value="RUC">Registro Único de Contribuyente - RUC</option>
						<option value="CE">Carné de Extranjería - CE</option>
						<option value="OT">Otros documentos de identidad</option>
					</select>
				</div>
				<div class="form-group">
					<label for="nrdocumento">Número del documento</label>
					<input type="text" class="form-control" id="nrdocumento" name="nrdocumento" placeholder="Ingresar e-mail">
				</div>
				<div class="form-group">
					<label for="apepat">Apellido Paterno</label>
					<input type="text" class="form-control" id="apepat" name="apepat" placeholder="Ingresar e-mail">
				</div>
				<div class="form-group">
					<label for="apemat">Apellido Materno</label>
					<input type="text" class="form-control" id="apemat" name="apemat" placeholder="Ingresar e-mail">
				</div>
				<div class="form-group">
					<label for="nombres">Nombres</label>
					<input type="text" class="form-control" id="nombres" name="nombres" placeholder="Ingresar e-mail">
				</div>
				<button type="submit" class="btn btn-primary"><i class="fa-floppy-o"></i> Guardar</button>
			</form>
		</div>
	</div>
	<!-- -->
	<script src="{{ asset('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>