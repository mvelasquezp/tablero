<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/getorgchart/getorgchart.css') }}">
        <style type="text/css">
            #table-menus {display:none}
            table td, table th{vertical-align:middle !important}
            .ch-acceso{display:none}
        </style>
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <div class="list-group">
                                @foreach($usuarios as $iusuario)
                                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start" data-id="{{ $iusuario->id }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $iusuario->nombres }} {{ $iusuario->apepat }} {{ $iusuario->apemat }}</h5>
                                        <small>{{ $iusuario->dni }}</small>
                                    </div>
                                    <small>{{ $iusuario->puesto }}</small>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-8">
                            <div id="alert-menus" class="alert alert-secondary">
                                <p class="mb-0">Seleccione un usuario para asignar los permisos</p>
                            </div>
                            <tag id="table-menus">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Menú</th>
                                            <th>Item</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($opciones as $opcion)
                                        <tr>
                                            <td>{{ $opcion->menu }}</td>
                                            <td>{{ $opcion->item }}</td>
                                            <td>
                                                <label class="btn btn-xs btn-danger mb-0" for="ch-{{ $opcion->id }}">Sin acceso</label>
                                                <input class="ch-acceso" type="checkbox" id="ch-{{ $opcion->id }}" value="{{ $opcion->id }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <a href="#" class="btn btn-sm btn-primary text-light" data-toggle="modal" data-target="#modal-permisos"><i class="fas fa-save"></i> Guardar los cambios</a>
                            </tag>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <div id="modal-permisos" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Asignar permisos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Se asignarán los permisos seleccionados. ¿Continuar?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"><i class="fas fa-save"></i> Asignar los permisos</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript" src="{{ asset('vendor/getorgchart/getorgchart.js') }}"></script>
        <script type="text/javascript">
            //
            function itemOnClick(event) {
                event.preventDefault();
                var a = $(this);
                $("#alert-menus").hide();
                $("#table-menus").show();
                $(".list-group-item.active").removeClass("active");
                $(".ch-acceso").prop("checked", true).trigger("click");
                a.addClass("active");
                var p = {
                    _token: "{{ csrf_token() }}",
                    usuario: a.data("id")
                };
                $.post("{{ url('ajax/registros/ls-permisos') }}", p, function(response) {
                    if(response.state == "success") {
                        var ls_accesos = response.data.accesos;
                        for(var i in ls_accesos) {
                            var iAcceso = ls_accesos[i];
                            $("#ch-" + iAcceso.id).trigger("click");
                        }
                    }
                }, "json");
            }
            function chAccesoOnChange(event) {
                var chbox = $(this);
                if(chbox.prop("checked")) chbox.prev().removeClass("btn-danger").addClass("btn-success").html("Acceso");
                else chbox.prev().removeClass("btn-success").addClass("btn-danger").html("Sin acceso");
            }
            function GuardarPermisos(event) {
                event.preventDefault();
                var a = $(this);
                a.hide();
                var chbs = $(".ch-acceso:checked");
                var accesos = [];
                $.each(chbs, function() {
                    accesos.push($(this).val());
                });
                var p = {
                    _token: "{{ csrf_token() }}",
                    usuario: $(".list-group-item.active").data("id"),
                    accesos: accesos
                };
                $.post("{{ url('ajax/registros/sv-permisos') }}", p, function(response) {
                    if(response.state == "success") {
                        alert("Permisos asignados");
                        $("#modal-permisos").modal("hide");
                    }
                    else alert(response.msg);
                    a.show();
                }, "json");
            }
            //
            $(".ch-acceso").prop("checked", false).on("change", chAccesoOnChange);
            $(".list-group-item").on("click", itemOnClick);
            $("#modal-permisos .modal-footer .btn-primary").on("click", GuardarPermisos);
        </script>
    </body>
</html>