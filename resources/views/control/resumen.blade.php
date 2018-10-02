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
        </style>
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <table id="grid-proyectos" class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th width="1%"></th>
                                        <th width="2%">ID</th>
                                        <th>Tipo proyecto</th>
                                        <th>Tipo orden</th>
                                        <th>N° Expediente</th>
                                        <th>Fecha emisión</th>
                                        <th>Área usuaria</th>
                                        <th>Descripción</th>
                                        <th>Fecha entrega</th>
                                        <th>Valor</th>
                                        <th>N° pagos</th>
                                        <th>% Avance</th>
                                        <th>Indicador</th>
                                        <th>Días vencimiento</th>
                                        <th>Estado actual</th>
                                        <th>Responsable</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <!-- -->
                            <nav aria-label="Navegador">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                    <li class="page-item" style="margin-left:20px;">
                                        <a class="page-link bg-success text-light" href="{{ url('intranet/seguimiento/crea-proyecto') }}"><i class="fas fa-plus"></i> Nuevo proyecto</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <form id="modal-proyecto" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registro de proyectos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="form-tipo">Tipo de proyecto</label>
                                <select id="form-tipo" class="form-control form-control-sm">
                                    <option value="0">- Seleccione -</option>
                                </select>
                            </div>
                            <div class="col"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="form-nombre">Descripción proyecto</label>
                                <input type="text" id="form-nombre" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="form-expediente">Tipo de proyecto</label>
                                <input type="text" id="form-expediente" class="form-control form-control-sm">
                            </div>
                            <div class="col">
                                <label for="form-hoja-tramite">Hoja de trámite</label>
                                <input type="text" id="form-hoja-tramite" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="form-area">Área usuaria</label>
                                <select id="form-area" class="form-control form-control-sm">
                                    <option value="0">- Seleccione -</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="form-valor">Valor del proyecto</label>
                                <input type="text" id="form-valor" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="form-nombre">Observaciones generales</label>
                                <textarea class="form-control form-control-sm" rows="3" style="resize:none;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- modals -->
        <div id="modal-actualiza-hito" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="datos-tab" data-toggle="tab" href="#datos" role="tab" aria-controls="datos" aria-selected="true">Estado del hito</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="atributos-tab" data-toggle="tab" href="#atributos" role="tab" aria-controls="atributos" aria-selected="false">Atributos</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="datos-tab">
                                <form id="form-actualiza-hito">
                                    <input type="hidden" id="mah-hito" name="hito">
                                    <input type="hidden" id="mah-proyecto" name="proyecto">
                                    <input type="hidden" id="mah-detalle" name="detalle">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="mah-inicio">Fecha inicio</label>
                                            <input type="text" id="mah-inicio" name="inicio" class="form-control form-control-sm datepicker" placeholder="yyyy-mm-dd">
                                        </div>
                                        <div class="col-4">
                                            <label for="mah-fin">Fecha fin</label>
                                            <input type="text" id="mah-fin" name="fin" class="form-control form-control-sm datepicker" placeholder="yyyy-mm-dd">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-10">
                                            <label for="mah-documentacion">Control documentario</label>
                                            <select id="mah-documentacion" name="documentacion" class="form-control form-control-sm">
                                                <option value="0" selected disabled>- Seleccione -</option>
                                                @foreach($estados as $estado)
                                                @if(strcmp($estado->tipo,"C") == 0)
                                                <option value="{{ $estado->value }}">{{ $estado->text }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-10">
                                            <label for="mah-proceso">Control proceso</label>
                                            <select id="mah-proceso" name="proceso" class="form-control form-control-sm">
                                                <option value="0" selected disabled>- Seleccione -</option>
                                                @foreach($estados as $estado)
                                                @if(strcmp($estado->tipo,"P") == 0)
                                                <option value="{{ $estado->value }}">{{ $estado->text }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="mah-observaciones">Observaciones</label>
                                            <textarea id="mah-observaciones" name="observaciones" class="form-control form-control-sm" style="resize:none"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="atributos" role="tabpanel" aria-labelledby="atributos-tab">
                                <p>Aquí irán los atributos de los hitos de control</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Actualizar hito</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
        <script type="text/javascript">
            var ls_proyectos = {!! json_encode($proyectos) !!};
            //
            function MuestraHitos(event) {
                event.preventDefault();
                var a = $(this);
                var id = a.data("id");
                $("#dv-" + id).empty().append(
                    $("<p/>").html("Cargando datos del proyecto. Por favor, espere...")
                ).parent().parent().toggle();
                var p = {
                    _token: "{{ csrf_token() }}",
                    proyecto: id
                };
                $.post("{{ url('ajax/control/ls-hitos-proyecto') }}", p, function(response) {
                    if(response.state == "success") {
                        var tbody = $("<tbody/>");
                        var ls_hitos = response.data.hitos;
                        for(var i in ls_hitos) {
                            var iHito = ls_hitos[i];
                            tbody.append(
                                $("<tr/>").append(
                                    $("<td/>").append(
                                        $("<a/>").attr({
                                            "href": "#",
                                            "data-hito": iHito.hid,
                                            "data-id": iHito.id,
                                            "data-proyecto": iHito.pid,
                                            "data-descripcion": iHito.hito,
                                            "data-toggle": "modal",
                                            "data-target": "#modal-actualiza-hito"
                                        }).append(
                                            $("<i/>").addClass("fas fa-sync-alt")
                                        ).addClass("btn btn-xs btn-success")
                                    ).addClass("text-light")
                                ).append(
                                    $("<td/>").html(iHito.hito)
                                ).append(
                                    $("<td/>")
                                ).append(
                                    $("<td/>")
                                ).append(
                                    $("<td/>").html(iHito.inicio)
                                ).append(
                                    $("<td/>").html(iHito.fin)
                                ).append(
                                    $("<td/>").html(iHito.diasvcto)
                                ).append(
                                    $("<td/>").html(iHito.responsable)
                                ).append(
                                    $("<td/>").html(iHito.nombre)
                                ).append(
                                    $("<td/>").html(iHito.edocumentacion)
                                ).append(
                                    $("<td/>").html(iHito.eproceso)
                                ).append(
                                    $("<td/>").html(iHito.observaciones)
                                )
                            );
                        }
                        $("#dv-" + id).empty().append(
                            $("<table/>").append(
                                $("<thead/>").append(
                                    $("<tr/>").append(
                                        $("<th/>").html("")
                                    ).append(
                                        $("<th/>").html("Hito de control")
                                    ).append(
                                        $("<th/>").html("% Avance")
                                    ).append(
                                        $("<th/>").html("Indicador")
                                    ).append(
                                        $("<th/>").html("Fecha inicio")
                                    ).append(
                                        $("<th/>").html("Fecha límite")
                                    ).append(
                                        $("<th/>").html("Días vcto.")
                                    ).append(
                                        $("<th/>").html("Responsable")
                                    ).append(
                                        $("<th/>").html("")
                                    ).append(
                                        $("<th/>").html("Control documentario")
                                    ).append(
                                        $("<th/>").html("Control del proceso")
                                    ).append(
                                        $("<th/>").html("Observaciones")
                                    )
                                ).addClass("thead-dark")
                            ).append(tbody).addClass("table table-sm table-striped")
                        );
                    }
                    else alert(response.msg);
                }, "json");
            }
            //
            function ListarProyectos() {
                var tbody = $("#grid-proyectos tbody");
                tbody.empty();
                for(var i in ls_proyectos) {
                    var iproyecto = ls_proyectos[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").append(
                                $("<a/>").attr({
                                    "href": "#",
                                    "data-id": iproyecto.id
                                }).addClass("btn btn-xs btn-primary text-light").append(
                                    $("<i/>").addClass("fas fa-list-ul")
                                ).on("click", MuestraHitos)
                            )
                        ).append(
                            $("<td/>").addClass("text-right").html(iproyecto.id)
                        ).append(
                            $("<td/>").html(iproyecto.tipo)
                        ).append(
                            $("<td/>").html(iproyecto.orden)
                        ).append(
                            $("<td/>").html(iproyecto.expediente)
                        ).append(
                            $("<td/>").html(iproyecto.femision)
                        ).append(
                            $("<td/>").html(iproyecto.areausr)
                        ).append(
                            $("<td/>").html(iproyecto.proyecto)
                        ).append(
                            $("<td/>").html(iproyecto.fentrega)
                        ).append(
                            $("<td/>").html(parseFloat(iproyecto.valor).toLocaleString("en-US", { minimumFractionDigits:2, maximumFractionDigits:2 })).addClass("text-right")
                        ).append(
                            $("<td/>").html(iproyecto.armadas).addClass("text-right")
                        ).append(
                            $("<td/>").html("").addClass("text-right")
                        ).append(
                            $("<td/>").html("")
                        ).append(
                            $("<td/>").html(iproyecto.diasvence)
                        ).append(
                            $("<td/>").html("")
                        ).append(
                            $("<td/>").html("")
                        ).append(
                            $("<td/>").html(iproyecto.observaciones)
                        )
                    ).append(
                        $("<tr/>").hide()
                    ).append(
                        $("<tr/>").append(
                            $("<td/>")
                        ).append(
                            $("<td/>").attr("colspan", 16).append(
                                $("<div/>").attr("id", "dv-" + iproyecto.id)
                            )
                        ).hide()
                    );
                }
            }
            function ModalActualizaHitoOnShow(args) {
                var dataset = args.relatedTarget.dataset;
                //
                document.getElementById("mah-inicio").value = "";
                document.getElementById("mah-fin").value = "";
                document.getElementById("mah-observaciones").value = "";
                $("#mah-documentacion option[value=0]").prop("selected", true);
                $("#mah-proceso option[value=0]").prop("selected", true);
                //
                $("#modal-actualiza-hito .modal-header .modal-title").html(dataset.descripcion);
                var p = {
                    _token: "{{ csrf_token() }}",
                    proyecto: dataset.proyecto,
                    hito: dataset.hito,
                    id: dataset.id
                };
                $.post("{{ url('ajax/control/ls-estado-hito') }}", p, function(response) {
                    if(response.state == "success") {
                        var estado = response.data.estado;
                        document.getElementById("mah-hito").value = dataset.hito;
                        document.getElementById("mah-proyecto").value = dataset.proyecto;
                        document.getElementById("mah-detalle").value = dataset.id;
                        document.getElementById("mah-inicio").value = estado.inicio;
                        document.getElementById("mah-fin").value = estado.fin;
                        document.getElementById("mah-observaciones").value = estado.observaciones;
                        $("#mah-documentacion option[value=" + estado.edocumentacion + "]").prop("selected", true);
                        $("#mah-proceso option[value=" + estado.eproceso + "]").prop("selected", true);
                    }
                    else alert(response.msg);
                }, "json");
            }
            function ActualizarHito(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    hito: document.getElementById("mah-hito").value,
                    proyecto: document.getElementById("mah-proyecto").value,
                    detalle: document.getElementById("mah-detalle").value,
                    inicio: document.getElementById("mah-inicio").value,
                    fin: document.getElementById("mah-fin").value,
                    documentacion: document.getElementById("mah-documentacion").value,
                    proceso: document.getElementById("mah-proceso").value,
                    observaciones: document.getElementById("mah-observaciones").value
                };
                $.post("{{ url('ajax/control/upd-estado-hito') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_proyectos = response.data.proyectos;
                        $("#modal-actualiza-hito").modal("hide");
                        ListarProyectos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            //
            ListarProyectos();
            $("#modal-actualiza-hito").on("show.bs.modal", ModalActualizaHitoOnShow);
            $(".datepicker").datepicker({
                autoclose: true,
                daysOfWeekHighlighted: [0,6],
                format: 'yyyy-mm-dd',
                language: 'es',
                startView: 0,
                startDate: '-1d',
                todayHighlight: true,
                zIndexOffset: 1030
            });
            $("#modal-actualiza-hito .modal-footer .btn-primary").on("click", ActualizarHito);
        </script>
    </body>
</html>