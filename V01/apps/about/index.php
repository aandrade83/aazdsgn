<?
include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php");
$data_esp = get_page_data(5);
$en = get_page_data(5,'_en');

$images[0] = '1800 x 768';
$images[5] = '3600 x 1536';
$images[1] = '635x422';
$images[6] = '1270x844 ';
$images[2] = '635x422';
$images[7] = '1270x844 ';

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
                    <div class="page-title-box">
                        <h4 class="page-title">ABOUT US</h4>
                        <p>Especifica una imagen alternativa para pantallas de alta densidad de píxeles, como las pantallas Retina de Apple</p>
                    </div>
                </div>
            </div>     
            <!-- end page title --> 
            
            <? $i =0;
            foreach($data_esp as $esp){ ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="mt-0"><? echo $esp->vars['descripcion']?></h5>
                            <p class="sub-header"></p>
                            <div class="table-responsive">
            

            <div class="editable-table-container">
            <table class="table table-bordered editable-table">
                <thead class="thead">
                    <tr>
                       <th>ID</th>
                        <th>Título</th>
                        <th>Título EN</th>
                        <th>Detalle</th>
                        <th>Detalle EN</th>
                        <th>Texto Link</th>
                        <th>Texto Link EN</th>
                        <th>Nombre Imagen</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="id-cell"><? echo $esp->vars['id'] ?></td>
                        <td class="editable"><? echo $esp->vars['text1'] ?></td>
                        <td class="editable"><? echo $en[$i]->vars['text1'] ?></td>
                        <td class="editable"><? echo $esp->vars['text2'] ?></td>
                        <td class="editable"><? echo $en[$i]->vars['text2'] ?></td>
                        <td class="editable"><? echo $esp->vars['text3'] ?></td>
                        <td class="editable"><? echo $en[$i]->vars['text3'] ?></td>
                        <td class="editable"><? echo str_replace("-".$esp->vars['id'],"",$esp->vars['nombre_imagen']) ?></td>
                        <td class="editable"><? echo $esp->vars['link'] ?></td>
                        
                    </tr>
                     <tr>
                        <td colspan="9">
                            <div class="row">
                                <div class="col-md-6"><? echo $images[$i] ?>
                                    <label for="dropify1"><? echo $esp->vars['text4'] ?></label>
                                    <input type="file" id="dropify1" class="dropify" data-default-file="<? echo $esp->vars['url'] ?>"/>
                                </div>
                               
                                <div class="col-md-6"><? echo $images[$i+5] ?>
                                    <label for="dropify2"><? echo $esp->vars['text5'] ?></label>
                                    <input type="file" id="dropify2" class="dropify" data-default-file="<? echo $esp->vars['url2'] ?>"/>
                                </div>
                               
                            </div>
                            <button class="btn btn-primary edit-btn mt-2"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-success save-btn mt-2" style="display: none;"><i class="fas fa-save"></i></button>
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

                 <? $i++; } ?>  




            </div> <!-- container -->

        </div> <!-- content -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/js/dropify.min.js"></script>
        <?
        include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/footer.php");
    ?>