<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num <= 0) {
        //echo $lgn_num; 
?>
    <?php
    } else {
        $prsnid = $_SESSION['PRSN_ID'];
        $orgID = $_SESSION['ORG_ID'];
        $cntrsRslt = getHmPgIndicators($prsnid);
        $inbxCntr = $cntrsRslt[0];
        $forumCntr = $cntrsRslt[1];
        $artclCntr = $cntrsRslt[2];
        $mdlsCntr = $cntrsRslt[3];
        $nwFileName = $myImgFileName;
        $prmSnsRstl = getHomePgPrmssns($prsnid);
        $lnkdFirmID = $prmSnsRstl[0];
        $canViewSelfsrvc = ($prmSnsRstl[1] >= 1) ? true : false;
        $canViewEvote = ($prmSnsRstl[2] >= 1) ? true : false;
        $canViewElearn = ($prmSnsRstl[3] >= 1) ? true : false;
        $canViewAcntng = ($prmSnsRstl[4] >= 1) ? true : false;
        $canViewPrsn = ($prmSnsRstl[5] >= 1) ? true : false;
        $canViewIntrnlPay = ($prmSnsRstl[6] >= 1) ? true : false;
        $canViewSales = ($prmSnsRstl[7] >= 1) ? true : false;
        $canViewVsts = ($prmSnsRstl[8] >= 1) ? true : false;
        $canViewEvnts = ($prmSnsRstl[9] >= 1) ? true : false;
        $canViewHotel = ($prmSnsRstl[10] >= 1) ? true : false;
        $canViewClnc = ($prmSnsRstl[11] >= 1) ? true : false;
        $canViewBnkng = ($prmSnsRstl[12] >= 1) ? true : false;
        $canViewPrfmnc = ($prmSnsRstl[13] >= 1) ? true : false;
        $canViewProjs = ($prmSnsRstl[14] >= 1) ? true : false;
        $canViewVMS = ($prmSnsRstl[15] >= 1) ? true : false;
        $canViewAgnt = false; // ($prmSnsRstl[16] >= 1) ? true : 
        $canViewATrckr = false; //($prmSnsRstl[17] >= 1) ? true : 
        $canViewSysAdmin = ($prmSnsRstl[18] >= 1) ? true : false;
        $canViewOrgStp = ($prmSnsRstl[19] >= 1) ? true : false;
        $canViewLov = ($prmSnsRstl[20] >= 1) ? true : false;
        $canViewWkf = ($prmSnsRstl[21] >= 1) ? true : false;
        $canViewArtclAdmn = ($prmSnsRstl[22] >= 1) ? true : false;
        $canViewRpts = ($prmSnsRstl[23] >= 1) ? true : false;
        $canViewHlpDsk = ($prmSnsRstl[27] >= 1) ? true : false;
        $startOfDayDate = substr(getStartOfDayDMYHMS(), 0, 11);
    ?>
        <link href="cmn_scrpts/bootstrap337/bootstrap3-dialog/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
        <link href="cmn_scrpts/bootstrap337/datatables/DataTables-1.10.11/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="cmn_scrpts/bootstrap337/bootstrap-dtimepckr/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="cmn_scrpts/jquery-ui-1121/jquery-ui.min.css">
        <link href="cmn_scrpts/summernote081/summernote.css" rel="stylesheet" type="text/css" />
        <link href="cmn_scrpts/carousel.css?v=<?php echo $jsCssFileVrsn; ?>" rel="stylesheet">
        <style>
            .icon-bar {
                <?php echo $bckcolorshv;
                ?>
            }

            .nav.navbar-top-links>li>a:hover,
            .nav.navbar-top-links>li>a:focus {
                <?php echo $forecolors;
                ?><?php echo $bckcolorshv;
                    ?>text-decoration: none;
            }

            .nav.navbar-top-links>li>a {
                <?php echo $forecolors;
                ?><?php echo $bckcolors_home;
                    ?>text-decoration: none;
            }

            .nav.nav-tabs>li>a:hover,
            .nav.nav-tabs>li>a:focus {
                <?php echo $forecolors;
                ?><?php echo $bckcolorshv;
                    ?>text-decoration: none;
            }

            .nav.nav-tabs>li.active>a,
            .nav.nav-tabs>li.active>a:focus,
            .nav.nav-tabs>li.active>a:hover {
                cursor: pointer;
                cursor: hand;
                <?php echo $forecolors;
                ?><?php echo $bckcolors_home;
                    ?>border: 1px solid <?php echo $bckcolorOnly;
                                        ?>
            }

            .rho-card-body2 {
                max-height: 130px !important;
                height: 130px !important;
                overflow: hidden;
                text-overflow: ellipsis;
                padding: 2px !important;
                margin-bottom: 5px !important;
            }

            .page-header.navbar {
                <?php echo $bckcolors_home;
                ?><?php echo $forecolors;
                    ?>border-bottom: 1px solid #fff;
            }

            .page-top {
                <?php echo $bckcolors_home;
                ?><?php echo $forecolors;
                    ?>
            }

            .page-sidebar,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover {
                <?php echo $bckcolors_home;
                ?>
            }

            .page-sidebar .page-sidebar-menu>li.open>a,
            .page-sidebar .page-sidebar-menu>li:hover>a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.open>a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li:hover>a {
                <?php echo $bckcolorshv;
                ?><?php echo $forecolors;
                    ?>
            }

            .page-sidebar .page-sidebar-menu .sub-menu li>a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu li>a {
                display: block;
                margin: 0;
                padding: 9px 14px 9px 30px;
                text-decoration: none;
                font-size: 14px;
                font-weight: 400;
                <?php echo $bckcolors_home;
                ?><?php echo $forecolors;
                    ?>
            }

            .page-sidebar .page-sidebar-menu .sub-menu>li.active>a,
            .page-sidebar .page-sidebar-menu .sub-menu>li.open>a,
            .page-sidebar .page-sidebar-menu .sub-menu>li:hover>a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li.active>a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li.open>a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li:hover>a {
                <?php echo $bckcolorshv;
                ?><?php echo $forecolors;
                    ?>
            }

            @media (max-width: 767px) {
                .page-top {
                    <?php echo $bckcolorshv;
                    ?><?php echo $forecolors;
                        ?>
                }

                .nav.navbar-top-links>li>a {
                    <?php echo $forecolors;
                    ?><?php echo $bckcolorshv;
                        ?>text-decoration: none;
                }
            }

            .login p.others {
                margin: 8px 0 0;
            }

            .login p.label {
                margin: -2px 0 0;
            }

            .login p:first-child {
                margin-top: 0;
            }

            .login input[type=text],
            .login input[type=password] {
                height: 40px;
                background-color: #ffc;
                border-radius: 0px 1px 1px 0px !important;
                -moz-outline-radius: 1px !important;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.12);
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.12);
            }

            /*a:link {
                color:  <?php echo $bckcolorOnly1; ?>;
            }
            a:visited {
                color: <?php echo $bckcolorOnly2; ?>;
            }
            a:hover {
                color: <?php echo $bckcolorOnly2; ?>;
            }
            a:active {
                color:  <?php echo $bckcolorOnly1; ?>;
            }*/


            .center {
                text-align: center;
            }

            .menu1 {
                width: 300px;
                z-index: 1;
                position: fixed;
                display: none;
                transition: 0.2s display ease-in;
            }


            .menu1-options {
                list-style: none;
                padding: 0;
                z-index: 1;
                border: solid 1px #ddd;
                box-shadow: 0 4px 5px 0px rgba(0, 0, 0, 0.2);
            }

            .menu1-option {
                font-weight: 500;
                z-index: 1;
                font-size: 14px;
                padding: 2px 20px 2px 15px;
                cursor: pointer;
                background: white;
            }

            .menu1-option:hover {
                background: #ddd;
            }

            @keyframes move {
                from {
                    transform: translate(0%);
                }

                50% {
                    transform: translate(-40%);
                }

                to {
                    transform: transform(0%);
                }
            }
        </style>
        </head>

        <body class="rhBody mdlloading page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo" style="background-color:white;min-width: 360px;height:100% !important;width:100% !important;overflow: auto;font-display: fallback !important;">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <div style="float:left;left: 0.5%; min-width:90%;">
                            <a href="index.php"><img src="<?php echo $app_image1; ?>" style="float:left;height:60px; width:auto; margin:5px; position: relative; vertical-align: middle;" id="mainLogo" /></a>
                            <a class="navbar-brand" href="index.php" style="<?php echo $forecolors; ?>; vertical-align: middle;"><?php echo $app_name; ?>&nbsp;<span style="color:cyan;font-weight:bold;"><?php echo $startOfDayDate; ?></span></a>
                        </div>
                        <div class="menu-toggler sidebar-toggler" style="float:right;">
                            <!-- margin: 5px 5px 0px 5px !important;DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                        </div>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN PAGE TOP javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&vtyp=6');-->
                    <div class="page-top">
                        <!-- BEGIN TOP NAVIGATION MENU -->
                        <div class="top-menu">
                            <ul class="nav navbar-top-links navbar-right navbar-nav">
                                <li class="dropdown">
                                    <a href="<?php echo $app_url . 'self'; ?>" class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom" title="Forums/Chat Rooms">
                                        <i class="fa fa-wechat fa-fw" style="<?php echo $forecolors; ?>"></i>
                                        <span class="badge bg-success" style="background-color: red;float:right;"><?php echo $forumCntr; ?></span>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="javascript:openATab('#profile', 'grp=3&typ=1&pg=1&vtyp=4&sbmtdUserID=<?php echo $usrID; ?>');" class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom" title="Roles/Priviledges!">
                                        <i class="fa fa-user fa-fw" style="<?php echo $forecolors; ?>font-size: 19px;"></i>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="javascript:window.location='index.php?cp=1';" class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom" title="Change Password!">
                                        <i class="fa fa-gear fa-fw" style="<?php echo $forecolors; ?>font-size: 19px;"></i>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="javascript: logOutFunc();" class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom" title="Logout!">
                                        <i class="fa fa-sign-out fa-fw" style="<?php echo $forecolors; ?>font-size: 19px;"></i>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="javascript:openATab('#allmodules', 'grp=8&typ=1&pg=1&vtyp=0');" data-toggle="tooltip" title="Person Profile!" data-placement="bottom">
                                        <span class="username" style="<?php echo $forecolors; ?>font-weight: bold;">
                                            <?php echo $usrName; ?> </span>
                                        <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                                        <img alt="" class="img-circle" src="<?php echo $tmpDest . $nwFileName; ?>" style="height: 45px !important; width: auto !important;">
                                    </a>
                                    <!-- /.dropdown-user -->
                                </li>
                                <!-- /.dropdown -->
                            </ul>
                        </div>
                        <!-- END TOP NAVIGATION MENU -->
                    </div>
                    <!-- END PAGE TOP -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <!-- BEGIN SIDEBAR MENU -->
                        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                        <ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                            <li class="nav-item ">
                                <a href="javascript:;" style="<?php echo $forecolors; ?>min-height:45px !important;">
                                    <i class="fa fa-search-plus fa-fw" style="<?php echo $forecolors; ?>float:left;"></i>
                                    <span class="title" style="float:right;max-width:88%;">
                                        <div class="input-group custom-search-form" style="float:right;margin-top:0px;">
                                            <input type="text" class="form-control" placeholder="Search Notices..." onkeyup="window.location = '<?php echo $app_url . "self"; ?>';">
                                            <span class="input-group-btn">
                                                <button class="btn1 btn-default" type="button" onclick="javascript:window.location = '<?php echo $app_url . "self"; ?>';">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </span>
                                </a>
                                <!-- /input-group -->
                            </li>
                            <li class="nav-item  ">
                                <a href="index.php" style="<?php echo $forecolors; ?>">
                                    <i class="fa fa-home fa-fw" style="<?php echo $forecolors; ?>"></i>
                                    <span class="title">Home Page</span>
                                </a>
                            </li>
                            <li class="nav-item hideNotice">
                                <a href="javascript: logOutFunc();" style="<?php echo $forecolors; ?>">
                                    <i class="fa fa-sign-out fa-fw" style="<?php echo $forecolors; ?>"></i>
                                    <span class="title">Logout (<?php echo $usrName; ?>)</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="javascript:openATab('#myinbox', 'grp=40&typ=2');" class="nav-link " style="<?php echo $forecolors; ?>">
                                    <i class="fa fa-envelope fa-fw" style="<?php echo $forecolors; ?>"></i>
                                    <span class="title">Inbox/Worklist</span>
                                    <span class="badge bg-success" style="background-color: lime;float:right;"><?php echo $inbxCntr; ?></span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link " style="<?php echo $forecolors; ?>">
                                    <i class="fa fa-bar-chart-o fa-fw" style="<?php echo $forecolors; ?>"></i>
                                    <span class="title">Charts/Reports</span>
                                    <span class="fa arrow" style="<?php echo $forecolors; ?>"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=4');" class="nav-link " style="<?php echo $forecolors; ?>">
                                            <span class="title">My Charts</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=4');" class="nav-link " style="<?php echo $forecolors; ?>">
                                            <span class="title">General Charts</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="javascript:openATab('#allmodules', 'grp=9&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                            <span class="title">Reports/Processes</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <?php if ($canViewSelfsrvc) { ?>
                                <li class="nav-item  ">
                                    <a href="self/" class="nav-link " style="<?php echo $forecolors; ?>">
                                        <i class="fa fa-wrench fa-fw" style="<?php echo $forecolors; ?>"></i>
                                        <span class="title">Self-Service</span>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (false) { ?>
                                <li class="nav-item  ">
                                    <a href="javascript:;" class="nav-link " style="<?php echo $forecolors; ?>">
                                        <i class="fa fa-wrench fa-fw" style="<?php echo $forecolors; ?>"></i>
                                        <span class="title">Self-Service</span>
                                        <span class="fa arrow" style="<?php echo $forecolors; ?>"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item  ">
                                            <a href="javascript:openATab('#allmodules', 'grp=8&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                <span class="title">Personal Records</span>
                                            </a>
                                        </li>
                                        <li class="nav-item  ">
                                            <a href="javascript:openATab('#allmodules', 'grp=7&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                <span class="title">Bills/Payments</span>
                                            </a>
                                        </li>
                                        <li class="nav-item  ">
                                            <a href="javascript:openATab('#allmodules', 'grp=16&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                <span class="title">Events/Attendances</span>
                                            </a>
                                        </li>
                                        <?php if ($canViewEvote) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=19&typ=10');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">e-Voting</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewElearn) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=19&typ=12');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">e-Library</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <!-- /.nav-second-level -->
                                </li>
                            <?php } ?>
                            <!--<li class="nav-item  ">
                                <a href="javascript:;" class="nav-link " style="<?php echo $forecolors; ?>">
                                    <i class="fa fa-sitemap fa-fw" style="<?php echo $forecolors; ?>"></i> 
                                    <span class="title">All Other Modules</span>
                                    <span class="fa arrow" style="<?php echo $forecolors; ?>"></span>
                                </a>
                                <ul class="sub-menu">
                                </ul>
                            </li>-->
                            <?php
                            if ($canViewAcntng || $canViewPrsn || $canViewIntrnlPay || $canViewSales || $canViewVsts || $canViewEvnts) {
                            ?>
                                <li class="nav-item  ">
                                    <a href="javascript:;" class="nav-link " style="<?php echo $forecolors; ?>">
                                        <i class="fa fa-anchor fa-fw" style="<?php echo $forecolors; ?>"></i>
                                        <span class="title">Core Modules</span>
                                        <span class="fa arrow" style="<?php echo $forecolors; ?>"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <?php if ($canViewAcntng) { ?>
                                            <li>
                                                <a href="javascript:openATab('#allmodules', 'grp=6&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Accounting</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewPrsn) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=8&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Basic Person Data</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewIntrnlPay) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=7&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Internal Payments</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewSales) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=12&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Sales/Inventory</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewVsts) { ?>
                                            <li>
                                                <a href="javascript:openATab('#allmodules', 'grp=14&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Visits/Appointments</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewEvnts) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=16&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Events/Attendance</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                            <?php
                            if ($canViewHotel || $canViewClnc || $canViewBnkng || $canViewPrfmnc || $canViewProjs || $canViewVMS || $canViewAgnt || $canViewATrckr) {
                            ?>
                                <li class="nav-item  ">
                                    <a href="javascript:;" class="nav-link " style="<?php echo $forecolors; ?>">
                                        <i class="fa fa-desktop fa-fw" style="<?php echo $forecolors; ?>"></i>
                                        <span class="title">Specialty Modules</span>
                                        <span class="fa arrow" style="<?php echo $forecolors; ?>"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <?php if ($canViewHotel) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=18&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Hospitality Management</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewClnc) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=14&typ=1&mdl=Clinic/Hospital');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Clinic/Hospital</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewBnkng) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=17&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Banking</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewVMS) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=25&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Vault Management</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewAgnt) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=26&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Agent Registry</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewATrckr) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=27&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Asset Tracking</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewPrfmnc) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=15&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Performance Management</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewProjs) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=13&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <span class="title">Projects Management</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                            <?php
                            if ($canViewSysAdmin || $canViewOrgStp || $canViewLov || $canViewWkf) {
                            ?>
                                <li class="nav-item  ">
                                    <a href="javascript:;" class="nav-link " style="<?php echo $forecolors; ?>">
                                        <i class="fa fa-key fa-fw" style="<?php echo $forecolors; ?>"></i>
                                        <span class="title">Administration</span>
                                        <span class="fa arrow" style="<?php echo $forecolors; ?>"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <?php if ($canViewSysAdmin) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=3&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <i class="fa fa-user fa-fw" style="<?php echo $forecolors; ?>"></i>
                                                    <span class="title">System Administration</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewOrgStp) { ?>
                                            <li>
                                                <a href="javascript:openATab('#allmodules', 'grp=5&typ=1&pg=1&vtyp=0');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <i class="fa fa-group fa-fw" style="<?php echo $forecolors; ?>"></i>
                                                    <span class="title">Organization Setup</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewLov) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=4&typ=1&pg=1&vtyp=0');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <i class="fa fa-list-ul fa-fw" style="<?php echo $forecolors; ?>"></i>
                                                    <span class="title">Value Lists Setup</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewWkf) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=11&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <i class="fa fa-globe fa-fw" style="<?php echo $forecolors; ?>"></i>
                                                    <span class="title">Workflow Administration</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                            <?php } ?>
                            <li class="nav-item hideNotice">
                                <a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&vtyp=5');" class="nav-link " style="<?php echo $forecolors; ?>">
                                    <i class="fa fa-comment fa-fw" style="<?php echo $forecolors; ?>"></i>
                                    <span class="title">Comments/Feedback</span>
                                </a>
                            </li>
                            <li class="nav-item hideNotice">
                                <a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&vtyp=6');" class="nav-link " style="<?php echo $forecolors; ?>">
                                    <i class="fa fa-wechat fa-fw" style="<?php echo $forecolors; ?>"></i>
                                    <span class="title">Forums/Chat Rooms</span>
                                    <span class="badge bg-success" style="background-color: red;float:right;"><?php echo $forumCntr; ?></span>
                                </a>
                            </li>
                            <?php if ($canViewHlpDsk) { ?>
                                <li class="nav-item  ">
                                    <a href="javascript:openATab('#allmodules', 'grp=41&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                        <i class="fa fa-headphones fa-fw" style="<?php echo $forecolors; ?>"></i>
                                        <span class="title">Help Desk</span>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="nav-item  ">
                                <a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&vtyp=20');" class="nav-link " style="<?php echo $forecolors; ?>">
                                    <i class="fa fa-file-text-o fa-fw" style="<?php echo $forecolors; ?>"></i>
                                    <span class="title">System Manuals</span>
                                </a>
                            </li>
                        </ul>
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content" style="min-height:1080px !important;">
                        <!-- BEGIN PAGE HEAD-->
                        <div class="page-head">
                        </div>
                        <!-- END PAGE HEAD-->
                        <!-- BEGIN PAGE BASE CONTENT -->
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs rho-hideable-tabs" role="tablist" style="padding-top:0px;" id="navtabheaders">
                            <li class="active"><a href="#home" id="hometab" data-toggle="tab" data-rhodata="grp=40&typ=1"><i class="fa fa-home fa-fw"></i><span class="nav-label">Home</span><span class="badge bg-success" style="background: none;">&nbsp;</span></a></li>
                            <li><a href="#myinbox" id="myinboxtab" data-toggle="tabajax" data-rhodata="grp=40&typ=2"><i class="fa fa-envelope fa-fw"></i><span class="nav-label">My
                                        Inbox&nbsp;&nbsp;</span><span class="badge bg-success" style="background-color: lime;"><?php echo $inbxCntr; ?></span></a></li>
                            <li><a href="#allnotices" id="allnoticestab" data-toggle="tabajax" data-rhodata="grp=40&typ=3"><i class="fa fa-file-text-o fa-fw"></i><span class="nav-label">All
                                        Notices&nbsp;&nbsp;</span><span class="badge bg-success" style="background-color: #f0ad4e;"><?php echo $artclCntr; ?></span></a></li>
                            <li><a href="#profile" id="profiletab" data-toggle="tabajax" data-rhodata="grp=3&typ=1&pg=1&vtyp=4&sbmtdUserID=<?php echo $usrID; ?>"><i class="fa fa-user fa-fw"></i><span class="nav-label">Profile&nbsp;&nbsp;</span><span class="badge bg-success" style="background: none;">&nbsp;</span></a></li>
                            <li><a href="#allmodules" id="allmodulestab" data-toggle="tabajax" data-rhodata="grp=40&typ=5"><i class="fa fa-sitemap fa-fw"></i><span class="nav-label">All
                                        Apps/Modules&nbsp;&nbsp;</span><span class="badge bg-success" style="background-color: cyan;color:#333;" id="appsMdlsCnt"><?php echo $mdlsCntr; ?></span></a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="home">
                                <?php require 'app_code/cmncde/' . $homepgfile; ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="myinbox"></div>
                            <div role="tabpanel" class="tab-pane" id="allnotices"></div>
                            <div role="tabpanel" class="tab-pane" id="profile"></div>
                            <div role="tabpanel" class="tab-pane" id="allmodules"></div>
                            <!-- END PAGE BASE CONTENT -->
                        </div>
                        <!-- END CONTENT BODY -->
                    </div>
                    <!-- END CONTENT -->
                    <br />
                </div>
                <!-- END CONTAINER -->

                <!-- BEGIN FOOTER -->
                <div class="page-footer">
                    <div style="min-height:20px;" style="<?php echo $bckcolors_home; ?>">
                        <div class="col-md-12" style="<?php echo $bckcolors_home; ?>color:#FFF;font-family: Times;font-style: italic;font-size:12px;text-align:center;border-top:1px solid #FFF;padding-top:5px;">
                            <p>Copyright &COPY; <?php echo date('Y'); ?> <a style="color:#FFF" href="<?php echo $about_url; ?>" target="_blank"><?php echo $app_org; ?></a>.</p>
                        </div>
                    </div>
                </div>
                <div class="modalLdng"></div>
                <div class="modal fade" id="sndBlkMsgForm" tabindex="-1" role="dialog" aria-labelledby="sndBlkMsgFormTitle" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" style="width:90% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="sndBlkMsgFormTitle">Send Bulk Email/SMS</h4>
                            </div>
                            <div class="modal-body" id="sndBlkMsgFormBody" style="border-bottom: none !important;">
                            </div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal-7" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 9997 !important;">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <span class="modal-title msgtitle">System Alert!</span>
                            </div>
                            <div class="modal-body">
                                Content is loading...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-white" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myLovModal" tabindex="-1" role="dialog" aria-labelledby="myLovModalTitle" style="z-index: 10000 !important;">
                    <div class="modal-dialog" role="document" id="myLovModalDiag">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myLovModalTitle"></h4>
                            </div>
                            <div class="modal-body" id="myLovModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalNrml" tabindex="-1" role="dialog" aria-labelledby="myFormsModalNrmlTitle" style="z-index: 9995 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalNrmlDiag" style="min-width:340px;max-width:90%;width:60%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalNrmlTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalNrmlBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalNrmlZI" tabindex="-1" role="dialog" aria-labelledby="myFormsModalNrmlTitleZI" style="z-index: 9999 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalNrmlDiagZI" style="min-width:340px;max-width:90%;width:60%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalNrmlTitleZI"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalNrmlBodyZI" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLgZI" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLgZI" style="z-index: 9999 !important;">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:93%;width:93%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleLgZI"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyLgZI" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalNm" tabindex="-1" role="dialog" aria-labelledby="myFormsModalNmTitle" style="z-index: 9994 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalNmDiag" style="min-width:340px;max-width:90%;width:80%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalNmTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalNmBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLRG" tabindex="-1" role="dialog" aria-labelledby="myFormsModalLRGTitle" style="z-index: 9993 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalLRGDiag" style="min-width:340px;max-width:95%;width:95%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalLRGTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalLRGBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModaln" tabindex="-1" role="dialog" aria-labelledby="myFormsModalnTitle" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalnDiag" style="min-width:300px;max-width:90%;width:60%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalnTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalnBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModaly" tabindex="-1" role="dialog" aria-labelledby="myFormsModalyTitle" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalyDiag" style="min-width:340px;max-width:90%;width:50%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalyTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalyBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalx" tabindex="-1" role="dialog" aria-labelledby="myFormsModalxTitle" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalxDiag" style="min-width:340px;max-width:90%;width:40%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalxTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalxBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalp" tabindex="-1" role="dialog" aria-labelledby="myFormsModalpTitle" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalpDiag" style="min-width:340px;max-width:90%;width:35%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalpTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalpBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModal" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitle" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" style="max-width:400px;" id="myFormsModalDiag">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLg" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLg">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:93%;width:93%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleLg"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyLg" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLx" tabindex="-1" role="dialog" aria-labelledby="myFormsModalLxTitle">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:87%;width:87%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalLxTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalLxBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myLovModal1" tabindex="-1" role="dialog" aria-labelledby="myLovModalTitle" style="z-index: 10001 !important;">
                    <div class="modal-dialog" role="document" id="myLovModalDiag">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myLovModal1Title"></h4>
                            </div>
                            <div class="modal-body" id="myLovModal1Body" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLgz" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLgz">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:87%;width:87%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleLgz"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyLgz" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModal1" tabindex="-1" role="dialog" aria-labelledby="myFormsModal1Title">
                    <div class="modal-dialog" role="document" style="width:85% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModal1Title"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModal1Body" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <!--  style="min-width: 1000px;left:-35%;"-->
                <div class="modal fade" id="myFormsModalCA" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleCA">
                    <div class="modal-dialog" role="document" style="width:90% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="updateCustAccountsListData1();"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleCA"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyCA" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalCA1" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleCA1">
                    <div class="modal-dialog" role="document" style="width:90% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleCA1"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyCA1" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLgCA" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLgCA">
                    <div class="modal-dialog" role="document" style="width:88% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleLgCA"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyLgCA" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLgZ" tabindex="-1" role="dialog" aria-labelledby="myFormsModalLgZTitle" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:90%;width:90%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalLgZTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalLgZBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLgY" tabindex="-1" role="dialog" aria-labelledby="myFormsModalLgYTitle" style="z-index: 9998 !important;">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:90%;width:90%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalLgYTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalLgYBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalCB" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleCB">
                    <div class="modal-dialog" role="document" style="width:90% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick=""><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleCB"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyCB" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalxLG" tabindex="-1" role="dialog" aria-labelledby="myFormsModalxLGTitle">
                    <div class="modal-dialog" role="document" id="myFormsModalxLGDiag" style="min-width:340px;max-width:90%;width:50%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalxLGTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalxLGBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myLovModal99" tabindex="-1" role="dialog" aria-labelledby="myLovModalTitle99" style="z-index: 10002 !important; width:60% !important;">
                    <div class="modal-dialog" role="document" id="myLovModalDiag" style="width:50% !important;max-width: 50% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myLovModalTitle99"></h4>
                            </div>
                            <div class="modal-body" id="myLovModalBody99" style="max-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                                <button type="button" class="btn btn-white" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--CLINIC/HOSPITAL-->
                <div class="modal fade" id="myFormsModalLgHZ" tabindex="-1" role="dialog" aria-labelledby="myFormsModalLgHZTitle" style="z-index: 9995 !important;">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:90%;width:90%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalLgHZTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalLgHZBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myLovModalH" tabindex="-1" role="dialog" aria-labelledby="myLovModalTitleH" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" id="myLovModalDiagH">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myLovModalTitleH"></h4>
                            </div>
                            <div class="modal-body" id="myLovModalBodyH" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLgH" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLgH" style="z-index: 9994 !important;">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:93%;width:93%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleLgH"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyLgH" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLgYH" tabindex="-1" role="dialog" aria-labelledby="myFormsModalLgYHTitle" style="z-index: 9995 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalLgYHDiag" style="min-width:300px;max-width:93%;width:93%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalLgYHTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalLgYHBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalyH" tabindex="-1" role="dialog" aria-labelledby="myFormsModalyHTitle" style="z-index: 9995 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalyHDiag" style="min-width:300px;max-width:90%;width:70%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalyHTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalyHBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLxH" tabindex="-1" role="dialog" aria-labelledby="myFormsModalLxHTitle" style="z-index: 9995 !important;">
                    <div class="modal-dialog" role="document" style="min-width:300px;max-width:87%;width:87%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalLxHTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalLxHBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalxH" tabindex="-1" role="dialog" aria-labelledby="myFormsModalxHTitle" style="z-index: 9995 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalxHDiag" style="min-width:340px;max-width:90%;width:40%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalxHTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalxHBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLgZH" tabindex="-1" role="dialog" aria-labelledby="myFormsModalLgZHTitle" style="z-index: 9997 !important;">
                    <div class="modal-dialog" role="document" id="myFormsModalLgZHDiag" style="min-width:300px;max-width:98%;width:98%;max-height: 500px !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalLgZHTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalLgZHBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalH" tabindex="-1" role="dialog" aria-labelledby="myFormsModalHTitle" style="z-index: 9995 !important;">
                    <div class="modal-dialog" role="document" style="max-width:400px;" id="myFormsModalHDiag">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalHTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalHBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;"></div>
                        </div>
                    </div>
                </div>
                <div id="custoverlay" style="z-index: 9998 !important;">
                    <div id="custoverlaytext" style="display:none !important;">Loading Historic Data....Please Wait....</div>
                </div>
                <!--CLINIC/HOSPITAL-->
                <div id="allOtherContent"></div>
                <iframe id="allOtherIframe1" style="display:none;font-display: fallback !important;" src=""></iframe>
                <a id="allOtherATag1" style="display:none" href="" download="exported_table.xls"></a>
                <input type="hidden" id="allOtherInputData1" value="" />
                <input type="hidden" id="allOtherInputData2" value="" />
                <input type="hidden" id="allOtherInputData3" value="" />
                <input type="hidden" id="allOtherInputData4" value="" />
                <input type="hidden" id="allOtherInputData5" value="" />
                <input type="hidden" id="allOtherInputData99" value="0" />
                <input type="hidden" id="allOtherInputData101" value="0" />
                <input id="allOtherFileInput1" type="file" style="visibility:hidden" />
                <input id="allOtherFileInput2" type="file" style="visibility:hidden" />
                <input id="allOtherFileInput3" type="file" style="visibility:hidden" />
                <input id="allOtherFileInput4" type="file" style="visibility:hidden" />
                <input id="allOtherFileInput5" type="file" style="visibility:hidden" />
                <input type="hidden" id="allOtherInputOrgID" value="<?php echo $orgID; ?>">
                <input type="hidden" id="allOtherInputUsrID" value="<?php echo $usrID; ?>">
                <input id="allOtherFileInput6" type="file" style="visibility:hidden" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                <!-- END FOOTER -->
                <!--[if lt IE 9]>
                <!--jQuery-->
                <!--<link rel="stylesheet" type="text/css" href="cmn_scrpts/MDB Free/css/mdb.min.css"/>-->
                <!-- jQuery -->
                <script src="cmn_scrpts/jquery-1.12.3.min.js"></script>
                <script type="text/javascript" src="cmn_scrpts/jquery-ui-1121/jquery-ui.min.js"></script>
                <!-- Bootstrap Core JavaScript -->
                <script src="cmn_scrpts/bootstrap337/js/bootstrap.min.js"></script>
                <script src="cmn_scrpts/bootstrap337/bootstrap3-dialog/js/bootstrap-dialog.min.js"></script>
                <script type="text/javascript">
                    var jsFilesVrsn = '<?php echo $jsCssFileVrsn; ?>';
                    var noticesElmntNm = '<?php echo $noticesElmntNm; ?>';
                    var shdHideFSRpt;
                    var autoCreateSalesLns = -1;
                    var trnsNtAllwdDts = new Array();
                    var trnsNtAllwdDys = new Array();
                    var App = function() {
                        var t, e = !1,
                            o = !1,
                            a = !1,
                            i = !1,
                            n = [],
                            l = "../assets/",
                            s = "global/img/",
                            r = "global/plugins/",
                            c = "global/css/",
                            d = {
                                blue: "#89C4F4",
                                red: "#F3565D",
                                green: "#1bbc9b",
                                purple: "#9b59b6",
                                grey: "#95a5a6",
                                yellow: "#F8CB00"
                            },
                            p = function() {
                                "rtl" === $("body").css("direction") && (e = !0), o = !!navigator.userAgent.match(/MSIE 8.0/),
                                    a = !!navigator.userAgent.match(/MSIE 9.0/), i = !!navigator.userAgent.match(/MSIE 10.0/),
                                    i && $("html").addClass("ie10"), (i || a || o) && $("html").addClass("ie")
                            },
                            h = function() {
                                for (var t = 0; t < n.length; t++) {
                                    var e = n[t];
                                    e.call()
                                }
                            },
                            u = function() {
                                var t;
                                if (o) {
                                    var e;
                                    $(window).resize(function() {
                                        e != document.documentElement.clientHeight && (t && clearTimeout(t), t =
                                            setTimeout(function() {
                                                h()
                                            }, 50), e = document.documentElement.clientHeight)
                                    })
                                } else
                                    $(window).resize(function() {
                                        t && clearTimeout(t), t = setTimeout(function() {
                                            h()
                                        }, 50)
                                    })
                            },
                            f = function() {
                                $("body").on("click", ".portlet > .portlet-title > .tools > a.remove", function(t) {
                                    t.preventDefault();
                                    var e = $(this).closest(".portlet");
                                    $("body").hasClass("page-portlet-fullscreen") && $("body").removeClass(
                                            "page-portlet-fullscreen"), e.find(".portlet-title .fullscreen").tooltip(
                                            "destroy"), e.find(".portlet-title > .tools > .reload").tooltip("destroy"),
                                        e.find(".portlet-title > .tools > .remove").tooltip("destroy"), e.find(
                                            ".portlet-title > .tools > .config").tooltip("destroy"), e.find(
                                            ".portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand"
                                        ).tooltip("destroy"), e.remove()
                                }), $("body").on("click", ".portlet > .portlet-title .fullscreen", function(t) {
                                    t.preventDefault();
                                    var e = $(this).closest(".portlet");
                                    if (e.hasClass("portlet-fullscreen"))
                                        $(this).removeClass("on"), e.removeClass("portlet-fullscreen"), $("body")
                                        .removeClass("page-portlet-fullscreen"), e.children(".portlet-body").css(
                                            "height", "auto");
                                    else {
                                        var o = App.getViewPort().height - e.children(".portlet-title").outerHeight() -
                                            parseInt(e.children(".portlet-body").css("padding-top")) - parseInt(e
                                                .children(".portlet-body").css("padding-bottom"));
                                        $(this).addClass("on"), e.addClass("portlet-fullscreen"), $("body").addClass(
                                            "page-portlet-fullscreen"), e.children(".portlet-body").css("height", o)
                                    }
                                }), $("body").on("click", ".portlet > .portlet-title > .tools > a.reload", function(t) {
                                    t.preventDefault();
                                    var e = $(this).closest(".portlet").children(".portlet-body"),
                                        o = $(this).attr("data-url"),
                                        a = $(this).attr("data-error-display");
                                    o ? (App.blockUI({
                                        target: e,
                                        animate: !0,
                                        overlayColor: "none"
                                    }), $.ajax({
                                        type: "GET",
                                        cache: !1,
                                        url: o,
                                        dataType: "html",
                                        success: function(t) {
                                            App.unblockUI(e), e.html(t), App.initAjax()
                                        },
                                        error: function(t, o, i) {
                                            App.unblockUI(e);
                                            var n =
                                                "Error on reloading the content. Please check your connection and try again.";
                                            "toastr" == a && toastr ? toastr.error(n) : "notific8" ==
                                                a && $.notific8 ? ($.notific8("zindex", 11500), $
                                                    .notific8(n, {
                                                        theme: "ruby",
                                                        life: 3e3
                                                    })) : alert(n)
                                        }
                                    })) : (App.blockUI({
                                        target: e,
                                        animate: !0,
                                        overlayColor: "none"
                                    }), window.setTimeout(function() {
                                        App.unblockUI(e)
                                    }, 1e3))
                                }), $('.portlet .portlet-title a.reload[data-load="true"]').click(), $("body").on("click",
                                    ".portlet > .portlet-title > .tools > .collapse, .portlet .portlet-title > .tools > .expand",
                                    function(t) {
                                        t.preventDefault();
                                        var e = $(this).closest(".portlet").children(".portlet-body");
                                        $(this).hasClass("collapse") ? ($(this).removeClass("collapse").addClass("expand"),
                                            e.slideUp(200)) : ($(this).removeClass("expand").addClass("collapse"), e
                                            .slideDown(200))
                                    })
                            },
                            b = function() {
                                if ($("body").on("click", ".md-checkbox > label, .md-radio > label", function() {
                                        var t = $(this),
                                            e = $(this).children("span:first-child");
                                        e.addClass("inc");
                                        var o = e.clone(!0);
                                        e.before(o), $("." + e.attr("class") + ":last", t).remove()
                                    }), $("body").hasClass("page-md")) {
                                    var t, e, o, a, i;
                                    $("body").on("click", "a.btn, button.btn, input.btn, label.btn", function(n) {
                                        t = $(this), 0 == t.find(".md-click-circle").length && t.prepend(
                                                "<span class='md-click-circle'></span>"), e = t.find(
                                                ".md-click-circle"), e.removeClass("md-click-animate"), e.height() || e
                                            .width() || (o = Math.max(t.outerWidth(), t.outerHeight()), e.css({
                                                height: o,
                                                width: o
                                            })), a = n.pageX - t.offset().left - e.width() / 2, i = n.pageY - t.offset()
                                            .top - e.height() / 2, e.css({
                                                top: i + "px",
                                                left: a + "px"
                                            }).addClass("md-click-animate"), setTimeout(function() {
                                                e.remove()
                                            }, 1e3)
                                    })
                                }
                                var n = function(t) {
                                    "" != t.val() ? t.addClass("edited") : t.removeClass("edited")
                                };
                                $("body").on("keydown", ".form-md-floating-label .form-control", function(t) {
                                    n($(this))
                                }), $("body").on("blur", ".form-md-floating-label .form-control", function(t) {
                                    n($(this))
                                }), $(".form-md-floating-label .form-control").each(function() {
                                    $(this).val().length > 0 && $(this).addClass("edited")
                                })
                            },
                            g = function() {
                                $().iCheck && $(".icheck").each(function() {
                                    var t = $(this).attr("data-checkbox") ? $(this).attr("data-checkbox") :
                                        "icheckbox_minimal-grey",
                                        e = $(this).attr("data-radio") ? $(this).attr("data-radio") :
                                        "iradio_minimal-grey";
                                    t.indexOf("_line") > -1 || e.indexOf("_line") > -1 ? $(this).iCheck({
                                        checkboxClass: t,
                                        radioClass: e,
                                        insert: '<div class="icheck_line-icon"></div>' + $(this).attr(
                                            "data-label")
                                    }) : $(this).iCheck({
                                        checkboxClass: t,
                                        radioClass: e
                                    })
                                })
                            },
                            m = function() {
                                $().bootstrapSwitch && $(".make-switch").bootstrapSwitch()
                            },
                            v = function() {
                                $().confirmation && $("[data-toggle=confirmation]").confirmation({
                                    btnOkClass: "btn btn-sm btn-success",
                                    btnCancelClass: "btn btn-sm btn-danger"
                                })
                            },
                            y = function() {
                                $("body").on("shown.bs.collapse", ".accordion.scrollable", function(t) {
                                    App.scrollTo($(t.target))
                                })
                            },
                            C = function() {
                                if (location.hash) {
                                    var t = encodeURI(location.hash.substr(1));
                                    $('a[href="#' + t + '"]').parents(".tab-pane:hidden").each(function() {
                                        var t = $(this).attr("id");
                                        $('a[href="#' + t + '"]').click()
                                    }), $('a[href="#' + t + '"]').click()
                                }
                                $().tabdrop && $(".tabbable-tabdrop .nav-pills, .tabbable-tabdrop .nav-tabs").tabdrop({
                                    text: '<i class="fa fa-ellipsis-v"></i>&nbsp;<i class="fa fa-angle-down"></i>'
                                })
                            },
                            x = function() {
                                $("body").on("hide.bs.modal", function() {
                                    $(".modal:visible").size() > 1 && $("html").hasClass("modal-open") === !1 ? $(
                                        "html").addClass("modal-open") : $(".modal:visible").size() <= 1 && $(
                                        "html").removeClass("modal-open")
                                }), $("body").on("show.bs.modal", ".modal", function() {
                                    $(this).hasClass("modal-scroll") && $("body").addClass("modal-open-noscroll")
                                }), $("body").on("hidden.bs.modal", ".modal", function() {
                                    $("body").removeClass("modal-open-noscroll")
                                }), $("body").on("hidden.bs.modal", ".modal:not(.modal-cached)", function() {
                                    $(this).removeData("bs.modal")
                                })
                            },
                            w = function() {
                                $(".tooltips").tooltip(), $(".portlet > .portlet-title .fullscreen").tooltip({
                                    trigger: "hover",
                                    container: "body",
                                    title: "Fullscreen"
                                }), $(".portlet > .portlet-title > .tools > .reload").tooltip({
                                    trigger: "hover",
                                    container: "body",
                                    title: "Reload"
                                }), $(".portlet > .portlet-title > .tools > .remove").tooltip({
                                    trigger: "hover",
                                    container: "body",
                                    title: "Remove"
                                }), $(".portlet > .portlet-title > .tools > .config").tooltip({
                                    trigger: "hover",
                                    container: "body",
                                    title: "Settings"
                                }), $(
                                    ".portlet > .portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand"
                                ).tooltip({
                                    trigger: "hover",
                                    container: "body",
                                    title: "Collapse/Expand"
                                })
                            },
                            k = function() {
                                $("body").on("click", ".dropdown-menu.hold-on-click", function(t) {
                                    t.stopPropagation()
                                })
                            },
                            I = function() {
                                $("body").on("click", '[data-close="alert"]', function(t) {
                                    $(this).parent(".alert").hide(), $(this).closest(".note").hide(), t.preventDefault()
                                }), $("body").on("click", '[data-close="note"]', function(t) {
                                    $(this).closest(".note").hide(), t.preventDefault()
                                }), $("body").on("click", '[data-remove="note"]', function(t) {
                                    $(this).closest(".note").remove(), t.preventDefault()
                                })
                            },
                            A = function() {
                                $('[data-hover="dropdown"]').not(".hover-initialized").each(function() {
                                    $(this).dropdownHover(), $(this).addClass("hover-initialized")
                                })
                            },
                            z = function() {
                                "function" == typeof autosize && autosize(document.querySelector("textarea.autosizeme"))
                            },
                            S = function() {
                                $(".popovers").popover(), $(document).on("click.bs.popover.data-api", function(e) {
                                    t && t.popover("hide")
                                })
                            },
                            P = function() {
                                App.initSlimScroll(".scroller")
                            },
                            T = function() {
                                jQuery.fancybox && $(".fancybox-button").size() > 0 && $(".fancybox-button").fancybox({
                                    groupAttr: "data-rel",
                                    prevEffect: "none",
                                    nextEffect: "none",
                                    closeBtn: !0,
                                    helpers: {
                                        title: {
                                            type: "inside"
                                        }
                                    }
                                })
                            },
                            D = function() {
                                $().counterUp && $("[data-counter='counterup']").counterUp({
                                    delay: 10,
                                    time: 1e3
                                })
                            },
                            U = function() {
                                (o || a) && $(
                                    "input[placeholder]:not(.placeholder-no-fix), textarea[placeholder]:not(.placeholder-no-fix)"
                                ).each(function() {
                                    var t = $(this);
                                    "" === t.val() && "" !== t.attr("placeholder") && t.addClass("placeholder").val(t
                                        .attr("placeholder")), t.focus(function() {
                                        t.val() == t.attr("placeholder") && t.val("")
                                    }), t.blur(function() {
                                        "" !== t.val() && t.val() != t.attr("placeholder") || t.val(t.attr(
                                            "placeholder"))
                                    })
                                })
                            },
                            E = function() {
                                $().select2 && ($.fn.select2.defaults.set("theme", "bootstrap"), $(".select2me").select2({
                                    placeholder: "Select",
                                    width: "auto",
                                    allowClear: !0
                                }))
                            },
                            G = function() {
                                $("[data-auto-height]").each(function() {
                                    var t = $(this),
                                        e = $("[data-height]", t),
                                        o = 0,
                                        a = t.attr("data-mode"),
                                        i = parseInt(t.attr("data-offset") ? t.attr("data-offset") : 0);
                                    e.each(function() {
                                        "height" == $(this).attr("data-height") ? $(this).css("height", "") : $(
                                            this).css("min-height", "");
                                        var t = "base-height" == a ? $(this).outerHeight() : $(this)
                                            .outerHeight(!0);
                                        t > o && (o = t)
                                    }), o += i, e.each(function() {
                                        "height" == $(this).attr("data-height") ? $(this).css("height", o) : $(
                                            this).css("min-height", o)
                                    }), t.attr("data-related") && $(t.attr("data-related")).css("height", t
                                        .height())
                                })
                            };
                        return {
                            init: function() {
                                p(), u(), b(), g(), m(), P(), T(), E(), f(), I(), k(), C(), w(), S(), y(), x(), v(), z(),
                                    D(), this.addResizeHandler(G), U()
                            },
                            initAjax: function() {
                                g(), m(), A(), P(), E(), T(), k(), w(), S(), y(), v()
                            },
                            initComponents: function() {
                                this.initAjax()
                            },
                            setLastPopedPopover: function(e) {
                                t = e
                            },
                            addResizeHandler: function(t) {
                                n.push(t)
                            },
                            runResizeHandlers: function() {
                                h()
                            },
                            scrollTo: function(t, e) {
                                var o = t && t.size() > 0 ? t.offset().top : 0;
                                t && ($("body").hasClass("page-header-fixed") ? o -= $(".page-header").height() : $("body")
                                    .hasClass("page-header-top-fixed") ? o -= $(".page-header-top").height() : $("body")
                                    .hasClass("page-header-menu-fixed") && (o -= $(".page-header-menu").height()), o +=
                                    e ? e : -1 * t.height()), $("html,body").animate({
                                    scrollTop: o
                                }, "slow")
                            },
                            initSlimScroll: function(t) {
                                $().slimScroll && $(t).each(function() {
                                    if (!$(this).attr("data-initialized")) {
                                        var t;
                                        t = $(this).attr("data-height") ? $(this).attr("data-height") : $(this).css(
                                            "height"), $(this).slimScroll({
                                            allowPageScroll: !0,
                                            size: "7px",
                                            color: $(this).attr("data-handle-color") ? $(this).attr(
                                                "data-handle-color") : "#bbb",
                                            wrapperClass: $(this).attr("data-wrapper-class") ? $(this).attr(
                                                "data-wrapper-class") : "slimScrollDiv",
                                            railColor: $(this).attr("data-rail-color") ? $(this).attr(
                                                "data-rail-color") : "#eaeaea",
                                            position: e ? "left" : "right",
                                            height: t,
                                            alwaysVisible: "1" == $(this).attr("data-always-visible"),
                                            railVisible: "1" == $(this).attr("data-rail-visible"),
                                            disableFadeOut: !0
                                        }), $(this).attr("data-initialized", "1")
                                    }
                                })
                            },
                            destroySlimScroll: function(t) {
                                $().slimScroll && $(t).each(function() {
                                    if ("1" === $(this).attr("data-initialized")) {
                                        $(this).removeAttr("data-initialized"), $(this).removeAttr("style");
                                        var t = {};
                                        $(this).attr("data-handle-color") && (t["data-handle-color"] = $(this).attr(
                                                "data-handle-color")), $(this).attr("data-wrapper-class") && (t[
                                                "data-wrapper-class"] = $(this).attr("data-wrapper-class")), $(this)
                                            .attr("data-rail-color") && (t["data-rail-color"] = $(this).attr(
                                                "data-rail-color")), $(this).attr("data-always-visible") && (t[
                                                "data-always-visible"] = $(this).attr("data-always-visible")), $(
                                                this).attr("data-rail-visible") && (t["data-rail-visible"] = $(this)
                                                .attr("data-rail-visible")), $(this).slimScroll({
                                                wrapperClass: $(this).attr("data-wrapper-class") ? $(this).attr(
                                                    "data-wrapper-class") : "slimScrollDiv",
                                                destroy: !0
                                            });
                                        var e = $(this);
                                        $.each(t, function(t, o) {
                                            e.attr(t, o)
                                        })
                                    }
                                })
                            },
                            scrollTop: function() {
                                App.scrollTo()
                            },
                            blockUI: function(t) {
                                t = $.extend(!0, {}, t);
                                var e = "";
                                if (e = t.animate ? '<div class="loading-message ' + (t.boxed ? "loading-message-boxed" :
                                        "") +
                                    '"><div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>' :
                                    t.iconOnly ? '<div class="loading-message ' + (t.boxed ? "loading-message-boxed" : "") +
                                    '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""></div>' :
                                    t.textOnly ? '<div class="loading-message ' + (t.boxed ? "loading-message-boxed" : "") +
                                    '"><span>&nbsp;&nbsp;' + (t.message ? t.message : "LOADING...") + "</span></div>" :
                                    '<div class="loading-message ' + (t.boxed ? "loading-message-boxed" : "") +
                                    '"><img src="' + this.getGlobalImgPath() +
                                    'loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (t.message ? t.message :
                                        "LOADING...") + "</span></div>", t.target) {
                                    var o = $(t.target);
                                    o.height() <= $(window).height() && (t.cenrerY = !0), o.block({
                                        message: e,
                                        baseZ: t.zIndex ? t.zIndex : 1e3,
                                        centerY: void 0 !== t.cenrerY ? t.cenrerY : !1,
                                        css: {
                                            top: "10%",
                                            border: "0",
                                            padding: "0",
                                            backgroundColor: "none"
                                        },
                                        overlayCSS: {
                                            backgroundColor: t.overlayColor ? t.overlayColor : "#555",
                                            opacity: t.boxed ? .05 : .1,
                                            cursor: "wait"
                                        }
                                    })
                                } else
                                    $.blockUI({
                                        message: e,
                                        baseZ: t.zIndex ? t.zIndex : 1e3,
                                        css: {
                                            border: "0",
                                            padding: "0",
                                            backgroundColor: "none"
                                        },
                                        overlayCSS: {
                                            backgroundColor: t.overlayColor ? t.overlayColor : "#555",
                                            opacity: t.boxed ? .05 : .1,
                                            cursor: "wait"
                                        }
                                    })
                            },
                            unblockUI: function(t) {
                                t ? $(t).unblock({
                                    onUnblock: function() {
                                        $(t).css("position", ""), $(t).css("zoom", "")
                                    }
                                }) : $.unblockUI()
                            },
                            startPageLoading: function(t) {
                                t && t.animate ? ($(".page-spinner-bar").remove(), $("body").append(
                                    '<div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>'
                                )) : ($(".page-loading").remove(), $("body").append(
                                    '<div class="page-loading"><img src="' + this.getGlobalImgPath() +
                                    'loading-spinner-grey.gif"/>&nbsp;&nbsp;<span>' + (t && t.message ? t.message :
                                        "Loading...") + "</span></div>"))
                            },
                            stopPageLoading: function() {
                                $(".page-loading, .page-spinner-bar").remove()
                            },
                            alert: function(t) {
                                t = $.extend(!0, {
                                    container: "",
                                    place: "append",
                                    type: "success",
                                    message: "",
                                    close: !0,
                                    reset: !0,
                                    focus: !0,
                                    closeInSeconds: 0,
                                    icon: ""
                                }, t);
                                var e = App.getUniqueID("App_alert"),
                                    o = '<div id="' + e + '" class="custom-alerts alert alert-' + t.type + ' fade in">' + (t
                                        .close ?
                                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' :
                                        "") + ("" !== t.icon ? '<i class="fa-lg fa fa-' + t.icon + '"></i>  ' : "") + t
                                    .message + "</div>";
                                return t.reset && $(".custom-alerts").remove(), t.container ? "append" == t.place ? $(t
                                        .container).append(o) : $(t.container).prepend(o) : 1 === $(
                                        ".page-fixed-main-content").size() ? $(".page-fixed-main-content").prepend(o) : ($(
                                        "body").hasClass("page-container-bg-solid") || $("body").hasClass(
                                        "page-content-white")) && 0 === $(".page-head").size() ? $(".page-title").after(o) :
                                    $(".page-bar").size() > 0 ? $(".page-bar").after(o) : $(
                                        ".page-breadcrumb, .breadcrumbs").after(o), t.focus && App.scrollTo($("#" + e)), t
                                    .closeInSeconds > 0 && setTimeout(function() {
                                        $("#" + e).remove()
                                    }, 1e3 * t.closeInSeconds), e
                            },
                            initFancybox: function() {
                                T()
                            },
                            getActualVal: function(t) {
                                return t = $(t), t.val() === t.attr("placeholder") ? "" : t.val()
                            },
                            getURLParameter: function(t) {
                                var e, o, a = window.location.search.substring(1),
                                    i = a.split("&");
                                for (e = 0; e < i.length; e++)
                                    if (o = i[e].split("="), o[0] == t)
                                        return unescape(o[1]);
                                return null
                            },
                            isTouchDevice: function() {
                                try {
                                    return document.createEvent("TouchEvent"), !0
                                } catch (t) {
                                    return !1
                                }
                            },
                            getViewPort: function() {
                                var t = window,
                                    e = "inner";
                                return "innerWidth" in window || (e = "client", t = document.documentElement || document
                                    .body), {
                                    width: t[e + "Width"],
                                    height: t[e + "Height"]
                                }
                            },
                            getUniqueID: function(t) {
                                return "prefix_" + Math.floor(Math.random() * (new Date).getTime())
                            },
                            isIE8: function() {
                                return o
                            },
                            isIE9: function() {
                                return a
                            },
                            isRTL: function() {
                                return e
                            },
                            isAngularJsApp: function() {
                                return "undefined" != typeof angular
                            },
                            getAssetsPath: function() {
                                return l
                            },
                            setAssetsPath: function(t) {
                                l = t
                            },
                            setGlobalImgPath: function(t) {
                                s = t
                            },
                            getGlobalImgPath: function() {
                                return l + s
                            },
                            setGlobalPluginsPath: function(t) {
                                r = t
                            },
                            getGlobalPluginsPath: function() {
                                return l + r
                            },
                            getGlobalCssPath: function() {
                                return l + c
                            },
                            getBrandColor: function(t) {
                                return d[t] ? d[t] : ""
                            },
                            getResponsiveBreakpoint: function(t) {
                                var e = {
                                    xs: 480,
                                    sm: 768,
                                    md: 992,
                                    lg: 1200
                                };
                                return e[t] ? e[t] : 0
                            }
                        }
                    }();
                    jQuery(document).ready(function() {
                        App.init()
                    });
                    var Layout = function() {
                        var e = "layouts/layout4/img/",
                            a = "layouts/layout4/css/",
                            s = App.getResponsiveBreakpoint("md"),
                            i = function() {
                                var e, a = $(".page-content"),
                                    i = $(".page-sidebar"),
                                    t = $("body");
                                if (t.hasClass("page-footer-fixed") === !0 && t.hasClass("page-sidebar-fixed") === !1) {
                                    var o = App.getViewPort().height - $(".page-footer").outerHeight(!0) - $(".page-header")
                                        .outerHeight(!0);
                                    a.height() < o && a.attr("style", "min-height:" + o + "px")
                                } else {
                                    if (t.hasClass("page-sidebar-fixed"))
                                        e = n() - 10, t.hasClass("page-footer-fixed") === !1 && (e -= $(".page-footer")
                                            .outerHeight(!0));
                                    else {
                                        var r = $(".page-header").outerHeight(!0),
                                            p = $(".page-footer").outerHeight(!0);
                                        e = App.getViewPort().width < s ? App.getViewPort().height - r - p : i.height() - 10,
                                            e + r + p <= App.getViewPort().height && (e = App.getViewPort().height - r - p - 45)
                                    }
                                    a.attr("style", "min-height:" + e + "px")
                                }
                            },
                            t = function(e, a) {
                                var i = location.hash.toLowerCase(),
                                    t = $(".page-sidebar-menu");
                                if ("click" === e || "set" === e ? a = $(a) : "match" === e && t.find("li > a").each(
                                        function() {
                                            var e = $(this).attr("href").toLowerCase();
                                            return e.length > 1 && i.substr(1, e.length - 1) == e.substr(1) ? void(a = $(
                                                this)) : void 0
                                        }), a && 0 != a.size() && "javascript:;" !== a.attr("href").toLowerCase() && "#" !== a.attr(
                                        "href").toLowerCase()) {
                                    parseInt(t.data("slide-speed")), t.data("keep-expanded");
                                    t.hasClass("page-sidebar-menu-hover-submenu") === !1 ? t.find("li.nav-item.open").each(
                                            function() {
                                                var e = !1;
                                                $(this).find("li").each(function() {
                                                    return $(this).find(" > a").attr("href") === a.attr("href") ? void(
                                                        e = !0) : void 0
                                                }), e !== !0 && ($(this).removeClass("open"), $(this).find(
                                                    "> a > .arrow.open").removeClass("open"), $(this).find(
                                                    "> .sub-menu").slideUp())
                                            }) : t.find("li.open").removeClass("open"), t.find("li.active").removeClass(
                                            "active"), t.find("li > a > .selected").remove(), a.parents("li").each(function() {
                                            $(this).addClass("active"), $(this).find("> a > span.arrow").addClass("open"),
                                                1 === $(this).parent("ul.page-sidebar-menu").size() && $(this).find("> a")
                                                .append('<span class="selected"></span>'), 1 === $(this).children(
                                                    "ul.sub-menu").size() && $(this).addClass("open")
                                        }), "click" === e && App.getViewPort().width < s && $(".page-sidebar").hasClass("in") &&
                                        $(".page-header .responsive-toggler").click()
                                }
                            },
                            o = function() {
                                $(".page-sidebar").on("click", "li > a", function(e) {
                                    if (!(App.getViewPort().width >= s && 1 === $(this).parents(
                                            ".page-sidebar-menu-hover-submenu").size())) {
                                        if ($(this).next().hasClass("sub-menu") === !1)
                                            return void(App.getViewPort().width < s && $(".page-sidebar").hasClass(
                                                "in") && $(".page-header .responsive-toggler").click());
                                        var a = $(this).parent().parent(),
                                            t = $(this),
                                            o = $(".page-sidebar-menu"),
                                            n = $(this).next(),
                                            r = o.data("auto-scroll"),
                                            p = parseInt(o.data("slide-speed")),
                                            d = o.data("keep-expanded");
                                        d !== !0 && (a.children("li.open").children("a").children(".arrow").removeClass(
                                                "open"), a.children("li.open").children(
                                                ".sub-menu:not(.always-open)").slideUp(p), a.children("li.open")
                                            .removeClass("open"));
                                        var l = -200;
                                        n.is(":visible") ? ($(".arrow", $(this)).removeClass("open"), $(this).parent()
                                            .removeClass("open"), n.slideUp(p, function() {
                                                r === !0 && $("body").hasClass("page-sidebar-closed") === !1 &&
                                                    ($("body").hasClass("page-sidebar-fixed") ? o.slimScroll({
                                                        scrollTo: t.position().top
                                                    }) : App.scrollTo(t, l)), i()
                                            })) : ($(".arrow", $(this)).addClass("open"), $(this).parent().addClass(
                                            "open"), n.slideDown(p, function() {
                                            r === !0 && $("body").hasClass("page-sidebar-closed") === !1 &&
                                                ($("body").hasClass("page-sidebar-fixed") ? o.slimScroll({
                                                    scrollTo: t.position().top
                                                }) : App.scrollTo(t, l)), i()
                                        })), e.preventDefault()
                                    }
                                }), App.isAngularJsApp() && $(".page-sidebar-menu li > a").on("click", function(e) {
                                    App.getViewPort().width < s && $(this).next().hasClass("sub-menu") === !1 && $(
                                        ".page-header .responsive-toggler").click()
                                }), $(".page-sidebar").on("click", " li > a.ajaxify", function(e) {
                                    e.preventDefault(), App.scrollTop();
                                    var a = $(this).attr("href"),
                                        i = $(".page-sidebar ul"),
                                        t = ($(".page-content"), $(".page-content .page-content-body"));
                                    i.children("li.active").removeClass("active"), i.children("arrow.open").removeClass(
                                            "open"), $(this).parents("li").each(function() {
                                            $(this).addClass("active"), $(this).children("a > span.arrow").addClass(
                                                "open")
                                        }), $(this).parents("li").addClass("active"), App.getViewPort().width < s && $(
                                            ".page-sidebar").hasClass("in") && $(".page-header .responsive-toggler")
                                        .click(), App.startPageLoading();
                                    var o = $(this);
                                    $.ajax({
                                        type: "GET",
                                        cache: !1,
                                        url: a,
                                        dataType: "html",
                                        success: function(e) {
                                            0 === o.parents("li.open").size() && $(
                                                    ".page-sidebar-menu > li.open > a").click(), App
                                                .stopPageLoading(), t.html(e), Layout.fixContentHeight(),
                                                App.initAjax()
                                        },
                                        error: function(e, a, s) {
                                            App.stopPageLoading(), t.html(
                                                "<h4>Could not load the requested content.</h4>")
                                        }
                                    })
                                }), $(".page-content").on("click", ".ajaxify", function(e) {
                                    e.preventDefault(), App.scrollTop();
                                    var a = $(this).attr("href"),
                                        i = ($(".page-content"), $(".page-content .page-content-body"));
                                    App.startPageLoading(), App.getViewPort().width < s && $(".page-sidebar").hasClass(
                                        "in") && $(".page-header .responsive-toggler").click(), $.ajax({
                                        type: "GET",
                                        cache: !1,
                                        url: a,
                                        dataType: "html",
                                        success: function(e) {
                                            App.stopPageLoading(), i.html(e), Layout.fixContentHeight(), App
                                                .initAjax()
                                        },
                                        error: function(e, a, s) {
                                            i.html("<h4>Could not load the requested content.</h4>"), App
                                                .stopPageLoading()
                                        }
                                    })
                                }), $(document).on("click", ".page-header-fixed-mobile .responsive-toggler", function() {
                                    App.scrollTop()
                                })
                            },
                            n = function() {
                                var e = App.getViewPort().height - $(".page-header").outerHeight(!0) - 40;
                                return $("body").hasClass("page-footer-fixed") && (e -= $(".page-footer").outerHeight()), e
                            },
                            r = function() {
                                var e = $(".page-sidebar-menu");
                                return App.destroySlimScroll(e), 0 === $(".page-sidebar-fixed").size() ? void i() : void(App
                                    .getViewPort().width >= s && (e.attr("data-height", n()), App.initSlimScroll(e), i()))
                            },
                            p = function() {
                                var e = $("body");
                                e.hasClass("page-sidebar-fixed") && $(".page-sidebar").on("mouseenter", function() {
                                    e.hasClass("page-sidebar-closed") && $(this).find(".page-sidebar-menu").removeClass(
                                        "page-sidebar-menu-closed")
                                }).on("mouseleave", function() {
                                    e.hasClass("page-sidebar-closed") && $(this).find(".page-sidebar-menu").addClass(
                                        "page-sidebar-menu-closed")
                                })
                            },
                            d = function() {
                                var e = $("body");
                                $.cookie && "1" === $.cookie("sidebar_closed") && App.getViewPort().width >= s && ($("body")
                                    .addClass("page-sidebar-closed"), $(".page-sidebar-menu").addClass(
                                        "page-sidebar-menu-closed")), $("body").on("click", ".sidebar-toggler", function(
                                    a) {
                                    var s = $(".page-sidebar"),
                                        i = $(".page-sidebar-menu");
                                    $(".sidebar-search", s).removeClass("open"), e.hasClass("page-sidebar-closed") ? (e
                                        .removeClass("page-sidebar-closed"), i.removeClass(
                                            "page-sidebar-menu-closed"), $.cookie && $.cookie("sidebar_closed", "0")
                                    ) : (e.addClass("page-sidebar-closed"), i.addClass(
                                            "page-sidebar-menu-closed"), e.hasClass("page-sidebar-fixed") && i
                                        .trigger("mouseleave"), $.cookie && $.cookie("sidebar_closed", "1")), $(
                                        window).trigger("resize")
                                }), p(), $(".page-sidebar").on("click", ".sidebar-search .remove", function(e) {
                                    e.preventDefault(), $(".sidebar-search").removeClass("open")
                                }), $(".page-sidebar .sidebar-search").on("keypress", "input.form-control", function(e) {
                                    return 13 == e.which ? ($(".sidebar-search").submit(), !1) : void 0
                                }), $(".sidebar-search .submit").on("click", function(e) {
                                    e.preventDefault(), $("body").hasClass("page-sidebar-closed") && $(
                                        ".sidebar-search").hasClass("open") === !1 ? (1 === $(".page-sidebar-fixed")
                                        .size() && $(".page-sidebar .sidebar-toggler").click(), $(".sidebar-search")
                                        .addClass("open")) : $(".sidebar-search").submit()
                                }), 0 !== $(".sidebar-search").size() && ($(".sidebar-search .input-group").on("click",
                                    function(e) {
                                        e.stopPropagation()
                                    }), $("body").on("click", function() {
                                    $(".sidebar-search").hasClass("open") && $(".sidebar-search").removeClass(
                                        "open")
                                }))
                            },
                            l = function() {
                                $(".page-header").on("click", ".search-form", function(e) {
                                    $(this).addClass("open"), $(this).find(".form-control").focus(), $(
                                        ".page-header .search-form .form-control").on("blur", function(e) {
                                        $(this).closest(".search-form").removeClass("open"), $(this).unbind(
                                            "blur")
                                    })
                                }), $(".page-header").on("keypress", ".hor-menu .search-form .form-control", function(e) {
                                    return 13 == e.which ? ($(this).closest(".search-form").submit(), !1) : void 0
                                }), $(".page-header").on("mousedown", ".search-form.open .submit", function(e) {
                                    e.preventDefault(), e.stopPropagation(), $(this).closest(".search-form").submit()
                                })
                            },
                            c = function() {
                                var e = 300,
                                    a = 500;
                                navigator.userAgent.match(/iPhone|iPad|iPod/i) ? $(window).bind(
                                    "touchend touchcancel touchleave",
                                    function(s) {
                                        $(this).scrollTop() > e ? $(".scroll-to-top").fadeIn(a) : $(".scroll-to-top")
                                            .fadeOut(a)
                                    }) : $(window).scroll(function() {
                                    $(this).scrollTop() > e ? $(".scroll-to-top").fadeIn(a) : $(".scroll-to-top")
                                        .fadeOut(a)
                                }), $(".scroll-to-top").click(function(e) {
                                    return e.preventDefault(), $("html, body").animate({
                                        scrollTop: 0
                                    }, a), !1
                                })
                            };
                        return {
                            initHeader: function() {
                                l()
                            },
                            setSidebarMenuActiveLink: function(e, a) {
                                t(e, a)
                            },
                            initSidebar: function() {
                                r(), o(), d(), App.isAngularJsApp() && t("match"), App.addResizeHandler(r)
                            },
                            initContent: function() {},
                            initFooter: function() {
                                c()
                            },
                            init: function() {
                                this.initHeader(), this.initSidebar(), this.initContent(), this.initFooter()
                            },
                            fixContentHeight: function() {},
                            initFixedSidebarHoverEffect: function() {
                                p()
                            },
                            initFixedSidebar: function() {
                                r()
                            },
                            getLayoutImgPath: function() {
                                return App.getAssetsPath() + e
                            },
                            getLayoutCssPath: function() {
                                return App.getAssetsPath() + a
                            }
                        }
                    }();
                    App.isAngularJsApp() === !1 && jQuery(document).ready(function() {
                        Layout.init()
                    });
                </script>
                <script type="application/javascript" src="cmn_scrpts/header_scripts.js?v=<?php echo $jsCssFileVrsn; ?>" rel="preload" as="script">
                </script>
                <script type="application/javascript">
                    $body = $("body");
                    $body.removeClass("mdlloading");
                </script>
                <script type="application/javascript" src="cmn_scrpts/global_scripts.js?v=<?php echo $jsCssFileVrsn; ?>" rel="preload" as="script">
                </script>
                <script type="application/javascript" src="app/rpt/rpt.js?v=<?php echo $jsCssFileVrsn; ?>" rel="preload" as="script"></script>
                <script type="application/javascript" src="app/cmncde/cmncde.js?v=<?php echo $jsCssFileVrsn; ?>" rel="preload" as="script"></script>
                <script src="cmn_scrpts/bootstrap337/datatables/DataTables-1.10.11/js/jquery.dataTables.min.js" rel="preload" as="script"></script>
                <script type="text/javascript" src="cmn_scrpts/bootstrap337/bootstrap-dtimepckr/js/bootstrap-datetimepicker.min.js" rel="preload" as="script"></script>
                <script type="text/javascript" src="cmn_scrpts/summernote081/summernote.min.js" rel="preload" as="script"></script>
                <!-- this cssfile can be found in the jScrollPane package -->
                <!-- the jScrollPane script -->
                <!-- the mousewheel plugin - optional to provide mousewheel support -->
                <!-- Bootstrap Core JavaScript -->
                <?php /*
                <script src="cmn_scrpts/bootstrap337/datatables/DataTables-1.10.11/js/dataTables.bootstrap.min.js"></script>
                <link rel="stylesheet" type="text/css" href="cmn_scrpts/jquery.jscrollpane.css" />
                <script type="text/javascript" src="cmn_scrpts/jquery.mousewheel.js"></script>
                <script type="text/javascript" src="cmn_scrpts/mwheelIntent.js"></script>
                <script type="text/javascript" src="cmn_scrpts/jquery.jscrollpane.min.js"></script>
                <script type="text/javascript" src="cmn_scrpts/jquery.csv.js"></script>
                 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAegIY618HReyUyNTbDicO3gF-Xh4Y2lvY"></script> 
                <script type="application/javascript" src="app/cmncde/mdls_wkf_suprt_scrpts.js?v=<?php echo $jsCssFileVrsn; ?>">
                </script>
                <script type="text/javascript" src="cmn_scrpts/pdfobject.min.js"></script>
                <script src="cmn_scrpts/amcharts_4.1.4/amcharts4/charts.js"></script>
                <script type="text/javascript" src="cmn_scrpts/MDB Free/js/popper.min.js"></script>
                
                https://www.amcharts.com/lib/4/core.js
                  https://www.amcharts.com/lib/4/charts.js
                 *      https://www.amcharts.com/lib/4/themes/animated.js                 */ ?>
                <script type="text/javascript" src="cmn_scrpts/bootstrap337/bootbox.min.js" rel="preload" as="script"></script>
                <script type="text/javascript" src="cmn_scrpts/summernote081/summernote-ext-print.js" rel="preload" as="script"></script>
                <link rel="stylesheet" type="text/css" href="cmn_scrpts/bootstrap-multiselect/css/bootstrap-multiselect.css" />
                <script type="text/javascript" src="cmn_scrpts/bootstrap-multiselect/js/bootstrap-multiselect.js" rel="preload" as="script"></script>
                <script type="text/javascript" src="cmn_scrpts/MDB Free/js/mdb.min.js" rel="preload" as="script"></script>
                <script src="cmn_scrpts/amcharts_4.1.4/amcharts4/core.js" rel="preload" as="script"></script>
                <script src="cmn_scrpts/amcharts_4.1.4/amcharts4/themes/animated.js" rel="preload" as="script"></script>
                <style type="text/css">
                    .jspTrack {
                        background: #fff;
                        /* changed from #b46868 #dde */
                        position: relative;
                        padding: 5px;
                    }

                    .jspDrag {
                        background: #ddd;
                        /* changed from #bbd */
                        position: relative;
                        top: 0;
                        left: 0;
                        cursor: pointer;
                        -moz-border-radius: 10px;
                        -webkit-border-radius: 10px;
                        border-radius: 10px;
                    }
                </style>
                <script type="text/javascript">
                    $body = $("body");
                    $body.removeClass("mdlloading");
                    $(document).ready(function() {
                        $('[data-toggle="tooltip"]').tooltip();
                    });
                    $(function() {
                        $('[data-toggle="tooltip"]').tooltip();
                        $('[data-toggle="tabajax"]').click(function(e) {
                            e.preventDefault();
                            var $this = $(this);
                            var targ = $this.attr('href');
                            var dttrgt = $this.attr('data-rhodata');
                            var linkArgs = dttrgt;
                            return openATab(targ, linkArgs);
                        });
                    });
                    startDashBoardRpts();
                </script>
        </body>

        </html>
<?php
    }
}
session_write_close();
/** Check and Execute DB Corrections */
$strSQL = "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d, ''||lnkd_prsn_id e, cust_or_sup f from scm.scm_cstmr_suplr where (is_enabled='1') order by 2";
$lovID1 = getLovID("All Customers and Suppliers");
if ($lovID1 > 0) {
    execUpdtInsSQL("UPDATE gst.gen_stp_lov_names
	SET value_list_name='All Business/Trade Partners', value_list_desc='All Business/Trade Partners', " .
        "sqlquery_if_dyn='" . loc_db_escape_string($strSQL) . "' 
    WHERE value_list_id=" . $lovID1);
} else {
    $lovID = getLovID("All Business/Trade Partners");
    $oldSQL = getGnrlRecNm("gst.gen_stp_lov_names", "value_list_id", "sqlquery_if_dyn", $lovID);
    if ($lovID > 0 && strpos($oldSQL, "cust_or_sup f") === FALSE) {
        updateLovNm($lovID, true, $strSQL, "SYS", true);
    }
}
$rptPrmCnt = (float) getGnrlRecNm2("rpt.rpt_report_parameters", "lov_name", "count(1)", "All Customers and Suppliers");
if ($rptPrmCnt > 0) {
    execUpdtInsSQL("UPDATE rpt.rpt_report_parameters SET lov_name= 'All Business/Trade Partners' WHERE lov_name='All Customers and Suppliers'");
}
?>