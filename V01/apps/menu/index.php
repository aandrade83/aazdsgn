<?
include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php");
$data_esp = get_page_data(1);
$data_en = get_page_data(1,'_en');

?>
<link rel="stylesheet" href="styles.css">

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
                    <div class="page-title-box">
                        <h4 class="page-title">MENU</h4>
                    </div>
                </div>
            </div>     
            <!-- end page title --> 

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="mt-0">MENU PRINCIPAL</h5>
                            <p class="sub-header">Edite el orden de las paginas, tomar en cuenta que la ultima posicion va a ser la del telefono.</p>
                            <div class="table-responsive">
                                         
                                           <table class="table" id="editableTable">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Menu</th>
                                                    <th>Eng</th>
                                                    <th>Link</th>
                                                    <th>Pos</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?if(!empty($data_esp)){
                                                    $i = 0;
                                                    foreach($data_esp as $menu){ 
                                                        ?>
                                                        <tr>
                                                           <td class="id-cell"><? echo $menu->vars['id']?></td>
                                                           <td class="editable"><? echo $menu->vars['descripcion']?></td>
                                                           <td class="editable"><? echo $data_en[$i]->vars['descripcion']?></td>
                                                           <td class="editable"><? echo $menu->vars['link']?></td>
                                                           <td class="editable"><? echo $menu->vars['pos']?></td>
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
                </div> <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->


        <?
        include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/footer.php");
    ?>