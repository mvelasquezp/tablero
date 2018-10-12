<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <style type="text/css">
            #form-hitos {display:none;}
            #dv-table {display:none;}
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
                                                    <button type="submit" class="btn btn-primary mt-1"><i class="fas fa-save"></i> Guardar</button>
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
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
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
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
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
                            $("<a/>").attr({
                                "href": "#",
                                "data-id": iorgano.id
                            }).addClass("btn btn-xs btn-danger").append(
                                $("<i/>").addClass("fas fa-trash")
                            ).append("&nbsp;Eliminar")
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
                            $("<a/>").attr({
                                "href": "#",
                                "data-id": idireccion.id
                            }).addClass("btn btn-xs btn-danger").append(
                                $("<i/>").addClass("fas fa-trash")
                            ).append("&nbsp;Eliminar")
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
                            $("<a/>").attr({
                                "href": "#",
                                "data-id": iarea.id
                            }).addClass("btn btn-xs btn-danger").append(
                                $("<i/>").addClass("fas fa-trash")
                            ).append("&nbsp;Eliminar")
                        )
                    );
                }
            }
            //listeners
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
            MuestraOrganos();
            MuestraDirecciones();
            MuestraAreas();
        </script>
    </body>
</html>