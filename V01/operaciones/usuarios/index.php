<?php
require_once("includes.php");

//$listaUsuarios=getUsuarios();
//print_r($listaUsuarios);

?>
<!DOCTYPE html>
<html lang="es">
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php"); ?>
    <body>

        <div id="wrapper">

            <?php require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/top-bar.php");?>
            <?php require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/menu-principal.php");?>

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <form class="form-inline">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control border-white" id="dash-daterange">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text bg-blue border-blue text-white">
                                                            <i class="mdi mdi-calendar-range font-13"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="javascript: void(0);" class="btn btn-blue btn-sm ml-2">
                                                <i class="mdi mdi-autorenew"></i>
                                            </a>
                                            <div class="btn btn-blue btn-sm ml-1" data-toggle="modal" data-target="#addUser">
                                                <i class="fa fa-plus"></i>
                                            </div>
                                        </form>
                                    </div>
                                    <h4 class="page-title">Usuarios</h4>
                                </div>
                            </div>
                        </div>     


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Lista de usuarios</h4>
                                        <p class="text-muted font-13 mb-4"></p>

                                        <table id="dataTables" class="table table-striped dt-responsiveX">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Usuario</th>
                                                    <th>Estado</th>
                                                    <th>Privilegio</th>
                                                    <th style="width: 100px;">Detalle</th>
                                                    <th style="width: 20px;"><i class="fa fa-cog"></i></th>
                                                </tr>
                                            </thead>
                                        
                                        
                                            <tbody>
                                                <?php
                                                    $activo[0]="Inactivo";
                                                    $activo[1]="Activo";
                                                    //////////////////////
                                                    $priv[1]="Usuario";
                                                    $priv[2]="Admin";
                                                    $priv[777]="Super Admin";
                                                    $listaUsuarios = array();
                                                    foreach($listaUsuarios AS $lu){
                                                ?>
                                                    <tr>
                                                        <td><?php echo $lu["nombre"];?></td>
                                                        <td><?php echo $lu["usuario"];?></td>
                                                        <td class="estadoW"><?php echo $activo[$lu["activo"]];?></td>
                                                        <td class="priviW"><?php echo $priv[$lu["privilegio"]];?></td>
                                                        <td><?php echo $lu["detalle"];?></td>
                                                        <td>
                                                            <div class="btn-group dropleft">
                                                                <div class="btn btn-primary waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-tools"></i>
                                                                </div>
                                                                <div class="dropdown-menu">
                                                                    <a href="#" class="dropdown-item updateUser" data-toggle="modal" data-target="#updateUser"> Editar</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a href="#" class="dropdown-item delUser" data-toggle="modal" data-target="#delUser"> Borrar</a>
                                                                </div>
                                                            </div>
<!-- campos para llenar form de edición de usuarios -->
<input type="hidden" class="id" value="<?php echo $lu['id'];?>">
<input type="hidden" class="nombre" value="<?php echo $lu['nombre'];?>">
<input type="hidden" class="usuario" value="<?php echo $lu['usuario'];?>">
<input type="hidden" class="activo" value="<?php echo $lu['activo'];?>">
<input type="hidden" class="privilegio" value="<?php echo $lu['privilegio'];?>">
<input type="hidden" class="detalle" value="<?php echo $lu['detalle'];?>">



                                                        </td>
                                                    </tr>
                                                <?php }?>

                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <?php require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/footer.php");?>

            </div>
        </div>

        <div class="rightbar-overlay"></div>

        <?php require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/pie-pagina.php");?>
        

        <?php 
            include("modales/modal-agregar-usuario.php");
            include("modales/modal-eliminar-usuario.php");
            include("modales/modal-actualizar-usuario.php");
        ?>
        
    </body>
</html>