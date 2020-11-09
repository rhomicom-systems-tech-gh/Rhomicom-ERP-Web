<?php
$canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[7], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Doc Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteEvent($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Metric */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteEvntMtrc($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Price */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePriceMtrc($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Default Account */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteCostAcnt($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Event Setup Form Saving
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                if ($usrTrnsCode == "") {
                    $usrTrnsCode = "XX";
                }
                $dte = date('ymd');
                $sbmtdAttnEvntStpID = isset($_POST['sbmtdAttnEvntStpID']) ? (float) cleanInputData($_POST['sbmtdAttnEvntStpID']) : -1;
                $attnEvntStpGrpID = isset($_POST['attnEvntStpGrpID']) ? (float) cleanInputData($_POST['attnEvntStpGrpID']) : -1;
                $attnEvntStpVnuID = isset($_POST['attnEvntStpVnuID']) ? (float) cleanInputData($_POST['attnEvntStpVnuID']) : -1;
                $attnEvntStpPrsnID = isset($_POST['attnEvntStpPrsnID']) ? (float) cleanInputData($_POST['attnEvntStpPrsnID']) : -1;
                $attnEvntStpFirmID = isset($_POST['attnEvntStpFirmID']) ? (float) cleanInputData($_POST['attnEvntStpFirmID']) : -1;
                $attnEvntStpFirmSiteID = isset($_POST['attnEvntStpFirmSiteID']) ? (float) cleanInputData($_POST['attnEvntStpFirmSiteID']) : -1;
                $attnEvntStpName = isset($_POST['attnEvntStpName']) ? cleanInputData($_POST['attnEvntStpName']) : "";
                $attnEvntStpDesc = isset($_POST['attnEvntStpDesc']) ? cleanInputData($_POST['attnEvntStpDesc']) : "";
                $attnEvntStpMtrcLOV = isset($_POST['attnEvntStpMtrcLOV']) ? cleanInputData($_POST['attnEvntStpMtrcLOV']) : "";
                $attnEvntStpScoresLOV = isset($_POST['attnEvntStpScoresLOV']) ? cleanInputData($_POST['attnEvntStpScoresLOV']) : "";
                $attnEvntStpSltPrty = isset($_POST['attnEvntStpSltPrty']) ? (float) cleanInputData($_POST['attnEvntStpSltPrty']) : 0;
                $attnEvntStpIsEnbld = (isset($_POST['attnEvntStpIsEnbld']) ? cleanInputData($_POST['attnEvntStpIsEnbld']) : "NO") === "YES" ? true : false;
                $attnEvntStpEvntTypVal = isset($_POST['attnEvntStpEvntTypVal']) ? cleanInputData($_POST['attnEvntStpEvntTypVal']) : "";
                $attnEvntStpClsfctn = isset($_POST['attnEvntStpClsfctn']) ? cleanInputData($_POST['attnEvntStpClsfctn']) : "";
                $attnEvntStpTtlSsnMins = isset($_POST['attnEvntStpTtlSsnMins']) ? (float) cleanInputData($_POST['attnEvntStpTtlSsnMins']) : 0;
                $attnEvntStpCntnsSsnMins = isset($_POST['attnEvntStpCntnsSsnMins']) ? (float) cleanInputData($_POST['attnEvntStpCntnsSsnMins']) : 0;
                $attnEvntStpGrpTyp = isset($_POST['attnEvntStpGrpTyp']) ? cleanInputData($_POST['attnEvntStpGrpTyp']) : "";
                $attnEvntStpGrpName = isset($_POST['attnEvntStpGrpName']) ? cleanInputData($_POST['attnEvntStpGrpName']) : "";
                $attnEvntStpVnuNm = isset($_POST['attnEvntStpVnuNm']) ? cleanInputData($_POST['attnEvntStpVnuNm']) : "";
                $attnEvntStpPrsnNm = isset($_POST['attnEvntStpPrsnNm']) ? cleanInputData($_POST['attnEvntStpPrsnNm']) : "";
                $attnEvntStpFirmNm = isset($_POST['attnEvntStpFirmNm']) ? cleanInputData($_POST['attnEvntStpFirmNm']) : "";

                $slctdResultMetrics = isset($_POST['slctdResultMetrics']) ? cleanInputData($_POST['slctdResultMetrics']) : "";
                $slctdPriceCtgrys = isset($_POST['slctdPriceCtgrys']) ? cleanInputData($_POST['slctdPriceCtgrys']) : "";
                $slctdDfltAccnts = isset($_POST['slctdDfltAccnts']) ? cleanInputData($_POST['slctdDfltAccnts']) : "";

                $exitErrMsg = "";
                if ($attnEvntStpName == "") {
                    $exitErrMsg .= "Please Event Name!<br/>";
                }
                if ($attnEvntStpEvntTypVal == "") {
                    $exitErrMsg .= "Event Type cannot be empty!<br/>";
                }
                if ($attnEvntStpClsfctn == "") {
                    $exitErrMsg .= "Event Classification cannot be empty!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAttnEvntStpID'] = $sbmtdAttnEvntStpID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $affctd = 0;
                $afftctd3 = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                $oldID = getEventID($attnEvntStpName, $orgID);
                if (($oldID <= 0 || $oldID == $sbmtdAttnEvntStpID)) {
                    if ($sbmtdAttnEvntStpID <= 0) {
                        $affctd += createEvent($orgID, $attnEvntStpName, $attnEvntStpDesc, $attnEvntStpEvntTypVal,
                                $attnEvntStpIsEnbld, $attnEvntStpPrsnID, $attnEvntStpGrpTyp, $attnEvntStpGrpID,
                                $attnEvntStpTtlSsnMins, $attnEvntStpCntnsSsnMins, $attnEvntStpSltPrty,
                                $attnEvntStpClsfctn, $attnEvntStpVnuID, $attnEvntStpGrpName, $attnEvntStpMtrcLOV,
                                $attnEvntStpScoresLOV, $attnEvntStpFirmID, $attnEvntStpFirmSiteID);
                        $sbmtdAttnEvntStpID = getEventID($attnEvntStpName, $orgID);
                    } else {
                        $affctd += updateEvent($sbmtdAttnEvntStpID, $attnEvntStpName, $attnEvntStpDesc, $attnEvntStpEvntTypVal,
                                $attnEvntStpIsEnbld, $attnEvntStpPrsnID, $attnEvntStpGrpTyp, $attnEvntStpGrpID,
                                $attnEvntStpTtlSsnMins, $attnEvntStpCntnsSsnMins, $attnEvntStpSltPrty,
                                $attnEvntStpClsfctn, $attnEvntStpVnuID, $attnEvntStpGrpName, $attnEvntStpMtrcLOV,
                                $attnEvntStpScoresLOV, $attnEvntStpFirmID, $attnEvntStpFirmSiteID);
                    }


                    if (trim($slctdResultMetrics, "|~") != "") {
                        $variousRows = explode("|", trim($slctdResultMetrics, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 6) {
                                $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_LineDesc = cleanInputData1($crntRow[1]);
                                $ln_RsltType = cleanInputData1($crntRow[2]);
                                $ln_Cmmnt = cleanInputData1($crntRow[3]);
                                $ln_Query = cleanInputData1($crntRow[4]);
                                $ln_IsEnbld = (cleanInputData1($crntRow[5]) == "YES") ? TRUE : FALSE;
                                if (trim($ln_Query) === "") {
                                    $ln_Query = "Select 0";
                                }
                                $errMsg = "";
                                if ($ln_LineDesc === "" || $ln_Cmmnt === "") {
                                    $errMsg = "Row " . ($y + 1) . ":- Metric Name and Description are all required Fields!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_TrnsLnID <= 0) {
                                        $ln_TrnsLnID = getNewMtrcLnID();
                                        $afftctd1 += createEvntMtrc($ln_TrnsLnID, $ln_LineDesc, $ln_Cmmnt, $ln_RsltType, $ln_IsEnbld, $ln_Query, $sbmtdAttnEvntStpID);
                                    } else if ($ln_TrnsLnID > 0) {
                                        $afftctd1 += updateEvntMtrc($ln_TrnsLnID, $ln_LineDesc, $ln_Cmmnt, $ln_RsltType, $ln_IsEnbld, $ln_Query, $sbmtdAttnEvntStpID);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }
                    if (trim($slctdPriceCtgrys, "|~") != "") {
                        $variousRows = explode("|", trim($slctdPriceCtgrys, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 7) {
                                $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_CtgryNm = cleanInputData1($crntRow[1]);
                                $ln_ItemID = (float) cleanInputData1($crntRow[2]);
                                $ln_ItemNm = cleanInputData1($crntRow[3]);
                                $ln_PrcLsTx = (float) cleanInputData1($crntRow[4]);
                                $ln_SellPrc = (float) cleanInputData1($crntRow[5]);
                                $ln_IsEnbld = (cleanInputData1($crntRow[6]) == "YES") ? TRUE : FALSE;
                                $errMsg = "";
                                if ($ln_CtgryNm === "" || $ln_ItemID <= 0) {
                                    $errMsg = "Row " . ($y + 1) . ":- Category Name and Linked Inventory Item are all required Fields!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_TrnsLnID <= 0) {
                                        $ln_TrnsLnID = getNewPriceLnID();
                                        $afftctd2 += createEvntPrice($ln_TrnsLnID, $ln_CtgryNm, $ln_IsEnbld, $ln_ItemID, $sbmtdAttnEvntStpID);
                                    } else if ($ln_TrnsLnID > 0) {
                                        $afftctd2 += updateEvntPrice($ln_TrnsLnID, $ln_CtgryNm, $ln_IsEnbld, $ln_ItemID, $sbmtdAttnEvntStpID);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }
                    if (trim($slctdDfltAccnts, "|~") != "") {
                        $variousRows = explode("|", trim($slctdDfltAccnts, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 8) {
                                $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_CtgryNm = cleanInputData1($crntRow[1]);
                                $ln_IncrsDcrs1 = cleanInputData1($crntRow[2]);
                                $ln_AcntID1 = (float) cleanInputData1($crntRow[3]);
                                $ln_AcntNm1 = cleanInputData1($crntRow[4]);
                                $ln_IncrsDcrs2 = cleanInputData1($crntRow[5]);
                                $ln_AcntID2 = (float) cleanInputData1($crntRow[6]);
                                $ln_AcntNm2 = cleanInputData1($crntRow[7]);
                                $errMsg = "";
                                if ($ln_CtgryNm === "" || $ln_AcntID1 <= 0 || $ln_AcntID2 <= 0) {
                                    $errMsg = "Row " . ($y + 1) . ":- Category Name and Accounts are all required Fields!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_TrnsLnID <= 0) {
                                        $ln_TrnsLnID = getNewCstAcntLnID();
                                        $afftctd3 += createEvntCstAcnt($ln_TrnsLnID, $ln_CtgryNm, $ln_IncrsDcrs1, $ln_AcntID1, $ln_IncrsDcrs2, $ln_AcntID2,
                                                $sbmtdAttnEvntStpID);
                                    } else if ($ln_TrnsLnID > 0) {
                                        $afftctd3 += updateEvntCstAcnt($ln_TrnsLnID, $ln_CtgryNm, $ln_IncrsDcrs1, $ln_AcntID1, $ln_IncrsDcrs2, $ln_AcntID2,
                                                $sbmtdAttnEvntStpID);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }

                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Event Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span" . $afftctd1 . " Event Metric(s) Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span" . $afftctd2 . " Event Price(s) Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span" . $afftctd3 . " Event Account(s) Successfully Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Event Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span" . $afftctd1 . " Event Metric(s) Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span" . $afftctd2 . " Event Price(s) Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span" . $afftctd3 . " Event Account(s) Successfully Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAttnEvntStpID'] = $sbmtdAttnEvntStpID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Event Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAttnEvntStpID'] = $sbmtdAttnEvntStpID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                $attnEvntStpID = getEventID("Normal Daily Visits", $orgID);
                if ($attnEvntStpID <= 0) {
                    createEvent($orgID, "Normal Daily Visits", "Normal Daily Visits", "R",
                            true, -1, "Everyone", -1,
                            0, 0, 0,
                            "Daily Visitor", -1, "", "Attendance HeadCount Metrics",
                            "", -1, -1);
                }
                $sbmtdInptCstmrID = isset($_POST['sbmtdInptCstmrID']) ? (float) $_POST['sbmtdInptCstmrID'] : -1;
                $sbmtdRegisterID = isset($_POST['sbmtdRegisterID']) ? (float) $_POST['sbmtdRegisterID'] : -1;
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Activities/Events</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Customer';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                $qShwUnpaidOnly = false;
                if (isset($_POST['qShwUnpaidOnly'])) {
                    $qShwUnpaidOnly = cleanInputData($_POST['qShwUnpaidOnly']) === "true" ? true : false;
                }
                $qShwSelfOnly = $vwOnlySelf;
                if (isset($_POST['qShwSelfOnly'])) {
                    $qShwSelfOnly = cleanInputData($_POST['qShwSelfOnly']) === "true" ? true : false;
                }
                $qShwMyBranch = true;
                if (isset($_POST['qShwMyBranch'])) {
                    $qShwMyBranch = cleanInputData($_POST['qShwMyBranch']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_Events($srchFor, $srchIn, $orgID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Basic_Events($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
                ?> 
                <form id='attnEvntStpForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">ALL ACTIVITIES/EVENTS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-3";
                            $colClassType3 = "col-md-10";

                            if ($canAdd === true) {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-3";
                                $colClassType3 = "col-md-7";
                                ?>   
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">                      
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAttnEvntStpForm(-1, 3, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Add New Check-In">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        NEW EVENT/ACTIVITY
                                    </button> 
                                </div>  
                            <?php } ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attnEvntStpSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttnEvntStp(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="attnEvntStpPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdScmRtrnSrcDocID" type = "hidden" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnEvntStp('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnEvntStp('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnEvntStpSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array(
                                            "Event Name", "Event Description");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnEvntStpDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "",
                                            "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30,
                                            50, 100, 500, 1000, 1000000);
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getAttnEvntStp('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAttnEvntStp('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attnEvntStpHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:35px;width:35px;">No.</th>
                                            <?php if ($canEdt === true) { ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            <?php } ?>
                                            <th>Event/Activity Name</th>
                                            <th>Event/Activity Description</th>
                                            <th>Event Type</th>
                                            <th>Classification</th>
                                            <th>Allowed Group Type/Name</th>
                                            <th style="max-width:75px;width:75px;">Enabled?</th>
                                            <th style="max-width:30px;width:30px;">...</th>
                                            <?php if ($canDel === true) { ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            <?php } ?>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                                ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;
                                            $trnsLnID = (float) $row[0];
                                            $trnsLnEvntNm = $row[1];
                                            $trnsLnEvntDesc = $row[2];
                                            $trnsLnEvntType = $row[3];
                                            $trnsLnEvntClsfctn = $row[4];
                                            $trnsLnEvntAllwdGrpTyp = $row[5];
                                            $trnsLnEvntAllwdGrpID = (float) $row[6];
                                            $trnsLnEvntAllwdGrpNm = $row[7];
                                            $trnsLnEvntIsEnbld = $row[8];
                                            ?>
                                            <tr id="attnEvntStpHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                <?php if ($canEdt === true) { ?>                                
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Event" 
                                                                onclick="getOneAttnEvntStpForm(<?php echo $row[0]; ?>, 3, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>  
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trnsLnID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_TrnsLnNm" value="<?php echo $trnsLnEvntNm; ?>">
                                                    <?php echo $trnsLnEvntNm; ?>
                                                </td>
                                                <td class="lovtd"><?php echo $trnsLnEvntDesc; ?></td>
                                                <td class="lovtd"><?php echo $trnsLnEvntType; ?></td>
                                                <td class="lovtd"><?php echo $trnsLnEvntClsfctn; ?></td>
                                                <td class="lovtd"><?php
                                                    echo str_replace(" - " . $trnsLnEvntAllwdGrpTyp, "",
                                                            $trnsLnEvntAllwdGrpTyp . " - " . $trnsLnEvntAllwdGrpNm);
                                                    ?></td>
                                                <td class="lovtd" style="text-align:center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($trnsLnEvntIsEnbld == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <div class="form-group form-group-sm">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_IsEnbld" name="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td> 
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Capture Event Activites and Results" 
                                                            onclick="getOneAttnActvtyRsltsForm(<?php echo $row[0]; ?>, 0, 'ShowDialog', '<?php echo $row[14]; ?>', 'EVENT_CHECK-IN');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>  
                                                <?php
                                                if ($canDel === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delAttnEvntStp('attnEvntStpHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <input type="hidden" id="attnEvntStpHdrsRow<?php echo $cntr; ?>_HdrID" name="attnEvntStpHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                    </td>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|attn.attn_attendance_events|event_id"),
                                                                        $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                <?php
            } else if ($vwtyp == 3) {
                //New Event Setup Form
                //var_dump($_POST);sbmtdAttnEventID
                $sbmtdAttnEvntStpID = isset($_POST['sbmtdAttnEvntStpID']) ? (float) cleanInputData($_POST['sbmtdAttnEvntStpID']) : -1;
                if (!$canAdd || ($sbmtdAttnEvntStpID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }

                $attnEvntStpName = "";
                $attnEvntStpDesc = "";
                $attnEvntStpMtrcLOV = "";
                $attnEvntStpScoresLOV = "";
                $attnEvntStpGrpTyp = "";
                $attnEvntStpGrpID = -1;
                $attnEvntStpGrpName = "";
                $attnEvntStpEvntTypVal = "";
                $attnEvntStpEvntType = "";
                $attnEvntStpClsfctn = 0;
                $attnEvntStpTtlSsnMins = 0;
                $attnEvntStpSltPrty = 0;
                $attnEvntStpCntnsSsnMins = 0;
                $attnEvntStpIsEnbld = "0";
                $attnEvntStpVnuID = -1;
                $attnEvntStpVnuNm = "";
                $attnEvntStpPrsnID = -1;
                $attnEvntStpPrsnNm = "";
                $attnEvntStpFirmID = -1;
                $attnEvntStpFirmSiteID = -1;
                $attnEvntStpFirmNm = "";

                if ($sbmtdAttnEvntStpID > 0) {
                    $result = get_One_EvntDet($sbmtdAttnEvntStpID);
                    if ($row = loc_db_fetch_array($result)) {
                        $attnEvntStpName = $row[1];
                        $attnEvntStpDesc = $row[2];
                        $attnEvntStpMtrcLOV = $row[17];
                        $attnEvntStpScoresLOV = $row[18];
                        $attnEvntStpGrpTyp = $row[6];
                        $attnEvntStpGrpID = (float) $row[7];
                        $attnEvntStpGrpName = $row[8];
                        $attnEvntStpEvntTypVal = $row[3];
                        $attnEvntStpEvntType = $row[4];
                        $attnEvntStpClsfctn = $row[5];
                        $attnEvntStpTtlSsnMins = (float) $row[12];
                        $attnEvntStpSltPrty = (float) $row[14];
                        $attnEvntStpCntnsSsnMins = (float) $row[13];
                        $attnEvntStpIsEnbld = $row[9];
                        $attnEvntStpVnuID = (float) $row[15];
                        $attnEvntStpVnuNm = $row[16];
                        $attnEvntStpPrsnID = (float) $row[10];
                        $attnEvntStpPrsnNm = $row[11];
                        $attnEvntStpFirmID = (float) $row[19];
                        $attnEvntStpFirmSiteID = (float) $row[20];
                        $attnEvntStpFirmNm = $row[22];
                    }
                }
                $reportName = getEnbldPssblValDesc("Sales Invoice", getLovID("Document Custom Print Process Names"));
                $reportTitle = $reportName;
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdAttnEvntStpID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);

                $reportName1 = "Sales Invoice-POS";
                $reportTitle1 = "Payment Receipt";
                $rptID1 = getRptID($reportName1);
                $prmID11 = getParamIDUseSQLRep("{:invoice_id}", $rptID1);
                $prmID12 = getParamIDUseSQLRep("{:documentTitle}", $rptID1);
                $paramRepsNVals1 = $prmID11 . "~" . $trnsID . "|" . $prmID12 . "~" . $reportTitle1 . "|-130~" . $reportTitle1 . "|-190~PDF";
                $paramStr1 = urlencode($paramRepsNVals1);
                $mkReadOnly = "";
                ?>
                <form class="form-horizontal" id="oneAttnEvntStpEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Event Name:</label>
                                    </div>
                                    <div class="col-md-8" style="padding:0px 15px 0px 15px;">
                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdAttnEvntStpID" name="sbmtdAttnEvntStpID" value="<?php echo $sbmtdAttnEvntStpID; ?>" readonly="true">
                                        <input type="hidden" id="attnEvntStpGrpID" value="<?php echo $attnEvntStpGrpID; ?>">
                                        <input type="hidden" id="attnEvntStpVnuID" value="<?php echo $attnEvntStpVnuID; ?>">
                                        <input type="hidden" id="attnEvntStpPrsnID" value="<?php echo $attnEvntStpPrsnID; ?>">
                                        <input type="hidden" id="attnEvntStpFirmID" value="<?php echo $attnEvntStpFirmID; ?>">
                                        <input type="hidden" id="attnEvntStpFirmSiteID" value="<?php echo $attnEvntStpFirmSiteID; ?>">
                                        <input type="text" class="form-control" aria-label="..." id="attnEvntStpName" name="attnEvntStpName" value="<?php echo $attnEvntStpName; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Event Description:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group"  style="width:100%;">
                                            <textarea class="form-control" rows="3" cols="20" id="attnEvntStpDesc" name="attnEvntStpDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;"><?php echo $attnEvntStpDesc; ?></textarea>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('attnEvntStpDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="attnEvntStpMtrcLOV" class="control-label col-md-4">Attnd. Metric LOV:</label>
                                    <div  class="col-md-8" style="padding:0px 15px 0px 15px;">
                                        <div class="input-group" style="width:100% !important;">
                                            <input type="text" class="form-control" aria-label="..." id="attnEvntStpMtrcLOV" name="attnEvntStpMtrcLOV" value="<?php echo $attnEvntStpMtrcLOV; ?>" readonly="true" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', '', 'attnEvntStpMtrcLOV', 'clear', 1, '', function () {});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="attnEvntStpScoresLOV" class="control-label col-md-4">Scores Labels LOV:</label>
                                    <div  class="col-md-8" style="padding:0px 15px 0px 15px;">
                                        <div class="input-group" style="width:100% !important;">
                                            <input type="text" class="form-control" aria-label="..." id="attnEvntStpScoresLOV" name="attnEvntStpScoresLOV" value="<?php echo $attnEvntStpScoresLOV; ?>" readonly="true" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', '', 'attnEvntStpScoresLOV', 'clear', 1, '', function () {});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Slot Priority:</label>
                                    </div>
                                    <div class="col-md-4" style="padding:0px 15px 0px 15px;">
                                        <input type="number" class="form-control" aria-label="..." id="attnEvntStpSltPrty" name="attnEvntStpSltPrty" value="<?php echo $attnEvntStpSltPrty; ?>" <?php echo $mkReadOnly; ?>>
                                    </div>
                                    <div class="col-md-4" style="padding: 0px 15px 0px 15px !important;">
                                        <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                            <label for="attnEvntStpIsEnbld" class="control-label">
                                                <?php
                                                $isChkd = "";
                                                $isRdOnly = "disabled=\"true\"";
                                                if ($canEdt === true) {
                                                    $isRdOnly = "";
                                                }
                                                if ($attnEvntStpIsEnbld == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" name="attnEvntStpIsEnbld" id="attnEvntStpIsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Event Type:</label>
                                    </div>
                                    <div class="col-md-8" style="padding:0px 15px 0px 15px;">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="attnEvntStpEvntTypVal">
                                            <?php
                                            $valslctdArry = array("", "");
                                            $srchInsArrys = array("R", "NR");
                                            $srchInsArrys1 = array(" R:RECURRING", "NR:NON-RECURRING");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($attnEvntStpEvntTypVal == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys1[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="attnEvntStpSrvcTyp" class="control-label col-md-4">Classification:</label> 
                                    <div  class="col-md-8" style="padding:0px 15px 0px 15px;">
                                        <div class="input-group" style="width:100% !important;">
                                            <input type="text" class="form-control" aria-label="..." id="attnEvntStpClsfctn" name="attnEvntStpClsfctn" value="<?php echo $attnEvntStpClsfctn; ?>" readonly="true" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Classifications', '', '', '', 'radio', true, '', 'attnEvntStpClsfctn', '', 'clear', 1, '', function () {});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <div class="col-md-7">
                                        <label style="margin-bottom:0px !important;">Total Time Table Session (mins):</label>
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 15px;">
                                        <input type="number" class="form-control" aria-label="..." id="attnEvntStpTtlSsnMins" name="attnEvntStpTtlSsnMins" value="<?php echo $attnEvntStpTtlSsnMins; ?>" <?php echo $mkReadOnly; ?>>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <div class="col-md-7">
                                        <label style="margin-bottom:0px !important;">Highest  Continuous Session (mins):</label>
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 15px;">
                                        <input type="number" class="form-control" aria-label="..." id="attnEvntStpCntnsSsnMins" name="attnEvntStpCntnsSsnMins" value="<?php echo $attnEvntStpCntnsSsnMins; ?>" <?php echo $mkReadOnly; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="attnEvntStpGrpTyp" class="control-label">Allowed Group Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <?php if ($canEdt === true) { ?>
                                            <select class="form-control" id="attnEvntStpGrpTyp" onchange="grpTypAttnChangeV();">                                                        
                                                <?php
                                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                                $valuesArrys = array("Everyone", "Divisions/Groups",
                                                    "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                for ($z = 0; $z < count($valuesArrys); $z++) {
                                                    if ($attnEvntStpGrpTyp == $valuesArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            <span><?php echo $attnEvntStpGrpTyp; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="attnEvntStpGrpName" class="control-label">Allowed Group Name:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <?php if ($canEdt === true) { ?>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="attnEvntStpGrpName" value="<?php echo $attnEvntStpGrpName; ?>" readonly="">
                                                <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnEvntStpGrpID', 'attnEvntStpGrpName', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        <?php } else { ?>
                                            <span><?php echo $attnEvntStpGrpName; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="attnEvntStpVnuNm" class="control-label col-md-4" style="padding: 0px 0px 0px 0px !important;">Preferred Venue:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="attnEvntStpVnuNm" name="attnEvntStpVnuNm" value="<?php echo $attnEvntStpVnuNm; ?>" readonly="true">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Venues', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnEvntStpVnuID', 'attnEvntStpVnuNm', 'clear', 1, '');" data-toggle="tooltip" title="Existing Venue">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label for="attnEvntStpPrsnNm" class="control-label col-md-4" style="padding: 0px 0px 0px 0px !important;">Host Person:</label>
                                    <div  class="col-md-8"> 
                                        <div class="input-group" style="width:100% !important;">
                                            <input type="text" class="form-control" aria-label="..." id="attnEvntStpPrsnNm" name="attnEvntStpPrsnNm" value="<?php echo $attnEvntStpPrsnNm; ?>" readonly="true" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnEvntStpPrsnID', 'attnEvntStpPrsnNm', 'clear', 1, '', function () {});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="attnEvntStpFirmNm" class="control-label col-md-4" style="padding: 0px 0px 0px 0px !important;">Hosting Firm:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="attnEvntStpFirmNm" name="attnEvntStpFirmNm" value="<?php echo $attnEvntStpFirmNm; ?>" readonly="true">
                                            <input type="hidden" id="attnEvntStpCstmrClsfctn" value="Customer/Supplier">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Supplier', 'ShowDialog', function () {}, 'attnEvntStpFirmID');" data-toggle="tooltip" title="Create/Edit Customer/Supplier">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'attnEvntStpCstmrClsfctn', 'radio', true, '', 'attnEvntStpFirmID', 'attnEvntStpFirmNm', 'clear', 1, '', function () {
                                                                        getAttnSpplrInfo();
                                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxevntstp" data-rhodata="" href="#evntStpDetLines" id="evntStpDetLinestab">Result Metrics</a></li>
                                    <li class=""><a data-toggle="tabajxevntstp" data-rhodata="" href="#evntStpExtraInfo" id="evntStpExtraInfotab">Pricing Categories</a></li>
                                    <li class=""><a data-toggle="tabajxevntstp" data-rhodata="" href="#evntStpDfltAcnts" id="evntStpDfltAcntstab">Default Accounts</a></li>
                                </ul>  
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                $edtPriceRdOnly = "readonly=\"true\"";
                                                $nwRowHtml33 = "<tr id=\"oneAttnEvntStpSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAttnEvntStpSmryLinesTable tr').index(this));\">                                    
                                                                        <td class=\"lovtd\"><span>New</span></td>                                              
                                                                        <td class=\"lovtd\"  style=\"\">  
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnEvntStpSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneAttnEvntStpSmryRow_WWW123WWW_LineDesc\" name=\"oneAttnEvntStpSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnEvntStpSmryRow_WWW123WWW_LineDesc', 'oneAttnEvntStpSmryLinesTable', 'jbDetDesc');\">
                                                                        </td> 
                                                                        <td class=\"lovtd\">
                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnEvntStpSmryRow_WWW123WWW_RsltType\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("NUMBER", "TEXT");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml33 .= "</select>
                                                                        </td> 
                                                                        <td class=\"lovtd\" style=\"\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneAttnEvntStpSmryRow_WWW123WWW_Cmmnt\" name=\"oneAttnEvntStpSmryRow_WWW123WWW_Cmmnt\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnEvntStpSmryRow_WWW123WWW_Cmmnt', 'oneAttnEvntStpSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnEvntStpSmryRow_WWW123WWW_Query\" name=\"oneAttnEvntStpSmryRow_WWW123WWW_Query\" value=\"Select 0\" style=\"width:100% !important;text-align: left;\">                                                    
                                                                        </td>
                                                                        <td class=\"lovtd\" style=\"text-align:center;\">
                                                                            <div class=\"form-group form-group-sm\">
                                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                    <label class=\"form-check-label\">
                                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAttnEvntStpSmryRow_WWW123WWW_IsEnbld\" name=\"oneAttnEvntStpSmryRow_WWW123WWW_IsEnbld\">
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td> 
                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttnEvntStpDetLn('oneAttnEvntStpSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Result Metric\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                                    </tr>";
                                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                                $nwRowHtml32 = "<tr id=\"oneAttnEvntStpPricesRow__WWW123WWW>\" onclick=\"$('#allOtherInputData99').val($('#oneAttnEvntStpPricesTable tr').index(this));\">                                    
                                                                        <td class=\"lovtd\"><span>New</span></td> 
                                                                        <td class=\"lovtd\" style=\"\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnEvntStpPricesRow_WWW123WWW>_TrnsLnID\" value=\"-1\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneAttnEvntStpPricesRow_WWW123WWW>_CtgryNm\" name=\"oneAttnEvntStpPricesRow_WWW123WWW>_CtgryNm\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnEvntStpPricesRow_WWW123WWW_CtgryNm', 'oneAttnEvntStpPricesTable', 'jbDetAccRate');\" style=\"width:100% !important;\">                                                    
                                                                        </td>     
                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneAttnEvntStpPricesRow_WWW123WWW_ItemNm\" name=\"oneAttnEvntStpPricesRow_WWW123WWW_ItemNm\" value=\"\" style=\"width:100% !important;\"  readonly=\"true\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnEvntStpPricesRow_WWW123WWW_ItemID\" value=\"-1\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAttnEvntStpPricesRow_WWW123WWW_ItemID', 'oneAttnEvntStpPricesRow_WWW123WWW_ItemNm', 'clear', 0, '', function () {});\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                            </div>
                                                                        </td> 
                                                                        <td class=\"lovtd\" style=\"text-align:right;\">
                                                                            <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAttnEvntStpPricesRow_WWW123WWW_PrcLsTx\" name=\"oneAttnEvntStpPricesRow_WWW123WWW_PrcLsTx\" value=\"\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">   
                                                                        </td>  
                                                                        <td class=\"lovtd\" style=\"text-align:right;\">
                                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnEvntStpPricesRow_WWW123WWW_SellPrc\" name=\"oneAttnEvntStpPricesRow_WWW123WWW_SellPrc\" value=\"\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">   
                                                                        </td>
                                                                        <td class=\"lovtd\" style=\"text-align:center;\">
                                                                            <div class=\"form-group form-group-sm\">
                                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                    <label class=\"form-check-label\">
                                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAttnEvntStpPricesRow_WWW123WWW_IsEnbld\" name=\"oneAttnEvntStpPricesRow_WWW123WWW_IsEnbld\">
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td> 
                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttnEvntStpPriceLn('oneAttnEvntStpPricesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Price Line\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                                    </tr>";
                                                $nwRowHtml32 = urlencode($nwRowHtml32);
                                                $nwRowHtml31 = "<tr id=\"oneAttnEvntStpDfltAcntsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAttnEvntStpDfltAcntsTable tr').index(this));\">                                    
                                                                    <td class=\"lovtd\"><span>New</span></td>     
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneAttnEvntStpDfltAcntsRow_WWW123WWW_CtgryNm\" name=\"oneAttnEvntStpDfltAcntsRow_WWW123WWW_CtgryNm\" value=\"\" style=\"width:100% !important;\" readonly=\"true\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnEvntStpPricesRow_WWW123WWW_TrnsLnID\" value=\"-1\">
                                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Cost Categories', '', '', '', 'radio', true, '', 'oneAttnEvntStpDfltAcntsRow_WWW123WWW_CtgryNm', '', 'clear', 0, '', function () {});\">
                                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td> 
                                                                    <td class=\"lovtd\">
                                                                        <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnEvntStpSmryRow_WWW123WWW_IncrsDcrs1\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml31 .= "<option value=\"" . $srchInsArrys[$z] . "\">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml31 .= "</select>
                                                                    </td>  
                                                                    <td class=\"lovtd\" style=\"text-align:center;\">
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntNm1\" name=\"oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntNm1\" value=\"\" style=\"width:100% !important;\" readonly=\"true\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntID1\" value=\"-1\">
                                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntID1', 'oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntNm1', 'clear', 0, '', function () {});\">
                                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td> 
                                                                    <td class=\"lovtd\">
                                                                        <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnEvntStpSmryRow_WWW123WWW_IncrsDcrs2\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml31 .= "<option value=\"" . $srchInsArrys[$z] . "\">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml31 .= "</select>
                                                                    </td>  
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntNm2\" name=\"oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntNm2\" value=\"\" style=\"width:100% !important;\" readonly=\"true\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntID2\" value=\"-1\">
                                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntID2', 'oneAttnEvntStpDfltAcntsRow_WWW123WWW_AcntNm2', 'clear', 0, '', function () {});\">
                                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttnEvntStpDfltAcntsLn('oneAttnEvntStpDfltAcntsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Default Account\">
                                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                        </button>
                                                                    </td>
                                                                </tr>";
                                                $nwRowHtml31 = urlencode($nwRowHtml31);
                                                ?> 
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <button id="addNwAttnEvntStpMtrcsBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAttnEvntStpRows('oneAttnEvntStpSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Event Result Metric">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add Result Metric
                                                            </button>
                                                            <button id="addNwAttnEvntStpPricesBtn" type="button" class="btn btn-default hideNotice" style="margin-bottom: 1px;height:30px;" onclick="insertNewAttnEvntStpRows('oneAttnEvntStpPricesTable', 0, '<?php echo $nwRowHtml32; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Event Price Category">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add Price Category
                                                            </button>
                                                            <button id="addNwAttnEvntStpAccntsBtn" type="button" class="btn btn-default hideNotice" style="margin-bottom: 1px;height:30px;" onclick="insertNewAttnEvntStpRows('oneAttnEvntStpDfltAcntsTable', 0, '<?php echo $nwRowHtml31; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Event Default Account">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add Default Account
                                                            </button>                                 
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAttnEvntStpDocsForm(<?php echo $sbmtdAttnEvntStpID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAttnEvntStpForm(<?php echo $sbmtdAttnEvntStpID; ?>, 3, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 10px 0px 10px !important;"> 
                                                    </div> 
                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                            <?php if ($canEdt === true) { ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAttnEvntStpForm();"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                            <?php } ?>
                                                        </div>
                                                    </div>                    
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAttnEvntStpLnsTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="evntStpDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneAttnEvntStpSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="min-width:250px;">Result Metric Name/Description</th>
                                                                <th style="min-width:120px;">Result Type</th>
                                                                <th style="min-width:120px;">Comment</th>
                                                                <th style="min-width:250px;">Attached Result Query</th>
                                                                <th style="max-width:70px;width:70px;text-align: center;">Enabled?</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_One_EvntMtrcs($sbmtdAttnEvntStpID);
                                                            $ttlTrsctnEntrdAmnt = 0;
                                                            $ttlRows = loc_db_num_rows($resultRw);
                                                            if ($ttlRows > 0) {
                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $trsctnLnID = (float) $rowRw[0];
                                                                    $trsctnLnNameDesc = $rowRw[1];
                                                                    $trsctnLnRsltTyp = $rowRw[2];
                                                                    $trsctnLnCmmnt = $rowRw[3];
                                                                    $trsctnLnQuery = $rowRw[4];
                                                                    $trsctnLnIsEnbld = $rowRw[5];
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneAttnEvntStpSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAttnEvntStpSmryLinesTable tr').index(this));">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                        <td class="lovtd"  style="">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnNameDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAttnEvntStpSmryRow<?php echo $cntr; ?>_LineDesc', 'oneAttnEvntStpSmryLinesTable', 'jbDetDesc');">
                                                                            <?php } else {
                                                                                ?>
                                                                                <span><?php echo $trsctnLnNameDesc; ?></span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td> 
                                                                        <td class="lovtd">
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_RsltType" style="width:100% !important;">
                                                                                <?php
                                                                                $valslctdArry = array("", "");
                                                                                $srchInsArrys = array("NUMBER", "TEXT");
                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                    if ($trsctnLnRsltTyp == $srchInsArrys[$z]) {
                                                                                        $valslctdArry[$z] = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td> 
                                                                        <td class="lovtd" style="">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_Cmmnt" name="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_Cmmnt" value="<?php
                                                                            echo $trsctnLnCmmnt;
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnEvntStpSmryRow<?php echo $cntr; ?>_Cmmnt', 'oneAttnEvntStpSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>                                                    
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_Query" name="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_Query" value="<?php echo $trsctnLnQuery; ?>" style="width:100% !important;text-align: left;">                                                    
                                                                        </td>
                                                                        <td class="lovtd" style="text-align:center;">
                                                                            <?php
                                                                            $isChkd = "";
                                                                            if ($trsctnLnIsEnbld == "1") {
                                                                                $isChkd = "checked=\"true\"";
                                                                            }
                                                                            ?>
                                                                            <div class="form-group form-group-sm">
                                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" class="form-check-input" id="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_IsEnbld" name="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td> 
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnEvntStpDetLn('oneAttnEvntStpSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="evntStpExtraInfo" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row"  style="padding:0px 15px 0px 15px;">
                                                <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneAttnEvntStpPricesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="min-width:250px;">Category Name</th>
                                                                <th style="min-width:120px;">Liked Sales Item</th>
                                                                <th style="min-width:120px;">Price Less Tax & Charges</th>
                                                                <th style="min-width:120px;">Selling Price</th>
                                                                <th style="max-width:70px;width:70px;text-align: center;">Enabled?</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_One_EvntPrices($sbmtdAttnEvntStpID);
                                                            $ttlTrsctnEntrdAmnt = 0;
                                                            $ttlRows = loc_db_num_rows($resultRw);
                                                            if ($ttlRows > 0) {
                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $trsctnLnID = (float) $rowRw[0];
                                                                    $trsctnLnCtgryNm = $rowRw[1];
                                                                    $trsctnLnItmID = (float) $rowRw[2];
                                                                    $trsctnLnItmNm = $rowRw[3];
                                                                    $trsctnLnIsEnbld = $rowRw[4];
                                                                    $trsctnLnPrcLsTx = (float) $rowRw[5];
                                                                    $trsctnLnSellPrc = (float) $rowRw[6];
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneAttnEvntStpPricesRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAttnEvntStpPricesTable tr').index(this));">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td> 
                                                                        <td class="lovtd" style="">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_CtgryNm" name="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_CtgryNm" value="<?php
                                                                            echo $trsctnLnCtgryNm;
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnEvntStpPricesRow<?php echo $cntr; ?>_CtgryNm', 'oneAttnEvntStpPricesTable', 'jbDetAccRate');" style="width:100% !important;" <?php echo $mkReadOnly; ?>>                                                    
                                                                        </td>     
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_ItemNm" name="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_ItemNm" value="<?php echo $trsctnLnItmNm; ?>" style="width:100% !important;"  readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_ItemID" value="<?php echo $trsctnLnItmID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnItmID; ?>', 'oneAttnEvntStpPricesRow<?php echo $cntr; ?>_ItemID', 'oneAttnEvntStpPricesRow<?php echo $cntr; ?>_ItemNm', 'clear', 0, '', function () {});">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </td> 
                                                                        <td class="lovtd" style="text-align:right;">
                                                                            <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_PrcLsTx" name="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_PrcLsTx" value="<?php
                                                                            echo number_format($trsctnLnPrcLsTx, 2);
                                                                            ?>" style="width:100% !important;text-align: right;" readonly="true">   
                                                                        </td>  
                                                                        <td class="lovtd" style="text-align:right;">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_SellPrc" name="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_SellPrc" value="<?php
                                                                            echo number_format($trsctnLnSellPrc, 2);
                                                                            ?>" style="width:100% !important;text-align: right;" readonly="true">   
                                                                        </td>
                                                                        <td class="lovtd" style="text-align:center;">
                                                                            <?php
                                                                            $isChkd = "";
                                                                            if ($trsctnLnIsEnbld == "1") {
                                                                                $isChkd = "checked=\"true\"";
                                                                            }
                                                                            ?>
                                                                            <div class="form-group form-group-sm">
                                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" class="form-check-input" id="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_IsEnbld" name="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td> 
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnEvntStpPriceLn('oneAttnEvntStpPricesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Price Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="evntStpDfltAcnts" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row"  style="padding:0px 15px 0px 15px;">
                                                <table class="table table-striped table-bordered table-responsive" id="oneAttnEvntStpDfltAcntsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th style="max-width:30px;width:30px;">No.</th>
                                                            <th style="min-width:250px;">Category Name</th>
                                                            <th style="min-width:120px;">Increase/Decrease</th>
                                                            <th style="min-width:120px;">Charge Account</th>
                                                            <th style="min-width:120px;">Increase/Decrease</th>
                                                            <th style="min-width:120px;">Balancing Account</th>
                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>   
                                                        <?php
                                                        $cntr = 0;
                                                        $resultRw = get_One_EvntCostAcnts($sbmtdAttnEvntStpID);
                                                        $ttlTrsctnEntrdAmnt = 0;
                                                        $ttlRows = loc_db_num_rows($resultRw);
                                                        if ($ttlRows > 0) {
                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                $trsctnLnID = (float) $rowRw[0];
                                                                $trsctnLnCtgryNm = $rowRw[1];
                                                                $trsctnLnIncrDcrs1 = $rowRw[2];
                                                                $trsctnLnAcntID1 = (float) $rowRw[3];
                                                                $trsctnLnAcntNm1 = $rowRw[4];
                                                                $trsctnLnIncrDcrs2 = $rowRw[5];
                                                                $trsctnLnAcntID2 = (float) $rowRw[6];
                                                                $trsctnLnAcntNm2 = $rowRw[7];
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneAttnEvntStpDfltAcntsRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAttnEvntStpDfltAcntsTable tr').index(this));">                                    
                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>     
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_CtgryNm" name="oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_CtgryNm" value="<?php echo $trsctnLnCtgryNm; ?>" style="width:100% !important;" readonly="true">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAttnEvntStpPricesRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Cost Categories', '', '', '', 'radio', true, '<?php echo $trsctnLnItmID; ?>', 'oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_CtgryNm', '', 'clear', 0, '', function () {});">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td> 
                                                                    <td class="lovtd">
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
                                                                            <?php
                                                                            $valslctdArry = array("", "");
                                                                            $srchInsArrys = array("Increase", "Decrease");
                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                if ($trsctnLnIncrDcrs1 == $srchInsArrys[$z]) {
                                                                                    $valslctdArry[$z] = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>  
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntNm1" name="oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntNm1" value="<?php echo $trsctnLnAcntNm1; ?>" style="width:100% !important;" readonly="true">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntID1" value="<?php echo $trsctnLnAcntID1; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnAcntID1; ?>', 'oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntID1', 'oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntNm1', 'clear', 0, '', function () {});">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td> 
                                                                    <td class="lovtd">
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnEvntStpSmryRow<?php echo $cntr; ?>_IncrsDcrs2" style="width:100% !important;">
                                                                            <?php
                                                                            $valslctdArry = array("", "");
                                                                            $srchInsArrys = array("Increase", "Decrease");
                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                if ($trsctnLnRsltTyp == $srchInsArrys[$z]) {
                                                                                    $valslctdArry[$z] = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>  
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntNm2" name="oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntNm2" value="<?php echo $trsctnLnAcntNm2; ?>" style="width:100% !important;" readonly="true">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntID2" value="<?php echo $trsctnLnAcntID2; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnAcntID2; ?>', 'oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntID2', 'oneAttnEvntStpDfltAcntsRow<?php echo $cntr; ?>_AcntNm2', 'clear', 0, '', function () {});">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnEvntStpDfltAcntsLn('oneAttnEvntStpDfltAcntsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Default Account">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php
            } else if ($vwtyp == 4) {
                //Get Selected Register ID Information
                header("content-type:application/json");
                $sbmtdRegisterID = isset($_POST['sbmtdRegisterID']) ? (float) cleanInputData($_POST['sbmtdRegisterID']) : -1;

                $sbmtdTmTblID = (float) getGnrlRecNm("attn.attn_attendance_recs_hdr", "recs_hdr_id", "time_table_id", $sbmtdRegisterID);
                $sbmtdTmTblDetID = (float) getGnrlRecNm("attn.attn_attendance_recs_hdr", "recs_hdr_id", "time_table_det_id",
                                $sbmtdRegisterID);
                $sbmtdEvntID = (float) getGnrlRecNm("attn.attn_time_table_details", "time_table_det_id", "event_id", $sbmtdTmTblDetID);

                $sbmtdStrdDte = getGnrlRecNm("attn.attn_attendance_recs_hdr", "recs_hdr_id",
                        "to_char(to_timestamp(event_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')", $sbmtdRegisterID);
                $sbmtdEndDte = getGnrlRecNm("attn.attn_attendance_recs_hdr", "recs_hdr_id",
                        "to_char(to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')", $sbmtdRegisterID);
                $srvcTypeTextBox = getGnrlRecNm("attn.attn_time_table_details", "time_table_det_id",
                        "'EVENT: ' || COALESCE(attn.get_event_name(event_id),'') || ' VENUE: ' || COALESCE(attn.get_venue_name(assgnd_venue_id),'') || ' HOST: ' || COALESCE(prs.get_prsn_name(assgnd_host_id),'') ",
                        $sbmtdTmTblDetID);
                $attnEvntStpRmNum = "";
                $attnEvntStpRmID = -1;
                $brghtStr = "";
                $isDynmyc = TRUE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Event Price Categories"), $isDynmyc, $sbmtdEvntID, "",
                        "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $attnEvntStpRmID = (float) $titleRow[0];
                    if ($attnEvntStpRmID > 0) {
                        $attnEvntStpRmNum = getGnrlRecNm("attn.event_price_categories", "price_ctgry_id", "price_category", $attnEvntStpRmID);
                        break;
                    }
                }
                $arr_content['srvcTypeIDTextBox'] = $sbmtdTmTblDetID;
                $arr_content['srvcTypeTextBox'] = $srvcTypeTextBox;
                $arr_content['sbmtdStrdDte'] = $sbmtdStrdDte;
                $arr_content['sbmtdEndDte'] = $sbmtdEndDte;
                $arr_content['sbmtdTmTblID'] = $sbmtdTmTblID;
                $arr_content['sbmtdEvntID'] = $sbmtdEvntID;
                $arr_content['attnEvntStpRmID'] = $attnEvntStpRmID;
                $arr_content['attnEvntStpRmNum'] = $attnEvntStpRmNum;
                //var_dump(json_encode($arr_content));
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 5) {
                
            }
        }
    }
}
?>