<?php
$canAdd = test_prmssns($dfltPrvldgs[52], $mdlNm);
$canEdt = $canAdd;
$canDel = $canAdd;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Template */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteTmpltHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Template Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteTmpltDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Document Templates
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $accbDocTmpltsID = isset($_POST['accbDocTmpltsID']) ? (int) cleanInputData($_POST['accbDocTmpltsID']) : -1;
                $accbDocTmpltsName = isset($_POST['accbDocTmpltsName']) ? cleanInputData($_POST['accbDocTmpltsName']) : "";
                $accbDocTmpltsDesc = isset($_POST['accbDocTmpltsDesc']) ? cleanInputData($_POST['accbDocTmpltsDesc']) : "";
                $accbDocTmpltsType = isset($_POST['accbDocTmpltsType']) ? cleanInputData($_POST['accbDocTmpltsType']) : "";
                $accbDocTmpltsIsEnbld = isset($_POST['accbDocTmpltsIsEnbld']) ? cleanInputData($_POST['accbDocTmpltsIsEnbld']) : "NO";
                $accbDocTmpltsIsEnbld_B = ($accbDocTmpltsIsEnbld == "YES") ? TRUE : FALSE;
                $slctdTransLines = isset($_POST['slctdTransLines']) ? cleanInputData($_POST['slctdTransLines']) : "";
                //SAVING DOCUMENT TEMPLATES...............
                $exitErrMsg = "";
                if ($accbDocTmpltsName == "") {
                    $exitErrMsg .= "Please enter Template Name!<br/>";
                }
                if ($accbDocTmpltsType == "") {
                    $exitErrMsg .= "Please enter Document Type!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['accbDocTmpltsID'] = $accbDocTmpltsID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldTmpltID = (int) getGnrlRecID(
                                "accb.accb_doc_tmplts_hdr", "doc_tmplt_name", "doc_tmplts_hdr_id", $accbDocTmpltsName, $orgID);

                if (($oldTmpltID <= 0 || $oldTmpltID == $accbDocTmpltsID)) {
                    if ($accbDocTmpltsID <= 0) {
                        createDocTmpltHdr($orgID, $accbDocTmpltsName, $accbDocTmpltsDesc, $accbDocTmpltsType, $accbDocTmpltsIsEnbld_B);
                        $accbDocTmpltsID = (int) getGnrlRecID("accb.accb_doc_tmplts_hdr", "doc_tmplt_name", "doc_tmplts_hdr_id", $accbDocTmpltsName,
                                        $orgID);
                    } else {
                        updtDocTmpltHdr($accbDocTmpltsID, $accbDocTmpltsName, $accbDocTmpltsDesc, $accbDocTmpltsType, $accbDocTmpltsIsEnbld_B);
                    }
                    $affctdRws = 0;
                    if (trim($slctdTransLines, "|~") != "" && $accbDocTmpltsID > 0) {
                        //Save Question Answers
                        $variousRows = explode("|", trim($slctdTransLines, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 7) {
                                $ln_DetID = (cleanInputData1($crntRow[0]));
                                $ln_ItemType = (cleanInputData1($crntRow[1]));
                                $ln_CodeBhndID = (int) (cleanInputData1($crntRow[2]));
                                $ln_LineDesc = (cleanInputData1($crntRow[3]));
                                $ln_IncrsDcrs = (cleanInputData1($crntRow[4]));
                                $ln_AccountID = (int) (cleanInputData1($crntRow[5]));
                                $ln_AutoCalc = cleanInputData1($crntRow[5]) == "YES" ? true : false;
                                $isdbtCrdt = dbtOrCrdtAccnt($ln_AccountID, substr(strtoupper($ln_IncrsDcrs), 0, 1));
                                $errMsg = "";
                                if ($ln_ItemType === "" || $ln_AccountID <= 0 || $ln_IncrsDcrs === "") {
                                    $errMsg .= "Row " . ($z + 1) . ":- Line Type, GL Account and Increase/Decrease are all required Fields!<br/>";
                                }
                                if (strpos($accbDocTmpltsType, "Supplier") !== FALSE) {
                                    if ($ln_ItemType == "1Initial Amount" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Asset/Expense/Prepaid Expense!)<br/>";
                                    }
                                    $isTxWthhldng = isTaxWthHldng($ln_CodeBhndID);
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBhndID > 0 && $isTxWthhldng == "0" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Purchase Tax Expense/Decrease Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBhndID > 0 && $isTxWthhldng == "1" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Withholding Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "3Discount" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Purchase Discounts (Contra Expense Account)!)<br/>";
                                    }
                                    if ($ln_ItemType == "4Extra Charge" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Asset/Expense!)<br/>";
                                    }
                                    if ($ln_ItemType == "5Applied Prepayment" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a CREDIT Transaction (i.e. Decrease Prepaid Expense!)<br/>";
                                    }
                                }
                                if (strpos($accbDocTmpltsType, "Customer") !== FALSE) {
                                    if ($ln_ItemType == "1Initial Amount" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Revenue/Custmr Advance Payments!)<br/>";
                                    }
                                    $isTxWthhldng = isTaxWthHldng($ln_CodeBhndID);
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBhndID > 0 && $isTxWthhldng == "1" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Withholding Tax Expense or Receivable/Decrease Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "2Tax" && $ln_CodeBhndID > 0 && $isTxWthhldng == "0" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Sales Taxes Payable!)<br/>";
                                    }
                                    if ($ln_ItemType == "3Discount" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a DEBIT Transaction (i.e. Increase Sales Discounts!)<br/>";
                                    }
                                    if ($ln_ItemType == "4Extra Charge" && strtoupper($isdbtCrdt) != "CREDIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a CREDIT Transaction (i.e. Increase Extra Revenue Account!)<br/>";
                                    }
                                    if ($ln_ItemType == "5Applied Prepayment" && strtoupper($isdbtCrdt) != "DEBIT") {
                                        $errMsg .= "Row " . ($z + 1) . ":- Expecting a DEBIT Transaction (i.e. Decrease Customer Advance Payments!)<br/>";
                                    }
                                }
                                if ($errMsg === "") {
                                    if ($ln_DetID <= 0) {
                                        $affctdRws += createDocTmpltDet($accbDocTmpltsID, $ln_ItemType, $ln_LineDesc, $ln_AutoCalc, $ln_IncrsDcrs,
                                                $ln_AccountID, $ln_CodeBhndID);
                                    } else {
                                        $affctdRws += updtDocTmpltDet($ln_DetID, $ln_ItemType, $ln_LineDesc, $ln_AutoCalc, $ln_IncrsDcrs,
                                                $ln_AccountID, $ln_CodeBhndID);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Document Template Successfully Saved!"
                                . "<br/>" . $affctdRws . " Template Line(s) Saved Successfully!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Document Template Successfully Saved!"
                                . "<br/>" . $affctdRws . " Template Line(s) Saved Successfully!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['accbDocTmpltsID'] = $accbDocTmpltsID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Template Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['accbDocTmpltsID'] = $accbDocTmpltsID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdDcTmpltID']) ? $_POST['sbmtdDcTmpltID'] : -1;
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Document Templates</span>
				</li>
                               </ul>
                              </div>";

                $tmpltNm = array("Customer Prepayment", "Goods Received Payment",
                    "Payment of Customer Goods Delivered", "Supplier Prepayment",
                    "Refund-Return of Goods Delivered", "Refund-Supplier's Goods/Services Returned");
                $tmpltDesc = array("Customer Prepayment", "Goods Received Payment",
                    "Payment of Customer Goods Delivered", "Supplier Prepayment",
                    "Refund-Return of Goods Delivered", "Refund-Supplier's Goods/Services Returned");
                $docType = array("Customer Advance Payment", "Supplier Standard Payment",
                    "Customer Standard Payment", "Supplier Advance Payment",
                    "Customer Debit Memo (Indirect-Refund)", "Supplier Credit Memo (Indirect-Refund)");
                $lneTypDec = array("Customer Prepayment", "Cost of Goods Received",
                    "Initial Price of Goods Delivered", "Supplier Prepayment",
                    "Initial Price of Goods/Services Returned", "Initial Price of Goods/Services Returned");

                for ($f = 0; $f < count($tmpltNm); $f++) {
                    $oldTmpltID = getGnrlRecID("accb.accb_doc_tmplts_hdr", "doc_tmplt_name", "doc_tmplts_hdr_id", $tmpltNm[$f], $orgID);
                    if ($oldTmpltID <= 0) {
                        createDocTmpltHdr($orgID, $tmpltNm[$f], $tmpltDesc[$f], $docType[$f], true);
                        $oldTmpltID = getGnrlRecID("accb.accb_doc_tmplts_hdr", "doc_tmplt_name", "doc_tmplts_hdr_id", $tmpltNm[$f], $orgID);

                        $lineTypeNm = "1Initial Amount";
                        $incrDcrs = "Increase";
                        $accntID = -1;
                        $codeBhndID = -1;
                        $lineDesc = $lneTypDec[$f];
                        $autoCalc = false;
                        createDocTmpltDet($oldTmpltID, $lineTypeNm, $lineDesc, $autoCalc, $incrDcrs, $accntID, $codeBhndID);
                    }
                }
                $total = get_Total_DocTmpltsHdr($srchFor, $srchIn, $orgID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_DocTmpltsHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='accbDocTmpltsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php if ($canAdd === true) { ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAccbDocTmpltForm(-1, 1);;" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Template
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAccbDocTmpltForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="accbDocTmpltsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAccbDocTmplts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="accbDocTmpltsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbDocTmplts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbDocTmplts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbDocTmpltsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Template Name", "Template Description", "Document Type");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbDocTmpltsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAccbDocTmplts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAccbDocTmplts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row"> 
                        <div  class="col-md-3">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="accbDocTmpltsTable" cellspacing="0" width="100%" style="width:100% !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Template Name</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($pkID <= 0 && $cntr <= 0) {
                                                $pkID = $row[0];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="accbDocTmpltsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltsRow<?php echo $cntr; ?>_TmpltID" value="<?php echo $row[0]; ?>"></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbDocTmplts('accbDocTmpltsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Template">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|accb.accb_doc_tmplts_hdr|doc_tmplts_hdr_id"),
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
                        <div  class="col-md-9" style="padding:0px 15px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                <div class="container-fluid" id="accbDocTmpltDetailInfo">
                                    <?php
                                    if ($pkID > 0) {
                                        $result1 = get_OneDocTmpltsHdr($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $accbDocTmpltsID = $row1[0];
                                            $accbDocTmpltsName = $row1[1];
                                            $accbDocTmpltsDesc = $row1[2];
                                            $accbDocTmpltsType = $row1[3];
                                            $accbDocTmpltsIsEnbld = $row1[4];
                                            ?>
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 1px 0px 1px !important;">
                                                            <label for="accbDocTmpltsName" class="control-label col-lg-3">Template Name:</label>
                                                            <div  class="col-lg-9">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="accbDocTmpltsName" name="accbDocTmpltsName" value="<?php echo $accbDocTmpltsName; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltsID" name="accbDocTmpltsID" value="<?php echo $accbDocTmpltsID; ?>">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $accbDocTmpltsName; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 1px 0px 1px !important;">
                                                            <label for="accbDocTmpltsIsEnbld" class="control-label col-lg-3">Is Enabled?:</label>
                                                            <div  class="col-lg-9">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($accbDocTmpltsIsEnbld == "1") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <label class="radio-inline"><input type="radio" name="accbDocTmpltsIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="accbDocTmpltsIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($accbDocTmpltsIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 1px 0px 1px !important;">
                                                            <label for="accbDocTmpltsDesc" class="control-label col-lg-3">Template Description:</label>
                                                            <div  class="col-lg-9">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="accbDocTmpltsDesc" name="accbDocTmpltsDesc" value="<?php echo $accbDocTmpltsDesc; ?>" style="width:100% !important;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $accbDocTmpltsDesc; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 1px 0px 1px !important;">
                                                            <label for="accbDocTmpltsType" class="control-label col-lg-3">Document Type:</label>
                                                            <div  class="col-lg-9">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbDocTmpltsType" style="min-width:70px !important;">                            
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "", "", "");
                                                                        $dsplySzeArry = array("Supplier Standard Payment",
                                                                            "Supplier Advance Payment",
                                                                            "Supplier Credit Memo (Indirect-Refund)",
                                                                            "Customer Standard Payment",
                                                                            "Customer Advance Payment",
                                                                            "Customer Debit Memo (Indirect-Refund)");
                                                                        for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                            if ($accbDocTmpltsType == $dsplySzeArry[$y]) {
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
                                                                <?php } else { ?>
                                                                    <span><?php echo $accbDocTmpltsType; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px 1px 0px 1px !important;">
                                                    <?php
                                                    $nwRowHtml33 = "<tr id=\"accbDocTmpltAdtTblsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAccbPyblsInvcSmryLinesTable tr').index(this));\">"
                                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control rqrdFld chosen-select\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_ItemType\" style=\"width:100% !important;\">";

                                                    $valslctdArry = array("", "", "", "", "");
                                                    $srchInsArrys = array("1Initial Amount", "2Tax", "3Discount", "4Extra Charge",
                                                        "5Applied Prepayment");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                    }
                                                    $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\"  style=\"\">  
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_CodeBhndID\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_CodeBhndID\" value=\"-1\"> 
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_DetID\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_DetID\" value=\"-1\">                                                                           
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_LineDesc\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getAccbDocTmpltLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'accbDocTmpltAdtTblsRow_WWW123WWW_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbDocTmpltAdtTblsRow_WWW123WWW_CodeBhndID', 'accbDocTmpltAdtTblsRow_WWW123WWW_LineDesc', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control rqrdFld chosen-select\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_IncrsDcrs\" style=\"width:100% !important;\">";
                                                    $valslctdArry = array("", "");
                                                    $srchInsArrys = array("Increase", "Decrease");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                    }
                                                    $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_AccountID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_AccountNm\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_AccountNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbDocTmpltAdtTblsRow_WWW123WWW_AccountID', 'accbDocTmpltAdtTblsRow_WWW123WWW_AccountNm', 'clear', 1, '', function () {
                                                                changeElmntTitleFunc('accbDocTmpltAdtTblsRow_WWW123WWW_AccountNm');
                                                            });\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>                                           
                                                        </td>
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <div class=\"form-group form-group-sm \">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_AutoCalc\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_AutoCalc\">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbPyblsInvcDetLn('accbDocTmpltAdtTblsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>";
                                                    if ($canVwRcHstry) {
                                                        $nwRowHtml33 .= "<td>&nbsp;</td>";
                                                    }
                                                    $nwRowHtml33 .= "</tr>";
                                                    $nwRowHtml33 = urlencode($nwRowHtml33);
                                                    $nwRowHtml1 = str_replace("WWW_LINETYPE_WWW", "1Initial Amount", $nwRowHtml33);
                                                    ?> 
                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                                            <?php if ($canEdt) { ?>
                                                                <button id="addNwAccbPyblsInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewAccbDocTmpltRows('accbDocTmpltAdtTblsTable', 0, '<?php echo $nwRowHtml1; ?>', '1Initial Amount');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Add New Template Line
                                                                </button>                                 
                                                            <?php } ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneAccbDocTmpltForm(<?php echo $pkID ?>, 1);"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        </div>                   
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px 1px 0px 1px !important;">         
                                                    <table class="table table-striped table-bordered table-responsive" id="accbDocTmpltAdtTblsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;text-align: center;">No.</th>
                                                                <th style="max-width:95px;width:95px;">Line Item Type</th>
                                                                <th>Item Description</th>
                                                                <th style="max-width:80px;width:80px;">Increase / Decrease</th>
                                                                <th>Charge Account</th>
                                                                <th style="max-width:45px;width:45px;text-align: center;">Auto Calc</th>
                                                                <th style="max-width:20px;width:20px;text-align: center;">...</th>
                                                                <?php if ($canVwRcHstry) { ?>
                                                                    <th style="max-width:20px;width:20px;text-align: center;">...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $result2 = get_DocTmpltsDet($pkID);
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                                $cntr += 1;
                                                                $lineDetID = $row2[0];
                                                                $lineCodeBhndID = $row2[7];
                                                                $trsctnLineType = $row2[1];
                                                                $lineDesc = $row2[2];
                                                                $trnsIncrsDcrs1 = $row2[3];
                                                                $trsctnAcntID = $row2[4];
                                                                $trsctnAcntNm = $row2[5];
                                                                $shdAutoCalc = $row2[6];
                                                                ?>
                                                                <tr id="accbDocTmpltAdtTblsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <select data-placeholder="Select..." class="form-control rqrdFld chosen-select" id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_ItemType" style="width:100% !important;">
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
                                                                        <?php } else { ?>
                                                                            <span><?php echo $trsctnLineType; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_CodeBhndID" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $lineCodeBhndID; ?>"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_DetID" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_DetID" value="<?php echo $lineDetID; ?>">                                                                           
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_LineDesc" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $lineDesc; ?>" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbDocTmpltLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $lineCodeBhndID; ?>', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_CodeBhndID', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_LineDesc', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $lineDesc; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>   
                                                                    <td class="lovtd">
                                                                        <select data-placeholder="Select..." class="form-control rqrdFld chosen-select" id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_IncrsDcrs" style="width:100% !important;">
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
                                                                        <input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;"> 
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountNm" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountID', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountNm', 'clear', 1, '', function () {
                                                                                            changeElmntTitleFunc('accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountNm');
                                                                                        });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>    
                                                                        <?php } else { ?>
                                                                            <span><?php echo $trsctnAcntNm; ?></span>
                                                                        <?php } ?>                                             
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        if ($shdAutoCalc == "1") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <div class="form-group form-group-sm ">
                                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                                <label class="form-check-label">
                                                                                    <input type="checkbox" class="form-check-input" id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AutoCalc" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AutoCalc" <?php echo $isChkd ?>>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </td> 
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbDocTmpltTrans('accbDocTmpltAdtTblsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Template">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                        ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                            echo urlencode(encrypt1(($lineDetID . "|accb.accb_doc_tmplts_det|doc_tmplt_det_id"),
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
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <span>No Results Found</span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 1) {
                $pkID = isset($_POST['sbmtdDcTmpltID']) ? $_POST['sbmtdDcTmpltID'] : -1;

                $accbDocTmpltsID = -1;
                $accbDocTmpltsName = "";
                $accbDocTmpltsDesc = "";
                $accbDocTmpltsType = "";
                $accbDocTmpltsIsEnbld = "1";

                if ($pkID > 0) {
                    $result1 = get_OneDocTmpltsHdr($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $accbDocTmpltsID = $row1[0];
                        $accbDocTmpltsName = $row1[1];
                        $accbDocTmpltsDesc = $row1[2];
                        $accbDocTmpltsType = $row1[3];
                        $accbDocTmpltsIsEnbld = $row1[4];
                    }
                } else if (!($canAdd || $canEdt)) {
                    restricted();
                    exit();
                }
                ?>
                <div class="row">
                    <div  class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 1px 0px 1px !important;">
                                <label for="accbDocTmpltsName" class="control-label col-lg-3">Template Name:</label>
                                <div  class="col-lg-9">
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <input type="text" class="form-control" aria-label="..." id="accbDocTmpltsName" name="accbDocTmpltsName" value="<?php echo $accbDocTmpltsName; ?>" style="width:100% !important;">
                                        <input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltsID" name="accbDocTmpltsID" value="<?php echo $accbDocTmpltsID; ?>">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $accbDocTmpltsName; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 1px 0px 1px !important;">
                                <label for="accbDocTmpltsIsEnbld" class="control-label col-lg-3">Is Enabled?:</label>
                                <div  class="col-lg-9">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    if ($accbDocTmpltsIsEnbld == "1") {
                                        $chkdNo = "";
                                        $chkdYes = "checked=\"\"";
                                    }
                                    ?>
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <label class="radio-inline"><input type="radio" name="accbDocTmpltsIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                        <label class="radio-inline"><input type="radio" name="accbDocTmpltsIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                    <?php } else {
                                        ?>
                                        <span><?php echo ($accbDocTmpltsIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div  class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 1px 0px 1px !important;">
                                <label for="accbDocTmpltsDesc" class="control-label col-lg-3">Template Description:</label>
                                <div  class="col-lg-9">
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <input type="text" class="form-control" aria-label="..." id="accbDocTmpltsDesc" name="accbDocTmpltsDesc" value="<?php echo $accbDocTmpltsDesc; ?>" style="width:100% !important;">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $accbDocTmpltsDesc; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 1px 0px 1px !important;">
                                <label for="accbDocTmpltsType" class="control-label col-lg-3">Document Type:</label>
                                <div  class="col-lg-9">
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbDocTmpltsType" style="min-width:70px !important;">                            
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "");
                                            $dsplySzeArry = array("Supplier Standard Payment",
                                                "Supplier Advance Payment",
                                                "Supplier Credit Memo (Indirect-Refund)",
                                                "Customer Standard Payment",
                                                "Customer Advance Payment",
                                                "Customer Debit Memo (Indirect-Refund)");
                                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                if ($accbDocTmpltsType == $dsplySzeArry[$y]) {
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
                                    <?php } else { ?>
                                        <span><?php echo $accbDocTmpltsType; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="padding:0px 4px 0px 4px !important;">
                        <?php
                        $nwRowHtml33 = "<tr id=\"accbDocTmpltAdtTblsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAccbPyblsInvcSmryLinesTable tr').index(this));\">"
                                . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control rqrdFld chosen-select\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_ItemType\" style=\"width:100% !important;\">";

                        $valslctdArry = array("", "", "", "", "");
                        $srchInsArrys = array("1Initial Amount", "2Tax", "3Discount", "4Extra Charge",
                            "5Applied Prepayment");
                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                            $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                        }
                        $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\"  style=\"\">  
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_CodeBhndID\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_CodeBhndID\" value=\"-1\"> 
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_DetID\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_DetID\" value=\"-1\">                                                                           
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_LineDesc\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getAccbDocTmpltLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'accbDocTmpltAdtTblsRow_WWW123WWW_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbDocTmpltAdtTblsRow_WWW123WWW_CodeBhndID', 'accbDocTmpltAdtTblsRow_WWW123WWW_LineDesc', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_IncrsDcrs\" style=\"width:100% !important;\">";
                        $valslctdArry = array("", "");
                        $srchInsArrys = array("Increase", "Decrease");
                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                            $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                        }
                        $nwRowHtml33 .= "</select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_AccountID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_AccountNm\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_AccountNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbDocTmpltAdtTblsRow_WWW123WWW_AccountID', 'accbDocTmpltAdtTblsRow_WWW123WWW_AccountNm', 'clear', 1, '', function () {
                                                                changeElmntTitleFunc('accbDocTmpltAdtTblsRow_WWW123WWW_AccountNm');
                                                            });\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                            </div>                                           
                                                        </td>
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <div class=\"form-group form-group-sm \">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"accbDocTmpltAdtTblsRow_WWW123WWW_AutoCalc\" name=\"accbDocTmpltAdtTblsRow_WWW123WWW_AutoCalc\">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbPyblsInvcDetLn('accbDocTmpltAdtTblsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>";
                        if ($canVwRcHstry) {
                            $nwRowHtml33 .= "<td>&nbsp;</td>";
                        }
                        $nwRowHtml33 .= "</tr>";
                        $nwRowHtml33 = urlencode($nwRowHtml33);
                        $nwRowHtml1 = str_replace("WWW_LINETYPE_WWW", "1Initial Amount", $nwRowHtml33);
                        ?> 
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                <?php if ($canEdt) { ?>
                                    <button id="addNwAccbPyblsInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewAccbDocTmpltRows('accbDocTmpltAdtTblsTable', 0, '<?php echo $nwRowHtml1; ?>', '1Initial Amount');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add New Template Line
                                    </button>                                 
                                <?php } ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneAccbDocTmpltForm(<?php echo $pkID ?>, 1);"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                            </div>                   
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="padding:0px 1px 0px 1px !important;">         
                        <table class="table table-striped table-bordered table-responsive" id="accbDocTmpltAdtTblsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                            <thead>
                                <tr>
                                    <th style="max-width:30px;width:30px;text-align: center;">No.</th>
                                    <th style="max-width:95px;width:95px;">Line Item Type</th>
                                    <th>Item Description</th>
                                    <th style="max-width:80px;width:80px;">Increase / Decrease</th>
                                    <th>Charge Account</th>
                                    <th style="max-width:45px;width:45px;text-align: center;">Auto Calc</th>
                                    <th style="max-width:20px;width:20px;text-align: center;">...</th>
                                    <?php if ($canVwRcHstry) { ?>
                                        <th style="max-width:20px;width:20px;text-align: center;">...</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result2 = get_DocTmpltsDet($pkID);
                                $cntr = 0;
                                $curIdx = 0;
                                while ($row2 = loc_db_fetch_array($result2)) {
                                    $cntr += 1;
                                    $lineDetID = $row2[0];
                                    $lineCodeBhndID = $row2[7];
                                    $trsctnLineType = $row2[1];
                                    $lineDesc = $row2[2];
                                    $trnsIncrsDcrs1 = $row2[3];
                                    $trsctnAcntID = $row2[4];
                                    $trsctnAcntNm = $row2[5];
                                    $shdAutoCalc = $row2[6];
                                    ?>
                                    <tr id="accbDocTmpltAdtTblsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <td class="lovtd">
                                            <?php
                                            if ($canEdt === true) {
                                                ?>
                                                <select data-placeholder="Select..." class="form-control rqrdFld chosen-select" id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_ItemType" style="width:100% !important;">
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
                                            <?php } else { ?>
                                                <span><?php echo $trsctnLineType; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_CodeBhndID" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $lineCodeBhndID; ?>"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_DetID" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_DetID" value="<?php echo $lineDetID; ?>">                                                                           
                                            <?php
                                            if ($canEdt === true) {
                                                ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_LineDesc" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $lineDesc; ?>" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbDocTmpltLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_ItemType', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $lineCodeBhndID; ?>', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_CodeBhndID', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_LineDesc', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div><?php } else {
                                                ?>
                                                <span><?php echo $lineDesc; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </td>   
                                        <td class="lovtd">
                                            <select data-placeholder="Select..." class="form-control rqrdFld chosen-select" id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_IncrsDcrs" style="width:100% !important;">
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
                                            <input type="hidden" class="form-control" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;"> 
                                            <?php
                                            if ($canEdt === true) {
                                                ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountNm" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountID', 'accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountNm', 'clear', 1, '', function () {
                                                                changeElmntTitleFunc('accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AccountNm');
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>    
                                            <?php } else { ?>
                                                <span><?php echo $trsctnAcntNm; ?></span>
                                            <?php } ?>                                             
                                        </td>
                                        <td class="lovtd" style="text-align: center;">
                                            <?php
                                            $isChkd = "";
                                            if ($shdAutoCalc == "1") {
                                                $isChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <div class="form-group form-group-sm ">
                                                <div class="form-check" style="font-size: 12px !important;">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" id="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AutoCalc" name="accbDocTmpltAdtTblsRow<?php echo $cntr; ?>_AutoCalc" <?php echo $isChkd ?>>
                                                    </label>
                                                </div>
                                            </div>
                                        </td> 
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbDocTmpltTrans('accbDocTmpltAdtTblsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Template">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <?php
                                        if ($canVwRcHstry === true) {
                                            ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                echo urlencode(encrypt1(($lineDetID . "|accb.accb_doc_tmplts_det|doc_tmplt_det_id"), $smplTokenWord1));
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
                <?php
            } else if ($vwtyp == 3) {
                
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    