<div class="row">

<h2 class="text-center">Estados Computadoras</h2>


<div class="col-md-55" style="">
  <div class="thumbnail fondo-pc-ocupado" style = "height: auto;">
    <div class="row">
      <div class="col-md-6">
          <img  class="img-pregunta img-responsive"  style = "height: 100%; width: 100%;" src="http://192.168.76.200/data/fotos/001138031328M.JPG" alt="Generic placeholder thumbnail">
      </div>
      <div class="col-md-6" style="color: #333; height: 40%; padding: 1px;">
        <ul class="list-unstyled">
          <li><b>PC: 1</b></li>
          <li>DOC:95579680</li>
          <li>Juan Carlos</li>
          <li>Ojeda Gomez</li>
          <li><b><span class="label label-success">REPROBADO </span></b></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="col-md-55" style="">
  <div class="thumbnail" style = "height: auto; background-color: #E6E6E6;">
    <div class="row">
      <div class="col-md-6">
          <img  class="img-pregunta img-responsive"  style = "height: 100%; width: 100%;" src="http://192.168.76.200/data/fotos/001138031328M.JPG" alt="Generic placeholder thumbnail">
      </div>
      <div class="col-md-6" style="color: #333; height: 40%; padding: 1px;">
        <ul class="list-unstyled">
          <li><b>PC: 1</b></li>
          <li>DOC:95579680</li>
          <li>Juan Carlos</li>
          <li>Ojeda Gomez</li>
          <li><b><span class="label label-danger">Reprobado </span></b></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="col-md-55" style="">
  <div class="thumbnail" style = "height: auto; background-color: #F78181;">
    <div class="row">
      <div class="col-md-6">
          <img  class="img-pregunta img-responsive"  style = "height: 100%; width: 100%;" src="http://192.168.76.200/data/fotos/001138031328M.JPG" alt="Generic placeholder thumbnail">
      </div>
      <div class="col-md-6" style="color: #333; height: 40%; padding: 1px;">
        <ul class="list-unstyled">
          <li><b>PC: 1</b></li>
          <li>DOC:95579680</li>
          <li>Juan Carlos</li>
          <li>Ojeda Gomez</li>
          <li><b><span class="label label-danger">Reprobado </span></b></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="col-md-55" style="">
  <div class="thumbnail" style = "height: auto; background-color: #A9F5A9;">
    <div class="row">
      <div class="col-md-6">
          <img  class="img-pregunta img-responsive"  style = "height: 100%; width: 100%;" src="http://192.168.76.200/data/fotos/001138031328M.JPG" alt="Generic placeholder thumbnail">
      </div>
      <div class="col-md-6" style="color: #333; height: 40%; padding: 1px;">
        <ul class="list-unstyled">
          <li><b>PC: 1</b></li>
          <li>DOC:95579680</li>
          <li>Juan Carlos</li>
          <li>Ojeda Gomez</li>
          <li><b><span class="label label-danger">Reprobado </span></b></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="col-md-55" style="">
  <div class="thumbnail" style = "height: auto; background-color: #A9F5A9;">
    <div class="row">
      <div class="col-md-6">
          <img  class="img-pregunta img-responsive"  style = "height: 100%; width: 100%;" src="http://192.168.76.200/data/fotos/001138031328M.JPG" alt="Generic placeholder thumbnail">
      </div>
      <div class="col-md-6" style="color: #333; height: 40%; padding: 1px;">
        <ul class="list-unstyled">
          <li><b>PC: 1</b></li>
          <li>DOC:95579680</li>
          <li>Juan Carlos</li>
          <li>Ojeda Gomez</li>
          <li><b><span class="label label-warning">EN CURSO </span></b></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="col-md-55" style="">
  <div class="thumbnail fondo-pc-libre" style = "height: auto;">
    <div class="row">
      <div class="col-md-6">
          <img  class="img-pregunta img-responsive"  style = "height: 100%; width: 100%;" src="http://192.168.76.200/data/fotos/001138031328M.JPG" alt="Generic placeholder thumbnail">
      </div>
      <div class="col-md-6" style="color: #333; height: 40%; padding: 1px;">
        <ul class="list-unstyled">
          <li><b>PC: 1</b></li>
          <li>DOC:95579680</li>
          <li>Juan Carlos</li>
          <li>Ojeda Gomez</li>
          <li><b><span class="label label-success">REPROBADO </span></b></li>
        </ul>
      </div>
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
@section('css')
<style>
    .fondo-pc-libre {
      background-color: #A9F5A9;
    }
    -- rojo A9F5A9
    .fondo-pc-ocupado {
      background-color: #adadad;
    }
</style>
@endsection
