  <?
   $main =  get_page_data(4,$lang);
   $total = count($main);
   $tittle = "Servicios de arquitectura en Costa Rica";
   if($lang == '_en'){ $tittle = "Architecture services in Costa Rica";}
  
  ?>
     <main>
      <article class="article">
        <header class="article__header">
          <div class="container">
            <h1 class="article__heading heading heading--size-large"><? echo $tittle ?></h1>
          </div>
        </header>
        <div class="container">
          <p class="article__header-text" style="max-width: 900px; margin: 0 auto 32px; text-align: justify; font-size: 18px; line-height: 1.7;">
            <? echo $lang == '_en' ? "At " : "En "; ?><strong class="color-yellow" style="font-size: 1.3em;">AAZ DSGN</strong>
            <? echo $lang == '_en'
              ? ", we offer architecture, design, remodeling, and construction services in Costa Rica, supporting each project from the conceptual stage through execution. Our team integrates preliminary design, construction drawings, technical inspection, budgeting, appraisals, and project management to help you make better decisions at every stage. We also understand the importance of following condominium guidelines and review requirements, helping your preliminary project move forward with greater clarity and fewer unnecessary complications."
              : " ofrecemos servicios de arquitectura, diseño, remodelación y construcción en Costa Rica, acompañando cada proyecto desde la etapa conceptual hasta su ejecución. Nuestro equipo integra anteproyecto, planos constructivos, inspección técnica, presupuesto, avalúos y administración de obra para ayudarte a tomar mejores decisiones en cada etapa. También conocemos la importancia de cumplir con lineamientos condominales y requisitos de revisión, ayudando a que tu anteproyecto avance con mayor claridad y sin complicaciones innecesarias."; ?>
          </p>
        </div>
        <div class="article__main container">
          <section class="services-list">
           <? foreach ($main as $service) { ?>  
             
          
           <div class="services-list__item service-block" data-aos="fade">
              <div class="service-block__header">
                <span class="service-block__number"></span>
                <h2 class="service-block__title"><? echo $service->vars['descripcion']?></h2>
              </div>
              <div class="service-block__line"></div>
              <div class="service-block__body">
                <div class="service-block__image">
                  <img src="<? echo $service->vars['url']?>" srcset="<? echo $service->vars['url2']?>" width="635" height="422" alt="<? echo $service->vars['text1']?>">
                </div>
                <div class="service-block__description"><? echo $service->vars['text2']?></div>
              </div>
            </div>
          <? } ?>
           
          
          </section>
        </div>
      </article>
    </main>