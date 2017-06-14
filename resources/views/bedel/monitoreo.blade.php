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
var imagenDefault = '{{ config('app.url') }}'+'{{ config('global.IMAGE_USER_DEFAULT') }}';
var sucursal_id = "{{ session('usuario_sucursal_id') }}";
actualizarMonitor(sucursal_id);
setInterval(Function("actualizarMonitor("+sucursal_id+");"), 15000);
</script>

@endsection
