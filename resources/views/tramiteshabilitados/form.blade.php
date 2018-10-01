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
            {!! Form::text('nro_doc', isset($edit) ? $edit->nro_doc : null, ['class' => 'form-control', 'placeholder' => 'Nro. Documento', 'required' => 'required' ]) !!}
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

        @hasrole('Legales')
            <div class="form-group">    
                {!! Form::label('nro_expediente', ' Nro. de Expediente / Carpeta') !!}
                {!! Form::text('nro_expediente', isset($edit) ? $edit->nro_expediente : null, ['class' => 'form-control', 'placeholder' => 'Ingrese Número de Expediente', 'required' => 'required']) !!}
            </div>
        @endhasrole
        
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
            $("input[name=nro_doc]").change(function(){
                var nro_doc = $(this).val();
                var tipo_doc = $("select[name=tipo_doc]").val();

                //Aplicar solo si nuevo registro, si esta editando no realizara la buscqueda
                @if(!isset($edit)) 
                    $("input[name=nombre], input[name=apellido], input[name=fecha_nacimiento]").val('');
                    $("select[name=pais]").val(1);
            
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        url: '/buscarDatosPersonales',
                        data: {tipo_doc: tipo_doc, nro_doc: nro_doc },
                        type: "GET", dataType: "json",
                        success: function(ret){
                            $("input[name=nombre]").val(ret.nombre);
                            $("input[name=apellido]").val(ret.apellido);
                            $("input[name=fecha_nacimiento]").val(ret.fecha_nacimiento);
                            $("select[name=sexo]").val(ret.sexo);
                            $("select[name=pais]").val(ret.pais);
                        }
                    });
                @endif
            });

            $("input[name=fecha], input[name=nro_doc], select[name=motivo_id]").change(function(){
                $("button[type='submit']").show();
                validarRetomaTurno();
            });

            function validarRetomaTurno(){
                var motivo = $("select[name=motivo_id]").val();
                var tipo_doc = $("select[name=tipo_doc]").val();
                var nro_doc = $("input[name=nro_doc]").val();
                $("#ultimo_turno").empty();
            
                if(motivo == '5' && nro_doc != ''){
                    $("button[type='submit']").hide();
                    console.log('motivo '+motivo+' nrodoc '+nro_doc);
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
            }
        });
    </script>
@endpush