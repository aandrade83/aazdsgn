# AAZDSGN Pre-Production SEO & QA Audit

Fecha: 2026-08-10
Entorno auditado: `http://localhost:8083` (Docker local)
Destino de producción: `https://aazdsgn.com`

## Resumen ejecutivo

El sitio funciona correctamente a nivel funcional y las URLs limpias (`/about`, `/projects`, `/projects/{id}`, etc.) están bien implementadas con redirecciones 301 desde las rutas legacy `.php`. Sin embargo, hay **dos hallazgos de seguridad críticos que deben resolverse antes de producción**: el archivo `.env` con credenciales completas (DB, Resend API key) y la carpeta `.git/` se sirven públicamente sin ninguna restricción. A nivel SEO, todas las páginas comparten el mismo `<title>` y `<meta description>`, la home no tiene `<h1>`, y no hay Open Graph/Twitter Cards — esto limita significativamente el rendimiento en buscadores pese a que la base técnica (canonical, sitemap, robots.txt) está bien encaminada.

## Críticos antes de producción

| Problema | Archivo / URL | Impacto | Recomendación | Prioridad |
|---|---|---|---|---|
| `.env` expuesto públicamente (credenciales DB, `RESEND_API_KEY`, `MYSQL_ROOT_PASSWORD`) | `http://localhost:8083/.env` | Fuga total de credenciales productivas | Bloquear dotfiles en Apache (`<FilesMatch "^\.">Require all denied</FilesMatch>` en `.htaccess` o vhost) y **rotar todas las credenciales** antes de ir a producción | Crítica |
| `.git/` expuesto (`/.git/HEAD`, `/.git/config` responden 200) | `http://localhost:8083/.git/` | Descarga completa del repositorio, historial y posibles secretos pasados (`git-dumper`) | Bloquear `/.git/` en Apache; en producción, el document root no debería contener `.git/` en absoluto | Crítica |
| PHP muestra warnings/notices en pantalla (`display_errors=1`) | `V01/utilities/vars.php` | Cada página filtra rutas absolutas del servidor y detalles internos (ej. `Undefined variable $v in ui/head.php:35`) | `display_errors=0` y `log_errors=1` en el entorno de producción | Crítica |
| `docker-compose.yml` y `Dockerfile` servidos públicamente (200) | `http://localhost:8083/docker-compose.yml` | Revela topología de infraestructura y nombres de servicios/contenedores | Bloquear estos archivos a nivel Apache o excluirlos del document root de producción | Alta |
| `/index.php` no redirige a `/` | `http://localhost:8083/index.php` | URL duplicada indexable, dilución de señal SEO en la página más importante | Agregar regla 301 en `.htaccess`, igual que el resto de rutas legacy | Alta |

## Importantes para SEO

| Problema | Archivo / URL | Impacto | Recomendación | Prioridad |
|---|---|---|---|---|
| Title y meta description idénticos en las 5 páginas públicas | `ui/head.php` (hardcodeado) | Canibalización SEO, Google no puede diferenciar páginas en resultados | Parametrizar `<title>`/`<meta description>` por página, según la ruta o el controlador que la incluye | Alta |
| `<html lang="en">` hardcodeado sin importar `$lang` | `ui/head.php:8` | Contenido en español mal etiquetado para buscadores y lectores de pantalla | `<html lang="<?php echo $lang == '_en' ? 'en' : 'es'; ?>">` | Alta |
| Home sin `<h1>` | `http://localhost:8083/` | La página más importante del sitio no tiene heading principal — señal SEO perdida | Agregar un `<h1>` visible (o accesible) en el template de home | Alta |
| `<h1>` vacío en `/projects` | `ui/main-projects.php` | Heading presente pero sin texto, no aporta valor SEO | Rellenar con texto real ("Proyectos" / "Projects" según `$lang`) | Media |
| Sin Open Graph ni Twitter Card tags | `ui/head.php` | Previews pobres al compartir en redes sociales / WhatsApp | Agregar `og:title`, `og:description`, `og:image`, `twitter:card` | Media |
| `sitemap.xml` no incluye páginas de proyectos individuales | `sitemap.xml` | `/projects/{id}` depende solo del crawl interno para ser descubierto | Generar el sitemap dinámicamente o agregar las URLs de proyectos activos | Media |
| Imágenes de proyectos con `alt=""` | `ui/main-projects.php`, `ui/main-detail.php` | Se pierde SEO de imágenes y accesibilidad, pese a que los nombres de archivo ya son descriptivos (ej. "Casa moderna Guacima-1-.png") | Generar el `alt` a partir del título del proyecto o del nombre de archivo | Media |
| Contenido delgado en Home y About (~860 palabras cada una) | Home template, `ui/main-about.php` | Menos señales de relevancia para keywords locales frente a competencia | Ampliar el copy con servicios y ubicaciones específicas | Media |
| Ciudades objetivo ausentes del contenido: Guácima, San José, Escazú, Santa Ana | Contenido de todas las páginas públicas | Se pierde intención de búsqueda local en esas zonas (0 menciones detectadas) | Agregar menciones naturales en about/services/footer | Media |
| "Diseño de interiores" nunca mencionado | Contenido de `/services` | Posible servicio no capturado en búsquedas relacionadas | Confirmar si aplica al negocio y agregarlo al copy de servicios | Baja |

## Mejoras recomendadas

| Problema | Archivo / URL | Impacto | Recomendación | Prioridad |
|---|---|---|---|---|
| ~12 librerías JS presentes en el repo pero no cargadas en ningún template público | `js/fullpage*.js`, `jquery.marquee.min.js`, `packery.pkgd.min.js` (sin `-mode`), `scrolloverflow.min.js`, `simpleParallax.min.js`, `SmoothScroll.js`, `smooth-scrollbar.js`, `timer.js`, `picturefill.js`, `intro.js`, `js/revolution/` | Peso muerto en el repositorio; no afecta el runtime real porque no se sirven, pero infla el repo y confunde mantenimiento | Confirmar que no se usan en ningún flujo (admin incluido) y eliminarlas en una limpieza aparte — **no borrar todavía** | Baja |
| Inputs del form de contacto sin `<label for>`/`id` reales (solo `placeholder` + `aria-label`) | `ui/main-contact.php` | Accesibilidad subóptima para lectores de pantalla en navegadores/AT que no respetan bien `aria-label` en labels envolventes | Asociar `<label for="...">` con el `id` real del input, o validar con axe/Lighthouse que el patrón actual sea suficiente | Baja |
| Sin cache-busting (`?v=`) en CSS/JS propios | `ui/head.php`, `ui/footer.php` | Riesgo de servir una versión cacheada de un asset tras el deploy | Agregar `?v=<?php echo $v; ?>` consistente a los assets propios (ya se usa para el CSS de SweetAlert2) | Baja |
| Imágenes de proyectos servidas desde CDN externo (`images.aazdsgn.com`), no auditable desde el repo local | N/A (externo) | No se puede verificar peso/formato desde este entorno | Auditar aparte si conviene servir WebP/AVIF desde ese CDN | Baja |

## Pendientes para después de producción

- Medir Core Web Vitals reales en producción (PageSpeed Insights / Lighthouse) una vez el CDN de imágenes esté sirviendo tráfico real.
- Verificar indexación real en Google Search Console tras el lanzamiento (cobertura, canonicalización correcta de URLs limpias).
- Monitorear que las redirecciones 301 legacy sigan funcionando después de cualquier cambio de infraestructura/hosting.
- Evaluar analytics y consentimiento de cookies si se agrega tracking adicional más adelante.

## Checklist final antes de subir

- [ ] Bloquear `/.env` y `/.git` en el servidor de producción (o, mejor, mantenerlos fuera del document root)
- [ ] Rotar todas las credenciales de `.env` (DB, Resend API key) dado que estuvieron expuestas en este entorno
- [ ] `display_errors=0` en producción
- [ ] Titles y meta descriptions únicos por página
- [ ] `<html lang>` dinámico según `$lang`
- [ ] `<h1>` presente y con texto en Home y en `/projects`
- [ ] Redirigir `/index.php` a `/`
- [ ] Actualizar `sitemap.xml` con las páginas de proyectos y enviarlo a Search Console
- [ ] Revisar y completar el `alt` de las imágenes de proyectos
- [ ] Revisar responsive en dispositivos móviles reales (no solo devtools) para `/contact`, `/projects` y `/projects/{id}`
