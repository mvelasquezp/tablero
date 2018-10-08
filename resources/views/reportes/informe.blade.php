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
                                <tbody>
                                    @foreach($proyectos as $proyecto)
                                    <tr>
                                        <td class="text-right">{{ $proyecto->id }}</td>
                                        <td>{{ $proyecto->tipo }}</td>
                                        <td>{{ $proyecto->orden }}</td>
                                        <td>{{ $proyecto->expediente }}</td>
                                        <td class="text-right">{{ $proyecto->femision }}</td>
                                        <td>{{ $proyecto->areausr }}</td>
                                        <td>{{ $proyecto->proyecto }}</td>
                                        <td class="text-right">{{ $proyecto->fentrega }}</td>
                                        <td class="text-right">{{ number_format($proyecto->valor,2) }}</td>
                                        <td class="text-right">{{ $proyecto->armadas }}</td>
                                        <td class="text-right">{{ number_format($proyecto->avance,2) }}</td>
                                        <td><a href="javascript:void(0)" class="btn btn-sm btn-{{ $proyecto->indicador }}" style="border-radius:16px;height:32px;width:32px;"></a></td>
                                        <td class="text-right">{{ $proyecto->diasvence }}</td>
                                        <td>{{ $proyecto->estado }}</td>
                                        <td>{{ $proyecto->responsable }}</td>
                                        <td>{{ $proyecto->hobservaciones }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-right">
                            <a href="{{ url('intranet/reportes/export/informe') }}" target="_blank" class="btn btn-sm btn-success text-light"><i class="far fa-file-excel"></i> Exportar a XLS</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- scripts -->
        @include('common.scripts')
        <script>
        </script>
    </body>
</html>