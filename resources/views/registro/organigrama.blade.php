<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/getorgchart/getorgchart.css') }}">
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
                        <div class="col-4">
                            <div class="alert alert-secondary">
                                <span class="alert-xs"><tag id="count-puestos">-</tag> puesto(s) registrado(s)</span>&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-xs btn-primary text-light" data-toggle="modal" data-target="#modal-registro"><i class="fas fa-user-plus"></i> Nuevo</a>
                            </div>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Oficina</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="puestos-tbody"></tbody>
                            </table>
                        </div>
                        <div class="col-8">
                            <div id="organigrama" style="height:480px;">
                                <p>Por favor, espere</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <div id="modal-registro" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo puesto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form id="form-registro">
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="reg-nombre">Nombre del puesto</label>
                                        <input type="text" class="form-control form-control-sm" id="reg-nombre" name="puesto" placeholder="Ingrese el nombre">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="reg-superior">Cargo superior</label>
                                        <select id="reg-superior" class="form-control form-control-sm">
                                            <option value="0">- Seleccione -</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="reg-oficina">Oficina</label>
                                        <select id="reg-oficina" class="form-control form-control-sm">
                                            <option value="0">- Seleccione -</option>
                                        </select>
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
        <!-- modals -->
        <div id="modal-edicion" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo puesto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form id="form-edicion">
                                <input type="hidden" id="ed-id">
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="ed-nombre">Nombre del puesto</label>
                                        <input type="text" class="form-control form-control-sm" id="ed-nombre" name="puesto" placeholder="Ingrese el nombre">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="ed-superior">Cargo superior</label>
                                        <select id="ed-superior" class="form-control form-control-sm">
                                            <option value="0">- Seleccione -</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="ed-oficina">Oficina</label>
                                        <select id="ed-oficina" class="form-control form-control-sm">
                                            <option value="0">- Seleccione -</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="ed-vigencia">¿Retirar?</label>
                                        <select id="ed-vigencia" class="form-control form-control-sm">
                                            <option value="Vigente">No</option>
                                            <option value="Retirado">Si</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-ed-edicion" onclick="$('#form-edicion').submit()"><i class="far fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript" src="{{ asset('vendor/getorgchart/getorgchart.js') }}"></script>
        <script type="text/javascript">
            var ls_puestos;
            var ls_ancestros = {!! json_encode($ancestros) !!};
            var ls_oficinas = {!! json_encode($oficinas) !!};
            var dataset = [];

            CargarAncestros();
            CargarOficinas();
            CargarDatosPuestos();
            
            function CargarDatosPuestos() {
                var p = { _token:"{{ csrf_token() }}" };
                $.post("{{ url('ajax/registros/ls-puestos') }}", p, function(response) {
                    ls_puestos = response;
                    TrazarTabla();
                    var puestosDiv = document.getElementById("organigrama");
                    var orgChart = new getOrgChart(puestosDiv, {
                        primaryFields: ["name", "title", "phone", "mail"],
                        //photoFields: ["image"],
                        enableEdit: false,
                        enableMove: true,
                        clickNodeEvent: (sender, args) => {
                            var p = {
                                _token: "{{ csrf_token() }}",
                                compid: args.node.id
                            };
                            $("#modal-edicion").modal("show");
                            $.post("{{ url('ajax/registros/dt-puesto') }}", p, function(response) {
                                if(response.state == "success") {
                                    var pdata = response.data.puesto;
                                    document.getElementById("ed-id").value = pdata.id;
                                    document.getElementById("ed-nombre").value = pdata.nombre;
                                    $("#ed-vigencia option[value=" + pdata.vigencia + "]").prop("selected", true);
                                    $("#ed-oficina option[value=" + pdata.oficina + "]").prop("selected", true);
                                    $("#ed-superior option[value=" + pdata.superior + "]").prop("selected", true);
                                }
                                else alert(response.msg);
                            }, "json");
                            //oli
                        },
                        expandToLevel: 100,
                        layout: getOrgChart.MIXED_HIERARCHY_RIGHT_LINKS,
                        dataSource: ls_puestos
                    });
                }, "json");
            }

            function TrazarTabla() {
                document.getElementById("count-puestos").innerHTML = ls_puestos.length;
                var tbody = $("#puestos-tbody");
                tbody.empty();
                for(var i in ls_puestos) {
                    var ipuesto = ls_puestos[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").append(
                                $("<p/>").addClass("text-dark mb-0").html(ipuesto.name)
                            ).append(
                                $("<p/>").addClass("text-secondary mb-0").html(ipuesto.title)
                            )
                        ).append(
                            $("<td/>").html(ipuesto.oficina)
                        ).append(
                            $("<td/>").append(
                                $("<a/>").addClass("btn btn-xs btn-success").attr({
                                    href: "#"
                                }).html(ipuesto.vigencia)
                            )
                        )
                    );
                }
            }

            function CargarAncestros() {
                $("#reg-superior").empty().append(
                    $("<option/>").val(0).html("- Seleccione -")
                );
                $("#ed-superior").empty().append(
                    $("<option/>").val(0).html("- Seleccione -")
                );
                for(var i in ls_ancestros) {
                    var iancestro = ls_ancestros[i];
                    $("#reg-superior").append(
                        $("<option/>").val(iancestro.value).html(iancestro.text)
                    );
                    $("#ed-superior").append(
                        $("<option/>").val(iancestro.value).html(iancestro.text)
                    );
                }
                $("#reg-superior option[value=0]").prop("selected", true);
                $("#ed-superior option[value=0]").prop("selected", true);
            }

            function CargarOficinas() {
                $("#reg-oficina").empty().append(
                    $("<option/>").val(0).html("- Seleccione -")
                );
                $("#ed-oficina").empty().append(
                    $("<option/>").val(0).html("- Seleccione -")
                );
                for(var i in ls_oficinas) {
                    var ioficina = ls_oficinas[i];
                    $("#reg-oficina").append(
                        $("<option/>").val(ioficina.value).html(ioficina.text)
                    );
                    $("#ed-oficina").append(
                        $("<option/>").val(ioficina.value).html(ioficina.text)
                    );
                }
                $("#reg-oficina option[value=0]").prop("selected", true);
                $("#ed-oficina option[value=0]").prop("selected", true);
            }

            $("#modal-registro").on("show.bs.modal", function(args) {
                document.getElementById("reg-nombre").value = "";
                $("#reg-superior option[value=0]").prop("selected", true);
                $("#reg-oficina option[value=0]").prop("selected", true);
            });

            $("#form-registro").on("submit", function(event) {
                event.preventDefault();
                $("#btn-sv-registro").hide();
                var isuperior = document.getElementById("reg-superior").value;
                var ioficina = document.getElementById("reg-oficina").value;
                var p = {
                    _token: "{{ csrf_token() }}",
                    nombre: document.getElementById("reg-nombre").value
                };
                if(isuperior != 0) p.ancestro = isuperior;
                if(ioficina != 0) p.oficina = ioficina;
                $.post("{{ url('ajax/registros/sv-puesto') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_puestos = response.data.puestos;
                        ls_ancestros = response.data.ancestros;
                        ls_oficinas = response.data.oficinas;
                        CargarDatosPuestos();
                        CargarAncestros();
                        CargarOficinas();
                        $("#modal-registro").modal("hide");
                    }
                    else alert(response.msg);
                    $("#btn-sv-registro").show();
                }, "json");
            });
            $("#form-edicion").on("submit", function(event) {
                event.preventDefault();
                $("#btn-ed-edicion").hide();
                var isuperior = document.getElementById("ed-superior").value;
                var ioficina = document.getElementById("ed-oficina").value;
                var p = {
                    _token: "{{ csrf_token() }}",
                    id: document.getElementById("ed-id").value,
                    nombre: document.getElementById("ed-nombre").value,
                    vigencia: document.getElementById("ed-vigencia").value
                };
                if(isuperior != 0) p.ancestro = isuperior;
                if(ioficina != 0) p.oficina = ioficina;
                $.post("{{ url('ajax/registros/ed-puesto') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_puestos = response.data.puestos;
                        ls_ancestros = response.data.ancestros;
                        ls_oficinas = response.data.oficinas;
                        CargarDatosPuestos();
                        CargarAncestros();
                        CargarOficinas();
                        $("#modal-edicion").modal("hide");
                    }
                    else alert(response.msg);
                    $("#btn-ed-edicion").show();
                }, "json");
            });
        </script>
    </body>
</html>