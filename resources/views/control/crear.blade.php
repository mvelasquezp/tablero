<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
        <style type="text/css">
            #dv-table {display:none;}
            .dropdown-menu{font-size:0.9rem !important;}
            textarea{resize:none;}

            ul.timeline {
                list-style-type: none;
                position: relative;
            }
            ul.timeline:before {
                content: ' ';
                background: #d4d9df;
                display: inline-block;
                position: absolute;
                left: 29px;
                width: 2px;
                height: 100%;
                z-index: 400;
            }
            ul.timeline > li {
                margin: 20px 0;
                padding-left: 20px;
            }
            ul.timeline > li:before {
                content: ' ';
                background: white;
                display: inline-block;
                position: absolute;
                border-radius: 50%;
                border: 3px solid #22c0e8;
                left: 20px;
                width: 20px;
                height: 20px;
                z-index: 400;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <form id="form-nuevo" class="container">
                    <div class="row">
                        <div class="col">
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="mb-1" for="np-catalogo">Tipo de proyecto</label>
                                    <select class="form-control form-control-sm" id="np-catalogo">
                                        <option value="0" selected disabled>- Seleccione -</option>
                                        @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->value }}">{{ $tipo->text }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="mb-1" for="np-tporden">Tipo de orden</label>
                                    <select class="form-control form-control-sm" id="np-tporden">
                                        <option value="0" selected disabled>- Primero seleccione tipo de proyecto -</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="mb-1" for="np-expediente">Número de expediente</label>
                                    <input type="text" id="np-expediente" class="form-control form-control-sm" placeholder="##-#######-###">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="mb-1" for="np-frecepcion">Fecha recepción UAP</label>
                                    <input type="text" id="np-frecepcion" class="form-control form-control-sm datepicker" placeholder="yyyy-mm-dd">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="mb-1" for="np-organo">Órgano central</label>
                                    <select class="form-control form-control-sm" id="np-organo">
                                        <option value="0" selected disabled>- Seleccione -</option>
                                        <option class="text-success" value="888">Nuevo órgano central</option>
                                        @foreach($organos as $organo)
                                        <option value="{{ $organo->value }}">{{ $organo->text }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="mb-1" for="np-direccion">Dirección general</label>
                                    <select class="form-control form-control-sm" id="np-direccion">
                                        <option value="0" selected disabled>- Seleccione -</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-10">
                                    <label class="mb-1" for="np-area">Área usuaria</label>
                                    <select class="form-control form-control-sm" id="np-area">
                                        <option value="0" selected disabled>- Seleccione -</option>
                                    </select>
                                </div>
                                <!--div class="col-2">
                                    <label class="mb-1">&nbsp;</label>
                                    <a id="bt-nuevo" href="#" data-toggle="modal" data-target="#modal-nuevo" class="btn btn-sm btn-primary text-light" style="display:none;"><i class="fas fa-plus"></i> Nuevo</a>
                                </div-->
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="mb-1" for="np-descripcion">Descripción</label>
                                    <input type="text" id="np-descripcion" class="form-control form-control-sm" placeholder="Asigne un nombre al proyecto">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="mb-1" for="np-plazo">Plazo de ejecución</label>
                                    <input type="text" id="np-plazo" class="form-control form-control-sm" placeholder="Ingrese nro. de días">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="mb-1" for="np-contratista">Contratista</label>
                                    <input type="text" id="np-contratista" class="form-control form-control-sm" placeholder="Nombre del contratista">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="mb-1" for="np-valor">Valor</label>
                                    <input type="text" id="np-valor" class="form-control form-control-sm" placeholder="Asigne el valor del proyecto">
                                </div>
                                <div class="col">
                                    <label class="mb-1" for="np-armadas">Nro. de pagos (armadas)</label>
                                    <select class="form-control form-control-sm" id="np-armadas">
                                        @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }} armada(s)</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="alert alert-light w-100 text-light" style="border:1px solid #b0b0b0">
                                <h4 class="text-dark">Hitos de control</h4>
                                <ul class="timeline mb-3" id="timeline-hitos"></ul>
                                <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-responsables"><i class="fas fa-external-link-alt"></i> Continuar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <div id="modal-responsables" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Responsables del proyecto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-striped" id="table-responsables">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Hito</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"><i class="fas fa-save"></i> Crear proyecto</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modals -->
        <div id="modal-nuevo" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva área usuaria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <label for="na-area">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Nombre Área Usuaria" id="na-area">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label for="na-abrev">Abreviatura</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Abreviatura" id="na-abrev">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modals -->
        <div id="modal-nuevo-organo" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo órgano central</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <label for="no-organo">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Nombre de la dirección" id="no-organo">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label for="no-abrev">Abreviatura</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Abreviatura" id="no-abrev">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modals -->
        <div id="modal-nueva-direccion" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva dirección general</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <label for="nd-direccion">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Nombre dirección central" id="nd-direccion">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label for="nd-abrev">Abreviatura</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Abreviatura" id="nd-abrev">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/imask/imask.js') }}"></script>
        <script type="text/javascript">
            var ls_hitos = [];
            var ls_cargos = [];
            var id_pago = parseInt("{{ $id_pago }}");
            //funciones normales
            function MuestraHitosControl() {
                var armadas = parseInt(document.getElementById("np-armadas").value);
                var ctHitos = $("#timeline-hitos");
                var tbody = $("#table-responsables tbody");
                ctHitos.empty();
                tbody.empty();
                var idx = 1;
var sTipo = $("#np-catalogo option:selected").html().toUpperCase();
console.log(sTipo);
                for(var i in ls_hitos) {
                    var iHito = ls_hitos[i];
                    if(iHito.id == id_pago) {
                        for(var k = 1; k <= armadas; k++) {
                            var select = $("<select/>").append(
                                $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                            ).addClass("form-control form-control-sm np-hito").attr({
                                "data-hito": iHito.id,
                                "data-pos": idx
                            }).on("change", CheckCombos);
                            for(var z in ls_cargos) {
                                var zCargo = ls_cargos[z];
                                if(zCargo.text == null || zCargo.text.indexOf("ANALISTA") == -1 || zCargo.text.indexOf(sTipo) > -1) {
                                    select.append(
                                        $("<option/>").attr("data-usuario", zCargo.usuario).val(zCargo.value).html(zCargo.text)
                                    );
                                }
                            }
                            ctHitos.append(
                                $("<li/>").append(
                                    $("<a/>").addClass("text-primary").attr("href","#").html("Hito de control # " + idx)
                                ).append(
                                    $("<a/>").attr("href", "#").addClass("float-right").html("Peso: " + iHito.peso)
                                ).append(
                                    $("<p/>").css("font-size","0.9em").html(iHito.hito + " " + k)
                                )
                            );
                            tbody.append(
                                $("<tr/>").attr("data-idx", idx).append(
                                    $("<td/>").html(idx)
                                ).append(
                                    $("<td/>").html(iHito.hito + " " + k)
                                ).append(
                                    $("<td/>").addClass("form-group").append(select)
                                )
                            );
                            idx++;
                        }
                    }
                    else {
                        var select = $("<select/>").append(
                            $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                        ).addClass("form-control form-control-sm np-hito").attr({
                            "data-hito": iHito.id,
                            "data-pos": idx
                        }).on("change", CheckCombos);
                        for(var z in ls_cargos) {
                            var zCargo = ls_cargos[z];
                            if(zCargo.text == null || zCargo.text.indexOf("ANALISTA") == -1 || zCargo.text.indexOf(sTipo) > -1) {
                                select.append(
                                    $("<option/>").attr("data-usuario", zCargo.usuario).val(zCargo.value).html(zCargo.text)
                                );
                            }
                        }
                        ctHitos.append(
                            $("<li/>").append(
                                $("<a/>").addClass("text-primary").attr("href","#").html("Hito de control # " + idx)
                            ).append(
                                $("<a/>").attr("href", "#").addClass("float-right").html("Peso: " + iHito.peso)
                            ).append(
                                $("<p/>").css("font-size","0.9em").html(iHito.hito)
                            )
                        );
                        tbody.append(
                            $("<tr/>").attr("data-idx", idx).append(
                                $("<td/>").html(idx)
                            ).append(
                                $("<td/>").html(iHito.hito)
                            ).append(
                                $("<td/>").append(select)
                            )
                        );
                        idx++;
                    }
                }
            }
            //listeners
            function CheckCombos() {
                var idx = parseInt($(this).data("pos"));
                var id = $(this).children("option:selected").data("usuario");
                var trs = $("#table-responsables tbody tr");
                $.each(trs, function() {
                    var tr = $(this);
                    if(parseInt(tr.data("idx")) > idx) {
                        tr.children("td").eq(2).children("select").children("option[data-usuario=" + id + "]").prop("selected", true);
                    }
                });
            }
            function NpCatalogoOnChange(event) {
                var catalogo = $(this).val();
                var combo = $("#np-tporden");
                combo.empty();
                if(catalogo == 1) { //ASP
                    combo.append(
                        $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                    ).append(
                        $("<option/>").val("S").html("Servicios")
                    ).append(
                        $("<option/>").val("C").html("Compras")
                    );
                }
                else {
                    combo.append(
                        $("<option/>").val("S").html("Servicios")
                    )
                }
                //cargar los hitos de control
                var p = {
                    _token: "{{ csrf_token() }}",
                    catalogo: catalogo
                };
                $.post("{{ url('ajax/control/ls-hitos-control') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_hitos = response.data.hitos;
                        ls_cargos = response.data.responsables;
                        MuestraHitosControl();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function NpOrganoOnChange(event) {
                const cOrgano = $(this).val();
                if(cOrgano == 888) {
                    $("#modal-nuevo-organo").modal("show");
                }
                else {
                    const p = {
                        _token: "{{ csrf_token() }}",
                        organo: cOrgano
                    };
                    $.post("{{ url('ajax/estandarizacion/ls-combo-direcciones') }}", p, function(response) {
                        if(response.state == "success") {
                            var cmb_direcciones = response.data.direcciones;
                            var combo = $("#np-direccion");
                            combo.empty().append(
                                $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                            ).append(
                                $("<option/>").val(888).html("Nueva dirección general").addClass("text-success")
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
            }
            function NpDireccionOnChange(event) {
                const cDireccion = $(this).val();
                if(cDireccion == 888) {
                    $("#modal-nueva-direccion").modal("show");
                }
                else {
                    var p = {
                        _token: "{{ csrf_token() }}",
                        organo: document.getElementById("np-organo").value,
                        direccion: cDireccion
                    };
                    $("#bt-nuevo").fadeIn(150);
                    $.post("{{ url('ajax/estandarizacion/ls-combo-areas') }}", p, function(response) {
                        if(response.state == "success") {
                            var cmb_direcciones = response.data.areas;
                            var combo = $("#np-area");
                            combo.empty().append(
                                $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                            ).append(
                                $("<option/>").val(888).html("Nueva área usuaria").addClass("text-success")
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
            }
            function NpAreaOnChange() {
                const cArea = $(this).val();
                if(cArea == 888) {
                    $("#modal-nuevo").modal("show");
                }
            }
            function GuardarProyecto(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    tpcateg: document.getElementById("np-catalogo").value,
                    tporden: document.getElementById("np-tporden").value,
                    expediente: document.getElementById("np-expediente").value,
                    inicio: document.getElementById("np-frecepcion").value,
                    organo: document.getElementById("np-organo").value,
                    direccion: document.getElementById("np-direccion").value,
                    area: document.getElementById("np-area").value,
                    descripcion: document.getElementById("np-descripcion").value,
                    ndias: document.getElementById("np-plazo").value,
                    contratista: document.getElementById("np-contratista").value,
                    valor: document.getElementById("np-valor").value,
                    armadas: document.getElementById("np-armadas").value
                };
                if(p.tpcateg == 0) {
                    alert("Seleccione el tipo del proyecto");
                    return false;
                }
                if(p.tporden == 0) {
                    alert("Seleccione el tipo de orden a registrar");
                    return false;
                }
                if(p.expediente == "") {
                    alert("Ingrese el número de expediente del proceso");
                    return false;
                }
                if(p.inicio == "") {
                    alert("Seleccione la fecha de recepción del proyecto");
                    return false;
                }
                if(p.organo == 0) {
                    alert("Seleccione el Órgano de Control");
                    return false;
                }
                if(p.direccion == 0) {
                    alert("Seleccione la Dirección Central");
                    return false;
                }
                if(p.area == 0) {
                    alert("Seleccione el Área Usuaria");
                    return false;
                }
                var hitos = [];
                var sl_hitos = $(".np-hito");
                var correcto = true;
                sl_hitos.each(function() {
                    var sl = $(this);
                    if(!sl.val()) {
                        alert("Designe al responsable del hito \"" + sl.parent().prev().html() + "\"");
                        correcto = false;
                        return false;
                    }
                    hitos.push({
                        orden: sl.data("pos"),
                        hid: sl.data("hito"),
                        responsable: sl.val(),
                        usuario: sl.children("option:selected").data("usuario")
                    });
                });
                if(correcto) {
                    p.hitos = hitos;
                    $.post("{{ url('ajax/control/sv-proyecto') }}", p, function(response) {
                        if(response.state == "success") {
                            alert("Proyecto registrado correctamente");
                            location.href = "{{ url('intranet/seguimiento/resumen') }}";
                        }
                    }, "json");
                }
            }
            function ModalResponsablesOnShow(event) {
                //
            }
            function ValidarNumeroEntero(e) {
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
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            }
            function ValidarNumeroDecimal(e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
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
            //inicio
            $("#np-catalogo option[value=0]").prop("selected", true);
            $("#np-tporden option[value=0]").prop("selected", true);
            $("#np-organo option[value=0]").prop("selected", true);
            $("#np-direccion option[value=0]").prop("selected", true);
            $("#np-area option[value=0]").prop("selected", true);
            document.getElementById("np-expediente").value = "";
            document.getElementById("np-frecepcion").value = "";
            document.getElementById("np-descripcion").value = "";
            document.getElementById("np-plazo").value = "";
            document.getElementById("np-contratista").value = "";
            document.getElementById("np-valor").value = "";
            $("#np-armadas option[value=1]").prop("selected", true);
            $(".datepicker").datepicker({
                autoclose: true,
                daysOfWeekHighlighted: [0,6],
                format: 'yyyy-mm-dd',
                language: 'es',
                startView: 0,
                todayHighlight: true,
                zIndexOffset: 1030
            });
            //
            function GuardarAreaUsuaria(event) {
                event.preventDefault();
                $("#np-area").empty().append(
                    $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                );
                var p = {
                    _token: "{{ csrf_token() }}",
                    organo: document.getElementById("np-organo").value,
                    direccion: document.getElementById("np-direccion").value,
                    area: document.getElementById("na-area").value,
                    abrev: document.getElementById("na-abrev").value,
                };
                $.post("{{ url('ajax/control/sv-nueva-area') }}", p, function(response) {
                    if(response.state == "success") {
                        var ls_careas = response.data.areas;
                        for(var i in ls_careas) {
                            var iArea = ls_careas[i];
                            $("#np-area").append(
                                $("<option/>").val(iArea.value).html(iArea.text)
                            );
                        }
                        alert("Área agregada");
                        $("#modal-nuevo").modal("hide");
                    }
                    else alert(response.msg);
                }, "json");
            }
            function ModalNuevoOnShow(event) {
                $("#na-area").val("");
                $("#na-organo option[value=0]").prop("selected", true);
                $("#na-direccion").empty().append(
                    $("<option/>").val(0).html("Seleccione")
                );
            }
            function ModalNuevoOrganoOnShow(event) {
                $("#no-organo").val("");
                $("#no-abrev").val("");
            }
            function ModalNuevaDireccionOnShow(event) {
                $("#nd-direccion").val("");
                $("#nd-abrev").val("");
            }
            function GuardarOrgano(event) {
                event.preventDefault();
                //
                const p = {
                    _token: "{{ csrf_token() }}",
                    nombre: document.getElementById("no-organo").value,
                    abrev: document.getElementById("no-abrev").value
                }
                $.post("{{ url('ajax/estandarizacion/sv-organo') }}", p, (response) => {
                    if(response.state == "success") {
                        $("#modal-nuevo-organo").modal("hide");
                        const select = $("#np-organo");
                        select.empty().append(
                            $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                        ).append(
                            $("<option/>").val(888).html("Nuevo órgano de control").addClass("text-success")
                        );
                        const organos = response.data.organos;
                        for(var i in organos) {
                            const iOrgano = organos[i];
                            select.append(
                                $("<option/>").val(iOrgano.id).html(iOrgano.organo)
                            );
                        }
                        //resetea los combos siguientes
                        $("#np-direccion").empty().append(
                            $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                        );
                        $("#np-area").empty().append(
                            $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                        );
                    }
                    else alert(response.msg);
                }, "json");
            }
            function GuardarDireccion(event) {
                event.preventDefault();
                //
                const p = {
                    _token: "{{ csrf_token() }}",
                    nombre: document.getElementById("nd-direccion").value,
                    abrev: document.getElementById("nd-abrev").value,
                    organo: document.getElementById("np-organo").value
                }
                $.post("{{ url('ajax/estandarizacion/sv-direccion-2') }}", p, (response) => {
                    if(response.state == "success") {
                        $("#modal-nueva-direccion").modal("hide");
                        const select = $("#np-direccion");
                        select.empty().append(
                            $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                        ).append(
                            $("<option/>").val(888).html("Nueva área usuaria").addClass("text-success")
                        );
                        const direcciones = response.data.direcciones;
                        for(var i in direcciones) {
                            const iDireccion = direcciones[i];
                            select.append(
                                $("<option/>").val(iDireccion.value).html(iDireccion.text)
                            );
                        }
                        //resetea los combos siguientes
                        $("#np-area").empty().append(
                            $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                        );
                    }
                    else alert(response.msg);
                }, "json");
            }
            //
            $("#np-catalogo").on("change", NpCatalogoOnChange);
            $("#np-organo").on("change", NpOrganoOnChange);
            $("#np-direccion").on("change", NpDireccionOnChange);
            $("#np-area").on("change", NpAreaOnChange);
            $("#np-armadas").on("change", MuestraHitosControl);
            $("#modal-responsables").on("show.bs.modal", ModalResponsablesOnShow);
            $("#modal-responsables .modal-footer .btn-primary").on("click", GuardarProyecto);
            $("#modal-nuevo").on("show.bs.modal", ModalNuevoOnShow);
            $("#modal-nuevo .modal-footer .btn-primary").on("click", GuardarAreaUsuaria);
            $("#np-valor").on("keydown", ValidarNumeroDecimal);
            $("#np-plazo").on("keydown", ValidarNumeroEntero);
            //
            $("#modal-nuevo-organo").on("show.bs.modal", ModalNuevoOrganoOnShow);
            $("#modal-nuevo-organo .modal-footer .btn-primary").on("click", GuardarOrgano);
            $("#modal-nueva-direccion").on("show.bs.modal", ModalNuevaDireccionOnShow);
            $("#modal-nueva-direccion .modal-footer .btn-primary").on("click", GuardarDireccion);
            //
            var element = document.getElementById('np-expediente');
            var maskOptions = {
                mask: '00-000000-000'
            };
            var mask = new IMask(element, maskOptions);
        </script>
    </body>
</html>