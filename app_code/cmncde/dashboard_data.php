<?php
$vwtyp = "0";
$qstr = "view";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$wkfAppID = -1;
$wkfAppActionID = -1;
if (isset($formArray)) {
    if (count($formArray) > 0) {
        $vwtyp = isset($formArray['vtyp']) ? cleanInputData($formArray['vtyp']) : "0";
        $qstr = isset($formArray['q']) ? cleanInputData($formArray['q']) : '';
    } else {
        $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
    }
} else {
    $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
}

if (isset($_POST['appID'])) {
    $wkfAppID = cleanInputData($_POST['appID']);
}

if (isset($_POST['appActionID'])) {
    $wkfAppActionID = cleanInputData($_POST['appActionID']);
}

if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}

if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}

if (isset($_POST['q'])) {
    $qstr = cleanInputData($_POST['q']);
}

if (isset($_POST['vtyp'])) {
    $vwtyp = cleanInputData($_POST['vtyp']);
}
if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
$canview = true;
$_jsonOutput = "";
$cntent = "<div>
                <ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
                        <li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
                                <span style=\"text-decoration:none;\">Home</span>
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
                                <span style=\"text-decoration:none;\">All Modules&nbsp;</span><span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
                                <span style=\"text-decoration:none;\">Summary Dashboard</span>
                        </li></ul></div>";

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$uName = $_SESSION['UNAME'];
$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        echo $cntent;
        $ymdtme = substr($gnrlTrnsDteDMYHMS, 0, 11);
        $ymdtme1 = getDB_Date_TmIntvlAddSub($ymdtme, "6 Month", "Subtract");
        //echo $ymdtme1;
        $ymdtme2 = getDB_Date_TmIntvlAddSub(getDB_Date_TmIntvlAddSub("01" . substr($gnrlTrnsDteDMYHMS, 2, 9), "1 month", "Add"), "1 day",
                "Subtract");
        $ymdtme3 = "01" . substr($ymdtme1, 2, 9);
        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
        $qShwSmmry = FALSE;
        $accbFSRptAcntTypes = "R-REVENUE/EX-EXPENSE";
        $accbFSRptPrdType = isset($_POST['accbFSRptPrdType']) ? cleanInputData($_POST['accbFSRptPrdType']) : "Monthly";
        $accbFSRptMaxAcntLvl = 100;
        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID']) : -1;
        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
        if ($accbFSRptSbmtdAccountID > 0) {
            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
        }
        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3, 0, 11);
        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
        $accbFSRptDte1 = $accbFSRptDte;
        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID']) : -1;
        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID']) : -1;
        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID']) : -1;
        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID']) : -1;
        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID']) : -1;
        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID']) : -1;
        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID']) : -1;
        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID']) : -1;
        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID']) : -1;
        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID']) : -1;


        $shwSmmryChkd = "";
        if ($qShwSmmry == true) {
            $shwSmmryChkd = "checked=\"true\"";
        }
        $fsrptRunID = getMinFSRptRunID();
        if ($startRunng == 1) {
            $fsrptRunID = getNewFSRptRunID();
            if ($accbStrtFSRptDte != "") {
                $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
            }
            if ($accbFSRptDte != "") {
                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
            }
            $strSql = "select accb.populate_prd_by_prd_bals( " . $fsrptRunID . ", '"
                    . $accbFSRptAcntTypes . "', '" . $accbStrtFSRptDte .
                    "', '" . $accbFSRptDte . "', '"
                    . $accbFSRptPrdType . "', " . $accbFSRptMaxAcntLvl . ", "
                    . $accbFSRptSbmtdAccountID . ","
                    . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                    . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                    . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                    . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                    . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                    . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
            //echo $strSql;
            $result = executeSQLNoParams($strSql);
        }
        $top5Name = "TOP FIVE EXPENDITURE";
        $top10Name = "TOP TEN EXPENDITURE";
        if ($fsrptRunID > 0) {
            $colsName = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                "", "", "", "", "", "", "", "", "", "", "");
            $colscntr = 0;
            $cntr = 0;
            $maxNoRows = 0;
            $resultHdr = null;
            if ($fsrptRunID > 0) {
                $resultHdr = get_PeriodicRptHdr($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                if ($rwHdr = loc_db_fetch_array($resultHdr)) {
                    $colsName[0] = $rwHdr[11];
                    $colsName[1] = $rwHdr[12];
                    $colsName[2] = $rwHdr[13];
                    $colsName[3] = $rwHdr[14];
                    $colsName[4] = $rwHdr[15];
                    $colsName[5] = $rwHdr[16];
                    $colsName[6] = $rwHdr[17];
                    $colsName[7] = $rwHdr[18];
                    $colsName[8] = $rwHdr[19];
                    $colsName[9] = $rwHdr[20];
                    $colsName[10] = $rwHdr[21];
                    $colsName[11] = $rwHdr[22];
                    $colsName[12] = $rwHdr[23];
                    $colsName[13] = $rwHdr[24];
                    $colsName[14] = $rwHdr[25];
                    $colsName[15] = $rwHdr[26];
                    $colsName[16] = $rwHdr[27];
                    $colsName[17] = $rwHdr[28];
                    $colsName[18] = $rwHdr[29];
                    $colsName[19] = $rwHdr[30];
                    $colsName[20] = $rwHdr[31];
                    $colsName[21] = $rwHdr[32];
                    $colsName[22] = $rwHdr[33];
                    $colsName[23] = $rwHdr[34];
                    $colsName[24] = $rwHdr[35];
                    $colsName[25] = $rwHdr[36];
                    $colsName[26] = $rwHdr[37];
                    $colsName[27] = $rwHdr[38];
                    $colsName[28] = $rwHdr[39];
                    $colsName[29] = $rwHdr[40];
                    $colsName[30] = $rwHdr[41];
                    $colsName[31] = $rwHdr[42];
                    $colsName[32] = $rwHdr[43];
                    $colsName[33] = $rwHdr[44];
                    $colsName[34] = $rwHdr[45];
                    $colsName[35] = $rwHdr[46];
                    $colsName[36] = $rwHdr[47];
                    $colsName[37] = $rwHdr[48];
                    $colsName[38] = $rwHdr[49];
                }
            }
            $_jsonOutput1 = "";
            $_jsonOutput2 = "";
            $_jsonOutput3 = "";
            $_jsonOutput4 = "";
            $opngBalsColNm = "gnrl_data11";
            $clsngBalsColNm = "";
            $top5Name = "TOP FIVE EXPENDITURE";
            $top10Name = "TOP TEN EXPENDITURE";
            while ($colscntr < count($colsName)) {
                if ($colsName[$colscntr] == "Closing Balance") {
                    $clsngBalsColNm = "gnrl_data" . (12 + $colscntr);
                    break;
                }
                if (strpos(trim($colsName[$colscntr]), "Period ") !== FALSE) {
                    break;
                }
                //Income Trend
                $tmpVal1 = get_PeriodicIncmRpt($fsrptRunID, "gnrl_data" . (12 + $colscntr));
                $tmpVal12 = get_PeriodicExpnsRpt($fsrptRunID, "gnrl_data" . (12 + $colscntr));
                $tmpArry1 = array(
                    'Period' => $colsName[$colscntr],
                    'income' => $tmpVal1,
                    'expenses' => $tmpVal12);
                $_jsonOutput1 .= json_encode($tmpArry1) . ",";

                //Net Income Trend
                $tmpVal4 = get_PeriodicNetIncmRpt($fsrptRunID, "gnrl_data" . (12 + $colscntr));
                $tmpArry4 = array(
                    'Period' => $colsName[$colscntr],
                    'Value' => $tmpVal4);
                $_jsonOutput4 .= json_encode($tmpArry4) . ",";
                $colscntr++;
            }
            if ($clsngBalsColNm == "") {
                $clsngBalsColNm = "gnrl_data50";
                execUpdtInsSQL("DELETE FROM rpt.rpt_accb_data_storage WHERE accb_rpt_runid=" . $fsrptRunID);
            }
            $orgType = getGnrlRecNm("org.org_details", "org_id", "gst.get_pssbl_val(org_typ_id)", $orgID);
            if ($orgType == "NGO" || $orgType == "Church") {
                $top5Name = "TOP FIVE EXPENDITURE";
                $top10Name = "EVENT EXPENDITURE";
                $rstlRw = get_TopExpnsRpt2($fsrptRunID, $opngBalsColNm, $clsngBalsColNm);
                if ($colscntr > 0) {
                    $colscntr = 0;
                }
                while ($rw = loc_db_fetch_array($rstlRw)) {
                    $tmpArry2 = array(
                        'Account' => $rw[2],
                        'Value' => $rw[0]);
                    if ($colscntr < 5) {
                        $_jsonOutput3 .= json_encode($tmpArry2) . ",";
                    }
                    $colscntr++;
                }
                $rstlRw = get_TopEvntExpnsRpt($fsrptRunID, $opngBalsColNm, $clsngBalsColNm);
                if ($colscntr > 0) {
                    $colscntr = 0;
                }
                while ($rw = loc_db_fetch_array($rstlRw)) {
                    $tmpArry2 = array(
                        'Account' => $rw[2],
                        'Value' => $rw[0]);
                    if ($colscntr < 10) {
                        $_jsonOutput2 .= json_encode($tmpArry2) . ",";
                    }
                    $colscntr++;
                }
            } else {
                $rstlRw = get_TopExpnsRpt($fsrptRunID, $opngBalsColNm, $clsngBalsColNm);
                if ($colscntr > 0) {
                    $colscntr = 0;
                }
                while ($rw = loc_db_fetch_array($rstlRw)) {
                    /* {
                      "account nm": "USA",
                      "nettrns": 3025
                      } */
                    $tmpArry2 = array(
                        'Account' => $rw[2],
                        'Value' => $rw[0]);
                    if ($colscntr < 10) {
                        $_jsonOutput2 .= json_encode($tmpArry2) . ",";
                    }
                    if ($colscntr < 5) {
                        $_jsonOutput3 .= json_encode($tmpArry2) . ",";
                    }
                    $colscntr++;
                }
            }
            $_jsonOutput1 = '[' . trim($_jsonOutput1, ",");
            $_jsonOutput1 .= ']';

            $_jsonOutput2 = '[' . trim($_jsonOutput2, ",");
            $_jsonOutput2 .= ']';

            $_jsonOutput3 = '[' . trim($_jsonOutput3, ",");
            $_jsonOutput3 .= ']';

            $_jsonOutput4 = '[' . trim($_jsonOutput4, ",");
            $_jsonOutput4 .= ']';
        }
        ?>
        <!-- HTML -->
        <div class="row">
            <div class="col-md-12" style="padding:0px 3px 0px 15px;">
                <fieldset class="" style="padding: 0px 0px 0px 0px !important;">
                    <div class="col-md-3" style="padding:0px 1px 0px 0px !important;">
                        <div class="form-group">
                            <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                <label style="margin-bottom:0px !important;">Period:</label>
                            </div>
                            <div class="col-md-9" style="padding:0px 0px 0px 0px !important;">
                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptPrdType">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "");
                                    $srchInsArrys = array("Yearly", "Half Yearly", "Quarterly", "Monthly",
                                        "Fortnightly", "Weekly");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($accbFSRptPrdType == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>                           
                    </div>
                    <div  class="col-md-3" style="padding:0px 4px 0px 0px !important;">
                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="padding:0px 1px 0px 6px !important;">
                        <div class="form-group" style="padding:0px 1px 0px 1px !important;">
                            <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                            <div  class="col-md-10" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="accbFSRptAcntNum" name="accbFSRptAcntNum" value="<?php echo $accbFSRptAcntNum; ?>" style="width:100%;" readonly="true">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt1ValID" name="accbFSRptSgmnt1ValID" value="<?php echo $accbFSRptSgmnt1ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt2ValID" name="accbFSRptSgmnt2ValID" value="<?php echo $accbFSRptSgmnt2ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt3ValID" name="accbFSRptSgmnt3ValID" value="<?php echo $accbFSRptSgmnt3ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt4ValID" name="accbFSRptSgmnt4ValID" value="<?php echo $accbFSRptSgmnt4ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt5ValID" name="accbFSRptSgmnt5ValID" value="<?php echo $accbFSRptSgmnt5ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt6ValID" name="accbFSRptSgmnt6ValID" value="<?php echo $accbFSRptSgmnt6ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt7ValID" name="accbFSRptSgmnt7ValID" value="<?php echo $accbFSRptSgmnt7ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt8ValID" name="accbFSRptSgmnt8ValID" value="<?php echo $accbFSRptSgmnt8ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt9ValID" name="accbFSRptSgmnt9ValID" value="<?php echo $accbFSRptSgmnt9ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt10ValID" name="accbFSRptSgmnt10ValID" value="<?php echo $accbFSRptSgmnt10ValID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt" name="accbFSRptSgmnt" value="">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptAcntNum1" name="accbFSRptAcntNum1" value="">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                        <span class="glyphicon glyphicon-th-list"></span>&nbsp;Segments
                                    </label>
                                </div>
                            </div>          
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=40&typ=4&vtyp=0');">
                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>          
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=40&typ=4&vtyp=0');">
                                <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                <div class="col-md-6" style="padding:0px 0px 0px 15px;">
                    <fieldset class=""><legend class="basic_person_lg">INCOME/EXPENDITURE TREND</legend> 
                        <div id="chartdiv1" class="chartdiv"></div>
                    </fieldset>
                </div>
                <div class="col-md-6" style="padding:0px 15px 0px 15px;">
                    <fieldset class=""><legend class="basic_person_lg">NET INCOME TREND</legend> 
                        <div id="chartdiv4" class="chartdiv"></div>
                    </fieldset>
                </div>
                <div class="col-md-6" style="padding:0px 0px 0px 15px;">
                    <fieldset class=""><legend class="basic_person_lg"><?php echo $top5Name; ?></legend> 
                        <div id="chartdiv3" class="chartdiv2"></div>
                    </fieldset>
                </div>
                <div class="col-md-6" style="padding:0px 15px 0px 15px;">
                    <fieldset class=""><legend class="basic_person_lg"><?php echo $top10Name; ?></legend> 
                        <div id="chartdiv2" class="chartdiv2"></div>
                    </fieldset>
                </div>
                <div class="col-md-12" style="padding:0px 15px 0px 15px;">
                    <fieldset class=""><legend class="basic_person_lg">INCOME vs EXPENDITURE</legend> 
                        <div id="chartdiv5" class="chartdiv3"></div>
                    </fieldset>
                </div>
            </div>
        </div>
        <!-- Styles -->
        <style>
            .chartdiv {
                width: 100%;
                height: 350px;
            }
            .chartdiv2 {
                width: 100%;
                height: 450px;
            }
            .chartdiv3 {
                width: 100%;
                height: 750px;
            }
            .amcharts-export-menu-top-right {
                top: 10px;
                right: 0;
            }
        </style>

        <!-- Resources -->

        <!-- Chart code -->
        <?php if ($fsrptRunID > 0) { ?>
            <script>
                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end
                // Create chart instance
                var chart = am4core.create("chartdiv1", am4charts.XYChart);
                chart.exporting.menu = new am4core.ExportMenu();
                var data = <?php echo $_jsonOutput1; ?>;
                /* Create axes */
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "Period";
                categoryAxis.renderer.grid.template.disabled = false;
                categoryAxis.renderer.minGridDistance = 30;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.labels.template.rotation = 270;
                //categoryAxis.startLocation = 0.5;
                //categoryAxis.endLocation = 0.5;
                //categoryAxis.renderer.minLabelPosition = 0.05;
                //categoryAxis.renderer.maxLabelPosition = 0.95;

                /* Create value axis */
                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.grid.template.disabled = false;
                //valueAxis.renderer.minLabelPosition = 0.05;
                //valueAxis.renderer.maxLabelPosition = 0.95;

                /* Create series */
                var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.name = "Income";
                columnSeries.dataFields.valueY = "income";
                columnSeries.dataFields.categoryX = "Period";

                columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
                columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
                columnSeries.columns.template.propertyFields.stroke = "stroke";
                columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
                columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
                columnSeries.tooltip.label.textAlign = "middle";

                var lineSeries = chart.series.push(new am4charts.LineSeries());
                lineSeries.name = "Expenses";
                lineSeries.dataFields.valueY = "expenses";
                lineSeries.dataFields.categoryX = "Period";

                lineSeries.stroke = am4core.color("red");//#fdd400
                lineSeries.strokeWidth = 4;
                lineSeries.propertyFields.strokeDasharray = "lineDash";
                lineSeries.tooltip.label.textAlign = "middle";

                var bullet = lineSeries.bullets.push(new am4charts.Bullet());
                bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
                bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
                var circle = bullet.createChild(am4core.Circle);
                circle.radius = 5;
                circle.fill = am4core.color("#fff");
                circle.strokeWidth = 4;

                chart.data = data;
                //Net Income Trend
                var chart = am4core.create("chartdiv4", am4charts.XYChart);
                chart.exporting.menu = new am4core.ExportMenu();
                // Add data
                chart.data = <?php echo $_jsonOutput4; ?>;

                // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "Period";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.grid.template.disabled = false;
                categoryAxis.renderer.minGridDistance = 30;
                categoryAxis.renderer.labels.template.rotation = 270;
                //categoryAxis.startLocation = 0.5;
                //categoryAxis.endLocation = 0.5;
                //categoryAxis.renderer.minLabelPosition = 0.05;
                //categoryAxis.renderer.maxLabelPosition = 0.95;


                var categoryAxisTooltip = categoryAxis.tooltip.background;
                categoryAxisTooltip.pointerLength = 0;
                categoryAxisTooltip.fillOpacity = 0.3;
                categoryAxisTooltip.filters.push(new am4core.BlurFilter).blur = 5;
                categoryAxis.tooltip.dy = 5;


                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.inside = true;
                valueAxis.renderer.grid.template.disabled = false;
                //valueAxis.renderer.minLabelPosition = 0.05;
                //valueAxis.renderer.maxLabelPosition = 0.95;

                var valueAxisTooltip = valueAxis.tooltip.background;
                valueAxisTooltip.pointerLength = 0;
                valueAxisTooltip.fillOpacity = 0.3;
                valueAxisTooltip.filters.push(new am4core.BlurFilter).blur = 5;


                // Create series
                var series3 = chart.series.push(new am4charts.LineSeries());
                series3.dataFields.categoryX = "Period";
                series3.dataFields.valueY = "Value";
                series3.stroke = am4core.color("green");
                series3.strokeWidth = 3;
                series3.strokeDasharray = "lineDash";
                series3.tooltipText = "{categoryX}\n---\n[bold font-size: 20]{valueY}[/]";
                series3.tooltip.pointerOrientation = "vertical";
                series3.tooltip.label.textAlign = "middle";

                var bullet3 = series3.bullets.push(new am4charts.CircleBullet());
                bullet3.circle.radius = 8;
                bullet3.fill = am4core.color("red");//chart.colors.getIndex(3);
                bullet3.stroke = am4core.color("#fff");
                bullet3.strokeWidth = 3;

                var bullet3hover = bullet3.states.create("hover");
                bullet3hover.properties.scale = 1.2;

                var shadow3 = new am4core.DropShadowFilter();
                series3.filters.push(shadow3);

                chart.cursor = new am4charts.XYCursor();
                chart.cursor.lineX.disabled = true;
                chart.cursor.lineY.disabled = true;
                //Largest Expenses 2
                var chart = am4core.create("chartdiv2", am4charts.XYChart);
                //chart.scrollbarX = new am4core.Scrollbar();
                chart.exporting.menu = new am4core.ExportMenu();
                // Add data
                chart.data = <?php echo $_jsonOutput2; ?>;

                // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "Account";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 30;
                categoryAxis.renderer.labels.template.horizontalCenter = "right";
                categoryAxis.renderer.labels.template.verticalCenter = "middle";
                categoryAxis.renderer.labels.template.rotation = 270;
                categoryAxis.tooltip.disabled = true;
                categoryAxis.renderer.minHeight = 110;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.minWidth = 50;

                // Create series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.sequencedInterpolation = true;
                series.dataFields.valueY = "Value";
                series.dataFields.categoryX = "Account";
                series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
                series.columns.template.strokeWidth = 0;

                series.tooltip.pointerOrientation = "vertical";

                series.columns.template.column.cornerRadiusTopLeft = 10;
                series.columns.template.column.cornerRadiusTopRight = 10;
                series.columns.template.column.fillOpacity = 0.8;

                // on hover, make corner radiuses bigger
                hoverState = series.columns.template.column.states.create("hover");
                hoverState.properties.cornerRadiusTopLeft = 0;
                hoverState.properties.cornerRadiusTopRight = 0;
                hoverState.properties.fillOpacity = 1;

                series.columns.template.adapter.add("fill", (fill, target) => {
                    return chart.colors.getIndex(target.dataItem.index);
                });

                // Cursor
                chart.cursor = new am4charts.XYCursor();

                var chart = am4core.create("chartdiv3", am4charts.PieChart);
                chart.exporting.menu = new am4core.ExportMenu();
                // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "Value";
                pieSeries.dataFields.category = "Account";

                // Let's cut a hole in our Pie chart the size of 30% the radius
                chart.innerRadius = am4core.percent(10);

                // Put a thick white border around each Slice
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 3;
                pieSeries.slices.template.strokeOpacity = 1;
                pieSeries.slices.template
                        // change the cursor on hover to make it apparent the object can be interacted with
                        .cursorOverStyle = [
                            {
                                "property": "cursor",
                                "value": "pointer"
                            }
                        ];

                pieSeries.alignLabels = true;
                pieSeries.labels.template.bent = true;
                pieSeries.labels.template.radius = 10;
                pieSeries.labels.template.padding(5, 5, 5, 5);

                pieSeries.ticks.template.disabled = false;

                // Create a base filter effect (as if it's not there) for the hover to return to
                var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
                shadow.opacity = 0;

                // Create hover state
                var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

                // Slightly shift the shadow and make it more prominent on hover
                var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
                hoverShadow.opacity = 0.7;
                hoverShadow.blur = 5;

                // Add a legend
                chart.legend = new am4charts.Legend();

                chart.data =<?php echo $_jsonOutput3; ?>;
                //Income Vs Expenditure CHART 5
                var chart = am4core.create("chartdiv5", am4charts.XYChart);
                chart.exporting.menu = new am4core.ExportMenu();
                chart.data = <?php echo $_jsonOutput1; ?>;

                // Create axes
                var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "Period";
                categoryAxis.numberFormatter.numberFormat = "#";
                categoryAxis.renderer.inversed = true;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.cellStartLocation = 0.1;
                categoryAxis.renderer.cellEndLocation = 0.85;

                var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.grid.template.location = 0;
                valueAxis.renderer.opposite = true;

                // Create series
                function createSeries(field, name) {
                    var series = chart.series.push(new am4charts.ColumnSeries());
                    series.dataFields.valueX = field;
                    series.dataFields.categoryY = "Period";
                    series.name = name;
                    series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
                    series.columns.template.height = am4core.percent(100);
                    series.sequencedInterpolation = true;

                    var valueLabel = series.bullets.push(new am4charts.LabelBullet());
                    valueLabel.label.text = "{valueX}";
                    valueLabel.label.horizontalCenter = "left";
                    valueLabel.label.dx = 10;
                    valueLabel.label.hideOversized = false;
                    valueLabel.label.truncate = false;

                    var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
                    categoryLabel.label.text = "{name}";
                    categoryLabel.label.horizontalCenter = "right";
                    categoryLabel.label.dx = -10;
                    categoryLabel.label.fill = am4core.color("#fff");
                    categoryLabel.label.hideOversized = false;
                    categoryLabel.label.truncate = false;
                }

                createSeries("income", "Income");
                createSeries("expenses", "Expenses");
            </script>
            <?php
        }
        /* if ($qstr == 'view') {
          if ($vwtyp == 0) {
          $total = 500;
          $result = getTechDivDist();

          while ($row = loc_db_fetch_array($result)) {
          //$chckd = FALSE;
          $wkfapp = array(
          'name' => $row[0],
          'g1' => $row[1],
          'g2' => $row[2],
          'g3' => (int) '0',
          'g4' => (int) '0',
          'g5' => (int) '0',
          'g6' => (int) '0',
          'desc' => "-");
          $_jsonOutput .= json_encode($wkfapp) . ",";
          }
          $_jsonOutput = '[' . trim($_jsonOutput, ",");
          $_jsonOutput .= ']';
          echo $_jsonOutput;
          } else if ($vwtyp == 1) {
          $total = 500;
          $result = getGradeDist();

          while ($row = loc_db_fetch_array($result)) {
          //$chckd = FALSE;
          $strt = strpos($row[0], "(");
          $ln = strpos($row[0], ")") - $strt - 1;
          $wkfapp = array(
          'name' => substr($row[0], $strt + 1, $ln),
          'g1' => $row[1],
          'g2' => $row[2],
          'g3' => (int) '0',
          'g4' => (int) '0',
          'g5' => (int) '0',
          'g6' => (int) '0',
          'desc' => $row[0]);
          $_jsonOutput .= json_encode($wkfapp) . ",";
          }
          $_jsonOutput = '[' . trim($_jsonOutput, ",");
          $_jsonOutput .= ']';
          echo $_jsonOutput;
          } else if ($vwtyp == 2) {
          $total = 500;
          $result = getPortDist();

          while ($row = loc_db_fetch_array($result)) {
          //$chckd = FALSE;
          $wkfapp = array(
          'name' => $row[0],
          'g1' => $row[1],
          'g2' => $row[2],
          'g3' => (int) '0',
          'g4' => (int) '0',
          'g5' => (int) '0',
          'g6' => (int) '0',
          'desc' => "-");
          $_jsonOutput .= json_encode($wkfapp) . ",";
          }
          $_jsonOutput = '[' . trim($_jsonOutput, ",");
          $_jsonOutput .= ']';
          echo $_jsonOutput;
          } else if ($vwtyp == 3) {
          $total = 500;
          $result = getGSDist();

          while ($row = loc_db_fetch_array($result)) {
          //$chckd = FALSE;
          $wkfapp = array(
          'g1' => (double) $row[0],
          'desc' => $row[1],
          'g2' => (int) '2345',
          'g3' => (int) '0',
          'g4' => (int) '0',
          'g5' => (int) '0',
          'g6' => (int) '0',
          'name' => "-");
          $_jsonOutput .= json_encode($wkfapp) . ",";
          }
          $_jsonOutput = '[' . trim($_jsonOutput, ",");
          $_jsonOutput .= ']';
          //var_dump($_jsonOutput);
          echo $_jsonOutput;
          } else if ($vwtyp == 4) {
          $total = 500;
          $result = getMyKPI();

          while ($row = loc_db_fetch_array($result)) {
          //$chckd = FALSE;
          $wkfapp = array(
          'name' => "-",
          'g1' => (int) $row[0],
          'g2' => (int) $row[1],
          'g3' => (int) $row[2],
          'g4' => (int) $row[3],
          'g5' => (int) $row[4],
          'g6' => (int) $row[5],
          'desc' => "-");
          $_jsonOutput .= json_encode($wkfapp) . ",";
          }

          $_jsonOutput = '[' . trim($_jsonOutput, ",");
          $_jsonOutput .= ']';
          //var_dump($_jsonOutput);
          echo $_jsonOutput;
          } else if ($vwtyp == 5) {
          //Get Reports Submitted Early
          $total = 500;
          $result = getSbmtdRptsDaysElpsd();
          while ($row = loc_db_fetch_array($result)) {
          //$chckd = FALSE;
          $wkfapp = array(
          'name' => $row[0],
          'g1' => $row[1],
          'g2' => $row[2],
          'g3' => (int) '0',
          'g4' => (int) '0',
          'g5' => (int) '0',
          'g6' => (int) '0',
          'desc' => $row[0]);
          $_jsonOutput .= json_encode($wkfapp) . ",";
          }
          $_jsonOutput = '[' . trim($_jsonOutput, ",");
          $_jsonOutput .= ']';
          echo $_jsonOutput;
          } else if ($vwtyp == 6) {
          //Get No. of Reports Submitted
          $total = 500;
          $result = getNoOfSbmtdRpts();
          while ($row = loc_db_fetch_array($result)) {
          //$chckd = FALSE;
          $wkfapp = array(
          'name' => $row[0],
          'g1' => $row[1],
          'g2' => (int) '0',
          'g3' => (int) '0',
          'g4' => (int) '0',
          'g5' => (int) '0',
          'g6' => (int) '0',
          'desc' => $row[0]);
          $_jsonOutput .= json_encode($wkfapp) . ",";
          }
          $_jsonOutput = '[' . trim($_jsonOutput, ",");
          $_jsonOutput .= ']';
          echo $_jsonOutput;
          }
          } */
    }
}

function getMyKPI() {
    $sqlStr = "select 3, 75, 125, 6000, 5000, 2000";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getGSDist() {
    $sqlStr = "select 47.55, '2357/4356 Reg. Members'";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getPortDist() {
    $sqlStr = "select 
(case when (trim(res_address||''||pstl_addrs) != '' AND trim(email) != '' 
AND trim(cntct_no_tel||''||cntct_no_mobl) != '') then 'Grade A' 
when (trim(email) != '' AND trim(cntct_no_tel||''||cntct_no_mobl) != '') then 'Grade B'  
when trim(cntct_no_tel||''||cntct_no_mobl) != '' then 'Grade C'  
when trim(email) != '' then 'Grade D' 
 else 'Grade E'  end) || '-' || count(1) portability1, 
count(1), 1, case when (trim(res_address||''||pstl_addrs) != '' AND trim(email) != '' 
AND trim(cntct_no_tel||''||cntct_no_mobl) != '') then 'Grade A' 
when (trim(email) != '' AND trim(cntct_no_tel||''||cntct_no_mobl) != '') then 'Grade B'  
when trim(cntct_no_tel||''||cntct_no_mobl) != '' then 'Grade C' 
when trim(email) != '' then 'Grade D'  
 else 'Grade E'   end portability 
FROM prs.prsn_names_nos pnn 
left outer join pasn.prsn_prsntyps ppt
 on pnn.person_id = ppt.person_id
 where '' || coalesce(ppt.prsn_type,'-1') = coalesce(NULLIF('',''), '' || coalesce(ppt.prsn_type,'-1')) 
 AND pnn.org_id = " . $_SESSION['ORG_ID'] .
            " group by 4,3";
//Registered Member {:pstntyp}
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getGradeDist() {
    $sqlStr = "
 select 
 (select substring(grade_code_name from 1 for 30) from org.org_grades og where og.grade_id = pg.grade_id) grade, count(1) countt,1    
 from pasn.prsn_grades pg  
 left outer join pasn.prsn_prsntyps ppt
 on (pg.person_id = ppt.person_id
  and now() between to_timestamp(ppt.valid_start_date,'YYYY-MM-DD HH24:MI:SS') 
 AND to_timestamp(ppt.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))
 left outer join prs.prsn_names_nos pnn ON (pnn.person_id  = ppt.person_id) 
 WHERE  ppt.prsn_type ilike 'Registered Member%'
 AND pnn.org_id = " . $_SESSION['ORG_ID'] .
            "AND (coalesce(pg.valid_start_date, to_char((select now()),'YYYY-MM-DD')) is not null 
 and ( pg.valid_end_date is null OR ((SELECT NOW())
  between to_timestamp(pg.valid_start_date,'YYYY-MM-DD HH24:MI:SS') 
  AND to_timestamp(pg.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))
  group by 1,3 Order By 2 DESC LIMIT 10 offset 0";
//Registered Member
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getTechDivDist() {
    $sqlStr = "
        select div_code_name division, 
        count(1) countt, 1, div_code_name  
from pasn.prsn_divs_groups pdg 
inner join org.org_divs_groups odg 
  on pdg.div_id = odg.div_id   
  inner join prs.prsn_names_nos pnn ON (pnn.person_id  = pdg.person_id) 
  inner join pasn.prsn_prsntyps ppt
 on (pnn.person_id = ppt.person_id
  and now() between to_timestamp(ppt.valid_start_date,'YYYY-MM-DD HH24:MI:SS') 
 AND to_timestamp(ppt.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))
 WHERE  ppt.prsn_type ilike '%'
 AND pnn.org_id = " . $_SESSION['ORG_ID'] .
            " AND (coalesce(pdg.valid_start_date, to_char((select now()),'YYYY-MM-DD')) is not null and ( pdg.valid_end_date is null OR ((SELECT NOW())
  between to_timestamp(pdg.valid_start_date,'YYYY-MM-DD HH24:MI:SS') 
  AND to_timestamp(pdg.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))
  AND UPPER(div_code_name) in ('CIVIL','ELECTRICAL/ELECTRONIC','MECH/AGRIC/MARINE','CHEMICAL/MINING') /**/
  AND gst.get_pssbl_val(div_typ_id)='Technical Division'
  group by 4, 3";
//Registered Member
    $result = executeSQLNoParams($sqlStr);
    return $result;
    /* || '-' || count(1) || '' 
      || ' (' || round(((count(1)::numeric/(Select count(1)::numeric
      from pasn.prsn_divs_groups pdg1
      inner join org.org_divs_groups odg1
      on pdg1.div_id = odg1.div_id
      inner join prs.prsn_names_nos pnn1 ON (pnn1.person_id  = pdg1.person_id)
      inner join pasn.prsn_prsntyps ppt1
      on (pnn1.person_id = ppt1.person_id
      and now() between to_timestamp(ppt1.valid_start_date,'YYYY-MM-DD HH24:MI:SS')
      AND to_timestamp(ppt1.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))
      WHERE  ppt1.prsn_type ilike '%'
      AND pnn1.org_id = ".$_SESSION['ORG_ID'].
      " AND (coalesce(pdg1.valid_start_date, to_char((select now()),'YYYY-MM-DD')) is not null
      and ( pdg1.valid_end_date is null OR ((SELECT NOW())
      between to_timestamp(pdg1.valid_start_date,'YYYY-MM-DD HH24:MI:SS')
      AND to_timestamp(pdg1.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))
      AND UPPER(odg1.div_code_name) in ('CIVIL','ELECTRICAL','MECH/AGRIC','CHEM/MINING')
      AND gst.get_pssbl_val(odg1.div_typ_id)='Technical Division'))*100),2) || '%)' */
}

function getSbmtdRptsDaysElpsd() {
    $sqlStr = "
 select tbl1.* from 
 (select 'PSB1' as name, 19 countt, 1 ordr
 UNION
 select 'PSB2', 2, 2 
 UNION
 select 'PSB3', 1, 3 
 UNION
 select 'PSB4', 8, 4 
 UNION
 select 'PSB5', 17, 5 
 UNION
 select 'PSB6', 9, 6 
 UNION
 select 'PSB7', 0, 7 
 UNION
 select 'PSB8', 5, 8 
 UNION
 select 'PSB9', 15, 9 
 UNION
 select 'PSB10', 20, 10) tbl1 ORDER BY 3 ASC ";
//Registered Member
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getNoOfSbmtdRpts() {
    $sqlStr = "
 select tbl1.* from 
 (select 'PSB1' as name, 5 countt, 1 ordr
 UNION
 select 'PSB2', 20, 2 
 UNION
 select 'PSB3', 13, 3 
 UNION
 select 'PSB4', 25, 4 
 UNION
 select 'PSB5', 14, 5 
 UNION
 select 'PSB6', 19, 6 
 UNION
 select 'PSB7', 30, 7 
 UNION
 select 'PSB8', 12, 8 
 UNION
 select 'PSB9', 6, 9 
 UNION
 select 'PSB10', 2, 10) tbl1 ORDER BY 3 ASC ";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_PeriodicRpt($trnsID, $asAtDate1, $asAtDate2) {
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data10||tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4::INTEGER accnt_id,
tbl1.gnrl_data5 is_prnt_accnt,
tbl1.gnrl_data6 has_sub_ledgers,
tbl1.gnrl_data7 accnt_type,
tbl1.gnrl_data8 depth,
tbl1.gnrl_data9 path,
tbl1.gnrl_data10 lftpaddng,
tbl1.gnrl_data11 openbals,
tbl1.gnrl_data12 period1,
tbl1.gnrl_data13 period2,
tbl1.gnrl_data14 period3,
tbl1.gnrl_data15 period4,
tbl1.gnrl_data16 period5,
tbl1.gnrl_data17 period6,
tbl1.gnrl_data18 period7,
tbl1.gnrl_data19 period8,
tbl1.gnrl_data20 period9,
tbl1.gnrl_data21 period10,
tbl1.gnrl_data22 period11,
tbl1.gnrl_data23 period12,
tbl1.gnrl_data24 period13,
tbl1.gnrl_data25 period14,
tbl1.gnrl_data26 period15,
tbl1.gnrl_data27 period16,
tbl1.gnrl_data28 period17,
tbl1.gnrl_data29 period18,
tbl1.gnrl_data30 period19,
tbl1.gnrl_data31 period20,
tbl1.gnrl_data32 period21,
tbl1.gnrl_data33 period22,
tbl1.gnrl_data34 period23,
tbl1.gnrl_data35 period24,
tbl1.gnrl_data36 period25,
tbl1.gnrl_data37 period26,
tbl1.gnrl_data38 period27,
tbl1.gnrl_data39 period28,
tbl1.gnrl_data40 period29,
tbl1.gnrl_data41 period30,
tbl1.gnrl_data42 period31,
tbl1.gnrl_data43 period32,
tbl1.gnrl_data44 period33,
tbl1.gnrl_data45 period34,
tbl1.gnrl_data46 period35,
tbl1.gnrl_data47 period36,
tbl1.gnrl_data48 period37,
tbl1.gnrl_data49 period38,
tbl1.gnrl_data50 period39,
to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE
FROM rpt.rpt_accb_data_storage tbl1
WHERE tbl1.gnrl_data1!='No.' and tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    //echo $strSql;
    //return;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PeriodicRptHdr($trnsID, $asAtDate1, $asAtDate2) {
    $strSql = "SELECT
tbl1.gnrl_data1 rwnumber,
tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4 accnt_id,
tbl1.gnrl_data5 is_prnt_accnt,
tbl1.gnrl_data6 has_sub_ledgers,
tbl1.gnrl_data7 accnt_type,
tbl1.gnrl_data8 depth,
tbl1.gnrl_data9 path,
tbl1.gnrl_data10 lftpaddng,
tbl1.gnrl_data11 openbals,
tbl1.gnrl_data12 period1,
tbl1.gnrl_data13 period2,
tbl1.gnrl_data14 period3,
tbl1.gnrl_data15 period4,
tbl1.gnrl_data16 period5,
tbl1.gnrl_data17 period6,
tbl1.gnrl_data18 period7,
tbl1.gnrl_data19 period8,
tbl1.gnrl_data20 period9,
tbl1.gnrl_data21 period10,
tbl1.gnrl_data22 period11,
tbl1.gnrl_data23 period12,
tbl1.gnrl_data24 period13,
tbl1.gnrl_data25 period14,
tbl1.gnrl_data26 period15,
tbl1.gnrl_data27 period16,
tbl1.gnrl_data28 period17,
tbl1.gnrl_data29 period18,
tbl1.gnrl_data30 period19,
tbl1.gnrl_data31 period20,
tbl1.gnrl_data32 period21,
tbl1.gnrl_data33 period22,
tbl1.gnrl_data34 period23,
tbl1.gnrl_data35 period24,
tbl1.gnrl_data36 period25,
tbl1.gnrl_data37 period26,
tbl1.gnrl_data38 period27,
tbl1.gnrl_data39 period28,
tbl1.gnrl_data40 period29,
tbl1.gnrl_data41 period30,
tbl1.gnrl_data42 period31,
tbl1.gnrl_data43 period32,
tbl1.gnrl_data44 period33,
tbl1.gnrl_data45 period34,
tbl1.gnrl_data46 period35,
tbl1.gnrl_data47 period36,
tbl1.gnrl_data48 period37,
tbl1.gnrl_data49 period38,
tbl1.gnrl_data50 period39
FROM rpt.rpt_accb_data_storage tbl1
WHERE tbl1.gnrl_data1='No.' and tbl1.accb_rpt_runid=" . $trnsID . "";
    //echo $strSql;
    //return;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PeriodicNetIncmRpt($trnsID, $columnNm) {
    $strSql = "SELECT SUM((CASE WHEN tbl1.gnrl_data7 IN ('R') THEN 1 ELSE -1 END) * (tbl1." . $columnNm . "::NUMERIC))
                FROM rpt.rpt_accb_data_storage tbl1
                WHERE tbl1.gnrl_data1 !='No.' and tbl1.accb_rpt_runid=" . $trnsID .
            " and tbl1.gnrl_data7 IN ('R','EX') and tbl1.gnrl_data5='0' and tbl1.gnrl_data6='0'";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_PeriodicIncmRpt($trnsID, $columnNm) {
    $strSql = "SELECT SUM((tbl1." . $columnNm . "::NUMERIC))
                FROM rpt.rpt_accb_data_storage tbl1
                WHERE tbl1.gnrl_data1 !='No.' and tbl1.accb_rpt_runid=" . $trnsID .
            " and tbl1.gnrl_data7 IN ('R') and tbl1.gnrl_data5='0' and tbl1.gnrl_data6='0'";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_PeriodicExpnsRpt($trnsID, $columnNm) {
    $strSql = "SELECT SUM((tbl1." . $columnNm . "::NUMERIC))
                FROM rpt.rpt_accb_data_storage tbl1
                WHERE tbl1.gnrl_data1 !='No.' and tbl1.accb_rpt_runid=" . $trnsID .
            " and tbl1.gnrl_data7 IN ('EX') and tbl1.gnrl_data5='0' and tbl1.gnrl_data6='0'";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_TopExpnsRpt($trnsID, $opngBalsColNm, $clsngBalsColNm) {
    $strSql = "SELECT tbl2.* FROM (SELECT round((tbl1." . $clsngBalsColNm . "::NUMERIC)-(tbl1." . $opngBalsColNm . "::NUMERIC),2) net_trns,
tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name
                FROM rpt.rpt_accb_data_storage tbl1
                WHERE tbl1.gnrl_data1 !='No.' and tbl1.accb_rpt_runid=" . $trnsID .
            " and tbl1.gnrl_data7 IN ('EX') and tbl1.gnrl_data5='0' and tbl1.gnrl_data6='0') tbl2 ORDER BY tbl2.net_trns DESC LIMIT 12 OFFSET 0";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TopExpnsRpt2($trnsID, $opngBalsColNm, $clsngBalsColNm) {
    $strSql = "SELECT tbl2.* FROM (SELECT round((tbl1." . $clsngBalsColNm . "::NUMERIC)-(tbl1." . $opngBalsColNm . "::NUMERIC),2) net_trns,
tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name
                FROM rpt.rpt_accb_data_storage tbl1
                WHERE tbl1.gnrl_data1 !='No.' and tbl1.accb_rpt_runid=" . $trnsID .
            " and tbl1.gnrl_data7 IN ('EX') and tbl1.gnrl_data5='0' and tbl1.gnrl_data6='0' and tbl1.gnrl_data4::INTEGER NOT IN (SELECT z.account_id
                       FROM accb.accb_account_clsfctns z
                       WHERE z.maj_rpt_ctgry in ('Running Cost'))"
            . " UNION "
            . " SELECT SUM(round((tbl1." . $clsngBalsColNm . "::NUMERIC)-(tbl1." . $opngBalsColNm . "::NUMERIC),2)) net_trns,
MIN(tbl1.gnrl_data2) account_number,
'Running Cost' accnt_name
                FROM rpt.rpt_accb_data_storage tbl1
                WHERE tbl1.gnrl_data1 !='No.' and tbl1.accb_rpt_runid=" . $trnsID .
            " and tbl1.gnrl_data7 IN ('EX') and tbl1.gnrl_data5='0' and tbl1.gnrl_data6='0' and tbl1.gnrl_data4::INTEGER IN (SELECT z.account_id
                       FROM accb.accb_account_clsfctns z
                       WHERE z.maj_rpt_ctgry in ('Running Cost'))) tbl2 ORDER BY tbl2.net_trns DESC LIMIT 12 OFFSET 0";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TopEvntExpnsRpt($trnsID, $opngBalsColNm, $clsngBalsColNm) {
    $strSql = "SELECT tbl2.* FROM (SELECT round((tbl1." . $clsngBalsColNm . "::NUMERIC)-(tbl1." . $opngBalsColNm . "::NUMERIC),2) net_trns,
tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name
                FROM rpt.rpt_accb_data_storage tbl1
                WHERE tbl1.gnrl_data1 !='No.' and tbl1.accb_rpt_runid=" . $trnsID .
            " and tbl1.gnrl_data7 IN ('EX') and tbl1.gnrl_data5='0' and tbl1.gnrl_data6='0' and tbl1.gnrl_data4::INTEGER IN (SELECT z.account_id
                       FROM accb.accb_account_clsfctns z
                       WHERE z.maj_rpt_ctgry in ('Event Account'))) tbl2 ORDER BY tbl2.net_trns DESC LIMIT 12 OFFSET 0";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}
?>
