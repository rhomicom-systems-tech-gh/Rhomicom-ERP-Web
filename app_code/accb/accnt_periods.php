<?php
$canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
            } else if ($actyp == 5) {
                /* Delete Period Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePeriodsDetLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Accounting Period
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdPrdHdrID = isset($_POST['sbmtdPrdHdrID']) ? (float) cleanInputData($_POST['sbmtdPrdHdrID']) : -1;
                $periodHdrNm = isset($_POST['periodHdrNm']) ? cleanInputData($_POST['periodHdrNm']) : "";
                $clndrDesc = isset($_POST['clndrDesc']) ? cleanInputData($_POST['clndrDesc']) : '';
                $noTrnsDaysLov = isset($_POST['noTrnsDaysLov']) ? cleanInputData($_POST['noTrnsDaysLov']) : '';
                $noTrnsDatesLov = isset($_POST['noTrnsDatesLov']) ? cleanInputData($_POST['noTrnsDatesLov']) : '';
                $noTrnsDaysLovID = getLovID($noTrnsDaysLov);
                $noTrnsDatesLovID = getLovID($noTrnsDatesLov);
                $periodType = isset($_POST['periodType']) ? cleanInputData($_POST['periodType']) : '';
                $shdUsePeriods = isset($_POST['shdUsePeriods']) ? (cleanInputData($_POST['shdUsePeriods']) === "YES" ? true : false) : false;
                $slctdPeriodLines = isset($_POST['slctdPeriodLines']) ? cleanInputData($_POST['slctdPeriodLines']) : '';

                $exitErrMsg = "";
                if ($periodHdrNm == "") {
                    $exitErrMsg .= "Please enter Calendar Name!<br/>";
                }
                if ($noTrnsDaysLovID <= 0) {
                    $exitErrMsg .= "Please select No Transaction Days LOV!<br/>";
                }
                if ($noTrnsDatesLovID <= 0) {
                    $exitErrMsg .= "Please select No Transaction Dates LOV!<br/>";
                }
                if ($periodType == "") {
                    $exitErrMsg .= "Period Type cannot be empty!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdPrdHdrID'] = $sbmtdPrdHdrID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdPrdHdrID <= 0) {
                    createPeriodsHdr(
                        $orgID,
                        $periodHdrNm,
                        $clndrDesc,
                        $periodType,
                        $shdUsePeriods,
                        $noTrnsDaysLov,
                        $noTrnsDatesLov
                    );
                    $sbmtdPrdHdrID = getGnrlRecID("accb.accb_periods_hdr", "period_hdr_name", "periods_hdr_id", $periodHdrNm, $orgID);
                } else if ($sbmtdPrdHdrID > 0) {
                    updatePeriodsHdr($sbmtdPrdHdrID, $periodHdrNm, $clndrDesc, $periodType, $shdUsePeriods, $noTrnsDaysLov, $noTrnsDatesLov);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdPeriodLines, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdPeriodLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 4) {
                            $ln_PeriodDetID = (float) (cleanInputData1($crntRow[0]));
                            $ln_PeriodNm = cleanInputData1($crntRow[1]);
                            $ln_StrtDte = cleanInputData1($crntRow[2]);
                            $ln_EndDte = cleanInputData1($crntRow[3]);
                            /*03-Jan-2020 23:59:59
                                strpos($ln_StrtDte, "00:00:00") === FALSE
                                strpos($ln_EndDte, "23:59:50") === FALSE
                                */
                            if (substr($ln_StrtDte, 12, 8) <= "23:59:50") {
                                $ln_StrtDte = substr(cleanInputData1($crntRow[2]), 0, 11) . " 00:00:00";
                            }
                            if (substr($ln_EndDte, 12, 8) <= "23:59:50") {
                                $ln_EndDte = substr(cleanInputData1($crntRow[3]), 0, 11) . " 23:59:50";
                            }
                            if ($ln_PeriodDetID <= 0) {
                                $ln_PeriodDetID = get_PrdDetID($sbmtdPrdHdrID, $ln_PeriodNm);
                            }
                            $errMsg = "";
                            $intrvalTyp = "1 month";
                            if ($periodType != "") {
                                if (substr($periodType, 0, 1) == "1") {
                                    $intrvalTyp = "1 week";
                                } else if (substr($periodType, 0, 1) == "2") {
                                    $intrvalTyp = "1 month";
                                } else if (substr($periodType, 0, 1) == "3") {
                                    $intrvalTyp = "4 month";
                                } else if (substr($periodType, 0, 1) == "4") {
                                    $intrvalTyp = "6 month";
                                } else if (substr($periodType, 0, 1) == "5") {
                                    $intrvalTyp = "12 month";
                                }
                            }
                            if ($ln_PeriodDetID <= 0) {
                                if ((doesNwPrdDatesMeetPrdTyp($ln_StrtDte, $ln_EndDte, $intrvalTyp) == true
                                        || doesNwPrdDatesMeetPrdTyp2($ln_StrtDte, $ln_EndDte, "18 second") == true)
                                    && isNwPrdDatesInUse($ln_StrtDte, $ln_EndDte) == false
                                ) {
                                    $afftctd += createPeriodsDetLn($sbmtdPrdHdrID, $ln_StrtDte, $ln_EndDte, "Never Opened", $ln_PeriodNm);
                                }
                            } else {
                                $oldStatus = getGnrlRecNm(
                                    "accb.accb_periods_det",
                                    "period_det_id",
                                    "period_status",
                                    $ln_PeriodDetID
                                );
                                if ((doesNwPrdDatesMeetPrdTyp($ln_StrtDte, $ln_EndDte, $intrvalTyp) == true
                                        || doesNwPrdDatesMeetPrdTyp2($ln_StrtDte, $ln_EndDte, "18 second") == true)
                                    && isNwPrdDatesInUse1($ln_StrtDte, $ln_EndDte, $ln_PeriodDetID) == false
                                    && $oldStatus == "Never Opened"
                                ) {
                                    $afftctd += updtPeriodsDetLn($ln_PeriodDetID, $ln_StrtDte, $ln_EndDte, $oldStatus, $ln_PeriodNm);
                                }
                            }
                        }
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Periods Setup Successfully Saved!"
                        . "<br/>" . $afftctd . " Accounting Period(s) Saved Successfully!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Periods Setup Successfully Saved!"
                        . "<br/>" . $afftctd . " Accounting Period(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdPrdHdrID'] = $sbmtdPrdHdrID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else if ($qstr == "FINALIZE") {
            if ($actyp == 1) {
                $prdDetID = isset($_POST['prdDetID']) ? cleanInputData($_POST['prdDetID']) : -1;
                echo actOnPeriodStatus($prdDetID);
            }
        } else {
            if ($vwtyp == 0) {
                //Accounting Periods
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Accounting Periods</span>
                            </li>
                           </ul>
                          </div>";
                $periodHdrNm1 = "Accounting Calendar (" . getOrgName($orgID) . ")";
                $sbmtdPrdHdrCnt = (int) getGnrlRecNm("accb.accb_periods_hdr", "org_id", "''||count(periods_hdr_id)", $orgID);
                $sbmtdPrdHdrID = (int) getGnrlRecID("accb.accb_periods_hdr", "period_hdr_name", "periods_hdr_id", $periodHdrNm1, $orgID);
                if ($sbmtdPrdHdrCnt <= 0 && $sbmtdPrdHdrID <= 0) {
                    createPeriodsHdr(
                        $orgID,
                        $periodHdrNm1,
                        $periodHdrNm1,
                        "2-Monthly",
                        true,
                        "Transactions not Allowed Days",
                        "Transactions not Allowed Days"
                    );
                }
                $sbmtdHdrID = -1;
                $gnrtdTrnsDate = $gnrlTrnsDteDMYHMS;
                $gnrtdTrnsDate1 = $gnrlTrnsDteYMDHMS;
                $periodHdrNm = "";
                $clndrDesc = "";
                $noTrnsDaysLov = "";
                $noTrnsDatesLov = "";
                $periodType = "";
                $shdUsePeriods = "0";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if (!$canEdt) {
                    $mkReadOnly = "readonly=\"true\"";
                    $mkRmrkReadOnly = "readonly=\"true\"";
                }
                $result = get_One_CaldrDet($orgID);
                if ($row = loc_db_fetch_array($result)) {
                    $sbmtdHdrID = (float) $row[0];
                    $periodHdrNm = $row[1];
                    $clndrDesc = $row[2];
                    $noTrnsDaysLov = $row[5];
                    $noTrnsDatesLov = $row[6];
                    $periodType = $row[3];
                    $shdUsePeriods = $row[4];
                }
?>
                <form class="form-horizontal" id="onePeriodEDTForm">
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-6" style="padding:0px 15px 0px 0px !important;">
                            <div class="" style="padding:0px 0px 0px 0px;float:left !important;">
                                <button type="button" class="btn btn-default" style="" onclick="openATab('#allmodules', 'grp=6&typ=1&pg=8&vtyp=0');" style="width:100% !important;">
                                    <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    Refresh Accounting Periods
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                            <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveAccbPeriodsForm();">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    Save Accounting Periods&nbsp;
                                </button>
                            </div>
                        </div>
                    </div>
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Calendar Name:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="periodHdrNm" name="periodHdrNm" value="<?php echo $periodHdrNm; ?>" <?php echo $mkReadOnly; ?>>
                                        <input class="form-control" type="hidden" id="sbmtdPrdHdrID" value="<?php echo $sbmtdHdrID; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Calendar Description:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea class="form-control" rows="5" cols="20" id="clndrDesc" name="clndrDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $clndrDesc; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-sm">
                                    <label for="noTrnsDaysLov" class="control-label col-md-4">No Trns. Days LOV:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="noTrnsDaysLov" name="noTrnsDaysLov" value="<?php echo $noTrnsDaysLov; ?>" readonly="true">
                                            <input type="hidden" id="noTrnsDaysLovID" value="-1">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', 'noTrnsDaysLovID', 'noTrnsDaysLov', 'clear', 1, '');" data-toggle="tooltip" title="LOV Names">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="noTrnsDatesLov" class="control-label col-md-4">No Trns. Dates LOV:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="noTrnsDatesLov" name="noTrnsDatesLov" value="<?php echo $noTrnsDaysLov; ?>" readonly="true">
                                            <input type="hidden" id="noTrnsDatesLovID" value="-1">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', 'noTrnsDatesLovID', 'noTrnsDatesLov', 'clear', 1, '');" data-toggle="tooltip" title="LOV Names">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="periodType" class="control-label col-md-4">Period Type:</label>
                                    <div class="col-md-8">
                                        <?php
                                        if ($canEdt === true) {
                                        ?>
                                            <select class="form-control selectpicker rqrdFld" id="periodType">
                                                <option value="" selected disabled>Please Select...</option>
                                                <?php
                                                $valslctdArry = array("", "", "", "", "");
                                                $optionsArrys = array("1-Weekly", "2-Monthly", "3-Quarterly", "4-Half-Yearly", "5-Annual");

                                                for ($z = 0; $z < count($optionsArrys); $z++) {
                                                    if ($periodType == $optionsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                ?>
                                                    <option value="<?php echo $optionsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $optionsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            <span><?php echo $periodType; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        &nbsp;
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                            <label for="shdUsePeriods" class="control-label">
                                                <?php
                                                $isChkd = "";
                                                $isRdOnly = "disabled=\"true\"";
                                                if ($canEdt === true) {
                                                    $isRdOnly = "";
                                                }
                                                if ($shdUsePeriods == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" name="shdUsePeriods" id="shdUsePeriods" <?php echo $isChkd . " " . $isRdOnly; ?>>Enforce Accounting Periods in this Organisation?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwUsrOnly = false;
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUsrOnly'])) {
                    $qShwUsrOnly = cleanInputData($_POST['qShwUsrOnly']) === "true" ? true : false;
                }
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_Period_DetLns($srchFor, $srchIn, $sbmtdHdrID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_One_Period_DetLns($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdHdrID);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-3";
                    $nwRowHtml33 = "<tr id=\"accbPeriodsHdrsRow__WWW123WWW\">                                    
                                        <td class=\"lovtd\">New</td>  
                                        <td class=\"lovtd\">
                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbPeriodsHdrsRow_WWW123WWW_PeriodNm\" value=\"\" style=\"width:100% !important;\">
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbPeriodsHdrsRow_WWW123WWW_PeriodDetID\" value=\"-1\">
                                            </div>                                                        
                                        </td>
                                        <td class=\"lovtd\">
                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"accbPeriodsHdrsRow_WWW123WWW_StrtDte\" value=\"\">
                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                </div>                                                                
                                            </div>                                                        
                                        </td>
                                        <td class=\"lovtd\">
                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"accbPeriodsHdrsRow_WWW123WWW_EndDte\" value=\"\">
                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                </div>                                                                
                                            </div>                                                        
                                        </td>                                            
                                        <td class=\"lovtd\" style=\"text-align:center;\">Never Opened</td> 
                                        <td class=\"lovtd\">
                                            <span>&nbsp;</span>                                                         
                                        </td>";
                    if ($canDel === true) {
                        $nwRowHtml33 .= "<td class=\"lovtd\">
                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Period\" onclick=\"delAcbAcntngPeriod('accbPeriodsHdrsRow__WWW123WWW');\" style=\"padding:2px !important;\">
                                                <img src=\"cmn_images/no.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">
                                            </button>
                                            <input type=\"hidden\" id=\"accbPeriodsHdrsRow_WWW123WWW_HdrID\" name=\"accbPeriodsHdrsRow_WWW123WWW_HdrID\" value=\"-1\">
                                        </td>";
                    }
                    if ($canVwRcHstry === true) {
                        $nwRowHtml33 .= "<td class=\"lovtd\">&nbsp;</td>";
                    }
                    $nwRowHtml33 .= "</tr>";
                    $nwRowHtml33 = urlencode($nwRowHtml33);
                    $nwRowHtml1 = $nwRowHtml33;
                ?>
                    <form id='accbPeriodsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245">ACCOUNTING PERIODS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                if ($canAdd === true) {
                                ?>
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewAccbPeriodsRows('accbPeriodsHdrsTable', 0, '<?php echo $nwRowHtml1; ?>');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Accounting Period
                                        </button>
                                    </div>
                                <?php
                                } else {
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-4";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbPeriodsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncAccbPeriods(event, '', '#allmodules', 'grp=6&typ=1&pg=8&vtyp=0')">
                                        <input id="accbPeriodsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPeriods('clear', '#allmodules', 'grp=6&typ=1&pg=8&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPeriods('', '#allmodules', 'grp=6&typ=1&pg=8&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType2; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbPeriodsSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "");
                                            $srchInsArrys = array("Period Name", "All", "Start Date", "End Date", "Status");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbPeriodsDsplySze" style="min-width:70px !important;">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 1000000);
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
                                                <a href="javascript:getAccbPeriods('previous', '#allmodules', 'grp=6&typ=1&pg=8&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbPeriods('next', '#allmodules', 'grp=6&typ=1&pg=8&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="accbPeriodsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:25px;width:25px;">No.</th>
                                                <th>Period Name</th>
                                                <th>Period Start Date</th>
                                                <th>Period End Date</th>
                                                <th style="max-width:95px;width:95px;text-align: center;">Period Status</th>
                                                <th style="max-width:95px;width:95px;text-align: center;">Change Status</th>
                                                <?php if ($canDel === true) { ?>
                                                    <th style="max-width:25px;width:25px;">...</th>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:25px;width:25px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $reportTitle = "Period Close Process";
                                            $reportName = "Period Close Process";
                                            $rptID = getRptID($reportName);
                                            $prmID1 = getParamIDUseSQLRep("{:orgID}", $rptID);
                                            $prmID2 = getParamIDUseSQLRep("{:closing_dte}", $rptID);

                                            $reportTitle1 = "Reversal of Posted Period Close Process";
                                            $reportName1 = "Reversal of Posted Period Close Process";
                                            $rptID1 = getRptID($reportName1);
                                            $prmID11 = getParamIDUseSQLRep("{:orgID}", $rptID1);
                                            $prmID12 = getParamIDUseSQLRep("{:closing_dte}", $rptID1);
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                            ?>
                                                <tr id="accbPeriodsHdrsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="accbPeriodsHdrsRow<?php echo $cntr; ?>_PeriodNm" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="accbPeriodsHdrsRow<?php echo $cntr; ?>_PeriodDetID" value="<?php echo $row[0]; ?>">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row[2]; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                    <input class="form-control" size="16" type="text" id="accbPeriodsHdrsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $row[3]; ?>">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row[3]; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                    <input class="form-control" size="16" type="text" id="accbPeriodsHdrsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $row[4]; ?>">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row[4]; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd" style="text-align:center;"><?php echo $row[5]; ?></td>
                                                    <td class="lovtd">
                                                        <?php
                                                        $paramRepsNVals = $prmID1 . "~" . $orgID . "|" . $prmID2 . "~" . $row[4] . "|-130~" . $reportTitle . "|-190~HTML";
                                                        $paramStr = urlencode($paramRepsNVals);

                                                        $paramRepsNVals1 = $prmID11 . "~" . $orgID . "|" . $prmID12 . "~" . $row[4] . "|-130~" . $reportTitle1 . "|-190~HTML";
                                                        $paramStr1 = urlencode($paramRepsNVals1);

                                                        $btnTxt = "";
                                                        $btnTxtImg = "";
                                                        if ($row[5] == "Never Opened") {
                                                            $btnTxt = "Open";
                                                            $btnTxtImg = "accounts_mn.jpg";
                                                        } else if ($row[5] == "Open") {
                                                            $btnTxt = "Close";
                                                            $btnTxtImg = "90.png";
                                                        } else if ($row[5] == "Closed") {
                                                            $btnTxt = "Re-Open";
                                                            $btnTxtImg = "undo_256.png";
                                                        }
                                                        if ($canEdt === true) {
                                                        ?>
                                                            <button type="button" class="btn btn-default btn-md" data-toggle="tooltip" data-placement="bottom" title="Action on Period" onclick="actOnPeriodStatus('accbPeriodsHdrsRow_<?php echo $cntr; ?>')" style="padding:5px !important;width:100%;">
                                                                <img src="cmn_images/<?php echo $btnTxtImg; ?>" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                <?php echo $btnTxt; ?>
                                                            </button>
                                                        <?php } else { ?>
                                                            <span>&nbsp;</span>
                                                        <?php } ?>
                                                    </td>
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Period" onclick="delAcbAcntngPeriod('accbPeriodsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="accbPeriodsHdrsRow<?php echo $cntr; ?>_HdrID" name="accbPeriodsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|accb.accb_periods_det|period_det_id"),
                                                                                                                                                                                                                        $smplTokenWord1
                                                                                                                                                                                                                    ));
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
                }
            }
        }
    }
}
?>