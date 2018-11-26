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
            .text-title{font-weight:bold}
            label{font-size:0.85rem}
            .tr-filtros{display: none;}
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
                            <div class="alert bg-success text-light p-4">
                                @if($foto == 1)
                                <img class="mb-3" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($path)) }}" style="width:100%;">
                                <p class="text-light">Si desea, puede cambiar su foto de perfil con el siguiente botón</p>
                                <a href="#" class="btn btn-light text-dark" data-toggle="modal" data-target="#modal-imagen"><i class="fas fa-edit"></i> Cambiar la foto</a>
                                @else
                                <img src="http://whitefiremedia.com/wp-content/uploads/2015/11/user-role.jpg" style="width:100%;">
                                <p class="text-light">No tiene una foto registrada</p>
                                <a href="#" class="btn btn-light text-dark" data-toggle="modal" data-target="#modal-imagen"><i class="fas fa-upload"></i> Subir una foto de perfil</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-8">
                            <h2 class="text-primary mb-3">{{ $datos->nombres }} {{ $datos->apepat }} {{ $datos->apemat }}</h2>
                            <form id="form-datos">
                                <input type="hidden" id="f-key" value="{{ encrypt(implode('@',[$datos->id, $datos->dni])) }}">
                                <div class="form-group mb-2">
                                    <label class="mb-1" for="fd-dni">DNI</label>
                                    <input type="text" class="form-control-plaintext form-control-sm" id="fd-dni" value="{{ $datos->dni }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="mb-1" for="fd-telefono">Teléfono</label>
                                    <input type="text" class="form-control form-control-sm" id="fd-telefono" value="{{ $datos->telefono }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="mb-1" for="fd-email">e-mail</label>
                                    <input type="text" class="form-control form-control-sm" id="fd-email" value="{{ $datos->email }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="mb-1" for="fd-alias">Usuario</label>
                                    <input type="text" class="form-control-plaintext form-control-sm" id="fd-alias" value="{{ $datos->alias }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="mb-1" for="fd-password">Clave</label>
                                    <input type="password" class="form-control form-control-sm d-inline" id="fd-password" value="putoelquelolea" style="width:240px;">
                                    <a href="#" class="btn btn-info d-inline text-light" data-toggle="modal" data-target="#modal-clave">Cambiar</a>
                                </div>
                                <div class="form-group mb-2">
                                    <label class="mb-1" for="fd-puesto">Puesto</label>
                                    <input type="text" class="form-control-plaintext form-control-sm text-primary" id="fd-puesto" value="{{ isset($datos->puesto) ? $datos->puesto : '(sin asignar)' }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="mb-1" for="fd-oficina">Oficina</label>
                                    <input type="text" class="form-control-plaintext form-control-sm text-success" id="fd-oficina" value="{{ isset($datos->oficina) ? $datos->oficina : '(sin asignar)' }}">
                                </div>
                                <button class="btn btn-primary"><i class="fas fa-save"></i> Actualizar datos</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <div id="modal-clave" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambio de clave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group row">
                                <label for="f-nueva" class="col-sm-2 col-form-label">Nueva clave</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="f-nueva" placeholder="Ingrese nueva clave">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="f-repite" class="col-sm-2 col-form-label">Repita clave</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="f-repite" placeholder="Repita la nueva clave">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar clave</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal imagen -->
        <div id="modal-imagen" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form class="modal-content" action="{{ url('ajax/intranet/upd-imagen') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="key" value="{{ encrypt(implode('@',[$datos->id, $datos->dni])) }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambiar imagen de perfil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="f-imagen" class="col-sm-2 col-form-label">Seleccionar imagen</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="f-imagen" name="imagen" accept="image/jpeg" placeholder="Seleccione un archivo">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-secondary" data-dismiss="modal">Cancelar</a>
                        <input type="submit" name="submit" class="btn btn-primary" value="Actualizar imagen">
                    </div>
                </form>
            </div>
        </div>
    </body>
    <!-- scripts -->
    @include('common.scripts')
    <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/imask/imask.js') }}"></script>
    <script type="text/javascript">
        FormDatosOnSubmit = (event) => {
            event.preventDefault();
            const p = {
                _token: "{{ csrf_token() }}",
                key: document.getElementById("f-key").value,
                telefono: document.getElementById("fd-telefono").value,
                email: document.getElementById("fd-email").value
            };
            $.post("{{ url('ajax/intranet/upd-datos') }}", p, (response) => {
                if(response.state == "success") {
                    alert("Datos actualizados!");
                }
                else alert(response.msg);
            }, "json");
        }
        ActualizarClave = (event) => {
            event.preventDefault();
            if(document.getElementById("f-nueva").value == document.getElementById("f-repite").value) {
                const p = {
                    _token: "{{ csrf_token() }}",
                    nclave: document.getElementById("f-nueva").value,
                    key: document.getElementById("f-key").value
                };
                $.post("{{ url('ajax/intranet/upd-clave') }}", p, (response) => {
                    if(response.state == "success") {
                        alert("Contraseña actualizada");
                        location.reload();
                    }
                    else alert(response.msg);
                }, "json");
            }
            else alert("Las contraseñas no coinciden");
        }
        $("#form-datos").on("submit", FormDatosOnSubmit);
        $("#modal-clave .modal-footer .btn-primary").on("click", ActualizarClave);
    </script>
</html>