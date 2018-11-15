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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-3">
                            <form id="form-tipo" class="alert alert-success mb-4">
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="reg-tipo">Tipo de proyecto</label>
                                        <select class="form-control form-control-sm" id="reg-tipo">
                                            <option value="0">- Seleccione -</option>
                                            @foreach($tipos as $tipo)
                                            <option value="{{ $tipo->value }}">{{ $tipo->text }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2 mt-4">
                                    <div class="col">
                                        <button class="btn btn-light"><i class="fas fa-chevron-right"></i> Seleccionar</button>
                                    </div>
                                </div>
                            </form>
                            <form id="form-hitos" class="alert alert-warning">
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="reg-hito">Seleccione un hito</label>
                                        <select class="form-control form-control-sm" id="reg-hito">
                                            <option value="0">- Seleccione -</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="reg-peso">Ingresa el peso</label>
                                        <input type="text" id="reg-peso" class="form-control form-control-sm" placeholder="Ingrese el peso">
                                    </div>
                                </div>
                                <div class="row mb-2 mt-4">
                                    <div class="col">
                                        <button class="btn btn-primary"><i class="fas fa-plus"></i> Agregar hito</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-9">
                            <tag id="dv-table">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th width="1%"></th>
                                            <th width="3%">ID</th>
                                            <th width="1%"></th>
                                            <th width="36%">Proceso</th>
                                            <th width="10%">Peso</th>
                                            <th width="30%">Usu.Registra</th>
                                            <th width="15%">Fe.Registro</th>
                                            <th width="2%"></th>
                                            <th width="2%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="procesos-tbody"></tbody>
                                </table>
                            </tag>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <div id="modal-hito-campo" class="modal fade  tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <div class="row" id="form-registro">
                                <div class="col">
                                    <p class="mb-2" style="font-size:0.75rem">Hito de control: <b id="reg-hito"></b></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Campo</th>
                                                <th>Tipo</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modal-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fas fa-chevron-left"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript">
            var ls_procesos;
            var nprocesos;
            //
            function CargarProcesos() {
                var combo = $("#reg-hito");
                var tbody = $("#procesos-tbody");
                combo.empty().append(
                    $("<option/>").val(0).html("- Seleccione -")
                );
                tbody.empty();
                nprocesos = 0;
                for(var i in ls_procesos) {
                    var iproceso = ls_procesos[i];
                    if(iproceso.peso) {
                        nprocesos++;
                        tbody.append(
                            $("<tr/>").append(
                                $("<td/>").append(
                                    $("<a/>").attr({
                                        "href": "#",
                                        "data-id": iproceso.id,
                                        "data-tipo": iproceso.tipo,
                                        "data-orden": iproceso.orden
                                    }).addClass("btn btn-xs btn-danger").append(
                                        $("<i/>").addClass("fas fa-arrow-up")
                                    ).on("click", SubirHito)
                                )
                            ).append(
                                $("<td/>").addClass("text-right").html(iproceso.orden)
                            ).append(
                                $("<td/>").append(
                                    $("<a/>").attr({
                                        "href": "#",
                                        "data-id": iproceso.id,
                                        "data-tipo": iproceso.tipo,
                                        "data-orden": iproceso.orden
                                    }).addClass("btn btn-xs btn-success").append(
                                        $("<i/>").addClass("fas fa-arrow-down")
                                    ).on("click", BajarHito)
                                )
                            ).append(
                                $("<td/>").html(iproceso.proceso)
                            ).append(
                                $("<td/>").append(
                                    $("<input/>").attr({
                                        type: "text",
                                        placeholder: "Ingresa el peso",
                                        id: "ip-" + iproceso.id,
                                    }).addClass("form-control form-control-sm text-right").val(iproceso.peso)
                                )
                            ).append(
                                $("<td/>").html(iproceso.agrega)
                            ).append(
                                $("<td/>").html(iproceso.fregistro)
                            ).append(
                                $("<td/>").append(
                                    $("<a/>").attr({
                                        "href": "#",
                                        "data-hito": iproceso.id,
                                        "data-tipo": iproceso.tipo,
                                        "title": "Grabar peso del hito"
                                    }).append(
                                        $("<i/>").addClass("fas fa-save")
                                    ).addClass("btn btn-primary btn-xs").on("click", ActualizaPesoProceso)
                                )
                            ).append(
                                $("<td/>").append(
                                    $("<a/>").attr({
                                        "href": "#",
                                        "data-hito": iproceso.id,
                                        "data-tipo": iproceso.tipo,
                                        "title": "Eliminar el hito"
                                    }).append(
                                        $("<i/>").addClass("fas fa-trash")
                                    ).addClass("btn btn-danger btn-xs").on("click", RetirarHito)
                                )
                            )
                        );
                    }
                    else {
                        combo.append(
                            $("<option/>").val(iproceso.id).html(iproceso.proceso)
                        );
                    }
                }
                $("#form-hitos").fadeIn(150);
                $("#dv-table").fadeIn(150);
            }
            //
            function SubirHito(event) {
                event.preventDefault();
                var a = $(this);
                if(parseInt(a.data("orden")) > 1) {
                    var p = {
                        _token: "{{ csrf_token() }}",
                        hito: a.data("id"),
                        tipo: a.data("tipo"),
                        orden: a.data("orden")
                    };
                    $.post("{{ url('ajax/estandarizacion/upd-sube-hito') }}", p, function(response) {
                        if(response.state == "success") {
                            ls_procesos = response.data.procesos;
                            CargarProcesos();
                        }
                        else alert(response.msg);
                    }, "json");
                }
                else console.log("seas pendejo...");
            }
            function BajarHito(event) {
                event.preventDefault();
                var a = $(this);
                if(parseInt(a.data("orden")) < nprocesos) {
                    var p = {
                        _token: "{{ csrf_token() }}",
                        hito: a.data("id"),
                        tipo: a.data("tipo"),
                        orden: a.data("orden")
                    };
                    $.post("{{ url('ajax/estandarizacion/upd-baja-hito') }}", p, function(response) {
                        if(response.state == "success") {
                            ls_procesos = response.data.procesos;
                            CargarProcesos();
                        }
                        else alert(response.msg);
                    }, "json");
                }
                else console.log("seas pendejo...");
            }
            function ActualizaPesoProceso(event) {
                event.preventDefault();
                var a = $(this);
                var input = $("#ip-" + a.data("hito"));
                a.hide();
                input.prop("readonly", true);
                var p = {
                    _token: "{{ csrf_token() }}",
                    hito: a.data("hito"),
                    tipo: a.data("tipo"),
                    peso: input.val()
                }
                $.post("{{ url('ajax/estandarizacion/upd-hito-proyecto') }}", p, function(response) {
                    if(response.state == "success") {
                        a.show();
                        input.prop("readonly", false);
                        alert("Se actualizó el peso del hito");
                    }
                }, "json");
            }
            function RetirarHito(event) {
                event.preventDefault();
                var a = $(this);
console.log(a);
                var p = {
                    _token: "{{ csrf_token() }}",
                    tipo: a.data("tipo"),
                    hito: a.data("hito")
                };
                $.post("{{ url('ajax/estandarizacion/upd-retira-hito') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_procesos = response.data.procesos;
                        CargarProcesos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormTipoOnSubmit(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    tipo: document.getElementById("reg-tipo").value
                };
                $.post("{{ url('ajax/estandarizacion/ls-hitos-proyecto') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_procesos = response.data.procesos;
                        CargarProcesos();
                    }
                }, "json");
            }
            function FormHitosOnSubmit(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    tipo: document.getElementById("reg-tipo").value,
                    hito: document.getElementById("reg-hito").value,
                    peso: document.getElementById("reg-peso").value
                };
                $.post("{{ url('ajax/estandarizacion/sv-hito-proyecto') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_procesos = response.data.procesos;
                        CargarProcesos();
                        document.getElementById("reg-peso").value = "";
                    }
                }, "json");
            }
            //
            $("#reg-tipo option[value=0]").prop("selected", true);
            $("#form-tipo").on("submit", FormTipoOnSubmit);
            $("#form-hitos").on("submit", FormHitosOnSubmit);
        </script>
    </body>
</html>