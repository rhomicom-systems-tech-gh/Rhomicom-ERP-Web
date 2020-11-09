<?php
$canAddLeaveOthrs = test_prmssns($dfltPrvldgs[23], $mdlNm);
$canEdtLeaveOthrs = test_prmssns($dfltPrvldgs[24], $mdlNm);
$canDelLeaveOthrs = test_prmssns($dfltPrvldgs[25], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$srchFor = "";
$srchIn = "";
$RoutingID = -1;
$qNotCnfrmd = false;
$qCnfrmdOnly = false;
$qStrtDte = "01-Jan-1900 00:00:00";
$qEndDte = "31-Dec-4000 23:59:59";
$qLowVal = 0;
$qHighVal = 0;

if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}
if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
if (isset($_POST['qNotCnfrmd'])) {
    $qNotCnfrmd = cleanInputData($_POST['qNotCnfrmd']) === "true" ? true : false;
}
if (isset($_POST['qCnfrmdOnly'])) {
    $qCnfrmdOnly = cleanInputData($_POST['qCnfrmdOnly']) === "true" ? true : false;
}
if (isset($_POST['qLowVal'])) {
    $qLowVal = (float) cleanInputData($_POST['qLowVal']);
}
if (isset($_POST['qHighVal'])) {
    $qHighVal = (float) cleanInputData($_POST['qHighVal']);
}
if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) == 11) {
        $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qStrtDte = "01-Jan-1900 00:00:00";
    }
}
if (isset($_POST['qEndDte'])) {
    $qEndDte = cleanInputData($_POST['qEndDte']);
    if (strlen($qEndDte) == 11) {
        $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
    } else {
        $qEndDte = "31-Dec-4000 23:59:59";
    }
}

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Plan Execution */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelLeaveOthrs) {
                    echo deletePlnExctns($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Absence Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelLeaveOthrs) {
                    echo deleteAbsenseLns($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Accrual Plan */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelLeaveOthrs) {
                    echo deleteAccrualPlns($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Accrual Plan Executions
                header("content-type:application/json");
                //var_dump($_POST);  
                //exit();
                $afftctd = 0;
                $sbmtdExctnID = isset($_POST['sbmtdExctnID']) ? (float) cleanInputData($_POST['sbmtdExctnID']) : -1;
                $sbmtdPlanID = isset($_POST['sbmtdPlanID']) ? (float) cleanInputData($_POST['sbmtdPlanID']) : -1;
                $sbmtdLnkdPrsnID = isset($_POST['lnkdPrsnID']) ? (float) cleanInputData($_POST['lnkdPrsnID']) : -1;
                $rmrksCmnts = isset($_POST['rmrksCmnts']) ? cleanInputData($_POST['rmrksCmnts']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (float) cleanInputData($_POST['shdSbmt']) : 0;
                if ($sbmtdPlanID <= 0 || $sbmtdExctnID <= 0) {
                    $arr_content['percent'] = 100;
                    $arr_content['exctnid'] = $sbmtdExctnID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Invalid Accrual Plan and/or Execution ID!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $errMsg = "";
                if ($sbmtdPlanID > 0 && $sbmtdExctnID > 0 && $sbmtdLnkdPrsnID > 0) {
                    $absPrsnID = (float) getGnrlRecNm("prs.hr_accrual_plan_exctns", "plan_execution_id", "person_id", $sbmtdExctnID);
                    $plnEndDate = getGnrlRecNm("prs.hr_accrual_plan_exctns", "plan_execution_id", "execution_end_dte", $sbmtdExctnID);
                    $plnAddItmID = (float) getGnrlRecNm("prs.hr_accrual_plans", "accrual_plan_id", "lnkd_balnc_add_item_id", $sbmtdPlanID);
                    //Update Remarks and Days Entitled
                    $noDaysEntitld = getLeaveDaysEntld($plnEndDate, $plnAddItmID, $absPrsnID, $orgID);
                    updatePlnExctnsInfo($sbmtdExctnID, $noDaysEntitld, $rmrksCmnts, "Not Submitted");
                    //Save Absence Lines
                    $slctdAbscLines = isset($_POST['slctdAbscLines']) ? cleanInputData($_POST['slctdAbscLines']) : '';
                    if (trim($slctdAbscLines, "|~") != "") {
                        $variousRows = explode("|", trim($slctdAbscLines, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 4) {
                                $absLineID = (float) (cleanInputData1($crntRow[0]));
                                $absStartDte = cleanInputData1($crntRow[1]);
                                $absNoDays = (float) cleanInputData1($crntRow[2]);
                                $absRsn = cleanInputData1($crntRow[3]);
                                $absEndDte = getLeaveEndDate($absStartDte, $absNoDays);
                                if ($absEndDte != "") {
                                    $absEndDte = cnvrtYMDToDMY($absEndDte);
                                }
                                $oldAbsLineID = getPlnExctnLineID($absStartDte, $sbmtdExctnID);
                                if ($oldAbsLineID <= 0 && $absNoDays > 0) {
                                    //Insert
                                    $afftctd += createAbsenseLns($sbmtdExctnID, $absPrsnID, $absStartDte, $absNoDays, $absEndDte, $absRsn,
                                            "Requested");
                                } else if ($oldAbsLineID > 0 && $oldAbsLineID == $absLineID) {
                                    $afftctd += updateAbsenseLns($absLineID, $sbmtdExctnID, $absPrsnID, $absStartDte, $absNoDays,
                                            $absEndDte, $absRsn, "Requested");
                                }
                            }
                        }
                    }
                    if ($shdSbmt > 0 && $afftctd > 0) {
                        if (isPlanExctnVld($sbmtdExctnID)) {
                            $srcDocID = $sbmtdExctnID;
                            $srcDocType = "Leave Requests";
                            $routingID = -1;
                            $inptSlctdRtngs = "";
                            $actionToPrfrm = "Initiate";
                            $errMsg = "<br/><br/>" . leaveReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
                        } else {
                            $errMsg = "<br/><span style=\"color:red;font-weight:bold;\">Error Occured!<br/>Kindly ensure that you have Scheduled exactly the full amount of days you're entitled to!</span><br/>";
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['exctnid'] = (float) $sbmtdExctnID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Leave Plan Execution Successfully Updated!<br/>" . $afftctd . " Absence Line(s) Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['exctnid'] = (float) $sbmtdExctnID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 3) {
                //Save Accrual Plan
                header("content-type:application/json");
                $sbmtdPlanID = isset($_POST['sbmtdPlanID']) ? (float) cleanInputData($_POST['sbmtdPlanID']) : -1;
                $plnNm = isset($_POST['plnNm']) ? cleanInputData($_POST['plnNm']) : '';
                $plnDesc = isset($_POST['plnDesc']) ? cleanInputData($_POST['plnDesc']) : '';
                $plnExctnIntrvl = isset($_POST['plnExctnIntrvl']) ? cleanInputData($_POST['plnExctnIntrvl']) : '';
                $plnStrtDte = isset($_POST['plnStrtDte']) ? cleanInputData($_POST['plnStrtDte']) : '';
                $plnEndDte = isset($_POST['plnEndDte']) ? cleanInputData($_POST['plnEndDte']) : '';
                $lnkdBalsItmID = isset($_POST['lnkdBalsItmID']) ? cleanInputData($_POST['lnkdBalsItmID']) : '';
                $lnkdAddItmID = isset($_POST['lnkdAddItmID']) ? cleanInputData($_POST['lnkdAddItmID']) : '';
                $lnkdSbtrctItmID = isset($_POST['lnkdSbtrctItmID']) ? cleanInputData($_POST['lnkdSbtrctItmID']) : '';
                $canExcdEntlmnt = isset($_POST['canExcdEntlmnt']) ? cleanInputData($_POST['canExcdEntlmnt']) : 'NO';
                $canExcdEntlmntVal = ($canExcdEntlmnt == "NO") ? "0" : "1";
                $oldPlnID = (float) getGnrlRecID("prs.hr_accrual_plans", "accrual_plan_name", "accrual_plan_id", $plnNm, $orgID);
                $errMsg = "";
                if ($plnNm != "" && $plnExctnIntrvl != "" && ($oldPlnID <= 0 || $oldPlnID == $sbmtdPlanID)) {
                    if ($sbmtdPlanID <= 0) {
                        createAccrualPlns($plnNm, $plnDesc, $plnExctnIntrvl, $plnStrtDte, $plnEndDte, $lnkdBalsItmID, $lnkdAddItmID, $orgID,
                                $lnkdSbtrctItmID, $canExcdEntlmntVal);
                        $sbmtdPlanID = (float) getGnrlRecID("prs.hr_accrual_plans", "accrual_plan_name", "accrual_plan_id", $plnNm, $orgID);
                    } else {
                        updateAccrualPlns($sbmtdPlanID, $plnNm, $plnDesc, $plnExctnIntrvl, $plnStrtDte, $plnEndDte, $lnkdBalsItmID,
                                $lnkdAddItmID, $orgID, $lnkdSbtrctItmID, $canExcdEntlmntVal);
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['plnID'] = $sbmtdPlanID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Accrual Plan Successfully Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['plnID'] = $sbmtdPlanID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 40) {
                //Submit Leave Requests to Workflow
                $sbmtdExctnID = isset($_POST['sbmtdExctnID']) ? (float) cleanInputData($_POST['sbmtdExctnID']) : -1;
                $RoutingID = -1;
                if (isset($_POST['RoutingID'])) {
                    $RoutingID = cleanInputData($_POST['RoutingID']);
                }
                if ($RoutingID <= 0) {
                    $RoutingID = getMxRoutingID($sbmtdExctnID, "Leave Requests");
                }
                if ($RoutingID <= 0) {
                    $prsnid = $_SESSION['PRSN_ID'];
                    $srcDocID = get_RqstID($prsnid);
                    $srcDocType = "Leave Requests";
                    $routingID = -1;
                    $inptSlctdRtngs = "";
                    $actionToPrfrm = "Initiate";
                    echo leaveReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
                } else {
                    $actiontyp = isset($_POST['actiontyp']) ? $_POST['actiontyp'] : "";
                    $usrID = $_SESSION['USRID'];
                    $arry1 = explode(";", $actiontyp);
                    for ($r = 0; $r < count($arry1); $r++) {
                        if ($arry1[$r] !== "") {
                            $srcDocID = -1;
                            $srcDocType = "Leave Requests";
                            $inptSlctdRtngs = "";
                            $routingID = $RoutingID;
                            $actionToPrfrm = $arry1[$r];
                            echo leaveReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
                        }
                    }
                }
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li  onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Leave of Absense</span>
				</li>
                               </ul>
                              </div>";
                $total = get_PlnExctnsTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_PlnExctns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte);
                $cntr = 0;

                $reportTitle = "Execute Leave Plan";
                $reportName = "Execute Leave Plan";
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:p_for_prsn_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $prsnid;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?> 
                <div class="row"> 
                    <div  class="col-md-12">                            
                        <ul class="nav nav-tabs" style="margin-top:1px !important;">
                            <li class="active"><a data-toggle="tabajxleave" data-rhodata="&pg=4&vtyp=1" href="#planExctns" id="planExctnstab" style="padding: 3px 10px !important;">Leave Plan Executions</a></li>
                            <li><a data-toggle="tabajxleave" data-rhodata="&pg=4&vtyp=2" href="#planExctnLns" id="planExctnLnstab" style="padding: 3px 10px !important;">Absence Lines</a></li>
                            <?php if ($canAddLeaveOthrs === TRUE) { ?>
                                <li><a data-toggle="tabajxleave" data-rhodata="&pg=4&vtyp=3" href="#plansLv" id="plansLvtab" style="padding: 3px 10px !important;">Leave Plans</a></li>
                            <?php } ?>
                        </ul>                            
                        <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneStoreDetTblSctn"> 
                            <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                <div id="planExctns" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                    <form id='allLeaveRqstsForm' action='' method='post' accept-charset='UTF-8'>
                                        <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                                            <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">                            
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="New Leave Plan Execution" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="padding:2px !important;" style="padding:2px !important;">                                                    
                                                    <img src="cmn_images/add1-64.png" style="height:25px; width:auto; position: relative; vertical-align: middle;">
                                                    Auto-Create&nbsp;
                                                </button>
                                                <?php
                                                if ($canAddLeaveOthrs === true) {
                                                    ?>  
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID; ?>');" data-toggle="tooltip" data-placement="bottom" title="Execute all Leave Plans for All Staff">
                                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Execute Plans&nbsp;
                                                    </button>
                                                <?php }
                                                ?>
                                            </div>
                                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                                <div class="input-group">
                                                    <input class="form-control" id="allLeaveRqstsSrchFor" type = "text" placeholder="Search For" value="<?php
                                                    echo trim(str_replace("%", " ", $srchFor));
                                                    ?>" onkeyup="enterKeyFuncAllLeaveRqsts(event, '', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1')">
                                                    <input id="allLeaveRqstsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLeaveRqsts('clear', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1')">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                    </label>
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLeaveRqsts('', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1');">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </label>
                                                    <span class="input-group-addon">In</span>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allLeaveRqstsSrchIn">
                                                        <?php
                                                        $valslctdArry = array("", "", "", "");
                                                        $srchInsArrys = array("Person Name/Number", "Status",
                                                            "Request Date", "Request Comment");
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allLeaveRqstsDsplySze" style="min-width:65px !important;">                            
                                                        <?php
                                                        $valslctdArry = array("", "", "", "", "", "",
                                                            "", "", "");
                                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                                            500, 1000);
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
                                            <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="allLeaveRqstsStrtDate" name="allLeaveRqstsStrtDate" value="<?php
                                                        echo substr($qStrtDte, 0, 11);
                                                        ?>" placeholder="Start Date">
                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text"  id="allLeaveRqstsEndDate" name="allLeaveRqstsEndDate" value="<?php
                                                        echo substr($qEndDte, 0, 11);
                                                        ?>" placeholder="End Date">
                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>                            
                                            </div>
                                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination" style="margin: 0px !important;">
                                                        <li>
                                                            <a class="rhopagination" href="javascript:getAllLeaveRqsts('previous', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1');" aria-label="Previous">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="rhopagination" href="javascript:getAllLeaveRqsts('next', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1');" aria-label="Next">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </form>
                                    <form id='allLeaveRqstsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-responsive" id="allLeaveRqstsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th style="max-width: 45px;width: 45px;">No.</th>		
                                                            <th style="min-width: 220px;width: 220px;">Full Name (Number)</th>
                                                            <th style="max-width: 100px;width: 100px;">Absence Type</th>
                                                            <th style="max-width: 100px;width: 100px;">From Date</th>
                                                            <th style="max-width: 100px;width: 100px;">To Date</th>
                                                            <!--<th style="min-width: 150px;width: 150px;">Remark/Narration</th>-->
                                                            <th style="text-align:right;max-width: 80px;width: 80px;">Days Entitled</th>
                                                            <th style="text-align:right;max-width: 85px;width: 85px;">Days Breakdown</th>
                                                            <th style="text-align:right;max-width: 85px;width: 85px;">Days Not Requested</th>
                                                            <th style="text-align:right;max-width: 80px;width: 80px;">Status</th>
                                                            <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                                            <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($row = loc_db_fetch_array($result)) {
                                                            /**/
                                                            $cntr += 1;
                                                            $style1 = "text-align:right;font-weight:bold;color:green;";
                                                            if ((float) $row[13] != 0) {
                                                                $style1 = "text-align:right;font-weight:bold;color:red;";
                                                            }
                                                            $style2 = "text-align:right;font-weight:bold;color:red;";
                                                            if ($row[9] == "Initiated") {
                                                                $style2 = "text-align:right;font-weight:bold;color:brown;";
                                                            }
                                                            if ($row[9] == "Approved") {
                                                                $style2 = "text-align:right;font-weight:bold;color:green;";
                                                            }
                                                            ?>
                                                            <tr id="allLeaveRqstsHdrsRow_<?php echo $cntr; ?>">
                                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                <td class="lovtd">
                                                                    <?php echo $row[2]; ?>
                                                                    <input type="hidden" class="form-control" aria-label="..." id="allLeaveRqstsHdrsRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                                                </td>
                                                                <td class="lovtd"><?php echo $row[4]; ?></td>
                                                                <td class="lovtd"><?php echo $row[5]; ?></td>
                                                                <td class="lovtd"><?php echo $row[6]; ?></td>
                                                                <!--<td class="lovtd"><?php echo $row[8]; ?></td>-->
                                                                <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $row[7]; ?></td>
                                                                <td class="lovtd"><?php
                                                                    echo "<span style=\"color:green;font-weight:bold;\">Taken:" . $row[10] . "</span><br/>"
                                                                    . "<span style=\"color:blue;font-weight:bold;\">Scheduled:" . $row[11] . "</span><br/>"
                                                                    . "<span style=\"color:red;font-weight:bold;\">Requested:" . $row[12] . "</span>";
                                                                    ?></td>
                                                                <td class="lovtd" style="<?php echo $style1 ?>"><?php echo $row[13]; ?></td>
                                                                <td class="lovtd" style="<?php echo $style2 ?>"><?php echo $row[9]; ?></td>
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneLeaveRqstsForm(<?php echo $row[0]; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <?php if (($canDelLeaveOthrs === true || ((float) $row[1]) == $prsnid) && $row[9] != "Initiated" && $row[9] != "Approved") { ?>
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delLeaveRqsts('allLeaveRqstsHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Leave Plan Execution">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?>
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
                                <div id="planExctnLns" class="tab-pane fadein hideNotice" style="border:none !important;padding:0px !important;">
                                    &nbsp;
                                </div>
                                <?php if ($canAddLeaveOthrs === TRUE) { ?>
                                    <div id="plansLv" class="tab-pane fadein hideNotice" style="border:none !important;padding:0px !important;">
                                        &nbsp;
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>                     
                </div>                              
                <?php
            } else if ($vwtyp == 1) {
                $total = get_PlnExctnsTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_PlnExctns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte);
                $cntr = 0;
                $reportTitle = "Execute Leave Plan";
                $reportName = "Execute Leave Plan";
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:p_for_prsn_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $paramRepsNVals = $prmID1 . "~" . $prsnid . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form id='allLeaveRqstsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">

                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">                            
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="New Leave Plan Execution" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="padding:2px !important;" style="padding:2px !important;">                                                    
                                <img src="cmn_images/add1-64.png" style="height:25px; width:auto; position: relative; vertical-align: middle;">
                                Auto-Create&nbsp;
                            </button>
                            <?php
                            if ($canAddLeaveOthrs === true) {
                                ?>  
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID; ?>');" data-toggle="tooltip" data-placement="bottom" title="Execute all Leave Plans for All Staff">
                                    <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Execute Plans&nbsp;
                                </button>
                            <?php }
                            ?>
                        </div>
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allLeaveRqstsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllLeaveRqsts(event, '', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1')">
                                <input id="allLeaveRqstsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLeaveRqsts('clear', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLeaveRqsts('', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                                <span class="input-group-addon">In</span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allLeaveRqstsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $srchInsArrys = array("Person Name/Number", "Status",
                                        "Request Date", "Request Comment");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allLeaveRqstsDsplySze" style="min-width:65px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "",
                                        "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                        500, 1000);
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
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="allLeaveRqstsStrtDate" name="allLeaveRqstsStrtDate" value="<?php
                                    echo substr($qStrtDte, 0, 11);
                                    ?>" placeholder="Start Date">
                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text"  id="allLeaveRqstsEndDate" name="allLeaveRqstsEndDate" value="<?php
                                    echo substr($qEndDte, 0, 11);
                                    ?>" placeholder="End Date">
                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllLeaveRqsts('previous', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllLeaveRqsts('next', '#planExctns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </form>
                <form id='allLeaveRqstsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allLeaveRqstsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th style="max-width: 45px;width: 45px;">No.</th>		
                                        <th style="min-width: 220px;width: 220px;">Full Name (Number)</th>
                                        <th style="max-width: 100px;width: 100px;">Absence Type</th>
                                        <th style="max-width: 100px;width: 100px;">From Date</th>
                                        <th style="max-width: 100px;width: 100px;">To Date</th>
                                        <!--<th style="min-width: 150px;width: 150px;">Remark/Narration</th>-->
                                        <th style="text-align:right;max-width: 80px;width: 80px;">Days Entitled</th>
                                        <th style="text-align:right;max-width: 85px;width: 85px;">Days Breakdown</th>
                                        <th style="text-align:right;max-width: 85px;width: 85px;">Days Not Requested</th>
                                        <th style="text-align:right;max-width: 80px;width: 80px;">Status</th>
                                        <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                        <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        $style1 = "text-align:right;font-weight:bold;color:green;";
                                        if ((float) $row[13] != 0) {
                                            $style1 = "text-align:right;font-weight:bold;color:red;";
                                        }
                                        $style2 = "text-align:right;font-weight:bold;color:red;";
                                        if ($row[9] == "Initiated") {
                                            $style2 = "text-align:right;font-weight:bold;color:brown;";
                                        }
                                        if ($row[9] == "Approved") {
                                            $style2 = "text-align:right;font-weight:bold;color:green;";
                                        }
                                        ?>
                                        <tr id="allLeaveRqstsHdrsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php echo $row[2]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allLeaveRqstsHdrsRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd"><?php echo $row[6]; ?></td>
                                            <!--<td class="lovtd"><?php echo $row[8]; ?></td>-->
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $row[7]; ?></td>
                                            <td class="lovtd">
                                                <?php
                                                echo "<span style=\"color:green;font-weight:bold;\">Taken:" . $row[10] . "</span><br/>"
                                                . "<span style=\"color:blue;font-weight:bold;\">Scheduled:" . $row[11] . "</span><br/>"
                                                . "<span style=\"color:red;font-weight:bold;\">Requested:" . $row[12] . "</span>";
                                                ?>
                                            </td>
                                            <td class="lovtd" style="<?php echo $style1 ?>"><?php echo $row[13]; ?></td>
                                            <td class="lovtd" style="<?php echo $style2 ?>"><?php echo $row[9]; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneLeaveRqstsForm(<?php echo $row[0]; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <?php if (($canDelLeaveOthrs === true || ((float) $row[1]) == $prsnid) && $row[9] != "Initiated" && $row[9] != "Approved") { ?>
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delLeaveRqsts('allLeaveRqstsHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Leave Plan Execution">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                <?php } else { ?>
                                                    &nbsp;
                                                <?php } ?>
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
            } else if ($vwtyp == 101) {
                //New Leave Form  
                $sbmtdExctnID = isset($_POST['sbmtdExctnID']) ? (float) cleanInputData($_POST['sbmtdExctnID']) : -1;
                $sbmtdPlanID = isset($_POST['sbmtdPlanID']) ? (float) cleanInputData($_POST['sbmtdPlanID']) : -1;
                $plnNm = "";
                $rmrksCmnts = "";
                $lnkdPrsnID = -1;
                $lnkdPrsnNm = "";
                $exctnStrtDte = "";
                $exctnEndDte = "";
                $daysEntitled = 0;
                $rqstStatus = "";
                $rqstStatusColor = "red";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdExctnID > 0) {
                    $result = get_OnePlnExctnDet($sbmtdExctnID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdExctnID = (float) $row[0];
                        $lnkdPrsnID = (float) $row[1];
                        $lnkdPrsnNm = $row[2];
                        $exctnStrtDte = $row[5];
                        $exctnEndDte = $row[6];
                        $daysEntitled = (float) $row[7];
                        $rqstStatus = $row[9];
                        $sbmtdPlanID = (float) $row[3];
                        $plnNm = $row[4];
                        $rmrksCmnts = $row[8];

                        if ($rqstStatus == "Not Submitted" || $rqstStatus == "Withdrawn" || $rqstStatus == "Rejected") {
                            $rqstStatusColor = "red";
                        } else if ($rqstStatus != "Approved") {
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                            $rqstStatusColor = "brown";
                        } else {
                            $rqstStatusColor = "green";
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                        }
                    }
                } else if ($canAddLeaveOthrs === FALSE) {
                    $lnkdPrsnID = $prsnid;
                    $lnkdPrsnNm = getPrsnFullNm($lnkdPrsnID);
                }
                /* if ($sbmtdExctnID <= 0) {
                  $sbmtdExctnID = getNewPlnExctnID();
                  } */
                $canEdtLve = $lnkdPrsnID == $prsnid || $canEdtLeaveOthrs === TRUE;
                $canAddLve = $lnkdPrsnID == $prsnid || $canAddLeaveOthrs === TRUE;
                $canDelLve = $lnkdPrsnID == $prsnid || $canDelLeaveOthrs === TRUE;

                $routingID = getMxRoutingID($sbmtdExctnID, "Leave Requests");
                $reportTitle = "Leave Profile Report";
                $reportName = "Leave Profile Report";
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:pln_exctn_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdExctnID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id='leavePlnExctnForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-6" style="padding:0px 15px 0px 0px !important;">
                            <div class="" style="padding:0px 0px 0px 0px;float:left !important;">                               
                                <button type="button" class="btn btn-default btn-sm" style="" id="myLeaveStatusBtn"><span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstStatusColor; ?>;font-weight: bold;"><?php echo $rqstStatus; ?></span></button>
                                <?php //if ($rqstStatus == "Approved") {      ?>
                                <button type="button" class="btn btn-default" style=""  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    Print Leave Profile
                                </button>
                                <?php //}       ?>
                            </div>
                        </div> 
                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                            <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                <?php
                                if (!($rqstStatus == "Not Submitted" || $rqstStatus == "Withdrawn" || $rqstStatus == "Rejected") && ($rqstStatus != "Approved")) {
                                    ?>                                    
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="actOnLeaveRqst('Withdraw');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="actOnLeaveRqst('Approve');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Approve&nbsp;</button>                                                                                                        
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Leave Approval Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>                                
                                    <?php
                                } else if ($rqstStatus == "Approved") {
                                    ?>                                    
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="actOnLeaveRqst('Withdraw');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Leave Approval Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Approval Progress History">Progress&nbsp;</button>
                                <?php }
                                ?>
                            </div>
                        </div>                    
                    </div>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="sbmtdExctnID" class="control-label">Plan Execution No.:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <input type="number" name="sbmtdExctnID" id="sbmtdExctnID" class="form-control" value="<?php echo $sbmtdExctnID; ?>" style="width:100% !important;" readonly="true">
                                            <?php } else { ?>
                                                <span><?php echo $sbmtdExctnID; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="plnNm" class="control-label">Leave Plan Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <input type="text" name="plnNm" id="plnNm" class="form-control" value="<?php echo $plnNm; ?>" style="width:100% !important;" readonly="true">
                                                <input type="hidden" name="sbmtdPlanID" id="sbmtdPlanID" class="form-control" value="<?php echo $sbmtdPlanID; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $plnNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="lnkdPrsnNm" class="control-label">Person:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php /* if ($canEdtLeaveOthrs === true) { ?>                                   
                                              <div class="input-group" style="width:100% !important;">
                                              <input type="text" name="lnkdPrsnNm" id="lnkdPrsnNm" class="form-control" value="<?php echo $lnkdPrsnNm; ?>" readonly="true" style="width:100% !important;">
                                              <input type="hidden" name="lnkdPrsnID" id="lnkdPrsnID" class="form-control" value="<?php echo $lnkdPrsnID; ?>">
                                              <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Persons', '', '', '', 'radio', true, '', 'lnkdPrsnID', 'lnkdPrsnNm', 'clear', 0, '', function () {
                                              var aa112 = 1;
                                              });">
                                              <span class="glyphicon glyphicon-th-list"></span>
                                              </label>
                                              </div>
                                              <?php } else { ?>
                                              <?php } */ ?>
                                            <input type="hidden" name="lnkdPrsnID" id="lnkdPrsnID" class="form-control" value="<?php echo $lnkdPrsnID; ?>">
                                            <span><?php echo $lnkdPrsnNm; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="daysEntitled" class="control-label">Days Entitled:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <input type="number" name="daysEntitled" id="daysEntitled" class="form-control" value="<?php echo $daysEntitled; ?>" style="width:100% !important;" readonly="true">
                                            <?php } else { ?>
                                                <span><?php echo $daysEntitled; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="rqstStatus" class="control-label">Status:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <input type="text" name="rqstStatus" id="rqstStatus" class="form-control" value="<?php echo $rqstStatus; ?>" style="width:100% !important;" readonly="true">
                                            <?php } else { ?>
                                                <span><?php echo $rqstStatus; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-6" style="padding: 1px !important;">
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="exctnStrtDte">Start Date:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                    <input class="form-control" size="16" type="text" id="exctnStrtDte" name="exctnStrtDte" value="<?php echo $exctnStrtDte; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $exctnStrtDte; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="exctnEndDte">End Date:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                    <input class="form-control" size="16" type="text" id="exctnEndDte" name="exctnEndDte" value="<?php echo $exctnEndDte; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $exctnEndDte; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="rmrksCmnts" class="control-label">Remarks/ Comments:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdtLve === true) { ?>
                                                <textarea rows="5" name="rmrksCmnts" id="rmrksCmnts" class="form-control"><?php echo $rmrksCmnts; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $rmrksCmnts; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>
                        <div class="row">
                            <div class="col-md-12">                                                                       
                                <div id="plnExctnAbsncs" class="" style="">
                                    <?php
                                    $nwRowHtml = urlencode("<tr id=\"onePlnExctnAbsncsRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span>New</span></td>"
                                            . "<td class=\"lovtd\">  
                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\" style=\"width:100%;\">
                                                                <input class=\"form-control rqrdFld\" size=\"16\" type=\"text\" id=\"onePlnExctnAbsncsRow_WWW123WWW_StrtDte\" value=\"\">
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>                                                                
                                                </td>
                                                <td class=\"lovtd\">
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"onePlnExctnAbsncsRow_WWW123WWW_NoOfDays\" value=\"0\" style=\"width:100%;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"onePlnExctnAbsncsRow_WWW123WWW_LineID\" value=\"-1\">
                                                </td>
                                                <td class=\"lovtd\">
                                                            <input class=\"form-control\" size=\"16\" type=\"text\" id=\"onePlnExctnAbsncsRow_WWW123WWW_EndDte\" value=\"\" readonly=\"true\" style=\"width:100%;\">                                                                                                                                           
                                                </td>
                                                <td class=\"lovtd\">
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"onePlnExctnAbsncsRow_WWW123WWW_AbsRsn\" value=\"\" style=\"width:100%;\">
                                                </td>
                                                <td class=\"lovtd\">
                                                        <span style=\"font-weight:bold;color:red;\">Not Submitted</span>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delLeaveRqstsLines('onePlnExctnAbsncsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Absence\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                                                </tr>");
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if ($canEdtLve === true) { ?>
                                                <button id="addNwVaultBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('onePlnExctnAbsncsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Absence Record">
                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    Add Absence
                                                </button>
                                            <?php } ?>
                                            <button id="refreshVltBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneLeaveRqstsForm(<?php echo $sbmtdExctnID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Leave Plan Execution">
                                                <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Refresh
                                            </button>
                                        </div>                                            
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered table-responsive" id="onePlnExctnAbsncsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="max-width: 45px;width: 45px;">No.</th>
                                                        <th style="max-width: 180px;width: 180px;">Absence Start Date</th>
                                                        <th style="text-align:right;min-width: 60px;width: 60px;">No. Days</th>
                                                        <th style="max-width: 150px;width: 150px;">Absence End Date</th>
                                                        <th style="min-width: 200px;">Remark/Narration</th>
                                                        <th style="max-width: 100px;width: 100px;">Status</th>
                                                        <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $rslt = get_OnePlnExctnAbsLns($sbmtdExctnID);
                                                    $cntrUsr = 0;
                                                    while ($rwLn = loc_db_fetch_array($rslt)) {
                                                        $cntrUsr++;
                                                        $noOfDays = $rwLn[6];
                                                        $absncStartDte = $rwLn[4];
                                                        $absncEndDte = $rwLn[5];
                                                        $absStatus = $rwLn[8];
                                                        $absReason = $rwLn[7];
                                                        $style1 = "text-align:right;font-weight:bold;color:red;";
                                                        if ($rwLn[8] == "Scheduled") {
                                                            $style1 = "text-align:right;font-weight:bold;color:blue;";
                                                        }
                                                        if ($rwLn[8] == "Taken") {
                                                            $style1 = "text-align:right;font-weight:bold;color:green;";
                                                        }
                                                        ?>
                                                        <tr id="onePlnExctnAbsncsRow_<?php echo $cntrUsr; ?>">                                    
                                                            <td class="lovtd"><span><?php echo ($cntrUsr); ?></span></td>
                                                            <td class="lovtd">                                                                            
                                                                <?php if ($canEdtLve === true) { ?>
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                                        <input class="form-control rqrdFld" size="16" type="text" id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_StrtDte" value="<?php echo $absncStartDte; ?>" style="width:100%;">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>  
                                                                <?php } else { ?>
                                                                    <span><?php echo $absncStartDte; ?></span>
                                                                <?php } ?> 
                                                            </td>
                                                            <td class="lovtd">
                                                                <?php if ($canEdtLve === true) { ?>
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_NoOfDays" value="<?php echo $noOfDays; ?>" style="width:100%;">

                                                                <?php } else { ?>
                                                                    <span><?php echo $noOfDays; ?></span>
                                                                <?php } ?> 
                                                            </td>
                                                            <td class="lovtd">
                                                                <?php if ($canEdtLve === true) { ?>
                                                                    <input class="form-control" size="16" type="text" id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_EndDte" value="<?php echo $absncEndDte; ?>" readonly="true" style="width:100%;">                                                                                                                                           

                                                                <?php } else { ?>
                                                                    <span><?php echo $absncEndDte; ?></span>
                                                                <?php } ?>  
                                                            </td>
                                                            <td class="lovtd">
                                                                <?php if ($canEdtLve === true) { ?>
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_AbsRsn" value="<?php echo $absReason; ?>" style="width:100%;">                                                                       

                                                                <?php } else { ?>
                                                                    <span><?php echo $absReason; ?></span>
                                                                <?php } ?> 
                                                            </td>
                                                            <td class="lovtd"> 
                                                                <input type="hidden" class="form-control" aria-label="..." id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $rwLn[0]; ?>">                                                               
                                                                <span style="<?php echo $style1; ?>"><?php echo $absStatus; ?></span>
                                                            </td>
                                                            <?php if ($canEdtLve === true) { ?>
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delLeaveRqstsLines('onePlnExctnAbsncsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Absence">
                                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtLve === true && ($rqstStatus == "Not Submitted" || $rqstStatus == "Withdrawn" || $rqstStatus == "Rejected")) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveLeaveRqstsForm(0);">Save Changes</button>
                                <button type="button" class="btn btn-primary" onclick="saveLeaveRqstsForm(1);">Submit</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 2) {
                $total = get_AbsenseLnsTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AbsenseLns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte);
                $cntr = 0;
                ?>
                <form id='allAbsencesForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allAbsencesSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllAbsences(event, '', '#planExctnLns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allAbsencesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAbsences('clear', '#planExctnLns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAbsences('', '#planExctnLns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <span class="input-group-addon">In</span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAbsencesSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $srchInsArrys = array("Person Name/Number", "Status",
                                        "Request Date", "Request Comment");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAbsencesDsplySze" style="min-width:65px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "",
                                        "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                        500, 1000);
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
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="allAbsencesStrtDate" name="allAbsencesStrtDate" value="<?php
                                    echo substr($qStrtDte, 0, 11);
                                    ?>" placeholder="Start Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text"  id="allAbsencesEndDate" name="allAbsencesEndDate" value="<?php
                                    echo substr($qEndDte, 0, 11);
                                    ?>" placeholder="End Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllAbsences('previous', '#planExctnLns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllAbsences('next', '#planExctnLns', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </form>
                <form id='allAbsencesHdrsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allAbsencesHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th style="max-width: 45px;width: 45px;">No.</th>		
                                        <th style="min-width: 220px;width: 220px;">Full Name (Number)</th>
                                        <th style="max-width: 100px;width: 100px;">Absence Type</th>
                                        <th style="max-width: 100px;width: 100px;">Absence Start Date</th>
                                        <th style="max-width: 100px;width: 100px;">Absence End Date</th>
                                        <th style="text-align:right;max-width: 80px;width: 80px;">No. of Days</th>
                                        <th style="min-width: 150px;width: 150px;">Remark/Narration</th>
                                        <th style="max-width: 80px;width: 80px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        $style1 = "text-align:right;font-weight:bold;color:red;";
                                        if ($row[9] == "Scheduled") {
                                            $style1 = "text-align:right;font-weight:bold;color:blue;";
                                        }
                                        if ($row[9] == "Taken") {
                                            $style1 = "text-align:right;font-weight:bold;color:green;";
                                        }
                                        ?>
                                        <tr id="allAbsencesHdrsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php echo $row[3]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allAbsencesHdrsRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[9]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $row[6]; ?></td>
                                            <td class="lovtd"><?php echo $row[7]; ?></td>
                                            <td class="lovtd" style="<?php echo $style1; ?>"><?php echo $row[8]; ?></td>
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
            } else if ($vwtyp == 3) {
                if ($canAddLeaveOthrs === FALSE) {
                    return "";
                }
                $total = get_AccrualPlnsTtl($srchFor, $srchIn, $orgID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AccrualPlns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                $cntr = 0;
                ?>
                <form id='allAccrlPlnsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">                            
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="New Accrual Plan" onclick="getOneAccrlPlnsForm(-1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">                                                    
                                <img src="cmn_images/add1-64.png" style="height:25px; width:auto; position: relative; vertical-align: middle;">
                                New Accrual Plan&nbsp;&nbsp;
                            </button>
                        </div>
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allAccrlPlnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllAccrlPlns(event, '', '#plansLv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allAccrlPlnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAccrlPlns('clear', '#plansLv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAccrlPlns('', '#plansLv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <span class="input-group-addon">In</span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAccrlPlnsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Plan Name", "Plan Description");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAccrlPlnsDsplySze" style="min-width:65px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "",
                                        "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                        500, 1000);
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
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllAccrlPlns('previous', '#plansLv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllAccrlPlns('next', '#plansLv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </form>
                <form id='allAccrlPlnsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allAccrlPlnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th style="max-width: 45px;width: 45px;">No.</th>		
                                        <th style="min-width: 320px;width: 320px;">Plan Name (Description)</th>
                                        <th style="max-width: 100px;width: 100px;">Execution Intervals</th>
                                        <th style="max-width: 100px;width: 100px;">From Date</th>
                                        <th style="max-width: 100px;width: 100px;">To Date</th>
                                        <th style="text-align:left;max-width: 120px;width: 120px;display:none;">Linked Balance Item</th>
                                        <th style="text-align:left;max-width: 120px;width: 120px;">Linked Addition Item</th>
                                        <th style="text-align:left;max-width: 120px;width: 120px;display:none;">Linked Subtraction Item</th>
                                        <th style="text-align:left;max-width: 30px;width: 30px;">&nbsp;</th>
                                        <th style="text-align:left;max-width: 30px;width: 30px;">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cntr = 0;
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        ?>
                                        <tr id="allAccrlPlnsHdrsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php echo $row[1]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allAccrlPlnsHdrsRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd" style="display:none;"><?php echo $row[7]; ?></td>
                                            <td class="lovtd"><?php echo $row[9]; ?></td>
                                            <td class="lovtd" style="display:none;"><?php echo $row[11]; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneAccrlPlnsForm(<?php echo $row[0]; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <?php if (($canDelLeaveOthrs === true || ((float) $row[1]) == $prsnid) && $row[9] != "Initiated" && $row[9] != "Approved") { ?>
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccrlPlns('allAccrlPlnsHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Leave Plan Execution">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                <?php } else { ?>
                                                    &nbsp;
                                                <?php } ?>
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
            } else if ($vwtyp == 301) {
                //New Accrual Plan Form  
                if ($canAddLeaveOthrs === FALSE) {
                    restricted();
                    exit();
                }
                $sbmtdPlanID = isset($_POST['sbmtdPlanID']) ? (float) cleanInputData($_POST['sbmtdPlanID']) : -1;
                $plnNm = "";
                $plnDesc = "";
                $plnExctnIntrvl = "";
                $plnStrtDte = "";
                $plnEndDte = "";
                $lnkdBalsItmID = -1;
                $lnkdAddItmID = -1;
                $lnkdSbtrctItmID = -1;
                $lnkdBalsItmNm = "";
                $lnkdAddItmNm = "";
                $lnkdSbtrctItmNm = "";
                $canExcdEntlmnt = "0";
                if ($sbmtdPlanID > 0) {
                    $result = get_OneAccrualPlnDet($sbmtdPlanID);
                    while ($row = loc_db_fetch_array($result)) {
                        $plnNm = $row[1];
                        $plnDesc = $row[2];
                        $plnExctnIntrvl = $row[3];
                        $plnStrtDte = $row[4];
                        $plnEndDte = $row[5];
                        $lnkdBalsItmID = (float) $row[6];
                        $lnkdAddItmID = (float) $row[8];
                        $lnkdSbtrctItmID = (float) $row[10];
                        $lnkdBalsItmNm = $row[7];
                        $lnkdAddItmNm = $row[9];
                        $lnkdSbtrctItmNm = $row[11];
                        $canExcdEntlmnt = $row[17];
                    }
                }
                $canEdtLve = $canEdtLeaveOthrs;
                $canAddLve = $canAddLeaveOthrs;
                $canDelLve = $canDelLeaveOthrs;
                ?>
                <form class="form-horizontal" id='oneAccrlPlnsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="plnNm" class="control-label">Plan Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <input type="text" name="plnNm" id="plnNm" class="form-control rqrdFld" value="<?php echo $plnNm; ?>" style="width:100% !important;">
                                                <input type="hidden" name="sbmtdPlanID" id="sbmtdPlanID" class="form-control" value="<?php echo $sbmtdPlanID; ?>" style="width:100% !important;" readonly="true">
                                            <?php } else { ?>
                                                <span><?php echo $plnNm; ?></span>
                                                <input type="hidden" name="sbmtdPlanID" id="sbmtdPlanID" class="form-control" value="<?php echo $sbmtdPlanID; ?>" style="width:100% !important;" readonly="true">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="plnDesc" class="control-label">Plan Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdtLve === true) { ?>
                                                <textarea rows="3" name="plnDesc" id="plnDesc" class="form-control rqrdFld"><?php echo $plnDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $plnDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="plnExctnIntrvl" class="control-label">Execution Interval:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?>
                                                <select class="form-control" id="plnExctnIntrvl">                                                        
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "", "");
                                                    $valuesArrys = array("Ad hoc", "LifeTime", "Yearly", "Half-Yearly",
                                                        "Quarterly", "Monthly", "Semi-Monthly", "Weekly", "Daily");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($plnExctnIntrvl == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $plnExctnIntrvl; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-6" style="padding: 1px !important;">
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="plnStrtDte">Start Date:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                    <input class="form-control rqrdFld" size="16" type="text" id="plnStrtDte" name="plnStrtDte" value="<?php echo $plnStrtDte; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $plnStrtDte; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="plnEndDte">End Date:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?> 
                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                    <input class="form-control" size="16" type="text" id="plnEndDte" name="plnEndDte" value="<?php echo $plnEndDte; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $plnEndDte; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="form-group" style="display:none;">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="lnkdBalsItmNm" class="control-label">Linked Balance Item:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="lnkdBalsItmNm" id="lnkdBalsItmNm" class="form-control rqrdFld" value="<?php echo $lnkdBalsItmNm; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="lnkdBalsItmID" id="lnkdBalsItmID" class="form-control" value="<?php echo $lnkdBalsItmID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Balance Items', '', '', '', 'radio', true, '', 'lnkdBalsItmID', 'lnkdBalsItmNm', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <input type="hidden" name="lnkdBalsItmID" id="lnkdBalsItmID" class="form-control" value="<?php echo $lnkdBalsItmID; ?>">
                                                <span><?php echo $lnkdBalsItmNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>                              
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="lnkdAddItmNm" class="control-label">Balance Add Item:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="lnkdAddItmNm" id="lnkdAddItmNm" class="form-control rqrdFld" value="<?php echo $lnkdAddItmNm; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="lnkdPrsnID" id="lnkdAddItmID" class="form-control" value="<?php echo $lnkdAddItmID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Non-Balance Items', '', '', '', 'radio', true, '', 'lnkdAddItmID', 'lnkdAddItmNm', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <input type="hidden" name="lnkdAddItmID" id="lnkdAddItmID" class="form-control" value="<?php echo $lnkdAddItmID; ?>">
                                                <span><?php echo $lnkdAddItmNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="form-group" style="display:none;">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="lnkdSbtrctItmNm" class="control-label">Balance Subtract Item:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtLve === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="lnkdSbtrctItmNm" id="lnkdSbtrctItmNm" class="form-control rqrdFld" value="<?php echo $lnkdSbtrctItmNm; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="lnkdSbtrctItmID" id="lnkdSbtrctItmID" class="form-control" value="<?php echo $lnkdSbtrctItmID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Non-Balance Items', '', '', '', 'radio', true, '', 'lnkdSbtrctItmID', 'lnkdSbtrctItmNm', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <input type="hidden" name="lnkdSbtrctItmID" id="lnkdSbtrctItmID" class="form-control" value="<?php echo $lnkdSbtrctItmID; ?>">
                                                <span><?php echo $lnkdSbtrctItmNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-md-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="canExcdEntlmnt" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtLve === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($canExcdEntlmnt == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="canExcdEntlmnt" id="canExcdEntlmnt" <?php echo $isChkd . " " . $isRdOnly; ?>>Can Days Requested Exceed Entitlement?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>                        
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">  
                            <?php if ($sbmtdPlanID > 0) { ?>
                                <button id="refreshAcrlPlnBtn" type="button" class="btn btn-default" onclick="getOneAccrlPlnsForm(<?php echo $sbmtdPlanID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Leave Plan Execution">
                                    <img src="cmn_images/refresh.bmp" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                    Refresh
                                </button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtLve === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveAccrlPlnsForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            }
        }
    }
}    