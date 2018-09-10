<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <style type="text/css">
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
                                            <th>ID</th>
                                            <th>Proyecto</th>
                                            <th>Peso</th>
                                            <th>Responsable</th>
                                            <th>% Avance</th>
                                            <th>% Acumulado</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
            function FormTipoOnSubmit(event) {
                event.preventDefault();
                alert("select!");
            }
            //
            $("#reg-tipo option[value=0]").prop("selected", true);
            $("#form-tipo").on("submit", FormTipoOnSubmit);
        </script>
    </body>
</html>