<div class="input-group">
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    <input name="fecha" id="fecha" type="text" data-date-format='dd-mm-yyyy' value="{{ date('d-m-Y', strtotime($fecha)) }}" class="form-control" >
    
    <div class="btn-group btn-group-justified btn-group-xs" > <!-- data-toggle="buttons" -->
        <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
            <input value="pausar" name='reload' id="pausarReload" type="radio"> &nbsp; <i class="fa fa-pause"></i> &nbsp;
        </label>
        <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
            <input value="start" name='reload' id="startReload" type="radio"> &nbsp; <i class="fa fa-play"></i> &nbsp; 
        </label>
        <label class="btn btn-default" onclick="$('#btnConsultar').click()">
            &nbsp; <i class="fa fa-refresh"></i> &nbsp;
        </label>
    </div>
    <button id="btnConsultar" type="submit" class="btn btn-primary" style='display:none;'></button>
</div>