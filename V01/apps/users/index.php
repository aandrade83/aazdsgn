<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");

//$bdBooks = get_all_books($_company);

?>
<!DOCTYPE html>
<html lang="es">

<script type="text/javascript" src="./js/functions.js"></script>


    <?php require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php"); ?>
    <body>

        <div id="wrapper">

            <?php require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/top-bar.php");?>
            <?php require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/menu-principal.php");?>
            <link rel="stylesheet" href="./css/styles.css" >
            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <?/* 
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
                                        */?>
                                    </div>
                                    <h4 class="page-title">SportsBooks</h4>
                                </div>
                            </div>
                        </div>     


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">List of Sportsbooks</h4>
                                        <p class="text-muted font-13 mb-4"></p>
                                        <select id="sel_active"  class="small" data-plugin="customselect" style="display: block;" onchange="load_main()">
                                                        <option value="-1">ALL</option>
                                                        <option selected="selected" value="1">Enabled</option>
                                                        <option value="0">Disabled</option>
                                                        
                                         </select>
                                        
                                         <BR><BR>
                                        <div id="div_content"> 
                                        <table id="dataTables" class="table table-striped dt-responsiveX">
                                            <thead>
                                                <tr class="stucka">
                                                    <th>Id Book</th>
                                                    <th>Bookname</th>
                                                    <th>Short</th>
                                                    <th>Available</th>
                                                </tr>
                                            </thead>
                                        
                                        
                                            <tbody>
                                                <? /* php
                                                  
                                                  foreach($bdBooks AS $book){
                                                ?>
                                                    <tr>
                                                        <td><?php echo $book->vars["id_book"]." / ".$book->vars["id"];?></td>
                                                        <td><?php echo $book->vars["name"];?></td>
                                                        <td><?php echo $book->vars["short"];?></td>
                                                        <td>
                                                        <input type="checkbox" class="Check" id="check_<?php echo $book->vars["id"];?>" <? if($book->vars["available"]) { ?> checked="checked" <? } ?> data-plugin="switchery" data-color="#1bb99a" data-switchery="true" onchange="saveChanges(<?php echo $book->vars["id_book"];?>,<?php echo $book->vars["id_company"];?>)">
                                                            
                                                        </td>
                                                       
                                                    </tr>
                                                <?php } */ ?>

                                            </tbody>
                                        </table>
                                        </div>
                                        
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
            //include("modales/modal-agregar-usuario.php");
            //include("modales/modal-eliminar-usuario.php");
            //include("modales/modal-actualizar-usuario.php");
        ?>
        
    </body>
</html>