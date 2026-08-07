  <?
   $main =  get_page_data(4,$lang);
   $total = count($main);
   $tittle = "Servicios";
   if($lang == '_en'){ $tittle = "Services";}
  
  ?>
     <main>
      <article class="article">
        <header class="article__header">
          <div class="container">
            <h1 class="article__heading heading heading--size-large"><? echo $tittle ?></h1>
          </div>
        </header>
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