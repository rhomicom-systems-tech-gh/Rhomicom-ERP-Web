<?php
$canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);
$canVoid = test_prmssns($dfltPrvldgs[16], $mdlNm);
$canPost = test_prmssns($dfltPrvldgs[21], $mdlNm);

$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Journal Batch */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteBatch($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2 || $actyp == 3) {
                /* Delete Journal Detail Line */
                /* Delete Journal Edit Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteJrnlDetln($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Journal Summary Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteJrnlSmmryln($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdtTrns) {
                    echo deleteJrnlBatchDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $voidedJrnlBatchID = isset($_POST['voidedJrnlBatchID']) ? (float) cleanInputData($_POST['voidedJrnlBatchID']) : -1;
                $jrnlBatchNum = isset($_POST['jrnlBatchNum']) ? cleanInputData($_POST['jrnlBatchNum']) : "";
                $jrnlBatchCreationDate = isset($_POST['jrnlBatchCreationDate']) ? cleanInputData($_POST['jrnlBatchCreationDate']) : '';
                $jrnlBatchSource = "Manual";
                // isset($_POST['jrnlBatchSource']) ? cleanInputData($_POST['jrnlBatchSource']) : 'Manual';
                $jrnlBatchDfltCurNm = isset($_POST['jrnlBatchDfltCurNm']) ? cleanInputData($_POST['jrnlBatchDfltCurNm']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $jrnlBatchDfltCurID = getPssblValID($jrnlBatchDfltCurNm, $curLovID);
                $jrnlBatchDfltTrnsDte = isset($_POST['jrnlBatchDfltTrnsDte']) ? cleanInputData($_POST['jrnlBatchDfltTrnsDte']) : '';
                $jrnlBatchDesc = isset($_POST['jrnlBatchDesc']) ? cleanInputData($_POST['jrnlBatchDesc']) : '';
                $jrnlBatchDfltBalsAcntID = isset($_POST['jrnlBatchDfltBalsAcntID']) ? (int) cleanInputData($_POST['jrnlBatchDfltBalsAcntID']) : -1;

                $slctdBatchDetLines = isset($_POST['slctdBatchDetLines']) ? cleanInputData($_POST['slctdBatchDetLines']) : '';
                $slctdBatchSmryLines = isset($_POST['slctdBatchSmryLines']) ? cleanInputData($_POST['slctdBatchSmryLines']) : '';
                $slctdBatchEditLines = isset($_POST['slctdBatchEditLines']) ? cleanInputData($_POST['slctdBatchEditLines']) : '';
                $rvrsalReason = "";
                if ($voidedJrnlBatchID > 0) {
                    $rvrsalReason = $jrnlBatchDesc;
                }
                if (strlen($jrnlBatchDesc) > 499) {
                    $jrnlBatchDesc = substr($jrnlBatchDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($jrnlBatchNum == "") {
                    $exitErrMsg .= "Please enter Batch Number!<br/>";
                }
                if ($jrnlBatchDesc == "") {
                    $exitErrMsg .= "Please enter Batch Description!<br/>";
                }

                $oldBatchID = getBatchID($jrnlBatchNum, $orgID);
                if ($oldBatchID > 0 && $oldBatchID != $sbmtdJrnlBatchID) {
                    $exitErrMsg .= "New Batch Number/Name is already in use in this Organization!<br/>";
                }

                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdJrnlBatchID'] = $sbmtdJrnlBatchID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }

                if ($sbmtdJrnlBatchID <= 0) {
                    createBatch($orgID, $jrnlBatchNum, $jrnlBatchDesc, $jrnlBatchSource, "VALID", $voidedJrnlBatchID, "1",
                            $jrnlBatchDfltBalsAcntID, $rvrsalReason, $jrnlBatchDfltCurID, $jrnlBatchDfltTrnsDte);
                    $sbmtdJrnlBatchID = getBatchID($jrnlBatchNum, $orgID);
                } else if ($sbmtdJrnlBatchID > 0) {
                    updateBatch($sbmtdJrnlBatchID, $jrnlBatchNum, $jrnlBatchDesc, $jrnlBatchDfltBalsAcntID, $rvrsalReason,
                            $jrnlBatchDfltCurID, $jrnlBatchDfltTrnsDte);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdBatchDetLines, "|~") != "") {
                    //Save Direct Debit/Credit Lines
                    $variousRows = explode("|", trim($slctdBatchDetLines, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 10) {
                            $lnTrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $lnTrnsSmryLnID = (float) (cleanInputData1($crntRow[1]));
                            $lnSlctdAmtBrkdwns = cleanInputData1($crntRow[2]);
                            $lnRefDoc = cleanInputData1($crntRow[3]);
                            $lineAccntID = cleanInputData1($crntRow[4]);
                            $lineDesc = cleanInputData1($crntRow[5]);
                            $lineCurNm = cleanInputData1($crntRow[6]);
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $lineDbtAmt = (float) cleanInputData1($crntRow[7]);
                            $lineCrdtAmt = (float) cleanInputData1($crntRow[8]);
                            $entrdAmt = ($lineDbtAmt >= $lineCrdtAmt) ? ($lineDbtAmt - $lineCrdtAmt) : ($lineCrdtAmt - $lineDbtAmt);
                            $drCrdt = ($lineDbtAmt >= $lineCrdtAmt) ? "D" : "C";
                            $lineTransDate = cleanInputData1($crntRow[9]);

                            $lineTransDate1 = cnvrtDMYTmToYMDTm($lineTransDate);
                            $oldTransID = getTrnsID($lineDesc, $lineAccntID, $entrdAmt, $lineCurID, $lineTransDate1);
                            $netAmnt = drCrAccMltplr($lineAccntID, $drCrdt) * $entrdAmt;
                            if ($oldTransID > 0 && $oldTransID != $lnTrnsLnID) {
                                $exitErrMsg .= "Same Transaction has been created Already!\r\nConsider changing the Date or Time and Try Again!"
                                        . "<br/>Narration:" . $lineDesc
                                        . "<br/>Date:" . $lineTransDate . "<br/>";
                                continue;
                            }
                            $dbtOrCrdt = substr(strtoupper($drCrdt), 0, 1);
                            $accntCurrID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lineAccntID);
                            $acntExchRate = round(get_LtstExchRate($lineCurID, $accntCurrID, $lineTransDate), 4);
                            $funcExchRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
                            $acntAmnt = $acntExchRate * $entrdAmt;
                            $srcTrnsID = -1;
                            $errMsg = "";

                            if ($lineDesc != "" && $lineAccntID > 0 && $lnTrnsSmryLnID <= 0) {
                                if ($lnTrnsLnID <= 0) {
                                    //Insert
                                    $lnTrnsLnID = getNewTrnsID();
                                    $afftctd1 += createTransaction($lnTrnsLnID, $lineAccntID, $lineDesc, $lineDbtAmt, $lineTransDate,
                                            $lineCurID, $sbmtdJrnlBatchID, $lineCrdtAmt, $netAmnt, $entrdAmt, $lineCurID, $acntAmnt,
                                            $accntCurrID, $funcExchRate, $acntExchRate, $dbtOrCrdt, $lnRefDoc, -1, -1, -1, "", -1);
                                } else if ($lnTrnsLnID > 0) {
                                    $afftctd1 += updateTransaction($lineAccntID, $lineDesc, $lineDbtAmt, $lineTransDate, $lineCurID,
                                            $sbmtdJrnlBatchID, $lineCrdtAmt, $netAmnt, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt,
                                            $accntCurrID, $funcExchRate, $acntExchRate, $dbtOrCrdt, $lnRefDoc, -1, -1, -1, "", -1);
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
                                                    $afftctd2 += createAmntBrkDwn($ln_BrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID,
                                                            $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                } else if ($oldBrkdwnLnID > 0) {
                                                    $afftctd2 += updateAmntBrkDwn($oldBrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID,
                                                            $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if (trim($slctdBatchEditLines, "|~") != "") {
                    //Save Direct Debit/Credit Lines
                    $variousRows = explode("|", trim($slctdBatchEditLines, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 12) {
                            $lnTrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $lnTrnsSmryLnID = (float) (cleanInputData1($crntRow[1]));
                            $ln_FuncExchgRate = (float) (cleanInputData1($crntRow[2]));
                            $ln_AccExchgRate = (float) (cleanInputData1($crntRow[3]));
                            $lnSlctdAmtBrkdwns = cleanInputData1($crntRow[4]);
                            $lnRefDoc = cleanInputData1($crntRow[5]);
                            $ln_IncrsDcrs1 = cleanInputData1($crntRow[6]);
                            $ln_AccountID1 = cleanInputData1($crntRow[7]);
                            $lineDesc = cleanInputData1($crntRow[8]);
                            $lineCurNm = cleanInputData1($crntRow[9]);
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $entrdAmt = (float) cleanInputData1($crntRow[10]);
                            $lineTransDate = cleanInputData1($crntRow[11]);
                            $lineTransDate1 = cnvrtDMYTmToYMDTm($lineTransDate);

                            $drCrdt = dbtOrCrdtAccnt($ln_AccountID1, substr($ln_IncrsDcrs1, 0, 1)) == "Debit" ? "D" : "C";
                            $lineDbtAmt = ($drCrdt == "D") ? $entrdAmt : 0;
                            $lineCrdtAmt = ($drCrdt == "C") ? $entrdAmt : 0;
                            if ($ln_FuncExchgRate == 0) {
                                $ln_FuncExchgRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
                            }
                            $netAmnt = drCrAccMltplr($ln_AccountID1, $drCrdt) * $entrdAmt;
                            $dbtOrCrdt = substr(strtoupper($drCrdt), 0, 1);
                            $accntCurrID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $ln_AccountID1);
                            $acntExchRate = round(get_LtstExchRate($lineCurID, $accntCurrID, $lineTransDate), 4);
                            if ($ln_AccExchgRate == 0) {
                                $ln_AccExchgRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
                            }
                            $acntAmnt = $acntExchRate * $entrdAmt;
                            $oldTransID = getTrnsID($lineDesc, $ln_AccountID1, $entrdAmt, $lineCurID, $lineTransDate1);
                            if ($oldTransID > 0 && $oldTransID != $lnTrnsLnID) {
                                $exitErrMsg .= "Same Transaction has been created Already!\r\nConsider changing the Date or Time and Try Again!"
                                        . "<br/>Narration:" . $lineDesc
                                        . "<br/>Date:" . $lineTransDate . "<br/>";
                                continue;
                            }
                            $srcTrnsID = -1;
                            $errMsg = "";
                            if ($lineDesc != "" && $ln_AccountID1 > 0 && $lnTrnsSmryLnID <= 0) {
                                if ($lnTrnsLnID <= 0) {
                                    //Insert
                                    $lnTrnsLnID = getNewTrnsID();
                                    $afftctd1 += createTransaction($lnTrnsLnID, $ln_AccountID1, $lineDesc, $lineDbtAmt, $lineTransDate,
                                            $lineCurID, $sbmtdJrnlBatchID, $lineCrdtAmt, $netAmnt, $entrdAmt, $lineCurID, $acntAmnt,
                                            $accntCurrID, $ln_FuncExchgRate, $ln_AccExchgRate, $dbtOrCrdt, $lnRefDoc, -1, -1, -1, "", -1);
                                } else if ($lnTrnsLnID > 0) {
                                    $afftctd1 += updateTransaction($ln_AccountID1, $lineDesc, $lineDbtAmt, $lineTransDate, $lineCurID,
                                            $sbmtdJrnlBatchID, $lineCrdtAmt, $netAmnt, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt,
                                            $accntCurrID, $ln_FuncExchgRate, $ln_AccExchgRate, $dbtOrCrdt, $lnRefDoc, -1, -1, -1, "", -1);
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
                                                    $afftctd2 += createAmntBrkDwn($ln_BrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID,
                                                            $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                } else if ($oldBrkdwnLnID > 0) {
                                                    $afftctd2 += updateAmntBrkDwn($oldBrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID,
                                                            $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if (trim($slctdBatchSmryLines, "|~") != "") {
                    //Save Simplified Double Entry Lines
                    $variousRows = explode("|", trim($slctdBatchSmryLines, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 12) {
                            $lnSmmryLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_FuncExchgRate = (float) (cleanInputData1($crntRow[1]));
                            $lnSlctdAmtBrkdwns = cleanInputData1($crntRow[2]);
                            $lnRefDoc = cleanInputData1($crntRow[3]);
                            $ln_IncrsDcrs1 = cleanInputData1($crntRow[4]);
                            $ln_AccountID1 = cleanInputData1($crntRow[5]);
                            $ln_IncrsDcrs2 = cleanInputData1($crntRow[6]);
                            $ln_AccountID2 = cleanInputData1($crntRow[7]);
                            $lineDesc = cleanInputData1($crntRow[8]);
                            $lineCurNm = cleanInputData1($crntRow[9]);
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $entrdAmt = (float) cleanInputData1($crntRow[10]);
                            $lineTransDate = cleanInputData1($crntRow[11]);
                            $drCrdt1 = dbtOrCrdtAccnt($ln_AccountID1, substr($ln_IncrsDcrs1, 0, 1)) == "Debit" ? "D" : "C";
                            $drCrdt2 = dbtOrCrdtAccnt($ln_AccountID2, substr($ln_IncrsDcrs2, 0, 1)) == "Debit" ? "D" : "C";
                            $lineDbtAmt1 = ($drCrdt1 == "D") ? $entrdAmt : 0;
                            $lineCrdtAmt1 = ($drCrdt1 == "C") ? $entrdAmt : 0;
                            $lineDbtAmt2 = ($drCrdt2 == "D") ? $entrdAmt : 0;
                            $lineCrdtAmt2 = ($drCrdt2 == "C") ? $entrdAmt : 0;
                            $lineTransDate1 = cnvrtDMYTmToYMDTm($lineTransDate);

                            $oldSmmryID = getTrnsSmmryID($lineDesc, $ln_AccountID1, $ln_AccountID2, $entrdAmt, $lineCurID, $lineTransDate1);

                            if ($oldSmmryID > 0 && $oldSmmryID != $lnSmmryLnID) {
                                $exitErrMsg .= "Same Transaction has been created Already!\r\nConsider changing the Date or Time and Try Again!"
                                        . "<br/>Narration:" . $lineDesc
                                        . "<br/>Date:" . $lineTransDate . "<br/>";
                                continue;
                            }
                            $funcExchRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);

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
                            //Create Summary Trns Record Itself
                            if ($lineDesc != "" && $ln_AccountID1 > 0 && $ln_AccountID2 > 0) {
                                if ($lnSmmryLnID <= 0) {
                                    $lnSmmryLnID = getNewTrnsSmryLnID();
                                    $afftctd += createTrnsSmryLn($lnSmmryLnID, $sbmtdJrnlBatchID, $lnRefDoc, $lineDesc, $ln_IncrsDcrs1,
                                            $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $entrdAmt, $lineTransDate, $lineCurID,
                                            $funcExchRate);
                                } else {
                                    $afftctd += updateTrnsSmryLn($lnSmmryLnID, $sbmtdJrnlBatchID, $lnRefDoc, $lineDesc, $ln_IncrsDcrs1,
                                            $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $entrdAmt, $lineTransDate, $lineCurID,
                                            $funcExchRate);
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
                                            $oldBrkdwnLnID = get_AccntBrkDwnSmryLnID($lnSmmryLnID, $ln_LnkdPValID);
                                            if ($ln_BrkdwnDesc != "" && $ln_BrkdwnTtl != 0) {
                                                if ($oldBrkdwnLnID <= 0) {
                                                    $ln_BrkdwnLnID = getNewAmntBrkDwnID();
                                                    $afftctd2 += createAmntBrkDwn($ln_BrkdwnLnID, -1, $ln_LnkdPValID, $ln_BrkdwnDesc,
                                                            $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, $lnSmmryLnID);
                                                } else if ($oldBrkdwnLnID > 0) {
                                                    $afftctd2 += updateAmntBrkDwn($oldBrkdwnLnID, -1, $ln_LnkdPValID, $ln_BrkdwnDesc,
                                                            $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, $lnSmmryLnID);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            //Create First Leg of Summary Transaction as Journal Entry
                            if ($lnSmmryLnID > 0 && $ln_AccountID1 > 0) {
                                $lnTrnsLnID = getTrnsIDUsgSmryDC($ln_AccountID1, $dbtOrCrdt1, $lnSmmryLnID);
                                if ($lnTrnsLnID <= 0) {
                                    //Insert
                                    $lnTrnsLnID = getNewTrnsID();
                                    $afftctd1 += createTransaction($lnTrnsLnID, $ln_AccountID1, $lineDesc, $lineDbtAmt1, $lineTransDate,
                                            $lineCurID, $sbmtdJrnlBatchID, $lineCrdtAmt1, $netAmnt1, $entrdAmt, $lineCurID, $acntAmnt1,
                                            $accntCurrID1, $funcExchRate, $acntExchRate1, $dbtOrCrdt1, $lnRefDoc, $srcTrnsID1, $srcTrnsID2,
                                            $lnSmmryLnID, "", -1);
                                } else if ($lnTrnsLnID > 0) {
                                    $afftctd1 += updateTransaction($ln_AccountID1, $lineDesc, $lineDbtAmt1, $lineTransDate, $lineCurID,
                                            $sbmtdJrnlBatchID, $lineCrdtAmt1, $netAmnt1, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt1,
                                            $accntCurrID1, $funcExchRate, $acntExchRate1, $dbtOrCrdt1, $lnRefDoc, $srcTrnsID1, $srcTrnsID2,
                                            $lnSmmryLnID, "", -1);
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
                                                    $afftctd2 += createAmntBrkDwn($ln_BrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID,
                                                            $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                } else if ($oldBrkdwnLnID > 0) {
                                                    $afftctd2 += updateAmntBrkDwn($oldBrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID,
                                                            $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            //Create Second Leg of Summary Transaction as Journal Entry
                            if ($lnSmmryLnID > 0 && $ln_AccountID2 > 0) {
                                $lnTrnsLnID = getTrnsIDUsgSmryDC($ln_AccountID2, $dbtOrCrdt2, $lnSmmryLnID);
                                //getTrnsIDUsgSmry($ln_AccountID2, $lnSmmryLnID);
                                if ($lnTrnsLnID <= 0) {
                                    //Insert
                                    $lnTrnsLnID = getNewTrnsID();
                                    $afftctd1 += createTransaction($lnTrnsLnID, $ln_AccountID2, $lineDesc, $lineDbtAmt2, $lineTransDate,
                                            $lineCurID, $sbmtdJrnlBatchID, $lineCrdtAmt2, $netAmnt2, $entrdAmt, $lineCurID, $acntAmnt2,
                                            $accntCurrID2, $funcExchRate, $acntExchRate2, $dbtOrCrdt2, $lnRefDoc, $srcTrnsID1, $srcTrnsID2,
                                            $lnSmmryLnID, "", -1);
                                } else if ($lnTrnsLnID > 0) {
                                    $afftctd1 += updateTransaction($ln_AccountID2, $lineDesc, $lineDbtAmt2, $lineTransDate, $lineCurID,
                                            $sbmtdJrnlBatchID, $lineCrdtAmt2, $netAmnt2, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt2,
                                            $accntCurrID2, $funcExchRate, $acntExchRate2, $dbtOrCrdt2, $lnRefDoc, $srcTrnsID1, $srcTrnsID2,
                                            $lnSmmryLnID, "", -1);
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
                                                    $afftctd2 += createAmntBrkDwn($ln_BrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID,
                                                            $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                } else if ($oldBrkdwnLnID > 0) {
                                                    $afftctd2 += updateAmntBrkDwn($oldBrkdwnLnID, $lnTrnsLnID, $ln_LnkdPValID,
                                                            $ln_BrkdwnDesc, $ln_BrkdwnQTY, $ln_BrkdwnUnitVal, $ln_BrkdwnTtl, -1);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            deleteTrnsIDUsgSmry($lnSmmryLnID);
                        }
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Journal Batch Successfully Saved!"
                            . "<br/>" . $afftctd . " Simplified Double Entry Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Direct Debit/Credit Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd2 . " Transaction Breakdown(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Journal Batch Successfully Saved!"
                            . "<br/>" . $afftctd . " Simplified Double Entry Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Direct Debit/Credit Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd2 . " Transaction Breakdown(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdJrnlBatchID'] = $sbmtdJrnlBatchID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 2) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdJrnlBatchID;
                if ($attchmentID > 0) {
                    uploadDaJrnlBatchDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewJRNLBATCHDocID();
                    createJrnlBatchDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaJrnlBatchDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/Accntn/" . $nwImgLoc;
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
            } else if ($actyp == 103) {
                $all_exitErrMsg = "";
                //Import Debit/Credit Transactions
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();
                $affctd = 0;
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                $curLovID = getLovID("Currencies");
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        logSessionErrs($variousRows[$z]);
                        if (count($crntRow) == 6) {
                            $acbAccountNum = str_replace("'", "", trim(cleanInputData1($crntRow[0])));
                            $acbAccountNumDesc = trim((cleanInputData1($crntRow[1])));
                            $acbTransDesc = trim(cleanInputData1($crntRow[2]));
                            $acbTransDbtAmnt = trim(cleanInputData1($crntRow[3]));
                            $acbTransCrdtAmnt = trim(cleanInputData1($crntRow[4]));
                            $acbTransDate = str_replace("'", "", trim(cleanInputData1($crntRow[5])));
                            if ($z == 0) {
                                if (strtoupper($acbAccountNum) == strtoupper("Account Number**") && strtoupper($acbAccountNumDesc) == strtoupper("Account Name") && strtoupper($acbTransDesc) == strtoupper("Transaction Description**") && strtoupper($acbTransDbtAmnt) == strtoupper("DEBIT**") && strtoupper($acbTransCrdtAmnt) == strtoupper("CREDIT**") && strtoupper($acbTransDate) == strtoupper("Transaction Date**")) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_dbtcrdttransimprt_progress.rho",
                                            json_encode($arr_content));
                                    break;
                                }
                            }
                            if (trim($acbTransDate) != "") {
                                $acbTransDate = cnvrtAllToDMYTm($acbTransDate);
                            }
                            $exitErrMsg = "";
                            if ($acbAccountNum == "") {
                                $exitErrMsg .= "Please enter Account Number and Description!<br/>";
                            }
                            if ($acbTransDesc == "") {
                                $exitErrMsg .= "Please provide Transaction Description!<br/>";
                            }
                            $all_exitErrMsg .= $exitErrMsg;

                            $lnTrnsLnID = -1;
                            $lnTrnsSmryLnID = -1;
                            $lnSlctdAmtBrkdwns = "";
                            $lnRefDoc = "";
                            $lineAccntID = getAccntID($acbAccountNum, $orgID);
                            $lineDesc = $acbTransDesc;
                            $lineCurNm = $fnccurnm;
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $lineDbtAmt = (float) $acbTransDbtAmnt;
                            $lineCrdtAmt = (float) $acbTransCrdtAmnt;
                            $entrdAmt = ($lineDbtAmt >= $lineCrdtAmt) ? ($lineDbtAmt - $lineCrdtAmt) : ($lineCrdtAmt - $lineDbtAmt);
                            $drCrdt = ($lineDbtAmt >= $lineCrdtAmt) ? "D" : "C";
                            $lineTransDate = $acbTransDate;

                            $lineTransDate1 = cnvrtDMYTmToYMDTm($lineTransDate);
                            $oldTransID = getTrnsID($lineDesc, $lineAccntID, $entrdAmt, $lineCurID, $lineTransDate1);
                            $netAmnt = drCrAccMltplr($lineAccntID, $drCrdt) * $entrdAmt;
                            if ($oldTransID > 0 && $oldTransID != $lnTrnsLnID) {
                                $exitErrMsg .= "Same Transaction has been created Already!\r\nConsider changing the Date or Time and Try Again!"
                                        . "<br/>Narration:" . $lineDesc
                                        . "<br/>Date:" . $lineTransDate . "<br/>";
                                $all_exitErrMsg .= $exitErrMsg;
                                continue;
                            }
                            $dbtOrCrdt = substr(strtoupper($drCrdt), 0, 1);
                            $accntCurrID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lineAccntID);
                            $acntExchRate = round(get_LtstExchRate($lineCurID, $accntCurrID, $lineTransDate), 4);
                            $funcExchRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
                            $acntAmnt = $acntExchRate * $entrdAmt;
                            $srcTrnsID = -1;
                            $errMsg = "";

                            if ($exitErrMsg == "" && $lineDesc != "" && $lineAccntID > 0 && $sbmtdJrnlBatchID > 0) {
                                if ($lnTrnsLnID <= 0) {
                                    //Insert
                                    $lnTrnsLnID = getNewTrnsID();
                                    $afftctd1 += createTransaction($lnTrnsLnID, $lineAccntID, $lineDesc, $lineDbtAmt, $lineTransDate,
                                            $lineCurID, $sbmtdJrnlBatchID, $lineCrdtAmt, $netAmnt, $entrdAmt, $lineCurID, $acntAmnt,
                                            $accntCurrID, $funcExchRate, $acntExchRate, $dbtOrCrdt, $lnRefDoc, -1, -1, -1, "", -1);
                                } else if ($lnTrnsLnID > 0) {
                                    $afftctd1 += updateTransaction($lineAccntID, $lineDesc, $lineDbtAmt, $lineTransDate, $lineCurID,
                                            $sbmtdJrnlBatchID, $lineCrdtAmt, $netAmnt, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt,
                                            $accntCurrID, $funcExchRate, $acntExchRate, $dbtOrCrdt, $lnRefDoc, -1, -1, -1, "", -1);
                                }
                            }
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Transactions(s) imported.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Transactions...Please Wait..." . ($z + 1) . " out of " . $total . " Account(s) imported." . $all_exitErrMsg;
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_dbtcrdttransimprt_progress.rho",
                                json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$all_exitErrMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_dbtcrdttransimprt_progress.rho",
                            json_encode($arr_content));
                }
            } else if ($actyp == 104) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_dbtcrdttransimprt_progress.rho";
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
            } else if ($actyp == 105) {
                //Import Increase/Decrease Transactions
                $all_exitErrMsg = "";
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();
                $affctd = 0;
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                $curLovID = getLovID("Currencies");
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 8) {
                            $acbTransDesc = trim(cleanInputData1($crntRow[0]));
                            $acbTransRefDoc = trim(cleanInputData1($crntRow[1]));
                            $acbTransIncrsDcrs = trim(cleanInputData1($crntRow[2]));
                            $acbAccountNum = str_replace("'", "", trim(cleanInputData1($crntRow[3])));
                            $acbAccountNumDesc = trim((cleanInputData1($crntRow[4])));
                            $acbTransAmnt = trim(cleanInputData1($crntRow[5]));
                            $acbTransCurr = trim(cleanInputData1($crntRow[6]));
                            $acbTransDate = str_replace("'", "", trim(cleanInputData1($crntRow[7])));

                            if ($z == 0) {
                                if (strtoupper($acbTransDesc) == strtoupper("Transaction Description**") && strtoupper($acbTransRefDoc) == strtoupper("Cheque/Voucher/Receipt No. (Ref. Doc. No.)") && strtoupper($acbTransIncrsDcrs) == strtoupper("Increase/Decrease**") && strtoupper($acbAccountNum) == strtoupper("Account Number**") && strtoupper($acbAccountNumDesc) == strtoupper("Account Name") && strtoupper($acbTransAmnt) == strtoupper("AMOUNT**") && strtoupper($acbTransCurr) == strtoupper("Curr.**") && strtoupper($acbTransDate) == strtoupper("Transaction Date**")) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_incrsdcrstransimprt_progress.rho",
                                            json_encode($arr_content));
                                    break;
                                }
                            }

                            if (trim($acbTransDate) != "") {
                                $acbTransDate = cnvrtAllToDMYTm($acbTransDate);
                            }
                            $exitErrMsg = "";
                            if ($acbAccountNum == "") {
                                $exitErrMsg .= "Please enter Account Number and Description!<br/>";
                            }
                            if ($acbTransDesc == "") {
                                $exitErrMsg .= "Please provide Transaction Description!<br/>";
                            }
                            $all_exitErrMsg .= $exitErrMsg;

                            $lnTrnsLnID = -1;
                            $lnTrnsSmryLnID = -1;
                            $ln_FuncExchgRate = 0;
                            $ln_AccExchgRate = 0;
                            $lnSlctdAmtBrkdwns = "";
                            $lnRefDoc = $acbTransRefDoc;
                            $lineDesc = $acbTransDesc;
                            $lineCurNm = $acbTransCurr;
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $ln_IncrsDcrs1 = $acbTransIncrsDcrs;
                            $ln_AccountID1 = getAccntID($acbAccountNum, $orgID);
                            $entrdAmt = (float) $acbTransAmnt;
                            $lineTransDate = $acbTransDate;
                            $drCrdt = dbtOrCrdtAccnt($ln_AccountID1, substr($ln_IncrsDcrs1, 0, 1)) == "Debit" ? "D" : "C";
                            $lineDbtAmt = ($drCrdt == "D") ? $entrdAmt : 0;
                            $lineCrdtAmt = ($drCrdt == "C") ? $entrdAmt : 0;

                            $lineTransDate1 = cnvrtDMYTmToYMDTm($lineTransDate);

                            if ($ln_FuncExchgRate == 0) {
                                $ln_FuncExchgRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
                            }
                            $netAmnt = drCrAccMltplr($ln_AccountID1, $drCrdt) * $entrdAmt;
                            $dbtOrCrdt = substr(strtoupper($drCrdt), 0, 1);
                            $accntCurrID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $ln_AccountID1);
                            $acntExchRate = round(get_LtstExchRate($lineCurID, $accntCurrID, $lineTransDate), 4);
                            if ($ln_AccExchgRate == 0) {
                                $ln_AccExchgRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
                            }
                            $acntAmnt = $acntExchRate * $entrdAmt;
                            $oldTransID = getTrnsID($lineDesc, $ln_AccountID1, $entrdAmt, $lineCurID, $lineTransDate1);
                            if ($oldTransID > 0 && $oldTransID != $lnTrnsLnID) {
                                $exitErrMsg .= "Same Transaction has been created Already!\r\nConsider changing the Date or Time and Try Again!"
                                        . "<br/>Narration:" . $lineDesc
                                        . "<br/>Date:" . $lineTransDate . "<br/>";
                                $all_exitErrMsg .= $exitErrMsg;
                                continue;
                            }
                            $srcTrnsID = -1;
                            $errMsg = "";

                            if ($exitErrMsg === "" && $lineDesc != "" && $ln_AccountID1 > 0 && $lnTrnsSmryLnID <= 0) {
                                if ($lnTrnsLnID <= 0) {
                                    //Insert
                                    $lnTrnsLnID = getNewTrnsID();
                                    $affctd += createTransaction($lnTrnsLnID, $ln_AccountID1, $lineDesc, $lineDbtAmt, $lineTransDate,
                                            $lineCurID, $sbmtdJrnlBatchID, $lineCrdtAmt, $netAmnt, $entrdAmt, $lineCurID, $acntAmnt,
                                            $accntCurrID, $ln_FuncExchgRate, $ln_AccExchgRate, $dbtOrCrdt, $lnRefDoc, -1, -1, -1, "", -1);
                                } else if ($lnTrnsLnID > 0) {
                                    $affctd += updateTransaction($ln_AccountID1, $lineDesc, $lineDbtAmt, $lineTransDate, $lineCurID,
                                            $sbmtdJrnlBatchID, $lineCrdtAmt, $netAmnt, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt,
                                            $accntCurrID, $ln_FuncExchgRate, $ln_AccExchgRate, $dbtOrCrdt, $lnRefDoc, -1, -1, -1, "", -1);
                                }
                            }
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Transaction(s) imported.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Transactions...Please Wait..." . ($z + 1) . " out of " . $total . " Transaction(s) imported." . $all_exitErrMsg;
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_incrsdcrstransimprt_progress.rho",
                                json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$all_exitErrMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_incrsdcrstransimprt_progress.rho",
                            json_encode($arr_content));
                }
            } else if ($actyp == 106) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_incrsdcrstransimprt_progress.rho";
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
            } else if ($actyp == 107) {
                //Import Simplified Transactions
                $all_exitErrMsg = "";
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                //logSessionErrs($dataToSend."::::".$sbmtdJrnlBatchID);
                session_write_close();
                $affctd = 0;
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                $curLovID = getLovID("Currencies");
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 11) {
                            $acbTransDesc = trim(cleanInputData1($crntRow[0]));
                            $acbTransRefDoc = trim(cleanInputData1($crntRow[1]));
                            $acbTransIncrsDcrs1 = trim(cleanInputData1($crntRow[2]));
                            $acbAccountNum1 = str_replace("'", "", trim(cleanInputData1($crntRow[3])));
                            $acbAccountNumDesc1 = trim((cleanInputData1($crntRow[4])));
                            $acbTransIncrsDcrs2 = trim(cleanInputData1($crntRow[5]));
                            $acbAccountNum2 = str_replace("'", "", trim(cleanInputData1($crntRow[6])));
                            $acbAccountNumDesc2 = trim((cleanInputData1($crntRow[7])));
                            $acbTransAmnt = trim(cleanInputData1($crntRow[8]));
                            $acbTransCurr = trim(cleanInputData1($crntRow[9]));
                            $acbTransDate = str_replace("'", "", trim(cleanInputData1($crntRow[10])));

                            if ($z == 0) {
                                if (strtoupper($acbTransDesc) == strtoupper("Transaction Description**") && strtoupper($acbTransRefDoc) == strtoupper("Cheque/Voucher/Receipt No. (Ref. Doc. No.)") && strtoupper($acbAccountNum1) == strtoupper("Account Number 1**") && strtoupper($acbAccountNumDesc1) == strtoupper("Account Name 1") && strtoupper($acbTransIncrsDcrs1) == strtoupper("Increase/Decrease 1**") && strtoupper($acbAccountNum2) == strtoupper("Account Number 2**") && strtoupper($acbAccountNumDesc2) == strtoupper("Account Name 2") && strtoupper($acbTransIncrsDcrs2) == strtoupper("Increase/Decrease 2**") && strtoupper($acbTransAmnt) == strtoupper("AMOUNT**") && strtoupper($acbTransCurr) == strtoupper("Curr.**") && strtoupper($acbTransDate) == strtoupper("Transaction Date**")) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_simpletransimprt_progress.rho",
                                            json_encode($arr_content));
                                    break;
                                }
                            }
                            if (trim($acbTransDate) != "") {
                                $acbTransDate = cnvrtAllToDMYTm($acbTransDate);
                            }
                            $exitErrMsg = "";
                            if ($acbAccountNum1 == "") {
                                $exitErrMsg .= "Please enter Account Number and Description!<br/>";
                            }
                            if ($acbTransDesc == "") {
                                $exitErrMsg .= "Please provide Transaction Description!<br/>";
                            }
                            $all_exitErrMsg .= $exitErrMsg;

                            $lnSmmryLnID = -1;
                            $ln_FuncExchgRate = 0;
                            $lnSlctdAmtBrkdwns = "";
                            $lnRefDoc = $acbTransRefDoc;
                            $ln_IncrsDcrs1 = $acbTransIncrsDcrs1;
                            $ln_AccountID1 = getAccntID($acbAccountNum1, $orgID);
                            $ln_IncrsDcrs2 = $acbTransIncrsDcrs2;
                            $ln_AccountID2 = getAccntID($acbAccountNum2, $orgID);
                            $lineDesc = $acbTransDesc;
                            $lineCurNm = $acbTransCurr;
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $entrdAmt = (float) $acbTransAmnt;
                            $lineTransDate = $acbTransDate;
                            $drCrdt1 = dbtOrCrdtAccnt($ln_AccountID1, substr($ln_IncrsDcrs1, 0, 1)) == "Debit" ? "D" : "C";
                            $drCrdt2 = dbtOrCrdtAccnt($ln_AccountID2, substr($ln_IncrsDcrs2, 0, 1)) == "Debit" ? "D" : "C";
                            $lineDbtAmt1 = ($drCrdt1 == "D") ? $entrdAmt : 0;
                            $lineCrdtAmt1 = ($drCrdt1 == "C") ? $entrdAmt : 0;
                            $lineDbtAmt2 = ($drCrdt2 == "D") ? $entrdAmt : 0;
                            $lineCrdtAmt2 = ($drCrdt2 == "C") ? $entrdAmt : 0;
                            $lineTransDate1 = cnvrtDMYTmToYMDTm($lineTransDate);

                            $oldSmmryID = getTrnsSmmryID($lineDesc, $ln_AccountID1, $ln_AccountID2, $entrdAmt, $lineCurID, $lineTransDate1);
                            //logSessionErrs($lineDesc . "::" . $ln_AccountID1 . "::" . $ln_AccountID2);
                            if ($oldSmmryID > 0 && $oldSmmryID != $lnSmmryLnID) {
                                $exitErrMsg .= "Same Transaction has been created Already!\r\nConsider changing the Date or Time and Try Again!"
                                        . "<br/>Narration:" . $lineDesc
                                        . "<br/>Date:" . $lineTransDate . "<br/>";
                                $all_exitErrMsg .= $exitErrMsg;
                                continue;
                            }
                            $funcExchRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);

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
                            //Create Summary Trns Record Itself
                            if ($exitErrMsg == "" && $lineDesc != "" && $ln_AccountID1 > 0 && $ln_AccountID2 > 0) {
                                if ($lnSmmryLnID <= 0) {
                                    $lnSmmryLnID = getNewTrnsSmryLnID();
                                    $afftctd += createTrnsSmryLn($lnSmmryLnID, $sbmtdJrnlBatchID, $lnRefDoc, $lineDesc, $ln_IncrsDcrs1,
                                            $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $entrdAmt, $lineTransDate, $lineCurID,
                                            $funcExchRate);
                                } else {
                                    $afftctd += updateTrnsSmryLn($lnSmmryLnID, $sbmtdJrnlBatchID, $lnRefDoc, $lineDesc, $ln_IncrsDcrs1,
                                            $ln_AccountID1, $ln_IncrsDcrs2, $ln_AccountID2, $entrdAmt, $lineTransDate, $lineCurID,
                                            $funcExchRate);
                                }

                                //Create First Leg of Summary Transaction as Journal Entry
                                if ($lnSmmryLnID > 0 && $ln_AccountID1 > 0) {
                                    $lnTrnsLnID = getTrnsIDUsgSmryDC($ln_AccountID1, $dbtOrCrdt1, $lnSmmryLnID);
                                    //getTrnsIDUsgSmry($ln_AccountID1, $lnSmmryLnID);
                                    if ($lnTrnsLnID <= 0) {
                                        //Insert
                                        $lnTrnsLnID = getNewTrnsID();
                                        $afftctd1 += createTransaction($lnTrnsLnID, $ln_AccountID1, $lineDesc, $lineDbtAmt1, $lineTransDate,
                                                $lineCurID, $sbmtdJrnlBatchID, $lineCrdtAmt1, $netAmnt1, $entrdAmt, $lineCurID, $acntAmnt1,
                                                $accntCurrID1, $funcExchRate, $acntExchRate1, $dbtOrCrdt1, $lnRefDoc, $srcTrnsID1,
                                                $srcTrnsID2, $lnSmmryLnID, "", -1);
                                    } else if ($lnTrnsLnID > 0) {
                                        $afftctd1 += updateTransaction($ln_AccountID1, $lineDesc, $lineDbtAmt1, $lineTransDate, $lineCurID,
                                                $sbmtdJrnlBatchID, $lineCrdtAmt1, $netAmnt1, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt1,
                                                $accntCurrID1, $funcExchRate, $acntExchRate1, $dbtOrCrdt1, $lnRefDoc, $srcTrnsID1,
                                                $srcTrnsID2, $lnSmmryLnID, "", -1);
                                    }
                                }
                                //Create Second Leg of Summary Transaction as Journal Entry
                                if ($lnSmmryLnID > 0 && $ln_AccountID2 > 0) {
                                    $lnTrnsLnID = getTrnsIDUsgSmryDC($ln_AccountID2, $dbtOrCrdt2, $lnSmmryLnID);
                                    //getTrnsIDUsgSmry($ln_AccountID2, $lnSmmryLnID);
                                    if ($lnTrnsLnID <= 0) {
                                        //Insert
                                        $lnTrnsLnID = getNewTrnsID();
                                        $afftctd1 += createTransaction($lnTrnsLnID, $ln_AccountID2, $lineDesc, $lineDbtAmt2, $lineTransDate,
                                                $lineCurID, $sbmtdJrnlBatchID, $lineCrdtAmt2, $netAmnt2, $entrdAmt, $lineCurID, $acntAmnt2,
                                                $accntCurrID2, $funcExchRate, $acntExchRate2, $dbtOrCrdt2, $lnRefDoc, $srcTrnsID1,
                                                $srcTrnsID2, $lnSmmryLnID, "", -1);
                                    } else if ($lnTrnsLnID > 0) {
                                        $afftctd1 += updateTransaction($ln_AccountID2, $lineDesc, $lineDbtAmt2, $lineTransDate, $lineCurID,
                                                $sbmtdJrnlBatchID, $lineCrdtAmt2, $netAmnt2, $lnTrnsLnID, $entrdAmt, $lineCurID, $acntAmnt2,
                                                $accntCurrID2, $funcExchRate, $acntExchRate2, $dbtOrCrdt2, $lnRefDoc, $srcTrnsID1,
                                                $srcTrnsID2, $lnSmmryLnID, "", -1);
                                    }
                                }
                                deleteTrnsIDUsgSmry($lnSmmryLnID);
                            }
                        }
                        //logSessionErrs($lineDesc . "::" . $ln_AccountID1 . "::" . $ln_AccountID2);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Transaction(s) imported.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Transactions...Please Wait..." . ($z + 1) . " out of " . $total . " Transaction(s) imported." . $all_exitErrMsg;
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_simpletransimprt_progress.rho",
                                json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$all_exitErrMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_simpletransimprt_progress.rho",
                            json_encode($arr_content));
                }
            } else if ($actyp == 108) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_simpletransimprt_progress.rho";
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
            } else if ($actyp == 3) {
                //Export Accounts
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array("No.", "Account Number**", "Account Name", "Transaction Description**",
                        "DEBIT**", "CREDIT**", "Transaction Date**");
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/DebitCreditTrnsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/DebitCreditTrnsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Debit/Credit Transactions Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_DebitCreditTrnsExprt_progress.rho",
                                json_encode($arr_content));

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_DetTransToExprt($sbmtdJrnlBatchID, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        //"" . ($z + 1), 
                        $crntRw = array("" . ($z + 1), $row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                    1) . " out of " . $total . " Debit/Credit Transaction(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Debit/Credit Transactions...Please Wait..." . ($z +
                                    1) . " out of " . $total . " Debit/Credit Transaction(s) exported.</span>";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_DebitCreditTrnsExprt_progress.rho",
                                json_encode($arr_content));
                        $z++;
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_DebitCreditTrnsExprt_progress.rho",
                            json_encode($arr_content));
                }
            } else if ($actyp == 4) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_DebitCreditTrnsExprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started No File</span>'));
                }
            } else if ($actyp == 5) {
                //Export Edit Transactions
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array("No.", "Transaction Description**", "Cheque/Voucher/Receipt No. (Ref. Doc. No.)", "Increase/Decrease**", "Account Number**",
                        "Account Name",
                        "AMOUNT**", "Curr.**", "Transaction Date**");
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/IncrsDcrsTrnsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/IncrsDcrsTrnsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Increase/Decrease Transactions Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_IncrsDcrsTrnsExprt_progress.rho",
                                json_encode($arr_content));

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_EditTransToExprt($sbmtdJrnlBatchID, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        //"" . ($z + 1), 
                        $dbtOrCrdt = $row[2];
                        $trsctnAcntID = (float) $row[3];
                        $trnsIncrsDcrs1 = "";
                        if ($dbtOrCrdt == "C") {
                            $trnsIncrsDcrs1 = str_replace("d", "D",
                                    str_replace("i", "I", strtolower(incrsOrDcrsAccnt($trsctnAcntID, "Credit"))));
                        } else {
                            $trnsIncrsDcrs1 = str_replace("d", "D",
                                    str_replace("i", "I", strtolower(incrsOrDcrsAccnt($trsctnAcntID, "Debit"))));
                        }
                        $crntRw = array("" . ($z + 1), $row[0], $row[1], $trnsIncrsDcrs1, $row[4], $row[5], $row[6], $row[7], $row[8]);
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                    1) . " out of " . $total . " Increase/Decrease Transaction(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Increase/Decrease Transactions...Please Wait..." . ($z +
                                    1) . " out of " . $total . " Increase/Decrease Transaction(s) exported.</span>";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_IncrsDcrsTrnsExprt_progress.rho",
                                json_encode($arr_content));
                        $z++;
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_IncrsDcrsTrnsExprt_progress.rho",
                            json_encode($arr_content));
                }
            } else if ($actyp == 6) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_IncrsDcrsTrnsExprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started No File</span>'));
                }
            } else if ($actyp == 7) {
                //Export Summary Transactions
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array("No.", "Transaction Description**", "Cheque/Voucher/Receipt No. (Ref. Doc. No.)",
                        "Increase/Decrease 1**", "Account Number 1**", "Account Name 1",
                        "Increase/Decrease 2**", "Account Number 2**", "Account Name 2",
                        "AMOUNT**", "Curr.**", "Transaction Date**");
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/SimplifiedTrnsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/SimplifiedTrnsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Simplified Transactions Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SimplifiedTrnsExprt_progress.rho",
                                json_encode($arr_content));

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_SmmryTransToExprt($sbmtdJrnlBatchID, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array("" . ($z + 1), $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9],
                            $row[10]);
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                    1) . " out of " . $total . " Simplified Transaction(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Simplified Transactions...Please Wait..." . ($z +
                                    1) . " out of " . $total . " Simplified Transaction(s) exported.</span>";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SimplifiedTrnsExprt_progress.rho",
                                json_encode($arr_content));
                        $z++;
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SimplifiedTrnsExprt_progress.rho",
                            json_encode($arr_content));
                }
            } else if ($actyp == 8) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SimplifiedTrnsExprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started No File</span>'));
                }
            }
        } else if ($qstr == "VOID") {
            if ($actyp == 1) {
                //Reverse Journal Batch
                $errMsg = "";
                if (!$canVoid) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $jrnlBatchRvrslRsn = isset($_POST['jrnlBatchDesc']) ? cleanInputData($_POST['jrnlBatchDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $orgnlBatchID = $sbmtdJrnlBatchID;
                $trnsIDStatus = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_status", $orgnlBatchID);
                /* if ($trnsIDStatus != "1") {
                  echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Only Posted Journal Batches can be voided!<br/> Please DELETE the Batch instead!</span>";
                  exit();
                  } */
                $trnsIDStatus2 = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_vldty_status", $orgnlBatchID);
                if (strtoupper($trnsIDStatus2) == "VOID") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Journal Batch has been voided already!</span>";
                    exit();
                }
                $lnkdVoidedBatchId = (float) getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "src_batch_id", $orgnlBatchID);
                $trnsIDStatus1 = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_vldty_status", $lnkdVoidedBatchId);
                if ($lnkdVoidedBatchId > 0 || $trnsIDStatus1 == "VOID") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Cannot Reverse a Reversal Batch!</span>";
                    exit();
                }
                $gnrtdTrnsNoExistsID2 = (float) getGnrlRecNm("accb.accb_trnsctn_batches", "src_batch_id", "batch_id", $orgnlBatchID);
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
                        //$jrnlBatchRvrslRsn = $row[15];
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
                        if ($voidedJrnlBatchID > 0) {
                            $jrnlBatchDesc = $jrnlBatchRvrslRsn;
                        }
                    }
                }
                $gnrtdTrnsNo1 = "RVRSL-" . $gnrtdTrnsNo;
                $gnrtdTrnsNoExistsID = (float) getGnrlRecNm2("accb.accb_trnsctn_batches", "batch_name", "batch_id", $gnrtdTrnsNo1);
                if ($gnrtdTrnsNoExistsID > 0 || $gnrtdTrnsNoExistsID2 > 0) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>A Reversal Transaction for this Batch Exists Already!</span>";
                    exit();
                } else {
                    if ($rqStatus == "Not Posted" && $sbmtdJrnlBatchID > 0) {
                        echo deleteBatch($sbmtdJrnlBatchID, $gnrtdTrnsNo);
                        exit();
                    }
                    $gnrtdTrnsNo = $gnrtdTrnsNo1;
                }
                $rsltCnt = 0;
                $affctd1 = 0;
                $affctd2 = 0;
                $affctd3 = 0;
                $affctd4 = 0;
                if ($voidedJrnlBatchID <= 0) {
                    //CREATE
                    $prdHdrID = getPrdHdrID($orgID);
                    $tstDate = cnvrtDMYTmToYMDTm($jrnlBatchDfltTrnsDte);
                    $prdLnID = getTrnsDteOpenPrdLnID($prdHdrID, $tstDate);
                    if ($prdLnID <= 0) {
                        $jrnlBatchDfltTrnsDte = getLtstOpenPrdAfterDate($tstDate);
                    }
                    if (!isTransPrmttd(get_DfltCashAcnt($orgID), $jrnlBatchDfltTrnsDte, 200, $errMsg)) {
                        echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>ERROR:" . $errMsg . "</span>";
                        exit();
                    }
                    $rsltCnt = createBatch($orgID, $gnrtdTrnsNo, "", "Manual Batch Reversal", "VALID", $sbmtdJrnlBatchID, "1",
                            $jrnlBatchDfltBalsAcntID, $jrnlBatchRvrslRsn, $jrnlBatchDfltCurID, $jrnlBatchDfltTrnsDte);
                    $voidedJrnlBatchID = getBatchID($gnrtdTrnsNo, $orgID);
                    if ($voidedJrnlBatchID > 0) {
                        //Create and Negate Batch Det Lines
                        $affctd1 += voidJrnlBatchTrans($sbmtdJrnlBatchID, $voidedJrnlBatchID, $jrnlBatchDfltTrnsDte);
                    }
                    $sbmtdJrnlBatchID = $voidedJrnlBatchID;
                } else if ($sbmtdJrnlBatchID > 0) {
                    $rsltCnt = updateBatchVoid($voidedJrnlBatchID, $jrnlBatchRvrslRsn);
                }
                if ($shdSbmt > 0 && $sbmtdJrnlBatchID > 0 && $orgnlBatchID > 0) {
                    $batchID = $orgnlBatchID;
                    $affctd = 0;
                    updateBatchVldtyStatus($orgnlBatchID, "VOID");
                    updateBatchAvlblty($sbmtdJrnlBatchID, "1");
                    $updtSQL = "UPDATE accb.accb_pybls_invc_hdr SET gl_batch_id=" . $sbmtdJrnlBatchID . ", approval_status='Cancelled', next_aproval_action='None' WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $updtSQL = "UPDATE accb.accb_rcvbls_invc_hdr SET gl_batch_id=" . $sbmtdJrnlBatchID . ", approval_status='Cancelled', next_aproval_action='None' WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $updtSQL = "UPDATE accb.accb_ptycsh_vchr_hdr SET gl_batch_id=" . $sbmtdJrnlBatchID . ", approval_status='Cancelled', next_aproval_action='None' WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $updtSQL = "UPDATE accb.accb_payments SET gl_batch_id=" . $sbmtdJrnlBatchID . ", pymnt_vldty_status='VOID' WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $updtSQL = "UPDATE accb.accb_fa_asset_trns SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $updtSQL = "UPDATE scm.scm_gl_interface SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $updtSQL = "UPDATE mcf.mcf_gl_interface SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $updtSQL = "UPDATE vms.vms_gl_interface SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $updtSQL = "UPDATE pay.pay_gl_interface SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
                    $affctd += execUpdtInsSQL($updtSQL);
                    $errMsg = $rsltCnt . " Batch Reversal Finalized!<br/>" . $affctd1 . " Transactions Reversed!<br/>" . $affctd . " Other Module Transactions Cancelled!";
                } else {
                    $errMsg = $rsltCnt . " Batch Reversal Created Pending Finalization!<br/>" . $affctd1 . " Transactions Reversed!";
                }
                $response = array('sbmtdJrnlBatchID' => $sbmtdJrnlBatchID,
                    'sbmtMsg' => $errMsg);
                echo json_encode($response);
            }
        } else {
            if ($vwtyp == 0) {
                //Journal Entries
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Journal Entries</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
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
                    $total = get_Total_Batches($srchFor, $srchIn, $orgID, $qShwUsrOnly, $qShwUnpstdOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Basic_BatchDet($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUsrOnly, $qShwUnpstdOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-6";

                    $reportTitle1 = "Post GL Transaction Batches-Web";
                    $reportName1 = "Post GL Transaction Batches-Web";
                    $rptID1 = getRptID($reportName1);
                    $prmID11 = getParamIDUseSQLRep("{:p_batch_id}", $rptID1);
                    $paramRepsNVals1 = $prmID11 . "~-1|-130~" . $reportTitle1 . "|-190~HTML";
                    $paramStr1 = urlencode($paramRepsNVals1);
                    ?> 
                    <form id='accbJrnlEntrsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">JOURNAL ENTRIES</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                if ($canAdd === true) {
                                    ?>   
                                    <div class="col-md-4" style="padding:0px 1px 0px 15px !important;">                    
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneJrnlBatchForm(-1, 1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="New Journal Entry Batch">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New
                                        </button>                   
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneJrnlBatchForm(-1, 101, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="New Batch (Simplified Double Entry)">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Simple Batch
                                        </button>
                                        <?php if ($canPost === true) {
                                            ?>  
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID1; ?>');" data-toggle="tooltip" data-placement="bottom" title="Post all Outstanding Journal Batches">
                                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post&nbsp;
                                            </button>
                                        <?php }
                                        ?>
                                    </div>
                                    <?php
                                } else {
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-6";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbJrnlEntrsSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAccbJrnlEntrs(event, '', '#allmodules', 'grp=6&typ=1&pg=2&vtyp=0')">
                                        <input id="accbJrnlEntrsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbJrnlEntrs('clear', '#allmodules', 'grp=6&typ=1&pg=2&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbJrnlEntrs('', '#allmodules', 'grp=6&typ=1&pg=2&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbJrnlEntrsSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "");
                                            $srchInsArrys = array("All", "Batch Name", "Batch Description", "Batch Status", "Batch Number",
                                                "Batch Date");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbJrnlEntrsDsplySze" style="min-width:70px !important;">                            
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
                                                <a href="javascript:getAccbJrnlEntrs('previous', '#allmodules', 'grp=6&typ=1&pg=2&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbJrnlEntrs('next', '#allmodules', 'grp=6&typ=1&pg=2&vtyp=0');" aria-label="Next">
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
                                                $shwUsrOnlyChkd = "";
                                                if ($qShwUsrOnly == true) {
                                                    $shwUsrOnlyChkd = "checked=\"true\"";
                                                }
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getAccbJrnlEntrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbJrnlEntrsShwUsrOnly" name="accbJrnlEntrsShwUsrOnly" <?php echo $shwUsrOnlyChkd; ?>>
                                                Show Only My Batches
                                            </label>
                                        </div>                            
                                    </div>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                        <div class="form-check" style="font-size: 12px !important;">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" onclick="getAccbJrnlEntrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbJrnlEntrsShwUnpstdOnly" name="accbJrnlEntrsShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted Batches
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="accbJrnlEntrsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:25px;width:25px;">No.</th>
                                                <th style="max-width:20px;width:20px;">...</th>
                                                <th style="max-width:20px;width:20px;">...</th>
                                                <th>Batch Number/Name</th>	
                                                <th>Batch Description</th>
                                                <th style="max-width:25px;width:25px;">CUR.</th>	
                                                <th style="text-align:right;">Total Debit</th>
                                                <th style="text-align:right;">Total Credit</th>
                                                <!--<th style="text-align:right;">Net Amount</th>-->
                                                <th style="min-width:120px;width:120px;">Batch. Date</th>
                                                <th style="max-width:80px;width:80px;">Batch Status</th>
                                                <?php if ($canDel === true) { ?>
                                                    <th style="max-width:20px;width:20px;">...</th>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:20px;width:20px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="accbJrnlEntrsHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Journal" 
                                                                onclick="getOneJrnlBatchForm(<?php echo $row[0]; ?>, 1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                    <?php
                                                                    if ($canEdt === true && $row[3] == "0" && strpos($row[5], "Manual") !== FALSE) {
                                                                        ?>                                
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>   
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Simplified Journal" 
                                                                onclick="getOneJrnlBatchForm(<?php echo $row[0]; ?>, 101, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                    <?php
                                                                    if ($canEdt === true && $row[3] == "0" && strpos($row[5], "Manual") !== FALSE) {
                                                                        ?>                                
                                                                <img src="cmn_images/vector-edit-icon.jpg" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/edit-notes.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php
                                                        echo $row[1] . " - " . $row[0] . " (" . $row[5] . ")";
                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[2]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $fnccurnm; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[8], 2);
                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[9], 2);
                                                        ?>
                                                    </td>
                                                    <!--<td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[8] - (float) $row[9], 2);
                                                    ?>
                                                    </td>-->
                                                    <td class="lovtd" style=""><?php echo $row[4]; ?></td> 
                                                    <td class="lovtd"><?php echo ($row[3] == "1" ? "Posted" : "Not Posted (" . $row[7] . ")") . " [" . $row[6] . "]"; ?></td>      
                                                    <!--<td class="lovtd" style=""><?php echo $row[6]; ?></td>-->                                                 
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Journal Batch" onclick="delAccbJrnlBatch('accbJrnlEntrsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="accbJrnlEntrsHdrsRow<?php echo $cntr; ?>_HdrID" name="accbJrnlEntrsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|accb.accb_trnsctn_batches|batch_id"),
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
            } else if ($vwtyp == 1 || $vwtyp == 101) {
                //New Journal Entry Batch Form
                //$canViewJrnlBatchDetLines = true;
                $lmtSze = isset($_POST['accbJrnlBatchDsplySze']) ? cleanInputData($_POST['accbJrnlBatchDsplySze']) : 50;
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                $extraPKeyID = isset($_POST['extraPKeyID']) ? (float) cleanInputData($_POST['extraPKeyID']) : -1;
                $extraPKeyType = isset($_POST['extraPKeyType']) ? cleanInputData($_POST['extraPKeyType']) : "";
                $edtJrnlBatchDetLines = ($canViewJrnlBatchDetLines === true) ? "" : "hideNotice";
                $edtJrnlBatchSmryLines = ($canViewJrnlBatchSmryLines === true && $edtJrnlBatchDetLines == "hideNotice") ? "" : "hideNotice";
                $edtJrnlBatchEditLines = ($canViewJrnlBatchEditLines === true && $edtJrnlBatchSmryLines == "hideNotice" && $edtJrnlBatchDetLines == "hideNotice") ? "" : "hideNotice";

                $edtJrnlBatchDetLinesTab = $canViewJrnlBatchDetLines === true ? "active" : "hideNotice";
                $edtJrnlBatchSmryLinesTab = $canViewJrnlBatchSmryLines === true ? "" : "hideNotice";
                $edtJrnlBatchEditLinesTab = ($canViewJrnlBatchEditLines === true) ? "" : "hideNotice";
                if ($edtJrnlBatchDetLinesTab == "hideNotice" && $edtJrnlBatchSmryLinesTab == "") {
                    $edtJrnlBatchSmryLinesTab = "active";
                }
                if ($edtJrnlBatchDetLinesTab == "hideNotice" && $edtJrnlBatchSmryLinesTab == "hideNotice" && $edtJrnlBatchEditLinesTab == "") {
                    $edtJrnlBatchEditLinesTab = "active";
                }
                //echo $canViewJrnlBatchDetLines . ":" . $canViewJrnlBatchEditLines . ":" . $canViewJrnlBatchSmryLines;
                if (!$canAdd || ($sbmtdJrnlBatchID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $hideSectionCls = "";
                if ($vwtyp == 101) {
                    $hideSectionCls = "display:none;";
                    $edtJrnlBatchDetLinesTab = "hideNotice";
                    $edtJrnlBatchSmryLinesTab = "active";
                    $edtJrnlBatchEditLinesTab = "hideNotice";
                    $edtJrnlBatchDetLines = "hideNotice";
                    $edtJrnlBatchSmryLines = "";
                    $edtJrnlBatchEditLines = "hideNotice";
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
                } else if ($extraPKeyID <= 0) {
                    //$sbmtdJrnlBatchID = getNewJrnlBatchID();
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $userAccntName = getGnrlRecNm("sec.sec_users", "user_id", "user_name", $usrID);
                    $gnrtdTrnsNo1 = substr($userAccntName, 0, 4) . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad((getRecCount_LstNum("accb.accb_trnsctn_batches", "batch_name", "batch_id",
                                            $gnrtdTrnsNo1 . "%") + 1), 3, '0', STR_PAD_LEFT);
                    createBatch($orgID, $gnrtdTrnsNo, $jrnlBatchDesc, "Manual", "VALID", $voidedJrnlBatchID, "0", $jrnlBatchDfltBalsAcntID,
                            $jrnlBatchRvrslRsn, $jrnlBatchDfltCurID, $jrnlBatchDfltTrnsDte);
                    $sbmtdJrnlBatchID = getBatchID($gnrtdTrnsNo, $orgID);
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
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Batch No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltLovID" name="sbmtdTempltLovID" value="-1" readonly="true">
                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltUsrID" name="sbmtdTempltUsrID" value="<?php echo $usrID; ?>" readonly="true">
                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltTrnsCount" name="sbmtdTempltTrnsCount" value="2" readonly="true">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdJrnlBatchID" name="sbmtdJrnlBatchID" value="<?php echo $sbmtdJrnlBatchID; ?>" readonly="true">
                                        <input class="form-control" type="hidden" id="voidedJrnlBatchID" value="<?php echo $voidedJrnlBatchID; ?>"/>
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="jrnlBatchNum" name="jrnlBatchNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Batch Date:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" aria-label="..." id="jrnlBatchCreationDate" name="jrnlBatchCreationDate" value="<?php echo $jrnlBatchCreationDate; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Batch Source:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" aria-label="..." id="jrnlBatchSource" name="jrnlBatchSource" value="<?php echo $jrnlBatchSource; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Cur./Date:</label>
                                    </div>
                                    <div class="col-md-8">
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
                            </div>
                            <div class="col-md-4">                                                               
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="input-group"  style="width:100%;">
                                            <input class="form-control" type="hidden" id="jrnlBatchDesc1" value="<?php echo $jrnlBatchDesc; ?>">
                                            <textarea class="form-control rqrdFld" rows="5" cols="20" id="jrnlBatchDesc" name="jrnlBatchDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $jrnlBatchDesc; ?></textarea>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('jrnlBatchDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label style="margin-bottom:0px !important;">Validity:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="rqstVldty" name="rqstVldty" value="<?php echo $rqstVldty; ?>" readonly="true" style="font-weight:bold;color:<?php echo $rqstVldtyColor; ?>">
                                    </div>
                                    <div class="col-md-6" style="padding:0px 15px 0px 1px;">                             
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;width:100% !important;" id="myJrnlBatchStatusBtn"><span style="font-weight:bold;height:30px;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php echo $rqStatus; ?></span></button>
                                        <input type="hidden" class="form-control" aria-label="..." id="autoPostStatus" name="autoPostStatus" value="<?php echo $autoPostStatus; ?>" readonly="true" style="font-weight:bold;color:<?php echo $autoPostStatusColor; ?>">                                    
                                    </div>
                                </div>
                            </div>
                            <div class = "col-md-4">
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
                                <div class="form-group">
                                    <label for="jrnlBatchDfltBalsAcnt" class="control-label col-md-3">Account:</label>
                                    <div  class="col-md-9">
                                        <div class="input-group">
                                            <input class="form-control" id="jrnlBatchDfltBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $jrnlBatchDfltBalsAcnt; ?>" readonly="true"/>
                                            <input type="hidden" id="jrnlBatchDfltBalsAcntID" value="<?php echo $jrnlBatchDfltBalsAcntID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', '', '', '', 'radio', true, '', 'jrnlBatchDfltBalsAcntID', 'jrnlBatchDfltBalsAcnt', 'clear', 1, '', function () {});">
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
                                    <li class="<?php echo $edtJrnlBatchDetLinesTab; ?>"><a data-toggle="tabajxjrnlbatch" data-rhodata="" href="#jrnlBatchDetLines" id="jrnlBatchDetLinestab">Direct Entries (Debit/Credit)</a></li>
                                    <li class="<?php echo $edtJrnlBatchSmryLinesTab; ?>"><a data-toggle="tabajxjrnlbatch" data-rhodata="&pg=2&vtyp=3&sbmtdJrnlBatchID=<?php echo $sbmtdJrnlBatchID; ?>" href="#jrnlBatchSmryLines" id="jrnlBatchSmryLinestab">Simplified Double Entries</a></li>
                                    <li class="<?php echo $edtJrnlBatchEditLinesTab; ?>"><a data-toggle="tabajxjrnlbatch" data-rhodata="&pg=2&vtyp=2&sbmtdJrnlBatchID=<?php echo $sbmtdJrnlBatchID; ?>" href="#jrnlBatchEditLines" id="jrnlBatchEditLinestab">Entries (Increase/Decrease)</a></li>
                                </ul>  
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
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
                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneJrnlBatchDetRow_WWW123WWW_TransDte\" value=\"" . $jrnlBatchDfltTrnsDte . "\">
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

                                                $nwRowHtml1 = "<tr id=\"oneJrnlBatchEditRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneJrnlBatchEditLinesTable tr').index(this));\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneJrnlBatchEditRow_WWW123WWW_IncrsDcrs1\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml1 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml1 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_AccountID1\" value=\"-1\" style=\"width:100% !important;\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">    
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_TrnsSmryLnID\" value=\"-1\" style=\"width:100% !important;\">   
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_AccountNm1\" name=\"oneJrnlBatchEditRow_WWW123WWW_AccountNm1\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchEditRow_WWW123WWW_AccountID1', 'oneJrnlBatchEditRow_WWW123WWW_AccountNm1', 'clear', 1, '', function () { });\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>                                            
                                                        </td>                                          
                                                        <td class=\"lovtd\"  style=\"\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_LineDesc\" name=\"oneJrnlBatchEditRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchEditRow_WWW123WWW_LineDesc', 'oneJrnlBatchEditLinesTable', 'jbDetDesc');\"> 
                                                        </td>                                                
                                                        <td class=\"lovtd\"  style=\"\">
                                                            <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_RefDoc\" name=\"oneJrnlBatchEditRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchEditRow_WWW123WWW_RefDoc', 'oneJrnlBatchEditLinesTable', 'jbDetRfDc');\">                                                    
                                                        </td>                                          
                                                        <td class=\"lovtd\">
                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_TrnsCurNm\" name=\"oneJrnlBatchEditRow_WWW123WWW_TrnsCurNm\" value=\"" . $jrnlBatchDfltCurNm . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchEditRow_WWW123WWW_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                $('#oneJrnlBatchEditRow_WWW123WWW_TrnsCurNm1').html($('#oneJrnlBatchEditRow_WWW123WWW_TrnsCurNm').val());
                                                                                                afterJrnlBatchCurSlctn('oneJrnlBatchEditRow__WWW123WWW');
                                                                                            });\">
                                                                    <span class=\"\" id=\"oneJrnlBatchEditRow_WWW123WWW_TrnsCurNm1\">" . $jrnlBatchDfltCurNm . "</span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_EntrdAmt\" name=\"oneJrnlBatchEditRow_WWW123WWW_EntrdAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchEditRow_WWW123WWW_EntrdAmt', 'oneJrnlBatchEditLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchEditTtl();\">                                                    
                                                        </td>  
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Denominational Breakdown\" 
                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneJrnlBatchEditRow_WWW123WWW_EntrdAmt', 'oneJrnlBatchEditRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneJrnlBatchEditRow_WWW123WWW_TransDte\" value=\"" . $jrnlBatchDfltTrnsDte . "\" style=\"width:100%;\" onchange=\"afterJrnlBatchCurSlctn('oneJrnlBatchEditRow__WWW123WWW');\">
                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>                                                        
                                                        </td>
                                                        <td class=\"lovtd\" style=\"\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetFuncRate\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_FuncExchgRate\" name=\"oneJrnlBatchEditRow_WWW123WWW_FuncExchgRate\" value=\"1.0000\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchEditRow_WWW123WWW_FuncExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetFuncRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchEditTtl();\">                                                    
                                                        </td> 
                                                        <td class=\"lovtd\" style=\"\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneJrnlBatchEditRow_WWW123WWW_AcntExchgRate\" name=\"oneJrnlBatchEditRow_WWW123WWW_AcntExchgRate\" value=\"1.0000\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchEditRow_WWW123WWW_AcntExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchEditTtl();\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbJrnlBatchDetLn('oneJrnlBatchEditRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete GL Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";
                                                $nwRowHtml1 = urlencode($nwRowHtml1);

                                                $nwRowHtml3 = "<tr id=\"oneJrnlBatchSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneJrnlBatchSmryLinesTable tr').index(this));\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                        <td class=\"lovtd\"  style=\"\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_LineDesc\" name=\"oneJrnlBatchSmryRow_WWW123WWW_LineDesc\" value=\"\" 
                                                                   style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow_WWW123WWW_LineDesc', 'oneJrnlBatchSmryLinesTable', 'jbDetDesc');\">                                                    
                                                        </td>                                                 
                                                        <td class=\"lovtd\"  style=\"\">
                                                            <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_RefDoc\" name=\"oneJrnlBatchSmryRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow_WWW123WWW_RefDoc', 'oneJrnlBatchSmryLinesTable', 'jbDetRfDc');\">                                                    
                                                        </td>                                          
                                                        <td class=\"lovtd\">
                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_TrnsCurNm\" name=\"oneJrnlBatchSmryRow_WWW123WWW_TrnsCurNm\" value=\"" . $jrnlBatchDfltCurNm . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow_WWW123WWW_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                $('#oneJrnlBatchSmryRow_WWW123WWW_TrnsCurNm1').html($('#oneJrnlBatchSmryRow_WWW123WWW_TrnsCurNm').val());
                                                                                                afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow__WWW123WWW');
                                                                                            });\">
                                                                    <span class=\"\" id=\"oneJrnlBatchSmryRow_WWW123WWW_TrnsCurNm1\">" . $jrnlBatchDfltCurNm . "</span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_EntrdAmt\" name=\"oneJrnlBatchSmryRow_WWW123WWW_EntrdAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow_WWW123WWW_EntrdAmt', 'oneJrnlBatchSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchSmryTtl();\">                                                    
                                                        </td>   
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Denominational Breakdown\" 
                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneJrnlBatchSmryRow_WWW123WWW_EntrdAmt', 'oneJrnlBatchSmryRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>      
                                                        <td class=\"lovtd\">
                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneJrnlBatchSmryRow_WWW123WWW_TransDte\" value=\"" . $jrnlBatchDfltTrnsDte . "\" style=\"width:100%;\" onchange=\"afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow__WWW123WWW');\">
                                                                    <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                </div>                                                        
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneJrnlBatchSmryRow_WWW123WWW_IncrsDcrs1\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml3 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml3 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_AccountID1\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_AccountNm1\" name=\"oneJrnlBatchSmryRow_WWW123WWW_AccountNm1\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow_WWW123WWW_AccountID1', 'oneJrnlBatchSmryRow_WWW123WWW_AccountNm1', 'clear', 1, '', function () {});\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>                                           
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneJrnlBatchSmryRow_WWW123WWW_IncrsDcrs2\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml3 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml3 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_AccountID2\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_AccountNm2\" name=\"oneJrnlBatchSmryRow_WWW123WWW_AccountNm2\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow_WWW123WWW_AccountID2', 'oneJrnlBatchSmryRow_WWW123WWW_AccountNm2', 'clear', 1, '', function () {});\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>                                            
                                                        </td>
                                                        <td class=\"lovtd\" style=\"\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetFuncRate\" aria-label=\"...\" id=\"oneJrnlBatchSmryRow_WWW123WWW_FuncExchgRate\" name=\"oneJrnlBatchSmryRow_WWW123WWW_FuncExchgRate\" value=\"1.0000\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow_WWW123WWW_FuncExchgRate', 'oneJrnlBatchSmryLinesTable', 'jbDetFuncRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchSmryTtl();\">                                                    
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbJrnlBatchSmmryLn('oneJrnlBatchSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete GL Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";
                                                $nwRowHtml3 = urlencode($nwRowHtml3);
                                                ?> 
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchDetHtml" name="addNwJrnlBatchDetHtml" value="<?php echo $nwRowHtml2; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchEditHtml" name="addNwJrnlBatchEditHtml" value="<?php echo $nwRowHtml1; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchSmryHtml" name="addNwJrnlBatchSmryHtml" value="<?php echo $nwRowHtml3; ?>" readonly="true">
                                                            <button id="addNwJrnlBatchDetBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchDetLines; ?>" style="margin-bottom: 1px;height:30px;" onclick="insertNewJrnlBatcRows('oneJrnlBatchDetLinesTable', 0, '<?php echo $nwRowHtml2; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Detailed Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>                     
                                                            <button id="addNwJrnlBatchDetTmpltBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchDetLines; ?>" style="margin-bottom: 1px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Transaction Templates', 'allOtherInputOrgID', 'sbmtdTempltUsrID', '', 'check', true, '', 'sbmtdTempltLovID', 'sbmtdTempltLovID', 'clear', 1, '', function () {
                                                                        getJrnlFrmTmplate(4, 'jrnlBatchDetLines');
                                                                    });" data-toggle="tooltip" data-placement="bottom" title = "Add Journal Entries from Templates">
                                                                <img src="cmn_images/plus_32.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Templates
                                                            </button>      
                                                            <button id="addNwJrnlBatchEditBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchEditLines; ?>" style="margin-bottom: 1px;height:30px;" onclick="insertNewJrnlBatcRows('oneJrnlBatchEditLinesTable', 0, '<?php echo $nwRowHtml1; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>                     
                                                            <button id="addNwJrnlBatchEditTmpltBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchEditLines; ?>" style="margin-bottom: 1px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Transaction Templates', 'allOtherInputOrgID', 'sbmtdTempltUsrID', '', 'check', true, '', 'sbmtdTempltLovID', '', 'clear', 1, '', function () {
                                                                        getJrnlFrmTmplate(2, 'jrnlBatchEditLines');
                                                                    });" data-toggle="tooltip" data-placement="bottom" title = "Add Journal Entries from Templates">
                                                                <img src="cmn_images/plus_32.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Templates
                                                            </button>
                                                            <button id="addNwJrnlBatchSmryBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchSmryLines; ?>" style="margin-bottom: 1px;height:30px;" onclick="insertNewJrnlBatcRows('oneJrnlBatchSmryLinesTable', 0, '<?php echo $nwRowHtml3; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Simplified Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>                     
                                                            <button id="addNwJrnlBatchSmryTmpltBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchSmryLines; ?>" style="margin-bottom: 1px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Transaction Templates', 'allOtherInputOrgID', 'sbmtdTempltUsrID', 'sbmtdTempltTrnsCount', 'check', true, '', 'sbmtdTempltLovID', '', 'clear', 1, '', function () {
                                                                        getJrnlFrmTmplate(3, 'jrnlBatchSmryLines');
                                                                    });" data-toggle="tooltip" data-placement="bottom" title = "Add Journal Entries from Templates">
                                                                <img src="cmn_images/plus_32.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Templates
                                                            </button>
                                                            <button id="exprtNwJrnlBatchDetBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchDetLines; ?>" style="margin-bottom: 1px;" onclick="exprtAccntDetTrns();" style="" data-toggle="tooltip" data-placement="bottom" title = "Export Direct Entries(Debit/Credit)">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Export
                                                            </button>
                                                            <button id="imprtNwJrnlBatchDetBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchDetLines; ?>" style="margin-bottom: 1px;" onclick="importAccntDetTrns(<?php echo $sbmtdJrnlBatchID; ?>);" style="" data-toggle="tooltip" data-placement="bottom" title = "Import Direct Entries(Debit/Credit)">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Import
                                                            </button>
                                                            <button id="exprtNwJrnlBatchSmryBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchSmryLines; ?>" style="margin-bottom: 1px;" onclick="exprtAccntSmmryTrns();" style="" data-toggle="tooltip" data-placement="bottom" title = "Export Simplified Double Entries">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Export
                                                            </button>
                                                            <button id="imprtNwJrnlBatchSmryBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchSmryLines; ?>" style="margin-bottom: 1px;" onclick="importAccntSmmryTrns(<?php echo $sbmtdJrnlBatchID; ?>);" style="" data-toggle="tooltip" data-placement="bottom" title = "Import Simplified Double Entries">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Import
                                                            </button> 
                                                            <button id="exprtNwJrnlBatchEditBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchEditLines; ?>" style="margin-bottom: 1px;" onclick="exprtAccntEditTrns();" style="" data-toggle="tooltip" data-placement="bottom" title = "Export Entries(Increase/Decrease)">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Export
                                                            </button>
                                                            <button id="imprtNwJrnlBatchEditBtn" type="button" class="btn btn-default <?php echo $edtJrnlBatchEditLines; ?>" style="margin-bottom: 1px;" onclick="importAccntEditTrns(<?php echo $sbmtdJrnlBatchID; ?>);" style="" data-toggle="tooltip" data-placement="bottom" title = "Import Entries(Increase/Decrease)">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Import
                                                            </button>                                 
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneJrnlBatchDocsForm(<?php echo $sbmtdJrnlBatchID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneJrnlBatchForm(<?php echo $sbmtdJrnlBatchID; ?>, <?php echo $vwtyp; ?>, 'ReloadDialog',<?php echo $extraPKeyID; ?>, '<?php echo $extraPKeyType; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbJrnlBatchDsplySze" style="max-width:70px !important;display:inline-block;" onchange="getOneJrnlBatchForm(<?php echo $sbmtdJrnlBatchID; ?>, <?php echo $vwtyp; ?>, 'ReloadDialog',<?php echo $extraPKeyID; ?>, '<?php echo $extraPKeyType; ?>');" data-toggle="tooltip" title="No. of Records to Display">                            
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "");
                                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 50000, 1000000000);
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
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlBatchForm('<?php echo $fnccurnm; ?>', 0, -1, -1, '',<?php echo $extraPKeyID; ?>, '<?php echo $extraPKeyType; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                                    <?php } ?>
                                                                    <?php
                                                                }
                                                                if ($canPost && $sbmtdJrnlBatchID > 0) {
                                                                    ?>  
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlBatchForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>',<?php echo $extraPKeyID; ?>, '<?php echo $extraPKeyType; ?>');"><img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post Batch&nbsp;</button>
                                                                    <?php
                                                                } else if ($sbmtdJrnlBatchID > 0) {
                                                                    ?>  
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="bootbox.alert({title: 'System Alert!', size: 'small', message: 'Permission Denied!'});"><img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post Batch&nbsp;</button>
                                                                    <?php
                                                                }
                                                            } else if ($rqStatus == "Posted") {
                                                                ?>
                                                                <button id="fnlzeRvrslJrnlBatchBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlRvrsBatchForm('<?php echo $fnccurnm; ?>', 1,<?php echo $extraPKeyID; ?>, '<?php echo $extraPKeyType; ?>');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                                                                   
                                                                <?php
                                                            }
                                                            if ($extraPKeyID > 0 && $extraPKeyType == "Petty Cash") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPttyCashForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    Go Back&nbsp;
                                                                </button>                                                                   
                                                                <?php
                                                            } else if ($extraPKeyID > 0 && $extraPKeyType == "Payable Invoice") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPyblsInvcForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    Go Back&nbsp;
                                                                </button>                                                                   
                                                                <?php
                                                            } else if ($extraPKeyID > 0 && $extraPKeyType == "Receivable Invoice") {
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
                                                            } else if ($extraPKeyID > 0 && $extraPKeyType == "Payment Batch") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPymntsForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    Go Back&nbsp;
                                                                </button>                                                                   
                                                                <?php
                                                            } else if ($extraPKeyID > 0 && strpos($extraPKeyType, "Payment History") !== FALSE) {
                                                                $accbInvcVchType = explode("|", $extraPKeyType)[1];
                                                                $extraPKeyType = explode("|", $extraPKeyType)[2];
                                                                $extraPKeyID1 = $extraPKeyID;
                                                                if (strpos($extraPKeyType, "Sales Invoice") !== FALSE) {
                                                                    $extraPKeyID1 = (float) getGnrlRecNm("accb.accb_rcvbls_invc_hdr",
                                                                                    "rcvbls_invc_hdr_id", "src_doc_hdr_id", $extraPKeyID);
                                                                }
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPymntsHstryForm(<?php echo $extraPKeyID; ?>, 103, 'ReloadDialog',<?php echo $extraPKeyID1; ?>, '<?php echo $extraPKeyType; ?>', '<?php echo $accbInvcVchType; ?>');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    Go Back&nbsp;
                                                                </button>                                                                   
                                                                <?php
                                                            } else if ($extraPKeyID > 0 && $extraPKeyType == "Asset Transaction") {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getAccbAstHdr('', '#assetDetlsTrans', 'grp=6&typ=1&pg=9&vtyp=2');">
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
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneJrnlBatchLnsTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">    
                                        <div id="jrnlBatchDetLines" class="tab-pane fadein <?php echo $edtJrnlBatchDetLinesTab; ?>" style="border:none !important;padding:0px !important;">
                                            <?php
                                            if ($edtJrnlBatchDetLinesTab == "active") {
                                                ?>
                                                <!-- Show ALways-->
                                            <?php } ?>
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
                                                                <th style="max-width:120px;width:120px;">Transaction Date</th>
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
                                                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
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
                                                    <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchDetCount" name="addNwJrnlBatchDetCount" value="<?php echo $cntr; ?>" readonly="true">
                                                </div>
                                            </div>
                                        </div>       
                                        <div id="jrnlBatchEditLines" class="tab-pane fadein <?php echo $edtJrnlBatchEditLinesTab; ?>" style="border:none !important;padding:0px !important;">
                                            <?php
                                            if ($edtJrnlBatchEditLinesTab == "active") {
                                                //Journal Batch Edit Lines
                                                $sbmtdTempltLovID = isset($_POST['sbmtdTempltLovID']) ? cleanInputData($_POST['sbmtdTempltLovID']) : "";
                                                $jrnlBatchAmountCrncy = isset($_POST['jrnlBatchAmountCrncy']) ? cleanInputData($_POST['jrnlBatchAmountCrncy']) : $fnccurnm;
                                                $jrnlBatchDfltTrnsDte = isset($_POST['jrnlBatchDfltTrnsDte']) ? cleanInputData($_POST['jrnlBatchDfltTrnsDte']) : $gnrlTrnsDteDMYHMS;
                                                $jrnlBatchDfltBalsAcnt = isset($_POST['jrnlBatchDfltBalsAcnt']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcnt']) : "";
                                                $jrnlBatchDfltBalsAcntID = isset($_POST['jrnlBatchDfltBalsAcntID']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcntID']) : -1;
                                                //$sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                                                $orgnlJrnlBatchID = $sbmtdJrnlBatchID;
                                                ?> 
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="oneJrnlBatchEditLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:20px;width:20px;">No.</th>
                                                                    <th style="max-width:80px;width:80px;">Increase/ Decrease</th>
                                                                    <th style="max-width:170px;width:170px;">GL Transaction Account</th>
                                                                    <th style="">Narration/Remarks</th>
                                                                    <th style="max-width:90px;width:90px;">Reference Doc. No.</th>
                                                                    <th style="max-width:35px;width:35px;">CUR.</th>
                                                                    <th style="max-width:70px;width:70px;">Entered Amount</th>
                                                                    <th style="max-width:20px;width:20px;">...</th>
                                                                    <th style="max-width:150px;width:150px;">Transaction Date</th>
                                                                    <th style="max-width:80px;width:80px;">Functional Currency Exchange Rate</th>
                                                                    <th style="max-width:80px;width:80px;">Account Currency Exchange Rate</th>
                                                                    <th style="max-width:20px;width:20px;">...</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>   
                                                                <?php
                                                                $cntr = 0;
                                                                $ttlTrsctnEntrdAmnt = 0;
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
                                                                    $trsctnLineStatus = "";
                                                                    $trsctnLineDate = "";
                                                                    $trnsIncrsDcrs1 = "Increase";
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

                                                                        $dbtOrCrdt = $rowRw[23];
                                                                        if ($dbtOrCrdt == "C") {
                                                                            $trnsIncrsDcrs1 = str_replace("d", "D",
                                                                                    str_replace("i", "I",
                                                                                            strtolower(incrsOrDcrsAccnt($trsctnAcntID,
                                                                                                            "Credit"))));
                                                                        } else {
                                                                            $trnsIncrsDcrs1 = str_replace("d", "D",
                                                                                    str_replace("i", "I",
                                                                                            strtolower(incrsOrDcrsAccnt($trsctnAcntID,
                                                                                                            "Debit"))));
                                                                        }
                                                                        $ttlTrsctnDbtAmnt += $trsctnDbtAmnt;
                                                                        $ttlTrsctnCrdtAmnt += $trsctnCrdtAmnt;
                                                                        $ttlTrsctnNetAmnt += $trsctnNetAmnt;
                                                                        $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;
                                                                    }
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneJrnlBatchEditRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>    
                                                                        <td class="lovtd">
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">   
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsSmryLnID" value="<?php echo $trsctnSmryLineID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountID1', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {

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
                                                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchEditLinesTable', 'jbDetDesc');">                                                    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLineDesc; ?></span>
                                                                            <?php } ?>   
                                                                        </td>                                                
                                                                        <td class="lovtd"  style="">
                                                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchEditLinesTable', 'jbDetRfDc');">                                                    
                                                                        </td>                                          
                                                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                                                            <div class="" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                            $('#oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                            afterJrnlBatchCurSlctn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');
                                                                                        });">
                                                                                    <span class="" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                                                            echo number_format($entrdAmnt, 2);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchEditLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>" style="width:100%;" onchange="afterJrnlBatchCurSlctn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLineDate; ?></span>
                                                                            <?php } ?>                                                         
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                                                            echo number_format($funcCrncyRate, 4);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                                                        </td> 
                                                                        <td class="lovtd" style="">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate" value="<?php
                                                                            echo number_format($acntCrncyRate, 4);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchDetLn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                $mkReadOnly = $ornlMkReadOnly;
                                                                $resultRw = get_Multi_Tmplt_Trns($sbmtdTempltLovID);
                                                                $maxNoRows = loc_db_num_rows($resultRw);
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
                                                                    $trnsIncrsDcrs1 = "Increase";
                                                                    $tmplateID = -1;
                                                                    $detailID = -1;

                                                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                        $trsctnLineID = -1;
                                                                        $trsctnSmryLineID = -1;
                                                                        $tmplateID = (float) $rowRw[6];
                                                                        $detailID = (float) $rowRw[0];
                                                                        $trsctnLineDesc = $rowRw[4];
                                                                        $trsctnLineRefDoc = "";
                                                                        $funcCurID = $fnccurid;
                                                                        $funcCurNm = $fnccurnm;
                                                                        $trsctnDbtAmnt = 0;
                                                                        $trsctnCrdtAmnt = 0;
                                                                        $trsctnNetAmnt = 0;
                                                                        $entrdCurID = getPssblValID($jrnlBatchAmountCrncy,
                                                                                getLovID("Currencies"));
                                                                        $entrdAmnt = 0;
                                                                        $entrdCurNm = $jrnlBatchAmountCrncy;
                                                                        $trsctnAcntID = (int) $rowRw[5];
                                                                        $trsctnAcntNm = $rowRw[2] . "." . $rowRw[3];
                                                                        $acntCrncyRate = 1.000;
                                                                        $trsctnLineStatus = "0";
                                                                        $trsctnLineDate = $jrnlBatchDfltTrnsDte;
                                                                        $funcCrncyRate = round(get_LtstExchRate($entrdCurID, $funcCurID,
                                                                                        $trsctnLineDate), 4);
                                                                        $trnsIncrsDcrs1 = $rowRw[1] == "I" ? "Increase" : "Decrease";
                                                                        $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnDbtAmnt;
                                                                        $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnCrdtAmnt;
                                                                        $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnNetAmnt;
                                                                        $isPosted = "false";
                                                                    }
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneJrnlBatchEditRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>    
                                                                        <td class="lovtd">
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">   
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsSmryLnID" value="<?php echo $trsctnSmryLineID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountID1', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {

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
                                                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchEditLinesTable', 'jbDetDesc');">                                                    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLineDesc; ?></span>
                                                                            <?php } ?>   
                                                                        </td>                                                
                                                                        <td class="lovtd"  style="">
                                                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchEditLinesTable', 'jbDetRfDc');">                                                    
                                                                        </td>                                          
                                                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                                                            <div class="" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                            $('#oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                            afterJrnlBatchCurSlctn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');
                                                                                        });">
                                                                                    <span class="" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                                                            echo number_format($entrdAmnt, 2);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchEditLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>" style="width:100%;" onchange="afterJrnlBatchCurSlctn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLineDate; ?></span>
                                                                            <?php } ?>                                                         
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                                                            echo number_format($funcCrncyRate, 4);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                                                        </td> 
                                                                        <td class="lovtd" style="">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate" value="<?php
                                                                            echo number_format($acntCrncyRate, 4);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchDetLn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
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
                                                                    <th>&nbsp;</th>
                                                                    <th>&nbsp;</th>
                                                                    <th>TOTALS:</th>
                                                                    <th>&nbsp;</th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdEntrdAmntTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                                                2, '.', ',') . "</span>";
                                                                        ?>
                                                                        <input type="hidden" id="myCptrdEntrdAmntTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                                    </th>
                                                                    <th style="">&nbsp;</th>                                           
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchEditCount" name="addNwJrnlBatchEditCount" value="<?php echo $cntr; ?>" readonly="true">
                                                    </div>
                                                </div>
                                            <?php }
                                            ?>
                                        </div> 
                                        <div id = "jrnlBatchSmryLines" class = "tab-pane fadein <?php echo $edtJrnlBatchSmryLinesTab; ?>" style = "border:none !important;padding:0px !important;">
                                            <?php
                                            if ($edtJrnlBatchSmryLinesTab == "active") {
                                                //Journal Batch Simplified Lines
                                                $sbmtdTempltLovID = isset($_POST['sbmtdTempltLovID']) ? cleanInputData($_POST['sbmtdTempltLovID']) : "";
                                                $jrnlBatchAmountCrncy = isset($_POST['jrnlBatchAmountCrncy']) ? cleanInputData($_POST['jrnlBatchAmountCrncy']) : $fnccurnm;
                                                $jrnlBatchDfltTrnsDte = isset($_POST['jrnlBatchDfltTrnsDte']) ? cleanInputData($_POST['jrnlBatchDfltTrnsDte']) : $gnrlTrnsDteDMYHMS;
                                                $jrnlBatchDfltBalsAcnt = isset($_POST['jrnlBatchDfltBalsAcnt']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcnt']) : "";
                                                $jrnlBatchDfltBalsAcntID = isset($_POST['jrnlBatchDfltBalsAcntID']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcntID']) : -1;
                                                //$sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                                                $orgnlJrnlBatchID = $sbmtdJrnlBatchID;
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="oneJrnlBatchSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:20px;width:20px;">No.</th>
                                                                    <th style="">Narration/Remark</th>
                                                                    <th style="max-width:70px;width:70px;">Reference Doc. No.</th>
                                                                    <th style="max-width:35px;width:35px;">CUR.</th>
                                                                    <th style="max-width:80px;width:80px;">Entered Amount</th>
                                                                    <th style="max-width:20px;width:20px;">...</th>
                                                                    <th style="max-width:120px;width:120px;">Transaction Date</th>
                                                                    <th style="max-width:50px;width:50px;text-align: center;">I/D</th>
                                                                    <th style="max-width:170px;width:170px;">Charge Account</th>
                                                                    <th style="max-width:50px;width:50px;text-align: center;">I/D</th>
                                                                    <th style="max-width:150px;width:150px;">Balancing Account Leg</th>
                                                                    <th style="max-width:70px;width:70px;">Functional Currency Exchange Rate</th>
                                                                    <th style="max-width:20px;width:20px;">...</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>   
                                                                <?php
                                                                $cntr = 0;
                                                                $resultRw = get_One_JrnlSmmry_Trns($sbmtdJrnlBatchID, $lmtSze);
                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                                $excludedItems = "";
                                                                $ttlTrsctnEntrdAmnt = 0;
                                                                $ttlTrsctnDbtAmnt = 0;
                                                                $ttlTrsctnCrdtAmnt = 0;
                                                                $ttlTrsctnNetAmnt = 0;
                                                                while ($cntr < $maxNoRows) {
                                                                    $trsctnDbtAmnt = 0;
                                                                    $trsctnCrdtAmnt = 0;
                                                                    $trsctnNetAmnt = 0;
                                                                    $trsctnLineID = -1;
                                                                    $trsctnLineDesc = "";
                                                                    $trsctnLineRefDoc = "";
                                                                    $funcCurID = -1;
                                                                    $funcCurNm = "";
                                                                    $entrdCurID = -1;
                                                                    $entrdAmnt = 0.00;
                                                                    $entrdCurNm = "";
                                                                    $acntCrncyRate = 0;
                                                                    $funcCrncyRate = 0;
                                                                    $trsctnLineStatus = "";
                                                                    $trsctnLineDate = "";
                                                                    $trnsIncrsDcrs1 = "Increase";
                                                                    $trsctnAcntID1 = -1;
                                                                    $trsctnAcntNm1 = -1;
                                                                    $trnsIncrsDcrs2 = "Increase";
                                                                    $trsctnAcntID2 = -1;
                                                                    $trsctnAcntNm2 = -1;
                                                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                        $trsctnLineID = (float) $rowRw[0];
                                                                        $trsctnLineDesc = $rowRw[2];
                                                                        $trsctnLineRefDoc = $rowRw[1];
                                                                        $entrdCurID = (int) $rowRw[11];
                                                                        $entrdAmnt = (float) $rowRw[3];
                                                                        $entrdCurNm = $rowRw[12];
                                                                        $trnsIncrsDcrs1 = $rowRw[5];
                                                                        $trsctnAcntID1 = $rowRw[6];
                                                                        $trsctnAcntNm1 = $rowRw[7];

                                                                        $trnsIncrsDcrs2 = $rowRw[8];
                                                                        $trsctnAcntID2 = $rowRw[9];
                                                                        $trsctnAcntNm2 = $rowRw[10];

                                                                        $funcCrncyRate = (float) $rowRw[13];
                                                                        $trsctnLineDate = $rowRw[4];
                                                                        $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;
                                                                    }
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneJrnlBatchSmryRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                        <td class="lovtd"  style="">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                                                            <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" 
                                                                                   style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchSmryLinesTable', 'jbDetDesc');">                                                    
                                                                        </td>                                                 
                                                                        <td class="lovtd"  style="">
                                                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchSmryLinesTable', 'jbDetRfDc');">                                                    
                                                                        </td>                                          
                                                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                                                            <div class="" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                            $('#oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                            afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');
                                                                                        });">
                                                                                    <span class="" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                                                            echo number_format($entrdAmnt, 2);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchSmryTtl();">                                                    
                                                                        </td>   
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>   
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>" style="width:100%;" onchange="afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLineDate; ?></span>
                                                                            <?php } ?>                                                         
                                                                        </td> 
                                                                        <td class="lovtd">
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID1', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnAcntNm1; ?></span>
                                                                            <?php } ?>                                             
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_IncrsDcrs2" style="width:100% !important;">
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
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID2" value="<?php echo $trsctnAcntID2; ?>" style="width:100% !important;"> 
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2" value="<?php echo $trsctnAcntNm2; ?>" readonly="true" style="width:100% !important;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID2', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnAcntNm2; ?></span>
                                                                            <?php } ?>                                             
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                                                            echo number_format($funcCrncyRate, 4);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate', 'oneJrnlBatchSmryLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchSmryTtl();">                                                    
                                                                        </td> 
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchSmmryLn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }

                                                                $resultRw = get_Multi_Tmplt_Trns1($sbmtdTempltLovID);
                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                                while ($cntr < $maxNoRows) {
                                                                    $trsctnLineID = -1;
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
                                                                    $trnsIncrsDcrs1 = "Increase";
                                                                    $trsctnAcntID1 = -1;
                                                                    $trsctnAcntNm1 = -1;
                                                                    $trnsIncrsDcrs2 = "Increase";
                                                                    $trsctnAcntID2 = -1;
                                                                    $trsctnAcntNm2 = -1;
                                                                    $tmplateID = -1;
                                                                    $detailID = -1;

                                                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                        $trsctnLineID = -1;
                                                                        $trsctnLineDesc = $rowRw[2];
                                                                        $trsctnLineRefDoc = "";
                                                                        $funcCurID = $fnccurid;
                                                                        $funcCurNm = $fnccurnm;
                                                                        $trsctnDbtAmnt = 0;
                                                                        $trsctnCrdtAmnt = 0;
                                                                        $trsctnNetAmnt = 0;
                                                                        $entrdCurID = getPssblValID($jrnlBatchAmountCrncy,
                                                                                getLovID("Currencies"));
                                                                        $entrdAmnt = 0;
                                                                        $entrdCurNm = $jrnlBatchAmountCrncy;
                                                                        $acntCrncyRate = 1.000;
                                                                        $trsctnLineStatus = "0";
                                                                        $trsctnLineDate = $jrnlBatchDfltTrnsDte;
                                                                        $funcCrncyRate = round(get_LtstExchRate($entrdCurID, $funcCurID,
                                                                                        $trsctnLineDate), 4);
                                                                        $trnsIncrsDcrs1 = $rowRw[0] == "I" ? "Increase" : "Decrease";
                                                                        $trsctnAcntID1 = (int) $rowRw[3];
                                                                        $trsctnAcntNm1 = $rowRw[1];

                                                                        $trnsIncrsDcrs2 = $rowRw[4] == "I" ? "Increase" : "Decrease";
                                                                        $trsctnAcntID2 = $rowRw[7];
                                                                        $trsctnAcntNm2 = $rowRw[5];

                                                                        $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnDbtAmnt;
                                                                        $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnCrdtAmnt;
                                                                        $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnNetAmnt;
                                                                        $isPosted = "false";
                                                                    }
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneJrnlBatchSmryRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                        <td class="lovtd"  style="">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                                                            <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" 
                                                                                   style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchSmryLinesTable', 'jbDetDesc');">                                                    
                                                                        </td>                                                 
                                                                        <td class="lovtd"  style="">
                                                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchSmryLinesTable', 'jbDetRfDc');">                                                    
                                                                        </td>                                          
                                                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                                                            <div class="" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                            $('#oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                            afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');
                                                                                        });">
                                                                                    <span class="" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                                                            echo number_format($entrdAmnt, 2);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchSmryTtl();">                                                    
                                                                        </td>   
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>   
                                                                        <td class="lovtd">
                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                <input class="form-control" size="16" type="text" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>" style="width:100%;" onchange="afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');">
                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>                                                       
                                                                        </td> 
                                                                        <td class="lovtd">
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnAcntNm1; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID1', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {
                                                                                                changeElmntTitleFunc('oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1');
                                                                                            });">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnAcntNm1; ?></span>
                                                                            <?php } ?>                                             
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_IncrsDcrs2" style="width:100% !important;">
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
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID2" value="<?php echo $trsctnAcntID2; ?>" style="width:100% !important;"> 
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2" value="<?php echo $trsctnAcntNm2; ?>" readonly="true" style="width:100% !important;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID2', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>    
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnAcntNm2; ?></span>
                                                                            <?php } ?>                                             
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                                                            echo number_format($funcCrncyRate, 4);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate', 'oneJrnlBatchSmryLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchSmryTtl();">                                                    
                                                                        </td> 
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchSmmryLn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
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
                                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                                                2, '.', ',') . "</span>";
                                                                        ?>
                                                                        <input type="hidden" id="myCptrdJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                                    </th>
                                                                    <th style="">&nbsp;</th>                                           
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchSmryCount" name="addNwJrnlBatchSmryCount" value="<?php echo $cntr; ?>" readonly="true">
                                                    </div>
                                                </div>
                                            <?php }
                                            ?>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php
            } else if ($vwtyp == 2) {
                //Journal Batch Edit Lines
                $lmtSze = isset($_POST['accbJrnlBatchDsplySze']) ? cleanInputData($_POST['accbJrnlBatchDsplySze']) : 50;
                $sbmtdTempltLovID = isset($_POST['sbmtdTempltLovID']) ? cleanInputData($_POST['sbmtdTempltLovID']) : "";
                $jrnlBatchAmountCrncy = isset($_POST['jrnlBatchAmountCrncy']) ? cleanInputData($_POST['jrnlBatchAmountCrncy']) : $fnccurnm;
                $jrnlBatchDfltTrnsDte = isset($_POST['jrnlBatchDfltTrnsDte']) ? cleanInputData($_POST['jrnlBatchDfltTrnsDte']) : $gnrlTrnsDteDMYHMS;
                $jrnlBatchDfltBalsAcnt = isset($_POST['jrnlBatchDfltBalsAcnt']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcnt']) : "";
                $jrnlBatchDfltBalsAcntID = isset($_POST['jrnlBatchDfltBalsAcntID']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcntID']) : -1;
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
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
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdJrnlBatchID > 0) {
                    $jrnlBatchSource = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_source", $sbmtdJrnlBatchID);
                    $rqStatus = (getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_status", $sbmtdJrnlBatchID)) == "1" ? "Posted" : "Not Posted";
                    if (strpos($jrnlBatchSource, "Manual") === FALSE || $rqStatus == "Posted") {
                        $canEdt = FALSE;
                        $mkReadOnly = "readonly=\"true\"";
                        $mkRmrkReadOnly = "readonly=\"true\"";
                    }
                }
                ?> 
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneJrnlBatchEditLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                            <thead>
                                <tr>
                                    <th style="max-width:20px;width:20px;">No.</th>
                                    <th style="max-width:80px;width:80px;">Increase/ Decrease</th>
                                    <th style="max-width:170px;width:170px;">GL Transaction Account</th>
                                    <th style="">Narration/Remarks</th>
                                    <th style="max-width:90px;width:90px;">Reference Doc. No.</th>
                                    <th style="max-width:35px;width:35px;">CUR.</th>
                                    <th style="max-width:70px;width:70px;">Entered Amount</th>
                                    <th style="max-width:20px;width:20px;">...</th>
                                    <th style="max-width:150px;width:150px;">Transaction Date</th>
                                    <th style="max-width:80px;width:80px;">Functional Currency Exchange Rate</th>
                                    <th style="max-width:80px;width:80px;">Account Currency Exchange Rate</th>
                                    <th style="max-width:20px;width:20px;">...</th>
                                </tr>
                            </thead>
                            <tbody>   
                                <?php
                                $cntr = 0;
                                $ttlTrsctnEntrdAmnt = 0;
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
                                    $trsctnLineStatus = "";
                                    $trsctnLineDate = "";
                                    $trnsIncrsDcrs1 = "Increase";
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

                                        $dbtOrCrdt = $rowRw[23];
                                        if ($dbtOrCrdt == "C") {
                                            $trnsIncrsDcrs1 = str_replace("d", "D",
                                                    str_replace("i", "I", strtolower(incrsOrDcrsAccnt($trsctnAcntID, "Credit"))));
                                        } else {
                                            $trnsIncrsDcrs1 = str_replace("d", "D",
                                                    str_replace("i", "I", strtolower(incrsOrDcrsAccnt($trsctnAcntID, "Debit"))));
                                        }
                                        $ttlTrsctnDbtAmnt += $trsctnDbtAmnt;
                                        $ttlTrsctnCrdtAmnt += $trsctnCrdtAmnt;
                                        $ttlTrsctnNetAmnt += $trsctnNetAmnt;
                                        $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;
                                    }
                                    $cntr += 1;
                                    ?>
                                    <tr id="oneJrnlBatchEditRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>    
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">   
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsSmryLnID" value="<?php echo $trsctnSmryLineID; ?>" style="width:100% !important;">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountID1', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {

                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm; ?></span>
                                            <?php } ?>                                             
                                        </td>                                          
                                        <td class="lovtd"  style="">
                                            <?php if ($canEdt === true) { ?>
                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchEditLinesTable', 'jbDetDesc');">                                                    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnLineDesc; ?></span>
                                            <?php } ?>   
                                        </td>                                                
                                        <td class="lovtd"  style="">
                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchEditLinesTable', 'jbDetRfDc');">                                                    
                                        </td>                                          
                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                            <div class="" style="width:100% !important;">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                            $('#oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                            afterJrnlBatchCurSlctn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');
                                                        });">
                                                    <span class="" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                </label>
                                            </div>                                              
                                        </td>
                                        <td class="lovtd">
                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                            echo number_format($entrdAmnt, 2);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchEditLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                            </button>
                                        </td>
                                        <td class="lovtd">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                    <input class="form-control" size="16" type="text" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>" style="width:100%;" onchange="afterJrnlBatchCurSlctn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');">
                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnLineDate; ?></span>
                                            <?php } ?>                                                         
                                        </td>
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                            echo number_format($funcCrncyRate, 4);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                        </td> 
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate" value="<?php
                                            echo number_format($acntCrncyRate, 4);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchDetLn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                $resultRw = get_Multi_Tmplt_Trns($sbmtdTempltLovID);
                                $maxNoRows = loc_db_num_rows($resultRw);
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
                                    $trnsIncrsDcrs1 = "Increase";
                                    $tmplateID = -1;
                                    $detailID = -1;

                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                        $trsctnLineID = -1;
                                        $trsctnSmryLineID = -1;
                                        $tmplateID = (float) $rowRw[6];
                                        $detailID = (float) $rowRw[0];
                                        $trsctnLineDesc = $rowRw[4];
                                        $trsctnLineRefDoc = "";
                                        $funcCurID = $fnccurid;
                                        $funcCurNm = $fnccurnm;
                                        $trsctnDbtAmnt = 0;
                                        $trsctnCrdtAmnt = 0;
                                        $trsctnNetAmnt = 0;
                                        $entrdCurID = getPssblValID($jrnlBatchAmountCrncy, getLovID("Currencies"));
                                        $entrdAmnt = 0;
                                        $entrdCurNm = $jrnlBatchAmountCrncy;
                                        $trsctnAcntID = (int) $rowRw[5];
                                        $trsctnAcntNm = $rowRw[2] . "." . $rowRw[3];
                                        $acntCrncyRate = 1.000;
                                        $trsctnLineStatus = "0";
                                        $trsctnLineDate = $jrnlBatchDfltTrnsDte;
                                        $funcCrncyRate = round(get_LtstExchRate($entrdCurID, $funcCurID, $trsctnLineDate), 4);
                                        $trnsIncrsDcrs1 = $rowRw[1] == "I" ? "Increase" : "Decrease";
                                        $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnDbtAmnt;
                                        $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnCrdtAmnt;
                                        $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnNetAmnt;
                                        $isPosted = "false";
                                    }
                                    $cntr += 1;
                                    ?>
                                    <tr id="oneJrnlBatchEditRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>    
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">    
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsSmryLnID" value="<?php echo $trsctnSmryLineID; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountID1', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {

                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm; ?></span>
                                            <?php } ?>                                             
                                        </td>                                          
                                        <td class="lovtd"  style="">
                                            <?php if ($canEdt === true) { ?>
                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchEditLinesTable', 'jbDetDesc');">                                                    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnLineDesc; ?></span>
                                            <?php } ?>   
                                        </td>                                                
                                        <td class="lovtd"  style="">
                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchEditLinesTable', 'jbDetRfDc');">                                                    
                                        </td>                                          
                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                            <div class="" style="width:100% !important;">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                            $('#oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                            afterJrnlBatchCurSlctn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');
                                                        });">
                                                    <span class="" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                </label>
                                            </div>                                              
                                        </td>
                                        <td class="lovtd">
                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                            echo number_format($entrdAmnt, 2);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchEditLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchEditRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                            </button>
                                        </td>
                                        <td class="lovtd">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                    <input class="form-control" size="16" type="text" id="oneJrnlBatchEditRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>" style="width:100%;" onchange="afterJrnlBatchCurSlctn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');">
                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnLineDate; ?></span>
                                            <?php } ?>                                                         
                                        </td>
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                            echo number_format($funcCrncyRate, 4);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_FuncExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                        </td> 
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate" name="oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate" value="<?php
                                            echo number_format($acntCrncyRate, 4);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchEditRow<?php echo $cntr; ?>_AcntExchgRate', 'oneJrnlBatchEditLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchEditTtl();">                                                    
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchDetLn('oneJrnlBatchEditRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
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
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>TOTALS:</th>
                                    <th>&nbsp;</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdEntrdAmntTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                2, '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdEntrdAmntTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                    </th>
                                    <th style="">&nbsp;</th>                                           
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </tfoot>
                        </table>
                        <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchEditCount" name="addNwJrnlBatchEditCount" value="<?php echo $cntr; ?>" readonly="true">
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 3) {
                //Journal Batch Simplified Lines
                $lmtSze = isset($_POST['accbJrnlBatchDsplySze']) ? cleanInputData($_POST['accbJrnlBatchDsplySze']) : 50;
                $sbmtdTempltLovID = isset($_POST['sbmtdTempltLovID']) ? cleanInputData($_POST['sbmtdTempltLovID']) : "";
                $jrnlBatchAmountCrncy = isset($_POST['jrnlBatchAmountCrncy']) ? cleanInputData($_POST['jrnlBatchAmountCrncy']) : $fnccurnm;
                $jrnlBatchDfltTrnsDte = isset($_POST['jrnlBatchDfltTrnsDte']) ? cleanInputData($_POST['jrnlBatchDfltTrnsDte']) : $gnrlTrnsDteDMYHMS;
                $jrnlBatchDfltBalsAcnt = isset($_POST['jrnlBatchDfltBalsAcnt']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcnt']) : "";
                $jrnlBatchDfltBalsAcntID = isset($_POST['jrnlBatchDfltBalsAcntID']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcntID']) : -1;
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
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
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdJrnlBatchID > 0) {
                    $jrnlBatchSource = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_source", $sbmtdJrnlBatchID);
                    $rqStatus = (getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_status", $sbmtdJrnlBatchID)) == "1" ? "Posted" : "Not Posted";
                    if (strpos($jrnlBatchSource, "Manual") === FALSE || $rqStatus == "Posted") {
                        $canEdt = FALSE;
                        $mkReadOnly = "readonly=\"true\"";
                        $mkRmrkReadOnly = "readonly=\"true\"";
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneJrnlBatchSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                            <thead>
                                <tr>
                                    <th style="max-width:20px;width:20px;">No.</th>
                                    <th style="">Narration/Remark</th>
                                    <th style="max-width:70px;width:70px;">Reference Doc. No.</th>
                                    <th style="max-width:35px;width:35px;">CUR.</th>
                                    <th style="max-width:80px;width:80px;">Entered Amount</th>
                                    <th style="max-width:20px;width:20px;">...</th>
                                    <th style="max-width:120px;width:120px;">Transaction Date</th>
                                    <th style="max-width:50px;width:50px;text-align: center;">I/D</th>
                                    <th style="max-width:170px;width:170px;">Charge Account</th>
                                    <th style="max-width:50px;width:50px;text-align: center;">I/D</th>
                                    <th style="max-width:150px;width:150px;">Balancing Account Leg</th>
                                    <th style="max-width:70px;width:70px;">Functional Currency Exchange Rate</th>
                                    <th style="max-width:20px;width:20px;">...</th>
                                </tr>
                            </thead>
                            <tbody>   
                                <?php
                                $cntr = 0;
                                $resultRw = get_One_JrnlSmmry_Trns($sbmtdJrnlBatchID, $lmtSze);
                                $maxNoRows = loc_db_num_rows($resultRw);
                                $excludedItems = "";
                                $ttlTrsctnEntrdAmnt = 0;
                                $ttlTrsctnDbtAmnt = 0;
                                $ttlTrsctnCrdtAmnt = 0;
                                $ttlTrsctnNetAmnt = 0;
                                while ($cntr < $maxNoRows) {
                                    $trsctnDbtAmnt = 0;
                                    $trsctnCrdtAmnt = 0;
                                    $trsctnNetAmnt = 0;
                                    $trsctnLineID = -1;
                                    $trsctnLineDesc = "";
                                    $trsctnLineRefDoc = "";
                                    $funcCurID = -1;
                                    $funcCurNm = "";
                                    $entrdCurID = -1;
                                    $entrdAmnt = 0.00;
                                    $entrdCurNm = "";
                                    $acntCrncyRate = 0;
                                    $funcCrncyRate = 0;
                                    $trsctnLineStatus = "";
                                    $trsctnLineDate = "";
                                    $trnsIncrsDcrs1 = "Increase";
                                    $trsctnAcntID1 = -1;
                                    $trsctnAcntNm1 = -1;
                                    $trnsIncrsDcrs2 = "Increase";
                                    $trsctnAcntID2 = -1;
                                    $trsctnAcntNm2 = -1;
                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                        $trsctnLineID = (float) $rowRw[0];
                                        $trsctnLineDesc = $rowRw[2];
                                        $trsctnLineRefDoc = $rowRw[1];
                                        $entrdCurID = (int) $rowRw[11];
                                        $entrdAmnt = (float) $rowRw[3];
                                        $entrdCurNm = $rowRw[12];
                                        $trnsIncrsDcrs1 = $rowRw[5];
                                        $trsctnAcntID1 = $rowRw[6];
                                        $trsctnAcntNm1 = $rowRw[7];

                                        $trnsIncrsDcrs2 = $rowRw[8];
                                        $trsctnAcntID2 = $rowRw[9];
                                        $trsctnAcntNm2 = $rowRw[10];

                                        $funcCrncyRate = (float) $rowRw[13];
                                        $trsctnLineDate = $rowRw[4];
                                        $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $entrdAmnt;
                                    }
                                    $cntr += 1;
                                    ?>
                                    <tr id="oneJrnlBatchSmryRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                        <td class="lovtd"  style="">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                            <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" 
                                                   style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchSmryLinesTable', 'jbDetDesc');">                                                    
                                        </td>                                                 
                                        <td class="lovtd"  style="">
                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchSmryLinesTable', 'jbDetRfDc');">                                                    
                                        </td>                                          
                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                            <div class="" style="width:100% !important;">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                            $('#oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                            afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');
                                                        });">
                                                    <span class="" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                </label>
                                            </div>                                              
                                        </td>
                                        <td class="lovtd">
                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                            echo number_format($entrdAmnt, 2);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchSmryTtl();">                                                    
                                        </td>   
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                            </button>
                                        </td>   
                                        <td class="lovtd">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                    <input class="form-control" size="16" type="text" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>" style="width:100%;" onchange="afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');">
                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnLineDate; ?></span>
                                            <?php } ?>                                                         
                                        </td> 
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID1', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {});">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm1; ?></span>
                                            <?php } ?>                                             
                                        </td>
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_IncrsDcrs2" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID2" value="<?php echo $trsctnAcntID2; ?>" style="width:100% !important;"> 
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2" value="<?php echo $trsctnAcntNm2; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID2', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2', 'clear', 1, '', function () {});">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm2; ?></span>
                                            <?php } ?>                                             
                                        </td>
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                            echo number_format($funcCrncyRate, 4);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate', 'oneJrnlBatchSmryLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchSmryTtl();">                                                    
                                        </td> 
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchSmmryLn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                $resultRw = get_Multi_Tmplt_Trns1($sbmtdTempltLovID);
                                $maxNoRows = loc_db_num_rows($resultRw);
                                while ($cntr < $maxNoRows) {
                                    $trsctnLineID = -1;
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
                                    $trnsIncrsDcrs1 = "Increase";
                                    $trsctnAcntID1 = -1;
                                    $trsctnAcntNm1 = -1;
                                    $trnsIncrsDcrs2 = "Increase";
                                    $trsctnAcntID2 = -1;
                                    $trsctnAcntNm2 = -1;
                                    $tmplateID = -1;
                                    $detailID = -1;

                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                        $trsctnLineID = -1;
                                        $trsctnLineDesc = $rowRw[2];
                                        $trsctnLineRefDoc = "";
                                        $funcCurID = $fnccurid;
                                        $funcCurNm = $fnccurnm;
                                        $trsctnDbtAmnt = 0;
                                        $trsctnCrdtAmnt = 0;
                                        $trsctnNetAmnt = 0;
                                        $entrdCurID = getPssblValID($jrnlBatchAmountCrncy, getLovID("Currencies"));
                                        $entrdAmnt = 0;
                                        $entrdCurNm = $jrnlBatchAmountCrncy;
                                        $trsctnLineDate = $jrnlBatchDfltTrnsDte;
                                        $acntCrncyRate = 1.000;
                                        $funcCrncyRate = round(get_LtstExchRate($entrdCurID, $funcCurID, $trsctnLineDate), 4);
                                        $trsctnLineStatus = "0";
                                        $trnsIncrsDcrs1 = $rowRw[0] == "I" ? "Increase" : "Decrease";
                                        $trsctnAcntID1 = (int) $rowRw[3];
                                        $trsctnAcntNm1 = $rowRw[1];

                                        $trnsIncrsDcrs2 = $rowRw[4] == "I" ? "Increase" : "Decrease";
                                        $trsctnAcntID2 = $rowRw[7];
                                        $trsctnAcntNm2 = $rowRw[5];

                                        $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnDbtAmnt;
                                        $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnCrdtAmnt;
                                        $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnNetAmnt;
                                        $isPosted = "false";
                                    }
                                    $cntr += 1;
                                    ?>
                                    <tr id="oneJrnlBatchSmryRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                        <td class="lovtd"  style="">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">  
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;"> 
                                            <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" 
                                                   style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchSmryLinesTable', 'jbDetDesc');">                                                    
                                        </td>                                                 
                                        <td class="lovtd"  style="">
                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchSmryLinesTable', 'jbDetRfDc');">                                                    
                                        </td>                                          
                                        <td class="lovtd" style="max-width:35px;width:35px;">
                                            <div class="" style="width:100% !important;">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                            $('#oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                            afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');
                                                        });">
                                                    <span class="" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                </label>
                                            </div>                                              
                                        </td>
                                        <td class="lovtd">
                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt" value="<?php
                                            echo number_format($entrdAmnt, 2);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchSmryTtl();">                                                    
                                        </td>   
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                            </button>
                                        </td>   
                                        <td class="lovtd">
                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                <input class="form-control" size="16" type="text" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>" style="width:100%;" onchange="afterJrnlBatchCurSlctn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');">
                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>                                                       
                                        </td> 
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;"> 
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm1; ?>" readonly="true" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnAcntNm1; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID1', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {
                                                                changeElmntTitleFunc('oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm1');
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm1; ?></span>
                                            <?php } ?>                                             
                                        </td>
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_IncrsDcrs2" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID2" value="<?php echo $trsctnAcntID2; ?>" style="width:100% !important;"> 
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2" value="<?php echo $trsctnAcntNm2; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountID2', 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_AccountNm2', 'clear', 1, '', function () {});">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm2; ?></span>
                                            <?php } ?>                                             
                                        </td>
                                        <td class="lovtd" style="">
                                            <input type="text" class="form-control rqrdFld jbDetFuncRate" aria-label="..." id="oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate" name="oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate" value="<?php
                                            echo number_format($funcCrncyRate, 4);
                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchSmryRow<?php echo $cntr; ?>_FuncExchgRate', 'oneJrnlBatchSmryLinesTable', 'jbDetFuncRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchSmryTtl();">                                                    
                                        </td> 
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchSmmryLn('oneJrnlBatchSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
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
                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                2, '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                    </th>
                                    <th style="">&nbsp;</th>                                           
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                    <th style="">&nbsp;</th>
                                </tr>
                            </tfoot>
                        </table>
                        <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchSmryCount" name="addNwJrnlBatchSmryCount" value="<?php echo $cntr; ?>" readonly="true">
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 4) {
                $sbmtdTempltLovID = isset($_POST['sbmtdTempltLovID']) ? cleanInputData($_POST['sbmtdTempltLovID']) : "";
                $jrnlBatchAmountCrncy = isset($_POST['jrnlBatchAmountCrncy']) ? cleanInputData($_POST['jrnlBatchAmountCrncy']) : $fnccurnm;
                $jrnlBatchDfltTrnsDte = isset($_POST['jrnlBatchDfltTrnsDte']) ? cleanInputData($_POST['jrnlBatchDfltTrnsDte']) : $gnrlTrnsDteDMYHMS;
                $jrnlBatchDfltBalsAcnt = isset($_POST['jrnlBatchDfltBalsAcnt']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcnt']) : "";
                $jrnlBatchDfltBalsAcntID = isset($_POST['jrnlBatchDfltBalsAcntID']) ? cleanInputData($_POST['jrnlBatchDfltBalsAcntID']) : -1;
                //echo "sbmtdTempltLovID:: $sbmtdTempltLovID";
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
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                ?>
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
                                    <th style="max-width:120px;width:120px;">Transaction Date</th>
                                    <th style="max-width:60px;width:60px;">Ref. Doc. No.</th>
                                    <th style="max-width:20px;width:20px;">...</th>
                                    <?php if ($canVwRcHstry === true) { ?>
                                        <th style="max-width:20px;width:20px;">...</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>   
                                <?php
                                $cntr = 0;
                                $ttlTrsctnDbtAmnt = 0;
                                $ttlTrsctnCrdtAmnt = 0;
                                $ttlTrsctnNetAmnt = 0;
                                $resultRw = get_Multi_Tmplt_Trns($sbmtdTempltLovID);
                                $maxNoRows = loc_db_num_rows($resultRw);
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
                                    $tmplateID = -1;
                                    $detailID = -1;
                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                        $trsctnLineID = -1;
                                        $trsctnSmryLineID = -1;
                                        $tmplateID = (float) $rowRw[6];
                                        $detailID = (float) $rowRw[0];
                                        $trsctnLineDesc = $rowRw[4];
                                        $trsctnLineRefDoc = "";
                                        $funcCurID = $fnccurid;
                                        $funcCurNm = $fnccurnm;
                                        $trsctnDbtAmnt = 0;
                                        $trsctnCrdtAmnt = 0;
                                        $trsctnNetAmnt = 0;
                                        $entrdCurID = getPssblValID($jrnlBatchAmountCrncy, getLovID("Currencies"));
                                        $entrdAmnt = 0;
                                        $entrdCurNm = $jrnlBatchAmountCrncy;
                                        $trsctnAcntID = (int) $rowRw[5];
                                        $trsctnAcntNm = $rowRw[2] . "." . $rowRw[3];
                                        $acntCrncyRate = 1.000;
                                        $funcCrncyRate = 1.000;
                                        $trsctnLineStatus = "0";
                                        $trsctnLineDate = $jrnlBatchDfltTrnsDte;

                                        $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnDbtAmnt;
                                        $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnCrdtAmnt;
                                        $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnNetAmnt;
                                        $isPosted = "false";
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
                                            <?php if ($canEdt === true) { ?>
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
                                            <?php if ($canEdt === true) { ?>
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
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                    onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', 'VIEW', '<?php echo $defaultBrkdwnLOV; ?>', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_EntrdAmt', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                            </button>
                                        </td>
                                        <td class="lovtd">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                    <input class="form-control" size="16" type="text" id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>">
                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div> 
                                            <?php } else { ?>
                                                <span><?php echo $trsctnLineDate; ?></span>
                                            <?php } ?>                                                         
                                        </td>                                         
                                        <td class="lovtd"  style="">
                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchDetLinesTable', 'jbDetRfDc');">                                                    
                                        </td>
                                        <?php if ($canDel === true && $canEdt === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchDetLn('oneJrnlBatchDetRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Journal Line">
                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        <?php } ?>
                                        <?php if ($canVwRcHstry === true) { ?>
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
                                    <?php if ($canVwRcHstry === true) { ?>
                                        <th style="max-width:20px;width:20px;">&nbsp;</th>
                                    <?php } ?>
                                </tr>
                            </tfoot>
                        </table>
                        <input type="hidden" class="form-control" aria-label="..." id="addNwJrnlBatchDetCount" name="addNwJrnlBatchDetCount" value="<?php echo $cntr; ?>" readonly="true">
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 10) {
                
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? cleanInputData($_POST['sbmtdJrnlBatchID']) : -1;
                if (!$canAdd || ($sbmtdJrnlBatchID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdJrnlBatchID;
                $total = get_Total_JrnBatchAttachments($srchFor, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_JrnBatchAttachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>       
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdJrnlBatchDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdJrnBatchDocsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                    . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdJrnBatchDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdJrnBatchDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdJrnBatchDocsRow_WWW123WWW_DocCtgryNm', 'attchdJrnBatchDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdJrnBatchDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToJrnlBatchDocs('attchdJrnBatchDocsRow_WWW123WWW_DocFile','attchdJrnBatchDocsRow_WWW123WWW_AttchdDocsID','attchdJrnBatchDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdJrnBatchDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdJrnlBatchDoc('attchdJrnBatchDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdJrnlBatchDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdJrnlBatchDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttchdJrnlBatchDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdJrnlBatchID=<?php echo $sbmtdJrnlBatchID; ?>', 'ReloadDialog');">
                                    <input id="attchdJrnlBatchDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdJrnlBatchDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdJrnlBatchID=<?php echo $sbmtdJrnlBatchID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdJrnlBatchDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdJrnlBatchID=<?php echo $sbmtdJrnlBatchID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdJrnlBatchDocsDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "", "",
                                            "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50,
                                            100, 500, 1000);
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
                                            <a class="rhopagination" href="javascript:getAttchdJrnlBatchDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdJrnlBatchID=<?php echo $sbmtdJrnlBatchID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdJrnlBatchDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdJrnlBatchID=<?php echo $sbmtdJrnlBatchID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdJrnlBatchDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/Accntn/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                            ?>
                                            <tr id="attchdJrnBatchDocsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdJrnBatchDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdJrnlBatchDoc('attchdJrnBatchDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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