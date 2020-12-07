<?php
$accbPyblsPrmSnsRstl = getAccbPyblsPrmssns($orgID);
$addRecsSSP = ($accbPyblsPrmSnsRstl[0] >= 1) ? true : false;
$editRecsSSP = ($accbPyblsPrmSnsRstl[1] >= 1) ? true : false;
$delRecsSSP = ($accbPyblsPrmSnsRstl[2] >= 1) ? true : false;
$addRecsSAP = ($accbPyblsPrmSnsRstl[3] >= 1) ? true : false;
$editRecsSAP = ($accbPyblsPrmSnsRstl[4] >= 1) ? true : false;
$delRecsSAP = ($accbPyblsPrmSnsRstl[5] >= 1) ? true : false;
$addRecsDRFS = ($accbPyblsPrmSnsRstl[6] >= 1) ? true : false;
$editRecsDRFS = ($accbPyblsPrmSnsRstl[7] >= 1) ? true : false;
$delRecsDRFS = ($accbPyblsPrmSnsRstl[8] >= 1) ? true : false;
$addRecsSCMIR = ($accbPyblsPrmSnsRstl[9] >= 1) ? true : false;
$editRecsSCMIR = ($accbPyblsPrmSnsRstl[10] >= 1) ? true : false;
$delRecsSCMIR = ($accbPyblsPrmSnsRstl[11] >= 1) ? true : false;
$addRecsDTFS = ($accbPyblsPrmSnsRstl[12] >= 1) ? true : false;
$editRecsDTFS = ($accbPyblsPrmSnsRstl[13] >= 1) ? true : false;
$delRecsDTFS = ($accbPyblsPrmSnsRstl[14] >= 1) ? true : false;
$addRecsSDMIT = ($accbPyblsPrmSnsRstl[15] >= 1) ? true : false;
$editRecsSDMIT = ($accbPyblsPrmSnsRstl[16] >= 1) ? true : false;
$delRecsSDMIT = ($accbPyblsPrmSnsRstl[17] >= 1) ? true : false;
$canRvwApprvDocs = ($accbPyblsPrmSnsRstl[18] >= 1) ? true : false;
$canPayDocs = ($accbPyblsPrmSnsRstl[19] >= 1) ? true : false;
$cancelDocs = ($accbPyblsPrmSnsRstl[20] >= 1) ? true : false;

$canAdd = $addRecsSSP || $addRecsSAP || $addRecsDRFS || $addRecsSCMIR || $addRecsDTFS || $addRecsSDMIT;
$canEdt = $editRecsSSP || $editRecsSAP || $editRecsDRFS || $editRecsSCMIR || $editRecsDTFS || $editRecsSDMIT;
$canDel = $delRecsSSP || $delRecsSAP || $delRecsDRFS || $delRecsSCMIR || $delRecsDTFS || $delRecsSDMIT;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";

$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canViewPybls === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Payables Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePyblsDocHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Payables Invoice Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePyblsDocDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdt) {
                    echo deletePyblsInvcDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Payables Invoice Transaction
                header("content-type:application/json");
                $sbmtdAccbPyblsInvcID = isset($_POST['sbmtdAccbPyblsInvcID']) ? (float) cleanInputData($_POST['sbmtdAccbPyblsInvcID']) : -1;
                $accbPyblsInvcDocNum = isset($_POST['accbPyblsInvcDocNum']) ? cleanInputData($_POST['accbPyblsInvcDocNum']) : "";
                $accbPyblsInvcDfltTrnsDte = isset($_POST['accbPyblsInvcDfltTrnsDte']) ? cleanInputData($_POST['accbPyblsInvcDfltTrnsDte']) : '';
                $accbPyblsInvcVchType = isset($_POST['accbPyblsInvcVchType']) ? cleanInputData($_POST['accbPyblsInvcVchType']) : '';

                $accbPyblsInvcPayMthdID = isset($_POST['accbPyblsInvcPayMthdID']) ? (int) cleanInputData($_POST['accbPyblsInvcPayMthdID']) : -10;
                $accbPyblsInvcDocTmplt = isset($_POST['accbPyblsInvcDocTmplt']) ? cleanInputData($_POST['accbPyblsInvcDocTmplt']) : '';
                $srcPyblsInvcDocTyp = isset($_POST['srcPyblsInvcDocTyp']) ? cleanInputData($_POST['srcPyblsInvcDocTyp']) : '';
                $srcPyblsInvcDocID = isset($_POST['srcPyblsInvcDocID']) ? (float) cleanInputData($_POST['srcPyblsInvcDocID']) : -1;
                $accbPyblsInvcPayTerms = isset($_POST['accbPyblsInvcPayTerms']) ? cleanInputData($_POST['accbPyblsInvcPayTerms']) : '';
                $firtsChequeNum = isset($_POST['firtsChequeNum']) ? cleanInputData($_POST['firtsChequeNum']) : '';
                $nextPartPayment = isset($_POST['nextPartPayment']) ? (float) cleanInputData($_POST['nextPartPayment']) : 0.00;
                $accbPyblsInvcFuncCrncyRate = isset($_POST['accbPyblsInvcFuncCrncyRate']) ? (float) cleanInputData($_POST['accbPyblsInvcFuncCrncyRate'])
                            : 1.000;

                $accbPyblsInvcSpplrInvcNum = isset($_POST['accbPyblsInvcSpplrInvcNum']) ? cleanInputData($_POST['accbPyblsInvcSpplrInvcNum'])
                            : '';

                $accbPyblsInvcInvcCur = isset($_POST['accbPyblsInvcInvcCur']) ? cleanInputData($_POST['accbPyblsInvcInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $accbPyblsInvcInvcCurID = getPssblValID($accbPyblsInvcInvcCur, $curLovID);
                $funcExchRate = round(get_LtstExchRate($accbPyblsInvcInvcCurID, $fnccurid, $accbPyblsInvcDfltTrnsDte), 4);
                if ($accbPyblsInvcFuncCrncyRate == 0 || $accbPyblsInvcFuncCrncyRate == 1) {
                    $accbPyblsInvcFuncCrncyRate = $funcExchRate;
                }
                $accbPyblsInvcTtlAmnt = isset($_POST['accbPyblsInvcTtlAmnt']) ? (float) cleanInputData($_POST['accbPyblsInvcTtlAmnt']) : 0;
                $myCptrdPyblsInvcValsTtlVal = isset($_POST['myCptrdPyblsInvcValsTtlVal']) ? (float) cleanInputData($_POST['myCptrdPyblsInvcValsTtlVal'])
                            : 0;
                $accbPyblsInvcEvntDocTyp = isset($_POST['accbPyblsInvcEvntDocTyp']) ? cleanInputData($_POST['accbPyblsInvcEvntDocTyp']) : '';
                $accbPyblsInvcEvntCtgry = isset($_POST['accbPyblsInvcEvntCtgry']) ? cleanInputData($_POST['accbPyblsInvcEvntCtgry']) : '';
                $accbPyblsInvcEvntRgstrID = isset($_POST['accbPyblsInvcEvntRgstrID']) ? (float) cleanInputData($_POST['accbPyblsInvcEvntRgstrID'])
                            : -1;
                $accbPyblsInvcSpplrID = isset($_POST['accbPyblsInvcSpplrID']) ? (float) cleanInputData($_POST['accbPyblsInvcSpplrID']) : -1;
                $accbPyblsInvcSpplrSiteID = isset($_POST['accbPyblsInvcSpplrSiteID']) ? (float) cleanInputData($_POST['accbPyblsInvcSpplrSiteID'])
                            : -1;
                $accbPyblsInvcDfltBalsAcntID = isset($_POST['accbPyblsInvcDfltBalsAcntID']) ? (float) cleanInputData($_POST['accbPyblsInvcDfltBalsAcntID'])
                            : -1;
                $accbPyblsInvcGLBatchID = isset($_POST['accbPyblsInvcGLBatchID']) ? (float) cleanInputData($_POST['accbPyblsInvcGLBatchID'])
                            : -1;
                $accbPyblsInvcDesc = isset($_POST['accbPyblsInvcDesc']) ? cleanInputData($_POST['accbPyblsInvcDesc']) : '';
                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $slctdExtraInfoLines = isset($_POST['slctdExtraInfoLines']) ? cleanInputData($_POST['slctdExtraInfoLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;

                if (strlen($accbPyblsInvcDesc) > 499) {
                    $accbPyblsInvcDesc = substr($pyblsInvcDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($accbPyblsInvcDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($accbPyblsInvcDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                if ($accbPyblsInvcVchType == "") {
                    $exitErrMsg .= "Document Type cannot be empty!<br/>";
                }
                if ($accbPyblsInvcDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($accbPyblsInvcSpplrID <= 0) {
                    $exitErrMsg .= "Supplier/Vendor cannot be empty!<br/>";
                }
                if ($accbPyblsInvcSpplrSiteID <= 0) {
                    $exitErrMsg .= "Supplier Site cannot be empty!<br/>";
                }
                if ($accbPyblsInvcEvntDocTyp != "None" && ($accbPyblsInvcEvntCtgry == "" || $accbPyblsInvcEvntRgstrID <= 0)) {
                    $exitErrMsg .= "Linked Event Number and Category Cannot be empty\r\n if the Event Type is not set to None!<br/>";
                }
                if ($accbPyblsInvcDfltBalsAcntID <= 0) {
                    $exitErrMsg .= "Please enter a Liability Account!<br/>";
                }
                $oldPyblDocID = getGnrlRecID("accb.accb_pybls_invc_hdr", "pybls_invc_number", "pybls_invc_hdr_id", $accbPyblsInvcDocNum,
                        $orgID);
                if ($oldPyblDocID > 0 && $oldPyblDocID != $sbmtdAccbPyblsInvcID) {
                    $exitErrMsg .= "New Document Number is already in use in this Organization!<br/>";
                }
                $apprvlStatus = "Not Validated";
                $nxtApprvlActn = "Approve";
                $amntPaid = 0;
                $glBtchID = -1;
                $amntAppld = 0;
                $advcPayDocID = -1;
                $advcPayDocTyp = "";
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAccbPyblsInvcID'] = $sbmtdAccbPyblsInvcID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdAccbPyblsInvcID <= 0) {
                    createPyblsDocHdr($orgID, $accbPyblsInvcDfltTrnsDte, $accbPyblsInvcDocNum, $accbPyblsInvcVchType, $accbPyblsInvcDesc,
                            $srcPyblsInvcDocID, $accbPyblsInvcSpplrID, $accbPyblsInvcSpplrSiteID, $apprvlStatus, $nxtApprvlActn,
                            $accbPyblsInvcTtlAmnt, $accbPyblsInvcPayTerms, $srcPyblsInvcDocTyp, $accbPyblsInvcPayMthdID, $amntPaid,
                            $glBtchID, $accbPyblsInvcSpplrInvcNum, $accbPyblsInvcDocTmplt, $accbPyblsInvcInvcCurID, $amntAppld,
                            $accbPyblsInvcEvntRgstrID, $accbPyblsInvcEvntCtgry, $accbPyblsInvcEvntDocTyp, $accbPyblsInvcDfltBalsAcntID,
                            $advcPayDocID, $advcPayDocTyp, $nextPartPayment, $firtsChequeNum, $accbPyblsInvcFuncCrncyRate);
                    $sbmtdAccbPyblsInvcID = getGnrlRecID("accb.accb_pybls_invc_hdr", "pybls_invc_number", "pybls_invc_hdr_id",
                            $accbPyblsInvcDocNum, $orgID);
                } else if ($sbmtdAccbPyblsInvcID > 0) {
                    updtPyblsDocHdr($sbmtdAccbPyblsInvcID, $accbPyblsInvcDfltTrnsDte, $accbPyblsInvcDocNum, $accbPyblsInvcVchType,
                            $accbPyblsInvcDesc, $srcPyblsInvcDocID, $accbPyblsInvcSpplrID, $accbPyblsInvcSpplrSiteID, $apprvlStatus,
                            $nxtApprvlActn, $accbPyblsInvcTtlAmnt, $accbPyblsInvcPayTerms, $srcPyblsInvcDocTyp, $accbPyblsInvcPayMthdID,
                            $amntPaid, $glBtchID, $accbPyblsInvcSpplrInvcNum, $accbPyblsInvcDocTmplt, $accbPyblsInvcInvcCurID, $amntAppld,
                            $accbPyblsInvcEvntRgstrID, $accbPyblsInvcEvntCtgry, $accbPyblsInvcEvntDocTyp, $accbPyblsInvcDfltBalsAcntID,
                            $advcPayDocID, $advcPayDocTyp, $nextPartPayment, $firtsChequeNum, $accbPyblsInvcFuncCrncyRate);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDetTransLines, "|~") != "" && $sbmtdAccbPyblsInvcID > 0) {
                    //Save Payables Invoice Double Entry Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 16) {
                            $lnSmmryLnID = (float) (cleanInputData1($crntRow[0]));
                            $lnSlctdAmtBrkdwns = cleanInputData1($crntRow[1]);
                            $lnRefDoc = cleanInputData1($crntRow[2]);
                            $ln_ItemType = cleanInputData1($crntRow[3]);
                            $ln_IncrsDcrs1 = cleanInputData1($crntRow[4]);
                            $ln_AccountID1 = (int) cleanInputData1($crntRow[5]);

                            $lineDesc = cleanInputData1($crntRow[6]);
                            $ln_CodeBehind = (int) (cleanInputData1($crntRow[7]));
                            $lineCurNm = cleanInputData1($crntRow[8]);
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $entrdAmt = (float) cleanInputData1($crntRow[9]);
                            $ln_AutoCalc = cleanInputData1($crntRow[10]) == "YES" ? true : false;
                            $ln_FuncExchgRate = $accbPyblsInvcFuncCrncyRate;
                            $ln_TaxID = (int) (cleanInputData1($crntRow[12]));
                            $ln_WHTaxID = (int) (cleanInputData1($crntRow[13]));
                            $ln_DscntID = (int) (cleanInputData1($crntRow[14]));
                            $ln_InitAmntLnID = (float) cleanInputData1($crntRow[15]);
                            $lineTransDate = $accbPyblsInvcDfltTrnsDte;

                            $funcCurrAmnt = $ln_FuncExchgRate * $entrdAmt;

                            $ln_IncrsDcrs2 = "Increase";
                            $ln_AccountID2 = $accbPyblsInvcDfltBalsAcntID;

                            $drCrdt1 = dbtOrCrdtAccnt($ln_AccountID1, substr($ln_IncrsDcrs1, 0, 1)) == "Debit" ? "D" : "C";
                            if ($drCrdt1 == "D") {
                                $ln_IncrsDcrs2 = str_replace("d", "D",
                                        str_replace("i", "I", strtolower(incrsOrDcrsAccnt($ln_AccountID2, "Credit"))));
                            } else {
                                $ln_IncrsDcrs2 = str_replace("d", "D",
                                        str_replace("i", "I", strtolower(incrsOrDcrsAccnt($ln_AccountID2, "Debit"))));
                            }
                            $drCrdt2 = dbtOrCrdtAccnt($ln_AccountID2, substr($ln_IncrsDcrs2, 0, 1)) == "Debit" ? "D" : "C";
                            $lineDbtAmt1 = ($drCrdt1 == "D") ? ($entrdAmt * $ln_FuncExchgRate) : 0;
                            $lineCrdtAmt1 = ($drCrdt1 == "C") ? ($entrdAmt * $ln_FuncExchgRate) : 0;
                            $lineDbtAmt2 = ($drCrdt2 == "D") ? ($entrdAmt * $ln_FuncExchgRate) : 0;
                            $lineCrdtAmt2 = ($drCrdt2 == "C") ? ($entrdAmt * $ln_FuncExchgRate) : 0;
                            $lineTransDate1 = cnvrtDMYTmToYMDTm($lineTransDate);
                            $prepayDocHdrID = -1;
                            if ($ln_ItemType == "5Applied Prepayment") {
                                $prepayDocHdrID = $ln_CodeBehind;
                                $ln_CodeBehind = -1;
                            }
                            $vldyStatus = "VALID";
                            $orgnlLnID = -1;
                            $initAmntID = -1;

                            $netAmnt1 = drCrAccMltplr($ln_AccountID1, $drCrdt1) * $entrdAmt;
                            $dbtOrCrdt1 = substr(strtoupper($drCrdt1), 0, 1);
                            $accntCurrID1 = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $ln_AccountID1);
                            $acntExchRate1 = round(get_LtstExchRate($lineCurID, $accntCurrID1, $lineTransDate), 4);
                            $acntAmnt1 = $acntExchRate1 * $entrdAmt;

                            $netAmnt2 = drCrAccMltplr($ln_AccountID2, $drCrdt2) * $entrdAmt;
                            $dbtOrCrdt2 = substr(strtoupper($drCrdt2), 0, 1);
                            $accntCurrID2 = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $ln_AccountID2);
                            $acntExchRate2 = round(get_LtstExchRate($lineCurID, $accntCurrID2, $lineTransDate), 4);
                            $acntAmnt2 = $acntExchRate1 * $entrdAmt;


                            $srcTrnsID1 = -1;
                            $srcTrnsID2 = -1;
                            $errMsg = "";
                            $isdbtCrdt = dbtOrCrdtAccnt($ln_AccountID1, substr(strtoupper($ln_IncrsDcrs1), 0, 1));
                            if ($ln_ItemType === "" || $ln_AccountID1 <= 0 || $ln_IncrsDcrs1 === "") {
                                $errMsg .= "Row " . ($y + 1) . ":- Line Type, GL Account and Increase/Decrease are all required Fields!<br/>";
                            }
                            if (strpos($accbPyblsInvcVchType, "Supplier") !== FALSE) {
                                if ($accbPyblsInvcVchType == "Supplier Standard Payment" || $accbPyblsInvcVchType == "Supplier Advance Payment"
                                        || $accbPyblsInvcVchType == "Direct Topup for Supplier" || $accbPyblsInvcVchType == "Supplier Debit Memo (InDirect Topup)") {
                                    if ($ln_ItemType == "1Initial Amount" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Asset/Expense/Prepaid Expense!)<br/>";
                                    }
                                    $isTxWthhldng = isTaxWthHldng($ln_CodeBehind);
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBehind > 0 && $isTxWthhldng == "0" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Tax Expense/Decrease Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBehind > 0 && $isTxWthhldng == "1" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Withholding Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "3Discount" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Purchase Discounts (Contra Expense Account)!)<br/>";
                                    }
                                    if ($ln_ItemType == "4Extra Charge" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Asset/Expense!)<br/>";
                                    }
                                    if ($ln_ItemType == "5Applied Prepayment" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Decrease Prepaid Expense!)<br/>";
                                    }
                                } else {
                                    if ($ln_ItemType == "1Initial Amount" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Decrease Asset/Expense/Prepaid Expense!)<br/>";
                                    }
                                    $isTxWthhldng = isTaxWthHldng($ln_CodeBehind);
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBehind > 0 && $isTxWthhldng == "0" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Decrease Tax Expense/Decrease Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBehind > 0 && $isTxWthhldng == "1" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Decrease Withholding Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "3Discount" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Decrease Purchase Discounts (Contra Expense Account)!)<br/>";
                                    }
                                    if ($ln_ItemType == "4Extra Charge" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Decrease Asset/Expense!)<br/>";
                                    }
                                    if ($ln_ItemType == "5Applied Prepayment" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Prepaid Expense!)<br/>";
                                    }
                                }
                            } else if (strpos($accbPyblsInvcVchType, "Customer") !== FALSE) {
                                if ($accbPyblsInvcVchType == "Customer Standard Payment" || $accbPyblsInvcVchType == "Customer Advance Payment"
                                        || $accbPyblsInvcVchType == "Direct Topup from Customer" || $accbPyblsInvcVchType == "Customer Credit Memo (InDirect Topup)") {
                                    if ($ln_ItemType == "1Initial Amount" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Revenue/Custmr Advance Payments!)<br/>";
                                    }
                                    $isTxWthhldng = isTaxWthHldng($ln_CodeBehind);
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBehind > 0 && $isTxWthhldng == "1" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Withholding Tax Expense or Receivable/Decrease Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBehind > 0 && $isTxWthhldng == "0" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Sales Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "3Discount" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Sales Discounts!)<br/>";
                                    }
                                    if ($ln_ItemType == "4Extra Charge" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Extra Revenue Account!)<br/>";
                                    }
                                    if ($ln_ItemType == "5Applied Prepayment" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Decrease Customer Advance Payments!)<br/>";
                                    }
                                } else {
                                    if ($ln_ItemType == "1Initial Amount" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Decrease Revenue/Custmr Advance Payments!)<br/>";
                                    }
                                    $isTxWthhldng = isTaxWthHldng($ln_CodeBehind);
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBehind > 0 && $isTxWthhldng == "1" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Decrease Withholding Tax Expense or Receivable/Increase Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBehind > 0 && $isTxWthhldng == "0" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Decrease Sales Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "3Discount" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Decrease Sales Discounts!)<br/>";
                                    }
                                    if ($ln_ItemType == "4Extra Charge" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a DEBIT Transaction (i.e. Decrease Extra Revenue Account!)<br/>";
                                    }
                                    if ($ln_ItemType == "5Applied Prepayment" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($y + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Customer Advance Payments!)<br/>";
                                    }
                                }
                            }
                                           
                            if ($errMsg === "") {
                                //Create Payable Invoice Summary Trns Record Itself
                                if ($lineDesc != "" && $ln_AccountID1 > 0 && $ln_AccountID2 > 0 && $ln_InitAmntLnID <= 0) {
                                    if ($lnSmmryLnID <= 0) {
                                        $lnSmmryLnID = getNewPyblsLnID();
                                        $afftctd += createPyblsDocDet($lnSmmryLnID, $sbmtdAccbPyblsInvcID, $ln_ItemType, $lineDesc,
                                                $entrdAmt, $lineCurID, $ln_CodeBehind, $accbPyblsInvcVchType, $ln_AutoCalc, $ln_IncrsDcrs1,
                                                $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $prepayDocHdrID, $vldyStatus, $orgnlLnID,
                                                $fnccurid, $accntCurrID1, $ln_FuncExchgRate, $acntExchRate1, $funcCurrAmnt, $acntAmnt1,
                                                $initAmntID, $lnRefDoc, $lnSlctdAmtBrkdwns, $ln_TaxID, $ln_WHTaxID, $ln_DscntID);
                                    } else {
                                        $afftctd += updtPyblsDocDet($lnSmmryLnID, $sbmtdAccbPyblsInvcID, $ln_ItemType, $lineDesc, $entrdAmt,
                                                $lineCurID, $ln_CodeBehind, $accbPyblsInvcVchType, $ln_AutoCalc, $ln_IncrsDcrs1,
                                                $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $prepayDocHdrID, $vldyStatus, $orgnlLnID,
                                                $fnccurid, $accntCurrID1, $ln_FuncExchgRate, $acntExchRate1, $funcCurrAmnt, $acntAmnt1,
                                                $initAmntID, $lnRefDoc, $lnSlctdAmtBrkdwns, $ln_TaxID, $ln_WHTaxID, $ln_DscntID);                                                
                                    }
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }
                if (trim($slctdExtraInfoLines, "|~") != "" && $sbmtdAccbPyblsInvcID > 0) {
                    $variousRows = explode("|", trim($slctdExtraInfoLines, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 6) {
                            $ln_DfltRowID = (float) (cleanInputData1($crntRow[0]));
                            $ln_CombntnID = (float) cleanInputData1($crntRow[1]);
                            $ln_TableID = (float) cleanInputData1($crntRow[2]);
                            $ln_extrInfoCtgry = cleanInputData1($crntRow[3]);
                            $ln_extrInfoLbl = cleanInputData1($crntRow[4]);
                            $ln_Value = cleanInputData1($crntRow[5]);
                            if ($ln_DfltRowID > 0) {
                                $afftctd1 += updateRowOthrInfVal("accb.accb_all_other_info_table", $ln_CombntnID, $sbmtdAccbPyblsInvcID,
                                        $ln_Value, $ln_extrInfoLbl, $ln_extrInfoCtgry, $ln_DfltRowID);
                            } else {
                                if (doesRowHvOthrInfo("accb.accb_all_other_info_table", $ln_CombntnID, $sbmtdAccbPyblsInvcID) > 0) {
                                    $afftctd1 += updateRowOthrInfVal("accb.accb_all_other_info_table", $ln_CombntnID, $sbmtdAccbPyblsInvcID,
                                            $ln_Value, $ln_extrInfoLbl, $ln_extrInfoCtgry, $ln_DfltRowID);
                                } else {
                                    $ln_DfltRowID = getNewExtInfoID("accb.accb_all_other_info_table_dflt_row_id_seq");
                                    $afftctd1 += createRowOthrInfVal("accb.accb_all_other_info_table", $ln_CombntnID, $sbmtdAccbPyblsInvcID,
                                            $ln_Value, $ln_extrInfoLbl, $ln_extrInfoCtgry, $ln_DfltRowID);
                                }
                            }
                        }
                    }
                }
                if ($shdSbmt != 2) {
                    $errMsg1 = reCalcPyblInvcSmmrys($sbmtdAccbPyblsInvcID, $accbPyblsInvcVchType, $accbPyblsInvcInvcCurID);
                   if (strpos($errMsg1, "ERROR") !== FALSE) {
                        $exitErrMsg .= "<br/>" . $errMsg1;
                    }
                    updtPyblsDocAmnt($sbmtdAccbPyblsInvcID, getPyblsDocGrndAmnt($sbmtdAccbPyblsInvcID));
                    $errMsg = "";
                    if (validatePyblInvcLns($sbmtdAccbPyblsInvcID, $accbPyblsInvcVchType, $accbPyblsInvcTtlAmnt, $errMsg) === false) {
                        $exitErrMsg .= "IS INVALID?<br/>" . $errMsg;
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Payables Invoice Voucher Successfully Saved!"
                            . "<br/>" . $afftctd . " Payables Invoice Transaction(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "";
                    //Final Approval
                    if ($shdSbmt == 2) {
                        $exitErrMsg = apprvPyblsRcvblDoc($sbmtdAccbPyblsInvcID, $accbPyblsInvcDocNum, "Payables", $orgID, $usrID);
                        if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        } else {
                            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        }
                        /* updtPyblsDocGLBatch($sbmtdAccbPyblsInvcID, $accbPyblsInvcGLBatchID);
                          updtPyblsDocApprvl($sbmtdAccbPyblsInvcID, "Approved", "Cancel"); */
                    } else {
                        $exitErrMsg .= "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Payables Invoice Voucher Successfully Saved!"
                                . "<br/>" . $afftctd . " Payables Invoice Transaction(s) Saved Successfully!";
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdAccbPyblsInvcID'] = $sbmtdAccbPyblsInvcID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 20) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdAccbPyblsInvcID = isset($_POST['sbmtdAccbPyblsInvcID']) ? cleanInputData($_POST['sbmtdAccbPyblsInvcID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdAccbPyblsInvcID;
                if ($attchmentID > 0) {
                    uploadDaPyblsInvcDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewPyblsInvcDocID();
                    createPyblsInvcDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaPyblsInvcDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/PyblDocs/" . $nwImgLoc;
                    $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                    if (file_exists($doc_src)) {
                        //file exists!
                    } else {
                        //file does not exist.
                        $doc_src_encrpt = "None";
                    }
                    $arr_content['crptpath'] = $doc_src_encrpt;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                }
                echo json_encode($arr_content);
                exit();
            }
        } else if ($qstr == "VOID") {
            if ($actyp == 1) {
                //Reverse Payables Invoice Voucher
                $errMsg = "";
                $accbPyblsInvcDesc = isset($_POST['accbPyblsInvcDesc']) ? cleanInputData($_POST['accbPyblsInvcDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdAccbPyblsInvcID = isset($_POST['sbmtdAccbPyblsInvcID']) ? cleanInputData($_POST['sbmtdAccbPyblsInvcID']) : -1;
                if (!$cancelDocs) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                $p_dochdrtype = "";
                if ($sbmtdAccbPyblsInvcID > 0) {
                    $result = get_One_PyblsInvcDocHdr($sbmtdAccbPyblsInvcID);
                    if ($row = loc_db_fetch_array($result)) {
                        $accbPyblsInvcDfltTrnsDte = $row[1] . " 12:00:00";
                        $accbPyblsInvcGLBatch = $row[21];
                        $gnrtdTrnsNo = $row[4];
                        $p_dochdrtype = $row[5];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                    }
                }
                if ($rqStatus == "Not Validated" && $sbmtdAccbPyblsInvcID > 0) {
                    echo deletePyblsDocHdrNDet($sbmtdAccbPyblsInvcID, $gnrtdTrnsNo);
                    exit();
                } else {
                    execUpdtInsSQL("UPDATE accb.accb_pybls_invc_hdr SET comments_desc='" . loc_db_escape_string($accbPyblsInvcDesc) . "' WHERE (pybls_invc_hdr_id = " . $sbmtdAccbPyblsInvcID . ")");

                    $exitErrMsg = cancelPyblsRcvblDoc($sbmtdAccbPyblsInvcID, $p_dochdrtype, "Payables", $orgID, $usrID);
                    $arr_content['sbmtdAccbPyblsInvcID'] = $sbmtdAccbPyblsInvcID;
                    $arr_content['percent'] = 100;
                    if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                        $arr_content['sbmtMsg'] = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $arr_content['sbmtMsg'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    }
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                //Payables Invoices
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Payable Invoices</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                $qShwUnpaidOnly = false;
                if (isset($_POST['qShwUnpaidOnly'])) {
                    $qShwUnpaidOnly = cleanInputData($_POST['qShwUnpaidOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_PyblsDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_PyblsDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
                    $colClassType3 = "col-md-5";
                    ?> 
                    <form id='accbPyblsInvcForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">PAYABLE INVOICES</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                                $colClassType3 = "col-md-10";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbPyblsInvcSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAccbPyblsInvc(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="accbPyblsInvcPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPyblsInvc('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPyblsInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbPyblsInvcSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                            $srchInsArrys = array("All", "Document Number", "Document Description", "Document Classification",
                                                "Supplier Name", "Supplier's Invoice Number", "Source Doc Number", "Approval Status", "Created By",
                                                "Currency");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbPyblsInvcDsplySze" style="min-width:70px !important;">                            
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
                                                <a href="javascript:getAccbPyblsInvc('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbPyblsInvc('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>   
                            <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                                <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                    <?php if ($canAdd === true) { ?>   
                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">                      
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbPyblsInvcForm(-1, 1, 'ShowDialog', 'Supplier Standard Payment');" data-toggle="tooltip" data-placement="bottom" title="Add New Supplier Standard Payment">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                SSP
                                            </button>                 
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbPyblsInvcForm(-1, 1, 'ShowDialog', 'Supplier Advance Payment');" data-toggle="tooltip" data-placement="bottom" title="Add New Supplier Advance Payment">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                SAP
                                            </button>                 
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbPyblsInvcForm(-1, 1, 'ShowDialog', 'Direct Refund from Supplier');" data-toggle="tooltip" data-placement="bottom" title="Add New Direct Refund from Supplier">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                DRFS
                                            </button>                  
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbPyblsInvcForm(-1, 1, 'ShowDialog', 'Supplier Credit Memo (InDirect Refund)');" data-toggle="tooltip" data-placement="bottom" title="Add New Supplier Credit Memo (InDirect Refund)">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                SCM-IR
                                            </button>                
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbPyblsInvcForm(-1, 1, 'ShowDialog', 'Direct Topup for Supplier');" data-toggle="tooltip" data-placement="bottom" title="Add New Direct Topup for Supplier">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                DTFS
                                            </button>                    
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbPyblsInvcForm(-1, 1, 'ShowDialog', 'Supplier Debit Memo (InDirect Topup)');" data-toggle="tooltip" data-placement="bottom" title="Add New Supplier Debit Memo (InDirect Topup)">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                SDM-IT
                                            </button>
                                        </div>  
                                    <?php }
                                    ?>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label">
                                                <?php
                                                $shwUnpaidOnlyChkd = "";
                                                if ($qShwUnpaidOnly == true) {
                                                    $shwUnpaidOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getAccbPyblsInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbPyblsInvcShwUnpaidOnly" name="accbPyblsInvcShwUnpaidOnly"  <?php echo $shwUnpaidOnlyChkd; ?>>
                                                Show Approved but Unpaid
                                            </label>
                                        </div> 
                                    </div>
                                    <div class = "col-md-3" style = "padding:5px 1px 0px 1px !important;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getAccbPyblsInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbPyblsInvcShwUnpstdOnly" name="accbPyblsInvcShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted
                                            </label>
                                        </div>                            
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="accbPyblsInvcHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:35px;width:35px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th>Invoice Number/Type - Transaction Description</th>
                                                <th style="text-align:center;max-width:30px;width:30px;">CUR.</th>	
                                                <th style="text-align:right;min-width:100px;width:100px;">Total Invoice Amount</th>
                                                <th style="text-align:right;min-width:100px;width:100px;">Total Amount Paid</th>
                                                <th style="text-align:right;min-width:100px;width:100px;">Amount Outstanding</th>
                                                <th style="max-width:75px;width:75px;">Invoice Status</th>
                                                <?php if ($canDel === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="accbPyblsInvcHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Invoice" 
                                                                onclick="getOneAccbPyblsInvcForm(<?php echo $row[0]; ?>, 1, 'ShowDialog', '<?php echo $row[2]; ?>');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                    <?php
                                                                    if ($canAdd === true) {
                                                                        ?>                                
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[1] . " (" . $row[2] . ") " . $row[9] . " " . $row[3]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[4]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[5], 2);
                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[6], 2);
                                                        ?></td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if (((float) $row[7]) <= 0) {
                                                        $style1 = "color:green;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;<?php echo $style1; ?>"><?php
                                                        echo number_format((float) $row[7], 2);
                                                        ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if ($row[8] == "Approved") {
                                                        $style1 = "color:green;";
                                                    } else if ($row[8] == "Cancelled") {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php echo $row[8]; ?></td>  
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delAccbPyblsInvc('accbPyblsInvcHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="accbPyblsInvcHdrsRow<?php echo $cntr; ?>_HdrID" name="accbPyblsInvcHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|accb.accb_pybls_invc_hdr|pybls_invc_hdr_id"),
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
                }
            } else if ($vwtyp == 1) {
                //New Payables Invoice Form
                $sbmtdAccbPyblsInvcID = isset($_POST['sbmtdAccbPyblsInvcID']) ? cleanInputData($_POST['sbmtdAccbPyblsInvcID']) : -1;
                $accbPyblsInvcVchType = isset($_POST['accbPyblsInvcVchType']) ? cleanInputData($_POST['accbPyblsInvcVchType']) : "Supplier Standard Payment";
                $extraPKeyID = isset($_POST['extraPKeyID']) ? (float) cleanInputData($_POST['extraPKeyID']) : -1;
                $extraPKeyType = isset($_POST['extraPKeyType']) ? cleanInputData($_POST['extraPKeyType']) : "";
                if (!$canAdd || ($sbmtdAccbPyblsInvcID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                if ($accbPyblsInvcVchType == "Supplier Standard Payment" && (!$addRecsSSP || ($sbmtdAccbPyblsInvcID > 0 && !$editRecsSSP))) {
                    restricted();
                    exit();
                }
                if ($accbPyblsInvcVchType == "Supplier Advance Payment" && (!$addRecsSAP || ($sbmtdAccbPyblsInvcID > 0 && !$editRecsSAP))) {
                    restricted();
                    exit();
                }
                if ($accbPyblsInvcVchType == "Direct Refund from Supplier" && (!$addRecsDRFS || ($sbmtdAccbPyblsInvcID > 0 && !$editRecsDRFS))) {
                    restricted();
                    exit();
                }
                if ($accbPyblsInvcVchType == "Supplier Credit Memo (InDirect Refund)" && (!$addRecsSCMIR || ($sbmtdAccbPyblsInvcID > 0 && !$editRecsSCMIR))) {
                    restricted();
                    exit();
                }
                if ($accbPyblsInvcVchType == "Direct Topup for Supplier" && (!$addRecsDTFS || ($sbmtdAccbPyblsInvcID > 0 && !$editRecsDTFS))) {
                    restricted();
                    exit();
                }
                if ($accbPyblsInvcVchType == "Supplier Debit Memo (InDirect Topup)" && (!$addRecsSDMIT || ($sbmtdAccbPyblsInvcID > 0 && !$editRecsSDMIT))) {
                    restricted();
                    exit();
                }
                $orgnlAccbPyblsInvcID = $sbmtdAccbPyblsInvcID;
                $accbPyblsInvcDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $accbPyblsInvcCreator = $uName;
                $accbPyblsInvcCreatorID = $usrID;
                $gnrtdTrnsNo = "";
                $accbPyblsInvcDesc = "";

                $srcPyblsInvcDocID = -1;
                $srcPyblsInvcDocTyp = "";
                $srcPyblsInvcDocNum = "";
                if ($accbPyblsInvcVchType == "Supplier Standard Payment") {
                    $srcPyblsInvcDocTyp = "";
                } elseif ($accbPyblsInvcVchType == "Supplier Advance Payment") {
                    $srcPyblsInvcDocTyp = "";
                } elseif ($accbPyblsInvcVchType == "Direct Refund from Supplier") {
                    $srcPyblsInvcDocTyp = "Supplier Standard Payment";
                } elseif ($accbPyblsInvcVchType == "Supplier Credit Memo (InDirect Refund)") {
                    $srcPyblsInvcDocTyp = "Supplier Standard Payment";
                } elseif ($accbPyblsInvcVchType == "Direct Topup for Supplier") {
                    $srcPyblsInvcDocTyp = "Supplier Standard Payment";
                } elseif ($accbPyblsInvcVchType == "Supplier Debit Memo (InDirect Topup)") {
                    $srcPyblsInvcDocTyp = "Supplier Standard Payment";
                }
                $accbPyblsInvcDocTmpltID = -1;

                $accbPyblsInvcSpplr = "";
                $accbPyblsInvcSpplrID = -1;
                $accbPyblsInvcSpplrSite = "";
                $accbPyblsInvcSpplrSiteID = -1;
                $accbPyblsInvcSpplrClsfctn = "Supplier";
                $rqStatus = "Not Validated";
                $rqStatusNext = "Approve";
                $rqstatusColor = "red";

                $accbPyblsInvcTtlAmnt = 0;
                $accbPyblsInvcAppldAmnt = 0;
                $accbPyblsInvcPayTerms = "";
                $accbPyblsInvcPayMthd = "";
                $accbPyblsInvcPayMthdID = -1;
                $accbPyblsInvcPaidAmnt = 0;
                $accbPyblsInvcGLBatch = "";
                $accbPyblsInvcGLBatchID = -1;
                $accbPyblsInvcSpplrInvcNum = "";
                $accbPyblsInvcDocTmplt = "";
                $accbPyblsInvcEvntRgstr = "";
                $accbPyblsInvcEvntRgstrID = -1;
                $accbPyblsInvcEvntCtgry = "";
                $accbPyblsInvcEvntDocTyp = "";
                $accbPyblsInvcDfltBalsAcntID = -1;
                $accbPyblsInvcDfltBalsAcnt = "";
                $accbPyblsInvcInvcCurID = $fnccurid;
                $accbPyblsInvcInvcCur = $fnccurnm;
                $accbPyblsInvcIsPstd = "0";
                $advcPayDocId = -1;
                $advcPayDocTyp = "";
                $nextPartPayment = 0;
                $accbPyblsInvcFuncCrncyRate = 1;
                $firtsChequeNum = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdAccbPyblsInvcID > 0) {
                    $result = get_One_PyblsInvcDocHdr($sbmtdAccbPyblsInvcID);
                    if ($row = loc_db_fetch_array($result)) {
                        $accbPyblsInvcDfltTrnsDte = $row[1];
                        $accbPyblsInvcCreator = $row[3];
                        $accbPyblsInvcCreatorID = $row[2];
                        $gnrtdTrnsNo = $row[4];
                        $accbPyblsInvcVchType = $row[5];
                        $accbPyblsInvcDesc = $row[6];
                        $srcPyblsInvcDocID = $row[7];
                        $srcPyblsInvcDocNum = $row[38];
                        $accbPyblsInvcSpplr = $row[9];
                        $accbPyblsInvcSpplrID = $row[8];
                        $accbPyblsInvcSpplrSite = $row[11];
                        $accbPyblsInvcSpplrSiteID = $row[10];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                        $rqstatusColor = "red";

                        $accbPyblsInvcTtlAmnt = (float) $row[14];
                        $accbPyblsInvcAppldAmnt = (float) $row[36];
                        $accbPyblsInvcPayTerms = $row[15];
                        $srcPyblsInvcDocTyp = $row[16];
                        $accbPyblsInvcPayMthd = $row[18];
                        $accbPyblsInvcPayMthdID = $row[17];
                        $accbPyblsInvcPaidAmnt = $row[19];
                        if (strpos($accbPyblsInvcVchType, "Advance Payment") === FALSE) {
                            $accbPyblsInvcAppldAmnt = $accbPyblsInvcPaidAmnt;
                        }
                        $accbPyblsInvcGLBatch = $row[21];
                        $accbPyblsInvcGLBatchID = $row[20];
                        $accbPyblsInvcSpplrInvcNum = $row[22];
                        $accbPyblsInvcDocTmplt = $row[23];
                        $accbPyblsInvcInvcCur = $row[25];
                        $accbPyblsInvcInvcCurID = $row[24];
                        $accbPyblsInvcEvntRgstr = "";
                        $accbPyblsInvcEvntRgstrID = $row[26];
                        $accbPyblsInvcEvntCtgry = $row[27];
                        $accbPyblsInvcEvntDocTyp = $row[28];
                        $accbPyblsInvcDfltBalsAcntID = $row[29];
                        $accbPyblsInvcDfltBalsAcnt = $row[30];
                        $accbPyblsInvcIsPstd = $row[31];
                        $advcPayDocId = $row[32];
                        $advcPayDocTyp = $row[33];
                        $nextPartPayment = $row[34];
                        $firtsChequeNum = $row[35];
                        $accbPyblsInvcFuncCrncyRate = (float) $row[37];
                        if ($rqStatus == "Approved") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Not Validated") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Approved") {
                                $mkRmrkReadOnly = "readonly=\"true\"";
                            }
                        }
                    }
                } else {
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypes = array("Supplier Standard Payment", "Supplier Advance Payment", "Direct Refund from Supplier",
                        "Supplier Credit Memo (InDirect Refund)", "Direct Topup for Supplier", "Supplier Debit Memo (InDirect Topup)");
                    $docTypPrfxs = array("SSP", "SAP", "DRFS", "SCM-IR", "DTFS", "SDM-IT");

                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $accbPyblsInvcVchType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("accb.accb_pybls_invc_hdr", "pybls_invc_number",
                                            "pybls_invc_hdr_id", $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $accbPyblsInvcDfltBalsAcntID = get_DfltSplrPyblsCashAcnt($accbPyblsInvcSpplrID, $orgID);
                    $accbPyblsInvcDfltBalsAcnt = getAccntNum($accbPyblsInvcDfltBalsAcntID) . "." . getAccntName($accbPyblsInvcDfltBalsAcntID);
                    if($accbPyblsInvcDfltBalsAcntID>0){
                    $accbPyblsInvcInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id",
                                    $accbPyblsInvcDfltBalsAcntID);
                    $accbPyblsInvcInvcCur = getPssblValNm($accbPyblsInvcInvcCurID);
                    }
                    createPyblsDocHdr($orgID, $accbPyblsInvcDfltTrnsDte, $gnrtdTrnsNo, $accbPyblsInvcVchType, $accbPyblsInvcDesc,
                            $srcPyblsInvcDocID, $accbPyblsInvcSpplrID, $accbPyblsInvcSpplrSiteID, $rqStatus, $rqStatusNext,
                            $accbPyblsInvcTtlAmnt, $accbPyblsInvcPayTerms, $srcPyblsInvcDocTyp, $accbPyblsInvcPayMthdID,
                            $accbPyblsInvcPaidAmnt, $accbPyblsInvcGLBatchID, $accbPyblsInvcSpplrInvcNum, $accbPyblsInvcDocTmplt,
                            $accbPyblsInvcInvcCurID, $accbPyblsInvcAppldAmnt, $accbPyblsInvcEvntRgstrID, $accbPyblsInvcEvntCtgry,
                            $accbPyblsInvcEvntDocTyp, $accbPyblsInvcDfltBalsAcntID, $advcPayDocId, $advcPayDocTyp, $nextPartPayment,
                            $firtsChequeNum, $accbPyblsInvcFuncCrncyRate);

                    $sbmtdAccbPyblsInvcID = getGnrlRecID("accb.accb_pybls_invc_hdr", "pybls_invc_number", "pybls_invc_hdr_id", $gnrtdTrnsNo,
                            $orgID);
                }
                $accbPyblsInvcOustndngAmnt = $accbPyblsInvcTtlAmnt - $accbPyblsInvcPaidAmnt;
                $accbPyblsInvcOustndngStyle = "color:red;";
                $accbPyblsInvcPaidStyle = "color:black;";
                if ($accbPyblsInvcOustndngAmnt <= 0) {
                    $accbPyblsInvcOustndngStyle = "color:green;";
                }
                if ($accbPyblsInvcPaidAmnt > 0 && $accbPyblsInvcOustndngAmnt <= 0) {
                    $accbPyblsInvcPaidStyle = "color:green;";
                } else if ($accbPyblsInvcPaidAmnt > 0) {
                    $accbPyblsInvcPaidStyle = "color:brown;";
                }
                $reportName = getEnbldPssblValDesc("Payables Invoice", getLovID("Document Custom Print Process Names"));
                $reportTitle = str_replace("Supplier Standard Payment", "Payment Voucher", $accbPyblsInvcVchType);
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdAccbPyblsInvcID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="oneAccbPyblsInvcEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdAccbPyblsInvcID" name="sbmtdAccbPyblsInvcID" value="<?php echo $sbmtdAccbPyblsInvcID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="accbPyblsInvcDocNum" name="accbPyblsInvcDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="accbPyblsInvcDfltTrnsDte" name="accbPyblsInvcDfltTrnsDte" value="<?php
                                        echo substr($accbPyblsInvcDfltTrnsDte, 0, 11);
                                        ?>" placeholder="Transactions Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" aria-label="..." id="accbPyblsInvcVchType" name="accbPyblsInvcVchType" value="<?php echo $accbPyblsInvcVchType; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accbPyblsInvcPayMthd" class="control-label col-md-4">Payment Method:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="accbPyblsInvcPayMthd" name="accbPyblsInvcPayMthd" value="<?php echo $accbPyblsInvcPayMthd; ?>" readonly="true">
                                            <input type="hidden" id="accbPyblsInvcPayMthdID" value="<?php echo $accbPyblsInvcPayMthdID; ?>">
                                            <input type="hidden" id="accbPyblsInvcMthdType" value="Supplier Payments">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Methods', 'allOtherInputOrgID', 'accbPyblsInvcMthdType', '', 'radio', true, '', 'accbPyblsInvcPayMthdID', 'accbPyblsInvcPayMthd', 'clear', 1, '');" data-toggle="tooltip" title="Existing Payment Method">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accbPyblsInvcDocTmplt" class="control-label col-md-4">Doc. Template:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbPyblsInvcDocTmplt" name="accbPyblsInvcDocTmplt" value="<?php echo $accbPyblsInvcDocTmplt; ?>" readonly="true">
                                            <input type="hidden" id="accbPyblsInvcDocTmpltID" value="<?php echo $accbPyblsInvcDocTmpltID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Document Templates', 'allOtherInputOrgID', 'accbPyblsInvcVchType', '', 'radio', true, '', 'accbPyblsInvcDocTmpltID', 'accbPyblsInvcDocTmplt', 'clear', 1, '', function () {
                                                        getPyblsFrmTmplate('pyblsInvcDetLines');
                                                    });" data-toggle="tooltip" title="Existing Document Template">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Status:</label>
                                    </div>
                                    <div class="col-md-8">                             
                                        <button type="button" class="btn btn-default" style="height:37px;width:100% !important;" id="myAccbPyblsInvcStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:37px;">
                                                <?php
                                                echo $rqStatus . ($accbPyblsInvcIsPstd == "1" ? " [Posted]" : " [Not Posted]");
                                                ?>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="accbPyblsInvcSpplr" class="control-label col-md-4">Supplier:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbPyblsInvcSpplr" name="accbPyblsInvcSpplr" value="<?php echo $accbPyblsInvcSpplr; ?>" readonly="true">
                                            <input type="hidden" id="accbPyblsInvcSpplrID" value="<?php echo $accbPyblsInvcSpplrID; ?>">
                                            <input type="hidden" id="accbPyblsInvcSpplrClsfctn" value="<?php echo $accbPyblsInvcSpplrClsfctn; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Supplier', 'ShowDialog', function () {}, 'accbPyblsInvcSpplrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'accbPyblsInvcSpplrClsfctn', 'radio', true, '', 'accbPyblsInvcSpplrID', 'accbPyblsInvcSpplr', 'clear', 1, '', function () {
                                                        getAccbPyblsCodeBhndInfo();
                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label for="accbPyblsInvcSpplrSite" class="control-label col-md-4">Site:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbPyblsInvcSpplrSite" name="accbPyblsInvcSpplrSite" value="<?php echo $accbPyblsInvcSpplrSite; ?>" readonly="true">
                                            <input class="form-control" type="hidden" id="accbPyblsInvcSpplrSiteID" value="<?php echo $accbPyblsInvcSpplrSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'accbPyblsInvcSpplrID', '', '', 'radio', true, '', 'accbPyblsInvcSpplrSiteID', 'accbPyblsInvcSpplrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <label style="margin-bottom:0px !important;">Supplier's Invoice No.:</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="accbPyblsInvcSpplrInvcNum" name="accbPyblsInvcSpplrInvcNum" value="<?php echo $accbPyblsInvcSpplrInvcNum; ?>" <?php echo $mkReadOnly; ?>>
                                    </div>
                                </div>                                                               
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group"  style="width:100%;">
                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="accbPyblsInvcDesc" name="accbPyblsInvcDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $accbPyblsInvcDesc; ?></textarea>
                                            <input class="form-control" type="hidden" id="accbPyblsInvcDesc1" value="<?php echo $accbPyblsInvcDesc; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbPyblsInvcDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Source Doc. Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" aria-label="..." id="srcPyblsInvcDocTyp" name="srcPyblsInvcDocTyp" value="<?php echo $srcPyblsInvcDocTyp; ?>">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="srcPyblsInvcDocNum" class="control-label col-md-4">Source Doc. No.:</label>
                                    <div  class="col-md-8">
                                        <input type="hidden" id="srcPyblsInvcDocID" value="<?php echo $srcPyblsInvcDocID; ?>"><?php
                                        if (!($accbPyblsInvcVchType == "Supplier Advance Payment" || $accbPyblsInvcVchType == "Supplier Standard Payment")) {
                                            ?>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="srcPyblsInvcDocNum" name="srcPyblsInvcDocNum" value="<?php echo $srcPyblsInvcDocNum; ?>" readonly="true" style="width:100%;">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Supplier Standard Payments New', 'allOtherInputOrgID', 'accbPyblsInvcSpplrID', 'accbPyblsInvcInvcCur', 'radio', true, '', 'srcPyblsInvcDocID', 'srcPyblsInvcDocNum', 'clear', 1, '', function () {});" data-toggle="tooltip" title="Existing Document Number">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        <?php } else { ?>
                                            <input type="text" class="form-control" aria-label="..." id="srcPyblsInvcDocNum" name="srcPyblsInvcDocNum" value="<?php echo $srcPyblsInvcDocNum; ?>" readonly="true" style="width:100%;">
                                        <?php } ?>
                                    </div>
                                </div>  
                            </div>
                            <div class = "col-md-4">  
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Invoice Total:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $accbPyblsInvcInvcCur; ?>', 'accbPyblsInvcInvcCur', '', 'clear', 0, '', function () {
                                                        $('#accbPyblsInvcInvcCur1').html($('#accbPyblsInvcInvcCur').val());
                                                        $('#accbPyblsInvcInvcCur2').html($('#accbPyblsInvcInvcCur').val());
                                                        $('#accbPyblsInvcInvcCur3').html($('#accbPyblsInvcInvcCur').val());
                                                        $('#accbPyblsInvcInvcCur4').html($('#accbPyblsInvcInvcCur').val());
                                                        $('#accbPyblsInvcInvcCur5').html($('#accbPyblsInvcInvcCur').val());
                                                        $('#accbPyblsInvcInvcCur6').html($('#accbPyblsInvcInvcCur').val());
                                                    }, 'accbPyblsInvcInvcCurID');">
                                                <span class="" style="font-size: 20px !important;" id="accbPyblsInvcInvcCur1"><?php echo $accbPyblsInvcInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="accbPyblsInvcInvcCur" value="<?php echo $accbPyblsInvcInvcCur; ?>"> 
                                            <input type="hidden" id="accbPyblsInvcInvcCurID" value="<?php echo $accbPyblsInvcInvcCurID; ?>"> 
                                            <input class="form-control rqrdFld" type="text" id="accbPyblsInvcTtlAmnt" value="<?php
                                            echo number_format($accbPyblsInvcTtlAmnt, 2);
                                            ?>"  
                                                   style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbPyblsInvcTtlAmnt');" <?php echo $mkReadOnly; ?>/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Exch. Rate (Multiplier):</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;" id="accbPyblsInvcInvcCur6"><?php echo $accbPyblsInvcInvcCur; ?></span>
                                                <span class="" style="font-size: 20px !important;" id="accbPyblsInvcFuncCur"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="accbPyblsInvcFuncCrncyRate" value="<?php
                                            echo number_format($accbPyblsInvcFuncCrncyRate, 4);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" <?php echo $mkReadOnly; ?>/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Total Amount Paid:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $accbPyblsInvcPaidStyle; ?>" id="accbPyblsInvcInvcCur2"><?php echo $accbPyblsInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="accbPyblsInvcPaidAmnt" value="<?php
                                            echo number_format($accbPyblsInvcPaidAmnt, 2);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $accbPyblsInvcPaidStyle; ?>" onchange="fmtAsNumber('accbPyblsInvcPaidAmnt');" readonly="true"/>
                                            <label data-toggle="tooltip" title="History of Payments" class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbPymntsHstryForm(<?php echo $sbmtdAccbPyblsInvcID; ?>, 103, 'ReloadDialog',<?php echo $sbmtdAccbPyblsInvcID; ?>, 'Payable Invoice', 'Supplier Payments');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Outstanding Balance:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $accbPyblsInvcOustndngStyle; ?>" id="accbPyblsInvcInvcCur3"><?php echo $accbPyblsInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="accbPyblsInvcOustndngAmnt" value="<?php
                                            echo number_format($accbPyblsInvcOustndngAmnt, 2);
                                            ?>" 
                                                   style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $accbPyblsInvcOustndngStyle; ?>" onchange="fmtAsNumber('accbPyblsInvcOustndngAmnt');"  readonly="true"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Available Prepayment:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;" id="accbPyblsInvcInvcCur4"><?php echo $accbPyblsInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="accbPyblsInvcAppldAmnt" value="<?php
                                            echo number_format($accbPyblsInvcPaidAmnt - $accbPyblsInvcAppldAmnt, 2);
                                            ?>" 
                                                   style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbPyblsInvcAppldAmnt');"  readonly="true"/>
                                        </div>
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label for="accbPyblsInvcGLBatch" class="control-label col-md-4">GL Batch Name:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="accbPyblsInvcGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $accbPyblsInvcGLBatch; ?>" readonly="true"/>
                                            <input type="hidden" id="accbPyblsInvcGLBatchID" value="<?php echo $accbPyblsInvcGLBatchID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $accbPyblsInvcGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdAccbPyblsInvcID; ?>, 'Payable Invoice');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
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
                                    <li class="active"><a data-toggle="tabajxpyblsinvc" data-rhodata="" href="#pyblsInvcDetLines" id="pyblsInvcDetLinestab">Invoice Lines</a></li>
                                    <li class=""><a data-toggle="tabajxpyblsinvc" data-rhodata="" href="#pyblsInvcExtraInfo" id="pyblsInvcExtraInfotab">Extra Information</a></li>
                                </ul>  
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                $trsctnLnTxNm = "View Non-WHT Tax Codes";
                                                $trsctnLnWHTxNm = "View WHT Tax Codes";
                                                $trsctnLnDscntNm = "View Discounts";
                                                $nwRowHtml33 = "<tr id=\"oneAccbPyblsInvcSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAccbPyblsInvcSmryLinesTable tr').index(this));\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\" style=\"display:none;\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_ItemType\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("");
                                                $srchInsArrys = array("WWW_LINETYPE_WWW");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\"  style=\"\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_CodeBhndID\" value=\"-1\" style=\"width:100% !important;\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_InitAmntLnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_IsWHTax\" value=\"0\" style=\"width:100% !important;\">   
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_LineDesc\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow_WWW123WWW_LineDesc', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetDesc');\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getAccbPyblsInvcLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'oneAccbPyblsInvcSmryRow_WWW123WWW_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAccbPyblsInvcSmryRow_WWW123WWW_CodeBhndID', 'oneAccbPyblsInvcSmryRow_WWW123WWW_LineDesc', 'clear', 0, '', function(){getAccbPyblsCodeBhndInfo('oneAccbPyblsInvcSmryRow__WWW123WWW');});\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </td>                                                 
                                                        <td class=\"lovtd\"  style=\"display:none;\">
                                                            <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_RefDoc\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow_WWW123WWW_RefDoc', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetRfDc');\">                                                    
                                                        </td>                                          
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_TrnsCurNm\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_TrnsCurNm\" value=\"" . $accbPyblsInvcInvcCur . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file\" onclick=\"\">
                                                                    <span class=\"\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_TrnsCurNm1\">" . $accbPyblsInvcInvcCur . "</span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_EntrdAmt\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_EntrdAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow_WWW123WWW_EntrdAmt', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbPyblsInvcSmryTtl();\">                                                    
                                                        </td>     
                                                        <td class=\"lovtd\" style=\"display:none;\">
                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Amount Breakdown\" 
                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneAccbPyblsInvcSmryRow_WWW123WWW_EntrdAmt', 'oneAccbPyblsInvcSmryRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>  
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_TaxID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_TaxNm\" value=\"" . $trsctnLnTxNm . "\" style=\"width:100% !important;\"> 
                                                            <button id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_TaxBtn\" type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $trsctnLnTxNm . "\" 
                                                                    onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Non-WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAccbPyblsInvcSmryRow_WWW123WWW_TaxID', 'oneAccbPyblsInvcSmryRow_WWW123WWW_TaxNm', 'clear', 0, '', function () {
                                                                                                    changeBtnTitleFunc('oneAccbPyblsInvcSmryRow_WWW123WWW_TaxNm', 'oneAccbPyblsInvcSmryRow_WWW123WWW_TaxBtn');
                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/tax-icon420x500.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>  
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_WHTaxID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_WHTaxNm\" value=\"" . $trsctnLnWHTxNm . "\" style=\"width:100% !important;\">   
                                                            <button id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_WHTaxBtn\" type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $trsctnLnWHTxNm . "\" 
                                                                    onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAccbPyblsInvcSmryRow_WWW123WWW_WHTaxID', 'oneAccbPyblsInvcSmryRow_WWW123WWW_WHTaxNm', 'clear', 0, '', function () {
                                                                                                    changeBtnTitleFunc('oneAccbPyblsInvcSmryRow_WWW123WWW_WHTaxNm', 'oneAccbPyblsInvcSmryRow_WWW123WWW_WHTaxBtn');});\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/tg-tax-icon.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>   
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_DscntID\" value=\"-1\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_DscntNm\" value=\"" . $trsctnLnDscntNm . "\" style=\"width:100% !important;\">  
                                                            <button id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_DscntBtn\" type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $trsctnLnDscntNm . "\" 
                                                                    onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAccbPyblsInvcSmryRow_WWW123WWW_DscntID', 'oneAccbPyblsInvcSmryRow_WWW123WWW_DscntNm', 'clear', 0, '', function () {
                                                                                                    changeBtnTitleFunc('oneAccbPyblsInvcSmryRow_WWW123WWW_DscntNm', 'oneAccbPyblsInvcSmryRow_WWW123WWW_DscntBtn');
                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/dscnt_456356.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>
                                                        <td class=\"lovtd\" style=\"text-align: center;display:none;\">
                                                            <div class=\"form-group form-group-sm \">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_AutoCalc\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_AutoCalc\">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_IncrsDcrs1\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_AccountID1\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_AccountNm1\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_AccountNm1\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAccbPyblsInvcSmryRow_WWW123WWW_AccountID1', 'oneAccbPyblsInvcSmryRow_WWW123WWW_AccountNm1', 'clear', 1, '', function () {
                                                                changeElmntTitleFunc('oneAccbPyblsInvcSmryRow_WWW123WWW_AccountNm1');
                                                            });\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>                                           
                                                        </td>
                                                        <td class=\"lovtd\" style=\"display:none;\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetFuncRate\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_FuncExchgRate\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_FuncExchgRate\" value=\"1.0000\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow_WWW123WWW_FuncExchgRate', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetFuncRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbPyblsInvcSmryTtl();\">                                                    
                                                        </td> 
                                                        <!--<td class=\"lovtd\" style=\"\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_AcntExchgRate\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_AcntExchgRate\" value=\"1.0000\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow_WWW123WWW_AcntExchgRate', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbPyblsInvcSmryTtl();\">                                                    
                                                        </td>-->
                                                        <td class=\"lovtd\" style=\"\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPyblsInvcSmryRow_WWW123WWW_ApldDocNum\" name=\"oneAccbPyblsInvcSmryRow_WWW123WWW_ApldDocNum\" value=\"\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbPyblsInvcSmryTtl();\" readonly=\"true\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbPyblsInvcDetLn('oneAccbPyblsInvcSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";
                                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                                $nwRowHtml1 = str_replace("WWW_LINETYPE_WWW", "1Initial Amount", $nwRowHtml33);
                                                $nwRowHtml2 = str_replace("WWW_LINETYPE_WWW", "2Tax", $nwRowHtml33);
                                                $nwRowHtml3 = str_replace("WWW_LINETYPE_WWW", "3Discount", $nwRowHtml33);
                                                $nwRowHtml4 = str_replace("WWW_LINETYPE_WWW", "4Extra Charge", $nwRowHtml33);
                                                $nwRowHtml5 = str_replace("WWW_LINETYPE_WWW", "5Applied Prepayment", $nwRowHtml33);
                                                ?> 
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <button id="addNwAccbPyblsInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbPyblsInvcRows('oneAccbPyblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml1; ?>', '1Initial Amount');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <button id="addNwAccbPyblsInvcTaxBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbPyblsInvcRows('oneAccbPyblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml2; ?>', '2Tax');" data-toggle="tooltip" data-placement="bottom" title = "New Tax Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">Tax
                                                            </button> 
                                                            <button id="addNwAccbPyblsInvcDscntBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbPyblsInvcRows('oneAccbPyblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml3; ?>', '3Discount');" data-toggle="tooltip" data-placement="bottom" title = "New Discount Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">Discount
                                                            </button> 
                                                            <button id="addNwAccbPyblsInvcChrgBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbPyblsInvcRows('oneAccbPyblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml4; ?>', '4Extra Charge');" data-toggle="tooltip" data-placement="bottom" title = "New Extra Charge Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">Extra Charge
                                                            </button> 
                                                            <button id="addNwAccbPyblsInvcPrepayBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbPyblsInvcRows('oneAccbPyblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml5; ?>', '5Applied Prepayment');" data-toggle="tooltip" data-placement="bottom" title = "New Applied Prepayment Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">Prepayment
                                                            </button>                                 
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPyblsInvcDocsForm(<?php echo $sbmtdAccbPyblsInvcID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPyblsInvcForm(<?php echo $sbmtdAccbPyblsInvcID; ?>, 1, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdPyblsInvcValsTtlBtn"><?php echo $accbPyblsInvcInvcCur; ?> 
                                                                <?php
                                                                echo number_format($accbPyblsInvcTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdPyblsInvcValsTtlVal" value="<?php echo $accbPyblsInvcTtlAmnt; ?>">
                                                        </button>
                                                    </div> 
                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                            <?php
                                                            if ($rqStatus == "Not Validated") {
                                                                ?>
                                                                <?php if ($canEdt) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPyblsInvcForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                                <?php } ?>
                                                                <?php if ($canRvwApprvDocs) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPyblsInvcForm('<?php echo $fnccurnm; ?>', 2);" data-toggle="tooltip" data-placement="bottom" title="Approve Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Approve
                                                                    </button>
                                                                <?php } ?>
                                                                <?php
                                                            } else if ($rqStatus == "Approved") {
                                                                if ($canPayDocs === true && $accbPyblsInvcOustndngAmnt > 0) {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPayInvcForm(<?php echo $sbmtdAccbPyblsInvcID; ?>, 'Supplier Payments', 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Pay Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Pay Invoice
                                                                    </button>
                                                                <?php } ?>
                                                                <?php if ($cancelDocs) { ?>
                                                                    <button id="fnlzeRvrslAccbPyblsInvcBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPyblsInvcRvrslForm('<?php echo $fnccurnm; ?>', 1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Document&nbsp;</button>                                                                   
                                                                    <?php
                                                                }
                                                            }
                                                            if ($extraPKeyID > 0 && $extraPKeyType == "Payable Invoice") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPyblsInvcForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    Go Back&nbsp;
                                                                </button>                                                                   
                                                                <?php
                                                            } else if ($extraPKeyID > 0 && $extraPKeyType == "Receipt") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmCnsgnRcptForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    Go Back&nbsp;
                                                                </button>                                                                   
                                                                <?php
                                                            } else if ($extraPKeyID > 0 && $extraPKeyType == "Receipt Returns") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmCnsgnRtrnForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    Go Back&nbsp;
                                                                </button>                                                                   
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
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbPyblsInvcLnsTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="pyblsInvcDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneAccbPyblsInvcSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="max-width:90px;width:90px;text-align: center;display:none;">Item Type</th>
                                                                <th style="min-width:250px;">Item Description</th>
                                                                <th style="max-width:80px;width:80px;display:none;">Reference Doc. No.</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">CUR.</th>
                                                                <th style="max-width:90px;width:90px;text-align: right;">Amount (Less VAT)</th>
                                                                <th style="max-width:30px;width:30px;display:none;">...</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">TX</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">WHT</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">DC</th>
                                                                <th style="max-width:40px;width:40px;text-align: center;display:none;">Auto Calc</th>
                                                                <th style="max-width:70px;width:70px;text-align: center;">Incrs./ Dcrs.</th>
                                                                <th style="min-width:160px;">Charge Account</th>
                                                                <th style="max-width:80px;width:80px;display:none;">Functional Currency Exchange Rate</th>
                                                                <!--<th style="max-width:80px;">Account Currency Exchange Rate</th>-->
                                                                <th style="max-width:80px;width:80px;">Applied Prepayment Doc. No.</th>
                                                                <th style="max-width:30px;width:30px;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_PyblsInvcDocDet($sbmtdAccbPyblsInvcID);
                                                            $ttlTrsctnEntrdAmnt = 0;
                                                            $trnsBrkDwnVType = "VIEW";
                                                            if ($mkReadOnly == "") {
                                                                $trnsBrkDwnVType = "EDIT";
                                                            }
                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                $trsctnLineID = (float) $rowRw[0];
                                                                $trsctnInitAmntLnID = (float) $rowRw[21];
                                                                $trsctnLineType = $rowRw[1];
                                                                $trsctnLineDesc = $rowRw[2];
                                                                $trsctnLineRefDoc = $rowRw[22];
                                                                $entrdCurID = (int) $rowRw[11];
                                                                $entrdAmnt = (float) $rowRw[3];
                                                                $entrdCurNm = $rowRw[12];
                                                                $trsctnCodeBhndID = (int) $rowRw[4];
                                                                $shdAutoCalc = $rowRw[5];
                                                                $trnsIncrsDcrs1 = $rowRw[6];
                                                                $trsctnAcntID1 = $rowRw[7];
                                                                $trsctnAcntNm1 = $rowRw[23];

                                                                $trnsIncrsDcrs2 = $rowRw[8];
                                                                $trsctnAcntID2 = $rowRw[9];
                                                                $trsctnAcntNm2 = $rowRw[24];
                                                                $pyblsInvcSlctdAmtBrkdwns = $rowRw[25];
                                                                $trsctnLineApldDocNo = $rowRw[26];
                                                                $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;
                                                                $funcCrncyRate = (float) $rowRw[17];
                                                                $acntCrncyRate = (float) $rowRw[18];
                                                                $trsctnLnTxID = (int) $rowRw[28];
                                                                $trsctnLnWHTxID = (int) $rowRw[29];
                                                                $trsctnLnDscntID = (int) $rowRw[30];
                                                                $trsctn_IsWHTax = $rowRw[27];
                                                                $trsctnLnTxNm = ($trsctnLnTxID > 0) ? $rowRw[31] : "View Non-WHT Tax Codes";
                                                                $trsctnLnWHTxNm = ($trsctnLnWHTxID > 0) ? $rowRw[32] : "View WHT Tax Codes";
                                                                $trsctnLnDscntNm = ($trsctnLnDscntID > 0) ? $rowRw[33] : "View Discounts";
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneAccbPyblsInvcSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAccbPyblsInvcSmryLinesTable tr').index(this));">                                    
                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>       
                                                                    <td class="lovtd" style="display:none;">
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_ItemType" style="width:100% !important;">
                                                                            <?php
                                                                            $valslctdArry = array("", "", "", "", "");
                                                                            $srchInsArrys = array("1Initial Amount", "2Tax", "3Discount",
                                                                                "4Extra Charge", "5Applied Prepayment");
                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                if ($trsctnLineType == $srchInsArrys[$z]) {
                                                                                    $valslctdArry[$z] = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>                                           
                                                                    <td class="lovtd"  style="">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $trsctnCodeBhndID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_InitAmntLnID" value="<?php echo $trsctnInitAmntLnID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_IsWHTax" value="<?php echo $trsctn_IsWHTax; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="<?php echo $pyblsInvcSlctdAmtBrkdwns; ?>" style="width:100% !important;"> 
                                                                        <?php
                                                                        if ($canEdt === true && $shdAutoCalc != "1") {
                                                                            ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetDesc');">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPyblsInvcLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnCodeBhndID; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_CodeBhndID', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'clear', 1, '', function () {
                                                                                            getAccbPyblsCodeBhndInfo('oneAccbPyblsInvcSmryRow_<?php echo $cntr; ?>');
                                                                                        });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <input type="text" class="form-control jbDetDesc" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" readonly="true"/>
                                                                            <!--<span><?php echo $trsctnLineDesc; ?></span>-->
                                                                        <?php } ?>
                                                                    </td>                                                 
                                                                    <td class="lovtd" style="display:none;">
                                                                        <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_RefDoc" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_RefDoc', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetRfDc');">                                                    
                                                                    </td>                                          
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <div class="" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="">
                                                                                <span class="" id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        $isReadonly = $mkReadOnly;
                                                                        $isReadonlyCls = "form-control rqrdFld jbDetDbt";
                                                                        if ($shdAutoCalc == "1") {
                                                                            $isReadonly = "readonly=\"true\"";
                                                                            $isReadonlyCls = "form-control jbDetDbt";
                                                                        }
                                                                        ?>
                                                                        <input type="text" class="<?php echo $isReadonlyCls; ?>" <?php echo $isReadonly; ?> aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                                                        echo number_format($entrdAmnt, 2);
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" onchange="calcAllAccbPyblsInvcSmryTtl();">                                                    
                                                                    </td>   
                                                                    <td class="lovtd" style="display:none;">
                                                                        <?php if ($trsctnLineType == "1Initial Amount") {
                                                                            ?>
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Amount Breakdown" 
                                                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <span>&nbsp;</span>
                                                                        <?php } ?>
                                                                    </td>     
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxNm" value="<?php echo $trsctnLnTxNm; ?>" style="width:100% !important;"> 

                                                                        <?php
                                                                        if ($trsctnLineType == "1Initial Amount") {
                                                                            ?>
                                                                            <button id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnTxNm; ?>" 
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Non-WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxID', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'clear', 0, '', function () {
                                                                                                changeBtnTitleFunc('oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxBtn');
                                                                                            });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <span>&nbsp;</span>
                                                                        <?php } ?>
                                                                    </td>  
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxID" value="<?php echo $trsctnLnWHTxID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm" value="<?php echo $trsctnLnWHTxNm; ?>" style="width:100% !important;">   

                                                                        <?php if ($trsctnLineType == "1Initial Amount") {
                                                                            ?>
                                                                            <button id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnWHTxNm; ?>" 
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnWHTxID; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxID', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm', 'clear', 0, '', function () {
                                                                                                changeBtnTitleFunc('oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxBtn');
                                                                                            });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/tg-tax-icon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <span>&nbsp;</span>
                                                                        <?php } ?>
                                                                    </td>   
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntNm" value="<?php echo $trsctnLnDscntNm; ?>" style="width:100% !important;">  

                                                                        <?php if ($trsctnLineType == "1Initial Amount") {
                                                                            ?>
                                                                            <button id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnDscntNm; ?>" 
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntID', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'clear', 0, '', function () {
                                                                                                changeBtnTitleFunc('oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntBtn');
                                                                                            });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <span>&nbsp;</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;display:none;">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        if ($shdAutoCalc == "1") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <div class="form-group form-group-sm ">
                                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                                <label class="form-check-label">
                                                                                    <input type="checkbox" class="form-check-input" id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AutoCalc" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AutoCalc" <?php echo $isChkd ?>>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </td>    
                                                                    <td class="lovtd">
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
                                                                            <?php
                                                                            $valslctdArry = array("", "");
                                                                            $srchInsArrys = array("Increase", "Decrease");
                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                if ($trnsIncrsDcrs1 == $srchInsArrys[$z]) {
                                                                                    $valslctdArry[$z] = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountID1', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {
                                                                                            changeElmntTitleFunc('oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1');
                                                                                        });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>    
                                                                        <?php } else { ?>
                                                                            <span><?php echo $trsctnAcntNm1; ?></span>
                                                                        <?php } ?>                                             
                                                                    </td>
                                                                    <td class="lovtd"  style="display:none;">
                                                                        <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                                                        echo number_format($funcCrncyRate, 4);
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbPyblsInvcSmryTtl();">                                                    
                                                                    </td> 
                                                                    <!--<td class="lovtd" style="">
                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AcntExchgRate" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AcntExchgRate" value="<?php
                                                                    echo number_format($acntCrncyRate, 4);
                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AcntExchgRate', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbPyblsInvcSmryTtl();">                                                    
                                                                    </td>-->
                                                                    <td class="lovtd" style="">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_ApldDocNum" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_ApldDocNum" value="<?php
                                                                        echo $trsctnLineApldDocNo;
                                                                        ?>" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllAccbPyblsInvcSmryTtl();">                                                    
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbPyblsInvcDetLn('oneAccbPyblsInvcSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Payables Invoice Line">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>                                                            
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th style="display:none;">&nbsp;</th>
                                                                <th>TOTALS:</th>
                                                                <th style="display:none;">&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPIJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdPIJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                                </th>
                                                                <th style="display:none;">&nbsp;</th>                                           
                                                                <th style="">&nbsp;</th>                                           
                                                                <th style="">&nbsp;</th>                                           
                                                                <th style="">&nbsp;</th>                                           
                                                                <th  style="display:none;">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="display:none;">&nbsp;</th>
                                                                <!--<th style="">&nbsp;</th>-->
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="pyblsInvcExtraInfo" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row"  style="padding:0px 15px 0px 15px;">
                                                <div class="col-md-4" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 0px !important;">                             
                                                    <div class="form-group">
                                                        <label for="accbPyblsInvcDfltBalsAcnt" class="control-label col-md-4">Liability Account:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" id="accbPyblsInvcDfltBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $accbPyblsInvcDfltBalsAcnt; ?>" readonly="true"/>
                                                                <input type="hidden" id="accbPyblsInvcDfltBalsAcntID" value="<?php echo $accbPyblsInvcDfltBalsAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbPyblsInvcDfltBalsAcntID', 'accbPyblsInvcDfltBalsAcnt', 'clear', 1, '', function () {});">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Payment Terms:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group"  style="width:100%;">
                                                                <textarea class="form-control" rows="2" cols="20" id="accbPyblsInvcPayTerms" name="accbPyblsInvcPayTerms" style="text-align:left !important;" <?php echo $mkReadOnly; ?>><?php echo $accbPyblsInvcPayTerms; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbPyblsInvcPayTerms');" style="max-width:30px;width:30px;">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">1st Cheque No.:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" aria-label="..." id="firtsChequeNum" name="firtsChequeNum" value="<?php echo $firtsChequeNum; ?>" <?php echo $mkReadOnly; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Next Payment Amount:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                                    <span class="" style="font-size: 20px !important;" id="accbPyblsInvcInvcCur5"><?php echo $accbPyblsInvcInvcCur; ?></span>
                                                                </label>
                                                                <input class="form-control" type="text" id="nextPartPayment" value="<?php
                                                                echo number_format($nextPartPayment, 2);
                                                                ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('nextPartPayment');" <?php echo $mkReadOnly; ?>/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Linked Document:</label>
                                                        </div>
                                                        <div class="col-md-8" style="padding:0px 0px 0px 0px;">
                                                            <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbPyblsInvcEvntDocTyp" style="width:100% !important;" onchange="lnkdEvntAccbPyblsInvcChng();">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "");
                                                                    $srchInsArrys = array("None", "Attendance Register", "Production Process Run",
                                                                        "Customer File Number",
                                                                        "Project Management");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($accbPyblsInvcEvntDocTyp == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-7" style="padding:0px 15px 0px 1px;">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="accbPyblsInvcEvntCtgry" name="accbPyblsInvcEvntCtgry" value="<?php echo $accbPyblsInvcEvntCtgry; ?>" readonly="true">
                                                                    <label id="accbPyblsInvcEvntCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbPILovCtgry('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', '', '', '', 'radio', true, '', 'accbPyblsInvcEvntCtgry', 'accbPyblsInvcEvntCtgry', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="padding:2px 15px 0px 15px;">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbPyblsInvcEvntRgstr" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Linked Document Number" type = "text" min="0" placeholder="" value="<?php echo $accbPyblsInvcEvntRgstr; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbPyblsInvcEvntRgstrID" value="<?php echo $accbPyblsInvcEvntRgstrID; ?>">
                                                                    <label id="accbPyblsInvcEvntRgstrLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbPILovEvnt('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbPyblsInvcEvntRgstrID', 'accbPyblsInvcEvntRgstr', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="padding: 0px 1px 0px 1px;">
                                                    <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 2px 5px 2px !important;margin-left:3px !important;">
                                                        <table class="table table-striped table-bordered table-responsive" id="oneAccbPyblsInvcExtrInfTable" cellspacing="0" width="100%" style="width:100%;min-width: 200px !important;">
                                                            <caption class="basic_person_lg" style="color:black !important;font-weight:bold;">EXTRA INFORMATION VALUES</caption>
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:30px;width:30px;">No.</th>
                                                                    <th style="">Extra Info Category</th>
                                                                    <th>Extra Info Label</th>
                                                                    <th style="min-width:300px;">Value</th><?php
                                                                    if ($canVwRcHstry === true) {
                                                                        ?>
                                                                        <th style="max-width:30px;width:30px;">...</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>    
                                                                <?php
                                                                $cntr = 0;
                                                                $vwSQLStmnt = "";
                                                                $resultRw = getAllwdExtInfosNVals("%", "Extra Info Label", 0, 10000000,
                                                                        $vwSQLStmnt, getMdlGrpID("Payable Invoices", $mdlNm),
                                                                        $sbmtdAccbPyblsInvcID, "accb.accb_all_other_info_table");
                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $extrInfoCtgry = $rowRw[0];
                                                                    $extrInfoLbl = $rowRw[1];
                                                                    $extrInfoVal = $rowRw[2];
                                                                    $cmbntnID = (float) $rowRw[3];
                                                                    $tableID = (float) $rowRw[4];
                                                                    $dfltRowID = (float) $rowRw[5];
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneAccbPyblsInvcExtrInfRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                        <td class="lovtd"  style="">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcExtrInfRow<?php echo $cntr; ?>_DfltRowID" value="<?php echo $dfltRowID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcExtrInfRow<?php echo $cntr; ?>_CombntnID" value="<?php echo $cmbntnID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcExtrInfRow<?php echo $cntr; ?>_TableID" value="<?php echo $tableID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcExtrInfRow<?php echo $cntr; ?>_extrInfoCtgry" value="<?php echo $extrInfoCtgry; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcExtrInfRow<?php echo $cntr; ?>_extrInfoLbl" value="<?php echo $extrInfoLbl; ?>" style="width:100% !important;"> 
                                                                            <span><?php echo $extrInfoCtgry; ?></span>                                                    
                                                                        </td>                                                
                                                                        <td class="lovtd"  style="">
                                                                            <span><?php echo $extrInfoCtgry; ?></span>                                                    
                                                                        </td>                                                 
                                                                        <td class="lovtd"  style="">
                                                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbPyblsInvcExtrInfRow<?php echo $cntr; ?>_Value" name="oneAccbPyblsInvcExtrInfRow<?php echo $cntr; ?>_Value" value="<?php echo $extrInfoVal; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcExtrInfRow<?php echo $cntr; ?>_Value', 'oneAccbPyblsInvcExtrInfTable', 'jbDetRfDc');">                                                    
                                                                        </td>
                                                                        <?php
                                                                        if ($canVwRcHstry === true) {
                                                                            ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                echo urlencode(encrypt1(($dfltRowID . "|accb.accb_all_other_info_table|dflt_row_id"),
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
                                                    </fieldset>
                                                </div>
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
                $sbmtdTempltLovID = isset($_POST['sbmtdTempltLovID']) ? cleanInputData($_POST['sbmtdTempltLovID']) : "";
                $accbPyblsInvcInvcCur = isset($_POST['accbPyblsInvcInvcCur']) ? cleanInputData($_POST['accbPyblsInvcInvcCur']) : $fnccurnm;
                $accbPyblsInvcInvcCurID = getPssblValID($accbPyblsInvcInvcCur, getLovID("Currencies"));
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneAccbPyblsInvcSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                            <thead>
                                <tr>
                                    <th style="max-width:30px;width:30px;">No.</th>
                                    <th style="max-width:90px;width:90px;text-align: center;display:none;">Item Type</th>
                                    <th style="min-width:250px;">Item Description</th>
                                    <th style="max-width:80px;width:80px;display:none;">Reference Doc. No.</th>
                                    <th style="max-width:30px;width:30px;text-align: center;">CUR.</th>
                                    <th style="max-width:90px;width:90px;text-align: right;">Amount (Less VAT)</th>
                                    <th style="max-width:30px;width:30px;display:none;">...</th>
                                    <th style="max-width:30px;width:30px;text-align: center;">TX</th>
                                    <th style="max-width:30px;width:30px;text-align: center;">WHT</th>
                                    <th style="max-width:30px;width:30px;text-align: center;">DC</th>
                                    <th style="max-width:40px;width:40px;text-align: center;display:none;">Auto Calc</th>
                                    <th style="max-width:70px;width:70px;text-align: center;">Incrs./ Dcrs.</th>
                                    <th style="min-width:160px;">Charge Account</th>
                                    <th style="max-width:80px;width:80px;display:none;">Functional Currency Exchange Rate</th>
                                    <!--<th style="max-width:80px;">Account Currency Exchange Rate</th>-->
                                    <th style="max-width:80px;width:80px;">Applied Prepayment Doc. No.</th>
                                    <th style="max-width:30px;width:30px;">...</th>
                                </tr>
                            </thead>
                            <tbody>   
                                <?php
                                $cntr = 0;
                                $resultRw = get_DocTmpltsDet($sbmtdTempltLovID);
                                $ttlTrsctnEntrdAmnt = 0;
                                $trnsBrkDwnVType = "VIEW";
                                if ($mkReadOnly == "") {
                                    $trnsBrkDwnVType = "EDIT";
                                }
                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                    $trsctnLineID = -1;
                                    $trsctnInitAmntLnID = -1;
                                    $trsctnLineType = $rowRw[1];
                                    $trsctnLineDesc = $rowRw[2];
                                    $trsctnLineRefDoc = "";
                                    $entrdCurID = $accbPyblsInvcInvcCurID;
                                    $entrdAmnt = 0.00;
                                    $entrdCurNm = $accbPyblsInvcInvcCur;
                                    $trsctnCodeBhndID = (int) $rowRw[7];
                                    $shdAutoCalc = $rowRw[6];
                                    $trnsIncrsDcrs1 = $rowRw[3];
                                    $trsctnAcntID1 = $rowRw[4];
                                    $trsctnAcntNm1 = $rowRw[5];

                                    $trnsIncrsDcrs2 = "";
                                    $trsctnAcntID2 = -1;
                                    $trsctnAcntNm2 = "";
                                    $pyblsInvcSlctdAmtBrkdwns = "";
                                    $trsctnLineApldDocNo = "";
                                    $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;
                                    $funcCrncyRate = 1.0000;
                                    $acntCrncyRate = 1.0000;
                                    $trsctnLnTxID = -1;
                                    $trsctnLnWHTxID = -1;
                                    $trsctnLnDscntID = -1;
                                    $trsctn_IsWHTax = $rowRw[8];
                                    $trsctnLnTxNm = "View Non-WHT Tax Codes";
                                    $trsctnLnWHTxNm = "View WHT Tax Codes";
                                    $trsctnLnDscntNm = "View Discounts";
                                    $cntr += 1;
                                    ?>
                                    <tr id="oneAccbPyblsInvcSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAccbPyblsInvcSmryLinesTable tr').index(this));">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>       
                                        <td class="lovtd" style="display:none;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_ItemType" style="width:100% !important;">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "");
                                                $srchInsArrys = array("1Initial Amount", "2Tax", "3Discount", "4Extra Charge",
                                                    "5Applied Prepayment");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($trsctnLineType == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>                                           
                                        <td class="lovtd"  style="">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $trsctnCodeBhndID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_InitAmntLnID" value="<?php echo $trsctnInitAmntLnID; ?>" style="width:100% !important;">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_IsWHTax" value="<?php echo $trsctn_IsWHTax; ?>" style="width:100% !important;">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="<?php echo $pyblsInvcSlctdAmtBrkdwns; ?>" style="width:100% !important;"> 
                                            <?php
                                            if ($canEdt === true && $shdAutoCalc != "1") {
                                                ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetDesc');">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPyblsInvcLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnCodeBhndID; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_CodeBhndID', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'clear', 1, '', function () {
                                                                getAccbPyblsCodeBhndInfo('oneAccbPyblsInvcSmryRow_<?php echo $cntr; ?>');
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <input type="text" class="form-control jbDetDesc" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" readonly="true"/>
                                                <!--<span><?php echo $trsctnLineDesc; ?></span>-->
                                            <?php } ?>
                                        </td>                                                 
                                        <td class="lovtd" style="display:none;">
                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_RefDoc" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_RefDoc', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetRfDc');">                                                    
                                        </td>                                          
                                        <td class="lovtd" style="text-align: center;">
                                            <div class="" style="width:100% !important;">
                                                <label class="btn btn-primary btn-file" onclick="">
                                                    <span class="" id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                </label>
                                            </div>                                              
                                        </td>
                                        <td class="lovtd">
                                            <?php
                                            $isReadonly = $mkReadOnly;
                                            $isReadonlyCls = "form-control rqrdFld jbDetDbt";
                                            if ($shdAutoCalc == "1") {
                                                $isReadonly = "readonly=\"true\"";
                                                $isReadonlyCls = "form-control jbDetDbt";
                                            }
                                            ?>
                                            <input type="text" class="<?php echo $isReadonlyCls; ?>" <?php echo $isReadonly; ?> aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                            echo number_format($entrdAmnt, 2);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" onchange="calcAllAccbPyblsInvcSmryTtl();">                                                    
                                        </td>   
                                        <td class="lovtd" style="display:none;">
                                            <?php if ($trsctnLineType == "1Initial Amount") { ?>
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Amount Breakdown" 
                                                        onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;"> 
                                                    <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                </button>
                                            <?php } else { ?>
                                                <span>&nbsp;</span>
                                            <?php } ?>
                                        </td>     
                                        <td class="lovtd" style="text-align: center;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxNm" value="<?php echo $trsctnLnTxNm; ?>" style="width:100% !important;"> 

                                            <?php if ($trsctnLineType == "1Initial Amount") { ?>
                                                <button id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnTxNm; ?>" 
                                                        onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Non-WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxID', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'clear', 0, '', function () {
                                                                    changeBtnTitleFunc('oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_TaxBtn');
                                                                });" style="padding:2px !important;"> 
                                                    <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                </button>
                                            <?php } else { ?>
                                                <span>&nbsp;</span>
                                            <?php } ?>
                                        </td>  
                                        <td class="lovtd" style="text-align: center;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxID" value="<?php echo $trsctnLnWHTxID; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm" value="<?php echo $trsctnLnWHTxNm; ?>" style="width:100% !important;">   

                                            <?php if ($trsctnLineType == "1Initial Amount") { ?>
                                                <button id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnWHTxNm; ?>" 
                                                        onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnWHTxID; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxID', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm', 'clear', 0, '', function () {
                                                                    changeBtnTitleFunc('oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_WHTaxBtn');
                                                                });" style="padding:2px !important;"> 
                                                    <img src="cmn_images/tg-tax-icon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                </button>
                                            <?php } else { ?>
                                                <span>&nbsp;</span>
                                            <?php } ?>
                                        </td>   
                                        <td class="lovtd" style="text-align: center;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntNm" value="<?php echo $trsctnLnDscntNm; ?>" style="width:100% !important;">  

                                            <?php if ($trsctnLineType == "1Initial Amount") { ?>
                                                <button id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnDscntNm; ?>" 
                                                        onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntID', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'clear', 0, '', function () {
                                                                    changeBtnTitleFunc('oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_DscntBtn');
                                                                });" style="padding:2px !important;"> 
                                                    <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                </button>
                                            <?php } else { ?>
                                                <span>&nbsp;</span>
                                            <?php } ?>
                                        </td>
                                        <td class="lovtd" style="text-align: center;display:none;">
                                            <?php
                                            $isChkd = "";
                                            if ($shdAutoCalc == "1") {
                                                $isChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <div class="form-group form-group-sm ">
                                                <div class="form-check" style="font-size: 12px !important;">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AutoCalc" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AutoCalc" <?php echo $isChkd ?>>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>    
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
                                                <?php
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($trnsIncrsDcrs1 == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                            <?php
                                            if ($canEdt === true) {
                                                ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountID1', 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {
                                                                changeElmntTitleFunc('oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1');
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm1; ?></span>
                                            <?php } ?>                                             
                                        </td>
                                        <td class="lovtd"  style="display:none;">
                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                            echo number_format($funcCrncyRate, 4);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbPyblsInvcSmryTtl();">                                                    
                                        </td> 
                                        <!--<td class="lovtd" style="">
                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AcntExchgRate" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AcntExchgRate" value="<?php
                                        echo number_format($acntCrncyRate, 4);
                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_AcntExchgRate', 'oneAccbPyblsInvcSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbPyblsInvcSmryTtl();">                                                    
                                        </td>-->
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control" aria-label="..." id="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_ApldDocNum" name="oneAccbPyblsInvcSmryRow<?php echo $cntr; ?>_ApldDocNum" value="<?php
                                            echo $trsctnLineApldDocNo;
                                            ?>" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllAccbPyblsInvcSmryTtl();">                                                    
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbPyblsInvcDetLn('oneAccbPyblsInvcSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Payables Invoice Line">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>                                                            
                                <tr>
                                    <th>&nbsp;</th>
                                    <th style="display:none;">&nbsp;</th>
                                    <th>TOTALS:</th>
                                    <th style="display:none;">&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPIJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                2, '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdPIJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                    </th>
                                    <th style="display:none;">&nbsp;</th>                                           
                                    <th style="">&nbsp;</th>                                           
                                    <th style="">&nbsp;</th>                                           
                                    <th style="">&nbsp;</th>                                           
                                    <th  style="display:none;">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="display:none;">&nbsp;</th>
                                    <!--<th style="">&nbsp;</th>-->
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 5) {
                //Get Selected Code ID Behind Information
                header("content-type:application/json");
                $sbmtdAccbPyblsInvcID = isset($_POST['sbmtdAccbPyblsInvcID']) ? (float) cleanInputData($_POST['sbmtdAccbPyblsInvcID']) : -1;
                $accbPyblsInvcSpplrID = isset($_POST['accbPyblsInvcSpplrID']) ? (float) cleanInputData($_POST['accbPyblsInvcSpplrID']) : -1;
                $accbPyblsInvcSpplrSiteID = isset($_POST['accbPyblsInvcSpplrSiteID']) ? (float) cleanInputData($_POST['accbPyblsInvcSpplrSiteID'])
                            : -1;
                $accbPyblsInvcInvcCurID = isset($_POST['accbPyblsInvcInvcCurID']) ? (float) cleanInputData($_POST['accbPyblsInvcInvcCurID'])
                            : -1;
                $accbPyblsInvcVchType = isset($_POST['accbPyblsInvcVchType']) ? cleanInputData($_POST['accbPyblsInvcVchType']) : "";
                $lineDesc = isset($_POST['accbPyblsInvcDesc']) ? cleanInputData($_POST['accbPyblsInvcDesc']) : "";
                $ln_CodeBhndID = isset($_POST['ln_CodeBhndID']) ? (float) cleanInputData($_POST['ln_CodeBhndID']) : -1;
                $ln_ItemType = isset($_POST['ln_ItemType']) ? (float) cleanInputData($_POST['ln_ItemType']) : "";
                $ttlInitAmount = isset($_POST['ttlInitAmount']) ? (float) cleanInputData($_POST['ttlInitAmount']) : 0.00;
                $ttlDscntAmount = isset($_POST['ttlDscntAmount']) ? (float) cleanInputData($_POST['ttlDscntAmount']) : 0.00;

                $txsmmryNm = "";
                $codeAmnt = 0.00;
                $accnts = array("Increase", /* Balancing Account */ "-1", "Increase", /* Charge Account */ "-1");
                $txlineDesc = "";

                if ($accbPyblsInvcSpplrSiteID > 0) {
                    $accbPyblsInvcSpplrSiteID = get_CstmrSpplrSiteLnkID($accbPyblsInvcSpplrID, $accbPyblsInvcSpplrSiteID);
                    if ($accbPyblsInvcSpplrSiteID <= 0) {
                        $accbPyblsInvcSpplrSiteID = get_DfltCstmrSpplrSiteID($accbPyblsInvcSpplrID);
                    }
                } else {
                    $accbPyblsInvcSpplrSiteID = get_DfltCstmrSpplrSiteID($accbPyblsInvcSpplrID);
                }
                if ($ln_ItemType == "2Tax") {
                    $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_CodeBhndID);
                    $codeAmnt = getCodeAmnt($ln_CodeBhndID, $ttlInitAmount - $ttlDscntAmount);
                    $accnts = getPyblBalncnAccnt("2Tax", $ln_CodeBhndID, $accbPyblsInvcSpplrID, -1, $accbPyblsInvcVchType);
                    $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $ttlInitAmount . ")";
                } else if ($ln_ItemType == "3Discount" || $ln_ItemType == "4Extra Charge") {
                    $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_CodeBhndID);
                    $codeAmnt = getCodeAmnt($ln_CodeBhndID, $ttlInitAmount);
                    $accnts = getPyblBalncnAccnt($ln_ItemType, $ln_CodeBhndID, $accbPyblsInvcSpplrID, -1, $accbPyblsInvcVchType);
                    $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $ttlInitAmount . ")";
                } else if ($ln_ItemType == "5Applied Prepayment") {
                    $txsmmryNm = getGnrlRecNm("accb.accb_pybls_invc_hdr", "pybls_invc_hdr_id", "pybls_invc_number", $ln_CodeBhndID);
                    $codeAmnt = (float) getGnrlRecNm("accb.accb_pybls_invc_hdr", "pybls_invc_hdr_id", "amnt_paid-invc_amnt_appld_elswhr",
                                    $ln_CodeBhndID);
                    $accnts = getPyblBalncnAccnt($ln_ItemType, -1, $accbPyblsInvcSpplrID, $ln_CodeBhndID, $accbPyblsInvcVchType);
                    $txlineDesc = "Application of Prepayment Doc. No. " . $txsmmryNm . " on " . $lineDesc . " (" . $ttlInitAmount . ")";
                } else {
                    $accnts = getPyblBalncnAccnt("", -1, $accbPyblsInvcSpplrID, -1, $accbPyblsInvcVchType);
                }
                $errMsg = "";
                $arr_content['txlineDesc'] = $txlineDesc;
                $arr_content['codeAmnt'] = $codeAmnt;
                $arr_content['txsmmryNm'] = $txsmmryNm;
                $arr_content['BalsAcntIncsDcrs'] = $accnts[0];
                $arr_content['BalsAcntID'] = $accnts[1];
                $arr_content['BalsAcntNm'] = getAccntNum($accnts[1]) . "." . getAccntName($accnts[1]);
                $arr_content['CostAcntIncsDcrs'] = $accnts[2];
                $arr_content['CostAcntID'] = $accnts[3];
                $arr_content['CostAcntNm'] = getAccntNum($accnts[3]) . "." . getAccntName($accnts[3]);
                $arr_content['accbPyblsInvcSpplrSiteID'] = $accbPyblsInvcSpplrSiteID;
                $arr_content['accbPyblsInvcSpplrSiteNm'] = getCstmrSiteNm($accbPyblsInvcSpplrSiteID, $accbPyblsInvcSpplrID);
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdAccbPyblsInvcID = isset($_POST['sbmtdAccbPyblsInvcID']) ? cleanInputData($_POST['sbmtdAccbPyblsInvcID']) : -1;
                if (!$canAdd || ($sbmtdAccbPyblsInvcID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdAccbPyblsInvcID;
                $total = get_Total_PyblsInvc_Attachments($srchFor, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_PyblsInvc_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>       
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdPyblsInvcDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdPyblsInvcDocsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                    . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdPyblsInvcDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdPyblsInvcDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdPyblsInvcDocsRow_WWW123WWW_DocCtgryNm', 'attchdPyblsInvcDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdPyblsInvcDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToPyblsInvcDocs('attchdPyblsInvcDocsRow_WWW123WWW_DocFile','attchdPyblsInvcDocsRow_WWW123WWW_AttchdDocsID','attchdPyblsInvcDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdPyblsInvcDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdPyblsInvcDoc('attchdPyblsInvcDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdPyblsInvcDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdPyblsInvcDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttchdPyblsInvcDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPyblsInvcID=<?php echo $sbmtdAccbPyblsInvcID; ?>', 'ReloadDialog');">
                                    <input id="attchdPyblsInvcDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdPyblsInvcDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPyblsInvcID=<?php echo $sbmtdAccbPyblsInvcID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdPyblsInvcDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPyblsInvcID=<?php echo $sbmtdAccbPyblsInvcID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdPyblsInvcDocsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdPyblsInvcDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPyblsInvcID=<?php echo $sbmtdAccbPyblsInvcID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdPyblsInvcDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPyblsInvcID=<?php echo $sbmtdAccbPyblsInvcID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdPyblsInvcDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Doc. Name/Description</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            $doc_src = $ftp_base_db_fldr . "/PyblDocs/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                            ?>
                                            <tr id="attchdPyblsInvcDocsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdPyblsInvcDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($doc_src_encrpt == "None") {
                                                        ?>
                                                        <span style="font-weight: bold;color:#FF0000;">
                                                            <?php
                                                            echo "File Not Found!";
                                                            ?>
                                                        </span>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $doc_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="Download Document">
                                                            <img src="cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdPyblsInvcDoc('attchdPyblsInvcDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
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
                </fieldset>         
                <?php
            }
        }
    }
}
?>