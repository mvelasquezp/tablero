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
                                <div class="col-2">
                                    <label class="mb-1">&nbsp;</label>
                                    <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Nuevo</a>
                                </div>
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
                                    <input type="text" id="np-valor" class="form-control form-control-sm" placeholder="Asigne un nombre al proyecto">
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
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
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
                for(var i in ls_hitos) {
                    var iHito = ls_hitos[i];
                    if(iHito.id == id_pago) {
                        for(var k = 1; k <= armadas; k++) {
                            var select = $("<select/>").append(
                                $("<option/>").val(0).html("- Seleccione -").prop("selected", true).prop("disabled", true)
                            ).addClass("form-control form-control-sm np-hito").attr({
                                "data-hito": iHito.id,
                                "data-pos": idx
                            });
                            for(var z in ls_cargos) {
                                var zCargo = ls_cargos[z];
                                select.append(
                                    $("<option/>").val(zCargo.value).html(zCargo.text)
                                );
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
                                $("<tr/>").append(
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
                        });
                        for(var z in ls_cargos) {
                            var zCargo = ls_cargos[z];
                            select.append(
                                $("<option/>").val(zCargo.value).html(zCargo.text)
                            );
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
                            $("<tr/>").append(
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
                var p = {
                    _token: "{{ csrf_token() }}",
                    organo: $(this).val()
                };
                $.post("{{ url('ajax/estandarizacion/ls-combo-direcciones') }}", p, function(response) {
                    if(response.state == "success") {
                        var cmb_direcciones = response.data.direcciones;
                        var combo = $("#np-direccion");
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
            function NpDireccionOnChange(event) {
                var p = {
                    _token: "{{ csrf_token() }}",
                    organo: document.getElementById("np-organo").value,
                    direccion: $(this).val()
                };
                $.post("{{ url('ajax/estandarizacion/ls-combo-areas') }}", p, function(response) {
                    if(response.state == "success") {
                        var cmb_direcciones = response.data.areas;
                        var combo = $("#np-area");
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
                if(p.descripcion == "") {
                    alert("Ingrese un nombre de proyecto válido");
                    return false;
                }
                if(p.ndias == "") {
                    alert("Ingrese el plazo de ejecución");
                    return false;
                }
                if(p.contratista == "") {
                    alert("Ingrese el nombre del contratista");
                    return false;
                }
                if(p.valor == "") {
                    alert("Ingrese el valor del proyecto");
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
                        responsable: sl.val()
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
            $("#np-catalogo").on("change", NpCatalogoOnChange);
            $("#np-organo").on("change", NpOrganoOnChange);
            $("#np-direccion").on("change", NpDireccionOnChange);
            $("#np-armadas").on("change", MuestraHitosControl);
            $("#modal-responsables").on("show.bs.modal", ModalResponsablesOnShow);
            $("#modal-responsables .modal-footer .btn-primary").on("click", GuardarProyecto);
        </script>
    </body>
</html>