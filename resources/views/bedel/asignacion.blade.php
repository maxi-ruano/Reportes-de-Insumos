@extends('layouts.templeate')
@section('titlePage', 'Teorico')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Buscar persona</h2>
          @include('includes.headerContainer')
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
                {!! Form::open(['route' => 'bedel.index', 'id'=>'formCategory', 'method' => 'GET', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
                <div class="form-group">
                    <div class="col-md-3 col-sm-3">
                      <select name="pais" class="form-control">
                        @foreach($paises as $pais)
                        @if($pais->id == 1)
                        <option value="{{ $pais->id }}" selected>{{ $pais->description }}</option>
                        @else
                        <option value="{{ $pais->id }}">{{ $pais->description }}</option>
                        @endif
                        @endforeach
                      </select>
                    </div>



                      <div class="col-md-1 col-sm-1">
                        <select class="form-control">
                          @foreach($tipo_doc as $tdoc)
                          @if($tdoc->id == 1)
                          <option value="{{ $tdoc->id }}" selected>{{ $tdoc->description }}</option>
                          @else
                          <option value="{{ $tdoc->id }}">{{ $tdoc->description }}</option>
                          @endif
                          @endforeach
                        </select>
                      </div>

                      <div class="col-md-5 col-sm-5">
                        <input name="doc" type="text" class="form-control" placeholder="Documento">
                      </div>

                        <div class="col-md-1 col-sm-1">
                          <select name="sexo" class="form-control">
                            @foreach($sexo as $sex)
                            @if($sex->id == 0)
                            <option value="{{ strtolower($sex->description) }}" selected>{{ $sex->description }}</option>
                            @else
                            <option value="{{ strtolower($sex->description) }}">{{ $sex->description }}</option>
                            @endif
                            @endforeach
                          </select>
                        </div>


                  <!--<div class="ln_solid"></div>-->

                    <!--<div class="col-md-2 col-sm-2">-->
                      <input id="send" type="submit" class="btn btn-success col-md-1 col-sm-1" value="Enviar">
                    <!--</div>-->
                      </div>
                {!! Form::close() !!}

                @foreach($peticion as $res)
                  <p>{{ $res}}</p>
                @endforeach
      </div>
    </div>
  </div>
</div>

<!-- /page content -->
@endsection

@section('scripts')
<!-- validator -->
<script src="{{ asset('vendors/validator/validator.js')}}"></script>
@include('includes.scriptForms')

@endsection
