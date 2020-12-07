<?php
$canAdd = test_prmssns($dfltPrvldgs[99], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[100], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[101], $mdlNm);
$canVoid = test_prmssns($dfltPrvldgs[16], $mdlNm);
$canApprove = test_prmssns($dfltPrvldgs[109], $mdlNm);

$canAdd1 = test_prmssns($dfltPrvldgs[103], $mdlNm);
$canEdt1 = test_prmssns($dfltPrvldgs[104], $mdlNm);
$canDel1 = test_prmssns($dfltPrvldgs[105], $mdlNm);
$canApprove1 = test_prmssns($dfltPrvldgs[110], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Petty Cash Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                $accbPttyCashVchType = getGnrlRecNm("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_hdr_id", "ptycsh_vchr_type", $pKeyID);
                if (($canDel === true && $accbPttyCashVchType == "Petty Cash Payments") || ($canDel1 === true && $accbPttyCashVchType == "Petty Cash Re-imbursements")) {
                    echo deletePttyCashDocHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Petty Cash Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                $pKeyID1 = (float) getGnrlRecNm("accb.accb_ptycsh_amnt_smmrys", "ptycsh_smmry_id", "src_ptycsh_hdr_id", $pKeyID);
                $accbPttyCashVchType = getGnrlRecNm("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_hdr_id", "ptycsh_vchr_type", $pKeyID1);
                if (($canDel === true && $accbPttyCashVchType == "Petty Cash Payments") || ($canDel1 === true && $accbPttyCashVchType == "Petty Cash Re-imbursements")) {
                    echo deletePttyCashDocDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdt || $canEdt1) {
                    echo deletePttyCashDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Petty Cash Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdAccbPttyCashID = isset($_POST['sbmtdAccbPttyCashID']) ? (float) cleanInputData($_POST['sbmtdAccbPttyCashID']) : -1;
                $accbPttyCashDocNum = isset($_POST['accbPttyCashDocNum']) ? cleanInputData($_POST['accbPttyCashDocNum']) : "";
                $accbPttyCashDfltTrnsDte = isset($_POST['accbPttyCashDfltTrnsDte']) ? cleanInputData($_POST['accbPttyCashDfltTrnsDte']) : '';
                $accbPttyCashVchType = isset($_POST['accbPttyCashVchType']) ? cleanInputData($_POST['accbPttyCashVchType']) : '';
                $accbPttyCashInvcCur = isset($_POST['accbPttyCashInvcCur']) ? cleanInputData($_POST['accbPttyCashInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $accbPttyCashInvcCurID = getPssblValID($accbPttyCashInvcCur, $curLovID);
                $accbPttyCashTtlAmnt = isset($_POST['accbPttyCashTtlAmnt']) ? (float) cleanInputData($_POST['accbPttyCashTtlAmnt']) : 0;
                $accbPttyCashEvntDocTyp = isset($_POST['accbPttyCashEvntDocTyp']) ? cleanInputData($_POST['accbPttyCashEvntDocTyp']) : '';
                $accbPttyCashEvntCtgry = isset($_POST['accbPttyCashEvntCtgry']) ? cleanInputData($_POST['accbPttyCashEvntCtgry']) : '';
                $accbPttyCashEvntRgstrID = isset($_POST['accbPttyCashEvntRgstrID']) ? (float) cleanInputData($_POST['accbPttyCashEvntRgstrID']) : -1;
                $accbPttyCashSpplrID = isset($_POST['accbPttyCashSpplrID']) ? (float) cleanInputData($_POST['accbPttyCashSpplrID']) : -1;
                $accbPttyCashSpplrSiteID = isset($_POST['accbPttyCashSpplrSiteID']) ? (float) cleanInputData($_POST['accbPttyCashSpplrSiteID']) : -1;
                $accbPttyCashDfltBalsAcntID = isset($_POST['accbPttyCashDfltBalsAcntID']) ? (float) cleanInputData($_POST['accbPttyCashDfltBalsAcntID']) : -1;
                $accbPttyCashGLBatchID = isset($_POST['accbPttyCashGLBatchID']) ? (float) cleanInputData($_POST['accbPttyCashGLBatchID']) : -1;
                $accbPttyCashDesc = isset($_POST['accbPttyCashDesc']) ? cleanInputData($_POST['accbPttyCashDesc']) : '';
                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;

                if (strlen($accbPttyCashDesc) > 499) {
                    $accbPttyCashDesc = substr($accbPttyCashDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($accbPttyCashDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($accbPttyCashDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                if ($accbPttyCashVchType == "") {
                    $exitErrMsg .= "Document Type cannot be empty!<br/>";
                }
                if ($accbPttyCashDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($accbPttyCashSpplrID <= 0) {
                    $exitErrMsg .= "Payee Name cannot be empty!<br/>";
                }
                if ($accbPttyCashSpplrSiteID <= 0) {
                    $exitErrMsg .= "Supplier Site cannot be empty!<br/>";
                }
                if ($accbPttyCashEvntDocTyp != "None" && ($accbPttyCashEvntCtgry == "" || $accbPttyCashEvntRgstrID <= 0)) {
                    $exitErrMsg .= "Linked Event Number and Category Cannot be empty\r\n if the Event Type is not set to None!<br/>";
                }
                if ($accbPttyCashDfltBalsAcntID <= 0) {
                    $exitErrMsg .= "Please enter a Cash Account!<br/>";
                }
                if ((!$canEdt && !$canAdd && $accbPttyCashVchType == "Petty Cash Payments") || (!$canEdt1 && !$canAdd1 && $accbPttyCashVchType == "Petty Cash Re-imbursements")) {
                    $exitErrMsg .= "Sorry you don't have permission to perform this action";
                }
                $accbPttyCashLtstBals = getAccntLstDailyNetBals($accbPttyCashDfltBalsAcntID, $accbPttyCashDfltTrnsDte);
                $accbPttyCashUnpstdBals = getPttyCashTrnsSumUsngStatus($accbPttyCashDfltBalsAcntID, "0");
                $accbPttyCashNetBals = ($accbPttyCashLtstBals + $accbPttyCashUnpstdBals);
                if ($accbPttyCashVchType == "Petty Cash Payments") {
                    $accbPttyCashNetBals = $accbPttyCashNetBals - $accbPttyCashTtlAmnt;
                    if ($accbPttyCashNetBals <= 0) {
                        $exitErrMsg .= "Sorry you cannot spend more than what you have available in your Petty Cash Account!<br/>";
                    }
                }
                $oldPtyCashID = getGnrlRecID("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_number", "ptycsh_vchr_hdr_id", $accbPttyCashDocNum, $orgID);
                if ($oldPtyCashID > 0 && $oldPtyCashID != $sbmtdAccbPttyCashID) {
                    $exitErrMsg .= "New Document Number/Name is already in use in this Organization!<br/>";
                }
                $apprvlStatus = "Not Validated";
                $nxtApprvlActn = "Approve";
                $pymntTrms = "";
                $srcDocHdrID = -1;
                $srcDocType = "";
                $pymntMthdID = -1;
                $amntPaid = 0;
                $glBtchID = $accbPttyCashGLBatchID;
                $spplrInvcNum = "";
                $docTmpltClsftn = "";
                $amntAppld = 0;
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAccbPttyCashID'] = $sbmtdAccbPttyCashID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdAccbPttyCashID <= 0) {
                    createPttyCashDocHdr($orgID, $accbPttyCashDfltTrnsDte, $accbPttyCashDocNum, $accbPttyCashVchType, $accbPttyCashDesc, $srcDocHdrID, $accbPttyCashSpplrID, $accbPttyCashSpplrSiteID, $apprvlStatus, $nxtApprvlActn, $accbPttyCashTtlAmnt, $pymntTrms, $srcDocType, $pymntMthdID, $amntPaid, $glBtchID, $spplrInvcNum, $docTmpltClsftn, $accbPttyCashInvcCurID, $amntAppld, $accbPttyCashEvntRgstrID, $accbPttyCashEvntCtgry, $accbPttyCashEvntDocTyp, $accbPttyCashDfltBalsAcntID);
                    $sbmtdAccbPttyCashID = getGnrlRecID("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_number", "ptycsh_vchr_hdr_id", $accbPttyCashDocNum, $orgID);
                } else if ($sbmtdAccbPttyCashID > 0) {
                    updtPttyCashDocHdr($sbmtdAccbPttyCashID, $accbPttyCashDfltTrnsDte, $accbPttyCashDocNum, $accbPttyCashVchType, $accbPttyCashDesc, $srcDocHdrID, $accbPttyCashSpplrID, $accbPttyCashSpplrSiteID, $apprvlStatus, $nxtApprvlActn, $accbPttyCashTtlAmnt, $pymntTrms, $srcDocType, $pymntMthdID, $amntPaid, $glBtchID, $spplrInvcNum, $docTmpltClsftn, $accbPttyCashInvcCurID, $amntAppld, $accbPttyCashEvntRgstrID, $accbPttyCashEvntCtgry, $accbPttyCashEvntDocTyp, $accbPttyCashDfltBalsAcntID);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if ($accbPttyCashGLBatchID <= 0 && $shdSbmt == 2) {
                    //$accbPttyCashGLBatchID = getNewJrnlBatchID();
                    $dte = date('ymd');
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    //$userAccntName = getGnrlRecNm("sec.sec_users", "user_id", "user_name", $usrID);
                    $gnrtdTrnsNo1 = "PTYCSH" . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad((getRecCount_LstNum("accb.accb_trnsctn_batches", "batch_name", "batch_id", $gnrtdTrnsNo1 . "%") + 1), 3, '0', STR_PAD_LEFT);
                    createBatch($orgID, $gnrtdTrnsNo, $accbPttyCashDesc, "Petty Cash", "VALID", -1, "0", $accbPttyCashDfltBalsAcntID, "", $accbPttyCashInvcCurID, $accbPttyCashDfltTrnsDte);
                    $accbPttyCashGLBatchID = getBatchID($gnrtdTrnsNo, $orgID);
                }
                if (trim($slctdDetTransLines, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 8) {
                            $lnSmmryLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_FuncExchgRate = 1;
                            $lnSlctdAmtBrkdwns = cleanInputData1($crntRow[1]);
                            $lnRefDoc = cleanInputData1($crntRow[2]);
                            $ln_IncrsDcrs1 = cleanInputData1($crntRow[3]);
                            $ln_AccountID1 =(int) cleanInputData1($crntRow[4]);
                            $ln_IncrsDcrs2 = "Increase";
                            $ln_AccountID2 = $accbPttyCashDfltBalsAcntID;
                            $lineDesc = cleanInputData1($crntRow[5]);
                            $lineCurNm = cleanInputData1($crntRow[6]);
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $entrdAmt =(float) cleanInputData1($crntRow[7]);
                            $lineTransDate = $accbPttyCashDfltTrnsDte;
                            $drCrdt1 = dbtOrCrdtAccnt($ln_AccountID1, substr($ln_IncrsDcrs1, 0, 1)) == "Debit" ? "D" : "C";
                            if ($drCrdt1 == "D") {
                                $ln_IncrsDcrs2 = str_replace("d", "D", str_replace("i", "I", strtolower(incrsOrDcrsAccnt($ln_AccountID2, "Credit"))));
                            } else {
                                $ln_IncrsDcrs2 = str_replace("d", "D", str_replace("i", "I", strtolower(incrsOrDcrsAccnt($ln_AccountID2, "Debit"))));
                            }
                            $drCrdt2 = dbtOrCrdtAccnt($ln_AccountID2, substr($ln_IncrsDcrs2, 0, 1)) == "Debit" ? "D" : "C";
                            $lineDbtAmt1 = ($drCrdt1 == "D") ? $entrdAmt : 0;
                            $lineCrdtAmt1 = ($drCrdt1 == "C") ? $entrdAmt : 0;
                            $lineDbtAmt2 = ($drCrdt2 == "D") ? $entrdAmt : 0;
                            $lineCrdtAmt2 = ($drCrdt2 == "C") ? $entrdAmt : 0;
                            $lineTransDate1 = cnvrtDMYTmToYMDTm($lineTransDate);
                            $lineType = "1Initial Amount";
                            $lineCodeBhnd = -1;
                            $lineAutoCalc = false;
                            $prepayDocHdrID = -1;
                            $vldyStatus = "VALID";
                            $orgnlLnID = -1;
                            $initAmntID = -1;
                            $lnkdVmsHdrID = -1;
                            $funcExchRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
                            $funcCurrAmnt = $funcExchRate * $entrdAmt;

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
                            //Create Petty Cash Summary Trns Record Itself
                            if ($lineDesc != "" && $ln_AccountID1 > 0 && $ln_AccountID2 > 0) {
                                if ($lnSmmryLnID <= 0) {
                                    $lnSmmryLnID = getNewPttyCashLnID();
                                    $afftctd += createPttyCashDocDet($lnSmmryLnID, $sbmtdAccbPttyCashID, $lineType, $lineDesc, $entrdAmt, $lineCurID, $lineCodeBhnd, $accbPttyCashVchType, $lineAutoCalc, $ln_IncrsDcrs1, $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $prepayDocHdrID, $vldyStatus, $orgnlLnID, $fnccurid, $accntCurrID1, $funcExchRate, $acntExchRate1, $funcCurrAmnt, $acntAmnt1, $initAmntID, $lnkdVmsHdrID, $lnRefDoc, $lnSlctdAmtBrkdwns);
                                } else {
                                    $afftctd += updtPttyCashDocDet($lnSmmryLnID, $sbmtdAccbPttyCashID, $lineType, $lineDesc, $entrdAmt, $lineCurID, $lineCodeBhnd, $accbPttyCashVchType, $lineAutoCalc, $ln_IncrsDcrs1, $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $prepayDocHdrID, $vldyStatus, $orgnlLnID, $fnccurid, $accntCurrID1, $funcExchRate, $acntExchRate1, $funcCurrAmnt, $acntAmnt1, $initAmntID, $lnkdVmsHdrID, $lnRefDoc, $lnSlctdAmtBrkdwns);
                                }
                            }
                            //Approve Transaction
                            if ($shdSbmt == 2 && (($canApprove === true && $accbPttyCashVchType == "Petty Cash Payments") || ($canApprove1 === true && $accbPttyCashVchType == "Petty Cash Re-imbursements"))) {
                                //Create First Leg of Summary Transaction as Journal Entry
                                //echo "lnSmmryLnID" . $lnSmmryLnID . "::ln_AccountID1" . $ln_AccountID1;
                                if ($lnSmmryLnID > 0 && $ln_AccountID1 > 0) {
                                    $lnTrnsLnID = getTrnsIDInBatch("Petty Cash", $lnSmmryLnID, $accbPttyCashGLBatchID, $ln_AccountID1, $dbtOrCrdt1);
                                    /* $oldTransID = getTrnsID($lineDesc, $ln_AccountID1, $entrdAmt, $lineCurID, $lineTransDate1);
                                      if ($oldTransID > 0 && $oldTransID != $lnTrnsLnID) {
                                      $exitErrMsg .= "Same Transaction has been created Already!\r\nConsider changing the Date or Time or Description and Try Again!"
                                      . "<br/>Narration:" . $lineDesc
                                      . "<br/>Date:" . $lineTransDate . "<br/>";
                                      continue;
                                      } */
                                    if ($lnTrnsLnID <= 0) {
                                        //Insert
                                        $lnTrnsLnID = getNewTrnsID();
                                        $afftctd1 += createTransaction($lnTrnsLnID, $ln_AccountID1, $lineDesc, $lineDbtAmt1, $lineTransDate, $lineCurID, $accbPttyCashGLBatchID, $lineCrdtAmt1, $netAmnt1, $entrdAmt, $lineCurID, $acntAmnt1, $accntCurrID1, $funcExchRate, $acntExchRate1, $dbtOrCrdt1, $lnRefDoc, $srcTrnsID1, $srcTrnsID2, -1, "Petty Cash", $lnSmmryLnID);
                                    } else if ($lnTrnsLnID > 0) {
                                        $afftctd1 += updateTransaction($ln_AccountID1, $lineDesc, $lineDbtAmt1, $lineTransDate, $lineCurID, $accbPttyCashGLBatchID, $lineCrdtAmt1, $netAmnt1, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt1, $accntCurrID1, $funcExchRate, $acntExchRate1, $dbtOrCrdt1, $lnRefDoc, $srcTrnsID1, $srcTrnsID2, -1, "Petty Cash", $lnSmmryLnID);
                                    }

                                    if (trim($lnSlctdAmtBrkdwns, "|~") != "") {
                                        $variousRows1 = explode("|", trim($lnSlctdAmtBrkdwns, "|"));
                                        for ($z = 0; $z < count($variousRows1); $z++) {
                                            $crntRow1 = explode("~", $variousRows1[$z]);
                                            if (count($crntRow1) == 6) {
                                                $ln_BrkdwnLnID = (float) (cleanInputData1($crntRow1[0]));
                                                $ln_LnkdPValID = (float) (cleanInputData1($crntRow1[1]));
                                                $ln_BrkdwnDesc = cleanInputData1($crntRow1[2]);
                                                $ln_BrkdwnQTY = (float) cleanInputData1($crntRow1[3]);
                                                $ln_BrkdwnUnitVal = (float) cleanInputData1($crntRow1[4]);
                                                $ln_BrkdwnTtl = (float) cleanInputData1($crntRow1[5]);

                                                $oldBrkdwnLnID = get_AccntBrkDwnLnID($lnTrnsLnID, $ln_LnkdPValID);
                                                if ($ln_BrkdwnDesc != "" && $ln_BrkdwnTtl != 0) {
                                                    if ($oldBrkdwnLnID <= 0) {
                                                        $ln_BrkdwnLnID = getNewAmntBrkDwnID();
                                                        $afftctd2 += createAmntBrkDwn($ln_BrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID, $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                    } else if ($oldBrkdwnLnID > 0) {
                                                        $afftctd2 += updateAmntBrkDwn($oldBrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID, $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                //Create Second Leg of Summary Transaction as Journal Entry
                                //echo "lnSmmryLnID" . $lnSmmryLnID . "::ln_AccountID2::" . $ln_AccountID2;
                                if ($lnSmmryLnID > 0 && $ln_AccountID2 > 0) {
                                    $lnTrnsLnID = getTrnsIDInBatch("Petty Cash", $lnSmmryLnID, $accbPttyCashGLBatchID, $ln_AccountID2, $dbtOrCrdt2);
                                    /* $oldTransID = getTrnsID($lineDesc, $ln_AccountID2, $entrdAmt, $lineCurID, $lineTransDate1);
                                      if ($oldTransID > 0 && $oldTransID != $lnTrnsLnID) {
                                      $exitErrMsg .= "Same Transaction has been created Already!\r\nConsider changing the Date or Time or Description and Try Again!"
                                      . "<br/>Narration:" . $lineDesc
                                      . "<br/>Date:" . $lineTransDate . "<br/>";
                                      continue;
                                      } */
                                    if ($lnTrnsLnID <= 0) {
                                        //Insert
                                        $lnTrnsLnID = getNewTrnsID();
                                        $afftctd1 += createTransaction($lnTrnsLnID, $ln_AccountID2, $lineDesc, $lineDbtAmt2, $lineTransDate, $lineCurID, $accbPttyCashGLBatchID, $lineCrdtAmt2, $netAmnt2, $entrdAmt, $lineCurID, $acntAmnt2, $accntCurrID2, $funcExchRate, $acntExchRate2, $dbtOrCrdt2, $lnRefDoc, $srcTrnsID1, $srcTrnsID2, -1, "Petty Cash", $lnSmmryLnID);
                                        //echo "lnTrnsLnID INSIDE 2:" . $lnTrnsLnID;
                                    } else if ($lnTrnsLnID > 0) {
                                        $afftctd1 += updateTransaction($ln_AccountID2, $lineDesc, $lineDbtAmt2, $lineTransDate, $lineCurID, $accbPttyCashGLBatchID, $lineCrdtAmt2, $netAmnt2, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt2, $accntCurrID2, $funcExchRate, $acntExchRate2, $dbtOrCrdt2, $lnRefDoc, $srcTrnsID1, $srcTrnsID2, -1, "Petty Cash", $lnSmmryLnID);
                                    }
                                    //echo "lnTrnsLnID" . $lnTrnsLnID;
                                    if (trim($lnSlctdAmtBrkdwns, "|~") != "") {
                                        $variousRows1 = explode("|", trim($lnSlctdAmtBrkdwns, "|"));
                                        for ($z = 0; $z < count($variousRows1); $z++) {
                                            $crntRow1 = explode("~", $variousRows1[$z]);
                                            if (count($crntRow1) == 6) {
                                                $ln_BrkdwnLnID = (float) (cleanInputData1($crntRow1[0]));
                                                $ln_LnkdPValID = (float) (cleanInputData1($crntRow1[1]));
                                                $ln_BrkdwnDesc = cleanInputData1($crntRow1[2]);
                                                $ln_BrkdwnQTY = (float) cleanInputData1($crntRow1[3]);
                                                $ln_BrkdwnUnitVal = (float) cleanInputData1($crntRow1[4]);
                                                $ln_BrkdwnTtl = (float) cleanInputData1($crntRow1[5]);

                                                $oldBrkdwnLnID = get_AccntBrkDwnLnID($lnTrnsLnID, $ln_LnkdPValID);
                                                if ($ln_BrkdwnDesc != "" && $ln_BrkdwnTtl != 0) {
                                                    if ($oldBrkdwnLnID <= 0) {
                                                        $ln_BrkdwnLnID = getNewAmntBrkDwnID();
                                                        $afftctd2 += createAmntBrkDwn($ln_BrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID, $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                    } else if ($oldBrkdwnLnID > 0) {
                                                        $afftctd2 += updateAmntBrkDwn($oldBrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID, $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                //Final Approval
                if ($shdSbmt == 2 && (($canApprove === true && $accbPttyCashVchType == "Petty Cash Payments") || ($canApprove1 === true && $accbPttyCashVchType == "Petty Cash Re-imbursements"))) {
                    updtPttyCashDocGLBatch($sbmtdAccbPttyCashID, $accbPttyCashGLBatchID);
                    updtPttyCashDocApprvl($sbmtdAccbPttyCashID, "Approved", "Cancel");
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Petty Cash Voucher Successfully Saved!"
                            . "<br/>" . $afftctd . " Petty Cash Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Direct Debit/Credit Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd2 . " Transaction Breakdown(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Petty Cash Voucher Successfully Saved!"
                            . "<br/>" . $afftctd . " Petty Cash Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Direct Debit/Credit Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd2 . " Transaction Breakdown(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdAccbPttyCashID'] = $sbmtdAccbPttyCashID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 2) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdAccbPttyCashID = isset($_POST['sbmtdAccbPttyCashID']) ? cleanInputData($_POST['sbmtdAccbPttyCashID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdAccbPttyCashID;
                if ($attchmentID > 0) {
                    uploadDaPttyCashDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewJrnlBatchDocID();
                    createPttyCashDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaPttyCashDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/PtyCshDocs/" . $nwImgLoc;
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
                //Reverse Petty Cash Voucher
                $errMsg = "";
                $accbPttyCashDesc = isset($_POST['accbPttyCashDesc']) ? cleanInputData($_POST['accbPttyCashDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdAccbPttyCashID = isset($_POST['sbmtdAccbPttyCashID']) ? cleanInputData($_POST['sbmtdAccbPttyCashID']) : -1;
                if (!$canVoid) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $sbmtdJrnlBatchID = (float) getGnrlRecNm("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_hdr_id", "gl_batch_id", $sbmtdAccbPttyCashID);
                $orgnlBatchID = $sbmtdJrnlBatchID;
                $trnsIDStatus2 = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_vldty_status", $orgnlBatchID);
                if (strtoupper($trnsIDStatus2) == "VOID") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Journal Batch has been voided already!</span>";
                    exit();
                }
                $trnsIDStatus1 = getGnrlRecNm("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_hdr_id", "approval_status", $sbmtdAccbPttyCashID);
                if ($trnsIDStatus1 == "Cancelled") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Document Already Cancelled!</span>";
                    exit();
                }
                $gnrtdTrnsNo = "";
                $accbPttyCashDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $accbPttyCashGLBatch = "";
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                if ($sbmtdAccbPttyCashID > 0) {
                    $result = get_One_PttyCashDocHdr($sbmtdAccbPttyCashID);
                    if ($row = loc_db_fetch_array($result)) {
                        $accbPttyCashDfltTrnsDte = $row[1] . " 12:00:00";
                        $accbPttyCashGLBatch = $row[21];
                        $gnrtdTrnsNo = $row[4];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                    }
                }
                $accbPttyCashGLBatch1 = "RVRSL-" . $accbPttyCashGLBatch;
                $gnrtdTrnsNoExistsID2 = (float) getGnrlRecNm2("accb.accb_trnsctn_batches", "batch_name", "batch_id", $accbPttyCashGLBatch1);
                if ($gnrtdTrnsNoExistsID2 > 0) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>A Reversal Batch for the Linked Journal Batch Exists Already!</span>";
                    exit();
                } else {
                    if ($rqStatus == "Not Validated" && $sbmtdAccbPttyCashID > 0) {
                        echo deletePttyCashDocHdrNDet($sbmtdAccbPttyCashID, $accbPttyCashGLBatch);
                        exit();
                    }
                }
                $affctd1 = 0;
                $affctd2 = 0;
                $affctd3 = 0;
                $affctd4 = 0;
                //CREATE
                $rvrsldesc = "";
                $prdHdrID = getPrdHdrID($orgID);
                $tstDate = cnvrtDMYTmToYMDTm($accbPttyCashDfltTrnsDte);
                $prdLnID = getTrnsDteOpenPrdLnID($prdHdrID, $tstDate);
                if ($prdLnID <= 0) {
                    $accbPttyCashDfltTrnsDte = getLtstOpenPrdAfterDate($tstDate);
                }
                if (!isTransPrmttd(get_DfltCashAcnt($orgID), $accbPttyCashDfltTrnsDte, 200, $errMsg)) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>ERROR:" . $errMsg . "</span>";
                    exit();
                }
                $affctd1 += voidJrnlBatch($orgnlBatchID, $accbPttyCashDesc, $accbPttyCashDfltTrnsDte);
                $voidedJrnlBatchID = getBatchID("RVRSL-" . $accbPttyCashGLBatch, $orgID);
                if ($voidedJrnlBatchID > 0) {
                    $affctd2 += voidJrnlBatchTrans($orgnlBatchID, $voidedJrnlBatchID, $accbPttyCashDfltTrnsDte);
                    $rsltCnt = updatePttyCashDocVoid($sbmtdAccbPttyCashID, $accbPttyCashDesc, "Cancelled", "None", $voidedJrnlBatchID);
                    $errMsg = $rsltCnt . " Voucher Reversal Finalized!";
                    $response = array('sbmtdAccbPttyCashID' => $sbmtdAccbPttyCashID,
                        'sbmtMsg' => $errMsg);
                    echo json_encode($response);
                } else {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Failed to reverse Journal Batch!</span>";
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                //Petty Cash Transactions
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Petty Cash Vouchers</span>
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
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_PttyCashDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_PttyCashDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-3";
                    ?> 
                    <form id='accbPttyCashForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">PETTY CASH TRANSACTIONS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php if ($canAdd === true || $canAdd1 === true) { ?>   
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;"> 
                                        <?php if ($canAdd === true) { ?> 
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAccbPttyCashForm(-1, 1, 'ShowDialog', 'Petty Cash Payments');">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Payment
                                            </button> 
                                        <?php } ?>
                                        <?php if ($canAdd1 === true) { ?> 
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAccbPttyCashForm(-1, 1, 'ShowDialog', 'Petty Cash Re-imbursements');">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Reimbursement
                                            </button>
                                        <?php } ?>
                                    </div>
                                    <?php
                                } else {
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-4";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbPttyCashSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAccbPttyCash(event, '', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                        <input id="accbPttyCashPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPttyCash('clear', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPttyCash('', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType2; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbPttyCashSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "", "");
                                            $srchInsArrys = array("All", "Document Number", "Document Description", "Document Classification", "Supplier Name",
                                                "Supplier's Invoice Number", "Source Doc Number", "Approval Status", "Created By");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbPttyCashDsplySze" style="min-width:70px !important;">                            
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
                                                <a href="javascript:getAccbPttyCash('previous', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbPttyCash('next', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>  
                            <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                                <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                        <div class="form-check" style="font-size: 12px !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getAccbPttyCash('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbPttyCashShwUnpstdOnly" name="accbPttyCashShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted
                                            </label>
                                        </div>                            
                                    </div>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="accbPttyCashHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:30px;width:30px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>                                                
                                                <th style="max-width:100px;width:100px;">Document Date</th>
                                                <th style="max-width:100px;width:100px;">Document Number</th>	
                                                <th>Document Description (Petty Cash Account)</th>	
                                                <th>Payee</th>
                                                <th style="text-align:center;max-width:30px;width:30px;">CUR.</th>
                                                <th style="text-align:right;max-width:90px;width:90px;">Total Amount</th>
                                                <th style="max-width:95px;width:95px;">Posting Status</th>
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
                                                <tr id="accbPttyCashHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Account" 
                                                                onclick="getOneAccbPttyCashForm(<?php echo $row[0]; ?>, 1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                        ?>                                
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[6]; ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?></td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[7] . " (" . $row[14] . ")"; ?></td>
                                                    <td class="lovtd"><?php echo $row[9]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[11]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[12], 2);
                                                        ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if ($row[4] == "Approved") {
                                                        $style1 = "color:green;";
                                                    } else if ($row[4] == "Cancelled") {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    ?> 
                                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php
                                                        echo $row[4] . " - " . ($row[15] == "1" ? "Posted" : "Not Posted");
                                                        ?></td>                                          
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delAccbPttyCash('accbPttyCashHdrsRow_<?php echo $cntr; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="accbPttyCashHdrsRow<?php echo $cntr; ?>_HdrID" name="accbPttyCashHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|accb.accb_ptycsh_vchr_hdr|ptycsh_vchr_hdr_id"), $smplTokenWord1));
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
                //New Petty Cash Form
                $sbmtdAccbPttyCashID = isset($_POST['sbmtdAccbPttyCashID']) ? cleanInputData($_POST['sbmtdAccbPttyCashID']) : -1;
                $accbPttyCashVchType = isset($_POST['accbPttyCashVchType']) ? cleanInputData($_POST['accbPttyCashVchType']) : "Petty Cash Payments";
                if ((!$canAdd && !$canAdd1) || ($sbmtdAccbPttyCashID > 0 && !$canEdt && !$canEdt1)) {
                    restricted();
                    exit();
                }
                $orgnlAccbPttyCashID = $sbmtdAccbPttyCashID;
                $accbPttyCashDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $accbPttyCashCreator = $uName;
                $accbPttyCashCreatorID = $usrID;
                $gnrtdTrnsNo = "";
                $accbPttyCashDesc = "";
                $srcAccbPttyCshDocID = -1;
                $srcAccbPttyCshDocTyp = "";
                $accbPttyCashSpplr = "";
                $accbPttyCashSpplrID = -1;
                $accbPttyCashSpplrSite = "";
                $accbPttyCashSpplrSiteID = -1;
                $accbPttyCashSpplrClsfctn = "Supplier";
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve"; //next_aproval_action
                $rqstatusColor = "red";

                $accbPttyCashTtlAmnt = 0;
                $accbPttyCashAppldAmnt = 0;
                $accbPttyCashPayTerms = "";
                $accbPttyCashSrcDcTyp = "";
                $accbPttyCashPayMthd = "";
                $accbPttyCashPayMthdID = -1;
                $accbPttyCashPaidAmnt = 0;
                $accbPttyCashGLBatch = "";
                $accbPttyCashGLBatchID = -1;
                $accbPttyCashSpplrInvcNum = "";
                $accbPttyCashDocTmplt = "";
                $accbPttyCashEvntRgstr = "";
                $accbPttyCashEvntRgstrID = -1;
                $accbPttyCashEvntCtgry = "";
                $accbPttyCashEvntDocTyp = "";
                $accbPttyCashDfltBalsAcntID = -1;
                $accbPttyCashDfltBalsAcnt = "";
                $accbPttyCashInvcCurID = $fnccurid;
                $accbPttyCashInvcCur = $fnccurnm;
                $accbPttyCashIsPstd = "0";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdAccbPttyCashID > 0) {
                    $result = get_One_PttyCashDocHdr($sbmtdAccbPttyCashID);
                    if ($row = loc_db_fetch_array($result)) {
                        $accbPttyCashDfltTrnsDte = $row[1];
                        $accbPttyCashCreator = $row[3];
                        $accbPttyCashCreatorID = $row[2];
                        $gnrtdTrnsNo = $row[4];
                        $accbPttyCashVchType = $row[5];
                        $accbPttyCashDesc = $row[6];
                        $voidedAccbPttyCashID = $row[7];
                        $accbPttyCashSpplr = $row[9];
                        $accbPttyCashSpplrID = $row[8];
                        $accbPttyCashSpplrSite = $row[11];
                        $accbPttyCashSpplrSiteID = $row[10];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                        $rqstatusColor = "red";

                        $accbPttyCashTtlAmnt = $row[14];
                        $accbPttyCashAppldAmnt = $row[14];
                        $accbPttyCashPayTerms = $row[15];
                        $accbPttyCashSrcDcTyp = $row[16];
                        $accbPttyCashPayMthd = $row[18];
                        $accbPttyCashPayMthdID = $row[17];
                        $accbPttyCashPaidAmnt = $row[19];
                        $accbPttyCashGLBatch = $row[21];
                        $accbPttyCashGLBatchID = $row[20];
                        $accbPttyCashSpplrInvcNum = $row[22];
                        $accbPttyCashDocTmplt = $row[23];
                        $accbPttyCashInvcCur = $row[25];
                        $accbPttyCashInvcCurID = $row[24];
                        $accbPttyCashEvntRgstr = "";
                        $accbPttyCashEvntRgstrID = $row[26];
                        $accbPttyCashEvntCtgry = $row[27];
                        $accbPttyCashEvntDocTyp = $row[28];
                        $accbPttyCashDfltBalsAcntID = $row[29];
                        $accbPttyCashDfltBalsAcnt = $row[30];
                        $accbPttyCashIsPstd = $row[31];

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
                    //$sbmtdAccbPttyCashID = getNewAccbPttyCashID();
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypPrfx = ($accbPttyCashVchType === "Petty Cash Payments") ? "PCP" : "PCR";
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_number", "ptycsh_vchr_hdr_id", $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $accbPttyCashDfltBalsAcntID = get_DfltPttyCashAcnt($orgID);
                    $accbPttyCashDfltBalsAcnt = getAccntNum($accbPttyCashDfltBalsAcntID) . "." . getAccntName($accbPttyCashDfltBalsAcntID);
                    if ($accbPttyCashDfltBalsAcntID > 0) {
                        $accbPttyCashInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $accbPttyCashDfltBalsAcntID);
                        $accbPttyCashInvcCur = getPssblValNm($accbPttyCashInvcCurID);
                    }
                    createPttyCashDocHdr($orgID, $accbPttyCashDfltTrnsDte, $gnrtdTrnsNo, $accbPttyCashVchType, $accbPttyCashDesc, $srcAccbPttyCshDocID, $accbPttyCashSpplrID, $accbPttyCashSpplrSiteID, $rqStatus, $rqStatusNext, $accbPttyCashTtlAmnt, $accbPttyCashPayTerms, $srcAccbPttyCshDocTyp, $accbPttyCashPayMthdID, $accbPttyCashPaidAmnt, $accbPttyCashGLBatchID, $accbPttyCashSpplrInvcNum, $accbPttyCashDocTmplt, $accbPttyCashInvcCurID, $accbPttyCashAppldAmnt, $accbPttyCashEvntRgstrID, $accbPttyCashEvntCtgry, $accbPttyCashEvntDocTyp, $accbPttyCashDfltBalsAcntID);
                    $sbmtdAccbPttyCashID = getGnrlRecID("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_number", "ptycsh_vchr_hdr_id", $gnrtdTrnsNo, $orgID);
                }
                $accbPttyCashLtstBals = getAccntLstDailyNetBals($accbPttyCashDfltBalsAcntID, $accbPttyCashDfltTrnsDte);
                $accbPttyCashUnpstdBals = getPttyCashTrnsSumUsngStatus($accbPttyCashDfltBalsAcntID, "0");
                $accbPttyCashNetBals = ($accbPttyCashLtstBals + $accbPttyCashUnpstdBals);
                $style1 = "color:green;";
                if ($accbPttyCashNetBals <= 0) {
                    $style1 = "color:red;";
                }
                $accbPttyCashTrnsAccntsLOV = "Asset and Expenditure Accounts";
                if ($accbPttyCashVchType == "Petty Cash Re-imbursements") {
                    $accbPttyCashTrnsAccntsLOV = "Cheque Clearing Accounts";
                }
                $reportTitle = "Petty Cash Voucher";
                $reportName = "Petty Cash Voucher";
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdAccbPttyCashID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="oneAccbPttyCashEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Doc No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdAccbPttyCashID" name="sbmtdAccbPttyCashID" value="<?php echo $sbmtdAccbPttyCashID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="accbPttyCashDocNum" name="accbPttyCashDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Doc. Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="accbPttyCashDfltTrnsDte" name="accbPttyCashDfltTrnsDte" value="<?php echo $accbPttyCashDfltTrnsDte; ?>" placeholder="Transactions Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" aria-label="..." id="accbPttyCashVchType" name="accbPttyCashVchType" value="<?php echo $accbPttyCashVchType; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Total Amount:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-info btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $accbPttyCashInvcCur; ?>', 'accbPttyCashInvcCur', '', 'clear', 0, '', function () {
                                                                        $('#accbPttyCashInvcCur1').html($('#accbPttyCashInvcCur').val());
                                                                    });">
                                                <span class="" style="font-size: 20px !important;" id="accbPttyCashInvcCur1"><?php echo $accbPttyCashInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="accbPttyCashInvcCur" value="<?php echo $accbPttyCashInvcCur; ?>"> 
                                            <input class="form-control rqrdFld" type="text" id="accbPttyCashTtlAmnt" value="<?php
                                            echo number_format($accbPttyCashTtlAmnt, 2);
                                            ?>"  
                                                   style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbPttyCashTtlAmnt');" <?php echo $mkReadOnly; ?>/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Linked Document:</label>
                                    </div>
                                    <div class="col-md-8" style="padding:0px 0px 0px 0px;">
                                        <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbPttyCashEvntDocTyp" style="width:100% !important;" onchange="lnkdEvntAccbPttyCashChng();">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "");
                                                $srchInsArrys = array("None", "Attendance Register", "Production Process Run", "Customer File Number",
                                                    "Project Management");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($accbPttyCashEvntDocTyp == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-7" style="padding:0px 15px 0px 1px;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="accbPttyCashEvntCtgry" name="accbPttyCashEvntCtgry" value="<?php echo $accbPttyCashEvntCtgry; ?>" readonly="true">
                                                <label id="accbPttyCashEvntCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbPCLovCtgry('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', '', '', '', 'radio', true, '', 'accbPttyCashEvntCtgry', 'accbPttyCashEvntCtgry', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="padding:2px 15px 0px 15px;">
                                            <div class="input-group">
                                                <input class="form-control" id="accbPttyCashEvntRgstr" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Linked Document Number" type = "text" min="0" placeholder="" value="<?php echo $accbPttyCashEvntRgstr; ?>" readonly="true"/>
                                                <input type="hidden" id="accbPttyCashEvntRgstrID" value="<?php echo $accbPttyCashEvntRgstrID; ?>">
                                                <label id="accbPttyCashEvntRgstrLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbPCLovEvnt('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbPttyCashEvntRgstrID', 'accbPttyCashEvntRgstr', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="accbPttyCashSpplr" class="control-label col-md-3">Payee:</label>
                                    <div  class="col-md-9">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbPttyCashSpplr" name="accbPttyCashSpplr" value="<?php echo $accbPttyCashSpplr; ?>" readonly="true">
                                            <input type="hidden" id="accbPttyCashSpplrID" value="<?php echo $accbPttyCashSpplrID; ?>">
                                            <input type="hidden" id="accbPttyCashSpplrClsfctn" value="<?php echo $accbPttyCashSpplrClsfctn; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Payee', 'ShowDialog', function () {}, 'accbPttyCashSpplrID');" data-toggle="tooltip" title="Create/Edit Payee">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'accbPttyCashSpplrClsfctn', 'radio', true, '', 'accbPttyCashSpplrID', 'accbPttyCashSpplr', 'clear', 1, '');" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label for="accbPttyCashSpplrSite" class="control-label col-md-3">Site:</label>
                                    <div  class="col-md-9">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbPttyCashSpplrSite" name="accbPttyCashSpplrSite" value="<?php echo $accbPttyCashSpplrSite; ?>" readonly="true">
                                            <input class="form-control" type="hidden" id="accbPttyCashSpplrSiteID" value="<?php echo $accbPttyCashSpplrSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'accbPttyCashSpplrID', '', '', 'radio', true, '', 'accbPttyCashSpplrSiteID', 'accbPttyCashSpplrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>                                                               
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="input-group"  style="width:100%;">
                                            <input class="form-control" type="hidden" id="accbPttyCashDesc1" value="<?php echo $accbPttyCashDesc; ?>">
                                            <textarea class="form-control rqrdFld" rows="5" cols="20" id="accbPttyCashDesc" name="accbPttyCashDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $accbPttyCashDesc; ?></textarea>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbPttyCashDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label style="margin-bottom:0px !important;">Status:</label>
                                    </div>
                                    <div class="col-md-9">                             
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;width:100% !important;" id="myAccbPttyCashStatusBtn"><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php
                                                echo $rqStatus . ($accbPttyCashIsPstd == "1" ? " [Posted]" : " [Not Posted]");
                                                ?></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-md-4">                               
                                <div class="form-group">
                                    <label for="accbPttyCashDfltBalsAcnt" class="control-label col-md-4">Cash Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="accbPttyCashDfltBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $accbPttyCashDfltBalsAcnt; ?>" readonly="true"/>
                                            <input type="hidden" id="accbPttyCashDfltBalsAcntID" value="<?php echo $accbPttyCashDfltBalsAcntID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Petty Cash Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbPttyCashDfltBalsAcntID', 'accbPttyCashDfltBalsAcnt', 'clear', 1, '', function () {
                                                                        afterAccbPttyCashAccSlctn();
                                                                    });">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:4px !important;">
                                    <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                        <label class=""  style="<?php echo $breadCrmbBckclr; ?>;width:100%!important;padding:3px 3px 3px 5px;border-radius:5px !important;height:33px;">
                                            <span style="font-weight:bold;<?php echo $forecolors; ?>;word-wrap: break-word;">Posted Balance:</span>
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="btn btn-default"  style="width:100%!important;padding:3px 3px 3px 5px;border-radius:5px !important;height:34px;">
                                            <span style="font-size:18px !important;font-weight:bold;color:green;" id="accbPttyCashLtstBals"><?php
                                                echo number_format($accbPttyCashLtstBals, 2);
                                                ?>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                        <label class=""  style="<?php echo $breadCrmbBckclr; ?>;width:100%!important;padding:3px 3px 3px 5px;border-radius:5px !important;height:33px;">
                                            <span style="font-weight:bold;<?php echo $forecolors; ?>;word-wrap: break-word;">Unposted Trns.:</span>
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="btn btn-default"  style="width:100%!important;padding:3px 3px 3px 5px;border-radius:5px !important;height:34px;">
                                            <span style="font-size:18px !important;font-weight:bold;color:brown;" id="accbPttyCashUnpstdBals"><?php
                                                echo number_format($accbPttyCashUnpstdBals, 2);
                                                ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                        <label class=""  style="<?php echo $breadCrmbBckclr; ?>;width:100%!important;padding:3px 3px 3px 5px;border-radius:5px !important;height:41px;">
                                            <span style="font-weight:bold;<?php echo $forecolors; ?>;word-wrap: break-word;">Available Cash Balance:</span>
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="btn btn-default"  style="width:100%!important;padding:3px 3px 3px 5px;border-radius:5px !important;height:42px;">
                                            <span style="font-size:18px !important;font-weight:bold;<?php echo $style1; ?>;" id="accbPttyCashNetBals"><?php
                                                echo number_format($accbPttyCashNetBals, 2);
                                                ?>
                                            </span>
                                        </label>
                                    </div>
                                </div>                              
                                <div class="form-group">
                                    <label for="accbPttyCashGLBatch" class="control-label col-md-4">GL Batch Name:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="accbPttyCashGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $accbPttyCashGLBatch; ?>" readonly="true"/>
                                            <input type="hidden" id="accbPttyCashGLBatchID" value="<?php echo $accbPttyCashGLBatchID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $accbPttyCashGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdAccbPttyCashID; ?>, 'Petty Cash');">
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
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                $nwRowHtml3 = "<tr id=\"oneAccbPttyCashSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAccbPttyCashSmryLinesTable tr').index(this));\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                        <td class=\"lovtd\"  style=\"\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_LineDesc\" name=\"oneAccbPttyCashSmryRow_WWW123WWW_LineDesc\" value=\"\" 
                                                                   style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbPttyCashSmryRow_WWW123WWW_LineDesc', 'oneAccbPttyCashSmryLinesTable', 'jbDetDesc');\">                                                    
                                                        </td>                                                 
                                                        <td class=\"lovtd\"  style=\"\">
                                                            <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_RefDoc\" name=\"oneAccbPttyCashSmryRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbPttyCashSmryRow_WWW123WWW_RefDoc', 'oneAccbPttyCashSmryLinesTable', 'jbDetRfDc');\">                                                    
                                                        </td>                                          
                                                        <td class=\"lovtd\">
                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_TrnsCurNm\" name=\"oneAccbPttyCashSmryRow_WWW123WWW_TrnsCurNm\" value=\"" . $accbPttyCashInvcCur . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneAccbPttyCashSmryRow_WWW123WWW_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                $('#oneAccbPttyCashSmryRow_WWW123WWW_TrnsCurNm1').html($('#oneAccbPttyCashSmryRow_WWW123WWW_TrnsCurNm').val());
                                                                                                afterAccbPttyCashCurSlctn('oneAccbPttyCashSmryRow__WWW123WWW');
                                                                                            });\">
                                                                    <span class=\"\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_TrnsCurNm1\">" . $accbPttyCashInvcCur . "</span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_EntrdAmt\" name=\"oneAccbPttyCashSmryRow_WWW123WWW_EntrdAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneAccbPttyCashSmryRow_WWW123WWW_EntrdAmt', 'oneAccbPttyCashSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllAccbPttyCashSmryTtl();\">                                                    
                                                        </td>     
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Amount Breakdown\" 
                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneAccbPttyCashSmryRow_WWW123WWW_EntrdAmt', 'oneAccbPttyCashSmryRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_IncrsDcrs1\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml3 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml3 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_AccountID1\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_AccountNm1\" name=\"oneAccbPttyCashSmryRow_WWW123WWW_AccountNm1\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '" . $accbPttyCashTrnsAccntsLOV . "', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAccbPttyCashSmryRow_WWW123WWW_AccountID1', 'oneAccbPttyCashSmryRow_WWW123WWW_AccountNm1', 'clear', 1, '', function () {});\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>                                           
                                                        </td>";
                                                /* "<td class=\"lovtd\">
                                                  <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_IncrsDcrs2\" style=\"width:100% !important;\">";
                                                  $valslctdArry = array("", "");
                                                  $srchInsArrys = array("Increase", "Decrease");
                                                  for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                  $nwRowHtml3 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                  }
                                                  $nwRowHtml3 .= "</select>
                                                  </td>
                                                  <td class=\"lovtd\">
                                                  <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_AccountID2\" value=\"-1\" style=\"width:100% !important;\">
                                                  <div class=\"input-group\" style=\"width:100% !important;\">
                                                  <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAccbPttyCashSmryRow_WWW123WWW_AccountNm2\" name=\"oneAccbPttyCashSmryRow_WWW123WWW_AccountNm2\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                  </div>
                                                  </td> */
                                                $nwRowHtml3 .= "<td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbPttyCashDetLn('oneAccbPttyCashSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete GL Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";
                                                $nwRowHtml3 = urlencode($nwRowHtml3);
                                                ?> 
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <button id="addNwAccbPttyCashSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAccbPttyCashRows('oneAccbPttyCashSmryLinesTable', 0, '<?php echo $nwRowHtml3; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>                                 
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPttyCashDocsForm(<?php echo $sbmtdAccbPttyCashID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPttyCashForm(<?php echo $sbmtdAccbPttyCashID; ?>, 1, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdPttyCashValsTtlBtn"><?php echo $accbPttyCashInvcCur; ?> 
                                                                <?php
                                                                echo number_format($accbPttyCashTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdPttyCashValsTtlVal" value="<?php echo $accbPttyCashTtlAmnt; ?>">
                                                        </button>
                                                    </div> 
                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                            <?php
                                                            if ($rqStatus == "Not Validated") {
                                                                ?>
                                                                <?php if (($canEdt === true && $accbPttyCashVchType == "Petty Cash Payments") || ($canEdt1 === true && $accbPttyCashVchType == "Petty Cash Re-imbursements")) {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPttyCashForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                    <?php
                                                                }
                                                                if (($canApprove === true && $accbPttyCashVchType == "Petty Cash Payments") || ($canApprove1 === true && $accbPttyCashVchType == "Petty Cash Re-imbursements")) {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPttyCashForm('<?php echo $fnccurnm; ?>', 2);" data-toggle="tooltip" data-placement="bottom" title="Approve Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Approve
                                                                    </button>    
                                                                <?php } ?>
                                                                <?php
                                                            } else if ($rqStatus == "Approved") {
                                                                if ($canVoid && (($canDel === true && $accbPttyCashVchType == "Petty Cash Payments") || ($canDel1 === true && $accbPttyCashVchType == "Petty Cash Re-imbursements"))) {
                                                                    ?>
                                                                    <button id="fnlzeRvrslAccbPttyCashBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPttyCashRvrslForm('<?php echo $fnccurnm; ?>', 1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Document&nbsp;</button>                                                                   
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>                    
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbPttyCashLnsTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-responsive" id="oneAccbPttyCashSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th style="max-width:20px;width:20px;">No.</th>
                                                            <th style="">Narration/Remark</th>
                                                            <th style="max-width:80px;width:80px;">Reference Doc. No.</th>
                                                            <th style="max-width:35px;width:35px;">CUR.</th>
                                                            <th style="max-width:90px;width:90px;">Entered Amount</th>
                                                            <th style="max-width:20px;width:20px;">...</th>
                                                            <th style="max-width:80px;width:80px;text-align: center;">Increase/ Decrease</th>
                                                            <th style="max-width:300px;width:300px;">Charge Account</th>
                                                            <!--<th style="max-width:50px;width:50px;text-align: center;">I/D</th>
                                                            <th style="max-width:150px;width:150px;">Balancing Account Leg</th>-->
                                                            <th style="max-width:20px;width:20px;">...</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>   
                                                        <?php
                                                        $cntr = 0;
                                                        $resultRw = get_PttyCashDocDet($sbmtdAccbPttyCashID);
                                                        $ttlTrsctnEntrdAmnt = 0;
                                                        $trnsBrkDwnVType = "VIEW";
                                                        if ($mkReadOnly == "") {
                                                            $trnsBrkDwnVType = "EDIT";
                                                        }
                                                        while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                            $trsctnLineID = (float) $rowRw[0];
                                                            $trsctnLineDesc = $rowRw[2];
                                                            $trsctnLineRefDoc = $rowRw[22];
                                                            $entrdCurID = (int) $rowRw[11];
                                                            $entrdAmnt = (float) $rowRw[3];
                                                            $entrdCurNm = $rowRw[12];
                                                            $trnsIncrsDcrs1 = $rowRw[6];
                                                            $trsctnAcntID1 = $rowRw[7];
                                                            $trsctnAcntNm1 = $rowRw[23];

                                                            $trnsIncrsDcrs2 = $rowRw[8];
                                                            $trsctnAcntID2 = $rowRw[9];
                                                            $trsctnAcntNm2 = $rowRw[24];
                                                            $pttyCashSlctdAmtBrkdwns = $rowRw[25];
                                                            $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;

                                                            $cntr += 1;
                                                            ?>
                                                            <tr id="oneAccbPttyCashSmryRow_<?php echo $cntr; ?>">                                    
                                                                <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                <td class="lovtd"  style="">  
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">  
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="<?php echo $pttyCashSlctdAmtBrkdwns; ?>" style="width:100% !important;"> 
                                                                    <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" 
                                                                           style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbPttyCashSmryRow<?php echo $cntr; ?>_LineDesc', 'oneAccbPttyCashSmryLinesTable', 'jbDetDesc');">                                                    
                                                                </td>                                                 
                                                                <td class="lovtd"  style="">
                                                                    <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_RefDoc" name="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbPttyCashSmryRow<?php echo $cntr; ?>_RefDoc', 'oneAccbPttyCashSmryLinesTable', 'jbDetRfDc');">                                                    
                                                                </td>                                          
                                                                <td class="lovtd" style="max-width:35px;width:35px;">
                                                                    <div class="" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_TrnsCurNm" name="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                        <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneAccbPttyCashSmryRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                        $('#oneAccbPttyCashSmryRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneAccbPttyCashSmryRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                                        afterAccbPttyCashCurSlctn('oneAccbPttyCashSmryRow_<?php echo $cntr; ?>');
                                                                                                    });">
                                                                            <span class="" id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                        </label>
                                                                    </div>                                              
                                                                </td>
                                                                <td class="lovtd">
                                                                    <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                                                    echo number_format($entrdAmnt, 2);
                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPttyCashSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbPttyCashSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbPttyCashSmryTtl();">                                                    
                                                                </td>   
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Amount Breakdown" 
                                                                            onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', 'oneAccbPttyCashSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneAccbPttyCashSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;"> 
                                                                        <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                    </button>
                                                                </td>    
                                                                <td class="lovtd">
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                        ?>
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $accbPttyCashTrnsAccntsLOV; ?>', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAccbPttyCashSmryRow<?php echo $cntr; ?>_AccountID1', 'oneAccbPttyCashSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {});">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>    
                                                                    <?php } else { ?>
                                                                        <span><?php echo $trsctnAcntNm1; ?></span>
                                                                    <?php } ?>                                             
                                                                </td>
                                                                <!--<td class="lovtd">
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_IncrsDcrs2" style="width:100% !important;">
                                                                <?php
                                                                $valslctdArry = array("", "");
                                                                $srchInsArrys = array("Increase", "Decrease");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($trnsIncrsDcrs2 == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                                                                                                                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_AccountID2" value="<?php echo $trsctnAcntID2; ?>" style="width:100% !important;"> 
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                                                                                                                                                                                <div class="input-group" style="width:100% !important;">
                                                                                                                                                                                                                                <input type="text" class="form-control" aria-label="..." id="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_AccountNm2" name="oneAccbPttyCashSmryRow<?php echo $cntr; ?>_AccountNm2" value="<?php echo $trsctnAcntNm2; ?>" readonly="true" style="width:100% !important;">
                                                                                                                                                                                                                                </div>    
                                                                <?php } else { ?>
                                                                                                                                                                                                                                <span><?php echo $trsctnAcntNm2; ?></span>
                                                                <?php } ?>                                             
                                                                </td>-->
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbPttyCashDetLn('oneAccbPttyCashSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Petty Cash Line">
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
                                                            <th>&nbsp;</th>
                                                            <th>TOTALS:</th>
                                                            <th>&nbsp;</th>
                                                            <th style="text-align: right;">
                                                                <?php
                                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPCJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt, 2, '.', ',') . "</span>";
                                                                ?>
                                                                <input type="hidden" id="myCptrdPCJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                            </th>
                                                            <th style="">&nbsp;</th>                                           
                                                            <th style="">&nbsp;</th>
                                                            <th style="">&nbsp;</th>
                                                            <!--<th style="">&nbsp;</th>-->
                                                            <th style="">&nbsp;</th>
                                                        </tr>
                                                    </tfoot>
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
            } else if ($vwtyp == 2) {
                //Get Latest Exchange Rate
                header("content-type:application/json");
                $accbPttyCashDfltBalsAcntID = isset($_POST['accbPttyCashDfltBalsAcntID']) ? cleanInputData($_POST['accbPttyCashDfltBalsAcntID']) : -1;
                $accbPttyCashDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $accbPttyCashLtstBals = getAccntLstDailyNetBals($accbPttyCashDfltBalsAcntID, $accbPttyCashDfltTrnsDte);
                $accbPttyCashUnpstdBals = getPttyCashTrnsSumUsngStatus($accbPttyCashDfltBalsAcntID, "0");
                $accbPttyCashNetBals = ($accbPttyCashLtstBals + $accbPttyCashUnpstdBals);
                $style1 = "green";
                if ($accbPttyCashNetBals <= 0) {
                    $style1 = "red;";
                }
                $arr_content['accbPttyCashLtstBals'] = number_format($accbPttyCashLtstBals, 2);
                $arr_content['accbPttyCashUnpstdBals'] = number_format($accbPttyCashUnpstdBals, 2);
                $arr_content['accbPttyCashNetBals'] = number_format($accbPttyCashNetBals, 2);
                $arr_content['Style1'] = $style1;

                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                //var_dump($arr_content);
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdAccbPttyCashID = isset($_POST['sbmtdAccbPttyCashID']) ? cleanInputData($_POST['sbmtdAccbPttyCashID']) : -1;
                if (!$canAdd || ($sbmtdAccbPttyCashID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdAccbPttyCashID;
                $total = get_Total_PttyCash_Attachments($srchFor, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_PttyCash_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>       
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdPttyCashDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdPttyCashDocsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                    . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdPttyCashDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdPttyCashDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdPttyCashDocsRow_WWW123WWW_DocCtgryNm', 'attchdPttyCashDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdPttyCashDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToPttyCashDocs('attchdPttyCashDocsRow_WWW123WWW_DocFile','attchdPttyCashDocsRow_WWW123WWW_AttchdDocsID','attchdPttyCashDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdPttyCashDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdPttyCashDoc('attchdPttyCashDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdPttyCashDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdPttyCashDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttchdPttyCashDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPttyCashID=<?php echo $sbmtdAccbPttyCashID; ?>', 'ReloadDialog');">
                                    <input id="attchdPttyCashDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdPttyCashDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPttyCashID=<?php echo $sbmtdAccbPttyCashID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdPttyCashDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPttyCashID=<?php echo $sbmtdAccbPttyCashID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdPttyCashDocsDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAttchdPttyCashDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPttyCashID=<?php echo $sbmtdAccbPttyCashID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdPttyCashDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPttyCashID=<?php echo $sbmtdAccbPttyCashID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdPttyCashDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/PtyCshDocs/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                            ?>
                                            <tr id="attchdPttyCashDocsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdPttyCashDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdPttyCashDoc('attchdPttyCashDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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
            ?>
            <?php
        }
    }
}
?>