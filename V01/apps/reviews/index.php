<?
include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php");
$reviews = get_google_reviews_for_manager();
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
                        <h4 class="page-title">REVIEWS</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="mt-0">Google Reviews</h5>
                            <p class="sub-header">Controle unicamente si una review se muestra o no en el sitio publico. El resto de la informacion es de solo lectura.</p>
                            <div class="table-responsive">

                                <table class="table" id="reviewsTable">
                                    <thead>
                                        <tr>
                                            <th>Persona</th>
                                            <th>Fecha</th>
                                            <th>Rating</th>
                                            <th>Comentario</th>
                                            <th>Mostrar</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?if(!empty($reviews)){
                                            foreach($reviews as $review){
                                                $name = htmlspecialchars((string) ($review->vars['reviewer_display_name'] ?? ''), ENT_QUOTES, 'UTF-8');

                                                $create_time = $review->vars['google_create_time'] ?? null;
                                                $date_display = '';
                                                if(!empty($create_time)){
                                                    $ts = strtotime($create_time);
                                                    if($ts !== false){
                                                        $date_display = date('d/m/Y', $ts);
                                                    }
                                                }
                                                $date_safe = htmlspecialchars($date_display, ENT_QUOTES, 'UTF-8');

                                                $stars = (int) ($review->vars['star_rating'] ?? 0);

                                                $comment = trim((string) ($review->vars['comment_original'] ?? ''));
                                                $comment_preview = $comment !== '' ? text_preview($comment, 100) : 'Sin comentario';
                                                $comment_safe = htmlspecialchars($comment_preview, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                                                $show_review = (int) ($review->vars['show_review'] ?? 0);
                                                $review_id_safe = htmlspecialchars((string) ($review->vars['review_id'] ?? ''), ENT_QUOTES, 'UTF-8');
                                                
                                               
                                               ?>
                                                <tr data-review-id="<? echo $review_id_safe; ?>">
                                                    <td><? echo $name; ?></td>
                                                    <td><? echo $date_safe; ?></td>
                                                    <td><? echo $stars; ?> / 5</td>
                                                    <td><? echo $comment_safe; ?></td>
                                                    <td class="show-cell" data-value="<? echo $show_review; ?>">
                                                        <span class="badge <? echo $show_review === 1 ? 'badge-success' : 'badge-danger'; ?>">
                                                            <? echo $show_review === 1 ? 'Si' : 'No'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm edit-btn"><i class="fas fa-edit"></i> Editar</button>
                                                        <button class="btn btn-success btn-sm save-btn" style="display: none;"><i class="fas fa-save"></i> Guardar</button>
                                                    </td>
                                                </tr>
                                                <?
                                            }
                                        } else { ?>
                                            <tr>
                                                <td colspan="6">No hay reviews registradas.</td>
                                            </tr>
                                        <? } ?>
                                    </tbody>
                                </table>

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
