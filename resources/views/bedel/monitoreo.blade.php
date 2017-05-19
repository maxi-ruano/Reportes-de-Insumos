<div class="row">

<h2 class="text-center">Estados Computadoras</h2>

<div class="computadoras">

</div>
</div>
<script src="{{ asset('./build/js/monitoreo.js') }}"></script>
@section('scripts')
<!-- validator -->


<script type="text/javascript">
var url =  '{{ config('app.url') }}'+'{{ config('global.URL_COMPUTADORAS_MONITOR') }}';
var imagenDefault = '{{ asset('production/images/user.png')}}';
actualizarMonitor();

setTimeout(function () {
  actualizarMonitor();
}, '{{ config('global.URL_COMPUTADORAS_MONITOR') }}'
);
</script>

@endsection
