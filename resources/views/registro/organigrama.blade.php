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
                                <span class="alert-xs"><tag id="count-puestos">-</tag> puesto(s) registrado(s)</span>&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-registro"><i class="fas fa-user-plus"></i> Nuevo</a>
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
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript" src="{{ asset('vendor/getorgchart/getorgchart.js') }}"></script>
        <script type="text/javascript">
            var ls_puestos;
            var ls_ancestros = {!! json_encode($ancestros) !!};
            var ls_oficinas = {!! json_encode($oficinas) !!};
            var dataset = [
                { id: 1, parentId: null, name: "Amber McKenzie", title: "CEO", phone: "678-772-470", mail: "lemmons@jourrapide.com", adress: "Atlanta, GA 30303", image: "images/f-1.jpg" },
                { id: 2, parentId: 1, name: "Ava Field", title: "Paper goods machine setter", phone: "937-912-4971", mail: "anderson@jourrapide.com", image: "images/f-2.jpg" },
                { id: 3, parentId: 1, name: "Evie Johnson", title: "Employer relations representative", phone: "314-722-6164", mail: "thornton@armyspy.com", image: "images/f-3.jpg" },
                { id: 4, parentId: 2, name: "Paul Shetler", title: "Teaching assistant", phone: "330-263-6439", mail: "shetler@rhyta.com", image: "images/f-4.jpg" },
                { id: 5, parentId: 2, name: "Rebecca Francis", title: "Welding machine setter", phone: "408-460-0589", image: "images/f-5.jpg" },
                { id: 6, parentId: 2, name: "Rebecca Randall", title: "Optometrist", phone: "801-920-9842", mail: "JasonWGoodman@armyspy.com", image: "images/f-6.jpg" },
                { id: 7, parentId: 2, name: "Riley Bray", title: "Structural metal fabricator", phone: "479-359-2159", image: "images/f-12.jpg" },
                { id: 8, parentId: 3, name: "Spencer May", title: "System operator", phone: "Conservation scientist", mail: "hodges@teleworm.us", image: "images/f-7.jpg" },
                { id: 9, parentId: 3, name: "Max Ford", title: "Budget manager", phone: "989-474-8325", mail: "hunter@teleworm.us", image: "images/f-8.jpg" },
                { id: 10, parentId: 3, name: "Riley Bray", title: "Structural metal fabricator", phone: "479-359-2159", image: "images/f-15.jpg" },
                { id: 11, parentId: 4, name: "Callum Whitehouse", title: "Radar controller", phone: "847-474-8775", image: "images/f-10.jpg" },
                { id: 12, parentId: 4, name: "Max Ford", title: "Budget manager", phone: "989-474-8325", mail: "hunter@teleworm.us", image: "images/f-11.jpg" },
                { id: 13, parentId: 4, name: "Riley Bray", title: "Structural metal fabricator", phone: "479-359-2159", image: "images/f-12.jpg" },
                { id: 14, parentId: 5, name: "Callum Whitehouse", title: "Radar controller", phone: "847-474-8775", image: "images/f-13.jpg" },
                { id: 15, parentId: 5, name: "Max Ford", title: "Budget manager", phone: "989-474-8325", mail: "hunter@teleworm.us", image: "images/f-14.jpg" },
                { id: 16, parentId: 5, name: "Riley Bray", title: "Structural metal fabricator", phone: "479-359-2159", image: "images/f-15.jpg" },
                { id: 17, parentId: 6, name: "Callum Whitehouse", title: "Radar controller", phone: "847-474-8775", image: "images/f-16.jpg" },
                { id: 18, parentId: 6, name: "Max Ford", title: "Budget manager", phone: "989-474-8325", mail: "hunter@teleworm.us", image: "images/f-17.jpg" },
                { id: 19, parentId: 7, name: "Spencer May", title: "System operator", phone: "Conservation scientist", mail: "hodges@teleworm.us", image: "images/f-7.jpg" },
                { id: 20, parentId: 7, name: "Max Ford", title: "Budget manager", phone: "989-474-8325", mail: "hunter@teleworm.us", image: "images/f-8.jpg" },
                { id: 21, parentId: 7, name: "Riley Bray", title: "Structural metal fabricator", phone: "479-359-2159", image: "images/f-9.jpg" },
                { id: 22, parentId: 8, name: "Ava Field", title: "Paper goods machine setter", phone: "937-912-4971", mail: "anderson@jourrapide.com", image: "images/f-2.jpg" },
                { id: 23, parentId: 8, name: "Evie Johnson", title: "Employer relations representative", phone: "314-722-6164", mail: "thornton@armyspy.com", image: "images/f-3.jpg" }, 
                { id: 24, parentId: 9, name: "Callum Whitehouse", title: "Radar controller", phone: "847-474-8775", image: "images/f-13.jpg" },
                { id: 25, parentId: 9, name: "Max Ford", title: "Budget manager", phone: "989-474-8325", mail: "hunter@teleworm.us", image: "images/f-14.jpg" },
                { id: 26, parentId: 9, name: "Riley Bray", title: "Structural metal fabricator", phone: "479-359-2159", image: "images/f-15.jpg" },
                { id: 27, parentId: 10, name: "Callum Whitehouse", title: "Radar controller", phone: "847-474-8775", image: "images/f-13.jpg" },
                { id: 28, parentId: 10, name: "Max Ford", title: "Budget manager", phone: "989-474-8325", mail: "hunter@teleworm.us", image: "images/f-14.jpg" },
                { id: 29, parentId: 10, name: "Riley Bray", title: "Structural metal fabricator", phone: "479-359-2159", image: "images/f-15.jpg" }

            ];

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
                        enableMove: true,
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
                for(var i in ls_ancestros) {
                    var iancestro = ls_ancestros[i];
                    $("#reg-superior").append(
                        $("<option/>").val(iancestro.value).html(iancestro.text)
                    );
                }
                $("#reg-superior option[value=0]").prop("selected", true);
            }

            function CargarOficinas() {
                $("#reg-oficina").empty().append(
                    $("<option/>").val(0).html("- Seleccione -")
                );
                for(var i in ls_oficinas) {
                    var ioficina = ls_oficinas[i];
                    $("#reg-oficina").append(
                        $("<option/>").val(ioficina.value).html(ioficina.text)
                    );
                }
                $("#reg-oficina option[value=0]").prop("selected", true);
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
        </script>
    </body>
</html>