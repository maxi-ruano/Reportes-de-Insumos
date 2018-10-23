@extends('layouts.templeate')
@section('titlePage', "Tramites Habilitados")

@section('content')
<!-- page content -->
<div class="container">
    @can('view_all_tramites_habilitados','view_self_tramites_habilitados')
        <div class="col-md-2 col-xs-12">
            <a href="{{route('tramitesHabilitados.index')}}?fecha={{date('Y-m-d')}}" class="btn btn-info btn-group-justified"> <i class="fa fa-list"></i> Mostrar listado </a>
        </div>
        <br><br>
    @endcan
    
    <h4>Crear Turno </h4>   
    
    @if(isset($edit)) 
        {!! Form::open(['route' => ['tramitesHabilitados.update', $edit], 'id'=>'formTramitesHabilitados', 'method' => 'PUT', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
    @else
        {!! Form::open(['route' => 'tramitesHabilitados.store', 'id'=>'formTramitesHabilitados', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
    @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

        <div class="form-group">
            <div class="col-md-3 col-xs-12">
                {!! Form::label('fecha', ' Fecha') !!}
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </div>
                    @can('enable_fecha_tramites_habilitados')
                        <input type="date" name="fecha" value="{{ isset($edit) ? $edit->fecha : $fecha }}" class="form-control" required="required" min="{{ $fecha }}" >    
                    @else
                        <input type="date" name="fecha" value="{{ isset($edit) ? $edit->fecha : $fecha }}" class="form-control" required="required" readonly="readonly" >
                    @endcan
                </div>
            </div>

            <div class="col-md-9 col-xs-12">
                {!! Form::label('sucursal', ' Sucursal') !!}
                @can('enable_sede_tramites_habilitados')
                    {!! Form::select('sucursal', $sucursales, isset($edit) ? $edit->sucursal : Auth::user()->sucursal , ['class' => 'form-control' ]) !!}
                @else
                    {!! Form::select('sucursal', $sucursales, isset($edit) ? $edit->sucursal : Auth::user()->sucursal , ['class' => 'form-control', 'readonly' => 'readonly', 'disabled' ]) !!}
                    <input type="hidden" name="sucursal" value="{{ isset($edit) ? $edit->sucursal : Auth::user()->sucursal }}">        
                @endcan
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('tipo_doc', ' Documento') !!}
            {!! Form::select('tipo_doc', $tdocs, isset($edit) ? $edit->tipo_doc : null, ['class' => 'form-control']) !!}
            {!! Form::text('nro_doc', isset($edit) ? $edit->nro_doc : null, ['class' => 'form-control', 'placeholder' => 'Nro. Documento', 'maxlength' => 10, 'required' => 'required' ]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('apellido', ' Apellidos') !!}
            {!! Form::text('apellido', isset($edit) ? $edit->apellido : null, ['class' => 'form-control', 'placeholder' => 'Apellidos', 'required' => 'required']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('nombre', ' Nombres') !!}
            {!! Form::text('nombre', isset($edit) ? $edit->nombre : null, ['class' => 'form-control', 'placeholder' => 'Nombres', 'required' => 'required']) !!}
        </div>

        <div class="form-group">
            <div class="col-md-2 col-xs-12">
                {!! Form::label('fecha_nacimiento', ' Fecha de Nacimiento') !!}
                <input type="date" name="fecha_nacimiento" value="{{ isset($edit) ? $edit->fecha_nacimiento : NULL }}" class="form-control" required="required" >
            </div>

            <div class="col-md-2 col-xs-12">
                {!! Form::label('sexo', ' Sexo') !!}
                {!! Form::select('sexo', ['F' => 'Femenino', 'M' => 'Masculino'], isset($edit) ? $edit->sexo : 'M' , ['class' => 'form-control', 'required' => 'required']) !!}
            </div>

            <div class="col-md-8 col-xs-12">
                {!! Form::label('pais', ' País') !!}
                {!! Form::select('pais', $paises, isset($edit) ? $edit->pais : 1 , ['class' => 'form-control', 'placeholder' => 'Seleccione', 'required' => 'required']) !!}
            </div>
        </div>

        <div class="form-group">                
            {!! Form::label('motivo_id', ' Motivo') !!}
            @if(count($motivos)==1)
                {!! Form::select('motivo_id', $motivos, isset($edit) ? $edit->motivo_id : null , ['class' => 'form-control', 'required' => 'required']) !!}
            @else
                {!! Form::select('motivo_id', $motivos, isset($edit) ? $edit->motivo_id : null , ['class' => 'form-control', 'placeholder' => 'Seleccione', 'required' => 'required']) !!}
            @endif
        </div>

       
        <div id="div_observacion" class="form-group">    
            {!! Form::label('observacion', ' Observación: ') !!}
            {!! Form::text('observacion', isset($edit) ? $edit->observacion : null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>
 
        
        <div id="ultimo_turno"> </div>
        <hr>
        
        @can('add_tramites_habilitados', 'edit_tramites_habilitados')
        <div class="col-md-2 col-xs-12">
            <button type="submit" class="btn btn-primary btn-group-justified"> <i class="fa fa-check-square-o"></i> Enviar </button>                
        </div>
        @endcan
       
    {!! Form::close() !!}
</div>

<!-- /page content -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var edad;
            validarMotivos();

            @if(!isset($edit)) 
                //Autocompletar datos solo si nuevo registro, si esta editando no realizara la buscqueda
                $("input[name=nro_doc]").change(function(){
                    var nro_doc = $(this).val();
                    var tipo_doc = $("select[name=tipo_doc]").val();
                    
                    $("input[name=nombre], input[name=apellido], input[name=fecha_nacimiento]").val('');
                    $("select[name=pais]").val(1);
            
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        url: '/buscarDatosPersonales',
                        data: {tipo_doc: tipo_doc, nro_doc: nro_doc },
                        type: "GET", dataType: "json",
                        success: function(ret){
                            console.log(ret);
                            $("input[name=nombre]").val(ret.nombre);
                            $("input[name=apellido]").val(ret.apellido);
                            $("input[name=fecha_nacimiento]").val(ret.fecha_nacimiento);
                            $("select[name=sexo]").val(ret.sexo);
                            $("select[name=pais]").val(ret.pais);
                        }
                    });
                });
            @endif

           
            $("input[name=fecha], input[name=nro_doc], select[name=motivo_id], input[name=fecha_nacimiento]").change(function(){
                var fecha = $('input[name=fecha_nacimiento]').val();
                edad = calcularEdad(fecha);
                validarMotivos();
            });

            //Segun el motivo solicitar mas informacion 
            function validarMotivos(){
                var motivo = $("select[name=motivo_id] option:selected").text();
                $("#div_observacion").show();
                $("#div_observacion input").removeAttr('required minlength maxlength');
                $("#div_observacion label").html('Observación: ');
                
                $("#ultimo_turno").empty();
                $("button[type='submit']").show();

                switch(motivo) {
                    case 'LEGALES':
                        //Nro. de Expediente (Obligatorio)
                        $("#div_observacion label").html('Nro. de Expediente / Carpeta: ');
                        $("#div_observacion input").attr('placeholder','Ingrese el Nro. del Expediente').attr('required','required');
                        break;
                    case 'ERROR EN TURNO':
                        //Nro. de Cita (Obligatorio)
                        $("#div_observacion label").html('Nro. de Cita: ');
                        $("#div_observacion input").attr('placeholder','Ingrese el Nro. de la Cita').attr('required','required').attr('minlength','8').attr('maxlength','8');
                        break;
                    case 'REINICIA TRAMITE':
                        //ID del Tramite (Obligatorio)
                        $("#div_observacion label").html('ID del Tramite: ');
                        $("#div_observacion input").attr('placeholder','Ingrese el ID del Tramite').attr('required','required').attr('minlength','7').attr('maxlength','7');;
                        break;
                    case 'DIRECCIÓN':
                        //Campo Obligatorio dejar constancia de quien viene (Obligatorio)
                        $("#div_observacion input").attr('placeholder','Ingrese el responsable de la solictud').attr('required','required');
                        break;
                    case 'GOEGS':
                        //Dejar constancia de quien viene NO Obligatorio
                        $("#div_observacion input").attr('placeholder','Ingrese el responsable de la solictud');
                        break;
                    case 'MAYOR DE 65':
                        $("#div_observacion").hide();
                        if(edad < 65){
                            $("button[type='submit']").hide();
                            $("#ultimo_turno").html('<h4 class="red"> <i class="fa fa-user-times" style="font-size:20px;"></i> Esta persona no cuenta con la edad permitida! tiene: '+edad+' años </h4>');
                        }
                        break;
                    case 'RETOMA TURNO':
                        $("#div_observacion").hide();
                        validarRetomaTurno();
                        break;
                    default:
                        $("#div_observacion").hide();
                        
                }
                
            }

            function validarRetomaTurno(){
                var motivo = $("select[name=motivo_id] option:selected").text();
                var tipo_doc = $("select[name=tipo_doc]").val();
                var nro_doc = $("input[name=nro_doc]").val();
                
                console.log('motivo '+motivo+' nrodoc '+nro_doc);
                
                if(nro_doc != ''){
                    $("button[type='submit']").hide();
                    
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        url: '/consultarUltimoTurno',
                        data: {tipo_doc: tipo_doc, nro_doc: nro_doc },
                        type: "GET", dataType: "json",
                        success: function(ret){
                            //Calcular los dias entre la dos fecha
                            var fechaini = new Date(ret.fecha);
                            var fechafin = new Date($("input[name=fecha]").val());
                            var diasdif= fechafin.getTime()-fechaini.getTime();
                            var dias = Math.round(diasdif/(1000*60*60*24));

                            var f = ret.fecha.split('-');
                            var fecha = f[2]+'/'+f[1]+'/'+f[0];

                            $("#ultimo_turno").html('Información de su último turno: <table class="table table-striped jambo_table"><tr><td>'+fecha+'</td><td>'+ret.hora+'</td><td>'+ret.descsede+'</td><td>'+ret.descprestacion+'</td><td class="red"> '+dias+' días</td><td class="icono"></td></tr></table>');

                            if(dias > 0 && dias <= 15){
                                $("#ultimo_turno .icono").html('<i class="fa fa-check-circle" style="font-size:26px;color:green"></i>');
                                $("button[type='submit']").show();
                            }else{
                                $("#ultimo_turno .icono").html('<i class="fa fa-times-circle" style="font-size:26px;color:red"></i>');
                                $("#ultimo_turno").append('<h4 class="red"> <i class="fa fa-user-times" style="font-size:30px;"></i> El turno previo ha superado el limite de los 15 días para poder RETOMAR TURNO!.</h4>');
                            }

                        },
                        error: function(err) {
                            $("#ultimo_turno").html('<h4 class="red"> <i class="fa fa-user-times" style="font-size:30px;"></i> Esta persona no cuenta con turno previo, debe contar con turno entre los 15 días para poder RETOMAR TURNO.</h4>');
                         }
                    });
                }

                //Si es Administrador permitir guardar
                @role('Administrador Tramites Habilitados')
                    $("button[type='submit']").show();
                @endrole
            }

            function calcularEdad(fecha) {
                var hoy = new Date();
                var cumpleanos = new Date(fecha);
                var edad = hoy.getFullYear() - cumpleanos.getFullYear();
                var m = hoy.getMonth() - cumpleanos.getMonth();
                if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
                    edad--;
                }
                return edad;
            }

        });
    </script>
@endpush