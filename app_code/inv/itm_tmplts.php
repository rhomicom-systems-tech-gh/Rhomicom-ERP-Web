<?php
$canAdd = test_prmssns($dfltPrvldgs[26], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[27], $mdlNm);
$canDel = $canEdt;
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 50;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Item */
                $canDelItm = test_prmssns($dfltPrvldgs[33], $mdlNm);
                $sbmtdItmTmpltID = isset($_POST['sbmtdItmTmpltID']) ? cleanInputData($_POST['sbmtdItmTmpltID']) : -1;
                $itemNm = isset($_POST['itemNm']) ? cleanInputData($_POST['itemNm']) : "";
                if ($canDelItm) {
                    echo deleteItmTmplt($sbmtdItmTmpltID, $itemNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Item Stores */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdStckID = isset($_POST['sbmtdStckID']) ? cleanInputData($_POST['sbmtdStckID']) : -1;
                $stockNm = isset($_POST['stockNm']) ? cleanInputData($_POST['stockNm']) : "";
                if ($canEdtItm) {
                    echo deleteItmTmpltstore($sbmtdStckID, $stockNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Item UoM */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdLineID = isset($_POST['sbmtdLineID']) ? cleanInputData($_POST['sbmtdLineID']) : -1;
                $uomNm = isset($_POST['uomNm']) ? cleanInputData($_POST['uomNm']) : "";
                if ($canEdtItm) {
                    echo deleteItmTmpltUom($sbmtdLineID, $uomNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Item Interactions */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdLineID = isset($_POST['sbmtdLineID']) ? cleanInputData($_POST['sbmtdLineID']) : -1;
                $drugNm = isset($_POST['drugNm']) ? cleanInputData($_POST['drugNm']) : "";
                if ($canEdtItm) {
                    echo deleteItmTmpltIntrctn($sbmtdLineID, $drugNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Item Template
                header("content-type:application/json");
                $sbmtdItmTmpltID = isset($_POST['sbmtdItmTmpltID']) ? (float) cleanInputData($_POST['sbmtdItmTmpltID']) : -1;
                $itmTmpltNm = isset($_POST['itmTmpltNm']) ? cleanInputData($_POST['itmTmpltNm']) : '';
                $itmTmpltDesc = isset($_POST['itmTmpltDesc']) ? cleanInputData($_POST['itmTmpltDesc']) : '';
                $invTmpltID = isset($_POST['invTmpltID']) ? (float) cleanInputData($_POST['invTmpltID']) : -1;
                $itmTmpltType = isset($_POST['itmTmpltType']) ? cleanInputData($_POST['itmTmpltType']) : '';
                $itmTmpltCtgryID = isset($_POST['itmTmpltCtgryID']) ? (float) cleanInputData($_POST['itmTmpltCtgryID']) : -1;
                $invTxCodeID = isset($_POST['invTxCodeID']) ? (float) cleanInputData($_POST['invTxCodeID']) : -1;
                $invBaseUomID = isset($_POST['invBaseUomID']) ? (float) cleanInputData($_POST['invBaseUomID']) : -1;
                $invDscntCodeID = isset($_POST['invDscntCodeID']) ? (float) cleanInputData($_POST['invDscntCodeID']) : -1;
                $invChrgCodeID = isset($_POST['invChrgCodeID']) ? (float) cleanInputData($_POST['invChrgCodeID']) : -1;
                $invMinItmQty = isset($_POST['invMinItmQty']) ? (float) cleanInputData($_POST['invMinItmQty']) : 0;
                $invMaxItmQty = isset($_POST['invMaxItmQty']) ? (float) cleanInputData($_POST['invMaxItmQty']) : 0;
                $invValCrncyID = isset($_POST['invValCrncyID']) ? (float) cleanInputData($_POST['invValCrncyID']) : -1;
                $invPriceLessTax = isset($_POST['invPriceLessTax']) ? (float) cleanInputData($_POST['invPriceLessTax']) : 0;
                $invSllngPrice = isset($_POST['invSllngPrice']) ? (float) cleanInputData($_POST['invSllngPrice']) : 0;
                $invNwPrftAmnt = isset($_POST['invNwPrftAmnt']) ? cleanInputData($_POST['invNwPrftAmnt']) : 0;
                $invNewSllngPrice = isset($_POST['invNewSllngPrice']) ? cleanInputData($_POST['invNewSllngPrice']) : 0;
                $invPrftMrgnPrcnt = isset($_POST['invPrftMrgnPrcnt']) ? cleanInputData($_POST['invPrftMrgnPrcnt']) : 0;
                $invPrftMrgnAmnt = isset($_POST['invPrftMrgnAmnt']) ? cleanInputData($_POST['invPrftMrgnAmnt']) : 0;
                $invAssetAcntID = isset($_POST['invAssetAcntID']) ? (float) cleanInputData($_POST['invAssetAcntID']) : -1;
                $invCogsAcntID = isset($_POST['invCogsAcntID']) ? (float) cleanInputData($_POST['invCogsAcntID']) : -1;
                $invSRvnuAcntID = isset($_POST['invSRvnuAcntID']) ? (float) cleanInputData($_POST['invSRvnuAcntID']) : -1;
                $invSRetrnAcntID = isset($_POST['invSRetrnAcntID']) ? (float) cleanInputData($_POST['invSRetrnAcntID']) : -1;
                $invPRetrnAcntID = isset($_POST['invPRetrnAcntID']) ? (float) cleanInputData($_POST['invPRetrnAcntID']) : -1;
                $invExpnsAcntID = isset($_POST['invExpnsAcntID']) ? (float) cleanInputData($_POST['invExpnsAcntID']) : -1;
                $itmTmpltOthrDesc = isset($_POST['itmTmpltOthrDesc']) ? cleanInputData($_POST['itmTmpltOthrDesc']) : '';
                $itmTmpltExtrInfo = isset($_POST['itmTmpltExtrInfo']) ? cleanInputData($_POST['itmTmpltExtrInfo']) : '';
                $itmTmpltGnrcNm = isset($_POST['itmTmpltGnrcNm']) ? cleanInputData($_POST['itmTmpltGnrcNm']) : '';
                $itmTmpltTradeNm = isset($_POST['itmTmpltTradeNm']) ? cleanInputData($_POST['itmTmpltTradeNm']) : '';
                $itmTmpltUslDsge = isset($_POST['itmTmpltUslDsge']) ? cleanInputData($_POST['itmTmpltUslDsge']) : '';
                $itmTmpltMaxDsge = isset($_POST['itmTmpltMaxDsge']) ? cleanInputData($_POST['itmTmpltMaxDsge']) : '';
                $itmTmpltCntrIndctns = isset($_POST['itmTmpltCntrIndctns']) ? cleanInputData($_POST['itmTmpltCntrIndctns']) : '';
                $itmTmpltFoodIntrctns = isset($_POST['itmTmpltFoodIntrctns']) ? cleanInputData($_POST['itmTmpltFoodIntrctns']) : '';

                $isItmEnbld = isset($_POST['isItmEnbld']) ? cleanInputData($_POST['isItmEnbld']) : 'NO';
                $isPlnngEnbld = isset($_POST['isPlnngEnbld']) ? cleanInputData($_POST['isPlnngEnbld']) : 'NO';
                $autoLoadInVMS = isset($_POST['autoLoadInVMS']) ? cleanInputData($_POST['autoLoadInVMS']) : 'NO';
                $oldItemID = getItmTmpltID($itmTmpltNm);
                $isItmEnbldVal = ($isItmEnbld == "NO") ? "0" : "1";
                $isPlnngEnbldVal = ($isPlnngEnbld == "NO") ? "0" : "1";
                $autoLoadInVMSVal = ($autoLoadInVMS == "NO") ? "0" : "1";
                $errMsg = "";
                if ($itmTmpltNm != "" && $itmTmpltDesc != "" && ($oldItemID <= 0 || $oldItemID == $sbmtdItmTmpltID)) {
                    if ($sbmtdItmTmpltID <= 0) {
                        createItmTmplt($itmTmpltNm, $itmTmpltDesc, $itmTmpltCtgryID, $orgID, $isItmEnbldVal, $invSllngPrice,
                                $invCogsAcntID, $invAssetAcntID, $invSRvnuAcntID, $invSRetrnAcntID, $invPRetrnAcntID,
                                $invExpnsAcntID, $invTxCodeID, $invDscntCodeID, $invChrgCodeID, $invMinItmQty, $invMaxItmQty,
                                $isPlnngEnbldVal, $itmTmpltType, $invBaseUomID, $invValCrncyID, $autoLoadInVMSVal);
                        $sbmtdItmTmpltID = getItmTmpltID($itmTmpltNm);
                    } else {
                        updateItmTmplt($sbmtdItmTmpltID, $itmTmpltNm, $itmTmpltDesc, $itmTmpltCtgryID, $orgID, $isItmEnbldVal,
                                $invSllngPrice, $invCogsAcntID, $invAssetAcntID, $invSRvnuAcntID, $invSRetrnAcntID,
                                $invPRetrnAcntID, $invExpnsAcntID, $invTxCodeID, $invDscntCodeID, $invChrgCodeID, $invMinItmQty,
                                $invMaxItmQty, $isPlnngEnbldVal, $itmTmpltType, $invBaseUomID, $invValCrncyID, $autoLoadInVMSVal);
                    }
                    //Save Item Stores
                    $afftctd = 0;
                    $slctdItmStores = isset($_POST['slctdItmStores']) ? cleanInputData($_POST['slctdItmStores']) : '';
                    if (trim($slctdItmStores, "|~") != "") {
                        $variousRows = explode("|", trim($slctdItmStores, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 6) {
                                $lnStockID = (float) (cleanInputData1($crntRow[0]));
                                $lnStoreID = (float) cleanInputData1($crntRow[1]);
                                $lnShelves = cleanInputData1($crntRow[2]);
                                $lnShelveIDs = cleanInputData1($crntRow[3]);
                                $lnStrtDte = cleanInputData1($crntRow[4]);
                                $lnEndDte = cleanInputData1($crntRow[5]);
                                if ($lnStrtDte != "") {
                                    $lnStrtDte = cnvrtDMYTmToYMDTm($lnStrtDte);
                                } else {
                                    $lnStrtDte = getDB_Date_time();
                                }
                                if ($lnEndDte != "") {
                                    $lnEndDte = cnvrtDMYTmToYMDTm($lnEndDte);
                                }
                                $oldStockID = getItmTmpltStockID($sbmtdItmTmpltID, $lnStoreID);
                                if ($oldStockID <= 0 && $lnStoreID > 0) {
                                    //Insert
                                    $afftctd += createItmTmpltStore($sbmtdItmTmpltID, $lnStoreID, $lnShelves, $orgID, $lnStrtDte,
                                            $lnEndDte, $lnShelveIDs);
                                } else if ($lnStockID > 0) {
                                    $afftctd += updateItmTmpltStore($oldStockID, $sbmtdItmTmpltID, $lnStoreID, $lnShelves, $orgID,
                                            $lnStrtDte, $lnEndDte, $lnShelveIDs);
                                }
                            }
                        }
                    }
                    //Save Item UOMs
                    $afftctd1 = 0;
                    $slctdItmUOMs = isset($_POST['slctdItmUOMs']) ? cleanInputData($_POST['slctdItmUOMs']) : '';
                    if (trim($slctdItmUOMs, "|~") != "") {
                        $variousRows = explode("|", trim($slctdItmUOMs, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 4) {
                                $lnLineID = (float) (cleanInputData1($crntRow[0]));
                                $lnUOMID = (float) cleanInputData1($crntRow[1]);
                                $lnCnvrsnFctr = (float) cleanInputData1($crntRow[2]);
                                $lnSortOrder = (float) cleanInputData1($crntRow[3]);
                                $oldLnLineID = getItmTmpltUomID($sbmtdItmTmpltID, $lnUOMID);
                                if ($oldLnLineID <= 0 && $lnUOMID > 0 && $lnUOMID != $invBaseUomID) {
                                    //Insert
                                    $afftctd1 += createItmTmpltUom($sbmtdItmTmpltID, $lnUOMID, $lnCnvrsnFctr, $lnSortOrder);
                                } else if ($oldLnLineID > 0 && $lnUOMID > 0 && $lnUOMID != $invBaseUomID) {
                                    $afftctd1 += updateItmTmpltUom($oldLnLineID, $sbmtdItmTmpltID, $lnUOMID, $lnCnvrsnFctr,
                                            $lnSortOrder);
                                }
                            }
                        }
                    }
                    $nwImgLoc = "";
                    $arr_content['percent'] = 100;
                    $arr_content['itemid'] = (float) $sbmtdItmTmpltID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Item Successfully Saved!<br/>" . $afftctd . " Item Store(s) Saved!<br/>" . $afftctd1 . " Item UoM(s) Saved!<br/>" . $nwImgLoc;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['itemid'] = (float) $sbmtdItmTmpltID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Item Type Templates</span>
				</li>
                               </ul>
                              </div>";
                //Stockable Item List
                $total = get_ItmTmpltsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_ItmTmplts($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                ?>
                <form id='allItmTmpltsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allItmTmpltsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllItmTmplts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allItmTmpltsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllItmTmplts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllItmTmplts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allItmTmpltsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Name", "Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allItmTmpltsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allItmTmpltsSortBy">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Value", "Last Created");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($sortBy == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllItmTmplts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllItmTmplts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>                   
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <?php if ($canAdd === true) { ?>                   
                                <button type="button" class="btn btn-default btn-sm" onclick="getOneItmTmpltsForm(-1, 'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Item Template
                                </button>
                            <?php } ?> 
                            <button type="button" class="btn btn-default btn-sm" onclick="">
                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                Export Item Templates
                            </button>                          
                            <?php if ($canAdd) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import Item Templates
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allItmTmpltsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Item Code/Name</th>
                                        <th>Type / Category / Description</th>
                                        <th>UOM</th>
                                        <th>CUR.</th>
                                        <th style="text-align:center;">Enabled?</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="allItmTmpltsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php
                                                echo str_replace(" (" . $row[1] . ")", "", $row[1] . " (" . $row[2] . ")");
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allItmTmpltsRow<?php echo $cntr; ?>_ItemTmpltID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[21] . " - " . $row[4] . " (" . $row[2] . ")"; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;color:black;font-weight:bold;"><?php echo $row[5]; ?></button>
                                            </td>
                                            <td class="lovtd"><?php echo $row[23]; ?></td>                                           
                                            <td class="lovtd" style="text-align:center;">
                                                <?php
                                                $isChkd = "";
                                                if ($row[16] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allItmTmpltsRow<?php echo $cntr; ?>_IsEnabled" name="allItmTmpltsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneItmTmpltsForm(<?php echo $row[0]; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDel === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delItmTmplts('allItmTmpltsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                    echo urlencode(encrypt1(($row[0] . "|inv.inv_itm_type_templates|item_type_id"),
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
                </form>
                <?php
            } else if ($vwtyp == 1) {
                //New Item Template Form
                $sbmtdItmTmpltID = isset($_POST['sbmtdItmTmpltID']) ? cleanInputData($_POST['sbmtdItmTmpltID']) : -1;

                $itmTmpltNm = "";
                $itmTmpltDesc = "";

                $invTmpltNm = "";
                $invTmpltID = -1;
                $itmTmpltType = "";
                $itmTmpltCtgry = "";
                $itmTmpltCtgryID = -1;
                $invBaseUom = "";
                $invBaseUomID = -1;

                $invTxCodeID = -1;
                $invTxCodeName = "";
                $invDscntCodeID = -1;
                $invDscntCodeName = "";
                $invChrgCodeID = -1;
                $invChrgCodeName = "";
                $isPlnngEnbld = "No";
                $autoLoadInVMS = "Yes";
                $isEnbld = "No";
                $invMinItmQty = 0;
                $invMaxItmQty = 0;

                $invValCrncyID = -1;
                $invValCrncyNm = "";

                $invAssetAcntID = -1;
                $invAssetAcntNm = "";
                $invCogsAcntID = -1;
                $invCogsAcntNm = "";
                $invSRvnuAcntID = -1;

                $invSRvnuAcntNm = "";
                $invSRetrnAcntID = -1;
                $invSRetrnAcntNm = "";
                $invPRetrnAcntID = -1;
                $invPRetrnAcntNm = "";
                $invExpnsAcntID = -1;
                $invExpnsAcntNm = "";
                $rsltAc = get_One_DfltAcnt($orgID);
                if ($rwAc = loc_db_fetch_array($rsltAc)) {
                    $invAssetAcntID = (int) $rwAc[1];
                    $invAssetAcntNm = getAccntNum($invAssetAcntID) . "." . getAccntName($invAssetAcntID);
                    $invCogsAcntID = (int) $rwAc[2];
                    $invCogsAcntNm = getAccntNum($invCogsAcntID) . "." . getAccntName($invCogsAcntID);
                    $invSRvnuAcntID = (int) $rwAc[5];

                    $invSRvnuAcntNm = getAccntNum($invSRvnuAcntID) . "." . getAccntName($invSRvnuAcntID);
                    $invSRetrnAcntID = (int) $rwAc[6];
                    $invSRetrnAcntNm = getAccntNum($invSRetrnAcntID) . "." . getAccntName($invSRetrnAcntID);
                    $invPRetrnAcntID = (int) $rwAc[4];
                    $invPRetrnAcntNm = getAccntNum($invPRetrnAcntID) . "." . getAccntName($invPRetrnAcntID);
                    $invExpnsAcntID = (int) $rwAc[3];
                    $invExpnsAcntNm = getAccntNum($invExpnsAcntID) . "." . getAccntName($invExpnsAcntID);
                }
                if ($sbmtdItmTmpltID > 0) {
                    $result = get_OneItmTmplts($sbmtdItmTmpltID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdItmTmpltID = (float) $row[0];
                        $itmTmpltNm = $row[1];
                        $itmTmpltDesc = $row[2];
                        $itmTmpltType = $row[31];
                        $itmTmpltCtgry = $row[8];
                        $itmTmpltCtgryID = (float) $row[7];
                        $invBaseUom = $row[10];
                        $invBaseUomID = (float) $row[9];

                        $invTxCodeID = (float) $row[22];
                        $invTxCodeName = $row[23];
                        $invDscntCodeID = (float) $row[24];
                        $invDscntCodeName = $row[25];
                        $invChrgCodeID = (float) $row[26];
                        $invChrgCodeName = $row[27];
                        $isPlnngEnbld = ($row[30] == "1") ? "Yes" : "No";
                        $autoLoadInVMS = ($row[34] == "1") ? "Yes" : "No";
                        $isEnbld = ($row[11] == "1") ? "Yes" : "No";
                        $invMinItmQty = (float) $row[28];
                        $invMaxItmQty = (float) $row[29];

                        $invValCrncyID = (float) $row[32];
                        $invValCrncyNm = $row[33];

                        $invAssetAcntID = (float) $row[5];
                        $invAssetAcntNm = $row[6];
                        $invCogsAcntID = (float) $row[3];
                        $invCogsAcntNm = $row[4];
                        $invSRvnuAcntID = (float) $row[13];

                        $invSRvnuAcntNm = $row[14];
                        $invSRetrnAcntID = (float) $row[15];
                        $invSRetrnAcntNm = $row[16];
                        $invPRetrnAcntID = (float) $row[17];
                        $invPRetrnAcntNm = $row[18];
                        $invExpnsAcntID = (float) $row[19];
                        $invExpnsAcntNm = $row[20];
                    }
                }
                ?>
                <form class="form-horizontal" id='itmTmpltStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-10" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="itmTmpltNm" class="control-label">Template Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?> 
                                                <input type="text" name="itmTmpltNm" id="itmTmpltNm" class="form-control rqrdFld" value="<?php echo $itmTmpltNm; ?>" style="width:100% !important;">
                                                <input type="hidden" name="sbmtdItmTmpltID" id="sbmtdItmTmpltID" class="form-control" value="<?php echo $sbmtdItmTmpltID; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $itmTmpltNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="itmTmpltDesc" class="control-label">Template Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdt === true) { ?>
                                                <textarea rows="5" name="itmTmpltDesc" id="itmTmpltDesc" class="form-control rqrdFld"><?php echo $itmTmpltDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $itmTmpltDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-md-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isItmEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdt === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isItmEnbld" id="isItmEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">                                           
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxitmtmplts" data-rhodata="" href="#itmTmpltsGnrl" id="itmTmpltsGnrltab" style="padding: 3px 10px !important;">General</a></li>
                                    <li><a data-toggle="tabajxitmtmplts" data-rhodata="" href="#itmTmpltsGl" id="itmTmpltsGltab" style="padding: 3px 10px !important;">GL Accounts</a></li>
                                    <li><a data-toggle="tabajxitmtmplts" data-rhodata="" href="#itmTmpltsStores" id="itmTmpltsStorestab" style="padding: 3px 10px !important;">Stores/WH</a></li>
                                    <li><a data-toggle="tabajxitmtmplts" data-rhodata="" href="#itmTmpltsUOM" id="itmTmpltsUOMtab" style="padding: 3px 10px !important;">UOMs</a></li>
                                </ul>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneItmTmpltDetTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="itmTmpltsGnrl" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="itmTmpltType" class="control-label">Item Type:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <select class="form-control rqrdFld" id="itmTmpltType" onchange="">                                                        
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "", "", "", "");
                                                                        $valuesArrys = array("Merchandise Inventory", "Non-Merchandise Inventory",
                                                                            "Fixed Assets", "Expense Item", "Services", "VaultItem-Cash",
                                                                            "VaultItem-NonCash");
                                                                        for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                            if ($itmTmpltType == $valuesArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <span><?php echo $itmTmpltType; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="itmTmpltCtgry" class="control-label">Category:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="itmTmpltCtgry" value="<?php echo $itmTmpltCtgry; ?>" readonly="">
                                                                        <input type="hidden" id="itmTmpltCtgryID" value="<?php echo $itmTmpltCtgryID; ?>">
                                                                        <label id="itmTmpltCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Categories', 'allOtherInputOrgID', '', '', 'radio', true, '', 'itmTmpltCtgryID', 'itmTmpltCtgry', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $itmTmpltCtgry; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invBaseUom" class="control-label">Base UOM:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>                                   
                                                                    <div class="input-group">
                                                                        <input type="text" name="invBaseUom" id="invBaseUom" class="form-control rqrdFld" value="<?php echo $invBaseUom; ?>" readonly="true">
                                                                        <input type="hidden" name="invBaseUomID" id="invBaseUomID" class="form-control" value="<?php echo $invBaseUomID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invBaseUomID', 'invBaseUom', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div> 
                                                                <?php } else { ?>
                                                                    <span><?php echo $invBaseUom; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invTxCodeName" class="control-label">Tax Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>                                   
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invTxCodeName" id="invTxCodeName" class="form-control" value="<?php echo $invTxCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invTxCodeID" id="invTxCodeID" class="form-control" value="<?php echo $invTxCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', '', '', '', 'radio', true, '', 'invTxCodeID', 'invTxCodeName', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invTxCodeName; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invDscntCodeName" class="control-label">Discount Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>                                   
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invDscntCodeName" id="invDscntCodeName" class="form-control" value="<?php echo $invDscntCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invDscntCodeID" id="invDscntCodeID" class="form-control" value="<?php echo $invDscntCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', '', '', '', 'radio', true, '', 'invDscntCodeID', 'invDscntCodeName', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invDscntCodeName; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invChrgCodeName" class="control-label">Charge Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>                                   
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invChrgCodeName" id="invChrgCodeName" class="form-control" value="<?php echo $invChrgCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invChrgCodeID" id="invChrgCodeID" class="form-control" value="<?php echo $invChrgCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', '', '', '', 'radio', true, '', 'invChrgCodeID', 'invChrgCodeName', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invChrgCodeName; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                &nbsp;
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                                    <label for="isPlnngEnbld" class="control-label">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        $isRdOnly = "disabled=\"true\"";
                                                                        if ($canEdt === true) {
                                                                            $isRdOnly = "";
                                                                        }
                                                                        if ($isPlnngEnbld == "Yes") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" name="isPlnngEnbld" id="isPlnngEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Is Planning Enabled?</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                                    <label for="autoLoadInVMS" class="control-label">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        $isRdOnly = "disabled=\"true\"";
                                                                        if ($canEdt === true) {
                                                                            $isRdOnly = "";
                                                                        }
                                                                        if ($autoLoadInVMS == "Yes") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" name="autoLoadInVMS" id="autoLoadInVMS" <?php echo $isChkd . " " . $isRdOnly; ?>>Auto-Load in VMS?</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invMinItmQty" class="control-label">Planning Qtys:</label>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>                                   
                                                                    <input type="number" name="invMinItmQty" id="invMinItmQty" class="form-control" value="<?php echo $invMinItmQty; ?>" placeholder="Min Qty.">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invMinItmQty; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>                                   
                                                                    <input type="number" name="invMaxItmQty" id="invMaxItmQty" class="form-control" value="<?php echo $invMaxItmQty; ?>" placeholder="Max Qty.">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invMaxItmQty; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invValCrncyNm" class="control-label">Value Currency:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdt === true) { ?>                                   
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invValCrncyNm" id="invValCrncyNm" class="form-control rqrdFld" value="<?php echo $invValCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invValCrncyID" id="invValCrncyID" class="form-control" value="<?php echo $invValCrncyID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'invValCrncyNm', '', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                }, 'invValCrncyID');"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invValCrncyNm; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>                                    
                                        <div id="itmTmpltsGl" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invAssetAcntNm" class="control-label">Inventory/Asset Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invAssetAcntNm" id="invAssetAcntNm" class="form-control rqrdFld" value="<?php echo $invAssetAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invAssetAcntID" id="invAssetAcntID" class="form-control" value="<?php echo $invAssetAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invAssetAcntID', 'invAssetAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invAssetAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invCogsAcntNm" class="control-label">Cost of Goods Sold:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invCogsAcntNm" id="invCogsAcntNm" class="form-control rqrdFld" value="<?php echo $invCogsAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invCogsAcntID" id="invCogsAcntID" class="form-control" value="<?php echo $invCogsAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Revenue Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invCogsAcntID', 'invCogsAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invCogsAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invSRvnuAcntNm" class="control-label">Sales Revenue:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invSRvnuAcntNm" id="invSRvnuAcntNm" class="form-control rqrdFld" value="<?php echo $invSRvnuAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invSRvnuAcntID" id="invSRvnuAcntID" class="form-control" value="<?php echo $invSRvnuAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Revenue Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invSRvnuAcntID', 'invSRvnuAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invSRvnuAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invSRetrnAcntNm" class="control-label">Sales Return:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invSRetrnAcntNm" id="invSRetrnAcntNm" class="form-control rqrdFld" value="<?php echo $invSRetrnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invSRetrnAcntID" id="invSRetrnAcntID" class="form-control" value="<?php echo $invSRetrnAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Revenue Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invSRetrnAcntID', 'invSRetrnAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invSRetrnAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invPRetrnAcntNm" class="control-label">Purchase Returns Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invPRetrnAcntNm" id="invPRetrnAcntNm" class="form-control rqrdFld" value="<?php echo $invPRetrnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invPRetrnAcntID" id="invPRetrnAcntID" class="form-control" value="<?php echo $invPRetrnAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Expense Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invPRetrnAcntID', 'invPRetrnAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invPRetrnAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invExpnsAcntNm" class="control-label">Expense Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invExpnsAcntNm" id="invExpnsAcntNm" class="form-control rqrdFld" value="<?php echo $invExpnsAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invExpnsAcntID" id="invExpnsAcntID" class="form-control" value="<?php echo $invExpnsAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Expense Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invExpnsAcntID', 'invExpnsAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invExpnsAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="itmTmpltsStores" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;"> 
                                            <?php
                                            $nwRowHtmlAB = "<tr id=\"oneItmStoresRow__WWW123WWW\">"
                                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                                    . "<td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StoreNm\" value=\"\" style=\"width:100%;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StoreID\" value=\"-1\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StockID\" value=\"-1\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Stores', '', '', '', 'radio', true, '', 'oneItmStoresRow_WWW123WWW_StoreID', 'oneItmStoresRow_WWW123WWW_StoreNm', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_ShlvNm\" value=\"\" style=\"width:100%;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_ShlvIDs\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Store Shelves', 'oneItmStoresRow_WWW123WWW_StoreID', '', '', 'check', true, '', 'oneItmStoresRow_WWW123WWW_ShlvIDs', 'oneItmStoresRow_WWW123WWW_ShlvNm', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\"> 
                                                        <div class=\"\" style=\"width:100% !important;\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneItmStoresRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>                                                                
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneItmStoresRow_WWW123WWW_EndDte\" value=\"\" readonly=\"true\" style=\"width:100%;\">
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>                                                                
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delItmTmpltstores('oneItmStoresRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item Store\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>";
                                            if ($canVwRcHstry === true) {
                                                $nwRowHtmlAB .= "<td class=\"lovtd\">&nbsp;</td></tr>";
                                            }
                                            $nwRowHtml = urlencode($nwRowHtmlAB);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($canEdt === true) { ?>
                                                        <button id="addNwStoreBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowAfta('oneItmStoresTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Item Store">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Store
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshItmBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneItmTmpltsForm(<?php echo $sbmtdItmTmpltID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Item Details">
                                                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Refresh
                                                    </button>
                                                </div>                                            
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneItmStoresTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:50px;width:50px;">No.</th>
                                                                <th >Store Name</th>
                                                                <th >Shelves</th>
                                                                <th >Start Date</th>
                                                                <th >End Date</th>
                                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                    ?>
                                                                    <th>...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneItmTmpltStores($sbmtdItmTmpltID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $storeName = $rwCage[1];
                                                                $shelves = $rwCage[2];
                                                                $storeStrtDte = $rwCage[3];
                                                                $storeEndDte = $rwCage[4];
                                                                ?>
                                                                <tr id="oneItmStoresRow_<?php echo $cntrUsr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdt === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StoreNm" value="<?php echo $storeName; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID" value="<?php echo $rwCage[5]; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StockID" value="<?php echo $rwCage[7]; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Stores', '', '', '', 'radio', true, '<?php echo $storeName; ?>', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeName; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdt === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvNm" value="<?php echo $shelves; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvIDs" value="<?php echo $rwCage[6]; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Store Shelves', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID', '', '', 'check', true, '<?php echo $shelves; ?>', 'oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvIDs', 'oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $shelves; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">                                                                            
                                                                        <?php if ($canEdt === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneItmStoresRow<?php echo $cntrUsr; ?>_StrtDte" value="<?php echo $storeStrtDte; ?>" readonly="true">
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>                                                                
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeStrtDte; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="">
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneItmStoresRow<?php echo $cntrUsr; ?>_EndDte" value="<?php echo $storeEndDte; ?>" readonly="true">
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>                                                                
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeEndDte; ?></span>
                                                                        <?php } ?>  
                                                                    </td>
                                                                    <?php if ($canEdt === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delItmTmpltstores('oneItmStoresRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item Store">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                        ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                            echo urlencode(encrypt1(($rwCage[7] . "|inv.inv_stock|stock_id"),
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
                                        </div>
                                        <div id="itmTmpltsUOM" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">                                             
                                            <?php
                                            $nwRowHtmlAB = "<tr id=\"oneItmUOMsRow__WWW123WWW\">"
                                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                                    . "<td class=\"lovtd\">
                                                            <div class=\"\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_UOMNm\" value=\"\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_UOMID\" value=\"\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_LineID\" value=\"\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneItmUOMsRow_WWW123WWW_UOMID', 'oneItmUOMsRow_WWW123WWW_UOMNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_CnvrsnFctr\" value=\"\">
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_SortOrdr\" value=\"\">
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delItmTmpltUoMs('oneItmUOMsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item UOM\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>";
                                            if ($canVwRcHstry === true) {
                                                $nwRowHtmlAB .= "<td class=\"lovtd\">&nbsp;</td></tr>";
                                            }
                                            $nwRowHtml = urlencode($nwRowHtmlAB);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($canEdt === true) { ?>
                                                        <button id="addNwUOMBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowAfta('oneItmUOMsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Item UOM">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add UOM
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshItmBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneItmTmpltsForm(<?php echo $sbmtdItmTmpltID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Item Details">
                                                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Refresh
                                                    </button>
                                                </div>                                            
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneItmUOMsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:50px;width:50px;">No.</th>
                                                                <th >UOM</th>
                                                                <th >Conversion Factor</th>
                                                                <th >Sort Order</th>
                                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                    ?>
                                                                    <th>...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneItmTmpltUOMs($sbmtdItmTmpltID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $itmUOM1 = $rwCage[1];
                                                                $itmUOMLineID = $rwCage[4];
                                                                $itmUOMID = $rwCage[5];
                                                                $uomCnvrsnFctr = $rwCage[2];
                                                                $uomSortOrdr = (float) $rwCage[3];
                                                                ?>
                                                                <tr id="oneItmUOMsRow_<?php echo $cntrUsr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMNm" value="<?php echo $itmUOM1; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMID" value="<?php echo $itmUOMID; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $itmUOMLineID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $itmUOMID; ?>', 'oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMID', 'oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $itmUOM1; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="">                                                                                
                                                                                <input type="number" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_CnvrsnFctr" value="<?php echo $uomCnvrsnFctr; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomCnvrsnFctr; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">                                                                            
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <div class="">                                                                                
                                                                                <input type="number" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_SortOrdr" value="<?php echo $uomSortOrdr; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomSortOrdr; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <?php if ($canEdt === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delItmTmpltUoMs('oneItmUOMsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item UOM">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                        ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                            echo urlencode(encrypt1(($rwCage[4] . "|inv.itm_uoms|itm_uom_id"),
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdt === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveItmTmpltsForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 2) {
                if (!$canAdd && !$canEdt) {
                    restricted();
                    exit();
                }
                $itemID = isset($_POST['sbmtdItemID']) ? cleanInputData($_POST['sbmtdItemID']) : 0;
                $varTtlQty = isset($_POST['varTtlQty']) ? cleanInputData($_POST['varTtlQty']) : 0;
                $sbmtdRwNum = isset($_POST['sbmtdRwNum']) ? cleanInputData($_POST['sbmtdRwNum']) : -1;
                $sbmtdTblRowID = isset($_POST['sbmtdTblRowID']) ? cleanInputData($_POST['sbmtdTblRowID']) : "";
                $rowIDAttrb = isset($_POST['rowIDAttrb']) ? cleanInputData($_POST['rowIDAttrb']) : "";
                $sbmtdCrncyNm = isset($_POST['sbmtdCrncyNm']) ? cleanInputData($_POST['sbmtdCrncyNm']) : -1;
                $sbmtdCrncyID = getPssblValID($sbmtdCrncyNm, getLovID("Currencies"));
                $ttlQty = $varTtlQty;
                $nwQty = 0;
                $rmndPrtVal = $ttlQty;
                $ttlPrce = 0;
                $fnccurid = $sbmtdCrncyID;
                $fnccurnm = $sbmtdCrncyNm;
                if ($sbmtdCrncyID <= 0) {
                    $fnccurid = getOrgFuncCurID($orgID);
                    $fnccurnm = getPssblValNm($fnccurid);
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneINVQtyBrkDwnTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UOM</th>
                                    <th style="text-align: right;">UOM QTY</th>
                                    <th style="text-align: right;">EQUIV BASE QTY</th>
                                    <th style="text-align: right;">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $crncyID = $fnccurid;
                                $crncyIDNm = $fnccurnm;
                                $whlPrtVal = 0;
                                $rngSum = 0;
                                $cnvrtdQty = 0;
                                $result = getUomBrkDwn($itemID);
                                while ($row = loc_db_fetch_array($result)) {
                                    $cntr++;
                                    $cnvsnFctr = $row[4];
                                    if ($rngSum == $ttlQty) {
                                        $cnvrtdQty = 0;
                                        $whlPrtVal = 0;
                                    } else {
                                        if ($rmndPrtVal >= $cnvsnFctr) {
                                            $whlPrt = (int) ($rmndPrtVal / $cnvsnFctr);
                                            $rmndPrt = $rmndPrtVal % $cnvsnFctr;
                                            if ($whlPrt > 0) {
                                                $whlPrtVal = $whlPrt;
                                                $cnvrtdQty = $whlPrtVal * $cnvsnFctr;
                                            }
                                            if ($rmndPrt > 0) {
                                                $rmndPrtVal = $rmndPrt;
                                            }
                                        } else {
                                            $cnvrtdQty = 0;
                                            $whlPrtVal = 0;
                                        }
                                        $rngSum = $rngSum + $cnvrtdQty;
                                    }
                                    $ttlPrce += $whlPrtVal * ($row[6]);
                                    $nwQty += $cnvrtdQty;
                                    ?>
                                    <tr id="oneINVQtyBrkRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <span><?php echo $row[1] ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_ItmUomID" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_UntVal" value="<?php echo $row[6]; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_CnvFctr" value="<?php echo $row[4]; ?>" style="width:100% !important;">                                                                                                
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbQty" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_BaseQty" name="oneINVQtyBrkRow<?php echo $cntr; ?>_BaseQty" value="<?php
                                            echo number_format($whlPrtVal, 0);
                                            ?>"  onchange="calcUomBrkdwnRowVal('oneINVQtyBrkRow_<?php echo $cntr; ?>');" onkeypress="invTrnsUomFormKeyPress(event, 'oneINVQtyBrkRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;">   
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbEqQty" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_EquivQty" name="oneINVQtyBrkRow<?php echo $cntr; ?>_EquivQty" value="<?php
                                            echo number_format($cnvrtdQty, 0);
                                            ?>" style="width:100% !important;text-align: right;" readonly="true"> 
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbTtl" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_TtlVal" name="oneINVQtyBrkRow<?php echo $cntr; ?>_TtlVal" value="<?php
                                            echo number_format($whlPrtVal * $row[6], 2);
                                            ?>" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>                                                            
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>TOTALS:</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdQtyTtlBtn\">" . number_format($nwQty, 0,
                                                '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                    </th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdUmValsTtlBtn\">" . number_format($ttlPrce,
                                                2, '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="float:right;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="applyNewINVQtyVal(<?php echo $sbmtdRwNum; ?>, 'myFormsModalx', '<?php echo $rowIDAttrb; ?>');">Apply Changes</button>
                        </div>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 3) {
                $itemID = isset($_POST['sbmtdItemID']) ? cleanInputData($_POST['sbmtdItemID']) : 0;
                $varTtlQty = isset($_POST['varTtlQty']) ? cleanInputData($_POST['varTtlQty']) : 0;
                $sbmtdCrncyNm = isset($_POST['sbmtdCrncyNm']) ? cleanInputData($_POST['sbmtdCrncyNm']) : -1;
                $sbmtdCrncyID = getPssblValID($sbmtdCrncyNm, getLovID("Currencies"));
                $ttlQty = $varTtlQty;
                $nwQty = 0;
                $rmndPrtVal = $ttlQty;
                $ttlPrce = 0;
                $fnccurid = $sbmtdCrncyID;
                $fnccurnm = $sbmtdCrncyNm;
                if ($sbmtdCrncyID <= 0) {
                    $fnccurid = getOrgFuncCurID($orgID);
                    $fnccurnm = getPssblValNm($fnccurid);
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneINVQtyBrkDwnTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UOM</th>
                                    <th style="text-align: right;">UOM QTY</th>
                                    <th style="text-align: right;">EQUIV BASE QTY</th>
                                    <th style="text-align: right;">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $crncyID = $fnccurid;
                                $crncyIDNm = $fnccurnm;
                                $whlPrtVal = 0;
                                $rngSum = 0;
                                $cnvrtdQty = 0;
                                $result = getUomBrkDwn($itemID);
                                while ($row = loc_db_fetch_array($result)) {
                                    $cntr++;
                                    $cnvsnFctr = $row[4];
                                    if ($rngSum == $ttlQty) {
                                        $cnvrtdQty = 0;
                                        $whlPrtVal = 0;
                                    } else {
                                        if ($rmndPrtVal >= $cnvsnFctr) {
                                            $whlPrt = (int) ($rmndPrtVal / $cnvsnFctr);
                                            $rmndPrt = $rmndPrtVal % $cnvsnFctr;
                                            if ($whlPrt > 0) {
                                                $whlPrtVal = $whlPrt;
                                                $cnvrtdQty = $whlPrtVal * $cnvsnFctr;
                                            }
                                            if ($rmndPrt > 0) {
                                                $rmndPrtVal = $rmndPrt;
                                            }
                                        } else {
                                            $cnvrtdQty = 0;
                                            $whlPrtVal = 0;
                                        }
                                        $rngSum = $rngSum + $cnvrtdQty;
                                    }
                                    $ttlPrce += $whlPrtVal * ($row[6]);
                                    $nwQty += $cnvrtdQty;
                                    ?>
                                    <tr id="oneINVQtyBrkRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <span><?php echo $row[1] ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_ItmUomID" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_UntVal" value="<?php echo $row[6]; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_CnvFctr" value="<?php echo $row[4]; ?>" style="width:100% !important;">                                                                                                
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($whlPrtVal, 0); ?></span> 
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($cnvrtdQty, 0); ?></span>
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($whlPrtVal * $row[6], 2); ?></span>                                                  
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>                                                            
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>TOTALS:</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdQtyTtlBtn\">" . number_format($nwQty, 0,
                                                '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                    </th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdUmValsTtlBtn\">" . number_format($ttlPrce,
                                                2, '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="float:right;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}
?>