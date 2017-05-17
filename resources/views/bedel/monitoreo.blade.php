<div class="row">

<legend><h2 class="text-center">Estados Computadoras</h2></legend>

<div class="col-md-55">
  <div class="thumbnail">
    <div class="image view view-first">
      <div class="thumbnail text-center">
        <img alt="..." class="img-pregunta img-responsive" style = "height: 60%;width: auto;" src="http://192.168.76.215/deve_teorico/public/production/images/persona4.jpg">
      </div>
    </div>
    <div class="caption text-center">
      <p><b>Juan Ojeda</b> DNI: 92883274</p>
      <h4><span class="label label-success"><span>PC: 1 </span> Disponible</span></h4>
    </div>
  </div>
</div>
  <div class="col-md-55">
    <div class="thumbnail">
      <div class="image view view-first">
        <div class="thumbnail text-center">
          <img alt="..." class="img-pregunta img-responsive" style = "height: 60%;width: auto;" src="http://192.168.76.215/deve_teorico/public/production/images/persona3.jpg">
        </div>
      </div>
      <div class="caption text-center">
        <p><b>Juan Perez</b> DNI: 92958921</p>
        <h4><span class="label label-danger"><span>PC: 2 </span> Ocupado</span></h4>
      </div>
    </div>
  </div>
  <div class="col-md-55">
    <div class="thumbnail">
      <div class="image view view-first">
        <div class="thumbnail text-center">
          <img alt="..." class="img-pregunta img-responsive" style = "height: 60%;width: auto;" src="http://192.168.76.215/deve_teorico/public/production/images/persona2.jpg">
        </div>
      </div>
      <div class="caption text-center">
        <p><b>Daniel Valdes</b> DNI: 93271366</p>
        <h4><span class="label label-success"><span>PC: 3 </span> Disponible</span></h4>
      </div>
    </div>
  </div>
  <div class="col-md-55">
    <div class="thumbnail">
      <div class="image view view-first">
        <div class="thumbnail text-center">
          <img alt="..." class="img-pregunta img-responsive" style = "height: 60%;width: auto;" src="http://192.168.76.215/deve_teorico/public/production/images/persona1.jpg">
        </div>
      </div>
      <div class="caption text-center">
        <p><b>Ernesto Diaz</b> DNI: 93320203</p>
        <h4><span class="label label-success"><span>PC: 4 </span> Disponible</span></h4>
      </div>
    </div>
  </div>
  <div class="col-md-55">
    <div class="thumbnail">
      <div class="image view view-first">
        <div class="thumbnail text-center">
          <img alt="..." class="img-pregunta img-responsive" style = "height: 60%;width: auto;" src="http://192.168.76.200/data/fotos/001138031328M.JPG">
        </div>
      </div>
      <div class="caption text-center">
        <p><b>Carlos Ortega</b> DNI: 10861570</p>
        <h4><span class="label label-danger"><span>PC: 5 </span> Ocupado</span></h4>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<!-- validator -->
<script type="text/javascript">

function actualizarMonitor(){
  $.ajax({
      type: "POST",
      url: '{{ config('app.APP_URL') }}'+'{{ config('app.URL_ESTADO_COMPUTADORAS') }}',
      //data: {examen_id: examen_id, respuesta_id: respuesta_id, pregunta_id: pregunta_id},
      //async:false,
      beforeSend: function(){

      },
      success: function( msg ) {
        renderMonitorComputadoras(msg)
      },

      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
      }
  });
}

function renderMonitorComputadoras(msg){
  console.log(smg);
}

</script>
@endsection
