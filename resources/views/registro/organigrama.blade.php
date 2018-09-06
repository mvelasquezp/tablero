<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('APP_TITLE') }}</title>
        @include('common.head')
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/getorgchart/getorgchart.css') }}">
    </head>
    <body>
        <div class="wrapper">
            @include('common.sidebar')
            @include('common.navbar')
            <div id="content">
                <div class="container">
                    <div class="row">
                        <div class="col-9">
                            <div id="organigrama">
                                <p>Por favor, espere</p>
                            </div>
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        @include('common.scripts')
        <script type="text/javascript" src="{{ asset('vendor/getorgchart/getorgchart.js') }}"></script>
        <script type="text/javascript">
            var p = { _token:"{{ csrf_token() }}" };
            $.post("{{ url('ajax/registros/ls-puestos') }}", p, function(response) {
                var puestosDiv = document.getElementById("organigrama");
                //puestosDiv.innerHTML = "";
                var orgChart = new getOrgChart(puestosDiv, {
                    primaryFields: ["name", "title", "phone", "mail"],
                    photoFields: ["image"],
                    expandToLevel: 100,
                    layout: getOrgChart.MIXED_HIERARCHY_RIGHT_LINKS,
                    dataSource: response
                });
            }, "json");
        </script>
    </body>
</html>