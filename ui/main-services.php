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
             
          
           <div class="services-list__item service-card" data-aos="fade">
              <div class="service-card__inner">
                <div class="service-card__left">
                  <div class="service-card__text"><? echo $service->vars['text2']?></div>
                </div>
                <div class="service-card__right">
                  <img src="<? echo $service->vars['url']?>" srcset="<? echo $service->vars['url2']?>" width="635" height="422" alt="<? echo $service->vars['text1']?>">
                  <div class="service-card__detail">
                    <h2 class="service-card__heading"><? echo $service->vars['descripcion']?></h2>
                    
                  </div>
                </div>
              </div>
            </div>
          <? } ?>
           
          
          </section>
        </div>
      </article>
    </main>