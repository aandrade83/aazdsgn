<div id="delUser" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form method="post"  action="<?php echo $base_url; ?>/V01/operaciones/usuarios/process/actions/admin-usuarios.php" id="formDelUser">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Desea borrar el registro</label>
                                <input type="hidden"  class="did" required name="id" id="did">
                                <input type="text" class="form-control duser" id="duser" readonly>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="action" value="delete">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
                <button type="submit" form="formDelUser" class="btn btn-danger waves-effect waves-light">Borrar</button>
            </div>
        </div>
    </div>
</div>

<script>
  $(".delUser").on("click",function(){
     $("#duser").val($(this).closest('td').find('.usuario').val());
     $("#did").val($(this).closest('td').find('.id').val());
  });
</script>