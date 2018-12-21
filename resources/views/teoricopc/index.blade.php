<br>
    <div class="form-group row">
        <div id="divTeoricoPc" class="col-sm-4 col-xs-12">
            Sucursal
            {!! Form::select('sucursal', $sucursales, null , ['class' => 'form-control', 'id' => 'sucursal', 'placeholder' => 'Seleccione ... ']) !!}
        </div>
    </div>

    <div id="divPcsActivas" class="form-group row">
        <div class="col-sm-4 col-xs-12">
            <h2>Computadoras ACTIVAS <span class="red">(Procesando examen)</span> </h2>
            {!! Form::select('pc_activas',[], null , ['class' => 'form-control', 'id' => 'pc_activas', 'placeholder' => 'Seleccione ... ']) !!}
            <input id="btnDesactivarPc" onclick="descativarPc()" class='btn btn-danger btn-xs' type='button' value=' Desactivar Pc seleccionada'> 
        </div>
    </div>

    <div id="divPcsDisponibles" class="form-group row">
        <div class="col-sm-4 col-xs-12">
                <h2>Computadoras <span class="red"> DISPONIBLES</span> </h2>
            {!! Form::select('pc_disponibles',[], null , ['class' => 'form-control', 'id' => 'pc_disponibles', 'placeholder' => 'Seleccione ... ']) !!}
        </div>
    </div>
    <hr>

    <div class="col-md-4 col-xs-12">
        <input id="btnCambiarPc" onclick="procesarCambioPc()" class='btn btn-success btn-group-justified' type='button' value='Realizar cambio de Pc'>         
    </div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#divPcsActivas, #divPcsDisponibles, #btnDesactivarPc, #btnCambiarPc").hide();

            $("#divTeoricoPc #sucursal").change(function(){
                mostrarPcs();
            });

            $("#pc_disponibles").change(function(){
                var pc_disponibles = $(this).val();
                var pc_activas = $("#pc_activas").val();
                if(pc_activas && pc_disponibles)
                    $("#btnCambiarPc").show();
                else
                    $("#btnCambiarPc").hide();
            });
        });

        function mostrarPcs(){
            var sucursal = $("#divTeoricoPc #sucursal").val();
            mostrarPcsActivas(sucursal);
            mostrarPcsDisponibles(sucursal);
        }

        function mostrarPcsActivas(sucursal){
            $("#divPcsActivas").show();
            $('#pc_activas').empty();
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '/listado_teoicopc',
                data: {sucursal: sucursal, activo: 1 },
                type: "GET", 
                success: function(pcs){
                    
                    $.each(pcs, function(index, pc){
                        $('#pc_activas').append('<option value="' + pc.id +'-' + pc.examen_id +'"> Pc #' + pc.id + ' - Examen ' + pc.examen_id + ' </option>');
                    });

                    if(!pcs.length){
                        $('#pc_activas').append('<option value="">No existen Pcs activas en esta sucursal</option>');
                        $("#btnDesactivarPc").hide();
                    }else{
                        $("#btnDesactivarPc").show();
                    }
                }
            });
        }

        function mostrarPcsDisponibles(sucursal){
            $("#divPcsDisponibles").show();
            $('#pc_disponibles').empty();            

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '/listado_teoicopc',
                data: {sucursal: sucursal, activo: 0 },
                type: "GET", 
                success: function(pcs){
                    $('#pc_disponibles').append('<option value="">Seleccione</option>');
                    $.each(pcs, function(index, pc){
                        $('#pc_disponibles').append('<option value="' + pc.id +'"> Pc #' + pc.id + ' (disponible) </option>');
                    });
                }
            });
        }

        function descativarPc(){            
            var pc_activa = $('#pc_activas').val().split('-');
            var pc_id = pc_activa[0];

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: 'desactivarPcTeorico',
                data: {pc_id: pc_id },
                type: "GET", 
                success: function(ret){
                    mostrarPcs();
                }
            });
        }

        function procesarCambioPc(){
            var pc_activa = $('#pc_activas').val().split('-');
            var pc_id = pc_activa[0];
            var examen_id = pc_activa[1];
            var pc_disponible = $('#pc_disponibles').val();

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: 'cambiarPcTeorico',
                data: {pc_id: pc_id, examen_id: examen_id, pc_disponible: pc_disponible },
                type: "GET", 
                success: function(ret){
                    mostrarPcs();
                }
            });
        }

    </script>
@endpush