<?php
   $main   = get_page_data(5, $lang, "descripcion");
   $total  = count($main);
   $tittle2 = ($lang == '_en') ? "Our Team" : "Nuestro Equipo";
   $tittle3 = ($lang == '_en') ? "Architecture for" : "Arquitectura para la ";
   $tittle4 = ($lang == '_en') ? "Life." : "Vida.";
?>

<main>
  <article class="article">
    <header class="article__header">
      <div class="container">
        <h1 class="article__heading heading heading--size-large"><? echo $tittle3; ?>
          <br>
          <span class="color-yellow"><? echo $tittle4; ?></span>
        </h1>
      </div>
    </header>


    <div class="article__about-hero container">
 
      <img src="<?php echo $main['MAIN']->vars['url']; ?>"
           srcset="<?php echo $main['MAIN']->vars['url2']; ?>"
           width="1800" height="768"
           alt="<?php echo ($lang == '_en') ? 'Residential architecture and creative design in Costa Rica' : 'Arquitectura residencial y diseño creativo en Costa Rica'; ?>">
    </div>

    <!-- Kill-switch SOLO para este bloque -->
    <style>
      /* Limita el alcance al bloque #about-top para no romper otros ::before */
      #about-top .about-block__inner,
      #about-top .about-block {
        /* por si el tema usa una variable para el número */
        --about-number: "";
      }
      #about-top .about-block__inner:before,
      #about-top .about-block__inner::before,
      #about-top .about-block:before,
      #about-top .about-block::before {
        content: "" !important;
        display: none !important;
        background: none !important;
        width: 0 !important;
        height: 0 !important;
        border: 0 !important;
     }
     .about-block__heading p {
    text-align: justify;
    text-justify: inter-word;
    line-height: 1.8;
     } 

     h2.about-block__heading {
    text-align: justify;
    }

    .article__header {
    margin-bottom: 5px; /* o incluso 0 */
     margin-top: 0px; /* o incluso 0 */
    }


    /* OCULTAMOS NUMERACION */
    .service-card__left::after {
    content: "" !important;
    display: none !important;
    visibility: hidden !important;
   }

  /*
   eliminar contador en todo
.service-card__left::after {
    content: "" !important;
    display: none !important;
    visibility: hidden !important;
}
  */

   .about-block__inner {
      padding-top: 0px;
    }
      /* 🔥 CONTROL TOTAL DE ESPACIOS SUPERIORES */

#about-top {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* 🔥 elimina espacio interno */
#about-top .about-block__inner {
    padding-top: 0 !important;
    padding-bottom: 20px !important; /* ajustable */
}

/* 🔥 reduce el espacio bajo la imagen de #about-top (antes de "Nuestro equipo") */
#about-top .about-block__header {
    margin-bottom: 0 !important;
}

/* 🔥 controla el texto */
#about-top p {
    margin-top: 0 !important;
}

/* 🔥 espacio antes de "Nuestro equipo" */
.webpage__about-block {
    margin-top: 20px !important;
    padding-top: 20px !important;
}


    </style>

    <!-- BLOQUE 1 (sin número) -->
    <section id="about-top" class="webpage__about-block about-block">
      <div class="about-block__inner container">
        <header class="about-block__header" data-aos="fade">
          <p>
            <?php echo nl2br(htmlspecialchars($main['MAIN']->vars['text2'])); ?>
        </p>
        <div style="text-align: center;">
          <img src="/img/ucr-tec.png" alt="Universidad de Costa Rica y Tecnológico de Costa Rica">
        </div>

        </header>
        
      </div>
    </section>

    <!-- BLOQUE 2 (normal, mantiene su número si el tema lo usa) -->
    <section class="webpage__about-block about-block">
      <div class="about-block__inner container">
        <header class="about-block__header" data-aos="fade">
          <h2 class="about-block__heading heading"><?php echo $tittle2; ?></h2>
        </header>

        <div class="services-list__item service-card" data-aos="fade">
          <div class="service-card__inner">
            <div class="service-card__left">
              <div class="service-card__text"><?php echo $main['Alejandra']->vars['text2']; ?></div>
            </div>
            <div class="service-card__right">
              <img src="<?php echo $main['Alejandra']->vars['url']; ?>"
                   srcset="<?php echo $main['Alejandra']->vars['url2']; ?>"
                   width="635" height="422"
                   alt="<?php echo $main['Alejandra']->vars['text1']; ?>"
                   style="margin-top: 100px;">
              <div class="service-card__detail">
                <h2 class="service-card__heading"><?php echo $main['Alejandra']->vars['text1']; ?></h2>
              </div>
            </div>
          </div>
        </div>

        <div class=" service-card" data-aos="fade">
          <div class="service-card__inner">
            <div class="service-card__left">
              <div class="service-card__text"><?php echo $main['Silvia']->vars['text2']; ?></div>
            </div>
            <div class="service-card__right">
              <img src="<?php echo $main['Silvia']->vars['url']; ?>"
                   srcset="<?php echo $main['Silvia']->vars['url2']; ?>"
                   width="635" height="422"
                   alt="<?php echo $main['Silvia']->vars['text1']; ?>"
                   style="margin-top: 100px;">
              <div class="service-card__detail">
                <h2 class="service-card__heading"><?php echo $main['Silvia']->vars['text1']; ?></h2>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

  </article>
</main>
