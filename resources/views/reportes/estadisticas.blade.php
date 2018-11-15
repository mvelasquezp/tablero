<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <style type="text/css">
            .dv-chart{height:320px;margin:0 auto;width:90%;}
        </style>
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <div class="container">
                    <div class="row mb-5">
                        <div class="col">
                            <p class="text-primary mb-2">1. Por tipo de proyecto</p>
                            <div class="dv-chart mb-3" id="ch-gr1"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-6">
                                    <table id="table-ch-1" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="75%">Tipo proyecto</th>
                                                <th width="25%">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-primary mb-2">2. Por tipo de orden</p>
                            <div class="dv-chart mb-3" id="ch-gr2"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-6">
                                    <table id="table-ch-2" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="75%">Tipo orden</th>
                                                <th width="25%">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="text-primary mb-2">3. Por Área Usuaria</p>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col">
                            <p class="text-secondary mb-2">Tipo: <b>ASP</b></p>
                            <div class="dv-chart mb-3" id="ch-gr31"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-10">
                                    <table id="table-ch-31" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="55%">Área usuaria</th>
                                                <th width="15%">Compras</th>
                                                <th width="15%">Servicios</th>
                                                <th width="15%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-secondary mb-2">Tipo: <b>Terceros</b></p>
                            <div class="dv-chart mb-3" id="ch-gr32"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-10">
                                    <table id="table-ch-32" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="75%">Área usuaria</th>
                                                <th width="25%">Servicios</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- --->
                    <div class="row">
                        <div class="col">
                            <p class="text-primary mb-2">4. Por responsable</p>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col">
                            <p class="text-secondary mb-2">Tipo: <b>ASP</b></p>
                            <div class="dv-chart mb-3" id="ch-gr41"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-10">
                                    <table id="table-ch-41" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="55%">Responsable</th>
                                                <th width="15%">Compras</th>
                                                <th width="15%">Servicios</th>
                                                <th width="15%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-secondary mb-2">Tipo: <b>Terceros</b></p>
                            <div class="dv-chart mb-3" id="ch-gr42"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-10">
                                    <table id="table-ch-42" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="75%">Responsable</th>
                                                <th width="25%">Servicios</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- --->
                    <div class="row">
                        <div class="col">
                            <p class="text-primary mb-2">5. Por días de vencimiento</p>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col">
                            <p class="text-secondary mb-2">Tipo: <b>ASP</b></p>
                            <div class="dv-chart mb-3" id="ch-gr51"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-10">
                                    <table id="table-ch-51" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="55%">Días vencidos</th>
                                                <th width="15%">Compras</th>
                                                <th width="15%">Servicios</th>
                                                <th width="15%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-secondary mb-2">Tipo: <b>Terceros</b></p>
                            <div class="dv-chart mb-3" id="ch-gr52"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-10">
                                    <table id="table-ch-52" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="75%">Días vencidos</th>
                                                <th width="25%">Servicios</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- --->
                    <div class="row">
                        <div class="col">
                            <p class="text-primary mb-2">6. Por estado del proyecto</p>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col">
                            <p class="text-secondary mb-2">Tipo: <b>ASP</b></p>
                            <div class="dv-chart mb-3" id="ch-gr61"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-10">
                                    <table id="table-ch-61" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="55%">Estado del proyecto</th>
                                                <th width="15%">Compras</th>
                                                <th width="15%">Servicios</th>
                                                <th width="15%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-secondary mb-2">Tipo: <b>Terceros</b></p>
                            <div class="dv-chart mb-3" id="ch-gr62"></div>
                            <div class="row justify-content-md-center">
                                <div class="col-10">
                                    <table id="table-ch-62" class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th width="75%">Estado del proyecto</th>
                                                <th width="25%">Servicios</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="{{ url('intranet/reportes/export/estadistica') }}" target="_blank" class="btn btn-sm btn-success text-light"><i class="far fa-file-excel"></i> Estadísticas en TD XLS</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- scripts -->
        @include('common.scripts')
        <script src="{{ asset('vendor/highcharts/highcharts.js') }}"></script>
        <script src="{{ asset('vendor/highcharts/highcharts-more.js') }}"></script>
        <script src="{{ asset('vendor/highcharts/modules/exporting.js') }}"></script>
        <script>
            var data_1 = {!! json_encode($data1) !!};
            var data_2 = {!! json_encode($data2) !!};
            var data_31 = {!! json_encode($data31) !!};
            var data_32 = {!! json_encode($data32) !!};
            var data_41 = {!! json_encode($data41) !!};
            var data_42 = {!! json_encode($data42) !!};
            var data_51 = {!! json_encode($data51) !!};
            var data_52 = {!! json_encode($data52) !!};
            var data_61 = {!! json_encode($data61) !!};
            var data_62 = {!! json_encode($data62) !!};
            //
            function EscribirGrafico1() {
                var tbody = $("#table-ch-1 tbody");
                var arr_categorias = [];
                var arr_series = [];
                tbody.empty();
                for(var i in data_1) {
                    var fila = data_1[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.catalogo)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.cantidad)
                        )
                    );
                    arr_categorias.push(fila.catalogo);
                    arr_series.push(parseInt(fila.cantidad));
                }
                //inserta el grafico
                $("#ch-gr1").highcharts({
                    chart: { type: 'column', width: 480 },
                    title: { text: 'Tipos de proyecto' },
                    subtitle: { text: 'Todos los proyectos' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Cantidad' }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}: {point.y} proy.</span><table>',
                        pointFormat: '',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            colorByPoint: true,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: "Proyectos",
                        data: arr_series

                    }],
                    legend: { enabled: false }
                });
            }
            function EscribirGrafico2() {
                var tbody = $("#table-ch-2 tbody");
                var arr_categorias = [];
                var arr_series = [];
                tbody.empty();
                for(var i in data_2) {
                    var fila = data_2[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.tipo)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.cantidad)
                        )
                    );
                    arr_categorias.push(fila.tipo);
                    arr_series.push(parseInt(fila.cantidad));
                }
                //inserta el grafico
                $("#ch-gr2").highcharts({
                    chart: { type: 'column', width: 480 },
                    title: { text: 'Tipos de orden' },
                    subtitle: { text: 'Proyectos ASP' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Cantidad' }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}: {point.y} proy.</span><table>',
                        pointFormat: '',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            colorByPoint: true,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: "Proyectos",
                        data: arr_series

                    }],
                    legend: { enabled: false }
                });
            }
            function EscribirGrafico31() {
                var tbody = $("#table-ch-31 tbody");
                var arr_categorias = [];
                var arr_series = [{
                    name: "compras",
                    data: []
                }, {
                    name: "servicios",
                    data: []
                }];
                tbody.empty();
                for(var i in data_31) {
                    var fila = data_31[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.area)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.compras)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.servicios)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.total)
                        )
                    );
                    arr_categorias.push(fila.area);
                    arr_series[0].data.push(parseInt(fila.compras));
                    arr_series[1].data.push(parseInt(fila.servicios));
                }
                //inserta el grafico
                $("#ch-gr31").highcharts({
                    chart: { type: 'column' },
                    title: { text: 'Área usuaria' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Proyectos ASP' },
                        stackLabels: {
                            enabled: true,
                            style: { fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray' }
                        }
                    },
                    legend: {
                        align: 'right',
                        x: -70,
                        verticalAlign: 'top',
                        y: 20,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.x + '</b><br/>' +
                                this.series.name + ': ' + this.y + '<br/>' +
                                'Total: ' + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                style: {
                                    textShadow: '0 0 3px black, 0 0 3px black'
                                }
                            }
                        }
                    },
                    series: arr_series
                });
            }
            function EscribirGrafico32() {
                var tbody = $("#table-ch-32 tbody");
                var arr_categorias = [];
                var arr_series = [];
                tbody.empty();
                for(var i in data_32) {
                    var fila = data_32[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.area)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.total)
                        )
                    );
                    arr_categorias.push(fila.area);
                    arr_series.push(parseInt(fila.total));
                }
                //inserta el grafico
                $("#ch-gr32").highcharts({
                    chart: { type: 'column', width: 480 },
                    title: { text: 'Área usuaria' },
                    subtitle: { text: 'Todos los proyectos' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Cantidad' }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}: {point.y} proy.</span><table>',
                        pointFormat: '',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            colorByPoint: true,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: "Áreas",
                        data: arr_series

                    }],
                    legend: { enabled: false }
                });
            }
            function EscribirGrafico41() {
                var tbody = $("#table-ch-41 tbody");
                var arr_categorias = [];
                var arr_series = [{
                    name: "compras",
                    data: []
                }, {
                    name: "servicios",
                    data: []
                }];
                tbody.empty();
                for(var i in data_41) {
                    var fila = data_41[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.responsable)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.compras)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.servicios)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.total)
                        )
                    );
                    arr_categorias.push(fila.responsable);
                    arr_series[0].data.push(parseInt(fila.compras));
                    arr_series[1].data.push(parseInt(fila.servicios));
                }
                //inserta el grafico
                $("#ch-gr41").highcharts({
                    chart: { type: 'column' },
                    title: { text: 'Responsable' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Proyectos ASP' },
                        stackLabels: {
                            enabled: true,
                            style: { fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray' }
                        }
                    },
                    legend: {
                        align: 'right',
                        x: -70,
                        verticalAlign: 'top',
                        y: 20,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.x + '</b><br/>' +
                                this.series.name + ': ' + this.y + '<br/>' +
                                'Total: ' + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                style: {
                                    textShadow: '0 0 3px black, 0 0 3px black'
                                }
                            }
                        }
                    },
                    series: arr_series
                });
            }
            function EscribirGrafico42() {
                var tbody = $("#table-ch-42 tbody");
                var arr_categorias = [];
                var arr_series = [];
                tbody.empty();
                for(var i in data_42) {
                    var fila = data_42[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.responsable)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.total)
                        )
                    );
                    arr_categorias.push(fila.responsable);
                    arr_series.push(parseInt(fila.total));
                }
                //inserta el grafico
                $("#ch-gr42").highcharts({
                    chart: { type: 'column', width: 480 },
                    title: { text: 'Área usuaria' },
                    subtitle: { text: 'Todos los proyectos' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Cantidad' }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}: {point.y} proy.</span><table>',
                        pointFormat: '',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            colorByPoint: true,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: "Responsables",
                        data: arr_series

                    }],
                    legend: { enabled: false }
                });
            }
            function EscribirGrafico51() {
                var tbody = $("#table-ch-51 tbody");
                var arr_categorias = [];
                var arr_series = [{
                    name: "compras",
                    data: []
                }, {
                    name: "servicios",
                    data: []
                }];
                tbody.empty();
                for(var i in data_51) {
                    var fila = data_51[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.dias + " días")
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.compras)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.servicios)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.total)
                        )
                    );
                    arr_categorias.push(fila.dias + " días");
                    arr_series[0].data.push(parseInt(fila.compras));
                    arr_series[1].data.push(parseInt(fila.servicios));
                }
                //inserta el grafico
                $("#ch-gr51").highcharts({
                    chart: { type: 'column' },
                    title: { text: 'Responsable' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Proyectos ASP' },
                        stackLabels: {
                            enabled: true,
                            style: { fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray' }
                        }
                    },
                    legend: {
                        align: 'right',
                        x: -70,
                        verticalAlign: 'top',
                        y: 20,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.x + '</b><br/>' +
                                this.series.name + ': ' + this.y + '<br/>' +
                                'Total: ' + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                style: {
                                    textShadow: '0 0 3px black, 0 0 3px black'
                                }
                            }
                        }
                    },
                    series: arr_series
                });
            }
            function EscribirGrafico52() {
                var tbody = $("#table-ch-52 tbody");
                var arr_categorias = [];
                var arr_series = [];
                tbody.empty();
                for(var i in data_52) {
                    var fila = data_52[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.dias + " días")
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.total)
                        )
                    );
                    arr_categorias.push(fila.dias + " días");
                    arr_series.push(parseInt(fila.total));
                }
                //inserta el grafico
                $("#ch-gr52").highcharts({
                    chart: { type: 'column', width: 480 },
                    title: { text: 'Área usuaria' },
                    subtitle: { text: 'Todos los proyectos' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Cantidad' }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}: {point.y} proy.</span><table>',
                        pointFormat: '',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            colorByPoint: true,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: "Responsables",
                        data: arr_series

                    }],
                    legend: { enabled: false }
                });
            }
            function EscribirGrafico61() {
                var tbody = $("#table-ch-61 tbody");
                var arr_categorias = [];
                var arr_series = [{
                    name: "compras",
                    data: []
                }, {
                    name: "servicios",
                    data: []
                }];
                tbody.empty();
                for(var i in data_61) {
                    var fila = data_61[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.hito)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.compras)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.servicios)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.total)
                        )
                    );
                    arr_categorias.push(fila.hito);
                    arr_series[0].data.push(parseInt(fila.compras));
                    arr_series[1].data.push(parseInt(fila.servicios));
                }
                //inserta el grafico
                $("#ch-gr61").highcharts({
                    chart: { type: 'column' },
                    title: { text: 'Estado actual' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Proyectos ASP' },
                        stackLabels: {
                            enabled: true,
                            style: { fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray' }
                        }
                    },
                    legend: {
                        align: 'right',
                        x: -70,
                        verticalAlign: 'top',
                        y: 20,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.x + '</b><br/>' +
                                this.series.name + ': ' + this.y + '<br/>' +
                                'Total: ' + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                style: {
                                    textShadow: '0 0 3px black, 0 0 3px black'
                                }
                            }
                        }
                    },
                    series: arr_series
                });
            }
            function EscribirGrafico62() {
                var tbody = $("#table-ch-62 tbody");
                var arr_categorias = [];
                var arr_series = [];
                tbody.empty();
                for(var i in data_62) {
                    var fila = data_62[i];
                    tbody.append(
                        $("<tr/>").append(
                            $("<td/>").html(fila.hito)
                        ).append(
                            $("<td/>").addClass("text-right").html(fila.total)
                        )
                    );
                    arr_categorias.push(fila.hito);
                    arr_series.push(parseInt(fila.total));
                }
                //inserta el grafico
                $("#ch-gr62").highcharts({
                    chart: { type: 'column', width: 480 },
                    title: { text: 'Estado actual' },
                    subtitle: { text: 'Proyectos Terceros' },
                    xAxis: { categories: arr_categorias },
                    yAxis: {
                        min: 0,
                        title: { text: 'Cantidad' }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}: {point.y} proy.</span><table>',
                        pointFormat: '',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            colorByPoint: true,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: "Responsables",
                        data: arr_series

                    }],
                    legend: { enabled: false }
                });
            }
            //
            EscribirGrafico1();
            EscribirGrafico2();
            EscribirGrafico31();
            EscribirGrafico32();
            EscribirGrafico41();
            EscribirGrafico42();
            EscribirGrafico51();
            EscribirGrafico52();
            EscribirGrafico61();
            EscribirGrafico62();
        </script>
    </body>
</html>