<div id="botoneraDashboard">
    <div class="col-md-6 col-sm-12 col-xs-12" >
        <div class="input-group">
            <span class="input-group-addon" style="font-size: 12px !important;"> <span class="glyphicon glyphicon-calendar"></span> Fecha</span>
            <input name="fecha" id="fecha" type="text" data-date-format='dd-mm-yyyy' value="{{ date('d-m-Y', strtotime($fecha)) }}" class="form-control" >
            <span class="input-group-btn" >
                <a href="#" class="btn btn-default" onclick="$('#btnConsultar').click()" title="Actualizar"> &nbsp; <i class="fa fa-refresh"></i> &nbsp; </a>
            </span>
        </div>
        <button id="btnConsultar" type="submit" class="btn btn-primary" style='display:none;'></button>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="btn-group btn-group-justified " data-toggle="buttons">
            <input id="starPause" type="checkbox" checked data-toggle="toggle"  data-on="<i class='fa fa-play'></i> Play" data-off="<i class='fa fa-pause'></i> Pause" data-onstyle="success" data-offstyle="danger" >     
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-6">
        <button id="pagNext" type="button" class="btn btn-primary btn-group-justified" data-loading-text="Loading...">Next</button>
    </div>
</div>

<!--
<span class="input-group-btn" >
    <div class="btn-group btn-group-justified " data-toggle="buttons">
        <a href="#" class="btn btn-default" onclick="$('#btnConsultar').click()" title="Actualizar"> &nbsp; <i class="fa fa-refresh"></i> &nbsp; </a>
    </span>
-->