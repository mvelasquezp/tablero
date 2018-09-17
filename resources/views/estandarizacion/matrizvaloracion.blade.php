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
                <form id="form-matriz" class="container">
                    <div class="row">
                        <div class="col">
                            <h3 class="text-primary mb-2">Matriz de valoración</h3>
                            <p class="text-secondary">Ingrese los valores correspondientes a cada estado de los procesos</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8 col-offset-2">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        @foreach($cestados as $cestado)
                                        <th>{{ $cestado->estado }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pestados as $pestado)
                                    <tr>
                                        <th>{{ $pestado->estado }}</th>
                                        @foreach($cestados as $cestado)
                                        <td>
                                            <input type="text" data-pestado="{{ $pestado->id }}" data-cestado="{{ $cestado->id }}" data-key="{{ $pestado->id }}-{{ $cestado->id }}" class="form-control form-control-sm ip-valoracion" style="width:120px">
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button id="btn-sv-matriz" class="btn btn-success"><i class="far fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="overlay"></div>
        <!-- modals -->
        <!-- scripts -->
        @include('common.scripts')
        <script type="text/javascript">
            var ls_puntajes = {!! json_encode($puntajes) !!};
            //
            function MuestraPuntajes() {
                for(var i in ls_puntajes) {
                    var ipuntaje = ls_puntajes[i];
                    $(".ip-valoracion[data-key=" + ipuntaje.pest + "-" + ipuntaje.cest + "]").val(ipuntaje.puntaje);
                }
            }
            //
            function FormMatrizOnSubmit(event) {
                event.preventDefault();
                var inputs = $(".ip-valoracion");
                var arr_pesos = [];
                $.each(inputs, function() {
                    var input = $(this);
                    if(input.val() == "") {
                        alert("Complete todos los valores de la matriz para continuar");
                        return false;
                    }
                    arr_pesos.push({
                        catp: input.data("pestado"),
                        catc: input.data("cestado"),
                        peso: input.val()
                    });
                });
                var p = {
                    _token: "{{ csrf_token() }}",
                    pesos: arr_pesos
                };
                $.post("{{ url('ajax/estandarizacion/sv-matriz-valoracion') }}", p, function(response) {
                    if(response.state == "success") {
                        ls_puntajes = response.data.puntajes;
                        MuestraPuntajes();
                    }
                }, "json");
            }
            //
            $("#form-matriz").on("submit", FormMatrizOnSubmit);
            MuestraPuntajes();
        </script>
    </body>
</html>