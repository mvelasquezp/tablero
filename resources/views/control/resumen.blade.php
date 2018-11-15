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
                        <div class="col">
                            <div class="alert alert-secondary">
                                <form class="form-inline">
                                    <div class="btn-group btn-group-sm mr-4" role="group" aria-label="Basic example">
                                        <button data-tipo="0" type="button" class="btn btn-success btn-catalogo active">Todos</button>
                                        <button data-tipo="1" type="button" class="btn btn-primary btn-catalogo">ASP</button>
                                        <button data-tipo="2" type="button" class="btn btn-danger btn-catalogo">Terceros</button>
                                    </div>
                                    <label for="fl-atributo" class="mr-2">Atributos del hito</label>
                                    <select id="fl-atributo" class="form-control form-control-sm mr-2">
                                        <option value="-1" selected disabled>- Seleccione -</option>
                                        <option value="0" data-tipo="0">Cualquier atributo</option>
                                        @foreach($atributos as $atributo)
                                        <option value="{{ $atributo->value }}" data-tipo="{{ $atributo->tipo }}">{{ $atributo->text }}</option>
                                        @endforeach
                                    </select>
                                    <tag id="fl-input" class="mr-3"></tag>
                                    <a id="fl-busca" href="#" class="btn btn-sm btn-primary text-light"><i class="fas fa-search"></i> Buscar</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <table id="grid-proyectos" class="table table-sm table-striped table-responsive">
                                <thead>
                                    <tr class="tr-header">
                                        <th width="1%">
                                            <a href="#" class="btn btn-xs btn-info text-light btn-filtros"><i class="fas fa-search"></i></a>
                                        </th>
                                        <th width="3%">ID</th>
                                        <th>Tipo proyecto</th>
                                        <th>Tipo orden</th>
                                        <th>N° Expediente</th>
                                        <th>Fecha recepción UAP</th>
                                        <th>Área usuaria</th>
                                        <th>Descripción</th>
                                        <th>Contratista</th>
                                        <th>Plazo ejecución</th>
                                        <th>Fecha entrega</th>
                                        <th>Valor</th>
                                        <th width="3%">N° pagos</th>
                                        <th class="text-danger">% Avance</th>
                                        <th class="text-danger">Indicador</th>
                                        <th class="text-danger">Días vencimiento</th>
                                        <th class="text-danger">Estado actual</th>
                                        <th class="text-danger">Responsable</th>
                                        <th class="text-danger">Observaciones</th>
                                    </tr>
                                    <tr class="tr-filtros tr-header">
                                        <th>
                                            <a href="#" class="btn btn-xs btn-info text-light btn-filtros"><i class="fas fa-search"></i></a>
                                        </th>
                                        <th width="3%">ID</th>
                                        <th>Tipo proyecto</th>
                                        <th>
                                            <select id="fl-tporden" class="form-control form-control-sm fl-select" data-idx="3">
                                                <option value="0">Cualquiera</option>
                                                <option value="Compras">Compras</option>
                                                <option value="Servicios">Servicios</option>
                                            </select>
                                        </th>
                                        <th>
                                            <input type="text" id="fl-expediente" class="form-control form-control-sm fl-texto" data-idx="4" placeholder="Expediente">
                                        </th>
                                        <th>
                                            <input type="text" id="fl-recepcion" class="form-control form-control-sm datepicker" placeholder="F.R.UAP">
                                        </th>
                                        <th>
                                            <input type="text" id="fl-areausr" class="form-control form-control-sm fl-texto" data-idx="6" placeholder="Área usuaria">
                                        </th>
                                        <th>
                                            <input type="text" id="fl-descripcion" class="form-control form-control-sm fl-texto" data-idx="7" placeholder="Descripción">
                                        </th>
                                        <th>
                                            <input type="text" id="fl-entrega" class="form-control form-control-sm datepicker" placeholder="F. Entrega">
                                        </th>
                                        <th>
                                            <input type="text" id="fl-valor" class="form-control form-control-sm fl-texto" data-idx="9" placeholder="Valor">
                                        </th>
                                        <th width="3%">
                                            <select id="fl-armadas" class="form-control form-control-sm fl-select" data-idx="10">
                                                <option value="0">Todo</option>
                                                @for($i = 1; $i < 11; $i++)
                                                <option value="{{ $i }}">{{ $i }} pagos</option>
                                                @endfor
                                            </select>
                                        </th>
                                        <th class="text-danger">
                                            <input type="text" id="fl-avance" class="form-control form-control-sm fl-texto" data-idx="11" placeholder="% avance">
                                        </th>
                                        <th class="text-danger">Indicador</th>
                                        <th class="text-danger">
                                            <input type="text" id="fl-diasvc" class="form-control form-control-sm fl-texto" data-idx="13" placeholder="Días vence">
                                        </th>
                                        <th class="text-danger">
                                            <input type="text" id="fl-estado" class="form-control form-control-sm fl-texto" data-idx="14" placeholder="Estado">
                                        </th>
                                        <th class="text-danger">
                                            <input type="text" id="fl-responsable" class="form-control form-control-sm fl-texto" data-idx="15" placeholder="Responsable">
                                        </th>
                                        <th class="text-danger">
                                            <input type="text" id="fl-observaciones" class="form-control form-control-sm fl-texto" data-idx="16" placeholder="Observaciones">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <!-- -->
                            <nav aria-label="Navegador">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <!--li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li-->
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
        <div id="modal-actualiza-hito" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambio de responsable</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col" style="border-right:1px solid #d8d8d8">
                                <p class="mb-2 text-danger text-title">Atributos del proceso</p>
                                <form id="form-actualiza-hito">
                                    <input type="hidden" id="mah-hito" name="hito">
                                    <input type="hidden" id="mah-proyecto" name="proyecto">
                                    <input type="hidden" id="mah-detalle" name="detalle">
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <label class="mb-1 text-danger" for="mah-fin">Fecha límite de ejecución</label>
                                            <input type="text" id="mah-fin" name="fin" class="form-control form-control-sm datepicker" placeholder="yyyy-mm-dd">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-10">
                                            <label class="mb-1 text-danger" for="mah-documentacion">Control documentario</label>
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
                                    <div class="row mb-2">
                                        <div class="col-10">
                                            <label class="mb-1 text-danger" for="mah-proceso">Control proceso</label>
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
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label class="mb-1 text-danger" for="mah-observaciones">Observaciones</label>
                                            <textarea id="mah-observaciones" name="observaciones" class="form-control form-control-sm" style="resize:none"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="col-atributos" class="col"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Actualizar hito</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- -->
        <div id="modal-edicion" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-nuevo" class="container">
                            <input type="hidden" id="np-proyecto">
                            <div class="row">
                                <div class="col">
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
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- -->
        <div id="modal-responsable" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-nuevo" class="container">
                            <input type="hidden" id="sr-proyecto">
                            <input type="hidden" id="sr-hito">
                            <input type="hidden" id="sr-detalle">
                            <div class="row">
                                <div class="col">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label class="mb-1" for="sr-responsable">Nuevo responsable</label>
                                            <select class="form-control" id="sr-responsable">
                                                <option value="-1" selected disabled>- Seleccione -</option>
                                                @foreach($responsables as $responsable)
                                                <option value="{{ $responsable->value }}" data-usuario="{{ $responsable->usuario }}">{{ $responsable->text }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
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
            var ls_proyectos = {!! json_encode($proyectos) !!};
            var ls_atributos;
            var curr_catalogo = 0;
            //
            function ListarProyectos() {
                var tbody = $("#grid-proyectos tbody");
                tbody.empty();
                for(var i in ls_proyectos) {
                    var iproyecto = ls_proyectos[i];
                    if(curr_catalogo == 0 || iproyecto.catalogo == curr_catalogo) {
                        tbody.append(
                            $("<tr/>").attr({
                                "id": "tr-" + iproyecto.id,
                                "data-visible": "S",
                                "data-catalogo": iproyecto.catalogo
                            }).addClass("tr-proyecto").append(
                                $("<td/>").append(
                                    $("<a/>").attr({
                                        "href": "#",
                                        "id": "a-" + iproyecto.id,
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
                                $("<td/>").html(iproyecto.contratista)
                            ).append(
                                $("<td/>").html(iproyecto.ndias).addClass("text-right")
                            ).append(
                                $("<td/>").html(iproyecto.fentrega)
                            ).append(
                                $("<td/>").html(parseFloat(iproyecto.valor).toLocaleString("en-US", { minimumFractionDigits:2, maximumFractionDigits:2 })).addClass("text-right")
                            ).append(
                                $("<td/>").html(iproyecto.armadas).addClass("text-right")
                            ).append(
                                $("<td/>").html(parseFloat(iproyecto.avance).toFixed(2) + "%").addClass("text-right text-danger").css("font-size","1.1em").css("font-weight","bold")
                            ).append(
                                $("<td/>").append(
                                    $("<a/>").attr("href","javascript:void(0)").addClass("btn btn-indicador btn-xs btn-" + iproyecto.indicador)
                                ).addClass("text-center")
                            ).append(
                                $("<td/>").html(iproyecto.diasvence).addClass("text-center text-danger").css("font-size","1.1em").css("font-weight","bold")
                            ).append(
                                $("<td/>").html(iproyecto.estado)
                            ).append(
                                $("<td/>").html(iproyecto.responsable)
                            ).append(
                                $("<td/>").html(iproyecto.hobservaciones)
                            )
                        ).append(
                            $("<tr/>").hide()
                        ).append(
                            $("<tr/>").append(
                                $("<td/>")
                            ).append(
                                $("<td/>").attr("colspan", 18).append(
                                    $("<div/>").attr("id", "dv-" + iproyecto.id)
                                )
                            ).hide()
                        );
                    }
                }
            }
            function ListarAtributos() {
                var form = $("<form/>");
                for(var i in ls_atributos) {
                    var iAtributo = ls_atributos[i];
                    var tipo = parseInt(iAtributo.tipo);
                    switch(tipo) {
                        case 1:
                        case 2:
                            form.append(
                                $("<div/>").addClass("row mb-2").append(
                                    $("<div/>").addClass("col-6").append(
                                        $("<label/>").addClass("mb-1 text-primary").attr("for","attr-" + iAtributo.campo).html(iAtributo.nombre)
                                    ).append(
                                        $("<input/>").attr({
                                            "type": "number",
                                            "id": "attr-" + iAtributo.campo,
                                            "placeholder": "Ingrese " + iAtributo.nombre.toLowerCase(),
                                            "data-proyecto": iAtributo.proyecto,
                                            "data-hito": iAtributo.hito,
                                            "data-campo": iAtributo.campo,
                                            "data-detalle": iAtributo.detalle
                                        }).addClass("form-control form-control-sm form-atributo").val(iAtributo.value)
                                    )
                                )
                            );
                            break;
                        case 3:
                            form.append(
                                $("<div/>").addClass("row mb-2").append(
                                    $("<div/>").addClass("col").append(
                                        $("<label/>").addClass("mb-1 text-primary").attr("for","attr-" + iAtributo.campo).html(iAtributo.nombre)
                                    ).append(
                                        $("<input/>").attr({
                                            "type": "text",
                                            "id": "attr-" + iAtributo.campo,
                                            "placeholder": "Ingrese " + iAtributo.nombre.toLowerCase(),
                                            "data-proyecto": iAtributo.proyecto,
                                            "data-hito": iAtributo.hito,
                                            "data-campo": iAtributo.campo,
                                            "data-detalle": iAtributo.detalle
                                        }).addClass("form-control form-control-sm form-atributo").val(iAtributo.value)
                                    )
                                )
                            );
                            break;
                        case 4:
                            form.append(
                                $("<div/>").addClass("row mb-2").append(
                                    $("<div/>").addClass("col-6").append(
                                        $("<label/>").addClass("mb-1 text-primary").attr("for","attr-" + iAtributo.campo).html(iAtributo.nombre)
                                    ).append(
                                        $("<input/>").attr({
                                            "type": "text",
                                            "id": "attr-" + iAtributo.campo,
                                            "placeholder": "yyyy-mm-dd",
                                            "data-proyecto": iAtributo.proyecto,
                                            "data-hito": iAtributo.hito,
                                            "data-campo": iAtributo.campo,
                                            "data-detalle": iAtributo.detalle
                                        }).addClass("form-control form-control-sm form-atributo datepicker").datepicker({
                                            autoclose: true,
                                            daysOfWeekHighlighted: [0,6],
                                            format: 'yyyy-mm-dd',
                                            language: 'es',
                                            startView: 0,
                                            todayHighlight: true,
                                            zIndexOffset: 1030
                                        }).val(iAtributo.value)
                                    )
                                )
                            );
                            break;
                        case 5:
                            form.append(
                                $("<div/>").addClass("row mb-2").append(
                                    $("<div/>").addClass("col-6").append(
                                        $("<label/>").addClass("mb-1 text-primary").attr("for","attr-" + iAtributo.campo).html(iAtributo.nombre)
                                    ).append(
                                        $("<input/>").attr({
                                            "type": "text",
                                            "id": "attr-" + iAtributo.campo,
                                            "placeholder": "Ingrese " + iAtributo.nombre.toLowerCase(),
                                            "maxlength": 1,
                                            "data-proyecto": iAtributo.proyecto,
                                            "data-hito": iAtributo.hito,
                                            "data-campo": iAtributo.campo,
                                            "data-detalle": iAtributo.detalle
                                        }).addClass("form-control form-control-sm form-atributo").val(iAtributo.value)
                                    )
                                )
                            );
                            break;
                        case 6:
                            form.append(
                                $("<div/>").addClass("row mb-2").append(
                                    $("<div/>").addClass("col-6").append(
                                        $("<label/>").addClass("mb-1 text-primary").attr("for","attr-" + iAtributo.campo)
                                    ).append(
                                        $("<input/>").attr({
                                            "type": "checkbox",
                                            "id": "attr-" + iAtributo.campo,
                                            "data-proyecto": iAtributo.proyecto,
                                            "data-hito": iAtributo.hito,
                                            "data-campo": iAtributo.campo,
                                            "data-detalle": iAtributo.detalle
                                        }).addClass("form-control form-control-sm form-atributo").prop("checked", iAtributo.value == "S")
                                    )
                                )
                            );
                            break;
                        default: break;
                    }
                }
                $("#col-atributos").empty().append(
                    $("<p/>").addClass("text-primary mb-2 text-title").html("Atributos del hito")
                ).append(form);
            }
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
                                    $("<td/>").html(iHito.avance + "%").addClass("text-right text-danger").css("font-size","1.1em").css("font-weight","bold")
                                ).append(
                                    $("<td/>").append(
                                        $("<a/>").attr("href","javascript:void(0)").addClass("btn btn-indicador btn-xs btn-" + iHito.indicador)
                                    ).addClass("text-center")
                                ).append(
                                    $("<td/>").html(iHito.fin).addClass("text-right")
                                ).append(
                                    $("<td/>").html(iHito.diasvcto).addClass("text-center")
                                ).append(
                                    $("<td/>").html(iHito.responsable)
                                ).append(
                                    $("<td/>").html(iHito.nombre)
                                ).append(
                                    $("<td/>").append(
                                        $("<a/>").append(
                                            $("<i/>").addClass("fas fa-exchange-alt")
                                        ).attr({
                                            "href": "#",
                                            "data-proyecto": id,
                                            "data-hito": iHito.hid,
                                            "data-id": iHito.id,
                                            "data-toggle": "modal",
                                            "data-target": "#modal-responsable"
                                        }).addClass("btn btn-primary btn-xs text-light")
                                    )
                                ).append(
                                    $("<td/>").html(iHito.edocumentacion).css("font-weight","bold")
                                ).append(
                                    $("<td/>").html(iHito.eproceso).css("font-weight","bold")
                                ).append(
                                    $("<td/>").html(iHito.observaciones).css("font-weight","bold")
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
                                        $("<th/>").html("Fecha límite")
                                    ).append(
                                        $("<th/>").html("Días vcto.")
                                    ).append(
                                        $("<th/>").html("Responsable")
                                    ).append(
                                        $("<th/>").html("")
                                    ).append(
                                        $("<th/>").attr("width","1%").html("")
                                    ).append(
                                        $("<th/>").html("Control documentario")
                                    ).append(
                                        $("<th/>").html("Control del proceso")
                                    ).append(
                                        $("<th/>").html("Observaciones")
                                    )
                                ).addClass("thead-dark")
                            ).append(tbody).addClass("table table-sm table-striped")
                        ).append(
                            $("<div/>").append(
                                $("<div/>").append(
                                    $("<a/>").attr({
                                        "href": "#",
                                        "data-toggle": "modal",
                                        "data-target": "#modal-edicion",
                                        "data-id": id
                                    }).append(
                                        $("<i/>").addClass("fas fa-edit")
                                    ).append("&nbsp;Editar proyecto").addClass("btn btn-sm btn-warning mb-2")
                                ).addClass("col")
                            ).addClass("row")
                        );
                    }
                    else alert(response.msg);
                }, "json");
            }
            function ModalActualizaHitoOnShow(args) {
                var dataset = args.relatedTarget.dataset;
                //
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
                        document.getElementById("mah-fin").value = estado.fin;
                        document.getElementById("mah-observaciones").value = estado.observaciones;
                        $("#mah-documentacion option[value=" + estado.edocumentacion + "]").prop("selected", true);
                        $("#mah-proceso option[value=" + estado.eproceso + "]").prop("selected", true);
                        //escribe el formulario de atributos
                        ls_atributos = response.data.atributos;
                        ListarAtributos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function ActualizarHito(event) {
                event.preventDefault();
                var atributos = [];
                var extras = $(".form-atributo");
                $.each(extras, function() {
                    var ipextra = $(this);
                    atributos.push({
                        aproyecto: ipextra.data("proyecto"),
                        ahito: ipextra.data("hito"),
                        acampo: ipextra.data("campo"),
                        adetalle: ipextra.data("detalle"),
                        avalor: ipextra.val()
                    });
                });
                var p = {
                    _token: "{{ csrf_token() }}",
                    hito: document.getElementById("mah-hito").value,
                    proyecto: document.getElementById("mah-proyecto").value,
                    detalle: document.getElementById("mah-detalle").value,
                    fin: document.getElementById("mah-fin").value,
                    documentacion: document.getElementById("mah-documentacion").value,
                    proceso: document.getElementById("mah-proceso").value,
                    observaciones: document.getElementById("mah-observaciones").value,
                    atributos: atributos
                };
                $.post("{{ url('ajax/control/upd-estado-hito') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_proyectos = response.data.proyectos;
                        $("#modal-actualiza-hito").modal("hide");
                        ListarProyectos();
                        $("#a-" + p.proyecto).trigger("click");
                    }
                    else alert(response.msg);
                }, "json");
            }
            function flAtributoOnChange(event) {
                var tipo = $("#fl-atributo option:selected").data("tipo");
                $("#fl-busca").show();
                var input = $("<input/>").attr({
                    "id": "fl-value",
                    "placeholder": "Ingrese valor",
                    "data-tipo": tipo
                }).addClass("form-control form-control-sm");
                switch(tipo) {
                    case 1:
                    case 2:
                        input.attr("type", "number").width(80);
                        break;
                    case 0:
                    case 3:
                        input.attr("type", "text").width(320);
                        break;
                    case 4:
                        input.attr("type", "text").datepicker({
                            autoclose: true,
                            daysOfWeekHighlighted: [0,6],
                            format: 'yyyy-mm-dd',
                            language: 'es',
                            startView: 0,
                            todayHighlight: true,
                            zIndexOffset: 1030
                        }).attr("placeholder", "yyyy-mm-dd").width(100);
                        break;
                    case 5:
                        input.attr({
                            "type": "text",
                            "maxlength": 1
                        }).width(60);
                        break;
                    case 6:
                        input.attr({
                            "type": "text",
                            "maxlength": 1
                        }).width(40);
                        break;
                    default: break;
                }
                $("#fl-input").empty().append(input);
            }
            function BuscarAtributo(event) {
                event.preventDefault();
                var texto = document.getElementById("fl-value").value;
                if(texto != "") {
                    var p = {
                        _token: "{{ csrf_token() }}",
                        tipo: $("#fl-value").data("tipo"),
                        texto: texto,
                        campo: document.getElementById("fl-atributo").value
                    };
                    $.post("{{ url('ajax/control/fl-busca-campo') }}", p, function(response) {
                        $("#grid-proyectos tbody tr").hide();
                        if(response.state == "success") {
                            var filas = response.data.proyectos;
                            for(var i in filas) {
                                var ifila = filas[i];
                                $("#tr-" + ifila.id).show();
                            }
                        }
                        else alert(response.msg);
                    }, "json");
                }
                else $("#grid-proyectos tbody .tr-proyecto").show();
            }
            function btnCatalogoOnClick(event) {
                event.preventDefault();
                var a = $(this);
                $(".btn-catalogo.active").removeClass("active");
                a.addClass("active");
                curr_catalogo = a.data("tipo");
                ListarProyectos();
            }
            function BtnFiltrosOnClick(event) {
                event.preventDefault();
                $(".tr-header").toggle();
            }
            function BuscaTxt(txt, col) {
                var filas = $(".tr-proyecto");
                $.each(filas, function() {
                    var fila = $(this);
                    var vcell = fila.children("td").eq(col).html().toLowerCase();
                    if(vcell.indexOf(txt) > -1) fila.show();
                    else fila.hide();
                });
            }
            function BuscaCombo(txt, col) {
                var filas = $(".tr-proyecto");
                $.each(filas, function() {
                    var fila = $(this);
                    var vcell = fila.children("td").eq(col).html();
                    if(txt == "0" || txt == 0 || vcell == txt) fila.show();
                    else fila.hide();
                });
            }
            function ActualizarProyecto(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    id: document.getElementById("np-proyecto").value,
                    expediente: document.getElementById("np-expediente").value,
                    frecepcion: document.getElementById("np-frecepcion").value,
                    descripcion: document.getElementById("np-descripcion").value,
                    plazo: document.getElementById("np-plazo").value,
                    contratista: document.getElementById("np-contratista").value,
                    valor: document.getElementById("np-valor").value
                };
                $.post("{{ url('ajax/control/upd-proyecto') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_proyectos = response.data.proyectos;
                        alert("Proyecto actualizado!");
                        $("#modal-edicion").modal("hide");
                        ListarProyectos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function ModalEdicionOnShow(event) {
                var dataset = event.relatedTarget.dataset;
                var id = dataset.id;
                var p = {
                    _token: "{{ csrf_token() }}",
                    id: id
                };
                $.post("{{ url('ajax/control/dt-proyecto') }}", p, function(response) {
                    if(response.state == "success") {
                        var proyecto = response.data.proyecto;
                        $("#modal-edicion .modal-title").html(proyecto.descripcion);
                        document.getElementById("np-proyecto").value = id;
                        document.getElementById("np-expediente").value = proyecto.expediente;
                        document.getElementById("np-frecepcion").value = proyecto.frecepcion;
                        document.getElementById("np-descripcion").value = proyecto.descripcion;
                        document.getElementById("np-plazo").value = proyecto.plazo;
                        document.getElementById("np-contratista").value = proyecto.contratista;
                        document.getElementById("np-valor").value = proyecto.valor;
                    }
                }, "json");
            }
            function ActualizarResponsable(event) {
                event.preventDefault();
                var p = {
                    _token: "{{ csrf_token() }}",
                    id: document.getElementById("sr-detalle").value,
                    proyecto: document.getElementById("sr-proyecto").value,
                    hito: document.getElementById("sr-hito").value,
                    responsable: document.getElementById("sr-responsable").value,
                    usuario: $("#sr-responsable option:selected").data("usuario")
                };
                $.post("{{ url('ajax/control/upd-responsable-proyecto') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_proyectos = response.data.proyectos;
                        alert("Responsable actualizado");
                        $("#modal-responsable").modal("hide");
                        ListarProyectos();
                    }
                    else alert(response.msg);
                }, "json");
            }
            function ModalResponsableOnShow(event) {
                var dataset = event.relatedTarget.dataset;
                $("#sr-responsable option[value=-1]").prop("selected", true);
                document.getElementById("sr-detalle").value = dataset.id;
                document.getElementById("sr-proyecto").value = dataset.proyecto;
                document.getElementById("sr-hito").value = dataset.hito;
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
                todayHighlight: true,
                zIndexOffset: 5000
            });
            //
            function FlKeyup(e) {
                var input = $(this);
                var col = input.data("idx");
                var txt = input.val().toLowerCase();
                BuscaTxt(txt, col);
            }
            function FlChange(e) {
                var select = $(this);
                var col = select.data("idx");
                var txt = select.val();
                BuscaCombo(txt, col);
            }
            //
            $("#modal-actualiza-hito .modal-footer .btn-primary").on("click", ActualizarHito);
            $("#fl-atributo option[value=-1]").prop("selected", true);
            $("#fl-atributo").on("change", flAtributoOnChange);
            $("#fl-busca").on("click", BuscarAtributo);
            $(".btn-catalogo").on("click", btnCatalogoOnClick);
            $(".btn-filtros").on("click", BtnFiltrosOnClick);
            //filtros
            $(".fl-select").on("change", FlChange);
            $(".fl-texto").on("keyup", FlKeyup);
            //modal
            $("#modal-edicion").on("show.bs.modal", ModalEdicionOnShow);
            $("#modal-edicion .modal-footer .btn-primary").on("click", ActualizarProyecto);
            $("#modal-responsable").on("show.bs.modal", ModalResponsableOnShow);
            $("#modal-responsable .modal-footer .btn-primary").on("click", ActualizarResponsable);
            //$("#fl-recepcion").on("keypress", )
            //$("#fl-entrega").on("keypress", FlE);
            var element = document.getElementById('np-expediente');
            var maskOptions = {
                mask: '00-0000000-000'
            };
            var mask = new IMask(element, maskOptions);
        </script>
    </body>
</html>