<?php
$canAdd = test_prmssns($dfltPrvldgs[89], $mdlNm);
$canEdt = $canAdd;
$canDel = $canAdd;
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
                    echo deleteStockTrnsfrHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Doc Header Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteStockTrnsfrLine($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Consignment Receipt Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdScmStockTrnsfrID = isset($_POST['sbmtdScmStockTrnsfrID']) ? (float) cleanInputData($_POST['sbmtdScmStockTrnsfrID']) : -1;
                $scmStockTrnsfrDocNum = isset($_POST['scmStockTrnsfrDocNum']) ? cleanInputData($_POST['scmStockTrnsfrDocNum']) : "";
                $scmStockTrnsfrDfltTrnsDte = isset($_POST['scmStockTrnsfrDfltTrnsDte']) ? cleanInputData($_POST['scmStockTrnsfrDfltTrnsDte']) : '';
                $scmStockTrnsfrType = 'Stock Transfer';
                $scmStockTrnsfrInvcCur = $fnccurnm;
                //isset($_POST['scmStockTrnsfrInvcCur']) ? cleanInputData($_POST['scmStockTrnsfrInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $scmStockTrnsfrInvcCurID = getPssblValID($scmStockTrnsfrInvcCur, $curLovID);
                $scmStockTrnsfrTtlAmnt = isset($_POST['scmStockTrnsfrTtlAmnt']) ? (float) cleanInputData($_POST['scmStockTrnsfrTtlAmnt']) : 0;

                $scmStockTrnsfrSrcStoreID = isset($_POST['scmStockTrnsfrSrcStoreID']) ? (float) cleanInputData($_POST['scmStockTrnsfrSrcStoreID']) : -1;
                $scmStockTrnsfrDestStoreID = isset($_POST['scmStockTrnsfrDestStoreID']) ? (float) cleanInputData($_POST['scmStockTrnsfrDestStoreID']) : -1;
                $scmStockTrnsfrDesc = isset($_POST['scmStockTrnsfrDesc']) ? cleanInputData($_POST['scmStockTrnsfrDesc']) : '';

                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                if (strlen($scmStockTrnsfrDesc) > 499) {
                    $scmStockTrnsfrDesc = substr($scmStockTrnsfrDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($scmStockTrnsfrDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($scmStockTrnsfrDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($scmStockTrnsfrDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                if ($scmStockTrnsfrSrcStoreID <= 0) {
                    $exitErrMsg .= "Source Store cannot be empty!<br/>";
                }
                if ($scmStockTrnsfrDestStoreID <= 0) {
                    $exitErrMsg .= "Destination Store cannot be empty!<br/>";
                }
                $oldPtyCashID = (float) getGnrlRecID("inv.inv_stock_transfer_hdr", "rcpt_number", "transfer_hdr_id", $scmStockTrnsfrDocNum, $orgID);
                if ($oldPtyCashID > 0 && $oldPtyCashID != $sbmtdScmStockTrnsfrID) {
                    $exitErrMsg .= "New Document Number/Name is already in use in this Organization!<br/>";
                }
                $apprvlStatus = "Incomplete";
                $nxtApprvlActn = "Receive";
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdScmStockTrnsfrID'] = $sbmtdScmStockTrnsfrID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdScmStockTrnsfrID <= 0) {
                    createStockTrnsfrHdr($orgID, $scmStockTrnsfrDocNum, $scmStockTrnsfrDesc, $scmStockTrnsfrDfltTrnsDte, $scmStockTrnsfrSrcStoreID,
                            $scmStockTrnsfrDestStoreID, $apprvlStatus, $scmStockTrnsfrTtlAmnt);
                    $sbmtdScmStockTrnsfrID = (float) getGnrlRecID("inv.inv_stock_transfer_hdr", "rcpt_number", "transfer_hdr_id", $scmStockTrnsfrDocNum, $orgID);
                } else if ($sbmtdScmStockTrnsfrID > 0) {
                    updtStockTrnsfrHdr($sbmtdScmStockTrnsfrID, $scmStockTrnsfrDocNum, $scmStockTrnsfrDesc, $scmStockTrnsfrDfltTrnsDte,
                            $scmStockTrnsfrSrcStoreID, $scmStockTrnsfrDestStoreID, $apprvlStatus, $scmStockTrnsfrTtlAmnt);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDetTransLines, "|~") != "" && $sbmtdScmStockTrnsfrID > 0) {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 6) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_ItmID = (float) cleanInputData1($crntRow[1]);
                            $ln_LineDesc = cleanInputData1($crntRow[2]);
                            $ln_QTY = (float) (cleanInputData1($crntRow[3]));
                            $ln_UnitPrice = (float) cleanInputData1($crntRow[4]);
                            $ln_CnsgnIDs = cleanInputData1($crntRow[5]);
                            $ln_ExtraDesc = "";
                            $ttlAmnt = ($ln_QTY * $ln_UnitPrice);
                            $errMsg = "";
                            if ($ln_LineDesc === "" || $ln_ItmID <= 0 || $ln_QTY <= 0) {
                                $errMsg = "Row " . ($y + 1) . ":- Item Description and Quantity are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                //Create Transfer Doc Lines
                                if ($ln_LineDesc != "" && $ln_ItmID > 0 && $ln_QTY > 0) {
                                    if ($ln_TrnsLnID <= 0) {
                                        $afftctd += createStockTrnsfrLine($ln_QTY, $ln_UnitPrice, "Good", $sbmtdScmStockTrnsfrID, $ln_ExtraDesc, $ln_ItmID,
                                                $scmStockTrnsfrDestStoreID, $ln_CnsgnIDs, $ttlAmnt, $scmStockTrnsfrSrcStoreID);
                                    } else {
                                        $afftctd += updtStockTrnsfrLine($ln_TrnsLnID, $ln_QTY, $ln_UnitPrice, "Good", $sbmtdScmStockTrnsfrID, $ln_ExtraDesc,
                                                $ln_ItmID, $scmStockTrnsfrDestStoreID, $ln_CnsgnIDs, $ttlAmnt, $scmStockTrnsfrSrcStoreID);
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
                        $exitErrMsg = approve_stck_trnsfr($sbmtdScmStockTrnsfrID, "Stock Transfer");
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
                $arr_content['sbmtdScmStockTrnsfrID'] = $sbmtdScmStockTrnsfrID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 20) {
                //Upload Attachment
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdScmStockTrnsfrID = isset($_POST['sbmtdScmStockTrnsfrID']) ? cleanInputData($_POST['sbmtdScmStockTrnsfrID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdScmStockTrnsfrID;
                if ($attchmentID > 0) {
                    uploadDaStockTrnsfrDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewStockTrnsfrDocID();
                    createStockTrnsfrDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaStockTrnsfrDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/Sales/" . $nwImgLoc;
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
                //Reverse Stock Transfer Voucher
                $errMsg = "";
                $scmStockTrnsfrDesc = isset($_POST['scmStockTrnsfrDesc']) ? cleanInputData($_POST['scmStockTrnsfrDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdScmStockTrnsfrID = isset($_POST['sbmtdScmStockTrnsfrID']) ? cleanInputData($_POST['sbmtdScmStockTrnsfrID']) : -1;
                if (!$cancelDocs) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                $p_dochdrtype = "";
                $scmInvcDfltTrnsDte = "";
                $scmInvcDocNum = "";
                if ($sbmtdScmStockTrnsfrID > 0) {
                    $result = get_One_StockTrnsfrDocHdr($sbmtdScmStockTrnsfrID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmInvcDfltTrnsDte = $row[1] . " 12:00:00";
                        $scmInvcDocNum = $row[4];
                        $p_dochdrtype = "Stock Transfer";
                        $rqStatus = $row[6];
                    }
                }
                if ($rqStatus == "Incomplete" && $sbmtdScmStockTrnsfrID > 0) {
                    echo deleteStockTrnsfrHdrNDet($sbmtdScmStockTrnsfrID, $scmInvcDocNum);
                    exit();
                } else {
                    $exitErrMsg = cancel_stck_trnsfr($sbmtdScmStockTrnsfrID, "Stock Transfer", $orgID, $usrID);
                    $arr_content['sbmtdScmStockTrnsfrID'] = $sbmtdScmStockTrnsfrID;
                    $arr_content['percent'] = 100;
                    if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                        execUpdtInsSQL("UPDATE inv.inv_consgmt_rcpt_hdr SET description='" . loc_db_escape_string($scmStockTrnsfrDesc) . "' WHERE (rcpt_id = " . $sbmtdScmStockTrnsfrID . ")");
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
                                    <span style=\"text-decoration:none;\">Stock Transfers</span>
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
                    $total = get_Total_StockTrnsfr($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Basic_StockTrnsfr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
                    $colClassType3 = "col-md-5";
                    ?> 
                    <form id='scmStockTrnsfrForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">STOCK TRANSFERS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                                $colClassType3 = "col-md-10";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="scmStockTrnsfrSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncScmStockTrnsfr(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="scmStockTrnsfrPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmStockTrnsfr('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmStockTrnsfr('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmStockTrnsfrSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "");
                                            $srchInsArrys = array("Document Number", "Document Description",
                                                "Status", "Created By");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmStockTrnsfrDsplySze" style="min-width:70px !important;">                            
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
                                                <a href="javascript:getScmStockTrnsfr('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getScmStockTrnsfr('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
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
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmStockTrnsfrForm(-1, 1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Add New Stock Transfer">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                NEW STOCK TRANSFER
                                            </button> 
                                        </div>  
                                    <?php }
                                    ?>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;display:none;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label">
                                                <?php
                                                $shwUnpaidOnlyChkd = "";
                                                if ($qShwUnpaidOnly == true) {
                                                    $shwUnpaidOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmStockTrnsfr('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmStockTrnsfrShwUnpaidOnly" name="scmStockTrnsfrShwUnpaidOnly"  <?php echo $shwUnpaidOnlyChkd; ?>>
                                                Show Approved but Unpaid
                                            </label>
                                        </div> 
                                    </div>
                                    <div class = "col-md-3" style = "padding:5px 1px 0px 1px !important;display:none;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmStockTrnsfr('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmStockTrnsfrShwUnpstdOnly" name="scmStockTrnsfrShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted
                                            </label>
                                        </div>                            
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="scmStockTrnsfrHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:35px;width:35px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th>Transfer Number - Description</th>
                                                <th>Source Store</th>
                                                <th>Destination Store</th>
                                                <th style="max-width:115px;width:115px;">Date Transferred</th>
                                                <th style="text-align:center;max-width:40px;width:40px;">CUR.</th>	
                                                <th style="text-align:right;min-width:120px;width:120px;">Total Amount</th>
                                                <th style="max-width:115px;width:115px;">Transfer Status</th>
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
                                                <tr id="scmStockTrnsfrHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Invoice" 
                                                                onclick="getOneScmStockTrnsfrForm(<?php echo $row[0]; ?>, 1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                    <?php if ($canAdd === true) { ?>                                
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[1] . " " . $row[3]; ?></td>
                                                    <td class="lovtd"><?php echo $row[7]; ?></td>
                                                    <td class="lovtd"><?php echo $row[8]; ?></td>
                                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[4]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[5], 2);
                                                        ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if (strpos($row[6], "Success") !== FALSE) {
                                                        $style1 = "color:green;";
                                                    } else if ($row[6] == "Cancelled") {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php echo $row[6]; ?></td>  
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delScmStockTrnsfr('scmStockTrnsfrHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="scmStockTrnsfrHdrsRow<?php echo $cntr; ?>_HdrID" name="scmStockTrnsfrHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|inv.inv_stock_transfer_hdr|transfer_hdr_id"), $smplTokenWord1));
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
                //New Stock Transfer Form
                //var_dump($_POST);
                $sbmtdScmStockTrnsfrID = isset($_POST['sbmtdScmStockTrnsfrID']) ? cleanInputData($_POST['sbmtdScmStockTrnsfrID']) : -1;

                if (!$canAdd || ($sbmtdScmStockTrnsfrID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $orgnlScmStockTrnsfrID = $sbmtdScmStockTrnsfrID;
                $scmStockTrnsfrDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $scmStockTrnsfrCreator = $uName;
                $scmStockTrnsfrCreatorID = $usrID;
                $scmStockTrnsfrSrcStoreID = -1;
                $scmStockTrnsfrSrcStore = "";
                $scmStockTrnsfrDestStoreID = -1;
                $scmStockTrnsfrDestStore = "";
                $gnrtdTrnsNo = "";
                $scmStockTrnsfrDesc = "Stock Transfer";
                $rqStatus = "Incomplete";
                $rqStatusNext = "Transfer Successful";
                $rqstatusColor = "red";

                $scmStockTrnsfrTtlAmnt = 0;
                $scmStockTrnsfrInvcCurID = $fnccurid;
                $scmStockTrnsfrInvcCur = $fnccurnm;
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdScmStockTrnsfrID > 0) {
                    $result = get_One_StockTrnsfrDocHdr($sbmtdScmStockTrnsfrID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmStockTrnsfrDfltTrnsDte = $row[1];
                        $scmStockTrnsfrCreator = $row[3];
                        $scmStockTrnsfrCreatorID = $row[2];
                        $gnrtdTrnsNo = $row[4];
                        $scmStockTrnsfrDesc = $row[5];
                        $rqStatus = $row[6];
                        $rqstatusColor = "red";
                        $scmStockTrnsfrTtlAmnt = (float) $row[7];
                        $scmStockTrnsfrSrcStoreID = (int) $row[8];
                        $scmStockTrnsfrSrcStore = $row[9];
                        $scmStockTrnsfrDestStoreID = (int) $row[10];
                        $scmStockTrnsfrDestStore = $row[11];
                        if ($rqStatus == "Transfer Successful") {
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
                            if ($rqStatus != "Transfer Successful") {
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
                    $docTypes = array("Stock Transfer");
                    $docTypPrfxs = array("TRNSFR");

                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, "Stock Transfer")];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("inv.inv_stock_transfer_hdr", "rcpt_number", "transfer_hdr_id",
                                            $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    createStockTrnsfrHdr($orgID, $gnrtdTrnsNo, $scmStockTrnsfrDesc, $scmStockTrnsfrDfltTrnsDte, $scmStockTrnsfrSrcStoreID,
                            $scmStockTrnsfrDestStoreID, $rqStatus, $scmStockTrnsfrTtlAmnt);
                    $sbmtdScmStockTrnsfrID = getGnrlRecID("inv.inv_stock_transfer_hdr", "rcpt_number", "transfer_hdr_id", $gnrtdTrnsNo, $orgID);
                }
                $reportName = getEnbldPssblValDesc("Sales Invoice", getLovID("Document Custom Print Process Names"));
                $reportTitle = str_replace("Pro-Forma Invoice", "Payment Voucher", "Stock Transfer");
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdScmStockTrnsfrID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="oneScmStockTrnsfrEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Doc. No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdScmStockTrnsfrID" name="sbmtdScmStockTrnsfrID" value="<?php echo $sbmtdScmStockTrnsfrID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="scmStockTrnsfrDocNum" name="scmStockTrnsfrDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="scmStockTrnsfrDfltTrnsDte" name="scmStockTrnsfrDfltTrnsDte" value="<?php echo $scmStockTrnsfrDfltTrnsDte; ?>" placeholder="Transactions Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Status:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="hidden" id="scmStockTrnsfrApprvlStatus" value="<?php echo $rqStatus; ?>">                              
                                        <button type="button" class="btn btn-default" style="height:34px;width:100% !important;" id="myScmStockTrnsfrStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:34px;">
                                                <?php
                                                echo $rqStatus;
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
                                        <div class="input-group"  style="width:100%;">
                                            <textarea class="form-control rqrdFld" rows="5" cols="20" id="scmStockTrnsfrDesc" name="scmStockTrnsfrDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $scmStockTrnsfrDesc; ?></textarea>
                                            <input class="form-control" type="hidden" id="scmStockTrnsfrDesc1" value="<?php echo $scmStockTrnsfrDesc; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmStockTrnsfrDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class = "col-md-4">                            
                                <div class="form-group">
                                    <label for="scmStockTrnsfrSrcStore" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Source Store:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control rqrdFld" id="scmStockTrnsfrSrcStore" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Source Store" type = "text" placeholder="" value="<?php echo $scmStockTrnsfrSrcStore; ?>" readonly="true"/>
                                            <input type="hidden" id="scmStockTrnsfrSrcStoreID" value="<?php echo $scmStockTrnsfrSrcStoreID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '<?php echo $scmStockTrnsfrSrcStoreID; ?>', 'scmStockTrnsfrSrcStoreID', 'scmStockTrnsfrSrcStore', 'clear', 0, '', function () {
                                                                        chngStockTrnsfrStores();
                                                                    });">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label for="scmStockTrnsfrDestStore" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Destination Store:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control rqrdFld" id="scmStockTrnsfrDestStore" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Destination Store" type = "text" placeholder="" value="<?php echo $scmStockTrnsfrDestStore; ?>" readonly="true"/>
                                            <input type="hidden" id="scmStockTrnsfrDestStoreID" value="<?php echo $scmStockTrnsfrDestStoreID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '<?php echo $scmStockTrnsfrDestStoreID; ?>', 'scmStockTrnsfrDestStoreID', 'scmStockTrnsfrDestStore', 'clear', 0, '', function () {
                                                                        chngStockTrnsfrStores();
                                                                    });">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                    <?php /*getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $scmStockTrnsfrInvcCur; ?>', 'scmStockTrnsfrInvcCur', '', 'clear', 0, '', function () {
                                                                        $('#scmStockTrnsfrInvcCur1').html($('#scmStockTrnsfrInvcCur').val());
                                                                        $('#scmStockTrnsfrInvcCur2').html($('#scmStockTrnsfrInvcCur').val());
                                                                        $('#scmStockTrnsfrInvcCur3').html($('#scmStockTrnsfrInvcCur').val());
                                                                        $('#scmStockTrnsfrInvcCur4').html($('#scmStockTrnsfrInvcCur').val());
                                                                        $('#scmStockTrnsfrInvcCur5').html($('#scmStockTrnsfrInvcCur').val());
                                                                    });*/ ?> 
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Transfer Total:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;" id="scmStockTrnsfrInvcCur1"><?php echo $scmStockTrnsfrInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="scmStockTrnsfrInvcCur" value="<?php echo $scmStockTrnsfrInvcCur; ?>"> 
                                            <input type="hidden" id="scmStockTrnsfrInvcCurID" value="<?php echo $scmStockTrnsfrInvcCurID; ?>"> 
                                            <input class="form-control" type="text" id="scmStockTrnsfrTtlAmnt" value="<?php
                                            echo number_format($scmStockTrnsfrTtlAmnt, 2);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('scmStockTrnsfrTtlAmnt');" readonly="true"/>
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
                                                $edtPriceRdOnly = "";
                                                $nwRowHtml33 = "<tr id=\"oneScmStockTrnsfrSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneScmStockTrnsfrSmryLinesTable tr').index(this));\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\"  style=\"\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_ItmID\" value=\"-1\" style=\"width:100% !important;\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_StoreID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_DestStoreID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_CnsgnIDs\" value=\"\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate jbDetDesc\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_LineDesc\" name=\"oneScmStockTrnsfrSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow_WWW123WWW_LineDesc', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate');\" onblur=\"afterSalesInvcItmSlctn('oneScmStockTrnsfrSmryRow__WWW123WWW');\" onchange=\"autoCreateSalesLns = 99;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getScmSalesInvcItems('oneScmStockTrnsfrSmryRow__WWW123WWW', 'ShowDialog', 'Stock Transfer', 'false', function () {
                                                                                                                var a = 1;
                                                                                                            });\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_SrcStore\" name=\"oneScmStockTrnsfrSmryRow_WWW123WWW_SrcStore\" value=\"\" style=\"width:100% !important;\" readonly=\"true\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                            <input type=\"text\" class=\"form-control jbDetAccRate1\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_SrcQTY\" name=\"oneScmStockTrnsfrSmryRow_WWW123WWW_SrcQTY\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow_WWW123WWW_SrcQTY', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate1');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_QTY\" name=\"oneScmStockTrnsfrSmryRow_WWW123WWW_QTY\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow_WWW123WWW_QTY', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');\">                                                    
                                                        </td>                                               
                                                        <td class=\"lovtd\" style=\"max-width:35px;width:35px;text-align: center;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_UomID\" value=\"-1\" style=\"width:100% !important;\">  
                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file\" onclick=\"getOneScmUOMBrkdwnForm(" . $sbmtdScmStockTrnsfrID . ", 2, 'oneScmStockTrnsfrSmryRow__WWW123WWW');\">
                                                                    <span class=\"\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_UomNm1\">each</span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_UnitPrice\" name=\"oneScmStockTrnsfrSmryRow_WWW123WWW_UnitPrice\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow_WWW123WWW_UnitPrice', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\" onchange=\"calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control jbDetCrdt\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_LineAmt\" name=\"oneScmStockTrnsfrSmryRow_WWW123WWW_LineAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow_WWW123WWW_LineAmt', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\" onchange=\"calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');\">                                                    
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_CnsgnNos\" value=\"\" style=\"width:100% !important;\" readonly=\"true\"> 
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneScmStockTrnsfrSmryRow_WWW123WWW_DestStore\" name=\"oneScmStockTrnsfrSmryRow_WWW123WWW_DestStore\" value=\"\" style=\"width:100% !important;\" readonly=\"true\">                                                    
                                                        </td>  
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Item Consignments\" 
                                                                    onclick=\"getScmSalesInvcItems('oneScmStockTrnsfrSmryRow__WWW123WWW', 'ShowDialog', 'Stock Transfer', 'true', function () {
                                                                                                            var a = 1;
                                                                                                        });\" style=\"padding:2px !important;\"> 
                                                                <img src=\"cmn_images/chcklst3.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                            </button>
                                                        </td>
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delScmStockTrnsfrDetLn('oneScmStockTrnsfrSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
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
                                                            <button id="addNwScmStockTrnsfrSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewScmSalesInvcRows('oneScmStockTrnsfrSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>                                 
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmStockTrnsfrDocsForm(<?php echo $sbmtdScmStockTrnsfrID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmStockTrnsfrForm(<?php echo $sbmtdScmStockTrnsfrID; ?>, 1, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdStockTrnsfrValsTtlBtn"><?php echo $scmStockTrnsfrInvcCur; ?> 
                                                                <?php
                                                                echo number_format($scmStockTrnsfrTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdStockTrnsfrValsTtlVal" value="<?php echo $scmStockTrnsfrTtlAmnt; ?>">
                                                        </button>
                                                    </div> 
                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                            <?php
                                                            if ($rqStatus == "Incomplete") {
                                                                ?>
                                                                <?php if ($canEdt) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmStockTrnsfrForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                                <?php } ?>
                                                                <?php if ($canRvwApprvDocs) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmStockTrnsfrForm('<?php echo $fnccurnm; ?>', 2);" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                    </button>
                                                                    <?php
                                                                }
                                                            } else if ($rqStatus == "Transfer Successful") {
                                                                ?>
                                                                <?php if ($cancelDocs) { ?>
                                                                    <button id="fnlzeRvrslScmStockTrnsfrBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmStockTrnsfrRvrslForm('<?php echo $fnccurnm; ?>', 1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Document&nbsp;</button>  
                                                                    <!--<button id="fnlzeBadDebtScmStockTrnsfrBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmStockTrnsfrRvrslForm('<?php echo $fnccurnm; ?>', 2);"  data-toggle="tooltip" data-placement="bottom" title="Declare as Bad Debt">
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
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneScmStockTrnsfrLnsTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="stockTrnsfrDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneScmStockTrnsfrSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="min-width:200px;">Item Code/Description</th>
                                                                <th style="max-width:170px;width:170px;">Source Store</th>
                                                                <?php if ($rqStatus == "Incomplete") { ?>
                                                                    <th style="max-width:70px;width:70px;text-align: right;">Source QTY</th>
                                                                <?php } ?>
                                                                <th style="max-width:70px;width:70px;text-align: right;">Transfer QTY</th>
                                                                <th style="max-width:55px;text-align:center;">UOM.</th>
                                                                <th style="max-width:170px;width:170px;text-align: right;">Unit Cost Price</th>
                                                                <th style="max-width:170px;width:170px;text-align: right;">Total Amount</th>
                                                                <th style="max-width:170px;width:170px;">Consignments</th>
                                                                <th style="max-width:170px;width:170px;">Destination Store</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">CS</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_StockTrnsfrDocDet($sbmtdScmStockTrnsfrID);
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
                                                                    $trsctnLnAmnt = (float) $rowRw[5];
                                                                    $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                    $trsctnLnSrcStoreID = (float) $rowRw[6];
                                                                    $trsctnLnSrcStoreNm = $rowRw[7];
                                                                    $trsctnLnDestStoreID = (float) $rowRw[8];
                                                                    $trsctnLnDestStoreNm = $rowRw[9];
                                                                    $trsctnLnCnsgnCndtn = $rowRw[10];
                                                                    $trsctnLnCnsgnIDs = $rowRw[11];
                                                                    $trsctnLnUomID = (int) $rowRw[12];
                                                                    $trsctnLnUomNm = $rowRw[13];
                                                                    $trsctnLnExtrDesc = $rowRw[14];
                                                                    $trsctnLnSrcStckBals = (float) $rowRw[15];
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmStockTrnsfrSmryLinesTable tr').index(this));">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                        <td class="lovtd"  style="">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnSrcStoreID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_DestStoreID" value="<?php echo $trsctnLnDestStoreID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_CnsgnIDs" value="<?php echo $trsctnLnCnsgnIDs; ?>" style="width:100% !important;"> 
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnItmNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>', 'ShowDialog', 'Stock Transfer', 'false', function () {
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
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcStore" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcStore" value="<?php echo $trsctnLnSrcStoreNm; ?>" style="width:100% !important;" readonly="true">                                                    
                                                                        </td>
                                                                        <?php if ($rqStatus == "Incomplete") { ?>
                                                                            <td class="lovtd" style="text-align: right;">
                                                                                <input type="text" class="form-control jbDetAccRate1" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcQTY" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcQTY" value="<?php
                                                                                echo $trsctnLnSrcStckBals;
                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcQTY', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate1');" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td class="lovtd" style="text-align: right;">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_QTY" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                            echo $trsctnLnQty;
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_QTY', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');">                                                    
                                                                        </td>                                               
                                                                        <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">  
                                                                            <div class="" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmStockTrnsfrID; ?>, 2, 'oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>');">
                                                                                    <span class="" id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                            echo number_format($trsctnLnUnitPrice, 5);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');">                                                    
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                            echo number_format($trsctnLnAmnt, 2);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');">                                                    
                                                                        </td> 
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_CnsgnNos" value="<?php echo $trsctnLnCnsgnIDs; ?>" style="width:100% !important;" readonly="true"> 
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_DestStore" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_DestStore" value="<?php echo $trsctnLnDestStoreNm; ?>" style="width:100% !important;" readonly="true">                                                    
                                                                        </td>  
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" 
                                                                                    onclick="getScmSalesInvcItems('oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>', 'ShowDialog', 'Stock Transfer', 'true', function () {
                                                                                                                        var a = 1;
                                                                                                                    });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmStockTrnsfrDetLn('oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
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
                                                                $trsctnLnAmnt = 0;
                                                                $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                $trsctnLnSrcStoreID = $scmStockTrnsfrSrcStoreID;
                                                                $trsctnLnSrcStoreNm = $scmStockTrnsfrSrcStore;
                                                                $trsctnLnDestStoreID = $scmStockTrnsfrDestStoreID;
                                                                $trsctnLnDestStoreNm = $scmStockTrnsfrDestStore;
                                                                $trsctnLnCnsgnCndtn = "Good";
                                                                $trsctnLnCnsgnIDs = "";
                                                                $trsctnLnUomID = -1;
                                                                $trsctnLnUomNm = "each";
                                                                $trsctnLnExtrDesc = "";
                                                                $trsctnLnSrcStckBals = 0;
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmStockTrnsfrSmryLinesTable tr').index(this));">                                    
                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                    <td class="lovtd"  style="">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnSrcStoreID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_DestStoreID" value="<?php echo $trsctnLnDestStoreID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_CnsgnIDs" value="<?php echo $trsctnLnCnsgnIDs; ?>" style="width:100% !important;"> 
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnItmNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>', 'ShowDialog', 'Stock Transfer', 'false', function () {
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
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcStore" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcStore" value="<?php echo $trsctnLnSrcStoreNm; ?>" style="width:100% !important;" readonly="true">                                                    
                                                                    </td>
                                                                    <?php if ($rqStatus == "Incomplete") { ?>
                                                                        <td class="lovtd" style="text-align: right;">
                                                                            <input type="text" class="form-control jbDetAccRate1" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcQTY" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcQTY" value="<?php
                                                                            echo $trsctnLnSrcStckBals;
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_SrcQTY', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate1');" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                                                        </td>
                                                                    <?php } ?>
                                                                    <td class="lovtd" style="text-align: right;">
                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_QTY" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                        echo $trsctnLnQty;
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_QTY', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');">                                                    
                                                                    </td>                                               
                                                                    <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">  
                                                                        <div class="" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmStockTrnsfrID; ?>, 2, 'oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>');">
                                                                                <span class="" id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                        echo number_format($trsctnLnUnitPrice, 5);
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');">                                                    
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                        echo number_format($trsctnLnAmnt, 2);
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');">                                                    
                                                                    </td> 
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_CnsgnNos" value="<?php echo $trsctnLnCnsgnIDs; ?>" style="width:100% !important;" readonly="true"> 
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_DestStore" name="oneScmStockTrnsfrSmryRow<?php echo $cntr; ?>_DestStore" value="<?php echo $trsctnLnDestStoreNm; ?>" style="width:100% !important;" readonly="true">                                                    
                                                                    </td>   
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" 
                                                                                onclick="getScmSalesInvcItems('oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>', 'ShowDialog', 'Stock Transfer', 'true', function () {
                                                                                                                var a = 1;
                                                                                                            });" style="padding:2px !important;"> 
                                                                            <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmStockTrnsfrDetLn('oneScmStockTrnsfrSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
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
                                                                <?php if ($rqStatus == "Incomplete") { ?>
                                                                    <th style="">&nbsp;</th>
                                                                <?php } ?>                                           
                                                                <th style="">&nbsp;</th>
                                                                <th>TOTALS:</th>
                                                                <th>&nbsp;</th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdRIJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdRIJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                                </th>                                           
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
            }
        }
    }
}
?>