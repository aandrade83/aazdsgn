<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");
//$pass = "aleaz05";
//echo super_encript($pass); exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAZDSGN MANAGER</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/V01/apps/login/css/style.css">
    <link href='<?php echo $base_url; ?>/V01/apps/login/css/style_login.css' rel='stylesheet'>
    <script>window.BASE_URL = '<?php echo $base_url; ?>';</script>
</head>

        <!-- Vendor js -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="<?php echo $base_url; ?>/V01/assets/js/vendor.min.js"></script>

 <?php //require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php"); ?>
 <script type="text/javascript" src="<?php echo $base_url; ?>/V01/apps/login/js/functions.js"></script>
<body>

    <div class="wrapper">
        <form action="">
            <h1>AAZ DSGN MANAGER</h1>
            <div class="input-box">
                <input type="text" id="user" placeholder="Username" autocomplete="username" required>
                <!-- <i class='bx bxs-user'></i> -->
            </div>
            <div class="input-box">
                <input type="password" id="pass" placeholder="Password" autocomplete="current-password" required>
                <!-- <i class='bx bxs-lock-alt'></i> -->
            </div>

            <div class="remember-forgot" style="color: red;
    font-size: 18px;">
                <label id="loginMsg" style="display: none"> User or Password incorrect</label>
                
            </div>

            <button type="button" class="btn" id="loginBtn">Login</button>

            <div class="register-link">
                <!-- <p>Don't have an account? <a href="#">Register</a></p> -->
            </div>
        </form>
    </div>

</body>

</html>