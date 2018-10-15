<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <style type="text/css">
            #form-hitos {display:none;}
            #dv-table {display:none;}
            table th, table td{vertical-align:middle}
        </style>
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-organos-tab" data-toggle="tab" href="#nav-organos" role="tab" aria-controls="nav-organos" aria-selected="true">Órganos de control</a>
                                    <a class="nav-item nav-link" id="nav-direccion-tab" data-toggle="tab" href="#nav-direccion" role="tab" aria-controls="nav-direccion" aria-selected="false">Dirección central</a>
                                    <a class="nav-item nav-link" id="nav-area-tab" data-toggle="tab" href="#nav-area" role="tab" aria-controls="nav-area" aria-selected="false">Área usuaria</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-organos" role="tabpanel" aria-labelledby="nav-organos-tab">
                                    <div class="row mt-3">
                                        <div class="col-4">
                                            <div class="alert alert-secondary">
                                                <form id="form-organo">
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="org-nombre">Nombre del órgano</label>
                                                        <input type="text" class="form-control form-control-sm" id="org-nombre" placeholder="Ingrese el nombre">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="org-abreviatura">Abreviatura</label>
                                                        <input type="text" class="form-control form-control-sm" id="org-abreviatura" placeholder="Abreviatura o siglas">
                                                    </div>
                                                    <button type="submit" class="btn btn-success mt-1"><i class="fas fa-save"></i> Guardar</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <table id="table-organo" class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Abreviatura</th>
                                                        <th>Descripción</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-direccion" role="tabpanel" aria-labelledby="nav-direccion-tab">
                                    <div class="row mt-3">
                                        <div class="col-4">
                                            <div class="alert alert-secondary">
                                                <form id="form-direccion">
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="dir-organo">Órgano de control</label>
                                                        <select id="dir-organo" class="form-control form-control-sm">
                                                            <option value="0">- Seleccione -</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="dir-nombre">Nombre de la dirección</label>
                                                        <input type="text" class="form-control form-control-sm" id="dir-nombre" placeholder="Ingrese el nombre">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="dir-abreviatura">Abreviatura</label>
                                                        <input type="text" class="form-control form-control-sm" id="dir-abreviatura" placeholder="Abreviatura o siglas">
                                                    </div>
                                                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <table id="table-direccion" class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Órgano</th>
                                                        <th>Dirección</th>
                                                        <th>Abreviatura</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-area" role="tabpanel" aria-labelledby="nav-area-tab">
                                    <div class="row mt-3">
                                        <div class="col-4">
                                            <div class="alert alert-secondary">
                                                <form id="form-area">
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="are-organo">Órgano de control</label>
                                                        <select id="are-organo" class="form-control form-control-sm">
                                                            <option value="0">- Seleccione -</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="are-direccion">Dirección central</label>
                                                        <select id="are-direccion" class="form-control form-control-sm">
                                                            <option value="0">- Seleccione -</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="are-nombre">Nombre del área</label>
                                                        <input type="text" class="form-control form-control-sm" id="are-nombre" placeholder="Ingrese el nombre">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1" for="are-abreviatura">Abreviatura</label>
                                                        <input type="text" class="form-control form-control-sm" id="are-abreviatura" placeholder="Abreviatura o siglas">
                                                    </div>
                                                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <table id="table-area" class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Órgano</th>
                                                        <th>Dirección</th>
                                                        <th>Área</th>
                                                        <th>Abreviatura</th>
                                                        <th>Estado</th>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modal edicion organo -->
        <div id="modal-eorgano" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar órgano</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-eorgano">
                            <input type="hidden" id="eorgano-id">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="eorgano-nombre">Órgano de control</label>
                                    <input type="text" class="form-control" id="eorgano-nombre" placeholder="Nombre órgano de control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="eorgano-abreviatura">Abreviatura</label>
                                    <input type="text" class="form-control" id="eorgano-abreviatura" placeholder="Abreviatura del órgano de control">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="$('#form-eorgano').submit()"><i class="fas fa-save"></i> Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal edicion direccion -->
        <div id="modal-edireccion" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar dirección</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-edireccion">
                            <input type="hidden" id="edireccion-id">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="edireccion-nombre">Dirección central</label>
                                    <input type="text" class="form-control" id="edireccion-nombre" placeholder="Nombre dirección central">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="edireccion-abreviatura">Abreviatura</label>
                                    <input type="text" class="form-control" id="edireccion-abreviatura" placeholder="Abreviatura de la dirección">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="$('#form-edireccion').submit()"><i class="fas fa-save"></i> Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal edicion area -->
        <div id="modal-earea" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar área</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-earea">
                            <input type="hidden" id="earea-id">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="earea-nombre">Área usuaria</label>
                                    <input type="text" class="form-control" id="earea-nombre" placeholder="Nombre área usuaria">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="earea-abreviatura">Abreviatura</label>
                                    <input type="text" class="form-control" id="earea-abreviatura" placeholder="Abreviatura del área usuaria">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="earea-vigencia">Retirar</label>
                                    <select id="earea-vigencia" class="form-control form-control-sm">
                                        <option value="Vigente" selected>No</option>
                                        <option value="Retirado">Si</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="$('#form-earea').submit()"><i class="fas fa-save"></i> Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript">
            var ls_organos = {!! json_encode($organos) !!};
            var ls_direcciones = {!! json_encode($direcciones) !!};
            var ls_areas = {!! json_encode($areas) !!};
            //funciones
            function MuestraOrganos() {
                var tbody = $("#table-organo tbody");
                var dcombo = $("#dir-organo");
                var acombo = $("#are-organo");
                tbody.empty();
                dcombo.empty().append(
                    $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                );
                acombo.empty().append(
                    $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                );
                $("#are-direccion").empty().append(
                    $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                );
                for(var i in ls_organos) {
                    var iorgano = ls_organos[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(iorgano.id)
                        ).append(
                            $("<td/>").html(iorgano.organo)
                        ).append(
                            $("<td/>").html(iorgano.abrev)
                        ).append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": iorgano.id,
                                    "data-toggle": "modal",
                                    "data-target": "#modal-eorgano"
                                }).addClass("btn btn-xs btn-primary text-light").append(
                                    $("<i/>").addClass("fas fa-edit")
                                ).append("&nbsp;Editar")
                            )
                        )
                    );
                    dcombo.append(
                        $("<option/>").val(iorgano.id).html(iorgano.organo)
                    );
                    acombo.append(
                        $("<option/>").val(iorgano.id).html(iorgano.organo)
                    );
                }
            }
            function MuestraDirecciones() {
                var tbody = $("#table-direccion tbody");
                tbody.empty();
                for(var i in ls_direcciones) {
                    var idireccion = ls_direcciones[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(idireccion.id)
                        ).append(
                            $("<td/>").html(idireccion.organo)
                        ).append(
                            $("<td/>").html(idireccion.direccion)
                        ).append(
                            $("<td/>").html(idireccion.abrev)
                        ).append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": idireccion.id,
                                    "data-toggle": "modal",
                                    "data-target": "#modal-edireccion"
                                }).addClass("btn btn-xs btn-primary text-light").append(
                                    $("<i/>").addClass("fas fa-edit")
                                ).append("&nbsp;Editar")
                            )
                        )
                    );
                }
            }
            function MuestraAreas() {
                var tbody = $("#table-area tbody");
                tbody.empty();
                for(var i in ls_areas) {
                    var iarea = ls_areas[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(iarea.id)
                        ).append(
                            $("<td/>").html(iarea.organo)
                        ).append(
                            $("<td/>").html(iarea.direccion)
                        ).append(
                            $("<td/>").html(iarea.area)
                        ).append(
                            $("<td/>").html(iarea.abrev)
                        ).append(
                            $("<td/>").append(
                                iarea.vigencia == 'Vigente' ?
                                $("<a/>").addClass("btn btn-xs btn-success text-light").attr("href","javascript:void(0)").html(iarea.vigencia) :
                                $("<a/>").addClass("btn btn-xs btn-danger text-light").attr("href","javascript:void(0)").html(iarea.vigencia)
                            )
                        ).append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": iarea.id,
                                    "data-toggle": "modal",
                                    "data-target": "#modal-earea"
                                }).addClass("btn btn-xs btn-primary text-light").append(
                                    $("<i/>").addClass("fas fa-edit")
                                ).append("&nbsp;Editar")
                            )
                        )
                    );
                }
            }
            //listeners
            function ModalEorganoOnShow(e) {
                var id = e.relatedTarget.dataset.id;
                document.getElementById("eorgano-id").value = id;
                var p = { _token: "{{ csrf_token() }}", id: id };
                $.post("{{ url('ajax/estandarizacion/dt-organo') }}", p, function(response) {
                    if(response.state == "success") {
                        var data = response.data.organo;
                        document.getElementById("eorgano-nombre").value = data.nombre;
                        document.getElementById("eorgano-abreviatura").value = data.abrev;
                    }
                    else alert(response.msg);
                }, "json");
            }
            function ModalEdireccionOnShow(e) {
                var id = e.relatedTarget.dataset.id;
                document.getElementById("edireccion-id").value = id;
                var p = { _token: "{{ csrf_token() }}", id: id };
                $.post("{{ url('ajax/estandarizacion/dt-direccion') }}", p, function(response) {
                    if(response.state == "success") {
                        var data = response.data.direccion;
                        document.getElementById("edireccion-nombre").value = data.nombre;
                        document.getElementById("edireccion-abreviatura").value = data.abrev;
                    }
                    else alert(response.msg);
                }, "json");
            }
            function ModalEareaOnShow(e) {
                var id = e.relatedTarget.dataset.id;
                document.getElementById("earea-id").value = id;
                var p = { _token: "{{ csrf_token() }}", id: id };
                $.post("{{ url('ajax/estandarizacion/dt-area') }}", p, function(response) {
                    if(response.state == "success") {
                        var data = response.data.area;
                        document.getElementById("earea-nombre").value = data.nombre;
                        document.getElementById("earea-abreviatura").value = data.abrev;
                        $("#earea-vigencia option[value=" + data.vigencia + "]").prop("selected", true);
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormEorganoOnSubmit(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    id: document.getElementById("eorgano-id").value,
                    nombre: document.getElementById("eorgano-nombre").value,
                    abreviatura: document.getElementById("eorgano-abreviatura").value
                };
                $.post("{{ url('ajax/estandarizacion/ed-organo') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_organos = response.data.organos;
                        MuestraOrganos();
                        $("#modal-eorgano").modal("hide");
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormEdireccionOnSubmit(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    id: document.getElementById("edireccion-id").value,
                    nombre: document.getElementById("edireccion-nombre").value,
                    abreviatura: document.getElementById("edireccion-abreviatura").value
                };
                $.post("{{ url('ajax/estandarizacion/ed-direccion') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_direcciones = response.data.direcciones;
                        MuestraDirecciones();
                        $("#modal-edireccion").modal("hide");
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormEareaOnSubmit(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    id: document.getElementById("earea-id").value,
                    nombre: document.getElementById("earea-nombre").value,
                    abreviatura: document.getElementById("earea-abreviatura").value,
                    vigencia: document.getElementById("earea-vigencia").value
                };
                $.post("{{ url('ajax/estandarizacion/ed-area') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_areas = response.data.areas;
                        MuestraAreas();
                        $("#modal-earea").modal("hide");
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormOrganoOnSubmit(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    nombre: document.getElementById("org-nombre").value,
                    abrev: document.getElementById("org-abreviatura").value
                };
                $.post("{{ url('ajax/estandarizacion/sv-organo') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_organos = response.data.organos;
                        document.getElementById("org-nombre").value = "";
                        document.getElementById("org-abreviatura").value = "";
                        MuestraOrganos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormDireccionOnSubmit(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    organo: document.getElementById("dir-organo").value,
                    nombre: document.getElementById("dir-nombre").value,
                    abrev: document.getElementById("dir-abreviatura").value
                };
                $.post("{{ url('ajax/estandarizacion/sv-direccion') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_direcciones = response.data.direcciones;
                        document.getElementById("dir-nombre").value = "";
                        document.getElementById("dir-abreviatura").value = "";
                        MuestraDirecciones();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormAreaOnSubmit(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    organo: document.getElementById("are-organo").value,
                    direccion: document.getElementById("are-direccion").value,
                    nombre: document.getElementById("are-nombre").value,
                    abrev: document.getElementById("are-abreviatura").value
                };
                $.post("{{ url('ajax/estandarizacion/sv-area') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_areas = response.data.areas;
                        document.getElementById("are-nombre").value = "";
                        document.getElementById("are-abreviatura").value = "";
                        MuestraAreas();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function DirOrganoOnChange(event) {
                var p = {
                    _token: "{{ csrf_token() }}",
                    organo: $(this).val()
                };
                $.post("{{ url('ajax/estandarizacion/ls-combo-direcciones') }}", p, function(response) {
                    if(response.state == "success") {
                        var cmb_direcciones = response.data.direcciones;
                        var combo = $("#are-direccion");
                        combo.empty().append(
                            $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                        );
                        for(var k in cmb_direcciones) {
                            var kdireccion = cmb_direcciones[k];
                            combo.append(
                                $("<option/>").val(kdireccion.value).html(kdireccion.text)
                            );
                        }
                    }
                    else alert(response.msg);
                }, "json");
            }
            //inicio
            $("#form-organo").on("submit", FormOrganoOnSubmit);
            $("#form-direccion").on("submit", FormDireccionOnSubmit);
            $("#form-area").on("submit", FormAreaOnSubmit);
            $("#are-organo").on("change", DirOrganoOnChange);
            document.getElementById("org-nombre").value = "";
            document.getElementById("org-abreviatura").value = "";
            document.getElementById("dir-nombre").value = "";
            document.getElementById("dir-abreviatura").value = "";
            $("#form-eorgano").on("submit", FormEorganoOnSubmit);
            $("#form-edireccion").on("submit", FormEdireccionOnSubmit);
            $("#form-earea").on("submit", FormEareaOnSubmit);
            $("#modal-eorgano").on("show.bs.modal", ModalEorganoOnShow);
            $("#modal-edireccion").on("show.bs.modal", ModalEdireccionOnShow);
            $("#modal-earea").on("show.bs.modal", ModalEareaOnShow);
            MuestraOrganos();
            MuestraDirecciones();
            MuestraAreas();
        </script>
    </body>
</html>