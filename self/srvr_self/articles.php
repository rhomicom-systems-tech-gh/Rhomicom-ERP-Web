<?php
$lighterColor1 = rhoHex2Rgba($bckcolorOnly1, 0.1);
$lighterColor2 = rhoHex2Rgba($bckcolorOnly1, 0.075);
//"rgba(135, 206, 235, 0.1)";
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
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 5;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Published";
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

if (isset($_POST['isMaster'])) {
    $isMaster = cleanInputData($_POST['isMaster']);
}
if ($qryStr == "UPDATE") {
    if ($actyp == 3) {
        //Create Comment
        $articleID = cleanInputData($_POST['articleID']);
        $prntCmntID = cleanInputData($_POST['prntCmntID']);
        $articleComment = cleanInputData($_POST['articleComment']);
        if ($articleID > 0 && $articleComment != "") {
            createNoticeCmmnt($articleID, $articleComment, $prntCmntID);
        }
    } else if ($actyp == 4) {
        //Queue Mail Messages              
        header('Content-Type: application/json');
        $msgType = "Email";
        $sndMsgOneByOne = "YES";
        $mailTo = $admin_email;
        $mailCc = trim(cleanInputData($_POST['mailCc']), ";, ");
        $mailBcc = "";
        $mailAttchmnts = trim(cleanInputData($_POST['mailAttchmnts']), ";, ");
        $mailSubject = trim(cleanInputData($_POST['mailSubject']), ";, ");
        $bulkMessageBody = cleanInputData($_POST['bulkMessageBody']);

        $dtaVld = TRUE;
        $errMsg = "";
        if ($mailSubject == "") {
            $dtaVld = FALSE;
            $errMsg = "Message Subject cannot be empty!";
        }
        if ($bulkMessageBody == "") {
            $dtaVld = FALSE;
            $errMsg = "Message Body cannot be empty!";
        }
        $mailCc = str_replace(" ", ";", str_replace(",", ";", str_replace("\r\n", ";", $mailCc)));
        $cpList = explode(";", $mailCc);
        if (count($cpList) > 9) {
            $mailCc = "";
            for ($p = 0; $p < 10; $p++) {
                $mailCc = $mailCc . $cpList[$p] . ";";
            }
        }
        $mailAttchmnts = str_replace(",", ";", str_replace("\r\n", "", $mailAttchmnts));

        if ($dtaVld === TRUE) {
            $msgBatchID = getMsgBatchID();
            $total = 1;
            for ($i = 0; $i < $total; $i++) {
                createMessageQueue($msgBatchID, $mailTo, trim($mailCc, ";, "), trim($mailBcc, ";, "), $bulkMessageBody, $mailSubject, trim($mailAttchmnts, ";, "), $msgType);
            }
            $reportTitle = "Send Outstanding Bulk Messages";
            $reportName = "Send Outstanding Bulk Messages";
            $rptID = getRptID($reportName);
            $prmID = getParamIDUseSQLRep("{:msg_batch_id}", $rptID);
            $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
            generateReportRun($rptID, $paramRepsNVals, -1);
            $arr_content['percent'] = 100;
            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!...1 out of 1 Messages(es) Submitted Successfully!";
            $arr_content['msgcount'] = 1;
            echo json_encode($arr_content);
        } else {
            $percent = 100;
            $arr_content['percent'] = $percent;
            $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
            $arr_content['msgcount'] = "";
            echo json_encode($arr_content);
        }
    }
} else {
    if ($vwtyp == 0) {
        $introToPrtlArtBodyID = getNoticeHeaderID("INTRODUCTION TO THE PORTAL");
        if ($introToPrtlArtBodyID <= 0) {
            createNotice("Welcome Message", "INTRODUCTION TO THE PORTAL", "javascript: showArticleDetails({:articleID},'Welcome Message');", $introToPrtlArtBody, "0", "4000-12-31 00:00:00", $admin_name,
                    $admin_email, 1, str_replace("<h3><a href=\"javascript: showNoticeDetails({:articleID},'Notices/Announcements');\" style=\"font-weight:bold;text-decoration:underline;\">INTRODUCTION TO THE PORTAL</a></h3>", "", $introToPrtlArtBody), "Everyone", "-1", "System Notifications");
        }
        $ltstNewArtBodyID = getNoticeHeaderID("Latest News");
        if ($ltstNewArtBodyID <= 0) {
            createNotice("Latest News", "Latest News", "javascript: showArticleDetails({:articleID},'Latest News');", $ltstNewArtBody, "0", "4000-12-31 00:00:00", $admin_name,
                    $admin_email, 1, $ltstNewArtBody, "Everyone", "-1", "System Notifications");
        }
        $usefulLnksArtBodyID = getNoticeHeaderID("USEFUL QUICK LINKS & RESOURCES");
        if ($usefulLnksArtBodyID <= 0) {
            createNotice("Useful Links", "USEFUL QUICK LINKS & RESOURCES", "javascript: showArticleDetails({:articleID},'Useful Links');", $usefulLnksArtBody, "0", "4000-12-31 00:00:00", $admin_name,
                    $admin_email, 1, $usefulLnksArtBody, "Everyone", "-1", "System Notifications");
        }
        $sliderID = getNoticeHeaderID("Slider One (1)");
        $sliderBody = "<img src=\"cmn_images/bkg6.jpeg\">";
        if ($sliderID <= 0) {
            createNotice("Slider", "Slider One (1)", "javascript: showArticleDetails({:articleID},'Slider');", $sliderBody, "0", "4000-12-31 00:00:00", $admin_name,
                    $admin_email, 1, $sliderBody, "Everyone", "-1", "System Notifications");
        }
        $sliderID2 = getNoticeHeaderID("Slider Two (2)");
        $sliderBody2 = "<img class=\"first-slide\" src=\"cmn_images/bkg2.jpeg\" alt=\"First slide\">
                            <div class=\"container\">
                                <div class=\"carousel-caption\">
                                    <span style=\"font-weight:bold;font-family:Arial Black;font-size:28px;\">PLEASE CALL 0247755514 OR 02447706647 FOR SUPPORT SERVICES</span>
                                    <p><a class=\"btn btn-sm btn-primary\" href=\"javascript: showArticleDetails({:articleID},'');\" role=\"button\">View Full Article</a></p>
                                </div>
                            </div>";
        if ($sliderID2 <= 0) {
            createNotice("Slider", "Slider Two (2)", "javascript: showArticleDetails({:articleID},'Slider');", $sliderBody2, "0", "4000-12-31 00:00:00", $admin_name,
                    $admin_email, 1, $sliderBody2, "Everyone", "-1", "System Notifications");
        }

        $total = get_NoticeTtls($srchFor, $srchIn);
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }

        $curIdx = $pageNo - 1;
        $result = get_Notices($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
        $colClassType1 = "col-lg-2";
        $colClassType2 = "col-lg-4";
        $colClassType3 = "col-lg-1";
        $colClassType4 = "col-lg-3";
        ?>
        <input id="allnoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">                    
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Notices/Announcements</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item active">Announcements</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 16px 0.5rem !important;background-color: <?php echo $lighterColor1; ?>;">
            <div class="container-fluid">
                <?php
                if ($curIdx == 0) {
                    $ltstNwsID = getLtstNoticeID("Latest News");
                    $artBody = rhoReplaceBtn(getNoticeIntroMsg($ltstNwsID), "<h3>", "</h3>", "", true);
                    $usfulLinksID = getLtstNoticeID("Useful Links");
                    $artBody1 = rhoReplaceBtn(getNoticeIntroMsg($usfulLinksID), "<h3>", "</h3>", "", true);
                    ?>
                    <!-- Main row -->
                    <div class="row" style="">
                        <!-- Illustrations card  shadow elevation-3 -->
                        <div class="col-lg-6">
                            <div class="card ">
                                <div class="card-header py-3" style="">
                                    <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-newspaper"></i> <a class="rho-link" href="javascript:showNoticeDetails(<?php echo $ltstNwsID; ?>,'Latest News');">LATEST NEWS AND HIGHLIGHTS</a></h6>
                                </div>
                                <div class="card-body" style="">
                                    <div class="rho-card-body" style="">                          
                                        <?php echo str_replace("url(cmn_images/", "url(../cmn_images/", $artBody); ?>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card ">
                                <div class="card-header py-3" style="">
                                    <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-newspaper"></i> <a class="rho-link" href="javascript:showNoticeDetails(<?php echo $usfulLinksID; ?>,'Useful Links');">USEFUL QUICK LINKS & RESOURCES</a></h6>
                                </div>
                                <div class="card-body">
                                    <div class="rho-card-body" style=""> 
                                        <?php echo str_replace("url(cmn_images/", "url(../cmn_images/", $artBody1); ?>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php
                $cntr = 0;
                $grpcntr = 0;
                $ttlRecs = loc_db_num_rows($result);
                while ($row = loc_db_fetch_array($result)) {
                    $cntr += 1;
                    $artBody3 = rhoReplaceBtn($row[13], "{:RMS}", "{:RME}", "...", true); // $artBody2;//preg_replace($search, $replace, $artBody2);
                    if ($cntr == 1) {
                        ?>
                        <div class="row" style="">
                            <!-- Illustrations -->
                            <div class="col-lg-12">
                                <div class="card ">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary"><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" class="text-primary"><i class="fa fa-certificate" aria-hidden="true"></i>&nbsp;<span style="text-decoration: none;"><?php echo $row[2]; ?></span></a></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="rho-card-body2" style="">  
                                            <p style="width:100% !important;min-width:100% !important;"><?php echo str_replace("\"cmn_images/", "\"../cmn_images/", $artBody3); ?></p>
                                        </div>
                                        <p><a class="btn rho-primary"  class="text-primary" style="" href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" role="button">Read more... &raquo;</a></p>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <?php
                    } else {
                        if ($grpcntr == 0) {
                            ?> 
                            <div class="row" style=""> 
                                <?php
                            }
                            $grpcntr = $grpcntr + 1;
                            ?>
                            <div class="col-lg-6">
                                <div class="card ">
                                    <div class="card-header py-3" style="">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');"  class="text-primary">
                                                <i class="fas fa-dot-circle"></i></i>&nbsp;<span style="text-decoration: none;"><?php echo $row[2]; ?></span></a>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="rho-card-body2" style="">
                                            <p style="width:100% !important;min-width:100% !important;"><?php echo str_replace("\"cmn_images/", "\"../cmn_images/", $artBody3); ?></p>
                                        </div>
                                        <p><a class="btn rho-primary" href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" role="button">Read more... &raquo;</a></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (($grpcntr % 2) == 0 || $cntr == $ttlRecs) {
                                ?>
                            </div>
                            <?php
                            $grpcntr = 0;
                        }
                    }
                }
                ?>
                <!-- /.row (main row) -->
                <div class="row">
                    <div class="container-fluid">
                        <div style="font-size:16px;padding:6px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;border-top:1px solid #ddd;">
                            <a class="btn btn-default" href="javascript:getAllNotices('previous', '#allmodules', 'grp=40&typ=1&pg=0&vtyp=0');" role="button" style="float:none;"> « Previous </a>
                            <a class="btn btn-default" href="javascript:getAllNotices('next', '#allmodules', 'grp=40&typ=1&pg=0&vtyp=0');" role="button" style="float:none;"> Next » </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    } else if ($vwtyp == 4) {
        /* Display one Full Notice ReadOnly */
        $lmtSze = 1;
        $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
        $sbmtdNoticeCtgry = isset($_POST['sbmtdNoticeCtgry']) ? cleanInputData($_POST['sbmtdNoticeCtgry']) : "";
        $total = get_NoticeTtls($srchFor, $srchIn);
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }
        $curIdx = $pageNo - 1;
        $result = NULL;
        if ($sbmtdNoticeID > 0) {
            $result = get_OneNotice($sbmtdNoticeID);
        } else {
            $result = get_Notices($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
            //get_OneNotices($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
        }
        $cntr = 0;
        while ($row = loc_db_fetch_array($result)) {
            $cntr += 1;
            $sbmtdNoticeID = $row[0];
            ?>    
            <input id="allnoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">                    
            <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Notices/Announcements</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=0');">Announcements</a></li>
                                <li class="breadcrumb-item active"><?php echo $row[2]; ?></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content" style="padding: 16px 0.5rem !important;">
                <div class="container-fluid">
                    <div class="row" style="">
                        <!-- Illustrations -->
                        <div class="col-lg-12">
                            <div class="card ">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary"><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" class="text-primary" style="text-decoration:none !important;"><i class="fa fa-certificate" aria-hidden="true"></i>&nbsp;<span style="text-decoration: none;"><?php echo $row[2]; ?></span></a></h6>
                                </div>
                                <div class="card-body" style="background-color: <?php echo $lighterColor1; ?>;">
                                    <div class="rho-card-body5" style="padding: 5px 30px 5px 30px;">  
                                        <p style="width:100% !important;min-width:100% !important;">
                                            <?php echo rhoReplaceBtn(str_replace("{:articleID}", $row[0], str_replace("\"cmn_images/", "\"../cmn_images/", str_replace("{:RME}", "", str_replace("{:RMS}", "", $row[4])))), "<h3>", "</h3>", "", true); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $pageNo1 = 1;
                    $ttlCommnts = get_OneCommentsTtl($sbmtdNoticeID);
                    ?>
                    <div class="row">
                        <div class="container-fluid">
                            <div style="font-size:16px;padding:5px 1px 5px 1px;margin:3px 0px 3px 0px;text-align: center;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <a class="btn btn-default" href="javascript:getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=0&isMaster=0');" role="button" style="float:left;margin: 1px;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
                                <a class="btn rho-primary" href="javascript:getAllComments('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=8',<?php echo $ttlCommnts; ?>,1);" role="button" style="float:left;margin: 1px;" id="shwCmmntsBtn">Show Comments (<span style="color:whitesmoke;"><?php echo $ttlCommnts; ?></span>) »</a>
                                <a class="btn btn-success hideNotice" href="javascript:getAllComments('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=8',<?php echo $ttlCommnts; ?>,3);" role="button" style="float:left;margin: 1px;" id="shwCmmntsRfrshBtn"> Refresh </a>
                                <?php if ($lgn_num > 0 && $usrID > 0) { ?>
                                    <a class="btn btn-default hideNotice" href="javascript:newComments(-1);" role="button" style="float:left;margin: 1px;" id="nwCmmntsBtn"> Add Comment </a>
                                <?php } ?>
                                <a class="btn btn-default" href="javascript:getOneNotice('previous', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=4',1,<?php echo $cntr; ?>);" role="button" style="float:none;margin: 1px;"> « Previous Notice</a>
                                <a class="btn btn-default" href="javascript:getOneNotice('next', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=4',1,<?php echo $cntr; ?>);" role="button" style="float:none;margin: 1px;"> Next Notice » </a>
                                <input id="onenoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <input id="onenoticesSortBy" type = "hidden" value="<?php echo $sortBy; ?>">
                                <input id="onenoticesSrchFor" type = "hidden" value="<?php echo $srchFor; ?>">
                                <nav aria-label="Page navigation" style="margin: 0px !important;padding:0px !important;float:right;vertical-align: middle !important;" class="hideNotice" id="cmmntsNavBtns">
                                    <ul class="pagination" style="margin: auto !important;">
                                        <li>
                                            <a style="margin: 0px !important;padding: 1px 15px 1px 15px !important;font-size: 20px !important;line-height: 1.12857143 !important;" class="rhopagination" href="javascript:getAllComments('previous', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=8',<?php echo $ttlCommnts; ?>,2);" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a style="margin: 0px !important;padding: 1px 15px 1px 15px !important;font-size: 20px !important;line-height: 1.12857143 !important;" class="rhopagination" href="javascript:getAllComments('next', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=8',<?php echo $ttlCommnts; ?>,2);" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <input id="allcommentsPageNo" type = "hidden" value="<?php echo $pageNo1; ?>">
                                    <input id="allcommentsArticleID" type = "hidden" value="<?php echo $sbmtdNoticeID; ?>">
                                    <input id="crntPrnCmntID" type = "hidden" value="-1">
                                </nav>
                            </div>
                        </div>
                    </div>   
                    <div class="row hideNotice" id="cmmntsDetailMsgsCntnr"></div>
                </div>
            </section>
            <?php
            if (getNoticeHitID($sbmtdNoticeID) <= 0) {
                createNoticeHit($sbmtdNoticeID);
            }
        }
    } else if ($vwtyp == 5) {
        //Comments/Feedback Email Form
        ?>                    
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Send Comments/Feedback</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=0');">Announcements</a></li>
                            <li class="breadcrumb-item active">Feedback</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content" style="padding: 16px 0.5rem !important;">
            <div class="container-fluid">
                <form class="form-horizontal" id="cmntsFdbckForm">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class = ""><legend class = "basic_person_lg">Addresses / Subject / Attachments</legend>
                                <?php if (1 > 2) { ?>
                                    <div class="form-group row" style="display:none;">
                                        <label for="fdbckMailCc" class="col-sm-2 col-form-label">Cc:</label>
                                        <div  class="col-sm-10">
                                            <input class="form-control" id="fdbckMailCc" name="fdbckMailCc" type = "text" placeholder="Cc"/>                                    
                                        </div>
                                    </div> 
                                <?php } ?>
                                <div class="form-group row">
                                    <label for="fdbckSubject" class="col-sm-2 col-form-label">Subject:</label>
                                    <div  class="col-sm-10">
                                        <input class="form-control" id="fdbckSubject" type = "text" placeholder="Subject"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fdbckMailAttchmnts" class="col-sm-2 col-form-label">Attached Files <span class="glyphicon glyphicon-paperclip"></span>:</label>
                                    <div class="col-sm-10 form-group row" style="padding:0px 0px 0px 15px !important;">
                                        <div  class="col-sm-11" style="padding:0px 1px 0px 0px !important;">
                                            <textarea class="form-control" id="fdbckMailAttchmnts" cols="2" placeholder="Attachments" rows="2"></textarea>
                                        </div>
                                        <div  class="col-sm-1" style="padding:0px 0px 0px 0px !important;">
                                            <button type="button" class="btn btn-default btn-sm" style="float:right;min-width:100% !important;width:100% !important;" onclick="attchFileToFdbck();"><i class="fas fa-paperclip"></i>Browse...</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class=""><legend class="basic_person_lg">Message Body</legend>
                                <div id="fdbckMsgBody"></div> 
                            </fieldset>
                        </div>
                    </div>
                    <div class="row" style="margin: -5px 0px 0px 0px !important;"> 
                        <div class="col-md-12" style="padding:0px 0px 0px 0px">
                            <div class="" style="padding:0px 1px 0px 1px !important;float:right !important;">
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="clearFdbckForm();"><img src="../cmn_images/reload.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">RESET</button>
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="autoQueueFdbck();"><img src="../cmn_images/Emailcon.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">SEND MESSAGE</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <?php
    } else if ($vwtyp == 6) {
        //Forums/Chat Rooms
        $total = get_ForumTtls($srchFor, $srchIn);
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }
        $curIdx = $pageNo - 1;
        $result = get_Forums($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
        $cntr = 0;
        ?>                    
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Forums and Chat Rooms</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=0');">Announcements</a></li>
                            <li class="breadcrumb-item active">Forums</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content" style="padding: 10px 5px 10px 5px !important;">
            <div class="container-fluid">
                <form id='allnoticesForm' class="form-horizontal" role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                    <div class="row">
                        <?php
                        $colClassType1 = "col-sm-2";
                        $colClassType2 = "col-sm-4";
                        $colClassType3 = "col-sm-1";
                        $colClassType4 = "col-sm-3";
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="">
                            <div class="input-group">
                                <input class="form-control" id="allnoticesSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncNotices(event, '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=6')">
                                <input id="allnoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <div class="input-group-append handCursor" onclick="getAllNotices('clear', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=6');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=6');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                </div>
                                              <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allnoticesSrchIn">
                                <?php
                                $valslctdArry = array("", "", "");
                                $srchInsArrys = array("Category", "Title", "Content");

                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                                                                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                                              </select>
                                              <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                -->
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allnoticesDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                        ?>
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group"> 
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-sort"></i></span>
                                </div>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allnoticesSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $srchInsArrys = array("Date Published", "No. of Hits", "Category", "Title");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($sortBy == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllNotices('previous', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=6');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllNotices('next', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=6');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row">   
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover dataTable dtr-inline" id="allForumsTable" style="width:100% !important;">
                                <thead>
                                    <tr>
                                        <th>Category and Topic</th>     
                                        <th style="text-align:center;">Message Count</th>  
                                        <th>Date Published</th>      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="allForumsRow_<?php echo $cntr; ?>" class="active">    
                                            <td>
                                                <strong><?php echo ($curIdx * $lmtSze) + ($cntr); ?>. <?php echo $row[14]; ?></strong><br/>
                                                <div style="margin-top:7px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:getOneNotice('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=4&sbmtdNoticeID=<?php echo $row[0]; ?>', 0,7);"><?php echo $row[2]; ?></a></div>
                                            </td>
                                            <td style="text-align:center;"><strong><?php echo $row[10]; ?></strong></td>
                                            <td><strong><?php echo $row[6]; ?></strong><br/>
                                                <a class="btn rho-link" href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=7&sbmtdNoticeID=<?php echo $row[0]; ?>');"><i class="far fa-hand-point-right"></i> Join the Discussion</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <?php
    } else if ($vwtyp == 7) {
        //Forums / Chats Messages Posted
        $lmtSze = 1;
        $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
        $result = get_OneNotice($sbmtdNoticeID);
        $cntr = 0;
        while ($row = loc_db_fetch_array($result)) {
            $cntr += 1;
            $sbmtdNoticeID = $row[0];
            ?>                 
            <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Forums and Chat Rooms</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=0');">Announcements</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=6');">Forums</a></li>
                                <li class="breadcrumb-item active">Chat Room</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <section class="content" style="padding: 10px 5px 10px 5px !important;">
                <div class="container-fluid">
                    <div class="card-body" style="border: 1px solid #ddd;border-radius: 5px;">
                        <h2><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" style=""><i class="fab fa-weixin"></i> <?php echo $row[2]; ?></a></h2>                            
                        <div><?php echo str_replace("{:articleID}", $row[0], $row[11]); ?></div>
                    </div>
                    <?php
                    $pageNo = 1;
                    ?>  
                    <div class="row" id="cmmntsDetailMsgsCntnr">
                        <?php
                        $lmtSze = 50;
                        $total = get_OneCommentsTtl($sbmtdNoticeID);
                        if ($pageNo > ceil($total / $lmtSze)) {
                            $pageNo = 1;
                        } else if ($pageNo < 1) {
                            $pageNo = ceil($total / $lmtSze);
                        }
                        $curIdx = $pageNo - 1;
                        $result = get_OneComments($curIdx, $lmtSze, $sbmtdNoticeID);
                        $cntr = 0;
                        ?> 
                        <div class="col-sm-12">
                            <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;">
                                <input id="allcommentsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <input id="allcommentsArticleID" type = "hidden" value="<?php echo $sbmtdNoticeID; ?>">
                                <input id="crntPrnCmntID" type = "hidden" value="-1">
                            </div>
                        </div>
                        <?php if ($lgn_num > 0 && $usrID > 0) { ?>
                            <div class="col-sm-12 hideNotice" id="cmmntsNewMsgs">
                                <form class="form-horizontal" role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                                    <div class="form-group row">
                                        <div class="col-sm-11" style="">
                                            <div id="articleNwCmmntsMsg"></div>                                     
                                        </div>
                                        <div class="col-sm-1" style="">
                                            <button type="button" class="btn rho-primary" style="" onclick="sendComment('', '#allmodules', 'grp=40&typ=1&pg=0&vtyp=7&sbmtdNoticeID=<?php echo $sbmtdNoticeID; ?>',<?php echo $total; ?>);">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
                        <div class="col-sm-12">
                            <div class="" id="cmmntsDetailMsgs">
                                <input id="allcommentsPageNo1" type = "hidden" value="<?php echo $pageNo; ?>">
                                <div class="card direct-chat direct-chat-primary">
                                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                                        <?php if ($lgn_num > 0 && $usrID > 0) { ?>                                   
                                            <button type="button" class="btn btn-default" onclick="newComments(-1);" id="nwCmmntsBtn"><i class="fas fa-plus-square"></i> Add New Comment
                                            </button>
                                        <?php } else { ?>                                         
                                            <h3 class="card-title">Messages</h3>
                                        <?php } ?>
                                        <div class="card-tools">
                                            <span data-toggle="tooltip" title="<?php echo $total; ?> Messages" class="badge badge-primary"><?php echo $total; ?></span>
                                            <button type="button" class="btn btn-tool" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&pg=0&vtyp=6');"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back
                                            </button>
                                            <button type="button" class="btn btn-tool" onclick="openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=7&sbmtdNoticeID=<?php echo $sbmtdNoticeID; ?>');"><i class="fas fa-sync-alt"></i> Refresh 
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" onclick="getAllComments('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=7', 2, 1);"><i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body" style="background-color: <?php echo $lighterColor2; ?>;">
                                        <!-- Conversations are loaded here -->
                                        <div class="direct-chat-messages">
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                $temp = explode(".", $row[8]);
                                                $extension = end($temp);
                                                if ($extension == "") {
                                                    $extension = "png";
                                                }
                                                $nwFileName = encrypt2($row[8], $smplTokenWord1) . "." . $extension;
                                                $ftp_src = $ftp_base_db_fldr . "/Person/" . $row[8];
                                                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                                                if (!file_exists($fullPemDest)) {
                                                    if (file_exists($ftp_src) && $row[8] != "") {
                                                        copy("$ftp_src", "$fullPemDest");
                                                    } else {
                                                        $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                                        copy("$ftp_src", "$fullPemDest");
                                                    }
                                                }
                                                $nwFileName = "../" . $tmpDest . $nwFileName;
                                                $byMeCls = $row[4] == $usrID ? "right" : "";
                                                $pullDrctn1 = $row[4] == $usrID ? "float-right" : "float-left";
                                                $pullDrctn2 = $row[4] == $usrID ? "float-left" : "float-right";
                                                ?>
                                                <div class="direct-chat-msg <?php echo $byMeCls; ?>" onclick="newComments(<?php echo $row[0]; ?>);">
                                                    <img class="img-circle elevation-3 direct-chat-img" src="<?php echo $nwFileName; ?>" alt="<?php echo $row[6]; ?>" style="height:44px;width: auto;">
                                                    <div class="direct-chat-text handCursor">
                                                        <div class="direct-chat-infos clearfix">
                                                            <span class="direct-chat-name <?php echo $pullDrctn1; ?>"><?php echo $row[7]; ?></span>
                                                            <span class="direct-chat-timestamp <?php echo $pullDrctn2; ?>"><?php echo $row[5]; ?>&nbsp;<i class="fa fa-reply-all" aria-hidden="true"></i></span>
                                                        </div>
                                                        <?php echo $row[2]; ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            $nwTtl = (($curIdx * $lmtSze) + $cntr);
                                            if ($nwTtl < $total) {
                                                ?>
                                                <div class="" id="showMoreCommntsBtn<?php echo $nwTtl; ?>">
                                                    <li>
                                                        <div class="container-fluid">
                                                            <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;">
                                                                <a class="btn btn-default" href="javascript:getMoreComments1('', '#showMoreCommntsBtn<?php echo $nwTtl; ?>', 'grp=40&typ=1&pg=0&vtyp=10');" role="button" style="float:none;"> « Show More »</a>
                                                                <input id="onenoticesPageNo1" type = "hidden" value="<?php echo $pageNo; ?>">
                                                            </div>
                                                        </div>
                                                    </li>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <!--/.direct-chat-messages-->
                                    <!-- /.direct-chat-pane -->
                                </div>
                                <!-- /.card-body -->    
                                <?php if ($lgn_num > 0 && $usrID > 0) { ?>
                                    <div class="card-footer" style="border-top: 1px solid #ddd;">
                                        <form  role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                                            <div class="input-group">
                                                <input type="text" name="articleNwCmmntsMsg2" id="articleNwCmmntsMsg2" value="" placeholder="Type Message ..." class="form-control" onkeyup="enterKeyFuncSndCmnt(event, '', '#allmodules', 'grp=40&typ=1&pg=0&vtyp=7&sbmtdNoticeID=<?php echo $sbmtdNoticeID; ?>',<?php echo $total; ?>);">
                                                <span class="input-group-append">
                                                    <button type="button" class="btn rho-primary" onclick="sendComment2('', '#allmodules', 'grp=40&typ=1&pg=0&vtyp=7&sbmtdNoticeID=<?php echo $sbmtdNoticeID; ?>',<?php echo $total; ?>);">Send</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        if (getNoticeHitID($sbmtdNoticeID) <= 0) {
            createNoticeHit($sbmtdNoticeID);
        }
    } else if ($vwtyp == 8) {
        /* Display one Notice Comments */
        $lmtSze = 10;
        $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
        $prntCmmntID = isset($_POST['prntCmmntID']) ? cleanInputData($_POST['prntCmmntID']) : -1;
        $total = get_NotcCommentsTtl($sbmtdNoticeID, $prntCmmntID);
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }
        $curIdx = $pageNo - 1;
        $result = get_NotcComments($curIdx, $lmtSze, $sbmtdNoticeID, $prntCmmntID);
        $cntr = 0;
        ?> 
        <?php if ($lgn_num > 0 && $usrID > 0) { ?>
            <div class="col-sm-12 hideNotice" id="cmmntsNewMsgs">
                <form class="form-horizontal" role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                    <div class="form-group row">
                        <div class="col-sm-11" style="">
                            <div id="articleNwCmmntsMsg"></div>                                     
                        </div>
                        <div class="col-sm-1" style="">
                            <button type="button" class="btn rho-primary" style="" onclick="sendComment('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=8',<?php echo $total; ?>);">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>
        <div class="col-sm-12">
            <div class="" id="cmmntsDetailMsgs">
                <input id="allcommentsPageNo1" type = "hidden" value="<?php echo $pageNo; ?>">
                <div class="card direct-chat direct-chat-primary">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">Comments</h3>                    
                        <div class="card-tools">
                            <span data-toggle="tooltip" title="<?php echo $total; ?> Messages" class="badge badge-primary"><?php echo $total; ?></span>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" onclick="getAllComments('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=8', 2, 1);"><i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="background-color: <?php echo $lighterColor2; ?>;">
                        <!-- Conversations are loaded here -->
                        <div class="direct-chat-messages">
                            <?php
                            while ($row = loc_db_fetch_array($result)) {
                                $cntr += 1;

                                $temp = explode(".", $row[8]);
                                $extension = end($temp);
                                if (trim($extension) == "") {
                                    $extension = "png";
                                }
                                $nwFileName = encrypt2($row[8], $smplTokenWord1) . "." . $extension;
                                $ftp_src = $ftp_base_db_fldr . "/Person/" . $row[8];
                                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                                if (!file_exists($fullPemDest)) {
                                    if (file_exists($ftp_src) && $row[8] != "") {
                                        copy("$ftp_src", "$fullPemDest");
                                    } else {
                                        $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                        copy("$ftp_src", "$fullPemDest");
                                    }
                                }
                                $nwFileName = "../" . $tmpDest . $nwFileName;
                                ?>
                                <div class="direct-chat-msg" onclick="newComments(<?php echo $row[0]; ?>);">
                                    <img class="img-circle elevation-3 direct-chat-img" src="<?php echo $nwFileName; ?>" alt="<?php echo $row[6]; ?>" style="height:44px;width: auto;">
                                    <div class="direct-chat-text handCursor">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left"><?php echo $row[7]; ?></span>
                                            <span class="direct-chat-timestamp float-right"><?php echo $row[5]; ?>&nbsp;<i class="fa fa-reply-all" aria-hidden="true"></i></span>
                                        </div>
                                        <?php echo $row[2]; ?>
                                    </div>
                                </div>
                                <?php
                                $prntCmmntID = $row[0];
                                $lmtSze1 = 10;
                                $pageNo1 = isset($_POST['pageNo1']) ? cleanInputData($_POST['pageNo1']) : 1;
                                $total1 = get_NotcCommentsTtl($sbmtdNoticeID, $prntCmmntID);
                                if ($pageNo1 > ceil($total1 / $lmtSze1)) {
                                    $pageNo1 = 1;
                                } else if ($pageNo1 < 1) {
                                    $pageNo1 = ceil($total1 / $lmtSze1);
                                }
                                $curIdx1 = $pageNo1 - 1;
                                $result1 = get_NotcComments($curIdx1, $lmtSze1, $sbmtdNoticeID, $prntCmmntID);
                                $cntr1 = 0;
                                while ($row1 = loc_db_fetch_array($result1)) {
                                    $cntr1 += 1;
                                    $temp = explode(".", $row1[8]);
                                    $extension = end($temp);
                                    if (trim($extension) == "") {
                                        $extension = "png";
                                    }
                                    $nwFileName = encrypt2($row1[8], $smplTokenWord1) . "." . $extension;
                                    $ftp_src = $ftp_base_db_fldr . "/Person/" . $row1[8];
                                    $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                                    if (!file_exists($fullPemDest)) {
                                        if (file_exists($ftp_src)) {
                                            copy("$ftp_src", "$fullPemDest");
                                        } else {
                                            $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                            copy("$ftp_src", "$fullPemDest");
                                        }
                                    }
                                    $nwFileName = "../" . $tmpDest . $nwFileName;
                                    ?>
                                    <div class="direct-chat-msg" style="margin-left:50px !important;" onclick="newComments(<?php echo $prntCmmntID; ?>);">
                                        <img class="img-circle elevation-3 direct-chat-img" src="<?php echo $nwFileName; ?>" alt="<?php echo $row[6]; ?>" style="height:44px;width: auto;">
                                        <div class="direct-chat-text handCursor">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-left"><?php echo $row1[7]; ?></span>
                                                <span class="direct-chat-timestamp float-right"><?php echo $row1[5]; ?>&nbsp;<i class="fa fa-reply-all" aria-hidden="true"></i></span>
                                            </div>
                                            <?php echo $row1[2]; ?>
                                        </div>
                                    </div> 
                                    <!--<div class="direct-chat-msg right">
                                        <img class="direct-chat-img" src="<?php echo $nwFileName; ?>" alt="<?php echo $row[6]; ?>">
                                        <div class="direct-chat-text">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-right"><?php echo $row1[7]; ?></span>
                                                <span class="direct-chat-timestamp float-left" style="color:white !important;"><?php echo $row1[5]; ?></span>
                                            </div>
                                    <?php echo $row1[2]; ?>
                                        </div>
                                    </div>-->
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <!--/.direct-chat-messages-->
                        <!-- /.direct-chat-pane -->
                    </div>
                    <!-- /.card-body -->
                    <?php if ($lgn_num > 0 && $usrID > 0) { ?>
                        <div class="card-footer" style="border-top: 1px solid #ddd;">
                            <form  role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                                <div class="input-group">
                                    <input type="text" name="articleNwCmmntsMsg2" id="articleNwCmmntsMsg2" value="" placeholder="Type Message ..." class="form-control" onkeyup="enterKeyFuncSndCmnt(event, '', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=8',<?php echo $total; ?>);">
                                    <span class="input-group-append">
                                        <button type="button" class="btn rho-primary" onclick="sendComment2('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=1&pg=0&vtyp=8',<?php echo $total; ?>);">Send</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
}

function get_Notices($searchFor, $searchIn, $offset, $limit_size, $ordrBy, $sbmtdNoticeCtgry = "") {
    global $usrID;
    global $lgn_num;
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;

    $extrWhr = "";
    if (trim($sbmtdNoticeCtgry) != "") {
        $artCategory = $sbmtdNoticeCtgry;
    }
    $ordrByCls = "ORDER BY tbl1.article_id DESC";
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (tbl1.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    if ($lgn_num > 0 && $usrID > 0) {
        if ($isMaster != "1") {
            $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
        }
    } else {
        $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (tbl1.allowed_group_type='Public')";
    }
    $wherecls = " AND (tbl1.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or tbl1.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $wherecls .= " AND (tbl1.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (tbl1.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }
    if ($ordrBy == "Date Published") {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    } else if ($ordrBy == "No. of Hits") {
        $ordrByCls = "ORDER BY tbl1.hits DESC";
    } else if ($ordrBy == "Category") {
        $ordrByCls = "ORDER BY tbl1.article_category ASC";
    } else if ($ordrBy == "Title") {
        $ordrByCls = "ORDER BY tbl1.article_header ASC";
    } else {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    }
    $sqlStr = "SELECT tbl1.* FROM (SELECT a.article_id, a.article_category, a.article_header, a.header_url, a.article_body,  
       a.is_published, a.publishing_date, a.author_name, a.author_email, prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
       a.allowed_group_type, a.allowed_group_id, a.article_intro_msg,
       org.get_criteria_name(a.allowed_group_id, a.allowed_group_type) group_name,
       a.local_classification  
  FROM self.self_articles a) tbl1  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_NoticeTtls($searchFor, $searchIn, $sbmtdNoticeCtgry = "") {
    global $usrID;
    global $lgn_num;
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;
    $extrWhr = "";
    if (trim($sbmtdNoticeCtgry) != "") {
        $artCategory = $sbmtdNoticeCtgry;
    }
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    if ($lgn_num > 0 && $usrID > 0) {
        if ($isMaster != "1") {
            $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
        }
    } else {
        $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (a.allowed_group_type='Public')";
    }
    $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
        $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
        $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }

    $sqlStr = "SELECT count(1) 
  FROM self.self_articles a 
  WHERE (1=1" . $extrWhr . "$wherecls)";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Manuals($searchFor, $searchIn, $offset, $limit_size, $ordrBy) {
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;

    $extrWhr = "";
    $ordrByCls = "ORDER BY tbl1.article_id DESC";
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (tbl1.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    if ($isMaster != "1") {
        $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category IN ('System Help', 'Operational Manuals')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
    } else {
        $extrWhr .= " and (tbl1.article_category IN ('System Help', 'Operational Manuals'))";
    }
    $wherecls = " AND (tbl1.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or tbl1.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $wherecls .= " AND (tbl1.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (tbl1.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }
    if ($ordrBy == "Date Published") {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    } else if ($ordrBy == "No. of Hits") {
        $ordrByCls = "ORDER BY tbl1.hits DESC";
    } else if ($ordrBy == "Category") {
        $ordrByCls = "ORDER BY tbl1.article_category ASC";
    } else if ($ordrBy == "Title") {
        $ordrByCls = "ORDER BY tbl1.article_header ASC";
    } else {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    }
    $sqlStr = "SELECT tbl1.* FROM (SELECT a.article_id, a.article_category, a.article_header, a.header_url, a.article_body,  
       a.is_published, a.publishing_date, a.author_name, a.author_email, prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
       a.allowed_group_type, a.allowed_group_id, a.article_intro_msg  
  FROM self.self_articles a) tbl1  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_ManualsTtls($searchFor, $searchIn) {
//global $usrID;
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;
    $extrWhr = "";

    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    if ($isMaster != "1") {
        $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category IN ('System Help', 'Operational Manuals')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
    } else {
        $extrWhr .= " and (a.article_category IN ('System Help', 'Operational Manuals'))";
    }
    $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
        $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
        $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }

    $sqlStr = "SELECT count(1) 
  FROM self.self_articles a 
  WHERE (1=1" . $extrWhr . "$wherecls)";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Forums($searchFor, $searchIn, $offset, $limit_size, $ordrBy) {
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $prsnid;

    $extrWhr = "";
    $ordrByCls = "ORDER BY tbl1.article_id DESC";
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (tbl1.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category IN ('Forum Topic','Chat Room')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
    $wherecls = " AND (tbl1.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or tbl1.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $wherecls .= " AND (tbl1.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (tbl1.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }
    if ($ordrBy == "Date Published") {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    } else if ($ordrBy == "No. of Hits") {
        $ordrByCls = "ORDER BY tbl1.hits DESC";
    } else if ($ordrBy == "Category") {
        $ordrByCls = "ORDER BY tbl1.article_category ASC";
    } else if ($ordrBy == "Title") {
        $ordrByCls = "ORDER BY tbl1.article_header ASC";
    } else {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    }
    $sqlStr = "SELECT tbl1.* FROM (SELECT a.article_id, a.article_category, a.article_header, a.header_url, a.article_body,  
       a.is_published, to_char(to_timestamp(a.publishing_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') publishing_date, 
       a.author_name, a.author_email, prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(b.posted_cmnt_id) from self.self_article_cmmnts b where a.article_id = b.article_id) hits,
       a.allowed_group_type, a.allowed_group_id, a.article_intro_msg, a.local_classification   
  FROM self.self_articles a) tbl1  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_ForumTtls($searchFor, $searchIn) {
//global $usrID;
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;
    $extrWhr = "";

    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category NOT IN ('Forum Topic','Chat Room')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
    $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
        $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
        $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }

    $sqlStr = "SELECT count(1) 
  FROM self.self_articles a 
  WHERE (1=1" . $extrWhr . "$wherecls)";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneNotices($searchFor, $searchIn, $offset, $limit_size, $ordrBy) {
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;

    $extrWhr = "";
    $ordrByCls = "ORDER BY a.article_id DESC";
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }

    if ($isMaster != "1") {
        $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
    }
    $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        //$qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
        $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        //$qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
        $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }
    if ($ordrBy == "Date Published") {
        $ordrByCls = "ORDER BY a.publishing_date DESC";
    } else if ($ordrBy == "No. of Hits") {
        $ordrByCls = "ORDER BY a.hits DESC";
    } else if ($ordrBy == "Category") {
        $ordrByCls = "ORDER BY a.article_category ASC";
    } else if ($ordrBy == "Title") {
        $ordrByCls = "ORDER BY a.article_header ASC";
    }
    $sqlStr = "SELECT a.article_id, 
        a.article_category, 
        a.article_header, 
        a.header_url, 
        a.article_body,  
       a.is_published, 
       to_char(to_timestamp(a.publishing_date, 'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') publishing_date,
       CASE WHEN a.author_prsn_id>0 THEN prs.get_prsn_name(a.author_prsn_id) ELSE a.author_name END author_name, 
       a.author_email, 
       prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
       a.article_intro_msg,
       a.allowed_group_type,
       a.allowed_group_id,
       org.get_criteria_name(a.allowed_group_id, a.allowed_group_type) group_name,
       a.local_classification
     FROM self.self_articles a  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_OneNotice($articleID) {
    $sqlStr = "SELECT a.article_id,
            a.article_category,
            a.article_header,
            a.header_url,
            a.article_body,
            a.is_published,
            to_char(to_timestamp(a.publishing_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') publishing_date,
            CASE WHEN a.author_prsn_id>0 THEN prs.get_prsn_name(a.author_prsn_id) ELSE a.author_name END author_name,
            a.author_email,
            prs.get_prsn_loc_id(a.author_prsn_id),
            (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
            a.article_intro_msg,
            a.allowed_group_type,
            a.allowed_group_id,
            org.get_criteria_name(a.allowed_group_id, a.allowed_group_type) group_name,
            a.local_classification
            FROM self.self_articles a
            WHERE article_id = " . $articleID;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_OneComments($offset, $limit_size, $articleID) {
    $sqlStr = "SELECT a.posted_cmnt_id, a.article_id, a.comment_or_post, a.in_rspns_to_cmnt_id, 
       a.created_by, a.creation_date, sec.get_usr_name(a.created_by), 
       prs.get_prsn_name(sec.get_usr_prsn_id(a.created_by)) fullname,
       prs.get_prsn_imgloc(sec.get_usr_prsn_id(a.created_by)) imglocs
  FROM self.self_article_cmmnts a WHERE a.article_id=" . $articleID
            . " ORDER BY a.creation_date ASC, a.posted_cmnt_id ASC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_OneCommentsTtl($articleID) {
    $sqlStr = "SELECT count(a.posted_cmnt_id) 
  FROM self.self_article_cmmnts a WHERE a.article_id=" . $articleID;

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_NotcComments($offset, $limit_size, $articleID, $prntCmmntID) {
    $sqlStr = "SELECT a.posted_cmnt_id, a.article_id, a.comment_or_post, a.in_rspns_to_cmnt_id, 
       a.created_by, to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') creation_date,
       sec.get_usr_name(a.created_by), 
       prs.get_prsn_name(sec.get_usr_prsn_id(a.created_by)) fullname,
       prs.get_prsn_imgloc(sec.get_usr_prsn_id(a.created_by)) imglocs
  FROM self.self_article_cmmnts a WHERE a.in_rspns_to_cmnt_id=$prntCmmntID and a.article_id=" . $articleID
            . " ORDER BY a.creation_date ASC, a.posted_cmnt_id ASC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_NotcCommentsTtl($articleID, $prntCmmntID) {
    $sqlStr = "SELECT count(a.posted_cmnt_id) 
  FROM self.self_article_cmmnts a WHERE a.in_rspns_to_cmnt_id=$prntCmmntID and a.article_id=" . $articleID;

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function deleteNotice(
        $articleID) {
    $insSQL = "DELETE FROM self.self_articles WHERE article_id = " . $articleID;
    $affctd1 = execUpdtInsSQL($insSQL);
    $insSQL = "DELETE FROM self.self_article_cmmnts WHERE article_id = " . $articleID;
    $affctd2 = execUpdtInsSQL($insSQL);
    $insSQL = "DELETE FROM self.self_articles_hits WHERE article_id = " . $articleID;
    $affctd3 = execUpdtInsSQL($insSQL);

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Notice(s)!";
        $dsply .= "<br/>$affctd2 Comment(s)!";
        $dsply .= "<br/>$affctd3 Hit(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createNotice($artclCatgry, $artTitle, $hdrURL, $artBody, $isPblshed, $datePublished, $authorNm, $authorEmail, $authorID, $introMsg, $allwdGrpTyp, $allwdGrpID, $locClsfctn) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_articles(
            article_category, article_header, article_body, header_url, 
            is_published, publishing_date, author_name, author_email, author_prsn_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            article_intro_msg, allowed_group_type, allowed_group_id, local_classification) VALUES ("
            . "'" . loc_db_escape_string($artclCatgry)
            . "', '" . loc_db_escape_string($artTitle)
            . "', '" . loc_db_escape_string($artBody)
            . "', '" . loc_db_escape_string($hdrURL)
            . "', '" . loc_db_escape_string($isPblshed)
            . "', '" . loc_db_escape_string($datePublished)
            . "', '" . loc_db_escape_string($authorNm)
            . "', '" . loc_db_escape_string($authorEmail)
            . "', " . loc_db_escape_string($authorID) .
            ", " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr
            . "', '" . loc_db_escape_string($introMsg)
            . "', '" . loc_db_escape_string($allwdGrpTyp)
            . "', " . loc_db_escape_string($allwdGrpID)
            . ", '" . loc_db_escape_string($locClsfctn)
            . "')";
    execUpdtInsSQL($insSQL);
}

function updateNotice($articleID, $artclCatgry, $artTitle, $hdrURL, $artBody, $isPblshed, $datePublished, $authorNm, $authorEmail, $authorID, $introMsg, $allwdGrpTyp, $allwdGrpID, $locClsfctn) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE self.self_articles SET 
            article_category='" . loc_db_escape_string($artclCatgry)
            . "', article_header='" . loc_db_escape_string($artTitle) .
            "', article_body='" . loc_db_escape_string($artBody) .
            "', header_url='" . loc_db_escape_string($hdrURL) .
            "', is_published='" . loc_db_escape_string($isPblshed) .
            "' , publishing_date='" . loc_db_escape_string($datePublished)
            . "', author_name = '" . loc_db_escape_string($authorNm)
            . "', author_email = '" . loc_db_escape_string($authorEmail)
            . "', author_prsn_id = " . loc_db_escape_string($authorID)
            . ", last_update_by=" . $usrID .
            " , last_update_date='" . loc_db_escape_string($dateStr) .
            "', article_intro_msg='" . loc_db_escape_string($introMsg) .
            "', allowed_group_type='" . loc_db_escape_string($allwdGrpTyp) .
            "', allowed_group_id=" . loc_db_escape_string($allwdGrpID) .
            ", local_classification='" . loc_db_escape_string($locClsfctn) .
            "'   WHERE article_id = " . $articleID;
//echo $insSQL;
    execUpdtInsSQL($insSQL);
}

function createNoticeCmmnt($artclID, $artCmmnt, $prntCmntID) {
    global $usrID;
    global $lgn_num;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_article_cmmnts(
            article_id, comment_or_post, in_rspns_to_cmnt_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            login_number) VALUES ("
            . "" . $artclID
            . ", '" . loc_db_escape_string($artCmmnt)
            . "', " . $prntCmntID
            . ", " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr
            . "', " . $lgn_num
            . ")";
    execUpdtInsSQL($insSQL);
}

function pblshUnplsh($articleID, $isPblshed, $datePublished) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE self.self_articles SET 
            is_published='" . loc_db_escape_string($isPblshed) .
            "' , publishing_date='" . loc_db_escape_string($datePublished)
            . "', last_update_by=" . $usrID .
            " , last_update_date='" . loc_db_escape_string($dateStr) .
            "'  WHERE article_id = " . $articleID;
//echo $insSQL;
    execUpdtInsSQL($insSQL);
}

function getLtstNoticeID($articleCtgry) {
    $sqlStr = "select article_id from self.self_articles "
            . "where is_published='1' and (now() >= to_timestamp(publishing_date,'YYYY-MM-DD HH24:MI:SS')) "
            . "and article_category = '" . loc_db_escape_string($articleCtgry)
            . "' ORDER BY publishing_date DESC LIMIT 1 OFFSET 0";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getNoticeBody($articleID) {
    $sqlStr = "select article_body from self.self_articles where article_id = " . $articleID;
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $artBody = str_replace("{:articleID}", $articleID, $row[0]);
        return $artBody;
    }
    return "";
}

function getNoticeHeaderUrl($articleID) {
    $sqlStr = "select header_url from self.self_articles where article_id = " . $articleID;
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getNoticeHeader($articleID) {
    $sqlStr = "select article_header from self.self_articles where article_id = " . $articleID;
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $hdrUrl = str_replace("{:articleID}", $articleID, getNoticeHeaderUrl($articleID));
        if ($hdrUrl == "") {
            return "$row[0]";
        } else {
            return "<a href=\"" . $hdrUrl . "\">" . $row[0] . "</a>";
        }
    }
    return "";
}

function getNoticeHeaderID($articleHdr) {
    $sqlStr = "select article_id  from self.self_articles where  article_header ilike '" . loc_db_escape_string($articleHdr) . "'";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getNoticeHitID($artclID) {
    global $usrID;
    $sqlStr = "select article_hit_id from self.self_articles_hits "
            . "where created_by = " . $usrID . " and article_id = " . $artclID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createNoticeHit($artclID) {
    global $usrID;
    global $lgn_num;

    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_articles_hits(
            article_id, created_by, creation_date, login_number) VALUES ("
            . loc_db_escape_string($artclID) . ", "
            . $usrID . ", '" . $dateStr . "', " . $lgn_num . ")";
    execUpdtInsSQL($insSQL);
}

function get_SliderNotices($searchFor, $searchIn, $offset, $limit_size, $ordrBy) {
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;

    $extrWhr = "";
    $ordrByCls = "ORDER BY tbl1.article_id DESC";
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (tbl1.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category IN ('Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
    $wherecls = " AND (tbl1.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or tbl1.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $wherecls .= " AND (tbl1.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (tbl1.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }
    if ($ordrBy == "Date Published") {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    } else if ($ordrBy == "No. of Hits") {
        $ordrByCls = "ORDER BY tbl1.hits DESC";
    } else if ($ordrBy == "Category") {
        $ordrByCls = "ORDER BY tbl1.article_category ASC";
    } else if ($ordrBy == "Title") {
        $ordrByCls = "ORDER BY tbl1.article_header ASC";
    } else {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    }
    $sqlStr = "SELECT tbl1.* FROM (SELECT a.article_id, a.article_category, a.article_header, a.header_url, a.article_body,  
       a.is_published, a.publishing_date, a.author_name, a.author_email, prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
       a.allowed_group_type, a.allowed_group_id, a.article_intro_msg  
  FROM self.self_articles a) tbl1  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_SliderNoticeTtls($searchFor, $searchIn) {
//global $usrID;
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;
    $extrWhr = "";

    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category IN ('Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
    $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
        $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
        $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }

    $sqlStr = "SELECT count(1) 
  FROM self.self_articles a 
  WHERE (1=1" . $extrWhr . "$wherecls)";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getNoticeIntroMsg($articleID) {
    $sqlStr = "select article_intro_msg from self.self_articles where article_id = " . $articleID;
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $artBody = str_replace("{:articleID}", $articleID, $row[0]);
        return $artBody;
    }
    return "";
}
?>