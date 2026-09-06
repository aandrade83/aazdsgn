<? include($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php"); ?>
<? 
  session_start();
  if(!isset($_SESSION['user'])) {
    header("Location: $base_url/V01/");
  }


  $user = get_user($_SESSION['user']);
  ?>
<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8" />
        <title>AAZDSGN MANAGER</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Manager for hte AAZDSGN PAGE" name="description" />
        <meta content="AAZDSGN" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo $base_url; ?>/V01/assets/images/favicon.ico">
        <!-- App css -->
        <link href="<?php echo $base_url; ?>/V01/assets/css/bootstrap-creative.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="<?php echo $base_url; ?>/V01/assets/css/app-creative.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <!-- icons -->
        <link href="<?php echo $base_url; ?>/V01/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

        <!-- Plugins css -->
        <link href="<?php echo $base_url; ?>/V01/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $base_url; ?>/V01/assets/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />

         <link href="<?php echo $base_url; ?>/V01/assets/libs/sweetalert2/sweetalert2.min.css?v=<?php echo $v;?>"/>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        


        <link href="<?php echo $base_url; ?>/V01/assets/css/custom.css" rel="stylesheet" type="text/css" />
        <script>window.BASE_URL = '<?php echo $base_url; ?>';</script>
    </head>
    

    <body class="loading1" data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}}'>

     <!-- Begin page -->
     <div id="wrapper">

          <!-- Topbar Start -->
            <div class="navbar-custom Custom_nav_color">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-right mb-0">

    
                        <li class="dropdown d-inline-block d-lg-none">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fe-search noti-icon"></i>
                            </a>
                            <div class="dropdown-menu dropdown-lg dropdown-menu-right p-0">
                                <form class="p-3">
                                    <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                </form>
                            </div>
                        </li>
    
    
                        <li class="dropdown d-none d-lg-inline-block topbar-dropdown">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo $base_url; ?>/V01/assets/images/flags/cr.png" alt="user-image" height="16">
                            </a>
                            
                        </li>
            
                        
    
                        <li class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo $base_url; ?>/V01/assets/images/users/<? echo $user->vars['id'] ?>.jpg" alt="user-image" class="rounded-circle">
                                <span class="pro-user-name ml-1">
                                   <? echo $user->vars['nombre'] ?> <i class="mdi mdi-chevron-down"></i> 
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>
    
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="fe-user"></i>
                                    <span>My Account</span>
                                </a>
    
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="fe-settings"></i>
                                    <span>Settings</span>
                                </a>
    
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="fe-lock"></i>
                                    <span>Lock Screen</span>
                                </a>
    
                                <div class="dropdown-divider"></div>
    
                                <!-- item-->
                                <a href="<?php echo $base_url; ?>/V01/apps/login/logout.php" class="dropdown-item notify-item">
                                    <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                </a>
    
                            </div>
                        </li>
    
                       
                    </ul>
    
                    <!-- LOGO -->
                    <div class="logo-box">
                        <a href="index.php" class="logo logo-dark text-center">
                            <span class="logo-sm">
                                <img src="<?php echo $base_url; ?>/img/AAZ%20design.png" alt="" height="40">
                                <!-- <span class="logo-lg-text-light">UBold</span> -->
                            </span>
                            <span class="logo-lg">
                                <img src="<?php echo $base_url; ?>/img/AAZ%20design.png" alt="" height="60">
                                <!-- <span class="logo-lg-text-light">U</span> -->
                            </span>
                        </a>
    
                        <a href="index.php" class="logo logo-light text-center">
                            <span class="logo-sm">
                                <img src="<?php echo $base_url; ?>/img/AAZ%20design.png" alt="" height="40">
                            </span>
                            <span class="logo-lg">
                                <img src="<?php echo $base_url; ?>/img/AAZ%20design.png" alt="" height="60">
                            </span>
                        </a>
                    </div>
    
                    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                        <li>
                            <button class="button-menu-mobile waves-effect waves-light">
                                <i class="fe-menu"></i>
                            </button>
                        </li>

                        <li>
                            <!-- Mobile menu toggle (Horizontal Layout)-->
                            <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </li>   
                     </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- end Topbar -->

            <div class="topnav shadow-lg">
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-sidebar mr-1"></i> PAGINAS <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a href="<?php echo $base_url; ?>/V01/apps/menu/index.php" class="dropdown-item">Menu</a>
                                        <a href="<?php echo $base_url; ?>/V01/apps/mainPage/index.php" class="dropdown-item">Pagina Principal</a>
                                        <a href="<?php echo $base_url; ?>/V01/apps/projects/index.php" class="dropdown-item">Proyectos</a>
                                         <a href="<?php echo $base_url; ?>/V01/apps/services/index.php" class="dropdown-item">Servicios</a>
                                        <a href="<?php echo $base_url; ?>/V01/apps/reviews/index.php" class="dropdown-item">Reviews</a>

                                        <a href="<?php echo $base_url; ?>/V01/apps/about/index.php" class="dropdown-item">Conozcanos</a>
                                        <a href="dashboard-4.html" class="dropdown-item">Footer</a>
                                    </div>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-grid mr-1"></i> Apps <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                        <a href="#" class="dropdown-item"><i class="fe-calendar mr-1"></i> Presupuestos</a>
                                        <? /*
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ecommerce"
                                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe-shopping-cart mr-1"></i> Ecommerce <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                                <a href="ecommerce-dashboard.html" class="dropdown-item">Dashboard</a>
                                                <a href="ecommerce-products.html" class="dropdown-item">Products</a>
                                                <a href="ecommerce-product-detail.html" class="dropdown-item">Product Detail</a>
                                          
                                            </div>
                                        </div>
                                        */?>
                                        
                                        
                                        
                                    </div>
                                </li>
                            </ul> <!-- end navbar-->
                        </div> <!-- end .collapsed-->
                    </nav>
                </div> <!-- end container-fluid -->
            </div> <!-- end topnav-->