<?php
$canAdd = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[16], $mdlNm);
$canDel = $canEdt;
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Store */
                $canDel = test_prmssns($dfltPrvldgs[27], $mdlNm);
                $sbmtdStoreWhsID = isset($_POST['sbmtdStoreWhsID']) ? cleanInputData($_POST['sbmtdStoreWhsID']) : -1;
                $storewhsNm = isset($_POST['storewhsNm']) ? cleanInputData($_POST['storewhsNm']) : "";
                if ($canDel) {
                    echo deleteStore($sbmtdStoreWhsID, $storewhsNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Store User */
                //var_dump($_POST);
                $canEdt = test_prmssns($dfltPrvldgs[26], $mdlNm);
                $lineID = isset($_POST['lineID']) ? cleanInputData($_POST['lineID']) : -1;
                $usrNm = isset($_POST['usrNm']) ? cleanInputData($_POST['usrNm']) : "";
                if ($canEdt) {
                    echo deleteStoreUser($lineID, $usrNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Cage/Till */
                $canDelCage = test_prmssns($dfltPrvldgs[30], $mdlNm);
                $lineID = isset($_POST['lineID']) ? cleanInputData($_POST['lineID']) : -1;
                $cageNm = isset($_POST['cageNm']) ? cleanInputData($_POST['cageNm']) : "";
                if ($canDelCage) {
                    echo deleteCageTill($lineID, $cageNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                header("content-type:application/json");
                $sbmtdStoreWhsID = isset($_POST['sbmtdStoreWhsID']) ? (float) cleanInputData($_POST['sbmtdStoreWhsID']) : -1;
                $storeNm = isset($_POST['storeNm']) ? cleanInputData($_POST['storeNm']) : '';
                $storeDesc = isset($_POST['storeDesc']) ? cleanInputData($_POST['storeDesc']) : '';
                $storeAddress = isset($_POST['storeAddress']) ? cleanInputData($_POST['storeAddress']) : '';
                $lnkdSiteID = isset($_POST['lnkdSiteID']) ? (float) cleanInputData($_POST['lnkdSiteID']) : -1;
                $lnkdGLAccountID = isset($_POST['lnkdGLAccountID']) ? (float) cleanInputData($_POST['lnkdGLAccountID']) : -1;
                $storewhsMngrsPrsnID = isset($_POST['storewhsMngrsPrsnID']) ? (float) cleanInputData($_POST['storewhsMngrsPrsnID'])
                            : -1;
                $grpType = isset($_POST['grpType']) ? cleanInputData($_POST['grpType']) : '';
                $allwdGroupNm = isset($_POST['allwdGroupNm']) ? cleanInputData($_POST['allwdGroupNm']) : '';
                $allwdGroupID = isset($_POST['allwdGroupID']) ? cleanInputData($_POST['allwdGroupID']) : '';
                $allwdGrpVal = $allwdGroupID;
                if ($grpType == "Person Types") {
                    $grpTypLovID = getLovID("Person Types");
                    $allwdGrpVal = getPssblValID($allwdGroupID, $grpTypLovID);
                }
                if (($grpType == "Everyone" && floatval($allwdGrpVal) >= 0) || ($grpType != "Everyone" && floatval($allwdGrpVal) <= 0)) {
                    $arr_content['percent'] = 100;
                    $arr_content['storewhsid'] = $sbmtdStoreWhsID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Invalid Allowed Group Type and Name!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $isStoreWhsEnbld = isset($_POST['isStoreWhsEnbld']) ? cleanInputData($_POST['isStoreWhsEnbld']) : 'NO';
                $isSalesAllwd = isset($_POST['isSalesAllwd']) ? cleanInputData($_POST['isSalesAllwd']) : 'NO';
                $oldStoreID = getStoreID($storeNm);
                $isStoreWhsEnbldVal = ($isStoreWhsEnbld == "NO") ? "0" : "1";
                $isSalesAllwdVal = ($isSalesAllwd == "NO") ? "0" : "1";
                $errMsg = "";
                if ($storeNm != "" && $storeDesc != "" && ($oldStoreID <= 0 || $oldStoreID == $sbmtdStoreWhsID)) {
                    if ($sbmtdStoreWhsID <= 0) {
                        createStore($storeNm, $storeDesc, $storeAddress, $isSalesAllwdVal, $storewhsMngrsPrsnID, $orgID,
                                $isStoreWhsEnbldVal, $lnkdGLAccountID, $lnkdSiteID, $grpType, $allwdGrpVal);
                        $sbmtdStoreWhsID = getStoreID($storeNm);
                    } else {
                        updateStore($sbmtdStoreWhsID, $storeNm, $storeDesc, $storeAddress, $isSalesAllwdVal, $storewhsMngrsPrsnID,
                                $orgID, $isStoreWhsEnbldVal, $lnkdGLAccountID, $lnkdSiteID, $grpType, $allwdGrpVal);
                    }
                    //Save Store Users
                    $afftctd = 0;
                    $slctdUsers = isset($_POST['slctdUsers']) ? cleanInputData($_POST['slctdUsers']) : '';
                    if (trim($slctdUsers, "|~") != "") {
                        $variousRows = explode("|", trim($slctdUsers, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 4) {
                                $storewhsUsrLineID = (float) (cleanInputData1($crntRow[0]));
                                $storewhsUsrID = (float) cleanInputData1($crntRow[1]);
                                $usrStartDte = cleanInputData1($crntRow[2]);
                                $usrEndDte = cleanInputData1($crntRow[3]);
                                //var_dump($crntRow);
                                $oldStoreWhsUsrLineID = getStoreUsrLineID($storewhsUsrID, $sbmtdStoreWhsID);
                                if ($oldStoreWhsUsrLineID <= 0 && $storewhsUsrLineID <= 0) {
                                    //Insert
                                    $afftctd += createStoreUser($storewhsUsrID, $sbmtdStoreWhsID, $usrStartDte, $usrEndDte);
                                } else if ($storewhsUsrLineID > 0) {
                                    $afftctd += updateStoreUser($storewhsUsrLineID, $storewhsUsrID, $sbmtdStoreWhsID,
                                            $usrStartDte, $usrEndDte);
                                }
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['storewhsid'] = (float) $sbmtdStoreWhsID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Store Successfully Saved!<br/>" . $afftctd . " Store User(s) Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['storewhsid'] = (float) $sbmtdStoreWhsID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                header("content-type:application/json");
                $cageLineID = isset($_POST['cageLineID']) ? (float) cleanInputData($_POST['cageLineID']) : -1;
                $cageShelfID = isset($_POST['cageShelfID']) ? (float) cleanInputData($_POST['cageShelfID']) : -1;
                $cageShelfNm = isset($_POST['cageShelfNm']) ? cleanInputData($_POST['cageShelfNm']) : '';
                $cageDesc = isset($_POST['cageDesc']) ? cleanInputData($_POST['cageDesc']) : '';
                $cageStoreID = isset($_POST['cageStoreID']) ? (float) cleanInputData($_POST['cageStoreID']) : -1;
                $cageOwnersCstmrID = isset($_POST['cageOwnersCstmrID']) ? (float) cleanInputData($_POST['cageOwnersCstmrID']) : -1;
                $lnkdGLAccountID = isset($_POST['lnkdGLAccountID']) ? (float) cleanInputData($_POST['lnkdGLAccountID']) : -1;
                $cageMngrsPrsnID = isset($_POST['cageMngrsPrsnID']) ? (float) cleanInputData($_POST['cageMngrsPrsnID']) : -1;
                $mngrsWithdrawlLmt = isset($_POST['mngrsWithdrawlLmt']) ? (float) cleanInputData($_POST['mngrsWithdrawlLmt']) : 0;
                $mngrsDepositLmt = isset($_POST['mngrsDepositLmt']) ? (float) cleanInputData($_POST['mngrsDepositLmt']) : 0;
                $grpType = isset($_POST['grpType']) ? cleanInputData($_POST['grpType']) : '';
                $allwdGroupNm = isset($_POST['allwdGroupNm']) ? cleanInputData($_POST['allwdGroupNm']) : '';
                $allwdGroupID = isset($_POST['allwdGroupID']) ? cleanInputData($_POST['allwdGroupID']) : '';
                $allwdGrpVal = $allwdGroupID;
                if ($grpType == "Person Types") {
                    $grpTypLovID = getLovID("Person Types");
                    $allwdGrpVal = getPssblValID($allwdGroupID, $grpTypLovID);
                }
                if (($grpType == "Everyone" && floatval($allwdGrpVal) >= 0) || ($grpType != "Everyone" && floatval($allwdGrpVal) <= 0)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Invalid Allowed Group Type and Name!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $dfltItemType = isset($_POST['dfltItemType']) ? cleanInputData($_POST['dfltItemType']) : '';
                $dfltItemState = isset($_POST['dfltItemState']) ? cleanInputData($_POST['dfltItemState']) : '';
                $isCageEnbld = isset($_POST['isCageEnbld']) ? cleanInputData($_POST['isCageEnbld']) : 'NO';
                $oldCageLineID = getCageTillID($cageShelfNm, $cageStoreID);
                $shlfLovID = getLovID("Shelves");
                $oldShelfID = getPssblValID($cageShelfNm, $shlfLovID);
                if ($oldShelfID <= 0) {
                    createPssblValsForLov1($shlfLovID, $cageShelfNm, $cageDesc, "1", "," . $orgID . ",");
                    $cageShelfID = getPssblValID($cageShelfNm, $shlfLovID);
                }
                $isCageEnbldVal = ($isCageEnbld == "NO") ? "0" : "1";
                $errMsg = "";
                if ($cageShelfNm != "" && $cageDesc != "" && ($oldCageLineID <= 0 || $oldCageLineID == $cageLineID)) {
                    if ($cageLineID <= 0) {
                        createCageTill($orgID, $cageShelfID, $cageStoreID, $cageShelfNm, $cageDesc, $cageOwnersCstmrID, $grpType,
                                $allwdGrpVal, $lnkdGLAccountID, $cageMngrsPrsnID, $dfltItemState, $mngrsWithdrawlLmt,
                                $mngrsDepositLmt, $dfltItemType, $isCageEnbldVal);
                        $cageLineID = getCageTillID($cageShelfNm, $cageStoreID);
                    } else {
                        updateCageTill($cageLineID, $cageShelfID, $cageStoreID, $cageShelfNm, $cageDesc, $cageOwnersCstmrID,
                                $grpType, $allwdGrpVal, $lnkdGLAccountID, $cageMngrsPrsnID, $dfltItemState, $mngrsWithdrawlLmt,
                                $mngrsDepositLmt, $dfltItemType, $isCageEnbldVal);
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Vault Cage Successfully Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Stores/Warehouses</span>
				</li>
                               </ul>
                              </div>";

                //Stores
                $total = get_StoresWhsTtl(-1, -1, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_StoresWhs(-1, -1, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                $prm = get_CurPlcy_Mx_Fld_lgns();
                ?>
                <form id='allStoresWhsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAdd === true) {
                            ?> 
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneStoresWhsForm(-1);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Store
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allStoresWhsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllStoresWhs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allStoresWhsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllStoresWhs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllStoresWhs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allStoresWhsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Store Name", "Store Description", "Site Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allStoresWhsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllStoresWhs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllStoresWhs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allStoresWhsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Store Name / Description</th>
                                        <th>Managed By</th>
                                        <th style="text-align:right;">CUR.</th>
                                        <th style="text-align:right;">Total Store Balance</th>
                                        <th style="text-align:right;">Posted Account Balance</th>
                                        <th>Store Account</th>
                                        <th>Branch / Agency</th>
                                        <th style="text-align: center;">Enabled?</th>
                                        <th>&nbsp;</th>
                                        <?php if ($canDel === true) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
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
                                        <tr id="allStoresWhsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td> 
                                            <td class="lovtd">
                                                <?php
                                                echo str_replace(" (" . $row[1] . ")", "", $row[1] . " (" . $row[2] . ")");
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allStoresWhsRow<?php echo $cntr; ?>_StoreWhsID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="allStoresWhsRow<?php echo $cntr; ?>_SiteID" value="<?php echo $row[12]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;"><?php echo $row[8]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                echo number_format((float) $row[11], 2);
                                                ?>
                                            </td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                echo number_format((float) $row[10], 2);
                                                ?>
                                            </td>
                                            <td class="lovtd"><?php echo $row[7]; ?></td>
                                            <td class="lovtd"><?php echo $row[13]; ?></td>
                                            <td class="lovtd" style="text-align: center;">
                                                <?php
                                                $isChkd = "";
                                                if ($row[3] == "Yes") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allStoresWhsRow<?php echo $cntr; ?>_IsEnabled" name="allCstmrsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneStoresWhsForm(<?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDel === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delStoresWhs('allStoresWhsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Store">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                    echo urlencode(encrypt1(($row[0] . "|inv.inv_itm_subinventories|subinv_id"),
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
                //New Store Form
                $canAddCage = test_prmssns($dfltPrvldgs[20], $mdlNm);
                $canEdtCage = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $canDelCage = $canEdtCage;
                $sbmtdStoreWhsID = isset($_POST['sbmtdStoreWhsID']) ? (float) cleanInputData($_POST['sbmtdStoreWhsID']) : -1;
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? (float) cleanInputData($_POST['sbmtdSiteID']) : -1;
                $storeNm = "";
                $storeDesc = "";
                $storeAddress = "";
                $lnkdSiteID = -1;
                $lnkdSiteNm = "";
                $lnkdGLAccountNm = "";
                $lnkdGLAccountID = -1;

                $grpType = "";
                $allwdGroupName = "";
                $allwdGroupID = -1;

                $storewhsMngrsPrsnID = -1;
                $storewhsMngrsName = "";
                $isSalesAllwd = "No";
                $isEnbld = "No";
                if ($sbmtdStoreWhsID > 0) {
                    $result = get_OneStoreDet($sbmtdStoreWhsID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdStoreWhsID = (float) $row[0];
                        $storeNm = $row[1];
                        $storeDesc = $row[2];
                        $storeAddress = $row[3];
                        $lnkdSiteID = (float) $row[4];
                        $lnkdSiteNm = $row[5];
                        $lnkdGLAccountNm = $row[7];
                        $lnkdGLAccountID = (float) $row[6];
                        $grpType = $row[9];
                        $allwdGroupName = $row[8];
                        $allwdGroupID = (float) $row[10];

                        $storewhsMngrsPrsnID = (float) $row[11];
                        $storewhsMngrsName = $row[12];
                        $isSalesAllwd = $row[13];
                        $isEnbld = $row[14];
                    }
                } else if ($sbmtdSiteID > 0) {
                    $lnkdSiteID = $sbmtdSiteID;
                    $lnkdSiteNm = getGnrlRecNm("org.org_sites_locations", "location_id",
                            "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $sbmtdSiteID);
                }
                ?>
                <form class="form-horizontal" id='storeStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="storeNm" class="control-label">Store Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?> 
                                                <input type="text" name="storeNm" id="storeNm" class="form-control rqrdFld" value="<?php echo $storeNm; ?>" style="width:100% !important;">
                                                <input type="hidden" name="sbmtdStoreWhsID" id="sbmtdStoreWhsID" class="form-control" value="<?php echo $sbmtdStoreWhsID; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $storeNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="storeDesc" class="control-label">Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdt === true) { ?>
                                                <textarea rows="3" name="storeDesc" id="storeDesc" class="form-control rqrdFld"><?php echo $storeDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $storeDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="storeAddress" class="control-label">Address:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdt === true) { ?>
                                                <textarea rows="3" name="storeAddress" id="storeAddress" class="form-control"><?php echo $storeAddress; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $storeAddress; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="lnkdSiteNm" class="control-label">Site Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="lnkdSiteNm" id="lnkdSiteNm" class="form-control rqrdFld" value="<?php echo $lnkdSiteNm; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="lnkdSiteID" id="lnkdSiteID" class="form-control" value="<?php echo $lnkdSiteID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', '', '', '', 'radio', true, '', 'lnkdSiteID', 'lnkdSiteNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $lnkdSiteNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-6" style="padding: 1px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="lnkdGLAccountNm" class="control-label">Linked GL Account:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="lnkdGLAccountNm" id="lnkdGLAccountNm" class="form-control" value="<?php echo $lnkdGLAccountNm; ?>" readonly="true">
                                                    <input type="hidden" name="lnkdGLAccountID" id="lnkdGLAccountID" class="form-control" value="<?php echo $lnkdGLAccountID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'lnkdGLAccountID', 'lnkdGLAccountNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $lnkdGLAccountNm; ?></span>
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
                                                <label for="isStoreWhsEnbld" class="control-label">
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
                                                    <input type="checkbox" name="isStoreWhsEnbld" id="isStoreWhsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">                                           
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isSalesAllwd" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdt === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isSalesAllwd == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isSalesAllwd" id="isSalesAllwd" <?php echo $isChkd . " " . $isRdOnly; ?>>Sales Allowed?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="grpType" class="control-label">Allowed Group Type:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <select class="form-control" id="grpType" onchange="grpTypMcfChangeV();">                                                        
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                                    $valuesArrys = array("Everyone", "Divisions/Groups",
                                                        "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($grpType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $grpType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="allwdGroupNm" class="control-label">Allowed Group Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="allwdGroupName" value="<?php echo $allwdGroupName; ?>" readonly="">
                                                    <input type="hidden" id="allwdGroupID" value="<?php echo $allwdGroupID; ?>">
                                                    <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'allwdGroupID', 'allwdGroupName', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $allwdGroupName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="storewhsMngrsName" class="control-label">Store Manager's Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="storewhsMngrsName" id="storewhsMngrsName" class="form-control" value="<?php echo $storewhsMngrsName; ?>" readonly="true">
                                                    <input type="hidden" name="storewhsMngrsPrsnID" id="storewhsMngrsPrsnID" class="form-control" value="<?php echo $storewhsMngrsPrsnID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'storewhsMngrsPrsnID', 'storewhsMngrsName', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div> 
                                            <?php } else { ?>
                                                <span><?php echo $storewhsMngrsName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>
                        <?php if ($sbmtdStoreWhsID > 0) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                        <li class="active"><a data-toggle="tabajxstores" data-rhodata="" href="#storeCages" id="storeCagestab" style="padding: 3px 10px !important;">Shelves/Cages</a></li>
                                        <li><a data-toggle="tabajxstores" data-rhodata="" href="#storeUsers" id="storeUserstab" style="padding: 3px 10px !important;">Manage Users</a></li>
                                    </ul>
                                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneStoreDetTblSctn"> 
                                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                            <div id="storeCages" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php
                                                        if ($canAddCage === true) {
                                                            ?>
                                                            <button id="addNwStoreBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneINVCgFormV(-1, 'Stores', <?php echo $sbmtdStoreWhsID; ?>, <?php echo $lnkdSiteID; ?>);" data-toggle="tooltip" data-placement="bottom" title = "New Store Cage/Shelf">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Add Store Cage/Shelf
                                                            </button>
                                                        <?php } ?>
                                                        <button id="refreshStoreWhsBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneStoresWhsForm(<?php echo $sbmtdStoreWhsID; ?>, 'ReloadDialog', 'FROMBRNCH', <?php echo $lnkdSiteID; ?>);" data-toggle="tooltip" data-placement="bottom" title = "Reload Store Cage/Shelf">
                                                            <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Refresh
                                                        </button>
                                                    </div>                                            
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="oneStoreCagesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:50px;width:50px;">No.</th>
                                                                    <th>Name</th>
                                                                    <th>Description</th>
                                                                    <th style="max-width:75px;width:75px;">Enabled?</th>
                                                                    <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                    <?php
                                                                    if ($canDelCage === true) {
                                                                        ?>
                                                                        <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                        ?>
                                                                        <th style="max-width:30px;width:30px;">...</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $rslt = get_OneStoreCages($sbmtdStoreWhsID);
                                                                $cntrCg = 0;
                                                                while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                    $cntrCg++;
                                                                    $cgLineID = (float) $rwCage[2];
                                                                    $cgName = $rwCage[15];
                                                                    $cgDesc = $rwCage[16];
                                                                    $cageEnbld = $rwCage[7];
                                                                    $clientMgnrNm = -1;
                                                                    ?>
                                                                    <tr id="oneStoreCagesRow_<?php echo $cntrCg; ?>">                                    
                                                                        <td class="lovtd">
                                                                            <input type="hidden" id="oneStoreCagesRow<?php echo $cntrCg; ?>_LineID" value="<?php echo $cgLineID; ?>"/>
                                                                            <span><?php echo ($rwCage[0]); ?></span>
                                                                        </td>
                                                                        <td class="lovtd"><span><?php echo $cgName; ?></span></td>
                                                                        <td class="lovtd"><span><?php echo $cgDesc; ?></span></td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <?php
                                                                            $isChkd = "";
                                                                            if ($cageEnbld == "1") {
                                                                                $isChkd = "checked=\"true\"";
                                                                            }
                                                                            ?>
                                                                            <div class="form-group form-group-sm">
                                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" class="form-check-input" id="oneStoreCagesRow<?php echo $cntrCg; ?>_IsEnabled" name="oneStoreCagesRow<?php echo $cntrCg; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneINVCgFormV(<?php echo $rwCage[2]; ?>, 'Stores');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <?php
                                                                        if ($canDelCage === true) {
                                                                            ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delINVCgV('oneStoreCagesRow_<?php echo $cntrCg; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Cage">
                                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php
                                                                        if ($canVwRcHstry === true) {
                                                                            ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                echo urlencode(encrypt1(($rwCage[2] . "|inv.inv_shelf|line_id"),
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
                                            <div id="storeUsers" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                                <?php
                                                $nwRowHtml = urlencode("<tr id=\"oneStoreUsersRow__WWW123WWW\">"
                                                        . "<td class=\"lovtd\"><span>New</span></td>"
                                                        . "<td class=\"lovtd\">
                                                                            <div class=\"form-group form-group-sm col-md-12\">
                                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneStoreUsersRow_WWW123WWW_UsrNm\" value=\"\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneStoreUsersRow_WWW123WWW_UsrID\" value=\"-1\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneStoreUsersRow_WWW123WWW_LineID\" value=\"-1\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '-1', 'oneStoreUsersRow_WWW123WWW_UsrID', 'oneStoreUsersRow_WWW123WWW_UsrNm', 'clear', 1, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\"> 
                                                                            <div class=\"form-group form-group-sm col-md-12\">
                                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneStoreUsersRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"true\">
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                </div>                                                                
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <div class=\"form-group form-group-sm col-md-12\">
                                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneStoreUsersRow_WWW123WWW_EndDte\" value=\"\" readonly=\"true\">
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                </div>                                                                
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delINVStoreWhsUsrs('oneStoreUsersRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete User\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>");
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php if ($canEdt === true) { ?>
                                                            <button id="addNwStoreBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowAfta('oneStoreUsersTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Store User">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Add Store User
                                                            </button>
                                                        <?php } ?>
                                                        <button id="refreshStoreWhsBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneStoresWhsForm(<?php echo $sbmtdStoreWhsID; ?>, 'ReloadDialog', 'FROMBRNCH', <?php echo $lnkdSiteID; ?>);" data-toggle="tooltip" data-placement="bottom" title = "Reload Store Cage/Shelf">
                                                            <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Refresh
                                                        </button>
                                                    </div>                                            
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="oneStoreUsersTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:50px;width:50px;">No.</th>
                                                                    <th >User Name</th>
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
                                                                $rslt = get_OneStoreUsers($sbmtdStoreWhsID);
                                                                $cntrUsr = 0;
                                                                while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                    $cntrUsr++;
                                                                    $usName = $rwCage[2];
                                                                    $usStartDte = $rwCage[3];
                                                                    $usEndDte = $rwCage[4];
                                                                    ?>
                                                                    <tr id="oneStoreUsersRow_<?php echo $cntrUsr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canEdt === true) { ?>
                                                                                <div class="form-group form-group-sm col-md-12">
                                                                                    <div class="input-group"  style="width:100%;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneStoreUsersRow<?php echo $cntrUsr; ?>_UsrNm" value="<?php echo $usName; ?>">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneStoreUsersRow<?php echo $cntrUsr; ?>_UsrID" value="<?php echo $rwCage[1]; ?>">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneStoreUsersRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $rwCage[5]; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '<?php echo $rwCage[1]; ?>', 'oneStoreUsersRow<?php echo $cntrUsr; ?>_UsrID', 'oneStoreUsersRow<?php echo $cntrUsr; ?>_UsrNm', 'clear', 1, '');">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $usName; ?></span>
                                                                            <?php } ?> 
                                                                        </td>
                                                                        <td class="lovtd">                                                                            
                                                                            <?php if ($canEdt
                                                                                    === true) {
                                                                                ?>
                                                                                <div class="form-group form-group-sm col-md-12">
                                                                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                        <input class="form-control" size="16" type="text" id="oneStoreUsersRow<?php echo $cntrUsr; ?>_StrtDte" value="<?php echo $usStartDte; ?>" readonly="true">
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                    </div>                                                                
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $usStartDte; ?></span>
                        <?php } ?> 
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canEdt
                                                                                    === true) {
                                                                                ?>
                                                                                <div class="form-group form-group-sm col-md-12">
                                                                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                        <input class="form-control" size="16" type="text" id="oneStoreUsersRow<?php echo $cntrUsr; ?>_EndDte" value="<?php echo $usEndDte; ?>" readonly="true">
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                    </div>                                                                
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $usEndDte; ?></span>
                                                                        <?php } ?>  
                                                                        </td>
                        <?php if ($canEdt === true) { ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delINVStoreWhsUsrs('oneStoreUsersRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete User">
                                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                            </td>
                        <?php } ?>
                                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                        echo urlencode(encrypt1(($rwCage[5] . "|inv.inv_user_subinventories|line_id"),
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
                            <?php } ?>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdt === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveStoresWhsForm(<?php echo $sbmtdSiteID; ?>);">Save Changes</button>
                <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 2) {
                //New Cage Form
                $canEdtCage = test_prmssns($dfltPrvldgs[20], $mdlNm);
                $sbmtdCageID = isset($_POST['sbmtdCageID']) ? cleanInputData($_POST['sbmtdCageID']) : -1;
                $cageStoreID = isset($_POST['cageStoreID']) ? (float) cleanInputData($_POST['cageStoreID']) : -1;
                $cageLineID = $sbmtdCageID;
                $cageShelfNm = "";
                $cageShelfID = -1;
                $cageStoreNm = "";
                $cageDesc = "";
                $cageOwnersCstmrID = -1;
                $cageOwnersCstmrNm = "";
                $lnkdGLAccountNm = "";
                $lnkdGLAccountID = -1;
                $grpType = "";
                $allwdGroupName = "";
                $allwdGroupID = -1;
                $cageMngrsPrsnID = -1;
                $cageMngrsName = "";
                $mngrsWithdrawlLmt = 0;
                $mngrsDepositLmt = 0;
                $dfltItemType = "";
                $dfltItemState = "";
                $isEnbld = "No";
                if ($sbmtdCageID > 0) {
                    $result = get_OneCageDet($sbmtdCageID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdCageID = (float) $row[0];
                        $cageShelfID = (float) $row[4];
                        $cageLineID = $sbmtdCageID;
                        $cageShelfNm = $row[1];
                        $cageStoreID = (float) $row[13];
                        $cageStoreNm = $row[14];
                        $cageDesc = $row[2];
                        $cageOwnersCstmrID = (float) $row[5];
                        $cageOwnersCstmrNm = $row[6];
                        $lnkdGLAccountNm = $row[10];
                        $lnkdGLAccountID = (float) $row[9];
                        $grpType = $row[7];
                        $allwdGroupName = $row[21];
                        $allwdGroupID = (float) $row[8];
                        $cageMngrsPrsnID = (float) $row[11];
                        $cageMngrsName = $row[12];
                        $mngrsWithdrawlLmt = (float) $row[22];
                        $mngrsDepositLmt = (float) $row[23];
                        $dfltItemType = $row[24];
                        $dfltItemState = $row[25];
                        $isEnbld = $row[3];
                    }
                } else if ($cageStoreID > 0) {
                    $cageStoreNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $cageStoreID);
                }
                ?>
                <form class="form-horizontal" id='mcfTillStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageShelfNm" class="control-label">Store Cage Name:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="cageShelfNm" id="cageShelfNm" class="form-control rqrdFld" value="<?php echo $cageShelfNm; ?>" style="width:100% !important;">
                                                    <input type="hidden" name="cageLineID" id="cageLineID" class="form-control" value="<?php echo $cageLineID; ?>">
                                                    <input type="hidden" name="cageShelfID" id="cageLineID" class="form-control" value="<?php echo $cageShelfID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Shelves', '', '', '', 'radio', true, '', 'cageShelfNm', 'cageDesc', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cageShelfNm; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageDesc" class="control-label">Description:</label>
                                        </div>
                                        <div class="col-md-8">     
                                            <?php if ($canEdtCage === true) { ?>
                                                <textarea rows="2" name="cageDesc" id="cageDesc" class="form-control rqrdFld"><?php echo $cageDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $cageDesc; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageStoreNm" class="control-label">Linked Store Name:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="cageStoreNm" id="cageStoreNm" class="form-control rqrdFld" value="<?php echo $cageStoreNm; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="cageStoreID" id="cageStoreID" class="form-control" value="<?php echo $cageStoreID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vaults', '', '', '', 'radio', true, '', 'cageStoreID', 'cageStoreNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cageStoreNm; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageOwnersCstmrNm" class="control-label">Client Owner:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="cageOwnersCstmrNm" id="cageOwnersCstmrNm" class="form-control" value="<?php echo $cageOwnersCstmrNm; ?>" readonly="true">
                                                    <input type="hidden" name="cageOwnersCstmrID" id="cageOwnersCstmrID" class="form-control" value="<?php echo $cageOwnersCstmrID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'cageOwnersCstmrID', 'cageOwnersCstmrNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cageOwnersCstmrNm; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="lnkdGLAccountNm" class="control-label">Linked GL Account:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="lnkdCgGLAccountNm" id="lnkdCgGLAccountNm" class="form-control rqrdFld" value="<?php echo $lnkdGLAccountNm; ?>" readonly="true">
                                                    <input type="hidden" name="lnkdCgGLAccountID" id="lnkdCgGLAccountID" class="form-control" value="<?php echo $lnkdGLAccountID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'lnkdCgGLAccountID', 'lnkdCgGLAccountNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $lnkdGLAccountNm; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label for="isCageEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtCage === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isCageEnbld" id="isCageEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="grpType" class="control-label">Allowed Group Type:</label>
                                        </div>
                                        <div class="col-md-8">
                                                <?php if ($canEdtCage === true) { ?>
                                                <select class="form-control" id="grpType" onchange="grpTypMcfChangeV();">                                                        
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                                    $valuesArrys = array("Everyone", "Divisions/Groups",
                                                        "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($grpType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $grpType; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="allwdGroupNm" class="control-label">Allowed Group Name:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtCage === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="allwdGroupName" value="<?php echo $allwdGroupName; ?>" readonly="">
                                                    <input type="hidden" id="allwdGroupID" value="<?php echo $allwdGroupID; ?>">
                                                    <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'allwdGroupID', 'allwdGroupName', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $allwdGroupName; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageMngrsName" class="control-label">Cage/Till Manager's Name:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="cageMngrsName" id="cageMngrsName" class="form-control" value="<?php echo $cageMngrsName; ?>" readonly="true">
                                                    <input type="hidden" name="cageMngrsPrsnID" id="cageMngrsPrsnID" class="form-control" value="<?php echo $cageMngrsPrsnID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'cageMngrsPrsnID', 'cageMngrsName', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div> 
                                            <?php } else { ?>
                                                <span><?php echo $cageMngrsName; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="mngrsWithdrawlLmt" class="control-label">Manager's Withdrawal Limit:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                   
                                                <input type="text" name="mngrsWithdrawlLmt" id="mngrsWithdrawlLmt" class="form-control" value="<?php
                                                       echo number_format($mngrsWithdrawlLmt, 2);
                                                       ?>">                                                        
                                            <?php } else { ?>
                                                <span><?php echo number_format($mngrsWithdrawlLmt, 2); ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="mngrsDepositLmt" class="control-label">Manager's Deposit Limit:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                 
                                                <input type="text" name="mngrsDepositLmt" id="mngrsDepositLmt" class="form-control" value="<?php
                                                       echo number_format($mngrsDepositLmt, 2);
                                                       ?>">                                                        
                                            <?php } else { ?>
                                                <span><?php echo number_format($mngrsDepositLmt, 2); ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="dfltItemType" class="control-label">Default Item Type:</label>
                                        </div>
                                        <div class="col-md-8">
                                                <?php if ($canEdtCage === true) { ?>
                                                <select class="form-control rqrdFld" id="dfltItemType">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "");
                                                    $valuesArrys = array("Merchandise Inventory", "Non-Merchandise Inventory",
                                                        "Fixed Assets", "Expense Item", "Services", "VaultItem-Cash", "VaultItem-NonCash");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($dfltItemType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $dfltItemType; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="dfltItemState" class="control-label">Default Item State:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="dfltItemState" id="dfltItemState" class="form-control rqrdFld" value="<?php echo $dfltItemState; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', 'dfltItemState', '', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div> 
                                            <?php } else { ?>
                                                <span><?php echo $dfltItemState; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>                               
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtCage === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveINVCgFormV();">Save Changes</button>
                <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            }
        }
    }
}
?>