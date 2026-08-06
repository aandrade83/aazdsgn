<?
include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php");

// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data_esp = get_projects();
//$en = get_projects('_en');
$categorias = get_page_data(3,'_esp','id');
$servicios = get_page_data(4,'_esp','id');

if(isset($_GET['prj'])){
 $id_proyect = $_GET['prj'];
} else { $id_proyect = 0 ; }


?>

<link rel="stylesheet" href="./css/styles.css">
<style>
  .project {
    display: block; /* Ocultar por defecto */
    padding: 10px;
    border: 1px solid #ccc;
    margin-top: 5px;
}


.p_name.row {
    cursor: pointer;
    background-color: #f0f0f0; /* Fondo gris claro */
    border: 1px solid #ccc; /* Borde gris */
    padding: 10px;
    width: 100%; /* Ocupa todo el ancho */
    box-sizing: border-box; /* Asegura que el padding no expanda el ancho */
    text-align: center; /* Centrar el texto */
    font-weight: bold; /* Texto en negrita */
}

</style>


<script>
    /*
    function toggleDiv(id) {
        var div = document.getElementById(id);
        if (div.style.display === "none" || div.style.display === "") {
            div.style.display = "block";
        } else {
            div.style.display = "none";
        }
    }*/
</script>

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

        <? $i =0;
        
                     
           ?>
           <BR>    
           <div class="p_name row" > 
              <form method="GET">
               
                <select id="prj" name="prj">
                  
                  <? foreach($data_esp as $prj_esp){ ?>
                     <option   <? if($id_proyect == $prj_esp->vars['id'] ){ echo 'selected="selected"';  } ?>  value="<? echo $prj_esp->vars['id']; ?>" ><? echo $prj_esp->vars['titulo']; ?></option>

                  <? } ?>  
                 </select>   
                 
                 <input type="submit" value="CONTINUAR" />
             </form>
           </div>

          <? if (isset($_GET['prj'])) { ?> 

          <?
             
             $images = get_projects_images($id_proyect) ; 
             $esp = get_project($id_proyect);
             $en = get_project($id_proyect,'_en');

          ?>  

           <div id="p_<? echo $esp->vars['id']?>" class="project">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="mt-0"><? echo $esp->vars['titulo']?></h5>
                            <p class="sub-header"></p>
                            <div class="table-responsive">


                                <div class="editable-table-container">
                                    <table class="table table-bordered editable-table">
                                        <thead class="thead">
                                            <tr>
                                             <th>ID</th>
                                             <th>Título</th>
                                             <th>Título EN</th>
                                             <th colspan="1">Categoria</th>
                                             <th colspan="2">Detalle</th>
                                             <th colspan="2">Detalle EN</th>
                                             

                                         </tr>
                                     </thead>
                                     <tbody>
                                        <tr>
                                            <td class="id-cell"><? echo $esp->vars['id'] ?></td>
                                            <td class="editable"><? echo $esp->vars['titulo'] ?></td>
                                            <td class="editable"><? echo $en->vars['titulo'] ?></td>
                                            <td colspan="1" class="cat">
                                               <select id="categoria" name="categoria" disabled >
                                                  <? foreach ($categorias    as $cat) { ?>
                                                      <option value="<? echo $cat->vars['id']?>"  <? if($cat->vars['id'] == $esp->vars['cat']) { echo 'selected = selected ';}  ?>><? echo $cat->vars['descripcion']?></option>
                                                  <? }?>
                                              </select >


                                          </td>
                                          <td colspan="2" class="editable"><? echo $esp->vars['description'] ?></td>
                                          <td colspan="2" class="editable"><? echo $en->vars['description'] ?></td>
                                          


                                      </tr>
                                      <tr >
                                          <td class=""> - </td>
                                          <td title="Area" class="editable"><?  if($esp->vars['area']) { echo $esp->vars['area']; } else { echo "Area"; }?></td>
                                          <td title="Ubicacion"  class="editable"><?  if($esp->vars['ubicacion']) { echo $esp->vars['ubicacion']; } else { echo "Ubicacion"; }?></td>
                                          <td title="Año" class="editable"><? if($esp->vars['year']) { echo $esp->vars['year']; } else { echo "Año"; } ?></td>
                                          <td title="Video" colspan="2"  colspan=""class="editable"><? if($esp->vars['video']) { echo $esp->vars['video']; } else { echo "Video (Optional)"; } ?></td>
                                          <td title="Año" class="editable"><? if($esp->vars['pos']) { echo $esp->vars['pos']; } else { echo "Posicion"; } ?></td>

                                        
                                    </tr>

                                      <tr class="service-row">
                                        <td colspan="9"> <!-- Para que abarque toda la fila -->
                                           <? $Pser = explode( ',', $esp->vars['services']);

                                           $checked_value = isset($Pser[0]) ? $Pser[0] : null; 
                                            
                                            foreach($servicios  as $service  ) { ?>
                                            <label>
                                                <input type="checkbox" name="service_<? echo $service->vars['id']?>" <? if (in_array($service->vars['id'], $Pser)) {  echo 'checked="checked"';}
  ?> value="<? echo $service->vars['id']?>" class="service" disabled> <? echo $service->vars['descripcion']?>&nbsp;&nbsp;<BR>
                                            </label>
                                        <? } ?>

                                            
                                        </td>
                                    </tr>




                                    <? $k=1;
                                    foreach ($images  as $img ) { ?> 
                                       <tr>
                                        <td colspan="9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label> Nombre: </label>
                                                    <input type="text" class="img_name" value="<? echo $img->vars['nombre'] ?>" disabled>          
                                                    <label style="float: inline-end;" for="dropify<? echo $k ?>"><? echo $img->vars['size1'] ?></label>
                                                    <input type="file" id="dropify<? echo $k ?>" class="dropify" data-default-file="<? echo $img->vars['url'] ?>"/>
                                                </div>
                                                <div class="col-md-6">
                                                    <label  style="float: inline-end;" for="dropify<? echo $k + 1 ?>"><? echo $img->vars['size2'] ?></label>
                                                    <input type="file" id="dropify<? echo $k + 1 ?>" class="dropify" data-default-file="<? echo $img->vars['url2'] ?>"/>
                                                </div>
                                            </div>


                                        </td>
                                    </tr>


                                    <?  $k++; ?>
                                <? } ?>


                                <tr><td colspan="9">
                                    <button class="btn btn-primary edit-btn mt-2">
    <i class="fas fa-edit"></i>
</button>

<button class="btn btn-danger delete-btn mt-2" data-id="<? echo $esp->vars['id'] ?>">
    <i class="fas fa-trash"></i>
</button>

<button class="btn btn-success save-btn mt-2" style="display: none;">
    <i class="fas fa-save"></i>
</button>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>



            </div> <!-- end .table-responsive-->
        </div> <!-- end card-body -->
    </div> <!-- end card -->
</div> <!-- end col -->
</div> <!-- end row -->
</div> <!-- end project -->

<? } ?>




</div> <!-- container -->

</div> <!-- content -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/js/dropify.min.js"></script>
<?
include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/footer.php");
?>