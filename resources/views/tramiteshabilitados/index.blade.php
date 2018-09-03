@extends('layouts.templeate')
@section('titlePage', 'Tramites Habilitados')
@section('content')
<!-- page content -->

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            {!! Form::open(['method'=>'GET','url'=>'tramitesHabilitados','class'=>'navbar-form navbar-left','role'=>'search'])  !!}
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Buscar..." value="{{ Request::get('search') }}">
                <span class="input-group-btn">
                    <input type="date" class="form-control" name="fecha" id="fecha" value={{ isset($_GET['fecha'])?$_GET['fecha']:date('Y-m-d') }}>
                    <button id="buscar" class="btn btn-default-sm" type="submit"><i class="fa fa-search"></i></button>
                </span>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="col-sm-4 col-xs-12 text-right">
            <a href="{{route('tramitesHabilitados.index')}}" class="btn btn-primary"> Actualizar <i class="glyphicon glyphicon-refresh"></i> </a>
            @can('add_tramitesHabilitados')
                <a href="{{route('tramitesHabilitados.create')}}" class="btn btn-primary">Nuevo <i class="glyphicon glyphicon-plus-sign"></i> </a>
            @endcan
        </div>        
    </div>

    <div class="table-responsive">
    @if($data)
        <table class="table table-striped jambo_table" data-form="deleteForm">
            <thead>
                <tr>
                    <th class="column-title">Apellido</th>
                    <th class="column-title">Nombre</th>
                    <th class="column-title">Tipo Doc.</th>
                    <th class="column-title">Nro. Doc.</th>
                    <th class="column-title">Pais</th>
                    <th class="column-title">Fecha</th>
                    <th class="column-title">Motivo</th>
                    <th class="column-title">Usuario</th>
                    <th class="column-title">Habilitado</th>
                    <th class="column-title">Precheck</th>
                    <th class="column-title"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->apellido }}</td>
                    <td>{{ $row->nombre }}</td>
                    <td>{{ $row->tipo_doc }}</td>
                    <td>{{ $row->nro_doc }}</td>
                    <td>{{ $row->pais }}</td>
                    <td>{{ $row->fecha }}</span>
                    <td>{{ $row->motivo_id }} </td>
                    <td>
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Creado {{ $row->created_at }}">    
                            {{ $row->user_id }}
                        </span>
                    </td>
                    <td>
                        @if($row->habilitado)
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{ $row->habilitado_user_id }}">    
                            @if(Auth::user()->hasRole('Admin'))
                                <input id="habilitado{{ $row->id }}" type="checkbox" checked onchange="habilitarTurno({{ $row->id }})" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60">
                            @else
                                <input id="habilitado{{ $row->id }}" type="checkbox" checked disabled data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60">
                            @endif
                        </span> 
                        @else
                            <input id="habilitado{{ $row->id }}" type="checkbox" onchange="habilitarTurno({{ $row->id }})" data-toggle="toggle"  data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60" >
                        @endif
                    </td>
                    <td>
                        <button type="button" onclick="cargarDatosPrecheck({{ $row->tramites_a_iniciar_id }})" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-precheck">
                            <i class="glyphicon glyphicon-check"></i> Precheck
                        </button>
                    </td>                             
                    <td>
                        @can('edit_tramitesHabilitados')
                            <a href="{{ route('tramitesHabilitados.edit', $row->id) }}" class="btn btn-success pull-right btn-xs" title="Editar"> Editar <i class="fa fa-edit"></i></a>
                        @endcan
                        @can('delete_tramitesHabilitados')
                            {!! Form::open(array('route' => array('tramitesHabilitados.destroy', $row->id), 'method' => 'delete', 'class' => 'form-delete')) !!}
                                <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete">
                                    <i class="glyphicon glyphicon-trash"></i> Borrar 
                                </button>
                            {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    </div>

    <div class="col-sm-12 col-xs-12 text-center">
        {{ $data->links() }}
    </div>

</div>
@include('includes.modalPrecheck')
<!-- /page content -->
@endsection


@push('scripts')
    <!-- Bootstrap-toggle -->
    <script src="{{ asset('vendors/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            habilitado = true;
            //Actualizar la pagina automaticamente solo si no han realizado busqueda o cambiado de paginacion
            @if(!isset($_GET['page']) && !isset($_GET['search']))
                iniciarRegargarPagina();
            @endif

            @if(isset($_GET['search']) && !isset($_GET['fecha']))
                @if($_GET['search'] == '')
                    iniciarRegargarPagina();
                @endif
            @endif

            @if(isset($_GET['page']))
                @if($_GET['page'] == '1')
                    iniciarRegargarPagina();
                @endif
	     @endif

	     $('#modal-precheck').on('hidden.bs.modal', function () {
		     iniciarRegargarPagina()
	     })

        });
        
        function habilitarTurno(id){
            var valor = $("#habilitado"+id).prop('checked');
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '/tramitesHabilitadosHabilitar',
                data: {id: id, valor:valor },
                type: "GET", 
                success: function(ret){
                    cargarPagina();
                }
            });
        }

        function cargarPagina(){
            window.location.href = "{{ route('tramitesHabilitados.index') }}";
        }

        var recargarPagina;

        function iniciarRegargarPagina() {
            recargarPagina = setTimeout(cargarPagina, 60000);
            console.log('se inicio')
        }

        function detenerRegargarPagina() {
            clearTimeout(recargarPagina);
            console.log('se detuvo')
        }

        function cargarDatosPrecheck(id){
            detenerRegargarPagina()
            getPreCheck(id)
        }

        /* TODO este codigo debera ir en un js compilado, ya q es reutilizado en checkModoAutonomo.blade.php*/
        function getPreCheck(id){
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: "GET",
                url: '/consultarPreCheck',
                data: { id: id, },
                //async:false,
                success: function( msg ) {
                    if(msg.error){
                    mostrarMensajeError(msg.error)
                    }else if(msg){
                    mostrarPreCheck(msg.precheck)
                    mostrarDatosPersona(msg.datosPersona)
                    }
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                }
            });
        }

        function mostrarPreCheck(res){
            $('#logPreCheck').empty()
            for (var i = 0; i < res.length; i++) {
            crearMensajePrecheck(res[i])
            }
        }

        function mostrarDatosPersona(datosPersona){
            //Convertir fecha a dd-mm-yyyy
            var f = datosPersona.fecha_nacimiento.split('-');
            var fecha_nac = f[2] +"-"+ f[1]+"-"+f[0];

            $('#nombre_texto').html(datosPersona.nombre+' '+datosPersona.apellido);
            $('#documento_texto').html(datosPersona.nro_doc);
            $('#fecha_nacimiento_texto').html(fecha_nac);
            $('#nacionalidad_texto').html(datosPersona.nacionalidad);

            if (datosPersona.fecha_paseturno == null)
                $('#logTurno').html(' <a id="btnFechaPaseTruno" onclick="getPaseTurno('+datosPersona.id+')" class="btn btn-danger btn-block"><span>SIGUIENTE SECTOR</span> <i class="fa fa-sign-in"></i></a> ');
            else
                $('#logTurno').html(' <a id="btnFechaPaseTruno" class="btn btn-success btn-block"><i class="fa fa-check-circle"></i> <span>PASO AL SIGUIENTE SECTOR <b>'+datosPersona.fecha_paseturno+'</b> </span> </a> ');
        }

        function mostrarMensajeError(error){
           $('#logPreCheck').append('<li><label class="btn btn-danger">'+error+'</label></li>')
        }

        function crearMensajePrecheck(msj){
            console.log(msj);

            type = 'danger'
            fecha_error = ''
            if(msj.validado){
                error = 'Verificado'
                type = 'success'
                fecha_error = (msj.comprobante) ? 'Comprobante Nro. '+msj.comprobante : '';
            }else{
                var prop = 'description'
                if (msj.error){
                if(msj.error.description)
                    error =  msj.error.description
                }else{
                error =  'No verificado'
                }

                //Si tiene un Plan de Pagos mostrar su fecha de vencimiento
                if(error.toUpperCase().indexOf("PLAN DE PAGO") > -1){
                var metadata = JSON.parse(msj.error.response_ws);
                var data = metadata.filter(metadataObj => metadataObj.tag.indexOf("AUTORIZACION") > -1);
                var fecha_vencimiento = JSON.stringify(data[0]['attributes']['FECHAVTOLICENCIA']);
                fecha_error = '<span class="red"> Plan de Pago con Fecha Vencimiento: '+fecha_vencimiento+'</span>';
                type = 'warning';

                }else{
                fecha_error = ((msj.error) ? msj.error.created_at : '')
                }

            }

            //Colocar el metodo onclick solo si no se ha verificado (type=danger)
            var precheckOnclick = '';
            if(type=='danger')
                precheckOnclick = 'onclick="runPrecheck('+msj.tramite_a_iniciar_id+','+msj.validation_id+')" ';

            //Boton del Log Prec-Check con su descripcion y fecha de ejecucion o Nro. Comrpobante
            html = '<li>'+
                '<div class="block_precheck">'+
                '<div class="tags_precheck">'+
                    '<a id="buttonValidacion" '+precheckOnclick+' class="btn btn-'+type+' btn-xs btn-block">'+
                    '<span>'+msj.description+'</span>'+
                    '</a>'+
                '</div>'+
                '<div class="block_content">'+
                    '<h2 class="title">'+
                        '<a id="textoValidacion">'+ error +'</a>'+
                    '</h2>'+
                    '<div class="byline">'+
                    '<span>'+fecha_error+'</span>'+
                    '</div>'+

                '</div>'+
                '</div>'+
            '</li>';
            $('#logPreCheck').append(html)
        }

        function runPrecheck(id, validation){
        console.log(id+' '+validation);
        
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type: "GET",
            url: '/runPrecheck',
            data: { id: id, validation: validation },
            Async:true,
            beforeSend: function(){
            // Handle the beforeSend event
            $('#logPreCheck').html('<img src="/img/buffer.gif" width="200" > Verificando... ');
            },
            success: function( msg ) {
            console.log('Finalizo: '+msg);
            getPreCheck(id);
            },
            error: function(xhr, status, error) {
            $('#logPreCheck').html('ocurrio un error!! Intenta de nuevo...');
            }
        });

      console.log('continuando')
    }

    function getPaseTurno(id){
      $.ajax({
          type: "POST",
          url: '/api/funciones/actualizarPaseATurno',
          data: { id: id},
          success: function( msg ) {
            getPreCheck(id);
          },
          error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
          }
      });
    }
        /**/
    </script>
@endpush

@section('css')
    <!-- bootstrap-toggle -->
    <link href="{{ asset('vendors/bootstrap-toggle/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/precheck.css') }}" rel="stylesheet">
@endsection 
