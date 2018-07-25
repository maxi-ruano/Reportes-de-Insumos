
<div class="modal fade" id="deleteModal{{$id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title red" id="myModalLabel"> 
                <i class="fa fa-warning"></i> 
                Confirmar Borrado
            </h4>
        </div>
        <div class="modal-body text-center">
               <h2>¿De verdad quieres borrar estos registros? </h2>
                Este proceso no se puede deshacer.
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger"> Si, borrar!</button>
        </div>
        </div>
    </div>
</div>