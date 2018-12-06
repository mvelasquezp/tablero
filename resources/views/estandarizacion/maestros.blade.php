<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <style type="text/css">
            .tr-hidden{display:none;}
            #rcampo-obligat{display: none;}
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
                            <ul class="nav nav-tabs" id="tab-maestros" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="campos-tab" data-toggle="tab" href="#campos" role="tab" aria-controls="campos" aria-selected="true">Campos definidos por el usuario</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="hitos-tab" data-toggle="tab" href="#hitos" role="tab" aria-controls="hitos" aria-selected="false">Hitos de control</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="eproceso-tab" data-toggle="tab" href="#eproceso" role="tab" aria-controls="eproceso" aria-selected="false">Estados de procesos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="econtrol-tab" data-toggle="tab" href="#econtrol" role="tab" aria-controls="econtrol" aria-selected="false">Estados control documentario</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="campos" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="container">
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <form id="form-campos">
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label for="rcampo-nombre">Nombre del campo</label>
                                                            <input type="text" class="form-control form-control-sm" id="rcampo-nombre" placeholder="Ingrese el nombre">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label for="rcampo-tipo">Tipo de campo</label>
                                                            <select id="rcampo-tipo" class="form-control form-control-sm">
                                                                <option value="0">- Seleccione -</option>
                                                                @foreach($tipos as $tipo)
                                                                <option value="{{ $tipo->value }}">{{ $tipo->text }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label class="mr-3">El campo es obligatorio</label>
                                                            <label for="rcampo-obligat" class="btn btn-sm btn-danger text-light"><tag>No</tag><input type="checkbox" id="rcampo-obligat"></label>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col text-light">
                                                            <button id="btn-sv-campos" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-1">&nbsp;</div>
                                            <div class="col-6">
                                                <table class="table table-sm table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Campo</th>
                                                            <th>Tipo</th>
                                                            <th>Clase</th>
                                                            <th>Fecha Registro</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="campos-tbody"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="hitos" role="tabpanel" aria-labelledby="hitos-tab">
                                    <div class="container">
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <form id="form-hitos">
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label for="rhito-nombre">Nombre del hito</label>
                                                            <input type="text" class="form-control form-control-sm" id="rhito-nombre" placeholder="Ingrese el nombre">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-8">
                                                            <label for="rhito-dias">Disparador del hito</label>
                                                            <input type="text" class="form-control form-control-sm" id="rhito-dias" placeholder="Ingrese el nro. de días">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col text-light">
                                                            <button id="btn-sv-hitos" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-1">&nbsp;</div>
                                            <div class="col-6">
                                                <table class="table table-sm table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Hito</th>
                                                            <th>Disparador</th>
                                                            <th>Fecha Registro</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="hitos-tbody"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="eproceso" role="tabpanel" aria-labelledby="eproceso-tab">
                                    <div class="container">
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <form id="form-eproceso">
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label for="reproceso-nombre">Descripción del estado</label>
                                                            <input type="text" class="form-control form-control-sm" id="reproceso-nombre" placeholder="Ingrese el nombre">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label for="reproceso-codigo">Código</label>
                                                            <input type="text" class="form-control form-control-sm" id="reproceso-codigo" placeholder="Ingrese el código">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col text-light">
                                                            <button id="btn-sv-eproceso" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-1">&nbsp;</div>
                                            <div class="col-6">
                                                <table class="table table-sm table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Estado</th>
                                                            <th>Código</th>
                                                            <th>Fecha Registro</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="eproceso-tbody"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="econtrol" role="tabpanel" aria-labelledby="econtrol-tab">
                                    <div class="container">
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <form id="form-econtrol">
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label for="recontrol-nombre">Descripción del estado</label>
                                                            <input type="text" class="form-control form-control-sm" id="recontrol-nombre" placeholder="Ingrese el nombre">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label for="recontrol-codigo">Código</label>
                                                            <input type="text" class="form-control form-control-sm" id="recontrol-codigo" placeholder="Ingrese el código">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col text-light">
                                                            <button id="btn-sv-econtrol" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-1">&nbsp;</div>
                                            <div class="col-6">
                                                <table class="table table-sm table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Estado</th>
                                                            <th>Código</th>
                                                            <th>Fecha Registro</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="econtrol-tbody"></tbody>
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
                    <div class="modal-footer text-light">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fas fa-chevron-left"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript">
            var ls_campos, ls_hitos, ls_eprocesos, ls_econtrol;
            //
            function FormCamposOnSubmit(event) {
                event.preventDefault();
                var sNombre = document.getElementById("rcampo-nombre").value;
                var sTipo = document.getElementById("rcampo-tipo").value;
                if(sNombre == "") {
                    alert("Debe ingresar el nombre del campo");
                    return false;
                }
                if(sTipo == 0) {
                    alert("Debe seleccionar el tipo del campo");
                    return false;
                }
                var p = {
                    _token: "{{ csrf_token() }}",
                    nombre: sNombre,
                    tipo: sTipo,
                    obligatorio: $("#rcampo-obligat").prop("checked") ? "S" : "N"
                };
                $.post("{{ url('ajax/estandarizacion/sv-campo') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_campos = response.data.campos;
                        document.getElementById("rcampo-nombre").value = "";
                        $("#rcampo-tipo option[value=0]").prop("selected", true);
                        EscribirListaCampos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormHitosSubmit(event) {
                event.preventDefault();
                var sHito = document.getElementById("rhito-nombre").value;
                var sDias = document.getElementById("rhito-dias").value;
                if(sHito == "") {
                    alert("Debe ingresar el nombre del hito");
                    return false;
                }
                if(sDias == "") {
                    alert("Debe ingresar el número de días");
                    return false;
                }
                var p = {
                    _token: "{{ csrf_token() }}",
                    nombre: sHito,
                    dias: sDias
                };
                $.post("{{ url('ajax/estandarizacion/sv-hito') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_hitos = response.data.hitos;
                        document.getElementById("rhito-nombre").value = "";
                        $("#rhito-responsable option[value=0]").prop("selected", true);
                        EscribirListaHitos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormProcesoSubmit(event) {
                event.preventDefault();
                var sEstado = document.getElementById("reproceso-nombre").value;
                var sCodigo = document.getElementById("reproceso-codigo").value;
                if(sEstado == "") {
                    alert("Debe ingresar el nombre del estado");
                    return false;
                }
                if(sCodigo == "") {
                    alert("Debe seleccionar el código del estado");
                    return false;
                }
                var p = {
                    _token: "{{ csrf_token() }}",
                    estado: sEstado,
                    codigo: sCodigo
                };
                $.post("{{ url('ajax/estandarizacion/sv-eproceso') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_eprocesos = response.data.estados;
                        document.getElementById("reproceso-nombre").value = "";
                        document.getElementById("reproceso-codigo").value = "";
                        EscribirListaProcesos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function FormControlSubmit(event) {
                event.preventDefault();
                var sEstado = document.getElementById("recontrol-nombre").value;
                var sCodigo = document.getElementById("recontrol-codigo").value;
                if(sEstado == "") {
                    alert("Debe ingresar el nombre del estado");
                    return false;
                }
                if(sCodigo == "") {
                    alert("Debe seleccionar el código del estado");
                    return false;
                }
                var p = {
                    _token: "{{ csrf_token() }}",
                    estado: sEstado,
                    codigo: sCodigo
                };
                $.post("{{ url('ajax/estandarizacion/sv-econtrol') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_econtrol = response.data.estados;
                        document.getElementById("recontrol-nombre").value = "";
                        document.getElementById("recontrol-codigo").value = "";
                        EscribirListaControl();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function AgregaCampo(event) {
                event.preventDefault();
                var a = $(this);
                var hito = a.data("hito");
                var campo = a.data("campo");
                a.hide();
                var p = {
                    _token: "{{ csrf_token() }}",
                    hito: hito,
                    campo: campo
                };
                $.post("{{ url('ajax/estandarizacion/sv-agrega-campo') }}", p, function(response) {
                    if(response.state == "success") {
                        //oliboli
                        a.removeClass("btn-success").empty().off("click").addClass("btn-danger").append(
                            $("<i/>").addClass("fas fa-trash")
                        ).append("&nbsp;Retirar campo").on("click", QuitarCampo);
                        //
                        var iTbody = $("#dv-" + p.hito).children("div").children("table").children("tbody");
                        iTbody.empty();
                        var campos = response.data.campos;
                        for(var i in campos) {
                            var iCampo = campos[i];
                            iTbody.append(
                                $("<tr/>").append(
                                    $("<td/>").html(iCampo.id)
                                ).append(
                                    $("<td/>").html(iCampo.campo)
                                ).append(
                                    $("<td/>").html(iCampo.tipo)
                                ).append(
                                    $("<td/>").append(
                                        /*$("<a/>").attr("href", "#").append(
                                            $("<i/>").addClass("fas fa-trash")
                                        ).addClass("btn btn-xs btn-danger")*/
                                    )
                                )
                            );
                        }
                    }
                    else alert(response.msg);
                    a.show();
                }, "json");
            }
            function QuitarCampo(event) {
                event.preventDefault();
                var a = $(this);
                var hito = a.data("hito");
                var campo = a.data("campo");
                a.hide();
                var p = {
                    _token: "{{ csrf_token() }}",
                    hito: hito,
                    campo: campo
                };
                $.post("{{ url('ajax/estandarizacion/sv-retira-campo') }}", p, function(response) {
                    if(response.state == "success") {
                        //oliboli
                        a.removeClass("btn-danger").empty().off("click").addClass("btn-success").append(
                            $("<i/>").addClass("fas fa-plus")
                        ).append("&nbsp;Agregar campo").on("click", AgregaCampo);
                        //
                        var iTbody = $("#dv-" + p.hito).children("div").children("table").children("tbody");
                        iTbody.empty();
                        var campos = response.data.campos;
                        for(var i in campos) {
                            var iCampo = campos[i];
                            iTbody.append(
                                $("<tr/>").append(
                                    $("<td/>").html(iCampo.id)
                                ).append(
                                    $("<td/>").html(iCampo.campo)
                                ).append(
                                    $("<td/>").html(iCampo.tipo)
                                ).append(
                                    $("<td/>").append(
                                        /*$("<a/>").attr("href", "#").append(
                                            $("<i/>").addClass("fas fa-trash")
                                        ).addClass("btn btn-xs btn-danger")*/
                                    )
                                )
                            );
                        }
                    }
                    else alert(response.msg);
                    a.show();
                }, "json");
            }
            function ModalHitoCampoOnShow(args) {
                var hid = args.relatedTarget.dataset.hid;
                document.getElementById("reg-hito").innerHTML = args.relatedTarget.dataset.desc;
                var p = {
                    _token: "{{ csrf_token() }}",
                    hito: hid
                };
                $("#modal-tbody").empty();
                $.post("{{ url('ajax/estandarizacion/ls-detalle-campos') }}", p, function(response) {
                    if(response.state == "success") {
                        var campos = response.data.campos;
                        for(var i in campos) {
                            var iCampo = campos[i];
                            var iBoton = $("<a/>").addClass("btn btn-xs").attr({
                                "href": "#",
                                "data-hito": hid,
                                "data-campo": iCampo.id
                            });
                            if(iCampo.hito == 0) {
                                iBoton.addClass("btn-success").append(
                                    $("<i/>").addClass("fas fa-plus")
                                ).append("&nbsp;Agregar campo").on("click", AgregaCampo);
                            }
                            else {
                                iBoton.addClass("btn-danger").append(
                                    $("<i/>").addClass("fas fa-trash")
                                ).append("&nbsp;Retirar campo").on("click", QuitarCampo);
                            }
                            $("#modal-tbody").append(
                                $("<tr/>").append(
                                    $("<td/>").html(iCampo.id)
                                ).append(
                                    $("<td/>").html(iCampo.campo)
                                ).append(
                                    $("<td/>").html(iCampo.tipo)
                                ).append(
                                    $("<td/>").addClass("text-light").append(iBoton)
                                )
                            );
                        }
                    }
                    else alert(response.msg);
                }, "json");
            }
            function CambiaObligatorio(event) {
                event.preventDefault();
                var a = $(this);
                a.hide();
                var p = {
                    _token: "{{ csrf_token() }}",
                    hito: a.data("id"),
                    tipo: a.data("obligat")
                }
                $.post("{{ url('ajax/estandarizacion/upd-obligat-campo') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_campos = response.data.campos;
                        EscribirListaCampos();
                    }
                    else alert(response.msg);
                }, "json").error(function(err) {
                    a.show();
                });
            }
            function RetirarCampo(event) {
                event.preventDefault();
                var a = $(this);
                if(window.confirm("¿Retirar el hito?")) {
                    var p = {
                        _token: "{{ csrf_token() }}",
                        id: a.data("id")
                    };
                    $.post("{{ url('ajax/estandarizacion/sv-elimina-campo') }}", p, function(response) {
                        if(response.state == "success") {
                            ls_campos = response.data.campos;
                            EscribirListaCampos();
                            alert("Campo retirado");
                        }
                        else alert(response.msg);
                    }, "json");
                }
            }
            function RetirarHito(event) {
                event.preventDefault();
                var a = $(this);
                if(window.confirm("¿Retirar el hito?")) {
                    var p = {
                        _token: "{{ csrf_token() }}",
                        id: a.data("id")
                    };
                    $.post("{{ url('ajax/estandarizacion/sv-elimina-hito') }}", p, function(response) {
                        if(response.state == "success") {
                            ls_hitos = response.data.hitos;
                            EscribirListaHitos();
                            alert("Hito retirado");
                        }
                        else alert(response.msg);
                    }, "json")
                }
            }
            function RetirarEstado(event) {
                event.preventDefault();
                var a = $(this);
                if(window.confirm("¿Retirar el estado?")) {
                    var p = {
                        _token: "{{ csrf_token() }}",
                        id: a.data("id")
                    };
                    $.post("{{ url('ajax/estandarizacion/sv-elimina-estado') }}", p, function(response) {
                        if(response.state == "success") {
                            ls_eprocesos = response.data.eprocesos;
                            ls_econtrol = response.data.econtrol;
                            EscribirListaProcesos();
                            EscribirListaControl();
                            alert("Estado retirado");
                        }
                        else alert(response.msg);
                    }, "json");
                }
            }
            //
            function EscribirListaCampos() {
                $("#rcampo-obligat").prop("checked", false).trigger("change");
                var tbody = $("#campos-tbody");
                tbody.empty();
                for(var i in ls_campos) {
                    var icampo = ls_campos[i];
                    var btnTipo = $("<a/>").attr({
                        "href": "#",
                        "data-id": icampo.id,
                        "data-obligat": icampo.obligatorio
                    }).addClass("btn btn-xs").on("click", CambiaObligatorio);
                    if(icampo.obligatorio == 'S') {
                        btnTipo.addClass("btn-success").html("Obligatorio");
                    }
                    else {
                        btnTipo.addClass("btn-warning").html("Personalizado");
                    }
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(icampo.id)
                        ).append(
                            $("<td/>").html(icampo.campo)
                        ).append(
                            $("<td/>").html(icampo.tipo)
                        ).append(
                            $("<td/>").addClass("text-light").append(btnTipo)
                        ).append(
                            $("<td/>").html(icampo.registro)
                        ).append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": icampo.id
                                }).addClass("btn btn-xs btn-danger").append(
                                    $("<i/>").addClass("fas fa-trash")
                                ).append(" Eliminar").on("click", RetirarCampo)
                            ).addClass("text-light")
                        )
                    );
                }
            }
            //
            function ActualizaDisparadorHito(event) {
                event.preventDefault();
                const id = $(this).data("id");
                do {
                    var selection = parseInt(window.prompt("Ingrese nuevo número de días para el disparador", ""), 10);
                } while(isNaN(selection) || selection > 100 || selection < 1);
                const p = {
                    _token: "{{ csrf_token() }}"
                };
                console.log(selection);
            }
            //
            function EscribirListaHitos() {
                var tbody = $("#hitos-tbody");
                tbody.empty();
                for(var i in ls_hitos) {
                    var ihito = ls_hitos[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").append(
                                $("<a/>").html(ihito.id).data("hid",ihito.id).attr("href","#").addClass("btn btn-xs btn-primary").on("click", MuestraCampos)
                            ).addClass("text-light")
                        ).append(
                            $("<td/>").html(ihito.hito)
                        ).append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": ihito.id
                                }).addClass("btn btn-xs btn-warning").html(ihito.dias + " días").on("click", ActualizaDisparadorHito)
                            ).addClass("text-right")
                        ).append(
                            $("<td/>").html(ihito.fecha)
                        ).append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": ihito.id
                                }).addClass("btn btn-xs btn-danger").append(
                                    $("<i/>").addClass("fas fa-trash")
                                ).append(" Eliminar").on("click", RetirarHito)
                            ).addClass("text-light")
                        )
                    ).append(
                        $("<tr/>").addClass("tr-hidden")
                    ).append(
                        $("<tr/>").append(
                            $("<td/>")
                        ).append(
                            $("<td/>").append(
                                $("<div/>").append(
                                    $("<div/>").append(
                                        $("<p/>").addClass("mb-1").html("Espere...")
                                    )
                                ).append(
                                    $("<a/>").attr({
                                        "href": "#",
                                        "data-toggle": "modal",
                                        "data-target": "#modal-hito-campo",
                                        "data-hid": ihito.id,
                                        "data-desc": ihito.hito
                                    }).addClass("btn btn-xs btn-success text-light").append(
                                        $("<i/>").addClass("fas fa-plus")
                                    ).append("&nbsp;Modificar los campos")
                                ).attr("id","dv-" + ihito.id).addClass("mb-2")
                            ).attr("colspan", 4)
                        ).addClass("tr-hidden")
                    );
                }
            }
            function EscribirListaProcesos() {
                var tbody = $("#eproceso-tbody");
                tbody.empty();
                for(var i in ls_eprocesos) {
                    var ieproceso = ls_eprocesos[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(ieproceso.id)
                        ).append(
                            $("<td/>").html(ieproceso.estado)
                        ).append(
                            $("<td/>").html(ieproceso.codigo)
                        ).append(
                            $("<td/>").html(ieproceso.fecha)
                        ).append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": ieproceso.id
                                }).addClass("btn btn-xs btn-danger").append(
                                    $("<i/>").addClass("fas fa-trash")
                                ).append(" Eliminar").on("click", RetirarEstado)
                            )
                        )
                    );
                }
            }
            function EscribirListaControl() {
                var tbody = $("#econtrol-tbody");
                tbody.empty();
                for(var i in ls_econtrol) {
                    var icontrol = ls_econtrol[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(icontrol.id)
                        ).append(
                            $("<td/>").html(icontrol.estado)
                        ).append(
                            $("<td/>").html(icontrol.codigo)
                        ).append(
                            $("<td/>").html(icontrol.fecha)
                        ).append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": icontrol.id
                                }).addClass("btn btn-xs btn-danger").append(
                                    $("<i/>").addClass("fas fa-trash")
                                ).append(" Eliminar").on("click", RetirarEstado)
                            )
                        )
                    );
                }
            }
            function MuestraCampos(event) {
                event.preventDefault();
                var a = $(this);
                var dvId = "dv-" + a.data("hid");
                a.parent().parent().next().next().toggle();
                var p = {
                    _token: "{{ csrf_token() }}",
                    hito: a.data("hid")
                };
                $.post("{{ url('ajax/estandarizacion/ls-campos-hito') }}", p, function(response) {
                    if(response.state == "success") {
                        var iTbody = $("<tbody/>");
                        var campos = response.data.campos;
                        for(var i in campos) {
                            var iCampo = campos[i];
                            iTbody.append(
                                $("<tr/>").append(
                                    $("<td/>").html(iCampo.id)
                                ).append(
                                    $("<td/>").html(iCampo.campo)
                                ).append(
                                    $("<td/>").html(iCampo.tipo)
                                ).append(
                                    $("<td/>").append(
                                        /*$("<a/>").attr("href", "#").append(
                                            $("<i/>").addClass("fas fa-trash")
                                        ).addClass("btn btn-xs btn-danger")*/
                                    )
                                )
                            );
                        }
                        $("#" + dvId).children("div").empty().append(
                            $("<table/>").append(
                                $("<thead/>").append(
                                    $("<tr/>").append(
                                        $("<th/>").html("ID")
                                    ).append(
                                        $("<th/>").html("Campo")
                                    ).append(
                                        $("<th/>").html("Tipo")
                                    ).append(
                                        $("<th/>").html("")
                                    )
                                )
                            ).append(iTbody).addClass("table table-sm table-striped")
                        );
                    }
                    else alert(response.msg);
                }, "json");
            }
            function rcampoObligatChange(event) {
                var rbox = $(this);
                if(rbox.prop("checked")) {
                    rbox.parent().removeClass("btn-danger").addClass("btn-success").children("tag").html("Si");
                }
                else {
                    rbox.parent().removeClass("btn-success").addClass("btn-danger").children("tag").html("No");
                }
            }
            function IniciarInterfaz() {
                var p = { _token: "{{ csrf_token() }}" };
                $.post("{{ url('ajax/estandarizacion/ls-interfaz') }}", p, function(response) {
                    ls_campos = response.data.campos;
                    ls_hitos = response.data.hitos;
                    ls_eprocesos = response.data.procesos;
                    ls_econtrol = response.data.control;
                    EscribirListaCampos();
                    EscribirListaHitos();
                    EscribirListaProcesos();
                    EscribirListaControl();
                }, "json");
                $("#form-campos").on("submit", FormCamposOnSubmit);
                $("#form-hitos").on("submit", FormHitosSubmit);
                $("#form-eproceso").on("submit", FormProcesoSubmit);
                $("#form-econtrol").on("submit", FormControlSubmit);
                $("#modal-hito-campo").on("show.bs.modal", ModalHitoCampoOnShow);
                $("#rcampo-obligat").prop("checked", false).change(rcampoObligatChange);
            }
            //
            $(IniciarInterfaz);
        </script>
    </body>
</html>