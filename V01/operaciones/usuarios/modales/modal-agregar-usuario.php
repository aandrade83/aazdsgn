<div id="addUser" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form method="post" action="<?php echo $base_url; ?>/V01/operaciones/usuarios/process/actions/admin-usuarios.php" id="formAddUser">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="field-2" class="control-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="field-2" class="control-label">Clave</label>
                                <input type="text" class="form-control" id="clave" name="clave">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-3" class="control-label">Estado</label>
                                <select class="form-control" id="estado" name="activo">
                                    <option value="">Elija una opción</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>  
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-3" class="control-label">Privilegio</label>
                                <select class="form-control" id="estado" name="privilegio">
                                    <option value="">Elija una opción</option>
                                    <option value="1">Usuario</option>
                                    <option value="2">Admin</option>
                                    <option value="777">Super Admin</option>    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group no-margin">
                                <label for="field-7" class="control-label">Detalle</label>
                                <textarea class="form-control" id="detalle" name="detalle"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="insert">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
                <button type="submit" form="formAddUser" class="btn btn-info waves-effect waves-light">Agregar</button>
            </div>
        </div>
    </div>
</div>