<?php
$canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Budget */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteBdgt($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Budget Line */
                $slctdBdgtLineIDs = isset($_POST['slctdBdgtLineIDs']) ? cleanInputData($_POST['slctdBdgtLineIDs']) : '';
                $variousRows = explode("|", trim($slctdBdgtLineIDs, "|"));
                $affctd1 = 0;
                if ($canDel) {
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $ln_TrnsLnID = (float) cleanInputData1($crntRow[0]);
                            $ln_TrnsLnIDDesc = cleanInputData1($crntRow[1]);
                            $affctd1 += deleteBudgetLine($ln_TrnsLnID, $ln_TrnsLnIDDesc);
                        }
                    }
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Budget Line(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Budget Amount Breakdown */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteBdgtBrkDwn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                header("content-type:application/json");
                $slctdBudgets = isset($_POST['slctdBudgets']) ? cleanInputData($_POST['slctdBudgets']) : '';
                $errMsg = "";
                if ($slctdBudgets != "") {
                    //Save Budgets
                    $affctd = 0;
                    $variousRows = explode("|", trim($slctdBudgets, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 7) {
                            $lnBdgtID = (float) cleanInputData1($crntRow[0]);
                            $ln_LineNm = cleanInputData1($crntRow[1]);
                            $ln_AcntType = cleanInputData1($crntRow[2]);
                            $ln_StrtDte = cleanInputData1($crntRow[3]);
                            $ln_EndDte = cleanInputData1($crntRow[4]);
                            $ln_PrdType = cleanInputData1($crntRow[5]);
                            $ln_IsActive = cleanInputData1($crntRow[6]) == "YES" ? TRUE : FALSE;
                            if ($lnBdgtID <= 0) {
                                $affctd += createBudget(
                                    $orgID,
                                    $ln_LineNm,
                                    $ln_LineNm,
                                    $ln_IsActive,
                                    $ln_StrtDte,
                                    $ln_EndDte,
                                    $ln_PrdType,
                                    $ln_AcntType
                                );
                            } else {
                                $affctd += updateBudget(
                                    $lnBdgtID,
                                    $ln_LineNm,
                                    $ln_LineNm,
                                    $ln_IsActive,
                                    $ln_StrtDte,
                                    $ln_EndDte,
                                    $ln_PrdType,
                                    $ln_AcntType
                                );
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Budget Header(s) Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                header("content-type:application/json");
                $accbSbmtdBudgetID = isset($_POST['accbSbmtdBudgetID']) ? (float) cleanInputData($_POST['accbSbmtdBudgetID']) : -1;
                $slctdBudgetLines = isset($_POST['slctdBudgetLines']) ? cleanInputData($_POST['slctdBudgetLines']) : '';
                $errMsg = "";
                //var_dump($_POST);
                if ($slctdBudgetLines != "" && $accbSbmtdBudgetID > 0) {
                    //Save Budget Lines
                    $affctd = 0;
                    $affctd1 = 0;
                    $variousRows = explode("|", trim($slctdBudgetLines, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 9) {
                            $ln_TrnsLnID = (float) cleanInputData1($crntRow[0]);
                            $ln_AccountID1 = (float) cleanInputData1($crntRow[1]);
                            $ln_SlctdAmtBrkdwns = cleanInputData1($crntRow[2]);
                            $ln_TrnsCurNm = cleanInputData1($crntRow[3]);
                            $ln_TrnsCurID = getPssblValID($ln_TrnsCurNm, getLovID("Currencies"));
                            if ($ln_TrnsCurID <= 0) {
                                $arr_content['percent'] = 100;
                                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Sorry, Budget Currency cannot be Empty!</span>";
                                echo json_encode($arr_content);
                                exit();
                            }
                            $ln_EntrdAmt = (float) cleanInputData1($crntRow[4]);
                            $ln_StrtDte = cleanInputData1($crntRow[5]);
                            $ln_EndDte = cleanInputData1($crntRow[6]);
                            $ln_Action = cleanInputData1($crntRow[7]);
                            $ln_FuncExchgRate = cleanInputData1($crntRow[8]);
                            if ($ln_FuncExchgRate == 1 || $ln_FuncExchgRate == 0) {
                                $ln_FuncExchgRate = round(get_LtstExchRate($ln_TrnsCurID, $fnccurid, $ln_StrtDte), 4);
                            }
                            if ($ln_TrnsLnID <= 0 && $accbSbmtdBudgetID > 0) {
                                $affctd += createBdgtLn(
                                    $accbSbmtdBudgetID,
                                    $ln_AccountID1,
                                    ($ln_EntrdAmt * $ln_FuncExchgRate),
                                    $ln_StrtDte,
                                    $ln_EndDte,
                                    $ln_Action,
                                    $ln_EntrdAmt,
                                    $ln_FuncExchgRate,
                                    $ln_TrnsCurID
                                );
                                $ln_TrnsLnID = get_BdgtDetID($ln_StrtDte, $ln_EndDte, $accbSbmtdBudgetID, $ln_AccountID1);
                            } else if ($accbSbmtdBudgetID > 0) {
                                $affctd += updateBdgtLn(
                                    $ln_TrnsLnID,
                                    $ln_AccountID1,
                                    ($ln_EntrdAmt * $ln_FuncExchgRate),
                                    $ln_StrtDte,
                                    $ln_EndDte,
                                    $ln_Action,
                                    $ln_EntrdAmt,
                                    $ln_FuncExchgRate,
                                    $ln_TrnsCurID
                                );
                            }
                            if ($ln_SlctdAmtBrkdwns != "" && $ln_TrnsLnID > 0) {
                                //Save Budget Amount Breakdowns
                                $variousRows1 = explode("|", trim($ln_SlctdAmtBrkdwns, "|"));
                                for ($y = 0; $y < count($variousRows1); $y++) {
                                    $crntRow = explode("~", $variousRows1[$y]);
                                    if (count($crntRow) == 9) {
                                        $ln_BrkdwnLnID = (float) cleanInputData1($crntRow[0]);
                                        $ln_DetID = (float) cleanInputData1($crntRow[1]);
                                        $ln_ItemID = (float) cleanInputData1($crntRow[2]);
                                        $ln_ItemName = cleanInputData1($crntRow[3]);
                                        $ln_DetType = cleanInputData1($crntRow[4]);
                                        $ln_BrkdwnQTY1 = (float) cleanInputData1($crntRow[5]);
                                        $ln_BrkdwnQTY2 = (float) cleanInputData1($crntRow[6]);
                                        $ln_BrkdwnUnitVal = (float) cleanInputData1($crntRow[7]);
                                        $ln_BrkdwnDesc = cleanInputData1($crntRow[8]);
                                        if ($ln_BrkdwnLnID <= 0 && ($ln_BrkdwnQTY1 * $ln_BrkdwnQTY2 * $ln_BrkdwnUnitVal) != 0) {
                                            $ln_BrkdwnLnID = getNewBrkDwnLnID();
                                            $affctd1 += createBdgtBrkDwn(
                                                $ln_BrkdwnLnID,
                                                $ln_AccountID1,
                                                $ln_ItemID,
                                                $ln_DetType,
                                                $ln_BrkdwnDesc,
                                                $ln_BrkdwnQTY1,
                                                $ln_BrkdwnQTY2,
                                                $ln_BrkdwnUnitVal,
                                                $ln_TrnsLnID,
                                                $ln_StrtDte,
                                                $ln_EndDte
                                            );
                                        } else if ($ln_BrkdwnLnID > 0 && ($ln_BrkdwnQTY1 * $ln_BrkdwnQTY2 * $ln_BrkdwnUnitVal) != 0) {
                                            $affctd1 += updateBdgtBrkDwn(
                                                $ln_BrkdwnLnID,
                                                $ln_AccountID1,
                                                $ln_ItemID,
                                                $ln_DetType,
                                                $ln_BrkdwnDesc,
                                                $ln_BrkdwnQTY1,
                                                $ln_BrkdwnQTY2,
                                                $ln_BrkdwnUnitVal,
                                                $ln_TrnsLnID,
                                                $ln_StrtDte,
                                                $ln_EndDte
                                            );
                                        }
                                    }
                                }
                                if ($affctd1 > 0) {
                                    updateBdgtDetAmnt1($ln_TrnsLnID, $ln_AccountID1);
                                }
                            }
                        }
                    }

                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Budget Line(s) Saved!<br/>"
                        . $affctd1 . " Budget Amount Breakdown(s) Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    session_write_close();
                    execSsnUpdtInsSQL("REFRESH MATERIALIZED VIEW CONCURRENTLY accb.accb_budget_detail_mv");
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 3) {
            } else if ($actyp == 901) {
                //Import Budget Lines
                $accbSbmtdBudgetID = isset($_POST['accbSbmtdBudgetID']) ? (float) cleanInputData($_POST['accbSbmtdBudgetID']) : -1;
                $bdgtPeriodType = getGnrlRecNm("accb.accb_budget_header", "budget_id", "period_type", $accbSbmtdBudgetID);
                $bdgtYear = getGnrlRecNm("accb.accb_budget_header", "budget_id", "substr(start_date,1,4)", $accbSbmtdBudgetID);
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                //var_dump($_POST);
                //logSessionErrs($dataToSend);
                session_write_close();
                $ln_accntnumber = "";
                $ln_accntName = "";
                $ln_bdgtCrncyCode = "";
                $ln_actnIfExceeded = "";
                $ln_amnt1Value = "";
                $ln_amnt2Value = "";
                $ln_amnt3Value = "";
                $ln_amnt4Value = "";
                $ln_amnt5Value = "";
                $ln_amnt6Value = "";
                $ln_amnt7Value = "";
                $ln_amnt8Value = "";
                $ln_amnt9Value = "";
                $ln_amnt10Value = "";
                $ln_amnt11Value = "";
                $ln_amnt12Value = "";
                $affctd = 0;
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    $expctdCols = 16;
                    if ($bdgtPeriodType == "Yearly") {
                        $expctdCols = 5;
                    } else if ($bdgtPeriodType == "Half Yearly") {
                        $expctdCols = 6;
                    } else if ($bdgtPeriodType == "Quarterly") {
                        $expctdCols = 8;
                    }
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) ==  $expctdCols) {
                            $ln_accntnumber = trim(cleanInputData1($crntRow[0]));
                            $ln_accntName = trim(cleanInputData1($crntRow[1]));
                            $ln_bdgtCrncyCode = trim(cleanInputData1($crntRow[2]));
                            $ln_actnIfExceeded = trim(cleanInputData1($crntRow[3]));
                            $ln_amnt1Value = trim(cleanInputData1($crntRow[4]));

                            if ($bdgtPeriodType == "Monthly") {
                                $ln_amnt2Value = trim(cleanInputData1($crntRow[5]));
                                $ln_amnt3Value = trim(cleanInputData1($crntRow[6]));
                                $ln_amnt4Value = trim(cleanInputData1($crntRow[7]));
                                $ln_amnt5Value = trim(cleanInputData1($crntRow[8]));
                                $ln_amnt6Value = trim(cleanInputData1($crntRow[9]));
                                $ln_amnt7Value = trim(cleanInputData1($crntRow[10]));
                                $ln_amnt8Value = trim(cleanInputData1($crntRow[11]));
                                $ln_amnt9Value = trim(cleanInputData1($crntRow[12]));
                                $ln_amnt10Value = trim(cleanInputData1($crntRow[13]));
                                $ln_amnt11Value = trim(cleanInputData1($crntRow[14]));
                                $ln_amnt12Value = trim(cleanInputData1($crntRow[15]));
                            } else if ($bdgtPeriodType == "Half Yearly") {
                                $ln_amnt2Value = trim(cleanInputData1($crntRow[5]));
                            } else if ($bdgtPeriodType == "Quarterly") {
                                $ln_amnt2Value = trim(cleanInputData1($crntRow[5]));
                                $ln_amnt3Value = trim(cleanInputData1($crntRow[6]));
                                $ln_amnt4Value = trim(cleanInputData1($crntRow[7]));
                            }
                            if ($z == 0) {
                                if (
                                    strtoupper($ln_accntnumber) == strtoupper("Account Number**")
                                    && strtoupper($ln_accntName) == strtoupper("Account Name**")
                                    && strtoupper($ln_bdgtCrncyCode) == strtoupper("Budget Currency Code**")
                                ) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents(
                                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_bdgtsimprt_progress.rho",
                                        json_encode($arr_content)
                                    );
                                    break;
                                }
                            }

                            $ln_AccountID1 =  getAccntID($ln_accntnumber, $orgID);;
                            $ln_SlctdAmtBrkdwns = "";
                            $ln_TrnsCurNm = ($ln_bdgtCrncyCode == "" ? $fnccurnm : $ln_bdgtCrncyCode);
                            $ln_TrnsCurID = getPssblValID($ln_TrnsCurNm, getLovID("Currencies"));
                            if ($ln_TrnsCurID <= 0 || $ln_AccountID1 <= 0) {
                                $arr_content['percent'] = 100;
                                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Sorry, Budget Currency and Account cannot be Empty!</span>";
                                $arr_content['msgcount'] = $total;
                                file_put_contents(
                                    $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_bdgtsimprt_progress.rho",
                                    json_encode($arr_content)
                                );
                                break;
                            }
                            $startDtes = array(
                                "01-Jan-" . $bdgtYear, "01-Feb-" . $bdgtYear, "01-Mar-" . $bdgtYear, "01-Apr-" . $bdgtYear, "01-May-" . $bdgtYear,
                                "01-Jun-" . $bdgtYear, "01-Jul-" . $bdgtYear, "01-Aug-" . $bdgtYear, "01-Sep-" . $bdgtYear, "01-Oct-" . $bdgtYear,
                                "01-Nov-" . $bdgtYear, "01-Dec-" . $bdgtYear
                            );
                            $endDtes = array(
                                "01-Jan-" . $bdgtYear, "01-Feb-" . $bdgtYear, "01-Mar-" . $bdgtYear, "01-Apr-" . $bdgtYear, "01-May-" . $bdgtYear,
                                "01-Jun-" . $bdgtYear, "01-Jul-" . $bdgtYear, "01-Aug-" . $bdgtYear, "01-Sep-" . $bdgtYear, "01-Oct-" . $bdgtYear,
                                "01-Nov-" . $bdgtYear, "01-Dec-" . $bdgtYear
                            );
                            $prdVals = array(
                                $ln_amnt1Value, $ln_amnt2Value, $ln_amnt3Value, $ln_amnt4Value,
                                $ln_amnt5Value, $ln_amnt6Value, $ln_amnt7Value, $ln_amnt8Value,
                                $ln_amnt9Value, $ln_amnt10Value, $ln_amnt11Value, $ln_amnt12Value
                            );
                            if ($bdgtPeriodType == "Yearly") {
                                $startDtes = array(
                                    "01-Jan-" . $bdgtYear
                                );
                                $endDtes = array(
                                    "01-Dec-" . $bdgtYear
                                );
                                $prdVals = array(
                                    $ln_amnt1Value
                                );
                            } else if ($bdgtPeriodType == "Half Yearly") {
                                $startDtes = array(
                                    "01-Jan-" . $bdgtYear, "01-Jul-" . $bdgtYear
                                );
                                $endDtes = array(
                                    "01-Jun-" . $bdgtYear, "01-Dec-" . $bdgtYear
                                );
                                $prdVals = array(
                                    $ln_amnt1Value, $ln_amnt2Value
                                );
                            } else if ($bdgtPeriodType == "Quarterly") {
                                $startDtes = array(
                                    "01-Jan-" . $bdgtYear, "01-Apr-" . $bdgtYear,
                                    "01-Jul-" . $bdgtYear, "01-Oct-" . $bdgtYear
                                );
                                $endDtes = array(
                                    "01-Mar-" . $bdgtYear, "01-Jun-" . $bdgtYear,
                                    "01-Sep-" . $bdgtYear, "01-Dec-" . $bdgtYear
                                );
                                $prdVals = array(
                                    $ln_amnt1Value, $ln_amnt2Value, $ln_amnt3Value, $ln_amnt4Value
                                );
                            }
                            for ($k = 0; $k < count($startDtes); $k++) {
                                $ln_EntrdAmt = (float) $prdVals[$k];
                                $ln_StrtDte = $startDtes[$k];
                                $ln_EndDte = get_Last_Date_Mnth($endDtes[$k]);
                                $ln_Action = $ln_actnIfExceeded;
                                $ln_FuncExchgRate = 1;
                                if ($ln_FuncExchgRate == 1 || $ln_FuncExchgRate == 0) {
                                    $ln_FuncExchgRate = round(get_LtstExchRate($ln_TrnsCurID, $fnccurid, $ln_StrtDte), 4);
                                }
                                $exitErrMsg = "";
                                if ($ln_AccountID1 <= 0) {
                                    $exitErrMsg .= "Account Number does not exist in this Organisation!<br/>";
                                }
                                $ln_TrnsLnID = get_BdgtDetID($ln_StrtDte, $ln_EndDte, $accbSbmtdBudgetID, $ln_AccountID1);
                                if ($ln_TrnsLnID <= 0 && $accbSbmtdBudgetID > 0) {
                                    $affctd += createBdgtLn(
                                        $accbSbmtdBudgetID,
                                        $ln_AccountID1,
                                        ($ln_EntrdAmt * $ln_FuncExchgRate),
                                        $ln_StrtDte,
                                        $ln_EndDte,
                                        $ln_Action,
                                        $ln_EntrdAmt,
                                        $ln_FuncExchgRate,
                                        $ln_TrnsCurID
                                    );
                                    $ln_TrnsLnID = get_BdgtDetID($ln_StrtDte, $ln_EndDte, $accbSbmtdBudgetID, $ln_AccountID1);
                                } else if ($accbSbmtdBudgetID > 0) {
                                    $affctd += updateBdgtLn(
                                        $ln_TrnsLnID,
                                        $ln_AccountID1,
                                        ($ln_EntrdAmt * $ln_FuncExchgRate),
                                        $ln_StrtDte,
                                        $ln_EndDte,
                                        $ln_Action,
                                        $ln_EntrdAmt,
                                        $ln_FuncExchgRate,
                                        $ln_TrnsCurID
                                    );
                                }
                            }
                            /*if ($ln_SlctdAmtBrkdwns != "" && $ln_TrnsLnID > 0) {
                                //Save Budget Amount Breakdowns
                                $variousRows1 = explode("|", trim($ln_SlctdAmtBrkdwns, "|"));
                                for ($y = 0; $y < count($variousRows1); $y++) {
                                    $crntRow = explode("~", $variousRows1[$y]);
                                    if (count($crntRow) == 9) {
                                        $ln_BrkdwnLnID = (float) cleanInputData1($crntRow[0]);
                                        $ln_DetID = (float) cleanInputData1($crntRow[1]);
                                        $ln_ItemID = (float) cleanInputData1($crntRow[2]);
                                        $ln_ItemName = cleanInputData1($crntRow[3]);
                                        $ln_DetType = cleanInputData1($crntRow[4]);
                                        $ln_BrkdwnQTY1 = (float) cleanInputData1($crntRow[5]);
                                        $ln_BrkdwnQTY2 = (float) cleanInputData1($crntRow[6]);
                                        $ln_BrkdwnUnitVal = (float) cleanInputData1($crntRow[7]);
                                        $ln_BrkdwnDesc = cleanInputData1($crntRow[8]);
                                        if ($ln_BrkdwnLnID <= 0 && ($ln_BrkdwnQTY1 * $ln_BrkdwnQTY2 * $ln_BrkdwnUnitVal) != 0) {
                                            $ln_BrkdwnLnID = getNewBrkDwnLnID();
                                            $affctd1 += createBdgtBrkDwn(
                                                $ln_BrkdwnLnID,
                                                $ln_AccountID1,
                                                $ln_ItemID,
                                                $ln_DetType,
                                                $ln_BrkdwnDesc,
                                                $ln_BrkdwnQTY1,
                                                $ln_BrkdwnQTY2,
                                                $ln_BrkdwnUnitVal,
                                                $ln_TrnsLnID,
                                                $ln_StrtDte,
                                                $ln_EndDte
                                            );
                                        } else if ($ln_BrkdwnLnID > 0 && ($ln_BrkdwnQTY1 * $ln_BrkdwnQTY2 * $ln_BrkdwnUnitVal) != 0) {
                                            $affctd1 += updateBdgtBrkDwn(
                                                $ln_BrkdwnLnID,
                                                $ln_AccountID1,
                                                $ln_ItemID,
                                                $ln_DetType,
                                                $ln_BrkdwnDesc,
                                                $ln_BrkdwnQTY1,
                                                $ln_BrkdwnQTY2,
                                                $ln_BrkdwnUnitVal,
                                                $ln_TrnsLnID,
                                                $ln_StrtDte,
                                                $ln_EndDte
                                            );
                                        }
                                    }
                                }
                                if ($affctd1 > 0) {
                                    updateBdgtDetAmnt1($ln_TrnsLnID, $ln_AccountID1);
                                }
                            }*/
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . ($z + 1) . " out of " . $total . " Budget Line(s) imported.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Budget Line(s)...Please Wait..." . ($z + 1) . " out of " . $total . " Budget Line(s) imported.";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_bdgtsimprt_progress.rho",
                            json_encode($arr_content)
                        );
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_bdgtsimprt_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 902) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_bdgtsimprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => null, "message" => null));
                }
            } else if ($actyp == 903) {
                //Export Accounts
                $accbSbmtdBudgetID = isset($_POST['accbSbmtdBudgetID']) ? (float) cleanInputData($_POST['accbSbmtdBudgetID']) : -1;
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                //var_dump($_POST);
                session_write_close();
                $bdgtPeriodType = getGnrlRecNm("accb.accb_budget_header", "budget_id", "period_type", $accbSbmtdBudgetID);
                $bdgtYear = getGnrlRecNm("accb.accb_budget_header", "budget_id", "substr(start_date,1,4)", $accbSbmtdBudgetID);
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array(
                        "Account Number**", "Account Name**", "Budget Currency Code**", "Action if Exceeded**", "Jan " . $bdgtYear . " Amount",
                        "Feb " . $bdgtYear . " Amount", "Mar " . $bdgtYear . " Amount", "Apr " . $bdgtYear . " Amount", "May " . $bdgtYear . " Amount",
                        "Jun " . $bdgtYear . " Amount", "Jul " . $bdgtYear . " Amount", "Aug " . $bdgtYear . " Amount", "Sep " . $bdgtYear . " Amount",
                        "Oct " . $bdgtYear . " Amount", "Nov " . $bdgtYear . " Amount", "Dec " . $bdgtYear . " Amount"
                    );
                    if ($bdgtPeriodType == "Yearly") {
                        $hdngs = array(
                            "Account Number**", "Account Name**", "Budget Currency Code**", "Action if Exceeded**", "" . $bdgtYear . " Amount"
                        );
                    } else if ($bdgtPeriodType == "Half Yearly") {
                        $hdngs = array(
                            "Account Number**", "Account Name**", "Budget Currency Code**", "Action if Exceeded**", "Jan-Jun " . $bdgtYear . " Amount", "Jul-Dec " . $bdgtYear . " Amount"
                        );
                    } else if ($bdgtPeriodType == "Quarterly") {
                        $hdngs = array(
                            "Account Number**", "Account Name**", "Budget Currency Code**", "Action if Exceeded**", "Q1 " . $bdgtYear . " Amount", "Q2 " . $bdgtYear . " Amount", "Q3 " . $bdgtYear . " Amount", "Q4 " . $bdgtYear . " Amount"
                        );
                    }
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/AccntBdgtsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/AccntBdgtsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Accounts Chart Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntBdgtsExprt_progress.rho",
                            json_encode($arr_content)
                        );
                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_One_BdgtDtExprt("%", "Account Number", 0, $limit_size, $accbSbmtdBudgetID, $bdgtYear, $bdgtPeriodType);
                    // get_ChartsToExprt($orgID, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        if ($bdgtPeriodType == "Yearly") {
                            $crntRw = array(
                                "" . $row[0], $row[1], $row[2], $row[3], $row[4]
                            );
                        }else if ($bdgtPeriodType == "Half Yearly") {
                            $crntRw = array(
                                "" . $row[0], $row[1], $row[2], $row[3], $row[4], $row[5]
                            );
                        } else if ($bdgtPeriodType == "Quarterly") {
                            $crntRw = array(
                                "" . $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]
                            );
                        } else {
                            $crntRw = array(
                                "" . $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6],
                                $row[7], $row[8], $row[9], $row[10], $row[11],
                                $row[12], $row[13], $row[14], $row[15]
                            );
                        }
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                1) . " out of " . $total . " Account(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Accounts...Please Wait..." . ($z +
                                1) . " out of " . $total . " Account(s) exported.</span>";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntBdgtsExprt_progress.rho",
                            json_encode($arr_content)
                        );
                        $z++;
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntBdgtsExprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else if ($actyp == 904) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntBdgtsExprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
            }
        } else {
            if ($vwtyp == 0) {
                //Budgeting                
                if (isBdgtMVPopulated() === false) {
                    execSsnUpdtInsSQL("REFRESH MATERIALIZED VIEW accb.accb_budget_detail_mv");
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Budgets</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $shdRefreshMatView = false;
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
                $total = get_Total_Bdgt($srchFor, $srchIn, $orgID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Basic_Bdgt($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-3";
?>
                <fieldset class="">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                <li class="active"><a data-toggle="tabajxaccbbdgts" data-rhodata="" href="#accbBdgtsMainList" id="accbBdgtsMainListtab">Summary List</a></li>
                                <li class=""><a data-toggle="tabajxaccbbdgts" data-rhodata="" href="#accbBdgtsDetList" id="accbBdgtsDetListtab">Detailed List</a></li>
                                <li class=""><a data-toggle="tabajxaccbbdgts" data-rhodata="" href="#accbBdgtsFurthDetList" id="accbBdgtsFurthDetListtab">Budget Items</a></li>
                            </ul>
                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                    <div id="accbBdgtsMainList" class="tab-pane fadein active" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                        <form id='accbBdgtsForm' action='' method='post' accept-charset='UTF-8'>
                                            <!--ROW ID-->
                                            <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                                            <fieldset class="">
                                                <legend class="basic_person_lg1" style="color: #003245">BUDGETS</legend>
                                                <div class="row" style="margin-bottom:0px;">
                                                    <?php
                                                    if ($canAdd === true) {
                                                        $nwRowHtml33 = "<tr id=\"accbBdgtsHdrsRow__WWW123WWW\">                                    
                                                                            <td class=\"lovtd\">New</td>    
                                                                            <td class=\"lovtd\">
                                                                                <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit Budget\" 
                                                                                        onclick=\"\" style=\"padding:2px !important;\" style=\"padding:2px !important;\"> 
                                                                                    <img src=\"cmn_images/kghostview.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">
                                                                                </button>
                                                                            </td>
                                                                            <td class=\"lovtd\">
                                                                                    <input type=\"hidden\" id=\"accbBdgtsHdrsRow_WWW123WWW_HdrID\" name=\"accbBdgtsHdrsRow_WWW123WWW_HdrID\" value=\"-1\">
                                                                                    <input type=\"text\" class=\"form-control rqrdFld jbDetRfDc\" aria-label=\"...\" id=\"accbBdgtsHdrsRow_WWW123WWW_LineNm\" name=\"accbBdgtsHdrsRow_WWW123WWW_LineNm\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtsHdrsRow_WWW123WWW_LineNm', 'accbBdgtsHdrsTable', 'jbDetRfDc');\">                                                    
                                                                            </td>
                                                                            <!--<td class=\"lovtd\">
                                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"accbBdgtsHdrsRow_WWW123WWW_LineDesc\" name=\"accbBdgtsHdrsRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtsHdrsRow_WWW123WWW_LineDesc', 'accbBdgtsHdrsTable', 'jbDetDesc');\">                                                    
                                                                                </td>-->
                                                                            <td class=\"lovtd\">
                                                                                    <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"accbBdgtsHdrsRow_WWW123WWW_AcntType\" style=\"width:100% !important;\">";
                                                        $valslctdArry = array("", "", "", "");
                                                        $srchInsArrys = array(
                                                            "INCOME/EXPENDITURE", "EXPENDITURE", "ASSETS/INCOME/EXPENDITURE",
                                                            "ALL"
                                                        );
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                        }
                                                        $nwRowHtml33 .= "</select>
                                                                            </td>
                                                                            <td class=\"lovtd\">
                                                                                    <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"accbBdgtsHdrsRow_WWW123WWW_StrtDte\" value=\"\" style=\"width:100%;\">
                                                                                        <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                    </div>  
                                                                            </td>
                                                                            <td class=\"lovtd\">
                                                                                    <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"accbBdgtsHdrsRow_WWW123WWW_EndDte\" value=\"\" style=\"width:100%;\">
                                                                                        <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                    </div>
                                                                            </td>
                                                                            <td class=\"lovtd\">
                                                                                    <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"accbBdgtsHdrsRow_WWW123WWW_PrdType\" style=\"width:100% !important;\">";
                                                        $valslctdArry = array("", "", "", "", "", "");
                                                        $srchInsArrys = array(
                                                            "Yearly", "Half Yearly", "Quarterly",
                                                            "Monthly"
                                                        );/*, "Fortnightly", "Weekly"*/
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                        }
                                                        $nwRowHtml33 .= "</select>
                                                                            </td>
                                                                            <td class=\"lovtd\" style=\"text-align: center;\">";
                                                        $isChkd = "checked=\"true\"";
                                                        $nwRowHtml33 .= "<div class=\"form-group form-group-sm \">
                                                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                            <label class=\"form-check-label\">
                                                                                                <input type=\"checkbox\" class=\"form-check-input\" id=\"accbBdgtsHdrsRow_WWW123WWW_IsActive\" name=\"accbBdgtsHdrsRow_WWW123WWW_IsActive\" " . $isChkd . ">
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                            </td>";
                                                        if ($canDel === true) {
                                                            $nwRowHtml33 .= "<td class=\"lovtd\">
                                                                                    <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Budget\" onclick=\"delAccbBdgtHdr('accbBdgtsHdrsRow__WWW123WWW');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\">
                                                                                        <img src=\"cmn_images/no.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">
                                                                                    </button>
                                                                                </td>";
                                                        }
                                                        if ($canVwRcHstry === true) {
                                                            $nwRowHtml33 .= "<td class=\"lovtd\">&nbsp;</td>";
                                                        }
                                                        $nwRowHtml33 .= "</tr>";
                                                        $nwRowHtml33 = urlencode($nwRowHtml33);
                                                        $nwRowHtml1 = $nwRowHtml33;
                                                    ?>
                                                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewAccbBdgtsRows('accbBdgtsHdrsTable', 0, '<?php echo $nwRowHtml1; ?>');">
                                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                New Budget
                                                            </button>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAccbBudgets();" style="width:100% !important;">
                                                                <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Save
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
                                                            <input class="form-control" id="accbBdgtsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                            ?>" onkeyup="enterKeyFuncAccbBdgts(event, '', '#allmodules', 'grp=6&typ=1&pg=6&vtyp=0')">
                                                            <input id="accbBdgtsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbBdgts('clear', '#allmodules', 'grp=6&typ=1&pg=6&vtyp=0');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbBdgts('', '#allmodules', 'grp=6&typ=1&pg=6&vtyp=0');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo $colClassType2; ?>">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtsSrchIn">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "");
                                                                $srchInsArrys = array(
                                                                    "All", "Batch Name", "Batch Description", "Batch Status", "Batch Number",
                                                                    "Batch Date"
                                                                );
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtsDsplySze" style="min-width:70px !important;">
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
                                                                    <a href="javascript:getAccbBdgts('previous', '#allmodules', 'grp=6&typ=1&pg=6&vtyp=0');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:getAccbBdgts('next', '#allmodules', 'grp=6&typ=1&pg=6&vtyp=0');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="accbBdgtsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:25px;width:25px;">No.</th>
                                                                    <th style="max-width:25px;width:25px;">...</th>
                                                                    <th>Budget Name</th>
                                                                    <!--<th>Budget Description</th>-->
                                                                    <th style="max-width:175px;width:175px;">Budget Accounts Type</th>
                                                                    <th style="max-width:165px;width:165px;">Start Date</th>
                                                                    <th style="max-width:165px;width:165px;">End Date</th>
                                                                    <th style="max-width:75px;width:75px;">Period Type</th>
                                                                    <th style="max-width:45px;width:45px;text-align: center;">Is Active?</th>
                                                                    <?php
                                                                    if ($canDel === true) {
                                                                    ?>
                                                                        <th style="max-width:25px;width:25px;">...</th>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                    ?>
                                                                        <th style="max-width:25px;width:25px;">...</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $trsctnLineID = -1;
                                                                $trsctnLineNm = "";
                                                                $trsctnLineID1 = -1;
                                                                $trsctnLineNm1 = "";
                                                                while ($row = loc_db_fetch_array($result)) {
                                                                    $cntr += 1;
                                                                    $trsctnLineID = $row[0];

                                                                    $bdgtLnCurID = get_Bdgt_MinCrncyID($trsctnLineID);
                                                                    if ($bdgtLnCurID <= 0) {
                                                                        $shdRefreshMatView = true;
                                                                    }

                                                                    $trsctnLineNm = $row[1];
                                                                    if ($cntr == 1) {
                                                                        $trsctnLineID1 = $row[0];
                                                                        $trsctnLineNm1 = $row[1];
                                                                    }
                                                                    $trsctnLineDesc = $row[2];
                                                                    $trsctnAcntTypes = $row[10];
                                                                    $trsctnStrtDte = $row[4];
                                                                    $trsctnEndDte = $row[5];
                                                                    $trsctnPrdType = $row[6];
                                                                ?>
                                                                    <tr id="accbBdgtsHdrsRow_<?php echo $cntr; ?>">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Budget" onclick="getAccbBdgtDets('clear', '#accbBdgtsDetList', 'grp=6&typ=1&pg=6&vtyp=1&accbSbmtdBudgetID=<?php echo $trsctnLineID; ?>&accbSbmtdBudgetNm=<?php echo urlencode($trsctnLineNm); ?>', <?php echo $trsctnLineID; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="hidden" id="accbBdgtsHdrsRow<?php echo $cntr; ?>_HdrID" name="accbBdgtsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <input type="text" class="form-control rqrdFld jbDetRfDc" aria-label="..." id="accbBdgtsHdrsRow<?php echo $cntr; ?>_LineNm" name="accbBdgtsHdrsRow<?php echo $cntr; ?>_LineNm" value="<?php echo $trsctnLineNm; ?>" style="width:100% !important;" onkeypress="gnrlFldKeyPress(event, 'accbBdgtsHdrsRow<?php echo $cntr; ?>_LineNm', 'accbBdgtsHdrsTable', 'jbDetRfDc');">
                                                                            <?php
                                                                            } else {
                                                                                echo $trsctnLineNm;
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <!--<td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                                                                                                                                                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="accbBdgtsHdrsRow<?php echo $cntr; ?>_LineDesc" name="accbBdgtsHdrsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" onkeypress="gnrlFldKeyPress(event, 'accbBdgtsHdrsRow<?php echo $cntr; ?>_LineDesc', 'accbBdgtsHdrsTable', 'jbDetDesc');">                                                    
                                                                            <?php
                                                                        } else {
                                                                            echo $trsctnLineDesc;
                                                                        }
                                                                            ?>
                                                                        </td>-->
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtsHdrsRow<?php echo $cntr; ?>_AcntType" style="width:100% !important;">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "");
                                                                                    $srchInsArrys = array(
                                                                                        "INCOME/EXPENDITURE", "EXPENDITURE", "ASSETS/INCOME/EXPENDITURE",
                                                                                        "ALL"
                                                                                    );
                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        if ($trsctnAcntTypes == $srchInsArrys[$z]) {
                                                                                            $valslctdArry[$z] = "selected";
                                                                                        }
                                                                                    ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            <?php
                                                                            } else {
                                                                                echo $trsctnAcntTypes;
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="accbBdgtsHdrsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnStrtDte; ?>" style="width:100%;">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnStrtDte; ?></span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="accbBdgtsHdrsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnEndDte; ?>" style="width:100%;">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnEndDte; ?></span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtsHdrsRow<?php echo $cntr; ?>_PrdType" style="width:100% !important;">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "", "", "");
                                                                                    $srchInsArrys = array(
                                                                                        "Yearly", "Half Yearly", "Quarterly",
                                                                                        "Monthly"
                                                                                    );/*, "Fortnightly", "Weekly"*/
                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        if ($trsctnPrdType == $srchInsArrys[$z]) {
                                                                                            $valslctdArry[$z] = "selected";
                                                                                        }
                                                                                    ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            <?php
                                                                            } else {
                                                                                echo $trsctnPrdType;
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <?php
                                                                            $isChkd = "";
                                                                            if ($row[3] == "1") {
                                                                                $isChkd = "checked=\"true\"";
                                                                            }
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="form-group form-group-sm ">
                                                                                    <div class="form-check" style="font-size: 12px !important;">
                                                                                        <label class="form-check-label">
                                                                                            <input type="checkbox" class="form-check-input" id="accbBdgtsHdrsRow<?php echo $cntr; ?>_IsActive" name="accbBdgtsHdrsRow<?php echo $cntr; ?>_IsActive" <?php echo $isChkd ?>>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span class=""><?php echo ($row[3] == "1" ? "Yes" : "No"); ?></span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <?php
                                                                        if ($canDel === true) {
                                                                        ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Budget" onclick="delAccbBdgtHdr('accbBdgtsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                    <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php
                                                                        if ($canVwRcHstry === true) {
                                                                        ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                        echo urlencode(encrypt1(($row[0] . "|accb.accb_budget_header|budget_id"),
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
                                                        <input type="hidden" id="accbBudgetID" name="accbBudgetID" value="<?php echo $trsctnLineID1; ?>">
                                                        <input type="hidden" id="accbBudgetNm" name="accbBudgetNm" value="<?php echo $trsctnLineNm1; ?>">
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div id="accbBdgtsDetList" class="tab-pane fadein" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                    </div>
                                    <div id="accbBdgtsFurthDetList" class="tab-pane fadein" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                    </div>
                                </div>
                            </div>
                        </div>
                </fieldset>
                <?php
                session_write_close();
                if ($shdRefreshMatView === true) {
                    execSsnUpdtInsSQL("REFRESH MATERIALIZED VIEW CONCURRENTLY accb.accb_budget_detail_mv");
                }
            } else if ($vwtyp == 1) {
                //var_dump($_POST);
                //Auto-Load all Budgetable Accounts
                $reportTitle1 = "Auto-Load all Budgetable Accounts";
                $reportName1 = "Auto-Load all Budgetable Accounts";
                $rptID1 = getRptID($reportName1);
                //Refresh Materialized Views
                $reportTitle = "Refresh Materialized Views";
                $reportName = "Refresh Materialized Views";
                $rptID = getRptID($reportName);

                $accbSbmtdBudgetID = isset($_POST['accbSbmtdBudgetID']) ? $_POST['accbSbmtdBudgetID'] : -1;
                $accbSbmtdBudgetNm = isset($_POST['accbSbmtdBudgetNm']) ? $_POST['accbSbmtdBudgetNm'] : "";
                $pkID = $accbSbmtdBudgetID;
                $shdRefreshMatView = false;
                $bdgtLnCurID = get_Bdgt_MinCrncyID($accbSbmtdBudgetID);
                if ($bdgtLnCurID <= 0) {
                    $shdRefreshMatView = true;
                }

                $qStrtDte = "01-Jan-1900 00:00:00";
                $qEndDte = "31-Dec-4000 23:59:59";
                $qShwNonZeroOnly = true;
                if (isset($_POST['qShwNonZeroOnly'])) {
                    $qShwNonZeroOnly = cleanInputData($_POST['qShwNonZeroOnly']) === "true" ? true : false;
                }
                //var_dump($_POST);
                $total = get_Total_BdgtDt($srchFor, $srchIn, $accbSbmtdBudgetID, $qShwNonZeroOnly, $shdRefreshMatView);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_One_BdgtDt($srchFor, $srchIn, $curIdx, $lmtSze, $accbSbmtdBudgetID, $qShwNonZeroOnly, $shdRefreshMatView);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-5";
                $vwtyp = 1;
                ?>
                <form id='accbBdgtDetsForm' action='' method='post' accept-charset='UTF-8'>
                    <fieldset class="">
                        <legend class="basic_person_lg1" style="color: #003245">BUDGET LINES</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            if ($canAdd === true) {
                                $nwRowHtml33 = "<tr id=\"accbBdgtDetsRow__WWW123WWW\">
                                                <td class=\"lovtd\">";
                                if ($canDel === TRUE) {
                                    $nwRowHtml33 .= "<input type=\"checkbox\" name=\"accbBdgtDetsRow_WWW123WWW_CheckBox\" value=\"-1;\">";
                                }
                                $nwRowHtml33 .= "New</td>
                            <td class=\"lovtd\">
                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_AccountID1\" value=\"-1\" style=\"width:100% !important;\">  
                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\">";
                                $nwRowHtml33 .= "<div class=\"input-group\" style=\"width:100% !important;\">
                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_AccountNm1\" name=\"accbBdgtDetsRow_WWW123WWW_AccountNm1\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbBdgtDetsRow_WWW123WWW_AccountID1', 'accbBdgtDetsRow_WWW123WWW_AccountNm1', 'clear', 1, '', function () {

                                                                                        });\">
                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                        </label>
                                    </div>                                 
                            </td>                                         
                            <td class=\"lovtd\" style=\"max-width:35px;width:35px;\">
                                <div class=\"\" style=\"width:100% !important;\">
                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_TrnsCurNm\" name=\"accbBdgtDetsRow_WWW123WWW_TrnsCurNm\" value=\"" . $fnccurnm . "\" readonly=\"true\" style=\"width:100% !important;\">
                                    <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'accbBdgtDetsRow_WWW123WWW_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                    $('#accbBdgtDetsRow_WWW123WWW_TrnsCurNm1').html($('#accbBdgtDetsRow_WWW123WWW_TrnsCurNm').val());
                                                                                    afterAccbBdgtDetCurSlctn('accbBdgtDetsRow__WWW123WWW');
                                                                                });\">
                                        <span class=\"\" id=\"accbBdgtDetsRow_WWW123WWW_TrnsCurNm1\">" . $fnccurnm . "</span>
                                    </label>
                                </div>                                              
                            </td>
                            <td class=\"lovtd\">
                                <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_EntrdAmt\" name=\"accbBdgtDetsRow_WWW123WWW_EntrdAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtDetsRow_WWW123WWW_EntrdAmt', 'accbBdgtDetsTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\">                                                    
                            </td>
                            <td class=\"lovtd\">
                                <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Account Budget Breakdown\" 
                                        onclick=\"getAccbBdgtDetBrkdwn(-1, 'ShowDialog', 'Budget Amount Breakdown', 'EDIT', 'accbBdgtDetsRow__WWW123WWW', 'accbBdgtDetsRow_WWW123WWW_EntrdAmt', 'accbBdgtDetsRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\"> 
                                    <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                </button>
                            </td>
                            <td class=\"lovtd\">
                                <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_ActlAmt\" name=\"accbBdgtDetsRow_WWW123WWW_ActlAmt\" value=\"0.00\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                            </td>
                            <td class=\"lovtd\">
                                <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_VarncAmt\" name=\"accbBdgtDetsRow_WWW123WWW_VarncAmt\" value=\"0.00\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                            </td>
                            <td class=\"lovtd\">
                                    <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"accbBdgtDetsRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"true\" style=\"width:100%;\">
                                        <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                    </div>                                           
                            </td>
                            <td class=\"lovtd\">
                                    <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"accbBdgtDetsRow_WWW123WWW_EndDte\" value=\"\" readonly=\"true\" style=\"width:100%;\">
                                        <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                    </div>                                         
                            </td>  
                            <td class=\"lovtd\">
                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"accbBdgtDetsRow_WWW123WWW_Action\" style=\"width:100% !important;\">";
                                $valslctdArry = array("", "", "", "");
                                $srchInsArrys = array(
                                    "Do Nothing",
                                    "Warn",
                                    "Disallow",
                                    "Congratulate"
                                );
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                }
                                $nwRowHtml33 .= "</select>
                            </td>
                            <td class=\"lovtd\" style=\"\">
                                <input type=\"text\" class=\"form-control rqrdFld jbDetFuncRate\" aria-label=\"...\" id=\"accbBdgtDetsRow_WWW123WWW_FuncExchgRate\" name=\"accbBdgtDetsRow_WWW123WWW_FuncExchgRate\" value=\"1.0000\" onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtDetsRow_WWW123WWW_FuncExchgRate', 'accbBdgtDetsTable', 'jbDetFuncRate');\" style=\"width:100% !important;text-align: right;\">                                                    
                            </td>";
                                if ($canVwRcHstry === true) {
                                    $nwRowHtml33 .= "<td class=\"lovtd\">&nbsp;</td>";
                                }
                                $nwRowHtml33 .= "</tr>";
                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                $nwRowHtml1 = $nwRowHtml33;
                            ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="insertNewAccbBdgtDetsRows('accbBdgtDetsTable', 0, '<?php echo $nwRowHtml1; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="New Budget Line">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        ADD
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveAccbBudgetLines();" style="width:100% !important;" data-toggle="tooltip" title="Save Budget Lines">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%;  height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtAccntBdgts();" data-toggle="tooltip" title="Export Budget Lines">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Export
                                    </button>
                                    <?php if ($canDel) { ?>
                                        <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 0px;" onclick="delSlctdAcbBdgtLines();" data-toggle="tooltip" title="Delete Selected Budget Lines">
                                            <img src="cmn_images/delete.png" style="left: 0.5%;  height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                    <?php } ?>
                                </div>
                            <?php
                            } else {
                                $colClassType1 = "col-lg-2";
                                $colClassType2 = "col-lg-5";
                            }
                            ?>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <input class="form-control" id="accbBdgtDetsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncAccbBdgtDets(event, '', '#accbBdgtsDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="accbBdgtDetsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbBdgtDets('clear', '#accbBdgtsDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbBdgtDets('', '#accbBdgtsDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtDetsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "");
                                        $srchInsArrys = array(
                                            "Account Number",
                                            "Account Name",
                                            "Period Start Date",
                                            "Period End Date"
                                        );

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtDetsDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 5000, 10000, 1000000);
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
                                            <a class="rhopagination" href="javascript:getAccbBdgtDets('previous', '#accbBdgtsDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAccbBdgtDets('next', '#accbBdgtsDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <div class="form-check" style="font-size: 12px !important;margin-top:6px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $shwNonZeroOnlyChkd = "";
                                        if ($qShwNonZeroOnly === true) {
                                            $shwNonZeroOnlyChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getAccbBdgtDets('', '#accbBdgtsDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbBdgtDetsNonZeroOnly" name="accbBdgtDetsNonZeroOnly" <?php echo $shwNonZeroOnlyChkd; ?> data-toggle="tooltip" title="Show Only Lines with Budgeted Amounts">
                                        Non-Zero Budgets
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 3px 0px;">
                        </div>
                        <div class="row " style="margin-bottom:2px;">
                            <div class="col-md-12">
                                <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group" style="width:100% !important;">
                                        <input type="text" class="form-control" aria-label="..." id="accbSbmtdBudgetNm" name="accbSbmtdBudgetNm" value="<?php echo $accbSbmtdBudgetNm; ?>" readonly="true" style="width:100% !important;">
                                        <input type="hidden" class="form-control" aria-label="..." id="accbSbmtdBudgetID" value="<?php echo $accbSbmtdBudgetID; ?>" style="width:100% !important;">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Budgets', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbSbmtdBudgetID', 'accbSbmtdBudgetNm', 'clear', 1, '', function () {
                                                    getAccbBdgtDets('clear', '#accbBdgtsDetList', 'grp=6&typ=1&pg=6&vtyp=1');
                                                });">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID1; ?>');" data-toggle="tooltip" title="Auto-Load all Budgetable Accounts">
                                        <img src="cmn_images/tick_64.png" style="left: 0.5%;  height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID; ?>');" data-toggle="tooltip" title="Refresh Materialized Views">
                                        <img src="cmn_images/refresh.bmp" style="left: 0.5%;  height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="importAccntBdgts();" data-toggle="tooltip" title="Import Budget Lines">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import
                                    </button>
                                </div>
                                <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>">
                                            <span style="font-weight:bold;<?php echo $forecolors; ?>">Income:</span>
                                        </label>
                                        <?php
                                        $aedffrc = get_Bdgt_IncmSum($accbSbmtdBudgetID);
                                        $style1 = "color:green;";
                                        ?>
                                        <input class="form-control" id="allBdgtsIncmSumAmt" type="text" placeholder="0.00" value="<?php
                                                                                                                                    echo number_format($aedffrc, 2);
                                                                                                                                    ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>">
                                            <span style="font-weight:bold;<?php echo $forecolors; ?>">Expenditure:</span>
                                        </label>
                                        <?php
                                        $lerdffrc = get_Bdgt_ExpnsSum($accbSbmtdBudgetID);
                                        $style1 = "color:green;";
                                        ?>
                                        <input class="form-control" id="allBdgtsExpnsSumAmt" type="text" placeholder="0.00" value="<?php
                                                                                                                                    echo number_format($lerdffrc, 2);
                                                                                                                                    ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>">
                                            <span style="font-weight:bold;<?php echo $forecolors; ?>">Net:</span>
                                        </label>
                                        <?php
                                        $dffrc = $aedffrc - $lerdffrc;
                                        $style1 = "color:green;";
                                        if ($dffrc <= 0) {
                                            $style1 = "color:red;";
                                        }
                                        ?>
                                        <input class="form-control" id="allBdgtsDffrncSumAmt" type="text" placeholder="0.00" value="<?php
                                                                                                                                    echo number_format($dffrc, 2);
                                                                                                                                    ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 3px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="padding:0px 15px 0px 15px !important;">
                                <table class="table table-striped table-bordered table-responsive" id="accbBdgtDetsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th style="min-width:150px;">Budget Account</th>
                                            <th style="max-width:55px;width:55px;text-align: right;">CUR.</th>
                                            <th style="max-width:120px;width:120px;text-align: right;">Budget Amount</th>
                                            <th>...</th>
                                            <th style="max-width:120px;width:120px;text-align: right;">Actual Amount</th>
                                            <th style="max-width:105px;width:105px;text-align: right;">Variance</th>
                                            <th style="max-width:125px;width:125px;">Start Date</th>
                                            <th style="max-width:125px;width:125px;">End Date</th>
                                            <th style="max-width:100px;width:100px;">Action if Limit Exceeded</th>
                                            <th style="max-width:75px;width:75px;">Func. Curr. Rate</th>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                            ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $trnsBrkDwnVType = "EDIT";
                                        while ($row = loc_db_fetch_array($result)) {
                                            $bdgtLnDetID = (float) $row[0];
                                            $bdgtLnAccntID = (int) $row[1];
                                            $bdgtLnAccntNm = $row[2] . "." . $row[3];
                                            $bdgtLnCurID = (int) $row[9];
                                            $bdgtLnCurNm = $row[10];
                                            $bdgtLnBdgtAmount = (float) $row[11];
                                            $bdgtLnActlAmount = (float) $row[5];
                                            $bdgtLnVariance = $bdgtLnBdgtAmount - $bdgtLnActlAmount;
                                            $bdgtLnStrtDte = $row[6];
                                            $bdgtLnEndDte = $row[7];
                                            $bdgtLnAction = $row[8];
                                            $bdgtLnRate = (float) $row[12];

                                            $cntr += 1;
                                        ?>
                                            <tr id="accbBdgtDetsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canDel === TRUE) {
                                                    ?>
                                                        <input type="checkbox" name="accbBdgtDetsRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $bdgtLnDetID . ";" . $bdgtLnAccntNm; ?>">
                                                    <?php } ?>
                                                    <?php
                                                    echo ($curIdx * $lmtSze) + ($cntr);
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $bdgtLnAccntID; ?>" style="width:100% !important;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $bdgtLnDetID; ?>" style="width:100% !important;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;">
                                                    <?php
                                                    if ($canEdt === true) {
                                                    ?>
                                                        <div class="input-group" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_AccountNm1" name="accbBdgtDetsRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $bdgtLnAccntNm; ?>" readonly="true" style="width:100% !important;">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbBdgtDetsRow<?php echo $cntr; ?>_AccountID1', 'accbBdgtDetsRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {

                                                                    });">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $bdgtLnAccntNm; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd" style="max-width:35px;width:35px;">
                                                    <div class="" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_TrnsCurNm" name="accbBdgtDetsRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $bdgtLnCurNm; ?>" readonly="true" style="width:100% !important;">
                                                        <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'accbBdgtDetsRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                    $('#accbBdgtDetsRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#accbBdgtDetsRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                    afterAccbBdgtDetCurSlctn('accbBdgtDetsRow_<?php echo $cntr; ?>');
                                                                });">
                                                            <span class="" id="accbBdgtDetsRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $bdgtLnCurNm; ?></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="lovtd">
                                                    <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_EntrdAmt" name="accbBdgtDetsRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                                                                                                                                                                                                                            echo number_format($bdgtLnBdgtAmount, 2);
                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDetsRow<?php echo $cntr; ?>_EntrdAmt', 'accbBdgtDetsTable', 'jbDetDbt');" style="width:100% !important;text-align: right;">
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Account Budget Breakdown" onclick="getAccbBdgtDetBrkdwn(<?php echo $bdgtLnDetID; ?>, 'ShowDialog', 'Budget Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', 'accbBdgtDetsRow_<?php echo $cntr; ?>', 'accbBdgtDetsRow<?php echo $cntr; ?>_EntrdAmt', 'accbBdgtDetsRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <td class="lovtd">
                                                    <input type="text" class="form-control jbDetDbt" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_ActlAmt" name="accbBdgtDetsRow<?php echo $cntr; ?>_ActlAmt" value="<?php
                                                                                                                                                                                                                                    echo number_format($bdgtLnActlAmount, 2);
                                                                                                                                                                                                                                    ?>" style="width:100% !important;text-align: right;" readonly="true">
                                                </td>
                                                <td class="lovtd">
                                                    <input type="text" class="form-control jbDetDbt" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_VarncAmt" name="accbBdgtDetsRow<?php echo $cntr; ?>_VarncAmt" value="<?php
                                                                                                                                                                                                                                    echo number_format($bdgtLnVariance, 2);
                                                                                                                                                                                                                                    ?>" style="width:100% !important;text-align: right;" readonly="true">
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdt === true) {
                                                    ?>
                                                        <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                            <input class="form-control" size="16" type="text" id="accbBdgtDetsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $bdgtLnStrtDte; ?>" readonly="true" style="width:100%;">
                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $bdgtLnStrtDte; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdt === true) {
                                                    ?>
                                                        <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                            <input class="form-control" size="16" type="text" id="accbBdgtDetsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $bdgtLnEndDte; ?>" readonly="true" style="width:100%;">
                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $bdgtLnEndDte; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtDetsRow<?php echo $cntr; ?>_Action" style="width:100% !important;">
                                                        <?php
                                                        $valslctdArry = array("", "", "", "");
                                                        $srchInsArrys = array(
                                                            "Do Nothing",
                                                            "Warn",
                                                            "Disallow",
                                                            "Congratulate"
                                                        );
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($bdgtLnAction == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $srchInsArrys[$z];
                                                                            ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="lovtd" style="">
                                                    <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="accbBdgtDetsRow<?php echo $cntr; ?>_FuncExchgRate" name="accbBdgtDetsRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                                                                                                                                                                                                                                            echo number_format($bdgtLnRate, 4);
                                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDetsRow<?php echo $cntr; ?>_FuncExchgRate', 'accbBdgtDetsTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;">
                                                </td>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                echo urlencode(encrypt1(($bdgtLnDetID . "|accb.accb_budget_details|budget_det_id"),
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
            } else if ($vwtyp == 2) {
                //Budget Amount Breakdown Dialog "EDIT"; // 
                $error = "";
                $sbmtdBdgtDetID = isset($_POST['sbmtdBdgtDetID']) ? cleanInputData($_POST['sbmtdBdgtDetID']) : -1;
                $sbmtdAccntID = isset($_POST['sbmtdAccntID']) ? cleanInputData($_POST['sbmtdAccntID']) : -1;
                $sbmtdAccntName = isset($_POST['sbmtdAccntName']) ? cleanInputData($_POST['sbmtdAccntName']) : "";
                $qShwAllPrds = false;
                if (isset($_POST['qShwAllPrds'])) {
                    $qShwAllPrds = cleanInputData($_POST['qShwAllPrds']) === "true" ? true : false;
                }
                $vtypActn = isset($_POST['vtypActn']) ? cleanInputData($_POST['vtypActn']) : 'VIEW';
                $trnsAmntElmntID = isset($_POST['trnsAmntElmntID']) ? cleanInputData($_POST['trnsAmntElmntID']) : '';
                $trnsAmtBrkdwnSaveElID = isset($_POST['trnsAmtBrkdwnSaveElID']) ? cleanInputData($_POST['trnsAmtBrkdwnSaveElID']) : '';
                $slctdBrkdwnLines = isset($_POST['slctdBrkdwnLines']) ? cleanInputData($_POST['slctdBrkdwnLines']) : '';
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-4";
                $mkReadOnly = "";
                if ($vtypActn == "VIEW") {
                    $mkReadOnly = "readonly=\"true\"";
                }
            ?>
                <form id='accbBdgtDtBrkdwnDiagForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                    <fieldset class="">
                        <div class="row">
                            <?php
                            if ($canAdd === true) {
                                $nwRowHtml33 = "<tr id=\"accbBdgtDtBrkdwnDiagHdrsRow__WWW123WWW\">                                    
                                                        <td class=\"lovtd\">New</td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnLnID\" value=\"-1\" style=\"width:100% !important;\">   
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_DetID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_ItemID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_ItemName\" name=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_ItemName\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_ItemID', 'accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_ItemName', 'clear', 1, '', function () {

                                                                                                            });\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>                                            
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_DetType\" style=\"width:100% !important;\">";
                                $valslctdArry = array("", "", "");
                                $srchInsArrys = array("Item Quantity", "Travel Allowance", "Salaries & Wages");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                }
                                $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input class=\"form-control rqrdFld acbBrkdwnQTY1\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnQTY1\" type = \"text\" placeholder=\"0\" value=\"0\" style=\"text-align:right;font-size:16px;font-weight:bold;width:100%;\" onchange=\"calcAllAccbBdgtDetBrkdwn();\" onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnQTY1', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnQTY1');\">
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input class=\"form-control rqrdFld acbBrkdwnQTY2\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnQTY2\" type = \"text\" placeholder=\"0\" value=\"1\" style=\"text-align:right;font-size:16px;font-weight:bold;width:100%;\" onchange=\"calcAllAccbBdgtDetBrkdwn();\" onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnQTY2', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnQTY2');\">
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input class=\"form-control rqrdFld acbBrkdwnUVl\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnUnitVal\" type = \"text\" placeholder=\"0\" value=\"0.00\" style=\"text-align:right;font-size:16px;font-weight:bold;width:100%;\" onchange=\"calcAllAccbBdgtDetBrkdwn();\" onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnUnitVal', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnUVl');\">
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input class=\"form-control acbBrkdwnTtl\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnTtl\" type = \"text\" placeholder=\"0\" value=\"0.00\" readonly=\"true\" style=\"text-align:right;font-size:16px;font-weight:bold;color:blue;width:100%;\" onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnTtl', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnTtl');\">
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <textarea class=\"form-control rqrdFld acbBrkdwnDesc\" rows=\"1\" cols=\"20\" id=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnDesc\" name=\"accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnDesc\" style=\"text-align:left !important;width:100%;\"  onkeypress=\"gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow_WWW123WWW_BrkdwnDesc', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnDesc');\"></textarea>  
                                                        </td>";
                                if ($canEdt === true) {
                                    $nwRowHtml33 .= "<td class=\"lovtd\">
                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbBdgtDetBrkdwn('accbBdgtDtBrkdwnDiagHdrsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Amount Breakdown\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                </button>
                                                            </td>";
                                }
                                if ($canVwRcHstry === true) {
                                    $nwRowHtml33 .= "<td class=\"lovtd\">&nbsp;</td>";
                                }
                                $nwRowHtml33 .= "</tr>";
                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                $nwRowHtml1 = $nwRowHtml33;
                            ?>
                                <div class="col-md-3" style="padding:0px 1px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewAccbBdgtBrkdwnRows('accbBdgtDtBrkdwnDiagHdrsTable', 0, '<?php echo $nwRowHtml1; ?>');">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Budget Item/Line
                                    </button>
                                </div>
                            <?php }
                            ?>
                            <div class="col-md-5">
                                <div class="form-group form-group-sm" style="margin-top:5px;">
                                    <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                        <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">Account:&nbsp;</label>
                                    </div>
                                    <div class="col-md-9" style="padding:0px 1px 0px 1px !important;">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagAcntNm" name="accbBdgtDtBrkdwnDiagAcntNm" value="<?php echo $sbmtdAccntName; ?>" readonly="true">
                                            <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagAcntID" name="accbBdgtDtBrkdwnDiagAcntID" value="<?php echo $sbmtdAccntID; ?>" readonly="true">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="accbBdgtDtBrkdwnDiagHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:25px;width:25px;">No.</th>
                                            <th style="max-width:145px;width:145px;">Budget Line Item</th>
                                            <th style="max-width:105px;width:105px;">Budget Detail Type</th>
                                            <th style="text-align:right;max-width:85px;width:85px;">M1 (QTY or No. of Persons)</th>
                                            <th style="text-align:right;max-width:85px;width:85px;">M2 (No. of Days or 1.00 if N/A)</th>
                                            <th style="text-align:right;max-width:105px;width:105px;">Unit Amount</th>
                                            <th style="text-align:right;max-width:105px;width:105px;">Total Amount</th>
                                            <th>Remarks or Justification</th>
                                            <?php if ($canEdt === true) { ?>
                                                <th style="max-width:25px;width:25px;">...</th>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th style="max-width:25px;width:25px;">...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ttlTrsctnAmnt = 0;
                                        if (trim($slctdBrkdwnLines, "|~") != "") {
                                            $variousRows = explode("|", $slctdBrkdwnLines);
                                            for ($y = 0; $y < count($variousRows); $y++) {
                                                $crntRow = explode("~", $variousRows[$y]);
                                                if (count($crntRow) == 9) {
                                                    $cntr += 1;
                                                    $rcHstryTblNm = "accb.accb_bdgt_amnt_brkdwn";
                                                    $rcHstryPKeyColNm = "bdgt_amnt_brkdwn_id";
                                                    $bdgtBrkdwnLineID = (float) (cleanInputData1($crntRow[0]));
                                                    $rcHstryPKeyColVal = $bdgtBrkdwnLineID;
                                                    $bdgtBrkdwnDetID = (float) (cleanInputData1($crntRow[1]));
                                                    $bdgtBrkdwnAccntID = $sbmtdAccntID;
                                                    $bdgtBrkdwnAccntNm = $sbmtdAccntName;
                                                    $bdgtBrkdwnItemID = (float) (cleanInputData1($crntRow[2]));
                                                    $bdgtBrkdwnItemNm = cleanInputData1($crntRow[3]);
                                                    $bdgtBrkdwnDetType = cleanInputData1($crntRow[4]);
                                                    $bdgtBrkdwnQty1 = (float) cleanInputData1($crntRow[5]);
                                                    $bdgtBrkdwnQty2 = (float) cleanInputData1($crntRow[6]);
                                                    $bdgtBrkdwnUntPrc = (float) cleanInputData1($crntRow[7]);
                                                    $bdgtBrkdwnTtlAmnt = $bdgtBrkdwnQty1 * $bdgtBrkdwnQty2 * $bdgtBrkdwnUntPrc;
                                                    $bdgtBrkdwnDesc = cleanInputData1($crntRow[8]);
                                                    $ttlTrsctnAmnt = $ttlTrsctnAmnt + $bdgtBrkdwnTtlAmnt;
                                        ?>
                                                    <tr id="accbBdgtDtBrkdwnDiagHdrsRow_<?php echo $cntr; ?>">
                                                        <td class="lovtd"><?php echo ($cntr); ?></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnLnID" value="<?php echo $bdgtBrkdwnLineID; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_DetID" value="<?php echo $bdgtBrkdwnDetID; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $bdgtBrkdwnItemID; ?>" style="width:100% !important;">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemName" name="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemName" value="<?php echo $bdgtBrkdwnItemNm; ?>" readonly="true" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemID', 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemName', 'clear', 1, '', function () {

                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $bdgtBrkdwnItemNm; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_DetType" style="width:100% !important;">
                                                                <?php
                                                                $valslctdArry = array("", "", "");
                                                                $srchInsArrys = array("Item Quantity", "Travel Allowance", "Salaries & Wages");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($bdgtBrkdwnDetType == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td class="lovtd">
                                                            <input class="form-control rqrdFld acbBrkdwnQTY1" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY1" type="text" placeholder="0" value="<?php
                                                                                                                                                                                                                    echo number_format($bdgtBrkdwnQty1, 0);
                                                                                                                                                                                                                    ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbBdgtDetBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY1', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnQTY1');">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input class="form-control rqrdFld acbBrkdwnQTY2" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY2" type="text" placeholder="0" value="<?php
                                                                                                                                                                                                                    echo number_format($bdgtBrkdwnQty2, 0);
                                                                                                                                                                                                                    ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbBdgtDetBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY2', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnQTY2');">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input class="form-control rqrdFld acbBrkdwnUVl" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnUnitVal" type="text" placeholder="0" value="<?php
                                                                                                                                                                                                                    echo number_format($bdgtBrkdwnUntPrc, 2);
                                                                                                                                                                                                                    ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbBdgtDetBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnUnitVal', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnUVl');">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input class="form-control acbBrkdwnTtl" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnTtl" type="text" placeholder="0" value="<?php
                                                                                                                                                                                                        echo number_format($bdgtBrkdwnTtlAmnt, 0);
                                                                                                                                                                                                        ?>" readonly="true" style="text-align:right;font-size:16px;font-weight:bold;color:blue;width:100%;" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnTtl', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnTtl');">
                                                        </td>
                                                        <td class="lovtd">
                                                            <textarea class="form-control rqrdFld acbBrkdwnDesc" rows="1" cols="20" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" name="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;width:100%;" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnDesc');"><?php echo $bdgtBrkdwnDesc; ?></textarea>
                                                        </td>
                                                        <?php if ($canEdt === true) { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbBdgtDetBrkdwn('accbBdgtDtBrkdwnDiagHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Amount Breakdown">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } ?>
                                                        <?php if ($canVwRcHstry === true) { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                        echo urlencode(encrypt1(($rcHstryPKeyColVal . "|" . $rcHstryTblNm . "|" . $rcHstryPKeyColNm),
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
                                            }
                                        } else {
                                            $chrt_SQL = "";
                                            $result = get_Bdgt_AmntBrkdwn($sbmtdAccntID, $sbmtdBdgtDetID, $chrt_SQL);
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                $rcHstryTblNm = "accb.accb_bdgt_amnt_brkdwn";
                                                $rcHstryPKeyColNm = "bdgt_amnt_brkdwn_id";
                                                $rcHstryPKeyColVal = $row[0];
                                                $bdgtBrkdwnLineID = (float) $row[0];
                                                $bdgtBrkdwnDetID = (float) $row[1];
                                                $bdgtBrkdwnAccntID = (int) $row[2];
                                                $bdgtBrkdwnAccntNm = $row[3];
                                                $bdgtBrkdwnItemID = (int) $row[4];
                                                $bdgtBrkdwnItemNm = $row[5];
                                                $bdgtBrkdwnDetType = $row[6];
                                                $bdgtBrkdwnQty1 = (float) $row[7];
                                                $bdgtBrkdwnQty2 = (float) $row[8];
                                                $bdgtBrkdwnUntPrc = (float) $row[9];
                                                $bdgtBrkdwnTtlAmnt = (float) $row[10];
                                                $bdgtBrkdwnDesc = $row[11];
                                                $ttlTrsctnAmnt = $ttlTrsctnAmnt + $bdgtBrkdwnTtlAmnt;
                                                ?>
                                                <tr id="accbBdgtDtBrkdwnDiagHdrsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><?php echo ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnLnID" value="<?php echo $bdgtBrkdwnLineID; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_DetID" value="<?php echo $bdgtBrkdwnDetID; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $bdgtBrkdwnItemID; ?>" style="width:100% !important;">
                                                        <?php
                                                        if ($canEdt === true) {
                                                        ?>
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemName" name="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemName" value="<?php echo $bdgtBrkdwnItemNm; ?>" readonly="true" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemID', 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_ItemName', 'clear', 1, '', function () {

                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $bdgtBrkdwnItemNm; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_DetType" style="width:100% !important;">
                                                            <?php
                                                            $valslctdArry = array("", "", "");
                                                            $srchInsArrys = array("Item Quantity", "Travel Allowance", "Salaries & Wages");
                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                if ($bdgtBrkdwnDetType == $srchInsArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td class="lovtd">
                                                        <input class="form-control rqrdFld acbBrkdwnQTY1" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY1" type="text" placeholder="0" value="<?php
                                                                                                                                                                                                                echo number_format($bdgtBrkdwnQty1, 0);
                                                                                                                                                                                                                ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbBdgtDetBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY1', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnQTY1');">
                                                    </td>
                                                    <td class="lovtd">
                                                        <input class="form-control rqrdFld acbBrkdwnQTY2" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY2" type="text" placeholder="0" value="<?php
                                                                                                                                                                                                                echo number_format($bdgtBrkdwnQty2, 0);
                                                                                                                                                                                                                ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbBdgtDetBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY2', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnQTY2');">
                                                    </td>
                                                    <td class="lovtd">
                                                        <input class="form-control rqrdFld acbBrkdwnUVl" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnUnitVal" type="text" placeholder="0" value="<?php
                                                                                                                                                                                                                echo number_format($bdgtBrkdwnUntPrc, 2);
                                                                                                                                                                                                                ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbBdgtDetBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnUnitVal', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnUVl');">
                                                    </td>
                                                    <td class="lovtd">
                                                        <input class="form-control acbBrkdwnTtl" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnTtl" type="text" placeholder="0" value="<?php
                                                                                                                                                                                                    echo number_format($bdgtBrkdwnTtlAmnt, 0);
                                                                                                                                                                                                    ?>" readonly="true" style="text-align:right;font-size:16px;font-weight:bold;color:blue;width:100%;" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnTtl', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnTtl');">
                                                    </td>
                                                    <td class="lovtd">
                                                        <textarea class="form-control rqrdFld acbBrkdwnDesc" rows="1" cols="20" id="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" name="accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;width:100%;" onkeypress="gnrlFldKeyPress(event, 'accbBdgtDtBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc', 'accbBdgtDtBrkdwnDiagHdrsTable', 'acbBrkdwnDesc');"><?php echo $bdgtBrkdwnDesc; ?></textarea>
                                                    </td>
                                                    <?php if ($canEdt === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbBdgtDetBrkdwn('accbBdgtDtBrkdwnDiagHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Amount Breakdown">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($rcHstryPKeyColVal . "|" . $rcHstryTblNm . "|" . $rcHstryPKeyColNm),
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
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myBdgtAmtBrkdwnTtlBtn\">" . number_format(
                                                    $ttlTrsctnAmnt,
                                                    2,
                                                    '.',
                                                    ','
                                                ) . "</span>";
                                                ?>
                                                <input type="hidden" id="myBdgtAmtBrkdwnTtlVal" value="<?php echo $ttlTrsctnAmnt; ?>">
                                            </th>
                                            <th>&nbsp;</th>
                                            <?php if ($canEdt === true) { ?>
                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="float:right;">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <?php if ($vtypActn == "EDIT") { ?>
                                        <button type="button" class="btn btn-primary" onclick="applyNewAccbBdgtDetBrkdwn('myFormsModalLx', '<?php echo $trnsAmntElmntID; ?>', '<?php echo $trnsAmtBrkdwnSaveElID; ?>');">Apply Total</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            <?php
            } else if ($vwtyp == 3) {
                //var_dump($_POST);
                $accbSbmtdBudgetID = isset($_POST['accbSbmtdBudgetID']) ? $_POST['accbSbmtdBudgetID'] : -1;
                $accbSbmtdBudgetNm = isset($_POST['accbSbmtdBudgetNm']) ? $_POST['accbSbmtdBudgetNm'] : "";
                $pkID = $accbSbmtdBudgetID;
                $qStrtDte = "01-Jan-1900 00:00:00";
                $qEndDte = "31-Dec-4000 23:59:59";
                $qShwNonZeroOnly = true;
                if (isset($_POST['qShwNonZeroOnly'])) {
                    $qShwNonZeroOnly = cleanInputData($_POST['qShwNonZeroOnly']) === "true" ? true : false;
                }
                $total = get_Ttl_Bdgt_DetBrkdwns($srchFor, $srchIn, $accbSbmtdBudgetID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Bdgt_DetBrkdwns($srchFor, $srchIn, $curIdx, $lmtSze, $accbSbmtdBudgetID);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-5";
                $vwtyp = 3;
            ?>
                <form id='accbBdgtFurthDetsForm' action='' method='post' accept-charset='UTF-8'>
                    <fieldset class="">
                        <legend class="basic_person_lg1" style="color: #003245">BUDGET ITEMS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group" style="width:100% !important;">
                                        <input type="text" class="form-control" aria-label="..." id="accbSbmtdBudgetNm1" name="accbSbmtdBudgetNm1" value="<?php echo $accbSbmtdBudgetNm; ?>" readonly="true" style="width:100% !important;">
                                        <input type="hidden" class="form-control" aria-label="..." id="accbSbmtdBudgetID1" value="<?php echo $accbSbmtdBudgetID; ?>" style="width:100% !important;">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Budgets', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbSbmtdBudgetID1', 'accbSbmtdBudgetNm1', 'clear', 1, '', function () {
                                                    getAccbBdgtFurthDets('clear', '#accbBdgtsFurthDetList', 'grp=6&typ=1&pg=6&vtyp=3');
                                                });">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Export Budget Lines">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Export
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import Budget Lines">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <input class="form-control" id="accbBdgtFurthDetsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncAccbBdgtFurthDets(event, '', '#accbBdgtsFurthDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="accbBdgtFurthDetsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbBdgtFurthDets('clear', '#accbBdgtsFurthDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbBdgtFurthDets('', '#accbBdgtsFurthDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtFurthDetsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "");
                                        $srchInsArrys = array(
                                            "Account Number",
                                            "Account Name",
                                            "Budget Item",
                                            "Remark"
                                        );

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbBdgtFurthDetsDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 5000, 10000, 1000000);
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
                                            <a class="rhopagination" href="javascript:getAccbBdgtFurthDets('previous', '#accbBdgtsFurthDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAccbBdgtFurthDets('next', '#accbBdgtsFurthDetList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 3px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="padding:0px 15px 0px 15px !important;">
                                <table class="table table-striped table-bordered table-responsive" id="accbBdgtFurthDetsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th style="min-width:200px;">Budget Account / Item / Detail Type</th>
                                            <th style="text-align:right;max-width:85px;width:85px;">M1 (QTY or No. of Prs.)</th>
                                            <th style="text-align:right;max-width:85px;width:85px;">M2 (No. of Days or 1.00)</th>
                                            <th style="max-width:55px;width:55px;text-align: right;">CUR.</th>
                                            <th style="text-align:right;max-width:105px;width:105px;">Unit Amount</th>
                                            <th style="text-align:right;max-width:105px;width:105px;">Total Amount</th>
                                            <th>Remarks or Justification</th>
                                            <th style="max-width:145px;width:145px;">Budget Period</th>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                            ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $trnsBrkDwnVType = "EDIT";
                                        $ttlTrsctnAmnt = 0;
                                        $mkReadOnly = "readonly=\"true\"";
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;
                                            $rcHstryTblNm = "accb.accb_bdgt_amnt_brkdwn";
                                            $rcHstryPKeyColNm = "bdgt_amnt_brkdwn_id";
                                            $bdgtLnAccntID = (int) $row[2];
                                            $bdgtLnAccntNm = $row[3];
                                            $bdgtLnCurID = (int) $row[17];
                                            $bdgtLnCurNm = $row[18];
                                            $bdgtLnStrtDte = $row[12];
                                            $bdgtLnEndDte = $row[13];
                                            $rcHstryPKeyColVal = $row[0];
                                            $bdgtBrkdwnLineID = (float) $row[0];
                                            $bdgtLnDetID = (float) $row[1];
                                            $bdgtBrkdwnAccntID = (int) $row[2];
                                            $bdgtBrkdwnAccntNm = $row[3];
                                            $bdgtBrkdwnItemID = (int) $row[4];
                                            $bdgtBrkdwnItemNm = $row[5];
                                            $bdgtBrkdwnDetType = $row[6];
                                            $bdgtBrkdwnQty1 = (float) $row[7];
                                            $bdgtBrkdwnQty2 = (float) $row[8];
                                            $bdgtBrkdwnUntPrc = (float) $row[9];
                                            $bdgtBrkdwnTtlAmnt = (float) $row[10];
                                            $bdgtBrkdwnDesc = $row[11];
                                            $ttlTrsctnAmnt = $ttlTrsctnAmnt + $bdgtBrkdwnTtlAmnt;
                                        ?>
                                            <tr id="accbBdgtFurthDetsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd">
                                                    <?php
                                                    echo ($curIdx * $lmtSze) + ($cntr);
                                                    ?></td>
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbBdgtFurthDetsRow<?php echo $cntr; ?>_BrkdwnLnID" value="<?php echo $bdgtBrkdwnLineID; ?>" style="width:100% !important;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbBdgtFurthDetsRow<?php echo $cntr; ?>_DetID" value="<?php echo $bdgtLnDetID; ?>" style="width:100% !important;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbBdgtFurthDetsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $bdgtBrkdwnItemID; ?>" style="width:100% !important;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbBdgtFurthDetsRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $bdgtLnAccntID; ?>" style="width:100% !important;">
                                                    <span><strong>Account:</strong> <?php echo $bdgtLnAccntNm; ?> <strong>Item:</strong> <?php echo $bdgtBrkdwnItemNm; ?> [<?php echo $bdgtBrkdwnDetType; ?>]</span>
                                                </td>
                                                <td class="lovtd" style="text-align:right;">
                                                    <span><?php echo number_format($bdgtBrkdwnQty1, 2); ?></span>
                                                </td>
                                                <td class="lovtd" style="text-align:right;">
                                                    <span><?php echo number_format($bdgtBrkdwnQty2, 2); ?></span>
                                                </td>
                                                <td class="lovtd" style="max-width:35px;width:35px;">
                                                    <div class="" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbBdgtFurthDetsRow<?php echo $cntr; ?>_TrnsCurNm" name="accbBdgtFurthDetsRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $bdgtLnCurNm; ?>" readonly="true" style="width:100% !important;">
                                                        <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'accbBdgtFurthDetsRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                    $('#accbBdgtFurthDetsRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#accbBdgtFurthDetsRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                    afterAccbBdgtFurthDetCurSlctn('accbBdgtFurthDetsRow_<?php echo $cntr; ?>');
                                                                });">
                                                            <span class="" id="accbBdgtFurthDetsRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $bdgtLnCurNm; ?></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="lovtd" style="text-align:right;">
                                                    <span><?php echo number_format($bdgtBrkdwnUntPrc, 2); ?></span>
                                                </td>
                                                <td class="lovtd" style="text-align:right;">
                                                    <span><?php echo number_format($bdgtBrkdwnTtlAmnt, 2); ?></span>
                                                </td>
                                                <td class="lovtd">
                                                    <span><?php echo $bdgtBrkdwnDesc; ?></span>
                                                </td>
                                                <td class="lovtd">
                                                    <span><?php echo $bdgtLnStrtDte; ?><br /><?php echo $bdgtLnEndDte; ?></span>
                                                </td>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                echo urlencode(encrypt1(($bdgtBrkdwnLineID . "|accb.accb_bdgt_amnt_brkdwn|bdgt_amnt_brkdwn_id"),
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
?>