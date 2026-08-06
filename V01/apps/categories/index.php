<?
include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php");
$data_esp = get_page_data(3);
$data_en = get_page_data(3,'_en');

?>
<link rel="stylesheet" href="./css/styles.css">

<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="tabs">
                        <a class="tab" href="<?php echo $base_url; ?>/V01/apps/projects/index.php">Proyectos</a>
                        <a class="tab" href="<?php echo $base_url; ?>/V01/apps/categories/index.php">Categorías</a>
                        <a class="tab" href="<?php echo $base_url; ?>/V01/apps/services/index.php">Servicios</a>
                      </div>
                </div>
            </div>     
            <!-- end page title --> 

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="mt-0">Tipos de Proyectos</h5>
                            <p class="sub-header">Categorias de los Proyectos</p>
                            <div class="table-responsive">
                                         
                                           <table class="table" id="editableTable">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Categoria</th>
                                                    <th>Eng</th>
                                                    <th>Link</th>
                                                    <th>Pos</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?if(!empty($data_esp)){
                                                    $i = 0;
                                                    foreach($data_esp as $cat){ 
                                                        ?>
                                                        <tr>
                                                           <td class="id-cell"><? echo $cat->vars['id']?></td>
                                                           <td class="editable"><? echo $cat->vars['descripcion']?></td>
                                                           <td class="editable"><? echo $data_en[$i]->vars['descripcion']?></td>
                                                           <td class="editable"><? echo $cat->vars['link']?></td>
                                                           <td class="editable"><? echo $cat->vars['pos']?></td>
                                                           <td>
                                                             <button class="btn btn-primary edit-btn"><i class="fas fa-edit"></i></button>
                                                             <button class="btn btn-success save-btn" style="display: none;"><i class="fas fa-save"></i></button>
                                                           </td>
                                                       </tr>
                                                   <? $i++; } 
                                               }?>
                                               <!-- Agrega más filas según sea necesario -->
                                           </tbody>
                                       </table>

                                   </div> <!-- end .table-responsive-->
                               </div> <!-- end card-body -->
                           </div> <!-- end card -->
                       </div> <!-- end col -->
                   </div> <!-- end row -->



                    
                   <?/* 
                   <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h5 class="mt-0">Menu Secundarios</h5>
                                <p class="sub-header">Espacio Para manejar Submenus.</p>
                                <div class="table-responsive">

                                </div> <!-- end .table-responsive-->
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                </div> <!-- end row --> */?>

            </div> <!-- container -->

        </div> <!-- content -->


        <?
        include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/footer.php");
    ?>