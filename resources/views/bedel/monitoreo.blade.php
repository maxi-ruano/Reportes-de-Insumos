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
actualizarMonitor();

setInterval(Function("actualizarMonitor();"), 5000);
</script>

@endsection
