<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <style type="text/css">
            #edit-imagen{max-height:240px;margin:0 auto;}
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
                                <form id="form-busca" class="form-inline">
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
                            <table id="tabla-usuarios" class="table table-sm table-striped table-hover">
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
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <label for="reg-vigencia">Habilitado</label>
                                                <select class="form-control form-control-sm" id="reg-vigencia" name="vigencia">
                                                    <option value="Vigente">Si</option>
                                                    <option value="Retirado">No</option>
                                                </select>
                                            </div>
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
        <!-- modals -->
        <div id="modal-edicion" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form id="form-edicion">
                                <div class="row">
                                    <div class="col">
                                        <input type="hidden" id="edit-id" name="uid">
                                        <input type="hidden" id="edit-dni" name="dni">
                                        <input type="hidden" id="edit-oldcargo" name="oldcargo">
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="edit-apepat">Ape. paterno</label>
                                                <input type="text" class="form-control form-control-sm" id="edit-apepat" name="apepat" placeholder="Ingrese apellido">
                                            </div>
                                            <div class="col">
                                                <label for="edit-apemat">Ape. materno</label>
                                                <input type="text" class="form-control form-control-sm" id="edit-apemat" name="apemat" placeholder="Ingrese apellido">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="edit-nombres">Nombres</label>
                                                <input type="text" class="form-control form-control-sm" id="edit-nombres" name="nombres" placeholder="Ingrese los nombres">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="edit-mail">e-mail</label>
                                                <input type="text" class="form-control form-control-sm" id="edit-mail" name="mail" placeholder="Ingrese e-mail">
                                            </div>
                                            <div class="col">
                                                <label for="edit-telefono">Teléfono</label>
                                                <input type="text" class="form-control form-control-sm" id="edit-telefono" name="telefono" placeholder="Ingrese nro. teléfono">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="edit-cargo">Cargo</label>
                                                <select class="form-control form-control-sm" id="edit-cargo" name="cargo"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="text-secondary mb-1">Fotografía</p>
                                        <div class="form-group">
                                            <img id="edit-imagen" src="" class="img-fluid">
                                            <label for="edit-foto">Cambiar la imagen</label>
                                            <input type="file" class="form-control-file" id="edit-foto" name="foto">
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <label for="edit-vigencia">Habilitado</label>
                                                <select class="form-control form-control-sm" id="edit-vigencia" name="vigencia">
                                                    <option value="Vigente">Si</option>
                                                    <option value="Retirado">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-sv-edicion" onclick="$('#form-edicion').submit()"><i class="far fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        @include('common.scripts')
        <script type="text/javascript">
            var ls_usuarios = {!! json_encode($usuarios) !!};
            //
            function EscribirListaUsuarios(text) {
                var tbody = $("#tabla-usuarios tbody");
                tbody.empty();
                for(var i in ls_usuarios) {
                    var iUsuario = ls_usuarios[i];
                    text = text.toLowerCase();
                    if(text == "" || iUsuario.dni.indexOf(text) > -1 || (iUsuario.apepat && iUsuario.apepat.toLowerCase().indexOf(text) > -1) || 
                        (iUsuario.apemat && iUsuario.apemat.toLowerCase().indexOf(text) > -1) || (iUsuario.nombres && iUsuario.nombres.toLowerCase().indexOf(text) > -1) || 
                        (iUsuario.puesto && iUsuario.puesto.toLowerCase().indexOf(text) > -1) || (iUsuario.oficina && iUsuario.oficina.toLowerCase().indexOf(text) > -1)) {
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
                                    $("<a/>").attr({
                                        "href": "#",
                                        "data-cod": iUsuario.dni,
                                        "data-id": iUsuario.id,
                                        "data-toggle": "modal",
                                        "data-target": "#modal-edicion"
                                    }).addClass("btn btn-xs btn-primary text-light").append(
                                        $("<i/>").addClass("fas fa-edit")
                                    ).append("&nbsp;Editar")
                                )
                            )
                        );
                    }
                }
            }
            function ValidarNumero(e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 || //añadir 110 y 190 para el punto decimal
                     // Allow: Ctrl/cmd+A
                    (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                     // Allow: Ctrl/cmd+C
                    (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                     // Allow: Ctrl/cmd+X
                    (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                     // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                         // let it happen, don't do anything
                         return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            }
            function ValidarTelefono(e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                     // Allow: Ctrl/cmd+A
                    (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                     // Allow: Ctrl/cmd+C
                    (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                     // Allow: Ctrl/cmd+X
                    (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                     // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                         // let it happen, don't do anything
                         return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105) && !(e.key == '(' || e.key == ')' || e.key == '-')) {
                    e.preventDefault();
                }
            }
            function LimpiarFormulario(args) {
                $("#form-registro")[0].reset();
                $("#reg-cargo option[value=0]").prop("selected", true);
                $("#reg-vigencia option[value=Vigente]").prop("selected", true);
            }
            function BuscarUsuarios(event) {
                event.preventDefault();
                EscribirListaUsuarios(document.getElementById("tb-buscar").value);
            }
            //
            $("#modal-registro").on("show.bs.modal", function(args) {
                var select = $("#reg-cargo");
                select.empty().append(
                    $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                );
                var p = { _token: "{{ csrf_token() }}" };
                $.post("{{ url('ajax/registros/ls-combo-puestos') }}", p, function(response) {
                    if(response.state == "success") {
                        var ls_puestos = response.data.puestos;
                        for(var i in ls_puestos) {
                            var iPuesto = ls_puestos[i];
                            select.append(
                                $("<option/>").val(iPuesto.value).html(iPuesto.text)
                            );
                        }
                    }
                }, "json");
            });
            $("#modal-edicion").on("show.bs.modal", function(args) {
                var dataset = args.relatedTarget.dataset;
                $("#form-edicion")[0].reset();
                var select = $("#edit-cargo");
                $("#edit-imagen").attr('src', '');
                select.empty().append(
                    $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                );
                var p = { _token: "{{ csrf_token() }}", id:dataset.id };
                document.getElementById("edit-id").value = dataset.id;
                document.getElementById("edit-dni").value = dataset.cod;
                $.post("{{ url('ajax/registros/dt-usuario') }}", p, function(response) {
                    if(response.state == "success") {
                        var ls_puestos = response.data.puestos;
                        var usrdata = response.data.usuario;
                        var imgdata = response.data.imagen;
                        for(var i in ls_puestos) {
                            var iPuesto = ls_puestos[i];
                            select.append(
                                $("<option/>").val(iPuesto.value).html(iPuesto.text)
                            );
                        }
                        document.getElementById("edit-apepat").value = usrdata.apepat;
                        document.getElementById("edit-apemat").value = usrdata.apemat;
                        document.getElementById("edit-nombres").value = usrdata.nombres;
                        document.getElementById("edit-mail").value = usrdata.mail;
                        document.getElementById("edit-telefono").value = usrdata.telefono;
                        document.getElementById("edit-oldcargo").value = usrdata.puesto;
                        $("#edit-cargo option[value=" + usrdata.puesto + "]").prop("selected", true);
                        $("#edit-vigencia option[value=" + usrdata.vigencia + "]").prop("selected", true);
                        if(imgdata != '') $("#edit-imagen").attr('src', 'data:image/jpeg;charset=utf-8;base64,' + imgdata);
                    }
                    else alert(response.msg);
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
                        if(data.state == "success") {
                            setTimeout(function() {},1000);
                            location.reload();
                        }
                        else alert(data.msg);
                    }
                });
            });
            $("#form-edicion").on("submit", function(event) {
                event.preventDefault();
                $("#btn-sv-edicion").hide();
                $.ajax({
                    url: "{{ url('ajax/registros/ed-usuario') }}",
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
            EscribirListaUsuarios('');
            $("#modal-registro").on("show.bs.modal", LimpiarFormulario);
            $("#reg-dni").on("keydown", ValidarNumero);
            $("#reg-telefono").on("keydown", ValidarTelefono);
            $("#form-busca").on("submit", BuscarUsuarios);
        </script>
    </body>
</html>