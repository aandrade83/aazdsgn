<?php
ob_start();


////////////////////////////////
   header("refresh:10;url=../../index.php");
////////////////////////////////


//Muestro el documento
// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, también hay que destruir la cookie de sesión.
// Nota: ¡Esto destruirá la sesión y no la información de la sesión!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <head>
            <title>AAZ DSGN</title>
            

            <style>
                .ms-list{
                    border: 4px solid #cdcdcd!important;
                }
            </style>
        </head>

    </head>

    <body class="loading auth-fluid-pages pb-0">

        <div class="auth-fluid">
            <!--Auth fluid left content -->
            <div class="auth-fluid-form-box">
                <div class="align-items-center d-flex h-100">
                    <div class="card-body">

                        <!-- Logo -->
                        <div class="auth-brand text-center text-lg-start">
                            <div class="auth-logo">
                                <a href="index.php" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="../utilidades/tema/images/logo-dark.png" alt="" height="22">
                                    </span>
                                </a>
            
                                <a href="index.php" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <img src="../utilidades/tema/images/logo-light.png" alt="" height="22">
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="mt-4">
                                <div class="logout-checkmark" style="width: 50px; height: 50px;">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                        <circle class="path circle" fill="none" stroke="#4bd396" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                        <polyline class="path check" fill="none" stroke="#4bd396" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                    </svg>
                                </div>
                            </div>

                            <h3>Muy bien!</h3>

                            <p class="text-muted"> Sesión cerrada correctamente. </p>
                            <p class="text-muted"> Será redireccionado en <strong id="time">00:010</strong></p>
                            <p class="text-muted">VOLVER A <a href="<?php echo $base_url; ?>/V01/" class="text-muted ms-1"><b>INGRESAR</b></a></p>

                            <script>
                            function startTimer(duration, display) {
                                var timer = duration, minutes, seconds;
                                setInterval(function () {
                                    minutes = parseInt(timer / 60, 10);
                                    seconds = parseInt(timer % 60, 10);
                                    minutes = minutes < 10 ? "0" + minutes : minutes;
                                    seconds = seconds < 10 ? "0" + seconds : seconds;
                                    display.textContent = minutes + ":" + seconds;
                                    if (--timer < 0) {
                                        timer = duration;
                                    }
                                },1000);
                            }

                            window.onload = function () {
                                var fiveMinutes =  9,
                                    display = document.querySelector('#time');
                                startTimer(fiveMinutes, display);
                            };
                            
                            </script>
                        </div>

                        <!-- Footer
                        <footer class="footer footer-alt">
                            <p class="text-muted">Volver a <a href="https://facturaciontotalcr.com/" class="text-muted ms-1"><b>Ingresar</b></a></p>
                        </footer>-->

                    </div> <!-- end .card-body -->
                </div> <!-- end .align-items-center.d-flex.h-100-->
            </div>
            <!-- end auth-fluid-form-box-->

            <!-- Auth fluid right content -->
            <div class="auth-fluid-right text-center" style="background-color: transparent;">
                <div class="auth-user-testimonial">
                    <h2 class="mb-3 text-white" style="color: #ffffff!important;">TENGA UN BUEN DÃA</h2>
                    <p class="lead" style="color: #ffffff!important;">
                      <i class="mdi mdi-format-quote-open"></i>
                      RecomendÃ¡ndonos con sus amigos.
                      <i class="mdi mdi-format-quote-close"></i>
                    </p>
                    <h5 class="text-white">
      
                    </h5>
                </div> <!-- end auth-user-testimonial-->
            </div>
            <!-- end Auth fluid right content -->
        </div>
        <!-- end auth-fluid-->

        
    </body>
</html>