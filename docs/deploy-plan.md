# Plan de despliegue a producción — AAZDSGN

Fecha: 2026-08-11
Origen: `D:\OneDrive\Documents\PROJECTS\AAZDSGN\aazdsgn` (local, `http://localhost:8083`)
Destino: `https://aazdsgn.com`

Este documento es solo planificación. No se modificó código ni se hizo commit.

---

## 1. Archivos de código que deben subirse

Todo el árbol del proyecto **excepto** lo listado en la sección 2. En concreto, esto incluye:

- `index.php`, `about.php`, `projects.php`, `projects-detail.php`, `services.php`, `contact.php`
- `.htaccess` (ver sección 3)
- `sitemap.xml`, `robots.txt` (ver sección 4)
- `errors/` (403.php, 404.php, 500.php)
- `process/` (incluye `process/actions/mail.php` y `process/actions/action.php`)
- `ui/`, `V01/`, `css/`, `js/`, `img/`, `favicons/`, `fonts/`, `video/`, `php/`
- `.env.example` (como referencia, no como archivo activo — ver sección 5)

## 2. Archivos que NO deben subirse

| Archivo/carpeta | Motivo |
|---|---|
| `.env` (el local) | Contiene credenciales de desarrollo (`DB_PASS`, `RESEND_API_KEY`, etc.). Producción necesita su **propio** `.env` con valores distintos (sección 5). |
| `.git/` | Repositorio completo con historial — nunca debe quedar accesible en el document root público. |
| `docker-compose.yml`, `Dockerfile` | Son para el entorno de desarrollo local (Docker). Solo aplican si producción también corre en Docker con esta misma composición; si el hosting es Apache/Plesk tradicional, no tienen función y no deben subirse. **A confirmar con el proveedor de hosting antes de decidir.** |
| `V01/LogFile.txt` | Log de debug temporal (ya vaciado y sacado del tracking de git). No debe subirse con contenido, y si se sube el archivo vacío, debe quedar bloqueado por `.htaccess` igual que en local (ya cubierto por el bloqueo de `*.log`... nota: el nombre real es `.txt`, no `.log` — ver sección 8, riesgo a revisar). |
| `*.sql`, `*.sql.gz`, dumps de base de datos | Nunca deben quedar en el document root público. |
| `docs/` (`audit-pre-prod.md`, `deploy-plan.md`, etc.) | Documentación interna — ya está bloqueada por `.htaccess` (`RewriteRule ^docs/ - [F,L]`), pero si se prefiere no publicarla en absoluto, lo más limpio es no subir la carpeta al servidor de producción. |
| `_unused_review/` | Carpeta de backups/versiones viejas (HTML/PHP de prueba, incluye un `.zip`) — no debe subirse; no aporta nada a producción y es superficie de riesgo. |
| `test.php` (raíz) | Archivo de prueba con una URL hardcodeada de ejemplo — no tiene función en producción, no subir. |
| `.DS_Store`, `Thumbs.db`, `*.lnk`, `.idea/`, `.vscode/` | Basura de sistema operativo/editor, sin función. |
| `.claude/` | Configuración de este asistente — no tiene función en el servidor. |

## 3. `.htaccess`

**Sí debe subirse.** Es el que implementa:
- Bloqueo de dotfiles, `.git`, `docker-compose.yml`/`Dockerfile`, extensiones sensibles (`.sql`, `.zip`, `.rar`, `.bak`, `.log`).
- Bloqueo de `/V01/utilities/`, `/ui/`, `/docs/`, `/_unused_review/`, `/.claude/`.
- Bloqueo de `/process/` salvo `mail.php` y `action.php`.
- Redirects 301 de las URLs legacy `.php` a las rutas limpias.
- Mapeo interno de rutas limpias (`/about`, `/projects/{id}`, etc.) a los `.php` reales.
- `ErrorDocument` para 403/404/500.

Sin este archivo, el sitio en producción serviría URLs `.php` sin redirigir, expondría `.env`/`.git`/carpetas internas, y mostraría las páginas de error genéricas de Apache. Es uno de los archivos más críticos del despliegue.

## 4. `sitemap.xml` y `robots.txt`

**Ambos deben subirse tal cual están en local.** Ya usan URLs absolutas a `https://aazdsgn.com` (no `localhost`), confirmado en la revisión anterior:
- `sitemap.xml`: 35 URLs limpias, sin `.php`.
- `robots.txt`: declara `Sitemap: https://aazdsgn.com/sitemap.xml` y bloquea solo carpetas internas.

No requieren ningún cambio de contenido para producción — ya están escritos con el dominio final.

## 5. `.env` de producción

**No se debe copiar el `.env` local.** Crear uno nuevo directamente en el servidor de producción (nunca por git ni por FTP en texto plano si se puede evitar — idealmente vía el panel de hosting/variables de entorno) con esta estructura:

```env
DB_HOST=
DB_USER=
DB_PASS=
DB_NAME=

RESEND_API_KEY=
CONTACT_TO=proyectos@aazdsgn.com
CONTACT_CC=alexis.andrade@gmail.com
CONTACT_FROM="AAZ DSGN Website <contact@aazdsgn.com>"
```

Notas:
- `DB_HOST`/`DB_USER`/`DB_PASS`/`DB_NAME` deben ser las credenciales **reales de producción**, provistas por el hosting — nunca las de `db`/`dev_aazdsgn` que usa Docker local.
- `RESEND_API_KEY` — usar la key de producción de Resend (puede ser la misma que en local si la cuenta de Resend es la misma, pero confirmar que el dominio `aazdsgn.com` esté verificado en Resend antes del deploy, o los correos no saldrán).
- `CONTACT_FROM` debe usar un remitente `@aazdsgn.com` verificado en Resend (SPF/DKIM configurados), si no, Resend puede rechazar o marcar como spam los envíos.
- El `.env.example` actual del repo **no incluye** `RESEND_API_KEY`/`CONTACT_TO`/`CONTACT_CC`/`CONTACT_FROM` (solo tiene las variables de DB y SMTP viejas) — vale la pena actualizarlo en algún momento para que sirva de referencia completa, aunque no es bloqueante para este deploy.
- Verificar que `V01/utilities/vars.php` resuelve `$base_url` dinámicamente desde `HTTP_HOST`/HTTPS (ya está así, sin hardcodear `localhost`) — en producción debería resolver solo a `https://aazdsgn.com` en cuanto el dominio y el SSL estén activos, sin ningún cambio de código.

## 6. Migración SQL para producción

Estos `UPDATE` ya se aplicaron en la BD **local** en tandas anteriores y no viajan con el código — hay que repetirlos contra la BD de producción. Patrón: `SELECT` antes → `UPDATE` → `SELECT` de verificación después.

```sql
-- ============================================
-- PASO 1: SELECT de diagnóstico (correr primero, revisar resultados)
-- ============================================
SELECT id, id_page, link, descripcion FROM content_esp WHERE link LIKE '%.php%';
SELECT id, id_page, link, descripcion FROM content_en  WHERE link LIKE '%.php%';

-- ============================================
-- PASO 2: UPDATE — menú de navegación (id_page = 1)
-- ============================================
UPDATE content_esp SET link='https://aazdsgn.com/about'    WHERE link LIKE '%about.php%';
UPDATE content_esp SET link='https://aazdsgn.com/projects' WHERE link LIKE '%projects.php%';
UPDATE content_esp SET link='https://aazdsgn.com/services' WHERE link LIKE '%services.php%';
UPDATE content_esp SET link='https://aazdsgn.com/contact'  WHERE link LIKE '%contact.php%';
UPDATE content_esp SET link='https://aazdsgn.com/'         WHERE link LIKE '%index.php%';

UPDATE content_en SET link='https://aazdsgn.com/about'    WHERE link LIKE '%about.php%';
UPDATE content_en SET link='https://aazdsgn.com/projects' WHERE link LIKE '%projects.php%';
UPDATE content_en SET link='https://aazdsgn.com/services' WHERE link LIKE '%services.php%';
UPDATE content_en SET link='https://aazdsgn.com/contact'  WHERE link LIKE '%contact.php%';
UPDATE content_en SET link='https://aazdsgn.com/'         WHERE link LIKE '%index.php%';

-- ============================================
-- PASO 3: UPDATE — tarjetas de proyecto en Home (id_page = 2)
-- ============================================
UPDATE content_esp SET link = REPLACE(link, 'projects-detail.php?id=', 'projects/') WHERE link LIKE '%projects-detail.php?id=%';
UPDATE content_en  SET link = REPLACE(link, 'projects-detail.php?id=', 'projects/') WHERE link LIKE '%projects-detail.php?id=%';

-- ============================================
-- PASO 4: SELECT de verificación (no debe quedar ningún .php)
-- ============================================
SELECT id, id_page, link, descripcion FROM content_esp WHERE link LIKE '%.php%';
SELECT id, id_page, link, descripcion FROM content_en  WHERE link LIKE '%.php%';
-- Ambos deben devolver 0 filas.
```

**Importante:** hacer un backup completo de la BD de producción (`mysqldump`) antes de correr estos `UPDATE` (ver sección 11).

## 7. Estructura de carpetas: local vs. producción

No se pudo verificar directamente la estructura del hosting de producción desde este entorno. Antes del deploy, confirmar manualmente:

- Que el document root de producción apunte a la raíz del proyecto (donde vive `index.php`, `.htaccess`), igual que en local (`/var/www/html` dentro del contenedor).
- Que la ruta relativa entre `V01/utilities/` y el resto del sitio se mantenga idéntica — varios archivos usan `$_SERVER['DOCUMENT_ROOT']."/V01/utilities/..."` de forma absoluta según el document root, así que **mientras el document root de producción sea la raíz del proyecto** (no una subcarpeta), esto funciona igual sin cambios de código.
- Si producción usa una subcarpeta (ej. `public_html/aazdsgn/` en vez de `public_html/`), habría que ajustar `RewriteBase` en `.htaccess` — **no aplica si el dominio apunta directo a la raíz del proyecto**, que es el escenario esperado para un dominio propio como `aazdsgn.com`.

## 8. Compatibilidad Apache/Plesk

- El `.htaccess` requiere `mod_rewrite` habilitado y `AllowOverride All` (o al menos `AllowOverride FileInfo AuthConfig Limit Indexes`) en la configuración del vhost — en local esto lo provee `docker-php.conf` del contenedor. **Confirmar con el proveedor de hosting/Plesk que ambas condiciones se cumplen antes del deploy**, si no, el `.htaccess` se ignora silenciosamente y todo el bloqueo de seguridad + redirects + URLs limpias deja de funcionar sin dar ningún error visible.
- En Plesk específicamente: mod_rewrite suele venir habilitado por defecto, pero `AllowOverride` a veces está limitado desde el panel — verificar en "Apache & nginx Settings" del dominio.
- Si Plesk usa nginx como proxy delante de Apache (común en Plesk moderno), confirmar que nginx no esté interceptando las rutas antes de que lleguen a Apache/`.htaccess` (podría requerir reglas adicionales de nginx para las URLs limpias) — **riesgo a validar, no asumir que funciona igual que en Apache puro**.
- Revisar que la extensión `mysqli` (usada por `V01/utilities/db/connection.php`) esté disponible en el PHP de producción — en Docker local se instala explícitamente en el `Dockerfile` (`docker-php-ext-install mysqli pdo pdo_mysql`); en un hosting compartido/Plesk normalmente ya viene, pero confirmar la versión de PHP (el proyecto corre sobre PHP 8.1 en local).
- **Riesgo detectado en `.htaccess`**: el bloqueo de logs usa `<FilesMatch "\.(sql|zip|rar|bak|log)$">`, pero `V01/LogFile.txt` tiene extensión `.txt`, no `.log` — si llegara a subirse con contenido, **no estaría bloqueado por esa regla**. Mitigación: no subir el archivo (sección 2), y si se sube vacío por error, no es sensible por sí solo (ya no contiene el `LOGIN_DEBUG` con hashes/emails que tenía antes).

## 9. Checklist de pruebas post-deploy

Con `curl` o navegador, contra `https://aazdsgn.com`:

- [ ] `/` → 200
- [ ] `/about` → 200
- [ ] `/projects` → 200
- [ ] `/projects/8` → 200
- [ ] `/services` → 200
- [ ] `/contact` → 200
- [ ] `/sitemap.xml` → 200, URLs con dominio de producción, sin `.php`
- [ ] `/robots.txt` → 200, `Sitemap:` apunta a `https://aazdsgn.com/sitemap.xml`
- [ ] `/about.php` → 301 → `/about` (repetir para `/projects.php`, `/services.php`, `/contact.php`, `/index.php` → `/`)
- [ ] `/projects-detail.php?id=8` → 301 → `/projects/8`
- [ ] `/no-existe-xyz` → 404 con la página personalizada (no la genérica de Apache)
- [ ] `/ui/` o `/V01/utilities/` → 403 con la página personalizada
- [ ] Enviar un mensaje de prueba real desde `/contact` → confirmar que llega el correo a `proyectos@aazdsgn.com` (y CC a `alexis.andrade@gmail.com`) vía Resend, y que el SweetAlert2 de éxito se muestra
- [ ] `GET /process/actions/mail.php` → 405
- [ ] `/.env` → 403 (o 404)
- [ ] `/.git/HEAD` → 403 (o 404, o mejor aún, que ni siquiera exista `.git` en el servidor)

## 10. Checklist SEO post-deploy

- [ ] Verificar propiedad del dominio en Google Search Console (si no está ya verificado).
- [ ] Enviar `https://aazdsgn.com/sitemap.xml` en Search Console → Sitemaps.
- [ ] Usar "Inspeccionar URL" en Search Console sobre `https://aazdsgn.com/` y solicitar indexación.
- [ ] Repetir la inspección para `/about`, `/projects`, `/services`, `/contact` (al menos las principales).
- [ ] Probar en mobile real (no solo devtools) las páginas más visitadas: home, contacto, un detalle de proyecto.
- [ ] Correr PageSpeed Insights sobre `https://aazdsgn.com/` y `/contact` (esta última tiene el video hero de ~18 MB, es la más pesada — ya señalado como pendiente recomendable en la auditoría anterior).
- [ ] Confirmar que las meta tags Open Graph se ven bien al compartir el link (probar con el debugger de Facebook o simplemente compartiendo en WhatsApp).

## 11. Riesgos y plan de rollback

**Antes de tocar producción:**
- [ ] Backup completo de archivos actuales de producción (si ya existe un sitio en `aazdsgn.com`, aunque sea el legacy) — comprimir el document root completo con fecha en el nombre.
- [ ] Backup de la base de datos de producción actual: `mysqldump -u [user] -p [database] > backup_aazdsgn_pre_deploy_2026-08-11.sql` (ejecutar esto y guardarlo en un lugar seguro **fuera** del document root público).
- [ ] Confirmar que se tiene acceso de rollback (FTP/SSH y panel de hosting) antes de empezar, para no quedar bloqueado si algo falla.

**Si algo falla después del deploy:**
- **Archivos rotos / sitio caído**: restaurar el backup de archivos tomado en el paso anterior; investigar en un entorno de staging antes de reintentar.
- **`.htaccess` mal interpretado por el hosting** (sitio muestra error 500 general): renombrar temporalmente `.htaccess` a `.htaccess.disabled` vía FTP/SSH para restaurar el acceso mientras se diagnostica, y luego revisar la sección 8 de este documento (`AllowOverride`, nginx-proxy).
- **Formulario de contacto no envía correos**: verificar `.env` de producción (paso 5) y que el dominio `aazdsgn.com` esté verificado en Resend — no es un problema de código si `/contact` carga bien pero el envío falla.
- **BD con datos corruptos tras el `UPDATE`**: restaurar el `mysqldump` tomado antes del paso 6.
- **Regresión de SEO** (páginas dejan de indexarse, redirects rotos): comparar contra este mismo checklist de la sección 9, y contra el reporte de auditoría anterior (`docs/audit-pre-prod.md`) para aislar qué cambió.

En todos los casos, **no se debe intentar arreglar en caliente sobre producción sin antes tener el backup confirmado** — restaurar primero, diagnosticar después en local o en un entorno de staging.

---

## Resumen ejecutivo

El sitio local está técnicamente listo (sin bloqueantes según la auditoría anterior). Los puntos que requieren decisión/acción humana antes de poder ejecutar el deploy son:

1. Confirmar con el proveedor de hosting si `docker-compose.yml`/`Dockerfile` aplican a producción o si es un hosting tradicional Apache/Plesk.
2. Crear el `.env` de producción con credenciales reales (nunca copiar el local).
3. Verificar el dominio `aazdsgn.com` en Resend (SPF/DKIM) para que el formulario de contacto funcione en producción.
4. Confirmar `mod_rewrite` + `AllowOverride` en el hosting de producción.
5. Tomar los dos backups (archivos y BD) antes de tocar nada.
6. Ejecutar la migración SQL de la sección 6 después del deploy de código, con el patrón SELECT → UPDATE → SELECT.

Este documento no modifica nada por sí solo — es la guía para ejecutar el despliegue de forma controlada.
