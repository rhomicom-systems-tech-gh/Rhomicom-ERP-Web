<?php
$canAdd = test_prmssns($dfltPrvldgs[22], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[87], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[23], $mdlNm);
$canRvwApprvDocs = $canEdt;
$cancelDocs = $canEdt;

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
                    echo deleteCnsgnRcptHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Doc Header Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteCnsgnRcptLine($pKeyID, $pKeyNm);
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
                //Save Consignment Receipt Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdScmCnsgnRcptID = isset($_POST['sbmtdScmCnsgnRcptID']) ? (float) cleanInputData($_POST['sbmtdScmCnsgnRcptID']) : -1;
                $scmCnsgnRcptDocNum = isset($_POST['scmCnsgnRcptDocNum']) ? cleanInputData($_POST['scmCnsgnRcptDocNum']) : "";
                $scmCnsgnRcptDfltTrnsDte = isset($_POST['scmCnsgnRcptDfltTrnsDte']) ? cleanInputData($_POST['scmCnsgnRcptDfltTrnsDte']) : '';
                $scmCnsgnRcptType = isset($_POST['scmCnsgnRcptType']) ? cleanInputData($_POST['scmCnsgnRcptType']) : '';
                $scmCnsgnRcptInvcCur = isset($_POST['scmCnsgnRcptInvcCur']) ? cleanInputData($_POST['scmCnsgnRcptInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $scmCnsgnRcptInvcCurID = getPssblValID($scmCnsgnRcptInvcCur, $curLovID);
                $scmCnsgnRcptTtlAmnt = isset($_POST['scmCnsgnRcptTtlAmnt']) ? (float) cleanInputData($_POST['scmCnsgnRcptTtlAmnt']) : 0;
                $scmCnsgnRcptExRate = isset($_POST['scmCnsgnRcptExRate']) ? (float) cleanInputData($_POST['scmCnsgnRcptExRate']) : 1;
                $funcExchRate = round(get_LtstExchRate($scmCnsgnRcptInvcCurID, $fnccurid, $scmCnsgnRcptDfltTrnsDte), 4);
                if ($scmCnsgnRcptExRate == 0 || $scmCnsgnRcptExRate == 1) {
                    $scmCnsgnRcptExRate = $funcExchRate;
                }
                $scmCnsgnRcptSpplrID = isset($_POST['scmCnsgnRcptSpplrID']) ? (float) cleanInputData($_POST['scmCnsgnRcptSpplrID']) : -1;
                $scmCnsgnRcptSpplrSiteID = isset($_POST['scmCnsgnRcptSpplrSiteID']) ? (float) cleanInputData($_POST['scmCnsgnRcptSpplrSiteID']) : -1;
                $scmCnsgnRcptDfltBalsAcntID = isset($_POST['scmCnsgnRcptDfltBalsAcntID']) ? (float) cleanInputData($_POST['scmCnsgnRcptDfltBalsAcntID']) : -1;
                $scmCnsgnRcptDesc = isset($_POST['scmCnsgnRcptDesc']) ? cleanInputData($_POST['scmCnsgnRcptDesc']) : '';
                $srcCnsgnRcptDocNum = isset($_POST['srcCnsgnRcptDocNum']) ? cleanInputData($_POST['srcCnsgnRcptDocNum']) : '';
                $srcCnsgnRcptDocID = isset($_POST['srcCnsgnRcptDocID']) ? (float) cleanInputData($_POST['srcCnsgnRcptDocID']) : -1;

                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                if (strlen($scmCnsgnRcptDesc) > 499) {
                    $scmCnsgnRcptDesc = substr($scmCnsgnRcptDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($scmCnsgnRcptDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($scmCnsgnRcptType == "") {
                    $exitErrMsg .= "Document Type cannot be empty!<br/>";
                }
                if ($scmCnsgnRcptDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($scmCnsgnRcptDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                /* if ($scmCnsgnRcptCstmrID <= 0) {
                  $exitErrMsg .= "Customer Name cannot be empty!<br/>";
                  }
                  if ($scmCnsgnRcptCstmrSiteID <= 0) {
                  $exitErrMsg .= "Customer Site cannot be empty!<br/>";
                  } */
                if ($scmCnsgnRcptDfltBalsAcntID <= 0) {
                    $exitErrMsg .= "Please enter a Supplier Payables Account!<br/>";
                }
                $oldPtyCashID = getGnrlRecID("inv.inv_consgmt_rcpt_hdr", "rcpt_number", "rcpt_id", $scmCnsgnRcptDocNum, $orgID);
                if ($oldPtyCashID > 0 && $oldPtyCashID != $sbmtdScmCnsgnRcptID) {
                    $exitErrMsg .= "New Document Number/Name is already in use in this Organization!<br/>";
                }
                $apprvlStatus = "Incomplete";
                $nxtApprvlActn = "Receive";
                $srcDocHdrID = $srcCnsgnRcptDocID;
                $srcDocType = "Purchase Order";
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdScmCnsgnRcptID'] = $sbmtdScmCnsgnRcptID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdScmCnsgnRcptID <= 0) {
                    createCnsgnRcpHdr(
                        $orgID,
                        $scmCnsgnRcptDocNum,
                        $scmCnsgnRcptDesc,
                        $scmCnsgnRcptDfltTrnsDte,
                        $scmCnsgnRcptSpplrID,
                        $scmCnsgnRcptSpplrSiteID,
                        $apprvlStatus,
                        $nxtApprvlActn,
                        $srcCnsgnRcptDocID,
                        $scmCnsgnRcptDfltBalsAcntID,
                        $scmCnsgnRcptInvcCurID,
                        $scmCnsgnRcptExRate
                    );
                    $sbmtdScmCnsgnRcptID = getGnrlRecID("inv.inv_consgmt_rcpt_hdr", "rcpt_number", "rcpt_id", $scmCnsgnRcptDocNum, $orgID);
                } else if ($sbmtdScmCnsgnRcptID > 0) {
                    updtCnsgnRcpHdr(
                        $sbmtdScmCnsgnRcptID,
                        $scmCnsgnRcptDocNum,
                        $scmCnsgnRcptDesc,
                        $scmCnsgnRcptDfltTrnsDte,
                        $scmCnsgnRcptSpplrID,
                        $scmCnsgnRcptSpplrSiteID,
                        $apprvlStatus,
                        $nxtApprvlActn,
                        $srcCnsgnRcptDocID,
                        $scmCnsgnRcptDfltBalsAcntID,
                        $scmCnsgnRcptInvcCurID,
                        $scmCnsgnRcptExRate
                    );
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDetTransLines, "|~") != "" && $sbmtdScmCnsgnRcptID > 0) {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 13) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_ItmID = (float) cleanInputData1($crntRow[1]);
                            $ln_StoreID = (int) cleanInputData1($crntRow[2]);
                            $ln_LineDesc = cleanInputData1($crntRow[3]);
                            $ln_QTY = (float) (cleanInputData1($crntRow[4]));
                            $ln_UnitPrice = (float) cleanInputData1($crntRow[5]);
                            $ln_PODocLnID = (int) (cleanInputData1($crntRow[6]));
                            $ln_ManDte = cleanInputData1($crntRow[7]);
                            $ln_ExpryDte = cleanInputData1($crntRow[8]);
                            $ln_TagNo = cleanInputData1($crntRow[9]);
                            $ln_SerialNo = cleanInputData1($crntRow[10]);
                            $ln_CnsgnCdtn = cleanInputData1($crntRow[11]);
                            $ln_ExtraDesc = cleanInputData1($crntRow[12]);
                            preg_match_all("/\[[^\]]*\]/", $ln_ExtraDesc, $matches);
                            //var_dump($matches);
                            if (trim($ln_ExtraDesc) != "" && strpos($matches[0][0], "[CS No.:") !== FALSE) {
                                $ln_ExtraDesc = str_replace($matches[0][0], "", $ln_ExtraDesc);
                            }
                            //var_dump($ln_ExtraDesc);
                            //exit();
                            $errMsg = "";
                            if ($ln_LineDesc === "" || $ln_ItmID <= 0 || $ln_QTY <= 0) {
                                $errMsg = "Row " . ($y + 1) . ":- Item Description and Quantity are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                //Create Sales Doc Lines
                                if ($ln_LineDesc != "" && $ln_ItmID > 0 && $ln_QTY > 0) {
                                    if ($ln_TrnsLnID <= 0) {
                                        $afftctd += createCnsgnRcptLine(
                                            $ln_QTY,
                                            $ln_UnitPrice,
                                            $ln_ExpryDte,
                                            $ln_ManDte,
                                            $ln_TagNo,
                                            $ln_SerialNo,
                                            $ln_PODocLnID,
                                            $ln_CnsgnCdtn,
                                            $ln_ExtraDesc,
                                            $ln_ItmID,
                                            $ln_StoreID,
                                            $sbmtdScmCnsgnRcptID
                                        );
                                    } else {
                                        $afftctd += updtCnsgnRcptLine(
                                            $ln_TrnsLnID,
                                            $ln_QTY,
                                            $ln_UnitPrice,
                                            $ln_ExpryDte,
                                            $ln_ManDte,
                                            $ln_TagNo,
                                            $ln_SerialNo,
                                            $ln_PODocLnID,
                                            $ln_CnsgnCdtn,
                                            $ln_ExtraDesc,
                                            $ln_ItmID,
                                            $ln_StoreID,
                                            $sbmtdScmCnsgnRcptID
                                        );
                                    }
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
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
                        $exitErrMsg = approve_cnsgn_rcpt($sbmtdScmCnsgnRcptID, "Receipt");
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
                $arr_content['sbmtdScmCnsgnRcptID'] = $sbmtdScmCnsgnRcptID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 20) {
                //Upload Attachment
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdScmCnsgnRcptID = isset($_POST['sbmtdScmCnsgnRcptID']) ? cleanInputData($_POST['sbmtdScmCnsgnRcptID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdScmCnsgnRcptID;
                if ($attchmentID > 0) {
                    uploadDaCnsgnRcptDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewCnsgnRcptDocID();
                    createCnsgnRcptDoc($attchmentID, $pkID, "RECEIPT", $docCtrgrName, "");
                    uploadDaCnsgnRcptDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/Rcpts/" . $nwImgLoc;
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
                $scmCnsgnRcptDesc = isset($_POST['scmCnsgnRcptDesc']) ? cleanInputData($_POST['scmCnsgnRcptDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdScmCnsgnRcptID = isset($_POST['sbmtdScmCnsgnRcptID']) ? cleanInputData($_POST['sbmtdScmCnsgnRcptID']) : -1;
                if (!$cancelDocs) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                $p_dochdrtype = "";
                $scmInvcDfltTrnsDte = "";
                $scmInvcDocNum = "";
                if ($sbmtdScmCnsgnRcptID > 0) {
                    $result = get_One_CnsgnRcptDocHdr($sbmtdScmCnsgnRcptID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmInvcDfltTrnsDte = $row[1] . " 12:00:00";
                        $scmInvcDocNum = $row[4];
                        $p_dochdrtype = $row[5];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                    }
                }
                if ($rqStatus == "Incomplete" && $sbmtdScmCnsgnRcptID > 0) {
                    echo deleteCnsgnRcptHdrNDet($sbmtdScmCnsgnRcptID, $scmInvcDocNum);
                    exit();
                } else {
                    $exitErrMsg = cancel_cnsgn_rcpt($sbmtdScmCnsgnRcptID, "Receipt", $orgID, $usrID);
                    $arr_content['sbmtdScmCnsgnRcptID'] = $sbmtdScmCnsgnRcptID;
                    $arr_content['percent'] = 100;
                    if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                        execUpdtInsSQL("UPDATE inv.inv_consgmt_rcpt_hdr SET description='" . loc_db_escape_string($scmCnsgnRcptDesc) . "' WHERE (rcpt_id = " . $sbmtdScmCnsgnRcptID . ")");
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
                                    <span style=\"text-decoration:none;\">Receipts</span>
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
                    $total = get_Total_CnsgnRcpt($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Basic_CnsgnRcpt($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
                    $colClassType3 = "col-md-5";
?>
                    <form id='scmCnsgnRcptForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245">CONSIGNMENT RECEIPTS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                                $colClassType3 = "col-md-10";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="scmCnsgnRcptSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncScmCnsgnRcpt(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="scmCnsgnRcptPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                        <input id="sbmtdScmCnsgnRcptPOID" type="hidden" value="-1">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmCnsgnRcpt('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmCnsgnRcpt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmCnsgnRcptSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "");
                                            $srchInsArrys = array(
                                                "Document Number", "Document Description",
                                                "Supplier Name", "Approval Status", "Created By"
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
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmCnsgnRcptDsplySze" style="min-width:70px !important;">
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
                                                <a href="javascript:getScmCnsgnRcpt('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getScmCnsgnRcpt('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
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
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmCnsgnRcptForm(-1, 1, 'ShowDialog', 'Miscellaneous Receipt');" data-toggle="tooltip" data-placement="bottom" title="Add New Miscellaneous Receipt">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                NEW MISC. RECEIPT
                                            </button>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Approved Purchase Orders', 'allOtherInputOrgID', '', '', 'radio', true, '', 'sbmtdScmCnsgnRcptPOID', '', 'clear', 1, '', function () {
                                                        getOneScmCnsgnRcptForm(-1, 1, 'ShowDialog', 'Purchase Order Receipt');
                                                    });" data-toggle="tooltip" data-placement="bottom" title="Add New Purchase Order Receipt">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                NEW PO RECEIPT
                                            </button>
                                        </div>
                                    <?php }
                                    ?>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;display:none;">
                                        <div class="form-check" style="font-size: 12px !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $shwUnpaidOnlyChkd = "";
                                                if ($qShwUnpaidOnly == true) {
                                                    $shwUnpaidOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmCnsgnRcpt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmCnsgnRcptShwUnpaidOnly" name="scmCnsgnRcptShwUnpaidOnly" <?php echo $shwUnpaidOnlyChkd; ?>>
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
                                                <input type="checkbox" class="form-check-input" onclick="getScmCnsgnRcpt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmCnsgnRcptShwUnpstdOnly" name="scmCnsgnRcptShwUnpstdOnly" <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="scmCnsgnRcptHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:35px;width:35px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th>Document Number/Type - Transaction Description</th>
                                                <th style="max-width:115px;width:115px;">Date Received</th>
                                                <th style="text-align:center;max-width:40px;width:40px;">CUR.</th>
                                                <th style="text-align:right;min-width:120px;width:120px;">Total Amount</th>
                                                <th style="max-width:115px;width:115px;">Document Status</th>
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
                                                <tr id="scmCnsgnRcptHdrsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Invoice" onclick="getOneScmCnsgnRcptForm(<?php echo $row[0]; ?>, 1, 'ShowDialog', '<?php echo $row[2]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <?php if ($canAdd === true) { ?>
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[1] . " " . $row[7] . " " . $row[3]; ?></td>
                                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[4]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                echo number_format((float) $row[5], 2);
                                                                                                                                ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if ($row[6] == "Received") {
                                                        $style1 = "color:green;";
                                                    } else if ($row[6] == "Cancelled") {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php echo $row[6]; ?></td>
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delScmCnsgnRcpt('scmCnsgnRcptHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="scmCnsgnRcptHdrsRow<?php echo $cntr; ?>_HdrID" name="scmCnsgnRcptHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                    ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|inv.inv_consgmt_rcpt_hdr|rcpt_id"), $smplTokenWord1));
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
                //New Receipt Form
                //var_dump($_POST);
                $lmtSze = isset($_POST['scmCnsgnRcptDsplySze']) ? cleanInputData($_POST['scmCnsgnRcptDsplySze']) : 50;
                $sbmtdScmCnsgnRcptID = isset($_POST['sbmtdScmCnsgnRcptID']) ? cleanInputData($_POST['sbmtdScmCnsgnRcptID']) : -1;
                $scmCnsgnRcptType = isset($_POST['scmCnsgnRcptType']) ? cleanInputData($_POST['scmCnsgnRcptType']) : "Miscellaneous Receipt";
                $scmCnsgnRcptSRC = isset($_POST['scmCnsgnRcptSRC']) ? cleanInputData($_POST['scmCnsgnRcptSRC']) : "NORMAL";
                $sbmtdScmCnsgnRcptPOID = isset($_POST['sbmtdScmCnsgnRcptPOID']) ? cleanInputData($_POST['sbmtdScmCnsgnRcptPOID']) : -1;
                $sbmtdScmCnsgnRcptITEMID = isset($_POST['sbmtdScmCnsgnRcptITEMID']) ? cleanInputData($_POST['sbmtdScmCnsgnRcptITEMID']) : -1;

                if (!$canAdd || ($sbmtdScmCnsgnRcptID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $orgnlScmCnsgnRcptID = $sbmtdScmCnsgnRcptID;
                $scmCnsgnRcptDfltTrnsDte = $gnrlTrnsDteDMY; //HMS
                $scmCnsgnRcptCreator = $uName;
                $scmCnsgnRcptCreatorID = $usrID;
                $gnrtdTrnsNo = "";
                $scmCnsgnRcptDesc = $scmCnsgnRcptType;

                $srcCnsgnRcptDocID = $sbmtdScmCnsgnRcptPOID;
                $srcCnsgnRcptDocTyp = "";
                $scmCnsgnRcptDocTmpltID = -1;
                $srcCnsgnRcptDocNum = "";

                $scmCnsgnRcptSpplr = "";
                $scmCnsgnRcptSpplrID = -1;
                $scmCnsgnRcptSpplrSite = "";
                $scmCnsgnRcptSpplrSiteID = -1;
                $scmCnsgnRcptSpplrClsfctn = "Supplier";
                $rqStatus = "Incomplete";
                $rqStatusNext = "Receive";
                $rqstatusColor = "red";

                $scmCnsgnRcptTtlAmnt = 0;
                $scmCnsgnRcptAppldAmnt = 0;
                $scmCnsgnRcptPayTerms = "";
                $scmCnsgnRcptPayMthd = "";
                $scmCnsgnRcptPayMthdID = -1;
                $scmCnsgnRcptPaidAmnt = 0;
                $scmCnsgnRcptGLBatch = "";
                $scmCnsgnRcptGLBatchID = -1;
                $scmCnsgnRcptSpplrInvcNum = "";
                $scmCnsgnRcptDocTmplt = "";
                $scmCnsgnRcptEvntRgstr = "";
                $scmCnsgnRcptEvntRgstrID = -1;
                $scmCnsgnRcptEvntCtgry = "";
                $scmCnsgnRcptEvntDocTyp = "";
                $scmCnsgnRcptDfltBalsAcnt = "";
                $scmCnsgnRcptInvcCurID = $fnccurid;
                $scmCnsgnRcptInvcCur = $fnccurnm;
                $scmCnsgnRcptExRate = 1;
                $scmCnsgnRcptIsPstd = "0";
                $scmCnsgnRcptAllwDues = "0";
                $scmCnsgnRcptAutoBals = "1";
                $otherModuleDocId = -1;
                $otherModuleDocTyp = "";
                $otherModuleDocNum = "";
                $sbmtdScmRcvblsInvcID = -1;
                $scmCnsgnRcptRcvblDocID = -1;
                $scmCnsgnRcptRcvblDoc = "";
                $scmCnsgnRcptRcvblDocType = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $scmCnsgnRcptDfltBalsAcntID = get_DfltSplrPyblsCashAcnt($scmCnsgnRcptSpplrID, $orgID);
                //echo "ACCID::".$scmCnsgnRcptDfltBalsAcntID;
                if ($sbmtdScmCnsgnRcptID > 0) {
                    $result = get_One_CnsgnRcptDocHdr($sbmtdScmCnsgnRcptID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmCnsgnRcptDfltTrnsDte = $row[1];
                        $scmCnsgnRcptCreator = $row[3];
                        $scmCnsgnRcptCreatorID = $row[2];
                        $gnrtdTrnsNo = $row[4];
                        $scmCnsgnRcptType = $row[5];
                        $scmCnsgnRcptDesc = $row[6];
                        $srcCnsgnRcptDocID = $row[7];
                        $srcCnsgnRcptDocNum = $row[35];
                        $scmCnsgnRcptSpplr = $row[9];
                        $scmCnsgnRcptSpplrID = $row[8];
                        $scmCnsgnRcptSpplrSite = $row[11];
                        $scmCnsgnRcptSpplrSiteID = $row[10];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                        $rqstatusColor = "red";

                        $scmCnsgnRcptPayTerms = $row[15];
                        $srcCnsgnRcptDocTyp = $row[16];
                        $scmCnsgnRcptPayMthd = $row[18];
                        $scmCnsgnRcptPayMthdID = $row[17];

                        $scmCnsgnRcptTtlAmnt = (float) $row[14];
                        $scmCnsgnRcptAppldAmnt = (float) $row[34];
                        $scmCnsgnRcptPaidAmnt = $row[19];
                        if (strpos($scmCnsgnRcptType, "Advance Payment") === FALSE) {
                            $scmCnsgnRcptAppldAmnt = $scmCnsgnRcptPaidAmnt;
                        }
                        $scmCnsgnRcptGLBatch = $row[21];
                        $scmCnsgnRcptGLBatchID = $row[20];
                        $scmCnsgnRcptSpplrInvcNum = $row[22];
                        $scmCnsgnRcptDocTmplt = $row[23];
                        $scmCnsgnRcptInvcCur = $row[25];
                        $scmCnsgnRcptInvcCurID = (float) $row[24];
                        if ($scmCnsgnRcptInvcCurID <= 0) {
                            $scmCnsgnRcptInvcCurID = $fnccurid;
                            $scmCnsgnRcptInvcCur = $fnccurnm;
                        }
                        $scmCnsgnRcptEvntRgstr = "";
                        $scmCnsgnRcptEvntRgstrID = $row[26];
                        $scmCnsgnRcptEvntCtgry = $row[27];
                        $scmCnsgnRcptEvntDocTyp = $row[28];
                        $scmCnsgnRcptDfltBalsAcntID = $row[29];
                        $scmCnsgnRcptDfltBalsAcnt = $row[30];
                        $scmCnsgnRcptIsPstd = $row[31];
                        $sbmtdScmRcvblsInvcID = (float) $row[32];
                        $scmCnsgnRcptRcvblDocID = $sbmtdScmRcvblsInvcID;
                        $scmCnsgnRcptRcvblDoc = $row[33];
                        $scmCnsgnRcptRcvblDocType = $row[34];
                        $scmCnsgnRcptExRate = (float) $row[36];
                        if ($rqStatus == "Received") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Incomplete") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Received") {
                                $mkRmrkReadOnly = "readonly=\"true\"";
                            }
                        }
                    }
                } else if ($scmCnsgnRcptDfltBalsAcntID > 0) {
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypes = array("Purchase Order Receipt", "Miscellaneous Receipt");
                    $docTypPrfxs = array("PO-RCPT", "MISC-RCPT");
                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $scmCnsgnRcptType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("inv.inv_consgmt_rcpt_hdr", "rcpt_number", "rcpt_id", $gnrtdTrnsNo1 . "%") + 1) . ""),
                        3,
                        '0',
                        STR_PAD_LEFT
                    );
                    $scmCnsgnRcptDfltBalsAcnt = getAccntNum($scmCnsgnRcptDfltBalsAcntID) . "." . getAccntName($scmCnsgnRcptDfltBalsAcntID);
                    $scmCnsgnRcptInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $scmCnsgnRcptDfltBalsAcntID);
                    if ($scmCnsgnRcptInvcCurID > 0) {
                        $scmCnsgnRcptInvcCur = getPssblValNm($scmCnsgnRcptInvcCurID);
                    }
                    if ($srcCnsgnRcptDocID > 0) {
                        $result = get_One_PrchsDocDocHdr($srcCnsgnRcptDocID);
                        if ($row = loc_db_fetch_array($result)) {
                            $srcCnsgnRcptDocNum = $row[4];
                            $srcCnsgnRcptDocTyp = $row[5];
                            $scmCnsgnRcptDesc = $row[6];
                            $scmCnsgnRcptSpplr = $row[9];
                            $scmCnsgnRcptSpplrID = $row[8];
                            $scmCnsgnRcptSpplrSite = $row[11];
                            $scmCnsgnRcptSpplrSiteID = $row[10];
                            $scmCnsgnRcptPayTerms = $row[15];
                            $scmCnsgnRcptInvcCur = $row[25];
                            $scmCnsgnRcptInvcCurID = $row[24];

                            $scmPrchsDocExRate = (float) $row[27];
                            $scmPrchsDocNeedByDte = $row[29];
                            if ($scmPrchsDocExRate == 0) {
                                $scmPrchsDocExRate = 1;
                            }
                            $scmCnsgnRcptExRate = $scmPrchsDocExRate;
                            $scmCnsgnRcptTtlAmnt = ((float) $row[14]);
                        }
                    }
                    $sbmtdScmCnsgnRcptID = createCnsgnRcpHdr(
                        $orgID,
                        $gnrtdTrnsNo,
                        $scmCnsgnRcptDesc,
                        $scmCnsgnRcptDfltTrnsDte,
                        $scmCnsgnRcptSpplrID,
                        $scmCnsgnRcptSpplrSiteID,
                        $rqStatus,
                        $rqStatusNext,
                        $srcCnsgnRcptDocID,
                        $scmCnsgnRcptDfltBalsAcntID,
                        $scmCnsgnRcptInvcCurID,
                        $scmCnsgnRcptExRate
                    );
                }
                $scmCnsgnRcptOustndngAmnt = $scmCnsgnRcptTtlAmnt - $scmCnsgnRcptPaidAmnt;
                $scmCnsgnRcptOustndngStyle = "color:red;";
                $scmCnsgnRcptPaidStyle = "color:black;";
                if ($scmCnsgnRcptOustndngAmnt <= 0) {
                    $scmCnsgnRcptOustndngStyle = "color:green;";
                }
                if ($scmCnsgnRcptPaidAmnt > 0 && $scmCnsgnRcptOustndngAmnt <= 0) {
                    $scmCnsgnRcptPaidStyle = "color:green;";
                } else if ($scmCnsgnRcptPaidAmnt > 0) {
                    $scmCnsgnRcptPaidStyle = "color:brown;";
                }
                $reportName = getEnbldPssblValDesc("Sales Invoice", getLovID("Document Custom Print Process Names"));
                if ($scmCnsgnRcptAllwDues == "1") {
                    $reportName = getEnbldPssblValDesc("Sales Invoice - Dues", getLovID("Document Custom Print Process Names"));
                }
                $reportTitle = str_replace("Pro-Forma Invoice", "Payment Voucher", $scmCnsgnRcptType);
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdScmCnsgnRcptID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="oneScmCnsgnRcptEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdScmCnsgnRcptID" name="sbmtdScmCnsgnRcptID" value="<?php echo $sbmtdScmCnsgnRcptID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="scmCnsgnRcptDocNum" name="scmCnsgnRcptDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="scmCnsgnRcptDfltTrnsDte" name="scmCnsgnRcptDfltTrnsDte" value="<?php echo $scmCnsgnRcptDfltTrnsDte; ?>" placeholder="Transactions Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" aria-label="..." id="scmCnsgnRcptType" name="scmCnsgnRcptType" value="<?php echo $scmCnsgnRcptType; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="srcCnsgnRcptDocNum" class="control-label col-md-4">Source Doc. No.:</label>
                                    <div class="col-md-8">
                                        <input type="hidden" class="form-control" aria-label="..." id="srcCnsgnRcptDocTyp" name="srcCnsgnRcptDocTyp" value="<?php echo $srcCnsgnRcptDocTyp; ?>">
                                        <input type="hidden" id="srcCnsgnRcptDocID" value="<?php echo $srcCnsgnRcptDocID; ?>">
                                        <input type="text" class="form-control" aria-label="..." id="srcCnsgnRcptDocNum" name="srcCnsgnRcptDocNum" value="<?php echo $srcCnsgnRcptDocNum; ?>" readonly="true" style="width:100%;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="scmCnsgnRcptSpplr" class="control-label col-md-4">Supplier:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmCnsgnRcptSpplr" name="scmCnsgnRcptSpplr" value="<?php echo $scmCnsgnRcptSpplr; ?>" readonly="true">
                                            <input type="hidden" id="scmCnsgnRcptSpplrID" value="<?php echo $scmCnsgnRcptSpplrID; ?>">
                                            <input type="hidden" id="scmCnsgnRcptSpplrClsfctn" value="<?php echo $scmCnsgnRcptSpplrClsfctn; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Supplier', 'ShowDialog', function () {}, 'scmCnsgnRcptSpplrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'scmCnsgnRcptSpplrClsfctn', 'radio', true, '', 'scmCnsgnRcptSpplrID', 'scmCnsgnRcptSpplr', 'clear', 1, '', function () {
                                                        getInvPyblsAcntInfo();
                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scmCnsgnRcptSpplrSite" class="control-label col-md-4">Site:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmCnsgnRcptSpplrSite" name="scmCnsgnRcptSpplrSite" value="<?php echo $scmCnsgnRcptSpplrSite; ?>" readonly="true">
                                            <input class="form-control" type="hidden" id="scmCnsgnRcptSpplrSiteID" value="<?php echo $scmCnsgnRcptSpplrSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'scmCnsgnRcptSpplrID', '', '', 'radio', true, '', 'scmCnsgnRcptSpplrSiteID', 'scmCnsgnRcptSpplrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
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
                                            <textarea class="form-control" rows="1" cols="20" id="scmCnsgnRcptDesc" name="scmCnsgnRcptDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $scmCnsgnRcptDesc; ?></textarea>
                                            <input class="form-control" type="hidden" id="scmCnsgnRcptDesc1" value="<?php echo $scmCnsgnRcptDesc; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmCnsgnRcptDesc');" style="max-width:30px;width:30px;">
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
                                        <input type="hidden" id="scmCnsgnRcptApprvlStatus" value="<?php echo $rqStatus; ?>">
                                        <button type="button" class="btn btn-default" style="height:37px;width:100% !important;" id="myScmCnsgnRcptStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:37px;">
                                                <?php
                                                echo $rqStatus; //. ($scmCnsgnRcptIsPstd == "1" ? " [Posted]" : " [Not Posted]")
                                                ?>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4" style="padding:0px 10px 0px 10px !important;">
                                        <label style="margin-bottom:0px !important;">Receipt Total:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $scmCnsgnRcptInvcCur; ?>', 'scmCnsgnRcptInvcCur', '', 'clear', 0, '', function () {
                                                        $('#scmCnsgnRcptInvcCur1').html($('#scmCnsgnRcptInvcCur').val());
                                                        $('#scmCnsgnRcptInvcCur2').html($('#scmCnsgnRcptInvcCur').val());
                                                        $('#scmCnsgnRcptInvcCur3').html($('#scmCnsgnRcptInvcCur').val());
                                                        $('#scmCnsgnRcptInvcCur4').html($('#scmCnsgnRcptInvcCur').val());
                                                        $('#scmCnsgnRcptInvcCur5').html($('#scmCnsgnRcptInvcCur').val());
                                                    });">
                                                <span class="" style="font-size: 20px !important;" id="scmCnsgnRcptInvcCur1"><?php echo $scmCnsgnRcptInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="scmCnsgnRcptInvcCur" value="<?php echo $scmCnsgnRcptInvcCur; ?>">
                                            <input type="hidden" id="scmCnsgnRcptInvcCurID" value="<?php echo $scmCnsgnRcptInvcCurID; ?>">
                                            <input class="form-control" type="text" id="scmCnsgnRcptTtlAmnt" value="<?php
                                                                                                                    echo number_format($scmCnsgnRcptTtlAmnt, 2);
                                                                                                                    ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('scmCnsgnRcptTtlAmnt');" readonly="true" />
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
                                                <span class="" style="font-size: 20px !important;" id="scmCnsgnRcptInvcCur4"><?php echo $scmCnsgnRcptInvcCur; ?></span>
                                                <span class="" style="font-size: 20px !important;" id="scmCnsgnRcptInvcCur6"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                            </label>
                                            <input type="text" class="form-control" aria-label="..." id="scmCnsgnRcptExRate" name="scmCnsgnRcptExRate" value="<?php
                                                                                                                                                                echo number_format($scmCnsgnRcptExRate, 4);
                                                                                                                                                                ?>" style="font-size: 18px !important;font-weight:bold;width:100%;" <?php echo $mkReadOnly; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none;">
                                    <label for="scmCnsgnRcptGLBatch" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">GL Batch Name:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="scmCnsgnRcptGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" placeholder="" value="<?php echo $scmCnsgnRcptGLBatch; ?>" readonly="true" />
                                            <input type="hidden" id="scmCnsgnRcptGLBatchID" value="<?php echo $scmCnsgnRcptGLBatchID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $scmCnsgnRcptGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdScmCnsgnRcptID; ?>, 'Sales Invoice');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scmCnsgnRcptRcvblDoc" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Payable Doc.</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="scmCnsgnRcptRcvblDoc" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" placeholder="" value="<?php echo $scmCnsgnRcptRcvblDoc; ?>" readonly="true" />
                                            <input type="hidden" id="scmCnsgnRcptRcvblDocID" value="<?php echo $scmCnsgnRcptRcvblDocID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbPyblsInvcForm(<?php echo $scmCnsgnRcptRcvblDocID; ?>, 1, 'ReloadDialog', '<?php echo $scmCnsgnRcptRcvblDocType; ?>',<?php echo $sbmtdScmCnsgnRcptID; ?>, 'Receipt');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scmCnsgnRcptDfltBalsAcnt" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Payable Account:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="scmCnsgnRcptDfltBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type="text" min="0" placeholder="" value="<?php echo $scmCnsgnRcptDfltBalsAcnt; ?>" readonly="true" />
                                            <input type="hidden" id="scmCnsgnRcptDfltBalsAcntID" value="<?php echo $scmCnsgnRcptDfltBalsAcntID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmCnsgnRcptDfltBalsAcntID', 'scmCnsgnRcptDfltBalsAcnt', 'clear', 1, '', function () {});">
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
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                $trsctnLnStoreID = $selectedStoreID;
                                                $trsctnLnStoreNm = getStoreNm($trsctnLnStoreID);
                                                $edtPriceRdOnly = "";
                                                $nwRowHtml33 = "<tr id=\"oneScmCnsgnRcptSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneScmCnsgnRcptSmryLinesTable tr').index(this));\">"
                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\"  style=\"\">  
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_ItmID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_StoreID\" value=\"" . $trsctnLnStoreID . "\" style=\"width:100% !important;\"> 
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_CnsgnID\" value=\"-1\" style=\"width:100% !important;\">    
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_PODocLnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                           <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                    <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate jbDetDesc\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_LineDesc\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow_WWW123WWW_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');\" onblur=\"afterSalesInvcItmSlctn('oneScmCnsgnRcptSmryRow__WWW123WWW');\" onchange=\"autoCreateSalesLns = 99;\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getScmSalesInvcItems('oneScmCnsgnRcptSmryRow__WWW123WWW', 'ShowDialog', 'Receipt', 'false', function () {});\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                            </div>
                                                                        </td> 
                                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_QTY\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_QTY\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow_WWW123WWW_QTY', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmCnsgnRcptSmryTtl();\">                                                    
                                                                        </td>                                               
                                                                        <td class=\"lovtd\" style=\"max-width:35px;width:35px;text-align: center;\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_UomID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                                <label class=\"btn btn-primary btn-file\" onclick=\"getOneScmUOMBrkdwnForm(-1, 2, 'oneScmCnsgnRcptSmryRow__WWW123WWW');\">
                                                                                    <span class=\"\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_UomNm1\">each</span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_UnitPrice\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_UnitPrice\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow_WWW123WWW_UnitPrice', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmCnsgnRcptSmryTtl();\">                                                    
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <input type=\"text\" class=\"form-control jbDetCrdt\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_LineAmt\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_LineAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow_WWW123WWW_LineAmt', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\" onchange=\"calcAllScmCnsgnRcptSmryTtl();\">                                                    
                                                                        </td>                                            
                                                                        <td class=\"lovtd\"  style=\"\">  
                                                                                <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_StoreNm\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_StoreNm\" value=\"" . $trsctnLnStoreNm . "\" style=\"width:100% !important;\" readonly=\"true\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow_WWW123WWW_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '', 'oneScmCnsgnRcptSmryRow_WWW123WWW_StoreID', 'oneScmCnsgnRcptSmryRow_WWW123WWW_StoreNm', 'clear',0, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                                </div>
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                                <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\" style=\"width:100% !important;\">
                                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_ManDte\" value=\"\">
                                                                                    <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                </div>                                                         
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                                <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\" style=\"width:100% !important;\">
                                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_ExpryDte\" value=\"\">
                                                                                    <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                </div>                                                         
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_TagNo\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_TagNo\" value=\"\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                        </td> 
                                                                        <td class=\"lovtd\">
                                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_SerialNo\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_SerialNo\" value=\"\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                        </td>                                            
                                                                        <td class=\"lovtd\"  style=\"\"> 
                                                                                <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_CnsgnCdtn\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_CnsgnCdtn\" value=\"Good\" style=\"width:100% !important;\" readonly=\"true\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow_WWW123WWW_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Consignment Conditions', '', '', '', 'radio', true, '', 'oneScmCnsgnRcptSmryRow_WWW123WWW_CnsgnCdtn', '', 'clear', 1, '');\" data-toggle=\"tooltip\" title=\"Existing Consignment Condition\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                                </div>
                                                                        </td>   
                                                                        <td class=\"lovtd\">
                                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneScmCnsgnRcptSmryRow_WWW123WWW_ExtraDesc\" name=\"oneScmCnsgnRcptSmryRow_WWW123WWW_ExtraDesc\" value=\"\" style=\"width:100% !important;text-align: left;\">                                                    
                                                                        </td>  
                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Item Consignments\" 
                                                                                    onclick=\"getScmSalesInvcItems('oneScmCnsgnRcptSmryRow__WWW123WWW', 'ShowDialog', 'Receipt', 'true', function () {});\" style=\"padding:2px !important;\"> 
                                                                                <img src=\"cmn_images/chcklst3.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                            </button>
                                                                        </td>  
                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View/Edit Item's New Selling Price\" 
                                                                                    onclick=\"getOneINVItmPricesForm('oneScmCnsgnRcptSmryRow__WWW123WWW', 'ShowDialog');\" style=\"padding:2px !important;\"> 
                                                                                <img src=\"cmn_images/payment_256.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                            </button>
                                                                        </td>
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delScmCnsgnRcptDetLn('oneScmCnsgnRcptSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";
                                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                                ?>
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <input type="hidden" id="nwSalesDocLineHtm" value="<?php echo $nwRowHtml33; ?>">
                                                            <button id="addNwScmCnsgnRcptSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewScmSalesInvcRows('oneScmCnsgnRcptSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmCnsgnRcptDocsForm(<?php echo $sbmtdScmCnsgnRcptID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmCnsgnRcptForm(<?php echo $sbmtdScmCnsgnRcptID; ?>, 1, 'ReloadDialog', '<?php echo $scmCnsgnRcptType; ?>', '<?php echo $scmCnsgnRcptSRC; ?>',<?php echo $sbmtdScmCnsgnRcptITEMID; ?>);"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdCnsgnRcptValsTtlBtn"><?php echo $scmCnsgnRcptInvcCur; ?>
                                                                <?php
                                                                echo number_format($scmCnsgnRcptTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdCnsgnRcptValsTtlVal" value="<?php echo $scmCnsgnRcptTtlAmnt; ?>">
                                                        </button>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmCnsgnRcptDsplySze1" style="max-width:70px !important;display:inline-block;" onchange="getOneScmCnsgnRcptForm(<?php echo $sbmtdScmCnsgnRcptID; ?>, 1, 'ReloadDialog', '<?php echo $scmCnsgnRcptType; ?>', '<?php echo $scmCnsgnRcptSRC; ?>',<?php echo $sbmtdScmCnsgnRcptITEMID; ?>);" data-toggle="tooltip" title="No. of Records to Display">
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
                                                            if ($rqStatus == "Incomplete") {
                                                            ?>
                                                                <?php if ($canEdt) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmCnsgnRcptForm('<?php echo $fnccurnm; ?>', 0, '<?php echo $scmCnsgnRcptSRC; ?>',<?php echo $sbmtdScmCnsgnRcptITEMID; ?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                <?php } ?>
                                                                <?php if ($canRvwApprvDocs) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmCnsgnRcptForm('<?php echo $fnccurnm; ?>', 2, '<?php echo $scmCnsgnRcptSRC; ?>',<?php echo $sbmtdScmCnsgnRcptITEMID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                    </button>
                                                                <?php
                                                                }
                                                            } else if ($rqStatus == "Received") {
                                                                if ($cancelDocs) {
                                                                ?>
                                                                    <button id="fnlzeRvrslScmCnsgnRcptBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmCnsgnRcptRvrslForm('<?php echo $fnccurnm; ?>', 1, '<?php echo $scmCnsgnRcptSRC; ?>',<?php echo $sbmtdScmCnsgnRcptITEMID; ?>);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Receipt&nbsp;</button>
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
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneScmCnsgnRcptLnsTblSctn">
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="cnsgnRcptDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneScmCnsgnRcptSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="min-width:180px;">Item Code/Description</th>
                                                                <th style="max-width:50px;width:50px;text-align: right;">QTY</th>
                                                                <th style="max-width:55px;text-align: center;">UOM.</th>
                                                                <th style="max-width:170px;width:140px;text-align: right;">Unit Cost Price</th>
                                                                <th style="max-width:170px;width:120px;text-align: right;">Total Amount</th>
                                                                <th style="min-width:130px;">Destination Store</th>
                                                                <th style="min-width:120px;">Manufacture Date</th>
                                                                <th style="min-width:120px;">Expiry Date</th>
                                                                <th style="max-width:100px;width:100px;">Tag Number</th>
                                                                <th style="max-width:100px;width:100px;">Serial Number</th>
                                                                <th style="max-width:70px;width:70px;">Consign. Condition</th>
                                                                <th style="min-width:120px;">Remarks</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">CS</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">SP</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_CnsgnRcptDocDet($sbmtdScmCnsgnRcptID, $lmtSze);
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
                                                                    $trsctnLnItmNm = $rowRw[2];
                                                                    $trsctnLnQty = (float) $rowRw[3];
                                                                    $trsctnLnUnitPrice = (float) $rowRw[4];
                                                                    $trsctnLnAmnt = $trsctnLnQty * $trsctnLnUnitPrice;
                                                                    $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                    $trsctnLnPoLnID = (float) $rowRw[5];
                                                                    $trsctnLnStoreID = (float) $rowRw[6];
                                                                    $trsctnLnStoreNm = $rowRw[7];
                                                                    $trsctnLnStckID = (float) $rowRw[8];
                                                                    $trsctnLnExpDte = $rowRw[9];
                                                                    $trsctnLnManDte = $rowRw[10];
                                                                    $trsctnLnSpan = $rowRw[11];
                                                                    $trsctnLnTagNo = $rowRw[12];
                                                                    $trsctnLnSerialNo = $rowRw[13];
                                                                    $trsctnLnCnsgnCndtn = $rowRw[14];
                                                                    $trsctnLnRmrks = $rowRw[15];
                                                                    $trsctnLnCnsgnID = (float) $rowRw[16];
                                                                    $trsctnLnUomID = (float) $rowRw[17];
                                                                    $trsctnLnUomNm = $rowRw[18];
                                                                    $cntr += 1;
                                                            ?>
                                                                    <tr id="oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmCnsgnRcptSmryLinesTable tr').index(this));">
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                                        <td class="lovtd" style="">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnID" value="<?php echo $trsctnLnCnsgnID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_PODocLnID" value="<?php echo $trsctnLnPoLnID; ?>" style="width:100% !important;">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnItmNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmCnsgnRcptType; ?>', 'false', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                            ?>
                                                                                <span><?php echo $trsctnLnItmNm; ?></span>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: right;">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                                                                                                                                                                                                                            echo $trsctnLnQty;
                                                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                        </td>
                                                                        <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">
                                                                            <div class="" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmCnsgnRcptID; ?>, 2, 'oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');">
                                                                                    <span class="" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                                                                                                                                                                                                                            echo number_format($trsctnLnUnitPrice, 5);
                                                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $edtPriceRdOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                                                                                                                                                                                                                        echo number_format($trsctnLnAmnt, 2);
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm" value="<?php echo $trsctnLnStoreNm; ?>" style="width:100% !important;" readonly="true" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '<?php echo $trsctnLnStoreID; ?>', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreID', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm', 'clear', 0, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                            ?>
                                                                                <span><?php echo $trsctnLnStoreNm; ?></span>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100% !important;">
                                                                                    <input class="form-control" size="16" type="text" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ManDte" value="<?php echo $trsctnLnManDte; ?>" readonly="">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLnManDte; ?></span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100% !important;">
                                                                                    <input class="form-control" size="16" type="text" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExpryDte" value="<?php echo $trsctnLnExpDte; ?>" readonly="">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLnExpDte; ?></span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TagNo" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TagNo" value="<?php echo $trsctnLnTagNo; ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_SerialNo" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_SerialNo" value="<?php echo $trsctnLnSerialNo; ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn" value="<?php echo $trsctnLnCnsgnCndtn; ?>" style="width:100% !important;" readonly="true" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Consignment Conditions', '', '', '', 'radio', true, '', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn', '', 'clear', 1, '');" data-toggle="tooltip" title="Existing Consignment Condition">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                            ?>
                                                                                <span><?php echo $trsctnLnCnsgnCndtn; ?></span>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExtraDesc" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExtraDesc" value="<?php echo "[CS No.:" . $trsctnLnCnsgnID . "]" . $trsctnLnRmrks; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?>>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" onclick="getScmSalesInvcItems('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmCnsgnRcptType; ?>', 'true', function () {
                                                                                                var a = 1;
                                                                                            });" style="padding:2px !important;">
                                                                                <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Item's New Selling Price" onclick="getOneINVItmPricesForm('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog');" style="padding:2px !important;">
                                                                                <img src="cmn_images/payment_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmCnsgnRcptDetLn('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else if ($sbmtdScmCnsgnRcptITEMID > 0) {
                                                                $error = "";
                                                                $searchAll = true;
                                                                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                                                                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                                                                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                                                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
                                                                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
                                                                $qCnsgnOnly = isset($_POST['qCnsgnOnly']) ? cleanInputData($_POST['qCnsgnOnly']) : "false";
                                                                $sbmtdDocType = isset($_POST['sbmtdDocType']) ? cleanInputData($_POST['sbmtdDocType']) : "";
                                                                $sbmtdItemID = $sbmtdScmCnsgnRcptITEMID;
                                                                $sbmtdStoreID = isset($_POST['sbmtdStoreID']) ? (int) cleanInputData($_POST['sbmtdStoreID']) : -1;
                                                                $sbmtdCstmrSiteID = isset($_POST['scmSalesInvcCstmrSiteID']) ? (float) cleanInputData($_POST['scmSalesInvcCstmrSiteID']) : -1;
                                                                if ($sbmtdStoreID <= 0) {
                                                                    $sbmtdStoreID = $selectedStoreID;
                                                                }
                                                                $sbmtdCallBackFunc = isset($_POST['sbmtdCallBackFunc']) ? cleanInputData($_POST['sbmtdCallBackFunc']) : 'function(){var a=1;}';
                                                                $sbmtdRowIDAttrb = isset($_POST['sbmtdRowIDAttrb']) ? cleanInputData($_POST['sbmtdRowIDAttrb']) : '';
                                                                $qCnsgnOnlyB = ($qCnsgnOnly == "true") ? true : false;
                                                                if (strpos($srchFor, "%") === FALSE) {
                                                                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                                                    $srchFor = str_replace("%%", "%", $srchFor);
                                                                }
                                                                $total = get_Total_StoreItms($srchFor, $srchIn, $orgID, $sbmtdStoreID, $sbmtdDocType, $qCnsgnOnlyB, $sbmtdItemID);
                                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                                    $pageNo = 1;
                                                                } else if ($pageNo < 1) {
                                                                    $pageNo = ceil($total / $lmtSze);
                                                                }
                                                                $curIdx = $pageNo - 1;
                                                                $result = get_StoreItems(
                                                                    $srchFor,
                                                                    $srchIn,
                                                                    $curIdx,
                                                                    $lmtSze,
                                                                    $orgID,
                                                                    $sbmtdStoreID,
                                                                    $sbmtdDocType,
                                                                    $qCnsgnOnlyB,
                                                                    $sbmtdItemID,
                                                                    $sbmtdCstmrSiteID
                                                                );
                                                                $cntr = 0;
                                                                while ($row = loc_db_fetch_array($result)) {
                                                                    $trsctnLnID = -1;
                                                                    $trsctnLnItmID = (float) $row[0];
                                                                    $trsctnLnItmNm = $row[2];
                                                                    $trsctnLnQty = 0;
                                                                    $trsctnLnUnitPrice = (float) $row[12];
                                                                    $trsctnLnAmnt = $trsctnLnQty * $trsctnLnUnitPrice;
                                                                    $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                    $trsctnLnPoLnID = -1;
                                                                    $trsctnLnStoreID = (int) $row[6];
                                                                    $trsctnLnStoreNm = getStoreNm($trsctnLnStoreID);
                                                                    $trsctnLnStckID = -1;
                                                                    $trsctnLnExpDte = "";
                                                                    $trsctnLnManDte = "";
                                                                    $trsctnLnSpan = "";
                                                                    $trsctnLnTagNo = "";
                                                                    $trsctnLnSerialNo = "";
                                                                    $trsctnLnCnsgnCndtn = "Good";
                                                                    $trsctnLnRmrks = "";
                                                                    $trsctnLnCnsgnID = -1;
                                                                    $trsctnLnUomID = (float) $row[18];
                                                                    $trsctnLnUomNm = $row[19];
                                                                    $cntr += 1;
                                                                ?>
                                                                    <tr id="oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmCnsgnRcptSmryLinesTable tr').index(this));">
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                                        <td class="lovtd" style="">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnID" value="<?php echo $trsctnLnCnsgnID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_PODocLnID" value="<?php echo $trsctnLnPoLnID; ?>" style="width:100% !important;">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnItmNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmCnsgnRcptType; ?>', 'false', function () {
                                                                                                var a = 1;
                                                                                            });">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                            ?>
                                                                                <span><?php echo $trsctnLnItmNm; ?></span>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: right;">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                                                                                                                                                                                                                            echo $trsctnLnQty;
                                                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                        </td>
                                                                        <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">
                                                                            <div class="" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmCnsgnRcptID; ?>, 2, 'oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');">
                                                                                    <span class="" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                                                                                                                                                                                                                            echo number_format($trsctnLnUnitPrice, 5);
                                                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $edtPriceRdOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                                                                                                                                                                                                                        echo number_format($trsctnLnAmnt, 2);
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm" value="<?php echo $trsctnLnStoreNm; ?>" style="width:100% !important;" readonly="true" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '<?php echo $trsctnLnStoreID; ?>', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreID', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm', 'clear', 0, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                            ?>
                                                                                <span><?php echo $trsctnLnStoreNm; ?></span>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100% !important;">
                                                                                    <input class="form-control" size="16" type="text" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ManDte" value="<?php echo $trsctnLnManDte; ?>" readonly="">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLnManDte; ?></span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100% !important;">
                                                                                    <input class="form-control" size="16" type="text" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExpryDte" value="<?php echo $trsctnLnExpDte; ?>" readonly="">
                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $trsctnLnExpDte; ?></span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TagNo" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TagNo" value="<?php echo $trsctnLnTagNo; ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_SerialNo" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_SerialNo" value="<?php echo $trsctnLnSerialNo; ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                            ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn" value="<?php echo $trsctnLnCnsgnCndtn; ?>" style="width:100% !important;" readonly="true" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Consignment Conditions', '', '', '', 'radio', true, '', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn', '', 'clear', 1, '');" data-toggle="tooltip" title="Existing Consignment Condition">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                            ?>
                                                                                <span><?php echo $trsctnLnCnsgnCndtn; ?></span>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExtraDesc" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExtraDesc" value="<?php echo "[CS No.:" . $trsctnLnCnsgnID . "]" . $trsctnLnRmrks; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?>>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" onclick="getScmSalesInvcItems('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmCnsgnRcptType; ?>', 'true', function () {
                                                                                                var a = 1;
                                                                                            });" style="padding:2px !important;">
                                                                                <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Item's New Selling Price" onclick="getOneINVItmPricesForm('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog');" style="padding:2px !important;">
                                                                                <img src="cmn_images/payment_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmCnsgnRcptDetLn('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else {
                                                                $trsctnLnID = -1;
                                                                $trsctnLnItmID = -1;
                                                                $trsctnLnItmNm = "";
                                                                $trsctnLnQty = 0;
                                                                $trsctnLnUnitPrice = 0;
                                                                $trsctnLnAmnt = $trsctnLnQty * $trsctnLnUnitPrice;
                                                                $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                $trsctnLnPoLnID = -1;
                                                                $trsctnLnStoreID = $selectedStoreID;
                                                                $trsctnLnStoreNm = "";
                                                                $trsctnLnStckID = -1;
                                                                $trsctnLnExpDte = "";
                                                                $trsctnLnManDte = "";
                                                                $trsctnLnSpan = "";
                                                                $trsctnLnTagNo = "";
                                                                $trsctnLnSerialNo = "";
                                                                $trsctnLnCnsgnCndtn = "Good";
                                                                $trsctnLnRmrks = "";
                                                                $trsctnLnCnsgnID = -1;
                                                                $trsctnLnUomID = -1;
                                                                $trsctnLnUomNm = "each";
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmCnsgnRcptSmryLinesTable tr').index(this));">
                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                                    <td class="lovtd" style="">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnID" value="<?php echo $trsctnLnCnsgnID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_PODocLnID" value="<?php echo $trsctnLnPoLnID; ?>" style="width:100% !important;">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnItmNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmCnsgnRcptType; ?>', 'false', function () {
                                                                                            var a = 1;
                                                                                        });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                        ?>
                                                                            <span><?php echo $trsctnLnItmNm; ?></span>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: right;">
                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                                                                                                                                                                                                                        echo $trsctnLnQty;
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_QTY', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                    </td>
                                                                    <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">
                                                                        <div class="" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmCnsgnRcptID; ?>, 2, 'oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');">
                                                                                <span class="" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                                                                                                                                                                                                                        echo number_format($trsctnLnUnitPrice, 5);
                                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $edtPriceRdOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                                                                                                                                                                                                                    echo number_format($trsctnLnAmnt, 2);
                                                                                                                                                                                                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl();">
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm" value="<?php echo $trsctnLnStoreNm; ?>" style="width:100% !important;" readonly="true" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '<?php echo $trsctnLnStoreID; ?>', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreID', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_StoreNm', 'clear', 0, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                        ?>
                                                                            <span><?php echo $trsctnLnStoreNm; ?></span>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100% !important;">
                                                                                <input class="form-control" size="16" type="text" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ManDte" value="<?php echo $trsctnLnManDte; ?>" readonly="">
                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $trsctnLnManDte; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100% !important;">
                                                                                <input class="form-control" size="16" type="text" id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExpryDte" value="<?php echo $trsctnLnExpDte; ?>" readonly="">
                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $trsctnLnExpDte; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TagNo" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_TagNo" value="<?php echo $trsctnLnTagNo; ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_SerialNo" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_SerialNo" value="<?php echo $trsctnLnSerialNo; ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn" value="<?php echo $trsctnLnCnsgnCndtn; ?>" style="width:100% !important;" readonly="true" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRcptSmryLinesTable', 'jbDetAccRate');">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Consignment Conditions', '', '', '', 'radio', true, '', 'oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_CnsgnCdtn', '', 'clear', 1, '');" data-toggle="tooltip" title="Existing Consignment Condition">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                        ?>
                                                                            <span><?php echo $trsctnLnCnsgnCndtn; ?></span>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExtraDesc" name="oneScmCnsgnRcptSmryRow<?php echo $cntr; ?>_ExtraDesc" value="<?php echo "[CS No.:" . $trsctnLnCnsgnID . "]" . $trsctnLnRmrks; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?>>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" onclick="getScmSalesInvcItems('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmCnsgnRcptType; ?>', 'true', function () {
                                                                                            var a = 1;
                                                                                        });" style="padding:2px !important;">
                                                                            <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Item's New Selling Price" onclick="getOneINVItmPricesForm('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>', 'ShowDialog');" style="padding:2px !important;">
                                                                            <img src="cmn_images/payment_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmCnsgnRcptDetLn('oneScmCnsgnRcptSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
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
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
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
                        </div>
                    </fieldset>
                </form>
            <?php
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdScmCnsgnRcptID = isset($_POST['sbmtdScmCnsgnRcptID']) ? cleanInputData($_POST['sbmtdScmCnsgnRcptID']) : -1;
                if (!$canAdd || ($sbmtdScmCnsgnRcptID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdScmCnsgnRcptID;
                $total = get_Total_CnsgnRcpt_Attachments($srchFor, $pkID, "RECEIPT");
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_CnsgnRcpt_Attachments($srchFor, $curIdx, $lmtSze, $pkID, "RECEIPT", $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
            ?>
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdCnsgnRcptDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdCnsgnRcptDocsRow__WWW123WWW\">"
                                . "<td class=\"lovtd\"><span>New</span></td>"
                                . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdCnsgnRcptDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdCnsgnRcptDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdCnsgnRcptDocsRow_WWW123WWW_DocCtgryNm', 'attchdCnsgnRcptDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdCnsgnRcptDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToCnsgnRcptDocs('attchdCnsgnRcptDocsRow_WWW123WWW_DocFile','attchdCnsgnRcptDocsRow_WWW123WWW_AttchdDocsID','attchdCnsgnRcptDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdCnsgnRcptDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdCnsgnRcptDoc('attchdCnsgnRcptDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdCnsgnRcptDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdCnsgnRcptDocsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncAttchdCnsgnRcptDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRcptID=<?php echo $sbmtdScmCnsgnRcptID; ?>', 'ReloadDialog');">
                                    <input id="attchdCnsgnRcptDocsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdCnsgnRcptDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRcptID=<?php echo $sbmtdScmCnsgnRcptID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdCnsgnRcptDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRcptID=<?php echo $sbmtdScmCnsgnRcptID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdCnsgnRcptDocsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getAttchdCnsgnRcptDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRcptID=<?php echo $sbmtdScmCnsgnRcptID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdCnsgnRcptDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRcptID=<?php echo $sbmtdScmCnsgnRcptID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdCnsgnRcptDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/Rcpts/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                        ?>
                                            <tr id="attchdCnsgnRcptDocsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span><?php
                                                                        echo ($curIdx * $lmtSze) + ($cntr);
                                                                        ?></span></td>
                                                <td class="lovtd">
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdCnsgnRcptDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdCnsgnRcptDoc('attchdCnsgnRcptDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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