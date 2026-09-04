  <?
   $categories = get_page_data(3,$lang);
   $main =  get_projects($lang,0);
   $total = count($main);
   $ver = "Ver más";
   if($lang == "_en"){ $ver = "See More";}
  ?>
     <main>
      <article class="article">
        <header class="article__header">
          <div class="container">
            <h1 class="article__heading heading heading--size-large"><?php echo $lang === '_en' ? 'Architecture projects in Costa Rica' : 'Proyectos de arquitectura en Costa Rica'; ?></h1>
            <p class="article__header-text" style="max-width: 900px; margin: 50px auto 32px; text-align: justify; font-size: 18px; line-height: 1.7;">
              <?php echo $lang === '_en'
                ? 'At <span class="color-yellow">AAZ DSGN</span>, we develop architecture, design, remodeling, and construction projects in Costa Rica, integrating aesthetics, functionality, and a strong connection with the surroundings. This selection brings together residential projects, terraces, remodels, and spaces designed around each client’s real needs, carefully guiding every stage from concept to execution.'
                : 'En <span class="color-yellow">AAZ DSGN</span> desarrollamos proyectos de arquitectura, diseño, remodelación y construcción en Costa Rica, integrando estética, funcionalidad y conexión con el entorno. Esta selección reúne proyectos residenciales, terrazas, remodelaciones y espacios diseñados para responder a las necesidades reales de cada cliente, cuidando cada etapa desde el concepto hasta la ejecución.'; ?>
            </p>
            <!-- Filter projects-->
            <div class="article__filter filter">
              <button class="filter__item filter__item--active __js_filter-btn" data-idcat="0" type="button" data-filter="*">all</button>
               <? foreach ($categories as $cat) { ?>
                <button class="filter__item __js_filter-btn btnCat" type="button" data-idcat="<? echo $cat->vars['id']?>" data-filter=".__js_<? echo $cat->vars['id']?>"><? echo $cat->vars['descripcion']?></button>
              <? } ?>  
              
            </div>
          </div>
        </header>
        <div class="article__main article__main--width-full container">
          <ul class="projects-masonry row __js_projects-grid">
           

            <? foreach ($main as $proyect) { ?>  
            <? $img = get_projects_images_custom($proyect->vars['id'],'image5');
            ?>     
            <li class="projects-masonry__item col-12 col-md-6 col-xl-3 __js_masonry-item __js_<? echo $proyect->vars['cat']?>">
              <a class="card card--small card--masonry" href="<?php echo $base_url; ?>/projects/<? echo $proyect->vars['id'] ?>">
                <div class="card__image">
                 <?php
  $projectTitle = trim($proyect->vars['titulo'] ?? '');
  $imageName = trim($img->vars['nombre'] ?? '');

  $altText = $imageName !== ''
    ? $projectTitle . ' - ' . $imageName
    : 'Proyecto ' . $projectTitle . ' de AAZ DSGN';
?>
  <img 
    src="<?php echo str_replace(' ','%20',$img->vars['url']); ?>" 
    srcset="<?php echo str_replace(' ','%20',$img->vars['url2']); ?> 2x" 
    width="428" 
    height="428" 
    alt="<?php echo htmlspecialchars($altText, ENT_QUOTES, 'UTF-8'); ?>"  
    loading="lazy">
                </div>
                <div class="card__content">
                  <h3 class="card__heading"><? echo $proyect->vars['titulo']?></h3>
                  <div class="card__text"><? echo text_preview($proyect->vars['description'],150)?></div>
                  <div class="card__bottom">
                    <span class="card__link"><? echo $ver ?>
                      <svg width="20" height="20">
                        <use xlink:href="#chevron-right"></use>
                      </svg>
                    </span>
                  </div>
                </div>
              </a>
            </li>
            <? } ?>

          </ul>
        </div>
      </article>
    </main>
  