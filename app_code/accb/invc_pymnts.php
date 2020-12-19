<?php
$canPayPyblsDocs = test_prmssns($dfltPrvldgs[71], $mdlNm);
$canPayRcvblsDocs = test_prmssns($dfltPrvldgs[72], $mdlNm);
$canVoid = test_prmssns($dfltPrvldgs[67], $mdlNm) || test_prmssns($dfltPrvldgs[68], $mdlNm);
$canAdd = $canPayPyblsDocs || $canPayRcvblsDocs;
$canEdt = $canAdd;
$canDel = $canAdd;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canViewPymnts === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Payment Batch */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePymntsBatchNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Payment Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePymntsDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Remove/Restore Payment Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                $pKeyRmvRstr = isset($_POST['pKeyRmvRstr']) ? cleanInputData($_POST['pKeyRmvRstr']) : "0";
                if ($canDel) {
                    echo rmvRestorePymntsDet($pKeyID, $pKeyRmvRstr);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdt) {
                    echo deletePymntsDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Payment Batch
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdAccbPymntsID = isset($_POST['sbmtdAccbPymntsID']) ? (float) cleanInputData($_POST['sbmtdAccbPymntsID']) : -1;
                $accbPymntsBatchNum = isset($_POST['accbPymntsBatchNum']) ? cleanInputData($_POST['accbPymntsBatchNum']) : "";
                $accbPymntsTrnsStrtDte = isset($_POST['accbPymntsTrnsStrtDte']) ? cleanInputData($_POST['accbPymntsTrnsStrtDte']) : '';
                if ($accbPymntsTrnsStrtDte == "") {
                    $accbPymntsTrnsStrtDte = "01-Jan-" . date('Y') . " 00:00:00";
                }
                $accbPymntsTrnsEndDte = isset($_POST['accbPymntsTrnsEndDte']) ? cleanInputData($_POST['accbPymntsTrnsEndDte']) : '';
                if ($accbPymntsTrnsEndDte == "") {
                    $accbPymntsTrnsEndDte = "31-Dec-" . date('Y') . " 23:59:59";
                }
                $accbPymntsDocType = isset($_POST['accbPymntsDocType']) ? cleanInputData($_POST['accbPymntsDocType']) : '';
                $accbPymntsDfltTrnsDte = isset($_POST['accbPymntsDfltTrnsDte']) ? cleanInputData($_POST['accbPymntsDfltTrnsDte']) : '';

                if ($accbPymntsDfltTrnsDte == "") {
                    $accbPymntsDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                }
                $accbPymntsMthdType = isset($_POST['accbPymntsMthdType']) ? cleanInputData($_POST['accbPymntsMthdType']) : '';
                $accbPymntsInvcCur = isset($_POST['accbPymntsInvcCur']) ? cleanInputData($_POST['accbPymntsInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $accbPymntsInvcCurID = getPssblValID($accbPymntsInvcCur, $curLovID);
                $accbPymntsGvnAmnt = isset($_POST['accbPymntsGvnAmnt']) ? (float) cleanInputData($_POST['accbPymntsGvnAmnt']) : 0;
                $accbPymntsDocTmplt = isset($_POST['accbPymntsDocTmplt']) ? cleanInputData($_POST['accbPymntsDocTmplt']) : '';
                $accbPymntsPayMthdID = isset($_POST['accbPymntsPayMthdID']) ? (float) cleanInputData($_POST['accbPymntsPayMthdID']) : -1;
                $accbPymntsSpplrID = isset($_POST['accbPymntsSpplrID']) ? (float) cleanInputData($_POST['accbPymntsSpplrID']) : -1;
                $myCptrdPymntsValsTtlVal = isset($_POST['myCptrdPymntsValsTtlVal']) ? (float) cleanInputData($_POST['myCptrdPymntsValsTtlVal']) : 0;

                $accbPymntsChqName = isset($_POST['accbPymntsChqName']) ? cleanInputData($_POST['accbPymntsChqName']) : '';
                $accbPymntsChqNumber = isset($_POST['accbPymntsChqNumber']) ? cleanInputData($_POST['accbPymntsChqNumber']) : '';
                $accbPymntsSignCode = isset($_POST['accbPymntsSignCode']) ? cleanInputData($_POST['accbPymntsSignCode']) : '';
                $accbPymntsGLBatchID = isset($_POST['accbPymntsGLBatchID']) ? (float) cleanInputData($_POST['accbPymntsGLBatchID']) : -1;
                $accbPymntsDesc = isset($_POST['accbPymntsDesc']) ? cleanInputData($_POST['accbPymntsDesc']) : '';
                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;

                if (strlen($accbPymntsDesc) > 499) {
                    $accbPymntsDesc = substr($pymntsInvcDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($accbPymntsBatchNum == "") {
                    $exitErrMsg .= "Please enter Batch Number!<br/>";
                }
                if ($accbPymntsDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                if ($accbPymntsMthdType == "") {
                    $exitErrMsg .= "Payment Type cannot be empty!<br/>";
                }
                if ($accbPymntsDfltTrnsDte == "") {
                    $exitErrMsg .= "Payment Date cannot be empty!<br/>";
                }
                if ($accbPymntsPayMthdID <= 0) {
                    $exitErrMsg .= "Payment Method cannot be empty!<br/>";
                }
                $oldBatchID = getGnrlRecID("accb.accb_payments_batches", "pymnt_batch_name", "pymnt_batch_id", $accbPymntsBatchNum, $orgID);
                if ($oldBatchID > 0 && $oldBatchID != $sbmtdAccbPymntsID) {
                    $exitErrMsg .= "New Batch Number/Name is already in use in this Organization!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAccbPymntsID'] = $sbmtdAccbPymntsID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $orgnlBtchID = -1;
                $vldtyStatus = "VALID";
                $batchStatus = "Unprocessed";
                $amntPaid = 0;
                $chngBals = 0;
                $incrdcrs1 = "Increase";
                $rcvblPyblAcntID = -1;
                $incrsDcrs2 = "Increase";
                $cashSuspnsAcntID = "-1";
                $funcCurRate = 1;
                $funcCurAmnt = 0;
                $acntCurID = -1;
                $acntCurRate = 1;
                $acntCurAmnt = 0;
                if ($sbmtdAccbPymntsID <= 0) {
                    createPymntsBatch(
                        $orgID,
                        $accbPymntsTrnsStrtDte,
                        $accbPymntsTrnsEndDte,
                        $accbPymntsDocType,
                        $accbPymntsBatchNum,
                        $accbPymntsDesc,
                        $accbPymntsSpplrID,
                        $accbPymntsPayMthdID,
                        $accbPymntsMthdType,
                        $orgnlBtchID,
                        $vldtyStatus,
                        $accbPymntsDocTmplt,
                        $batchStatus,
                        $accbPymntsDfltTrnsDte,
                        $incrdcrs1,
                        $rcvblPyblAcntID,
                        $incrsDcrs2,
                        $cashSuspnsAcntID,
                        $accbPymntsGvnAmnt,
                        $amntPaid,
                        $chngBals,
                        $accbPymntsInvcCurID,
                        $fnccurid,
                        $funcCurRate,
                        $funcCurAmnt,
                        $acntCurID,
                        $acntCurRate,
                        $acntCurAmnt,
                        $accbPymntsChqName,
                        $accbPymntsChqNumber,
                        $accbPymntsSignCode
                    );
                    $sbmtdAccbPymntsID = getGnrlRecID("accb.accb_payments_batches", "pymnt_batch_name", "pymnt_batch_id", $accbPymntsBatchNum, $orgID);
                } else if ($sbmtdAccbPymntsID > 0) {
                    updtPymntsBatch(
                        $sbmtdAccbPymntsID,
                        $accbPymntsTrnsStrtDte,
                        $accbPymntsTrnsEndDte,
                        $accbPymntsDocType,
                        $accbPymntsBatchNum,
                        $accbPymntsDesc,
                        $accbPymntsSpplrID,
                        $accbPymntsPayMthdID,
                        $accbPymntsMthdType,
                        $orgnlBtchID,
                        $vldtyStatus,
                        $accbPymntsDocTmplt,
                        $batchStatus,
                        $accbPymntsDfltTrnsDte,
                        $incrdcrs1,
                        $rcvblPyblAcntID,
                        $incrsDcrs2,
                        $cashSuspnsAcntID,
                        $accbPymntsGvnAmnt,
                        $amntPaid,
                        $chngBals,
                        $accbPymntsInvcCurID,
                        $fnccurid,
                        $funcCurRate,
                        $funcCurAmnt,
                        $acntCurID,
                        $acntCurRate,
                        $acntCurAmnt,
                        $accbPymntsChqName,
                        $accbPymntsChqNumber,
                        $accbPymntsSignCode
                    );
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDetTransLines, "|~") != "") {
                    //Save Payment Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 4) {
                            $lnSmmryLnID = (float) (cleanInputData1($crntRow[0]));
                            $lineDesc = cleanInputData1($crntRow[1]);
                            $lineCurNm = cleanInputData1($crntRow[2]);
                            $lineCurID = getPssblValID($lineCurNm, $curLovID);
                            $entrdAmt = (float) cleanInputData1($crntRow[3]);
                            $prepayDocID = -1;
                            $prepayDocType = "";
                            $otherinfo = "";
                            $expryDte = "";
                            $actvtyStatus = "";
                            $actvtyDocName = "";
                            $errMsg = "";
                            if ($lineDesc != "" && $entrdAmt > 0) {
                                if ($lnSmmryLnID > 0) {
                                    $afftctd += updtPymntDet1(
                                        $lnSmmryLnID,
                                        $sbmtdAccbPymntsID,
                                        $accbPymntsPayMthdID,
                                        $amntPaid,
                                        $accbPymntsInvcCurID,
                                        $chngBals,
                                        $lineDesc,
                                        $accbPymntsDfltTrnsDte,
                                        $incrdcrs1,
                                        $rcvblPyblAcntID,
                                        $incrsDcrs2,
                                        $cashSuspnsAcntID,
                                        -1,
                                        $vldtyStatus,
                                        -1,
                                        $fnccurid,
                                        $acntCurID,
                                        $funcCurRate,
                                        $acntCurRate,
                                        $funcCurAmnt,
                                        $acntCurAmnt,
                                        $prepayDocID,
                                        $prepayDocType,
                                        $otherinfo,
                                        $accbPymntsChqName,
                                        $expryDte,
                                        $accbPymntsChqNumber,
                                        $accbPymntsSignCode,
                                        $actvtyStatus,
                                        $actvtyDocName,
                                        -1,
                                        '0',
                                        $entrdAmt
                                    );
                                }
                            }
                        }
                    }
                }

                $whereClause = "";
                if ($accbPymntsInvcCurID > 0) {
                    $whereClause .= " and (invc_curr_id=" . $accbPymntsInvcCurID . ")";
                }
                if ($accbPymntsDocTmplt != "") {
                    $whereClause .= " and (doc_tmplt_clsfctn='" . loc_db_escape_string($accbPymntsDocTmplt) . "')";
                }
                if ($accbPymntsPayMthdID > 0) {
                    $whereClause .= " and (pymny_method_id=" . $accbPymntsPayMthdID . ")";
                }
                if ($accbPymntsMthdType == "Supplier Payments") {
                    if ($accbPymntsTrnsStrtDte != "") {
                        $whereClause .= " and (to_timestamp(pybls_invc_date,'YYYY-MM-DD')>= to_timestamp('" . loc_db_escape_string($accbPymntsTrnsStrtDte) . "','DD-Mon-YYYY HH24:MI:SS'))";
                    }
                    if ($accbPymntsTrnsEndDte != "") {
                        $whereClause .= " and (to_timestamp(pybls_invc_date,'YYYY-MM-DD')<= to_timestamp('" . loc_db_escape_string($accbPymntsTrnsEndDte) . "','DD-Mon-YYYY HH24:MI:SS'))";
                    }
                    if ($accbPymntsDocType != "") {
                        $whereClause .= " and (pybls_invc_type='" . loc_db_escape_string($accbPymntsDocType) . "')";
                    }
                    if ($accbPymntsSpplrID > 0) {
                        $whereClause .= " and (supplier_id=" . $accbPymntsSpplrID . ")";
                    }
                } else {
                    if ($accbPymntsTrnsStrtDte != "") {
                        $whereClause .= " and (to_timestamp(rcvbls_invc_date,'YYYY-MM-DD')>= to_timestamp('" . loc_db_escape_string($accbPymntsTrnsStrtDte) . "','DD-Mon-YYYY HH24:MI:SS'))";
                    }
                    if ($accbPymntsTrnsEndDte != "") {
                        $whereClause .= " and (to_timestamp(rcvbls_invc_date,'YYYY-MM-DD')<= to_timestamp('" . loc_db_escape_string($accbPymntsTrnsEndDte) . "','DD-Mon-YYYY HH24:MI:SS'))";
                    }
                    if ($accbPymntsDocType != "") {
                        $whereClause .= " and (rcvbls_invc_type='" . loc_db_escape_string($accbPymntsDocType) . "')";
                    }
                    if ($accbPymntsSpplrID > 0) {
                        $whereClause .= " and (customer_id=" . $accbPymntsSpplrID . ")";
                    }
                }
                $ftchQlfyngDocs = get_QlfyngPaymentDocs($accbPymntsMthdType, $whereClause);
                while ($rwFtch = loc_db_fetch_array($ftchQlfyngDocs)) {
                    $docHdrID = (float) $rwFtch[0];
                    $lnSmmryLnID = get_PaymentDocsPymtID($docHdrID, $accbPymntsMthdType, $sbmtdAccbPymntsID);
                    $pymntRemark = "Payment of Invoice No.:" . $rwFtch[1] . " " . $rwFtch[3];
                    $srcDocType = $rwFtch[2];
                    $srcDocID = $docHdrID;
                    $prepayDocID = -1;
                    $prepayDocType = "";
                    $otherinfo = "";
                    $expryDte = "";
                    $actvtyStatus = "";
                    $actvtyDocName = "";
                    $entrdAmt = ((float) $rwFtch[4]) - ((float) $rwFtch[5]);

                    /* $entrdAmt1 = $accbPymntsGvnAmnt;
                      $accbPymntsGvnAmnt = $accbPymntsGvnAmnt - $entrdAmt;
                      if ($accbPymntsGvnAmnt < 0) {
                      $accbPymntsGvnAmnt = $entrdAmt1;
                      $entrdAmt = $accbPymntsGvnAmnt;
                      } */
                    if ($lnSmmryLnID <= 0) {
                        $lnSmmryLnID = getNewPymntLnID();
                        $afftctd += createPymntDet(
                            $lnSmmryLnID,
                            $sbmtdAccbPymntsID,
                            $accbPymntsPayMthdID,
                            $amntPaid,
                            $accbPymntsInvcCurID,
                            $chngBals,
                            $pymntRemark,
                            $srcDocType,
                            $srcDocID,
                            $accbPymntsDfltTrnsDte,
                            $incrdcrs1,
                            $rcvblPyblAcntID,
                            $incrsDcrs2,
                            $cashSuspnsAcntID,
                            -1,
                            $vldtyStatus,
                            -1,
                            $fnccurid,
                            $acntCurID,
                            $funcCurRate,
                            $acntCurRate,
                            $funcCurAmnt,
                            $acntCurAmnt,
                            $prepayDocID,
                            $prepayDocType,
                            $otherinfo,
                            $accbPymntsChqName,
                            $expryDte,
                            $accbPymntsChqNumber,
                            $accbPymntsSignCode,
                            $actvtyStatus,
                            $actvtyDocName,
                            $entrdAmt
                        );
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Payment Batch Successfully Saved!"
                        . "<br/>" . $afftctd . " Payment Document(s) Saved Successfully!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Payment Batch Successfully Saved!"
                        . "<br/>" . $afftctd . " Payment Document(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdAccbPymntsID'] = $sbmtdAccbPymntsID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 5) {
                header("content-type:application/json");
                //var_dump($_POST);
                $sbmtdDocumentID = isset($_POST['sbmtdDocumentID']) ? (float) cleanInputData($_POST['sbmtdDocumentID']) : -1;
                $sbmtdDocumentType = isset($_POST['sbmtdDocumentType']) ? cleanInputData($_POST['sbmtdDocumentType']) : "";
                $accbPymntsPayMthdID = isset($_POST['accbPymntsPayMthdID']) ? (float) cleanInputData($_POST['accbPymntsPayMthdID']) : -1;
                $accbPymntsPrepayDocIDs = isset($_POST['accbPymntsPrepayDocIDs']) ? cleanInputData($_POST['accbPymntsPrepayDocIDs']) : "-1";
                $accbPymntsInvcSpplrID = isset($_POST['accbPymntsInvcSpplrID']) ? (float) cleanInputData($_POST['accbPymntsInvcSpplrID']) : -1;
                $accbPymntsInvcCur = isset($_POST['accbPymntsInvcCur']) ? cleanInputData($_POST['accbPymntsInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $accbPymntsInvcCurID = getPssblValID($accbPymntsInvcCur, $curLovID);
                $accbPymntsDesc = isset($_POST['accbPymntsDesc']) ? cleanInputData($_POST['accbPymntsDesc']) : "";
                $accbPymntsDfltTrnsDte = isset($_POST['accbPymntsDfltTrnsDte']) ? cleanInputData($_POST['accbPymntsDfltTrnsDte']) : '';
                $accbPymntsGvnAmnt = isset($_POST['accbPymntsGvnAmnt']) ? (float) cleanInputData($_POST['accbPymntsGvnAmnt']) : 0;
                $accbPymntsPaidAmnt = isset($_POST['accbPymntsPaidAmnt']) ? (float) cleanInputData($_POST['accbPymntsPaidAmnt']) : 0;
                $accbPymntsChngBals = isset($_POST['accbPymntsChngBals']) ? (float) cleanInputData($_POST['accbPymntsChngBals']) : 0;
                $accbPymntsChqName = isset($_POST['accbPymntsChqName']) ? cleanInputData($_POST['accbPymntsChqName']) : '';
                $accbPymntsChqNumber = isset($_POST['accbPymntsChqNumber']) ? cleanInputData($_POST['accbPymntsChqNumber']) : "";
                $accbPymntsExpiryDate = isset($_POST['accbPymntsExpiryDate']) ? cleanInputData($_POST['accbPymntsExpiryDate']) : "";
                $accbPymntsSignCode = isset($_POST['accbPymntsSignCode']) ? cleanInputData($_POST['accbPymntsSignCode']) : "";
                $p_orgnlPymntID = isset($_POST['sbmtdAccbPymntsID']) ? (float) cleanInputData($_POST['sbmtdAccbPymntsID']) : -1;
                $p_NewPymntBatchID = -1;
                $p_invoice_id = $sbmtdDocumentID;
                $p_msPyID = -1;
                $p_createPrepay = "false";
                $exitErrMsg = "";
                if ($sbmtdDocumentID <= 0) {
                    $p_createPrepay = "true";
                }
                if ($p_orgnlPymntID > 0) {
                    $p_NewPymntBatchID = (float) getGnrlRecNm("accb.accb_payments", "pymnt_id", "pymnt_batch_id", $p_orgnlPymntID);
                    $p_msPyID = (float) getGnrlRecNm("accb.accb_payments", "pymnt_id", "intnl_pay_trns_id", $p_orgnlPymntID);
                }
                $p_doc_types = $sbmtdDocumentType;
                $p_pay_mthd_id = $accbPymntsPayMthdID;
                $p_pay_remarks = $accbPymntsDesc;
                $p_pay_date = $accbPymntsDfltTrnsDte;
                $p_pay_amt_rcvd = $accbPymntsGvnAmnt;
                $p_cheque_card_name = $accbPymntsChqName;
                $p_cheque_card_num = $accbPymntsChqNumber;
                $p_cheque_card_code = $accbPymntsSignCode;
                $p_cheque_card_expdate = $accbPymntsExpiryDate;
                $p_who_rn = $usrID;
                $p_run_date = "";
                $orgidno = $orgID;
                $p_msgid = -1;
                $p_appld_prpay_docid = -1;
                if (trim($accbPymntsPrepayDocIDs, ",") != "" && trim($accbPymntsPrepayDocIDs, ",") != "-1") {
                    $vArry = explode(",", $accbPymntsPrepayDocIDs);
                    for ($k = 0; $k < count($vArry); $k++) {
                        $accbPymntsPrepayDocID = (int) $vArry[$k];
                        $p_appld_prpay_docid = $accbPymntsPrepayDocID;
                        
                        if ($p_doc_types == "Supplier Payments") {
                            $p_pay_amt_rcvd = getPyblsPrepayAvlblAmt($accbPymntsPrepayDocID);
                        } else {
                            $p_pay_amt_rcvd = getRcvblsPrepayAvlblAmt($accbPymntsPrepayDocID);
                        }
                        //echo $accbPymntsGvnAmnt . "::" . $p_appld_prpay_docid . "::" . $p_pay_amt_rcvd;
                        if ($accbPymntsGvnAmnt < $p_pay_amt_rcvd) {
                            $p_pay_amt_rcvd = $accbPymntsGvnAmnt;
                        }
                        $exitErrMsg .= processInvcQuickPay(
                            $p_orgnlPymntID,
                            $p_NewPymntBatchID,
                            $p_invoice_id,
                            $p_msPyID,
                            $p_createPrepay,
                            $p_doc_types,
                            $p_pay_mthd_id,
                            $p_pay_remarks,
                            $p_pay_date,
                            $p_pay_amt_rcvd,
                            $p_appld_prpay_docid,
                            $p_cheque_card_name,
                            $p_cheque_card_num,
                            $p_cheque_card_code,
                            $p_cheque_card_expdate,
                            $p_who_rn,
                            $p_run_date,
                            $orgidno,
                            $p_msgid,
                            $accbPymntsInvcSpplrID,
                            $accbPymntsInvcCurID
                        );
                        $accbPymntsGvnAmnt -= $p_pay_amt_rcvd;
                    }
                } else {
                    $exitErrMsg .= processInvcQuickPay(
                        $p_orgnlPymntID,
                        $p_NewPymntBatchID,
                        $p_invoice_id,
                        $p_msPyID,
                        $p_createPrepay,
                        $p_doc_types,
                        $p_pay_mthd_id,
                        $p_pay_remarks,
                        $p_pay_date,
                        $p_pay_amt_rcvd,
                        $p_appld_prpay_docid,
                        $p_cheque_card_name,
                        $p_cheque_card_num,
                        $p_cheque_card_code,
                        $p_cheque_card_expdate,
                        $p_who_rn,
                        $p_run_date,
                        $orgidno,
                        $p_msgid,
                        $accbPymntsInvcSpplrID,
                        $accbPymntsInvcCurID
                    );
                }
                $arr_content['percent'] = 100;
                if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                }
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 20) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdAccbPymntsID = isset($_POST['sbmtdAccbPymntsID']) ? cleanInputData($_POST['sbmtdAccbPymntsID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdAccbPymntsID;
                if ($attchmentID > 0) {
                    uploadDaPymntsDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewPymntsDocID();
                    createPymntsDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaPymntsDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/PymntDocs/" . $nwImgLoc;
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
                //Reverse Payment Batch
                $errMsg = "";
                $accbPymntsDesc = isset($_POST['accbPymntsDesc']) ? cleanInputData($_POST['accbPymntsDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdAccbPymntsID = isset($_POST['sbmtdAccbPymntsID']) ? cleanInputData($_POST['sbmtdAccbPymntsID']) : -1;
                if (!$canVoid) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $trnsIDStatus1 = getGnrlRecNm("accb.accb_payments_batches", "pymnt_batch_id", "batch_vldty_status", $sbmtdAccbPymntsID);
                if ($trnsIDStatus1 == "VOID") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Payment Batch Already Voided!</span>";
                    exit();
                }
                if ($sbmtdAccbPymntsID > 0 && $accbPymntsDesc != "") {
                    $rsltCnt = execUpdtInsSQL("UPDATE accb.accb_payments_batches SET pymnt_batch_desc='" . loc_db_escape_string($accbPymntsDesc) . "' WHERE pymnt_batch_id=" . $sbmtdAccbPymntsID);
                    $errMsg = $rsltCnt . " Batch Reversal Started Successfully!";
                    $response = array(
                        'sbmtdAccbPymntsID' => $sbmtdAccbPymntsID,
                        'sbmtMsg' => $errMsg
                    );
                    echo json_encode($response);
                } else {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Failed to start Payment Batch Reversal!</span>";
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                //Invoice Payments
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Invoice Payments</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'All';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Payment ID DESC";
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                $date = new DateTime($gnrlTrnsDteYMD);
                $date->modify('-24 month');
                $qStrtDte = $date->format('d-M-Y') . " 00:00:00";
                $date = new DateTime($gnrlTrnsDteYMD);
                $date->modify('+24 month');
                $qEndDte = $date->format('d-M-Y') . " 23:59:59";

                if (isset($_POST['qStrtDte'])) {
                    $qStrtDte = cleanInputData($_POST['qStrtDte']);
                    if (strlen($qStrtDte) == 11) {
                        $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
                    } else {
                        $qStrtDte = "01-Jan-1900 00:00:00";
                    }
                }
                if (isset($_POST['qEndDte'])) {
                    $qEndDte = cleanInputData($_POST['qEndDte']);
                    if (strlen($qEndDte) == 11) {
                        $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
                    } else {
                        $qEndDte = "31-Dec-4000 23:59:59";
                    }
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_PymntBatch($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qShwUnpstdOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_PymntBatch($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qShwUnpstdOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
                    $colClassType3 = "col-md-5";
?>
                    <form id='accbPymntsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245">INVOICE PAYMENTS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                                $colClassType3 = "col-md-10";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbPymntsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncAccbPymnts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="accbPymntsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPymnts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbPymnts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbPymntsSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                            $srchInsArrys = array(
                                                "All", "Batch Name", "Batch Description", "Document Classification", "Customer/Supplier Name",
                                                "Payment Method", "Source Doc Number", "Document Type", "Batch Source", "Batch Status"
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
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbPymntsDsplySze" style="min-width:70px !important;">
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
                                                <a href="javascript:getAccbPymnts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbPymnts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
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
                                        <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbPymntsForm(-1, 1, 'ShowDialog', 'Supplier Standard Payment');" data-toggle="tooltip" data-placement="bottom" title="Add New Supplier Payment">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                New Supplier Payment
                                            </button>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbPymntsForm(-1, 1, 'ShowDialog', 'Customer Standard Payment');" data-toggle="tooltip" data-placement="bottom" title="Add New Customer Payment">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                New Customer Payment
                                            </button>
                                        </div>
                                    <?php }
                                    ?>
                                    <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                        <div class="form-check" style="font-size: 12px !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getAccbPymnts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbPymntsShwUnpstdOnly" name="accbPymntsShwUnpstdOnly" <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Only Unposted
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-5" style="padding:0px 1px 0px 1px !important;">
                                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control" size="16" type="text" id="accbPymntsStrtDate" name="accbPymntsStrtDate" value="<?php
                                                                                                                                                            echo substr($qStrtDte, 0, 11);
                                                                                                                                                            ?>" placeholder="Start Date">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control" size="16" type="text" id="accbPymntsEndDate" name="accbPymntsEndDate" value="<?php
                                                                                                                                                            echo substr($qEndDte, 0, 11);
                                                                                                                                                            ?>" placeholder="End Date">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="accbPymntsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:35px;width:35px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th style="max-width:155px;width:155px;">Batch Number/Type</th>
                                                <th>Batch Description</th>
                                                <th style="min-width:100px;width:100px;">Payment Method</th>
                                                <th style="min-width:100px;width:100px;">Payment Date</th>
                                                <th style="text-align:center;max-width:35px;width:35px;">CUR.</th>
                                                <th style="text-align:right;min-width:100px;width:100px;">Total Payment Amount</th>
                                                <th style="max-width:75px;width:75px;">Payment Status</th>
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
                                                <tr id="accbPymntsHdrsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Payment" onclick="getOneAccbPymntsForm(<?php echo $row[0]; ?>, 1, 'ShowDialog', '<?php echo $row[2]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <?php
                                                            if ($canAdd === true) {
                                                            ?>
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[1]; ?></td>
                                                    <td class="lovtd"><?php echo $row[3] . " (" . $row[2] . ")"; ?></td>
                                                    <td class="lovtd" style="font-weight: bold;color:black;"><?php echo $row[4]; ?></td>
                                                    <td class="lovtd" style=""><?php echo $row[5]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[6]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                echo number_format((float) $row[7], 2);
                                                                                                                                ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if ($row[8] == "Processed") {
                                                        $style1 = "color:green;";
                                                    } else if ($row[8] == "Cancelled") {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php echo $row[8]; ?></td>
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Batch" onclick="delAccbPymnts('accbPymntsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="accbPymntsHdrsRow<?php echo $cntr; ?>_HdrID" name="accbPymntsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                    ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|accb.accb_payments_batches|pymnt_batch_id"), $smplTokenWord1));
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
                //New Payments Form
                $date = new DateTime($gnrlTrnsDteYMD);
                $qStrtDte = $date->format('d-M-Y') . " 00:00:00";
                $date = new DateTime($gnrlTrnsDteYMD);
                $qEndDte = $date->format('d-M-Y') . " 23:59:59";

                $sbmtdAccbPymntsID = isset($_POST['sbmtdAccbPymntsID']) ? cleanInputData($_POST['sbmtdAccbPymntsID']) : -1;
                $accbPymntsVchType = isset($_POST['accbPymntsVchType']) ? cleanInputData($_POST['accbPymntsVchType']) : "Supplier Payments";
                $accbPymntsDocType = isset($_POST['accbPymntsDocType']) ? cleanInputData($_POST['accbPymntsDocType']) : "Supplier Standard Payment";
                if (!$canAdd || ($sbmtdAccbPymntsID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                if ($accbPymntsVchType == "Supplier Payments" && !$canPayPyblsDocs) {
                    restricted();
                    exit();
                }
                if ($accbPymntsVchType == "Customer Payments" && !$canPayRcvblsDocs) {
                    restricted();
                    exit();
                }
                $voidedAccbPymntsID = -1;
                $accbPymntsDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $accbPymntsTrnsStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 00:00:00";
                $accbPymntsTrnsEndDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 23:59:59";

                $accbPymntsVldtyStatus = "VALID";
                $accbPymntsIncrsDcrs1 = "Decrease";
                $accbPymntsAccntID1 = -1;
                $accbPymntsAccntNm1 = "";
                $accbPymntsIncrsDcrs2 = "Decrease";
                $accbPymntsAccntID2 = -1;
                $accbPymntsAccntNm2 = "";
                $gnrtdTrnsNo = "";
                $accbPymntsDesc = "";

                $accbPymntsDocTmpltID = -1;
                $srcPymntsDocNum = "";

                $accbPymntsSpplr = "";
                $accbPymntsSpplrID = -1;
                $rqStatus = "Unprocessed";
                $rqstatusColor = "red";

                $accbPymntsPayTerms = "";
                $accbPymntsPayMthd = "";
                $accbPymntsPayMthdID = -1;
                $accbPymntsTtlAmnt = 0;
                $accbPymntsRmvdTtlAmnt = 0;
                $accbPymntsAppldAmnt = 0;
                $accbPymntsGvnAmnt = 0;
                $accbPymntsPaidAmnt = 0;
                $accbPymntsChngBals = 0;
                $accbPymntsGLBatch = "";
                $accbPymntsGLBatchID = -1;
                $accbPymntsSpplrInvcNum = "";
                $accbPymntsDocTmplt = "";
                $accbPymntsEvntRgstr = "";
                $accbPymntsEvntRgstrID = -1;
                $accbPymntsEvntCtgry = "";
                $accbPymntsEvntDocTyp = "";
                $accbPymntsDfltBalsAcntID = -1;
                $accbPymntsDfltBalsAcnt = "";
                $accbPymntsInvcCurID = -1;
                $accbPymntsInvcCur = "";
                $accbPymntsFuncCurID = $fnccurid;
                $accbPymntsFuncCur = $fnccurnm;
                $accbPymntsFuncCurRate = 1.0000;
                $accbPymntsFuncCurAmnt = 1.0000;
                $accbPymntsAcntCurID = $fnccurid;
                $accbPymntsAcntCur = $fnccurnm;
                $accbPymntsAcntCurRate = 1.0000;
                $accbPymntsAcntCurAmnt = 1.0000;
                $accbPymntsIsPstd = "0";
                $accbPymntsChqName = "";
                $accbPymntsChqNumber = "";
                $accbPymntsSignCode = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdAccbPymntsID > 0) {
                    $result = get_One_PymntBatchHdr($sbmtdAccbPymntsID);
                    if ($row = loc_db_fetch_array($result)) {
                        $sbmtdAccbPymntsID = (float) $row[0];
                        $gnrtdTrnsNo = $row[1];
                        $accbPymntsDesc = $row[2];
                        $accbPymntsPayMthd = $row[4];
                        $accbPymntsPayMthdID = (float) $row[3];
                        $accbPymntsDocType = $row[5];
                        $accbPymntsDocTmplt = $row[6];
                        $accbPymntsTrnsStrtDte = $row[7];
                        $accbPymntsTrnsEndDte = $row[8];
                        $rqStatus = $row[9];
                        $accbPymntsVchType = $row[10];
                        $accbPymntsGLBatch = $row[12];
                        $accbPymntsGLBatchID = (float) $row[11];
                        $accbPymntsSpplr = $row[14];
                        $accbPymntsSpplrID = (float) $row[13];
                        $accbPymntsVldtyStatus = $row[15];
                        $voidedAccbPymntsID = (float) $row[16];
                        $accbPymntsDfltTrnsDte = $row[17];
                        $accbPymntsIncrsDcrs1 = $row[18];
                        $accbPymntsAccntID1 = (float) $row[19];
                        $accbPymntsAccntNm1 = $row[20];
                        $accbPymntsIncrsDcrs2 = $row[21];
                        $accbPymntsAccntID2 = (float) $row[22];
                        $accbPymntsAccntNm2 = $row[23];
                        $accbPymntsGvnAmnt = (float) $row[24];
                        $accbPymntsPaidAmnt = (float) $row[25];
                        $accbPymntsChngBals = (float) $row[26];
                        $accbPymntsInvcCurID = (int) $row[27];
                        $accbPymntsInvcCur = $row[28];
                        $accbPymntsFuncCurID = (int) $row[29];
                        $accbPymntsFuncCur = $row[30];
                        $accbPymntsFuncCurRate = (float) $row[31];
                        $accbPymntsFuncCurAmnt = (float) $row[32];
                        $accbPymntsAcntCurID = (int) $row[33];
                        $accbPymntsAcntCur = $row[34];
                        $accbPymntsAcntCurRate = (float) $row[35];
                        $accbPymntsAcntCurAmnt = (float) $row[36];
                        $accbPymntsChqName = $row[37];
                        $accbPymntsChqNumber = $row[38];
                        $accbPymntsSignCode = decrypt($row[39], $smplTokenWord1);
                        $accbPymntsIsPstd = $row[40];

                        $accbPymntsTtlAmnt = 0;
                        $accbPymntsRmvdTtlAmnt = 0;
                        $accbPymntsAppldAmnt = 0;


                        if ($rqStatus == "Processed") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Unprocessed") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Processed") {
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
                    $docTypes = array("Supplier Payments", "Customer Payments");
                    $docTypPrfxs = array("PYMT-SPLR", "RCPT-CSTMR");

                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $accbPymntsVchType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("accb.accb_payments_batches", "pymnt_batch_name", "pymnt_batch_id", $gnrtdTrnsNo1 . "%") + 1) . ""),
                        3,
                        '0',
                        STR_PAD_LEFT
                    );
                    if ($accbPymntsVchType == "Customer Payments") {
                        $accbPymntsDfltBalsAcntID = get_DfltCstmrRcvblsCashAcnt($accbPymntsSpplrID, $orgID);
                        $accbPymntsIncrsDcrs2 = "Increase";
                    } else {
                        $accbPymntsDfltBalsAcntID = get_DfltSplrPyblsCashAcnt($accbPymntsSpplrID, $orgID);
                        $accbPymntsIncrsDcrs2 = "Increase";
                    }
                    $accbPymntsAccntID1 = $accbPymntsDfltBalsAcntID;
                    $accbPymntsDfltBalsAcnt = getAccntNum($accbPymntsDfltBalsAcntID) . "." . getAccntName($accbPymntsDfltBalsAcntID);
                    $accbPymntsInvcCurID = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $accbPymntsDfltBalsAcntID);
                    $accbPymntsInvcCur = getPssblValNm($accbPymntsInvcCurID);

                    $accbPymntsFuncCurID = $fnccurid;
                    $accbPymntsFuncCur = $fnccurnm;
                    $accbPymntsFuncCurRate = 1.0000;
                    $accbPymntsFuncCurAmnt = 1.0000;
                    $accbPymntsAcntCurID = $accbPymntsInvcCurID;
                    $accbPymntsAcntCur = $accbPymntsInvcCur;
                    $accbPymntsAcntCurRate = 1.0000;
                    $accbPymntsAcntCurAmnt = 1.0000;
                    $accbPymntsIsPstd = "0";
                    $accbPymntsChqName = "";
                    $accbPymntsChqNumber = "";
                    $accbPymntsSignCode = "";
                    createPymntsBatch(
                        $orgID,
                        $accbPymntsTrnsStrtDte,
                        $accbPymntsTrnsEndDte,
                        $accbPymntsDocType,
                        $gnrtdTrnsNo,
                        $accbPymntsDesc,
                        $accbPymntsSpplrID,
                        $accbPymntsPayMthdID,
                        $accbPymntsVchType,
                        $voidedAccbPymntsID,
                        $accbPymntsVldtyStatus,
                        $accbPymntsDocTmplt,
                        $rqStatus,
                        $accbPymntsDfltTrnsDte,
                        $accbPymntsIncrsDcrs1,
                        $accbPymntsAccntID1,
                        $accbPymntsIncrsDcrs2,
                        $accbPymntsAccntID2,
                        $accbPymntsGvnAmnt,
                        $accbPymntsPaidAmnt,
                        $accbPymntsChngBals,
                        $accbPymntsInvcCurID,
                        $accbPymntsFuncCurID,
                        $accbPymntsFuncCurRate,
                        $accbPymntsFuncCurAmnt,
                        $accbPymntsAcntCurID,
                        $accbPymntsAcntCurRate,
                        $accbPymntsAcntCurAmnt,
                        $accbPymntsChqName,
                        $accbPymntsChqNumber,
                        $accbPymntsSignCode
                    );
                    $sbmtdAccbPymntsID = getGnrlRecID("accb.accb_payments_batches", "pymnt_batch_name", "pymnt_batch_id", $gnrtdTrnsNo, $orgID);
                }

                $reportName = "Payment Advice";
                $reportTitle = "Payment Advice";
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdAccbPymntsID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);

                $reportTitle1 = "Bulk Invoice Payments";
                $reportName1 = "Bulk Invoice Payments";
                $rptID1 = getRptID($reportName1);
                $prmID11 = getParamIDUseSQLRep("{:p_batch_id}", $rptID1);
                $paramRepsNVals1 = $prmID11 . "~" . $sbmtdAccbPymntsID . "|-130~" . $reportTitle1 . "|-190~HTML";
                $paramStr1 = urlencode($paramRepsNVals1);

                $reportTitle2 = "Void Bulk Invoice Payments";
                $reportName2 = "Void Bulk Invoice Payments";
                $rptID2 = getRptID($reportName2);
                $prmID22 = getParamIDUseSQLRep("{:p_batch_id}", $rptID2);
                $paramRepsNVals2 = $prmID22 . "~" . $sbmtdAccbPymntsID . "|-130~" . $reportTitle2 . "|-190~HTML";
                $paramStr2 = urlencode($paramRepsNVals2);
                ?>
                <form class="form-horizontal" id="oneAccbPymntsEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Batch No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdAccbPymntsID" name="sbmtdAccbPymntsID" value="<?php echo $sbmtdAccbPymntsID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="accbPymntsBatchNum" name="accbPymntsBatchNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Start Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control rqrdFld" size="16" type="text" id="accbPymntsTrnsStrtDte" name="accbPymntsTrnsStrtDte" value="<?php echo $accbPymntsTrnsStrtDte; ?>" placeholder="From Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">End Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control rqrdFld" size="16" type="text" id="accbPymntsTrnsEndDte" name="accbPymntsTrnsEndDte" value="<?php echo $accbPymntsTrnsEndDte; ?>" placeholder="To Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="accbPymntsDocType" style="width:100% !important;">
                                            <?php
                                            $valslctdArry = array("");
                                            $srchInsArrys = array("");
                                            if ($accbPymntsVchType == "Supplier Payments") {
                                                $valslctdArry = array("", "", "", "", "", "");
                                                $srchInsArrys = array(
                                                    "Supplier Standard Payment",
                                                    "Supplier Advance Payment",
                                                    "Direct Refund from Supplier",
                                                    "Supplier Credit Memo (InDirect Refund)",
                                                    "Direct Topup for Supplier",
                                                    "Supplier Debit Memo (InDirect Topup)"
                                                );
                                            } else {
                                                $valslctdArry = array("", "", "", "", "", "");
                                                $srchInsArrys = array(
                                                    "Customer Standard Payment",
                                                    "Customer Advance Payment",
                                                    "Direct Topup from Customer",
                                                    "Direct Refund to Customer",
                                                    "Customer Credit Memo (InDirect Topup)",
                                                    "Customer Debit Memo (InDirect Refund)"
                                                );
                                            }
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($accbPymntsDocType == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="accbPymntsPayMthd" class="control-label col-md-4">Payment Method:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="accbPymntsPayMthd" name="accbPymntsPayMthd" value="<?php echo $accbPymntsPayMthd; ?>" readonly="true">
                                            <input type="hidden" id="accbPymntsPayMthdID" value="<?php echo $accbPymntsPayMthdID; ?>">
                                            <input type="hidden" id="accbPymntsMthdType" value="<?php echo $accbPymntsVchType; ?>">
                                            <!--<label class="btn btn-primary btn-file input-group-addon" onclick="" data-toggle="tooltip" title="Create New Payment Method">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>-->
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Methods', 'allOtherInputOrgID', 'accbPymntsMthdType', '', 'radio', true, '', 'accbPymntsPayMthdID', 'accbPymntsPayMthd', 'clear', 1, '');" data-toggle="tooltip" title="Existing Payment Method">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accbPymntsDocTmplt" class="control-label col-md-4">Doc. Template:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbPymntsDocTmplt" name="accbPymntsDocTmplt" value="<?php echo $accbPymntsDocTmplt; ?>" readonly="true">
                                            <input type="hidden" id="accbPymntsDocTmpltID" value="<?php echo $accbPymntsDocTmpltID; ?>">
                                            <!--<label class="btn btn-primary btn-file input-group-addon" onclick="" data-toggle="tooltip" title="Create New Document Template">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>-->
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Document Templates', 'allOtherInputOrgID', 'accbPymntsDocType', '', 'radio', true, '', 'accbPymntsDocTmpltID', 'accbPymntsDocTmplt', 'clear', 1, '');" data-toggle="tooltip" title="Existing Document Template">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accbPymntsSpplr" class="control-label col-md-4">Trade Partner:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="accbPymntsSpplr" name="accbPymntsSpplr" value="<?php echo $accbPymntsSpplr; ?>" readonly="true">
                                            <input type="hidden" id="accbPymntsSpplrID" value="<?php echo $accbPymntsSpplrID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbPymntsSpplrID', 'accbPymntsSpplr', 'clear', 1, '');" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Batch Source:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <button type="button" class="btn btn-default" style="width:100% !important;">
                                            <span style="color:blue;font-weight: bold;">
                                                <?php
                                                $style3 = "red";
                                                if ($accbPymntsVldtyStatus == "VALID") {
                                                    $style3 = "green";
                                                }
                                                echo $accbPymntsVchType . "&nbsp;<span style=\"color:" . $style3 . "\">[" . $accbPymntsVldtyStatus . "]</span>";
                                                ?>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group" style="width:100%;">
                                            <textarea class="form-control rqrdFld" rows="3" cols="20" id="accbPymntsDesc" name="accbPymntsDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $accbPymntsDesc; ?></textarea>
                                            <input class="form-control" type="hidden" id="accbPymntsDesc1" value="<?php echo $accbPymntsDesc; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbPymntsDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accbPymntsGLBatch" class="control-label col-md-4">GL Batch Name:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="accbPymntsGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" placeholder="" value="<?php echo $accbPymntsGLBatch; ?>" readonly="true" />
                                            <input type="hidden" id="accbPymntsGLBatchID" value="<?php echo $accbPymntsGLBatchID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $accbPymntsGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdAccbPymntsID; ?>, 'Payment Batch');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Status:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <button type="button" class="btn btn-default" style="width:100% !important;" id="myAccbPymntsStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;">
                                                <?php
                                                $style3 = "red";
                                                if ($accbPymntsIsPstd == "1") {
                                                    $style3 = "green";
                                                }
                                                echo $rqStatus . "&nbsp;<span style=\"color:" . $style3 . "\">[" . ($accbPymntsIsPstd == "1" ? "Posted" : "Not Posted") . "]</span>";
                                                //echo $rqStatus . ($accbPymntsIsPstd == "1" ? " [Posted]" : " [Not Posted]");
                                                ?>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxpymntsinvc" data-rhodata="" href="#pymntsInvcExtraInfo" id="pymntsInvcExtraInfotab">Payment Information</a></li>
                                    <li class=""><a data-toggle="tabajxpymntsinvc" data-rhodata="" href="#pymntsInvcSelected" id="pymntsInvcSelectedtab">Selected Invoices</a></li>
                                    <li class=""><a data-toggle="tabajxpymntsinvc" data-rhodata="" href="#pymntsInvcRemoved" id="pymntsInvcRemovedtab">Removed Invoices</a></li>
                                </ul>
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if (1 == 2) { ?>
                                                            <button id="addNwAccbPymntsRmvBtn" type="button" class="btn btn-default hideNotice" style="margin-bottom: 1px;height:30px;" onclick="" data-toggle="tooltip" data-placement="bottom" title="Remove Selected Lines">
                                                                <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Remove Selection
                                                            </button>
                                                            <button id="addNwAccbPymntsRstrBtn" type="button" class="btn btn-default hideNotice" style="margin-bottom: 1px;height:30px;" onclick="" data-toggle="tooltip" data-placement="bottom" title="Restore Selected Lines">
                                                                <img src="cmn_images/undo_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Restore Selection
                                                            </button>
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPymntsForm(<?php echo $sbmtdAccbPymntsID; ?>, 1, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total Selected: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdPymntsValsTtlBtn"><?php echo $accbPymntsInvcCur; ?>
                                                                <?php
                                                                echo number_format($accbPymntsTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdPymntsValsTtlVal" value="<?php echo $accbPymntsTtlAmnt; ?>">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total Removed: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdPymntsRmvdValsTtlBtn"><?php echo $accbPymntsInvcCur; ?>
                                                                <?php
                                                                echo number_format($accbPymntsRmvdTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdPymntsRmvdValsTtlVal" value="<?php echo $accbPymntsRmvdTtlAmnt; ?>">
                                                        </button>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                                            <?php
                                                            if ($rqStatus == "Unprocessed") {
                                                            ?>
                                                                <?php if ($canEdt) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPymntsForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                <?php } ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPymntsForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>');" data-toggle="tooltip" data-placement="bottom" title="Run Payment Batch">
                                                                    <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Run Payment Batch
                                                                </button>
                                                            <?php
                                                            } else if ($rqStatus == "Processed") {
                                                            ?>
                                                                <button id="fnlzeRvrslAccbPymntsBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbPymntsRvrslForm('<?php echo $fnccurnm; ?>', 1,<?php echo $rptID2; ?>, -1, '<?php echo $paramStr2; ?>');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Payment Batch&nbsp;</button>
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
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbPymntsLnsTblSctn">
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="pymntsInvcExtraInfo" class="tab-pane fadein active" style="border:none !important;padding:0px 15px 0px 15px !important;">
                                            <div class="row">
                                                <div class="col-md-4" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 2px !important;margin-left: -2px !important;">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Payment Date:</label>
                                                        </div>
                                                        <div class="col-md-8 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 15px 0px 15px !important;">
                                                            <input class="form-control" size="16" type="text" id="accbPymntsDfltTrnsDte" name="accbPymntsDfltTrnsDte" value="<?php echo $accbPymntsDfltTrnsDte; ?>" placeholder="Payment Date" readonly="true">
                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Amount Given:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $accbPymntsInvcCur; ?>', 'accbPymntsInvcCur', '', 'clear', 0, '', function () {
                                                                                            $('#accbPymntsInvcCur1').html($('#accbPymntsInvcCur').val());
                                                                                            $('#accbPymntsInvcCur2').html($('#accbPymntsInvcCur').val());
                                                                                            $('#accbPymntsInvcCur3').html($('#accbPymntsInvcCur').val());
                                                                                        });">
                                                                    <span class="" style="font-size: 20px !important;" id="accbPymntsInvcCur1"><?php echo $accbPymntsInvcCur; ?></span>
                                                                </label>
                                                                <input type="hidden" id="accbPymntsInvcCur" value="<?php echo $accbPymntsInvcCur; ?>">
                                                                <input class="form-control rqrdFld" type="text" id="accbPymntsGvnAmnt" value="<?php
                                                                                                                                                echo number_format($accbPymntsGvnAmnt, 2);
                                                                                                                                                ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbPymntsGvnAmnt');" <?php echo $mkReadOnly; ?> />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Amount To Pay:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                                    <span class="" style="font-size: 20px !important;" id="accbPymntsInvcCur2"><?php echo $accbPymntsInvcCur; ?></span>
                                                                </label>
                                                                <input class="form-control" type="text" id="accbPymntsPaidAmnt" value="<?php
                                                                                                                                        echo number_format($accbPymntsPaidAmnt, 2);
                                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbPymntsPaidAmnt');" readonly="true" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Balance:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                                    <span class="" style="font-size: 20px !important;" id="accbPymntsInvcCur3"><?php echo $accbPymntsInvcCur; ?></span>
                                                                </label>
                                                                <input class="form-control" type="text" id="accbPymntsChngBals" value="<?php
                                                                                                                                        echo number_format($accbPymntsChngBals, 2);
                                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbPymntsChngBals');" readonly="true" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 2px !important;">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Cheque/Card:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" data-toggle="tooltip" title="Cheque/Card Owner's Name" class="form-control rqrdFld" aria-label="..." id="accbPymntsChqName" name="accbPymntsChqName" value="<?php echo $accbPymntsChqName; ?>" <?php echo $mkReadOnly; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Number:</label>
                                                        </div>
                                                        <div class="col-md-5" style="padding: 0px 2px 0px 15px !important;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." data-toggle="tooltip" title="Cheque/Card Number" id="accbPymntsChqNumber" name="accbPymntsChqNumber" value="<?php echo $accbPymntsChqNumber; ?>" <?php echo $mkReadOnly; ?>>
                                                        </div>
                                                        <div class="col-md-3" style="padding: 0px 15px 0px 2px !important;">
                                                            <input class="form-control rqrdFld" type="text" data-toggle="tooltip" title="Sign Code (CCV)" id="accbPymntsSignCode" value="<?php echo $accbPymntsSignCode; ?>" style="width:100%;" <?php echo $mkReadOnly; ?> />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Func. Curr.:</label>
                                                        </div>
                                                        <div class="col-md-4" style="padding: 0px 2px 0px 15px !important;">
                                                            <div class="input-group">
                                                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                                    <span class="" style="" id="accbPymntsInvcCur4"><?php echo $accbPymntsFuncCur; ?></span>
                                                                </label>
                                                                <input class="form-control" type="text" id="accbPymntsFuncCurRate" data-toggle="tooltip" title="Rate" value="<?php
                                                                                                                                                                                echo number_format($accbPymntsFuncCurRate, 4);
                                                                                                                                                                                ?>" style="width:100%;" onchange="fmtAsNumber('accbPymntsFuncCurRate');" readonly="true" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="padding: 0px 15px 0px 2px !important;">
                                                            <input class="form-control" type="text" data-toggle="tooltip" title="Amount" id="accbPymntsFuncCurAmnt" value="<?php echo $accbPymntsFuncCurAmnt; ?>" style="width:100%;" readonly="true" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Accnt. Curr.:</label>
                                                        </div>
                                                        <div class="col-md-4" style="padding: 0px 2px 0px 15px !important;">
                                                            <div class="input-group">
                                                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                                    <span class="" style="" id="accbPymntsInvcCur5"><?php echo $accbPymntsAcntCur; ?></span>
                                                                </label>
                                                                <input class="form-control" type="text" id="accbPymntsAcntCurRate" data-toggle="tooltip" title="Rate" value="<?php
                                                                                                                                                                                echo number_format($accbPymntsAcntCurRate, 4);
                                                                                                                                                                                ?>" style="width:100%;" onchange="fmtAsNumber('accbPymntsAcntCurRate');" readonly="true" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="padding: 0px 15px 0px 2px !important;">
                                                            <input class="form-control" type="text" id="accbPymntsAcntCurAmnt" data-toggle="tooltip" title="Amount" value="<?php
                                                                                                                                                                            echo number_format($accbPymntsAcntCurAmnt, 2);
                                                                                                                                                                            ?>" style="width:100%;" readonly="true" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-left: 0px !important;margin-right: -2px !important;">
                                                    <div class="form-group">
                                                        <label for="accbPymntsIncrsDcrs1" class="control-label col-md-4">Incrs./ Dcrs.:</label>
                                                        <div class="col-md-8">
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbPymntsIncrsDcrs1" style="width:100% !important;">
                                                                <?php
                                                                $valslctdArry = array("", "");
                                                                $srchInsArrys = array("Increase", "Decrease");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($accbPymntsIncrsDcrs1 == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="accbPymntsAccntNm1" class="control-label col-md-4">Clearing Acc.:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="accbPymntsAccntNm1" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $accbPymntsAccntNm1; ?>" readonly="true" />
                                                            <input type="hidden" id="accbPymntsAccntID1" value="<?php echo $accbPymntsAccntID1; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="accbPymntsIncrsDcrs2" class="control-label col-md-4">Incrs./ Dcrs.:</label>
                                                        <div class="col-md-8">
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbPymntsIncrsDcrs2" style="width:100% !important;">
                                                                <?php
                                                                $valslctdArry = array("", "");
                                                                $srchInsArrys = array("Increase", "Decrease");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($accbPymntsIncrsDcrs2 == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="accbPymntsAccntNm2" class="control-label col-md-4">Charge Acc.:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="accbPymntsAccntNm2" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $accbPymntsAccntNm2; ?>" readonly="true" />
                                                            <input type="hidden" id="accbPymntsAccntID2" value="<?php echo $accbPymntsAccntID2; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="pymntsInvcSelected" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneAccbPymntsSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="">No.</th>
                                                                <th style="min-width:170px;">Source Doc. Number/Type</th>
                                                                <th style="min-width:250px;">Payment Remark/Comment</th>
                                                                <th style="">CUR.</th>
                                                                <th style="text-align: right;">Amount Given</th>
                                                                <th style="text-align: right;">Amount Paid</th>
                                                                <th style="text-align: right;">Change/ Balance</th>
                                                                <th style="">Applied Prepayment Doc. No.</th>
                                                                <th style="min-width:180px;">GL Batch Name</th>
                                                                <th style="">&nbsp;</th>
                                                                <?php
                                                                if ($canDel === true) {
                                                                ?>
                                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                <?php } ?>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_PymntBatchLns(0, 1000000, $sbmtdAccbPymntsID, '0');
                                                            $ttlTrsctnAmntGvn = 0;
                                                            $ttlTrsctnAmntPaid = 0;
                                                            $ttlTrsctnAmntBals = 0;
                                                            $trnsBrkDwnVType = "VIEW";

                                                            if ($mkReadOnly == "") {
                                                                $trnsBrkDwnVType = "EDIT";
                                                            }

                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                $trsctnLineID = (float) $rowRw[0];
                                                                $trsctnLineSrcDocType = $rowRw[5];
                                                                $trsctnLineSrcDocNum = $rowRw[7];
                                                                $trsctnLineDesc = $rowRw[4];
                                                                $trsctnLineAmntGvn = $rowRw[29];
                                                                $trsctnLineAmntPaid = $rowRw[2];
                                                                $trsctnLineBals = $rowRw[3];
                                                                $entrdCurID = (int) $rowRw[17];
                                                                $entrdCurNm = $rowRw[18];
                                                                $trsctnLineApldDocNo = $rowRw[30];
                                                                $trsctnLineGlBtchNm = $rowRw[14];
                                                                $trsctnLineGlBtchID = (float) $rowRw[13];
                                                                $ttlTrsctnAmntGvn = $ttlTrsctnAmntGvn + $trsctnLineAmntGvn;
                                                                $ttlTrsctnAmntPaid = $ttlTrsctnAmntPaid + $trsctnLineAmntPaid;
                                                                $ttlTrsctnAmntBals = $ttlTrsctnAmntBals + $trsctnLineBals;
                                                                $cntr += 1;
                                                            ?>
                                                                <tr id="oneAccbPymntsSmryRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd">
                                                                        <span><?php echo ($cntr); ?></span>
                                                                        <input type="checkbox" name="oneAccbPymntsSmryRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $trsctnLineID . ";" . $trsctnLineSrcDocNum; ?>">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo ($trsctnLineSrcDocType . "<br/>" . $trsctnLineSrcDocNum); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                        <div class="input-group" style="width:100%;">
                                                                            <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAccbPymntsSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbPymntsSmryRow<?php echo $cntr; ?>_LineDesc', 'oneAccbPymntsSmryLinesTable', 'jbDetDesc');">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('oneAccbPymntsSmryRow<?php echo $cntr; ?>_LineDesc');" style="max-width:30px;width:30px;">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="lovtd" style="max-width:35px;width:35px;">
                                                                        <div class="" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="">
                                                                                <span class="" id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_AmtGvn" name="oneAccbPymntsSmryRow<?php echo $cntr; ?>_AmtGvn" value="<?php
                                                                                                                                                                                                                                                                        echo number_format($trsctnLineAmntGvn, 2);
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPymntsSmryRow<?php echo $cntr; ?>_AmtGvn', 'oneAccbPymntsSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbPymntsSmryTtl();">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_AmtPaid" name="oneAccbPymntsSmryRow<?php echo $cntr; ?>_AmtPaid" value="<?php
                                                                                                                                                                                                                                                        echo number_format($trsctnLineAmntPaid, 2);
                                                                                                                                                                                                                                                        ?>" style="width:100% !important;text-align: right;" readonly="true">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_ChngBals" name="oneAccbPymntsSmryRow<?php echo $cntr; ?>_ChngBals" value="<?php
                                                                                                                                                                                                                                                        echo number_format($trsctnLineBals, 2);
                                                                                                                                                                                                                                                        ?>" style="width:100% !important;text-align: right;" readonly="true">
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <span><?php echo ($trsctnLineApldDocNo); ?></span>
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_ApldDocNum" name="oneAccbPymntsSmryRow<?php echo $cntr; ?>_ApldDocNum" value="<?php
                                                                                                                                                                                                                                                                echo $trsctnLineApldDocNo;
                                                                                                                                                                                                                                                                ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllAccbPymntsSmryTtl();">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input class="form-control" id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_GlBtchNm" style="font-size: 13px !important;font-weight: bold !important;width:100% !important;" placeholder="" type="text" value="<?php echo $trsctnLineGlBtchNm; ?>" readonly="true" />
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $trsctnLineGlBtchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdAccbPymntsID; ?>, 'Payment Batch');">
                                                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="rmvRstrAccbPymntsDetLn('oneAccbPymntsSmryRow_<?php echo $cntr; ?>', '1');" data-toggle="tooltip" data-placement="bottom" title="Remove Payment Document">
                                                                            <img src="cmn_images/delete.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <?php
                                                                    if ($canDel === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Permanently Delete Payment" onclick="delAccbPymntsDetLn('oneAccbPymntsSmryRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($rowRw[0] . "|accb.accb_payments|pymnt_id"), $smplTokenWord1));
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
                                                                <th>&nbsp;</th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTGvnSmryAmtTtlBtn\">" . number_format(
                                                                        $ttlTrsctnAmntGvn,
                                                                        2,
                                                                        '.',
                                                                        ','
                                                                    ) . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdPMTGvnSmryAmtTtlVal" value="<?php echo $ttlTrsctnAmntGvn; ?>">
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTMdeSmryAmtTtlBtn\">" . number_format(
                                                                        $ttlTrsctnAmntPaid,
                                                                        2,
                                                                        '.',
                                                                        ','
                                                                    ) . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdPMTMdeSmryAmtTtlVal" value="<?php echo $ttlTrsctnAmntPaid; ?>">
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTBalsSmryAmtTtlBtn\">" . number_format(
                                                                        $ttlTrsctnAmntBals,
                                                                        2,
                                                                        '.',
                                                                        ','
                                                                    ) . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdPMTBalsSmryAmtTtlVal" value="<?php echo $ttlTrsctnAmntBals; ?>">
                                                                </th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                                <?php
                                                                if ($canDel === true) {
                                                                ?>
                                                                    <th style="">&nbsp;</th>
                                                                <?php } ?>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th style="">&nbsp;</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="pymntsInvcRemoved" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneAccbPymntsRmvdLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="">No.</th>
                                                                <th style="min-width:110px;">Source Doc. Number/Type</th>
                                                                <th style="min-width:250px;">Payment Remark/Comment</th>
                                                                <th style="">CUR.</th>
                                                                <th style="text-align: right;">Amount Given</th>
                                                                <th style="text-align: right;">Amount Paid</th>
                                                                <th style="text-align: right;">Change/ Balance</th>
                                                                <th style="">Applied Prepayment Doc. No.</th>
                                                                <th style="">GL Batch Name</th>
                                                                <th style="">&nbsp;</th>
                                                                <?php
                                                                if ($canDel === true) {
                                                                ?>
                                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                <?php } ?>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_PymntBatchLns(0, 1000000, $sbmtdAccbPymntsID, '1');
                                                            $ttlTrsctnAmntGvn = 0;
                                                            $ttlTrsctnAmntPaid = 0;
                                                            $ttlTrsctnAmntBals = 0;
                                                            $trnsBrkDwnVType = "VIEW";

                                                            if ($mkReadOnly == "") {
                                                                $trnsBrkDwnVType = "EDIT";
                                                            }

                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                $trsctnLineID = (float) $rowRw[0];
                                                                $trsctnLineSrcDocType = $rowRw[5];
                                                                $trsctnLineSrcDocNum = $rowRw[7];
                                                                $trsctnLineDesc = $rowRw[4];
                                                                $trsctnLineAmntGvn = $rowRw[29];
                                                                $trsctnLineAmntPaid = $rowRw[2];
                                                                $trsctnLineBals = $rowRw[3];
                                                                $entrdCurID = (int) $rowRw[17];
                                                                $entrdCurNm = $rowRw[18];
                                                                $trsctnLineApldDocNo = $rowRw[30];
                                                                $ttlTrsctnAmntGvn = $ttlTrsctnAmntGvn + $trsctnLineAmntGvn;
                                                                $ttlTrsctnAmntPaid = $ttlTrsctnAmntPaid + $trsctnLineAmntPaid;
                                                                $ttlTrsctnAmntBals = $ttlTrsctnAmntBals + $trsctnLineBals;
                                                                $cntr += 1;
                                                            ?>
                                                                <tr id="oneAccbPymntsRmvdRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd">
                                                                        <span><?php echo ($cntr); ?></span>
                                                                        <input type="checkbox" name="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $trsctnLineID . ";" . $trsctnLineSrcDocNum; ?>">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo ($trsctnLineSrcDocType . "<br/>" . $trsctnLineSrcDocNum); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                        <span><?php echo ($trsctnLineDesc); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="max-width:35px;width:35px;">
                                                                        <div class="" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="">
                                                                                <span class="" id="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_AmtGvn" name="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_AmtGvn" value="<?php
                                                                                                                                                                                                                                                                echo number_format($trsctnLineAmntGvn, 2);
                                                                                                                                                                                                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneAccbPymntsRmvdRow<?php echo $cntr; ?>_AmtGvn', 'oneAccbPymntsRmvdLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_AmtPaid" name="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_AmtPaid" value="<?php
                                                                                                                                                                                                                                                        echo number_format($trsctnLineAmntPaid, 2);
                                                                                                                                                                                                                                                        ?>" style="width:100% !important;text-align: right;" readonly="true">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_ChngBals" name="oneAccbPymntsRmvdRow<?php echo $cntr; ?>_ChngBals" value="<?php
                                                                                                                                                                                                                                                        echo number_format($trsctnLineBals, 2);
                                                                                                                                                                                                                                                        ?>" style="width:100% !important;text-align: right;" readonly="true">
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <span><?php echo ($trsctnLineApldDocNo); ?></span>
                                                                    </td>
                                                                    <td style="">&nbsp;</td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="rmvRstrAccbPymntsDetLn('oneAccbPymntsRmvdRow_<?php echo $cntr; ?>', '0');" data-toggle="tooltip" data-placement="bottom" title="Restore Payment Document">
                                                                            <img src="cmn_images/undo_256.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">&nbsp;Restore
                                                                        </button>
                                                                    </td>
                                                                    <?php
                                                                    if ($canDel === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Permanently Delete Payment" onclick="delAccbPymntsDetLn('oneAccbPymntsRmvdRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($rowRw[0] . "|accb.accb_payments|pymnt_id"), $smplTokenWord1));
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
                                                                <th>&nbsp;</th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTGvnRmvdAmtTtlBtn\">" . number_format(
                                                                        $ttlTrsctnAmntGvn,
                                                                        2,
                                                                        '.',
                                                                        ','
                                                                    ) . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdPMTGvnRmvdAmtTtlVal" value="<?php echo $ttlTrsctnAmntGvn; ?>">
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTMdeRmvdAmtTtlBtn\">" . number_format(
                                                                        $ttlTrsctnAmntPaid,
                                                                        2,
                                                                        '.',
                                                                        ','
                                                                    ) . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdPMTMdeRmvdAmtTtlVal" value="<?php echo $ttlTrsctnAmntPaid; ?>">
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTBalsRmvdAmtTtlBtn\">" . number_format(
                                                                        $ttlTrsctnAmntBals,
                                                                        2,
                                                                        '.',
                                                                        ','
                                                                    ) . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdPMTBalsRmvdAmtTtlVal" value="<?php echo $ttlTrsctnAmntBals; ?>">
                                                                </th>
                                                                <th style="">&nbsp;</th>
                                                                <td style="">&nbsp;</td>
                                                                <th style="">&nbsp;</th>
                                                                <?php
                                                                if ($canDel === true) {
                                                                ?>
                                                                    <th style="">&nbsp;</th>
                                                                <?php } ?>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th style="">&nbsp;</th>
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
            <?php
            } else if ($vwtyp == 101) {
                $sbmtdAccbPymntsID = isset($_POST['sbmtdAccbPymntsID']) ? cleanInputData($_POST['sbmtdAccbPymntsID']) : -1;
                $sbmtdDocumentID = isset($_POST['sbmtdDocumentID']) ? cleanInputData($_POST['sbmtdDocumentID']) : -1;
                $sbmtdExtraPKeyID = isset($_POST['sbmtdExtraPKeyID']) ? (float) cleanInputData($_POST['sbmtdExtraPKeyID']) : -1;
                $sbmtdExtraPKeyType = isset($_POST['sbmtdExtraPKeyType']) ? cleanInputData($_POST['sbmtdExtraPKeyType']) : "";
                $accbPymntsInvcSpplrID = isset($_POST['accbPymntsInvcSpplrID']) ? cleanInputData($_POST['accbPymntsInvcSpplrID']) : -1;
                $accbPymntsInvcCur = isset($_POST['accbPymntsInvcCur']) ? cleanInputData($_POST['accbPymntsInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $accbPymntsInvcCurID = getPssblValID($accbPymntsInvcCur, $curLovID);
                $accbPymntsInvcSpplrNm = "";
                $dfltAmountTndrd = isset($_POST['dfltAmountTndrd']) ? (float) cleanInputData($_POST['dfltAmountTndrd']) : 0;
                $accbPymntsVchType = isset($_POST['sbmtdDocumentType']) ? cleanInputData($_POST['sbmtdDocumentType']) : "Supplier Payments";
                if (!$canAdd || ($sbmtdAccbPymntsID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                if ($accbPymntsVchType == "Supplier Payments" && !$canPayPyblsDocs) {
                    restricted();
                    exit();
                }
                if ($accbPymntsVchType == "Customer Payments" && !$canPayRcvblsDocs) {
                    restricted();
                    exit();
                }

                $accbPymntsDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $accbPymntsDocType = "Supplier Standard Payment";
                $accbPymntsVldtyStatus = "VALID";
                $accbPymntsIncrsDcrs1 = "Decrease";
                $accbPymntsAccntID1 = -1;
                $accbPymntsAccntNm1 = "";
                $accbPymntsIncrsDcrs2 = "Decrease";
                $accbPymntsAccntID2 = -1;
                $accbPymntsAccntNm2 = "";
                $gnrtdTrnsNo = "";
                $accbPymntsDesc = "";

                $srcPymntsDocNum = "";

                $rqStatus = "Unprocessed";
                $rqstatusColor = "red";

                $accbPymntsPayMthd = "";
                $accbPymntsPayMthdID = -1;
                $accbPymntsTtlAmnt = 0;
                $accbPymntsAppldAmnt = 0;
                $accbPymntsGvnAmnt = 0;
                $accbPymntsPaidAmnt = 0;
                $accbPymntsChngBals = 0;
                $accbPymntsGLBatch = "";
                $accbPymntsGLBatchID = -1;
                $accbPymntsDfltBalsAcntID = -1;
                $accbPymntsDfltBalsAcnt = "";
                $accbPymntsFuncCurID = $fnccurid;
                $accbPymntsFuncCur = $fnccurnm;
                $accbPymntsFuncCurRate = 1.0000;
                $accbPymntsFuncCurAmnt = 1.0000;
                $accbPymntsAcntCurID = $fnccurid;
                $accbPymntsAcntCur = $fnccurnm;
                $accbPymntsAcntCurRate = 1.0000;
                $accbPymntsAcntCurAmnt = 1.0000;
                $accbPymntsIsPstd = "0";
                $accbPymntsChqName = "";
                $accbPymntsChqNumber = "";
                $accbPymntsSignCode = "";
                $accbPymntsExpiryDate = "";
                $accbPymntsPrepayDocID = "";
                $accbPymntsPrepayDocNum = "";
                $accbPymntsPrepayDocLovNm = "Customer Prepayments";
                if ($accbPymntsVchType == "Supplier Payments") {
                    $accbPymntsPrepayDocLovNm = "Supplier Prepayments";
                }
                $mkReadOnly = "";
                $accbPymntsPrepayFrmGroupStyle = "";

                if ($sbmtdAccbPymntsID <= 0) {
                    if ($accbPymntsVchType == "Supplier Payments") {
                        if ($sbmtdDocumentID > 0) {
                            $result = get_One_PyblsInvcDocHdr($sbmtdDocumentID);
                            while ($row = loc_db_fetch_array($result)) {
                                $accbPymntsDocType = $row[5];
                                $accbPymntsPayMthd = $row[18];
                                if (strpos($accbPymntsPayMthd, "Prepayment") === FALSE) {
                                    $accbPymntsPrepayFrmGroupStyle = "hideNotice";
                                }
                                $accbPymntsPayMthdID = (int) $row[17];
                                if ($accbPymntsPayMthdID <= 0) {
                                    $accbPymntsPayMthdID = get_DfltPayMthdID($orgID, "Supplier", "Cheque");
                                    $accbPymntsPayMthd = get_DfltPayMthdNm($orgID, "Supplier", "Cheque");
                                }
                                $accbPymntsIncrsDcrs1 = "Decrease";
                                $accbPymntsAccntID1 = (int) $row[29];
                                $accbPymntsAccntNm1 = $row[30];
                                $accbPymntsIncrsDcrs2 = "Decrease";
                                $accbPymntsAccntID2 = (int) getGnrlRecNm("accb.accb_paymnt_mthds", "paymnt_mthd_id", "current_asst_acnt_id", $accbPymntsPayMthdID);
                                $accbPymntsAccntNm2 = getGnrlRecNm(
                                    "accb.accb_paymnt_mthds",
                                    "paymnt_mthd_id",
                                    "accb.get_accnt_num(current_asst_acnt_id) || '.' || accb.get_accnt_name(current_asst_acnt_id)",
                                    $accbPymntsPayMthdID
                                );
                                $accbPymntsInvcSpplrID = (float) $row[8];
                                $accbPymntsDesc = "Payment of Invoice No.:" . $row[4] . " (" . $row[6] . ") by " . $row[9];
                                $srcPymntsDocNum = $row[4];
                                $accbPymntsTtlAmnt = (float) $row[14];
                                $accbPymntsGvnAmnt = $dfltAmountTndrd;
                                $accbPymntsPaidAmnt = (float) $row[14] - (float) $row[19];
                                $accbPymntsChngBals = $accbPymntsTtlAmnt - $accbPymntsGvnAmnt;
                                $accbPymntsInvcCurID = (int) $row[24];
                                $accbPymntsInvcCur = $row[25];
                                $accbPymntsIsPstd = "0";
                                $accbPymntsChqName = getOrgName($orgID);
                                /* if ((strpos($accbPymntsPayMthd, "Check") >= 0 || strpos($accbPymntsPayMthd, "Cheque") >= 0)) {
                                  $accbPymntsChqName = getOrgName($orgID);
                                  } */
                            }
                        } else if ($accbPymntsInvcSpplrID > 0) {
                            $accbPymntsTtlAmnt = 0;
                            $accbPymntsGvnAmnt = $dfltAmountTndrd;
                            $accbPymntsChngBals = $accbPymntsTtlAmnt - $accbPymntsGvnAmnt;
                            $accbPymntsInvcSpplrNm = getCstmrSpplrName($accbPymntsInvcSpplrID);
                            $accbPymntsDesc = "Advance Payment to Supplier " . $accbPymntsInvcSpplrNm;
                        }
                    } else {
                        if ($sbmtdDocumentID > 0) {
                            $result = get_One_RcvblsInvcDocHdr($sbmtdDocumentID);
                            while ($row = loc_db_fetch_array($result)) {
                                $accbPymntsDocType = $row[5];
                                $accbPymntsPayMthd = $row[18];
                                if (strpos($accbPymntsPayMthd, "Prepayment") === FALSE) {
                                    $accbPymntsPrepayFrmGroupStyle = "hideNotice";
                                }
                                $accbPymntsPayMthdID = (int) $row[17];
                                if ($accbPymntsPayMthdID <= 0) {
                                    $accbPymntsPayMthdID = get_DfltPayMthdID($orgID, "Customer", "Cash");
                                    $accbPymntsPayMthd = get_DfltPayMthdNm($orgID, "Customer", "Cash");
                                }
                                $accbPymntsIncrsDcrs1 = "Decrease";
                                $accbPymntsAccntID1 = (int) $row[29];
                                $accbPymntsAccntNm1 = $row[30];
                                $accbPymntsIncrsDcrs2 = "Increase";
                                $accbPymntsAccntID2 = (int) getGnrlRecNm("accb.accb_paymnt_mthds", "paymnt_mthd_id", "current_asst_acnt_id", $accbPymntsPayMthdID);
                                $accbPymntsAccntNm2 = getGnrlRecNm(
                                    "accb.accb_paymnt_mthds",
                                    "paymnt_mthd_id",
                                    "accb.get_accnt_num(current_asst_acnt_id) || '.' || accb.get_accnt_name(current_asst_acnt_id)",
                                    $accbPymntsPayMthdID
                                );
                                $accbPymntsInvcSpplrID = (float) $row[8];
                                $accbPymntsDesc = "Payment of Invoice No.:" . $row[4] . " (" . $row[6] . ") by " . $row[9];
                                $srcPymntsDocNum = $row[4];
                                $accbPymntsTtlAmnt = (float) $row[14];
                                $accbPymntsGvnAmnt = $dfltAmountTndrd;
                                $accbPymntsPaidAmnt = (float) $row[14] - (float) $row[19];
                                $accbPymntsChngBals = $accbPymntsTtlAmnt - $accbPymntsGvnAmnt;
                                $accbPymntsInvcCurID = (int) $row[24];
                                $accbPymntsInvcCur = $row[25];
                                $accbPymntsIsPstd = "0";
                                $accbPymntsChqName = getCstmrSpplrName($accbPymntsInvcSpplrID);
                                if (trim($accbPymntsChqName) == "") {
                                    $accbPymntsChqName = getOrgName($orgID);
                                }
                                /* if ((strpos($accbPymntsPayMthd, "Check") >= 0 || strpos($accbPymntsPayMthd, "Cheque") >= 0)) {
                                  $accbPymntsChqName = getCstmrSpplrName($accbPymntsInvcSpplrID);
                                  } */
                            }
                        } else if ($accbPymntsInvcSpplrID > 0) {
                            $accbPymntsTtlAmnt = 0;
                            $accbPymntsGvnAmnt = $dfltAmountTndrd;
                            $accbPymntsChngBals = $accbPymntsTtlAmnt - $accbPymntsGvnAmnt;
                            $accbPymntsInvcSpplrNm = getCstmrSpplrName($accbPymntsInvcSpplrID);
                            $accbPymntsDesc = "Advance Payment from Customer " . $accbPymntsInvcSpplrNm;
                        }
                    }
                } else {
                    $mkReadOnly = "readonly=\"true\"";
                    $result = get_One_PymntLnDets($sbmtdAccbPymntsID);
                    while ($row = loc_db_fetch_array($result)) {
                        $accbPymntsDocType = $row[6];
                        $accbPymntsPayMthd = $row[2];
                        if (strpos($accbPymntsPayMthd, "Prepayment") === FALSE) {
                            $accbPymntsPrepayFrmGroupStyle = "hideNotice";
                        }
                        $accbPymntsDfltTrnsDte = $row[9];
                        $accbPymntsPayMthdID = (int) $row[1];
                        $accbPymntsIncrsDcrs1 = $row[10];
                        $accbPymntsAccntID1 = (int) $row[11];
                        $accbPymntsAccntNm1 = $row[12];
                        $accbPymntsIncrsDcrs2 = $row[13];
                        $accbPymntsAccntID2 = (int) $row[14];
                        $accbPymntsAccntNm2 = $row[15];
                        $accbPymntsDesc = "(REVERSAL) " . $row[5] . "";
                        $accbPymntsGLBatch = $row[17];
                        $accbPymntsGLBatchID = (float) $row[16];
                        $accbPymntsInvcSpplrID = (float) $row[42];
                        $srcPymntsDocNum = $row[8];
                        $accbPymntsTtlAmnt = 0;
                        $accbPymntsGvnAmnt = -1 * (float) $row[32];
                        $accbPymntsPaidAmnt = -1 * (float) $row[3];
                        $accbPymntsChngBals = -1 * (float) $row[4];
                        $accbPymntsInvcCurID = (int) $row[20];
                        $accbPymntsInvcCur = $row[21];
                        $accbPymntsIsPstd = "0";
                        $accbPymntsChqName = $row[36];
                        $accbPymntsChqNumber = $row[38];
                        $accbPymntsSignCode = $row[39];
                        $accbPymntsExpiryDate = $row[37];
                        $accbPymntsPrepayDocID = $row[33];
                        $accbPymntsPrepayDocNum = $row[34];
                    }
                }
            ?>
                <form class="form-horizontal" id='accbPymntsPayInvcForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="col-md-12" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-top: 2px !important;">
                            <div class="form-group">
                                <label for="accbPymntsPayMthdID" class="control-label col-md-4">Pay Method:</label>
                                <div class="col-md-8">
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="accbPymntsPayMthdID" style="width:100% !important;" onchange="accbPymntsPayMthdChng();">
                                        <?php
                                        $brghtStr = "";
                                        $isDynmyc = TRUE;
                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Payment Methods"), $isDynmyc, $orgID, $accbPymntsVchType, "");
                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                            $selectedTxt = "";
                                            if ($titleRow[0] == $accbPymntsPayMthdID) {
                                                $selectedTxt = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group <?php echo $accbPymntsPrepayFrmGroupStyle; ?>" id="accbPymntsPrepayFrmGroup">
                                <label for="accbPymntsPrepayDocNum" class="control-label col-md-4">Prepayment:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="accbPymntsPrepayDocNum" name="accbPymntsPrepayDocNum" value="<?php echo $accbPymntsPrepayDocNum; ?>" readonly="true">
                                        <input type="hidden" id="accbPymntsPrepayDocID" value="<?php echo $accbPymntsPrepayDocID; ?>">
                                        <input type="hidden" id="accbPymntsPrepayDocLovNm" value="<?php echo $accbPymntsPrepayDocLovNm; ?>">
                                        <input type="hidden" id="accbPymntsInvcSpplrID" value="<?php echo $accbPymntsInvcSpplrID; ?>">
                                        <input type="hidden" id="accbPymntsInvcCurID" value="<?php echo $accbPymntsInvcCurID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $accbPymntsPrepayDocLovNm; ?>', 'allOtherInputOrgID', 'accbPymntsInvcSpplrID', 'accbPymntsInvcCurID', 'check', true, '', 'accbPymntsPrepayDocID', 'accbPymntsPrepayDocNum', 'clear', 0, '', function () {
                                                                    afterPrepayDocSlctnQckInvPay();
                                                                });" data-toggle="tooltip" title="Select Advance Payment to Apply">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="accbPymntsDesc" class="control-label col-md-4">Remarks/ Narration:</label>
                                <div class="col-md-8">
                                    <input type="hidden" id="sbmtdAccbPymntsID" value="<?php echo $sbmtdAccbPymntsID; ?>">
                                    <input type="hidden" id="sbmtdDocumentID" value="<?php echo $sbmtdDocumentID; ?>">
                                    <input type="hidden" id="sbmtdDocumentType" value="<?php echo $accbPymntsVchType; ?>">
                                    <input type="hidden" id="sbmtdExtraPKeyID" value="<?php echo $sbmtdExtraPKeyID; ?>">
                                    <input type="hidden" id="sbmtdExtraPKeyType" value="<?php echo $sbmtdExtraPKeyType; ?>">
                                    <textarea class="form-control rqrdFld" rows="2" cols="20" id="accbPymntsDesc" name="accbPymntsDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;"><?php echo $accbPymntsDesc; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-top: 2px !important;">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Payment Date:</label>
                                </div>
                                <div class="col-md-8 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 15px 0px 15px !important;">
                                    <input class="form-control" size="16" type="text" id="accbPymntsDfltTrnsDte" name="accbPymntsDfltTrnsDte" value="<?php echo $accbPymntsDfltTrnsDte; ?>" placeholder="Payment Date" readonly="true">
                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Amount Given:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $accbPymntsInvcCur; ?>', 'accbPymntsInvcCur', '', 'clear', 0, '', function () {
                                                                    $('#accbPymntsInvcCur1').html($('#accbPymntsInvcCur').val());
                                                                    $('#accbPymntsInvcCur2').html($('#accbPymntsInvcCur').val());
                                                                    $('#accbPymntsInvcCur3').html($('#accbPymntsInvcCur').val());
                                                                    $('#accbPymntsInvcCur4').html($('#accbPymntsInvcCur').val());
                                                                    $('#accbPymntsInvcCur5').html($('#accbPymntsInvcCur').val());
                                                                });">
                                            <span class="" style="font-size: 20px !important;" id="accbPymntsInvcCur1"><?php echo $accbPymntsInvcCur; ?></span>
                                        </label>
                                        <input type="hidden" id="accbPymntsInvcCur" value="<?php echo $accbPymntsInvcCur; ?>">
                                        <input class="form-control rqrdFld" type="text" id="accbPymntsGvnAmnt" value="<?php
                                                                                                                        echo number_format($accbPymntsGvnAmnt, 2);
                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onkeyup="quickInvcPayAmntKeyFunc(event);" onchange="quickInvcPayAmntChng();" <?php echo $mkReadOnly; ?> />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Amount To Pay:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                            <span class="" style="font-size: 20px !important;" id="accbPymntsInvcCur2"><?php echo $accbPymntsInvcCur; ?></span>
                                        </label>
                                        <input class="form-control" type="text" id="accbPymntsPaidAmnt" value="<?php
                                                                                                                echo number_format($accbPymntsPaidAmnt, 2);
                                                                                                                ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbPymntsPaidAmnt');" readonly="true" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Balance:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                            <span class="" style="font-size: 20px !important;" id="accbPymntsInvcCur3"><?php echo $accbPymntsInvcCur; ?></span>
                                        </label>
                                        <input class="form-control" type="text" id="accbPymntsChngBals" value="<?php
                                                                                                                echo number_format($accbPymntsChngBals, 2);
                                                                                                                ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('accbPymntsChngBals');" readonly="true" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-top: 2px !important;">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Cheque/Card:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" data-toggle="tooltip" title="Cheque/Card Owner's Name" class="form-control" aria-label="..." id="accbPymntsChqName" name="accbPymntsChqName" value="<?php echo $accbPymntsChqName; ?>" <?php echo $mkReadOnly; ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Number:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" data-toggle="tooltip" title="Cheque/Card Number" class="form-control" aria-label="..." id="accbPymntsChqNumber" name="accbPymntsChqNumber" value="<?php echo $accbPymntsChqNumber; ?>" <?php echo $mkReadOnly; ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">(MM/YY)/CCV:</label>
                                </div>
                                <div class="col-md-5" style="padding: 0px 2px 0px 15px !important;">
                                    <input type="text" class="form-control" aria-label="..." data-toggle="tooltip" title="Card Expiry Date" id="accbPymntsExpiryDate" name="accbPymntsExpiryDate" value="<?php echo $accbPymntsExpiryDate; ?>" <?php echo $mkReadOnly; ?>>
                                </div>
                                <div class="col-md-3" style="padding: 0px 15px 0px 2px !important;">
                                    <input class="form-control" type="text" data-toggle="tooltip" title="Sign Code (CCV)" id="accbPymntsSignCode" value="<?php echo $accbPymntsSignCode; ?>" style="width:100%;" <?php echo $mkReadOnly; ?> />
                                </div>
                            </div>
                            <div class="form-group" style="display:none">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Func. Curr.:</label>
                                </div>
                                <div class="col-md-4" style="padding: 0px 2px 0px 15px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                            <span class="" style="" id="accbPymntsInvcCur4"><?php echo $accbPymntsInvcCur; ?></span>
                                        </label>
                                        <input class="form-control rqrdFld" type="text" id="accbPymntsFuncCurRate" data-toggle="tooltip" title="Rate" value="<?php
                                                                                                                                                                echo number_format($accbPymntsFuncCurRate, 4);
                                                                                                                                                                ?>" style="width:100%;" onchange="fmtAsNumber('accbPymntsFuncCurRate');" <?php echo $mkReadOnly; ?> />
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding: 0px 15px 0px 2px !important;">
                                    <input class="form-control" type="text" data-toggle="tooltip" title="Amount" id="accbPymntsFuncCurAmnt" value="<?php echo $accbPymntsFuncCurAmnt; ?>" style="width:100%;" readonly="true" />
                                </div>
                            </div>
                            <div class="form-group" style="display:none">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Accnt. Curr.:</label>
                                </div>
                                <div class="col-md-4" style="padding: 0px 2px 0px 15px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                            <span class="" style="" id="accbPymntsInvcCur5"><?php echo $accbPymntsInvcCur; ?></span>
                                        </label>
                                        <input class="form-control rqrdFld" type="text" id="accbPymntsAcntCurRate" data-toggle="tooltip" title="Rate" value="<?php
                                                                                                                                                                echo number_format($accbPymntsAcntCurRate, 4);
                                                                                                                                                                ?>" style="width:100%;" onchange="fmtAsNumber('accbPymntsAcntCurRate');" <?php echo $mkReadOnly; ?> />
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding: 0px 15px 0px 2px !important;">
                                    <input class="form-control" type="text" id="accbPymntsAcntCurAmnt" data-toggle="tooltip" title="Amount" value="<?php
                                                                                                                                                    echo number_format($accbPymntsAcntCurAmnt, 2);
                                                                                                                                                    ?>" style="width:100%;" readonly="true" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="display:none;border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-top: 2px !important;">
                            <div class="form-group">
                                <label for="accbPymntsIncrsDcrs1" class="control-label col-md-4">Incrs./ Dcrs.:</label>
                                <div class="col-md-8">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbPymntsIncrsDcrs1" style="width:100% !important;">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Increase", "Decrease");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($accbPymntsIncrsDcrs1 == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="accbPymntsAccntNm1" class="control-label col-md-4">Clearing Acc.:</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="accbPymntsAccntNm1" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $accbPymntsAccntNm1; ?>" readonly="true" />
                                    <input type="hidden" id="accbPymntsAccntID1" value="<?php echo $accbPymntsAccntID1; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="accbPymntsIncrsDcrs2" class="control-label col-md-4">Incrs./ Dcrs.:</label>
                                <div class="col-md-8">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbPymntsIncrsDcrs2" style="width:100% !important;">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Increase", "Decrease");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($accbPymntsIncrsDcrs2 == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="accbPymntsAccntNm2" class="control-label col-md-4">Charge Acc.:</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="accbPymntsAccntNm2" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $accbPymntsAccntNm2; ?>" readonly="true" />
                                    <input type="hidden" id="accbPymntsAccntID2" value="<?php echo $accbPymntsAccntID2; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 1px 0px;">
                        </div>
                        <div class="row" style="float:right;padding-right: 15px;margin-top: 2px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdt === true) { ?>
                                <button id="saveQuickInvPayBtn" type="button" class="btn btn-success" onclick="saveQuickInvPayForm();">Process Payment</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 102) {
                header("content-type:application/json");
                $accbPymntsPrepayDocIDs = isset($_POST['accbPymntsPrepayDocIDs']) ? cleanInputData($_POST['accbPymntsPrepayDocIDs']) : "-1";
                $accbPymntsPrepayDocLovNm = isset($_POST['accbPymntsPrepayDocLovNm']) ? cleanInputData($_POST['accbPymntsPrepayDocLovNm']) : "Customer Prepayments";
                $accbPymntsGvnAmnt = 0;
                if (trim($accbPymntsPrepayDocIDs, ",") != "" && trim($accbPymntsPrepayDocIDs, ",") != "-1") {
                    $vArry = explode(",", $accbPymntsPrepayDocIDs);
                    for ($k = 0; $k < count($vArry); $k++) {
                        $accbPymntsPrepayDocID = (int) $vArry[$k];
                        if ($accbPymntsPrepayDocLovNm == "Customer Prepayments") {
                            $accbPymntsGvnAmnt += getRcvblsPrepayAvlblAmt($accbPymntsPrepayDocID);
                        } else {
                            $accbPymntsGvnAmnt += getPyblsPrepayAvlblAmt($accbPymntsPrepayDocID);
                        }
                    }
                }
                $arr_content['accbPymntsGvnAmnt'] = number_format($accbPymntsGvnAmnt, 2);
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 103) {
                $lmtSze = isset($_POST['accbJrnlBatchDsplySze']) ? cleanInputData($_POST['accbJrnlBatchDsplySze']) : 50;
                $accbInvcVchType = isset($_POST['accbInvcVchType']) ? cleanInputData($_POST['accbInvcVchType']) : "";
                $sbmtdAccbInvcID = isset($_POST['sbmtdAccbInvcID']) ? (float) cleanInputData($_POST['sbmtdAccbInvcID']) : -1;
                $extraPKeyID = isset($_POST['extraPKeyID']) ? (float) cleanInputData($_POST['extraPKeyID']) : -1;
                $extraPKeyType = isset($_POST['extraPKeyType']) ? cleanInputData($_POST['extraPKeyType']) : "";
                $sbmtdAccbPymntsID = $sbmtdAccbInvcID;
                $mkReadOnly = "readonly=\"true\"";
            ?>
                <div id="pymntsInvcSelected" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                <?php
                                //echo $extraPKeyID . "<br/>" . $extraPKeyType;
                                if ($extraPKeyID > 0 && ($extraPKeyType == "Payable Invoice" || $accbInvcVchType == "Supplier Payments")) {
                                ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPyblsInvcForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        Go Back&nbsp;
                                    </button>
                                <?php
                                } else if ($extraPKeyID > 0 && ($extraPKeyType == "Receivable Invoice" || ($accbInvcVchType == "Customer Payments" && strpos(
                                    $extraPKeyType,
                                    "Sales Invoice"
                                ) === FALSE))) {
                                ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbRcvblsInvcForm(<?php echo $extraPKeyID; ?>, 1, 'ReloadDialog');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        Go Back&nbsp;
                                    </button>
                                <?php
                                } else if ($extraPKeyID > 0 && ($extraPKeyType == "Sales Invoice" && $accbInvcVchType == "Customer Payments") && $extraPKeyType != "Quick Sales Invoice") {
                                ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmSalesInvcForm(<?php echo $extraPKeyID; ?>, 3, 'ReloadDialog');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        Go Back&nbsp;
                                    </button>
                                <?php
                                } else if ($extraPKeyID > 0 && ($extraPKeyType == "Sales Invoice-Hospitality" && $accbInvcVchType == "Customer Payments")) {
                                ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneHotlChckinDocForm(<?php echo $extraPKeyID; ?>, 3, 'ReloadDialog');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        Go Back&nbsp;
                                    </button>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="oneAccbPymntsHstryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th style="min-width:150px;">Source Doc. Number/Type</th>
                                        <th>Payment Remark/Comment</th>
                                        <th>CUR.</th>
                                        <th style="text-align: right;">Amount Given</th>
                                        <th style="text-align: right;">Amount Paid</th>
                                        <th style="text-align: right;">Change/ Balance</th>
                                        <th style="">Payment Method.</th>
                                        <th style="min-width:220px;">GL Batch Name</th>
                                        <th style="">&nbsp;</th>
                                        <?php
                                        if ($canVwRcHstry === true) {
                                        ?>
                                            <th style="max-width:20px;width:20px;">&nbsp;</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cntr = 0;
                                    $resultRw = get_PayHstry_Trns($sbmtdAccbInvcID, $accbInvcVchType);
                                    //get_PymntBatchLns(0, 1000000, $sbmtdAccbPymntsID, '0');
                                    $ttlTrsctnAmntGvn = 0;
                                    $ttlTrsctnAmntPaid = 0;
                                    $ttlTrsctnAmntBals = 0;
                                    $trnsBrkDwnVType = "VIEW";
                                    if ($mkReadOnly == "") {
                                        $trnsBrkDwnVType = "EDIT";
                                    }
                                    while ($rowRw = loc_db_fetch_array($resultRw)) {
                                        $trsctnLineID = (float) $rowRw[0];
                                        $trsctnLineSrcDocType = $rowRw[6];
                                        $trsctnLinePayMethod = $rowRw[2];
                                        $trsctnLineSrcDocNum = $rowRw[8];
                                        $trsctnLineSrcDocID = $rowRw[7];
                                        $trsctnLineDesc = $rowRw[5] . " [Payment Date:" . $rowRw[10] . "]";
                                        $trsctnLineAmntGvn = $rowRw[25];
                                        $trsctnLineAmntPaid = $rowRw[3];
                                        $trsctnLineBals = $rowRw[4];
                                        $entrdCurID = (int) $rowRw[26];
                                        $entrdCurNm = $rowRw[27];
                                        $accbInvcVchType = $rowRw[28];
                                        $trsctnLineApldDocNo = $rowRw[17];
                                        $trsctnLineGlBtchID = $rowRw[12];
                                        $trsctnLineGlBtchNm = $rowRw[13];
                                        $ttlTrsctnAmntGvn = $ttlTrsctnAmntGvn + $trsctnLineAmntGvn;
                                        $ttlTrsctnAmntPaid = $ttlTrsctnAmntPaid + $trsctnLineAmntPaid;
                                        $ttlTrsctnAmntBals = $ttlTrsctnAmntBals + $trsctnLineBals;
                                        $cntr += 1;
                                    ?>
                                        <tr id="oneAccbPymntsHstryRow_<?php echo $cntr; ?>">
                                            <td class="lovtd">
                                                <span><?php echo ($cntr); ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo ($trsctnLineSrcDocType . "<br/>" . $trsctnLineSrcDocNum); ?></span>
                                            </td>
                                            <td class="lovtd" style="">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbPymntsHstryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                <span><?php echo $trsctnLineDesc; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <div class="" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file" onclick="">
                                                        <span class="" id="oneAccbPymntsHstryRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="lovtd" style="text-align: right;">
                                                <span style="font-weight:bold;font-size:16px;color:blue;text-align: right;"><?php
                                                                                                                            echo number_format($trsctnLineAmntGvn, 2);
                                                                                                                            ?></span>
                                            </td>
                                            <td class="lovtd" style="text-align: right;">
                                                <span style="font-weight:bold;font-size:16px;color:green;text-align: right;"><?php
                                                                                                                                echo number_format($trsctnLineAmntPaid, 2);
                                                                                                                                ?></span>
                                            </td>
                                            <td class="lovtd" style="text-align: right;">
                                                <span style="font-weight:bold;font-size:16px;color:red;text-align: right;"><?php
                                                                                                                            echo number_format($trsctnLineBals, 2);
                                                                                                                            ?></span>
                                            </td>
                                            <td class="lovtd" style="">
                                                <span><?php echo $trsctnLinePayMethod . "<br/>" . $trsctnLineApldDocNo; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <div class="input-group" style="width:100% !important;">
                                                    <input class="form-control" id="oneAccbPymntsSmryRow<?php echo $cntr; ?>_GlBtchNm" style="font-size: 13px !important;font-weight: bold !important;width:100% !important;" placeholder="" type="text" value="<?php echo $trsctnLineGlBtchNm; ?>" readonly="true" />
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $trsctnLineGlBtchID; ?>, 1, 'ReloadDialog', <?php echo $sbmtdAccbInvcID; ?>, 'Payment History|<?php echo $accbInvcVchType; ?>|<?php echo $extraPKeyType; ?>');">
                                                        <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="font-size: 14px;" onclick="getOneAccbPayInvcForm(<?php echo $trsctnLineSrcDocID; ?>, '<?php echo $accbInvcVchType; ?>', 'ShowDialog', <?php echo $trsctnLineID; ?>, <?php echo $extraPKeyID; ?>, '<?php echo $extraPKeyType; ?>');" data-toggle="tooltip" data-placement="bottom" title="Reverse Payment">
                                                    <img src="cmn_images/delete.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;Void
                                                </button>
                                            </td>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                            ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                            echo urlencode(encrypt1(($trsctnLineID . "|accb.accb_payments|pymnt_id"), $smplTokenWord1));
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
                                        <th>&nbsp;</th>
                                        <th style="text-align: right;">
                                            <?php
                                            echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTGvnHstryAmtTtlBtn\">" . number_format(
                                                $ttlTrsctnAmntGvn,
                                                2,
                                                '.',
                                                ','
                                            ) . "</span>";
                                            ?>
                                            <input type="hidden" id="myCptrdPMTGvnHstryAmtTtlVal" value="<?php echo $ttlTrsctnAmntGvn; ?>">
                                        </th>
                                        <th style="text-align: right;">
                                            <?php
                                            echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTMdeHstryAmtTtlBtn\">" . number_format(
                                                $ttlTrsctnAmntPaid,
                                                2,
                                                '.',
                                                ','
                                            ) . "</span>";
                                            ?>
                                            <input type="hidden" id="myCptrdPMTMdeHstryAmtTtlVal" value="<?php echo $ttlTrsctnAmntPaid; ?>">
                                        </th>
                                        <th style="text-align: right;">
                                            <?php
                                            echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdPMTBalsHstryAmtTtlBtn\">" . number_format(
                                                $ttlTrsctnAmntBals,
                                                2,
                                                '.',
                                                ','
                                            ) . "</span>";
                                            ?>
                                            <input type="hidden" id="myCptrdPMTBalsHstryAmtTtlVal" value="<?php echo $ttlTrsctnAmntBals; ?>">
                                        </th>
                                        <th style="">&nbsp;</th>
                                        <th style="">&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdAccbPymntsID = isset($_POST['sbmtdAccbPymntsID']) ? cleanInputData($_POST['sbmtdAccbPymntsID']) : -1;
                if (!$canAdd || ($sbmtdAccbPymntsID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdAccbPymntsID;
                $total = get_Total_Pymnts_Attachments($srchFor, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_Pymnts_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
            ?>
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdPymntsDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdPymntsDocsRow__WWW123WWW\">"
                                . "<td class=\"lovtd\"><span>New</span></td>"
                                . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdPymntsDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdPymntsDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdPymntsDocsRow_WWW123WWW_DocCtgryNm', 'attchdPymntsDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdPymntsDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToPymntsDocs('attchdPymntsDocsRow_WWW123WWW_DocFile','attchdPymntsDocsRow_WWW123WWW_AttchdDocsID','attchdPymntsDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdPymntsDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdPymntsDoc('attchdPymntsDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdPymntsDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdPymntsDocsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncAttchdPymntsDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPymntsID=<?php echo $sbmtdAccbPymntsID; ?>', 'ReloadDialog');">
                                    <input id="attchdPymntsDocsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdPymntsDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPymntsID=<?php echo $sbmtdAccbPymntsID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdPymntsDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPymntsID=<?php echo $sbmtdAccbPymntsID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdPymntsDocsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getAttchdPymntsDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPymntsID=<?php echo $sbmtdAccbPymntsID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdPymntsDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbPymntsID=<?php echo $sbmtdAccbPymntsID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdPymntsDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/PymntDocs/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                        ?>
                                            <tr id="attchdPymntsDocsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdPymntsDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdPymntsDoc('attchdPymntsDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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