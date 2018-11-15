<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
        <style type="text/css">
            #form-hitos {display:none;}
            #dv-table {display:none;}
            .table th, .table td{vertical-align:middle !important;}
            #grid-proyectos thead tr th {text-align:center}
            .btn-indicador{border-radius:32px;height:32px;text-align:center;width:32px !important;}
            #fl-busca{display:none;}
        </style>
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <form>
                                <div class="form-group">
                                    <label for="form-saludo">Saludo</label>
                                    <input type="text" class="form-control form-control-sm" id="form-saludo" placeholder="Ingresa el saludo">
                                </div>
                                <div class="form-group">
                                    <label for="form-cuerpo">Cuerpo del mensaje</label>
                                    <textarea id="form-cuerpo" class="form-control form-control-sm" rows="8" style="resize:none;"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="form-boton">Texto del botón</label>
                                    <input type="text" class="form-control form-control-sm" id="form-boton" placeholder="Ingresa el texto del botón">
                                </div>
                                <button id="btn-previsualiza" type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> Previsualizar</button>
                            </form>
                        </div>
                        <div class="col-8">
                            <p class="text-secondary mb-2">Previsualización del mensaje</p>
                            <div class="alert alert-light mb-4" style="border:1px solid #e0e0e0">
                                <div class="row">
                                    <div class="col-4" style="background-color:#e0e0e0;">
                                        <br/><br/><br/><br/><br/><br/>
                                        <img class="mb-2" src="{{ asset('images/icons/logo_minsa.png') }}" style="height:32px;">
                                    </div>
                                    <div class="col-8">
                                        <h4 id="msj-saludo" class="text-primary mb-3">{{ $mensaje->saludo }}</h4>
                                        <p class="text-secondary mb-4"><span id="msj-cuerpo">{{ $mensaje->cuerpo }}</span>. Podrás acceder desde este <a href="javascript:void(0)">enlace</a> utilizando las siguientes credenciales:</p>
                                        <p class="mb-1">Usuario: <b>MIUSUARIO</b></p>
                                        <p class="mb-5" >Clave: <b>miclave</b></p>
                                        <p class="mb-0" id="msj-boton">{{ $mensaje->despedida }}</p>
                                    </div>
                                </div>
                            </div>
                            <button id="btn-guarda-cambios" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Guardar los cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript">
            var mensaje = {!! json_encode($mensaje) !!};
            //
            function PrevisualizarMensaje(event) {
                event.preventDefault();
                document.getElementById("msj-saludo").innerHTML = document.getElementById("form-saludo").value;
                document.getElementById("msj-cuerpo").innerHTML = document.getElementById("form-cuerpo").value;
                document.getElementById("msj-boton").innerHTML = document.getElementById("form-boton").value;
            }
            function GuardarCambios(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    saludo: document.getElementById("msj-saludo").innerHTML,
                    cuerpo: document.getElementById("msj-cuerpo").innerHTML,
                    boton: document.getElementById("msj-boton").innerHTML
                };
                $.post("{{ url('ajax/registros/sv-mensaje') }}", p, function(response) {
                    if(response.state == "success") alert("Mensaje actualizado");
                    else alert(response.msg);
                }, "json");
            }
            //
            document.getElementById("form-saludo").value = mensaje.saludo;
            document.getElementById("form-cuerpo").value = mensaje.cuerpo;
            document.getElementById("form-boton").value = mensaje.despedida;
            $("#btn-previsualiza").on("click", PrevisualizarMensaje);
            $("#btn-guarda-cambios").on("click", GuardarCambios);
        </script>
    </body>
</html>