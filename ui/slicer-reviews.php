<?php
/**
 * Google Reviews carousel. Reusable — safe to include from any page.
 * Single query via the handler, nothing rendered if there are no reviews.
 */
$google_reviews = get_google_reviews_by_review_id_index_show();

if (!empty($google_reviews)):
    $__gr_months_es = [1=>'ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
    $__gr_months_en = [1=>'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    $__gr_labels = ($lang === '_en') ? [
        'subtitle'    => 'Google Reviews',
        'title'       => 'What our clients say',
        'read_more'   => 'Read more',
        'show_less'   => 'Show less',
        'google_tag'  => 'Google Review',
        'prev'        => 'Previous review',
        'next'        => 'Next review',
        'stars_label' => 'out of 5',
    ] : [
        'subtitle'    => 'Google Reviews',
        'title'       => 'Lo que dicen nuestros clientes',
        'read_more'   => 'Leer más',
        'show_less'   => 'Ver menos',
        'google_tag'  => 'Reseña de Google',
        'prev'        => 'Reseña anterior',
        'next'        => 'Reseña siguiente',
        'stars_label' => 'de 5',
    ];

    $__gr_count = count($google_reviews);
?>
<section class="google-reviews" data-slides-count="<?php echo (int) $__gr_count; ?>">
  <div class="google-reviews__inner container">
    <div class="google-reviews__header">
      <span class="google-reviews__subtitle"><?php echo htmlspecialchars($__gr_labels['subtitle'], ENT_QUOTES, 'UTF-8'); ?></span>
      <h2 class="google-reviews__title"><?php echo htmlspecialchars($__gr_labels['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
    </div>

    <div class="google-reviews__carousel-wrap">
      <button type="button" class="google-reviews__nav google-reviews__nav--prev __js_google-reviews-prev" aria-label="<?php echo htmlspecialchars($__gr_labels['prev'], ENT_QUOTES, 'UTF-8'); ?>">
        <svg width="20" height="20" aria-hidden="true">
          <use xlink:href="#chevron-left"></use>
        </svg>
      </button>

      <div class="swiper google-reviews__carousel __js_google-reviews-carousel">
        <div class="swiper-wrapper">
          <?php foreach ($google_reviews as $review_id => $review): ?>
            <?php
              $name = trim((string) ($review->vars['reviewer_display_name'] ?? ''));
              $name_safe = htmlspecialchars($name !== '' ? $name : ($lang === '_en' ? 'Google user' : 'Usuario de Google'), ENT_QUOTES, 'UTF-8');
              $initial_safe = htmlspecialchars(function_exists('mb_strtoupper') && $name !== '' ? mb_substr($name, 0, 1) : ($name !== '' ? strtoupper($name[0]) : '?'), ENT_QUOTES, 'UTF-8');

              $use_translation = ($lang === '_en')
                  && !empty($review->vars['has_google_translation'])
                  && trim((string) ($review->vars['comment_google_translation'] ?? '')) !== '';
              $comment_raw  = $use_translation ? $review->vars['comment_google_translation'] : ($review->vars['comment_original'] ?? '');
              $comment_safe = nl2br(htmlspecialchars((string) $comment_raw, ENT_QUOTES, 'UTF-8'));

              $stars = (int) ($review->vars['star_rating'] ?? 0);
              $stars = max(0, min(5, $stars));

              $create_time = $review->vars['google_create_time'] ?? null;
              $date_display = '';
              if (!empty($create_time)) {
                  $ts = strtotime($create_time);
                  if ($ts !== false) {
                      $months = ($lang === '_en') ? $__gr_months_en : $__gr_months_es;
                      $date_display = (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
                  }
              }
              $date_safe = htmlspecialchars($date_display, ENT_QUOTES, 'UTF-8');

              $photo_url = trim((string) ($review->vars['reviewer_photo_url'] ?? ''));
              $photo_ok  = $photo_url !== '' && filter_var($photo_url, FILTER_VALIDATE_URL) !== false
                  && (stripos($photo_url, 'http://') === 0 || stripos($photo_url, 'https://') === 0);
              $photo_safe = $photo_ok ? htmlspecialchars($photo_url, ENT_QUOTES, 'UTF-8') : '';
            ?>
            <div class="swiper-slide">
              <article class="google-review-card">
                <div class="google-review-card__avatar">
                  <?php if ($photo_safe !== ''): ?>
                    <img src="<?php echo $photo_safe; ?>" alt="<?php echo $name_safe; ?>" loading="lazy" referrerpolicy="no-referrer">
                  <?php else: ?>
                    <span class="google-review-card__avatar-fallback" aria-hidden="true"><?php echo $initial_safe; ?></span>
                    <span class="visually-hidden"><?php echo $name_safe; ?></span>
                  <?php endif; ?>
                </div>

                <div class="google-review-card__name"><?php echo $name_safe; ?></div>
                <?php if ($date_safe !== ''): ?>
                  <div class="google-review-card__date"><?php echo $date_safe; ?></div>
                <?php endif; ?>

                <div class="google-review-card__stars" aria-label="<?php echo (int) $stars . ' ' . htmlspecialchars($__gr_labels['stars_label'], ENT_QUOTES, 'UTF-8'); ?>">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" class="google-review-card__star <?php echo $i <= $stars ? 'is-filled' : ''; ?>">
                      <path d="M12 1.5l3.09 6.26 6.91 1-5 4.87 1.18 6.87L12 17.27l-6.18 3.23L7 13.63l-5-4.87 6.91-1L12 1.5z"/>
                    </svg>
                  <?php endfor; ?>
                </div>

                <div class="google-review-card__text-wrap">
                  <p class="google-review-card__text __js_google-review-text"><?php echo $comment_safe; ?></p>
                  <button type="button" class="google-review-card__toggle __js_google-review-toggle"
                          data-label-more="<?php echo htmlspecialchars($__gr_labels['read_more'], ENT_QUOTES, 'UTF-8'); ?>"
                          data-label-less="<?php echo htmlspecialchars($__gr_labels['show_less'], ENT_QUOTES, 'UTF-8'); ?>"
                          aria-expanded="false">
                    <?php echo htmlspecialchars($__gr_labels['read_more'], ENT_QUOTES, 'UTF-8'); ?>
                  </button>
                </div>

                <div class="google-review-card__badge"><?php echo htmlspecialchars($__gr_labels['google_tag'], ENT_QUOTES, 'UTF-8'); ?></div>
              </article>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <button type="button" class="google-reviews__nav google-reviews__nav--next __js_google-reviews-next" aria-label="<?php echo htmlspecialchars($__gr_labels['next'], ENT_QUOTES, 'UTF-8'); ?>">
        <svg width="20" height="20" aria-hidden="true">
          <use xlink:href="#chevron-right"></use>
        </svg>
      </button>
    </div>
  </div>
</section>
<?php
    unset($google_reviews, $__gr_months_es, $__gr_months_en, $__gr_labels, $__gr_count);
endif;
?>
