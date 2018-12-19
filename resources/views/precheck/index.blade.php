@extends('layouts.templeate')

@section('content')

@role('Admin')

<div class="tab-v1">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tabPrecheck" aria-controls="tabPrecheck" role="tab" data-toggle="tab">Precheck Comprobantes</a></li>
        <li role="presentation"><a href="#tabExamen" aria-controls="tabExamen" role="tab" data-toggle="tab"> Examen Teorico</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tabPrecheck"> 
            @include('precheck.anularComprobante') 
        </div>
        <div role="tabpanel" class="tab-pane" id="tabExamen"> 
            <!-- @include('examen.anularExamen')  -->
        </div>
    </div>
</div>

@endrole

@endsection

@push('scripts')
    <!-- Bootstrap-toggle -->
    <script src="{{ asset('vendors/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
@endpush

@section('css')
    <!-- bootstrap-toggle -->
    <link href="{{ asset('vendors/bootstrap-toggle/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/precheck.css') }}" rel="stylesheet">
@endsection