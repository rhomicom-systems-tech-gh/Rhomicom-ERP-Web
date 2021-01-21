<?php
$mdlNm = "System Administration";
$ModuleName = $mdlNm;
$dfltPrvldgs = array("View System Administration", "View Users & their Roles",
    /* 2 */ "View Roles & their Priviledges", "View Registered Modules & their Priviledges",
    /* 4 */ "View Security Policies", "View Server Settings", "View User Logins",
    /* 7 */ "View Audit Trail Tables", "Add New Users & their Roles", "Edit Users & their Roles",
    /* 10 */ "Add New Roles & their Priviledges", "Edit Roles & their Priviledges",
    /* 12 */ "Add New Security Policies", "Edit Security Policies", "Add New Server Settings",
    /* 15 */ "Edit Server Settings", "Set manual password for users",
    /* 17 */ "Send System Generated Passwords to User Mails",
    /* 18 */ "View SQL", "View Record History", "Add/Edit Extra Info Labels", "Delete Extra Info Labels",
    /* 22 */ "Add Notices", "Edit Notices", "Delete Notices", "View Notices Admin");

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

$cntent = "<div>
				<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
					<li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
						<span style=\"text-decoration:none;\">Home</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#$noticesElmntNm', 'grp=40&typ=3&vtyp=0');\">
						<span style=\"text-decoration:none;\">All Notices</span>
					</li>";
$canview = test_prmssns($dfltPrvldgs[0], $mdlNm) || (($vwtyp <= 1 || $vwtyp >= 4) && test_prmssns("View Self-Service", "Self Service"));
$canAddNotices = test_prmssns($dfltPrvldgs[22], $mdlNm);
$canEdtNotices = test_prmssns($dfltPrvldgs[23], $mdlNm);
$canDelNotices = test_prmssns($dfltPrvldgs[24], $mdlNm);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qryStr == "DELETE") {
            if ($actyp == 1) {
                //articleID
                $articleID = cleanInputData($_POST['articleID']);
                echo deleteNotice($articleID);
            }
        } else if ($qryStr == "UPDATE") {
            if ($actyp == 1) {
                //Notices
                $articleID = cleanInputData($_POST['articleID']);
                $artclCatgry = cleanInputData($_POST['articleCategory']);
                $articleLoclClsfctn = cleanInputData($_POST['articleLoclClsfctn']);
                $grpType = cleanInputData($_POST['grpType']);
                $groupID = cleanInputData($_POST['groupID']);
                $articleIntroMsg = (cleanInputData($_POST['articleIntroMsg']));
                $artBody = (cleanInputData($_POST['articleBodyText']));
                $artTitle = cleanInputData($_POST['articleTitle']);
                $datePublished = cleanInputData($_POST['datePublished']);
                $AuthorName = getPrsnFullNm($prsnid);
                $AuthorEmail = getPrsnEmail($prsnid);
                $authorPrsnID = $prsnid;
                if ($grpType == "Person Type" && is_numeric($groupID) === FALSE) {
                    $groupID = getPssblValID($groupID, getLovID("Person Types"));
                }
                if ($AuthorEmail == "") {
                    $AuthorEmail = $admin_email;
                }
                $hdrURL = "";
                if ($datePublished != "") {
                    $datePublished = cnvrtDMYTmToYMDTm($datePublished);
                } else {
                    $datePublished = "4000-12-31 23:59:59";
                }
                $isPblshed = date('Y-m-d H:i:s') < $datePublished ? "0" : "1";
                $oldNoticeID = getGnrlRecID2("self.self_articles", "article_header", "article_id", $artTitle);
                $lovIDClsfctn = getLovID("Notice Classifications");
                $clsfctnID = getPssblValID($articleLoclClsfctn, $lovIDClsfctn);
                if ($clsfctnID <= 0 && $lovIDClsfctn > 0) {
                    createPssblValsForLov($lovIDClsfctn, $articleLoclClsfctn, $articleLoclClsfctn, "1", "," . $orgID . ",");
                }
                if ($artclCatgry != "" && $articleLoclClsfctn != "" && $artTitle != "" && ($oldNoticeID <= 0 || $oldNoticeID == $articleID)) {
                    if ($articleID <= 0) {
                        createNotice($artclCatgry, $artTitle, $hdrURL, $artBody, $isPblshed, $datePublished, $AuthorName, $AuthorEmail, $authorPrsnID, $articleIntroMsg, $grpType, $groupID, $articleLoclClsfctn);
                    } else {
                        updateNotice($articleID, $artclCatgry, $artTitle, $hdrURL, $artBody, $isPblshed, $datePublished, $AuthorName, $AuthorEmail, $authorPrsnID, $articleIntroMsg, $grpType, $groupID, $articleLoclClsfctn);
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Forum/Notice Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to save Forum/Notice!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 2) {
                //Publish Unpublish
                $articleID = cleanInputData($_POST['articleID']);
                $actionNtice = cleanInputData($_POST['actionNtice']);
                $datePublished = "4000-12-31 23:59:59";
                $isPblshed = "0";
                if ($actionNtice == "Publish") {
                    $datePublished = date('Y-m-d H:i:s');
                    $isPblshed = "1";
                }
                if ($articleID > 0 && $actionNtice != "") {
                    pblshUnplsh($articleID, $isPblshed, $datePublished);
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Publishing Status Changed Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Change Publishing Status!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 3) {
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
            if ($pgNo == 0) {
                if ($vwtyp == 0) {
                    $isMaster = "0";
                    echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Notices Home</span>
					</li>
                                       </ul>
                                     </div>";

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
                    <form id='allnoticesForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allnoticesSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncNotices(event, '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=0&isMaster=<?php echo $isMaster; ?>')">
                                    <input id="allnoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('clear', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=0&isMaster=<?php echo $isMaster; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=0&isMaster=<?php echo $isMaster; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allnoticesSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Category", "Title", "Content");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>                                                                                                                                                                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                    </select>-->
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
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
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
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
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>')">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllNotices('previous', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=0&isMaster=<?php echo $isMaster; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllNotices('next', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=0&isMaster=<?php echo $isMaster; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row" style="padding:0px 15px 0px 15px !important;">
                            <?php
                            $cntr = 0;
                            $grpcntr = 0;
                            $ttlRecs = loc_db_num_rows($result);
                            while ($row = loc_db_fetch_array($result)) {
                                $cntr += 1;
                                if ($cntr == 1) {
                                    ?>
                                    <div class="jumbotron" style="border: 1px solid #ddd;border-radius: 2px;">
                                        <div class="container-fluid">
                                            <h2><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" style=""><i class="fa fa-certificate" aria-hidden="true"></i> <?php echo $row[2]; ?></a></h2>
                                            <div class="rho-card-body2" style="width:100% !important;min-width:100% !important;"><?php echo rhoReplaceBtn($row[13], "{:RMS}", "{:RME}", "...", true); ?></div>
                                            <p><a class="btn btn-primary" href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" role="button">View Full Article &raquo;</a></p>
                                        </div>
                                    </div>  
                                    <?php
                                } else {
                                    if ($grpcntr == 0) {
                                        ?>
                                        <div class="" style="border: 1px solid #ddd;border-radius: 2px;margin-bottom: 10px;">
                                            <div class="container-fluid">
                                                <div class="row">          
                                                    <?php
                                                }
                                                ?>
                                                <div class="col-md-6">
                                                    <div style="">
                                                        <h2><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" style=""><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?php echo $row[2]; ?></a></h2>
                                                        <div class="rho-card-body2" style="width:100% !important;min-width:100% !important;"><?php echo rhoReplaceBtn($row[13], "{:RMS}", "{:RME}", "...", true); ?></div>
                                                        <p><a class="btn btn-default" href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" role="button">View details &raquo;</a></p>
                                                    </div>
                                                </div>
                                                <?php
                                                if ($grpcntr == 1 || $cntr == $ttlRecs) {
                                                    ?>
                                                </div>
                                            </div> 
                                        </div>
                                        <?php
                                        $grpcntr = 0;
                                    } else {
                                        $grpcntr = $grpcntr + 1;
                                    }
                                }
                            }
                            ?>
                        </div>
                    </form>
                    <?php
                } else if ($vwtyp == 1) {
                    $isMaster = $canEdtNotices || $canDelNotices ? "1" : "0";
                    echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Notices List</span>
					</li>
                                       </ul>
                                     </div>";
                    $total = get_NoticeTtls($srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_Notices($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-1";
                    $colClassType4 = "col-lg-3";
                    ?>
                    <form id='allnoticesForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php
                            if ($canAddNotices === true) {
                                $isMaster = "1";
                                ?> 
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneNoticeForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'allnoticesForm', 'Add New Notice', -1, 2, 0)">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Notice
                                    </button>
                                </div>
                                <?php
                            } else {
                                $isMaster = "0";
                                $colClassType1 = "col-lg-2";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-1";
                                $colClassType4 = "col-lg-3";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allnoticesSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncNotices(event, '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>')">
                                    <input id="allnoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('clear', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
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
                                    </select>-->
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
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
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
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
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=0&isMaster=<?php echo $isMaster; ?>')">
                                        <span class="glyphicon glyphicon-th-large"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllNotices('previous', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllNotices('next', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allnoticesTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canEdtNotices === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>Notice Category</th>
                                            <th>Title</th>
                                            <th>Author Email</th>
                                            <th>No. of Hits</th>
                                            <th>...</th>
                                            <?php if ($canEdtNotices === true) { ?>
                                                <th>...</th>
                                                <th>...</th>
                                                <th>ID</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="allnoticesRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canEdtNotices === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Notice" 
                                                                onclick="getOneNoticeForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'allnoticesForm', 'View/Edit Notice (ID:<?php echo $row[0]; ?>)', <?php echo $row[0]; ?>, 3, 0)" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <?php echo $row[1]; ?>
                                                    <input type="hidden" id="allnoticesRow<?php echo $cntr; ?>_ArticleID" value="<?php echo $row[0]; ?>"/>
                                                </td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[8]; ?></td>
                                                <td class="lovtd"><?php echo $row[10]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Notice" onclick="getOneNotice('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=4&sbmtdNoticeID=<?php echo $row[0]; ?>', 0,<?php echo $cntr; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php
                                                if ($canEdtNotices === true) {
                                                    if ($row[5] == 1) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Unpublish" onclick="pblshUnplsh('allnoticesRow_<?php echo $cntr; ?>', 'Unpublish', '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/success.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } else {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Publish" onclick="pblshUnplsh('allnoticesRow_<?php echo $cntr; ?>', 'Publish', '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <?php
                                                if ($canEdtNotices === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" onclick="delOneNotice('allnoticesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Notice" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[0]; ?></td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>                     
                        </div>
                    </form>
                    <?php
                } else if ($vwtyp == 2) {
                    /* New Notice Form */
                    $sbmtdNoticeID = -1;
                    ?>
                    <div class="">
                        <div class="row">                  
                            <div class="col-md-12">
                                <form class="form-horizontal">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <fieldset class="" style="min-height:240px !important;">
                                                        <legend class="basic_person_lg">Topic/Notice Attributes</legend>
                                                        <div class="row" style="padding:0px 15px 0px 15px !important;">
                                                            <div class="col-md-12" style="padding:0px 15px 0px 15px !important;">
                                                                <div class="row"> 
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="articleCategory" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Category:</label>
                                                                        <div  class="col-md-9">
                                                                            <select class="form-control" id="articleCategory" >
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "", "");
                                                                                $valuesArry = array("Notices/Announcements", "Welcome Message", "Latest News", "Useful Links",
                                                                                    "System Help", "Write-Ups/Research Papers", "Operational Manuals", "About Organisation",
                                                                                    "Other Notices", "Forum Topic", "Chat Room", "Slider");
                                                                                for ($y = 0; $y < count($valuesArry); $y++) {
                                                                                    /* if ($lmtSze == $valuesArry[$y]) {
                                                                                      $valslctdArry[$y] = "selected";
                                                                                      } else {
                                                                                      $valslctdArry[$y] = "";
                                                                                      } */
                                                                                    ?>
                                                                                    <option value="<?php echo $valuesArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $valuesArry[$y]; ?></option>                            
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">                                                                        
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="articleLoclClsfctn" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Local Classification:</label>
                                                                        <div  class="col-md-9">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="articleLoclClsfctn" value="">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Notice Classifications', 'gnrlOrgID', '', '', 'radio', true, '', 'articleLoclClsfctn', 'articleLoclClsfctn', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="grpType" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Group Type:</label>
                                                                        <div  class="col-md-9">
                                                                            <select class="form-control" id="grpType" onchange="grpTypNoticesChange();">
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                                $valuesArry = array("Public", "Everyone", "Divisions/Groups", "Grade", "Job",
                                                                                    "Position", "Site/Location", "Person Type");
                                                                                for ($y = 0; $y < count($valuesArry); $y++) {
                                                                                    ?>
                                                                                    <option value="<?php echo $valuesArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $valuesArry[$y]; ?></option>                            
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="groupName" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Group Name:</label>
                                                                        <div class="col-md-9">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="groupName" value="" readonly="">
                                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                <input type="hidden" id="groupID" value="-1">
                                                                                <?php
                                                                                $lovName = "";
                                                                                ?>
                                                                                <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'groupID', 'groupName', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row"> 
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="datePublished" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Publishing Date:</label>
                                                                        <div  class="col-md-9">
                                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd hh:ii:ss">
                                                                                <input class="form-control" size="16" type="text" id="datePublished" value="" readonly="">
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                </div>    
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div> 
                                        </div>                                         
                                        <div class="col-md-8">
                                            <fieldset class=""><legend class="basic_person_lg">Notice/Forum Intro Message</legend>
                                                <div class="row" style="margin: 1px 0px 0px 0px !important;padding:0px 15px 0px 15px !important;"> 
                                                    <div class="form-group form-group-sm">
                                                        <!--<label for="articleTitle" class="control-label col-xs-1">Topic:</label>-->
                                                        <div  class="col-md-9" style="padding:0px 1px 0px 1px !important;margin-bottom:3px !important;">
                                                            <input class="form-control rqrdFld" id="articleTitle" type = "text" placeholder="Forum Topic or Article Title"/>
                                                            <input class="form-control" id="articleID" type = "hidden" value="<?php echo $sbmtdNoticeID; ?>"/>
                                                        </div>
                                                        <div class="col-md-3" style="padding:0px 1px 0px 1px">
                                                            <div class="" style="padding:0px 1px 0px 1px !important;float:right !important;">
                                                                <button type="button" class="btn btn-default btn-sm" style="" onclick="closeNoticeForm('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');"><img src="cmn_images/close.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">CLOSE</button>
                                                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveOneNoticeForm('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">SAVE</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="padding:0px 15px 0px 15px !important;">
                                                    <div id="articleIntroMsg"></div> 
                                                </div> 
                                                <input type="hidden" id="articleIntroMsgDecoded" value="">
                                            </fieldset>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <fieldset class=""><legend class="basic_person_lg">Notice/Forum Full Message Text</legend>                                                
                                                <div class="row" style="padding:0px 15px 0px 15px !important;">
                                                    <div id="articleBodyText"></div> 
                                                </div> 
                                                <div class="row" style="margin: -5px 0px 0px 0px !important;"> 
                                                    <input type="hidden" id="articleBodyTextDecoded" value="">
                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px">
                                                        <div class="" style="padding:0px 1px 0px 1px !important;float:right !important;">
                                                            <button type="button" class="btn btn-default btn-sm" style="" onclick="closeNoticeForm('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');"><img src="cmn_images/close.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">CLOSE</button>
                                                            <button type="button" class="btn btn-default btn-sm" style="" onclick="saveOneNoticeForm('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">SAVE</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>                                   
                            </div>                
                        </div>          
                    </div> 
                    <?php
                } else if ($vwtyp == 3) {
                    /* Edit Notice Form */
                    $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
                    $result = get_OneNotice($sbmtdNoticeID);
                    while ($row = loc_db_fetch_array($result)) {
                        ?>
                        <div class="">
                            <div class="row">                  
                                <div class="col-md-12">
                                    <form class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <fieldset class="" style="min-height:240px !important;">
                                                            <legend class="basic_person_lg">Topic/Notice Attributes</legend>
                                                            <div class="row" style="padding:0px 15px 0px 15px !important;">
                                                                <div class="col-md-12" style="padding:0px 15px 0px 15px !important;">
                                                                    <div class="row"> 
                                                                        <div class="form-group form-group-sm">
                                                                            <label for="articleCategory" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Category:</label>
                                                                            <div  class="col-md-9">
                                                                                <select class="form-control" id="articleCategory" >
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "", "");
                                                                                    $valuesArry = array("Notices/Announcements", "Welcome Message", "Latest News", "Useful Links",
                                                                                        "System Help", "Write-Ups/Research Papers", "Operational Manuals", "About Organisation",
                                                                                        "Other Notices", "Forum Topic", "Chat Room", "Slider");
                                                                                    for ($y = 0; $y < count($valuesArry); $y++) {
                                                                                        if ($row[1] == $valuesArry[$y]) {
                                                                                            $valslctdArry[$y] = "selected";
                                                                                        } else {
                                                                                            $valslctdArry[$y] = "";
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $valuesArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $valuesArry[$y]; ?></option>                            
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">                                                                        
                                                                        <div class="form-group form-group-sm">
                                                                            <label for="articleLoclClsfctn" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Local Classification:</label>
                                                                            <div  class="col-md-9">
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="articleLoclClsfctn" value="<?php echo $row[15]; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Notice Classifications', 'gnrlOrgID', '', '', 'radio', true, '', 'articleLoclClsfctn', 'articleLoclClsfctn', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group form-group-sm">
                                                                            <label for="grpType" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Group Type:</label>
                                                                            <div  class="col-md-9">
                                                                                <select class="form-control" id="grpType" onchange="grpTypNoticesChange();">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                                    $valuesArry = array("Public", "Everyone", "Divisions/Groups", "Grade", "Job",
                                                                                        "Position", "Site/Location", "Person Type");
                                                                                    for ($y = 0; $y < count($valuesArry); $y++) {
                                                                                        if ($row[12] == $valuesArry[$y]) {
                                                                                            $valslctdArry[$y] = "selected";
                                                                                        } else {
                                                                                            $valslctdArry[$y] = "";
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $valuesArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $valuesArry[$y]; ?></option>                            
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group form-group-sm">
                                                                            <label for="groupName" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Group Name:</label>
                                                                            <div  class="col-md-9">
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" aria-label="..." id="groupName" value="<?php echo $row[14]; ?>" readonly="">
                                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                    <input type="hidden" id="groupID" value="<?php echo $row[13]; ?>">
                                                                                    <?php
                                                                                    $dsbld = "disabled=\"true\"";
                                                                                    if ($row[12] != "Everyone" && $row[12] != "") {
                                                                                        $dsbld = "";
                                                                                    }
                                                                                    ?>
                                                                                    <label <?php echo $dsbld; ?> id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'groupID', 'groupName', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row"> 
                                                                        <div class="form-group form-group-sm">
                                                                            <label for="datePublished" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Publishing Date:</label>
                                                                            <div  class="col-md-9">
                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd hh:ii:ss">
                                                                                    <input class="form-control" size="16" type="text" id="datePublished" value="<?php echo $row[6]; ?>" readonly="">
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        </div> 
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group form-group-sm">
                                                                            <label for="authorName" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Author:</label>
                                                                            <div  class="col-md-9">
                                                                                <input type="text" class="form-control" aria-label="..." id="authorName" value="<?php echo $row[7]; ?>" style="width:100% !important;" readonly="true">
                                                                                <input type="hidden" id="authorPrsnLocID" value="<?php echo $row[9]; ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row"> 
                                                                        <div class="form-group form-group-sm">
                                                                            <label for="authorEmail" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Email:</label>
                                                                            <div  class="col-md-9">
                                                                                <input type="text" class="form-control" aria-label="..." id="authorEmail" value="<?php echo $row[8]; ?>" style="width:100% !important;">                                                                                
                                                                            </div>
                                                                        </div> 
                                                                    </div>    
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div> 
                                            </div>                                         
                                            <div class="col-md-8">
                                                <fieldset class=""><legend class="basic_person_lg">Notice/Forum Intro Message</legend>
                                                    <div class="row" style="margin: 1px 0px 0px 0px !important;padding:0px 15px 0px 15px !important;"> 
                                                        <div class="form-group form-group-sm">
                                                            <!--<label for="articleTitle" class="control-label col-md-2">Topic/Title:</label>-->
                                                            <div  class="col-md-9" style="padding:0px 1px 0px 1px !important;margin-bottom:3px !important;">
                                                                <input class="form-control rqrdFld" id="articleTitle" type = "text" placeholder="Forum Topic or Article Title" value="<?php echo $row[2]; ?>"/>
                                                                <input class="form-control" id="articleID" type = "hidden" value="<?php echo $sbmtdNoticeID; ?>"/>
                                                            </div>
                                                            <div class="col-md-3" style="padding:0px 1px 0px 1px">
                                                                <div class="" style="padding:0px 1px 0px 1px !important;float:right !important;">
                                                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="closeNoticeForm('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');"><img src="cmn_images/close.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">CLOSE</button>
                                                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="saveOneNoticeForm('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">SAVE</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding:0px 15px 0px 15px !important;">
                                                        <div id="articleIntroMsg"></div> 
                                                    </div> 
                                                    <input type="hidden" id="articleIntroMsgDecoded" value="<?php echo urlencode($row[11]); ?>">
                                                </fieldset>

                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                <fieldset class=""><legend class="basic_person_lg">Notice/Forum Full Message Text</legend>                                                
                                                    <div class="row" style="padding:0px 15px 0px 15px !important;">
                                                        <div id="articleBodyText"></div> 
                                                    </div> 
                                                    <div class="row" style="margin: -5px 0px 0px 0px !important;"> 
                                                        <input type="hidden" id="articleBodyTextDecoded" value="<?php echo urlencode($row[4]); ?>">
                                                        <div class="col-md-12" style="padding:0px 0px 0px 0px">
                                                            <div class="" style="padding:0px 1px 0px 1px !important;float:right !important;">
                                                                <button type="button" class="btn btn-default btn-sm" style="" onclick="closeNoticeForm('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');"><img src="cmn_images/close.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">CLOSE</button>
                                                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveOneNoticeForm('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">SAVE</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </form>                                   
                                </div>                
                            </div>          
                        </div>
                        <?php
                    }
                } else if ($vwtyp == 4) {
                    /* Display one Full Notice ReadOnly */
                    $lmtSze = 1;
                    $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
                    $sbmtdNoticeCtgry = isset($_POST['sbmtdNoticeCtgry']) ? cleanInputData($_POST['sbmtdNoticeCtgry']) : -1;
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
                        $result =get_Notices($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                        //get_OneNotices($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                    }
                    if (loc_db_num_rows($result) <= 0) {
                        echo $cntent . "<li onclick=\"openATab('#$noticesElmntNm', 'grp=40&typ=3&vtyp=1');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Notices List</span>
					</li>
                                        <li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">No Notice/Article Found</span>
					</li>
                                       </ul>
                                     </div>";
                    }
                    $cntr = 0;
                    while ($row = loc_db_fetch_array($result)) {
                        $cntr += 1;
                        echo $cntent . "<li onclick=\"openATab('#$noticesElmntNm', 'grp=40&typ=3&vtyp=1');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Notices List</span>
					</li>
                                        <li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">" . $row[2] . "</span>
					</li>
                                       </ul>
                                     </div>";
                        $sbmtdNoticeID = $row[0];
                        ?>
                        <div class="" style="border: 1px solid #ddd;border-radius: 2px;">
                            <div class="container-fluid">
                                <h2><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" style=""><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?php echo $row[2]; ?></a></h2>                            
                                <div class="col-md-12" style="min-height: 250px !important;"><?php echo str_replace("{:articleID}", $row[0], str_replace("{:RME}", "", str_replace("{:RMS}", "", $row[4]))); ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="container-fluid">
                                <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;">
                                    <a class="btn btn-default" href="javascript:getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=0&isMaster=0');" role="button" style="float:left;"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a>
                                    <a class="btn btn-default" href="javascript:getOneNotice('previous', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=4',1,<?php echo $cntr; ?>);" role="button" style="float:none;"> « Previous Notice</a>
                                    <a class="btn btn-default" href="javascript:getOneNotice('next', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=4',1,<?php echo $cntr; ?>);" role="button" style="float:none;"> Next Notice » </a>
                                    <input id="onenoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="onenoticesSortBy" type = "hidden" value="<?php echo $sortBy; ?>">
                                    <input id="onenoticesSrchFor" type = "hidden" value="<?php echo $srchFor; ?>">
                                </div>
                            </div>
                        </div>
                        <?php
                        $pageNo = 1;
                        $ttlCommnts = get_OneCommentsTtl($sbmtdNoticeID);
                        ?>
                        <div class="row" style="margin-bottom:5px;">
                            <div class="container-fluid" >
                                <div class="col-md-12" style="font-size:16px;padding:4px 1px 4px 1px;margin:3px 0px 2px 0px;text-align: center;border-top:1px solid #ddd;">
                                    <div class="">
                                        <a class="btn btn-info" href="javascript:getAllComments('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=3&pg=0&vtyp=8',<?php echo $ttlCommnts; ?>,1);" role="button" style="float:left;height: 31px;margin: 1px;" id="shwCmmntsBtn">Show Comments (<span style="color:whitesmoke;"><?php echo $ttlCommnts; ?></span>) »</a>
                                        <a class="btn btn-success hideNotice" href="javascript:getAllComments('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=3&pg=0&vtyp=8',<?php echo $ttlCommnts; ?>,3);" role="button" style="float:left;height: 31px;margin: 1px;" id="shwCmmntsRfrshBtn"> Refresh </a>
                                        <a class="btn btn-default hideNotice" href="javascript:newComments(-1);" role="button" style="float:left;height: 31px;margin: 1px;" id="nwCmmntsBtn"> Add Comment </a>
                                        <nav aria-label="Page navigation" style="margin: 0px !important;padding:0px !important;float:right;vertical-align: middle !important;" class="hideNotice" id="cmmntsNavBtns">
                                            <ul class="pagination" style="margin: auto !important;">
                                                <li>
                                                    <a style="margin: 0px !important;padding: 1px 15px 1px 15px !important;font-size: 20px !important;line-height: 1.12857143 !important;" class="rhopagination" href="javascript:getAllComments('previous', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=3&pg=0&vtyp=8',<?php echo $ttlCommnts; ?>,2);" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a style="margin: 0px !important;padding: 1px 15px 1px 15px !important;font-size: 20px !important;line-height: 1.12857143 !important;" class="rhopagination" href="javascript:getAllComments('next', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=3&pg=0&vtyp=8',<?php echo $ttlCommnts; ?>,2);" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <input id="allcommentsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                            <input id="allcommentsArticleID" type = "hidden" value="<?php echo $sbmtdNoticeID; ?>">
                                            <input id="crntPrnCmntID" type = "hidden" value="-1">
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <div class="row hideNotice" id="cmmntsDetailMsgsCntnr"></div>
                        <?php
                        if (getNoticeHitID($sbmtdNoticeID) <= 0) {
                            createNoticeHit($sbmtdNoticeID);
                        }
                    }
                } else if ($vwtyp == 5) {
                    //Comments/Feedback Email Form
                    echo $cntent . "    <li onclick=\"openATab('#$noticesElmntNm', 'grp=40&typ=3&vtyp=1');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Notices List</span>
					</li>
                                        <li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Send Feedback</span>
					</li>
                                       </ul>
                                     </div>";
                    ?>
                    <form class="form-horizontal" id="cmntsFdbckForm">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class = ""><legend class = "basic_person_lg">Addresses / Subject / Attachments</legend>
                                    <div class="form-group form-group-sm">
                                        <label for="fdbckMailCc" class="control-label col-md-2">Cc:</label>
                                        <div  class="col-md-10">
                                            <input class="form-control" id="fdbckMailCc" name="fdbckMailCc" type = "text" placeholder="Cc"/>                                    
                                        </div>
                                    </div> 
                                    <div class="form-group form-group-sm">
                                        <label for="fdbckSubject" class="control-label col-md-2">Subject:</label>
                                        <div  class="col-md-10">
                                            <input class="form-control" id="fdbckSubject" type = "text" placeholder="Subject"/>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label for="fdbckMailAttchmnts" class="control-label col-md-2">Attached Files <span class="glyphicon glyphicon-paperclip"></span>:</label>
                                        <div  class="col-md-10">
                                            <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                <textarea class="form-control" id="fdbckMailAttchmnts" cols="2" placeholder="Attachments" rows="3"></textarea>
                                            </div>
                                            <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                <button type="button" class="btn btn-default btn-sm" style="float:right;" onclick="attchFileToFdbck();"><span class="glyphicon glyphicon-paperclip"></span>Browse...</button>
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
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="clearFdbckForm();"><img src="cmn_images/reload.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">RESET</button>
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="autoQueueFdbck();"><img src="cmn_images/Emailcon.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">SEND MESSAGE</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                } else if ($vwtyp == 6) {
                    //Forums/Chat Rooms
                    echo $cntent . "<li onclick=\"openATab('#$noticesElmntNm', 'grp=40&typ=3&vtyp=1');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Notices List</span>
					</li>
                                        <li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Forums and Chat Rooms</span>
					</li>
                                       </ul>
                                     </div>";
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
                    <form id='allnoticesForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-4";
                            $colClassType3 = "col-lg-1";
                            $colClassType4 = "col-lg-3";
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allnoticesSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncNotices(event, '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=6')">
                                    <input id="allnoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('clear', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=6')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=6')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
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
                                    </select>-->
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
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
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
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
                                            <a class="rhopagination" href="javascript:getAllNotices('previous', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=6');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllNotices('next', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=6');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">   
                            <div class="col-md-12">
                                <table class="table table-striped table-responsive table-bordered" id="allForumsTable" style="width:100% !important;">
                                    <thead>
                                        <tr>
                                            <th>Category and Topic</th>     
                                            <th>Message Count</th>  
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
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="javascript:getOneNotice('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=4&sbmtdNoticeID=<?php echo $row[0]; ?>', 0,7);"><?php echo $row[2]; ?></a><br/>
                                                </td>
                                                <td><strong><?php echo $row[10]; ?></strong></td>
                                                <td><strong><?php echo $row[6]; ?></strong><br/>
                                                    <a class="btn btn-hot" href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&vtyp=7&sbmtdNoticeID=<?php echo $row[0]; ?>');"><i class="fa fa-hand-o-right"></i> Join the Discussion</a>
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
                    <?php
                } else if ($vwtyp == 7) {
                    //Forums / Chats Messages Posted
                    echo $cntent . "<li onclick=\"openATab('#$noticesElmntNm', 'grp=40&typ=3&vtyp=1');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Notices List</span>
					</li>
                                        <li onclick=\"openATab('#$noticesElmntNm', 'grp=40&typ=3&vtyp=6');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Forums and Chat Rooms</span>
					</li>
                                        <li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">One Chat Room</span>
					</li>
                                       </ul>
                                     </div>";
                    $lmtSze = 1;
                    $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
                    $result = get_OneNotice($sbmtdNoticeID);
                    $cntr = 0;
                    while ($row = loc_db_fetch_array($result)) {
                        $cntr += 1;
                        $sbmtdNoticeID = $row[0];
                        ?>
                        <div class="" style="border: 1px solid #ddd;border-radius: 2px;">
                            <div class="container-fluid">
                                <h2><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" style=""><i class="fa fa-weixin" aria-hidden="true"></i> <?php echo $row[2]; ?></a></h2>                            
                                <div><?php echo str_replace("{:articleID}", $row[0], $row[11]); ?></div>
                            </div>
                        </div>
                        <?php
                        $pageNo = 1;
                        ?>  
                        <div class="row" id="cmmntsDetailMsgsCntnr">
                            <?php
                            $lmtSze = 5;
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
                            <div class="col-md-12">
                                <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;"><input id="allcommentsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="allcommentsArticleID" type = "hidden" value="<?php echo $sbmtdNoticeID; ?>">
                                    <input id="crntPrnCmntID" type = "hidden" value="-1">
                                </div>
                            </div>

                            <div class="row col-md-12" id="cmmntsNewMsgs">
                                <form class="form-inline">
                                    <div class="form-group col-md-12">
                                        <div class="col-md-11" style="padding:0px 1px 0px 1px !important;">
                                            <div id="articleNwCmmntsMsg"></div>                                     
                                        </div>
                                        <div class="col-md-1" style="padding:3px 1px 0px 1px !important;">
                                            <button type="button" class="btn btn-info" style="" onclick="sendComment('', '#cmmntsDetailMsgs', 'grp=40&typ=3&pg=0&vtyp=11',<?php echo $total; ?>);">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <div class="" id="cmmntsDetailMsgs">
                                    <input id="allcommentsPageNo1" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="pull-left">Messages</div>
                                            <div class="widget-icons pull-right">
                                                <a href="javascript:getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=6');" class="wminimize"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a> 
                                                <a href="javascript:javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&vtyp=7&sbmtdNoticeID=<?php echo $sbmtdNoticeID; ?>');" class="wclose"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh </a>
                                            </div>  
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-body" style="padding:0px;">
                                            <!-- Widget content -->
                                            <div class="padd sscroll">
                                                <ul class="chats" style="margin-left: 10px !important;margin-right: 10px !important;padding: 7px !important;">
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
                                                            if (file_exists($ftp_src) && $row[8] != "" && !is_dir($ftp_src)) {
                                                                copy("$ftp_src", "$fullPemDest");
                                                            } else {
                                                                $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                                                copy("$ftp_src", "$fullPemDest");
                                                            }
                                                        }
                                                        $nwFileName = $tmpDest . $nwFileName;
                                                        $byMeCls = $row[4] == $usrID ? "by-me" : "by-other";
                                                        $pullDrctn = $row[4] == $usrID ? "pull-left" : "pull-right";
                                                        ?>
                                                        <li class="<?php echo $byMeCls; ?>">
                                                            <div class="avatar <?php echo $pullDrctn; ?>">
                                                                <img src="<?php echo $nwFileName; ?>" alt="<?php echo $row[6]; ?>" style="height:44px;width: 44px;object-fit: contain;">
                                                            </div>
                                                            <div class="chat-content">
                                                                <div class="chat-meta"><?php echo $row[7]; ?> <span class="pull-right"><?php echo $row[5]; ?>&nbsp;</span></div>
                                                                <?php echo $row[2]; ?>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    $nwTtl = (($curIdx * $lmtSze) + $cntr);
                                                    if ($nwTtl < $total) {
                                                        ?>
                                                        <div class="" id="showMoreCommntsBtn<?php echo $nwTtl; ?>">
                                                            <li>
                                                                <div class="container-fluid">
                                                                    <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;">
                                                                        <a class="btn btn-default" href="javascript:getMoreComments1('', '#showMoreCommntsBtn<?php echo $nwTtl; ?>', 'grp=40&typ=3&pg=0&vtyp=10');" role="button" style="float:none;"> « Show More »</a>
                                                                        <input id="onenoticesPageNo1" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        </div>
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
                        <div class="row col-md-12 hideNotice" id="cmmntsNewMsgs">
                            <form class="form-inline">
                                <div class="form-group col-md-12">
                                    <div class="col-md-11" style="padding:0px 1px 0px 1px !important;">
                                        <div id="articleNwCmmntsMsg"></div>                                     
                                    </div>
                                    <div class="col-md-1" style="padding:3px 1px 0px 1px !important;">
                                        <button type="button" class="btn btn-info" style="" onclick="sendComment('', '#cmmntsDetailMsgsCntnr', 'grp=40&typ=3&pg=0&vtyp=8',<?php echo $total; ?>);">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>                   
                        <div class="col-md-12">
                            <div class="" id="cmmntsDetailMsgs">
                                <input id="allcommentsPageNo1" type = "hidden" value="<?php echo $pageNo; ?>">
                                <ul class="chats" style="margin-left: 10px !important;padding: 7px 1px !important;">
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
                                            if (file_exists($ftp_src) && $row[8] != "" && !is_dir($ftp_src)) {
                                                copy("$ftp_src", "$fullPemDest");
                                            } else {
                                                $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                                copy("$ftp_src", "$fullPemDest");
                                            }
                                        }
                                        $nwFileName = $tmpDest . $nwFileName;
                                        ?>
                                        <li class="by-me" onclick="newComments(<?php echo $row[0]; ?>);">
                                            <div class="avatar pull-left">
                                                <img src="<?php echo $nwFileName; ?>" alt="<?php echo $row[6]; ?>" style="height:44px;width: 44px;object-fit: contain;">
                                            </div>
                                            <div class="chat-content">
                                                <div class="chat-meta"><?php echo $row[7]; ?> <span class="pull-right"><?php echo $row[5]; ?>&nbsp;<i class="fa fa-reply-all" aria-hidden="true"></i></span></div>
                                                <?php echo $row[2]; ?>
                                                <div class="clearfix"></div>
                                            </div>
                                        </li>   
                                        <?php
                                        $prntCmmntID = $row[0];
                                        $lmtSze1 = 5;
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
                                            $nwFileName = encrypt2($row1[8], $smplTokenWord1) . "." . $extension;
                                            $ftp_src = $ftp_base_db_fldr . "/Person/" . $row1[8];
                                            $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                                            if (!file_exists($fullPemDest)) {
                                                if (file_exists($ftp_src) && !is_dir($ftp_src)) {
                                                    copy("$ftp_src", "$fullPemDest");
                                                } else {
                                                    $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                                    copy("$ftp_src", "$fullPemDest");
                                                }
                                            }
                                            $nwFileName = $tmpDest . $nwFileName;
                                            ?>
                                            <div class="" id="showMoreCommntsBtn">
                                                <li class="by-me" style="margin-left: 50px !important;">
                                                    <div class="avatar pull-left">
                                                        <img src="<?php echo $nwFileName; ?>" alt="<?php echo $row[6]; ?>" style="height:44px;width: 44px;object-fit: contain;">
                                                    </div>
                                                    <div class="chat-content">
                                                        <div class="chat-meta"><?php echo $row1[7]; ?> <span class="pull-right"><?php echo $row1[5]; ?></span></div>
                                                        <?php echo $row1[2]; ?>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </li>  
                                            </div>
                                            <?php
                                        }
                                        $nwTtl = (($curIdx1 * $lmtSze1) + $cntr1);
                                        if ($nwTtl < $total1) {
                                            ?>
                                            <div class="" id="showMoreCommntsBtn<?php echo $nwTtl; ?>">
                                                <li>
                                                    <div class="container-fluid">
                                                        <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;">
                                                            <a class="btn btn-default" href="javascript:getMoreComments('', '#showMoreCommntsBtn<?php echo $nwTtl; ?>', 'grp=40&typ=3&pg=0&vtyp=9');" role="button" style="float:none;"> « Show More »</a>
                                                            <input id="onenoticesPageNo1" type = "hidden" value="<?php echo $pageNo1; ?>">
                                                            <input id="onenoticesCmntID1" type = "hidden" value="<?php echo $prntCmmntID; ?>">
                                                        </div>
                                                    </div>
                                                </li>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                    } else if ($vwtyp == 9) {
                        $prntCmmntID = isset($_POST['prntCmmntID']) ? cleanInputData($_POST['prntCmmntID']) : -1;
                        $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
                        $lmtSze1 = 5;
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
                            $nwFileName = encrypt2($row1[8], $smplTokenWord1) . "." . $extension;
                            $ftp_src = $ftp_base_db_fldr . "/Person/" . $row1[8];
                            $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                            if (!file_exists($fullPemDest)) {
                                if (file_exists($ftp_src) && !is_dir($ftp_src)) {
                                    copy("$ftp_src", "$fullPemDest");
                                } else {
                                    $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                    copy("$ftp_src", "$fullPemDest");
                                }
                            }
                            $nwFileName = $tmpDest . $nwFileName;
                            ?>
                            <li class="by-me" style="margin-left: 50px !important;">
                                <div class="avatar pull-left">
                                    <img src="<?php echo $nwFileName; ?>" alt="<?php echo $row1[6]; ?>" style="height:44px;width: 44px;object-fit: contain;">
                                </div>
                                <div class="chat-content">
                                    <div class="chat-meta"><?php echo $row1[7]; ?> <span class="pull-right"><?php echo $row1[5]; ?></span></div>
                                    <?php echo $row1[2]; ?>
                                    <div class="clearfix"></div>
                                </div>
                            </li>  
                            <?php
                        }
                        $nwTtl = (($curIdx1 * $lmtSze1) + $cntr1);
                        if ($nwTtl < $total1) {
                            ?>
                            <div class="" id="showMoreCommntsBtn<?php echo $nwTtl; ?>">
                                <li>
                                    <div class="container-fluid">
                                        <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;">
                                            <a class="btn btn-default" href="javascript:getMoreComments('', '#showMoreCommntsBtn<?php echo $nwTtl; ?>', 'grp=40&typ=3&pg=0&vtyp=9');" role="button" style="float:none;"> « Show More »</a>
                                            <input id="onenoticesPageNo1" type = "hidden" value="<?php echo $pageNo1; ?>">
                                            <input id="onenoticesCmntID1" type = "hidden" value="<?php echo $prntCmmntID; ?>">
                                        </div>
                                    </div>
                                </li>
                            </div>
                            <?php
                        }
                    } else if ($vwtyp == 10) {
                        $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
                        $lmtSze1 = 5;
                        $pageNo1 = isset($_POST['pageNo1']) ? cleanInputData($_POST['pageNo1']) : 1;
                        $total1 = get_OneCommentsTtl($sbmtdNoticeID);
                        if ($pageNo1 > ceil($total1 / $lmtSze1)) {
                            $pageNo1 = 1;
                        } else if ($pageNo1 < 1) {
                            $pageNo1 = ceil($total1 / $lmtSze1);
                        }
                        $curIdx1 = $pageNo1 - 1;
                        $result1 = get_OneComments($curIdx1, $lmtSze1, $sbmtdNoticeID);
                        $cntr1 = 0;
                        while ($row1 = loc_db_fetch_array($result1)) {
                            $cntr1 += 1;

                            $temp = explode(".", $row1[8]);
                            $extension = end($temp);
                            $nwFileName = encrypt2($row1[8], $smplTokenWord1) . "." . $extension;
                            $ftp_src = $ftp_base_db_fldr . "/Person/" . $row1[8];
                            $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                            if (!file_exists($fullPemDest)) {
                                if (file_exists($ftp_src) && !is_dir($ftp_src)) {
                                    copy("$ftp_src", "$fullPemDest");
                                } else {
                                    $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                    copy("$ftp_src", "$fullPemDest");
                                }
                            }
                            $nwFileName = $tmpDest . $nwFileName;
                            $byMeCls = $row1[4] == $usrID ? "by-me" : "by-other";
                            $pullDrctn = $row1[4] == $usrID ? "pull-left" : "pull-right";
                            ?>
                            <li class="<?php echo $byMeCls; ?>">
                                <div class="avatar <?php echo $pullDrctn; ?>">
                                    <img src="<?php echo $nwFileName; ?>" alt="<?php echo $row1[6]; ?>" style="height:44px;width: 44px;object-fit: contain;">
                                </div>
                                <div class="chat-content">
                                    <div class="chat-meta"><?php echo $row1[7]; ?> <span class="pull-right"><?php echo $row1[5]; ?></span></div>
                                    <?php echo $row1[2]; ?>
                                    <div class="clearfix"></div>
                                </div>
                            </li>  
                            <?php
                        }
                        $nwTtl = (($curIdx1 * $lmtSze1) + $cntr1);
                        if ($nwTtl < $total1) {
                            ?>
                            <div class="" id="showMoreCommntsBtn<?php echo $nwTtl; ?>">
                                <li>
                                    <div class="container-fluid">
                                        <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;">
                                            <a class="btn btn-default" href="javascript:getMoreComments1('', '#showMoreCommntsBtn<?php echo $nwTtl; ?>', 'grp=40&typ=3&pg=0&vtyp=10');" role="button" style="float:none;"> « Show More »</a>
                                            <input id="onenoticesPageNo1" type = "hidden" value="<?php echo $pageNo1; ?>">
                                            <input id="onenoticesCmntID1" type = "hidden" value="<?php echo $prntCmmntID; ?>">
                                        </div>
                                    </div>
                                </li>
                            </div>
                            <?php
                        }
                    } else if ($vwtyp == 11) {
                        /* Display one Notice Comments */
                        $sbmtdNoticeID = isset($_POST['sbmtdNoticeID']) ? cleanInputData($_POST['sbmtdNoticeID']) : -1;
                        $lmtSze = 5;
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
                        <input id="allcommentsPageNo1" type = "hidden" value="<?php echo $pageNo; ?>">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-left">Messages</div>
                                <div class="widget-icons pull-right">
                                    <a href="javascript:getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=6');" class="wminimize"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a> 
                                    <a href="javascript:javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&vtyp=7&sbmtdNoticeID=<?php echo $sbmtdNoticeID; ?>');" class="wclose"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh </a>
                                </div>  
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body" style="padding:0px;">
                                <!-- Widget content -->
                                <div class="padd sscroll">
                                    <ul class="chats" style="margin-left: 10px !important;margin-right: 10px !important;padding: 7px !important;">
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;

                                            $temp = explode(".", $row[8]);
                                            $extension = end($temp);
                                            $nwFileName = encrypt2($row[8], $smplTokenWord1) . "." . $extension;
                                            $ftp_src = $ftp_base_db_fldr . "/Person/" . $row[8];
                                            $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                                            if (!file_exists($fullPemDest)) {
                                                if (file_exists($ftp_src) && !is_dir($ftp_src)) {
                                                    copy("$ftp_src", "$fullPemDest");
                                                } else {
                                                    $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                                    copy("$ftp_src", "$fullPemDest");
                                                }
                                            }
                                            $nwFileName = $tmpDest . $nwFileName;
                                            $byMeCls = $row[4] == $usrID ? "by-me" : "by-other";
                                            $pullDrctn = $row[4] == $usrID ? "pull-left" : "pull-right";
                                            ?>
                                            <li class="<?php echo $byMeCls; ?>">
                                                <div class="avatar <?php echo $pullDrctn; ?>">
                                                    <img src="<?php echo $nwFileName; ?>" alt="<?php echo $row[6]; ?>" style="height:44px;width: 44px;object-fit: contain;">
                                                </div>
                                                <div class="chat-content">
                                                    <div class="chat-meta"><?php echo $row[7]; ?> <span class="pull-right"><?php echo $row[5]; ?>&nbsp;</span></div>
                                                    <?php echo $row[2]; ?>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                        $nwTtl = (($curIdx * $lmtSze) + $cntr);
                                        if ($nwTtl < $total) {
                                            ?>
                                            <div class="" id="showMoreCommntsBtn<?php echo $nwTtl; ?>">
                                                <li>
                                                    <div class="container-fluid">
                                                        <div style="font-size:16px;padding:3px 1px 2px 1px;margin:3px 0px 2px 0px;text-align: center;">
                                                            <a class="btn btn-default" href="javascript:getMoreComments1('', '#showMoreCommntsBtn<?php echo $nwTtl; ?>', 'grp=40&typ=3&pg=0&vtyp=10');" role="button" style="float:none;"> « Show More »</a>
                                                            <input id="onenoticesPageNo1" type = "hidden" value="<?php echo $pageNo; ?>">
                                                        </div>
                                                    </div>
                                                </li>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>                 
                        <?php
                    } else if ($vwtyp == 20) {
                        //Help Notices Tabular
                        echo $cntent . "<li onclick=\"openATab('#$noticesElmntNm', 'grp=40&typ=3&vtyp=1');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Notices List</span>
					</li>
                                        <li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Help</span>
					</li>
                                       </ul>
                                     </div>";

                        $isMaster = $canEdtNotices || $canDelNotices ? "1" : "0";
                        $total = get_ManualsTtls($srchFor, $srchIn);
                        if ($pageNo > ceil($total / $lmtSze)) {
                            $pageNo = 1;
                        } else if ($pageNo < 1) {
                            $pageNo = ceil($total / $lmtSze);
                        }

                        $curIdx = $pageNo - 1;
                        $result = get_Manuals($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                        $cntr = 0;
                        $colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-3";
                        $colClassType3 = "col-lg-1";
                        $colClassType4 = "col-lg-3";
                        ?>
                        <form id='allnoticesForm' action='' method='post' accept-charset='UTF-8'>
                            <div class="row rhoRowMargin">
                                <?php
                                if ($canAddNotices === true) {
                                    $isMaster = "1";
                                    ?> 
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneNoticeForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'allnoticesForm', 'Add New Notice', -1, 2, 0)">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Notice
                                        </button>
                                    </div>
                                    <?php
                                } else {
                                    $isMaster = "0";
                                    $colClassType1 = "col-lg-2";
                                    $colClassType2 = "col-lg-4";
                                    $colClassType3 = "col-lg-1";
                                    $colClassType4 = "col-lg-3";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="allnoticesSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncNotices(event, '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>')">
                                        <input id="allnoticesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('clear', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>')">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>')">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType1; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
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
                                        </select>-->
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
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
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
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
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllNotices('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=0&isMaster=<?php echo $isMaster; ?>')">
                                            <span class="glyphicon glyphicon-th-large"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType1; ?>">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a class="rhopagination" href="javascript:getAllNotices('previous', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getAllNotices('next', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="allnoticesTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <?php if ($canEdtNotices === true) { ?>
                                                    <th>...</th>
                                                <?php } ?>
                                                <th>Notice Category</th>
                                                <th>Title</th>
                                                <th>Author Email</th>
                                                <th>No. of Hits</th>
                                                <th>...</th>
                                                <?php if ($canEdtNotices === true) { ?>
                                                    <th>...</th>
                                                    <th>...</th>
                                                    <th>ID</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="allnoticesRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <?php if ($canEdtNotices === true) { ?>                                    
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Notice" 
                                                                    onclick="getOneNoticeForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'allnoticesForm', 'View/Edit Notice (ID:<?php echo $row[0]; ?>)', <?php echo $row[0]; ?>, 3, 0)" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="lovtd">
                                                        <?php echo $row[1]; ?>
                                                        <input type="hidden" id="allnoticesRow<?php echo $cntr; ?>_ArticleID" value="<?php echo $row[0]; ?>"/>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                                    <td class="lovtd"><?php echo $row[8]; ?></td>
                                                    <td class="lovtd"><?php echo $row[10]; ?></td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Notice" onclick="getOneNotice('', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=4&sbmtdNoticeID=<?php echo $row[0]; ?>', 0,<?php echo $cntr; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php
                                                    if ($canEdtNotices === true) {
                                                        if ($row[5] == 1) {
                                                            ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Unpublish" onclick="pblshUnplsh('allnoticesRow_<?php echo $cntr; ?>', 'Unpublish', '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                    <img src="cmn_images/success.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } else {
                                                            ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Publish" onclick="pblshUnplsh('allnoticesRow_<?php echo $cntr; ?>', 'Publish', '', '#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=3&pg=0&vtyp=1&isMaster=<?php echo $isMaster; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                    <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($canEdtNotices === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" onclick="delOneNotice('allnoticesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Notice" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                        <td class="lovtd"><?php echo $row[0]; ?></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>                     
                            </div>
                        </form>
                        <?php
                    }
                }
            }
        }
    }

function get_Notices($searchFor, $searchIn, $offset, $limit_size, $ordrBy, $sbmtdNoticeCtgry = "") {
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
    if ($isMaster != "1") {
        $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
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
//global $usrID;
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
 /*   function get_Notices($searchFor, $searchIn, $offset, $limit_size, $ordrBy, $sbmtdNoticeCtgry = "") {
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
        if ($isMaster != "1") {
            $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
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

    function get_NoticeTtls($searchFor, $searchIn, $sbmtdNoticeCtgry = "") {
//global $usrID;
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
*/
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

        $extrWhr = "";
        $ordrByCls = "ORDER BY a.article_id DESC";
        if ($artCategory != "" && $artCategory != "All") {
            $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
        }
        if ($isMaster != "1") {
            $extrWhr .= " AND (a.is_published = '1')";
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
                . " ORDER BY a.creation_date DESC, a.posted_cmnt_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
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
                . " ORDER BY a.creation_date DESC, a.posted_cmnt_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

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
    