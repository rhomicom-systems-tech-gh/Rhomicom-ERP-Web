<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$curIdx = 0;
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$canAddItmSet = test_prmssns($dfltPrvldgs[11], $mdlNm);
$canEdtItmSet = test_prmssns($dfltPrvldgs[12], $mdlNm);
$canDelItmSet = test_prmssns($dfltPrvldgs[13], $mdlNm);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                if ($canDelItmSet === FALSE) {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Deletion Failed! Permission Denied!</span>
                        </div>
                    </div>
                    <?php
                    exit();
                }
                $pKeyID = isset($_POST['prsSetID']) ? cleanInputData($_POST['prsSetID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : -1;
                if (isItmStInUse($pKeyID) == true) {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Deletion Failed! <br/>This Item Set has either been assigned to a Mass Pay or a Survey hence cannot be DELETED!</span>
                        </div>
                    </div>
                    <?php
                    exit();
                } else {
                    echo deleteItemSet($pKeyID, $prsnSetNm);
                }
            } else if ($actyp == 2) {
                //"Removing Set Persons...";  
                if ($canDelItmSet === FALSE) {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Deletion Failed! Permission Denied!</span>
                        </div>
                    </div>
                    <?php
                    exit();
                }
                $pItemName = isset($_POST['pItemName']) ? cleanInputData($_POST['pItemName']) : -1;
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                echo deleteItemSetDet($pKeyID, $pItemName);
            } else if ($actyp == 3) {
                if ($canDelItmSet === FALSE) {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Deletion Failed! Permission Denied!</span>
                        </div>
                    </div>
                    <?php
                    exit();
                }
                $pKeyID = isset($_POST['payRoleID']) ? cleanInputData($_POST['payRoleID']) : -1;
                //$rolNm = getRol
                echo deleteItemSetRole($pKeyID, "");
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                $itemSetID = isset($_POST['itemSetID']) ? (float) cleanInputData($_POST['itemSetID']) : '';
                $itemSetNm = isset($_POST['itemSetNm']) ? cleanInputData($_POST['itemSetNm']) : '';
                $itemSetDesc = isset($_POST['itemSetDesc']) ? cleanInputData($_POST['itemSetDesc']) : '';
                $itemSetUsesSQL = isset($_POST['itemSetUsesSQL']) ? cleanInputData($_POST['itemSetUsesSQL']) : 'NO';
                $itemSetEnbld = isset($_POST['itemSetEnbld']) ? cleanInputData($_POST['itemSetEnbld']) : 'NO';
                $itemSetIsDflt = isset($_POST['itemSetIsDflt']) ? cleanInputData($_POST['itemSetIsDflt']) : 'NO';
                $itemSetSQL = isset($_POST['itemSetSQL']) ? cleanInputData($_POST['itemSetSQL']) : '';
                $slctdItemSetRoles = isset($_POST['slctdItemSetRoles']) ? cleanInputData($_POST['slctdItemSetRoles']) : '';
                $oldItemSetID = getItmStID($itemSetNm, $orgID);
                $itemSetUsesSQLBool = $itemSetUsesSQL == "NO" ? FALSE : TRUE;
                $itemSetEnbldBool = $itemSetEnbld == "NO" ? FALSE : TRUE;
                $itemSetIsDfltBool = $itemSetIsDflt == "NO" ? FALSE : TRUE;
                if ($itemSetUsesSQLBool == FALSE) {
                    $itemSetSQL = "";
                }

                $errMsg = "";
                if (($itemSetUsesSQLBool == TRUE && $itemSetSQL != "")) {
                    if (isItemSetSQLValid($itemSetSQL, $errMsg) === FALSE) {
                        ?>
                        <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                            <div class="row" style="float:none;width:100%;text-align: center;">
                                <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to save Item Set!<br/><?php echo $errMsg; ?></span>
                            </div>
                        </div>
                        <?php
                        exit();
                    }
                }
                if ($itemSetNm != "" &&
                        ($oldItemSetID <= 0 || $oldItemSetID == $itemSetID) && (($itemSetUsesSQLBool == TRUE && $itemSetSQL != "") || ($itemSetUsesSQLBool == FALSE))) {
                    if ($itemSetID <= 0) {
                        createItmSt($orgID, $itemSetNm, $itemSetDesc, $itemSetEnbldBool, $itemSetIsDfltBool, $itemSetUsesSQLBool, $itemSetSQL);
                        $itemSetID = getItmStID($itemSetNm, $orgID);
                    } else {
                        updateItmSt($itemSetID, $itemSetNm, $itemSetDesc, $itemSetEnbldBool, $itemSetIsDfltBool, $itemSetUsesSQLBool, $itemSetSQL);
                    }
                    //Save Role Sets
                    $variousRows = explode("|", trim($slctdItemSetRoles, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 3) {
                            $payRoleID = cleanInputData1($crntRow[0]);
                            $inptRoleNm = cleanInputData1($crntRow[1]);
                            $inptRoleID = cleanInputData1($crntRow[2]);
                            if (doesItmSetHvRole($itemSetID, $inptRoleID) <= 0) {
                                createPayRole($itemSetID, -1, $inptRoleID);
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Item Set Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to save Item Set!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 2) {
                //"Saving Set Items...";
                $sbmtdItemSetHdrID = isset($_POST['sbmtdItemSetHdrID']) ? cleanInputData($_POST['sbmtdItemSetHdrID']) : '';
                $slctdItemSetItms = isset($_POST['slctdItemSetItms']) ? cleanInputData($_POST['slctdItemSetItms']) : '';
                $affctd = 0;
                if (trim($slctdItemSetItms, "|~") != "" && $sbmtdItemSetHdrID > 0) {
                    //Save Persons
                    $variousRows = explode("|", trim($slctdItemSetItms, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $inptItmID = (int) cleanInputData1($crntRow[0]);
                            $inptItmNm = cleanInputData1($crntRow[1]);
                            if (doesItmStHvItm($sbmtdItemSetHdrID, $inptItmID) === FALSE) {
                                $affctd += createItemSetDet($sbmtdItemSetHdrID, $inptItmID);
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $affctd; ?> Item(s) Added Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Add Item(s)!</span>
                        </div>
                    </div>
                    <?php
                }
            }
        } else {
            $prsnid = $_SESSION['PRSN_ID'];
            if ($vwtyp == 0) {
                $sbmtdItemSetHdrID = isset($_POST['sbmtdItemSetHdrID']) ? $_POST['sbmtdItemSetHdrID'] : -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Pay Item Sets</span>
				</li>
                               </ul>
                              </div>";
                $total = get_Total_ItmSt($srchFor, $srchIn, $orgID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Basic_ItmSt($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='allItemSetsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php if ($canAddItmSet === true) { ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneItemSetForm(-1, 2);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Pay Item Set
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-4";
                            $colClassType2 = "col-lg-4";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allItemSetsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllItemSets(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allItemSetsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllItemSets('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllItemSets('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allItemSetsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Item Set Name", "Item Set Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allItemSetsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllItemSets('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllItemSets('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>   
                </div>
                <div class="row" style="padding:0px 15px 0px 15px !important"> 
                    <div class="col-md-5" style="padding:0px 1px 0px 1px !important">
                        <fieldset class="basic_person_fs">                                        
                            <table class="table table-striped table-bordered table-responsive" id="allItemSetsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Item Set Name</th>   
                                        <th style="text-align:center;">Enabled?</th>   
                                        <th style="text-align:center;">Is Default?</th> 
                                        <th>&nbsp;</th>                                       
                                        <?php if ($canDelItmSet === true) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usesSQL = "0";
                                    while ($row = loc_db_fetch_array($result)) {
                                        if ($sbmtdItemSetHdrID <= 0 && $cntr <= 0) {
                                            $sbmtdItemSetHdrID = $row[0];
                                            $usesSQL = $row[5];
                                        }
                                        $cntr += 1;
                                        ?>
                                        <tr id="allItemSetsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[1]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allItemSetsRow<?php echo $cntr; ?>_ItemSetID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd" style="text-align:center;">
                                                <?php
                                                $isChkd = "";
                                                $isRdOnly = "disabled=\"true\"";
                                                if ($row[3] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>   
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allItemSetsRow<?php echo $cntr; ?>_IsEnbld" name="allItemSetsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd" style="text-align:center;">
                                                <?php
                                                $isChkd = "";
                                                $isRdOnly = "disabled=\"true\"";
                                                if ($row[4] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>   
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allItemSetsRow<?php echo $cntr; ?>_IsDflt" name="allItemSetsRow<?php echo $cntr; ?>_IsDflt" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneItemSetForm(<?php echo $row[0]; ?>, 2);" data-toggle="tooltip" data-placement="bottom" title="View Details">
                                                    <img src="cmn_images/kghostview.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDelItmSet === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delItemSet('allItemSetsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item Set">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                    <div  class="col-md-7" style="padding:0px 1px 0px 1px !important">
                        <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                            <div class="" id="allItemSetsDetailInfo">
                                <?php
                                $srchFor = "%";
                                $srchIn = "Name";
                                $pageNo = 1;
                                $lmtSze = 10;
                                $vwtyp = 1;
                                if ($sbmtdItemSetHdrID > 0) {
                                    $result2 = get_AllItmStDet($sbmtdItemSetHdrID);
                                    ?>
                                    <div class="row">
                                        <?php
                                        if ($canEdtItmSet === true && $usesSQL != "1") {
                                            $colClassType1 = "col-lg-2";
                                            $colClassType2 = "col-lg-3";
                                            $colClassType3 = "col-lg-4";
                                            $nwRowHtml = urlencode("<tr id=\"itemSetItmsRow__WWW123WWW\">"
                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                    . "<td class=\"lovtd\">
                                                                    <div class=\"input-group\" style=\"width:100% !important;\">
                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"itemSetItmsRow_WWW123WWW_ItemName\" name=\"itemSetItmsRow_WWW123WWW_ItemName\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Pay Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'itemSetItmsRow_WWW123WWW_ItemID', 'itemSetItmsRow_WWW123WWW_ItemName', 'clear', 1, '');\">
                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                        </label>
                                                                    </div>
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"itemSetItmsRow_WWW123WWW_ItemID\" value=\"-1\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"itemSetItmsRow_WWW123WWW_PKeyID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                            </td>                                             
                                                            <td class=\"lovtd\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"itemSetItmsRow_WWW123WWW_ItemMinType\" name=\"itemSetItmsRow_WWW123WWW_ItemMinType\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">                                                               
                                                            </td>
                                                                <td class=\"lovtd\">
                                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delItemSetItm('itemSetItmsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Pay Item\">
                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                    </button>
                                                                </td>
                                        </tr>");
                                            ?> 
                                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">     
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('itemSetItmsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Pay Item">
                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveItemSetItms();" data-toggle="tooltip" data-placement="bottom" title="Save Items">
                                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                        <div class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                            <input type="hidden" id="sbmtdItemSetHdrID" value="<?php echo $sbmtdItemSetHdrID; ?>"/>
                                            <table class="table table-striped table-bordered table-responsive" id="itemSetItmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Item Code/Name</th>
                                                        <th>Item Name</th>
                                                        <?php if ($usesSQL != "1") { ?>
                                                            <th>&nbsp;</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $cntr = 0;
                                                    while ($row2 = loc_db_fetch_array($result2)) {
                                                        $cntr += 1;
                                                        ?>
                                                        <tr id="itemSetItmsRow_<?php echo $cntr; ?>">                                    
                                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                            <td class="lovtd">
                                                                <?php if ($canEdtItmSet === true && $usesSQL != "1") { ?>
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" class="form-control" aria-label="..." id="itemSetItmsRow<?php echo $cntr; ?>_ItemName" name="itemSetItmsRow<?php echo $cntr; ?>_ItemName" value="<?php echo $row2[1]; ?>" readonly="true" style="width:100% !important;">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Pay Items', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $row2[1]; ?>', 'itemSetItmsRow<?php echo $cntr; ?>_ItemID', 'itemSetItmsRow<?php echo $cntr; ?>_ItemName', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row2[1]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <input type="hidden" class="form-control" aria-label="..." id="itemSetItmsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                <input type="hidden" class="form-control" aria-label="..." id="itemSetItmsRow<?php echo $cntr; ?>_PKeyID" value="<?php echo $row2[6]; ?>" style="width:100% !important;">                                             
                                                            </td>                                             
                                                            <td class="lovtd">  
                                                                <?php if ($canEdtItmSet === true && $usesSQL != "1") { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="itemSetItmsRow<?php echo $cntr; ?>_ItemMinType" name="itemSetItmsRow<?php echo $cntr; ?>_ItemMinType" value="<?php echo $row2[5]; ?>" readonly="true" style="width:100% !important;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row2[2]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </td>
                                                            <?php if ($usesSQL != "1") { ?>
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delItemSetItm('itemSetItmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item from Item Set">
                                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                $sbmtdItemSetHdrID = isset($_POST['sbmtdItemSetHdrID']) ? $_POST['sbmtdItemSetHdrID'] : -1;
                if ($sbmtdItemSetHdrID > 0) {
                    $usesSQL = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "uses_sql", $sbmtdItemSetHdrID);
                    $result2 = get_AllItmStDet($sbmtdItemSetHdrID);
                    ?>
                    <div class="row">
                        <?php
                        if ($canEdtItmSet === true && $usesSQL != "1") {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-4";
                            $nwRowHtml = urlencode("<tr id=\"itemSetItmsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                    . "<td class=\"lovtd\">
                                                                    <div class=\"input-group\" style=\"width:100% !important;\">
                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"itemSetItmsRow_WWW123WWW_ItemName\" name=\"itemSetItmsRow_WWW123WWW_ItemName\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Pay Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'itemSetItmsRow_WWW123WWW_ItemID', 'itemSetItmsRow_WWW123WWW_ItemName', 'clear', 1, '');\">
                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                        </label>
                                                                    </div>
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"itemSetItmsRow_WWW123WWW_ItemID\" value=\"-1\" style=\"width:100% !important;\">   
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"itemSetItmsRow_WWW123WWW_PKeyID\" value=\"-1\" style=\"width:100% !important;\">                                           
                                                            </td>                                             
                                                            <td class=\"lovtd\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"itemSetItmsRow_WWW123WWW_ItemMinType\" name=\"itemSetItmsRow_WWW123WWW_ItemMinType\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">                                                               
                                                            </td>
                                                                <td class=\"lovtd\">
                                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delItemSetItm('itemSetItmsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Pay Item\">
                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                    </button>
                                                                </td>
                                        </tr>");
                            ?> 
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">     
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('itemSetItmsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Pay Item">
                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveItemSetItms();" data-toggle="tooltip" data-placement="bottom" title="Save Items">
                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">                  
                        <div class="col-md-12" style="padding:0px 1px 0px 1px !important">
                            <input type="hidden" id="sbmtdItemSetHdrID" value="<?php echo $sbmtdItemSetHdrID; ?>"/>
                            <table class="table table-striped table-bordered table-responsive" id="itemSetItmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Item Code/Name</th>
                                        <th>Item Name</th>
                                        <?php if ($usesSQL != "1") { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cntr = 0;
                                    while ($row2 = loc_db_fetch_array($result2)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="itemSetItmsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                            <td class="lovtd">
                                                <?php if ($canEdtItmSet === true && $usesSQL != "1") { ?>
                                                    <div class="input-group" style="width:100% !important;">
                                                        <input type="text" class="form-control" aria-label="..." id="itemSetItmsRow<?php echo $cntr; ?>_ItemName" name="itemSetItmsRow<?php echo $cntr; ?>_ItemName" value="<?php echo $row2[1]; ?>" readonly="true" style="width:100% !important;">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Pay Items', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $row2[1]; ?>', 'itemSetItmsRow<?php echo $cntr; ?>_ItemID', 'itemSetItmsRow<?php echo $cntr; ?>_ItemName', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                <?php } else {
                                                    ?>
                                                    <span><?php echo $row2[1]; ?></span>
                                                    <?php
                                                }
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="itemSetItmsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
                                                <input type="hidden" class="form-control" aria-label="..." id="itemSetItmsRow<?php echo $cntr; ?>_PKeyID" value="<?php echo $row2[6]; ?>" style="width:100% !important;">                                                
                                            </td>                                             
                                            <td class="lovtd">  
                                                <?php if ($canEdtItmSet === true && $usesSQL != "1") { ?>
                                                    <input type="text" class="form-control" aria-label="..." id="itemSetItmsRow<?php echo $cntr; ?>_ItemMinType" name="itemSetItmsRow<?php echo $cntr; ?>_ItemMinType" value="<?php echo $row2[5]; ?>" readonly="true" style="width:100% !important;">
                                                <?php } else {
                                                    ?>
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <?php if ($usesSQL != "1") { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delItemSetItm('itemSetItmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item from Item Set">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            } else if ($vwtyp == 2) {
                //Item Set Detail                
                $sbmtdItemSetHdrID = isset($_POST['sbmtdItemSetHdrID']) ? $_POST['sbmtdItemSetHdrID'] : -1;
                $itemSetNm = "";
                $itemSetDesc = "";
                $itemSetUsesSQL = "0";
                $itemSetEnbld = "0";
                $itemSetIsDflt = "0";
                $itemSetSQL = "";
                $result = get_ItemSetDetail($sbmtdItemSetHdrID);
                while ($row = loc_db_fetch_array($result)) {
                    $itemSetNm = $row[1];
                    $itemSetDesc = $row[2];
                    $itemSetUsesSQL = $row[5];
                    $itemSetEnbld = $row[3];
                    $itemSetIsDflt = $row[4];
                    $itemSetSQL = $row[6];
                }
                ?>
                <form class="form-horizontal" id='itemSetDetailsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-md-4">
                                <label for="itemSetNm" class="control-label">Item Set Name:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="itemSetNm" id="itemSetNm" class="form-control" value="<?php echo $itemSetNm; ?>">
                                <input type="text" name="itemSetID" id="itemSetID" class="form-control" value="<?php echo $sbmtdItemSetHdrID; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-md-4">
                                <label for="itemSetDesc" class="control-label">Item Set Description:</label>
                            </div>
                            <div class="col-md-8">     
                                <textarea rows="2" name="itemSetDesc" id="itemSetDesc" class="form-control"><?php echo $itemSetDesc; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="itemSetUsesSQL" class="control-label">
                                        <?php
                                        $isChkd = "";
                                        $isRdOnly = "";
                                        if ($itemSetUsesSQL == "1") {
                                            $isChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" name="itemSetUsesSQL" id="itemSetUsesSQL" <?php echo $isChkd . " " . $isRdOnly; ?>>Uses SQL</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="itemSetEnbld" class="control-label">
                                        <?php
                                        $isChkd = "";
                                        $isRdOnly = "";
                                        if ($itemSetEnbld == "1") {
                                            $isChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" name="itemSetEnbld" id="itemSetEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="itemSetIsDflt" class="control-label">
                                        <?php
                                        $isChkd = "";
                                        $isRdOnly = "";
                                        if ($itemSetIsDflt == "1") {
                                            $isChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" name="itemSetIsDflt"  id="itemSetIsDflt" <?php echo $isChkd . " " . $isRdOnly; ?>>Default Item Set?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">                                
                            <div class="col-md-12" style="margin-bottom:5px;">
                                <label for="itemSetSQL" class="control-label">Item Set SQL Query: </label>
                            </div>
                            <div class="col-md-12" style="margin-bottom:5px;">
                                <textarea rows="10" name="itemSetSQL" id="itemSetSQL" class="form-control"><?php echo $itemSetSQL; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-md-12">
                                <?php
                                if ($canEdtItmSet === true) {
                                    $nwRowHtml = urlencode("<tr id=\"itemSetAlwdRlsRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                            . "<td class=\"lovtd\">
                                                                <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"itemSetAlwdRlsRow_WWW123WWW_RoleNm\" name=\"itemSetAlwdRlsRow_WWW123WWW_RoleNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"itemSetAlwdRlsRow_WWW123WWW_RoleID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'itemSetAlwdRlsRow_WWW123WWW_RoleID', 'itemSetAlwdRlsRow_WWW123WWW_RoleNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"itemSetAlwdRlsRow_WWW123WWW_PayRoleID\" value=\"-1\" style=\"width:100% !important;\">                                                             
                                                            </td>  
                                                            <td class=\"lovtd\">
                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delItmSetRoleSet('itemSetAlwdRlsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Role Set\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                </button>
                                                            </td>
                                        </tr>");
                                    ?> 
                                    <div class="" style="float:right !important;padding-right: 1px;">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('itemSetAllwdRolesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Role">
                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add Role Set
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveItemSetForm();" data-toggle="tooltip" data-placement="bottom" title = "Saves Item Set">
                                            <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Save Item Set
                                        </button>
                                    </div>
                                <?php } else { ?>                                        
                                    <label class="control-label">Permitted / Allowed Role Sets:</label>
                                <?php } ?>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="itemSetAllwdRolesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Role Set Name</th>
                                            <?php if ($canEdtItmSet === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        $result2 = get_AllRoles($sbmtdItemSetHdrID);
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="itemSetAlwdRlsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <span><?php echo $row2[1]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="itemSetAlwdRlsRow<?php echo $cntr; ?>_PayRoleID" value="<?php echo $row2[2]; ?>" style="width:100% !important;">                                              
                                                </td>     
                                                <?php if ($canEdtItmSet === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delItmSetRoleSet('itemSetAlwdRlsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Role Set">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                </form>                    
                <?php
            }
        }
    }
}    
