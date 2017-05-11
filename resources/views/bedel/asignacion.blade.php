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
                {!! Form::open(['route' => 'computadoras.store', 'id'=>'formCategory', 'method' => 'GET', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
                  <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nombre <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="name" name="name" value="{{ isset($customer) ? $customer->name : null }}" class="form-control col-md-7 col-xs-12" data-validate-length-range="3,255" placeholder="Minimo 3 caracteres ..." required="required" type="text">
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">Telefono
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="number" id="phone" name="phone" value="{{ isset($customer) ? $customer->phone : null }}" data-validate-minmax="4,10" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="email" id="email" name="email" value="{{ isset($customer) ? $customer->email : null }}" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                      <button type="submit" class="btn btn-primary">Cancel</button>
                      <button id="send" type="submit" class="btn btn-success">Submit</button>
                    </div>
                  </div>
                {!! Form::close() !!}
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
