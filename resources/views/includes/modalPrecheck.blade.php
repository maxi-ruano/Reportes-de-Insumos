<div class="modal fade" id="modal-precheck" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title text-center" id="myModalLabel"> 
                <i class="glyphicon glyphicon-check"></i> 
                Validaciones Precheck
            </h4>
        </div>
        <div class="modal-body text-center">
               @include('safit.resultCheckModoAutonomo')
        </div>
        <div class="modal-footer">
            <button type="button" onclick="iniciarRegargarPagina()" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
</div>
