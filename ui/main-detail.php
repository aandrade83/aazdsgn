<?
  
   $id = param('id');
  if (!isset($proyect) || !$proyect) { $proyect = get_project($id,$lang); }
  $categories = get_page_data(3,$lang,'id');
  $services = get_page_data(4,$lang,'id');
  $images = get_projects_images($id);
  $str_services = "SERVICIOS REALIZADOS";  
  $area = "AREA";
  $ubicacion = "UBICACION";
  $ano = "AÑO";
  $tipo = "TIPO PROYECTO";
 
  if($lang == "_en"){
   $area = "AREA";
   $ubicacion = "LOCATION";
   $ano = "YEAR";
   $tipo = "PROJECT TYPE";
   $str_services = "SERVICES PROVIDED";
  }

  

?>

 <main>
      <article class="article">
        <header class="article__header">
          <div class="container">
            <h1 class="article__heading heading heading--size-large"><? echo $proyect->vars['titulo'] ?></h1>
            <ul class="article__header-meta project-meta">
              <li class="project-meta__item" data-aos="fade">
                
                <div class="project-meta__item-title"><? echo $area ?></div>
                <div class="project-meta__item-text"><? echo $proyect->vars['area'] ?></div>
              </li>
              <li class="project-meta__item" data-aos="fade" data-aos-delay="200">
                <div class="project-meta__item-title"><? echo $ano ?></div>
                <div class="project-meta__item-text"><? echo $proyect->vars['year'] ?></div>
              </li>
              <li class="project-meta__item" data-aos="fade" data-aos-delay="400">
                <div class="project-meta__item-title"><? echo $tipo ?></div>
                <div class="project-meta__item-text"><? echo $categories[$proyect->vars['cat']]->vars['descripcion'] ?></div>
              </li>
              <li class="project-meta__item" data-aos="fade" data-aos-delay="600">
                <div class="project-meta__item-title"><? echo $ubicacion ?></div>
                <div class="project-meta__item-text"><? echo $proyect->vars['ubicacion'] ?></div>
              </li>
            </ul>
          </div>
        </header>
        <div class="article__project-hero" data-aos="fade">
          <img src="<? echo $images['image1']->vars['url']?>" srcset="<? echo $images['image1']->vars['url2']?> 2x" width="1800" height="768" alt="Villa near Moscow photo">
        </div>
        <div class="container">
          <div class="article__project-text" data-aos="fade">
            <p style="text-align: justify;">
              <? echo nl2br(htmlspecialchars($proyect->vars['description'])); ?>

            </p>
          </div>
        </div>
        <div class="article__project-images container">
          <div class="row">
            <div class="col-12 col-md-6" data-aos="fade">
              <img src="<? echo $images['image2']->vars['url']?>" srcset="<? echo $images['image2']->vars['url2']?> 2x" width="886" height="886" alt="">
            </div>
            <div class="article__project-images-cell col-12 col-md-6" data-aos="fade">
              <img src="<? echo $images['image3']->vars['url']?>" srcset="<? echo $images['image3']->vars['url2']?> 2x" width="886" height="428" alt="">
              <div class="row">
                <div class="article__project-images-cell col-12 col-md-6" data-aos="fade">
                  <img src="<? echo $images['image4']->vars['url']?>" srcset="<? echo $images['image4']->vars['url2']?> 2x" width="428" height="428" alt="">
                </div>
                <div class="article__project-images-cell col-12 col-md-6" data-aos="fade">
                  <img src="<? echo $images['image5']->vars['url']?>" srcset="<? echo $images['image5']->vars['url2']?> 2x" width="428" height="428" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
        <? $ser = explode(',',$proyect->vars['services']);?>
        <section class="webpage__services-provided services-provided">
          <div class="container">
            <h2 class="services-provided__heading heading heading--size-small" data-aos="fade"><? echo $str_services ?></h2>
            
              <ul class="article__header-meta project-meta">
              <? foreach($ser as $service){ ?>
              <li class="project-meta__item" data-aos="fade">
                
                <div class="project-meta__item-title"><? echo $services[$service]->vars['descripcion'];  ?></div>
                
              </li>
              <? } ?>
            </ul>
            </div>
          
        </section>
     <? if (strlen($proyect->vars['video']) >= 20) { ?>
        <section class="webpage__services-provided services-provided">
 <div class="container">
    <h2 class="services-provided__heading heading heading--size-small" data-aos="fade">
      VIDEO
    </h2>
     <!-- Video -->
    <div class="video-wrapper" data-aos="fade">
      <iframe
        src="<? echo $proyect->vars['video']?>"
        title="Video de servicios"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        allowfullscreen>
      </iframe>
    </div>
  </div>
</section>
<? } ?>


        
      </article>
    </main>
