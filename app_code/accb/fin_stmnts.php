<?php
$canview = test_prmssns($dfltPrvldgs[4], $mdlNm) || test_prmssns($dfltPrvldgs[5], $mdlNm) || test_prmssns($dfltPrvldgs[6], $mdlNm);
$finStmtItems = array("Trial Balance", "Profit & Loss", "Balance Sheet", "Sub-Ledger Balances",
    "GL Account Statements", "Period By Period Net Trns.", "Cash Flow Statement", "Cash Book", 
    "Financial Ratios","Consolidated Trial Balance");
$finStmtImages = array("report-icon-png.png", "report-icon-png.png", "report-icon-png.png",
    "report-icon-png.png", "report-icon-png.png", "report-icon-png.png",
    "report-icon-png.png", "report-icon-png.png", "report-icon-png.png", 
    "report-icon-png.png", "report-icon-png.png");
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            }
        } else {
            $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Financial Statements</span>
				</li>";
            if ($vwtyp == 0) {
                $cntent .= "</ul>
                              </div>";

                $cntent .= "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
                    <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                        <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                        font-weight:normal;\">Financial Statements:</span>
                    </div>
                <p>";
                $grpcntr = 0;
                for ($i = 0; $i < count($finStmtItems); $i++) {
                    $No = ($i + 1) * 10;
                    if ($i == 0) {
                        
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }

                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$No');\">
            <img src=\"cmn_images/$finStmtImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($finStmtItems[$i]) . "</span>
        </button>
            </div>";
                    if ($grpcntr == 3) {
                        $cntent .= "</div>";
                        $grpcntr = 0;
                    } else {
                        $grpcntr = $grpcntr + 1;
                    }
                }
                $cntent .= "
      </p>
    </div>";
                echo $cntent;
            } else if ($vwtyp == 10) {
                $canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
                $canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canVoid = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canPost = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Trial Balance</span>
				</li>
                               </ul>
                              </div>";
                echo $cntent;
                ?>
                <div class="row">
                    <?php
                    $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                    //echo $ymdtme;
                    $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                    $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");

                    $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                    $qShwVariance = isset($_POST['accbFSRptShwVariance']) ? (cleanInputData($_POST['accbFSRptShwVariance']) === "YES" ? TRUE
                                : FALSE) : FALSE;
                    $qShwHideZero = isset($_POST['accbFSRptShwHideZero']) ? (cleanInputData($_POST['accbFSRptShwHideZero']) === "YES" ? TRUE
                                : FALSE) : TRUE;
                    $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                : TRUE;
                    $qShwNetPos = isset($_POST['accbFSRptShwNetPos']) ? (cleanInputData($_POST['accbFSRptShwNetPos']) === "YES" ? TRUE : FALSE)
                                : TRUE;

                    $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                    $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                : -1;
                    $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                    if ($accbFSRptSbmtdAccountID > 0) {
                        $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                    }
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

                    $shwVarianceChkd = "";
                    if ($qShwVariance == true) {
                        $shwVarianceChkd = "checked=\"true\"";
                    }
                    $shwHideZeroChkd = "";
                    if ($qShwHideZero == true) {
                        $shwHideZeroChkd = "checked=\"true\"";
                    }
                    $shwSmmryChkd = "";
                    if ($qShwSmmry == true) {
                        $shwSmmryChkd = "checked=\"true\"";
                    }
                    $shwNetPosChkd = "";
                    if ($qShwNetPos == true) {
                        $shwNetPosChkd = "checked=\"true\"";
                    }
                    $fsrptRunID = -1;
                    if ($startRunng == 1) {
                        $fsrptRunID = getNewFSRptRunID();
                        $useNetPos = $qShwNetPos ? "Yes" : "No";
                        $shwVarnce = $qShwVariance ? "Yes" : "No";
                        if ($accbFSRptDte != "") {
                            $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                        }
                        $strSql = "select accb.populate_trial_bals2( " . $fsrptRunID . ", '" . $useNetPos .
                                "', '" . $accbFSRptDte . "', " . $accbFSRptMaxAcntLvl . ", "
                                . $accbFSRptSbmtdAccountID . ", '" . $shwVarnce . "', "
                                . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                                . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                                . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                                . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                                . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                                . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                        $result = executeSQLNoParams($strSql);
                    }

                    $nwRowHtml2 = "<tr id=\"oneJrnlBatchDetRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneJrnlBatchDetLinesTable tr').index(this));\">"
                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                            . "<td class=\"lovtd\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_AccountID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">    
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsSmryLnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_AccountNm\" name=\"oneJrnlBatchDetRow_WWW123WWW_AccountNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchDetRow_WWW123WWW_AccountID', 'oneJrnlBatchDetRow_WWW123WWW_AccountNm', 'clear', 1, '', function () {

                                                                                                                });\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                        </div>                                              
                                                                    </td>                                          
                                                                    <td class=\"lovtd\"  style=\"\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_LineDesc\" name=\"oneJrnlBatchDetRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_LineDesc', 'oneJrnlBatchDetLinesTable', 'jbDetDesc');\">                                                    
                                                                    </td>                                                  
                                                                    <td class=\"lovtd\">
                                                                        <div class=\"\" style=\"width:100% !important;\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm\" name=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm\" value=\"" . $fnccurnm . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                            <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                            $('#oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm1').html($('#oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm').val());
                                                                                                        });\">
                                                                                <span class=\"\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm1\">" . $fnccurnm . "</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_DebitAmnt\" name=\"oneJrnlBatchDetRow_WWW123WWW_DebitAmnt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_DebitAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchDetTtl();\">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetCrdt\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_CreditAmnt\" name=\"oneJrnlBatchDetRow_WWW123WWW_CreditAmnt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_CreditAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchDetTtl();\">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Denominational Breakdown\" 
                                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneJrnlBatchDetRow_WWW123WWW_DebitAmnt', 'oneJrnlBatchDetRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\"> 
                                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                            </button>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneJrnlBatchDetRow_WWW123WWW_TransDte\" value=\"" . $accbFSRptDte1 . " 12:00:00\">
                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                            </div>                                                        
                                                                    </td>                                         
                                                                    <td class=\"lovtd\"  style=\"\">
                                                                        <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_RefDoc\" name=\"oneJrnlBatchDetRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_RefDoc', 'oneJrnlBatchDetLinesTable', 'jbDetRfDc');\">                                                    
                                                                    </td>";
                    if ($canDel === true && $canEdt === true) {
                        $nwRowHtml2 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbJrnlBatchDetLn('oneJrnlBatchDetRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Journal Line\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                    }
                    if ($canVwRcHstry === true) {
                        $nwRowHtml2 .= "<td class=\"lovtd\">&nbsp;</td>";
                    }
                    $nwRowHtml2 .= "</tr>";
                    $nwRowHtml2 = urlencode($nwRowHtml2);
                    ?>
                    <div class="col-md-12" style="padding:0px 15px 0px 15px;">
                        <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                            <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                <li class="active"><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclGlStatemtLines" id="accbRcnclGlStatemtLinestab">Main Report</a></li>
                                <li class=""><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclJrnlTrnsLines" id="accbRcnclJrnlTrnsLinestab">Corrective Journal Entries</a></li>
                            </ul>
                            <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                    <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                        <div class="row"> 

                                            <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">

                                                <form class="form-horizontal" id="accbFSRptForm">
                                                    <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                        <legend class="basic_person_lg">
                                                            Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </legend>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwVariance" name="accbFSRptShwVariance" <?php echo $shwVarianceChkd; ?>>
                                                                    Show Variances
                                                                </label>
                                                            </div>                            
                                                        </div>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwHideZero" name="accbFSRptShwHideZero" <?php echo $shwHideZeroChkd; ?>>
                                                                    Hide Zero Balances
                                                                </label>
                                                            </div>                            
                                                        </div>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                                                    Summary Report
                                                                </label>
                                                            </div>                            
                                                        </div>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwNetPos" name="accbFSRptShwNetPos" <?php echo $shwNetPosChkd; ?>>
                                                                    Use Net Positions
                                                                </label>
                                                            </div>                            
                                                        </div>
                                                        <div  class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="margin-bottom:0px !important;">As At Date:</label>
                                                                </div>
                                                                <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                    <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="As At Date:">
                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="margin-bottom:0px !important;">Account Level Maximum Depth:</label>
                                                                </div>
                                                                <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                                                    <input type="number" min="1" max="10000" class="form-control" aria-label="..." id="accbFSRptMaxAcntLvl" name="accbFSRptMaxAcntLvl" value="<?php echo $accbFSRptMaxAcntLvl; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                                                <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                                                                <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                                                    </label>
                                                                </div>
                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=10');">
                                                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Generate Report
                                                            </button>
                                                        </div>
                                                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=10');">
                                                                <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </div>
                                                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </div>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="adjustSelectedBals('<?php echo $nwRowHtml2; ?>');">
                                                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Adjust Selected Balances
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div>
                                            <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                <form class="form-horizontal" id="accbFSRptDetForm">
                                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a> TRIAL BALANCE AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                                        </caption>
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                <!--<th style="max-width:20px;width:20px;">...</th>-->
                                                                <th style="max-width:20px;width:20px;">No.</th>
                                                                <th style="max-width:450px !important;">Account Number/Name</th>
                                                                <th style="text-align: right;">Debit Balance</th>
                                                                <th style="text-align: right;">Credit Balance</th>
                                                                <th style="text-align: right;">Net Balance</th>
                                                                <th style="max-width:70px;width:70px;">Transaction Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $maxNoRows = 0;
                                                            $resultRw = null;
                                                            if ($fsrptRunID > 0) {
                                                                $resultRw = get_TbalsRpt($fsrptRunID, $accbFSRptDte);
                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                            }
                                                            $ttlTrsctnDbtAmnt = 0;
                                                            $ttlTrsctnCrdtAmnt = 0;
                                                            $ttlTrsctnNetAmnt = 0;
                                                            while ($cntr < $maxNoRows) {
                                                                $rowNumber = 0;
                                                                $trsctnAcntID = -1;
                                                                $trsctnAcntNm = "";
                                                                $trsctnDbtAmnt = 0;
                                                                $trsctnCrdtAmnt = 0;
                                                                $trsctnNetAmnt = 0;
                                                                $trsctnLineDate = "";
                                                                $isParent = "0";
                                                                $numStyle1 = "text-align:right;";
                                                                $nameStyle1 = "";
                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $rowNumber = (float) $rowRw[0];
                                                                    $trsctnAcntID = (int) $rowRw[7];
                                                                    $trsctnAcntNm = str_replace(" ", "&nbsp;", $rowRw[1] . "." . $rowRw[2]);
                                                                    $trsctnDbtAmnt = (float) $rowRw[3];
                                                                    $trsctnCrdtAmnt = (float) $rowRw[4];
                                                                    $trsctnNetAmnt = (float) $rowRw[5];
                                                                    $trsctnLineDate = $rowRw[6];
                                                                    $isParent = $rowRw[8];
                                                                    if ($isParent == "1") {
                                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                                        $nameStyle1 = "font-weight:bold;";
                                                                    }
                                                                    $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + (float) $rowRw[10];
                                                                    $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + (float) $rowRw[11];
                                                                    $ttlTrsctnNetAmnt = $ttlTrsctnDbtAmnt - $ttlTrsctnCrdtAmnt;
                                                                }
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                    <td class="lovtd">
                                                                        <input type="checkbox" name="oneAccbFSRptRow<?php echo $cntr; ?>_CheckBox" value="oneAccbFSRptRow_<?php echo $cntr; ?>">
                                                                    </td>                                
                                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $rowRw[2]; ?>" >
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">   
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNm" value="<?php echo trim($trsctnAcntNm); ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_DbtAmnt" value="<?php echo $trsctnDbtAmnt; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_CrdtAmnt" value="<?php echo $trsctnCrdtAmnt; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_TrnsDte" value="<?php echo trim($trsctnLineDate); ?>" style="width:100% !important;">
                                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnNetAmnt, 2); ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo $trsctnLineDate; ?></span>                   
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>                                                            
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <!--<th>&nbsp;</th>-->
                                                                <th style="">&nbsp;</th>
                                                                <th>TOTALS:</th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                                </th>   
                                                                <th style="">&nbsp;</th>   
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="accbRcnclJrnlTrnsLines" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                        <div class="row"> 
                                            <div class="col-md-12"> 
                                                <?php
                                                $lmtSze = isset($_POST['accbJrnlBatchDsplySze']) ? cleanInputData($_POST['accbJrnlBatchDsplySze'])
                                                            : 50;
                                                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID'])
                                                            : -1;
                                                if (!$canAdd || ($sbmtdJrnlBatchID > 0 && !$canEdt)) {
                                                    restricted();
                                                    exit();
                                                }
                                                $orgnlJrnlBatchID = $sbmtdJrnlBatchID;
                                                $rqStatus = "Not Posted";
                                                $rqstatusColor = "red";
                                                $rqstVldty = "VALID";
                                                $rqstVldtyColor = "green";
                                                $autoPostStatus = "Not Monitored";
                                                $autoPostStatusColor = "black";
                                                $dte = date('ymd');
                                                $gnrtdTrnsNo = "";
                                                $gnrtdTrnsDate = $gnrlTrnsDteDMYHMS; //date('d-M-Y H:i:s');
                                                $gnrtdTrnsDate1 = $gnrlTrnsDteYMDHMS; //date('Y-m-d H:i:s');
                                                $crncyID = $fnccurid;
                                                $crncyIDNm = $fnccurnm;
                                                $voidedJrnlBatchID = -1;
                                                $jrnlBatchDesc = "";
                                                $jrnlBatchRvrslRsn = "";
                                                $jrnlBatchNetAmnt = 0;
                                                $jrnlBatchDbtAmnt = 0;
                                                $jrnlBatchCrdtAmnt = 0;
                                                $jrnlBatchDfltBalsAcntID = 0;
                                                $jrnlBatchDfltBalsAcnt = "";
                                                $jrnlBatchCreationDate = $gnrtdTrnsDate;
                                                $jrnlBatchSource = "Manual";
                                                $jrnlBatchDfltCurID = $fnccurid;
                                                $jrnlBatchDfltCurNm = $fnccurnm;
                                                $jrnlBatchDfltTrnsDte = $jrnlBatchCreationDate;
                                                $mkReadOnly = "";
                                                $mkRmrkReadOnly = "";
                                                if ($sbmtdJrnlBatchID > 0) {
                                                    //Important! Must Check if One also has prmsn to Edit brought Trns Hdr ID
                                                    $result = get_One_BatchDet($sbmtdJrnlBatchID);
                                                    if ($row = loc_db_fetch_array($result)) {
                                                        $rqStatus = ($row[3] == "1") ? "Posted" : "Not Posted";
                                                        $rqstVldty = $row[6];
                                                        $autoPostStatus = $row[7];
                                                        $voidedJrnlBatchID = (float) $row[12];
                                                        $gnrtdTrnsNo = $row[1];
                                                        $jrnlBatchDesc = $row[2];
                                                        $jrnlBatchRvrslRsn = $row[15];
                                                        $jrnlBatchDfltCurID = $row[16];
                                                        $jrnlBatchDfltCurNm = $row[17];
                                                        $jrnlBatchDfltTrnsDte = $row[18];
                                                        $jrnlBatchDbtAmnt = get_Batch_DbtSum($sbmtdJrnlBatchID);
                                                        $jrnlBatchCrdtAmnt = get_Batch_CrdtSum($sbmtdJrnlBatchID);
                                                        $jrnlBatchNetAmnt = abs($jrnlBatchDbtAmnt - $jrnlBatchCrdtAmnt);
                                                        $jrnlBatchDfltBalsAcntID = (float) $row[13];
                                                        $jrnlBatchDfltBalsAcnt = $row[14];
                                                        $jrnlBatchCreationDate = $row[4];
                                                        $jrnlBatchSource = $row[5];
                                                        if ($jrnlBatchDfltCurID <= 0) {
                                                            $jrnlBatchDfltCurID = $fnccurid;
                                                            $jrnlBatchDfltCurNm = $fnccurnm;
                                                            $jrnlBatchDfltTrnsDte = $jrnlBatchCreationDate;
                                                        }
                                                        if ($rqStatus == "Not Posted") {
                                                            $rqstatusColor = "red";
                                                            if ($voidedJrnlBatchID <= 0) {
                                                                $mkReadOnly = "";
                                                                $mkRmrkReadOnly = "";
                                                            } else {
                                                                $mkReadOnly = "readonly=\"true\"";
                                                                $mkRmrkReadOnly = "";
                                                            }
                                                        } else {
                                                            $canEdt = FALSE;
                                                            $rqstatusColor = "green";
                                                            $mkReadOnly = "readonly=\"true\"";
                                                            if ($rqStatus != "Posted" && $voidedJrnlBatchID <= 0) {
                                                                $mkRmrkReadOnly = "readonly=\"true\"";
                                                            }
                                                        }
                                                        if ($rqstVldty == "VALID") {
                                                            $rqstVldtyColor = "green";
                                                        } else {
                                                            $rqstVldtyColor = "red";
                                                        }
                                                        if ($autoPostStatus == "Not Monitored") {
                                                            $autoPostStatusColor = "black";
                                                        } else {
                                                            $autoPostStatusColor = "green";
                                                        }
                                                        if ($voidedJrnlBatchID > 0) {
                                                            $jrnlBatchDesc = $jrnlBatchRvrslRsn;
                                                        }
                                                    }
                                                } else {
                                                    //$sbmtdJrnlBatchID = getNewJrnlBatchID();
                                                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                                                    if ($usrTrnsCode == "") {
                                                        $usrTrnsCode = "XX";
                                                    }
                                                    $userAccntName = getGnrlRecNm("sec.sec_users", "user_id", "user_name", $usrID);
                                                    $gnrtdTrnsNo1 = substr($userAccntName, 0, 4) . "-" . $usrTrnsCode . "-" . $dte . "-";
                                                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad((getRecCount_LstNum("accb.accb_trnsctn_batches",
                                                                            "batch_name", "batch_id", $gnrtdTrnsNo1 . "%") + 1), 3, '0',
                                                                    STR_PAD_LEFT);
                                                    /* createBatch($orgID, $gnrtdTrnsNo, $jrnlBatchDesc, "Manual", "VALID",
                                                      $voidedJrnlBatchID, "0", $jrnlBatchDfltBalsAcntID, $jrnlBatchRvrslRsn,
                                                      $jrnlBatchDfltCurID, $jrnlBatchDfltTrnsDte);
                                                      $sbmtdJrnlBatchID = getBatchID($gnrtdTrnsNo, $orgID); */
                                                }
                                                $style1 = "color:green;";
                                                if ($jrnlBatchNetAmnt != 0) {
                                                    $style1 = "color:red;";
                                                }
                                                if (strpos($jrnlBatchSource, "Manual") === FALSE) {
                                                    $canEdt = FALSE;
                                                    $mkReadOnly = "readonly=\"true\"";
                                                    $mkRmrkReadOnly = "readonly=\"true\"";
                                                }
                                                $reportTitle = "Journal Entry Batch";
                                                $reportName = "Journal Entry Batch";
                                                $rptID = getRptID($reportName);
                                                $prmID1 = getParamIDUseSQLRep("{:batch_id}", $rptID);
                                                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                $trnsID = $sbmtdJrnlBatchID;
                                                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                                                $paramStr = urlencode($paramRepsNVals);

                                                $reportTitle1 = "Post GL Transaction Batches-Web";
                                                $reportName1 = "Post GL Transaction Batches-Web";
                                                $rptID1 = getRptID($reportName1);
                                                $prmID11 = getParamIDUseSQLRep("{:p_batch_id}", $rptID1);
                                                $paramRepsNVals1 = $prmID11 . "~" . $sbmtdJrnlBatchID . "|-130~" . $reportTitle1 . "|-190~HTML";
                                                $paramStr1 = urlencode($paramRepsNVals1);
                                                ?>
                                                <form class="form-horizontal" id="oneJrnlBatchEDTForm">
                                                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                                                        <!--<legend class="basic_person_lg">Transaction Header Information</legend>-->
                                                        <div class="row" style="margin-top:5px;">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <div class="col-md-2">
                                                                        <label style="margin-bottom:0px !important;">No.:</label>
                                                                    </div>
                                                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltLovID" name="sbmtdTempltLovID" value="-1" readonly="true">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltUsrID" name="sbmtdTempltUsrID" value="<?php echo $usrID; ?>" readonly="true">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltTrnsCount" name="sbmtdTempltTrnsCount" value="2" readonly="true">
                                                                        <input type="text" class="form-control" aria-label="..." id="sbmtdJrnlBatchID" name="sbmtdJrnlBatchID" value="<?php echo $sbmtdJrnlBatchID; ?>" readonly="true">
                                                                        <input class="form-control" type="hidden" id="voidedJrnlBatchID" value="<?php echo $voidedJrnlBatchID; ?>"/>
                                                                    </div>
                                                                    <div class="col-md-7" style="padding:0px 15px 0px 1px;">
                                                                        <input type="text" class="form-control" aria-label="..." id="jrnlBatchNum" name="jrnlBatchNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-md-2">
                                                                        <label style="margin-bottom:0px !important;">Date:</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <div class="col-md-3" style="padding:0px 0px 0px 0px !important;">
                                                                            <label class="btn btn-info btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $jrnlBatchDfltCurNm; ?>', 'jrnlBatchDfltCurNm', '', 'clear', 0, '', function () {
                                                                                        $('#jrnlBatchAmountCrncy').html($('#jrnlBatchDfltCurNm').val());
                                                                                    });">
                                                                                <span class="" style="font-size: 20px !important;" id="jrnlBatchAmountCrncy"><?php echo $jrnlBatchDfltCurNm; ?></span>
                                                                            </label>
                                                                            <input type="hidden" id="jrnlBatchDfltCurNm" value="<?php echo $jrnlBatchDfltCurNm; ?>">
                                                                        </div>
                                                                        <div class="col-md-9 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 0px 0px 0px !important;">
                                                                            <input class="form-control" size="16" type="text" id="jrnlBatchDfltTrnsDte" name="jrnlBatchDfltTrnsDte" value="<?php echo $jrnlBatchDfltTrnsDte; ?>" placeholder="Transactions Date">
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-md-2">
                                                                        <label style="margin-bottom:0px !important;">Validity:</label>
                                                                    </div>
                                                                    <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                                                        <input type="text" class="form-control" aria-label="..." id="rqstVldty" name="rqstVldty" value="<?php echo $rqstVldty; ?>" readonly="true" style="font-weight:bold;color:<?php echo $rqstVldtyColor; ?>">
                                                                    </div>
                                                                    <div class="col-md-6" style="padding:0px 15px 0px 1px;">                             
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;width:100% !important;" id="myJrnlBatchStatusBtn"><span style="font-weight:bold;height:30px;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php echo $rqStatus; ?></span></button>
                                                                        <input type="hidden" class="form-control" aria-label="..." id="autoPostStatus" name="autoPostStatus" value="<?php echo $autoPostStatus; ?>" readonly="true" style="font-weight:bold;color:<?php echo $autoPostStatusColor; ?>">                                    
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">                                                               
                                                                <div class="form-group">
                                                                    <div class="col-md-2">
                                                                        <label style="margin-bottom:0px !important;">Remarks:</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <div class="input-group"  style="width:100%;">
                                                                            <textarea class="form-control rqrdFld" rows="5" cols="20" id="jrnlBatchDesc" name="jrnlBatchDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $jrnlBatchDesc; ?></textarea>
                                                                            <input class="form-control" type="hidden" id="jrnlBatchDesc1" value="<?php echo $jrnlBatchDesc; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('jrnlBatchDesc');" style="max-width:30px;width:30px;">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file input-group-addon"  style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                                                <span style="font-weight:bold;<?php echo $forecolors; ?>">Total Debits:&nbsp;&nbsp;</span>
                                                                            </label>
                                                                            <input class="form-control" id="jrnlBatchDbtAmnt" type = "text" placeholder="0.00" value="<?php
                                                                            echo number_format($jrnlBatchDbtAmnt, 2);
                                                                            ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file input-group-addon"  style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                                                <span style="font-weight:bold;<?php echo $forecolors; ?>">Total Credits:&nbsp;</span>
                                                                            </label>
                                                                            <input class="form-control" id="jrnlBatchCrdtAmnt" type = "text" placeholder="0.00" value="<?php
                                                                            echo number_format($jrnlBatchCrdtAmnt, 2);
                                                                            ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file input-group-addon"  style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                                                <span style="font-weight:bold;<?php echo $forecolors; ?>">Difference:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                                            </label>
                                                                            <input class="form-control" id="jrnlBatchNetAmnt" type = "text" placeholder="0.00" value="<?php
                                                                            echo number_format($jrnlBatchNetAmnt, 2);
                                                                            ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                                        </div>
                                                                    </div>
                                                                </div>   
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset class="">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                        <?php if ($canEdt) { ?>
                                                                                            <button id="addNwJrnlBatchDetBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewJrnlBatcRows('oneJrnlBatchDetLinesTable', 0, '<?php echo $nwRowHtml2; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Detailed Transaction Line">
                                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>                               
                                                                                        <?php } ?>
                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneJrnlBatchDocsForm(<?php echo $sbmtdJrnlBatchID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button> 
                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneJrnlBatchForm(<?php echo $sbmtdJrnlBatchID; ?>, 11, 'ReloadDialog', -1, '', '#accbRcnclJrnlTrnsLines');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            Print
                                                                                        </button>
                                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbJrnlBatchDsplySze" style="margin-bottom: -3px;height:30px;max-width:70px !important;display:inline-block;" onchange="getOneJrnlBatchForm(<?php echo $sbmtdJrnlBatchID; ?>, 1, 'ReloadDialog', -1, '', '#accbRcnclJrnlTrnsLines');" data-toggle="tooltip" title="No. of Records to Display">                            
                                                                                            <?php
                                                                                            $valslctdArry = array("", "", "", "", "", "",
                                                                                                "", "");
                                                                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                                                                                500, 1000, 50000, 1000000000);
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
                                                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                                                            <?php
                                                                                            if ($rqStatus == "Not Posted") {
                                                                                                ?>
                                                                                                <?php
                                                                                                if ($voidedJrnlBatchID <= 0) {
                                                                                                    ?> 
                                                                                                    <?php if ($canEdt) { ?>
                                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlBatchForm('<?php echo $fnccurnm; ?>', 0, -1, -1, '', -1, '','TBALS');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                                                                    <?php } ?>
                                                                                                    <?php
                                                                                                }
                                                                                                if ($canPost && $sbmtdJrnlBatchID > 0) {
                                                                                                    ?>  
                                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlBatchForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>', -1, '','TBALS');"><img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post Batch&nbsp;</button>
                                                                                                    <?php
                                                                                                } else if ($sbmtdJrnlBatchID > 0) {
                                                                                                    ?>  
                                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="bootbox.alert({title: 'System Alert!', size: 'small', message: 'Permission Denied!'});"><img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post Batch&nbsp;</button>
                                                                                                    <?php
                                                                                                }
                                                                                            } else if ($rqStatus == "Posted") {
                                                                                                ?>
                                                                                                <button id="fnlzeRvrslJrnlBatchBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlRvrsBatchForm('<?php echo $fnccurnm; ?>', 1, -1, '');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                                                                   
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        </div>
                                                                                    </div>                    
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneJrnlBatchLnsTblSctn"> 
                                                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">    
                                                                        <div id="jrnlBatchDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <table class="table table-striped table-bordered table-responsive" id="oneJrnlBatchDetLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>No.</th>
                                                                                                <th style="min-width:220px;">GL Transaction Account</th>
                                                                                                <th style="min-width:250px;">Narration/Remarks</th>
                                                                                                <th>CUR.</th>
                                                                                                <th style="text-align: right;">Debit Amount</th>
                                                                                                <th style="text-align: right;">Credit Amount</th>
                                                                                                <th style="max-width:20px;width:20px;">...</th>
                                                                                                <th>Transaction Date</th>
                                                                                                <th style="max-width:60px;width:60px;">Ref. Doc. No.</th>
                                                                                                <th style="max-width:20px;width:20px;">...</th>
                                                                                                <?php
                                                                                                if ($canVwRcHstry === true) {
                                                                                                    ?>
                                                                                                    <th style="max-width:20px;width:20px;">...</th>
                                                                                                <?php } ?>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>   
                                                                                            <?php
                                                                                            $cntr = 0;
                                                                                            $resultRw = get_One_Batch_Trns($sbmtdJrnlBatchID,
                                                                                                    $lmtSze);
                                                                                            $maxNoRows = loc_db_num_rows($resultRw);
                                                                                            $ttlTrsctnDbtAmnt = 0;
                                                                                            $ttlTrsctnCrdtAmnt = 0;
                                                                                            $ttlTrsctnNetAmnt = 0;
                                                                                            $ornlMkReadOnly = $mkReadOnly;
                                                                                            $trnsBrkDwnVType = "VIEW";
                                                                                            while ($cntr < $maxNoRows) {
                                                                                                $trsctnLineID = -1;
                                                                                                $trsctnSmryLineID = -1;
                                                                                                $trsctnLineDesc = "";
                                                                                                $trsctnLineRefDoc = "";
                                                                                                $funcCurID = -1;
                                                                                                $funcCurNm = "";
                                                                                                $trsctnDbtAmnt = 0;
                                                                                                $trsctnCrdtAmnt = 0;
                                                                                                $trsctnNetAmnt = 0;
                                                                                                $entrdCurID = -1;
                                                                                                $entrdAmnt = 0.00;
                                                                                                $entrdCurNm = "";
                                                                                                $trsctnAcntID = -1;
                                                                                                $trsctnAcntNm = -1;
                                                                                                $acntCrncyRate = 0;
                                                                                                $funcCrncyRate = 0;
                                                                                                $trsctnLineStatus = "0";
                                                                                                $trsctnLineDate = "";
                                                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                                    $trsctnLineID = (float) $rowRw[0];
                                                                                                    $trsctnSmryLineID = (float) $rowRw[24];
                                                                                                    if ($trsctnSmryLineID > 0) {
                                                                                                        $canEdt = FALSE;
                                                                                                        $mkReadOnly = "readonly=\"true\"";
                                                                                                    } else {
                                                                                                        $mkReadOnly = $ornlMkReadOnly;
                                                                                                    }
                                                                                                    if ($mkReadOnly != "") {
                                                                                                        $trnsBrkDwnVType = "VIEW";
                                                                                                    } else {
                                                                                                        $trnsBrkDwnVType = "EDIT";
                                                                                                    }
                                                                                                    $trsctnLineDesc = $rowRw[3];
                                                                                                    $trsctnLineRefDoc = $rowRw[20];
                                                                                                    $funcCurID = (int) $rowRw[7];
                                                                                                    $funcCurNm = $rowRw[21];
                                                                                                    $trsctnDbtAmnt = (float) $rowRw[4];
                                                                                                    $trsctnCrdtAmnt = (float) $rowRw[5];
                                                                                                    $trsctnNetAmnt = (float) $rowRw[10];
                                                                                                    $entrdCurID = (int) $rowRw[14];
                                                                                                    $entrdAmnt = (float) $rowRw[12];
                                                                                                    $entrdCurNm = $rowRw[13];
                                                                                                    $trsctnAcntID = $rowRw[9];
                                                                                                    $trsctnAcntNm = $rowRw[1] . "." . $rowRw[2];
                                                                                                    $acntCrncyRate = (float) $rowRw[19];
                                                                                                    $funcCrncyRate = (float) $rowRw[18];
                                                                                                    $trsctnLineStatus = $rowRw[11];
                                                                                                    $trsctnLineDate = $rowRw[6];

                                                                                                    $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnDbtAmnt;
                                                                                                    $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnCrdtAmnt;
                                                                                                    $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnNetAmnt;
                                                                                                    $isPosted = ($row[11] == "1") ? "true" : "false";
                                                                                                }
                                                                                                $cntr += 1;
                                                                                                ?>
                                                                                                <tr id="oneJrnlBatchDetRow_<?php echo $cntr; ?>">                                    
                                                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>    
                                                                                                    <td class="lovtd">
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">    
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsSmryLnID" value="<?php echo $trsctnSmryLineID; ?>" style="width:100% !important;"> 
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                                                                                        <?php
                                                                                                        if ($canEdt === true) {
                                                                                                            ?>
                                                                                                            <div class="input-group" style="width:100% !important;">
                                                                                                                <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountID', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm', 'clear', 1, '', function () {

                                                                                                                        });">
                                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                                </label>
                                                                                                            </div>      
                                                                                                        <?php } else { ?>
                                                                                                            <span><?php echo $trsctnAcntNm; ?></span>
                                                                                                        <?php } ?>                                             
                                                                                                    </td>                                          
                                                                                                    <td class="lovtd"  style="">
                                                                                                        <?php
                                                                                                        if ($canEdt === true) {
                                                                                                            ?>
                                                                                                            <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchDetLinesTable', 'jbDetDesc');">                                                    
                                                                                                        <?php } else { ?>
                                                                                                            <span><?php echo $trsctnLineDesc; ?></span>
                                                                                                        <?php } ?>    
                                                                                                    </td>                                                  
                                                                                                    <td class="lovtd">
                                                                                                        <div class="" style="width:100% !important;">
                                                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $funcCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                                                            <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                                        $('#oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                                                    });">
                                                                                                                <span class="" id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $funcCurNm; ?></span>
                                                                                                            </label>
                                                                                                        </div>                                              
                                                                                                    </td>
                                                                                                    <td class="lovtd">
                                                                                                        <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt" value="<?php
                                                                                                        echo number_format($trsctnDbtAmnt, 2);
                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchDetTtl();">                                                    
                                                                                                    </td>
                                                                                                    <td class="lovtd">
                                                                                                        <input type="text" class="form-control rqrdFld jbDetCrdt" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt" value="<?php
                                                                                                        echo number_format($trsctnCrdtAmnt,
                                                                                                                2);
                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchDetTtl();">                                                    
                                                                                                    </td>
                                                                                                    <td class="lovtd">
                                                                                                        <?php
                                                                                                        if ($rowRw[22] != ",") {
                                                                                                            ?>
                                                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Interface Table Breakdown" 
                                                                                                                    onclick="getAccbTransSrchDet(<?php echo $trsctnLineID; ?>, 'Transaction ID', <?php echo $isPosted; ?>, true, '', '', 'Breakdown of Source Transactions', 'ShowDialog', function () {});" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                                            </button>
                                                                                                        <?php } else { ?>
                                                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                                                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', '', '');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                                            </button>
                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                    <td class="lovtd">
                                                                                                        <?php
                                                                                                        if ($canEdt === true) {
                                                                                                            ?>
                                                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                                                                <input class="form-control" size="16" type="text" id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>">
                                                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                            </div> 
                                                                                                        <?php } else { ?>
                                                                                                            <span><?php echo $trsctnLineDate; ?></span>
                                                                                                        <?php } ?>                                                         
                                                                                                    </td>                                         
                                                                                                    <td class="lovtd" style="">
                                                                                                        <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchDetLinesTable', 'jbDetRfDc');">                                                    
                                                                                                    </td>
                                                                                                    <td class="lovtd">
                                                                                                        <?php
                                                                                                        if ($canDel === true && $canEdt === true) {
                                                                                                            ?>
                                                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchDetLn('oneJrnlBatchDetRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Journal Line">
                                                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                                            </button>
                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                    <?php
                                                                                                    if ($canVwRcHstry === true) {
                                                                                                        ?>
                                                                                                        <td class="lovtd">
                                                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                            echo urlencode(encrypt1(($trsctnLineID . "|accb.accb_trnsctn_details|transctn_id"),
                                                                                                                            $smplTokenWord1));
                                                                                                            ?>');" style="padding:2px !important;">
                                                                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                            </button>
                                                                                                        </td>
                                                                                                    <?php } ?>
                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                            $mkReadOnly = $ornlMkReadOnly;
                                                                                            ?>
                                                                                        </tbody>
                                                                                        <tfoot>                                                            
                                                                                            <tr>
                                                                                                <th>&nbsp;</th>
                                                                                                <th>&nbsp;</th>
                                                                                                <th>TOTALS:</th>
                                                                                                <th style=""><?php echo $crncyIDNm; ?></th>
                                                                                                <th style="text-align: right;">
                                                                                                    <?php
                                                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                                                                            2, '.', ',') . "</span>";
                                                                                                    ?>
                                                                                                    <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                                                                </th>
                                                                                                <th style="text-align: right;">
                                                                                                    <?php
                                                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                                                                            2, '.', ',') . "</span>";
                                                                                                    ?>
                                                                                                    <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                                                                </th>
                                                                                                <th style="">&nbsp;</th>                                           
                                                                                                <th style="">&nbsp;</th>
                                                                                                <th style="">&nbsp;</th>
                                                                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                                                <?php
                                                                                                if ($canVwRcHstry === true) {
                                                                                                    ?>
                                                                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                                                <?php } ?>
                                                                                            </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>   
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 11) {
                $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                //echo $ymdtme;
                $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                $accbFSRptDte1 = substr($ymdtme2, 0, 11);
                $canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
                $canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canVoid = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canPost = test_prmssns($dfltPrvldgs[21], $mdlNm);


                $nwRowHtml2 = "<tr id=\"oneJrnlBatchDetRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneJrnlBatchDetLinesTable tr').index(this));\">"
                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                        . "<td class=\"lovtd\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_AccountID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">    
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsSmryLnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_AccountNm\" name=\"oneJrnlBatchDetRow_WWW123WWW_AccountNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchDetRow_WWW123WWW_AccountID', 'oneJrnlBatchDetRow_WWW123WWW_AccountNm', 'clear', 1, '', function () {

                                                                                                                });\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                        </div>                                              
                                                                    </td>                                          
                                                                    <td class=\"lovtd\"  style=\"\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_LineDesc\" name=\"oneJrnlBatchDetRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_LineDesc', 'oneJrnlBatchDetLinesTable', 'jbDetDesc');\">                                                    
                                                                    </td>                                                  
                                                                    <td class=\"lovtd\">
                                                                        <div class=\"\" style=\"width:100% !important;\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm\" name=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm\" value=\"" . $fnccurnm . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                            <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                            $('#oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm1').html($('#oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm').val());
                                                                                                        });\">
                                                                                <span class=\"\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm1\">" . $fnccurnm . "</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_DebitAmnt\" name=\"oneJrnlBatchDetRow_WWW123WWW_DebitAmnt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_DebitAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchDetTtl();\">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetCrdt\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_CreditAmnt\" name=\"oneJrnlBatchDetRow_WWW123WWW_CreditAmnt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_CreditAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchDetTtl();\">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Denominational Breakdown\" 
                                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneJrnlBatchDetRow_WWW123WWW_DebitAmnt', 'oneJrnlBatchDetRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\"> 
                                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                            </button>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneJrnlBatchDetRow_WWW123WWW_TransDte\" value=\"" . $accbFSRptDte1 . " 12:00:00\">
                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                            </div>                                                        
                                                                    </td>                                         
                                                                    <td class=\"lovtd\"  style=\"\">
                                                                        <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_RefDoc\" name=\"oneJrnlBatchDetRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_RefDoc', 'oneJrnlBatchDetLinesTable', 'jbDetRfDc');\">                                                    
                                                                    </td>";
                if ($canDel === true && $canEdt === true) {
                    $nwRowHtml2 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbJrnlBatchDetLn('oneJrnlBatchDetRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Journal Line\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                }
                if ($canVwRcHstry === true) {
                    $nwRowHtml2 .= "<td class=\"lovtd\">&nbsp;</td>";
                }
                $nwRowHtml2 .= "</tr>";
                $nwRowHtml2 = urlencode($nwRowHtml2);
                ?>
                <div class="row"> 
                    <div class="col-md-12"> 
                        <?php
                        $lmtSze = isset($_POST['accbJrnlBatchDsplySze']) ? cleanInputData($_POST['accbJrnlBatchDsplySze']) : 50;
                        $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                        if (!$canAdd || ($sbmtdJrnlBatchID > 0 && !$canEdt)) {
                            restricted();
                            exit();
                        }
                        $orgnlJrnlBatchID = $sbmtdJrnlBatchID;
                        $rqStatus = "Not Posted";
                        $rqstatusColor = "red";
                        $rqstVldty = "VALID";
                        $rqstVldtyColor = "green";
                        $autoPostStatus = "Not Monitored";
                        $autoPostStatusColor = "black";
                        $dte = date('ymd');
                        $gnrtdTrnsNo = "";
                        $gnrtdTrnsDate = $gnrlTrnsDteDMYHMS; //date('d-M-Y H:i:s');
                        $gnrtdTrnsDate1 = $gnrlTrnsDteYMDHMS; //date('Y-m-d H:i:s');
                        $crncyID = $fnccurid;
                        $crncyIDNm = $fnccurnm;
                        $voidedJrnlBatchID = -1;
                        $jrnlBatchDesc = "";
                        $jrnlBatchRvrslRsn = "";
                        $jrnlBatchNetAmnt = 0;
                        $jrnlBatchDbtAmnt = 0;
                        $jrnlBatchCrdtAmnt = 0;
                        $jrnlBatchDfltBalsAcntID = 0;
                        $jrnlBatchDfltBalsAcnt = "";
                        $jrnlBatchCreationDate = $gnrtdTrnsDate;
                        $jrnlBatchSource = "Manual";
                        $jrnlBatchDfltCurID = $fnccurid;
                        $jrnlBatchDfltCurNm = $fnccurnm;
                        $jrnlBatchDfltTrnsDte = $jrnlBatchCreationDate;
                        $mkReadOnly = "";
                        $mkRmrkReadOnly = "";
                        if ($sbmtdJrnlBatchID > 0) {
                            //Important! Must Check if One also has prmsn to Edit brought Trns Hdr ID
                            $result = get_One_BatchDet($sbmtdJrnlBatchID);
                            if ($row = loc_db_fetch_array($result)) {
                                $rqStatus = ($row[3] == "1") ? "Posted" : "Not Posted";
                                $rqstVldty = $row[6];
                                $autoPostStatus = $row[7];
                                $voidedJrnlBatchID = (float) $row[12];
                                $gnrtdTrnsNo = $row[1];
                                $jrnlBatchDesc = $row[2];
                                $jrnlBatchRvrslRsn = $row[15];
                                $jrnlBatchDfltCurID = $row[16];
                                $jrnlBatchDfltCurNm = $row[17];
                                $jrnlBatchDfltTrnsDte = $row[18];
                                $jrnlBatchDbtAmnt = get_Batch_DbtSum($sbmtdJrnlBatchID);
                                $jrnlBatchCrdtAmnt = get_Batch_CrdtSum($sbmtdJrnlBatchID);
                                $jrnlBatchNetAmnt = abs($jrnlBatchDbtAmnt - $jrnlBatchCrdtAmnt);
                                $jrnlBatchDfltBalsAcntID = (float) $row[13];
                                $jrnlBatchDfltBalsAcnt = $row[14];
                                $jrnlBatchCreationDate = $row[4];
                                $jrnlBatchSource = $row[5];
                                if ($jrnlBatchDfltCurID <= 0) {
                                    $jrnlBatchDfltCurID = $fnccurid;
                                    $jrnlBatchDfltCurNm = $fnccurnm;
                                    $jrnlBatchDfltTrnsDte = $jrnlBatchCreationDate;
                                }
                                if ($rqStatus == "Not Posted") {
                                    $rqstatusColor = "red";
                                    if ($voidedJrnlBatchID <= 0) {
                                        $mkReadOnly = "";
                                        $mkRmrkReadOnly = "";
                                    } else {
                                        $mkReadOnly = "readonly=\"true\"";
                                        $mkRmrkReadOnly = "";
                                    }
                                } else {
                                    $canEdt = FALSE;
                                    $rqstatusColor = "green";
                                    $mkReadOnly = "readonly=\"true\"";
                                    if ($rqStatus != "Posted" && $voidedJrnlBatchID <= 0) {
                                        $mkRmrkReadOnly = "readonly=\"true\"";
                                    }
                                }
                                if ($rqstVldty == "VALID") {
                                    $rqstVldtyColor = "green";
                                } else {
                                    $rqstVldtyColor = "red";
                                }
                                if ($autoPostStatus == "Not Monitored") {
                                    $autoPostStatusColor = "black";
                                } else {
                                    $autoPostStatusColor = "green";
                                }
                                if ($voidedJrnlBatchID > 0) {
                                    $jrnlBatchDesc = $jrnlBatchRvrslRsn;
                                }
                            }
                        } else {
                            //$sbmtdJrnlBatchID = getNewJrnlBatchID();
                            $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                            if ($usrTrnsCode == "") {
                                $usrTrnsCode = "XX";
                            }
                            $userAccntName = getGnrlRecNm("sec.sec_users", "user_id", "user_name", $usrID);
                            $gnrtdTrnsNo1 = substr($userAccntName, 0, 4) . "-" . $usrTrnsCode . "-" . $dte . "-";
                            $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad((getRecCount_LstNum("accb.accb_trnsctn_batches", "batch_name",
                                                    "batch_id", $gnrtdTrnsNo1 . "%") + 1), 3, '0', STR_PAD_LEFT);
                            /* createBatch($orgID, $gnrtdTrnsNo, $jrnlBatchDesc, "Manual", "VALID",
                              $voidedJrnlBatchID, "0", $jrnlBatchDfltBalsAcntID, $jrnlBatchRvrslRsn,
                              $jrnlBatchDfltCurID, $jrnlBatchDfltTrnsDte);
                              $sbmtdJrnlBatchID = getBatchID($gnrtdTrnsNo, $orgID); */
                        }
                        $style1 = "color:green;";
                        if ($jrnlBatchNetAmnt != 0) {
                            $style1 = "color:red;";
                        }
                        if (strpos($jrnlBatchSource, "Manual") === FALSE) {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                        }
                        $reportTitle = "Journal Entry Batch";
                        $reportName = "Journal Entry Batch";
                        $rptID = getRptID($reportName);
                        $prmID1 = getParamIDUseSQLRep("{:batch_id}", $rptID);
                        $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                        $trnsID = $sbmtdJrnlBatchID;
                        $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                        $paramStr = urlencode($paramRepsNVals);

                        $reportTitle1 = "Post GL Transaction Batches-Web";
                        $reportName1 = "Post GL Transaction Batches-Web";
                        $rptID1 = getRptID($reportName1);
                        $prmID11 = getParamIDUseSQLRep("{:p_batch_id}", $rptID1);
                        $paramRepsNVals1 = $prmID11 . "~" . $sbmtdJrnlBatchID . "|-130~" . $reportTitle1 . "|-190~HTML";
                        $paramStr1 = urlencode($paramRepsNVals1);
                        ?>
                        <form class="form-horizontal" id="oneJrnlBatchEDTForm">
                            <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                                <!--<legend class="basic_person_lg">Transaction Header Information</legend>-->
                                <div class="row" style="margin-top:5px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <label style="margin-bottom:0px !important;">No.:</label>
                                            </div>
                                            <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                                <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltLovID" name="sbmtdTempltLovID" value="-1" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltUsrID" name="sbmtdTempltUsrID" value="<?php echo $usrID; ?>" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltTrnsCount" name="sbmtdTempltTrnsCount" value="2" readonly="true">
                                                <input type="text" class="form-control" aria-label="..." id="sbmtdJrnlBatchID" name="sbmtdJrnlBatchID" value="<?php echo $sbmtdJrnlBatchID; ?>" readonly="true">
                                                <input class="form-control" type="hidden" id="voidedJrnlBatchID" value="<?php echo $voidedJrnlBatchID; ?>"/>
                                            </div>
                                            <div class="col-md-7" style="padding:0px 15px 0px 1px;">
                                                <input type="text" class="form-control" aria-label="..." id="jrnlBatchNum" name="jrnlBatchNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <label style="margin-bottom:0px !important;">Date:</label>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="col-md-3" style="padding:0px 0px 0px 0px !important;">
                                                    <label class="btn btn-info btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $jrnlBatchDfltCurNm; ?>', 'jrnlBatchDfltCurNm', '', 'clear', 0, '', function () {
                                                                $('#jrnlBatchAmountCrncy').html($('#jrnlBatchDfltCurNm').val());
                                                            });">
                                                        <span class="" style="font-size: 20px !important;" id="jrnlBatchAmountCrncy"><?php echo $jrnlBatchDfltCurNm; ?></span>
                                                    </label>
                                                    <input type="hidden" id="jrnlBatchDfltCurNm" value="<?php echo $jrnlBatchDfltCurNm; ?>">
                                                </div>
                                                <div class="col-md-9 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 0px 0px 0px !important;">
                                                    <input class="form-control" size="16" type="text" id="jrnlBatchDfltTrnsDte" name="jrnlBatchDfltTrnsDte" value="<?php echo $jrnlBatchDfltTrnsDte; ?>" placeholder="Transactions Date">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <label style="margin-bottom:0px !important;">Validity:</label>
                                            </div>
                                            <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                                <input type="text" class="form-control" aria-label="..." id="rqstVldty" name="rqstVldty" value="<?php echo $rqstVldty; ?>" readonly="true" style="font-weight:bold;color:<?php echo $rqstVldtyColor; ?>">
                                            </div>
                                            <div class="col-md-6" style="padding:0px 15px 0px 1px;">                             
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;width:100% !important;" id="myJrnlBatchStatusBtn"><span style="font-weight:bold;height:30px;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php echo $rqStatus; ?></span></button>
                                                <input type="hidden" class="form-control" aria-label="..." id="autoPostStatus" name="autoPostStatus" value="<?php echo $autoPostStatus; ?>" readonly="true" style="font-weight:bold;color:<?php echo $autoPostStatusColor; ?>">                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                                               
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <label style="margin-bottom:0px !important;">Remarks:</label>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="input-group"  style="width:100%;">
                                                    <textarea class="form-control rqrdFld" rows="5" cols="20" id="jrnlBatchDesc" name="jrnlBatchDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $jrnlBatchDesc; ?></textarea>
                                                    <input class="form-control" type="hidden" id="jrnlBatchDesc1" value="<?php echo $jrnlBatchDesc; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('jrnlBatchDesc');" style="max-width:30px;width:30px;">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="input-group" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon"  style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                        <span style="font-weight:bold;<?php echo $forecolors; ?>">Total Debits:&nbsp;&nbsp;</span>
                                                    </label>
                                                    <input class="form-control" id="jrnlBatchDbtAmnt" type = "text" placeholder="0.00" value="<?php
                                                    echo number_format($jrnlBatchDbtAmnt, 2);
                                                    ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="input-group" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon"  style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                        <span style="font-weight:bold;<?php echo $forecolors; ?>">Total Credits:&nbsp;</span>
                                                    </label>
                                                    <input class="form-control" id="jrnlBatchCrdtAmnt" type = "text" placeholder="0.00" value="<?php
                                                    echo number_format($jrnlBatchCrdtAmnt, 2);
                                                    ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="input-group" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon"  style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                        <span style="font-weight:bold;<?php echo $forecolors; ?>">Difference:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                    </label>
                                                    <input class="form-control" id="jrnlBatchNetAmnt" type = "text" placeholder="0.00" value="<?php
                                                    echo number_format($jrnlBatchNetAmnt, 2);
                                                    ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                </div>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                            <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                <?php if ($canEdt) { ?>
                                                                    <button id="addNwJrnlBatchDetBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewJrnlBatcRows('oneJrnlBatchDetLinesTable', 0, '<?php echo $nwRowHtml2; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Detailed Transaction Line">
                                                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button>                               
                                                                <?php } ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneJrnlBatchDocsForm(<?php echo $sbmtdJrnlBatchID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                                    <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button> 
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneJrnlBatchForm(<?php echo $sbmtdJrnlBatchID; ?>, 11, 'ReloadDialog', -1, '', '#accbRcnclJrnlTrnsLines');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Print
                                                                </button>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbJrnlBatchDsplySze" style="margin-bottom: -3px;height:30px;max-width:70px !important;display:inline-block;" onchange="getOneJrnlBatchForm(<?php echo $sbmtdJrnlBatchID; ?>, 1, 'ReloadDialog', -1, '', '#accbRcnclJrnlTrnsLines');" data-toggle="tooltip" title="No. of Records to Display">                            
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "", "",
                                                                        "", "");
                                                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                                                        500, 1000, 50000, 1000000000);
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
                                                            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                                                <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                                    <?php
                                                                    if ($rqStatus == "Not Posted") {
                                                                        ?>
                                                                        <?php
                                                                        if ($voidedJrnlBatchID <= 0) {
                                                                            ?> 
                                                                            <?php if ($canEdt) { ?>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlBatchForm('<?php echo $fnccurnm; ?>', 0, -1, -1, '', -1, '','TBALS');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                                            <?php } ?>
                                                                            <?php
                                                                        }
                                                                        if ($canPost && $sbmtdJrnlBatchID > 0) {
                                                                            ?>  
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlBatchForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>', -1, '','TBALS');"><img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post Batch&nbsp;</button>
                                                                            <?php
                                                                        } else if ($sbmtdJrnlBatchID > 0) {
                                                                            ?>  
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="bootbox.alert({title: 'System Alert!', size: 'small', message: 'Permission Denied!'});"><img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post Batch&nbsp;</button>
                                                                            <?php
                                                                        }
                                                                    } else if ($rqStatus == "Posted") {
                                                                        ?>
                                                                        <button id="fnlzeRvrslJrnlBatchBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlRvrsBatchForm('<?php echo $fnccurnm; ?>', 1, -1, '');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                                                                   
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>                    
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneJrnlBatchLnsTblSctn"> 
                                            <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">    
                                                <div id="jrnlBatchDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-bordered table-responsive" id="oneJrnlBatchDetLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th style="min-width:220px;">GL Transaction Account</th>
                                                                        <th style="min-width:250px;">Narration/Remarks</th>
                                                                        <th>CUR.</th>
                                                                        <th style="text-align: right;">Debit Amount</th>
                                                                        <th style="text-align: right;">Credit Amount</th>
                                                                        <th style="max-width:20px;width:20px;">...</th>
                                                                        <th>Transaction Date</th>
                                                                        <th style="max-width:60px;width:60px;">Ref. Doc. No.</th>
                                                                        <th style="max-width:20px;width:20px;">...</th>
                                                                        <?php
                                                                        if ($canVwRcHstry === true) {
                                                                            ?>
                                                                            <th style="max-width:20px;width:20px;">...</th>
                                                                        <?php } ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>   
                                                                    <?php
                                                                    $cntr = 0;
                                                                    $resultRw = get_One_Batch_Trns($sbmtdJrnlBatchID, $lmtSze);
                                                                    $maxNoRows = loc_db_num_rows($resultRw);
                                                                    $ttlTrsctnDbtAmnt = 0;
                                                                    $ttlTrsctnCrdtAmnt = 0;
                                                                    $ttlTrsctnNetAmnt = 0;
                                                                    $ornlMkReadOnly = $mkReadOnly;
                                                                    $trnsBrkDwnVType = "VIEW";
                                                                    while ($cntr < $maxNoRows) {
                                                                        $trsctnLineID = -1;
                                                                        $trsctnSmryLineID = -1;
                                                                        $trsctnLineDesc = "";
                                                                        $trsctnLineRefDoc = "";
                                                                        $funcCurID = -1;
                                                                        $funcCurNm = "";
                                                                        $trsctnDbtAmnt = 0;
                                                                        $trsctnCrdtAmnt = 0;
                                                                        $trsctnNetAmnt = 0;
                                                                        $entrdCurID = -1;
                                                                        $entrdAmnt = 0.00;
                                                                        $entrdCurNm = "";
                                                                        $trsctnAcntID = -1;
                                                                        $trsctnAcntNm = -1;
                                                                        $acntCrncyRate = 0;
                                                                        $funcCrncyRate = 0;
                                                                        $trsctnLineStatus = "0";
                                                                        $trsctnLineDate = "";
                                                                        if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                            $trsctnLineID = (float) $rowRw[0];
                                                                            $trsctnSmryLineID = (float) $rowRw[24];
                                                                            if ($trsctnSmryLineID > 0) {
                                                                                $canEdt = FALSE;
                                                                                $mkReadOnly = "readonly=\"true\"";
                                                                            } else {
                                                                                $mkReadOnly = $ornlMkReadOnly;
                                                                            }
                                                                            if ($mkReadOnly != "") {
                                                                                $trnsBrkDwnVType = "VIEW";
                                                                            } else {
                                                                                $trnsBrkDwnVType = "EDIT";
                                                                            }
                                                                            $trsctnLineDesc = $rowRw[3];
                                                                            $trsctnLineRefDoc = $rowRw[20];
                                                                            $funcCurID = (int) $rowRw[7];
                                                                            $funcCurNm = $rowRw[21];
                                                                            $trsctnDbtAmnt = (float) $rowRw[4];
                                                                            $trsctnCrdtAmnt = (float) $rowRw[5];
                                                                            $trsctnNetAmnt = (float) $rowRw[10];
                                                                            $entrdCurID = (int) $rowRw[14];
                                                                            $entrdAmnt = (float) $rowRw[12];
                                                                            $entrdCurNm = $rowRw[13];
                                                                            $trsctnAcntID = $rowRw[9];
                                                                            $trsctnAcntNm = $rowRw[1] . "." . $rowRw[2];
                                                                            $acntCrncyRate = (float) $rowRw[19];
                                                                            $funcCrncyRate = (float) $rowRw[18];
                                                                            $trsctnLineStatus = $rowRw[11];
                                                                            $trsctnLineDate = $rowRw[6];

                                                                            $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnDbtAmnt;
                                                                            $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnCrdtAmnt;
                                                                            $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnNetAmnt;
                                                                            $isPosted = ($row[11] == "1") ? "true" : "false";
                                                                        }
                                                                        $cntr += 1;
                                                                        ?>
                                                                        <tr id="oneJrnlBatchDetRow_<?php echo $cntr; ?>">                                    
                                                                            <td class="lovtd"><span><?php echo ($cntr); ?></span></td>    
                                                                            <td class="lovtd">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">    
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsSmryLnID" value="<?php echo $trsctnSmryLineID; ?>" style="width:100% !important;"> 
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                                                                <?php
                                                                                if ($canEdt === true) {
                                                                                    ?>
                                                                                    <div class="input-group" style="width:100% !important;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountID', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm', 'clear', 1, '', function () {

                                                                                                });">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>      
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $trsctnAcntNm; ?></span>
                                                                                <?php } ?>                                             
                                                                            </td>                                          
                                                                            <td class="lovtd"  style="">
                                                                                <?php
                                                                                if ($canEdt === true) {
                                                                                    ?>
                                                                                    <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchDetLinesTable', 'jbDetDesc');">                                                    
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $trsctnLineDesc; ?></span>
                                                                                <?php } ?>    
                                                                            </td>                                                  
                                                                            <td class="lovtd">
                                                                                <div class="" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $funcCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                                    <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                $('#oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                            });">
                                                                                        <span class="" id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $funcCurNm; ?></span>
                                                                                    </label>
                                                                                </div>                                              
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt" value="<?php
                                                                                echo number_format($trsctnDbtAmnt, 2);
                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchDetTtl();">                                                    
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <input type="text" class="form-control rqrdFld jbDetCrdt" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt" value="<?php
                                                                                echo number_format($trsctnCrdtAmnt, 2);
                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchDetTtl();">                                                    
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <?php
                                                                                if ($rowRw[22] != ",") {
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Interface Table Breakdown" 
                                                                                            onclick="getAccbTransSrchDet(<?php echo $trsctnLineID; ?>, 'Transaction ID', <?php echo $isPosted; ?>, true, '', '', 'Breakdown of Source Transactions', 'ShowDialog', function () {});" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                    </button>
                                                                                <?php } else { ?>
                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                                                            onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', '', '');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                                        <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                    </button>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <?php
                                                                                if ($canEdt === true) {
                                                                                    ?>
                                                                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                                        <input class="form-control" size="16" type="text" id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>">
                                                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                    </div> 
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $trsctnLineDate; ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>                                         
                                                                            <td class="lovtd" style="">
                                                                                <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchDetLinesTable', 'jbDetRfDc');">                                                    
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <?php
                                                                                if ($canDel === true && $canEdt === true) {
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchDetLn('oneJrnlBatchDetRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Journal Line">
                                                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php
                                                                            if ($canVwRcHstry === true) {
                                                                                ?>
                                                                                <td class="lovtd">
                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                    echo urlencode(encrypt1(($trsctnLineID . "|accb.accb_trnsctn_details|transctn_id"),
                                                                                                    $smplTokenWord1));
                                                                                    ?>');" style="padding:2px !important;">
                                                                                        <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                            <?php } ?>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    $mkReadOnly = $ornlMkReadOnly;
                                                                    ?>
                                                                </tbody>
                                                                <tfoot>                                                            
                                                                    <tr>
                                                                        <th>&nbsp;</th>
                                                                        <th>&nbsp;</th>
                                                                        <th>TOTALS:</th>
                                                                        <th style=""><?php echo $crncyIDNm; ?></th>
                                                                        <th style="text-align: right;">
                                                                            <?php
                                                                            echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                                                    2, '.', ',') . "</span>";
                                                                            ?>
                                                                            <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                                        </th>
                                                                        <th style="text-align: right;">
                                                                            <?php
                                                                            echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                                                    2, '.', ',') . "</span>";
                                                                            ?>
                                                                            <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                                        </th>
                                                                        <th style="">&nbsp;</th>                                           
                                                                        <th style="">&nbsp;</th>
                                                                        <th style="">&nbsp;</th>
                                                                        <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                        <?php
                                                                        if ($canVwRcHstry === true) {
                                                                            ?>
                                                                            <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                        <?php } ?>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 20) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Profit & Loss</span>
				</li>
                               </ul>
                              </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                        $ymdtme3 = "01" . $ymdtme;
                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                    : TRUE;

                        $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                    : -1;
                        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                        if ($accbFSRptSbmtdAccountID > 0) {
                            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                        }
                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3,
                                        0, 11);
                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID'])
                                    : -1;
                        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID'])
                                    : -1;
                        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID'])
                                    : -1;
                        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID'])
                                    : -1;
                        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID'])
                                    : -1;
                        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID'])
                                    : -1;
                        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID'])
                                    : -1;
                        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID'])
                                    : -1;
                        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID'])
                                    : -1;
                        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID'])
                                    : -1;


                        $shwSmmryChkd = "";
                        if ($qShwSmmry == true) {
                            $shwSmmryChkd = "checked=\"true\"";
                        }
                        $fsrptRunID = -1;
                        if ($startRunng == 1) {
                            $fsrptRunID = getNewFSRptRunID();
                            if ($accbStrtFSRptDte != "") {
                                $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                            }
                            if ($accbFSRptDte != "") {
                                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                            }
                            $strSql = "select accb.populate_pnl2( " . $fsrptRunID . ", '" . $accbStrtFSRptDte .
                                    "', '" . $accbFSRptDte . "', " . $accbFSRptMaxAcntLvl . ", "
                                    . $accbFSRptSbmtdAccountID . ","
                                    . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                                    . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                                    . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                                    . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                                    . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                                    . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                            $result = executeSQLNoParams($strSql);
                        }
                        ?>
                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                            Summary Report
                                        </label>
                                    </div>                            
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">From Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">To Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">Account Level Maximum Depth:</label>
                                        </div>
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <input type="number" min="1" max="10000" class="form-control" aria-label="..." id="accbFSRptMaxAcntLvl" name="accbFSRptMaxAcntLvl" value="<?php echo $accbFSRptMaxAcntLvl; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                        <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                            <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                            </label>
                                        </div>
                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                    </div>
                                </div>
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=20');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div> 
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=20');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div> 
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a> INCOME STATEMENT FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="max-width:20px;width:20px;">No.</th>
                                                <th style="max-width:450px !important;">Account Number/Name</th>
                                                <th style="text-align: right;">Credit Amount</th>
                                                <th style="text-align: right;">Debit Amount</th>
                                                <th style="text-align: right;">TOTALS</th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php
                                            $cntr = 0;
                                            $maxNoRows = 0;
                                            $resultRw = null;
                                            if ($fsrptRunID > 0) {
                                                $resultRw = get_PnLRpt($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                            }
                                            $ttlTrsctnDbtAmnt = 0;
                                            $ttlTrsctnCrdtAmnt = 0;
                                            $ttlTrsctnNetAmnt = 0;
                                            while ($cntr < $maxNoRows) {
                                                $rowNumber = 0;
                                                $trsctnAcntID = -1;
                                                $trsctnAcntNm = "";
                                                $trsctnDbtAmnt = 0;
                                                $trsctnCrdtAmnt = 0;
                                                $trsctnNetAmnt = 0;
                                                $trsctnLineDate = "";
                                                $isParent = "0";
                                                $hsSbldgr = "0";
                                                $numStyle1 = "text-align:right;";
                                                $nameStyle1 = "";
                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                    $rowNumber = (float) $rowRw[0];
                                                    $trsctnAcntID = (int) $rowRw[4];
                                                    $trsctnAcntNm = str_replace(" ", "&nbsp;", $rowRw[1] . "." . $rowRw[2]);
                                                    $trsctnDbtAmnt = (float) $rowRw[3];
                                                    $trsctnCrdtAmnt = (float) $rowRw[16];
                                                    $trsctnNetAmnt = (float) $rowRw[8];
                                                    $trsctnLineDate = $rowRw[6];
                                                    $isParent = $rowRw[5];
                                                    $hsSbldgr = $rowRw[6];
                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                        $nameStyle1 = "font-weight:bold;";
                                                    }
                                                    $ttlTrsctnDbtAmnt = (float) $rowRw[14];
                                                    $ttlTrsctnCrdtAmnt = (float) $rowRw[15];
                                                    $ttlTrsctnNetAmnt = $ttlTrsctnDbtAmnt - $ttlTrsctnCrdtAmnt;
                                                }
                                                $cntr += 1;
                                                ?>
                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor">                         
                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $rowRw[2]; ?>" >
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">  
                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo (($trsctnNetAmnt != 0) ? number_format($trsctnNetAmnt, 2) : ""); ?></span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>                                                            
                                            <tr>
                                                <th style="">&nbsp;</th>
                                                <th>TOTALS:</th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    if ($ttlTrsctnNetAmnt <= 0) {
                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                2, '.', ',') . "</span>";
                                                    } else {
                                                        echo "<span style=\"color:green;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                2, '.', ',') . "</span>";
                                                    }
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                </th>     
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 30) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Balance Sheet</span>
				</li>
                               </ul>
                              </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                        $ymdtme3 = "01" . $ymdtme;
                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                    : TRUE;

                        $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                    : -1;
                        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                        if ($accbFSRptSbmtdAccountID > 0) {
                            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                        }
                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3,
                                        0, 11);
                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID'])
                                    : -1;
                        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID'])
                                    : -1;
                        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID'])
                                    : -1;
                        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID'])
                                    : -1;
                        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID'])
                                    : -1;
                        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID'])
                                    : -1;
                        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID'])
                                    : -1;
                        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID'])
                                    : -1;
                        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID'])
                                    : -1;
                        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID'])
                                    : -1;


                        $shwSmmryChkd = "";
                        if ($qShwSmmry == true) {
                            $shwSmmryChkd = "checked=\"true\"";
                        }
                        $fsrptRunID = -1;
                        if ($startRunng == 1) {
                            $fsrptRunID = getNewFSRptRunID();
                            if ($accbStrtFSRptDte != "") {
                                $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                            }
                            if ($accbFSRptDte != "") {
                                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                            }
                            $strSql = "select accb.populate_bals_sheet2( " . $fsrptRunID . ", '"
                                    . $accbFSRptDte . "', " . $accbFSRptMaxAcntLvl . ", "
                                    . $accbFSRptSbmtdAccountID . ","
                                    . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                                    . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                                    . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                                    . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                                    . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                                    . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                            $result = executeSQLNoParams($strSql);
                            /* echo $strSql;
                              if($rw= loc_db_fetch_array($result)){
                              echo $rw[0];
                              } */
                        }
                        ?>
                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                            Summary Report
                                        </label>
                                    </div>                            
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">As At Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">Account Level Maximum Depth:</label>
                                        </div>
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <input type="number" min="1" max="10000" class="form-control" aria-label="..." id="accbFSRptMaxAcntLvl" name="accbFSRptMaxAcntLvl" value="<?php echo $accbFSRptMaxAcntLvl; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                        <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                            <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                            </label>
                                        </div>
                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                    </div>
                                </div>
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=30');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div> 
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=30');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div> 
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a> BALANCE SHEET AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="max-width:25px;width:25px;">No.</th>
                                                <th style="max-width:450px !important;">Account Number/Name</th>
                                                <th style="text-align: right;">Debit Amount</th>
                                                <th style="text-align: right;">Credit Amount</th>
                                                <th style="text-align: right;">TOTALS</th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php
                                            $cntr = 0;
                                            $maxNoRows = 0;
                                            $resultRw = null;
                                            if ($fsrptRunID > 0) {
                                                $resultRw = get_BlShtRpt($fsrptRunID, $accbFSRptDte);
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                            }
                                            $ttlTrsctnDbtAmnt = 0;
                                            $ttlTrsctnCrdtAmnt = 0;
                                            $ttlTrsctnNetAmnt = 0;
                                            while ($cntr < $maxNoRows) {
                                                $rowNumber = 0;
                                                $trsctnAcntID = -1;
                                                $trsctnAcntNm = "";
                                                $trsctnDbtAmnt = 0;
                                                $trsctnCrdtAmnt = 0;
                                                $trsctnNetAmnt = 0;
                                                $trsctnLineDate = "";
                                                $isParent = "0";
                                                $hsSbldgr = "0";
                                                $numStyle1 = "text-align:right;";
                                                $nameStyle1 = "";
                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                    $rowNumber = (float) $rowRw[0];
                                                    $trsctnAcntID = (int) $rowRw[4];
                                                    $trsctnAcntNm = str_replace(" ", "&nbsp;", $rowRw[1] . "." . $rowRw[2]);
                                                    $trsctnDbtAmnt = (float) $rowRw[3];
                                                    $trsctnCrdtAmnt = (float) $rowRw[16];
                                                    $trsctnNetAmnt = (float) $rowRw[8];
                                                    $trsctnLineDate = $rowRw[12];
                                                    $isParent = $rowRw[5];
                                                    $hsSbldgr = $rowRw[6];
                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                        $nameStyle1 = "font-weight:bold;";
                                                    }
                                                    $ttlTrsctnDbtAmnt = (float) $rowRw[13];
                                                    $ttlTrsctnCrdtAmnt = (float) $rowRw[14] + (float) $rowRw[15];
                                                    $ttlTrsctnNetAmnt = $ttlTrsctnDbtAmnt - $ttlTrsctnCrdtAmnt;
                                                }
                                                $cntr += 1;
                                                ?>
                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor">                         
                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $rowRw[2]; ?>" >
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">  
                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo (($trsctnNetAmnt != 0) ? number_format($trsctnNetAmnt, 2) : ""); ?></span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>                                                            
                                            <tr>
                                                <th style="">&nbsp;</th>
                                                <th>TOTALS:</th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    if ($ttlTrsctnNetAmnt != 0) {
                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                2, '.', ',') . "</span>";
                                                    } else {
                                                        echo "<span style=\"color:green;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                2, '.', ',') . "</span>";
                                                    }
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                </th>     
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 40) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Sub-Ledger Balances</span>
				</li>
                               </ul>
                              </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");

                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $qShwVariance = isset($_POST['accbFSRptShwVariance']) ? (cleanInputData($_POST['accbFSRptShwVariance']) === "YES" ? TRUE
                                    : FALSE) : FALSE;
                        $qShwHideZero = isset($_POST['accbFSRptShwHideZero']) ? (cleanInputData($_POST['accbFSRptShwHideZero']) === "YES" ? TRUE
                                    : FALSE) : TRUE;
                        $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                    : TRUE;
                        $qShwNetPos = isset($_POST['accbFSRptShwNetPos']) ? (cleanInputData($_POST['accbFSRptShwNetPos']) === "YES" ? TRUE : FALSE)
                                    : TRUE;

                        $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                    : -1;
                        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                        if ($accbFSRptSbmtdAccountID > 0) {
                            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                        }
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID'])
                                    : -1;
                        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID'])
                                    : -1;
                        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID'])
                                    : -1;
                        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID'])
                                    : -1;
                        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID'])
                                    : -1;
                        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID'])
                                    : -1;
                        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID'])
                                    : -1;
                        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID'])
                                    : -1;
                        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID'])
                                    : -1;
                        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID'])
                                    : -1;

                        $shwVarianceChkd = "";
                        if ($qShwVariance == true) {
                            $shwVarianceChkd = "checked=\"true\"";
                        }
                        $shwHideZeroChkd = "";
                        if ($qShwHideZero == true) {
                            $shwHideZeroChkd = "checked=\"true\"";
                        }
                        $shwSmmryChkd = "";
                        if ($qShwSmmry == true) {
                            $shwSmmryChkd = "checked=\"true\"";
                        }
                        $shwNetPosChkd = "";
                        if ($qShwNetPos == true) {
                            $shwNetPosChkd = "checked=\"true\"";
                        }
                        $fsrptRunID = -1;
                        if ($startRunng == 1) {
                            $fsrptRunID = getNewFSRptRunID();
                            $useNetPos = $qShwNetPos ? "Yes" : "No";
                            $shwVarnce = $qShwVariance ? "Yes" : "No";
                            if ($accbFSRptDte != "") {
                                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                            }
                            $strSql = "select accb.populate_subldgr_bals( " . $fsrptRunID . ", '" . $useNetPos .
                                    "', '" . $accbFSRptDte . "', " . $accbFSRptMaxAcntLvl . ", "
                                    . $accbFSRptSbmtdAccountID . ", '" . $shwVarnce . "', "
                                    . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                                    . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                                    . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                                    . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                                    . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                                    . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                            $result = executeSQLNoParams($strSql);
                        }
                        ?>
                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwVariance" name="accbFSRptShwVariance" <?php echo $shwVarianceChkd; ?>>
                                            Show Variances
                                        </label>
                                    </div>                            
                                </div>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwHideZero" name="accbFSRptShwHideZero" <?php echo $shwHideZeroChkd; ?>>
                                            Hide Zero Balances
                                        </label>
                                    </div>                            
                                </div>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                            Summary Report
                                        </label>
                                    </div>                            
                                </div>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwNetPos" name="accbFSRptShwNetPos" <?php echo $shwNetPosChkd; ?>>
                                            Use Net Positions
                                        </label>
                                    </div>                            
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">As At Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="As At Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">Account Level Maximum Depth:</label>
                                        </div>
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <input type="number" min="1" max="10000" class="form-control" aria-label="..." id="accbFSRptMaxAcntLvl" name="accbFSRptMaxAcntLvl" value="<?php echo $accbFSRptMaxAcntLvl; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                        <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                            <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                            </label>
                                        </div>
                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                    </div>
                                </div> 
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=40');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=40');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a> SUB-LEDGER BALANCES AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                <th style="max-width:20px;width:20px;">No.</th>
                                                <th style="max-width:450px !important;">Account Number/Name</th>
                                                <th style="text-align: right;">Debit Balance</th>
                                                <th style="text-align: right;">Credit Balance</th>
                                                <th style="text-align: right;">Net Balance</th>
                                                <th style="max-width:70px;width:70px;">Transaction Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php
                                            $cntr = 0;
                                            $maxNoRows = 0;
                                            $resultRw = null;
                                            if ($fsrptRunID > 0) {
                                                $resultRw = get_TbalsRpt($fsrptRunID, $accbFSRptDte);
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                            }
                                            $ttlTrsctnDbtAmnt = 0;
                                            $ttlTrsctnCrdtAmnt = 0;
                                            $ttlTrsctnNetAmnt = 0;
                                            while ($cntr < $maxNoRows) {
                                                $rowNumber = 0;
                                                $trsctnAcntID = -1;
                                                $trsctnAcntNm = "";
                                                $trsctnDbtAmnt = 0;
                                                $trsctnCrdtAmnt = 0;
                                                $trsctnNetAmnt = 0;
                                                $trsctnLineDate = "";
                                                $isParent = "0";
                                                $numStyle1 = "text-align:right;";
                                                $nameStyle1 = "";
                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                    $rowNumber = (float) $rowRw[0];
                                                    $trsctnAcntID = (int) $rowRw[7];
                                                    $trsctnAcntNm = str_replace(" ", "&nbsp;", $rowRw[1] . "." . $rowRw[2]);
                                                    $trsctnDbtAmnt = (float) $rowRw[3];
                                                    $trsctnCrdtAmnt = (float) $rowRw[4];
                                                    $trsctnNetAmnt = (float) $rowRw[5];
                                                    $trsctnLineDate = $rowRw[6];
                                                    $isParent = $rowRw[8];
                                                    if ($isParent == "1") {
                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                        $nameStyle1 = "font-weight:bold;";
                                                    }
                                                    $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + (float) $rowRw[10];
                                                    $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + (float) $rowRw[11];
                                                    $ttlTrsctnNetAmnt = $ttlTrsctnDbtAmnt - $ttlTrsctnCrdtAmnt;
                                                }
                                                $cntr += 1;
                                                ?>
                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                    <td class="lovtd">
                                                        <input type="checkbox" name="oneAccbFSRptRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $trsctnAcntID . ";" . $trsctnAcntNm; ?>">
                                                    </td>                                   
                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $rowRw[2]; ?>" >
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">  
                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnNetAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd">
                                                        <span><?php echo $trsctnLineDate; ?></span>                   
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>                                                            
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th style="">&nbsp;</th>
                                                <th>TOTALS:</th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                </th>   
                                                <th style="">&nbsp;</th>   
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 50) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">GL Account Statements</span>
			    </li>
                           </ul>
                          </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                        $ymdtme3 = "01" . $ymdtme;
                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                    : TRUE;

                        $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                    : -1;
                        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                        if ($accbFSRptSbmtdAccountID > 0) {
                            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                        }
                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3,
                                        0, 11);
                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID'])
                                    : -1;
                        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID'])
                                    : -1;
                        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID'])
                                    : -1;
                        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID'])
                                    : -1;
                        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID'])
                                    : -1;
                        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID'])
                                    : -1;
                        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID'])
                                    : -1;
                        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID'])
                                    : -1;
                        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID'])
                                    : -1;
                        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID'])
                                    : -1;


                        $shwSmmryChkd = "";
                        if ($qShwSmmry == true) {
                            $shwSmmryChkd = "checked=\"true\"";
                        }
                        $shwIntrfcsParam = ($qShwSmmry) ? "Yes" : "No";

                        $fsrptRunID = -1;
                        if ($startRunng == 1 && $accbFSRptSbmtdAccountID > 0) {
                            $fsrptRunID = getNewFSRptRunID();
                            if ($accbStrtFSRptDte != "") {
                                $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                            }
                            if ($accbFSRptDte != "") {
                                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                            }
                            $strSql = "select accb.populate_gl_statement2( " . $fsrptRunID . ", "
                                    . $accbFSRptSbmtdAccountID . ",'" . $shwIntrfcsParam . "', '" . $accbStrtFSRptDte .
                                    "', '" . $accbFSRptDte . "', "
                                    . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                            $result = executeSQLNoParams($strSql);
                        }
                        ?>
                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                            Show Interface Trns.
                                        </label>
                                    </div>                            
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">From Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">To Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                        <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">GL Account:</label>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                    </div>
                                </div>
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=50');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div> 
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=50');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div> 
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a><?php echo $accbFSRptAcntNum; ?> ACCOUNT STATEMENT FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="max-width:20px;width:20px;">No.</th>
                                                <th style="max-width:450px !important;">Transaction Description (Ref. Doc. No.)</th>
                                                <th style="text-align: right;">Debit Amount</th>
                                                <th style="text-align: right;">Credit Amount</th>
                                                <th style="text-align: right;">Running Balance</th>
                                                <th style="max-width:70px;width:70px;">Transaction Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php
                                            $cntr = 0;
                                            $maxNoRows = 0;
                                            $resultRw = null;
                                            if ($fsrptRunID > 0) {
                                                $resultRw = get_GLStmntRpt($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                            }
                                            $ttlTrsctnDbtAmnt = 0;
                                            $ttlTrsctnCrdtAmnt = 0;
                                            $ttlTrsctnNetAmnt = 0;
                                            while ($cntr < $maxNoRows) {
                                                $rowNumber = 0;
                                                $trsctnAcntID = -1;
                                                $trsctnAcntNm = "";
                                                $trsctnDbtAmnt = 0;
                                                $trsctnCrdtAmnt = 0;
                                                $trsctnNetAmnt = 0;
                                                $trsctnLineDate = "";
                                                $isParent = "0";
                                                $hsSbldgr = "0";
                                                $numStyle1 = "text-align:right;";
                                                $nameStyle1 = "";
                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                    $rowNumber = (float) $rowRw[0];
                                                    $trsctnAcntID = -1; // (int) $rowRw[4];
                                                    $trsctnAcntNm = ""; // $rowRw[3];
                                                    if ($rowNumber > 1) {
                                                        $trsctnAcntNm = $rowRw[3];
                                                        //"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". 
                                                    } else {
                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                        $nameStyle1 = "font-weight:bold;";
                                                    }
                                                    $trsctnDbtAmnt = (float) $rowRw[5];
                                                    $trsctnCrdtAmnt = (float) $rowRw[6];
                                                    $trsctnNetAmnt = (float) $rowRw[17];
                                                    $trsctnLineDate = $rowRw[14];
                                                    $trsctnLineID = (float) $rowRw[18];
                                                    $ttlTrsctnDbtAmnt = (float) $rowRw[11];
                                                    $ttlTrsctnCrdtAmnt = (float) $rowRw[12];
                                                    $ttlTrsctnNetAmnt = (float) $rowRw[13];
                                                }
                                                $cntr += 1;
                                                ?>
                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor">                         
                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>" >
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_TransLineID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;"> 
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">  
                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnNetAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd">
                                                        <span><?php echo $trsctnLineDate; ?></span>                   
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>                                                            
                                            <tr>
                                                <th style="">&nbsp;</th>
                                                <th>TOTALS:</th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    if ($ttlTrsctnNetAmnt <= 0) {
                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                2, '.', ',') . "</span>";
                                                    } else {
                                                        echo "<span style=\"color:green;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                2, '.', ',') . "</span>";
                                                    }
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                </th>
                                                <th style="">&nbsp;</th>     
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 60) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Period By Period Net Trns.</span>
			    </li>
                           </ul>
                          </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                        $ymdtme3 = "01" . $ymdtme;
                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                    : TRUE;
                        $accbFSRptAcntTypes = isset($_POST['accbFSRptAcntTypes']) ? cleanInputData($_POST['accbFSRptAcntTypes']) : "R-REVENUE/EX-EXPENSE";
                        $accbFSRptPrdType = isset($_POST['accbFSRptPrdType']) ? cleanInputData($_POST['accbFSRptPrdType']) : "Quarterly";
                        $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                    : -1;
                        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                        if ($accbFSRptSbmtdAccountID > 0) {
                            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                        }
                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3,
                                        0, 11);
                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID'])
                                    : -1;
                        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID'])
                                    : -1;
                        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID'])
                                    : -1;
                        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID'])
                                    : -1;
                        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID'])
                                    : -1;
                        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID'])
                                    : -1;
                        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID'])
                                    : -1;
                        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID'])
                                    : -1;
                        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID'])
                                    : -1;
                        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID'])
                                    : -1;


                        $shwSmmryChkd = "";
                        if ($qShwSmmry == true) {
                            $shwSmmryChkd = "checked=\"true\"";
                        }
                        $fsrptRunID = -1;
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
                        ?>
                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                            Summary Report
                                        </label>
                                    </div>                            
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">Account Types:</label>
                                        </div>
                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptAcntTypes">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                                $srchInsArrys = array("A-ASSET", "EQ-EQUITY", "L-LIABILITY", "R-REVENUE",
                                                    "EX-EXPENSE", "R-REVENUE/EX-EXPENSE", "Balance Sheet Accounts", "All");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($accbFSRptAcntTypes == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>                           
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">Period Type:</label>
                                        </div>
                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
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
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">From Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">To Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">Account Level Maximum Depth:</label>
                                        </div>
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <input type="number" min="1" max="10000" class="form-control" aria-label="..." id="accbFSRptMaxAcntLvl" name="accbFSRptMaxAcntLvl" value="<?php echo $accbFSRptMaxAcntLvl; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                        <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                            <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                            </label>
                                        </div>
                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                    </div>
                                </div>
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=60');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div> 
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=60');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div> 
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a> <?php echo $accbFSRptAcntNum; ?> PERIOD BY PERIOD BALANCES FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                        </caption>
                                        <thead>
                                            <tr>  
                                                <?php
                                                $colsName = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                    "", "", "", "", "", "", "", "",
                                                    "", "", "", "", "", "", "", "", "", "", "", "");
                                                $colscntr = 0;
                                                $cntr = 0;
                                                $maxNoRows = 0;
                                                $resultHdr = null;
                                                if ($fsrptRunID > 0) {
                                                    $resultHdr = get_PeriodicRptHdr($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                    //$colsTtl = loc_db_num_rows($resultHdr);
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
                                                ?>
                                                <th style="max-width:25px;width:25px;">No.</th>
                                                <th style="max-width:450px !important;">Account Number/Name</th>
                                                <th style="text-align: right;">Opening Balance</th>
                                                <?php
                                                while ($colscntr < count($colsName)) {
                                                    ?>
                                                    <th style="text-align: right;"><?php echo $colsName[$colscntr]; ?></th>
                                                    <?php
                                                    if ($colsName[$colscntr] == "Closing Balance" || strpos(trim($colsName[$colscntr]),
                                                                    "Period ") !== FALSE) {
                                                        break;
                                                    }
                                                    $colscntr++;
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php
                                            $resultRw = null;
                                            if ($fsrptRunID > 0) {
                                                $resultRw = get_PeriodicRpt($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                            }
                                            $ttlTrsctnDbtAmnt = 0;
                                            $ttlTrsctnCrdtAmnt = 0;
                                            $ttlTrsctnNetAmnt = 0;
                                            while ($cntr < $maxNoRows) {
                                                $rowNumber = 0;
                                                $trsctnAcntID = -1;
                                                $trsctnAcntNm = "";
                                                $trsctnDbtAmnt = 0;
                                                $trsctnCrdtAmnt = 0;
                                                $trsctnNetAmnt = 0;
                                                $trsctnLineDate = "";
                                                $isParent = "0";
                                                $hsSbldgr = "0";
                                                $numStyle1 = "text-align:right;";
                                                $nameStyle1 = "";
                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                    $rowNumber = (float) $rowRw[0];
                                                    $trsctnAcntID = (int) $rowRw[3];
                                                    $trsctnAcntNm = str_replace(" ", "&nbsp;", $rowRw[1] . "." . $rowRw[2]);
                                                    $trsctnOpngAmnt = (float) $rowRw[10];
                                                    $isParent = $rowRw[4];
                                                    $hsSbldgr = $rowRw[5];
                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                        $nameStyle1 = "font-weight:bold;";
                                                    }
                                                }
                                                $cntr += 1;
                                                ?>
                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor">                         
                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $rowRw[2]; ?>" >
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">  
                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnOpngAmnt, 2); ?></span>
                                                    </td>
                                                    <?php
                                                    $colscntr = 0;
                                                    while ($colscntr < count($colsName)) {
                                                        ?>
                                                        <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                            <span><?php echo number_format((float) $rowRw[$colscntr + 11], 2); ?></span>
                                                        </td>
                                                        <?php
                                                        if ($colsName[$colscntr] == "Closing Balance" || strpos(trim($colsName[$colscntr]),
                                                                        "Period ") !== FALSE) {
                                                            break;
                                                        }
                                                        $colscntr++;
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 70) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Cash Flow Statement</span>
			    </li>
                           </ul>
                          </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                        $ymdtme3 = "01" . $ymdtme;
                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                    : TRUE;
                        $accbFSRptAcntTypes = isset($_POST['accbFSRptAcntTypes']) ? cleanInputData($_POST['accbFSRptAcntTypes']) : "Indirect Method-Summary";
                        $accbFSRptPrdType = isset($_POST['accbFSRptPrdType']) ? cleanInputData($_POST['accbFSRptPrdType']) : "Quarterly";
                        $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                    : -1;
                        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                        if ($accbFSRptSbmtdAccountID > 0) {
                            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                        }
                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3,
                                        0, 11);
                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID'])
                                    : -1;
                        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID'])
                                    : -1;
                        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID'])
                                    : -1;
                        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID'])
                                    : -1;
                        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID'])
                                    : -1;
                        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID'])
                                    : -1;
                        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID'])
                                    : -1;
                        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID'])
                                    : -1;
                        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID'])
                                    : -1;
                        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID'])
                                    : -1;


                        $shwSmmryChkd = "";
                        if ($qShwSmmry == true) {
                            $shwSmmryChkd = "checked=\"true\"";
                        }
                        $fsrptRunID = -1;
                        if ($startRunng == 1) {
                            $fsrptRunID = getNewFSRptRunID();
                            if ($accbStrtFSRptDte != "") {
                                $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                            }
                            if ($accbFSRptDte != "") {
                                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                            }
                            $strSql = "select accb.populate_cash_flow( " . $fsrptRunID . ", '"
                                    . $accbFSRptAcntTypes . "', '" . $accbStrtFSRptDte .
                                    "', '" . $accbFSRptDte . "', '"
                                    . $accbFSRptPrdType . "',"
                                    . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                                    . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                                    . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                                    . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                                    . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                                    . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                            //echo $strSql;
                            $result = executeSQLNoParams($strSql);
                        }
                        ?>
                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">Cash Flow Type:</label>
                                        </div>
                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptAcntTypes">
                                                <?php
                                                $valslctdArry = array("", "", "", "");
                                                $srchInsArrys = array("Direct Method-Detail", "Direct Method-Summary", "Indirect Method-Detail",
                                                    "Indirect Method-Summary");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($accbFSRptAcntTypes == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                <?php } ?>
                                            </select>
                                        </div>
                                    </div>                           
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">Period Type:</label>
                                        </div>
                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
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
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">From Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">To Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                        <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                            <div class="input-group" style="width:100% !important;">
                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptAcntNum" name="accbFSRptAcntNum" value="<?php echo $accbFSRptAcntNum; ?>" style="width:100% !important;" readonly="true">
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
                                            </div>
                                        </div>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                            <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                            </label>
                                        </div>
                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                    </div>
                                </div>
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=70');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div> 
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=70');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div> 
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a> <?php echo $accbFSRptAcntNum; ?> CASH FLOW STATEMENT FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                        </caption>
                                        <thead>
                                            <tr>  
                                                <?php
                                                $colsName = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                    "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                                                $colscntr = 0;
                                                $cntr = 0;
                                                $maxNoRows = 0;
                                                $resultHdr = null;
                                                if ($fsrptRunID > 0) {
                                                    $resultHdr = get_CshFlwRptHdr($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                    //$colsTtl = loc_db_num_rows($resultHdr);
                                                    if ($rwHdr = loc_db_fetch_array($resultHdr)) {
                                                        $colsName[0] = $rwHdr[10];
                                                        $colsName[1] = $rwHdr[11];
                                                        $colsName[2] = $rwHdr[12];
                                                        $colsName[3] = $rwHdr[13];
                                                        $colsName[4] = $rwHdr[14];
                                                        $colsName[5] = $rwHdr[15];
                                                        $colsName[6] = $rwHdr[16];
                                                        $colsName[7] = $rwHdr[17];
                                                        $colsName[8] = $rwHdr[18];
                                                        $colsName[9] = $rwHdr[19];
                                                        $colsName[10] = $rwHdr[20];
                                                        $colsName[11] = $rwHdr[21];
                                                        $colsName[12] = $rwHdr[22];
                                                        $colsName[13] = $rwHdr[23];
                                                        $colsName[14] = $rwHdr[24];
                                                        $colsName[15] = $rwHdr[25];
                                                        $colsName[16] = $rwHdr[26];
                                                        $colsName[17] = $rwHdr[27];
                                                        $colsName[18] = $rwHdr[28];
                                                        $colsName[19] = $rwHdr[29];
                                                        $colsName[20] = $rwHdr[30];
                                                        $colsName[21] = $rwHdr[31];
                                                        $colsName[22] = $rwHdr[32];
                                                        $colsName[23] = $rwHdr[33];
                                                        $colsName[24] = $rwHdr[34];
                                                        $colsName[25] = $rwHdr[35];
                                                        $colsName[26] = $rwHdr[36];
                                                        $colsName[27] = $rwHdr[37];
                                                        $colsName[28] = $rwHdr[38];
                                                        $colsName[29] = $rwHdr[39];
                                                        $colsName[30] = $rwHdr[40];
                                                        $colsName[31] = $rwHdr[41];
                                                        $colsName[32] = $rwHdr[42];
                                                        $colsName[33] = $rwHdr[43];
                                                        $colsName[34] = $rwHdr[44];
                                                        $colsName[35] = $rwHdr[45];
                                                        $colsName[36] = $rwHdr[46];
                                                        $colsName[37] = $rwHdr[47];
                                                        $colsName[38] = $rwHdr[48];
                                                        $colsName[39] = $rwHdr[49];
                                                    }
                                                }
                                                ?>
                                                <th style="max-width:25px;width:25px;">No.</th>
                                                <th style="max-width:450px !important;">Account Number/Name</th>
                                                <?php
                                                while ($colscntr < count($colsName)) {
                                                    ?>
                                                    <th style="text-align: right;"><?php echo $colsName[$colscntr]; ?></th>
                                                    <?php
                                                    if ($colsName[$colscntr] == "Totals" || strpos(trim($colsName[$colscntr]), "Period ") !== FALSE) {
                                                        break;
                                                    }
                                                    $colscntr++;
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php
                                            $resultRw = null;
                                            if ($fsrptRunID > 0) {
                                                $resultRw = get_CshFlwRpt($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                            }
                                            $ttlTrsctnDbtAmnt = 0;
                                            $ttlTrsctnCrdtAmnt = 0;
                                            $ttlTrsctnNetAmnt = 0;
                                            while ($cntr < $maxNoRows) {
                                                $rowNumber = 0;
                                                $trsctnAcntID = -1;
                                                $trsctnAcntNm = "";
                                                $trsctnDbtAmnt = 0;
                                                $trsctnCrdtAmnt = 0;
                                                $trsctnNetAmnt = 0;
                                                $trsctnLineDate = "";
                                                $isParent = "0";
                                                $hsSbldgr = "0";
                                                $numStyle1 = "text-align:right;";
                                                $nameStyle1 = "";
                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                    $rowNumber = (float) $rowRw[0];
                                                    $trsctnAcntID = (int) $rowRw[3];
                                                    $trsctnAcntNm = str_replace(" ", "&nbsp;", $rowRw[2]);
                                                    $isParent = $rowRw[4];
                                                    $hsSbldgr = $rowRw[5];
                                                    $trsctnOpngAmnt = 0;
                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                        $nameStyle1 = "font-weight:bold;";
                                                    }
                                                }
                                                $cntr += 1;
                                                ?>
                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor">                         
                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $rowRw[2]; ?>" >
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">  
                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                    </td>
                                                    <?php
                                                    $colscntr = 0;
                                                    $ttlValue = 0;
                                                    while ($colscntr < count($colsName)) {
                                                        $colValue = "";
                                                        if ($rowRw[$colscntr + 10] !== "") {
                                                            if ($colsName[$colscntr] != "Totals") {
                                                                $colValue = number_format((float) $rowRw[$colscntr + 10], 2);
                                                                if (strpos($rowRw[1], "Cash and Cash Equivalents") === FALSE) {
                                                                    $ttlValue = $ttlValue + (float) $rowRw[$colscntr + 10];
                                                                } else {
                                                                    $ttlValue = (float) $rowRw[$colscntr + 10];
                                                                }
                                                            } else if ($rowRw[$colscntr + 9] !== "") {
                                                                $colValue = number_format($ttlValue, 2);
                                                            } else {
                                                                $colValue = "";
                                                            }
                                                        }
                                                        /* else if ($colsName[$colscntr] === "Totals" && strpos($rowRw[1], "Cash and Cash Equivalents") !== FALSE) {
                                                          $colValue = number_format((float) $rowRw[$colscntr + 10], 2);
                                                          } */
                                                        ?>
                                                        <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                            <span><?php echo $colValue; ?></span>
                                                        </td>
                                                        <?php
                                                        if ($colsName[$colscntr] == "Totals" || strpos(trim($colsName[$colscntr]), "Period ")
                                                                !== FALSE) {
                                                            break;
                                                        }
                                                        $colscntr++;
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 80) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Cash Book</span>
			    </li>
                           </ul>
                          </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                        $ymdtme3 = "01" . $ymdtme;
                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                    : TRUE;

                        $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                    : -1;
                        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                        if ($accbFSRptSbmtdAccountID > 0) {
                            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                        }
                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3,
                                        0, 11);
                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID'])
                                    : -1;
                        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID'])
                                    : -1;
                        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID'])
                                    : -1;
                        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID'])
                                    : -1;
                        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID'])
                                    : -1;
                        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID'])
                                    : -1;
                        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID'])
                                    : -1;
                        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID'])
                                    : -1;
                        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID'])
                                    : -1;
                        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID'])
                                    : -1;


                        $shwSmmryChkd = "";
                        if ($qShwSmmry == true) {
                            $shwSmmryChkd = "checked=\"true\"";
                        }
                        $shwIntrfcsParam = ($qShwSmmry) ? "Yes" : "No";

                        $fsrptRunID = -1;
                        if ($startRunng == 1) {
                            $fsrptRunID = getNewFSRptRunID();
                            if ($accbStrtFSRptDte != "") {
                                $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                            }
                            if ($accbFSRptDte != "") {
                                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                            }
                            $strSql = "select accb.populate_cash_book( " . $fsrptRunID . ",'" . $shwIntrfcsParam . "', '" . $accbStrtFSRptDte .
                                    "', '" . $accbFSRptDte . "', "
                                    . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                                    . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                                    . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                                    . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                                    . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                                    . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                            //echo $strSql;
                            $result = executeSQLNoParams($strSql);
                        }
                        ?>
                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                            Summarize Narrations
                                        </label>
                                    </div>                            
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">From Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">To Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                        <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">GL Account:</label>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">
                                            <div class="input-group">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                            <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                            </label>
                                        </div>
                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                    </div>
                                </div>
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=80');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div> 
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=80');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div> 
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a><?php echo strtoupper($accbFSRptAcntNum); ?> CASH BOOK FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="max-width:20px;width:20px;">No.</th>
                                                <th style="max-width:65px;width:65px;">Transaction Date</th>
                                                <th style="max-width:550px !important;">Transaction Description (GL Account)</th>
                                                <th style="text-align: right;">Debit Amount</th>
                                                <th style="text-align: right;">Credit Amount</th>
                                                <th style="text-align: right;">Cash Balance</th>
                                                <th style="text-align: right;">Bank Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php
                                            $cntr = 0;
                                            $maxNoRows = 0;
                                            $resultRw = null;
                                            if ($fsrptRunID > 0) {
                                                $resultRw = get_CshBkStmntRpt($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                            }
                                            $ttlTrsctnDbtAmnt = 0;
                                            $ttlTrsctnCrdtAmnt = 0;
                                            $ttlTrsctnNetAmnt = 0;
                                            $ttlTrsctnNetAmnt1 = 0;
                                            while ($cntr < $maxNoRows) {
                                                $rowNumber = 0;
                                                $trsctnAcntID = -1;
                                                $trsctnAcntNm = "";
                                                $trsctnDbtAmnt = "";
                                                $trsctnCrdtAmnt = "";
                                                $trsctnNetAmnt = 0;
                                                $trsctnNetAmnt1 = 0;
                                                $trsctnLineDate = "";
                                                $isParent = "0";
                                                $hsSbldgr = "0";
                                                $numStyle1 = "text-align:right;";
                                                $nameStyle1 = "";
                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                    $rowNumber = (float) $rowRw[0];
                                                    $trsctnAcntID = -1; // (int) $rowRw[4];
                                                    $trsctnAcntNm = $rowRw[3];
                                                    if ($rowNumber > 1) {
                                                        $trsctnDbtAmnt = number_format((float) $rowRw[5], 2);
                                                        $trsctnCrdtAmnt = number_format((float) $rowRw[6], 2);

                                                        $trsctnAcntNm = $rowRw[3];
                                                        //"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". 
                                                    } else {
                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                        $nameStyle1 = "font-weight:bold;";
                                                    }
                                                    $trsctnNetAmnt = (float) $rowRw[17];
                                                    $trsctnNetAmnt1 = (float) $rowRw[19];
                                                    $trsctnLineDate = $rowRw[14];
                                                    $trsctnLineID = (float) $rowRw[18];
                                                    $ttlTrsctnDbtAmnt = (float) $rowRw[11];
                                                    $ttlTrsctnCrdtAmnt = (float) $rowRw[12];
                                                    $ttlTrsctnNetAmnt = (float) $rowRw[13];
                                                    $ttlTrsctnNetAmnt1 = (float) $rowRw[21];
                                                }
                                                $cntr += 1;
                                                ?>
                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor">                         
                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>
                                                    <td class="lovtd">
                                                        <span><?php echo $trsctnLineDate; ?></span>                   
                                                    </td>    
                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>" >
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_TransLineID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;"> 
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_TrnsDate" value="<?php echo $trsctnLineDate; ?>" style="width:100% !important;">  
                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo $trsctnDbtAmnt; ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo $trsctnCrdtAmnt; ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnNetAmnt, 2); ?></span>
                                                    </td>
                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                        <span><?php echo number_format($trsctnNetAmnt1, 2); ?></span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>                                                            
                                            <tr>
                                                <th style="">&nbsp;</th>
                                                <th style="">&nbsp;</th>
                                                <th>CLOSING BALANCE:</th>
                                                <th style="text-align: right;">&nbsp;</th>
                                                <th style="text-align: right;">&nbsp;</th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    if ($ttlTrsctnNetAmnt <= 0) {
                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                2, '.', ',') . "</span>";
                                                    } else {
                                                        echo "<span style=\"color:green;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                2, '.', ',') . "</span>";
                                                    }
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    if ($ttlTrsctnNetAmnt1 <= 0) {
                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt1,
                                                                2, '.', ',') . "</span>";
                                                    } else {
                                                        echo "<span style=\"color:green;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt1,
                                                                2, '.', ',') . "</span>";
                                                    }
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt1; ?>">
                                                </th>     
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 90) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Financial Ratios & Memo Account Balances</span>
			    </li>
                           </ul>
                          </div>";
                echo $cntent;
                $curIdx = 0;
                $cntr = 0;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");

                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $qShwVariance = isset($_POST['accbFSRptShwVariance']) ? (cleanInputData($_POST['accbFSRptShwVariance']) === "YES" ? TRUE
                                    : FALSE) : FALSE;
                        $qShwHideZero = isset($_POST['accbFSRptShwHideZero']) ? (cleanInputData($_POST['accbFSRptShwHideZero']) === "YES" ? TRUE
                                    : FALSE) : TRUE;
                        $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                    : TRUE;
                        $qShwNetPos = isset($_POST['accbFSRptShwNetPos']) ? (cleanInputData($_POST['accbFSRptShwNetPos']) === "YES" ? TRUE : FALSE)
                                    : TRUE;

                        $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                        $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                    : -1;
                        $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                        if ($accbFSRptSbmtdAccountID > 0) {
                            $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                        }
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID'])
                                    : -1;
                        $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID'])
                                    : -1;
                        $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID'])
                                    : -1;
                        $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID'])
                                    : -1;
                        $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID'])
                                    : -1;
                        $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID'])
                                    : -1;
                        $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID'])
                                    : -1;
                        $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID'])
                                    : -1;
                        $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID'])
                                    : -1;
                        $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID'])
                                    : -1;

                        $shwVarianceChkd = "";
                        if ($qShwVariance == true) {
                            $shwVarianceChkd = "checked=\"true\"";
                        }
                        $shwHideZeroChkd = "";
                        if ($qShwHideZero == true) {
                            $shwHideZeroChkd = "checked=\"true\"";
                        }
                        $shwSmmryChkd = "";
                        if ($qShwSmmry == true) {
                            $shwSmmryChkd = "checked=\"true\"";
                        }
                        $shwNetPosChkd = "";
                        if ($qShwNetPos == true) {
                            $shwNetPosChkd = "checked=\"true\"";
                        }
                        $fsrptRunID = -1;
                        if ($startRunng == 1) {
                            $fsrptRunID = getNewFSRptRunID();
                            $useNetPos = $qShwNetPos ? "Yes" : "No";
                            $shwVarnce = $qShwVariance ? "Yes" : "No";
                            if ($accbFSRptDte != "") {
                                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                            }
                            $strSql = "select accb.populate_trial_bals2( " . $fsrptRunID . ", '" . $useNetPos .
                                    "', '" . $accbFSRptDte . "', " . $accbFSRptMaxAcntLvl . ", "
                                    . $accbFSRptSbmtdAccountID . ", '" . $shwVarnce . "', "
                                    . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                                    . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                                    . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                                    . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                                    . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                                    . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                            $result = executeSQLNoParams($strSql);
                        }
                        ?>
                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">As At Date:</label>
                                        </div>
                                        <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="As At Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                        <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                            <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                            </label>
                                        </div>
                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                    </div>
                                </div> 
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=90');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=90');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclGlStatemtLines" id="accbRcnclGlStatemtLinestab">LIQUIDITY RATIOS</a></li>
                                    <li class=""><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclJrnlTrnsLines" id="accbRcnclJrnlTrnsLinestab">SOLVENCY/LEVERAGE RATIOS</a></li>
                                    <li class=""><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclActvtyRatios" id="accbRcnclActvtyRatiostab">EFFICIENCY/ACTIVITY RATIOS</a></li>
                                    <li class=""><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclProfitRatios" id="accbRcnclProfitRatiostab">PROFITABILITY RATIOS</a></li>
                                </ul>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row"> 
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-responsive table-bordered" id="accbFSRptTable" style="width:100% !important;">
                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a> LIQUIDITY RATIO ANALYSIS AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                                        </caption>
                                                        <thead>
                                                            <tr>
                                                                <th>Ratio Type & Description</th>     
                                                                <th>Ratio Result</th>  
                                                                <th style="min-width:250px;width:250px;">Values Used (Date)</th>      
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ratioTypes = array("Current Ratio", "Quick ratio");
                                                            $ratioDescs = array("The current ratio indicates if the company can pay off its short-term liabilities in an emergency by liquidating its current assets.<br/>(Current Ratio = Current Assets ÷ Current Liabilities)",
                                                                "This ratio compares the cash, short-term marketable securities and accounts receivable to current liabilities. The thought behind the quick ratio is that certain line items, such as prepaid expenses, have already been paid out for future use and cannot be quickly and easily converted back to cash for liquidity purposes.<br/>[Quick ratio = (cash + short-term marketable securities + accounts receivable) ÷ current liabilities]");
                                                            $ratioValue1Lbl = array("Current Assets = 0", "Current Assets = 0");
                                                            $ratioValue2Lbl = array("Current Liabilities = 0", "Current Liabilities = 0");
                                                            $ratioValue3Lbl = array("", "Prepaid Expenses");
                                                            $ratioValue4Lbl = array("", "");
                                                            while ($cntr < count($ratioTypes)) {
                                                                ?>
                                                                <tr id="allForumsRow_<?php echo $cntr; ?>" class="active">    
                                                                    <td>
                                                                        <strong><?php echo ($curIdx * $lmtSze) + ($cntr + 1); ?>. <?php echo $ratioTypes[$cntr]; ?></strong><br/>
                                                                        <!--<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
                                                                        <a style="text-decoration:none;" href="https://www.aaii.com/journal/article/16-financial-ratios-for-analyzing-a-companys-strengths-and-weaknesses.touch" target="_blank"><?php echo $ratioDescs[$cntr]; ?></a><br/>
                                                                    </td>
                                                                    <td><strong>0.00</strong></td>
                                                                    <td>
                                                                        <strong><?php echo $ratioValue1Lbl[$cntr]; ?></strong><br/>
                                                                        <strong><?php echo $ratioValue2Lbl[$cntr]; ?></strong><br/>
                                                                        <strong><?php echo $ratioValue3Lbl[$cntr]; ?></strong><br/>
                                                                        <strong><?php echo $ratioValue4Lbl[$cntr]; ?></strong><br/>
                                                                        <a class="btn btn-hot" href="javascript: rhovoid();"><i class="fa">Values As At:</i> <?php echo $accbFSRptDte1; ?></a>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $cntr += 1;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="accbRcnclJrnlTrnsLines" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row"> 
                                                <div class="col-md-12"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div id="accbRcnclActvtyRatios" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row"> 
                                                <div class="col-md-12"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div id="accbRcnclProfitRatios" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row"> 
                                                <div class="col-md-12"> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </fieldset>
                    </div>
                </div>
                </form>
                <?php
            }else if ($vwtyp == 100) {
                $canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
                $canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canVoid = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canPost = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Consolidated Trial Balance</span>
				</li>
                               </ul>
                              </div>";
                echo $cntent;
                ?>
                <div class="row">
                    <?php
                    $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                    //echo $ymdtme;
                    $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                    $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");

                    $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                    $qShwVariance = isset($_POST['accbFSRptShwVariance']) ? (cleanInputData($_POST['accbFSRptShwVariance']) === "YES" ? TRUE
                                : FALSE) : FALSE;
                    $qShwHideZero = isset($_POST['accbFSRptShwHideZero']) ? (cleanInputData($_POST['accbFSRptShwHideZero']) === "YES" ? TRUE
                                : FALSE) : TRUE;
                    $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE)
                                : TRUE;
                    $qShwNetPos = isset($_POST['accbFSRptShwNetPos']) ? (cleanInputData($_POST['accbFSRptShwNetPos']) === "YES" ? TRUE : FALSE)
                                : TRUE;

                    $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                    $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                                : -1;
                    $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                    if ($accbFSRptSbmtdAccountID > 0) {
                        $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                    }
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

                    $shwVarianceChkd = "";
                    if ($qShwVariance == true) {
                        $shwVarianceChkd = "checked=\"true\"";
                    }
                    $shwHideZeroChkd = "";
                    if ($qShwHideZero == true) {
                        $shwHideZeroChkd = "checked=\"true\"";
                    }
                    $shwSmmryChkd = "";
                    if ($qShwSmmry == true) {
                        $shwSmmryChkd = "checked=\"true\"";
                    }
                    $shwNetPosChkd = "";
                    if ($qShwNetPos == true) {
                        $shwNetPosChkd = "checked=\"true\"";
                    }
                    $fsrptRunID = -1;
                    if ($startRunng == 1) {
                        $fsrptRunID = getNewFSRptRunID();
                        $useNetPos = $qShwNetPos ? "Yes" : "No";
                        $shwVarnce = $qShwVariance ? "Yes" : "No";
                        if ($accbFSRptDte != "") {
                            $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                        }
                        //Here's the main work
                        $strSql = "select accb.populate_conso_trial_bals( " . $fsrptRunID . ", '" . $useNetPos .
                                "', '" . $accbFSRptDte . "', " . $accbFSRptMaxAcntLvl . ", "
                                . $accbFSRptSbmtdAccountID . ", '" . $shwVarnce . "', "
                                . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                                . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                                . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                                . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                                . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                                . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                        $result = executeSQLNoParams($strSql);
                    }

                    $nwRowHtml2 = "<tr id=\"oneJrnlBatchDetRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneJrnlBatchDetLinesTable tr').index(this));\">"
                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                            . "<td class=\"lovtd\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_AccountID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">    
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsSmryLnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_AccountNm\" name=\"oneJrnlBatchDetRow_WWW123WWW_AccountNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchDetRow_WWW123WWW_AccountID', 'oneJrnlBatchDetRow_WWW123WWW_AccountNm', 'clear', 1, '', function () {

                                                                                                                });\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                        </div>                                              
                                                                    </td>                                          
                                                                    <td class=\"lovtd\"  style=\"\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_LineDesc\" name=\"oneJrnlBatchDetRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_LineDesc', 'oneJrnlBatchDetLinesTable', 'jbDetDesc');\">                                                    
                                                                    </td>                                                  
                                                                    <td class=\"lovtd\">
                                                                        <div class=\"\" style=\"width:100% !important;\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm\" name=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm\" value=\"" . $fnccurnm . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                            <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                            $('#oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm1').html($('#oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm').val());
                                                                                                        });\">
                                                                                <span class=\"\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm1\">" . $fnccurnm . "</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_DebitAmnt\" name=\"oneJrnlBatchDetRow_WWW123WWW_DebitAmnt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_DebitAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchDetTtl();\">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetCrdt\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_CreditAmnt\" name=\"oneJrnlBatchDetRow_WWW123WWW_CreditAmnt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_CreditAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchDetTtl();\">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Denominational Breakdown\" 
                                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneJrnlBatchDetRow_WWW123WWW_DebitAmnt', 'oneJrnlBatchDetRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\"> 
                                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                            </button>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneJrnlBatchDetRow_WWW123WWW_TransDte\" value=\"" . $accbFSRptDte1 . " 12:00:00\">
                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                            </div>                                                        
                                                                    </td>                                         
                                                                    <td class=\"lovtd\"  style=\"\">
                                                                        <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_RefDoc\" name=\"oneJrnlBatchDetRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_RefDoc', 'oneJrnlBatchDetLinesTable', 'jbDetRfDc');\">                                                    
                                                                    </td>";
                    if ($canDel === true && $canEdt === true) {
                        $nwRowHtml2 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbJrnlBatchDetLn('oneJrnlBatchDetRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Journal Line\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                    }
                    if ($canVwRcHstry === true) {
                        $nwRowHtml2 .= "<td class=\"lovtd\">&nbsp;</td>";
                    }
                    $nwRowHtml2 .= "</tr>";
                    $nwRowHtml2 = urlencode($nwRowHtml2);
                    ?>
                    <div class="col-md-12" style="padding:0px 15px 0px 15px;">
                        <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                            <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                <li class="active"><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclGlStatemtLines" id="accbRcnclGlStatemtLinestab">Main Report</a></li>
                                <li class="" style="display:none;"><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclJrnlTrnsLines" id="accbRcnclJrnlTrnsLinestab">Corrective Journal Entries</a></li>
                            </ul>
                            <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                    <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                        <div class="row"> 
                                            <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                                                <form class="form-horizontal" id="accbFSRptForm">
                                                    <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                        <legend class="basic_person_lg">
                                                            Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </legend>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwVariance" name="accbFSRptShwVariance" <?php echo $shwVarianceChkd; ?>>
                                                                    Show Variances
                                                                </label>
                                                            </div>                            
                                                        </div>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwHideZero" name="accbFSRptShwHideZero" <?php echo $shwHideZeroChkd; ?>>
                                                                    Hide Zero Balances
                                                                </label>
                                                            </div>                            
                                                        </div>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                                                    Summary Report
                                                                </label>
                                                            </div>                            
                                                        </div>
                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwNetPos" name="accbFSRptShwNetPos" <?php echo $shwNetPosChkd; ?>>
                                                                    Use Net Positions
                                                                </label>
                                                            </div>                            
                                                        </div>
                                                        <div  class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="margin-bottom:0px !important;">As At Date:</label>
                                                                </div>
                                                                <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                    <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="As At Date:">
                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="margin-bottom:0px !important;">Account Level Maximum Depth:</label>
                                                                </div>
                                                                <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                                                    <input type="number" min="1" max="10000" class="form-control" aria-label="..." id="accbFSRptMaxAcntLvl" name="accbFSRptMaxAcntLvl" value="<?php echo $accbFSRptMaxAcntLvl; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                                                <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">Starting Parent Account:</label>
                                                                <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
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
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div  class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="width:100%;" class="btn btn-primary btn-file" onclick="getAcntSgmtBrkdwnForm(<?php echo $accbFSRptSbmtdAccountID; ?>, 2, 'accbFSRptSgmnt', 'accbFSRptAcntNum1', 'accbFSRptAcntNum');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                                                    </label>
                                                                </div>
                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=5&vtyp=100');">
                                                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Generate Report
                                                            </button>
                                                        </div>
                                                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=5&vtyp=100');">
                                                                <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </div>
                                                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div>
                                            <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                <form class="form-horizontal" id="accbFSRptDetForm">
                                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a> CONSOLIDATED TRIAL BALANCE AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                                        </caption>
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                <!--<th style="max-width:20px;width:20px;">...</th>-->
                                                                <th style="max-width:20px;width:20px;">No.</th>
                                                                <th style="max-width:450px !important;">Account Number/Name</th>
                                                                <th style="text-align: right;">Debit Balance</th>
                                                                <th style="text-align: right;">Credit Balance</th>
                                                                <th style="text-align: right;">Net Balance</th>
                                                                <th style="max-width:70px;width:70px;">Transaction Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $maxNoRows = 0;
                                                            $resultRw = null;
                                                            if ($fsrptRunID > 0) {
                                                                $resultRw = get_TbalsRpt($fsrptRunID, $accbFSRptDte);
                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                            }
                                                            $ttlTrsctnDbtAmnt = 0;
                                                            $ttlTrsctnCrdtAmnt = 0;
                                                            $ttlTrsctnNetAmnt = 0;
                                                            while ($cntr < $maxNoRows) {
                                                                $rowNumber = 0;
                                                                $trsctnAcntID = -1;
                                                                $trsctnAcntNm = "";
                                                                $trsctnDbtAmnt = 0;
                                                                $trsctnCrdtAmnt = 0;
                                                                $trsctnNetAmnt = 0;
                                                                $trsctnLineDate = "";
                                                                $isParent = "0";
                                                                $numStyle1 = "text-align:right;";
                                                                $nameStyle1 = "";
                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $rowNumber = (float) $rowRw[0];
                                                                    $trsctnAcntID = (int) $rowRw[7];
                                                                    $trsctnAcntNm = str_replace(" ", "&nbsp;", $rowRw[1] . "." . $rowRw[2]);
                                                                    $trsctnDbtAmnt = (float) $rowRw[3];
                                                                    $trsctnCrdtAmnt = (float) $rowRw[4];
                                                                    $trsctnNetAmnt = (float) $rowRw[5];
                                                                    $trsctnLineDate = $rowRw[6];
                                                                    $isParent = $rowRw[8];
                                                                    if ($isParent == "1") {
                                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                                        $nameStyle1 = "font-weight:bold;";
                                                                    }
                                                                    $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + (float) $rowRw[10];
                                                                    $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + (float) $rowRw[11];
                                                                    $ttlTrsctnNetAmnt = $ttlTrsctnDbtAmnt - $ttlTrsctnCrdtAmnt;
                                                                }
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                    <td class="lovtd">
                                                                        <input type="checkbox" name="oneAccbFSRptRow<?php echo $cntr; ?>_CheckBox" value="oneAccbFSRptRow_<?php echo $cntr; ?>">
                                                                    </td>                                
                                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $rowRw[2]; ?>" >
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">   
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNm" value="<?php echo trim($trsctnAcntNm); ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_DbtAmnt" value="<?php echo $trsctnDbtAmnt; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_CrdtAmnt" value="<?php echo $trsctnCrdtAmnt; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_TrnsDte" value="<?php echo trim($trsctnLineDate); ?>" style="width:100% !important;">
                                                                        <span><?php echo $trsctnAcntNm; ?></span>             
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnNetAmnt, 2); ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo $trsctnLineDate; ?></span>                   
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>                                                            
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <!--<th>&nbsp;</th>-->
                                                                <th style="">&nbsp;</th>
                                                                <th>TOTALS:</th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format($ttlTrsctnNetAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                                </th>   
                                                                <th style="">&nbsp;</th>   
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <?php
            }
        }
    }
}
?>