@extends('layouts.templeate')

@section('content')
<!-- page content -->
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">

      <div class="x_content">

                {!! Form::open(['route' => 'bedel.index', 'id'=>'formCategory', 'method' => 'GET', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
                <input type="text" name="op" id="op" value="find" class="hide">
                <div class="form-group">
                    <div class="col-md-2 col-sm-2">
                      <select name="pais" class="form-control select2 paises" required>
                        <option value="1" selected>ARGENTINA</option>
                        <script>var paises = new Array();
			@foreach($default['paises'] as $pais)
			@if((int)$pais->id != 1)
				paises.push({ id: {{ $pais->id }}, text: "{{ $pais->description }}" })
			@endif
			@endforeach
                        </script>
                      </select>
                    </div>

                    <div class="col-md-1 col-sm-1">
                      <select name ="tipo_doc" class="form-control select2 tdocs" required>
			<option value="" selected disabled>Doc</option>
			<option value="1" selected>DNI</option>
                        <script>var tdocs = new Array();
                          @foreach($default['tdoc'] as $tdoc)
			  	@if((int)$tdoc->id != 1)
					tdocs.push({ id: {{ $tdoc->id }}, text: "{{ $tdoc->description }}" })
				@endif
                          @endforeach
                        </script>
                      </select>
                    </div>

                    <div class="col-md-2 col-sm-2">
                      <input name="doc" type="text" autocomplete="off" class="form-control" placeholder="Documento" required>
                    </div>

                    <div class="col-md-1 col-sm-1">
                      <select name="sexo" class="form-control select2 sexos" required>
                        <option value="" selected disabled>Sexo</option>
                        <script>var sexos = new Array();
                          @foreach($default['sexo'] as $sex)
                            sexos.push({ id: "{{ $sex->description }}", text: "{{ $sex->description }}" })
                          @endforeach
                        </script>
                      </select>
                    </div>


                  <!--<div class="ln_solid"></div>-->

                    <!--<div class="col-md-2 col-sm-2">-->
                      <input id="send" type="submit" class="btn btn-success col-md-1 col-sm-1" value="Enviar">
                    </div>


                {!! Form::close() !!}
                @if(isset($_GET['msg']) AND $_GET['msg'] != '')
                <div class="form-group">
                  <div class="panel panel-default">
                    <div class="panel-body"><h5> {{ $_GET['msg'] }} </h5></div>
                  </div>
                </div>
                @endif
                @if( $categorias[0] != false )
                <div id="modalCliente" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content text-center">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel"><strong>Datos Personales</strong></h4>
                      </div>
                      <div class="modal-body text-center">
                        @if($categorias[0] != false)
                        <div class="profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                            <div class="right col-xs-5 text-center">
                              <img  class="img-pregunta img-responsive" onerror="this.src=\'{{ config('app.url') }}{{ config('global.IMAGE_USER_DEFAULT') }}\'"  style = "height: 150px; width: auto;" src="{{ $datos[2] }}" alt="Generic placeholder thumbnail">

                            </div>
                            <div class="left col-xs-7">
                              @if($categorias[0] != false)
                              <h2> Nombre:<strong>  {{ $datos[1]->nombre }} </strong></h2>
                              <h2> Apellido:<strong>  {{ $datos[1]->apellido }} </strong></h2>
                              <h2><p>DNI: <strong> {{  $datos[1]->nro_doc }}</strong> </p></h2>
                              @endif
                            </div>

                          </div>
                        </div>
                        </div>
                        {!! Form::open(['route' => 'asignarExamen', 'id'=>'formAsignar', 'method' => 'GET', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}

                        <div class="form-group">
                          <div class="row">
                              <div class="col-lg-6 col-lg-offset-3">
                                <div class="col-md-4 col-sm-6">
                                  <select name ="clase_name" class="form-control" required>
                                    <option value="" selected>Categoria</option>
                                    @foreach($categorias[1]->tramite as $cat)
                                    <option value="{{ $cat->clase }}">{{ $cat->clase }}</option>
                                    @endforeach
                                  </select>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                  <select name ="pc_id" class="form-control" required>
                                    <option value="" selected>Computadora</option>
                                    @if($computadoras[0] != false)
                                      @foreach($computadoras[1] as $computadora)
                                      <option value="{{ $computadora->id }}">{{ $computadora->name }}</option>
                                      @endforeach
                                    @endif
                                  </select>

                                </div>
                                <div class="col-md-4 col-sm-6">
                                  <button type="submit" class="btn btn-primary">ASIGNAR</button>
                                </div>
                            </div>
                          </div>
                          <input name="tramite_id" type="hidden" value="{{ $peticion[1]->tramite_id }}">
                        </div>
                        {!! Form::close() !!}
                        @else
                        @endif
                      </div>
                      <div class="modal-footer">
                        <div class="text-center">
                          <button type="button" aling="center" class="btn btn-default" data-dismiss="modal">CERRAR</button>

                        </div>

                      </div>

                    </div>
                  </div>
                </div>
                @endif
                @if($categorias[0] != true AND isset($categorias[1]) AND !is_array($categorias[1]))
                <div class="form-group">
                  <div class="panel panel-default">
                    <div class="panel-body"><h5> {{ $categorias[1] }} </h5></div>
                  </div>
                </div>
                @endif

      </div>
      @include('bedel.monitoreo')
    </div>
  </div>
</div>
<!-- /page content -->

@endsection('content')

@push('scripts')

    <script>
    $( document ).ready(function() {
      if ('{{ !empty($peticion[1]->tramite_id) }}')
        $('#modalCliente').modal('show');
        console.l
      $(".select2").select2({
        allowClear: true,
        language: "es"
      });

      $(".paises").select2({
        data: paises,
        placeholder: "Nacionalidad"
      });

      $(".tdocs").select2({
        data: tdocs,
        placeholder: "Doc"
      });

      $(".sexos").select2({
        data: sexos,
        placeholder: "Sexo"
      });
    });
    $('#formAsignar').submit(function() {
      $(this).find("button[type='submit']").prop('disabled',true);
    });

    </script>
    <script src="{{ asset('vendors/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('vendors/validator/validator.js')}}"></script>
@endpush

@section('css')
<!-- Select2 -->
    <link href="{{ asset('vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection
