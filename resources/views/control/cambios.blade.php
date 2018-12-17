<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary">
                                <form id="fl-form" class="form-inline">
                                	<p class="text-primary mb-0 mr-3">Revisar</p>
                                    <label for="fl-desde" class="mr-2">Desde</label>
                                    <input type="text" class="form-control form-control-sm datepicker mr-2" id="fl-desde" style="width:6.5rem;" placeholder="yyyy-mm-dd">
                                    <label for="fl-hasta" class="mr-2">Hasta</label>
                                    <input type="text" class="form-control form-control-sm datepicker mr-2" id="fl-hasta" style="width:6.5rem;" placeholder="yyyy-mm-dd">
                                    <label for="fl-nusuario" class="mr-2">Usuario</label>
                                    <input type="hidden" id="fl-usuario">
                                    <input type="text" class="form-control form-control-sm mr-2" id="fl-nusuario" placeholder="Clic para seleccionar usuario">
                                    <button href="#" class="btn btn-sm btn-primary text-light"><i class="fas fa-search"></i> Buscar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-2">
                	<div class="row">
                		<div class="col">
                			<table id="tabla-acciones" class="table table-striped table-hover table-sm" style="visibility:hidden">
                				<thead>
                					<tr>
                						<th width="2%">#</th>
                                        <th>Usuario</th>
                						<th width="10%">Fecha y hora</th>
                						<th>Acción</th>
                					</tr>
                				</thead>
                				<tbody></tbody>
                			</table>
                		</div>
                	</div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <div id="modal-usuario" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva dirección general</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    	<div class="row">
                    		<div class="col">
                    			<p class="text-secondary mb-2">Selecciona un usuario</p>
                    			<!-- -->
                    		</div>
                    	</div>
                        <div class="row">
                        	<div class="col">
                        		<table class="table table-sm table-striped table-hover">
                        			<thead>
                        				<tr>
                        					<th>Codigo</th>
                        					<th>Usuario</th>
                        					<th>Puesto</th>
                        					<th width="5%"></th>
                        				</tr>
                        			</thead>
                        			<tbody>
                                        <tr>
                                            <td>0</td>
                                            <td>Todos los usuarios</td>
                                            <td>-</td>
                                            <td>
                                                <a href="#" data-id="0" data-nombre="Todos los usuarios" class="btn btn-xs btn-primary text-light btn-selector"><i class="fas fa-check"></i></a>
                                            </td>
                                        </tr>
                        				@foreach($usuarios as $idx => $iUsuario)
                        				<tr>
                        					<td>{{ $iUsuario->dni }}</td>
                        					<td>{{ $iUsuario->nombre }}</td>
                        					<td>{{ $iUsuario->puesto }}</td>
                        					<td>
                        						<a href="#" data-id="{{ $iUsuario->id }}" data-nombre="{{ $iUsuario->nombre }}" class="btn btn-xs btn-primary text-light btn-selector"><i class="fas fa-check"></i></a>
                        					</td>
                        				</tr>
                        				@endforeach
                        			</tbody>
                        		</table>
                        	</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
        <script>
        	var ls_acciones;

        	function BuscarAcciones(event) {
        		event.preventDefault();
        		const p = {
        			_token: "{{ csrf_token() }}",
        			desde: document.getElementById("fl-desde").value,
        			hasta: document.getElementById("fl-hasta").value,
        			usuario: document.getElementById("fl-usuario").value
        		};
        		$.post("{{ url('ajax/control/ls-historial-acciones') }}", p, function(response) {
        			if(response.state == "success") {
        				ls_acciones = response.data.acciones;
        				EscribirListaAcciones();
        				$("#tabla-acciones").fadeIn();
        			}
        			else alert(response.msg);
        		}, "json");
        	}
        	function EscribirListaAcciones() {
        		$("#tabla-acciones tbody").empty();
        		for(var i in ls_acciones) {
        			const iAccion = ls_acciones[i];
        			$("#tabla-acciones tbody").append(
        				$("<tr/>").append(
        					$("<td/>").html(parseInt(i) + 1)
    					).append(
                            $("<td/>").html(iAccion.usuario)
                        ).append(
        					$("<td/>").html(iAccion.fecha)
    					).append(
        					$("<td/>").html(iAccion.accion)
    					)
    				);
        		}
        	}
        	function ModalSeleccionarUsuario(event) {
        		$("#modal-usuario").modal("show");
        	}
        	function SeleccionarUsuario(event) {
        		event.preventDefault();
        		const dataset = $(this).data();
        		document.getElementById("fl-nusuario").value = dataset.nombre;
        		document.getElementById("fl-usuario").value = dataset.id;
				$("#modal-usuario").modal("hide");
        	}
        	//
        	$("#tabla-acciones").removeAttr("style").hide();
        	document.getElementById("fl-nusuario").value = "Todos los usuarios";
        	document.getElementById("fl-usuario").value = "0";
        	document.getElementById("fl-desde").value = "{{ $inicio }}";
        	document.getElementById("fl-hasta").value = "{{ $fin }}";
        	$("#fl-form").on("submit", BuscarAcciones);
        	$("#fl-nusuario").on("click", ModalSeleccionarUsuario);
        	$(".btn-selector").on("click", SeleccionarUsuario);
        	$(".datepicker").datepicker({
                autoclose: true,
                daysOfWeekHighlighted: [0,6],
                format: 'yyyy-mm-dd',
                language: 'es',
                startView: 0,
                todayHighlight: true,
                zIndexOffset: 1030
            });
            $("#fl-form").trigger("submit");
        </script>
    </body>
</html>