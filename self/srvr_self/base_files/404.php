<?php
/* $dte1=DateTime::createFromFormat('d-M-Y H:i:s','01-Jan-2019 12:14:56');
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y')." 00:00:00")->modify('+1 day')->format('d-M-Y H:i:s')."<br/>";
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y')." 00:00:00")->modify('+1 month')->format('d-M-Y H:i:s')."<br/>";
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y H:i:s'))->modify('+1 hour')->format('d-M-Y H:i:s')."<br/>"; */
$qstr = "";
$dsply = "";
$actyp = "";
$PKeyID = -1;
$vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
$usrID = $_SESSION['USRID'];
$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$error = "";
$searchAll = true;

$srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
$srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'All';
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Published";
$isMaster = isset($_POST['isMaster']) ? cleanInputData($_POST['isMaster']) : "0";
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
    $srchFor = str_replace("%%", "%", $srchFor);
}

if (isset($_POST['vtyp'])) {
    $vwtyp = cleanInputData($_POST['vtyp']);
}

if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}

$qStrtDte = "";
$qEndDte = "";
$artCategory = "";
$isMaster = "0";

if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) == 19) {
        $qStrtDte = substr($qStrtDte, 0, 10) . " 00:00:00";
    } else {
        $qStrtDte = "";
    }
}

if (isset($_POST['qEndDte'])) {
    $qEndDte = cleanInputData($_POST['qEndDte']);
    if (strlen($qEndDte) == 19) {
        $qEndDte = substr($qEndDte, 0, 10) . " 23:59:59";
    } else {
        $qEndDte = "";
    }
}

if (isset($_POST['artCategory'])) {
    $artCategory = cleanInputData($_POST['artCategory']);
}
require 'srvr_self/header1.php';
?>
<style>
    [class*=sidebar-dark-] {
        <?php echo $forecolors; ?>
        <?php echo $bckcolors_home; ?>
    }
    [class*=sidebar-dark] .brand-link {
        padding-bottom: 22px !important;
    }
    :not(.layout-fixed) {
        .main-sidebar {
            height: inherit;
            min-height: 50% !important;
            position: absolute;
            top: 0;
        }
    }
    .sidebar{
        padding: 0px 0px 10px 0px !important; 
    }
    .rho-gradient-1 {
        background-image: linear-gradient(to bottom right, red, #800000) !important;
        /*, #3693ce*/
    }
    .rho-gradient-2 {
        background-image: linear-gradient(to bottom right, #F2C900, #ff8c00) !important;
    }
    .rho-gradient-3 {
        background-image: linear-gradient(to bottom right, #2ac363, #006400) !important;
    }
    .rho-gradient-4 {
        background-image: linear-gradient(to bottom right, #0048BA, #003245) !important;
    }
    .rounded .card {
        border-radius: .75rem;
    }
    .progress-banner:hover {
        background-position: right top;
    }

    /* Let's get this party started */
    ::-webkit-scrollbar {
        width: 6px;
        height:6px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
        -webkit-border-radius: 10px;
        border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background: rgba(221,221,221,0.8); 
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
    }
    ::-webkit-scrollbar-thumb:window-inactive {
        background: rgba(221,221,221,0.4); 
    }

    .wordwrap3 { 
        white-space: pre-wrap;      /* CSS3 */   
        white-space: -moz-pre-wrap; /* Firefox */    
        white-space: -pre-wrap;     /* Opera <7 */   
        white-space: -o-pre-wrap;   /* Opera 7 */    
        word-wrap: break-word;      /* IE */
        vertical-align: middle;
    }
    .user-panel{
        padding: 12px 0px 11px 0px !important;
        margin: -1px 0px 0px 0px !important;
    }
    #footer {
        padding: 7px !important;
    }
    body{
        min-height:100vh !important;
        line-height: 1.0 !important;
        font-family: "Open Sans",Montserrat,"Playfair Display",Roboto,"Proxima Nova",Tahoma,"Helvetica Neue",Helvetica,Arial,sans-serif !important;
    }
    #allmodules{
        background-color:white !important;
        min-height:87vh !important;
    }
    .handCursor{    
        cursor: pointer; 
        cursor: hand;
    }
    .card{
        padding-top:15px !important;
        padding-bottom:10px !important;
    }
    h1.m-0.text-dark{
        color:<?php echo $bckcolorOnly1; ?> !important;
    }
    .introMsg{
        padding:15px;
        padding-left: 1px !important;
        font-size:14px;
        /*font-family: Tahoma,"Helvetica Neue",Helvetica,Arial,sans-serif;*/
        font-weight:bold;
        text-align: center;
        color:#000;
        margin-bottom:10px;
        margin-top: 0px;
        background:#f9f9f9;
        border:1px solid;
        border-color:#ddd;
        border-radius:5px;
        box-sizing:content-box;
        background-image:-webkit-linear-gradient(top,#fefefe,#f9f9f9);
        background-image:-moz-linear-gradient(top,#fefefe,#f9f9f9);
        background-image: -ms-linear-gradient(top, #fefefe, #f9f9f9);
        background-image:-o-linear-gradient(top,#fefefe,#f9f9f9);
        background-image:linear-gradient(bottom,#fefefe,#f9f9f9);
    }
    /* Carousel base class */
    .carousel {
        margin: 0px 0px 10px 0px;
    }
    /* Since positioning the image, we need to help out the caption */
    .carousel-caption {
        z-index: 10;
    }
    /* Declare heights because of positioning of img element */
    .carousel, .carousel-item {
        background-color: #777;
        height: 300px;
        border-radius:5px;
    }
    .carousel-inner {
        width: 100%;
        height: 100%;
        justify-content: center;
        overflow: hidden;
        height: 300px;
        border-radius:5px;
    }
    .carousel-inner > .carousel-item > img {
        /*position: absolute;
        top: 0;
        left: 0;
        width: auto !important;
        height: auto !important;*/
        max-width: 100% !important;
        max-height: 100% !important;
        width: 100%; 
        height: 100%; 
        object-fit: cover;
        border-radius:5px;
    }
    /* Bump up size of carousel content */
    .carousel-caption p {
        margin-bottom: 20px;
        font-size: 21px;
        line-height: 1.4;
    }
    .navbar-expand .navbar-nav .nav-link {
        padding-right: 0.55rem !important;
        padding-left: 0.55rem !important;
    }
    /*Phones*/
    @media screen and (max-width: 767px) {
        .carousel .item {height: 200px !important;}
        .carousel-caption h1 {font-size:20px !important;}
        .wordwrap1 { 
            left:30%;
        }
        .wordwrap2 { 
            left:30%;
        }
        #startOfDayDate1{
            display:none;
        }
        #startOfDayDate2{
            display:inline-block;
        }
    }
    /*Tablets*/
    @media (min-width: 768px) and (max-width: 991px) {
        .carousel, .carousel-item {height: 200px !important;}
        .carousel-caption h1 {font-size:24px !important;}
        .wordwrap1 { 
            left:35%;
        }
        .wordwrap2 { 
            left:35%;
        }
        #startOfDayDate1{
            display:inline-block;
        }
        #startOfDayDate2{
            display:none;
        }
    }
    /*Laptops*/
    @media (min-width: 992px) and (max-width: 1199px) {
        .carousel-caption h1 {font-size:30px !important;}
        #startOfDayDate1{
            display:inline-block;
        }
        #startOfDayDate2{
            display:none;
        }
    }
    /*Desktops*/
    @media screen and (min-width: 1200px) {
        .carousel-caption h1 {font-size:36px !important;}
        #startOfDayDate1{
            display:inline-block;
        }
        #startOfDayDate2{
            display:none;
        }
    }

</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php //margin-top:5px;  ?>
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php" role="button"><i class="fas fa-home"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php //margin:5px 10px 10px 5px !important;border-radius: 5px !important;max-height:600px !important;height: 600px !important;min-height: 98% !important;  ?>
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link" style="line-height:1.0 !important;">
                <img src="../cmn_images/<?php echo $app_image1; ?>" alt="Org Logo" class="brand-image img-circle elevation-3" style="opacity: .99">
                <span class="brand-text font-weight-light" style="font-size:13px !important;text-overflow: ellipsis;font-weight:bold;"><?php echo $app_name; ?></span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../cmn_images/image_up.png" class="img-circle elevation-2" alt="User Image" style="height: 45px !important; width: auto !important;">
                    </div>
                    <div class="info" style="line-height:2.0 !important;">
                        <a href="javascript:openATab('#allmodules', 'grp=8&typ=1&pg=1&vtyp=0');" class="d-block" style="<?php echo $forecolors; ?>">GUEST</a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="index.php" class="nav-link" style="<?php echo $forecolors; ?>">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Home</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" id="allmodules">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>404 Error Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">404 Error Page</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="error-page">
                    <h2 class="headline text-warning"> 404</h2>

                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

                        <p>
                            We could not find the page you were looking for.
                            Meanwhile, you may <a href="index.php">return to the dashboard</a> or <a href="mailto:<?php echo $admin_email; ?>?subject=Mail from <?php echo $page_title; ?>">Email the System Administrator</a>.
                        </p>
                    </div>
                    <!-- /.error-content -->
                </div>
                <!-- /.error-page -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <div id="footer" class="main-footer">
            <div style="min-height:20px;" style="<?php echo $bckcolors_home; ?>">
                <div class="col-md-12" style="<?php echo $bckcolors_home; ?>color:#FFF;font-family: Times;font-style: italic;font-size:12px;text-align:center;padding-top:5px;padding-bottom:5px;">
                    <p style="margin: 0px !important;">Copyright &COPY; <?php echo date('Y'); ?> <a style="color:#FFF" href="<?php echo $about_url; ?>" target="_blank"><?php echo $app_org; ?></a>.</p>
                </div>
            </div>
        </div>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <?php
    require 'srvr_self/footer1.php';
    ?>