/* Google Reviews carousel — init + expand/collapse. Self-contained, does not touch main.js. */
(function () {
	function getSlidesPerViewForWidth(width) {
		if (width >= 1200) return 4;
		if (width >= 768) return 2;
		return 1;
	}

	function initCarousel() {
		var section = document.querySelector('.google-reviews');
		var carouselEl = document.querySelector('.__js_google-reviews-carousel');
		if (!section || !carouselEl || typeof Swiper === 'undefined') {
			return;
		}

		var slidesCount = parseInt(section.getAttribute('data-slides-count'), 10) || 0;
		var slidesPerView = getSlidesPerViewForWidth(window.innerWidth);
		var enoughForLoop = slidesCount > slidesPerView;

		var swiper = new Swiper(carouselEl, {
			slidesPerView: 1,
			spaceBetween: 20,
			loop: enoughForLoop,
			speed: 700,
			watchOverflow: true,
			autoplay: enoughForLoop ? {
				delay: 4500,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			} : false,
			breakpoints: {
				768: {
					slidesPerView: 2,
					spaceBetween: 24,
				},
				1200: {
					slidesPerView: 4,
					spaceBetween: 28,
				},
			},
			navigation: {
				nextEl: '.__js_google-reviews-next',
				prevEl: '.__js_google-reviews-prev',
			},
			a11y: {
				enabled: true,
			},
			keyboard: {
				enabled: true,
				onlyInViewport: true,
			},
		});

		if (swiper && swiper.el) {
			swiper.el.addEventListener('focusin', function () {
				if (swiper.autoplay && swiper.autoplay.running) {
					swiper.autoplay.pause();
				}
			});
			swiper.el.addEventListener('focusout', function () {
				if (swiper.autoplay && swiper.params.autoplay) {
					swiper.autoplay.resume();
				}
			});
		}
	}

	function initExpandToggles() {
		var toggles = document.querySelectorAll('.__js_google-review-toggle');
		toggles.forEach(function (btn) {
			var card = btn.closest('.google-review-card__text-wrap');
			var text = card ? card.querySelector('.__js_google-review-text') : null;
			if (!text) return;

			// Only show the toggle if the text is actually clamped/overflowing.
			if (text.scrollHeight <= text.clientHeight + 1) {
				btn.setAttribute('hidden', 'hidden');
				return;
			}

			btn.addEventListener('click', function () {
				var expanded = text.classList.toggle('is-expanded');
				btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
				btn.textContent = expanded
					? btn.getAttribute('data-label-less')
					: btn.getAttribute('data-label-more');
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		initCarousel();
		// Let layout settle (fonts/images) before measuring clamped height.
		window.setTimeout(initExpandToggles, 50);
	});
})();
