<?php
$canAdd = test_prmssns($dfltPrvldgs[11], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[12], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[13], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Template */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteTaxItm($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Tax Codes
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $accbTxCdeID = isset($_POST['accbTxCdeID']) ? (int) cleanInputData($_POST['accbTxCdeID']) : -1;
                $accbTxCdeName = isset($_POST['accbTxCdeName']) ? cleanInputData($_POST['accbTxCdeName']) : "";
                $accbTxCdeDesc = isset($_POST['accbTxCdeDesc']) ? cleanInputData($_POST['accbTxCdeDesc']) : "";
                $accbTxCdeType = isset($_POST['accbTxCdeType']) ? cleanInputData($_POST['accbTxCdeType']) : "";
                $accbTxCdeSQL = isset($_POST['accbTxCdeSQL']) ? cleanInputData($_POST['accbTxCdeSQL']) : "";
                $accbTxCdeUnitPrc = isset($_POST['accbTxCdeUnitPrc']) ? (float) cleanInputData($_POST['accbTxCdeUnitPrc']) : 0.00;
                $accbTxCdeQty = isset($_POST['accbTxCdeQty']) ? (float) cleanInputData($_POST['accbTxCdeQty']) : 0.00;

                $accbTxCdePyblAcntID = isset($_POST['accbTxCdePyblAcntID']) ? (int) cleanInputData($_POST['accbTxCdePyblAcntID']) : -1;
                $accbTxCdeExpnsAcntID = isset($_POST['accbTxCdeExpnsAcntID']) ? (int) cleanInputData($_POST['accbTxCdeExpnsAcntID']) : -1;
                $accbTxCdeRvnuAcntID = isset($_POST['accbTxCdeRvnuAcntID']) ? (int) cleanInputData($_POST['accbTxCdeRvnuAcntID']) : -1;
                $accbTxCdeTxExpAccID = isset($_POST['accbTxCdeTxExpAccID']) ? (int) cleanInputData($_POST['accbTxCdeTxExpAccID']) : -1;
                $accbTxCdePrchDscAccID = isset($_POST['accbTxCdePrchDscAccID']) ? (int) cleanInputData($_POST['accbTxCdePrchDscAccID']) : -1;
                $accbTxCdeChrgExpAccID = isset($_POST['accbTxCdeChrgExpAccID']) ? (int) cleanInputData($_POST['accbTxCdeChrgExpAccID']) : -1;

                $accbTxCdeIsEnbld = isset($_POST['accbTxCdeIsEnbld']) ? (cleanInputData($_POST['accbTxCdeIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $accbTxCdeIsParnt = isset($_POST['accbTxCdeIsParnt']) ? (cleanInputData($_POST['accbTxCdeIsParnt']) == "YES" ? TRUE : FALSE) : FALSE;
                $accbTxCdeIsWthHldng = isset($_POST['accbTxCdeIsWthHldng']) ? (cleanInputData($_POST['accbTxCdeIsWthHldng']) == "YES" ? TRUE : FALSE) : FALSE;
                $accbTxCdeIsTxRcvrbl = isset($_POST['accbTxCdeIsTxRcvrbl']) ? (cleanInputData($_POST['accbTxCdeIsTxRcvrbl']) == "YES" ? TRUE : FALSE) : FALSE;
                $slctdCodeIDs = isset($_POST['slctdCodeIDs']) ? cleanInputData($_POST['slctdCodeIDs']) : "";

                $exitErrMsg = "";
                if ($accbTxCdeName == "") {
                    $exitErrMsg .= "Please enter Item Name!<br/>";
                }
                if ($accbTxCdeType == "") {
                    $exitErrMsg .= "Please enter Item Type!<br/>";
                }
                if ($accbTxCdeIsParnt == false) {
                    if ($accbTxCdeType == "Tax" && $accbTxCdePyblAcntID <= 0) {
                        $exitErrMsg .= "Taxes Payable Account CANNOT be EMPTY if Item Type is Tax!";
                    }
                    if ($accbTxCdeType != "Tax" && $accbTxCdePyblAcntID > 0) {
                        $exitErrMsg .= "Taxes Payable Account MUST be EMPTY if Item Type is not Tax!";
                    }

                    if ($accbTxCdeType == "Discount" && $accbTxCdeExpnsAcntID <= 0) {
                        $exitErrMsg .= "Contra Revenue Account CANNOT be EMPTY if Item Type is Discount!";
                    }
                    if ($accbTxCdeType != "Discount" && $accbTxCdeExpnsAcntID > 0) {
                        $exitErrMsg .= "Contra Revenue Account MUST be EMPTY if Item Type is not Discount!";
                    }
                    if ($accbTxCdeType == "Extra Charge" && $accbTxCdeRvnuAcntID <= 0) {
                        $exitErrMsg .= "Revenue Account CANNOT be EMPTY if Item Type is Extra Charge!";
                    }
                    if ($accbTxCdeType != "Extra Charge" && $accbTxCdeRvnuAcntID > 0) {
                        $exitErrMsg .= "Revenue Account MUST be EMPTY if Item Type is not Extra Charge!";
                    }
                } else if (trim($slctdCodeIDs, "|~") === "") {
                    $exitErrMsg .= "Child Items cannot be empty for a Parent Item!";
                }
                $CalcItemValue = 0.00;
                if (!isTxCdeSQLValid($accbTxCdeSQL, $accbTxCdeUnitPrc, $accbTxCdeQty, $CalcItemValue)) {
                    $exitErrMsg .= "SQL is NOT valid!";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['accbTxCdeID'] = $accbTxCdeID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = getTaxID($accbTxCdeName, $orgID);
                if (($oldID <= 0 || $oldID == $accbTxCdeID)) {
                    if ($accbTxCdeID <= 0) {
                        createTaxRec($orgID, $accbTxCdeName, $accbTxCdeDesc, $accbTxCdeType, $accbTxCdeIsEnbld, $accbTxCdePyblAcntID
                                , $accbTxCdeExpnsAcntID, $accbTxCdeRvnuAcntID, $accbTxCdeSQL, $accbTxCdeIsTxRcvrbl, $accbTxCdeTxExpAccID, $accbTxCdePrchDscAccID, $accbTxCdeChrgExpAccID, $accbTxCdeIsWthHldng, $accbTxCdeIsParnt, $slctdCodeIDs);
                        $accbTxCdeID = getTaxID($accbTxCdeName, $orgID);
                    } else {
                        updateTaxRec($accbTxCdeID, $accbTxCdeName, $accbTxCdeDesc, $accbTxCdeType, $accbTxCdeIsEnbld, $accbTxCdePyblAcntID
                                , $accbTxCdeExpnsAcntID, $accbTxCdeRvnuAcntID, $accbTxCdeSQL, $accbTxCdeIsTxRcvrbl, $accbTxCdeTxExpAccID, $accbTxCdePrchDscAccID, $accbTxCdeChrgExpAccID, $accbTxCdeIsWthHldng, $accbTxCdeIsParnt, $slctdCodeIDs);
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Tax/Discount/Charge Successfully Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Tax/Discount/Charge Successfully Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['accbTxCdeID'] = $accbTxCdeID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Item Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['accbTxCdeID'] = $accbTxCdeID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdTaxCodeID']) ? $_POST['sbmtdTaxCodeID'] : -1;
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Tax Codes</span>
				</li>
                               </ul>
                              </div>";

                $taxNm = array("T.L 1%", "VAT-17.5%", "VAT-17.5% + TL 1%", "Miscellaneous Charges");
                $taxSQL = array("select 0.010*{:unit_price}", "select 0.175*{:unit_price}",
                    "select 0.185*{:unit_price}", "Select 1");
                $lneTypDec = array("Tax", "Tax", "Tax", "Extra Charge");
                $isParent = array(false, false, true, false);
                $isWithldngTax = array(false, false, false, false);
                $isEnbld1 = array(true, true, false, true);
                $codeIDs = ",";
                for ($f = 0; $f < count($taxNm); $f++) {
                    $oldTaxID = getGnrlRecID("scm.scm_tax_codes", "code_name", "code_id", $taxNm[$f], $orgID);
                    if ($oldTaxID <= 0) {
                        $codename = $taxNm[$f];
                        $codedesc = $taxNm[$f];
                        $itmTyp = $lneTypDec[$f];
                        $isEnbld = false;
                        $taxAcntID = -1;
                        $expnsAcntID = -1;
                        $rvnuAcntID = -1;
                        $sqlFormular = $taxSQL[$f];
                        $isTxRcvrbl = false;
                        $txExpAccID = -1;
                        $prchDscAccID = -1;
                        $chrgExpAccID = -1;
                        $isWthHldng = $isWithldngTax[$f];
                        $isParnt = $isParent[$f];
                        createTaxRec($orgID, $codename, $codedesc, $itmTyp, $isEnbld, $taxAcntID, $expnsAcntID, $rvnuAcntID, $sqlFormular, $isTxRcvrbl, $txExpAccID, $prchDscAccID, $chrgExpAccID, $isWthHldng, $isParnt, $codeIDs);
                        $oldTaxID = getGnrlRecID("scm.scm_tax_codes", "code_name", "code_id", $taxNm[$f], $orgID);
                    }
                }

                $total = get_Total_Tax($srchFor, $srchIn, $orgID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_Basic_Tax($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='accbTxCdeForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php if ($canAdd === true) { ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAccbTaxCodeForm(-1, 1);;" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Tax/Discount/Extra Charge Code">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Code
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAccbTaxCodeForm();" style="width:100% !important;">
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
                                <input class="form-control" id="accbTxCdeSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAccbTxCde(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="accbTxCdePageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbTxCde('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbTxCde('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbTxCdeSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Item Name", "Item Description", "Item Type");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbTxCdeDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAccbTxCde('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAccbTxCde('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row"> 
                        <div  class="col-md-3" style="padding:0px 1px 0px 15px !important;">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="accbTxCdeTable" cellspacing="0" width="100%" style="width:100% !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Taxes / Discounts / Charges</th>
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
                                            <tr id="accbTxCdeRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbTxCdeRow<?php echo $cntr; ?>_CodeID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbTxCdeRow<?php echo $cntr; ?>_CodeNm" value="<?php echo $row[1]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbTxCde('accbTxCdeRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|scm.scm_tax_codes|code_id"), $smplTokenWord1));
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
                                <div class="container-fluid" id="accbTaxCodeDetailInfo">
                                    <?php
                                    if ($pkID > 0) {
                                        $result1 = get_OneTaxCodesHdr($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $accbTxCdeID = $row1[0];
                                            $accbTxCdeName = $row1[1];
                                            $accbTxCdeDesc = $row1[2];
                                            $accbTxCdeType = $row1[3];
                                            $accbTxCdeIsEnbld = $row1[4];
                                            $accbTxCdePyblAcntID = $row1[5];
                                            $accbTxCdePyblAcntNm = $row1[6];
                                            $accbTxCdeExpnsAcntID = $row1[7];
                                            $accbTxCdeExpnsAcntNm = $row1[8];
                                            $accbTxCdeRvnuAcntID = $row1[9];
                                            $accbTxCdeRvnuAcntNm = $row1[10];
                                            $accbTxCdeSQL = $row1[11];
                                            $accbTxCdeIsTxRcvrbl = $row1[12];
                                            $accbTxCdeTxExpAccID = $row1[13];
                                            $accbTxCdeTxExpAccNm = $row1[14];
                                            $accbTxCdePrchDscAccID = $row1[15];
                                            $accbTxCdePrchDscAccNm = $row1[16];
                                            $accbTxCdeChrgExpAccID = $row1[17];
                                            $accbTxCdeChrgExpAccNm = $row1[18];
                                            $accbTxCdeIsWthHldng = $row1[19];
                                            $accbTxCdeIsParnt = $row1[20];
                                            $slctdCodeIDs = $row1[21];
                                            ?>
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeName" class="control-label col-lg-4">Item Code:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="accbTxCdeName" name="accbTxCdeName" value="<?php echo $accbTxCdeName; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbTxCdeID" name="accbTxCdeID" value="<?php echo $accbTxCdeID; ?>">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $accbTxCdeName; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeDesc" class="control-label col-lg-4">Description:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="accbTxCdeDesc" name="accbTxCdeDesc" value="<?php echo $accbTxCdeDesc; ?>" style="width:100% !important;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $accbTxCdeDesc; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeType" class="control-label col-lg-4">Item Type:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbTxCdeType" style="min-width:70px !important;">                            
                                                                        <?php
                                                                        $valslctdArry = array("", "", "");
                                                                        $dsplySzeArry = array("Tax",
                                                                            "Discount",
                                                                            "Extra Charge");
                                                                        for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                            if ($accbTxCdeType == $dsplySzeArry[$y]) {
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
                                                                    <span><?php echo $accbTxCdeType; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>                      
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;margin-top:17px;">
                                                            <label for="accbTxCdePyblAcntNm" class="control-label col-md-4">Taxes Payable:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbTxCdePyblAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Payable Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdePyblAcntNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbTxCdePyblAcntID" value="<?php echo $accbTxCdePyblAcntID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdePyblAcntID', 'accbTxCdePyblAcntNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>                            
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeExpnsAcntNm" class="control-label col-md-4">Sales Discount:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbTxCdePyblAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Sales Discount Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdeExpnsAcntNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbTxCdeExpnsAcntID" value="<?php echo $accbTxCdeExpnsAcntID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdeExpnsAcntID', 'accbTxCdeExpnsAcntNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>                            
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeRvnuAcntNm" class="control-label col-md-4">Charges Revenue/Liability:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbTxCdeRvnuAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Revenue/Liability Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdeRvnuAcntNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbTxCdeRvnuAcntID" value="<?php echo $accbTxCdeRvnuAcntID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Revenue and Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdeRvnuAcntID', 'accbTxCdeRvnuAcntNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">                            
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeTxExpAccNm" class="control-label col-md-4">Tax Expense/ Receivable:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbTxCdeTxExpAccNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Expense/Receivable Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdeTxExpAccNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbTxCdeTxExpAccID" value="<?php echo $accbTxCdeTxExpAccID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset and Expenditure Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdeTxExpAccID', 'accbTxCdeTxExpAccNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>                            
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdePyblAcntNm" class="control-label col-md-4">Purchase Discount:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbTxCdePrchDscAccNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Purchase Discount Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdePrchDscAccNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbTxCdePrchDscAccID" value="<?php echo $accbTxCdePrchDscAccID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdePrchDscAccID', 'accbTxCdePrchDscAccNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>                            
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeChrgExpAccNm" class="control-label col-md-4">Charges Expense:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbTxCdeChrgExpAccNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Expense Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdeChrgExpAccNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbTxCdeChrgExpAccID" value="<?php echo $accbTxCdeChrgExpAccID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Expense Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdeChrgExpAccID', 'accbTxCdeChrgExpAccNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeIsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                                                            <div  class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($accbTxCdeIsEnbld == "1") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($accbTxCdeIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>      
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeIsParnt" class="control-label col-lg-6">Is Parent?:</label>
                                                            <div  class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($accbTxCdeIsParnt == "1") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsParnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsParnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($accbTxCdeIsParnt == "1" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeIsWthHldng" class="control-label col-lg-6">Is Witholding?:</label>
                                                            <div  class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($accbTxCdeIsWthHldng == "1") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsWthHldng" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsWthHldng" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($accbTxCdeIsWthHldng == "1" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>      
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeIsTxRcvrbl" class="control-label col-lg-6">Is Recoverable?:</label>
                                                            <div  class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($accbTxCdeIsTxRcvrbl == "1") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsTxRcvrbl" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsTxRcvrbl" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($accbTxCdeIsTxRcvrbl == "1" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;margin-bottom: 5px!important;">
                                                            <label for="accbTxCdeSQL" class="control-label col-md-2">SQL Formular:</label>
                                                            <div class="col-md-10">
                                                                <div class="input-group"  style="width:100%;">
                                                                    <textarea class="form-control rqrdFld" rows="1" cols="20" id="accbTxCdeSQL" name="accbTxCdeSQL" style="text-align:left !important;"><?php echo $accbTxCdeSQL; ?></textarea>
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbTxCdeSQL');" style="max-width:30px;width:30px;">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>   
                                                        <div class="form-group form-group-sm col-md-4" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeUnitPrc" class="control-label col-lg-6" style="margin-top:6px;">{:unit_price}</label>
                                                            <div  class="col-lg-6" style="">
                                                                <input type="number" class="form-control" aria-label="..." id="accbTxCdeUnitPrc" name="accbTxCdeUnitPrc" value="1" style="width:100% !important;">
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm col-md-2" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="accbTxCdeQty" class="control-label col-lg-4" style="margin-top:6px;">{:qty}</label>
                                                            <div  class="col-lg-8" style="">
                                                                <input type="number" class="form-control" aria-label="..." id="accbTxCdeQty" name="accbTxCdeQty" value="1" style="width:100% !important;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-2" style="padding:0px 0px 0px 0px !important;">
                                                            <div  class="col-lg-12" style="">
                                                                <button id="addNwAccbPyblsInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="testTxCdeSQLQuery()" data-toggle="tooltip" data-placement="bottom" title = "Test SQL">
                                                                    <img src="cmn_images/tick_64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Test Query
                                                                </button>  
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-4" style="padding:0px 15px 0px 0px !important;">
                                                            <label id="accbTxCdeSQLTestRslts" style="width:100% !important;color:green;margin-left:1px;font-size: 13px;font-weight: bold;border:1px solid #ddd;border-radius: 5px;padding:5px;">
                                                                &nbsp;TEST RESULTS
                                                            </label>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <?php
                                                    $nwRowHtml33 = "<tr id=\"accbTaxCodeAdtTblsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#accbTaxCodeAdtTblsTable tr').index(this));\">"
                                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>  
                                                        <td class=\"lovtd\"  style=\"\">  
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbTaxCodeAdtTblsRow_WWW123WWW_CodeBhndID\" name=\"accbTaxCodeAdtTblsRow_WWW123WWW_CodeBhndID\" value=\"-1\"> 
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbTaxCodeAdtTblsRow_WWW123WWW_LineDesc\" name=\"accbTaxCodeAdtTblsRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getAccbTxCdeLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'accbTxCdeType', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTaxCodeAdtTblsRow_WWW123WWW_LineDesc', 'accbTaxCodeAdtTblsRow_WWW123WWW_CodeBhndID', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbTxCdeLne('accbTaxCodeAdtTblsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Child Code\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>";
                                                    $nwRowHtml33 .= "</tr>";
                                                    $nwRowHtml33 = urlencode($nwRowHtml33);
                                                    $nwRowHtml1 = $nwRowHtml33;
                                                    ?> 
                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                                            <?php if ($canEdt) { ?>
                                                                <button id="addNwAccbPyblsInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewAccbTaxCodeRows('accbTaxCodeAdtTblsTable', 0, '<?php echo $nwRowHtml1; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Add New Child Code
                                                                </button>                                 
                                                            <?php } ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneAccbTaxCodeForm(<?php echo $pkID ?>, 1);"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        </div>                   
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">         
                                                    <table class="table table-striped table-bordered table-responsive" id="accbTaxCodeAdtTblsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;text-align: center;">No.</th>
                                                                <th>Item Description</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            if (trim($slctdCodeIDs, ",") != "") {
                                                                $variousRows = explode(",", trim($slctdCodeIDs, ","));
                                                                for ($z = 0; $z < count($variousRows); $z++) {
                                                                    $lineDetID = (float) (cleanInputData1($variousRows[$z]));
                                                                    $trsctnLineType = getTaxNm($lineDetID);
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="accbTaxCodeAdtTblsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <input type="hidden" id="accbTaxCodeAdtTblsRow<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $lineDetID; ?>"/>
                                                                            <input type="hidden" id="accbTaxCodeAdtTblsRow<?php echo $cntr; ?>_LineDesc"value="<?php echo $trsctnLineType; ?>">
                                                                            <span><?php echo $trsctnLineType; ?></span>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbTxCdeLne('accbTaxCodeAdtTblsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Child Code">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
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
                $pkID = isset($_POST['sbmtdTaxCodeID']) ? $_POST['sbmtdTaxCodeID'] : -1;
                $accbTxCdeID = -1;
                $accbTxCdeName = "";
                $accbTxCdeDesc = "";
                $accbTxCdeType = "";
                $accbTxCdeIsEnbld = "0";
                $accbTxCdePyblAcntID = -1;
                $accbTxCdePyblAcntNm = "";
                $accbTxCdeExpnsAcntID = -1;
                $accbTxCdeExpnsAcntNm = "";
                $accbTxCdeRvnuAcntID = -1;
                $accbTxCdeRvnuAcntNm = "";
                $accbTxCdeSQL = "select 0";
                $accbTxCdeIsTxRcvrbl = "0";
                $accbTxCdeTxExpAccID = -1;
                $accbTxCdeTxExpAccNm = "";
                $accbTxCdePrchDscAccID = -1;
                $accbTxCdePrchDscAccNm = "";
                $accbTxCdeChrgExpAccID = -1;
                $accbTxCdeChrgExpAccNm = "";
                $accbTxCdeIsWthHldng = "0";
                $accbTxCdeIsParnt = "0";
                $slctdCodeIDs = ",";

                if ($pkID > 0) {
                    $result1 = get_OneTaxCodesHdr($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $accbTxCdeID = $row1[0];
                        $accbTxCdeName = $row1[1];
                        $accbTxCdeDesc = $row1[2];
                        $accbTxCdeType = $row1[3];
                        $accbTxCdeIsEnbld = $row1[4];
                        $accbTxCdePyblAcntID = $row1[5];
                        $accbTxCdePyblAcntNm = $row1[6];
                        $accbTxCdeExpnsAcntID = $row1[7];
                        $accbTxCdeExpnsAcntNm = $row1[8];
                        $accbTxCdeRvnuAcntID = $row1[9];
                        $accbTxCdeRvnuAcntNm = $row1[10];
                        $accbTxCdeSQL = $row1[11];
                        $accbTxCdeIsTxRcvrbl = $row1[12];
                        $accbTxCdeTxExpAccID = $row1[13];
                        $accbTxCdeTxExpAccNm = $row1[14];
                        $accbTxCdePrchDscAccID = $row1[15];
                        $accbTxCdePrchDscAccNm = $row1[16];
                        $accbTxCdeChrgExpAccID = $row1[17];
                        $accbTxCdeChrgExpAccNm = $row1[18];
                        $accbTxCdeIsWthHldng = $row1[19];
                        $accbTxCdeIsParnt = $row1[20];
                        $slctdCodeIDs = $row1[21];
                    }
                } else if (!($canAdd || $canEdt)) {
                    restricted();
                    exit();
                }
                ?>
                <div class="row">
                    <div  class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeName" class="control-label col-lg-4">Item Code:</label>
                                <div  class="col-lg-8">
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <input type="text" class="form-control" aria-label="..." id="accbTxCdeName" name="accbTxCdeName" value="<?php echo $accbTxCdeName; ?>" style="width:100% !important;">
                                        <input type="hidden" class="form-control" aria-label="..." id="accbTxCdeID" name="accbTxCdeID" value="<?php echo $accbTxCdeID; ?>">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $accbTxCdeName; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeDesc" class="control-label col-lg-4">Description:</label>
                                <div  class="col-lg-8">
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <input type="text" class="form-control" aria-label="..." id="accbTxCdeDesc" name="accbTxCdeDesc" value="<?php echo $accbTxCdeDesc; ?>" style="width:100% !important;">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $accbTxCdeDesc; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeType" class="control-label col-lg-4">Item Type:</label>
                                <div  class="col-lg-8">
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbTxCdeType" style="min-width:70px !important;">                            
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $dsplySzeArry = array("Tax",
                                                "Discount",
                                                "Extra Charge");
                                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                if ($accbTxCdeType == $dsplySzeArry[$y]) {
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
                                        <span><?php echo $accbTxCdeType; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>                      
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;margin-top:17px;">
                                <label for="accbTxCdePyblAcntNm" class="control-label col-md-4">Taxes Payable:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="accbTxCdePyblAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Payable Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdePyblAcntNm; ?>" readonly="true"/>
                                        <input type="hidden" id="accbTxCdePyblAcntID" value="<?php echo $accbTxCdePyblAcntID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdePyblAcntID', 'accbTxCdePyblAcntNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeExpnsAcntNm" class="control-label col-md-4">Sales Discount:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="accbTxCdePyblAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Sales Discount Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdeExpnsAcntNm; ?>" readonly="true"/>
                                        <input type="hidden" id="accbTxCdeExpnsAcntID" value="<?php echo $accbTxCdeExpnsAcntID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdeExpnsAcntID', 'accbTxCdeExpnsAcntNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeRvnuAcntNm" class="control-label col-md-4">Charges Revenue/Liability:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="accbTxCdeRvnuAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Revenue/Liability Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdeRvnuAcntNm; ?>" readonly="true"/>
                                        <input type="hidden" id="accbTxCdeRvnuAcntID" value="<?php echo $accbTxCdeRvnuAcntID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Revenue and Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdeRvnuAcntID', 'accbTxCdeRvnuAcntNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div  class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">                            
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeTxExpAccNm" class="control-label col-md-4">Tax Expense/ Receivable:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="accbTxCdeTxExpAccNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Expense/Receivable Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdeTxExpAccNm; ?>" readonly="true"/>
                                        <input type="hidden" id="accbTxCdeTxExpAccID" value="<?php echo $accbTxCdeTxExpAccID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset and Expenditure Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdeTxExpAccID', 'accbTxCdeTxExpAccNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdePyblAcntNm" class="control-label col-md-4">Purchase Discount:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="accbTxCdePrchDscAccNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Purchase Discount Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdePrchDscAccNm; ?>" readonly="true"/>
                                        <input type="hidden" id="accbTxCdePrchDscAccID" value="<?php echo $accbTxCdePrchDscAccID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdePrchDscAccID', 'accbTxCdePrchDscAccNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeChrgExpAccNm" class="control-label col-md-4">Charges Expense:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="accbTxCdeChrgExpAccNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Expense Account" type = "text" min="0" placeholder="" value="<?php echo $accbTxCdeChrgExpAccNm; ?>" readonly="true"/>
                                        <input type="hidden" id="accbTxCdeChrgExpAccID" value="<?php echo $accbTxCdeChrgExpAccID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Expense Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTxCdeChrgExpAccID', 'accbTxCdeChrgExpAccNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeIsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                                <div  class="col-lg-6">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    if ($accbTxCdeIsEnbld == "1") {
                                        $chkdNo = "";
                                        $chkdYes = "checked=\"\"";
                                    }
                                    ?>
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <label class="radio-inline"><input type="radio" name="accbTxCdeIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                        <label class="radio-inline"><input type="radio" name="accbTxCdeIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                    <?php } else {
                                        ?>
                                        <span><?php echo ($accbTxCdeIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>      
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeIsParnt" class="control-label col-lg-6">Is Parent?:</label>
                                <div  class="col-lg-6">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    if ($accbTxCdeIsParnt == "1") {
                                        $chkdNo = "";
                                        $chkdYes = "checked=\"\"";
                                    }
                                    ?>
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <label class="radio-inline"><input type="radio" name="accbTxCdeIsParnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                        <label class="radio-inline"><input type="radio" name="accbTxCdeIsParnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                    <?php } else {
                                        ?>
                                        <span><?php echo ($accbTxCdeIsParnt == "1" ? "YES" : "NO"); ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeIsWthHldng" class="control-label col-lg-6">Is Witholding?:</label>
                                <div  class="col-lg-6">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    if ($accbTxCdeIsWthHldng == "1") {
                                        $chkdNo = "";
                                        $chkdYes = "checked=\"\"";
                                    }
                                    ?>
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <label class="radio-inline"><input type="radio" name="accbTxCdeIsWthHldng" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                        <label class="radio-inline"><input type="radio" name="accbTxCdeIsWthHldng" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                    <?php } else {
                                        ?>
                                        <span><?php echo ($accbTxCdeIsWthHldng == "1" ? "YES" : "NO"); ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>      
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeIsTxRcvrbl" class="control-label col-lg-6">Is Recoverable?:</label>
                                <div  class="col-lg-6">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    if ($accbTxCdeIsTxRcvrbl == "1") {
                                        $chkdNo = "";
                                        $chkdYes = "checked=\"\"";
                                    }
                                    ?>
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <label class="radio-inline"><input type="radio" name="accbTxCdeIsTxRcvrbl" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                        <label class="radio-inline"><input type="radio" name="accbTxCdeIsTxRcvrbl" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                    <?php } else {
                                        ?>
                                        <span><?php echo ($accbTxCdeIsTxRcvrbl == "1" ? "YES" : "NO"); ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div  class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;margin-bottom: 5px!important;">
                                <label for="accbTxCdeSQL" class="control-label col-md-2">SQL Formular:</label>
                                <div class="col-md-10">
                                    <div class="input-group"  style="width:100%;">
                                        <textarea class="form-control rqrdFld" rows="1" cols="20" id="accbTxCdeSQL" name="accbTxCdeSQL" style="text-align:left !important;"><?php echo $accbTxCdeSQL; ?></textarea>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbTxCdeSQL');" style="max-width:30px;width:30px;">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>  
                            <div class="form-group form-group-sm col-md-4" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeUnitPrc" class="control-label col-lg-6" style="margin-top:6px;">{:unit_price}</label>
                                <div  class="col-lg-6" style="">
                                    <input type="number" class="form-control" aria-label="..." id="accbTxCdeUnitPrc" name="accbTxCdeUnitPrc" value="1" style="width:100% !important;">
                                </div>
                            </div> 
                            <div class="form-group form-group-sm col-md-2" style="padding:0px 0px 0px 0px !important;">
                                <label for="accbTxCdeQty" class="control-label col-lg-4" style="margin-top:6px;">{:qty}</label>
                                <div  class="col-lg-8" style="">
                                    <input type="number" class="form-control" aria-label="..." id="accbTxCdeQty" name="accbTxCdeQty" value="1" style="width:100% !important;">
                                </div>
                            </div> 
                            <div class="form-group form-group-sm col-md-2" style="padding:0px 0px 0px 0px !important;">
                                <div  class="col-lg-12" style="">
                                    <button id="addNwAccbPyblsInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="testTxCdeSQLQuery()" data-toggle="tooltip" data-placement="bottom" title = "Test SQL">
                                        <img src="cmn_images/tick_64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Test Query
                                    </button>  
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-4" style="padding:0px 15px 0px 0px !important;">
                                <label id="accbTxCdeSQLTestRslts" style="width:100% !important;color:green;margin-left:1px;font-size: 13px;font-weight: bold;border:1px solid #ddd;border-radius: 5px;padding:5px;">
                                    &nbsp;TEST RESULTS
                                </label>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <?php
                        $nwRowHtml33 = "<tr id=\"accbTaxCodeAdtTblsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#accbTaxCodeAdtTblsTable tr').index(this));\">"
                                . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>  
                                                        <td class=\"lovtd\"  style=\"\">  
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbTaxCodeAdtTblsRow_WWW123WWW_CodeBhndID\" name=\"accbTaxCodeAdtTblsRow_WWW123WWW_CodeBhndID\" value=\"-1\"> 
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbTaxCodeAdtTblsRow_WWW123WWW_LineDesc\" name=\"accbTaxCodeAdtTblsRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getAccbTxCdeLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'accbTxCdeType', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTaxCodeAdtTblsRow_WWW123WWW_LineDesc', 'accbTaxCodeAdtTblsRow_WWW123WWW_CodeBhndID', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbTxCdeLne('accbTaxCodeAdtTblsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Child Code\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>";
                        $nwRowHtml33 .= "</tr>";
                        $nwRowHtml33 = urlencode($nwRowHtml33);
                        $nwRowHtml1 = $nwRowHtml33;
                        ?> 
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                <?php if ($canEdt) { ?>
                                    <button id="addNwAccbPyblsInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewAccbTaxCodeRows('accbTaxCodeAdtTblsTable', 0, '<?php echo $nwRowHtml1; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Child Code">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add New Child Code
                                    </button>                                 
                                <?php } ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneAccbTaxCodeForm(<?php echo $pkID ?>, 1);"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                            </div>                   
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">         
                        <table class="table table-striped table-bordered table-responsive" id="accbTaxCodeAdtTblsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                            <thead>
                                <tr>
                                    <th style="max-width:30px;width:30px;text-align: center;">No.</th>
                                    <th>Item Description</th>
                                    <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $curIdx = 0;
                                if (trim($slctdCodeIDs, ",") != "") {
                                    $variousRows = explode(",", trim($slctdCodeIDs, ","));
                                    for ($z = 0; $z < count($variousRows); $z++) {
                                        $lineDetID = (float) (cleanInputData1($variousRows[$z]));
                                        $trsctnLineType = getTaxNm($lineDetID);
                                        $cntr += 1;
                                        ?>
                                        <tr id="accbTaxCodeAdtTblsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <input type="hidden" id="accbTaxCodeAdtTblsRow<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $lineDetID; ?>"/>
                                                <input type="hidden" id="accbTaxCodeAdtTblsRow<?php echo $cntr; ?>_LineDesc"value="<?php echo $trsctnLineType; ?>">
                                                <span><?php echo $trsctnLineType; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbTxCdeLne('accbTaxCodeAdtTblsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Child Code">
                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 3) {
                //Test SQL
                header("content-type:application/json");
                $accbTxCdeSQL = isset($_POST['accbTxCdeSQL']) ? cleanInputData($_POST['accbTxCdeSQL']) : "";
                $accbTxCdeUnitPrc = isset($_POST['accbTxCdeUnitPrc']) ? (float) cleanInputData($_POST['accbTxCdeUnitPrc']) : 0.00;
                $accbTxCdeQty = isset($_POST['accbTxCdeQty']) ? (float) cleanInputData($_POST['accbTxCdeQty']) : 0.00;
                $errMsg = "";
                $CalcItemValue = 0.00;
                $boolRes = isTxCdeSQLValid($accbTxCdeSQL, $accbTxCdeUnitPrc, $accbTxCdeQty, $CalcItemValue);
                $arr_content['CalcItemValue'] = $CalcItemValue;
                if (!$boolRes) {
                    $errMsg .= "SQL is NOT valid!";
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-close\" aria-hidden=\"true\"></i>ERROR:" . $errMsg . "</span>";
                } else {
                    $errMsg = "SUCCESS";
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i>" . $errMsg . ": " . number_format($CalcItemValue, 5) . "</span>";
                }
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    