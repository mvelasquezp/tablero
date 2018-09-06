<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <style type="text/css">
            .btn-xs{
                /**/
            }
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
                                    <input type="text" class="form-control form-control-sm mr-sm-2" id="tb-buscar" placeholder="¿Qué desea buscar?">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Buscar</button>
                                    <a href="#" class="btn btn-sm btn-success ml-5" data-toggle="modal" data-target="#modal-registro"><i class="fas fa-user-plus"></i> Nuevo</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <div id="modal-registro" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form id="form-registro">
                                <div class="row">
                                    <div class="col">
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-tpdoc">Tipo documento</label>
                                                <select class="form-control form-control-sm" id="reg-tpdoc" name="tpdoc">
                                                    <option value="DNI">Documento Nacional de Identidad (DNI)</option>
                                                    <option value="CE">Carné de extranjería (CE)</option>
                                                    <option value="OT">Otros documentos de identidad</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="reg-dni">Nro. documento</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-dni" name="dni" placeholder="Ingrese DNI / CE / OT">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-apepat">Ape. paterno</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-apepat" name="apepat" placeholder="Ingrese apellido">
                                            </div>
                                            <div class="col">
                                                <label for="reg-apemat">Ape. materno</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-apemat" name="apemat" placeholder="Ingrese apellido">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-nombres">Nombres</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-nombres" name="nombres" placeholder="Ingrese los nombres">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="reg-mail">e-mail</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-mail" name="mail" placeholder="Ingrese e-mail">
                                            </div>
                                            <div class="col">
                                                <label for="reg-telefono">Teléfono</label>
                                                <input type="text" class="form-control form-control-sm" id="reg-telefono" name="telefono" placeholder="Ingrese nro. teléfono">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="text-secondary">Fotografía</p>
                                        <div class="form-group">
                                            <label for="reg-foto">Seleccione una imagen</label>
                                            <input type="file" class="form-control-file" id="reg-foto" name="foto">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-sv-registro" onclick="$('#form-registro').submit()"><i class="far fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        @include('common.scripts')
        <script type="text/javascript">
            $("#form-registro").on("submit", function(event) {
                event.preventDefault();
                $("#btn-sv-registro").hide();
                $.ajax({
                    url: "{{ url('ajax/registros/sv-usuario') }}",
                    type: "post",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        location.reload();
                    }
                });
            });
        </script>
    </body>
</html>