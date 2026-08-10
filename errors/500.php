<?php
http_response_code(500);
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$base_url = ($is_https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'aazdsgn.com');
?>
<!DOCTYPE html>
<html class="no-js" lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $base_url; ?>/favicons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $base_url; ?>/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $base_url; ?>/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="<?php echo $base_url; ?>/css/bootstrap-grid.css">
  <link rel="stylesheet" href="<?php echo $base_url; ?>/css/main.css">
  <title>Something went wrong - AAZDSGN</title>
  <meta name="robots" content="noindex, nofollow">
</head>

<body class="webpage">
    <main>
      <article class="article">
        <header class="article__header">
          <div class="container" style="text-align:center; padding-top: 60px;">
            <a class="logo" href="<?php echo $base_url; ?>/" style="display:inline-block; margin-bottom: 24px;">
              <span class="logo__large">AAZ DSGN</span>
              <span class="logo__small">Architecture studio</span>
            </a>
            <h1 class="article__heading heading heading--size-large">Something went wrong</h1>
          </div>
        </header>
        <div class="article__main container" style="text-align:center; padding: 24px 0 80px;">
          <p style="max-width: 560px; margin: 0 auto 32px;">We are sorry, something unexpected happened. Please try again later or contact us.</p>
          <div style="display:flex; gap: 16px; justify-content:center; flex-wrap: wrap;">
            <a class="btn" href="<?php echo $base_url; ?>/">Back to home</a>
            <a class="btn" href="<?php echo $base_url; ?>/contact">Contact us</a>
          </div>
        </div>
      </article>
    </main>
</body>

</html>
