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
                        <div class="col">
                            <table id="grid-proyectos" class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo</th>
                                        <th>Fecha registro</th>
                                        <th>Descripción</th>
                                        <th>N° Expediente</th>
                                        <th>Hoja trámite</th>
                                        <th>Área usuaria</th>
                                        <th>Valor</th>
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
                                        <a class="page-link bg-success text-light" data-toggle="modal" data-target="#modal-proyecto" href="#"><i class="fas fa-plus"></i> Nuevo proyecto</a>
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
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript">
            var ls_proyectos = {!! json_encode($proyectos) !!};
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
                                    "data-id": iproyecto.id
                                }).addClass("btn btn-xs btn-primary").html(iproyecto.id)
                            )
                        ).append(
                            $("<td/>").html(iproyecto.tipo)
                        ).append(
                            $("<td/>").html(iproyecto.fregistro)
                        ).append(
                            $("<td/>").html(iproyecto.proyecto)
                        ).append(
                            $("<td/>").html(iproyecto.expediente)
                        ).append(
                            $("<td/>").html(iproyecto.hojatramite)
                        ).append(
                            $("<td/>").html(iproyecto.areausr)
                        ).append(
                            $("<td/>").html(iproyecto.valor)
                        ).append(
                            $("<td/>").html(iproyecto.observaciones)
                        )
                    );
                }
            }
            //
            ListarProyectos();
        </script>
    </body>
</html>