<?php
/* echo str_replace("index.php", "", rhoUrl()) . "<br/>";
  echo rhoBaseUrl(); */
$accbRcvblsPrmSnsRstl = getAccbRcvblsPrmssns($orgID);
$addRecsCSP = ($accbRcvblsPrmSnsRstl[0] >= 1) ? true : false;
$editRecsCSP = ($accbRcvblsPrmSnsRstl[1] >= 1) ? true : false;
$delRecsCSP = ($accbRcvblsPrmSnsRstl[2] >= 1) ? true : false;
$addRecsCAP = ($accbRcvblsPrmSnsRstl[3] >= 1) ? true : false;
$editRecsCAP = ($accbRcvblsPrmSnsRstl[4] >= 1) ? true : false;
$delRecsCAP = ($accbRcvblsPrmSnsRstl[5] >= 1) ? true : false;
$addRecsDRTC = ($accbRcvblsPrmSnsRstl[6] >= 1) ? true : false;
$editRecsDRTC = ($accbRcvblsPrmSnsRstl[7] >= 1) ? true : false;
$delRecsDRTC = ($accbRcvblsPrmSnsRstl[8] >= 1) ? true : false;
$addRecsCCMIT = ($accbRcvblsPrmSnsRstl[9] >= 1) ? true : false;
$editRecsCCMIT = ($accbRcvblsPrmSnsRstl[10] >= 1) ? true : false;
$delRecsCCMIT = ($accbRcvblsPrmSnsRstl[11] >= 1) ? true : false;
$addRecsDTFC = ($accbRcvblsPrmSnsRstl[12] >= 1) ? true : false;
$editRecsDTFC = ($accbRcvblsPrmSnsRstl[13] >= 1) ? true : false;
$delRecsDTFC = ($accbRcvblsPrmSnsRstl[14] >= 1) ? true : false;
$addRecsCDMIR = ($accbRcvblsPrmSnsRstl[15] >= 1) ? true : false;
$editRecsCDMIR = ($accbRcvblsPrmSnsRstl[16] >= 1) ? true : false;
$delRecsCDMIR = ($accbRcvblsPrmSnsRstl[17] >= 1) ? true : false;
$canRvwApprvDocs = ($accbRcvblsPrmSnsRstl[18] >= 1) ? true : false;
$canPayDocs = ($accbRcvblsPrmSnsRstl[19] >= 1) ? true : false;
$cancelDocs = ($accbRcvblsPrmSnsRstl[20] >= 1) ? true : false;

$canAdd = $addRecsCSP || $addRecsCAP || $addRecsDRTC || $addRecsCCMIT || $addRecsDTFC || $addRecsCDMIR;
$canEdt = $editRecsCSP || $editRecsCAP || $editRecsDRTC || $editRecsCCMIT || $editRecsDTFC || $editRecsCDMIR;
$canDel = $delRecsCSP || $delRecsCAP || $delRecsDRTC || $delRecsCCMIT || $delRecsDTFC || $delRecsCDMIR;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";

$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canViewRcvbls === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Petty Cash Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteRcvblsDocHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Petty Cash Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteRcvblsDocDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdt) {
                    echo deleteRcvblsInvcDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Receivables Invoice Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdAccbRcvblsInvcID = isset($_POST['sbmtdAccbRcvblsInvcID']) ? (float) cleanInputData($_POST['sbmtdAccbRcvblsInvcID']) : -1;
                $accbRcvblsInvcDocNum = isset($_POST['accbRcvblsInvcDocNum']) ? cleanInputData($_POST['accbRcvblsInvcDocNum']) : "";
                $accbRcvblsInvcDfltTrnsDte = isset($_POST['accbRcvblsInvcDfltTrnsDte']) ? cleanInputData($_POST['accbRcvblsInvcDfltTrnsDte']) : '';
                $accbRcvblsInvcVchType = isset($_POST['accbRcvblsInvcVchType']) ? cleanInputData($_POST['accbRcvblsInvcVchType']) : '';
                $accbRcvblsInvcInvcCur = isset($_POST['accbRcvblsInvcInvcCur']) ? cleanInputData($_POST['accbRcvblsInvcInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $accbRcvblsInvcInvcCurID = getPssblValID($accbRcvblsInvcInvcCur, $curLovID);
                $accbRcvblsInvcTtlAmnt = isset($_POST['accbRcvblsInvcTtlAmnt']) ? (float) cleanInputData($_POST['accbRcvblsInvcTtlAmnt']) : 0;
                $accbRcvblsInvcFuncCrncyRate = isset($_POST['accbRcvblsInvcFuncCrncyRate']) ? (float) cleanInputData($_POST['accbRcvblsInvcFuncCrncyRate']) : 1.0000;

                $funcExchRate = round(get_LtstExchRate($accbRcvblsInvcInvcCurID, $fnccurid, $accbRcvblsInvcDfltTrnsDte), 4);
                if ($accbRcvblsInvcFuncCrncyRate == 0 || $accbRcvblsInvcFuncCrncyRate == 1) {
                    $accbRcvblsInvcFuncCrncyRate = $funcExchRate;
                }

                $accbRcvblsInvcEvntDocTyp = isset($_POST['accbRcvblsInvcEvntDocTyp']) ? cleanInputData($_POST['accbRcvblsInvcEvntDocTyp']) : '';
                $accbRcvblsInvcEvntCtgry = isset($_POST['accbRcvblsInvcEvntCtgry']) ? cleanInputData($_POST['accbRcvblsInvcEvntCtgry']) : '';
                $accbRcvblsInvcEvntRgstrID = isset($_POST['accbRcvblsInvcEvntRgstrID']) ? (float) cleanInputData($_POST['accbRcvblsInvcEvntRgstrID']) : -1;
                $accbRcvblsInvcCstmrID = isset($_POST['accbRcvblsInvcCstmrID']) ? (float) cleanInputData($_POST['accbRcvblsInvcCstmrID']) : -1;
                $accbRcvblsInvcCstmrSiteID = isset($_POST['accbRcvblsInvcCstmrSiteID']) ? (float) cleanInputData($_POST['accbRcvblsInvcCstmrSiteID']) : -1;
                $accbRcvblsInvcDfltBalsAcntID = isset($_POST['accbRcvblsInvcDfltBalsAcntID']) ? (float) cleanInputData($_POST['accbRcvblsInvcDfltBalsAcntID']) : -1;
                $accbRcvblsInvcGLBatchID = isset($_POST['accbRcvblsInvcGLBatchID']) ? (float) cleanInputData($_POST['accbRcvblsInvcGLBatchID']) : -1;
                $accbRcvblsInvcDesc = isset($_POST['accbRcvblsInvcDesc']) ? cleanInputData($_POST['accbRcvblsInvcDesc']) : '';
                $accbRcvblsInvcPayTerms = isset($_POST['accbRcvblsInvcPayTerms']) ? cleanInputData($_POST['accbRcvblsInvcPayTerms']) : '';

                $accbRcvblsInvcPayMthdID = isset($_POST['accbRcvblsInvcPayMthdID']) ? (int) cleanInputData($_POST['accbRcvblsInvcPayMthdID']) : -10;
                $accbRcvblsInvcDocTmplt = isset($_POST['accbRcvblsInvcDocTmplt']) ? cleanInputData($_POST['accbRcvblsInvcDocTmplt']) : '';
                $srcRcvblsInvcDocTyp = isset($_POST['srcRcvblsInvcDocTyp']) ? cleanInputData($_POST['srcRcvblsInvcDocTyp']) : '';
                $accbRcvblsInvcCstmrInvcNum = isset($_POST['accbRcvblsInvcCstmrInvcNum']) ? cleanInputData($_POST['accbRcvblsInvcCstmrInvcNum']) : '';
                $srcRcvblsInvcDocID = isset($_POST['srcRcvblsInvcDocID']) ? (float) cleanInputData($_POST['srcRcvblsInvcDocID']) : -1;

                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $slctdExtraInfoLines = isset($_POST['slctdExtraInfoLines']) ? cleanInputData($_POST['slctdExtraInfoLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                $accbRcvblDebtGlBatchID = -1;
                $advcPayDocId = -1;
                $advcPayDocTyp = "";
                if (strlen($accbRcvblsInvcDesc) > 499) {
                    $accbRcvblsInvcDesc = substr($accbRcvblsInvcDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($accbRcvblsInvcDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($accbRcvblsInvcDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                if ($accbRcvblsInvcVchType == "") {
                    $exitErrMsg .= "Document Type cannot be empty!<br/>";
                }
                if ($accbRcvblsInvcDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($accbRcvblsInvcCstmrID <= 0) {
                    $exitErrMsg .= "Customer Name cannot be empty!<br/>";
                }
                if ($accbRcvblsInvcCstmrSiteID <= 0) {
                    $exitErrMsg .= "Customer Site cannot be empty!<br/>";
                }
                if ($accbRcvblsInvcEvntDocTyp != "None" && ($accbRcvblsInvcEvntCtgry == "" || $accbRcvblsInvcEvntRgstrID <= 0)) {
                    $exitErrMsg .= "Linked Event Number and Category Cannot be empty\r\n if the Event Type is not set to None!<br/>";
                }
                if ($accbRcvblsInvcDfltBalsAcntID <= 0) {
                    $exitErrMsg .= "Please enter a Receivable Account!<br/>";
                }
                $oldPtyCashID = getGnrlRecID("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_number", "rcvbls_invc_hdr_id", $accbRcvblsInvcDocNum,
                        $orgID);
                if ($oldPtyCashID > 0 && $oldPtyCashID != $sbmtdAccbRcvblsInvcID) {
                    $exitErrMsg .= "New Document Number/Name is already in use in this Organization!<br/>";
                }
                $apprvlStatus = "Not Validated";
                $nxtApprvlActn = "Approve";
                $pymntTrms = $accbRcvblsInvcPayTerms;
                $srcDocHdrID = $srcRcvblsInvcDocID;
                $srcDocType = $srcRcvblsInvcDocTyp;
                $pymntMthdID = $accbRcvblsInvcPayMthdID;
                $amntPaid = 0;
                $glBtchID = $accbRcvblsInvcGLBatchID;
                $cstmrInvcNum = $accbRcvblsInvcCstmrInvcNum;
                $docTmpltClsftn = $accbRcvblsInvcDocTmplt;
                $amntAppld = 0;
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAccbRcvblsInvcID'] = $sbmtdAccbRcvblsInvcID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdAccbRcvblsInvcID <= 0) {
                    createRcvblsDocHdr($orgID, $accbRcvblsInvcDfltTrnsDte, $accbRcvblsInvcDocNum, $accbRcvblsInvcVchType,
                            $accbRcvblsInvcDesc, $srcDocHdrID, $accbRcvblsInvcCstmrID, $accbRcvblsInvcCstmrSiteID, $apprvlStatus,
                            $nxtApprvlActn, $accbRcvblsInvcTtlAmnt, $pymntTrms, $srcDocType, $pymntMthdID, $amntPaid, $glBtchID,
                            $cstmrInvcNum, $docTmpltClsftn, $accbRcvblsInvcInvcCurID, $amntAppld, $accbRcvblsInvcEvntRgstrID,
                            $accbRcvblsInvcEvntCtgry, $accbRcvblsInvcEvntDocTyp, $accbRcvblsInvcDfltBalsAcntID, $accbRcvblDebtGlBatchID,
                            $advcPayDocId, $advcPayDocTyp, $accbRcvblsInvcFuncCrncyRate);

                    $sbmtdAccbRcvblsInvcID = getGnrlRecID("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_number", "rcvbls_invc_hdr_id",
                            $accbRcvblsInvcDocNum, $orgID);
                } else if ($sbmtdAccbRcvblsInvcID > 0) {
                    updtRcvblsDocHdr($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcDfltTrnsDte, $accbRcvblsInvcDocNum, $accbRcvblsInvcVchType,
                            $accbRcvblsInvcDesc, $srcDocHdrID, $accbRcvblsInvcCstmrID, $accbRcvblsInvcCstmrSiteID, $apprvlStatus,
                            $nxtApprvlActn, $accbRcvblsInvcTtlAmnt, $pymntTrms, $srcDocType, $pymntMthdID, $amntPaid, $glBtchID,
                            $cstmrInvcNum, $docTmpltClsftn, $accbRcvblsInvcInvcCurID, $amntAppld, $accbRcvblsInvcEvntRgstrID,
                            $accbRcvblsInvcEvntCtgry, $accbRcvblsInvcEvntDocTyp, $accbRcvblsInvcDfltBalsAcntID, $accbRcvblsInvcFuncCrncyRate);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDetTransLines, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 17) {
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
                            $ln_FuncExchgRate = $accbRcvblsInvcFuncCrncyRate;
                            $ln_lineQty = (float) (cleanInputData1($crntRow[11]));
                            $ln_TaxID = (int) (cleanInputData1($crntRow[13]));
                            $ln_WHTaxID = (int) (cleanInputData1($crntRow[14]));
                            $ln_DscntID = (int) (cleanInputData1($crntRow[15]));
                            $ln_InitAmntLnID = (float) cleanInputData1($crntRow[16]);
                            if ($ln_lineQty <= 0) {
                                $ln_lineQty = 1;
                            }
                            $unitPrice = $entrdAmt / $ln_lineQty;
                            $lineTransDate = $accbRcvblsInvcDfltTrnsDte;
                            $funcCurrAmnt = $ln_FuncExchgRate * $entrdAmt;

                            $ln_IncrsDcrs2 = "Increase";
                            $ln_AccountID2 = $accbRcvblsInvcDfltBalsAcntID;

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
                            if (strpos($accbRcvblsInvcVchType, "Supplier") !== FALSE) {
                                if ($accbRcvblsInvcVchType == "Supplier Standard Payment" || $accbRcvblsInvcVchType == "Supplier Advance Payment" || $accbRcvblsInvcVchType == "Direct Topup for Supplier" || $accbRcvblsInvcVchType == "Supplier Debit Memo (InDirect Topup)") {
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
                            } else if (strpos($accbRcvblsInvcVchType, "Customer") !== FALSE) {
                                if ($accbRcvblsInvcVchType == "Customer Standard Payment" || $accbRcvblsInvcVchType == "Customer Advance Payment" || $accbRcvblsInvcVchType == "Direct Topup from Customer" || $accbRcvblsInvcVchType == "Customer Credit Memo (InDirect Topup)") {
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
                                //Create Petty Cash Summary Trns Record Itself
                                if ($lineDesc != "" && $ln_AccountID1 > 0 && $ln_AccountID2 > 0 && $ln_InitAmntLnID <= 0) {
                                    if ($lnSmmryLnID <= 0) {
                                        $lnSmmryLnID = getNewRcvblsLnID();
                                        $afftctd += createRcvblsDocDet($lnSmmryLnID, $sbmtdAccbRcvblsInvcID, $ln_ItemType, $lineDesc,
                                                $entrdAmt, $lineCurID, $ln_CodeBehind, $accbRcvblsInvcVchType, $ln_AutoCalc, $ln_IncrsDcrs1,
                                                $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $prepayDocHdrID, $vldyStatus, $orgnlLnID,
                                                $fnccurid, $accntCurrID1, $ln_FuncExchgRate, $acntExchRate1, $funcCurrAmnt, $acntAmnt1,
                                                $initAmntID, $ln_lineQty, $unitPrice, $lnRefDoc, $lnSlctdAmtBrkdwns, $ln_TaxID, $ln_WHTaxID,
                                                $ln_DscntID);
                                    } else {
                                        $afftctd += updtRcvblsDocDet($lnSmmryLnID, $sbmtdAccbRcvblsInvcID, $ln_ItemType, $lineDesc,
                                                $entrdAmt, $lineCurID, $ln_CodeBehind, $accbRcvblsInvcVchType, $ln_AutoCalc, $ln_IncrsDcrs1,
                                                $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $prepayDocHdrID, $vldyStatus, $orgnlLnID,
                                                $fnccurid, $accntCurrID1, $ln_FuncExchgRate, $acntExchRate1, $funcCurrAmnt, $acntAmnt1,
                                                $initAmntID, $ln_lineQty, $unitPrice, $lnRefDoc, $lnSlctdAmtBrkdwns, $ln_TaxID, $ln_WHTaxID,
                                                $ln_DscntID);
                                    }
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }

                if (trim($slctdExtraInfoLines, "|~") != "" && $sbmtdAccbRcvblsInvcID > 0) {
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
                                $afftctd1 += updateRowOthrInfVal("accb.accb_all_other_info_table", $ln_CombntnID, $sbmtdAccbRcvblsInvcID,
                                        $ln_Value, $ln_extrInfoLbl, $ln_extrInfoCtgry, $ln_DfltRowID);
                            } else {
                                if (doesRowHvOthrInfo("accb.accb_all_other_info_table", $ln_CombntnID, $sbmtdAccbRcvblsInvcID) > 0) {
                                    $afftctd1 += updateRowOthrInfVal("accb.accb_all_other_info_table", $ln_CombntnID,
                                            $sbmtdAccbRcvblsInvcID, $ln_Value, $ln_extrInfoLbl, $ln_extrInfoCtgry, $ln_DfltRowID);
                                } else {
                                    $ln_DfltRowID = getNewExtInfoID("accb.accb_all_other_info_table_dflt_row_id_seq");
                                    $afftctd1 += createRowOthrInfVal("accb.accb_all_other_info_table", $ln_CombntnID,
                                            $sbmtdAccbRcvblsInvcID, $ln_Value, $ln_extrInfoLbl, $ln_extrInfoCtgry, $ln_DfltRowID);
                                }
                            }
                        }
                    }
                }
                if ($shdSbmt != 2) {
                    $errMsg1 = reCalcRcvblInvcSmmrys($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcVchType, $accbRcvblsInvcInvcCurID);
                    if (strpos($errMsg1, "ERROR") !== FALSE) {
                        $exitErrMsg .= "<br/>" . $errMsg1;
                    }
                    $errMsg = "";
                    updtRcvblsDocAmnt($sbmtdAccbRcvblsInvcID, getRcvblsDocGrndAmnt($sbmtdAccbRcvblsInvcID));
                    if (validateRcvblInvcLns($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcVchType, $accbRcvblsInvcTtlAmnt, $errMsg) === false) {
                        $exitErrMsg .= "<br/>" . $errMsg;
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Receivable Invoice Voucher Successfully Saved!"
                            . "<br/>" . $afftctd . " Receivable Invoice Transaction(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "";
                    //Final Approval
                    if ($shdSbmt == 2) {
                        $exitErrMsg = apprvPyblsRcvblDoc($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcDocNum, "Receivables", $orgID, $usrID);
                        if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        } else {
                            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        }
                        /* updtRcvblsDocGLBatch($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcGLBatchID);
                          updtRcvblsDocApprvl($sbmtdAccbRcvblsInvcID, "Approved", "Cancel"); */
                    } else {
                        $exitErrMsg .= "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Receivable Invoice Successfully Saved!"
                                . "<br/>" . $afftctd . " Receivable Invoice Transaction(s) Saved Successfully!";
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdAccbRcvblsInvcID'] = $sbmtdAccbRcvblsInvcID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 20) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdAccbRcvblsInvcID = isset($_POST['sbmtdAccbRcvblsInvcID']) ? cleanInputData($_POST['sbmtdAccbRcvblsInvcID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdAccbRcvblsInvcID;
                if ($attchmentID > 0) {
                    uploadDaRcvblsInvcDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewRcvblsInvcDocID();
                    createRcvblsInvcDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaRcvblsInvcDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/RcvblDocs/" . $nwImgLoc;
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
                $accbRcvblsInvcDesc = isset($_POST['accbRcvblsInvcDesc']) ? cleanInputData($_POST['accbRcvblsInvcDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdAccbRcvblsInvcID = isset($_POST['sbmtdAccbRcvblsInvcID']) ? cleanInputData($_POST['sbmtdAccbRcvblsInvcID']) : -1;
                if (!$cancelDocs) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                $p_dochdrtype = "";
                if ($sbmtdAccbRcvblsInvcID > 0) {
                    $result = get_One_RcvblsInvcDocHdr($sbmtdAccbRcvblsInvcID);
                    if ($row = loc_db_fetch_array($result)) {
                        $accbRcvblsInvcDfltTrnsDte = $row[1] . " 12:00:00";
                        $accbRcvblsInvcDocNum = $row[4];
                        $p_dochdrtype = $row[5];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                    }
                }
                if ($rqStatus == "Not Validated" && $sbmtdAccbRcvblsInvcID > 0) {
                    echo deleteRcvblsDocHdrNDet($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcDocNum);
                    exit();
                } else {
                    execUpdtInsSQL("UPDATE accb.accb_rcvbls_invc_hdr SET comments_desc='" . loc_db_escape_string($accbRcvblsInvcDesc) . "' WHERE (rcvbls_invc_hdr_id = " . $sbmtdAccbRcvblsInvcID . ")");

                    $exitErrMsg = cancelPyblsRcvblDoc($sbmtdAccbRcvblsInvcID, $p_dochdrtype, "Receivables", $orgID, $usrID);
                    $arr_content['sbmtdAccbRcvblsInvcID'] = $sbmtdAccbRcvblsInvcID;
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
                //Receivables Invoices
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Receivable Invoices</span>
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
                    $total = get_Total_RcvblsDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_RcvblsDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
                    $colClassType3 = "col-md-5";
                    ?> 
                    <form id='accbRcvblsInvcForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">RECEIVABLE INVOICES</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                                $colClassType3 = "col-md-10";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbRcvblsInvcSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAccbRcvblsInvc(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="accbRcvblsInvcPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbRcvblsInvc('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbRcvblsInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbRcvblsInvcSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                            $srchInsArrys = array("All", "Document Number", "Document Description", "Document Classification",
                                                "Customer Name", "Customer's Doc. Number", "Source Doc Number", "Approval Status", "Created By",
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
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbRcvblsInvcDsplySze" style="min-width:70px !important;">                            
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
                                                <a href="javascript:getAccbRcvblsInvc('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbRcvblsInvc('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
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
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbRcvblsInvcForm(-1, 1, 'ShowDialog', 'Customer Standard Payment');" data-toggle="tooltip" data-placement="bottom" title="Add New Customer Standard Payment">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                CSP
                                            </button>                 
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbRcvblsInvcForm(-1, 1, 'ShowDialog', 'Customer Advance Payment');" data-toggle="tooltip" data-placement="bottom" title="Add New Customer Advance Payment">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                CAP
                                            </button>                 
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbRcvblsInvcForm(-1, 1, 'ShowDialog', 'Direct Refund to Customer');" data-toggle="tooltip" data-placement="bottom" title="Add New Direct Refund to Customer">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                DRTC
                                            </button>                  
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbRcvblsInvcForm(-1, 1, 'ShowDialog', 'Customer Credit Memo (InDirect Topup)');" data-toggle="tooltip" data-placement="bottom" title="Add New Customer Credit Memo (InDirect Topup)">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                CCM-IT
                                            </button>                
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbRcvblsInvcForm(-1, 1, 'ShowDialog', 'Direct Topup from Customer');" data-toggle="tooltip" data-placement="bottom" title="Add New Direct Topup from Customer">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                DTFC
                                            </button>                    
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbRcvblsInvcForm(-1, 1, 'ShowDialog', 'Customer Debit Memo (InDirect Refund)');" data-toggle="tooltip" data-placement="bottom" title="Add New Customer Debit Memo (InDirect Refund)">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                CDM-IR
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
                                                <input type="checkbox" class="form-check-input" onclick="getAccbRcvblsInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbRcvblsInvcShwUnpaidOnly" name="accbRcvblsInvcShwUnpaidOnly"  <?php echo $shwUnpaidOnlyChkd; ?>>
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
                                                <input type="checkbox" class="form-check-input" onclick="getAccbRcvblsInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbRcvblsInvcShwUnpstdOnly" name="accbRcvblsInvcShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted
                                            </label>
                                        </div>                            
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="accbRcvblsInvcHdrsTable" cellspacing="0" width="100%" style="width:100%;">
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
                                                <?php if ($canDel === true) {
                                                    ?>
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
                                                <tr id="accbRcvblsInvcHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Invoice" 
                                                                onclick="getOneAccbRcvblsInvcForm(<?php echo $row[0]; ?>, 1, 'ShowDialog', '<?php echo $row[2]; ?>');" style="padding:2px !important;" style="padding:2px !important;">                                                                
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
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delAccbRcvblsInvc('accbRcvblsInvcHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="accbRcvblsInvcHdrsRow<?php echo $cntr; ?>_HdrID" name="accbRcvblsInvcHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|accb.accb_rcvbls_invc_hdr|rcvbls_invc_hdr_id"),
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
                //New Receivables Invoice Form
                $sbmtdAccbRcvblsInvcID = isset($_POST['sbmtdAccbRcvblsInvcID']) ? cleanInputData($_POST['sbmtdAccbRcvblsInvcID']) : -1;
                $accbRcvblsInvcVchType = isset($_POST['accbRcvblsInvcVchType']) ? cleanInputData($_POST['accbRcvblsInvcVchType']) : "Customer Standard Payment";
                $extraPKeyID = isset($_POST['extraPKeyID']) ? (float) cleanInputData($_POST['extraPKeyID']) : -1;
                $extraPKeyType = isset($_POST['extraPKeyType']) ? cleanInputData($_POST['extraPKeyType']) : "";
                if (!$canAdd || ($sbmtdAccbRcvblsInvcID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                if ($accbRcvblsInvcVchType == "Customer Standard Payment" && (!$addRecsCSP || ($sbmtdAccbRcvblsInvcID > 0 && !$editRecsCSP))) {
                    restricted();
                    exit();
                }
                if ($accbRcvblsInvcVchType == "Customer Advance Payment" && (!$addRecsCAP || ($sbmtdAccbRcvblsInvcID > 0 && !$editRecsCAP))) {
                    restricted();
                    exit();
                }
                if ($accbRcvblsInvcVchType == "Direct Refund to Customer" && (!$addRecsDRTC || ($sbmtdAccbRcvblsInvcID > 0 && !$editRecsDRTC))) {
                    restricted();
                    exit();
                }
                if ($accbRcvblsInvcVchType == "Customer Credit Memo (InDirect Topup)" && (!$addRecsCCMIT || ($sbmtdAccbRcvblsInvcID > 0 && !$editRecsCCMIT))) {
                    restricted();
                    exit();
                }
                if ($accbRcvblsInvcVchType == "Direct Topup from Customer" && (!$addRecsDTFC || ($sbmtdAccbRcvblsInvcID > 0 && !$editRecsDTFC))) {
                    restricted();
                    exit();
                }
                if ($accbRcvblsInvcVchType == "Customer Debit Memo (InDirect Refund)" && (!$addRecsCDMIR || ($sbmtdAccbRcvblsInvcID > 0 && !$editRecsCDMIR))) {
                    restricted();
                    exit();
                }
                $orgnlAccbRcvblsInvcID = $sbmtdAccbRcvblsInvcID;
                $accbRcvblsInvcDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $accbRcvblsInvcCreator = $uName;
                $accbRcvblsInvcCreatorID = $usrID;
                $gnrtdTrnsNo = "";
                $accbRcvblsInvcDesc = "";

                $srcRcvblsInvcDocID = -1;
                $srcRcvblsInvcDocTyp = "";
                if ($accbRcvblsInvcVchType == "Customer Standard Payment") {
                    $srcRcvblsInvcDocTyp = "";
                } elseif ($accbRcvblsInvcVchType == "Customer Advance Payment") {
                    $srcRcvblsInvcDocTyp = "";
                } elseif ($accbRcvblsInvcVchType == "Direct Refund to Customer") {
                    $srcRcvblsInvcDocTyp = "Customer Standard Payment";
                } elseif ($accbRcvblsInvcVchType == "Customer Credit Memo (InDirect Topup)") {
                    $srcRcvblsInvcDocTyp = "Customer Standard Payment";
                } elseif ($accbRcvblsInvcVchType == "Direct Topup from Customer") {
                    $srcRcvblsInvcDocTyp = "Customer Standard Payment";
                } elseif ($accbRcvblsInvcVchType == "Customer Debit Memo (InDirect Refund)") {
                    $srcRcvblsInvcDocTyp = "Customer Standard Payment";
                }
                $accbRcvblsInvcDocTmpltID = -1;
                $srcRcvblsInvcDocNum = "";

                $accbRcvblsInvcCstmr = "";
                $accbRcvblsInvcCstmrID = -1;
                $accbRcvblsInvcCstmrSite = "";
                $accbRcvblsInvcCstmrSiteID = -1;
                $accbRcvblsInvcCstmrClsfctn = "Customer";
                $rqStatus = "Not Validated";
                $rqStatusNext = "Approve";
                $rqstatusColor = "red";

                $accbRcvblsInvcTtlAmnt = 0;
                $accbRcvblsInvcAppldAmnt = 0;
                $accbRcvblsInvcPayTerms = "";
                $accbRcvblsInvcPayMthd = "";
                $accbRcvblsInvcPayMthdID = -1;
                $accbRcvblsInvcPaidAmnt = 0;
                $accbRcvblsInvcGLBatch = "";
                $accbRcvblsInvcGLBatchID = -1;
                $accbRcvblsInvcCstmrInvcNum = "";
                $accbRcvblsInvcDocTmplt = "";
                $accbRcvblsInvcEvntRgstr = "";
                $accbRcvblsInvcEvntRgstrID = -1;
                $accbRcvblsInvcEvntCtgry = "";
                $accbRcvblsInvcEvntDocTyp = "";
                $accbRcvblsInvcDfltBalsAcnt = "";
                $accbRcvblsInvcInvcCurID = $fnccurid;
                $accbRcvblsInvcInvcCur = $fnccurnm;
                $accbRcvblsInvcIsPstd = "0";
                $advcPayDocId = -1;
                $advcPayDocTyp = "";
                $accbRcvblAmtApldElswhr = 0;
                $accbRcvblDebtGlBatchID = -1;
                $accbRcvblDebtGlBatchNm = "";
                $accbRcvblsInvcFuncCrncyRate = 1;
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $accbRcvblsInvcDfltBalsAcntID = get_DfltCstmrRcvblsCashAcnt($accbRcvblsInvcCstmrID, $orgID);
                //echo "ACCID::".$accbRcvblsInvcDfltBalsAcntID;
                if ($sbmtdAccbRcvblsInvcID > 0) {
                    $result = get_One_RcvblsInvcDocHdr($sbmtdAccbRcvblsInvcID);
                    if ($row = loc_db_fetch_array($result)) {
                        $accbRcvblsInvcDfltTrnsDte = $row[1];
                        $accbRcvblsInvcCreator = $row[3];
                        $accbRcvblsInvcCreatorID = $row[2];
                        $gnrtdTrnsNo = $row[4];
                        $accbRcvblsInvcVchType = $row[5];
                        $accbRcvblsInvcDesc = $row[6];
                        $srcRcvblsInvcDocID = $row[7];
                        $srcRcvblsInvcDocNum = $row[37];
                        $accbRcvblsInvcCstmr = $row[9];
                        $accbRcvblsInvcCstmrID = $row[8];
                        $accbRcvblsInvcCstmrSite = $row[11];
                        $accbRcvblsInvcCstmrSiteID = $row[10];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                        $rqstatusColor = "red";

                        $accbRcvblsInvcTtlAmnt = (float) $row[14];
                        $accbRcvblsInvcAppldAmnt = (float) $row[34];
                        $accbRcvblsInvcPayTerms = $row[15];
                        $srcRcvblsInvcDocTyp = $row[16];
                        $accbRcvblsInvcPayMthd = $row[18];
                        $accbRcvblsInvcPayMthdID = $row[17];
                        $accbRcvblsInvcPaidAmnt = $row[19];
                        if (strpos($accbRcvblsInvcVchType, "Advance Payment") === FALSE) {
                            $accbRcvblsInvcAppldAmnt = $accbRcvblsInvcPaidAmnt;
                        }
                        $accbRcvblsInvcGLBatch = $row[21];
                        $accbRcvblsInvcGLBatchID = $row[20];
                        $accbRcvblsInvcCstmrInvcNum = $row[22];
                        $accbRcvblsInvcDocTmplt = $row[23];
                        $accbRcvblsInvcInvcCur = $row[25];
                        $accbRcvblsInvcInvcCurID = $row[24];
                        $accbRcvblsInvcEvntRgstr = "";
                        $accbRcvblsInvcEvntRgstrID = $row[26];
                        $accbRcvblsInvcEvntCtgry = $row[27];
                        $accbRcvblsInvcEvntDocTyp = $row[28];
                        $accbRcvblsInvcDfltBalsAcntID = $row[29];
                        $accbRcvblsInvcDfltBalsAcnt = $row[30];
                        $accbRcvblsInvcIsPstd = $row[31];
                        $advcPayDocId = $row[32];
                        $advcPayDocTyp = $row[33];
                        $accbRcvblAmtApldElswhr = $row[34];
                        $accbRcvblDebtGlBatchID = $row[35];
                        $accbRcvblDebtGlBatchNm = $row[36];
                        $accbRcvblsInvcFuncCrncyRate = (float) $row[38];
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
                    //if ($accbRcvblsInvcDfltBalsAcntID > 0)
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypes = array("Customer Standard Payment", "Customer Advance Payment", "Direct Refund to Customer",
                        "Customer Credit Memo (InDirect Topup)", "Direct Topup from Customer", "Customer Debit Memo (InDirect Refund)");
                    $docTypPrfxs = array("CSP", "CAP", "DRTC", "CCM-IT", "DTFC", "CDM-IR");

                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $accbRcvblsInvcVchType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_number",
                                            "rcvbls_invc_hdr_id", $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $accbRcvblsInvcDfltBalsAcnt = getAccntNum($accbRcvblsInvcDfltBalsAcntID) . "." . getAccntName($accbRcvblsInvcDfltBalsAcntID);
                    if ($accbRcvblsInvcDfltBalsAcntID > 0) {
                        $accbRcvblsInvcInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id",
                                        $accbRcvblsInvcDfltBalsAcntID);
                        $accbRcvblsInvcInvcCur = getPssblValNm($accbRcvblsInvcInvcCurID);
                    }
                    createRcvblsDocHdr($orgID, $accbRcvblsInvcDfltTrnsDte, $gnrtdTrnsNo, $accbRcvblsInvcVchType, $accbRcvblsInvcDesc,
                            $srcRcvblsInvcDocID, $accbRcvblsInvcCstmrID, $accbRcvblsInvcCstmrSiteID, $rqStatus, $rqStatusNext,
                            $accbRcvblsInvcTtlAmnt, $accbRcvblsInvcPayTerms, $srcRcvblsInvcDocTyp, $accbRcvblsInvcPayMthdID,
                            $accbRcvblsInvcPaidAmnt, $accbRcvblsInvcGLBatchID, $accbRcvblsInvcCstmrInvcNum, $accbRcvblsInvcDocTmplt,
                            $accbRcvblsInvcInvcCurID, $accbRcvblsInvcAppldAmnt, $accbRcvblsInvcEvntRgstrID, $accbRcvblsInvcEvntCtgry,
                            $accbRcvblsInvcEvntDocTyp, $accbRcvblsInvcDfltBalsAcntID, $accbRcvblDebtGlBatchID, $advcPayDocId,
                            $advcPayDocTyp, $accbRcvblsInvcFuncCrncyRate);

                    $sbmtdAccbRcvblsInvcID = getGnrlRecID("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_number", "rcvbls_invc_hdr_id",
                            $gnrtdTrnsNo, $orgID);
                }
                $accbRcvblsInvcOustndngAmnt = $accbRcvblsInvcTtlAmnt - $accbRcvblsInvcPaidAmnt;
                $accbRcvblsInvcOustndngStyle = "color:red;";
                $accbRcvblsInvcPaidStyle = "color:black;";
                if ($accbRcvblsInvcOustndngAmnt <= 0) {
                    $accbRcvblsInvcOustndngStyle = "color:green;";
                }
                if ($accbRcvblsInvcPaidAmnt > 0 && $accbRcvblsInvcOustndngAmnt <= 0) {
                    $accbRcvblsInvcPaidStyle = "color:green;";
                } else if ($accbRcvblsInvcPaidAmnt > 0) {
                    $accbRcvblsInvcPaidStyle = "color:brown;";
                }
                $reportName = getEnbldPssblValDesc("Receivables Invoice", getLovID("Document Custom Print Process Names"));
                $reportTitle = str_replace("Customer Standard Payment", "Payment Receipt", $accbRcvblsInvcVchType);
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdAccbRcvblsInvcID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="oneAccbRcvblsInvcEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdAccbRcvblsInvcID" name="sbmtdAccbRcvblsInvcID" value="<?php echo $sbmtdAccbRcvblsInvcID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="accbRcvblsInvcDocNum" name="accbRcvblsInvcDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control rqrdFld" size="16" type="text" id="accbRcvblsInvcDfltTrnsDte" name="accbRcvblsInvcDfltTrnsDte" value="<?php
                                        echo substr($accbRcvblsInvcDfltTrnsDte, 0, 11);
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
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="accbRcvblsInvcVchType" name="accbRcvblsInvcVchType" value="<?php echo $accbRcvblsInvcVchType; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accbRcvblsInvcPayMthd" class="control-label col-md-4">Payment Method:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbRcvblsInvcPayMthd" name="accbRcvblsInvcPayMthd" value="<?php echo $accbRcvblsInvcPayMthd; ?>" readonly="true">
                                            <input type="hidden" id="accbRcvblsInvcPayMthdID" value="<?php echo $accbRcvblsInvcPayMthdID; ?>">
                                            <input type="hidden" id="accbRcvblsInvcMthdType" value="Customer Payments">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Methods', 'allOtherInputOrgID', 'accbRcvblsInvcMthdType', '', 'radio', true, '', 'accbRcvblsInvcPayMthdID', 'accbRcvblsInvcPayMthd', 'clear', 1, '');" data-toggle="tooltip" title="Existing Payment Method">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accbRcvblsInvcDocTmplt" class="control-label col-md-4">Doc. Template:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbRcvblsInvcDocTmplt" name="accbRcvblsInvcDocTmplt" value="<?php echo $accbRcvblsInvcDocTmplt; ?>" readonly="true">
                                            <input type="hidden" id="accbRcvblsInvcDocTmpltID" value="<?php echo $accbRcvblsInvcDocTmpltID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Document Templates', 'allOtherInputOrgID', 'accbRcvblsInvcVchType', '', 'radio', true, '', 'accbRcvblsInvcDocTmpltID', 'accbRcvblsInvcDocTmplt', 'clear', 1, '', function () {
                                                                        getRcvblsFrmTmplate('rcvblsInvcDetLines');
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
                                        <button type="button" class="btn btn-default" style="height:37px;width:100% !important;" id="myAccbRcvblsInvcStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:37px;">
                                                <?php
                                                echo $rqStatus . ($accbRcvblsInvcIsPstd == "1" ? " [Posted]" : " [Not Posted]");
                                                ?>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="accbRcvblsInvcCstmr" class="control-label col-md-4">Customer:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="accbRcvblsInvcCstmr" name="accbRcvblsInvcCstmr" value="<?php echo $accbRcvblsInvcCstmr; ?>" readonly="true">
                                            <input type="hidden" id="accbRcvblsInvcCstmrID" value="<?php echo $accbRcvblsInvcCstmrID; ?>">
                                            <input type="hidden" id="accbRcvblsInvcCstmrClsfctn" value="<?php echo $accbRcvblsInvcCstmrClsfctn; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Supplier', 'ShowDialog', function () {}, 'accbRcvblsInvcCstmrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'accbRcvblsInvcCstmrClsfctn', 'radio', true, '', 'accbRcvblsInvcCstmrID', 'accbRcvblsInvcCstmr', 'clear', 1, '', function () {
                                                                        getAccbRcvblsCodeBhndInfo();
                                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label for="accbRcvblsInvcCstmrSite" class="control-label col-md-4">Site:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="accbRcvblsInvcCstmrSite" name="accbRcvblsInvcCstmrSite" value="<?php echo $accbRcvblsInvcCstmrSite; ?>" readonly="true">
                                            <input class="form-control" type="hidden" id="accbRcvblsInvcCstmrSiteID" value="<?php echo $accbRcvblsInvcCstmrSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'accbRcvblsInvcCstmrID', '', '', 'radio', true, '', 'accbRcvblsInvcCstmrSiteID', 'accbRcvblsInvcCstmrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <label style="margin-bottom:0px !important;">Customer's Doc. No.:</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" aria-label="..." id="accbRcvblsInvcCstmrInvcNum" name="accbRcvblsInvcCstmrInvcNum" value="<?php echo $accbRcvblsInvcCstmrInvcNum; ?>">
                                    </div>
                                </div>                                                               
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group"  style="width:100%;">
                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="accbRcvblsInvcDesc" name="accbRcvblsInvcDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $accbRcvblsInvcDesc; ?></textarea>
                                            <input class="form-control" type="hidden" id="accbRcvblsInvcDesc1" value="<?php echo $accbRcvblsInvcDesc; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbRcvblsInvcDesc');" style="max-width:30px;width:30px;">
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
                                        <input type="text" class="form-control" aria-label="..." id="srcRcvblsInvcDocTyp" name="srcRcvblsInvcDocTyp" value="<?php echo $srcRcvblsInvcDocTyp; ?>">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="srcRcvblsInvcDocNum" class="control-label col-md-4">Source Doc. No.:</label>
                                    <div  class="col-md-8">
                                        <input type="hidden" id="srcRcvblsInvcDocID" value="<?php echo $srcRcvblsInvcDocID; ?>"><?php
                                        if (!($accbRcvblsInvcVchType == "Customer Advance Payment" || $accbRcvblsInvcVchType == "Customer Standard Payment")) {
                                            ?>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="srcRcvblsInvcDocNum" name="srcRcvblsInvcDocNum" value="<?php echo $srcRcvblsInvcDocNum; ?>" readonly="true" style="width:100%;">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer Standard Payments New', 'allOtherInputOrgID', 'accbRcvblsInvcCstmrID', 'accbRcvblsInvcInvcCur', 'radio', true, '', 'srcRcvblsInvcDocID', 'srcRcvblsInvcDocNum', 'clear', 1, '', function () {});" data-toggle="tooltip" title="Existing Document Number">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        <?php } else { ?>
                                            <input type="text" class="form-control" aria-label="..." id="srcRcvblsInvcDocNum" name="srcRcvblsInvcDocNum" value="<?php echo $srcRcvblsInvcDocNum; ?>" readonly="true" style="width:100%;">
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
                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $accbRcvblsInvcInvcCur; ?>', 'accbRcvblsInvcInvcCur', '', 'clear', 0, '', function () {
                                                                        $('#accbRcvblsInvcInvcCur1').html($('#accbRcvblsInvcInvcCur').val());
                                                                        $('#accbRcvblsInvcInvcCur2').html($('#accbRcvblsInvcInvcCur').val());
                                                                        $('#accbRcvblsInvcInvcCur3').html($('#accbRcvblsInvcInvcCur').val());
                                                                        $('#accbRcvblsInvcInvcCur4').html($('#accbRcvblsInvcInvcCur').val());
                                                                        $('#accbRcvblsInvcInvcCur5').html($('#accbRcvblsInvcInvcCur').val());
                                                                        $('#accbRcvblsInvcInvcCur6').html($('#accbRcvblsInvcInvcCur').val());
                                                                    });">
                                                <span class="" style="font-size: 20px !important;" id="accbRcvblsInvcInvcCur1"><?php echo $accbRcvblsInvcInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="accbRcvblsInvcInvcCur" value="<?php echo $accbRcvblsInvcInvcCur; ?>"> 
                                            <input type="hidden" id="accbRcvblsInvcInvcCurID" value="<?php echo $accbRcvblsInvcInvcCurID; ?>"> 
                                            <input class="form-control rqrdFld" type="text" id="accbRcvblsInvcTtlAmnt" value="<?php
                                            echo number_format($accbRcvblsInvcTtlAmnt, 2);
                                            ?>"  
                                                   style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbRcvblsInvcTtlAmnt');" <?php echo $mkReadOnly; ?>/>
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
                                                <span class="" style="font-size: 20px !important;" id="accbRcvblsInvcInvcCur6"><?php echo $accbRcvblsInvcInvcCur; ?></span>
                                                <span class="" style="font-size: 20px !important;" id="accbRcvblsInvcFuncCur"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="accbRcvblsInvcFuncCrncyRate" value="<?php
                                            echo number_format($accbRcvblsInvcFuncCrncyRate, 4);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" <?php echo $mkReadOnly; ?>/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Total Amount Received:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $accbRcvblsInvcPaidStyle; ?>" id="accbRcvblsInvcInvcCur2"><?php echo $accbRcvblsInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="accbRcvblsInvcPaidAmnt" value="<?php
                                            echo number_format($accbRcvblsInvcPaidAmnt, 2);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $accbRcvblsInvcPaidStyle; ?>" onchange="fmtAsNumber('accbRcvblsInvcPaidAmnt');" readonly="true"/>
                                            <label data-toggle="tooltip" title="History of Payments" class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbPymntsHstryForm(<?php echo $sbmtdAccbRcvblsInvcID; ?>, 103, 'ReloadDialog',<?php echo $sbmtdAccbRcvblsInvcID; ?>, 'Receivable Invoice', 'Customer Payments');">
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
                                                <span class="" style="font-size: 20px !important;<?php echo $accbRcvblsInvcOustndngStyle; ?>" id="accbRcvblsInvcInvcCur3"><?php echo $accbRcvblsInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="accbRcvblsInvcOustndngAmnt" value="<?php
                                            echo number_format($accbRcvblsInvcOustndngAmnt, 2);
                                            ?>" 
                                                   style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $accbRcvblsInvcOustndngStyle; ?>" onchange="fmtAsNumber('accbRcvblsInvcOustndngAmnt');"  readonly="true"/>
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
                                                <span class="" style="font-size: 20px !important;" id="accbRcvblsInvcInvcCur4"><?php echo $accbRcvblsInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="accbRcvblsInvcAppldAmnt" value="<?php
                                            echo number_format($accbRcvblsInvcPaidAmnt - $accbRcvblsInvcAppldAmnt, 2);
                                            ?>" 
                                                   style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbRcvblsInvcAppldAmnt');"  readonly="true"/>
                                        </div>
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label for="accbRcvblsInvcGLBatch" class="control-label col-md-4">GL Batch Name:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="accbRcvblsInvcGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $accbRcvblsInvcGLBatch; ?>" readonly="true"/>
                                            <input type="hidden" id="accbRcvblsInvcGLBatchID" value="<?php echo $accbRcvblsInvcGLBatchID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $accbRcvblsInvcGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdAccbRcvblsInvcID; ?>, 'Receivable Invoice');">
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
                                    <li class="active"><a data-toggle="tabajxrcvblsinvc" data-rhodata="" href="#rcvblsInvcDetLines" id="rcvblsInvcDetLinestab">Invoice Lines</a></li>
                                    <li class=""><a data-toggle="tabajxrcvblsinvc" data-rhodata="" href="#rcvblsInvcExtraInfo" id="rcvblsInvcExtraInfotab">Extra Information</a></li>
                                </ul>  
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                $trsctnLnTxNm = "View Non-WHT Tax Codes";
                                                $trsctnLnWHTxNm = "View WHT Tax Codes";
                                                $trsctnLnDscntNm = "View Discounts";
                                                $nwRowHtml33 = "<tr id=\"oneAccbRcvblsInvcSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAccbRcvblsInvcSmryLinesTable tr').index(this));\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\" style=\"display:none;\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_ItemType\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("");
                                                $srchInsArrys = array("WWW_LINETYPE_WWW");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\"  style=\"\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_CodeBhndID\" value=\"-1\" style=\"width:100% !important;\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_InitAmntLnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_IsWHTax\" value=\"0\" style=\"width:100% !important;\">    
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_LineDesc\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow_WWW123WWW_LineDesc', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetDesc');\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getAccbRcvblsInvcLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_CodeBhndID', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_LineDesc', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </td>                                                 
                                                        <td class=\"lovtd\" style=\"display:none;\">
                                                            <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_RefDoc\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow_WWW123WWW_RefDoc', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetRfDc');\">                                                    
                                                        </td>                                          
                                                        <td class=\"lovtd\">
                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_TrnsCurNm\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_TrnsCurNm\" value=\"" . $accbRcvblsInvcInvcCur . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file\" onclick=\"\">
                                                                    <span class=\"\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_TrnsCurNm1\">" . $accbRcvblsInvcInvcCur . "</span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_EntrdAmt\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_EntrdAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow_WWW123WWW_EntrdAmt', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbRcvblsInvcSmryTtl();\">                                                    
                                                        </td>     
                                                        <td class=\"lovtd\" style=\"display:none;\">
                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Amount Breakdown\" 
                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_EntrdAmt', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>  
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_TaxID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_TaxNm\" value=\"" . $trsctnLnTxNm . "\" style=\"width:100% !important;\"> 
                                                            <button id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_TaxBtn\" type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $trsctnLnTxNm . "\" 
                                                                    onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Non-WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_TaxID', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_TaxNm', 'clear', 0, '', function () {
                                                                                                    changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow_WWW123WWW_TaxNm', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_TaxBtn');
                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/tax-icon420x500.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>  
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_WHTaxID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_WHTaxNm\" value=\"" . $trsctnLnWHTxNm . "\" style=\"width:100% !important;\">   
                                                            <button id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_WHTaxBtn\" type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $trsctnLnWHTxNm . "\" 
                                                                    onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_WHTaxID', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_WHTaxNm', 'clear', 0, '', function () {
                                                                                                    changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow_WWW123WWW_WHTaxNm', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_WHTaxBtn');});\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/tg-tax-icon.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>   
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_DscntID\" value=\"-1\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_DscntNm\" value=\"" . $trsctnLnDscntNm . "\" style=\"width:100% !important;\">  
                                                            <button id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_DscntBtn\" type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $trsctnLnDscntNm . "\" 
                                                                    onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_DscntID', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_DscntNm', 'clear', 0, '', function () {
                                                                                                    changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow_WWW123WWW_DscntNm', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_DscntBtn');
                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/dscnt_456356.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td> 
                                                        <td class=\"lovtd\" style=\"text-align: center;display:none;\">
                                                            <div class=\"form-group form-group-sm \">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_AutoCalc\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_AutoCalc\">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_IncrsDcrs1\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_AccountID1\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_AccountNm1\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_AccountNm1\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_AccountID1', 'oneAccbRcvblsInvcSmryRow_WWW123WWW_AccountNm1', 'clear', 1, '', function () {
                                                                changeElmntTitleFunc('oneAccbRcvblsInvcSmryRow_WWW123WWW_AccountNm1');
                                                            });\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>                                           
                                                        </td>
                                                        <td class=\"lovtd\"  style=\"display:none;\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetFuncRate\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_FuncExchgRate\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_FuncExchgRate\" value=\"1.0000\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow_WWW123WWW_FuncExchgRate', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetFuncRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbRcvblsInvcSmryTtl();\">                                                    
                                                        </td> 
                                                        <td class=\"lovtd\" style=\"\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_QTY\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_QTY\" value=\"1.0000\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow_WWW123WWW_AcntExchgRate', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbRcvblsInvcSmryTtl();\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\" style=\"\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_ApldDocNum\" name=\"oneAccbRcvblsInvcSmryRow_WWW123WWW_ApldDocNum\" value=\"\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbRcvblsInvcSmryTtl();\" readonly=\"true\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbRcvblsInvcDetLn('oneAccbRcvblsInvcSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
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
                                                    <div class="col-md-7" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <button id="addNwAccbRcvblsInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbRcvblsInvcRows('oneAccbRcvblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml1; ?>', '1Initial Amount');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button> 
                                                            <button id="addNwAccbRcvblsInvcTaxBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbRcvblsInvcRows('oneAccbRcvblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml2; ?>', '2Tax');" data-toggle="tooltip" data-placement="bottom" title = "New Tax Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">Tax
                                                            </button> 
                                                            <button id="addNwAccbRcvblsInvcDscntBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbRcvblsInvcRows('oneAccbRcvblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml3; ?>', '3Discount');" data-toggle="tooltip" data-placement="bottom" title = "New Discount Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">Discount
                                                            </button> 
                                                            <button id="addNwAccbRcvblsInvcChrgBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbRcvblsInvcRows('oneAccbRcvblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml4; ?>', '4Extra Charge');" data-toggle="tooltip" data-placement="bottom" title = "New Extra Charge Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">Extra Charge
                                                            </button> 
                                                            <button id="addNwAccbRcvblsInvcPrepayBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbRcvblsInvcRows('oneAccbRcvblsInvcSmryLinesTable', 0, '<?php echo $nwRowHtml5; ?>', '5Applied Prepayment');" data-toggle="tooltip" data-placement="bottom" title = "New Applied Prepayment Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">Prepayment
                                                            </button>                                 
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbRcvblsInvcDocsForm(<?php echo $sbmtdAccbRcvblsInvcID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbRcvblsInvcForm(<?php echo $sbmtdAccbRcvblsInvcID; ?>, 1, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdRcvblsInvcValsTtlBtn"><?php echo $accbRcvblsInvcInvcCur; ?> 
                                                                <?php
                                                                echo number_format($accbRcvblsInvcTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdRcvblsInvcValsTtlVal" value="<?php echo $accbRcvblsInvcTtlAmnt; ?>">
                                                        </button>
                                                    </div> 
                                                    <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                            <?php
                                                            if ($rqStatus == "Not Validated") {
                                                                ?>
                                                                <?php if ($canEdt) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbRcvblsInvcForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                                <?php } ?>
                                                                <?php if ($canRvwApprvDocs) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbRcvblsInvcForm('<?php echo $fnccurnm; ?>', 2);" data-toggle="tooltip" data-placement="bottom" title="Approve Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Approve
                                                                    </button>
                                                                <?php } ?>
                                                                <?php
                                                            } else if ($rqStatus == "Approved") {
                                                                if ($canPayDocs && $accbRcvblsInvcOustndngAmnt > 0) {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPayInvcForm(<?php echo $sbmtdAccbRcvblsInvcID; ?>, 'Customer Payments', 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Pay Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Pay Invoice
                                                                    </button>
                                                                <?php } ?>
                                                                <?php if ($cancelDocs) { ?>
                                                                    <button id="fnlzeRvrslAccbRcvblsInvcBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbRcvblsInvcRvrslForm('<?php echo $fnccurnm; ?>', 1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Document&nbsp;</button>                                                                   
                                                                    <?php
                                                                }
                                                            }
                                                            if ($extraPKeyID > 0 && $extraPKeyType == "Receivable Invoice") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbRcvblsInvcForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    Go Back&nbsp;
                                                                </button>                                                                   
                                                                <?php
                                                            } else if ($extraPKeyID > 0 && $extraPKeyType == "Sales Invoice") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmSalesInvcForm(<?php echo $extraPKeyID; ?>, 3, 'ReloadDialog');">
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
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbRcvblsInvcLnsTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="rcvblsInvcDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneAccbRcvblsInvcSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="max-width:90px;width:90px;text-align: center;display:none;">Item Type</th>
                                                                <th style="min-width:250px;">Item Description</th>
                                                                <th style="max-width:70px;width:70px;display:none;">Reference Doc. No.</th>
                                                                <th style="max-width:35px;">CUR.</th>
                                                                <th style="max-width:90px;width:90px;">Amount (Less VAT)</th>
                                                                <th style="max-width:30px;width:30px;display:none;">...</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">TX</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">WHT</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">DC</th>
                                                                <th style="max-width:40px;width:40px;text-align: center;display:none;">Auto Calc</th>
                                                                <th style="max-width:60px;width:60px;text-align: center;">Incrs./ Dcrs.</th>
                                                                <th style="min-width:150px;">Charge Account</th>
                                                                <th style="max-width:80px;width:80px;display:none;">Functional Currency Exchange Rate</th>
                                                                <th style="max-width:50px;width:50px;">QTY</th>
                                                                <th style="max-width:80px;width:80px;">Applied Prepaym't Doc. No.</th>
                                                                <th style="max-width:30px;width:30px;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_RcvblsInvcDocDet($sbmtdAccbRcvblsInvcID);
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
                                                                $rcvblsInvcSlctdAmtBrkdwns = $rowRw[25];
                                                                $trsctnLineApldDocNo = $rowRw[26];
                                                                $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;
                                                                $funcCrncyRate = (float) $rowRw[17];
                                                                $acntCrncyRate = (float) $rowRw[18];
                                                                $trsctnLnQty = (float) $rowRw[28];
                                                                $trsctnLnTxID = (int) $rowRw[31];
                                                                $trsctnLnWHTxID = (int) $rowRw[32];
                                                                $trsctnLnDscntID = (int) $rowRw[33];
                                                                $trsctn_IsWHTax = $rowRw[30];
                                                                $trsctnLnTxNm = ($trsctnLnTxID > 0) ? $rowRw[34] : "View Non-WHT Tax Codes";
                                                                $trsctnLnWHTxNm = ($trsctnLnWHTxID > 0) ? $rowRw[35] : "View WHT Tax Codes";
                                                                $trsctnLnDscntNm = ($trsctnLnDscntID > 0) ? $rowRw[36] : "View Discounts";
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneAccbRcvblsInvcSmryRow_<?php echo $cntr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>       
                                                                    <td class="lovtd" style="display:none;">
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_ItemType" style="width:100% !important;">
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
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $trsctnCodeBhndID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_InitAmntLnID" value="<?php echo $trsctnInitAmntLnID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_IsWHTax" value="<?php echo $trsctn_IsWHTax; ?>" style="width:100% !important;">    
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="<?php echo $rcvblsInvcSlctdAmtBrkdwns; ?>" style="width:100% !important;"> 
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetDesc');">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbRcvblsInvcLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnCodeBhndID; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_CodeBhndID', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'clear', 1, '', function () {
                                                                                                                    getAccbRcvblsCodeBhndInfo('oneAccbRcvblsInvcSmryRow_<?php echo $cntr; ?>');
                                                                                                                });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <input type="text" class="form-control jbDetDesc" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" readonly="true"/>
                                                                            <!--<span><?php echo $trsctnLineDesc; ?></span>-->
                                                                        <?php } ?>
                                                                    </td>                                                 
                                                                    <td class="lovtd"  style="display:none;">
                                                                        <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_RefDoc" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_RefDoc', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetRfDc');">                                                    
                                                                    </td>                                          
                                                                    <td class="lovtd" style="max-width:35px;width:35px;">
                                                                        <div class="" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="">
                                                                                <span class="" id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                                                        echo number_format($entrdAmnt, 2);
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbRcvblsInvcSmryTtl();">                                                    
                                                                    </td>   
                                                                    <td class="lovtd" style="display:none;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Amount Breakdown" 
                                                                                onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;"> 
                                                                            <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                        </button>
                                                                    </td>     
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxNm" value="<?php echo $trsctnLnTxNm; ?>" style="width:100% !important;"> 

                                                                        <?php
                                                                        if ($trsctnLineType == "1Initial Amount") {
                                                                            ?>
                                                                            <button id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnTxNm; ?>" 
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Non-WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxID', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'clear', 0, '', function () {
                                                                                                                        changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxBtn');
                                                                                                                    });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <span>&nbsp;</span>
                                                                        <?php } ?>
                                                                    </td>  
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxID" value="<?php echo $trsctnLnWHTxID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm" value="<?php echo $trsctnLnWHTxNm; ?>" style="width:100% !important;">   

                                                                        <?php
                                                                        if ($trsctnLineType == "1Initial Amount") {
                                                                            ?>
                                                                            <button id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnWHTxNm; ?>" 
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnWHTxID; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxID', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm', 'clear', 0, '', function () {
                                                                                                                        changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxBtn');
                                                                                                                    });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/tg-tax-icon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <span>&nbsp;</span>
                                                                        <?php } ?>
                                                                    </td>   
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntNm" value="<?php echo $trsctnLnDscntNm; ?>" style="width:100% !important;">  

                                                                        <?php
                                                                        if ($trsctnLineType == "1Initial Amount") {
                                                                            ?>
                                                                            <button id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnDscntNm; ?>" 
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntID', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'clear', 0, '', function () {
                                                                                                                        changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntBtn');
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
                                                                                    <input type="checkbox" class="form-check-input" id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AutoCalc" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AutoCalc" <?php echo $isChkd ?>>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </td>    
                                                                    <td class="lovtd">
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountID1', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {
                                                                                                                    changeElmntTitleFunc('oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1');
                                                                                                                });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>    
                                                                        <?php } else { ?>
                                                                            <span><?php echo $trsctnAcntNm1; ?></span>
                                                                        <?php } ?>                                             
                                                                    </td>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                                                        echo number_format($funcCrncyRate, 4);
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbRcvblsInvcSmryTtl();">                                                    
                                                                    </td> 
                                                                    <td class="lovtd" style="">
                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_QTY" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                        echo $trsctnLnQty;
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_QTY', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbRcvblsInvcSmryTtl();">                                                    
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_ApldDocNum" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_ApldDocNum" value="<?php
                                                                        echo $trsctnLineApldDocNo;
                                                                        ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbRcvblsInvcSmryTtl();">                                                    
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbRcvblsInvcDetLn('oneAccbRcvblsInvcSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Receivables Invoice Line">
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
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdRIJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdRIJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                                </th>
                                                                <th  style="display:none;">&nbsp;</th>                                           
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th  style="display:none;">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th  style="display:none;">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="rcvblsInvcExtraInfo" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row"  style="padding:0px 15px 0px 15px;">
                                                <div class="col-md-4" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 0px !important;">                              
                                                    <div class="form-group">
                                                        <label for="accbRcvblsInvcDfltBalsAcnt" class="control-label col-md-4">Receivable Account:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" id="accbRcvblsInvcDfltBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $accbRcvblsInvcDfltBalsAcnt; ?>" readonly="true"/>
                                                                <input type="hidden" id="accbRcvblsInvcDfltBalsAcntID" value="<?php echo $accbRcvblsInvcDfltBalsAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbRcvblsInvcDfltBalsAcntID', 'accbRcvblsInvcDfltBalsAcnt', 'clear', 1, '', function () {});">
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
                                                                <textarea class="form-control" rows="2" cols="20" id="accbRcvblsInvcPayTerms" name="accbRcvblsInvcPayTerms" <?php echo $mkReadOnly; ?> style="text-align:left !important;"><?php echo $accbRcvblsInvcPayTerms; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbRcvblsInvcPayTerms');" style="max-width:30px;width:30px;">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Linked Document:</label>
                                                        </div>
                                                        <div class="col-md-8" style="padding:0px 0px 0px 0px;">
                                                            <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbRcvblsInvcEvntDocTyp" style="width:100% !important;" onchange="lnkdEvntAccbRcvblsInvcChng();">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "");
                                                                    $srchInsArrys = array("None", "Attendance Register", "Production Process Run",
                                                                        "Customer File Number", "Project Management");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($accbRcvblsInvcEvntDocTyp == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-7" style="padding:0px 15px 0px 1px;">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="accbRcvblsInvcEvntCtgry" name="accbRcvblsInvcEvntCtgry" value="<?php echo $accbRcvblsInvcEvntCtgry; ?>" readonly="true">
                                                                    <label id="accbRcvblsInvcEvntCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbRILovCtgry('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', '', '', '', 'radio', true, '', 'accbRcvblsInvcEvntCtgry', 'accbRcvblsInvcEvntCtgry', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="padding:2px 15px 0px 15px;">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbRcvblsInvcEvntRgstr" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Linked Document Number" type = "text" min="0" placeholder="" value="<?php echo $accbRcvblsInvcEvntRgstr; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbRcvblsInvcEvntRgstrID" value="<?php echo $accbRcvblsInvcEvntRgstrID; ?>">
                                                                    <label id="accbRcvblsInvcEvntRgstrLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbRILovEvnt('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbRcvblsInvcEvntRgstrID', 'accbRcvblsInvcEvntRgstr', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="padding: 0px 1px 0px 1px;">
                                                    <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 2px 5px 2px !important;margin-left:3px !important;">
                                                        <table class="table table-striped table-bordered table-responsive" id="oneAccbRcvblsInvcExtrInfTable" cellspacing="0" width="100%" style="width:100%;min-width: 200px !important;">
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
                                                                        $vwSQLStmnt, getMdlGrpID("Receivable Invoices", $mdlNm),
                                                                        $sbmtdAccbRcvblsInvcID, "accb.accb_all_other_info_table");
                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $extrInfoCtgry = $rowRw[0];
                                                                    $extrInfoLbl = $rowRw[1];
                                                                    $extrInfoVal = $rowRw[2];
                                                                    $cmbntnID = (float) $rowRw[3];
                                                                    $tableID = (float) $rowRw[4];
                                                                    $dfltRowID = (float) $rowRw[5];
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneAccbRcvblsInvcExtrInfRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                        <td class="lovtd"  style="">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcExtrInfRow<?php echo $cntr; ?>_DfltRowID" value="<?php echo $dfltRowID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcExtrInfRow<?php echo $cntr; ?>_CombntnID" value="<?php echo $cmbntnID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcExtrInfRow<?php echo $cntr; ?>_TableID" value="<?php echo $tableID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcExtrInfRow<?php echo $cntr; ?>_extrInfoCtgry" value="<?php echo $extrInfoCtgry; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcExtrInfRow<?php echo $cntr; ?>_extrInfoLbl" value="<?php echo $extrInfoLbl; ?>" style="width:100% !important;">
                                                                            <span><?php echo $extrInfoCtgry; ?></span>                                                    
                                                                        </td>                                                
                                                                        <td class="lovtd"  style="">
                                                                            <span><?php echo $extrInfoCtgry; ?></span>                                                    
                                                                        </td>                                                 
                                                                        <td class="lovtd"  style="">
                                                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbRcvblsInvcExtrInfRow<?php echo $cntr; ?>_Value" name="oneAccbRcvblsInvcExtrInfRow<?php echo $cntr; ?>_Value" value="<?php echo $extrInfoVal; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcExtrInfRow<?php echo $cntr; ?>_Value', 'oneAccbRcvblsInvcExtrInfTable', 'jbDetRfDc');">                                                    
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
                $accbRcvblsInvcInvcCur = isset($_POST['accbRcvblsInvcInvcCur']) ? cleanInputData($_POST['accbRcvblsInvcInvcCur']) : $fnccurnm;
                $accbRcvblsInvcInvcCurID = getPssblValID($accbRcvblsInvcInvcCur, getLovID("Currencies"));
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneAccbRcvblsInvcSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                            <thead>
                                <tr>
                                    <th style="max-width:30px;width:30px;">No.</th>
                                    <th style="max-width:90px;width:90px;text-align: center;display:none;">Item Type</th>
                                    <th style="min-width:250px;">Item Description</th>
                                    <th style="max-width:70px;width:70px;display:none;">Reference Doc. No.</th>
                                    <th style="max-width:35px;">CUR.</th>
                                    <th style="max-width:90px;width:90px;">Amount (Less VAT)</th>
                                    <th style="max-width:30px;width:30px;display:none;">...</th>
                                    <th style="max-width:30px;width:30px;text-align: center;">TX</th>
                                    <th style="max-width:30px;width:30px;text-align: center;">WHT</th>
                                    <th style="max-width:30px;width:30px;text-align: center;">DC</th>
                                    <th style="max-width:40px;width:40px;text-align: center;display:none;">Auto Calc</th>
                                    <th style="max-width:60px;width:60px;text-align: center;">Incrs./ Dcrs.</th>
                                    <th style="min-width:150px;">Charge Account</th>
                                    <th style="max-width:80px;width:80px;display:none;">Functional Currency Exchange Rate</th>
                                    <th style="max-width:50px;width:50px;">QTY</th>
                                    <th style="max-width:80px;width:80px;">Applied Prepaym't Doc. No.</th>
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
                                    $entrdCurID = $accbRcvblsInvcInvcCurID;
                                    $entrdAmnt = 0.00;
                                    $entrdCurNm = $accbRcvblsInvcInvcCur;
                                    $trsctnCodeBhndID = (int) $rowRw[7];
                                    $shdAutoCalc = $rowRw[6];
                                    $trnsIncrsDcrs1 = $rowRw[3];
                                    $trsctnAcntID1 = $rowRw[4];
                                    $trsctnAcntNm1 = $rowRw[5];

                                    $trnsIncrsDcrs2 = "";
                                    $trsctnAcntID2 = -1;
                                    $trsctnAcntNm2 = "";
                                    $rcvblsInvcSlctdAmtBrkdwns = "";
                                    $trsctnLineApldDocNo = "";
                                    $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;
                                    $funcCrncyRate = 1.0000;
                                    $acntCrncyRate = 1.0000;
                                    $trsctnLnQty = 1;
                                    $trsctnLnTxID = -1;
                                    $trsctnLnWHTxID = -1;
                                    $trsctnLnDscntID = -1;
                                    $trsctn_IsWHTax = $rowRw[8];
                                    $trsctnLnTxNm = "View Non-WHT Tax Codes";
                                    $trsctnLnWHTxNm = "View WHT Tax Codes";
                                    $trsctnLnDscntNm = "View Discounts";
                                    $cntr += 1;
                                    ?>
                                    <tr id="oneAccbRcvblsInvcSmryRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>       
                                        <td class="lovtd" style="display:none;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_ItemType" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $trsctnCodeBhndID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_InitAmntLnID" value="<?php echo $trsctnInitAmntLnID; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_IsWHTax" value="<?php echo $trsctn_IsWHTax; ?>" style="width:100% !important;">    
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="<?php echo $rcvblsInvcSlctdAmtBrkdwns; ?>" style="width:100% !important;"> 
                                            <?php
                                            if ($canEdt === true) {
                                                ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetDesc');">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbRcvblsInvcLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnCodeBhndID; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_CodeBhndID', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'clear', 1, '', function () {
                                                                                        getAccbRcvblsCodeBhndInfo('oneAccbRcvblsInvcSmryRow_<?php echo $cntr; ?>');
                                                                                    });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <input type="text" class="form-control jbDetDesc" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" readonly="true"/>
                                                <!--<span><?php echo $trsctnLineDesc; ?></span>-->
                                            <?php } ?>
                                        </td>                                                 
                                        <td class="lovtd"  style="display:none;">
                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_RefDoc" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_RefDoc', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetRfDc');">                                                    
                                        </td>                                          
                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                            <div class="" style="width:100% !important;">
                                                <label class="btn btn-primary btn-file" onclick="">
                                                    <span class="" id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                </label>
                                            </div>                                              
                                        </td>
                                        <td class="lovtd">
                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                            echo number_format($entrdAmnt, 2);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbRcvblsInvcSmryTtl();">                                                    
                                        </td>   
                                        <td class="lovtd" style="display:none;">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Amount Breakdown" 
                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;"> 
                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                            </button>
                                        </td>     
                                        <td class="lovtd" style="text-align: center;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxNm" value="<?php echo $trsctnLnTxNm; ?>" style="width:100% !important;"> 

                                            <?php if ($trsctnLineType == "1Initial Amount") { ?>
                                                <button id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnTxNm; ?>" 
                                                        onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Non-WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxID', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'clear', 0, '', function () {
                                                                                            changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_TaxBtn');
                                                                                        });" style="padding:2px !important;"> 
                                                    <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                </button>
                                            <?php } else { ?>
                                                <span>&nbsp;</span>
                                            <?php } ?>
                                        </td>  
                                        <td class="lovtd" style="text-align: center;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxID" value="<?php echo $trsctnLnWHTxID; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm" value="<?php echo $trsctnLnWHTxNm; ?>" style="width:100% !important;">   

                                            <?php if ($trsctnLineType == "1Initial Amount") { ?>
                                                <button id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnWHTxNm; ?>" 
                                                        onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'WHT Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnWHTxID; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxID', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm', 'clear', 0, '', function () {
                                                                                            changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxNm', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_WHTaxBtn');
                                                                                        });" style="padding:2px !important;"> 
                                                    <img src="cmn_images/tg-tax-icon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                </button>
                                            <?php } else { ?>
                                                <span>&nbsp;</span>
                                            <?php } ?>
                                        </td>   
                                        <td class="lovtd" style="text-align: center;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntNm" value="<?php echo $trsctnLnDscntNm; ?>" style="width:100% !important;">  

                                            <?php if ($trsctnLineType == "1Initial Amount") { ?>
                                                <button id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntBtn" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnDscntNm; ?>" 
                                                        onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntID', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'clear', 0, '', function () {
                                                                                            changeBtnTitleFunc('oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_DscntBtn');
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
                                                        <input type="checkbox" class="form-check-input" id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AutoCalc" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AutoCalc" <?php echo $isChkd ?>>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>    
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                            <?php
                                            if ($canEdt === true) {
                                                ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountID1', 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {
                                                                                        changeElmntTitleFunc('oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_AccountNm1');
                                                                                    });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm1; ?></span>
                                            <?php } ?>                                             
                                        </td>
                                        <td class="lovtd" style="display:none;">
                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                            echo number_format($funcCrncyRate, 4);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_FuncExchgRate', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbRcvblsInvcSmryTtl();">                                                    
                                        </td> 
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_QTY" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                            echo $trsctnLnQty;
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_QTY', 'oneAccbRcvblsInvcSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbRcvblsInvcSmryTtl();">                                                    
                                        </td>
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control" aria-label="..." id="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_ApldDocNum" name="oneAccbRcvblsInvcSmryRow<?php echo $cntr; ?>_ApldDocNum" value="<?php
                                            echo $trsctnLineApldDocNo;
                                            ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbRcvblsInvcSmryTtl();">                                                    
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbRcvblsInvcDetLn('oneAccbRcvblsInvcSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Receivables Invoice Line">
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
                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdRIJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                2, '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdRIJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                    </th>
                                    <th  style="display:none;">&nbsp;</th>                                           
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th  style="display:none;">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th  style="display:none;">&nbsp;</th>
                                    <th style="">&nbsp;</th>
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
                $sbmtdAccbRcvblsInvcID = isset($_POST['sbmtdAccbRcvblsInvcID']) ? (float) cleanInputData($_POST['sbmtdAccbRcvblsInvcID']) : -1;
                $accbRcvblsInvcCstmrID = isset($_POST['accbRcvblsInvcCstmrID']) ? (float) cleanInputData($_POST['accbRcvblsInvcCstmrID']) : -1;
                $accbRcvblsInvcCstmrSiteID = isset($_POST['accbRcvblsInvcCstmrSiteID']) ? (float) cleanInputData($_POST['accbRcvblsInvcCstmrSiteID']) : -1;
                $accbRcvblsInvcInvcCurID = isset($_POST['accbRcvblsInvcInvcCurID']) ? (float) cleanInputData($_POST['accbRcvblsInvcInvcCurID']) : -1;
                $accbRcvblsInvcVchType = isset($_POST['accbRcvblsInvcVchType']) ? cleanInputData($_POST['accbRcvblsInvcVchType']) : "";
                $lineDesc = isset($_POST['accbRcvblsInvcDesc']) ? cleanInputData($_POST['accbRcvblsInvcDesc']) : "";
                $ln_CodeBhndID = isset($_POST['ln_CodeBhndID']) ? (float) cleanInputData($_POST['ln_CodeBhndID']) : -1;
                $ln_ItemType = isset($_POST['ln_ItemType']) ? (float) cleanInputData($_POST['ln_ItemType']) : "";
                $ttlInitAmount = isset($_POST['ttlInitAmount']) ? (float) cleanInputData($_POST['ttlInitAmount']) : 0.00;
                $ttlDscntAmount = isset($_POST['ttlDscntAmount']) ? (float) cleanInputData($_POST['ttlDscntAmount']) : 0.00;

                $txsmmryNm = "";
                $codeAmnt = 0.00;
                $accnts = array("Increase", /* Balancing Account */ "-1", "Increase", /* Charge Account */ "-1");
                $txlineDesc = "";
                if ($accbRcvblsInvcCstmrSiteID > 0) {
                    $accbRcvblsInvcCstmrSiteID = get_CstmrSpplrSiteLnkID($accbRcvblsInvcCstmrID, $accbPyblsInvcSpplrSiteID);
                    if ($accbRcvblsInvcCstmrSiteID <= 0) {
                        $accbRcvblsInvcCstmrSiteID = get_DfltCstmrSpplrSiteID($accbRcvblsInvcCstmrID);
                    }
                } else {
                    $accbRcvblsInvcCstmrSiteID = get_DfltCstmrSpplrSiteID($accbRcvblsInvcCstmrID);
                }
                if ($ln_ItemType == "2Tax") {
                    $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_CodeBhndID);
                    $codeAmnt = getCodeAmnt($ln_CodeBhndID, $ttlInitAmount - $ttlDscntAmount);
                    $accnts = getRcvblBalncnAccnt("2Tax", $ln_CodeBhndID, $accbRcvblsInvcCstmrID, -1, $accbRcvblsInvcVchType);
                    $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $ttlInitAmount . ")";
                } else if ($ln_ItemType == "3Discount" || $ln_ItemType == "4Extra Charge") {
                    $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_CodeBhndID);
                    $codeAmnt = getCodeAmnt($ln_CodeBhndID, $ttlInitAmount);
                    $accnts = getRcvblBalncnAccnt($ln_ItemType, $ln_CodeBhndID, $accbRcvblsInvcCstmrID, -1, $accbRcvblsInvcVchType);
                    $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $ttlInitAmount . ")";
                } else if ($ln_ItemType == "5Applied Prepayment") {
                    $txsmmryNm = getGnrlRecNm("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_hdr_id", "rcvbls_invc_number", $ln_CodeBhndID);
                    $codeAmnt = (float) getGnrlRecNm("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_hdr_id", "amnt_paid-invc_amnt_appld_elswhr",
                                    $ln_CodeBhndID);
                    $accnts = getRcvblBalncnAccnt($ln_ItemType, -1, $accbRcvblsInvcCstmrID, $ln_CodeBhndID, $accbRcvblsInvcVchType);
                    $txlineDesc = "Application of Prepayment Doc. No. " . $txsmmryNm . " on " . $lineDesc . " (" . $ttlInitAmount . ")";
                } else {
                    $accnts = getRcvblBalncnAccnt("", -1, $accbRcvblsInvcCstmrID, -1, $accbRcvblsInvcVchType);
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
                $arr_content['accbRcvblsInvcCstmrSiteID'] = $accbRcvblsInvcCstmrSiteID;
                $arr_content['accbRcvblsInvcCstmrSite'] = getCstmrSiteNm($accbRcvblsInvcCstmrSiteID, $accbRcvblsInvcCstmrID);
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdAccbRcvblsInvcID = isset($_POST['sbmtdAccbRcvblsInvcID']) ? cleanInputData($_POST['sbmtdAccbRcvblsInvcID']) : -1;
                if (!$canAdd || ($sbmtdAccbRcvblsInvcID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdAccbRcvblsInvcID;
                $total = get_Total_RcvblsInvc_Attachments($srchFor, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_RcvblsInvc_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>       
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdRcvblsInvcDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdRcvblsInvcDocsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                    . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdRcvblsInvcDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdRcvblsInvcDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdRcvblsInvcDocsRow_WWW123WWW_DocCtgryNm', 'attchdRcvblsInvcDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdRcvblsInvcDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToRcvblsInvcDocs('attchdRcvblsInvcDocsRow_WWW123WWW_DocFile','attchdRcvblsInvcDocsRow_WWW123WWW_AttchdDocsID','attchdRcvblsInvcDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdRcvblsInvcDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdRcvblsInvcDoc('attchdRcvblsInvcDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdRcvblsInvcDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdRcvblsInvcDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttchdRcvblsInvcDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbRcvblsInvcID=<?php echo $sbmtdAccbRcvblsInvcID; ?>', 'ReloadDialog');">
                                    <input id="attchdRcvblsInvcDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdRcvblsInvcDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbRcvblsInvcID=<?php echo $sbmtdAccbRcvblsInvcID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdRcvblsInvcDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbRcvblsInvcID=<?php echo $sbmtdAccbRcvblsInvcID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdRcvblsInvcDocsDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAttchdRcvblsInvcDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbRcvblsInvcID=<?php echo $sbmtdAccbRcvblsInvcID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdRcvblsInvcDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbRcvblsInvcID=<?php echo $sbmtdAccbRcvblsInvcID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdRcvblsInvcDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/RcvblDocs/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                            ?>
                                            <tr id="attchdRcvblsInvcDocsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdRcvblsInvcDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdRcvblsInvcDoc('attchdRcvblsInvcDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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