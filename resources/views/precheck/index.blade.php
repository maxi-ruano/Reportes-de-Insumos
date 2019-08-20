@extends('layouts.templeate')

@section('content')

<div class="tab-v1">
    <ul class="nav nav-tabs" role="tablist">
        @can('anular_comprobantes_precheck')
            <li role="presentation" class="active"><a href="#tabPrecheck" aria-controls="tabPrecheck" role="tab" data-toggle="tab">Precheck Comprobantes</a></li>
        @endcan

        @can('anular_examen_teorico')
            <li role="presentation"><a href="#tabExamen" aria-controls="tabExamen" role="tab" data-toggle="tab"> Examen Teorico</a></li>
        @endcan

        @can('cambiar_pcs_examen_teorico')
            <li role="presentation"><a href="#tabTeoricoPc" aria-controls="tabTeoricoPc" role="tab" data-toggle="tab"> Teorico PCs</a></li>
        @endcan
    </ul>
    <div class="tab-content">
        @can('anular_comprobantes_precheck')
            <div role="tabpanel" class="tab-pane active" id="tabPrecheck">
                @include('precheck.anularComprobante')
            </div>
        @endcan

        @can('anular_examen_teorico')
            <div role="tabpanel" class="tab-pane" id="tabExamen">
                @include('examen.anularExamen')
            </div>
        @endcan

        @can('cambiar_pcs_examen_teorico')
            <div role="tabpanel" class="tab-pane" id="tabTeoricoPc">
                @include('teoricopc.index')
            </div>
        @endcan
    </div>
</div>

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