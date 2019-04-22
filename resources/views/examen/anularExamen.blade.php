<br>
<div id="divBuscarExamenTeorico" class="row">
    <div class="col-sm-3 col-xs-12">
        Número del Tramite: <br>
        <div class="input-group">
            <input type="number" class="form-control" id="tramite_id" name="tramite_id" placeholder="Buscar por Tramite ID" value="{{ Request::get('tramite_id') }}">
            <span class="input-group-btn">
                <button onclick="buscarExamenes()" class="btn btn-default-sm" type="button"><i class="fa fa-search"></i></button>
            </span>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table id="table_tramite" class="table table-striped table-hover jambo_table">
        <thead>
            <tr>
                <th class="column-title">Tramite ID</th>
                <th class="column-title">Nro. Documento</th>
                <th class="column-title">Nombre</th>
                <th class="column-title">Apellido</th>
                <th class="column-title">Fecha Inicio</th>
                <th class="column-title">Estado</th>
            </tr>
        </thead>
        <tbody>
    
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <table id="table_examenes" class="table table-striped table-hover jambo_table">
        <thead>
            <tr>
                <th class="column-title">Examen ID</th>
                <th class="column-title">Fecha Inicio</th>
                <th class="column-title">Fecha Fin</th>
                <th class="column-title">Aprobado</th>
                <th class="column-title">Porcentaje</th>
                <th class="column-title">Clase</th>
                <th class="column-title">Anulado</th>
            </tr>
        </thead>
        <tbody>
    
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        function buscarExamenes(){
            var tramite_id = $("#divBuscarExamenTeorico #tramite_id").val();
            $("#table_tramite tbody, #table_examenes tbody").empty();
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: 'listado_examenes',
                data: {tramite_id: tramite_id },
                type: "GET", 
                success: function(ret){
                    //console.log(ret);
                    var tramite = ret['tramite'];
                    var examenes = ret['examenes'];
                    
                    mostrarTramite(tramite);

                    for (var i = 0; i < examenes.length; i++) {
                        mostrarExamenes(examenes[i], tramite['estado']);
                    }
                }
            });
        }

        function mostrarTramite(tramite){
            $("#table_tramite tbody").html("<tr> "
                +"<td>"+tramite.tramite_id+"</td>"
                +"<td>"+tramite.nro_doc+"</td> "
                +"<td>"+tramite.nombre+"</td> "
                +"<td>"+tramite.apellido+"</td> "
                +"<td>"+tramite.fec_inicio+"</td> "
                +"<td>"+tramite.estado+"</td> "
                +"</tr>"
            );
        }

        function mostrarExamenes(examen, estado){
            var aprobado = (examen.aprobado)?' SI <i class="fa fa-check-circle" style="font-size:24px;color:green"></i> ':'NO <i class="fa fa-times-circle" style="font-size:24px;color:red"></i>';
            
            $("#table_examenes tbody").append("<tr> "
                +"<td>"+examen.etl_examen_id+"</td>"
                +"<td>"+examen.fecha_inicio+"</td> "
                +"<td>"+examen.fecha_fin+"</td>"
                +"<td>"+aprobado+"</td>"
                +"<td>"+examen.porcentaje+"</td>"
                +"<td>"+examen.clase_name+"</td>"
                +"<td><input id='examen"+examen.etl_examen_id+"' type='checkbox' data-toggle='toggle' data-on='Si' data-off='No' data-onstyle='success' data-offstyle='danger' data-size='mini' data-width='60'> </td>"
                +"</tr>"
            );

            if(examen.anulado)
                $('#examen'+examen.etl_examen_id).bootstrapToggle('on');
            else
                $('#examen'+examen.etl_examen_id).bootstrapToggle('off');
            
            if(!examen.aprobado && estado == 8 ) {
                $('#examen'+examen.etl_examen_id).attr("onchange","anularExamen("+examen.etl_examen_id+")");
                $('#examen'+examen.etl_examen_id).bootstrapToggle('enable');
            }else{
                $('#examen'+examen.etl_examen_id).bootstrapToggle('disable'); 
            }
        
        }

        function anularExamen(id){
            var anulado = $("#examen"+id).prop('checked');
            //console.log(id+' '+anulado);
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: 'anular_examen',
                data: {id: id, anulado:anulado },
                type: "GET", 
                success: function(ret){
                    //buscarExamenes();
                }
            });
        }

    </script>
@endpush