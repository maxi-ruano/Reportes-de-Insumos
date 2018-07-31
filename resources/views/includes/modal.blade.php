
<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
            <button type="button" class="btn btn-danger" id="delete-btn"> Si, borrar!</button>
        </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            //Modal delete confirmar antes de borrar
            $('table[data-form="deleteForm"]').on('click', '.form-delete', function(e){
                e.preventDefault();
                var $form=$(this);
                $('#modal-delete').on('click', '#delete-btn', function(){
                    $form.submit();
                });
            });
        });
    </script>
@endpush