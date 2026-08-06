<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");

$action  = param("ac");

switch ($action){

case "About":

    // --------- INPUTS ---------
    $id         = param("id");
    $text1      = param('titulo', false);
    $text1_en   = param('titulo_en', false);
    $text2      = param('detalle', false);
    $text2_en   = param('detalle_en', false);
    $text3      = param('texto_link', false);
    $text3_en   = param('texto_link_en', false);
    $link       = param('link', false);

    // Base SEO ingresada por el usuario (NO tocar esta variable luego)
    $nombre_imagen_base = param('nombre_imagen');
    $nombre_imagen_base = trim($nombre_imagen_base);
    $nombre_imagen_base = str_replace([' ', '_'], '-', $nombre_imagen_base);
    $nombre_imagen_base = preg_replace('/-+/', '-', $nombre_imagen_base);

    $alt         = trim($text1." ".param('nombre_imagen'));
    $img_control = false;
    $data        = [];

    $pag_esp = get_content_by_id($id);
    $pag_en  = get_content_by_id($id, "_en");

    // Sufijos configurados en CMS (si vinieran vacíos, caen a defaults)
    $sufijo1 = !empty($pag_esp->vars['text4']) ? $pag_esp->vars['text4'] : 'img1';
    $sufijo2 = !empty($pag_esp->vars['text5']) ? $pag_esp->vars['text5'] : 'img2';

    // Normaliza sufijos
    $sufijo1 = preg_replace('/[^a-zA-Z0-9\-]+/','-', str_replace([' ', '_'], '-', $sufijo1));
    $sufijo2 = preg_replace('/[^a-zA-Z0-9\-]+/','-', str_replace([' ', '_'], '-', $sufijo2));
    $sufijo1 = preg_replace('/-+/', '-', $sufijo1);
    $sufijo2 = preg_replace('/-+/', '-', $sufijo2);

    // Para devolver URLs actualizadas si hubo upload
    $url1_file = null;
    $url2_file = null;

    // --------- SUBIDA DE IMÁGENES ---------
    // Acepta si subes 1 o 2 imágenes. Cada una se maneja independiente.
    $destDir = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/about/uploads/";
    if (!is_dir($destDir)) { @mkdir($destDir, 0775, true); }

    // Helper para subir una imagen
    $subir = function($fileKey, $indice, $sufijo) use ($id, $nombre_imagen_base, $destDir) {
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            return [false, null, "No file for $fileKey"];
        }

        $tmp  = $_FILES[$fileKey]['tmp_name'];
        $name = $_FILES[$fileKey]['name'];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg','jpeg','png','webp','gif','avif'])) {
            return [false, null, "Extensión no permitida: $ext"];
        }

        // Nombre SEO: <base>-<id>-<sufijo>-<indice>.<ext>
        $final = "{$nombre_imagen_base}-{$id}-{$sufijo}-{$indice}.{$ext}";
        $final = preg_replace('/-+/', '-', $final);

        $dest = $destDir.$final;
        if (file_exists($dest)) {
            // Evita sobrescribir si ya existe
            $uniq  = uniqid('', true);
            $final = "{$nombre_imagen_base}-{$id}-{$sufijo}-{$indice}-{$uniq}.{$ext}";
            $final = preg_replace('/-+/', '-', $final);
            $dest  = $destDir.$final;
        }

        if (!move_uploaded_file($tmp, $dest)) {
            return [false, null, "No se pudo mover el archivo {$fileKey}"];
        }

        return [true, $final, null];
    };

    // image1
    if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
        list($ok1, $file1, $err1) = $subir('image1', 1, $sufijo1);
        if ($ok1) { $url1_file = $file1; $img_control = true; }
        else { $data['error_image1'] = $err1; }
    }

    // image2
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
        list($ok2, $file2, $err2) = $subir('image2', 2, $sufijo2);
        if ($ok2) { $url2_file = $file2; $img_control = true; }
        else { $data['error_image2'] = $err2; }
    }

    // --------- GUARDAR (ES) ---------
    $pag_esp->vars['text1'] = $text1;
    $pag_esp->vars['text2'] = $text2;
    $pag_esp->vars['text3'] = $text3;
    $pag_esp->vars['link']  = $link;

    if ($img_control) {
        // Guarda la base SEO (sin ID ni sufijo) para mantener consistencia
        $pag_esp->vars['nombre_imagen'] = $nombre_imagen_base;
        if ($url1_file) $pag_esp->vars['url']  = 'https://images.aazdsgn.com/about/uploads/'.$url1_file;
        if ($url2_file) $pag_esp->vars['url2'] = 'https://images.aazdsgn.com/about/uploads/'.$url2_file;
        $pag_esp->vars['alt'] = $alt;
    }

    $ok_es = $pag_esp->update(array('text1','text2','text3','link','nombre_imagen','alt','url','url2'));

    // --------- GUARDAR (EN) ---------
    $pag_en->vars['text1'] = $text1_en;
    $pag_en->vars['text2'] = $text2_en;
    $pag_en->vars['text3'] = $text3_en;
    $pag_en->vars['link']  = $link;

    if ($img_control) {
        $pag_en->vars['nombre_imagen'] = $nombre_imagen_base;
        if ($url1_file) $pag_en->vars['url']  = 'https://images.aazdsgn.com/about/uploads/'.$url1_file;
        if ($url2_file) $pag_en->vars['url2'] = 'https://images.aazdsgn.com/about/uploads/'.$url2_file;
        $pag_en->vars['alt'] = $alt;
    }

    $ok_en = $pag_en->update(array('text1','text2','text3','link','nombre_imagen','alt','url','url2'));

    // --------- RESPUESTA ---------
    $data['control'] = ($ok_es && $ok_en) ? 1 : 0;
    if ($url1_file) $data['url']  = 'https://images.aazdsgn.com/about/uploads/'.$url1_file;
    if ($url2_file) $data['url2'] = 'https://images.aazdsgn.com/about/uploads/'.$url2_file;

    echo json_encode($data);
    break;

default:
    // No-op
    break;

}
?>