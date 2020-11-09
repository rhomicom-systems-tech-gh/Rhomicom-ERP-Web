<?php
$canAdd = test_prmssns($dfltPrvldgs[45], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[46], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[47], $mdlNm);
$canVoid = test_prmssns($dfltPrvldgs[10], $mdlNm);
$canApprove = test_prmssns($dfltPrvldgs[23], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Fund Management Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if (($canDel === true)) {
                    echo deleteInvstTrans($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                
            } else if ($actyp == 5) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdt) {
                    echo deleteInvstTransDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Fund Management Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? (float) cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                $payInvstTrnsType = isset($_POST['payInvstTrnsType']) ? cleanInputData($_POST['payInvstTrnsType']) : "PURCHASE";
                $payInvstItemType = isset($_POST['payInvstItemType']) ? cleanInputData($_POST['payInvstItemType']) : '';
                $payInvstItemTypID = isset($_POST['payInvstItemTypID']) ? (float) cleanInputData($_POST['payInvstItemTypID']) : -1;
                $payInvstTransInvcCur = isset($_POST['payInvstTransInvcCur']) ? cleanInputData($_POST['payInvstTransInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $payInvstTransInvcCurID = getPssblValID($payInvstTransInvcCur, $curLovID);
                $payInvstPrchsAmnt = isset($_POST['payInvstPrchsAmnt']) ? (float) cleanInputData($_POST['payInvstPrchsAmnt']) : 0;
                $payInvstMatureAmnt = isset($_POST['payInvstMatureAmnt']) ? (float) cleanInputData($_POST['payInvstMatureAmnt']) : 0;
                $payInvstIntrstRate = isset($_POST['payInvstIntrstRate']) ? (float) cleanInputData($_POST['payInvstIntrstRate']) : 0;
                $payInvstExchngRate = isset($_POST['payInvstExchngRate']) ? (float) cleanInputData($_POST['payInvstExchngRate']) : 0;
                $payInvstRollOvrType = isset($_POST['payInvstRollOvrType']) ? cleanInputData($_POST['payInvstRollOvrType']) : '';
                $payInvstPrchsDte = isset($_POST['payInvstPrchsDte']) ? cleanInputData($_POST['payInvstPrchsDte']) : '';
                $payInvstMatureDte = isset($_POST['payInvstMatureDte']) ? cleanInputData($_POST['payInvstMatureDte']) : '';
                $payInvstRefNum = isset($_POST['payInvstRefNum']) ? cleanInputData($_POST['payInvstRefNum']) : -1;
                $payInvstTransDesc = isset($_POST['payInvstTransDesc']) ? cleanInputData($_POST['payInvstTransDesc']) : '';
                $payInvstClientID = isset($_POST['payInvstClientID']) ? cleanInputData($_POST['payInvstClientID']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                $payInvstTransGLBatchID = (float) getGnrlRecNm("pay.pay_fund_management", "investment_id", "gl_batch_id", $sbmtdPayInvstTransID);
                $payInvstTransDfltTrnsDte = $payInvstPrchsDte;
                if (strlen($payInvstTransDesc) > 499) {
                    $payInvstTransDesc = substr($payInvstTransDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($payInvstItemType == "") {
                    $exitErrMsg .= "Please enter Transaction Type!<br/>";
                }
                if ($payInvstTransDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                if ($payInvstPrchsDte == "") {
                    $exitErrMsg .= "Puchase Date cannot be empty!<br/>";
                }
                if ($payInvstPrchsAmnt <= 0 && $payInvstMatureAmnt <=0) {
                    $exitErrMsg .= "Purchase Amount and Maturity Amount cannot be both zero or less!<br/>";
                }
                if ((!$canEdt && !$canAdd)) {
                    $exitErrMsg .= "Sorry you don't have permission to perform this action";
                }
                
                if ($payInvstPrchsAmnt <= 0 && $shdSbmt == 2) {
                    $exitErrMsg .= "Purchase Amount must be provided before finalization can be done!<br/>";
                }
                $apprvlStatus = "Not Finalized";
                $nxtApprvlActn = "Finalize";
                $pymntTrms = "";
                $srcDocHdrID = -1;
                $srcDocType = "";
                $pymntMthdID = -1;
                $amntPaid = 0;
                $glBtchID = $payInvstTransGLBatchID;
                $spplrInvcNum = "";
                $docTmpltClsftn = "";
                $amntAppld = 0;
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdPayInvstTransID'] = $sbmtdPayInvstTransID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if ($sbmtdPayInvstTransID <= 0) {
                    $sbmtdPayInvstTransID = getNewPayInvstTransID();
                    $afftctd += createInvstTransDocHdr($sbmtdPayInvstTransID, $orgID, $payInvstItemTypID, $payInvstClientID, $payInvstRefNum, $payInvstTransDesc, $payInvstTrnsType,
                            $payInvstRollOvrType, $payInvstTransInvcCurID, $payInvstPrchsAmnt, $payInvstMatureAmnt, $payInvstExchngRate,
                            $payInvstIntrstRate, $payInvstPrchsDte, $payInvstMatureDte);
                } else if ($sbmtdPayInvstTransID > 0) {
                    $afftctd += updtInvstTransDocHdr($sbmtdPayInvstTransID, $payInvstItemTypID, $payInvstClientID, $payInvstRefNum, $payInvstTransDesc, $payInvstTrnsType,
                            $payInvstRollOvrType, $payInvstTransInvcCurID, $payInvstPrchsAmnt, $payInvstMatureAmnt, $payInvstExchngRate,
                            $payInvstIntrstRate, $payInvstPrchsDte, $payInvstMatureDte);
                }
                $payOLDMatureAmnt = $payInvstMatureAmnt;
                if ($payInvstTransGLBatchID <= 0 && $shdSbmt == 2 && $canApprove === true) {
                    createInvstTrnsAccntng($sbmtdPayInvstTransID, $payInvstTrnsType, $payInvstItemTypID, $payInvstTransDesc, $payInvstRefNum,
                            $payInvstTransInvcCur, $payInvstPrchsDte, $payInvstExchngRate, $payInvstPrchsAmnt, $payInvstMatureAmnt, $payOLDMatureAmnt, $afftctd1, $errMsg);
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " Fund Management Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Account Transaction(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " Fund Management Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Account Transaction(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdPayInvstTransID'] = $sbmtdPayInvstTransID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 2) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                $payTransType = "INVESTMENT";
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdPayInvstTransID;
                if ($attchmentID > 0) {
                    uploadDaInvstTransDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewInvstTransDocID();
                    createInvstTransDoc($attchmentID, $pkID, $payTransType, $docCtrgrName, "");
                    uploadDaInvstTransDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/PayDocs/" . $nwImgLoc;
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
            } else if ($actyp == 5) {
                //Save Redemption Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? (float) cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                $payInvstTrnsType = "REDEEM";
                $payInvstMatureAmnt = isset($_POST['payInvstMatureAmnt']) ? (float) cleanInputData($_POST['payInvstMatureAmnt']) : 0;
                $payOLDMatureAmnt = isset($_POST['payOLDMatureAmnt']) ? (float) cleanInputData($_POST['payOLDMatureAmnt']) : 0;
                $payInvstRollOvrType = isset($_POST['payInvstRollOvrType']) ? cleanInputData($_POST['payInvstRollOvrType']) : '';
                $payInvstMatureDte = isset($_POST['payInvstMatureDte']) ? cleanInputData($_POST['payInvstMatureDte']) : '';
                $payInvstTransDesc = isset($_POST['payInvstTransDesc']) ? cleanInputData($_POST['payInvstTransDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                //$payInvstTransGLBatchID = (float) getGnrlRecNm("pay.pay_fund_management", "investment_id", "gl_batch_id", $sbmtdPayInvstTransID);
                $payInvstTransDfltTrnsDte = $payInvstMatureDte;
                if (strlen($payInvstTransDesc) > 499) {
                    $payInvstTransDesc = substr($payInvstTransDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($payInvstTransDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                if ($payInvstMatureDte == "") {
                    $exitErrMsg .= "Maturity Date cannot be empty!<br/>";
                }
                if ($payInvstMatureAmnt <= 0) {
                    $exitErrMsg .= "Maturity Amount cannot be zero or less!<br/>";
                }
                if ((!$canEdt && !$canAdd)) {
                    $exitErrMsg .= "Sorry you don't have permission to perform this action";
                }
                $apprvlStatus = "Not Finalized";
                $nxtApprvlActn = "Finalize";
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdPayInvstTransID'] = $sbmtdPayInvstTransID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                $payInvstItemTypID = -1;
                $payInvstRefNum = "";
                $payInvstTransInvcCur = "";
                $payInvstExchngRate = 1;
                $payInvstPrchsAmnt = 0;
                $payInvstClientID = "";
                $payInvstIntrstRate = 0;
                $payInvstExpctdIntrst = 0;
                $payInvstTransIsPstd = '0';
                $payInvstPrdFig = "";
                $payInvstPeriodTyp = "";

                if ($shdSbmt == 2 && $canApprove === true) {
                    if ($sbmtdPayInvstTransID > 0) {
                        $result = get_One_InvstTransDocHdr($sbmtdPayInvstTransID);
                        while ($row = loc_db_fetch_array($result)) {
                            $payInvstItemTypID = (int) $row[1];
                            $payInvstRefNum = $row[3];
                            $payInvstPrchsAmnt = (float) $row[9];
                            $payInvstExchngRate = (float) $row[20];
                            $payInvstTransInvcCurID = (int) $row[21];
                            $payInvstTransInvcCur = $row[22];

                            $payInvstItemType = $row[4];
                            $payInvstPrchsDte = $row[15];
                            $payInvstClientID = $row[2];
                            $payInvstIntrstRate = (float) $row[12];
                            $payInvstExpctdIntrst = (float) $row[11];
                            $payInvstTransIsPstd = $row[19];
                            $payInvstPrdFig = (float) $row[13];
                            $payInvstPeriodTyp = $row[14];
                        }
                        $afftctd += updtInvstTransDocHdr22($sbmtdPayInvstTransID, $payInvstTransDesc, $payInvstTrnsType,
                                $payInvstRollOvrType, $payInvstMatureAmnt, $payOLDMatureAmnt, $payInvstMatureDte);
                        createInvstTrnsAccntng($sbmtdPayInvstTransID, $payInvstTrnsType, $payInvstItemTypID, $payInvstTransDesc, $payInvstRefNum,
                                $payInvstTransInvcCur, $payInvstMatureDte, $payInvstExchngRate, $payInvstPrchsAmnt, $payInvstMatureAmnt, $payOLDMatureAmnt, $afftctd1, $errMsg);
                    }
                    if ($payInvstRollOvrType == "Roll Over") {
                        $sbmtdPayInvstTransID1 = getNewPayInvstTransID();
                        $afftctd += createInvstTransDocHdr($sbmtdPayInvstTransID1, $orgID, $payInvstItemTypID, $payInvstClientID, $payInvstRefNum,
                                "RE-PURCHASE-" . $payInvstTransDesc, "PURCHASE",
                                $payInvstRollOvrType, $payInvstTransInvcCurID, $payInvstPrchsAmnt, $payInvstMatureAmnt, $payInvstExchngRate,
                                $payInvstIntrstRate, $payInvstMatureDte, "");
                    } else if ($payInvstRollOvrType == "Roll Over with Interest") {
                        $sbmtdPayInvstTransID1 = getNewPayInvstTransID();
                        $afftctd += createInvstTransDocHdr($sbmtdPayInvstTransID1, $orgID, $payInvstItemTypID, $payInvstClientID, $payInvstRefNum,
                                "RE-PURCHASE-" . $payInvstTransDesc, "PURCHASE",
                                $payInvstRollOvrType, $payInvstTransInvcCurID, $payInvstMatureAmnt, 0, $payInvstExchngRate,
                                $payInvstIntrstRate, $payInvstMatureDte, "");
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " Fund Management Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Account Transaction(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " Fund Management Transaction(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Account Transaction(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdPayInvstTransID'] = $sbmtdPayInvstTransID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else if ($qstr == "VOID") {
            if ($actyp == 1) {
                //Reverse Fund Management Voucher
                $errMsg = "";
                $payInvstTransDesc = isset($_POST['payInvstTransDesc']) ? cleanInputData($_POST['payInvstTransDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                if (!$canVoid) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $sbmtdJrnlBatchID = (float) getGnrlRecNm("pay.pay_fund_management", "investment_id", "gl_batch_id", $sbmtdPayInvstTransID);
                $orgnlBatchID = $sbmtdJrnlBatchID;
                $trnsIDStatus2 = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_vldty_status", $orgnlBatchID);
                if (strtoupper($trnsIDStatus2) == "VOID") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Journal Batch has been voided already!</span>";
                    exit();
                }
                $trnsIDStatus1 = getGnrlRecNm("pay.pay_fund_management", "investment_id", "REQUEST_STATUS", $sbmtdPayInvstTransID);
                if ($trnsIDStatus1 == "Cancelled") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Transaction Already Cancelled!</span>";
                    exit();
                }
                $gnrtdTrnsNo = "";
                $payInvstTransDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $payInvstTransGLBatch = "";
                $rqStatus = "Not Finalized"; //approval_status
                $rqStatusNext = "Finalize";
                if ($sbmtdPayInvstTransID > 0) {
                    $result = get_One_InvstTransDocHdr($sbmtdPayInvstTransID);
                    if ($row = loc_db_fetch_array($result)) {
                        $payInvstTransDfltTrnsDte = $row[15] . " 12:00:00";
                        $payInvstTransGLBatch = $row[18];
                        //$gnrtdTrnsNo = $row[4];
                        $rqStatus = $row[6];
                        $rqStatusNext = "";
                    }
                }
                $payInvstTransGLBatch1 = "RVRSL-" . $payInvstTransGLBatch;
                $gnrtdTrnsNoExistsID2 = (float) getGnrlRecNm2("accb.accb_trnsctn_batches", "batch_name", "batch_id", $payInvstTransGLBatch1);
                if ($gnrtdTrnsNoExistsID2 > 0) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>A Reversal Batch for the Linked Journal Batch Exists Already!</span>";
                    exit();
                } else {
                    if ($rqStatus == "Not Finalized" && $sbmtdPayInvstTransID > 0) {
                        echo deleteInvstTrans($sbmtdPayInvstTransID, $payInvstTransGLBatch);
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
                $tstDate = cnvrtDMYTmToYMDTm($payInvstTransDfltTrnsDte);
                $prdLnID = getTrnsDteOpenPrdLnID($prdHdrID, $tstDate);
                if ($prdLnID <= 0) {
                    $payInvstTransDfltTrnsDte = getLtstOpenPrdAfterDate($tstDate);
                }
                if (!isTransPrmttd(get_DfltCashAcnt($orgID), $payInvstTransDfltTrnsDte, 200, $errMsg)) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>ERROR:" . $errMsg . "</span>";
                    exit();
                }
                $affctd1 += voidJrnlBatch($orgnlBatchID, $payInvstTransDesc, $payInvstTransDfltTrnsDte);
                $voidedJrnlBatchID = getBatchID("RVRSL-" . $payInvstTransGLBatch, $orgID);
                if ($voidedJrnlBatchID > 0) {
                    $affctd2 += voidJrnlBatchTrans($orgnlBatchID, $voidedJrnlBatchID, $payInvstTransDfltTrnsDte);
                    $rsltCnt = updateInvstTransDocVoid($sbmtdPayInvstTransID, $payInvstTransDesc, "Cancelled", "None", $voidedJrnlBatchID);
                    $errMsg = $rsltCnt . " Transaction Reversal Finalized!";
                    $response = array('sbmtdPayInvstTransID' => $sbmtdPayInvstTransID,
                        'sbmtMsg' => $errMsg);
                    echo json_encode($response);
                } else {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Failed to reverse Journal Batch!</span>";
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                //Fund Management Transactions
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Fund Management</span>
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
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? (float) cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                $payInvstTrnsType = isset($_POST['payInvstTrnsType']) ? cleanInputData($_POST['payInvstTrnsType']) : "PURCHASE";
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_InvstTransDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_InvstTransDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-3";
                    ?> 
                    <form id='payInvstTransForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">INVESTMENT TRANSACTIONS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php if ($canAdd === true) { ?>   
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;"> 
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayInvstTransForm(-1, 1, 'ShowDialog');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Bill/Bond Purchase
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
                                        <input class="form-control" id="payInvstTransSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncPayInvstTrans(event, '', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                        <input id="payInvstTransPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayInvstTrans('clear', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayInvstTrans('', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType2; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payInvstTransSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("All", "Ref. Number", "Description");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payInvstTransDsplySze" style="min-width:70px !important;">                            
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
                                                <a href="javascript:getPayInvstTrans('previous', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getPayInvstTrans('next', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
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
                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOnePayInvstCalcForm(<?php echo $sbmtdPayInvstTransID; ?>, 19);" data-toggle="tooltip" data-placement="bottom" title = "Investment Calculator">
                                            <img src="cmn_images/calculator.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Investment Calculator
                                        </button>
                                    </div>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                        <div class="form-check" style="font-size: 12px !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getPayInvstTrans('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="payInvstTransShwUnpstdOnly" name="payInvstTransShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted
                                            </label>
                                        </div>                            
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="payInvstTransHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:30px;width:30px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>  
                                                <th style="max-width:100px;width:100px;">Reference Number</th>
                                                <th>Security Type/Name</th>                                  
                                                <th style="max-width:100px;width:100px;">Purchase Date</th>	                                              
                                                <th style="max-width:100px;width:100px;">Maturity Date</th>
                                                <th style="text-align:center;max-width:30px;width:30px;">CUR.</th>
                                                <th style="text-align:right;max-width:90px;width:90px;">Purchase Amount</th>
                                                <th style="text-align:right;max-width:90px;width:90px;">Maturity Amount</th>
                                                <th style="max-width:95px;width:95px;">Transaction Type/Status</th>
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
                                                <tr id="payInvstTransHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Voucher" 
                                                                onclick="getOnePayInvstTransForm(<?php echo $row[0]; ?>, 1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                        ?>                                
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[3] . " - " . $row[2]; ?></td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[4] . " (" . $row[8] . ")"; ?></td>
                                                    <td class="lovtd"><?php echo $row[15]; ?></td>
                                                    <td class="lovtd"><?php echo $row[16]; ?></td>
                                                    <td class="lovtd"><?php echo $row[22]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo number_format((float) $row[9], 2); ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[10], 2);
                                                        ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    $style2 = "color:red;";
                                                    $style3 = "color:red;";
                                                    $payInvstTrnsTypePst = ($row[7] == "PURCHASE") ? "D" : "ED";
                                                    if ($row[7] == "PURCHASE") {
                                                        $style1 = "color:green;";
                                                    } else {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    if ($row[6] == "Finalized") {
                                                        $style2 = "color:green;";
                                                    } else if ($row[6] == "Cancelled") {
                                                        $style2 = "color:#0d0d0d;";
                                                    }
                                                    if ($row[19] == "1") {
                                                        $style3 = "color:green;";
                                                    }
                                                    ?> 
                                                    <td class="lovtd" style="font-weight:bold;"><?php
                                                        echo "<span style=\"" . $style1 . "\">" . $row[7] . $payInvstTrnsTypePst . "</span><br/><span style=\"" . $style2 . "\">" . $row[6] . "</span><br/><span style=\"" . $style3 . "\">" . ($row[19] == "1" ? "Posted" : "Not Posted") . "</span>";
                                                        ?>
                                                    </td>                                          
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delPayInvstTrans('payInvstTransHdrsRow_<?php echo $cntr; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="payInvstTransHdrsRow<?php echo $cntr; ?>_HdrID" name="payInvstTransHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|pay.pay_fund_management|investment_id"), $smplTokenWord1));
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
                //New Fund Management Form
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? (float) cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                $payInvstTrnsType = isset($_POST['payInvstTrnsType']) ? cleanInputData($_POST['payInvstTrnsType']) : "PURCHASE";
                $payInvstItemType = "";
                $payInvstItemTypID = "";
                $payInvstRollOvrType = "";
                $payInvstPrchsDte = $gnrlTrnsDteDMY;
                $payInvstMatureDte = "";
                $payInvstTransDesc = "";
                $payInvstRefNum = "";
                $payInvstClientID = "";
                $rqStatus = "Not Finalized"; //approval_status
                $rqStatusNext = "Finalize"; //next_aproval_action
                $rqstatusColor = "red";

                $payInvstPrchsAmnt = 0;
                $payInvstMatureAmnt = 0;
                $payInvstIntrstRate = 0;
                $payInvstExpctdIntrst = 0;
                $payInvstExchngRate = 1;
                $payInvstTransGLBatch = "";
                $payInvstTransGLBatchID = -1;
                $payInvstTransInvcCurID = $fnccurid;
                $payInvstTransInvcCur = $fnccurnm;
                $payInvstTransIsPstd = "0";
                $payInvstPrdFig = 0;
                $payInvstPeriodTyp = "Day(s)";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdPayInvstTransID > 0) {
                    $result = get_One_InvstTransDocHdr($sbmtdPayInvstTransID);
                    if ($row = loc_db_fetch_array($result)) {
                        $payInvstTrnsType = $row[7];
                        $payInvstItemType = $row[4];
                        $payInvstItemTypID = (int) $row[1];
                        $payInvstRollOvrType = $row[8];
                        $payInvstPrchsDte = $row[15];
                        $payInvstMatureDte = $row[16];
                        $payInvstTransDesc = $row[5];
                        $payInvstRefNum = $row[3];
                        $payInvstClientID = $row[2];
                        $rqStatus = $row[6];
                        $rqStatusNext = "Finalize"; //next_aproval_action
                        $rqstatusColor = "red";

                        $payInvstPrchsAmnt = (float) $row[9];
                        $payInvstMatureAmnt = (float) $row[10];
                        $payInvstIntrstRate = (float) $row[12];
                        $payInvstExpctdIntrst = (float) $row[11];
                        $payInvstExchngRate = (float) $row[20];
                        $payInvstTransInvcCurID = (int) $row[21];
                        $payInvstTransInvcCur = $row[22];
                        $payInvstTransIsPstd = $row[19];
                        $payInvstPrdFig = (float) $row[13];
                        $payInvstPeriodTyp = $row[14];
                        $payInvstTransGLBatch = $row[18];
                        $payInvstTransGLBatchID = (float) $row[17];

                        if ($rqStatus == "Finalized") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Not Finalized") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Finalized") {
                                $mkRmrkReadOnly = "readonly=\"true\"";
                            }
                        }
                    }
                }
                $reportTitle = "Internal Pay Fund Management Voucher";
                $reportName = "Internal Pay Fund Management Voucher";
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdPayInvstTransID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="onePayInvstTransEDTForm">
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Transaction Type:</label>
                                </div>
                                <div class="col-md-8"> 
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdPayInvstTransID" name="sbmtdPayInvstTransID" value="<?php echo $sbmtdPayInvstTransID; ?>" readonly="true">
                                    <input type="hidden" class="form-control" aria-label="..." id="payInvstTrnsType" name="payInvstTrnsType" value="<?php echo $payInvstTrnsType; ?>" readonly="true">
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payInvstItemType" style="width:100% !important;">
                                        <?php
                                        $brghtStr = "";
                                        $isDynmyc = FALSE;
                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Internal Pay Investment Types"), $isDynmyc, -1, "", "");
                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                            $selectedTxt = "";
                                            if (((int) $titleRow[0]) == $payInvstItemTypID) {
                                                $selectedTxt = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Purchase Date:</label>
                                </div>
                                <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                    <input class="form-control" size="16" type="text" id="payInvstPrchsDte" name="payInvstPrchsDte" value="<?php echo $payInvstPrchsDte; ?>" placeholder="DD-MMM-YYYY">
                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Maturity Date:</label>
                                </div>
                                <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                    <input class="form-control" size="16" type="text" id="payInvstMatureDte" name="payInvstMatureDte" value="<?php echo $payInvstMatureDte; ?>" placeholder="DD-MMM-YYYY">
                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Purchase Amount:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label class="btn btn-info btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $payInvstTransInvcCur; ?>', 'payInvstTransInvcCur', '', 'clear', 0, '', function () {
                                                    $('#payInvstTransInvcCur1').html($('#payInvstTransInvcCur').val());
                                                    $('#payInvstTransInvcCur2').html($('#payInvstTransInvcCur').val());
                                                    $('#payInvstTransInvcCur3').html($('#payInvstTransInvcCur').val());
                                                });">
                                            <span class="" style="font-size: 20px !important;" id="payInvstTransInvcCur1"><?php echo $payInvstTransInvcCur; ?></span>
                                        </label>
                                        <input type="hidden" id="payInvstTransInvcCur" value="<?php echo $payInvstTransInvcCur; ?>"> 
                                        <input class="form-control rqrdFldLght fundNumber" type="text" id="payInvstPrchsAmnt" value="<?php
                                        echo number_format($payInvstPrchsAmnt, 2);
                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('payInvstPrchsAmnt');" <?php echo $mkReadOnly; ?>/>
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
                                            <span class="" style="font-size: 20px !important;" id="payInvstTransInvcCur3"><?php echo $payInvstTransInvcCur; ?></span>
                                            <span class="" style="font-size: 20px !important;"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                        </label>
                                        <input class="form-control fundNumber" type="text" id="payInvstExchngRate" value="<?php
                                        echo number_format($payInvstExchngRate, 4);
                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" <?php echo $mkReadOnly; ?>/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Maturity Amount:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                            <span class="" style="font-size: 20px !important;" id="payInvstTransInvcCur2"><?php echo $payInvstTransInvcCur; ?></span>
                                        </label>
                                        <input class="form-control rqrdFldLght fundNumber" type="text" id="payInvstMatureAmnt" value="<?php
                                        echo number_format($payInvstMatureAmnt, 2);
                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('payInvstMatureAmnt');" <?php echo $mkReadOnly; ?>/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Investment Interest Rate:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control fundNumber" aria-label="..." id="payInvstIntrstRate" name="payInvstIntrstRate" value="<?php echo number_format($payInvstIntrstRate, 2); ?>" onchange="fmtAsNumber('payInvstIntrstRate');" <?php echo $mkReadOnly; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-top:2px;margin-top:2px;border-top:1px solid #ddd;"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Reference Number:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="payInvstRefNum" name="payInvstRefNum" value="<?php echo $payInvstRefNum; ?>" <?php echo $mkReadOnly; ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Client ID:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" aria-label="..." id="payInvstClientID" name="payInvstClientID" value="<?php echo $payInvstClientID; ?>" <?php echo $mkReadOnly; ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="">Roll Over Type:</label>
                                </div>
                                <div class="col-md-8">
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payInvstRollOvrType" style="width:100% !important;">
                                        <?php
                                        $valslctdArry = array("", "", "");
                                        $srchInsArrys = array("None", "Roll Over", "Roll Over with Interest");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($payInvstRollOvrType == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>                                                            
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="">Remark / Narration:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group"  style="width:100%;">
                                        <textarea class="form-control rqrdFld" rows="5" cols="20" id="payInvstTransDesc" name="payInvstTransDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payInvstTransDesc; ?></textarea>
                                        <input class="form-control" type="hidden" id="payInvstTransDesc1" value="<?php echo $payInvstTransDesc; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payInvstTransDesc');" style="max-width:30px;width:30px;">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Status:</label>
                                </div>
                                <?php
                                $style1 = "color:red;";
                                $style2 = "color:red;";
                                $style3 = "color:red;";
                                $payInvstTrnsTypePst = ($payInvstTrnsType == "PURCHASE") ? "D" : "ED";
                                if ($payInvstTrnsType == "PURCHASE") {
                                    $style1 = "color:green;";
                                } else {
                                    $style1 = "color:#0d0d0d;";
                                }
                                if ($rqStatus == "Finalized") {
                                    $style2 = "color:green;";
                                } else if ($rqStatus == "Cancelled") {
                                    $style2 = "color:#0d0d0d;";
                                }
                                if ($payInvstTransIsPstd == "1") {
                                    $style3 = "color:green;";
                                }
                                ?>
                                <div class="col-md-8">                             
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;width:100% !important;" id="myPayInvstTransStatusBtn">
                                        <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php
                                            echo "<span style=\"" . $style1 . "\">" . $payInvstTrnsType . $payInvstTrnsTypePst . "</span> - <span style=\"" . $style2 . "\">" . $rqStatus . "</span><span style=\"" . $style3 . "\">" . ($payInvstTransIsPstd == "1" ? " [Posted]" : " [Not Posted]") . "</span>";
                                            ?>
                                        </span>
                                    </button>
                                </div>
                            </div>                        
                            <div class="form-group">
                                <label for="payInvstTransGLBatch" class="control-label col-md-4">GL Batch Name:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payInvstTransGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $payInvstTransGLBatch; ?>" readonly="true"/>
                                        <input type="hidden" id="payInvstTransGLBatchID" value="<?php echo $payInvstTransGLBatchID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $payInvstTransGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdPayInvstTransID; ?>, 'Fund Management');">
                                            <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:2px 0px 2px 0px;"></div>
                    <div class="row">
                        <div class="col-md-12">     
                            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOnePayInvstCalcForm(<?php echo $sbmtdPayInvstTransID; ?>, 19);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                    <img src="cmn_images/calculator.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Investment Calculator
                                </button>
                            </div> 
                            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                    <?php if ($sbmtdPayInvstTransID > 0) { ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOnePayInvstTransDocsForm(<?php echo $sbmtdPayInvstTransID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button> 
                                    <?php } ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOnePayInvstTransForm(<?php echo $sbmtdPayInvstTransID; ?>, 1, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                    <?php
                                    if ($rqStatus == "Not Finalized") {
                                        ?>
                                        <?php if (($canEdt === true)) {
                                            ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayInvstTransForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                            <?php
                                        }
                                        if (($canApprove === true)) {
                                            ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayInvstTransForm('<?php echo $fnccurnm; ?>', 2);" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                            </button>    
                                        <?php } ?>
                                        <?php
                                    } else if ($rqStatus == "Finalized") {
                                        if ($canVoid && ($canDel === true)) {
                                            ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOnePInvstRedeemForm(<?php echo $sbmtdPayInvstTransID; ?>, 18);" data-toggle="tooltip" data-placement="bottom" title = "Redeem/Rediscount Investment">
                                                <img src="cmn_images/back_2.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Redeem Investment
                                            </button>
                                            <button id="fnlzeRvrslPayInvstTransBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayInvstTransRvrslForm('<?php echo $fnccurnm; ?>', 1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                                                                   
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>                    
                            </div> 
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 18) {
                /* REDEPTION FORM - REDEEM */
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                $payInvstTrnsType = isset($_POST['payInvstTrnsType']) ? cleanInputData($_POST['payInvstTrnsType']) : "REDEEM";
                $payInvstTransInvcCur = isset($_POST['payInvstTransInvcCur']) ? cleanInputData($_POST['payInvstTransInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $payInvstTransInvcCurID = getPssblValID($payInvstTransInvcCur, $curLovID);
                $payInvstItemTypID = -1;
                $payInvstRollOvrType = isset($_POST['payInvstRollOvrType']) ? cleanInputData($_POST['payInvstRollOvrType']) : '';
                $payInvstPrchsDte = isset($_POST['payInvstPrchsDte']) ? cleanInputData($_POST['payInvstPrchsDte']) : '';
                $payInvstMatureDte = isset($_POST['payInvstMatureDte']) ? cleanInputData($_POST['payInvstMatureDte']) : '';
                $payInvstTransDesc = isset($_POST['payInvstTransDesc']) ? cleanInputData($_POST['payInvstTransDesc']) : '';
                $payOLDMatureAmnt = 0;
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $rqStatus = "Not Finalized"; //approval_status
                $rqStatusNext = "Finalize"; //next_aproval_action
                $rqstatusColor = "red";
                if ($sbmtdPayInvstTransID > 0) {
                    $result = get_One_InvstTransDocHdr($sbmtdPayInvstTransID);
                    while ($row = loc_db_fetch_array($result)) {
                        $payInvstTrnsType = $row[7];
                        $payInvstRollOvrType = $row[8];
                        $payInvstPrchsDte = $row[15];
                        $payInvstMatureDte = $row[16];
                        $payInvstTransDesc = "Redemption of Investment - " . $row[5];
                        $rqStatus = $row[6];
                        $rqStatusNext = "Finalize"; //next_aproval_action
                        $rqstatusColor = "red";

                        $payInvstPrchsAmnt = (float) $row[9];
                        $payOLDMatureAmnt = (float) $row[10];
                        $payInvstTransInvcCurID = (int) $row[21];
                        $payInvstTransInvcCur = $row[22];
                        /*if ($rqStatus == "Finalized") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Not Finalized") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Finalized") {
                                $mkRmrkReadOnly = "readonly=\"true\"";
                            }
                        }*/
                    }
                }
                $payInvstMatureAmnt = $payOLDMatureAmnt;
                ?> 
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="margin-bottom:0px !important;">Maturity Amount:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                        <span class="" style="font-size: 20px !important;" id="payInvstTransInvcCur22"><?php echo $payInvstTransInvcCur; ?></span>
                                    </label>
                                    <input class="form-control" type="hidden" id="payOLDMatureAmnt2" value="<?php echo number_format($payOLDMatureAmnt, 2); ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdPayInvstTransID2" name="sbmtdPayInvstTransID2" value="<?php echo $sbmtdPayInvstTransID; ?>" readonly="true">
                                    <input class="form-control fundNumber rqrdFld" type="text" id="payInvstMatureAmnt2" value="<?php
                                    echo number_format($payInvstMatureAmnt, 2);
                                    ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('payInvstMatureAmnt2');" <?php echo $mkReadOnly; ?>/>
                                </div>
                            </div>
                        </div>  
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="margin-bottom:0px !important;">Redemption Date:</label>
                            </div>
                            <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                <input class="form-control rqrdFld" size="16" type="text" id="payInvstMatureDte2" name="payInvstMatureDte2" value="<?php echo $payInvstMatureDte; ?>" placeholder="DD-MMM-YYYY">
                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="">Action after Redemption:</label>
                            </div>
                            <div class="col-md-8">
                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payInvstRollOvrType2" style="width:100% !important;">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("None", "Roll Over", "Roll Over with Interest");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($payInvstRollOvrType == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>                                                          
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="">Remark / Narration:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group"  style="width:100%;">
                                    <textarea class="form-control" rows="5" cols="20" id="payInvstTransDesc2" name="payInvstTransDesc2" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payInvstTransDesc; ?></textarea>
                                    <input class="form-control" type="hidden" id="payInvstTransDesc12" value="<?php echo $payInvstTransDesc; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payInvstTransDesc');" style="max-width:30px;width:30px;">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>   
                    </div>
                </div> 
                <div class="row" style="margin-top:10px;">                                                    
                    <div class="col-md-12">  
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <?php if ($payInvstTrnsType == "PURCHASE") { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePInvstRedeemForm('<?php echo $fnccurnm; ?>', 2);" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                    </button>
                                <?php } ?>
                            </div> 
                        </div>
                    </div>
                </div>  
                <?php
            } else if ($vwtyp == 19) {
                /* Investment Calculator */
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                $payInvstTrnsType = isset($_POST['payInvstTrnsType']) ? cleanInputData($_POST['payInvstTrnsType']) : "PURCHASE";
                $payInvstItemTypID = -1;
                ?> 
                <div class="row">
                    <div class="col-md-12">                                      
                        <div class="form-group" style="margin-top:10px !important;">
                            <label for="payInvstCalcAmount" class="control-label col-md-4" style="margin-top:5px !important;">Amount to Invest:</label>
                            <div  class="col-md-8" style="margin-top:5px !important;">
                                <input class="form-control" id="principal" type="text" onfinishinput="calculate_bill()" style="height:36px !important;font-size:22px !important;font-weight:bold;">
                            </div>
                        </div>                                                      
                        <div class="form-group" style="">
                            <label for="payInvstCalcAmount" class="control-label col-md-4" style="margin-top:5px !important;">Interest Rate:</label>
                            <div  class="col-md-8" style="margin-top:5px !important;">
                                <div class="input-group"> 
                                    <select id="billrate" type="text" value="25" class="form-control" style="height:36px !important;font-size:15px !important;font-weight:bold;"> 
                                        <option value=".13">13%</option>
                                        <option value=".14">14%</option>
                                        <option value=".15">15%</option>
                                        <option value=".16">16%</option>
                                        <option value=".17">17%</option>
                                        <option value=".18">18%</option>
                                        <option value=".19">19%</option>
                                        <option value=".20">20%</option>
                                        <option value=".21">21%</option>
                                        <option value=".22">22%</option>
                                        <option value=".23">23%</option>
                                        <option value=".24">24%</option>
                                        <option value=".25">25%</option>
                                        <option value=".26">26%</option>
                                        <option value=".27">27%</option>
                                        <option value=".28">28%</option>
                                        <option value=".29">29%</option>
                                        <option value=".30">30%</option>
                                        <option value=".31">31%</option>
                                        <option value=".32">32%</option>
                                        <option value=".33">33%</option>
                                        <option value=".34">34%</option>
                                        <option value=".35">35%</option>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <span class="input-group-addon"> OR </span>     
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <input id="erate" type="text" class="form-control"  style="height:36px !important;font-size:15px !important;font-weight:bold;" placeholder="Enter" onfinishinput="calculate_bill()">
                                    <span class="input-group-addon"> % </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="row" style="margin-top:10px;">                                                    
                    <div class="col-md-12">                                                    
                        <div class="col-md-12">
                            <table class="gridtable" id="myInbxDetTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px;">
                                <caption class="basic_person_lg" style="background-image: linear-gradient(#fefefe,rgba(0,50,69,0.05)) !important;">Expected Investment Returns</caption>
                                <tbody>
                                    <tr><td width="20%" class="likeheader" style="font-weight:normal;font-size:14px;">91 Day Bill</td><td width="70%"><div class="total total-91" style="font-weight:bold;font-size:16px;color:blue;">GHS 0.00</div></td></tr>
                                    <tr><td width="20%" class="likeheader" style="font-weight:normal;font-size:14px;">182 Day Bill</td><td width="70%"><div class="total total-180" style="font-weight:bold;font-size:16px;color:blue;">GHS 0.00</div></td></tr>
                                    <tr><td width="20%" class="likeheader" style="font-weight:normal;font-size:14px;">1 Year Note</td><td width="70%"><div class="total total-360" style="font-weight:bold;font-size:16px;color:blue;">GHS 0.00</div></td></tr>
                                    <tr><td width="20%" class="likeheader" style="font-weight:normal;font-size:14px;">2 Year FXR Note</td><td width="70%"><div class="total total-720" style="font-weight:bold;font-size:16px;color:blue;">GHS 0.00</div></td></tr>
                                    <tr><td width="20%" class="likeheader" style="font-weight:normal;font-size:14px;">3 Year Bond</td><td width="70%"><div class="total total-1080" style="font-weight:bold;font-size:16px;color:blue;">GHS 0.00</div></td></tr>
                                    <tr><td width="20%" class="likeheader" style="font-weight:normal;font-size:14px;">5 Year Bond</td><td width="70%"><div class="total total-1800" style="font-weight:bold;font-size:16px;color:blue;">GHS 0.00</div></td></tr>
                                    <tr><td width="20%" class="likeheader" style="font-weight:normal;font-size:14px;">20 Year Bond</td><td width="70%"><div class="total total-7200" style="font-weight:bold;font-size:16px;color:blue;">GHS 0.00</div></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>  
                <?php
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdPayInvstTransID = isset($_POST['sbmtdPayInvstTransID']) ? cleanInputData($_POST['sbmtdPayInvstTransID']) : -1;
                if (!$canAdd || ($sbmtdPayInvstTransID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdPayInvstTransID;
                $total = get_Total_InvstTrans_Attachments($srchFor, $pkID, "INVESTMENT");
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_InvstTrans_Attachments($srchFor, $curIdx, $lmtSze, $pkID, "INVESTMENT", $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>       
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdInvstTransDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdInvstTransDocsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                    . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdInvstTransDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdInvstTransDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdInvstTransDocsRow_WWW123WWW_DocCtgryNm', 'attchdInvstTransDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdInvstTransDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToInvstTransDocs('attchdInvstTransDocsRow_WWW123WWW_DocFile','attchdInvstTransDocsRow_WWW123WWW_AttchdDocsID','attchdInvstTransDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdInvstTransDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdInvstTransDoc('attchdInvstTransDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdInvstTransDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdInvstTransDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttchdInvstTransDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayInvstTransID=<?php echo $sbmtdPayInvstTransID; ?>', 'ReloadDialog');">
                                    <input id="attchdInvstTransDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdInvstTransDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayInvstTransID=<?php echo $sbmtdPayInvstTransID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdInvstTransDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayInvstTransID=<?php echo $sbmtdPayInvstTransID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdInvstTransDocsDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAttchdInvstTransDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayInvstTransID=<?php echo $sbmtdPayInvstTransID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdInvstTransDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayInvstTransID=<?php echo $sbmtdPayInvstTransID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdInvstTransDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/PayDocs/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                            ?>
                                            <tr id="attchdInvstTransDocsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdInvstTransDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdInvstTransDoc('attchdInvstTransDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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