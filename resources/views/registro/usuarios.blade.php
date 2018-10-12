<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <style type="text/css">
            .btn-xs{
                /**/
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <div class="container mb-1">
                    <div class="row justify-content-md-center">
                        <div class="col-6">
                            <div class="alert alert-secondary">
                                <form class="form-inline">
                                    <input type="text" class="form-control form-control-sm mr-sm-2" id="tb-buscar" placeholder="¿Qué desea buscar?">
                                    <button type="submit" class="btn btn-sm btn-primary text-light"><i class="fas fa-search"></i> Buscar</button>
                                    <a href="#" class="btn btn-sm btn-success ml-3 text-light" data-toggle="modal" data-target="#modal-registro"><i class="fas fa-user-plus"></i> Nuevo</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <table id="tabla-usuarios" class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>DNI</th>
                                        <th>Ap.Paterno</th>
                                        <th>Ap.Materno</th>
                                        <th>Nombres</th>
                                        <th>Fe.Ingreso</th>
                                        <th>Usuario</th>
                                        <th>Puesto</th>
                                        <th>Oficina</th>
                                        <th>Teléfono</th>
                                        <th>e-mail</th>
                                        <th></th>
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
        <div id="modal-registro" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form id="form-registro">
                                <div class="row">
                                    <div class="col">
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-tpdoc">Tipo documento</label>
                                                <select class="form-control form-control-sm" id="reg-tpdoc" name="tpdoc">
                                                    <option value="DNI">Documento Nacional de Identidad (DNI)</option>
                                                    <option value="CE">Carné de extranjería (CE)</option>
                                                    <option value="OT">Otros documentos de identidad</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="reg-dni">Nro. documento</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-dni" name="dni" placeholder="Ingrese DNI / CE / OT">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-apepat">Ape. paterno</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-apepat" name="apepat" placeholder="Ingrese apellido">
                                            </div>
                                            <div class="col">
                                                <label for="reg-apemat">Ape. materno</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-apemat" name="apemat" placeholder="Ingrese apellido">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-nombres">Nombres</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-nombres" name="nombres" placeholder="Ingrese los nombres">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-mail">e-mail</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-mail" name="mail" placeholder="Ingrese e-mail">
                                            </div>
                                            <div class="col">
                                                <label for="reg-telefono">Teléfono</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-telefono" name="telefono" placeholder="Ingrese nro. teléfono">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-cargo">Cargo</label>
                                                <select class="form-control form-control-sm" id="reg-cargo" name="cargo">
                                                    <option value="DNI">Documento Nacional de Identidad (DNI)</option>
                                                    <option value="CE">Carné de extranjería (CE)</option>
                                                    <option value="OT">Otros documentos de identidad</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="text-secondary">Fotografía</p>
                                        <div class="form-group">
                                            <label for="reg-foto">Seleccione una imagen</label>
                                            <input type="file" class="form-control-file" id="reg-foto" name="foto">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-sv-registro" onclick="$('#form-registro').submit()"><i class="far fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        @include('common.scripts')
        <script type="text/javascript">
            var ls_usuarios = {!! json_encode($usuarios) !!};
            //
            function EscribirListaUsuarios() {
                var tbody = $("#tabla-usuarios tbody");
                tbody.empty();
                for(var i in ls_usuarios) {
                    var iUsuario = ls_usuarios[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>")
                        ).append(
                            $("<td/>").html(iUsuario.dni)
                        ).append(
                            $("<td/>").html(iUsuario.apepat)
                        ).append(
                            $("<td/>").html(iUsuario.apemat)
                        ).append(
                            $("<td/>").html(iUsuario.nombres)
                        ).append(
                            $("<td/>").html(iUsuario.fingreso)
                        ).append(
                            $("<td/>").html(iUsuario.alias)
                        ).append(
                            $("<td/>").html(iUsuario.puesto)
                        ).append(
                            $("<td/>").html(iUsuario.oficina)
                        ).append(
                            $("<td/>").html(iUsuario.telefono)
                        ).append(
                            $("<td/>").html(iUsuario.email)
                        ).append(
                            $("<td/>").append(
                                /*$("<a/>").attr({
                                    "href": "#",
                                    "data-cod": iUsuario.dni,
                                    "data-id": iUsuario.id
                                }).addClass("btn btn-xs btn-primary text-light").append(
                                    $("<i/>").addClass("fas fa-edit")
                                ).append("&nbsp;Editar")*/
                            )
                        )
                    );
                }
            }
            //
            $("#modal-registro").on("show.bs.modal", function(args) {
                var select = $("#reg-cargo");
                select.empty().append(
                    $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                );
                var p = { _token: "{{ csrf_token() }}" };
                $.post("{{ url('ajax/registros/ls-puestos') }}", p, function(response) {
                    var ls_puestos = response;
                    for(var i in ls_puestos) {
                        var iPuesto = ls_puestos[i];
                        select.append(
                            $("<option/>").val(iPuesto.id).html(iPuesto.name)
                        );
                    }
                }, "json");
            });
            $("#form-registro").on("submit", function(event) {
                event.preventDefault();
                $("#btn-sv-registro").hide();
                $.ajax({
                    url: "{{ url('ajax/registros/sv-usuario') }}",
                    type: "post",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        location.reload();
                    }
                });
            });
            EscribirListaUsuarios();
        </script>
    </body>
</html>