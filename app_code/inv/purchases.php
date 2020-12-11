<?php
$scmSalesPrmSnsRstl = getScmPrchsPrmssns($orgID);
$addRecsPO = ($scmSalesPrmSnsRstl[0] >= 1) ? true : false;
$editRecsPO = ($scmSalesPrmSnsRstl[1] >= 1) ? true : false;
$delRecsPO = ($scmSalesPrmSnsRstl[2] >= 1) ? true : false;
$addRecsPR = ($scmSalesPrmSnsRstl[3] >= 1) ? true : false;
$editRecsPR = ($scmSalesPrmSnsRstl[4] >= 1) ? true : false;
$delRecsPR = ($scmSalesPrmSnsRstl[5] >= 1) ? true : false;
$vwOnlySelf = ($scmSalesPrmSnsRstl[6] >= 1) ? true : false;
$canPayDocs = ($scmSalesPrmSnsRstl[7] >= 1) ? true : false;
$cancelDocs = ($scmSalesPrmSnsRstl[8] >= 1) ? true : false;
$canEdtPrice = ($scmSalesPrmSnsRstl[9] >= 1) ? true : false;

$canAdd = $addRecsPO || $addRecsPR;
$canEdt = $editRecsPO || $editRecsPR;
$canDel = $delRecsPO || $delRecsPR;

$canRvwApprvDocs = $canAdd || $canEdt;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Doc Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePrchsDocHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Doc Header Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePrchsDocLine($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Doc Attachment Line */
                $pKeyID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $pKeyNm = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : "";
                if ($canDel) {
                    echo deleteCnsgnRcptDoc($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Sales Invoice Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdScmPrchsDocID = isset($_POST['sbmtdScmPrchsDocID']) ? (float) cleanInputData($_POST['sbmtdScmPrchsDocID']) : -1;
                $scmPrchsDocDocNum = isset($_POST['scmPrchsDocDocNum']) ? cleanInputData($_POST['scmPrchsDocDocNum']) : "";
                $scmPrchsDocDfltTrnsDte = isset($_POST['scmPrchsDocDfltTrnsDte']) ? cleanInputData($_POST['scmPrchsDocDfltTrnsDte']) : '';
                $scmPrchsDocNeedByDte = isset($_POST['scmPrchsDocNeedByDte']) ? cleanInputData($_POST['scmPrchsDocNeedByDte']) : '';
                $scmPrchsDocVchType = isset($_POST['scmPrchsDocVchType']) ? cleanInputData($_POST['scmPrchsDocVchType']) : '';
                $scmPrchsDocInvcCur = isset($_POST['scmPrchsDocInvcCur']) ? cleanInputData($_POST['scmPrchsDocInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $scmPrchsDocInvcCurID = getPssblValID($scmPrchsDocInvcCur, $curLovID);
                $scmPrchsDocTtlAmnt = isset($_POST['scmPrchsDocTtlAmnt']) ? (float) cleanInputData($_POST['scmPrchsDocTtlAmnt']) : 0;
                $scmPrchsDocExRate = isset($_POST['scmPrchsDocExRate']) ? (float) cleanInputData($_POST['scmPrchsDocExRate']) : 1.0000;

                $funcExchRate = round(get_LtstExchRate($scmPrchsDocInvcCurID, $fnccurid, $scmPrchsDocDfltTrnsDte), 4);
                if ($scmPrchsDocExRate == 0 || $scmPrchsDocExRate == 1) {
                    $scmPrchsDocExRate = $funcExchRate;
                }
                $scmPrchsDocSpplrID = isset($_POST['scmPrchsDocSpplrID']) ? (float) cleanInputData($_POST['scmPrchsDocSpplrID']) : -1;
                $scmPrchsDocSpplrSiteID = isset($_POST['scmPrchsDocSpplrSiteID']) ? (float) cleanInputData($_POST['scmPrchsDocSpplrSiteID'])
                    : -1;
                $scmPrchsDocDesc = isset($_POST['scmPrchsDocDesc']) ? cleanInputData($_POST['scmPrchsDocDesc']) : '';
                $scmPrchsDocPayTerms = isset($_POST['scmPrchsDocPayTerms']) ? cleanInputData($_POST['scmPrchsDocPayTerms']) : '';
                $srcPrchsDocDocID = isset($_POST['srcPrchsDocDocID']) ? (float) cleanInputData($_POST['srcPrchsDocDocID']) : -1;
                $scmPrchsDocBrnchID = isset($_POST['scmPrchsDocBrnchID']) ? (float) cleanInputData($_POST['scmPrchsDocBrnchID']) : $brnchLocID;

                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                if (strlen($scmPrchsDocDesc) > 499) {
                    $scmPrchsDocDesc = substr($scmPrchsDocDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($scmPrchsDocDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($scmPrchsDocVchType == "") {
                    $exitErrMsg .= "Document Type cannot be empty!<br/>";
                }
                if ($scmPrchsDocDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($scmPrchsDocDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                /*  if ($scmPrchsDocCstmrID <= 0) {
                  $exitErrMsg .= "Customer Name cannot be empty!<br/>";
                  }
                  if ($scmPrchsDocCstmrSiteID <= 0) {
                  $exitErrMsg .= "Customer Site cannot be empty!<br/>";
                  } */
                $oldPtyCashID = getGnrlRecID("scm.scm_prchs_docs_hdr", "purchase_doc_num", "prchs_doc_hdr_id", $scmPrchsDocDocNum, $orgID);
                if ($oldPtyCashID > 0 && $oldPtyCashID != $sbmtdScmPrchsDocID) {
                    $exitErrMsg .= "New Document Number/Name is already in use in this Organization!<br/>";
                }
                $apprvlStatus = "Not Validated";
                $nxtApprvlActn = "Approve";
                $pymntTrms = $scmPrchsDocPayTerms;
                $srcDocHdrID = $srcPrchsDocDocID;
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdScmPrchsDocID'] = $sbmtdScmPrchsDocID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdScmPrchsDocID <= 0) {
                    createPrchsDocHdr(
                        $orgID,
                        $scmPrchsDocDocNum,
                        $scmPrchsDocDesc,
                        $scmPrchsDocVchType,
                        $scmPrchsDocDfltTrnsDte,
                        $scmPrchsDocPayTerms,
                        $scmPrchsDocSpplrID,
                        $scmPrchsDocSpplrSiteID,
                        $apprvlStatus,
                        $nxtApprvlActn,
                        $srcPrchsDocDocID,
                        $scmPrchsDocInvcCurID,
                        $scmPrchsDocExRate,
                        $scmPrchsDocNeedByDte,
                        $scmPrchsDocBrnchID
                    );
                    $sbmtdScmPrchsDocID = getGnrlRecID(
                        "scm.scm_prchs_docs_hdr",
                        "purchase_doc_num",
                        "prchs_doc_hdr_id",
                        $scmPrchsDocDocNum,
                        $orgID
                    );
                } else if ($sbmtdScmPrchsDocID > 0) {
                    updtPrchsDocHdr(
                        $sbmtdScmPrchsDocID,
                        $scmPrchsDocDocNum,
                        $scmPrchsDocDesc,
                        $scmPrchsDocVchType,
                        $scmPrchsDocDfltTrnsDte,
                        $scmPrchsDocPayTerms,
                        $scmPrchsDocSpplrID,
                        $scmPrchsDocSpplrSiteID,
                        $apprvlStatus,
                        $nxtApprvlActn,
                        $srcPrchsDocDocID,
                        $scmPrchsDocInvcCurID,
                        $scmPrchsDocExRate,
                        $scmPrchsDocNeedByDte,
                        $scmPrchsDocBrnchID
                    );
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDetTransLines, "|~") != "" && $sbmtdScmPrchsDocID > 0) {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 10) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_ItmID = (float) cleanInputData1($crntRow[1]);
                            $ln_StoreID = (int) cleanInputData1($crntRow[2]);
                            $ln_LineDesc = cleanInputData1($crntRow[3]);
                            $ln_QTY = (float) (cleanInputData1($crntRow[4]));
                            $ln_UnitPrice = (float) cleanInputData1($crntRow[5]);
                            $ln_TaxID = (int) cleanInputData1($crntRow[6]);
                            $ln_DscntID = (int) cleanInputData1($crntRow[7]);
                            $ln_ChrgID = (int) (cleanInputData1($crntRow[8]));
                            $ln_SrcDocLnID = (int) (cleanInputData1($crntRow[9]));
                            $errMsg = "";
                            if ($ln_LineDesc === "" || $ln_ItmID <= 0 || $ln_QTY <= 0) {
                                $errMsg = "Row " . ($y + 1) . ":- Item Description and Quantity are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                //Create Sales Doc Lines
                                if ($ln_LineDesc != "" && $ln_ItmID > 0 && $ln_QTY > 0) {
                                    if ($ln_TrnsLnID <= 0) {
                                        $afftctd += createPrchsDocLn(
                                            $sbmtdScmPrchsDocID,
                                            $ln_ItmID,
                                            $ln_QTY,
                                            $ln_UnitPrice,
                                            $ln_StoreID,
                                            $scmPrchsDocInvcCurID,
                                            $ln_SrcDocLnID,
                                            $ln_LineDesc,
                                            $ln_TaxID,
                                            $ln_DscntID,
                                            $ln_ChrgID
                                        );
                                    } else {
                                        $afftctd += updatePrchsDocLn(
                                            $ln_TrnsLnID,
                                            $sbmtdScmPrchsDocID,
                                            $ln_ItmID,
                                            $ln_QTY,
                                            $ln_UnitPrice,
                                            $ln_StoreID,
                                            $scmPrchsDocInvcCurID,
                                            $ln_SrcDocLnID,
                                            $ln_LineDesc,
                                            $ln_TaxID,
                                            $ln_DscntID,
                                            $ln_ChrgID
                                        );
                                    }
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }
                //Final Approval
                if ($shdSbmt !== 2) {
                    $errMsg1 = reCalcPrchsDocSmmrys(
                        $sbmtdScmPrchsDocID,
                        $scmPrchsDocVchType,
                        $scmPrchsDocSpplrID,
                        $scmPrchsDocInvcCurID,
                        $apprvlStatus
                    );
                    if (strpos($errMsg1, "ERROR") !== FALSE) {
                        $exitErrMsg .= "<br/>" . $errMsg1;
                    }
                }
                $errMsg = "";
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Document Successfully Saved!"
                        . "<br/>" . $afftctd . " Transaction(s) Saved Successfully!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "";
                    if ($shdSbmt == 2) {
                        $exitErrMsg = approve_sales_prchsdoc($sbmtdScmPrchsDocID, "Purchase");
                        if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        } else {
                            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        }
                        /* updtRcvblsDocGLBatch($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcGLBatchID);
                          updtRcvblsDocApprvl($sbmtdAccbRcvblsInvcID, "Approved", "Cancel"); */
                    } else {
                        $exitErrMsg .= "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Document Successfully Saved!"
                            . "<br/>" . $afftctd . " Document Transaction(s) Saved Successfully!";
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdScmPrchsDocID'] = $sbmtdScmPrchsDocID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 20) {
                //Upload Attachment
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdScmPrchsDocID = isset($_POST['sbmtdScmPrchsDocID']) ? cleanInputData($_POST['sbmtdScmPrchsDocID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdScmPrchsDocID;
                if ($attchmentID > 0) {
                    uploadDaPrchsDocDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewCnsgnRcptDocID();
                    createCnsgnRcptDoc($attchmentID, $pkID, "PURCHASE", $docCtrgrName, "");
                    uploadDaPrchsDocDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/Prchs/" . $nwImgLoc;
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
                //Reverse Sales Invoice Voucher
                $errMsg = "";
                $scmPrchsDocDesc = isset($_POST['scmPrchsDocDesc']) ? cleanInputData($_POST['scmPrchsDocDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdScmPrchsDocID = isset($_POST['sbmtdScmPrchsDocID']) ? cleanInputData($_POST['sbmtdScmPrchsDocID']) : -1;
                if (!$cancelDocs) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                $p_dochdrtype = "";
                $scmInvcDfltTrnsDte = "";
                $scmInvcDocNum = "";
                if ($sbmtdScmPrchsDocID > 0) {
                    $result = get_One_PrchsDocDocHdr($sbmtdScmPrchsDocID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmInvcDfltTrnsDte = $row[1] . " 12:00:00";
                        $scmInvcDocNum = $row[4];
                        $p_dochdrtype = $row[5];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                    }
                }
                if ($rqStatus == "Not Validated" && $sbmtdScmPrchsDocID > 0) {
                    echo deletePrchsDocHdrNDet($sbmtdScmPrchsDocID, $scmInvcDocNum);
                    exit();
                } else {
                    $exitErrMsg = cancelSalesPrchsDoc($sbmtdScmPrchsDocID, "Purchase", $orgID, $usrID);
                    $arr_content['sbmtdScmPrchsDocID'] = $sbmtdScmPrchsDocID;
                    $arr_content['percent'] = 100;
                    if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                        execUpdtInsSQL("UPDATE scm.scm_prchs_docs_hdr SET comments_desc='" . loc_db_escape_string($scmPrchsDocDesc) . "' WHERE (prchs_doc_hdr_id = " . $sbmtdScmPrchsDocID . ")");
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
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Purchases</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Document Number';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
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
                    $total = get_Total_PrchsDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Basic_PrchsDoc($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
                    $colClassType3 = "col-md-5";
?>
                    <form id='scmPrchsDocForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245">PURCHASING DOCUMENTS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                                $colClassType3 = "col-md-10";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="scmPrchsDocSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncScmPrchsDoc(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="scmPrchsDocPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                        <input id="sbmtdScmPrchsReqID" type="hidden" value="-1">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmPrchsDoc('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmPrchsDoc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmPrchsDocSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "");
                                            $srchInsArrys = array(
                                                "Document Number", "Document Description",
                                                "Supplier Name", "Requisition Number", "Approval Status", "Created By", "Branch"
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
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmPrchsDocDsplySze" style="min-width:70px !important;">
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
                                                <a href="javascript:getScmPrchsDoc('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getScmPrchsDoc('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
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
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmPrchsDocForm(-1, 1, 'ShowDialog', 'Purchase Order');" data-toggle="tooltip" data-placement="bottom" title="Add New Purchase Order">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                NEW PO
                                            </button>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmPrchsDocForm(-1, 1, 'ShowDialog', 'Purchase Requisition');" data-toggle="tooltip" data-placement="bottom" title="Add New Purchase Requisition">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                NEW PR
                                            </button>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Approved Requisitions', 'allOtherInputOrgID', '', '', 'radio', true, '', 'sbmtdScmPrchsReqID', '', 'clear', 1, '', function () {
                                                        getOneScmPrchsDocForm(-1, 1, 'ShowDialog', 'Purchase Order');
                                                    });" data-toggle="tooltip" data-placement="bottom" title="Add New Purchase Order from Purchase Requisition">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                NEW PO FROM PR
                                            </button>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;display:none;">
                                        <div class="form-check" style="font-size: 12px !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $shwUnpaidOnlyChkd = "";
                                                if ($qShwUnpaidOnly == true) {
                                                    $shwUnpaidOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmPrchsDoc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmPrchsDocShwUnpaidOnly" name="scmPrchsDocShwUnpaidOnly" <?php echo $shwUnpaidOnlyChkd; ?>>
                                                Show Approved but Unpaid
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;display:none;">
                                        <div class="form-check" style="font-size: 12px !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmPrchsDoc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmPrchsDocShwUnpstdOnly" name="scmPrchsDocShwUnpstdOnly" <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="scmPrchsDocHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:35px;width:35px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th>Document Number/Type - Transaction Description</th>
                                                <th style="max-width:75px;width:75px;">Branch</th>
                                                <th style="text-align:center;max-width:30px;width:30px;">CUR.</th>
                                                <th style="text-align:right;min-width:100px;width:100px;">Total Amount</th>
                                                <th style="max-width:75px;width:75px;">Document Status</th>
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
                                                <tr id="scmPrchsDocHdrsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Document" onclick="getOneScmPrchsDocForm(<?php echo $row[0]; ?>, 1, 'ShowDialog', '<?php echo $row[2]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <?php
                                                            if ($canAdd === true) {
                                                            ?>
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[1] . " (" . $row[2] . ") " . $row[7] . " " . $row[3]; ?></td>
                                                    <td class="lovtd"><?php echo $row[9]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[4]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                echo number_format((float) $row[5], 2);
                                                                                                                                ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if ($row[6] == "Approved") {
                                                        $style1 = "color:green;";
                                                    } else if ($row[6] == "Cancelled") {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php echo $row[6]; ?></td>
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delScmPrchsDoc('scmPrchsDocHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="scmPrchsDocHdrsRow<?php echo $cntr; ?>_HdrID" name="scmPrchsDocHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                    ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|scm.scm_prchs_docs_det|prchs_doc_hdr_id"),
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
            } else if ($vwtyp == 1) {
                //New Purchase Order Form
                //var_dump($_POST);
                $sbmtdScmPrchsDocID = isset($_POST['sbmtdScmPrchsDocID']) ? cleanInputData($_POST['sbmtdScmPrchsDocID']) : -1;
                $scmPrchsDocVchType = isset($_POST['scmPrchsDocVchType']) ? cleanInputData($_POST['scmPrchsDocVchType']) : "Purchase Order";
                $sbmtdScmPrchsReqID = isset($_POST['sbmtdScmPrchsReqID']) ? cleanInputData($_POST['sbmtdScmPrchsReqID']) : -1;

                if (!$canAdd || ($sbmtdScmPrchsDocID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                if ($scmPrchsDocVchType == "Purchase Order" && (!$addRecsPO || ($sbmtdScmPrchsDocID > 0 && !$editRecsPO))) {
                    restricted();
                    exit();
                }
                if ($scmPrchsDocVchType == "Purchase Requisition" && (!$addRecsPR || ($sbmtdScmPrchsDocID > 0 && !$editRecsPR))) {
                    restricted();
                    exit();
                }
                $orgnlScmPrchsDocID = $sbmtdScmPrchsDocID;
                $scmPrchsDocDfltTrnsDte = $gnrlTrnsDteDMY; //HMS
                $scmPrchsDocCreator = $uName;
                $scmPrchsDocCreatorID = $usrID;
                $scmPrchsDocBrnchID = $brnchLocID;
                $scmPrchsDocBrnchNm = $brnchLoc;
                $gnrtdTrnsNo = "";
                $scmPrchsDocDesc = "";

                $srcPrchsDocDocID = $sbmtdScmPrchsReqID;
                $srcPrchsDocDocTyp = "";
                if ($scmPrchsDocVchType == "Purchase Order") {
                    $srcPrchsDocDocTyp = "Purchase Requisition";
                } elseif ($scmPrchsDocVchType == "Purchase Requisition") {
                    $srcPrchsDocDocTyp = "";
                }
                $srcPrchsDocDocNum = "";

                $scmPrchsDocSpplr = "";
                $scmPrchsDocSpplrID = -1;
                $scmPrchsDocSpplrSite = "";
                $scmPrchsDocSpplrSiteID = -1;
                $scmPrchsDocSpplrClsfctn = "Supplier";
                $rqStatus = "Not Validated";
                $rqStatusNext = "Approve";
                $rqstatusColor = "red";

                $scmPrchsDocTtlAmnt = 0;
                $scmPrchsDocPayTerms = "";
                $scmPrchsDocInvcCurID = $fnccurid;
                $scmPrchsDocInvcCur = $fnccurnm;
                $scmPrchsDocExRate = 1;
                $scmPrchsDocNeedByDte = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdScmPrchsDocID > 0) {
                    $result = get_One_PrchsDocDocHdr($sbmtdScmPrchsDocID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmPrchsDocDfltTrnsDte = $row[1];
                        $scmPrchsDocCreator = $row[3];
                        $scmPrchsDocCreatorID = $row[2];
                        $gnrtdTrnsNo = $row[4];
                        $scmPrchsDocVchType = $row[5];
                        $scmPrchsDocDesc = $row[6];
                        $srcPrchsDocDocID = $row[7];
                        $scmPrchsDocSpplr = $row[9];
                        $scmPrchsDocSpplrID = $row[8];
                        $scmPrchsDocSpplrSite = $row[11];
                        $scmPrchsDocSpplrSiteID = $row[10];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                        $rqstatusColor = "red";

                        $scmPrchsDocPayTerms = $row[15];
                        $srcPrchsDocDocTyp = $row[16];
                        $srcPrchsDocDocNum = $row[26];

                        $scmPrchsDocTtlAmnt = (float) $row[14];
                        $scmPrchsDocInvcCur = $row[25];
                        $scmPrchsDocInvcCurID = $row[24];
                        $scmPrchsDocExRate = (float) $row[27];
                        $scmPrchsDocNeedByDte = $row[29];
                        $scmPrchsDocBrnchID = (int) $row[30];
                        $scmPrchsDocBrnchNm = $row[31];
                        if ($scmPrchsDocExRate == 0) {
                            $scmPrchsDocExRate = 1;
                        }
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
                        $errMsg1 = reCalcPrchsDocSmmrys(
                            $sbmtdScmPrchsDocID,
                            $scmPrchsDocVchType,
                            $scmPrchsDocSpplrID,
                            $scmPrchsDocInvcCurID,
                            $rqStatus
                        );
                        $rslt = get_One_PrchsDocAmounts($sbmtdScmPrchsDocID);
                        if ($rw = loc_db_fetch_array($rslt)) {
                            $scmPrchsDocTtlAmnt = (float) $rw[0];
                        }
                    }
                } else {
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypes = array("Purchase Order", "Purchase Requisition");
                    $docTypPrfxs = array("PO", "PR");

                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $scmPrchsDocVchType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum(
                        "scm.scm_prchs_docs_hdr",
                        "purchase_doc_num",
                        "prchs_doc_hdr_id",
                        $gnrtdTrnsNo1 . "%"
                    ) + 1) . ""), 3, '0', STR_PAD_LEFT);
                    if ($srcPrchsDocDocID > 0) {
                        $result = get_One_PrchsDocDocHdr($srcPrchsDocDocID);
                        if ($row = loc_db_fetch_array($result)) {
                            $srcPrchsDocDocNum = $row[4];
                            $srcPrchsDocDocTyp = $row[5];
                            $scmPrchsDocDesc = $row[6];
                            $scmPrchsDocSpplr = $row[9];
                            $scmPrchsDocSpplrID = $row[8];
                            $scmPrchsDocSpplrSite = $row[11];
                            $scmPrchsDocSpplrSiteID = $row[10];
                            $scmPrchsDocPayTerms = $row[15];
                            $scmPrchsDocTtlAmnt = (float) $row[14];
                            $scmPrchsDocInvcCur = $row[25];
                            $scmPrchsDocInvcCurID = $row[24];
                            $scmPrchsDocExRate = (float) $row[27];
                            $scmPrchsDocNeedByDte = $row[29];
                            $scmPrchsDocBrnchID = (int) $row[30];
                            $scmPrchsDocBrnchNm = $row[31];
                            if ($scmPrchsDocExRate == 0) {
                                $scmPrchsDocExRate = 1;
                            }
                        }
                    }
                    $sbmtdScmPrchsDocID = createPrchsDocHdr(
                        $orgID,
                        $gnrtdTrnsNo,
                        $scmPrchsDocDesc,
                        $scmPrchsDocVchType,
                        $scmPrchsDocDfltTrnsDte,
                        $scmPrchsDocPayTerms,
                        $scmPrchsDocSpplrID,
                        $scmPrchsDocSpplrSiteID,
                        $rqStatus,
                        $rqStatusNext,
                        $srcPrchsDocDocID,
                        $scmPrchsDocInvcCurID,
                        $scmPrchsDocExRate,
                        $scmPrchsDocNeedByDte,
                        $scmPrchsDocBrnchID
                    );
                }
                $reportName = getEnbldPssblValDesc("Sales Invoice", getLovID("Document Custom Print Process Names"));
                $reportTitle = str_replace("Purchase Order", "Payment Voucher", $scmPrchsDocVchType);
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdScmPrchsDocID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="oneScmPrchsDocEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Doc. No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdScmPrchsDocID" name="sbmtdScmPrchsDocID" value="<?php echo $sbmtdScmPrchsDocID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="scmPrchsDocDocNum" name="scmPrchsDocDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="scmPrchsDocDfltTrnsDte" name="scmPrchsDocDfltTrnsDte" value="<?php echo $scmPrchsDocDfltTrnsDte; ?>" placeholder="Transactions Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Need By Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="scmPrchsDocNeedByDte" name="scmPrchsDocNeedByDte" value="<?php echo $scmPrchsDocNeedByDte; ?>" placeholder="Need By Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" aria-label="..." id="scmPrchsDocVchType" name="scmPrchsDocVchType" value="<?php echo $scmPrchsDocVchType; ?>" readonly="true">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="scmPrchsDocSpplr" class="control-label col-md-4">Supplier:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmPrchsDocSpplr" name="scmPrchsDocSpplr" value="<?php echo $scmPrchsDocSpplr; ?>" readonly="true">
                                            <input type="hidden" id="scmPrchsDocSpplrID" value="<?php echo $scmPrchsDocSpplrID; ?>">
                                            <input type="hidden" id="scmPrchsDocSpplrClsfctn" value="<?php echo $scmPrchsDocSpplrClsfctn; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Supplier', 'ShowDialog', function () {}, 'scmPrchsDocSpplrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'scmPrchsDocSpplrClsfctn', 'radio', true, '', 'scmPrchsDocSpplrID', 'scmPrchsDocSpplr', 'clear', 1, '', function () {
                                                        getInvRcvblsAcntInfo();
                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scmPrchsDocSpplrSite" class="control-label col-md-4">Site:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmPrchsDocSpplrSite" name="scmPrchsDocSpplrSite" value="<?php echo $scmPrchsDocSpplrSite; ?>" readonly="true">
                                            <input class="form-control" type="hidden" id="scmPrchsDocSpplrSiteID" value="<?php echo $scmPrchsDocSpplrSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'scmPrchsDocSpplrID', '', '', 'radio', true, '', 'scmPrchsDocSpplrSiteID', 'scmPrchsDocSpplrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group" style="width:100%;">
                                            <textarea class="form-control rqrdFld" rows="1" cols="20" id="scmPrchsDocDesc" name="scmPrchsDocDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;min-height: 30px !important;height: 30px !important;"><?php echo $scmPrchsDocDesc; ?></textarea>
                                            <input class="form-control" type="hidden" id="scmPrchsDocDesc1" value="<?php echo $scmPrchsDocDesc; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmPrchsDocDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Payment Terms:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group" style="width:100%;">
                                            <textarea class="form-control" rows="1" cols="20" id="scmPrchsDocPayTerms" name="scmPrchsDocPayTerms" <?php echo $mkReadOnly; ?> style="text-align:left !important;min-height: 30px !important;height: 30px !important;"><?php echo $scmPrchsDocPayTerms; ?></textarea>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmPrchsDocPayTerms');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="srcPrchsDocDocNum" class="control-label col-md-4">Source Doc. No.:</label>
                                    <div class="col-md-8">
                                        <input type="hidden" class="form-control" aria-label="..." id="srcPrchsDocDocTyp" name="srcPrchsDocDocTyp" value="<?php echo $srcPrchsDocDocTyp; ?>">
                                        <input type="hidden" id="srcPrchsDocDocID" value="<?php echo $srcPrchsDocDocID; ?>"><?php
                                                                                                                            $lovNm = "";
                                                                                                                            if ($scmPrchsDocVchType == "Purchase Requisition") {
                                                                                                                                $lovNm = "Approved Purchase Orders";
                                                                                                                            }
                                                                                                                            if (!($scmPrchsDocVchType == "Purchase Requisition" || $scmPrchsDocVchType == "Purchase Order")) {
                                                                                                                            ?>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="srcPrchsDocDocNum" name="srcPrchsDocDocNum" value="<?php echo $srcPrchsDocDocNum; ?>" readonly="true" style="width:100%;">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovNm; ?>', 'allOtherInputOrgID', 'scmPrchsDocSpplrID', 'scmPrchsDocInvcCur', 'radio', true, '', 'srcPrchsDocDocID', 'srcPrchsDocDocNum', 'clear', 1, '', function () {});" data-toggle="tooltip" title="Existing Document Number">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        <?php } else { ?>
                                            <input type="text" class="form-control" aria-label="..." id="srcPrchsDocDocNum" name="srcPrchsDocDocNum" value="<?php echo $srcPrchsDocDocNum; ?>" readonly="true" style="width:100%;">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Total:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $scmPrchsDocInvcCur; ?>', 'scmPrchsDocInvcCur', '', 'clear', 0, '', function () {
                                                        $('#scmPrchsDocInvcCur1').html($('#scmPrchsDocInvcCur').val());
                                                        $('#scmPrchsDocInvcCur2').html($('#scmPrchsDocInvcCur').val());
                                                        $('#scmPrchsDocInvcCur3').html($('#scmPrchsDocInvcCur').val());
                                                        $('#scmPrchsDocInvcCur4').html($('#scmPrchsDocInvcCur').val());
                                                        $('#scmPrchsDocInvcCur5').html($('#scmPrchsDocInvcCur').val());
                                                    });">
                                                <span class="" style="font-size: 20px !important;" id="scmPrchsDocInvcCur1"><?php echo $scmPrchsDocInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="scmPrchsDocInvcCur" value="<?php echo $scmPrchsDocInvcCur; ?>">
                                            <input type="hidden" id="scmPrchsDocInvcCurID" value="<?php echo $scmPrchsDocInvcCurID; ?>">
                                            <input class="form-control" type="text" id="scmPrchsDocTtlAmnt" value="<?php
                                                                                                                    echo number_format($scmPrchsDocTtlAmnt, 2);
                                                                                                                    ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('scmPrchsDocTtlAmnt');" readonly="true" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Rate (Multiplier):</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;" id="scmPrchsDocInvcCur4"><?php echo $scmPrchsDocInvcCur; ?></span>
                                                <span class="" style="font-size: 20px !important;" id="scmPrchsDocFuncCur"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                            </label>
                                            <input type="text" class="form-control" aria-label="..." id="scmPrchsDocExRate" name="scmPrchsDocExRate" value="<?php
                                                                                                                                                            echo number_format($scmPrchsDocExRate, 4);
                                                                                                                                                            ?>" style="font-size: 18px !important;font-weight:bold;width:100%;" <?php echo $mkReadOnly; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Status:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="hidden" id="scmPrchsDocApprvlStatus" value="<?php echo $rqStatus; ?>">
                                        <button type="button" class="btn btn-default" style="height:30px;width:100% !important;" id="myScmPrchsDocStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;">
                                                <?php
                                                echo $rqStatus;
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
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                $edtPriceRdOnly = "";
                                                $nwRowHtml33 = "<tr id=\"oneScmPrchsDocSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneScmPrchsDocSmryLinesTable tr').index(this));\">"
                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\"  style=\"\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_ItmID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_StoreID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_SrcDocLnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                         <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate jbDetDesc\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_LineDesc\" name=\"oneScmPrchsDocSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow_WWW123WWW_LineDesc', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate');\" onblur=\"afterSalesInvcItmSlctn('oneScmPrchsDocSmryRow__WWW123WWW');\" onchange=\"autoCreateSalesLns=99;\">
                                                                         <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getScmSalesInvcItems('oneScmPrchsDocSmryRow__WWW123WWW', 'ShowDialog', '" . $scmPrchsDocVchType . "', 'false', function () {var a=1;});\">
                                                                             <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                         </label>
                                                                        </div>
                                                                    </td> 
                                                                    <td class=\"lovtd\" style=\"text-align: right;\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_QTY\" name=\"oneScmPrchsDocSmryRow_WWW123WWW_QTY\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow_WWW123WWW_QTY', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');\">                                                    
                                                                    </td>                                               
                                                                    <td class=\"lovtd\" style=\"max-width:35px;width:35px;text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_UomID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <div class=\"\" style=\"width:100% !important;\">
                                                                            <label class=\"btn btn-primary btn-file\" onclick=\"getOneScmUOMBrkdwnForm(-1, 2, 'oneScmPrchsDocSmryRow__WWW123WWW');\">
                                                                                <span class=\"\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_UomNm1\">Each</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_UnitPrice\" name=\"oneScmPrchsDocSmryRow_WWW123WWW_UnitPrice\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow_WWW123WWW_UnitPrice', 'oneScmPrchsDocSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');\" " . $edtPriceRdOnly . ">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control jbDetCrdt\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_LineAmt\" name=\"oneScmPrchsDocSmryRow_WWW123WWW_LineAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow_WWW123WWW_LineAmt', 'oneScmPrchsDocSmryLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\" onchange=\"calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');\">                                                    
                                                                    </td>  
                                                                    <td class=\"lovtd\" style=\"text-align: right;\">
                                                                        <input type=\"text\" class=\"form-control jbDetAccRate1\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_SrcQTY\" name=\"oneScmPrchsDocSmryRow_WWW123WWW_SrcQTY\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow_WWW123WWW_SrcQTY', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate1');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\" onchange=\"calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');\">                                                    
                                                                    </td> 
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Item Consignments\" 
                                                                                onclick=\"getScmSalesInvcItems('oneScmPrchsDocSmryRow__WWW123WWW', 'ShowDialog', '" . $scmPrchsDocVchType . "', 'true', function () {var a=1;});\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/chcklst3.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>    
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_TaxID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Tax Codes\" 
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmPrchsDocSmryRow_WWW123WWW_TaxID', '', 'clear', 1, '');\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/tax-icon420x500.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>   
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_DscntID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Discounts\" 
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmPrchsDocSmryRow_WWW123WWW_DscntID', '', 'clear', 1, '');\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/dscnt_456356.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>  
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmPrchsDocSmryRow_WWW123WWW_ChrgID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Extra Charges\" 
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmPrchsDocSmryRow_WWW123WWW_ChrgID', '', 'clear', 1, '');\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/truck571d7f45.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delScmPrchsDocDetLn('oneScmPrchsDocSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";
                                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                                ?>
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <input type="hidden" id="nwSalesDocLineHtm" value="<?php echo $nwRowHtml33; ?>">
                                                            <button id="addNwScmPrchsDocSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewScmSalesInvcRows('oneScmPrchsDocSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmPrchsDocDocsForm(<?php echo $sbmtdScmPrchsDocID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmPrchsDocForm(<?php echo $sbmtdScmPrchsDocID; ?>, 1, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdPrchsDocValsTtlBtn"><?php echo $scmPrchsDocInvcCur; ?>
                                                                <?php
                                                                echo number_format($scmPrchsDocTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdPrchsDocValsTtlVal" value="<?php echo $scmPrchsDocTtlAmnt; ?>">
                                                        </button>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 10px 0px 10px !important;">
                                                        <div class="form-group">
                                                            <label for="scmPrchsDocBrnchNm" class="control-label col-md-4" style="padding:5px 10px 0px 13px !important;">Branch:</label>
                                                            <div class="col-md-8" style="padding:0px 23px 0px 11px !important;">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="scmPrchsDocBrnchNm" name="scmPrchsDocBrnchNm" value="<?php echo $scmPrchsDocBrnchNm; ?>" readonly="true">
                                                                    <input class="form-control" type="hidden" id="scmPrchsDocBrnchID" value="<?php echo $scmPrchsDocBrnchID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmPrchsDocBrnchID', 'scmPrchsDocBrnchNm', 'clear', 0, '');" data-toggle="tooltip" title="">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 15px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                                            <?php
                                                            if ($rqStatus == "Not Validated") {
                                                            ?>
                                                                <?php if ($canEdt) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmPrchsDocForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                <?php } ?>
                                                                <?php if ($canRvwApprvDocs) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmPrchsDocForm('<?php echo $fnccurnm; ?>', 2);" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                    </button>
                                                                <?php
                                                                }
                                                            } else if ($rqStatus == "Approved") {
                                                                if ($cancelDocs) {
                                                                ?>
                                                                    <button id="fnlzeRvrslScmPrchsDocBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmPrchsDocRvrslForm('<?php echo $fnccurnm; ?>', 1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Document&nbsp;</button>
                                                                    <!--<button id="fnlzeBadDebtScmPrchsDocBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmPrchsDocRvrslForm('<?php echo $fnccurnm; ?>', 2);"  data-toggle="tooltip" data-placement="bottom" title="Declare as Bad Debt">
                                                                    <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Bad Debt&nbsp;</button>-->
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
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneScmPrchsDocLnsTblSctn">
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="PrchsDocDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                <div class="col-md-10" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneScmPrchsDocSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="min-width:250px;">Item Code/Description</th>
                                                                <th style="max-width:50px;width:50px;text-align: right;">QTY</th>
                                                                <th style="max-width:55px;text-align: center;">UOM.</th>
                                                                <th style="max-width:170px;width:140px;text-align: right;">Unit Price (Including VAT)</th>
                                                                <th style="max-width:170px;width:120px;text-align: right;">Total Amount</th>
                                                                <?php
                                                                if ($scmPrchsDocVchType == "Purchase Order") {
                                                                ?>
                                                                    <th style="max-width:50px;width:50px;text-align: right;">Source QTY</th>
                                                                <?php } else { ?>
                                                                    <th style="max-width:50px;width:50px;text-align: right;">QTY in POs</th>
                                                                <?php } ?>
                                                                <th style="max-width:30px;width:30px;text-align: center;">CS</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">TX</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">DC</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">EX</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_PrchsDocDocDet($sbmtdScmPrchsDocID);
                                                            $ttlTrsctnEntrdAmnt = 0;
                                                            $trnsBrkDwnVType = "VIEW";
                                                            if ($mkReadOnly == "") {
                                                                $trnsBrkDwnVType = "EDIT";
                                                            }
                                                            $ttlRows = loc_db_num_rows($resultRw);
                                                            if ($ttlRows > 0) {
                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $trsctnLnID = (float) $rowRw[0];
                                                                    $trsctnLnItmID = (float) $rowRw[1];
                                                                    $trsctnLnQty = (float) $rowRw[2];
                                                                    $trsctnLnUnitPrice = (float) $rowRw[3];
                                                                    $trsctnLnAmnt = (float) $rowRw[4];
                                                                    $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                    $trsctnLnStoreID = (float) $rowRw[5];
                                                                    $trsctnLnCurID = $rowRw[6];
                                                                    $trsctnLnSrcLnID = (float) $rowRw[7];
                                                                    $trsctnLnAvlblQty = (float) $rowRw[9];
                                                                    if ($scmPrchsDocVchType == "Purchase Order") {
                                                                        $trsctnLnAvlblQty = (float) $rowRw[8];
                                                                    }
                                                                    $trsctnLnTxID = (float) $rowRw[10];
                                                                    $trsctnLnDscntID = (float) $rowRw[11];
                                                                    $trsctnLnChrgID = (float) $rowRw[12];
                                                                    $trsctnLnUomID = (float) $rowRw[13];
                                                                    $trsctnLnUomNm = $rowRw[14];
                                                                    $trsctnLnDesc = $rowRw[15];
                                                                    $cntr += 1;
                                                            ?>
                                                                    <tr id="oneScmPrchsDocSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmPrchsDocSmryLinesTable tr').index(this));">
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                                        <td class="lovtd" style="">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_SrcDocLnID" value="<?php echo $trsctnLnSrcLnID; ?>" style="width:100% !important;">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmPrchsDocSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmPrchsDocSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmPrchsDocVchType; ?>', 'false', function () {
                                                                                                var a = 1;
                                                                                            });">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                            ?>
                                                                                <span><?php echo $trsctnLnDesc; ?></span>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: right;">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_QTY" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                                                                                                                                                                                                                            echo $trsctnLnQty;
                                                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_QTY', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');">
                                                                        </td>
                                                                        <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">
                                                                            <div class="" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmPrchsDocID; ?>, 2, 'oneScmPrchsDocSmryRow_<?php echo $cntr; ?>');">
                                                                                    <span class="" id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                                                                                                                                                                                                                            echo number_format($trsctnLnUnitPrice, 5);
                                                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmPrchsDocSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');">
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                                                                                                                                                                                                                        echo number_format($trsctnLnAmnt, 2);
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmPrchsDocSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');">
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: right;">
                                                                            <input type="text" class="form-control jbDetAccRate1" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_SrcQTY" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_SrcQTY" value="<?php
                                                                                                                                                                                                                                                                        echo $trsctnLnAvlblQty;
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_SrcQTY', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate1');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');">
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" onclick="getScmSalesInvcItems('oneScmPrchsDocSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmPrchsDocVchType; ?>', 'true', function () {
                                                                                                var a = 1;
                                                                                            });" style="padding:2px !important;">
                                                                                <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Tax Codes" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_TaxID', '', 'clear', 1, '');" style="padding:2px !important;">
                                                                                <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Discounts" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_DscntID', '', 'clear', 1, '');" style="padding:2px !important;">
                                                                                <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_ChrgID" value="<?php echo $trsctnLnChrgID; ?>" style="width:100% !important;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Extra Charges" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnChrgID; ?>', 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_ChrgID', '', 'clear', 1, '');" style="padding:2px !important;">
                                                                                <img src="cmn_images/truck571d7f45.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmPrchsDocDetLn('oneScmPrchsDocSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else {
                                                                $trsctnLnID = -1;
                                                                $trsctnLnItmID = -1;
                                                                $trsctnLnQty = 0;
                                                                $trsctnLnUnitPrice = 0;
                                                                $trsctnLnAmnt = 0;
                                                                $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                $trsctnLnStoreID = -1;
                                                                $trsctnLnCurID = $fnccurid;
                                                                $trsctnLnSrcLnID = -1;
                                                                $trsctnLnAvlblQty = 0;
                                                                $trsctnLnTxID = -1;
                                                                $trsctnLnDscntID = -1;
                                                                $trsctnLnChrgID = -1;
                                                                $trsctnLnUomID = -1;
                                                                $trsctnLnUomNm = "each";
                                                                $trsctnLnDesc = "";
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneScmPrchsDocSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmPrchsDocSmryLinesTable tr').index(this));">
                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                                    <td class="lovtd" style="">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_SrcDocLnID" value="<?php echo $trsctnLnSrcLnID; ?>" style="width:100% !important;">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmPrchsDocSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmPrchsDocSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmPrchsDocVchType; ?>', 'false', function () {
                                                                                            var a = 1;
                                                                                        });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                        ?>
                                                                            <span><?php echo $trsctnLnDesc; ?></span>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: right;">
                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_QTY" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                                                                                                                                                                                                                        echo $trsctnLnQty;
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_QTY', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');">
                                                                    </td>
                                                                    <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">
                                                                        <div class="" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmPrchsDocID; ?>, 2, 'oneScmPrchsDocSmryRow_<?php echo $cntr; ?>');">
                                                                                <span class="" id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                                                                                                                                                                                                                        echo number_format($trsctnLnUnitPrice, 5);
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmPrchsDocSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                                                                                                                                                                                                                    echo number_format($trsctnLnAmnt, 2);
                                                                                                                                                                                                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmPrchsDocSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');">
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: right;">
                                                                        <input type="text" class="form-control jbDetAccRate1" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_SrcQTY" name="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_SrcQTY" value="<?php
                                                                                                                                                                                                                                                                    echo $trsctnLnAvlblQty;
                                                                                                                                                                                                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_SrcQTY', 'oneScmPrchsDocSmryLinesTable', 'jbDetAccRate1');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');">
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" onclick="getScmSalesInvcItems('oneScmPrchsDocSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmPrchsDocVchType; ?>', 'true', function () {
                                                                                            var a = 1;
                                                                                        });" style="padding:2px !important;">
                                                                            <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Tax Codes" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_TaxID', '', 'clear', 1, '');" style="padding:2px !important;">
                                                                            <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Discounts" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_DscntID', '', 'clear', 1, '');" style="padding:2px !important;">
                                                                            <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmryRow<?php echo $cntr; ?>_ChrgID" value="<?php echo $trsctnLnChrgID; ?>" style="width:100% !important;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Extra Charges" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnChrgID; ?>', 'oneScmPrchsDocSmryRow<?php echo $cntr; ?>_ChrgID', '', 'clear', 1, '');" style="padding:2px !important;">
                                                                            <img src="cmn_images/truck571d7f45.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmPrchsDocDetLn('oneScmPrchsDocSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
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
                                                                <th>TOTALS:</th>
                                                                <th>&nbsp;</th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdRIJbSmryAmtTtlBtn\">" . number_format(
                                                                        $ttlTrsctnEntrdAmnt,
                                                                        2,
                                                                        '.',
                                                                        ','
                                                                    ) . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdRIJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                                </th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="col-md-2" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneScmPrchsDocSmry1Table" cellspacing="0" width="100%" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Summary Item</th>
                                                                <th style="text-align:right;">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_DocSmryLns($sbmtdScmPrchsDocID, $scmPrchsDocVchType);
                                                            $ttlTrsctnEntrdAmnt = 0;
                                                            $trnsBrkDwnVType = "VIEW";
                                                            if ($mkReadOnly == "") {
                                                                $trnsBrkDwnVType = "EDIT";
                                                            }
                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                $trsctnLineID = (float) $rowRw[0];
                                                                $trsctnLineDesc = $rowRw[1];
                                                                $entrdAmnt = (float) $rowRw[2];
                                                                $trsctnCodeBhndID = (int) $rowRw[3];
                                                                $shdAutoCalc = $rowRw[4];
                                                                $cntr += 1;
                                                            ?>
                                                                <tr id="oneScmPrchsDocSmry1Row_<?php echo $cntr; ?>">
                                                                    <td class="lovtd" style="">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmry1Row<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmPrchsDocSmry1Row<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $trsctnCodeBhndID; ?>" style="width:100% !important;">
                                                                        <span><?php echo $trsctnLineDesc; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;">
                                                                        <span><?php
                                                                                echo number_format($entrdAmnt, 2);
                                                                                ?></span>
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
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdScmPrchsDocID = isset($_POST['sbmtdScmPrchsDocID']) ? cleanInputData($_POST['sbmtdScmPrchsDocID']) : -1;
                if (!$canAdd || ($sbmtdScmPrchsDocID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdScmPrchsDocID;
                $total = get_Total_CnsgnRcpt_Attachments($srchFor, $pkID, "PURCHASE");
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_CnsgnRcpt_Attachments($srchFor, $curIdx, $lmtSze, $pkID, "PURCHASE", $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
            ?>
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdPrchsDocDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdPrchsDocDocsRow__WWW123WWW\">"
                                . "<td class=\"lovtd\"><span>New</span></td>"
                                . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdPrchsDocDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdPrchsDocDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdPrchsDocDocsRow_WWW123WWW_DocCtgryNm', 'attchdPrchsDocDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdPrchsDocDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToPrchsDocDocs('attchdPrchsDocDocsRow_WWW123WWW_DocFile','attchdPrchsDocDocsRow_WWW123WWW_AttchdDocsID','attchdPrchsDocDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdPrchsDocDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdPrchsDocDoc('attchdPrchsDocDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdPrchsDocDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdPrchsDocDocsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncAttchdPrchsDocDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmPrchsDocID=<?php echo $sbmtdScmPrchsDocID; ?>', 'ReloadDialog');">
                                    <input id="attchdPrchsDocDocsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdPrchsDocDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmPrchsDocID=<?php echo $sbmtdScmPrchsDocID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdPrchsDocDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmPrchsDocID=<?php echo $sbmtdScmPrchsDocID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdPrchsDocDocsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getAttchdPrchsDocDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmPrchsDocID=<?php echo $sbmtdScmPrchsDocID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdPrchsDocDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmPrchsDocID=<?php echo $sbmtdScmPrchsDocID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdPrchsDocDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/Prchs/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                        ?>
                                            <tr id="attchdPrchsDocDocsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span><?php
                                                                        echo ($curIdx * $lmtSze) + ($cntr);
                                                                        ?></span></td>
                                                <td class="lovtd">
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdPrchsDocDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdPrchsDocDoc('attchdPrchsDocDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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